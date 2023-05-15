<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\ApplicationModel;
use App\Models\EmailModel;
use App\Models\StudentModel;
use App\Models\SmsModel;
use App\Models\FeesModel;
use App\Models\ExamModel;
use App\Libraries\App_lib;
use App\Libraries\Student_csvimport;
use App\Libraries\QRCode;

/**
 * 
 */
class Student extends BaseController
{
	private $handle = "";
    private $filepath = FALSE;
    private $column_headers = FALSE;
    private $initial_line = 0;
    private $delimiter = ",";
    private $detect_line_endings = FALSE;



	public $application_model;
	public $student_model;
	public $email_model;
	public $router;
	public $sms_model;
	public $fees_model;
	public $exam_model;
	public $app_lib;
	public $csvimport;
	public $session;
    public $ciqrcode;
	
	function __construct()
	{
		$this->application_model = new ApplicationModel();
		$this->student_model = new StudentModel();
		$this->email_model = new EmailModel();
		$this->router = \Config\Services::router();
		$this->sms_model = new SmsModel();
		$this->fees_model = new FeesModel();
		$this->exam_model = new ExamModel();
		$this->app_lib = new App_lib();
		$this->csvimport = new Student_csvimport();
		$this->session = \Config\Services::session();
        $config['cachedir'] = FCPATH . 'public/uploads/qrcode_cache/';
        // $this->ciqrcode = new Ciqrcode($config);
        // $this->ciqrcode = (new QRCode)->render($config);
	}


	public function index()
	{
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

		return redirect()->to(base_url('student/view'));
	}


	/* student form validation rules */
    protected function student_validation()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

    	$rules = array(
    		'roll' => [
    			'label' => translate('roll_number'),
    			'rules' => 'trim|required|numeric|unique_roll[roll]',
    			'errors' => [
    				'unique_roll' => translate('already_taken'),
    			],
    		],
    		'register_no' => [
    			'label' => translate('register_no'),
    			'rules' => 'trim|required|unique_registerid[register]',
    			'errors' => [
    				'unique_registerid' => translate('already_taken'),
    			],
    		],
    		'email' => [
    			'label' => translate('email'),
    			'rules' => 'trim|required|valid_email|student_unique_username[email]',
    			'errors' => [
    				'student_unique_username' => translate('already_taken'),
    			],
    		],
    	);
        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'trim|required');
        }
        $this->validation->setRule('year_id', translate('academic_year'), 'trim|required');
        $this->validation->setRule('register_no', translate('register_no'), 'trim|required');
        $this->validation->setRule('admission_date', translate('admission_date'), 'trim|required');
        $this->validation->setRule('class_id', translate('class'), 'trim|required');
        $this->validation->setRule('section_id', translate('section'), 'trim|required');
        $this->validation->setRule('category_id', translate('category'), 'trim|required');
        $this->validation->setRule('first_name', translate('first_name'), 'trim|required');
        $this->validation->setRule('last_name', translate('last_name'), 'trim|required');
        $this->validation->setRule('mobileno', translate('mobile_no'), 'trim|required');
        $this->validation->setRules($rules);
        $this->validation->setRules($rules);
        $this->validation->setRules($rules);
        $this->validation->setRule('user_photo', 'Profile Picture', 'mime_in[user_photo,image/png,image/jpeg,image/jpg]|max_size[user_photo,4096]');
        // $this->validation->set_rules('user_photo', 'profile_picture',array(array('handle_upload', array($this->application_model, 'profilePicUpload'))));
        if (!isset($_POST['student_id'])) {
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
                $this->validation->setRule("custom_fields[student][" . $fieldsID . "]", $fieldLabel, 'trim|required');
            }
        }
    }

    /* student information delete here */
    public function delete_data($eid = '', $sid = '')
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

    	$builder = $this->db->table('enroll');
        if (get_permission('student', 'is_delete')) {
            // Check student restrictions
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->where('id', $eid);
            $builder->delete();
            if ($this->db->affectedRows() > 0) {
                $this->db->table('student')->where('id', $sid)
                ->delete();
                $this->db->table('login_credential')->where(array('user_id' => $sid, 'role' => 7))
                ->delete();
            }
        }
    }


    // student document details are create here / ajax
    public function document_create()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('student', 'is_edit')) {
            ajax_access_denied();
        }
        $this->validation->setRule('document_title', translate('document_title'), 'trim|required');
        $this->validation->setRule('document_category', translate('document_category'), 'trim|required');
        if (isset($_FILES['document_file']['name']) && empty($_FILES['document_file']['name'])) {
            $this->validation->setRule('document_file', translate('document_file'), 'required');
        }
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $upload_path = ROOTPATH .'public/uploads/attachments/documents/';
            $insert_doc = array(
                'student_id' => $this->request->getVar('patient_id'),
                'title' => $this->request->getVar('document_title'),
                'type' => $this->request->getVar('document_category'),
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

            // uploading file using codeigniter upload library

        if ($this->validate($rules)) {
                $file = $this->request->getFile('document_file');
                if ($file->isValid() && !$file->hasMoved()) {
                    // encrypt the file name before upload
                    $enc_file = $file->getRandomName();
                    // Now upload the file with the original file name since no parameter was passed in the move function
                    if ($file->move($upload_path, $enc_file)) {
                        $insert_doc['file_name'] = $file->getClientName();
                        $insert_doc['enc_name'] = $enc_file;
                        $this->db->table('student_documents')->insert($insert_doc);
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


    // student document details are update here / ajax
    public function document_update()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('student', 'is_edit')) {
            ajax_access_denied();
        }
        // validate inputs
        $this->validation->setRule('document_title', translate('document_title'), 'trim|required');
        $this->validation->setRule('document_category', translate('document_category'), 'trim|required');
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $upload_path = ROOTPATH .'public/uploads/attachments/documents/';
            $document_id = $this->request->getVar('document_id');
            $insert_doc = array(
                'title' => $this->request->getVar('document_title'),
                'type' => $this->request->getVar('document_category'),
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
            $builder = $this->db->table('student_documents')->where('id', $document_id);
            $builder->update($insert_doc);
            echo json_encode(array('status' => 'success'));
            $this->session->setFlashdata('documents_details', 1);
        } else {
            $error = $this->validation->getErrors();
            echo json_encode(array('status' => 'fail', 'error' => $error));
        }
        
    }


    // student document details are delete here
    public function document_delete($id)
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }
        $builder = $this->db->table('student_documents');

        if (get_permission('student', 'is_edit')) {
            $enc_name = $builder->select('enc_name')->where('id', $id)->get()->getRow()->enc_name;
            $file_name = FCPATH . 'public/uploads/attachments/documents/' . $enc_name;
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
        $id = $this->request->getVar('id');
        $builder = $this->db->table('student_documents')->where('id', $id);
        $query = $builder->get();
        $result = $query->getRowArray();
        echo json_encode($result);
    }


    // file downloader
    public function documents_download()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        $encrypt_name = $this->request->getGet('file');
        $file_name = $this->db->table('student_documents')->select('enc_name')->where('enc_name', $encrypt_name)->get()->getRow()->enc_name;
        $file = ROOTPATH.'public/uploads/attachments/documents/'.$file_name;//file location+filename
        return $this->response->download($file, null);//download file
    }




	/* showing student list by class and section */
    public function view()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        // check access permission
        if (!get_permission('student', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['search'])) {
            $classID = $this->request->getVar('class_id');
            $sectionID = $this->request->getVar('section_id');
            $this->data['students'] = $this->application_model->getStudentListByClassSection($classID, $sectionID, $branchID, false, true);
        }

        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('student_list');
        $this->data['main_menu'] = 'student';
        $this->data['sub_page'] = 'student/view';
        $this->data['headerelements'] = array(
            'js' => array(
                'js/student.js'
            ),
        );
        return view('layout/index', $this->data);
    }


    /* student admission information are prepared and stored in the database here */
    public function add()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        // check access permission
        if (!get_permission('student', 'is_add')) {
            access_denied();
        }

        $rules = array(
            'grd_email' => [
                'label' => translate('email'),
                'rules' => 'trim|required|get_valid_guardian_email[name]',
                'errors' => [
                    'get_valid_guardian_email' => translate('username_has_already_been_used'),
                ],
            ],
        );

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['save'])) {
            $this->student_validation();
            if (!isset($_POST['guardian_chk'])) {
                $this->validation->setRule('grd_name', translate('name'), 'trim|required');
                $this->validation->setRule('grd_relation', translate('relation'), 'trim|required');
                $this->validation->setRule('grd_occupation', translate('occupation'), 'trim|required');
                $this->validation->setRule('grd_mobileno', translate('mobile_no'), 'trim|required');
                $this->validation->setRules($rules);
                $this->validation->setRule('grd_password', translate('password'), 'trim|required');
                $this->validation->setRule('grd_retype_password', translate('retype_password'), 'trim|required|matches[grd_password]');
            } else {
                $this->validation->setRule('parent_id', translate('guardian'), 'required');
            }
            if ($this->validation->withRequest($this->request)->run() == true) {
                $post = $this->request->getVar();
                //save all student information in the database file
                $studentID = $this->student_model->Studsave($post);
                //save student enroll information in the database file
                $arrayEnroll = array(
                    'student_id' => $studentID,
                    'class_id' => $post['class_id'],
                    'section_id' => $post['section_id'],
                    'roll' => $post['roll'],
                    'session_id' => $post['year_id'],
                    'branch_id' => $branchID,
                );
                $this->db->table('enroll')->insert($arrayEnroll);

                // handle custom fields data
                $class_slug = strtolower(class_basename($this->router->controllerName()));
                $customField = $this->request->getVar("custom_fields[$class_slug]");
                if (!empty($customField)) {
                    saveCustomFields($customField, $studentID);
                }

                //send account activate sms
                $this->sms_model->send_sms($arrayEnroll, 1);
                //send account activate email
                $emailData = array(
                    'name' => $this->request->getVar('first_name'),
                    'login_email' => $this->request->getVar('email'),
                    'password' => $this->request->getVar('password'),
                    'user_role' => 7,
                    'email' => $this->request->getVar('email'),
                );
                $this->email_model->sentStaffRegisteredAccount($emailData);

                set_alert('success', translate('information_has_been_saved_successfully'));
                return redirect()->to(base_url('student/add'));
            }
        }
        $this->data['branch_id'] = $branchID;
        $this->data['sub_page'] = 'student/add';
        $this->data['main_menu'] = 'admission';
        $this->data['register_id'] = $this->student_model->regSerNumber();
        $this->data['title'] = translate('create_admission');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'js/student.js',
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        return view('layout/index', $this->data);
    }


    /* showing disable authentication student list */
    public function disable_authentication()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        // check access permission
        if (!get_permission('student_disable_authentication', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['search'])) {
            $classID = $this->request->getVar('class_id');
            $sectionID = $this->request->getVar('section_id');
            $this->data['students'] = $this->application_model->getStudentListByClassSection($classID, $sectionID, $branchID, true);
        }

        if (isset($_POST['auth'])) {
            if (!get_permission('student_disable_authentication', 'is_add')) {
                access_denied();
            }
            $stafflist = $this->request->getVar('views_bulk_operations');
            if (isset($stafflist)) {
                foreach ($stafflist as $id) {
                    $builder = $this->db->table('login_credential')->where(array('role' => 7, 'user_id' => $id));
                    $builder->update(array('active' => 1));
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
            } else {
                set_alert('error', 'Please select at least one item');
            }
            return redirect()->to(base_url('student/disable_authentication'));
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('deactivate_account');
        $this->data['sub_page'] = 'student/disable_authentication';
        $this->data['main_menu'] = 'student';
        return view('layout/index', $this->data);
    }


    // add new student category
    public function category()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (isset($_POST['category'])) {
            if (!get_permission('student_category', 'is_add')) {
                access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->validation->setRule('branch_id', translate('branch'), 'required');
            }
            $rules = array(
	            'category_name' => [
	                'label' => translate('category_name'),
	                'rules' => 'trim|required|unique_category[name]',
	                'errors' => [
	                    'unique_category' => translate('already_taken'),
	                ],
	            ],
	        );
            $this->validation->setRules($rules);
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $arrayData = array(
                    'name' => $this->request->getVar('category_name'),
                    'branch_id' => $this->application_model->get_branch_id(),
                );
                $this->db->table('student_category')->insert($arrayData);
                set_alert('success', translate('information_has_been_saved_successfully'));
                return redirect()->to(base_url('student/category'));
            }
        }
        $this->data['title'] = translate('student') . " " . translate('details');
        $this->data['sub_page'] = 'student/category';
        $this->data['main_menu'] = 'admission';
        return view('layout/index', $this->data);
    }


    // update existing student category
    public function category_edit()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (!get_permission('student_category', 'is_edit')) {
            ajax_access_denied();
        }
        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'required');
        }
        $rules = array(
            'category_name' => [
                'label' => translate('category_name'),
                'rules' => 'trim|required|unique_category[name]',
                'errors' => [
                    'unique_category' => translate('already_taken'),
                ],
            ],
        );

        $this->validation->setRules($rules);
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $category_id = $this->request->getVar('category_id');
            $arrayData = array(
                'name' => $this->request->getVar('category_name'),
                'branch_id' => $this->application_model->get_branch_id(),
            );
            $builder = $this->db->table('student_category')->where('id', $category_id);
            $builder->update($arrayData);
            set_alert('success', translate('information_has_been_updated_successfully'));
            $array  = array('status' => 'success');
        } else {
            $error = $this->validation->getErrors();
            $array = array('status' => 'fail','error' => $error);
        }
        echo json_encode($array);
    }

    // delete student category from database
    public function category_delete($id)
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

    	$builder = $this->db->table('student_category');
        if (get_permission('student_category', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->where('id', $id);
            $builder->delete();
        }
    }

    // student category details send by ajax
    public function categoryDetails()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

    	$builder = $this->db->table('student_category');
        if (get_permission('student_category', 'is_edit')) {
            $id = $this->request->getVar('id');
            $builder->where('id', $id);
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $query = $builder->get();
            $result = $query->getRowArray();
            echo json_encode($result);
        }
    }


    /* profile preview and information are updating here */
    public function profile($id = '')
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        // check access permission
        if (!get_permission('student', 'is_edit')) {
            access_denied();
        }

        $getStudent = $this->student_model->getSingleStudent($id);
        if (isset($_POST['update'])) {
            $this->session->setFlashdata('profile_tab', 1);
            $this->data['branch_id'] = $this->application_model->get_branch_id();
            $this->student_validation();
            $this->validation->setRule('parent_id', translate('guardian'), 'required');
            if ($this->validation->withRequest($this->request)->run() == true) {
                $post = $this->request->getVar();
                //save all student information in the database file
                $studentID = $this->student_model->Studsave($post);
                //save student enroll information in the database file
                $arrayEnroll = array(
                    'class_id' => $this->request->getVar('class_id'),
                    'section_id' => $this->request->getVar('section_id'),
                    'roll' => $this->request->getVar('roll'),
                    'session_id' => $this->request->getVar('year_id'),
                    'branch_id' => $this->data['branch_id'],
                );
                $builder = $this->db->table('enroll')->where('student_id', $id);
                $builder->update($arrayEnroll);

                // handle custom fields data
                $class_slug = strtolower(class_basename($this->router->controllerName()));
                $customField = $this->request->getVar("custom_fields[$class_slug]");
                if (!empty($customField)) {
                    saveCustomFields($customField, $id);
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
                return redirect()->to(base_url('student/profile/' . $id));
            }
        }
        $this->data['student'] = $getStudent;
        $this->data['title'] = translate('student_profile');
        $this->data['sub_page'] = 'student/profile';
        $this->data['main_menu'] = 'student';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'js/student.js',
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        return view('layout/index', $this->data);
    }


    public function search()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        // check access permission
        if (!get_permission('student', 'is_view')) {
            access_denied();
        }

        $search_text = $this->request->getVar('search_text');
        $this->data['query'] = $this->student_model->getSearchStudentList(trim($search_text));
        $this->data['title'] = translate('searching_results');
        $this->data['sub_page'] = 'student/search';
        $this->data['main_menu'] = '';
        return view('layout/index', $this->data);
    }


    /* student transfer user interface and information stored in the database here */
    public function transfer()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        // check access permission
        if (!get_permission('student_promotion', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($this->request->getVar()) {
            $this->data['class_id'] = $this->request->getVar('class_id');
            $this->data['section_id'] = $this->request->getVar('section_id');
            $this->data['students'] = $this->application_model->getStudentListByClassSection($this->data['class_id'], $this->data['section_id'], $branchID, false, true);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('student_promotion');
        $this->data['sub_page'] = 'student/transfer';
        $this->data['main_menu'] = 'transfer';
        return view('layout/index', $this->data);
    }


    public function transfersave()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }
        
        // check access permission
        if (!get_permission('student_promotion', 'is_add')) {
            ajax_access_denied();
        }


        if ($_POST) {
            $this->validation->setRule('promote_session_id', translate('promote_to_session'), 'required');
            $this->validation->setRule('promote_class_id', translate('promote_to_class'), 'required');
            $this->validation->setRule('promote_section_id', translate('promote_section_id'), 'required');
            $items = $this->request->getVar('promote');
            foreach ($items as $key => $value) {
                if (isset($value['enroll_id'])) {
                    $rules = array(
                        'promote[' . $key . '][roll]' => [
                            'label' => translate('roll'),
                            'rules' => 'unique_prom_roll[roll]',
                            'errors' => [
                                'unique_prom_roll' => translate('the_%s_is_already_exists.'),
                            ],
                        ],
                    );

                    $this->validation->setRules($rules);
                }
            }
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $promote_session_id = $this->request->getVar('promote_session_id');
                $promote_class_id = $this->request->getVar('promote_class_id');
                $promote_section_id = $this->request->getVar('promote_section_id');
                $branchID = $this->application_model->get_branch_id();
                $promote = $this->request->getVar('promote');
            //     print_r($items);
            // die();
                foreach ($promote as $key => $value) {
                    if (isset($value['enroll_id'])) {
                        $roll = $value['roll'];
                        $enroll_id = $value['enroll_id'];
                        $builder = $this->db->table('enroll');
                        $builder->where('student_id', $value['student_id']);
                        $builder->where('session_id', $promote_session_id);
                        $query = $builder->get();
                        $arrayData = array(
                            'student_id' => $value['student_id'],
                            'class_id' => $promote_class_id,
                            'roll' => $roll,
                            'section_id' => $promote_section_id,
                            'session_id' => $promote_session_id,
                            'branch_id' => $branchID,
                        );

                        if ($query->getNumRows() > 0) {
                            $this->db->table('enroll')->where('id', $enroll_id)
                            ->update($arrayData);
                        } else {
                            $this->db->table('enroll')->insert($arrayData);
                        }
                    }
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('student/transfer');
                $array = array('status' => 'success', 'url' => $url, 'error' => '');
            } else {
                $error = $this->validation->getErrors();
                $array = array('status' => 'fail', 'url' => '', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    /* generate qrcode by id */
    public function create_qrcode($id)
    {
        // $config['cachedir'] = FCPATH . 'public/uploads/qrcode_cache/';
        // $this->load->library('ciqrcode', $config);
        header("Content-Type: image/png");
        $params['data'] = $id;
        $this->ciqrcode->generate($params);
    }


    /* generate student id card with qr code */
    public function generate_idcard()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }
        // check access permission
        if (!get_permission('student_id_card', 'is_view')) {
            access_denied();
        }

        $this->data['branch_id'] = $this->application_model->get_branch_id();
        if ($_POST) {
            $class_id = $this->request->getVar('class_id');
            $section_id = $this->request->getVar('section_id');
            $this->data['query'] = $this->student_model->getStudentList($class_id, $section_id, $this->data['branch_id']);
        }
        $this->data['title'] = translate('id_card_generate');
        $this->data['headerelements'] = array(
            'css' => array(
                'css/idcard.css',
            )
        );
        $this->data['sub_page'] = 'student/idcard';
        $this->data['main_menu'] = 'student';
        return view('layout/index', $this->data);
    }


    /* student password change here */
    public function change_password()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if (get_permission('student', 'is_edit')) {
            if (!isset($_POST['authentication'])) {
                $this->validation->setRule('password', translate('password'), 'trim|required|min_length[4]');
            } else {
                $this->validation->setRule('password', translate('password'), 'trim');
            }
            if ($this->validation->withRequest($this->request)->run() !== false) {
                $studentID = $this->request->getVar('student_id');
                $password = $this->request->getVar('password');
                $builder = $this->db->table('login_credential');
                if (!isset($_POST['authentication'])) {
                    $builder->where('role', 7);
                    $builder->where('user_id', $studentID);
                    $builder->update(array('password' => $this->app_lib->pass_hashed($password)));
                }else{
                    $builder->where('role', 7);
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
    }


    // student quick details
    public function quickDetails()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        $id = $this->request->getVar('student_id');
        $builder = $this->db->table('enroll')->select('student.*,enroll.student_id,enroll.roll,student_category.name as cname')
        ->join('student', 'student.id = enroll.student_id', 'inner')
        ->join('student_category', 'student_category.id = student.category_id', 'inner')
        ->where('enroll.id', $id);
        $row = $builder->get()->getRow();
        $data['photo'] = get_image_url('student', $row->photo);
        $data['full_name'] = $row->first_name . " " . $row->last_name;
        $data['student_category'] = $row->cname;
        $data['register_no'] = $row->register_no;
        $data['roll'] = $row->roll;
        $data['admission_date'] = empty($row->admission_date) ? "N/A" : _d($row->admission_date);
        $data['birthday'] = empty($row->birthday) ? "N/A" : _d($row->birthday);
        $data['blood_group'] = empty($row->blood_group) ? "N/A" : $row->blood_group;
        $data['religion'] = empty($row->religion) ? "N/A" : $row->religion;
        $data['email'] = $row->email;
        $data['mobileno'] = empty($row->mobileno) ? "N/A" : $row->mobileno;
        $data['state'] = empty($row->state) ? "N/A" : $row->state;
        $data['address'] = empty($row->current_address) ? "N/A" : $row->current_address;
        echo json_encode($data);
    }


    function bulk_delete()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        $status = 'success';
        $message = translate('information_deleted');
        if (get_permission('student', 'is_delete')) {
            $arrayID = $this->request->getVar('array_id');
            $this->db->table('enroll')->whereIn('student_id', $arrayID)
            ->delete();
            $this->db->table('student')->whereIn('id', $arrayID)
            ->delete();
            $this->db->table('login_credential')->whereIn('user_id', $arrayID)
            ->where('role', 7)
            ->delete();
        } else {
            $message = translate('access_denied');
            $status = 'error';
        }
        echo json_encode(array('status' => $status, 'message' => $message));
    }


    /* sample csv downloader */
    public function csv_Sampledownloader()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        // $data = file_get_contents('uploads/multi_employee_sample.csv');
        $data = ROOTPATH.'public/uploads/multi_student_sample.csv';//file location+filename
         return $this->response->download($data, null);//download file
    }

    /* validate here, if the check multi admission  email and roll */
    public function csvCheckExistsData($email, $roll, $class_id, $branchID)
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        $array = array();
        $roll_query = $this->db->table('enroll')->getWhere(array(
            'roll' => $roll,
            'class_id' => $class_id,
            'branch_id' => $branchID,
        ));
        if ($roll_query->getNumRows() > 0) {
            $array['status'] = false;
            $array['message'] = "Roll Already Exists.";
            return $array;
        }
        $query = $this->db->table('login_credential');
        $query->where('username', $email);
        if ($query->get()->getNumRows() > 0) {
            $array['status'] = false;
            $array['message'] = "Email Already Exists.";
            return $array;
        }
        $array['status'] = true;
        return $array;
    }



    /* csv file to import student information  and stored in the database here */
    public function csv_import()
    {
        if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        // check access permission
        if (!get_permission('multiple_import', 'is_add')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['save'])) {
            $err_msg = "";
            $i = 0;
            // $this->load->library('csvimport');
            // form validation rules
            if (is_superadmin_loggedin() == true) {
                $this->validation->setRule('branch_id', 'Branch', 'trim|required');
            }
            $this->validation->setRule('class_id', 'Class', 'trim|required');
            $this->validation->setRule('section_id', 'Section', 'trim|required');
            if (isset($_FILES["userfile"]) && empty($_FILES['userfile']['name'])) {
                $this->validation->setRule('userfile', 'CSV File', 'required');
            }
            if ($this->validation->withRequest($this->request)->run() == true) {
                $classID = $this->request->getVar('class_id');
                $sectionID = $this->request->getVar('section_id');
                // $csv_array = $this->csvimport->get_array($_FILES["userfile"]["tmp_name"]);


                if($file = $this->request->getFile('userfile')) {
                if ($file->isValid() && ! $file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH .'public/csvfile', $newName);
                    $filepath = ROOTPATH . 'public/csvfile/'.$newName;
                   
                   // get the csv array from the importCsvToDb Library 
                  $csvArr =  $this->csvimport->importCsvToDb($filepath);

                  // Then Loop
                   $count = 0;
                    foreach($csvArr as $row){

                        if (filter_var($row['StudentEmail'], FILTER_VALIDATE_EMAIL)) {
                        	$r = $this->csvCheckExistsData($row['StudentEmail'], $row['Roll'], $classID, $branchID);
                        	if ($r['status'] == false) {
                                $err_msg .= $row['FirstName'] . ' ' . $row['LastName'] . " - Imported Failed : " . $r['message'] . "<br>";
                            } else {
                                $this->student_model->csvImport($row, $classID, $sectionID, $branchID);
                                // $i++;
                                $count++;
                            }

                        }else {
                                $err_msg .= $row['FirstName'] . ' ' . $row['LastName'] . " - Imported Failed : Invalid Email.<br>";
                        }

                    } // ENd Foreach

                    if ($err_msg != null) {
                        $this->session->setFlashdata('csvimport', $err_msg);
                    }
                    if ($count > 0) {
                        set_alert('success', $count . ' Students Have Been Successfully Added');
                    }
                    return redirect()->to(base_url('student/csv_import'));

                    echo json_encode(array('status' => 'success')); 

                }
            }

            
            }
        }
        $this->data['title'] = translate('multiple_import');
        $this->data['branch_id'] = $branchID;
        $this->data['sub_page'] = 'student/multi_add';
        $this->data['main_menu'] = 'admission';
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






} /*End Class */