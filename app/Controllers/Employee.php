<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\EmployeeModel;
use App\Models\EmailModel;
use App\Models\CrudModel;
use App\Models\ApplicationModel;
use App\Libraries\App_lib;
use App\Libraries\Csvimport;

/**
 * 
 */
class Employee extends BaseController
{
    private $handle = "";
    private $filepath = FALSE;
    private $column_headers = FALSE;
    private $initial_line = 0;
    private $delimiter = ",";
    private $detect_line_endings = FALSE;


	public $validation;
	public $employee_model;
	public $email_model;
	public $crud_model;
    public $application_model;
    public $app_lib;
    public $router;
    public $csvimport;
	
	function __construct()
	{;
		helper(['custom_fields','download']);
        $this->router = \Config\Services::router();
		$this->validation = \Config\Services::validation();
		$this->employee_model = new EmployeeModel();
		$this->email_model = new EmailModel();
		$this->crud_model = new CrudModel();
        $this->application_model = new ApplicationModel();
        $this->app_lib = new App_lib();
        $this->csvimport = new Csvimport();
	}

	public function index()
	{
		return redirect()->to(base_url('dashboard'));
	}

	/* staff form validation rules */
	protected function employee_validation()
	{
		if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'trim|required');
        }
        $this->validation->setRule('name', translate('name'), 'trim|required');
        $this->validation->setRule('mobile_no', translate('mobile_no'), 'trim|required');
        $this->validation->setRule('present_address', translate('present_address'), 'trim|required');
        $this->validation->setRule('designation_id', translate('designation'), 'trim|required');
        $this->validation->setRule('department_id', translate('department'), 'trim|required');
        $this->validation->setRule('joining_date', translate('joining_date'), 'trim|required');
        $this->validation->setRule('qualification', translate('qualification'), 'trim|required');
        $this->validation->setRule('user_role', translate('role'), 'trim|required|valid_role[id]');
        $this->validation->setRule('email', translate('email'), 'trim|required|valid_email|unique_username[username]', array('unique_username' => translate('username_has_already_been_used')));
        if (!isset($_POST['staff_id'])) {
            $this->validation->setRule('password', translate('password'), 'trim|required|min_length[4]');
            $this->validation->setRule('retype_password', translate('retype_password'), 'trim|required|matches[password]');
        }
        $this->validation->setRule('facebook', 'Facebook', 'valid_url');
        $this->validation->setRule('twitter', 'Twitter', 'valid_url');
        $this->validation->setRule('linkedin', 'Linkedin', 'valid_url');
        $this->validation->setRule('user_photo', 'profile_picture', 'uploaded[user_photo]|mime_in[user_photo,user_photo/jpg,user_photo/jpeg,user_photo/gif,user_photo/png]|max_size[user_photo,4096]');
        // $this->validation->setRule('user_photo', 'profile_picture',array(array('handle_upload', array($this->application_model, 'profilePicUpload'))));
        // custom fields validation rules
        $class_slug = strtolower(class_basename($this->router->controllerName()));
        $customFields = getCustomFields($class_slug);
        foreach ($customFields as $fields_key => $fields_value) {
            if ($fields_value['required']) {
                $fieldsID = $fields_value['id'];
                $fieldLabel = $fields_value['field_label'];
                $this->validation->setRule("custom_fields[employee][" . $fieldsID . "]", $fieldLabel, 'trim|required');
            }
        }
	}



	/* getting all employee list */
    public function view($role = 2)
    {
        if (!get_permission('employee', 'is_view') || ($role == 1 || $role == 6 || $role == 7)) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        $this->data['act_role'] = $role;
        $this->data['title'] = translate('employee');
        $this->data['sub_page'] = 'employee/view';
        $this->data['main_menu'] = 'employee';
        $this->data['stafflist'] = $this->employee_model->getStaffList($branchID, $role);
        return view('layout/index', $this->data);
    }


    /* bank form validation rules */
    protected function bank_validation()
    {
        $this->validation->setRule('bank_name', translate('bank_name'), 'trim|required');
        $this->validation->setRule('account_name', translate('account_name'), 'trim|required');
        $this->validation->setRule('bank_branch', translate('bank_branch'), 'trim|required');
        $this->validation->setRule('account_no',  translate('account_no'), 'trim|required');
    }


    /* employees all information are prepared and stored in the database here */
    public function add()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('employee', 'is_add')) {
            access_denied();
        }
        if ($this->request->getMethod() == 'post') {
            $this->employee_validation();
            if (!isset($_POST['chkskipped'])) {
                $this->bank_validation();
            }

            if ($this->validation->withRequest($this->request)->run() !== false) {

                //save all employee information in the database
                $user_id = $this->employee_model->saveEmp($this->request->getVar());

                // handle custom fields data
                // $router = service('router');
                $class_slug = strtolower(class_basename($this->router->controllerName()));
                $customField = $this->request->getVar("custom_fields[$class_slug]");
                if (!empty($customField)) {
                    saveCustomFields($customField, $user_id);
                    // saveCustomFields($customField, $studentID);
                }

                set_alert('success', translate('information_has_been_saved_successfully'));
                //send account activate email
                $this->email_model->sentStaffRegisteredAccount($_POST);
                return redirect()->to(base_url().'employee/view/'. $_POST['user_role']);
            }
        }


        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['title'] = translate('add_employee');
        $this->data['sub_page'] = 'employee/add';
        $this->data['main_menu'] = 'employee';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'js/employee.js',
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        return view('layout/index', $this->data);
    }


    /* profile preview and information are controlled here */
    public function profile($id = '')
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('employee', 'is_edit')) {
            access_denied();
        }
        if ($this->request->getVar('submit') == 'update') {
            $this->employee_validation();
            if ($this->validation->withRequest($this->request)->run() == true) {
                //save all employee information in the database
                $this->employee_model->saveEmp($this->request->getVar());

                // handle custom fields data
                $class_slug = strtolower(class_basename($this->router->controllerName()));
                $customField = $this->request->getVar("custom_fields[$class_slug]");
                if (!empty($customField)) {
                    saveCustomFields($customField, $id);
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
                $this->session->setFlashdata('profile_tab', 1);
                return redirect()->to(base_url('employee/profile/' . $id));
            } else {
                $this->session->setFlashdata('profile_tab', 1);
            }
        }
        $this->data['categorylist'] = $this->app_lib->get_document_category();
        $this->data['staff'] = $this->employee_model->getSingleStaff($id);
        $this->data['title'] = translate('employee_profile');
        $this->data['sub_page'] = 'employee/profile';
        $this->data['main_menu'] = 'employee';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'js/employee.js',
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        return view('layout/index', $this->data);
    }


    // user interface and employees all information are prepared and stored in the database here
    public function delete($id = '')
    {
        $builder = $this->db->table('staff');
        if (!get_permission('employee', 'is_delete')) {
            access_denied();
        }
        // check student restrictions
        if (!is_superadmin_loggedin()) {
            $builder->where('branch_id', get_loggedin_branch_id());
        }
        $builder->delete(array('id' => $id));
        if ($this->db->affectedRows() > 0) {
            $builder = $this->db->table('login_credential');
            $builder->where('user_id', $id);
            $builder->whereNotIn('role', array(1, 6, 7));
            $builder->delete();
        }
    }


    /* department form validation rules */
    protected function department_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'required');
        }
        $this->validation->setRule('department_name', translate('department_name'), 'trim|required|unique_department[name]', ["unique_department" => translate('already_taken')]);
    }


    // employee department user interface and information are controlled here
    public function department()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($_POST) {
            if (!get_permission('department', 'is_add')) {
                access_denied();
            }
            $this->department_validation();
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $arrayDepartment = array(
                    'name' => $this->request->getVar('department_name'), 
                    'branch_id' => $this->application_model->get_branch_id(), 
                );
                $this->db->table('staff_department')->insert($arrayDepartment);
                set_alert('success', translate('information_has_been_saved_successfully'));
                return redirect()->to(base_url().'employee/department');
            }
        }
        $this->data['department'] = $this->app_lib->getTable('staff_department');
        $this->data['title'] = translate('employee');
        $this->data['sub_page'] = 'employee/department';
        $this->data['main_menu'] = 'employee';
        return view('layout/index', $this->data);
    }


    public function department_edit()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('department', 'is_edit')) {
            ajax_access_denied();
        }

        $this->department_validation();
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $builder = $this->db->table('staff_department');
            $arrayDepartment = array(
                'name' => $this->request->getVar('department_name'), 
                'branch_id' => $this->application_model->get_branch_id(), 
            );
            $department_id = $this->request->getVar('department_id');
            $builder->where('id', $department_id);
            $builder->update($arrayDepartment);
            set_alert('success', translate('information_has_been_updated_successfully'));
            $array  = array('status' => 'success');
        } else {

            $error = $this->validation->getErrors();
            $array = array('status' => 'fail','error' => $error);
        }
        echo json_encode($array);
    }

    public function department_delete($id)
    {
        $builder = $this->db->table('staff_department');
        if (!get_permission('department', 'is_delete')) {
            access_denied();
        }
        if (!is_superadmin_loggedin()) {
            $builder->where('branch_id', get_loggedin_branch_id());
        }
        $builder->where('id', $id);
        $builder->delete();
    }


    /* designation form validation rules */
    protected function designation_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'required');
        }
        $this->validation->setRule('designation_name', translate('designation_name'), 'trim|required|unique_designation[name]', ["unique_designation" => translate('already_taken')]);
    }

    // employee designation user interface and information are controlled here
    public function designation()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($_POST) {
            if (!get_permission('designation', 'is_add')) {
                access_denied();
            }
            $this->designation_validation();
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $arrayData = array(
                    'name' => $this->request->getVar('designation_name'), 
                    'branch_id' => $this->application_model->get_branch_id(), 
                );
                $this->db->table('staff_designation')->insert($arrayData);
                set_alert('success', translate('information_has_been_saved_successfully'));
                return redirect()->to(base_url().'employee/designation');
            }
        }
        $this->data['designation'] = $this->app_lib->getTable('staff_designation');
        $this->data['title'] = translate('employee');
        $this->data['sub_page'] = 'employee/designation';
        $this->data['main_menu'] = 'employee';
        return view('layout/index', $this->data);
    }


    public function designation_edit()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('designation', 'is_edit')) {
            ajax_access_denied();
        }
        $this->designation_validation();
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $designation_id = $this->request->getVar('designation_id');
            $arrayData = array(
                'name' => $this->request->getVar('designation_name'), 
                'branch_id' => $this->application_model->get_branch_id(), 
            );
            $builder = $this->db->table('staff_designation');
            $builder->where('id', $designation_id);
            $builder->update($arrayData);
            set_alert('success', translate('information_has_been_updated_successfully'));
            $array  = array('status' => 'success');
        } else {
            $error = $this->validation->getErrors();
            $array = array('status' => 'fail','error' => $error);
        }
        echo json_encode($array);
    }

    public function designation_delete($id)
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('designation', 'is_delete')) {
            access_denied();
        }
        $builder = $this->db->table('staff_designation')->where('id', $id);
        $builder->delete();
    }


    // employee login password change here by admin
    public function change_password()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('employee', 'is_edit')) {
            ajax_access_denied();
        }
        if (!isset($_POST['authentication'])) {
            $this->validation->setRule('password', translate('password'), 'trim|required|min_length[4]');
        } else {
            $this->validation->setRule('password', translate('password'), 'trim');
        }
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $studentID = $this->request->getVar('staff_id');
            $password = $this->request->getVar('password');

            $builder = $this->db->table('login_credential');
            if (!isset($_POST['authentication'])) {
                $builder->whereNotIn('role', array(1, 6, 7));
                $builder->where('user_id', $studentID);
                $builder->update(array('password' => $this->app_lib->pass_hashed($password)));
            }else{
                $builder->whereNotIn('role', array(1, 6, 7));
                $builder->where('user_id', $studentID);
                $builder->update(array('active' => 0));
            }
            set_alert('success', translate('information_has_been_updated_successfully'));
            $array  = array('status' => 'success');
        } else {
            $error = $this->validation->getErrors();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }


    // employee bank details are create here / ajax
    public function bank_account_create()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('employee', 'is_edit')) {
            ajax_access_denied();
        }
        $this->bank_validation();
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $post = $this->request->getVar();
            $this->employee_model->bankSave($post);
            set_alert('success', translate('information_has_been_saved_successfully'));
            $this->session->setFlashdata('bank_tab', 1);
            echo json_encode(array('status' => 'success'));
        } else {
            $error = $this->validation->getErrors();
            echo json_encode(array('status' => 'fail', 'error' => $error));
        }
        
    }


    // employee bank details are update here / ajax
    public function bank_account_update()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('employee', 'is_edit')) {
            ajax_access_denied();
        }
        $this->bank_validation();
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $post = $this->request->getVar();
            $this->employee_model->bankSave($post);
            $this->session->setFlashdata('bank_tab', 1);
            set_alert('success', translate('information_has_been_updated_successfully'));
            echo json_encode(array('status' => 'success'));
        } else {
            $error = $this->validation->getErrors();
            echo json_encode(array('status' => 'fail', 'error' => $error));
        }
    }


    // employee bank details are delete here
    public function bankaccount_delete($id)
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (get_permission('employee', 'is_edit')) {
            $builder = $this->db->table('staff_bank_account')->where('id', $id);
            $builder->delete();
            $this->session->setFlashdata('bank_tab', 1);
        }
    }

    public function bank_details()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        $id = $this->request->getVar('id');
        $builder = $this->db->table('staff_bank_account')->where('id', $id);
        $query = $builder->get();
        $result = $query->getRowArray();
        echo json_encode($result);
    }

    protected function document_validation()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        $this->validation->setRule('document_title', translate('document_title'), 'trim|required');
        $this->validation->setRule('document_category', translate('document_category'), 'trim|required');
        if ((string)$this->uri->getSegment(3) != 'document_update') {
            if (isset($_FILES['document_file']['name']) && empty($_FILES['document_file']['name'])) {
                $this->validation->setRule('document_file', translate('document_file'), 'required');
            }
        }
    }

    // employee document details are create here / ajax
    public function document_create()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('employee', 'is_edit')) {
            ajax_access_denied();
        }
        $this->document_validation();
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $upload_path = ROOTPATH .'public/uploads/attachments/documents/';
            $insert_doc = array(
                'staff_id' => $this->request->getVar('staff_id'),
                'title' => $this->request->getVar('document_title'),
                'category_id' => $this->request->getVar('document_category'),
                'remarks' => $this->request->getVar('remarks'),
            );

            $rules = [
                'document_file' => [
                    'rules' => 'uploaded[document_file]|max_size[document_file,2048]|ext_in[document_file,png,jpg,gif,pdf,docx,csv,txt]',
                    'errors' => [
                        'ext_in' => 'Invalid file extension',
                    ],
                ],
            ];
            if ($this->validate($rules)) {
                $file = $this->request->getFile('document_file');
                if ($file->isValid() && !$file->hasMoved()) {
                    // encrypt the file name before upload
                    $enc_file = $file->getRandomName();
                    // Now upload the file with the original file name since no parameter was passed in the move function
                    if ($file->move($upload_path, $enc_file)) {
                        $insert_doc['file_name'] = $file->getClientName();
                        $insert_doc['enc_name'] = $enc_file;
                        $this->db->table('staff_documents')->insert($insert_doc);
                        set_alert('success', translate('information_has_been_saved_successfully'));
                    } else {
                        set_alert('error', strip_tags($this->validation->getErrors()));
                    }
                    $this->session->setFlashdata('documents_details', 1);
                    echo json_encode(array('status' => 'success'));
                }
            }
        } else {
            $error = $this->validation->getErrors();
            echo json_encode(array('status' => 'fail', 'error' => $error));
        }
    }


    // employee document details are update here / ajax
    public function document_update()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('employee', 'is_edit')) {
            ajax_access_denied();
        }
        // validate inputs
        $this->document_validation();
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $upload_path = ROOTPATH .'public/uploads/attachments/documents/';
            $document_id = $this->request->getVar('document_id');
            $insert_doc = array(
                'title' => $this->request->getVar('document_title'),
                'category_id' => $this->request->getVar('document_category'),
                'remarks' => $this->request->getVar('remarks'),
            );
            if (isset($_FILES["document_file"]) && !empty($_FILES['document_file']['name'])) {
                $rules = [
                    'document_file' => [
                        'rules' => 'uploaded[document_file]|max_size[document_file,2048]|ext_in[document_file,png,jpg,gif,pdf,docx,txt,csv]',
                        'errors' => [
                            'ext_in' => 'Invalid file extension',
                        ],
                    ],
                ];
                if($this->validate($rules)) {
                $file = $this->request->getFile('document_file');
                    if ($file->isValid() && !$file->hasMoved()) {
                        // encrypt the file before upload
                        $enc_file = $file->getRandomName();
                        // now move the file with the original name since no paramter image name was passed
                        if ($file->move($upload_path, $enc_file)) {
                            $exist_file_name = $this->request->getVar('exist_file_name');
                            $exist_file_path = ROOTPATH . 'public/uploads/attachments/documents/' . $exist_file_name;
                            // need to unlink previous photo
                            if (file_exists($exist_file_path)) {
                                @unlink($exist_file_path);
                            }
                            $insert_doc['file_name'] = $file->getClientName();
                            $insert_doc['enc_name'] = $enc_file;
                        }else {
                            set_alert('error', strip_tags($this->validation->getErrors()));
                        }
                    }
                }
            }
            set_alert('success', translate('information_has_been_updated_successfully'));
            $builder = $this->db->table('staff_documents')->where('id', $document_id);
            $builder->update($insert_doc);
            echo json_encode(array('status' => 'success'));
            $this->session->setFlashdata('documents_details', 1);
        } else {
            $error = $this->validation->getErrors();
            echo json_encode(array('status' => 'fail', 'error' => $error));
        }
        
    }


    // employee document details are delete here
    public function document_delete($id)
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        $builder = $this->db->table('staff_documents');
        if (get_permission('employee', 'is_edit')) {
            $enc_name = $builder->select('enc_name')->where('id', $id)->get()->getRow()->enc_name;
            $file_name = ROOTPATH . 'public/uploads/attachments/documents/' . $enc_name;
            if (file_exists($file_name)) {
                unlink($file_name);
            }
            $builder->where('id', $id);
            $builder->delete();
            $this->session->setFlashdata('documents_details', 1);
        }
    }

    public function document_details()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        $id = $this->request->getVar('id');
        $builder = $this->db->table('staff_documents')->where('id', $id);
        $query = $builder->get();
        $result = $query->getRowArray();
        echo json_encode($result);
    }

    /* file downloader */
    public function documents_download()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        $encrypt_name = $this->request->getGet('file');
        $file_name = $this->db->table('staff_documents')->select('enc_name')->where('enc_name', $encrypt_name)->get()->getRow()->enc_name;
        $file = ROOTPATH.'public/uploads/attachments/documents/'.$file_name;//file location+filename
        return $this->response->download($file, null);//download file
    }

    // showing disable authentication student list
    public function disable_authentication()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        // check access permission
        if (!get_permission('employee_disable_authentication', 'is_view')) {
            access_denied();
        }

        if (isset($_POST['search'])) {
            $branchID = $this->application_model->get_branch_id();
            $role = $this->request->getVar('staff_role');
            $this->data['stafflist'] = $this->employee_model->getStaffList($branchID, $role, 0);
        }

        if (isset($_POST['auth'])) {
            if (!get_permission('employee_disable_authentication', 'is_add')) {
                access_denied();
            }
            $stafflist = $this->request->getVar('views_bulk_operations');
            if (isset($stafflist)) {
                foreach ($stafflist as $id) {
                    $builder = $this->db->table('login_credential')->where('user_id', $id);
                    $builder->whereNotIn('role', array(1, 6, 7));
                    $builder->update(array('active' => 1));
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
            } else {
                set_alert('error', 'Please select at least one item');
            }
            return redirect()->to(base_url('employee/disable_authentication'));
        }
        $this->data['title'] = translate('deactivate_account');
        $this->data['sub_page'] = 'employee/disable_authentication';
        $this->data['main_menu'] = 'employee';
        return view('layout/index', $this->data);
    }


    /* employee csv importer */
    public function csv_import()
    {
        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'trim|required');
        }
        $this->validation->setRule('user_role', translate('role'), 'trim|required');
        $this->validation->setRule('designation_id', translate('designation'), 'trim|required');
        $this->validation->setRule('department_id', translate('department'), 'trim|required');
        if (isset($_FILES['userfile']['name']) && empty($_FILES['userfile']['name'])) {
            $this->validation->setRule('userfile', "Select CSV File", 'required');
        }
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $branchID = $this->application_model->get_branch_id();
            $userRole = $this->request->getVar('user_role');
            $designationID = $this->request->getVar('designation_id');
            $departmentID = $this->request->getVar('department_id');
            $err_msg = "";
            // $i = 0;

            if($file = $this->request->getFile('userfile')) {
                if ($file->isValid() && ! $file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH .'public/csvfile', $newName);
                    
                    $this->csvimport->importCsvToDb($branchID,$userRole,$designationID,$departmentID,$newName);
                }
            }
        }else {
            $error = $this->validation->getErrors();
            echo json_encode(array('status' => 'fail', 'error' => $error));
        }

    }





    /* employee csv importer */
    // public function csv_import()
    // {
    //     if (is_superadmin_loggedin()) {
    //         $this->validation->setRule('branch_id', translate('branch'), 'trim|required');
    //     }
    //     $this->validation->setRule('user_role', translate('role'), 'trim|required');
    //     $this->validation->setRule('designation_id', translate('designation'), 'trim|required');
    //     $this->validation->setRule('department_id', translate('department'), 'trim|required');
    //     if (isset($_FILES['userfile']['name']) && empty($_FILES['userfile']['name'])) {
    //         $this->validation->setRule('userfile', "Select CSV File", 'required');
    //     }
    //     if ($this->validation->withRequest($this->request)->run() !== false) {
    //         $branchID = $this->application_model->get_branch_id();
    //         $userRole = $this->request->getVar('user_role');
    //         $designationID = $this->request->getVar('designation_id');
    //         $departmentID = $this->request->getVar('department_id');
    //         $err_msg = "";
    //         // $i = 0;

    //         if($file = $this->request->getFile('userfile')) {
    //             if ($file->isValid() && ! $file->hasMoved()) {
    //                 $newName = $file->getRandomName();
    //                 $file->move(ROOTPATH .'public/csvfile', $newName);
    //                 $file = fopen(ROOTPATH ."public/csvfile/".$newName,"r");
    //                 $i = 0;
    //                 $numberOfFields = 12;
    //                 $csvArr = array();
                    
    //                 while (($filedata = fgetcsv($file, 0, $this->delimiter)) !== FALSE) {
    //                     if ($filedata[0] == NULL)
    //                     continue;
    //                     $num = count($filedata);
    //                     if($i > 0){ 
    //                         $csvArr[$i]['Name'] = $filedata[0];
    //                         $csvArr[$i]['Gender'] = $filedata[1];
    //                         $csvArr[$i]['Religion'] = $filedata[2];
    //                         $csvArr[$i]['BloodGroup'] = $filedata[3];
    //                         $csvArr[$i]['DateOfBirth'] = $filedata[4];
    //                         $csvArr[$i]['JoiningDate'] = $filedata[5];
    //                         $csvArr[$i]['Qualification'] = $filedata[6];
    //                         $csvArr[$i]['MobileNo'] = $filedata[7];
    //                         $csvArr[$i]['PresentAddress'] = $filedata[8];
    //                         $csvArr[$i]['PermanentAddress'] = $filedata[9];
    //                         $csvArr[$i]['Email'] = $filedata[10];
    //                         $csvArr[$i]['Password'] = $filedata[11];
    //                         // echo "<pre>";
    //                         // echo json_encode($filedata);
    //                         // echo json_encode($filedata[0]);
    //                     }
    //                     $i++;
    //                 }
    //                 fclose($file);
    //                 $count = 0;
    //                 foreach($csvArr as $row){

    //                     if (filter_var($row['Email'], FILTER_VALIDATE_EMAIL)) {
    //                         // verify existing username
    //                         $builder = $this->db->table('login_credential')->where('username', $row['Email']);
    //                         $query = $builder->get();
    //                         if ($query->getNumRows() > 0) {
    //                             $err_msg .= $row['Name'] . " - Imported Failed : Email Already Exists.<br>";
    //                         } else {
    //                             // save all employee information in the database
    //                             $this->employee_model->csvImport($row, $branchID, $userRole, $designationID, $departmentID);
    //                             // $i++;
    //                             $count++;
    //                         }
    //                     }else {
    //                         $err_msg .= $row['Name'] . " - Imported Failed : Invalid Email.<br>";
    //                     } /*End Validate Email*/

    //                 } // ENd Foreach

    //                 if ($err_msg != null) {
    //                     $msgRes = $count . ' Students Have Been Successfully Added. <br>';
    //                     $msgRes .= $err_msg;
    //                     echo json_encode(array('status' => 'errlist', 'errMsg' => $msgRes));
    //                     exit();
    //                 }
    //                 if ($count > 0) {
    //                     set_alert('success', $count . ' Students Have Been Successfully Added');
    //                 }

    //                 echo json_encode(array('status' => 'success'));
    //             }
    //         }
    //     }else {
    //         $error = $this->validation->getErrors();
    //         echo json_encode(array('status' => 'fail', 'error' => $error));
    //     }

    // } /*End Csv Import Method*/




    /* sample csv downloader */
    public function csv_Sampledownloader()
    {
        // $data = file_get_contents('uploads/multi_employee_sample.csv');
        $data = ROOTPATH.'public/uploads/multi_employee_sample.csv';//file location+filename
         return $this->response->download($data, null);//download file
    }





} /*End Class*/