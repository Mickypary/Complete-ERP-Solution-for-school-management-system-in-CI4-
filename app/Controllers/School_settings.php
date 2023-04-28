<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\SchoolModel;

/**
 * 
 */
class School_settings extends BaseController
{
	public $school_model;
	public $validation;
    public $session;
	
	function __construct()
	{
		$this->validation = \Config\Services::validation();
		$this->school_model = new SchoolModel();
        $this->session = \Config\Services::session();
	}

	public function index()
	{
		$data = [];

        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }
        
		if (!get_permission('school_settings', 'is_view')) {
            access_denied();
        }

        $branchID = $this->school_model->getBranchID();

        if ($this->request->getMethod() == 'post') {
        	if (!get_permission('school_settings', 'is_edit')) {
                ajax_access_denied();
            }

            $this->validation->setRule('branch_name', translate('branch_name'), 'required');
    		$this->validation->setRule('school_name', translate('school_name'), 'required');
    		$this->validation->setRule('email', translate('email'), 'required|valid_email');
    		$this->validation->setRule('mobileno', translate('mobile_no'), 'required');
    		$this->validation->setRule('currency', translate('currency'), 'required');
    		$this->validation->setRule('currency_symbol', translate('currency_symbol'), 'required');

    		if ($this->validation->withRequest($this->request)->run() == true) {
    			$post = $this->request->getVar();
                $post['branch_id'] = $branchID;
                $this->school_model->branchUpdate($post);
                $message = translate('the_configuration_has_been_updated');
                $array = array('status' => 'success', 'message' => $message);
                // $this->session->setFlashdata('success', $message);
    		}else {
    			$error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'error' => $error);
    		}
    		echo json_encode($array);
            exit();
        }

        $this->data['branchID'] = $branchID;
        $this->data['school'] = $this->school_model->get('branch', array('id' => $branchID), true);
        $this->data['title'] = translate('school_settings');
        $this->data['sub_page'] = 'school_settings/school';
        $this->data['main_menu'] = 'school_m';
        return view('layout/index', $this->data);
	}


} /*End Class*/