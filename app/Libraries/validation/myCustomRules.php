<?php

namespace App\Libraries\Validation;

use App\Models\ApplicationModel;
use App\Libraries\App_lib;


/**
 * 
 */
class myCustomRules
{
    public $request;
    public $db;
    public $validation;
    public $application_model;
    public $app_lib;

	public function __construct() {

		$this->request = \Config\Services::request();
    	$this->db = \Config\Database::connect();
    	$this->validation = \Config\Services::validation();
        $this->application_model = new ApplicationModel();
        $this->app_lib = new App_lib();
	}


	 /* unique academic sessions name verification is done here */
    public function unique_name(string $year)
    {

    	$builder = $this->db->table('schoolyear');

        $schoolyearID = $this->request->getVar('schoolyear_id');
        if (!empty($schoolyearID)) {
        	// Note whereNotIn should be an array, so we have to enclose $schoolyearID in square bracket else it wont proceed.
            $builder = $this->db->table('schoolyear')->whereNotIn('id', [$schoolyearID]);
        }
        $builder->where(array('school_year' => $year));
        $uniform_row = $builder->get()->getNumRows();
        if ($uniform_row == 0) {
            return true;
        } else {
            // $this->validation->setRule("unique_name", translate('already_taken'));
            return false;
        }
    }

    // check unique name
    public function unique_name_role($name)
    {
        $id = $this->request->getVar('id');
        if (isset($id)) {
            $where = array('name' => $name, 'id != ' => $id);
        } else {
            $where = array('name' => $name);
        }
        $q = $this->db->table('roles')->getWhere($where);
        if ($q->getNumRows() > 0) {
            // $this->form_validation->set_message("unique_name", translate('already_taken'));
            return false;
        } else {
            return true;
        }
    }



    // unique valid username verification is done here
    public function unique_username(string $username, $error)
    {
        $builder = $this->db->table('login_credential');
        if ($this->request->getVar('staff_id')) {
            $staff_id = $this->request->getVar('staff_id');
            $login_id = $this->app_lib->get_credential_id($staff_id);
            $builder = $builder->whereNotIn('id', [$login_id]);
        }
        $builder->where('username', $username);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return false;
        } else {
            return true;
        }
    }


    public function valid_role($id)
    {
        $restrictions = array(1, 6, 7);
        if (in_array($id, $restrictions)) {
            $this->validation->set_message("valid_role", translate('selected_role_restrictions'));
            return false;
        } else {
            return true;
        }
    }


    // unique valid department name verification is done here
    public function unique_department($name)
    {
        $builder = $this->db->table('staff_department');

        $department_id = $this->request->getVar('department_id');
        $branchID = $this->application_model->get_branch_id();
        if (!empty($department_id)) {
            $builder->whereNotIn('id', [$department_id]);
        }

        $builder->where('branch_id', $branchID);
        $builder->where('name', $name);
        $q = $builder->get();
        if ($q->getNumRows() > 0) {
            return false;
        } else {
            return true;
        }
    }


    // unique valid designation name verification is done here
    public function unique_designation($name)
    {
        $builder = $this->db->table('staff_designation');

        $designation_id = $this->request->getVar('designation_id');
        $branchID = $this->application_model->get_branch_id();
        if (!empty($designation_id)) {
            $builder->whereNotIn('id', [$designation_id]);
        }
        $builder->where('name', $name);
        $builder->where('branch_id', $branchID);
        $q = $builder->get();
        if ($q->getNumRows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    /* unique valid branch name verification is done here */
    public function unique_name_branch($name)
    {
        $branch_id = $this->request->getVar('branch_id');

        $builder = $this->db->table('branch');
        if (!empty($branch_id)) {
            $builder->whereNotIn('id', [$branch_id]);
        }
        $builder->where('name', $name);
        $name = $builder->get()->getNumRows();
        if ($name == 0) {
            return true;
        } else {
            return false;
        }
    }





} /*End Class */