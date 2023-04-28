<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\App_lib;

use App\Models\ApplicationModel;

/**
 * 
 */
class AuthenticationModel extends Model
{
	public $db;
	public $app_lib;
	public $application_model;
	public $email_model;
	
	function __construct()
	{
		// code...
		$this->db = \Config\Database::connect();
		$this->app_lib = new App_lib();
		$this->application_model = new ApplicationModel();
		$this->email_model = new EmailModel();
	}

	public function login_credential($username, $password)
	{
		$builder = $this->db->table('login_credential');
		$builder->where('username', $username);
		$builder->limit(1);
		$query = $builder->get();
		if ($this->db->affectedRows() == 1) {
			$verify_password = $this->app_lib->verify_password($password, $query->getRow()->password);
			if ($verify_password) {
                return $query->getRow();
            }
		}
		return false;
	}

	public function lose_password($username)
	{
		if (!empty($username)) {
			$builder = $this->db->table('login_credential');
			$builder->where('username', $username);
			$builder->select('*');
			$builder->limit(1);
			$query = $builder->get();

			if (count($query->getResult()) > 0) {
				$login_credential = $query->getRow();
				$getUser = $this->application_model->getUserNameByRoleID($login_credential->role, $login_credential->user_id);
				$key = hash('sha512', $login_credential->role . $login_credential->username . app_generate_hash());
				$query = $this->db->table('reset_password')->where(['login_credential_id' => $login_credential->id])->get();
				if (count($query->getResult()) > 0) {
					$this->db->table('reset_password')->where('login_credential_id',$login_credential->id)->delete();
				}

				$arrayReset = array(
                    'key' => $key,
                    'login_credential_id' => $login_credential->id,
                    'username' => $login_credential->username,
                );
                $this->db->table('reset_password')->insert($arrayReset);

                // send email for forgot password
                $arrayData = array(
                    'role' => $login_credential->role, 
                    'branch_id' => $getUser['branch_id'], 
                    'username' => $login_credential->username, 
                    'name' => $getUser['name'], 
                    'reset_url' => base_url('authentication/pwreset/' . $key), 
                    'email' => $getUser['email'], 
                );
                $this->email_model->sentForgotPassword($arrayData);
                return true;
			}
		}
		return false;
	}










} /*End Class*/