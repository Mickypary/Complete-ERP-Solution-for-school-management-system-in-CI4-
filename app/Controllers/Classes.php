<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Libraries\App_lib;
use App\Models\ApplicationModel;
use App\Models\ClassesModel;

/**
 * 
 */
class Classes extends BaseController
{
	public $validation;
	public $app_lib;
	public $application_model;
	public $classes_model;
	
	function __construct()
	{
		$this->validation = \Config\Services::validation();
		$this->app_lib = new App_lib();
		$this->application_model = new ApplicationModel();
		$this->classes_model = new ClassesModel();
	}


	/* class form validation rules */
    protected function class_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'required');
        }
        $this->validation->setRule('name', translate('name'), 'trim|required');
        $this->validation->setRule('name_numeric', translate('name_numeric'), 'trim|numeric');
        $this->validation->setRule('sections', translate('section'), 'required');
    }


    public function index()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('classes', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (get_permission('classes', 'is_add')) {
                $this->class_validation();
                if ($this->validation->withRequest($this->request)->run() !== false) {
                    $arrayClass = array(
                        'name' => $this->request->getVar('name'),
                        'name_numeric' => $this->request->getVar('name_numeric'),
                        'branch_id' => $this->application_model->get_branch_id(),
                    );
                    $this->db->table('class')->insert($arrayClass);
                    $class_id = $this->db->insertID();
                    $sections = $this->request->getVar('sections');
                    foreach ($sections as $section) {
                        $arrayData = array(
                            'class_id' => $class_id,
                            'section_id' => $section,
                        );
                        $query = $this->db->table('sections_allocation')->getWhere($arrayData);
                        if ($query->getNumRows() == 0) {
                            $this->db->table('sections_allocation')->insert($arrayData);
                        }
                    }
                    set_alert('success', translate('information_has_been_saved_successfully'));
                    $url = base_url('classes');
                    $array = array('status' => 'success', 'url' => $url, 'error' => '');
                } else {
                    $error = $this->validation->getErrors();
                    $array = array('status' => 'fail', 'url' => '', 'error' => $error);
                }
                echo json_encode($array);
                exit();
            }
        }
        $this->data['classlist'] = $this->app_lib->getTable('class');
        $this->data['query_classes'] = $this->db->table('class')->get();
        $this->data['title'] = translate('control_classes');
        $this->data['sub_page'] = 'classes/index';
        $this->data['main_menu'] = 'classes';
        return view('layout/index', $this->data);

    }


    public function edit($id = '')
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('classes', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->class_validation();
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $id = $this->request->getVar('class_id');
                $arrayClass = array(
                    'name' => $this->request->getVar('name'),
                    'name_numeric' => $this->request->getVar('name_numeric'),
                    'branch_id' => $this->application_model->get_branch_id(),
                );
                $builder = $this->db->table('class')->where('id', $id);
                $builder->update($arrayClass);
                $sections = $this->request->getVar('sections');
                foreach ($sections as $section) {
                    $query = $this->db->table('sections_allocation')->getWhere(array('class_id' => $id, 'section_id' => $section));
                    if ($query->getNumRows() == 0) {
                        $this->db->table('sections_allocation')->insert(array('class_id' => $id, 'section_id' => $section));
                    }
                }
                $builder = $this->db->table('sections_allocation')->whereNotIn('section_id', $sections);
                $builder->where('class_id', $id);
                $builder->delete();
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('classes');
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'url' => '', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['class'] = $this->app_lib->getTable('class', array('t.id' => $id), true);
        $this->data['title'] = translate('control_classes');
        $this->data['sub_page'] = 'classes/edit';
        $this->data['main_menu'] = 'classes';
        return view('layout/index', $this->data);
    }


    public function delete($id = '')
    {
    	$builder = $this->db->table('class');
        if (get_permission('classes', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->where('id', $id);
            $builder->delete();
            if ($this->db->affectedRows() > 0) {
                $query = $this->db->table('sections_allocation')->where('class_id', $id);
                $query->delete();
            }
        }
    }


    // class teacher allocation
    public function teacher_allocation()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('assign_class_teacher', 'is_view')) {
            access_denied();
        }
        $branch_id = $this->application_model->get_branch_id();
        $this->data['branch_id'] = $branch_id;
        $this->data['query'] = $this->classes_model->getTeacherAllocation($branch_id);
        $this->data['title'] = translate('assign_class_teacher');
        $this->data['sub_page'] = 'classes/teacher_allocation';
        $this->data['main_menu'] = 'classes';
        return view('layout/index', $this->data);
    }


    public function getAllocationTeacher()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (get_permission('assign_class_teacher', 'is_edit')) {
            $allocation_id = $this->request->getVar('id');
            $this->data['data'] = $this->app_lib->get_table('teacher_allocation', $allocation_id, true);
            return view('classes/tallocation_modalEdit', $this->data);
        }
    }


    public function teacher_allocation_save()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($_POST) {
            if (is_superadmin_loggedin()) {
                $this->validation->setRule('branch_id', translate('branch'), 'required');
            }
            $this->validation->setRule('class_id', translate('class'), 'required');
	            $rules = array(
	            	'section_id' => [
	            		'label' => translate('section'),
	            		'rules' => 'required|unique_sectionID[name]',
	            		'errors' => [
	            			'unique_sectionID' => translate('this_class_teacher_already_assigned'),
	            		],
	            	],

	            	'staff_id' => [
	            		'label' => translate('teacher'),
	            		'rules' => 'required|unique_teacherID[name]',
	            		'errors' => [
	            			'unique_teacherID' => translate('class_teachers_are_already_allocated_for_this_class'),
	            		],
	            	],
	            );
            $this->validation->setRules($rules);
            $this->validation->setRules($rules);
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $post = $this->request->getVar();
                $this->classes_model->teacherAllocationSave($post);
                $url = base_url('classes/teacher_allocation');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }


    public function teacher_allocation_delete($id = '')
    {
    	$builder = $this->db->table('teacher_allocation');
        if (get_permission('assign_class_teacher', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->where('id', $id);
            $builder->delete();
        }
    }





} /*End Class*/