<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\SessionModel;

use App\Libraries\Validation\myCustomRules;

/**
 * 
 */
class Sessions extends BaseController
{
	public $validation;
	public $db;
    public $session;
    public $sess_model;
	
	function __construct()
	{
		helper(['form','url']);
		$this->validation = \Config\Services::validation();
		$this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();

        $this->sess_model = new SessionModel();
	}

	/* form validation rules */
    protected function rules()
    {

        $rules = [
        	'session' => [
        		'rules' => 'required|trim|unique_name[year]',
        		'errors' => [
        			'required' => 'Session field is required',
                    'unique_name' => 'This session already exist',
        		],
        	],
        ];
        return $rules;
    }



    public function index()
    {
    	$this->data['validation'] = null;
    	$data = [];
        if (is_superadmin_loggedin()) {
            if (isset($_POST['save'])) {
                $this->validation->setRules($this->rules());
                if ($this->validation->withRequest($this->request)->run() == true) {
                    $this->save($this->request->getVar());
                    set_alert('success', translate('information_has_been_saved_successfully'));
                    return redirect()->to(base_url() . 'sessions');
                }else {
                	$this->data['validation'] = $this->validator;
                }
            }
            $this->data['title'] = translate('session_settings');
            $this->data['sub_page'] = 'sessions/index';
            $this->data['sub_title'] = 'sessions';
            $this->data['main_menu'] = 'settings';
            return view('layout/index', $this->data);
        } else {
            session()->set('last_page', current_url());
            return redirect()->to(base_url());
        }
    }


    protected function save($data)
    {
        $arrayYear = array(
            'school_year' => $data['session'],
            'created_by' => get_loggedin_user_id(),
        );
        if (!isset($data['schoolyear_id'])) {
            $this->db->table('schoolyear')->insert($arrayYear);
        } else {
            $this->db->table('schoolyear')->where('id', $data['schoolyear_id'])->update($arrayYear);
        }
    }


    public function set_academic($action = '')
    {
        if (is_loggedin()) {
            $this->session->set('set_session_id', $action);
            if (!empty($_SERVER['HTTP_REFERER'])) {
                return redirect()->to($_SERVER['HTTP_REFERER']);
            } else {
                return redirect()->to(base_url('dashboard'));
            }
        } else {
            return redirect()->to(base_url());
        }
    }


    /* academic sessions information are prepared and stored in the database here */
    public function edit()
    {
        if ($this->request->getMethod() == 'post') {
            if (!is_superadmin_loggedin()) {
               ajax_access_denied(); 
            }
            $this->validation->setRules($this->rules());
            if ($this->validation->withRequest($this->request)->run() == true) {
                $this->save($this->request->getVar());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'url' => '', 'error' => $error);
            }
            echo json_encode($array);
        }
    }




    public function delete($id = '')
    {
        if (is_superadmin_loggedin())
        {
            $this->db->table('schoolyear')->where('id', $id)->delete();
        }
    }


    /* unique academic sessions name verification is done here */
    // public function unique_name($year)
    // {
    //     $schoolyearID = $this->request->getVar('schoolyear_id');
    //     if (!empty($schoolyearID)) {
    //         $builder = $this->db->table('schoolyear')->whereNotIn('id', $schoolyearID);
    //     }
    //     $builder->where(array('school_year' => $year));
    //     $uniform_row = $builder->get()->getNumRows();
    //     if ($uniform_row == 0) {
    //         return true;
    //     } else {
    //         $this->validation->setRule("unique_name", translate('already_taken'));
    //         return false;
    //     }
    // }








} // End Class