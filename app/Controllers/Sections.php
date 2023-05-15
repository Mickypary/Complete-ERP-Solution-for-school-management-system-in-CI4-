<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Libraries\App_lib;
use App\Models\ApplicationModel;

/**
 * 
 */
class Sections extends BaseController
{
	public $app_lib;
    public $validation;
    public $application_model;
	
	function __construct()
	{
        $this->app_lib = new App_lib();
        $this->validation = \Config\Services::validation();
        $this->application_model = new ApplicationModel();
	}


	public function index()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('section', 'is_view')) {
            access_denied();
        }

        $this->data['sectionlist'] = $this->app_lib->getTable('section');
        $this->data['title'] = translate('section_control');
        $this->data['sub_page'] = 'sections/index';
        $this->data['main_menu'] = 'sections';
        return view('layout/index', $this->data);
    }


    public function edit($id = '')
    {
        if (!get_permission('section', 'is_edit')) {
            access_denied();
        }
        $this->data['section'] = $this->app_lib->getTable('section', array('t.id' => $id), true);
        $this->data['title'] = translate('section_control');
        $this->data['sub_page'] = 'sections/edit';
        $this->data['main_menu'] = 'sections';
        return view('layout/index', $this->data);
    }


    public function save()
    {
        $rules = array(
            'name' => [
                'label' => 'Name',
                'rules' => 'trim|required|section_unique_name[name]',
                'errors' => [
                    'section_unique_name' => 'This Section Name already exists.',
                ],
            ],
        );

        if ($_POST) {
            if (is_superadmin_loggedin()) {
                $this->validation->setRule('branch_id', translate('branch'), 'required');
            }
            $this->validation->setRules($rules);
            $this->validation->setRule('capacity', translate('capacity'), 'trim|numeric');
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $arraySection = array(
                    'name' => $this->request->getVar('name'),
                    'capacity' => $this->request->getVar('capacity'),
                    'branch_id' => $this->application_model->get_branch_id(),
                );
                $sectionID = $this->request->getVar('section_id');
                if (empty($sectionID)) {
                    if (get_permission('section', 'is_add')) {
                        $this->db->table('section')->insert($arraySection);
                    }
                    set_alert('success', translate('information_has_been_saved_successfully'));
                } else {
                    if (get_permission('section', 'is_edit')) {
                        $builder = $this->db->table('section');
                        if (!is_superadmin_loggedin()) {
                            $builder->where('branch_id', get_loggedin_branch_id());
                        }
                        $builder->where('id', $sectionID);
                        $builder->update($arraySection);
                    }
                    set_alert('success', translate('information_has_been_updated_successfully'));
                }
                $url = base_url('sections');
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'url' => '', 'error' => $error);

            }
            echo json_encode($array);
        }
    }


    public function delete($id = '')
    {
        $builder = $this->db->table('section');
        if (get_permission('section', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->where('id', $id);
            $builder->delete();
        }
    }




} /* End Class */