<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Libraries\App_lib;
use App\Models\ApplicationModel;
use App\Models\SubjectModel;

/**
 * 
 */
class Subject extends BaseController
{
	public $app_lib;
	public $application_model;
	public $subject_model;
	
	function __construct()
	{
		$this->app_lib = new App_lib();
		$this->application_model = new ApplicationModel();
		$this->subject_model = new SubjectModel();
	}

	public function index()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('subject', 'is_view')) {
            access_denied();
        }
        $this->data['subjectlist'] = $this->app_lib->getTable('subject');
        $this->data['title'] = translate('subject');
        $this->data['sub_page'] = 'subject/index';
        $this->data['main_menu'] = 'subject';
        return view('layout/index', $this->data);
    }

    // subject edit page
    public function edit($id = '')
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('subject', 'is_edit')) {
            access_denied();
        }

        $this->data['subject'] = $this->app_lib->getTable('subject', array('t.id' => $id), true);
        $this->data['title'] = translate('subject');
        $this->data['sub_page'] = 'subject/edit';
        $this->data['main_menu'] = 'subject';
        return view('layout/index', $this->data);
    }

    // moderator subject all information
    public function save()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($_POST) {
            if (is_superadmin_loggedin()) {
                $this->validation->setRule('branch_id', translate('branch'), 'required');
            }
            $this->validation->setRule('name', translate('subject_name'), 'trim|required');
            $this->validation->setRule('subject_code', translate('subject_code'), 'trim|required');
            $this->validation->setRule('subject_type', translate('subject_type'), 'trim|required');
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $arraySubject = array(
                    'name' => $this->request->getVar('name'),
                    'subject_code' => $this->request->getVar('subject_code'),
                    'subject_type' => $this->request->getVar('subject_type'),
                    'subject_author' => $this->request->getVar('subject_author'),
                    'branch_id' => $this->application_model->get_branch_id(),
                );
                $subjectID = $this->request->getVar('subject_id');
                $builder = $this->db->table('subject');
                if (empty($subjectID)) {
                    if (get_permission('subject', 'is_add')) {
                        $builder->insert($arraySubject);
                    }
                    set_alert('success', translate('information_has_been_saved_successfully'));
                } else {
                    if (get_permission('subject', 'is_edit')) {
                        if (!is_superadmin_loggedin()) {
                            $builder->where('branch_id', get_loggedin_branch_id());
                        }
                        $builder->where('id', $subjectID);
                        $builder->update($arraySubject);
                    }
                    set_alert('success', translate('information_has_been_updated_successfully'));
                }
                $url = base_url('subject');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function delete($id = '')
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (get_permission('subject', 'is_delete')) {
            $this->app_lib->check_branch_restrictions('subject', $id);
            $builder = $this->db->table('subject')->where('id', $id);
            $builder->delete();
            $builder = $this->db->table('subject_assign')->where('subject_id', $id);
            $builder->delete();
        }
    }

    // add subject assign information and delete
    public function class_assign()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('subject_class_assign', 'is_view')) {
            access_denied();
        }

        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['assignlist'] = $this->subject_model->getAssignList();
        $this->data['title'] = translate('class_assign');
        $this->data['sub_page'] = 'subject/class_assign';
        $this->data['main_menu'] = 'subject';
        return view('layout/index', $this->data);
    }

    // moderator class assign save all information
    public function class_assign_save()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($_POST) {
            if (get_permission('subject_class_assign', 'is_add')) {
                if (is_superadmin_loggedin()) {
                    $this->validation->setRule('branch_id', translate('branch'), 'required');
                }
                $rules = array(
                	'class_id' => [
                		'label' => translate('class'),
                		'rules' => 'trim|required|unique_subject_assign[class_id]',
                		'errors' => [
                			'unique_subject_assign' => translate('this_class_and_section_is_already_ assigned.'),
                		],
                	],
                	'subjects' => [
                		'label' => translate('subject'),
                		'rules' => 'required',
                		'errors' => [
                			'required' => translate('this_field_is_required'),
                		],
                	],
                );

                $this->validation->setRules($rules);
                $this->validation->setRule('section_id', translate('section'), 'trim|required');
                $this->validation->setRules($rules);
                if ($this->validation->withRequest($this->request)->run() !== false) {
                    $branchID = $this->application_model->get_branch_id();
                    $arraySubject = array();
                    $arraySubject = array(
                        'class_id' => $this->request->getVar('class_id'),
                        'section_id' => $this->request->getVar('section_id'),
                        'session_id' => get_session_id(),
                        'branch_id' => $branchID,
                    );

                    // get class teacher details
                    $get_teacher = $this->subject_model
                    ->where($arraySubject)
                    ->first();
                    $subjects = $this->request->getVar('subjects');
                    foreach ($subjects as $subject) {
                        $arraySubject['subject_id'] = $subject;
                        $query = $this->db->table('subject_assign')->getWhere($arraySubject);

                        if ($query->getNumRows() == 0) {
                            $arraySubject['teacher_id'] = empty($get_teacher) ? 0 : $get_teacher['teacher_id'];
                            $this->db->table('subject_assign')->insert($arraySubject);
                        }
                    }
                    set_alert('success', translate('information_has_been_saved_successfully'));
                    $url = base_url('subject/class_assign');
                    $array = array('status' => 'success', 'url' => $url, 'error' => '');
                } else {
                    $error = $this->validation->getErrors();
                    $array = array('status' => 'fail', 'url' => '', 'error' => $error);
                }
                echo json_encode($array);
            }
        }
    }


    // subject assign information edit
    public function class_assign_edit()
    {
        if ($_POST) {
            if (get_permission('subject_class_assign', 'is_edit')) {
                $this->validation->setRule('subjects', translate('subject'), 'required');
                if ($this->validation->withRequest($this->request)->run() !== false) {
                    $sessionID = get_session_id();
                    $classID = $this->request->getVar('class_id');
                    $sectionID = $this->request->getVar('section_id');
                    $branchID = $this->application_model->get_branch_id();
                    $arraySubject = array(
                        'class_id' => $classID,
                        'section_id' => $sectionID,
                        'session_id' => $sessionID,
                        'branch_id' => $branchID,
                    );

                    // get class teacher details
                    $get_teacher = $this->subject_model
                    ->where($arraySubject)
                    ->first();

                    $subjects = $this->request->getVar('subjects');
                    foreach ($subjects as $subject) {
                        $arraySubject['subject_id'] = $subject;
                        $query = $this->db->table('subject_assign')->getWhere($arraySubject);
                        if ($query->getNumRows() == 0) {
                            $arraySubject['teacher_id'] = empty($get_teacher) ? 0 : $get_teacher['teacher_id'];
                            $this->db->table('subject_assign')->insert($arraySubject);
                        }
                    }
                    $builder = $this->db->table('subject_assign')->whereNotIn('subject_id', $subjects);
                    $builder->where('class_id', $classID);
                    $builder->where('section_id', $sectionID);
                    $builder->where('session_id', $sessionID);
                    $builder->where('branch_id', $branchID);
                    $builder->delete();
                    set_alert('success', translate('information_has_been_updated_successfully'));
                    $url = base_url('subject/class_assign');
                    $array = array('status' => 'success', 'url' => $url, 'error' => '');
                } else {
                    $error = $this->validation->getErrors();
                    $array = array('status' => 'fail', 'url' => '', 'error' => $error);
                }
                echo json_encode($array);
            }
        }
    }


    public function class_assign_delete($class_id = '', $section_id = '')
    {
        if (!get_permission('subject_class_assign', 'is_delete')) {
            access_denied();
        }
        $builder = $this->db->table('subject_assign');
        if (!is_superadmin_loggedin()) {
            $builder->where('branch_id', get_loggedin_branch_id());
        }
        $builder->where('class_id', $class_id);
        $builder->where('section_id', $section_id);
        $builder->where('session_id', get_session_id());
        $builder->delete();
    }


    // teacher assign view page
    public function teacher_assign()
    {
        if (!get_permission('subject_teacher_assign', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (get_permission('subject_teacher_assign', 'is_add')) {
                if (is_superadmin_loggedin()) {
                    $this->validation->setRule('branch_id', translate('branch'), 'required');
                }
                $this->validation->setRule('staff_id', translate('teacher'), 'trim|required');
                $this->validation->setRule('class_id', translate('class'), 'trim|required');
                $this->validation->setRule('section_id', translate('section'), 'trim|required');
                $this->validation->setRule('subject_id', translate('subject'), 'trim|required');
                if ($this->validation->withRequest($this->request)->run() !== false) {
                    $sessionID = get_session_id();
                    $branchID = $this->application_model->get_branch_id();
                    $classID = $this->request->getVar('class_id');
                    $sectionID = $this->request->getVar('section_id');
                    $subjectID = $this->request->getVar('subject_id');
                    $teacherID = $this->request->getVar('staff_id');
                    $query = $this->db->table('subject_assign')->getWhere(array(
                        'class_id' => $classID,
                        'section_id' => $sectionID,
                        'subject_id' => $subjectID,
                        'session_id' => $sessionID,
                        'branch_id' => $branchID,
                    ));
                    if ($query->getNumRows() != 0) {
                        $this->db->table('subject_assign')->where('id', $query->getRow()->id)
                        ->update(array('teacher_id' => $teacherID));
                    }
                    set_alert('success', translate('information_has_been_updated_successfully'));
                    $url = base_url('subject/teacher_assign');
                    $array = array('status' => 'success', 'url' => $url, 'error' => '');
                } else {
                    $error = $this->validation->getErrors();
                    $array = array('status' => 'fail', 'url' => '', 'error' => $error);
                }
                echo json_encode($array);
                exit();
            }
        }

        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['assignlist'] = $this->subject_model->getTeacherAssignList();
        $this->data['title'] = translate('teacher_assign');
        $this->data['sub_page'] = 'subject/teacher_assign';
        $this->data['main_menu'] = 'subject';
        return view('layout/index', $this->data);
    }


    // teacher assign information moderator
    public function teacher_assign_delete($id = '')
    {
        $builder = $this->db->table('subject_assign');
        if (get_permission('subject_teacher_assign', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->where('id', $id);
            $builder->update(array('teacher_id' => 0));
        }
    }


    // get subject list based on class section
    public function getByClassSection()
    {
        $html = '';
        $classID = $this->request->getVar('classID');
        $sectionID = $this->request->getVar('sectionID');
        if (!empty($classID)) {
            $query = $this->db->table('subject_assign')->select('id,subject_id')->where(array('class_id' => $classID, 'section_id' => $sectionID))->get();
            if ($query->getNumRows() != 0) {
                $html .= '<option value="">' . translate('select') . '</option>';
                $subjects = $query->getResultArray();
                foreach ($subjects as $row) {
                    $html .= '<option value="' . $row['subject_id'] . '">' . get_type_name_by_id('subject', $row['subject_id']) . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select') . '</option>';
        }
        echo $html;
    }





} /* End Class*/