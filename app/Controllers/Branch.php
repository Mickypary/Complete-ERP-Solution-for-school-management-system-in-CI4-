<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\BranchModel;
use App\Models\ApplicationModel;

/**
 * 
 */
class Branch extends BaseController
{
	public $branch_model;
	public $validation;
	public $application_model;
	public $db;
    public $session;
	
	function __construct()
	{
		// code...
		helper(['form']);
		$this->branch_model = new BranchModel();
		$this->validation = \Config\Services::validation();
		$this->application_model = new ApplicationModel();
		$this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();

        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }
	}

	public function index()
	{
		// $data = [];
		if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }
        
        if (is_superadmin_loggedin()) {
        	if ($this->request->getVar('submit') == 'save') {
        		$this->validation->setRule('branch_name', translate('branch_name'), 'required|unique_name_branch[name]', ["unique_name_branch" => translate('already_taken')]);
        		$this->validation->setRule('school_name', translate('school_name'), 'required');
        		$this->validation->setRule('email', translate('email'), 'required|valid_email');
        		$this->validation->setRule('mobileno', translate('mobile_no'), 'required');
        		$this->validation->setRule('currency', translate('currency'), 'required');
        		$this->validation->setRule('currency_symbol', translate('currency_symbol'), 'required');

        		if ($this->validation->withRequest($this->request)->run() == true) {
                	$post = $this->request->getVar();
                	$response = $this->branch_model->saveBranch($post);
                	if ($response) {
                        set_alert('success', translate('information_has_been_saved_successfully'));
                        $this->session->setFlashdata('success', 'information_has_been_saved_successfully');
                    }
                	return redirect()->to(base_url().'branch');

	            }else {
	                $this->data['validation_error'] = true;
	            }
        	}
        } else {
            session()->set('last_page', current_url());
            return redirect()->to(base_url());
        }


		$this->data['title'] = translate('branch');
        $this->data['sub_page'] = 'branch/add';
        $this->data['sub_title'] = 'branch';
        $this->data['main_menu'] = 'branch';
		return view('layout/index', $this->data);
	}



	/* branch information update here */
    public function edit($id = '')
    {
    	// $data = [];
		if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }


        if (is_superadmin_loggedin()) {
            if ($this->request->getVar('submit') == 'save') {
                $this->validation->setRule('branch_name', translate('branch_name'), 'required|unique_name_branch[name]', ["unique_name_branch" => translate('already_taken')]);
                $this->validation->setRule('school_name', translate('school_name'), 'required');
        		$this->validation->setRule('email', translate('email'), 'required|valid_email');
        		$this->validation->setRule('mobileno', translate('mobile_no'), 'required');
        		$this->validation->setRule('currency', translate('currency'), 'required');
        		$this->validation->setRule('currency_symbol', translate('currency_symbol'), 'required');

                if ($this->validation->withRequest($this->request)->run() == true) {
                	$post = $this->request->getVar();
                	$response = $this->branch_model->saveBranch($post, $id);
                	if ($response) {
                        // $this->session->setFlashdata('success', translate('information_has_been_updated_successfully'));
                        set_alert('success', translate('information_has_been_updated_successfully'));
                    }
                	return redirect()->to(base_url().'branch');

	            }
            }

            $this->data['data'] = $this->application_model->getSingle('branch', $id, true);
            $this->data['title'] = translate('branch');
            $this->data['sub_page'] = 'branch/edit';
            $this->data['sub_title'] = 'Edit Branch';
            $this->data['main_menu'] = 'branch';
            return view('layout/index', $this->data);
        } else {
            session()->set('last_page', current_url());
            return redirect()->to(base_url());
        }
    }


    /* delete information */
    public function delete_data($id = '')
    {
        if (is_superadmin_loggedin()) {
            $this->db->table('branch')->where('id', $id)->delete();
        } else {
            return redirect()->to(base_url());
        }
    }





} // End Class