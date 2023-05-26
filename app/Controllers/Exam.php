<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\ApplicationModel;
use App\Models\ExamModel;
use App\Libraries\App_lib;
use App\Models\SmsModel;

/**
 * 
 */
class Exam extends BaseController
{
	public $application_model;
	public $exam_model;
	public $app_lib;
	public $sms_model;
	
	function __construct()
	{
		$this->application_model = new ApplicationModel();
		$this->exam_model = new ExamModel();
		$this->app_lib = new App_lib();
		$this->sms_model = new SmsModel();
	}

	/* exam form validation rules */
    protected function exam_validation()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'required');
        }
        $this->validation->setRule('name', translate('name'), 'trim|required');
        $this->validation->setRule('type_id', translate('exam_type'), 'trim|required');
        $this->validation->setRule('mark_distribution', translate('mark_distribution'), 'required');
    }

    public function index()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('exam', 'is_view')) {
            access_denied();
        }

        if ($_POST) {
            if (!get_permission('exam', 'is_view')) {
                ajax_access_denied();
            }
            $this->exam_validation();

            if ($this->validation->withRequest($this->request)->run() !== false) {
                $post = $this->request->getVar();
                $this->exam_model->exam_save($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('exam');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }

        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['examlist'] = $this->exam_model->getExamList();
        $this->data['title'] = translate('exam_list');
        $this->data['sub_page'] = 'exam/index';
        $this->data['main_menu'] = 'exam';
        return view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('exam', 'is_edit')) {
            access_denied();
        }

        if ($_POST) {
            $this->exam_validation();
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $post = $this->request->getVar();
                $this->exam_model->exam_save($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('exam');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }

        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['exam'] = $this->app_lib->getTable('exam', array('t.id' => $id), true);
        $this->data['title'] = translate('exam_list');
        $this->data['sub_page'] = 'exam/edit';
        $this->data['main_menu'] = 'exam';
        return view('layout/index', $this->data);
    }

    // exam information delete stored in the database here
    public function delete($id)
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

    	$builder = $this->db->table('exam');
        if (!get_permission('exam', 'is_delete')) {
            access_denied();
        }
        if (!is_superadmin_loggedin()) {
            $builder->where('branch_id', get_loggedin_branch_id());
        }
        $builder->where('id', $id);
        $builder->delete();
    }

    /* term form validation rules */
    protected function term_validation()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'required');
        }

        $rules = array(
        	'term_name' => [
        		'label' => translate('name'),
        		'rules' => 'trim|required|unique_term[name]',
        		'errors' => [
        			'unique_term' => translate('already_taken'),
        		],
        	],
        );
        $this->validation->setRules($rules);
    }

    // exam term information are prepared and stored in the database here
    public function term()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (isset($_POST['save'])) {
            if (!get_permission('exam_term', 'is_add')) {
                access_denied();
            }
            $this->term_validation();
            if ($this->validation->withRequest($this->request)->run() !== false) {
                //save exam term information in the database file
                $this->exam_model->termSave($this->request->getVar());
                set_alert('success', translate('information_has_been_saved_successfully'));
                return redirect()->to(current_url());
            }
        }
        $this->data['termlist'] = $this->app_lib->getTable('exam_term');
        $this->data['sub_page'] = 'exam/term';
        $this->data['main_menu'] = 'exam';
        $this->data['title'] = translate('exam_term');
        return view('layout/index', $this->data);
    }

    public function term_edit()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($_POST) {
            if (!get_permission('exam_term', 'is_edit')) {
                ajax_access_denied();
            }
            $this->term_validation();
            if ($this->validation->withRequest($this->request)->run() !== false) {
                //save exam term information in the database file
                $this->exam_model->termSave($this->request->getVar());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('exam/term');
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'url' => '', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function term_delete($id)
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('exam_term', 'is_delete')) {
            access_denied();
        }
        $builder = $this->db->table('exam_term');
        if (!is_superadmin_loggedin()) {
            $builder->where('branch_id', get_loggedin_branch_id());
        }
        $builder->where('id', $id);
        $builder->delete();
    }

    public function mark_distribution()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (isset($_POST['save'])) {
            if (!get_permission('mark_distribution', 'is_add')) {
                access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->validation->setRule('branch_id', translate('branch'), 'required');
            }
            $this->validation->setRule('name', translate('name'), 'trim|required');
            if ($this->validation->withRequest($this->request)->run() !== false) {
                // save mark distribution information in the database file
                $arrayDistribution = array(
                    'name' => $this->request->getVar('name'),
                    'branch_id' => $this->application_model->get_branch_id(),
                );
                $this->db->table('exam_mark_distribution')->insert($arrayDistribution);
                set_alert('success', translate('information_has_been_saved_successfully'));
                return redirect()->to(current_url());
            }
        }
        $this->data['termlist'] = $this->app_lib->getTable('exam_mark_distribution');
        $this->data['sub_page'] = 'exam/mark_distribution';
        $this->data['main_menu'] = 'exam';
        $this->data['title'] = translate('mark_distribution');
        return view('layout/index', $this->data);
    }

    public function mark_distribution_edit()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($_POST) {
            if (!get_permission('mark_distribution', 'is_edit')) {
                ajax_access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->validation->setRule('branch_id', translate('branch'), 'required');
            }
            $this->validation->setRule('name', translate('name'), 'trim|required');
            if ($this->validation->withRequest($this->request)->run() !== false) {
                // save mark distribution information in the database file
                $arrayDistribution = array(
                    'name' => $this->request->getVar('name'),
                    'branch_id' => $this->application_model->get_branch_id(),
                );
                $builder = $this->db->table('exam_mark_distribution')->where('id', $this->request->getVar('distribution_id'));
                $builder->update($arrayDistribution);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('exam/mark_distribution');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function mark_distribution_delete($id)
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('mark_distribution', 'is_delete')) {
            access_denied();
        }
        $builder = $this->db->table('exam_mark_distribution');
        if (!is_superadmin_loggedin()) {
            $builder->where('branch_id', get_loggedin_branch_id());
        }
        $builder->where('id', $id);
        $builder->delete();
    }

    /* hall form validation rules */
    protected function hall_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'required');
        }
        $rules = array(
        	'hall_no' => [
        		'label' => translate('hall_no'),
        		'rules' => 'trim|required|unique_hall_no[hall_no]',
        		'errors' => [
        			'unique_hall_no' => translate('already_taken'),
        		],
        	],
        );
        $this->validation->setRules($rules);
        $this->validation->setRule('no_of_seats', translate('no_of_seats'), 'trim|required|numeric');
    }

    /* exam hall information moderator and page */
    public function hall($action = '', $id = '')
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (isset($_POST['save'])) {
            if (!get_permission('exam_hall', 'is_add')) {
                access_denied();
            }
            $this->hall_validation();
            if ($this->validation->withRequest($this->request)->run() !== false) {
                //save exam hall information in the database file
                $this->exam_model->hallSave($this->request->getVar());
                set_alert('success', translate('information_has_been_saved_successfully'));
                return redirect()->to(current_url());
            }
        }
        $this->data['halllist'] = $this->app_lib->getTable('exam_hall');
        $this->data['title'] = translate('exam_hall');
        $this->data['sub_page'] = 'exam/hall';
        $this->data['main_menu'] = 'exam';
        return view('layout/index', $this->data);
    }

    public function hall_edit()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($_POST) {
            if (!get_permission('exam_hall', 'is_edit')) {
                ajax_access_denied();
            }
            $this->hall_validation();
            if ($this->validation->withRequest($this->request)->run() !== false) {
                //save exam hall information in the database file
                $this->exam_model->hallSave($this->request->getVar());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('exam/hall');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function hall_delete($id)
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('exam_hall', 'is_delete')) {
            access_denied();
        }
        $builder = $this->db->table('exam_hall');
        if (!is_superadmin_loggedin()) {
            $builder->where('branch_id', get_loggedin_branch_id());
        }
        $builder->where('id', $id);
        $builder->delete();
    }

    /* exam mark information are prepared and stored in the database here */
    public function mark_entry()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('exam_mark', 'is_add')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $classID = $this->request->getVar('class_id');
        $sectionID = $this->request->getVar('section_id');
        $subjectID = $this->request->getVar('subject_id');
        $examID = $this->request->getVar('exam_id');
        $typeID = $this->request->getVar('type_id');
        $typeRelID = $this->request->getVar('type_rel_id');

        $this->data['branch_id'] = $branchID;
        $this->data['class_id'] = $classID;
        $this->data['section_id'] = $sectionID;
        $this->data['subject_id'] = $subjectID;
        $this->data['exam_id'] = $examID;
        $this->data['type_id'] = $typeID;
        $this->data['type_rel_id'] = $typeRelID;
        if (isset($_POST['search'])) {
            $this->data['timetable_detail'] = $this->exam_model->getTimetableDetail($classID, $sectionID, $examID, $subjectID);

            $this->data['student'] = $this->exam_model->getMarkAndStudent($branchID, $classID, $sectionID, $examID, $subjectID);
        }

        $this->data['sub_page'] = 'exam/marks_register';
        $this->data['main_menu'] = 'mark';
        $this->data['title'] = translate('mark_entries');
        return view('layout/index', $this->data);
    }

    public function mark_save()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($this->request->getMethod() == 'post') {
            if (!get_permission('exam_mark', 'is_add')) {
                ajax_access_denied();
            }
            $inputMarks = $this->request->getVar('mark');
            foreach ($inputMarks as $key => $value) {
                if (!isset($value['absent'])) {
                    foreach ($value['assessment'] as $i => $row) {
                        $this->validation->setRule('mark', translate('mark'), 'required');
                    }
                }
            }
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $branchID = $this->application_model->get_branch_id();
                $classID = $this->request->getVar('class_id');
                $sectionID = $this->request->getVar('section_id');
                $subjectID = $this->request->getVar('subject_id');
                $examID = $this->request->getVar('exam_id');
                $typeID = $this->request->getVar('type_id');
                $typeRelID = $this->request->getVar('type_rel_id');
                $inputMarks = $this->request->getVar('mark');

                foreach ($inputMarks as $key => $value) {
                    $assMark = array();
                    $sum = array();
                    foreach ($value['sum'] as $i => $row1) {
                        $sum[$i] = $row1;
                    }

                    foreach ($value['assessment'] as $i => $row) {
                        $assMark[$i] = $row;
                        
                    }
                    
                    $arrayMarks = array(
                        'student_id' => $value['student_id'],
                        'exam_id' => $examID,
                        'class_id' => $classID,
                        'section_id' => $sectionID,
                        'subject_id' => $subjectID,
                        'branch_id' => $branchID,
                        'type_id' => $typeID,
                        'session_id' => get_session_id(),
                    );
                    $inputMark = (isset($value['absent']) ? null : json_encode($assMark));
                    $totalMark = (isset($value['absent']) ? null : json_encode($sum));
                    $absent = (isset($value['absent']) ? 'on' : '');
                    $query = $this->db->table('mark')->getWhere($arrayMarks);
                    if ($query->getNumRows() > 0) {
                        $builder = $this->db->table('mark')->where('id', $query->getRow()->id);
                        if ($absent) {
                            $this->db->table('mark')->where('id', $query->getRow()->id)->delete();
                        }
                        if ($typeID == 3) {
                            $builder->update(array('mark' => $inputMark, 'total' => $totalMark, 'absent' => $absent));

                            // if ($typeRelID == 4) {
                            //     $where = "type_id=4 AND student_id= ".$value['student_id']. " AND subject_id =".$subjectID;
                            //     $this->db->table('mark_rel')
                            //     ->where($where)
                            //     ->update(array('mark_mid' => $inputMark));
                            //     }

                        }else {
                            $builder->update(array('mark' => $inputMark, 'total' => $totalMark, 'absent' => $absent));
                        }
                        // $builder->update(array('mark' => $inputMark, 'total' => $totalMark, 'absent' => $absent));
                    } elseif($typeID == 3) {
                        $arrayMarks['mark'] = $inputMark;
                        // $arrayMarks['mark_mid'] = $inputMark;
                        $arrayMarks['total'] = $totalMark;
                        $arrayMarks['absent'] = $absent;
                        $res = $this->db->table('mark')->insert($arrayMarks);
                        // send exam results sms
                        // $this->sms_model->send_sms($arrayMarks, 5);
                    }elseif($typeID == 4 && $typeRelID == 4) {
                        $arrayMarks['mark'] = $inputMark;
                        // $arrayMarks['mark_mid'] = $inputMark;
                        $arrayMarks['total'] = $totalMark;
                        $arrayMarks['absent'] = $absent;
                        $this->db->table('mark')->insert($arrayMarks);
                    }elseif($typeID == 5 && $typeRelID == 5) {
                        $arrayMarks['mark'] = $inputMark;
                        // $arrayMarks['mark_mid'] = $inputMark;
                        $arrayMarks['total'] = $totalMark;
                        $arrayMarks['absent'] = $absent;
                        $this->db->table('mark')->insert($arrayMarks);
                    }elseif($typeID == 6 && $typeRelID == 6) {
                        $arrayMarks['mark'] = $inputMark;
                        // $arrayMarks['mark_mid'] = $inputMark;
                        $arrayMarks['total'] = $totalMark;
                        $arrayMarks['absent'] = $absent;
                        $this->db->table('mark')->insert($arrayMarks);
                    }
                        

                    } // End 1st Foreach


                    // FOR UPDATE OR INSERT INTO MARK_REL

                    foreach ($inputMarks as $key => $value) {
                    $assMark = array();
                    $sum = array();
                    foreach ($value['sum'] as $i => $row1) {
                        $sum[$i] = $row1;
                    }

                    foreach ($value['assessment'] as $i => $row) {
                        $assMark[$i] = $row;
                        
                    }
                    
                    $arrayMarks = array(
                        'student_id' => $value['student_id'],
                        'exam_id' => $examID,
                        'class_id' => $classID,
                        'section_id' => $sectionID,
                        'subject_id' => $subjectID,
                        'branch_id' => $branchID,
                        'type_id' => $typeRelID,
                        'session_id' => get_session_id(),
                    );
                    $inputMark = (isset($value['absent']) ? null : json_encode($assMark));
                    $totalMark = (isset($value['absent']) ? null : json_encode($sum));
                    $absent = (isset($value['absent']) ? 'on' : '');
                    $query = $this->db->table('mark_rel')->getWhere($arrayMarks);
                        if($typeID == 3 && $typeRelID == 4) {
                            if ($query->getNumRows() > 0) {
                                $builder = $this->db->table('mark_rel')->where('id', $query->getRow()->id);
                                if ($absent) {
                                    $this->db->table('mark_rel')->where('id', $query->getRow()->id)->delete();
                                }
                                $builder->update(array('mark_mid' => $inputMark, 'absent' => $absent));
                            }else {
                                $arrayMarks['type_id'] = $typeRelID;
                                $arrayMarks['mark_mid'] = $inputMark;
                                $arrayMarks['absent'] = $absent;
                                $res = $this->db->table('mark_rel')->insert($arrayMarks);
                            }
                            
                            
                        // send exam results sms
                        // $this->sms_model->send_sms($arrayMarks, 5);
                        }elseif($typeID == 3 && $typeRelID == 5) {
                           if ($query->getNumRows() > 0) {
                                $builder = $this->db->table('mark_rel')->where('id', $query->getRow()->id);
                                if ($absent) {
                                    $this->db->table('mark_rel')->where('id', $query->getRow()->id)->delete();
                                }
                                $builder->update(array('mark_mid' => $inputMark, 'absent' => $absent));
                            }else {
                                $arrayMarks['type_id'] = $typeRelID;
                                $arrayMarks['mark_mid'] = $inputMark;
                                $arrayMarks['absent'] = $absent;
                                $res = $this->db->table('mark_rel')->insert($arrayMarks);
                            }
                        }elseif($typeID == 3 && $typeRelID == 6) {
                            if ($query->getNumRows() > 0) {
                                $builder = $this->db->table('mark_rel')->where('id', $query->getRow()->id);
                                if ($absent) {
                                    $this->db->table('mark_rel')->where('id', $query->getRow()->id)->delete();
                                }
                                $builder->update(array('mark_mid' => $inputMark, 'absent' => $absent));
                            }else {
                                $arrayMarks['type_id'] = $typeRelID;
                                $arrayMarks['mark_mid'] = $inputMark;
                                $arrayMarks['absent'] = $absent;
                                $res = $this->db->table('mark_rel')->insert($arrayMarks);
                            }
                        }


                    } // End 2nd Foreach

                $message = translate('information_has_been_saved_successfully');
                $array = array('status' => 'success', 'message' => $message);
                // return redirect()->to(current_url());
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }

    }


    /* exam grade form validation rules */
    protected function grade_validation()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'required');
        }
        $this->validation->setRule('name', translate('name'), 'trim|required');
        $this->validation->setRule('grade_point', translate('grade_point'), 'trim|required');
        $this->validation->setRule('lower_mark', translate('mark_from'), 'trim|required');
        $this->validation->setRule('upper_mark', translate('mark_upto'), 'trim|required');
    }

    /* exam grade information are prepared and stored in the database here */
    public function grade($action = '')
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('exam_grade', 'is_view')) {
            access_denied();
        }

        if ($_POST) {
            if (!get_permission('exam_grade', 'is_view')) {
                ajax_access_denied();
            }
            $this->grade_validation();
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $post = $this->request->getVar();
                $this->exam_model->gradeSave($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('exam/grade');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['title'] = translate('grades_range');
        $this->data['sub_page'] = 'exam/grade';
        $this->data['main_menu'] = 'mark';
        return view('layout/index', $this->data);
    }

    // exam grade information updating here
    public function grade_edit($id = '')
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('exam_grade', 'is_edit')) {
            ajax_access_denied();
        }

        if ($_POST) {
            $this->grade_validation();
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $post = $this->request->getVar();
                $this->exam_model->gradeSave($post);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('exam/grade');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['grade'] = $this->app_lib->getTable('grade', array('t.id' => $id), true);
        $this->data['sub_page'] = 'exam/grade_edit';
        $this->data['title'] = translate('grades_range');
        $this->data['main_menu'] = 'exam';
        return view('layout/index', $this->data);
    }

    public function grade_delete($id = '')
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

    	$builder = $this->db->table('grade');
        if (get_permission('exam_grade', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->where('id', $id);
            $builder->delete();
        }
    }

    public function marksheet()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('report_card', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            $sessionID = $this->request->getVar('session_id');
            $examID = $this->request->getVar('exam_id');
            $classID = $this->request->getVar('class_id');
            $sectionID = $this->request->getVar('section_id');
            $builder = $this->db->table('enroll as e')->select('e.roll,s.*,c.name as category')
            ->join('student as s', 'e.student_id = s.id', 'inner')
            ->join('mark as m', 'm.student_id = s.id', 'inner')
            ->join('student_category as c', 'c.id = s.category_id', 'left')
            ->where('e.session_id', $sessionID)
            ->where('e.class_id', $classID)
            ->where('e.section_id', $sectionID)
            ->where('e.branch_id', $branchID)
            ->where('m.exam_id', $examID)
            ->groupBy('m.student_id');
            $this->data['student'] = $builder->get()->getResultArray();
        }

        $this->data['branch_id'] = $branchID;
        $this->data['sub_page'] = 'exam/marksheet';
        $this->data['main_menu'] = 'exam_reports';
        $this->data['title'] = translate('report_card');
        return view('layout/index', $this->data);
    }

    public function reportCardPrint()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($_POST) {
            if (!get_permission('report_card', 'is_view')) {
                ajax_access_denied();
            }
            $this->data['student_array'] = $this->request->getVar('student_id');
            $this->data['remarks_array'] = $this->request->getVar('remarks');
            $this->data['grade_scale'] = $this->request->getVar('grade_scale');
            $this->data['attendance'] = $this->request->getVar('attendance');
            $this->data['print_date'] = $this->request->getVar('print_date');
            $this->data['examID'] = $this->request->getVar('exam_id');
            $this->data['sessionID'] = $this->request->getVar('session_id');
            return view('exam/reportCard', $this->data);
        }
    }

    /* tabulation sheet report generating here */
    public function tabulation_sheet()
    {
        $typeRelID = '';
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('tabulation_sheet', 'is_view')) {
            access_denied();
        }
        $this->data['branch_id'] = $this->application_model->get_branch_id();
        if (!empty($this->request->getVar('submit'))) {
            $classID = $this->request->getVar('class_id');
            $sectionID = $this->request->getVar('section_id');
            $examID = $this->request->getVar('exam_id');
            $sessionID = $this->request->getVar('session_id');
            $typeRelID = $this->request->getVar('type_rel_id');
            $this->data['get_subjects'] = $this->exam_model->getSubjectList($examID, $classID, $sectionID, $sessionID);
        }
        $this->data['type_rel_id'] = $typeRelID;
        $this->data['title'] = translate('tabulation_sheet');
        $this->data['sub_page'] = 'exam/tabulation_sheet';
        $this->data['main_menu'] = 'exam_reports';
        return view('layout/index', $this->data);
    }

    public function getDistributionByBranch()
    {
        $html = "";
        $table = $this->request->getVar('table');
        $branch_id = $this->application_model->get_branch_id();
        if (!empty($branch_id)) {
            $result = $this->db->table('exam_mark_distribution')->select('id,name')->where('branch_id', $branch_id)->get()->getResultArray();
            if (count($result)) {
                foreach ($result as $row) {
                    $html .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
            }
        }
        echo $html;
    }




} /*End Class */