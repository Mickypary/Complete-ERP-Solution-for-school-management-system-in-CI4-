<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\ApplicationModel;

/**
 * 
 */
class ExamModel extends Model
{
	public $db;
	public $application_model;
	
	function __construct()
	{
		parent::__construct();
		$this->db = \Config\Database::connect();
		$this->application_model = new ApplicationModel();
	}


	public function getExamList()
    {
        $builder = $this->db->table('exam as e')->select('e.*,b.name as branch_name');
        $builder->join('branch as b', 'b.id = e.branch_id', 'left');
        if (!is_superadmin_loggedin()) {
            $builder->where('e.branch_id', get_loggedin_branch_id());
        }
        $builder->where('e.session_id', get_session_id());
        $builder->orderBy('e.id', 'asc');
        return $builder->get()->getResultArray();
    }

    // Store exam mark distribution inside exam table
    public function exam_save($data)
    {
    	$builder = $this->db->table('exam');
        $arrayExam = array(
            'name' => $data['name'],
            'branch_id' => $this->application_model->get_branch_id(),
            'term_id' => $data['term_id'],
            'type_id' => $data['type_id'],
            'mark_distribution' => json_encode($data['mark_distribution']),
            'remark' => $data['remark'],
            'session_id' => get_session_id(),
        );
        if (!isset($data['exam_id'])) {
            $builder->insert($arrayExam);
        } else {
            $builder->where('id', $data['exam_id']);
            $builder->update($arrayExam);
        }
    }


    public function termSave($post)
    {
    	$builder = $this->db->table('exam_term');
        $arrayTerm = array(
            'name' => $post['term_name'],
            'branch_id' => $this->application_model->get_branch_id(),
            'session_id' => get_session_id(),
        );
        if (!isset($post['term_id'])) {
            $builder->insert($arrayTerm);
        } else {
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->where('id', $post['term_id']);
            $builder->update($arrayTerm);
        }
    }


    public function hallSave($post)
    {
    	$builder = $this->db->table('exam_hall');
        $arrayHall = array(
            'hall_no' => $post['hall_no'],
            'seats' => $post['no_of_seats'],
            'branch_id' => $this->application_model->get_branch_id(),
        );
        if (!isset($post['hall_id'])) {
            $builder->insert($arrayHall);
        } else {
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->where('id', $post['hall_id']);
            $builder->update($arrayHall);
        }
    }


    public function gradeSave($data)
    {
    	$builder = $this->db->table('grade');
        $arrayData = array(
            'branch_id' => $this->application_model->get_branch_id(),
            'name' => $data['name'],
            'grade_point' => $data['grade_point'],
            'lower_mark' => $data['lower_mark'],
            'upper_mark' => $data['upper_mark'],
            'remark' => $data['remark'],
        );
        // posted all data XSS filtering
        if (!isset($data['grade_id'])) {
            $builder->insert($arrayData);
        } else {
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->where('id', $data['grade_id']);
            $builder->update($arrayData);
        }
    }


    public function get_grade($mark, $branch_id)
    {
        $builder = $this->db->table('grade')->where('branch_id', $branch_id);
        $query = $builder->get();
        $grades = $query->getResultArray();
        foreach ($grades as $row) {
            if ($mark >= $row['lower_mark'] && $mark <= $row['upper_mark']) {
                return $row;
            }
        }
    }


    public function getSubjectList($examID, $classID, $sectionID, $sessionID)
    {
        $branchID = $this->application_model->get_branch_id();
        $builder = $this->db->table('timetable_exam as t')->select('t.*,s.name as subject_name')
        ->join('subject as s', 's.id = t.subject_id', 'left')
        ->where('t.exam_id', $examID)
        ->where('t.class_id', $classID)
        ->where('t.section_id', $sectionID)
        ->where('t.session_id', $sessionID)
        ->where('t.branch_id', $branchID);
        return $builder->get();
        // return $builder->getResult();
    }


    public function getTimetableDetail($classID, $sectionID, $examID, $subjectID)
    {
        $builder = $this->db->table('timetable_exam')->select('timetable_exam.mark_distribution')
        ->where('class_id', $classID)
        ->where('section_id', $sectionID)
        ->where('exam_id', $examID)
        ->where('subject_id', $subjectID)
        ->where('session_id', get_session_id());
        return $builder->get()->getRowArray();
    }


    public function getMarkAndStudent($branchID, $classID, $sectionID, $examID, $subjectID)
    {
        $builder = $this->db->table('enroll as en')->select('en.*,st.first_name,st.last_name,st.register_no,st.category_id,m.mark as get_mark,m.total as get_total,IFNULL(m.absent, 0) as get_abs,subject.name as subject_name')
        ->join('student as st', 'st.id = en.student_id', 'inner')
        ->join('mark as m', 'm.student_id = en.student_id and m.class_id = en.class_id and m.section_id = en.section_id and m.exam_id = ' . $this->db->escape($examID) . ' and m.subject_id = ' . $this->db->escape($subjectID), 'left')
        ->join('subject', 'subject.id = m.subject_id', 'left')
        ->where('en.class_id', $classID)
        ->where('en.section_id', $sectionID)
        ->where('en.branch_id', $branchID)
        ->where('en.session_id', get_session_id())
        ->orderBy('en.roll', 'ASC');
        return $builder->get()->getResultArray();
    }


    public function getStudentReportCard($studentID, $examID, $sessionID)
    {
        $result = array();
        $builder = $this->db->table('enroll')->select('enroll.roll,student.*,c.name as class_name,se.name as section_name,IFNULL(parent.father_name,"N/A") as father_name,IFNULL(parent.mother_name,"N/A") as mother_name')
        ->join('student', 'student.id = enroll.student_id', 'inner')
        ->join('class as c', 'c.id = enroll.class_id', 'left')
        ->join('section as se', 'se.id = enroll.section_id', 'left')
        ->join('parent', 'parent.id = student.parent_id', 'left')
        ->where('enroll.student_id', $studentID);
        $result['student'] = $builder->get()->getRowArray();

        $builder = $this->db->table('mark as m')->select('m.mark as get_mark,mr.mark_mid as mark_mid,ct.mark_xmas as mark_xmas,clt.mark_lent as mark_lent,mxt.mark_mid as xmas_mid_total,mlt.mark_mid as lent_mid_total,IFNULL(m.absent, 0) as get_abs,subject.name as subject_name, te.mark_distribution')
        ->join('subject', 'subject.id = m.subject_id', 'left')
        ->join('mark_rel as mr', 'mr.type_id = m.type_id and mr.subject_id = m.subject_id and m.student_id = mr.student_id', 'left')
        ->join('cum_xmas_total as ct', 'ct.type_id != m.type_id and ct.student_id = m.student_id and ct.subject_id = m.subject_id', 'left')
        ->join('cum_lent_total as clt', 'clt.type_id != m.type_id and clt.student_id = m.student_id and clt.subject_id = m.subject_id', 'left')
        ->join('mid_xmas_total as mxt', 'mxt.type_id != m.type_id and mxt.student_id = m.student_id and mxt.subject_id = m.subject_id', 'left')
        ->join('mid_lent_total as mlt', 'mlt.type_id != m.type_id and mlt.student_id = m.student_id and mlt.subject_id = m.subject_id', 'left')
        ->join('timetable_exam as te', 'te.exam_id = m.exam_id and te.class_id = m.class_id and te.section_id = m.section_id and te.subject_id = m.subject_id', 'left')
        ->where('m.exam_id', $examID)
        ->where('m.student_id', $studentID)
        ->where('m.session_id', $sessionID)
        ->groupBy(['ct.subject_id','m.subject_id']);
        $result['exam'] = $builder->get()->getResultArray();

        return $result;
    }



} /*End Class */