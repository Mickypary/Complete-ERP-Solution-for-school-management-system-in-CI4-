<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\ApplicationModel;

/**
 * 
 */
class TimetableModel extends Model
{
	public $application_model;
	
	function __construct()
	{
		parent::__construct();
		$this->application_model = new ApplicationModel();
	}

	// class wise information save
    public function classwise_save($data, $mode)
    {
        $branchID   = $this->application_model->get_branch_id();
        $sectionID  = $data['section_id'];
        $classID    = $data['class_id'];
        $sessionID  = get_session_id();
        $day        = $data['day'];

        $arrayItems = $data['timetable'];
        foreach ($arrayItems as $key => $value) {
            if (!isset($value['break'])) {
                $subjectID  = $value['subject'];
                $teacherID  = $value['teacher'];
                $break      = false;
            } else {
                $subjectID  = 0;
                $teacherID  = 0;
                $break      = true;
            }
            $timeStart = date("H:i:s", strtotime($value['time_start']));
            $timeEnd = date("H:i:s", strtotime($value['time_end']));
            $roomNumber = $value['class_room'];
            if (!empty($timeStart) && !empty($timeEnd)) {
                $arrayRoutine = array(
                    'class_id'      => $classID,
                    'section_id'    => $sectionID,
                    'subject_id'    => $subjectID,
                    'teacher_id'    => $teacherID,
                    'time_start'    => $timeStart,
                    'time_end'      => $timeEnd,
                    'class_room'    => $roomNumber,
                    'session_id'    => $sessionID,
                    'branch_id'     => $branchID,
                    'break'         => $break,
                    'day'           => $day,
                );
                $builder = $this->db->table('timetable_class');
                if ($mode == 'new') {
                    $builder->insert($arrayRoutine);
                } elseif ($mode == 'update') {
                    $builder->where('id', $data['i'][$key]);
                    $builder->update($arrayRoutine);
                }
            }
        }
        if ($mode == 'update') {
            $arrayI = $data['i'];
            $builder = $this->db->table('timetable_class')->whereNotIn('id', $arrayI);
            $builder->where('class_id', $classID);
            $builder->where('section_id', $sectionID);
            $builder->where('day', $day);
            $builder->where('session_id', $sessionID);
            $builder->where('branch_id', $branchID);
            $builder->delete();
        }
    }

    public function getExamTimetableList($classID, $sectionID, $branchID)
    {
        $builder = $this->db->table('timetable_exam as t')->select('t.*,b.name as branch_name')
        ->join('branch as b', 'b.id = t.branch_id', 'left')
        ->where('t.branch_id', $branchID)
        ->where('t.class_id', $classID)
        ->where('t.section_id', $sectionID)
        ->where('t.session_id', get_session_id())
        ->orderBy('t.id', 'asc')
        ->groupBy('t.exam_id');
        return $builder->get()->getResultArray();
    }

    public function getSubjectExam($classID, $sectionID, $examID, $branchID)
    {
        $sql = "SELECT sa.*, s.name as subject_name, te.time_start, te.time_end, te.hall_id, te.exam_date, te.mark_distribution FROM subject_assign as sa
        LEFT JOIN subject as s ON s.id = sa.subject_id LEFT JOIN timetable_exam as te ON te.class_id = sa.class_id and te.section_id = sa.section_id and
        te.subject_id = sa.subject_id and te.session_id = sa.session_id and te.exam_id = " . $this->db->escape($examID) . " WHERE sa.class_id = " .
        $this->db->escape($classID) . " AND sa.section_id = " . $this->db->escape($sectionID) . " AND sa.branch_id = " .
        $this->db->escape($branchID) . " AND sa.session_id = " . $this->db->escape(get_session_id());
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    public function getExamTimetableByModal($examID, $classID, $sectionID)
    {
        $builder = $this->db->table('timetable_exam as t')->select('t.*,s.name as subject_name,eh.hall_no')
        ->join('subject as s', 's.id = t.subject_id', 'left')
        ->join('exam_hall as eh', 'eh.id = t.hall_id', 'left');
        if (!is_superadmin_loggedin()) {
            $builder->where('t.branch_id', get_loggedin_branch_id());
        }
        $builder->where('t.exam_id', $examID);
        $builder->where('t.class_id', $classID);
        $builder->where('t.section_id', $sectionID);
        $builder->where('t.session_id', get_session_id());
        return $builder->get();
    }




} /*End Class */