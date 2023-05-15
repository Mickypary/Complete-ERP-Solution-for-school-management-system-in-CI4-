<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\ApplicationModel;
use App\Models\EmailModel;
use App\Models\ParentsModel;

/**
 * 
 */
class Parents extends BaseController
{
	public $router;
	public $application_model;
	public $email_model;
	public $parents_model;
	
	function __construct()
	{
		$this->router = \Config\Services::router();
		$this->application_model = new ApplicationModel();
		$this->email_model = new EmailModel();
		$this->parents_model = new ParentsModel();
	}


	public function index()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

    	return redirect()->to(base_url('parents/view'));
    }

    /* parent form validation rules */
    protected function parent_validation()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'trim|required');
        }
        $rules = array(
        	'email' => [
        		'label' => translate('email'),
        		'rules' => 'trim|required|valid_email|unique_username_parent[name]',
        		'errors' => [
        			'unique_username_parent' => translate('already_taken'),
        		],
        	],
        );
        $this->validation->setRule('name', translate('name'), 'trim|required');
        $this->validation->setRule('relation', translate('relation'), 'trim|required');
        $this->validation->setRule('occupation', translate('occupation'), 'trim|required');
        $this->validation->setRule('income', translate('income'), 'trim|numeric');
        $this->validation->setRule('mobileno', translate('mobile_no'), 'trim|required');
        $this->validation->setRules($rules);
        $this->validation->setRule('user_photo', 'Profile Picture', 'mime_in[user_photo,image/png,image/jpeg,image/jpg]|max_size[user_photo,4096]');
        // $this->validation->set_rules('user_photo', 'profile_picture',array(array('handle_upload', array($this->application_model, 'profilePicUpload'))));
        $this->validation->setRule('facebook', 'Facebook', 'valid_url');
        $this->validation->setRule('twitter', 'Twitter', 'valid_url');
        $this->validation->setRule('linkedin', 'Linkedin', 'valid_url');
        if (!isset($_POST['parent_id'])) {
            $this->validation->setRule('password', translate('password'), 'trim|required|min_length[4]');
            $this->validation->setRule('retype_password', translate('retype_password'), 'trim|required|matches[password]');
        }
        // custom fields validation rules
        $class_slug = strtolower(class_basename($this->router->controllerName()));
        $customFields = getCustomFields($class_slug);
        foreach ($customFields as $fields_key => $fields_value) {
            if ($fields_value['required']) {
                $fieldsID   = $fields_value['id'];
                $fieldLabel = $fields_value['field_label'];
                $this->validation->setRule("custom_fields[parents][" . $fieldsID . "]", $fieldLabel, 'trim|required');
            }
        }
    }


    /* parents list user interface  */
    public function view()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        // check access permission
        if (!get_permission('parent', 'is_view')) {
            access_denied();
        }
        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['title'] = translate('parents_list');
        $this->data['sub_page'] = 'parents/view';
        $this->data['main_menu'] = 'parents';
        return view('layout/index', $this->data);  
    }


    /* user all information are prepared and stored in the database here */
    public function add()
    {
        if (!get_permission('parent', 'is_add')) {
            access_denied();
        }
        if ($this->request->getVar('submit') == 'save') {
            $this->parent_validation();
            if ($this->validation->withRequest($this->request)->run() == true) {
                $post = $this->request->getVar();
                //save all employee information in the database
                $parentID = $this->parents_model->saveParent($post);

                // handle custom fields data
                $class_slug = strtolower(class_basename($this->router->controllerName()));
                $customField = $this->request->getVar("custom_fields[$class_slug]");
                if (!empty($customField)) {
                    saveCustomFields($customField, $parentID);
                }

                // send account activate email
                $post['user_role'] = 6;
                $this->email_model->sentStaffRegisteredAccount($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                return redirect()->to(base_url('parents/add'));
            }
        }
        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['title'] = translate('add_parent');
        $this->data['sub_page'] = 'parents/add';
        $this->data['main_menu'] = 'parents';
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


} /*End Class*/

