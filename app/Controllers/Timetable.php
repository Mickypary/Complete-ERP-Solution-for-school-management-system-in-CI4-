<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\ApplicationModel;
use App\Models\TimetableModel;

/**
 * 
 */
class Timetable extends BaseController
{
	public $application_model;
	public $timetable_model;
	
	function __construct()
	{
		$this->application_model = new ApplicationModel();
		$this->timetable_model = new TimetableModel();
	}

	public function index()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (get_loggedin_id()) {
        	return redirect()->to(base_url('timetable/view_classwise'));
        } else {
        	return redirect()->to(base_url());
        }
    }

    /*class timetable view page*/
    public function viewclass()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('class_timetable', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            $classID = $this->request->getVar('class_id');
            $sectionID = $this->request->getVar('section_id');
            $arrayTimetable = array(
                'branch_id' => $branchID,
                'class_id' => $classID,
                'section_id' => $sectionID,
                'session_id' => get_session_id(),
            );

            $this->data['timetables'] = $this->db->table('timetable_class')->orderBy('time_start', 'asc')->getWhere($arrayTimetable)->getResult();
            $this->data['class_id'] = $classID;
            $this->data['section_id'] = $sectionID;
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('class') . " " . translate('schedule');
        $this->data['sub_page'] = 'timetable/viewclass';
        $this->data['main_menu'] = 'timetable';
        return view('layout/index', $this->data);
    }

    /* class timetable information are prepared and stored in the database here */
    public function set_classwise()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('class_timetable', 'is_add')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            $this->data['class_id'] = $this->request->getVar('class_id');
            $this->data['day'] = $this->request->getVar('day');
            $this->data['section_id'] = $this->request->getVar('section_id');
            $this->data['branch_id'] = $branchID;
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('add') . " " . translate('schedule');
        $this->data['sub_page'] = 'timetable/set_classwise';
        $this->data['main_menu'] = 'timetable';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
            ),
            'js' => array(
                'vendor/bootstrap-timepicker/bootstrap-timepicker.js',
            ),
        );
        return view('layout/index', $this->data);
    }

    /* class timetable updating here */
    public function update_classwise()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('class_timetable', 'is_edit')) {
            access_denied();
        }

        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['class_id'] = $this->request->getVar('class_id');
        $this->data['section_id'] = $this->request->getVar('section_id');
        $this->data['day'] = $this->request->getVar('day');
        $timetable_array = array(
            'branch_id' => $this->data['branch_id'],
            'class_id' => $this->data['class_id'],
            'section_id' => $this->data['section_id'],
            'day' => $this->data['day'],
            'session_id' => get_session_id(),
        );

        $this->data['timetables'] = $this->db->table('timetable_class')->orderBy('time_start', 'asc')->getWhere($timetable_array)->getResult();
        $this->data['title'] = translate('class') . " " . translate('schedule');
        $this->data['sub_page'] = 'timetable/update_classwise';
        $this->data['main_menu'] = 'timetable';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
            ),
            'js' => array(
                'vendor/bootstrap-timepicker/bootstrap-timepicker.js',
            ),
        );
        return view('layout/index', $this->data);
    }


    public function class_save($mode = '')
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($_POST) {
            if (!get_permission('class_timetable', 'is_add')) {
                ajax_access_denied();
            }

            $items = $this->request->getVar('timetable');
            foreach ($items as $key => $value) {
                $this->validation->setRule('timetable', translate('starting_time'), 'required');
                $this->validation->setRule('timetable', translate('ending_time'), 'required');
                if (!isset($value['break'])) {
                    $this->validation->setRule('timetable', translate('subject'), 'required');
                    $this->validation->setRule('timetable', translate('teacher'), 'required');
                }
            }
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $post = $this->request->getVar();
                $this->timetable_model->classwise_save($post, $mode);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('timetable/set_classwise');
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'url' => '', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    // exam timetable preview page
    public function viewexam()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('exam_timetable', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            $classID = $this->request->getVar('class_id');
            $sectionID = $this->request->getVar('section_id');
            $this->data['examlist'] = $this->timetable_model->getExamTimetableList($classID, $sectionID, $branchID);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('exam') . " " . translate('schedule');
        $this->data['sub_page'] = 'timetable/viewexam';
        $this->data['main_menu'] = 'exam_timetable';
        return view('layout/index', $this->data);
    }

    // exam timetable information are prepared and stored in the database here
    public function set_examwise()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }
        
        if (!get_permission('exam_timetable', 'is_add')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            $examID = $this->request->getVar('exam_id');
            $classID = $this->request->getVar('class_id');
            $sectionID = $this->request->getVar('section_id');
            $this->data['exam_id'] = $examID;
            $this->data['class_id'] = $classID;
            $this->data['section_id'] = $sectionID;
            $this->data['subjectassign'] = $this->timetable_model->getSubjectExam($classID, $sectionID, $examID, $branchID);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('add') . " " . translate('schedule');
        $this->data['sub_page'] = 'timetable/set_examwise';
        $this->data['main_menu'] = 'exam_timetable';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
            ),
            'js' => array(
                'vendor/bootstrap-timepicker/bootstrap-timepicker.js',
            ),
        );
        return view('layout/index', $this->data);
    }

    public function exam_create()
    {
        if (!get_permission('exam_timetable', 'is_add')) {
            ajax_access_denied();
        }
        if ($_POST) {
            // form validation rules
            $items = $this->request->getVar('timetable');
            foreach ($items as $key => $value) {
                $this->validation->setRule('timetable', translate('date'), 'required');
                $this->validation->setRule('timetable', translate('starting_time'), 'required');
                $this->validation->setRule('timetable', translate('ending_time'), 'required');
                $rules = array(
                    'timetable' => [
                        'label' => translate('hall_room'),
                        'rules' => 'required|check_hallseat_capacity[name]',
                        'errors' => [
                            'check_hallseat_capacity' => translate('the_seats_capacity_is_exceeded.'),
                        ],
                    ],
                );
                $this->validation->setRules($rules);
                foreach ($value['full_mark'] as $i => $id) {
                    $this->validation->setRule('timetable', translate('full_mark'), 'required');
                    $this->validation->setRule('timetable', translate('pass_mark'), 'required');
                }
            }
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $branchID = $this->application_model->get_branch_id();
                $examID = $this->request->getVar('exam_id');
                $classID = $this->request->getVar('class_id');
                $sectionID = $this->request->getVar('section_id');
                $timetable = $this->request->getVar('timetable');
                // print_r($timetable);
                // die();
                foreach ($timetable as $key => $value) {
                    // distribution array
                    $distribution = array();
                    foreach ($value['full_mark'] as $id => $mark) {
                       $distribution[$id]['full_mark'] = $mark;

                    }
                    foreach ($value['pass_mark'] as $id => $mark) {
                       $distribution[$id]['pass_mark'] = $mark;

                    }
                    $arrayData = array(
                        'exam_id' => $examID,
                        'class_id' => $classID,
                        'section_id' => $sectionID,
                        'subject_id' => $value['subject_id'],
                        'time_start' => $value['time_start'],
                        'time_end' => $value['time_end'],
                        'hall_id' => $value['hall_id'],
                        'exam_date' => $value['date'],
                        'mark_distribution' => json_encode($distribution),
                        'branch_id' => $branchID,
                        'session_id' => get_session_id(),
                    );
                    $builder = $this->db->table('timetable_exam')
                    ->where('exam_id', $examID)
                    ->where('class_id', $classID)
                    ->where('section_id', $sectionID)
                    ->where('subject_id', $value['subject_id'])
                    ->where('session_id', get_session_id());
                    $q = $builder->get();
                    if ($q->getNumRows() > 0) {
                        $result = $q->getRowArray();
                        $builder = $this->db->table('timetable_exam')->where('id', $result['id']);
                        $builder->update($arrayData);
                    } else {
                        $this->db->table('timetable_exam')->insert($arrayData);
                    }
                }
                $message = translate('information_has_been_saved_successfully');
                $array = array('status' => 'success', 'message' => $message);
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function exam_delete($examID, $classID, $sectionID)
    {
        $builder = $this->db->table('timetable_exam');
        if (get_permission('exam_timetable', 'is_delete')) {
            $builder->where('exam_id', $examID);
            $builder->where('class_id', $classID);
            $builder->where('section_id', $sectionID);
            $builder->where('session_id', get_session_id());
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->delete();
        }
    }

    public function getExamTimetableM()
    {
        $examID = $this->request->getVar('exam_id');
        $classID = $this->request->getVar('class_id');
        $sectionID = $this->request->getVar('section_id');
        $this->data['exam_id'] = $examID;
        $this->data['class_id'] = $classID;
        $this->data['section_id'] = $sectionID;
        $this->data['timetables'] = $this->timetable_model->getExamTimetableByModal($examID, $classID, $sectionID);
        return view('timetable/examTimetableM', $this->data);
    }




} /*End Class */