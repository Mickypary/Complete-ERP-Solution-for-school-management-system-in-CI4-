<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AuthenticationModel;
use App\Models\ApplicationModel;
use App\Libraries\App_lib;
/**
 * 
 */
class Authentication extends BaseController
{
	public $authentication_model;
	public $application_model;
	public $db;
	public $session;
	public $app_lib;
	
	function __construct()
	{
		// code...
		$this->authentication_model = new AuthenticationModel();
		$this->application_model = new ApplicationModel();
		$this->app_lib = new App_lib();
		$this->db = \Config\Database::connect();
		$this->session = \Config\Services::session();
	}

	public function index()
	{
		$data = [];
		$data['errors'] = null;

		if (is_loggedin()) {
            return redirect()->to(base_url().'dashboard');
        }

        if ($this->request->getMethod() == 'post') {
        	
        	$rules = [
				'email' => [
					'rules' => 'required|valid_email',
					'errors' => [
						'required' => 'Email is mandatory',
						'valid_email' => 'Please enter a valid email address'
					],
				],
				'password' => 'required|trim',
			];

			if ($this->validate($rules)) {
				$email = $this->request->getVar('email');
				$password = $this->request->getVar('password');

				// username is okey lets check the password now
                $login_credential = $this->authentication_model->login_credential($email, $password);
                if ($login_credential) {
                	if ($login_credential->active) {
                		if ($login_credential->role == 6) {
                			$userType = 'parent';
                		}elseif ($login_credential->role == 7) {
                			$userType = 'student';
                		}else {
                			$userType = 'staff';
                		}

                		$getUser = $this->application_model->getUserNameByRoleID($login_credential->role, $login_credential->user_id);
                		$getConfig = $this->db->table('global_settings')->where(array('id' => 1))->get()->getRow();
                		// get logger name
                        $sessionData = array(
                            'name' => $getUser['name'],
                            'email' => $getUser['email'],
                            'logger_photo' => $getUser['photo'],
                            'loggedin_branch' => $getUser['branch_id'],
                            'loggedin_id' => $login_credential->id,
                            'loggedin_userid' => $login_credential->user_id,
                            'loggedin_role_id' => $login_credential->role,
                            'loggedin_type' => $userType,
                            'set_lang' => $getConfig->translation,
                            'set_session_id' => $getConfig->session_id,
                            'active' => $login_credential->active,
                            'loggedin' => true,
                        );
                        
                        $this->session->set($sessionData);
                        $status = $this->db->table('login_credential')->where(['id' => $login_credential->id])->update(['last_login' => date('Y-m-d H:i:s')]);
                        // is logged in
                        if ($this->session->has('redirect_url')) {
                            return redirect()->to($this->session->get('redirect_url'));
                        } else {
                            return redirect()->to(base_url().'dashboard');
                        }
                	}else {
                        set_alert('error', translate('inactive_account'));
                        return redirect()->to(base_url().'authentication');
                    }
                }else {
                    set_alert('error', translate('username_or_password_incorrect'));
                    return redirect()->to(base_url().'authentication');
                }
			}else {
				$data['validation'] = $this->validator;
			}
        }
		
		return view('authentication/login', $this->data);

	} // End Index Method


	// forgot password
    public function forgot()
    {
    	$data = [];
    	if (is_loggedin()) {
            return redirect()->to(base_url().'dashboard');
        }
        if ($this->request->getMethod() == 'post') {

            $rules = [
            	'username' => [
            		'rules' => 'required|trim',
            		'error' => [
            			'required' => 'Username field is required',
            		],
            	],
            ];

            if ($this->validate($rules)) {
            	$username = $this->request->getVar('username');
            	$res = $this->authentication_model->lose_password($username);
            	if ($res == true) {
                    $this->session->setFlashdata('reset_res', TRUE);
                    return redirect()->to(base_url().'authentication/forgot');
                } else {
                    $this->session->setFlashdata('reset_res', FALSE);
                    return redirect()->to(base_url().'authentication/forgot');
                }
            }
        }

        return view('authentication/forgot', $this->data);

    } // End Forgot Method

    /* password reset */
    public function pwreset($id = null)
    {
    	$data = [];
    	if (is_loggedin()) {
            return redirect()->to(base_url().'dashboard');
        }

        // $key = $this->request->getGet('key');

        if (!empty($id)) {
        	$data['id'] = $id;

        	$query = $this->db->table('reset_password')->where('key',$id)->get();

        	if (count($query->getResult()) > 0) {

        		if ($_POST) {

        			$rules = [
        				'password' => [
        					'rules' => 'trim|required|min_length[4]|matches[c_password]',
        					'errors' => [
        						'required' => 'Password field cannot be empty',
        					],
        				],
        			];

        			if ($this->validate($rules)) {
        				$password = $this->app_lib->pass_hashed($this->request->getVar('password'));

                        $this->db->table('login_credential')->where('id', $query->getRow()->login_credential_id)->update(['password' => $password]);
                        $this->db->table('reset_password')->where('login_credential_id', $query->getRow()->login_credential_id)->delete();

                        set_alert('success', 'Password Reset Successfully');
                        return redirect()->to(base_url().'authentication');
        			}else {

        				$data['validation'] = $this->validator;
        			}
        		}

        		return view('authentication/pwreset', $data);
        	}else {
        		set_alert('error', 'Invalid Token');
                return redirect()->to(base_url().'authentication');
        	}
        }else {
        		set_alert('error', 'Token Has Expired');
                return redirect()->to(base_url().'authentication');
        }

    } // End pwreset Method




	/* session logout */
    public function logout()
    {
        $this->session->remove('name');
        $this->session->remove('logger_photo');
        $this->session->remove('loggedin_id');
        $this->session->remove('loggedin_userid');
        $this->session->remove('loggedin_type');
        $this->session->remove('set_lang');
        $this->session->remove('set_session_id');
        $this->session->remove('loggedin_branch');
        $this->session->remove('loggedin');
        $this->session->destroy();
        return redirect()->to(base_url().'authentication');
    }











} /*End Class*/