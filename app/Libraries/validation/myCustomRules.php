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
        $currentURL = current_url();
        $this->uri = new \CodeIgniter\HTTP\URI($currentURL);
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


    // validate here, if the check sectio name
    public function section_unique_name($name)
    {
        $branchID = $this->application_model->get_branch_id();
        $sectionID = $this->request->getVar('section_id');
        $builder = $this->db->table('section');
        if (!empty($sectionID)) {
            $builder->whereNotIn('id', [$sectionID]);
        }
        $builder->where(array('name' => $name, 'branch_id' => $branchID));
        $uniform_row = $builder->get()->getNumRows();
        if ($uniform_row == 0) {
            return true;
        } else {
            return false;
        }
    }


    // validate here, if the check teacher allocated for this class
    public function unique_sectionID($sectionID)
    {
        $builder = $this->db->table('teacher_allocation');

        if (!empty($sectionID)) {
            $classID = $this->request->getVar('class_id');
            $allocationID = $this->request->getVar('allocation_id');
            if (!empty($allocationID)) {
                $builder->whereNotIn('id', [$allocationID]);
            }
            $builder->where('class_id', $classID);
            $builder->where('section_id', $sectionID);
            $builder->where('session_id', get_session_id());
            $query = $builder->get();
            if ($query->getNumRows() > 0) {
                return false;
            } else {
                return true;
            }
        }
    }


    // validate here, if the check teacher allocated for this class
    public function unique_teacherID($teacher_id)
    {
        $builder = $this->db->table('teacher_allocation');

        if (!empty($teacher_id)) {
            $classID = $this->request->getVar('class_id');
            $sectionID = $this->request->getVar('section_id');
            $allocationID = $this->request->getVar('allocation_id');
            if (!empty($allocationID)) {
                $builder->whereNotIn('id', [$allocationID]);
            }
            $builder->where('teacher_id', $teacher_id);
            $builder->where('class_id', $classID);
            $builder->where('section_id', $sectionID);
            $builder->where('session_id', get_session_id());
            $query = $builder->get();
            if ($query->getNumRows() > 0) {
                return false;
            } else {
                return true;
            }
        }
    }


    /* validate here, if the check student category name */
    public function unique_category($name)
    {
        $builder = $this->db->table('student_category');
        $branchID = $this->application_model->get_branch_id();
        $category_id = $this->request->getVar('category_id');
        if (!empty($category_id)) {
            $builder->whereNotIn('id', [$category_id]);
        }
        $builder->where(array('name' => $name, 'branch_id' => $branchID));
        $uniform_row = $builder->get()->getNumRows();
        if ($uniform_row == 0) {
            return true;
        } else {
            // $this->form_validation->set_message("unique_category", translate('already_taken'));
            return false;
        }
    }


    /* unique valid guardian email address verification is done here */
    public function get_valid_guardian_email($email)
    {
        $builder = $this->db->table('login_credential')->where('username', $email);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return false;
        } else {
            return true;
        }
    }


    /* unique valid student roll verification is done here */
    public function unique_roll($roll)
    {
        $builder = $this->db->table('enroll');
        $branchID = $this->application_model->get_branch_id();
        $classID = $this->request->getVar('class_id');
        if ((string)$this->uri->getSegment(4)) {
            $builder->whereNotIn('student_id', [(string)$this->uri->getSegment(4)]);
        }
        $builder->where(array('roll' => $roll, 'class_id' => $classID, 'branch_id' => $branchID));
        $q = $builder->get()->getNumRows();
        if ($q == 0) {
            return true;
        } else {
            return false;
        }
    }


    /* unique valid register ID verification is done here */
    public function unique_registerid($register)
    {
        $builder = $this->db->table('student');
        $branchID = $this->application_model->get_branch_id();
        if ((string)$this->uri->getSegment(4)) {
            $builder->whereNotIn('id', [(string)$this->uri->getSegment(4)]);
        }
        $builder->where('register_no', $register);
        $query = $builder->get()->getNumRows();
        if ($query == 0) {
            return true;
        } else {
            return false;
        }
    }


    // unique valid username verification is done here
    public function student_unique_username($email)
    {
        $builder = $this->db->table('login_credential');
        if ($this->request->getVar('student_id')) {
            $student_id = $this->request->getVar('student_id');
            $login_id = $this->app_lib->get_credential_id($student_id, 'student');
            $builder->whereNotIn('id', [$login_id]);
        }
        $builder->where('username', $email);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    // unique valid username verification is done here
    public function unique_username_parent($username)
    {
        $builder = $this->db->table('login_credential');
        $parent_id = $this->request->getVar('parent_id');
        if (!empty($parent_id)) {
            $login_id = $this->app_lib->get_credential_id($parent_id, 'parent');
            $builder->whereNotIn('id', [$login_id]);
        }
        $builder->where('username', $username);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            // $this->form_validation->set_message("unique_username", translate('already_taken'));
            return false;
        } else {
            return true;
        }
    }


    public function unique_prom_roll($roll)
    {
        if ($roll) {
            $promote_session_id = $this->request->getVar('promote_session_id');
            $promote_class_id = $this->request->getVar('promote_class_id');
            $branchID = $this->application_model->get_branch_id();
            $unique_roll = $this->db->table('enroll')->select('id')->where(array(
                'roll' => $roll,
                'class_id' => $promote_class_id,
                'session_id' => $promote_session_id,
                'branch_id' => $branchID,
            ))->get()->getNumRows();
            if ($unique_roll == 0) {
                return true;
            } else {
                $this->form_validation->set_message('unique_prom_roll', "The %s is already exists.");
                return false;
            }
        }
    }





} /*End Class */