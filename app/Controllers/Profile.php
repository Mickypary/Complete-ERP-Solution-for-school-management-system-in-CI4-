<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Libraries\App_lib;

use App\Models\ApplicationModel;
use App\Models\ParentsModel;
use App\Models\StudentModel;
use App\Models\EmployeeModel;
use App\Models\ProfileModel;

/**
 * 
 */
class Profile extends BaseController
{
    public $session;
    public $validation;
    public $db;
    public $app_lib;
    public $application_model;
    public $profile_model;
    public $parents_model;
    public $student_model;
    public $employee_model;

	
	function __construct()
	{
		$this->validation = \Config\Services::validation();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->app_lib = new App_lib();
        $this->application_model = new ApplicationModel();
        $this->parents_model = new ParentsModel();
        $this->student_model = new StudentModel();
        $this->employee_model = new EmployeeModel();
        $this->profile_model = new ProfileModel();
	}

    public function index()
    {
        $data= [];

        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }
        
        $userID = get_loggedin_user_id();
        $loggedinRoleID = loggedin_role_id();
        if ($loggedinRoleID == 6) {
            if ($this->request->getMethod() == 'post') {
                $this->validation->setRule('name', translate('name'), 'trim|required');
                $this->validation->setRule('relation', translate('relation'), 'trim|required');
                $this->validation->setRule('occupation', translate('occupation'), 'trim|required');
                $this->validation->setRule('income', translate('income'), 'trim|numeric');
                $this->validation->setRule('mobileno', translate('mobile_no'), 'trim|required');
                $this->validation->setRule('email', translate('email'), 'trim|required|valid_email');
                $this->validation->setRule('user_photo', 'profile_picture',array(array('handle_upload', array($this->application_model, 'profilePicUpload'))));
                $this->validation->setRule('facebook', 'Facebook', 'valid_url');
                $this->validation->setRule('twitter', 'Twitter', 'valid_url');
                $this->validation->setRule('linkedin', 'Linkedin', 'valid_url');
                $this->validation->setRule('occupation', translate('occupation'), 'trim|required');

                if ($this->validation->withRequest($this->request)->run() == true) {
                    $data = $this->request->getVar();
                    $this->profile_model->parentUpdate($data);
                    set_alert('success', translate('information_has_been_updated_successfully'));
                    return redirect()->to(base_url('profile'));
                }
            }
            $this->data['parent'] = $this->parents_model->getSingleParent($userID);
            $this->data['sub_page'] = 'profile/parent';
        } elseif($loggedinRoleID == 7) {
            if ($this->request->getMethod() == 'post') {
                $this->validation->setRule('first_name', translate('first_name'), 'trim|required');
                $this->validation->setRule('last_name', translate('last_name'), 'trim|required');
                $this->validation->setRule('mobileno', translate('mobile_no'), 'trim|required');
                $this->validation->setRule('email', translate('email'), 'trim|required|valid_email');
                $this->validation->setRule('user_photo', 'profile_picture',array(array('handle_upload', array($this->application_model, 'profilePicUpload'))));

                if ($this->validation->withRequest($this->request)->run() == true) {
                    $data = $this->request->getVar();
                    $this->profile_model->studentUpdate($data);
                    set_alert('success', translate('information_has_been_updated_successfully'));
                    return redirect()->to(base_url('profile'));
                }
            }
            $this->data['student'] = $this->student_model->getSingleStudent($userID);
            $this->data['sub_page'] = 'profile/student';
        } else {
            if ($this->request->getMethod() == 'post') {
                $this->validation->setRule('name', translate('name'), 'trim|required');
                $this->validation->setRule('mobile_no', translate('mobile_no'), 'trim|required');
                $this->validation->setRule('present_address', translate('present_address'), 'trim|required');
                if (is_admin_loggedin()) {
                    $this->validation->setRule('designation_id', translate('designation'), 'trim|required');
                    $this->validation->setRule('department_id', translate('department'), 'trim|required');
                    $this->validation->setRule('joining_date', translate('joining_date'), 'trim|required');
                    $this->validation->setRule('qualification', translate('qualification'), 'trim|required');
                }
                $this->validation->setRule('email', translate('email'), 'trim|required|valid_email');
                $this->validation->setRule('facebook', 'Facebook', 'valid_url');
                $this->validation->setRule('twitter', 'Twitter', 'valid_url');
                $this->validation->setRule('linkedin', 'Linkedin', 'valid_url');
                // $this->validation->setRule('user_photo', 'profile_picture','mime_in[image,image/jpg,image/jpeg,image/gif,image/png]');
                // $this->validation->setRule('user_photo', 'profile_picture',array(array('handle_upload', array($this->application_model, 'profilePicUpload'))));

                if ($this->validation->withRequest($this->request)->run() == true) {
                    $data = $this->request->getPost();
                    $this->profile_model->staffUpdate($data);
                    set_alert('success', translate('information_has_been_updated_successfully'));
                    return redirect()->to(base_url('profile'));
                }
            }
            $this->data['staff'] = $this->employee_model->getSingleStaff($userID);
            $this->data['sub_page'] = 'profile/employee';
        }

        $this->data['title'] = translate('profile') . " " . translate('edit');
        $this->data['main_menu'] = 'profile';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        return view('layout/index', $this->data);
    }



    // when user change his password
    public function password()
    {
        $data = [];
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        $userID = get_loggedin_user_id();
        $loggedinRoleID = loggedin_role_id();

        if ($this->request->getMethod() == 'post') {
            $this->validation->setRule('current_password', 'Current Password', 'trim|required|min_length[4]');
            $this->validation->setRule('new_password', 'New Password', 'trim|required|min_length[4]');
            $this->validation->setRule('confirm_password', 'Confirm Password', 'trim|required|min_length[4]|matches[new_password]');
            $this->form_validation = $this->validation->withRequest($this->request);
            if ($this->form_validation->run() !== false) {
                $old_password = $this->request->getVar('current_password');
                    $check = $this->db->table('login_credential')->where('user_id',$userID)->get()->getRow()->password;
                    if (password_verify($old_password, $check)) {
                        $new_password = $this->request->getVar('new_password');
                        $this->db->table('login_credential')->where('id', get_loggedin_id())->update(['password'=> $this->app_lib->pass_hashed($new_password)]);
                        // $this->session->setFlashdata('success', translate('password_has_been_changed'));
                        set_alert('success', translate('password_has_been_changed'));
                        return redirect()->to(base_url().'profile/password');
                    }else {
                        set_alert('error', translate('failed_to_update_password. incorrect_old_password'));  
                        // $this->session->setFlashdata('error', translate('please_check_current_password'));
                        return redirect()->to(base_url().'profile/password');
                                
                    }

            }else {
                $this->data['validation'] = $this->validation;
            }
        }

        $this->data['sub_page'] = 'profile/password_change';
        $this->data['main_menu'] = 'profile';
        $this->data['title'] = translate('profile');
        return view('layout/index', $this->data);
    }






} // End CLass