<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\ApplicationModel;
use App\Libraries\App_lib;

/**
 * 
 */
class EmployeeModel extends Model
{
	public $db;
    public $application_model;
    public $request;
    public $app_lib;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
        $this->application_model = new ApplicationModel();
        $this->request = \Config\Services::request();
        $this->app_lib = new App_lib();
	}


    // moderator employee all information
    public function saveEmp($data, $role = null, $id = null)
    {
        $inser_data1 = array(
            'branch_id' => $this->application_model->get_branch_id(),
            'name' => $data['name'],
            'sex' => $data['sex'],
            'religion' => $data['religion'],
            'blood_group' => $data['blood_group'],
            'birthday' => $data["birthday"],
            'mobileno' => $data['mobile_no'],
            'present_address' => $data['present_address'],
            'permanent_address' => $data['permanent_address'],
            'photo' => $this->uploadImage('staff'),
            'designation' => $data['designation_id'],
            'department' => $data['department_id'],
            'joining_date' => date("Y-m-d", strtotime($data['joining_date'])),
            'qualification' => $data['qualification'],
            'email' => $data['email'],
            'facebook_url' => $data['facebook'],
            'linkedin_url' => $data['linkedin'],
            'twitter_url' => $data['twitter'],
        );

        $inser_data2 = array(
            'username' => $data["email"],
            'role' => $data["user_role"],
        );

        if (!isset($data['staff_id']) && empty($data['staff_id'])) {
            // RANDOM STAFF ID GENERATE
            $inser_data1['staff_id'] = substr(app_generate_hash(), 3, 7);
            // SAVE EMPLOYEE INFORMATION IN THE DATABASE
            $this->db->table('staff')->insert($inser_data1);
            $employeeID = $this->db->insertID();

            // SAVE EMPLOYEE LOGIN CREDENTIAL INFORMATION IN THE DATABASE
            $inser_data2['active'] = 1;
            $inser_data2['user_id'] = $employeeID;
            $inser_data2['password'] = $this->app_lib->pass_hashed($data["password"]);
            $this->db->table('login_credential')->insert($inser_data2);

            // SAVE USER BANK INFORMATION IN THE DATABASE
            if (!isset($data['chkskipped'])) {
                $data['staff_id'] = $employeeID;
                $this->bankSave($data);
            }
            return $employeeID;
        } else {
            $builder = $this->db->table('staff');
            // UPDATE ALL INFORMATION IN THE DATABASE
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder->where('id', $data['staff_id']);
            $builder->update($inser_data1);
            // UPDATE LOGIN CREDENTIAL INFORMATION IN THE DATABASE
            $builder = $this->db->table('login_credential')->where('user_id', $data['staff_id']);
            $builder->whereNotIn('role', array(6,7));
            $builder->update($inser_data2);
        }
    }



	// GET SINGLE EMPLOYEE DETAILS
    public function getSingleStaff($id = '')
    {
    	$builder = $this->db->table('staff');
    	$builder->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id,login_credential.active,login_credential.username, roles.name as role');
        
        $builder->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "6" and login_credential.role != "7"', 'inner');
        $builder->join('roles', 'roles.id = login_credential.role', 'left');
        $builder->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $builder->join('staff_department', 'staff_department.id = staff.department', 'left');
        $builder->where('staff.id', $id);
        //
        // $query = $builder->get();
        // print_r($query->getResult());
        // die();
        if (!is_superadmin_loggedin()) {
            $builder->where('staff.branch_id', get_loggedin_branch_id());
        }
        $query = $builder->get();

        if ($this->db->affectedRows() == 0) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return $query->getRowArray();
    }


    // get staff all list
    public function getStaffList($branchID = '', $role_id, $active = 1)
    {
        $builder = $this->db->table('staff')->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name,login_credential.role as role_id, roles.name as role');
        $builder->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != "6" and login_credential.role != "7"', 'inner');
        $builder->join('roles', 'roles.id = login_credential.role', 'left');
        $builder->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $builder->join('staff_department', 'staff_department.id = staff.department', 'left');
        if ($branchID != "") {
            $builder->where('staff.branch_id', $branchID);
        }
        $builder->where('login_credential.role', $role_id);
        $builder->where('login_credential.active', $active);
        $builder->orderBy('staff.id', 'ASC');
        return $builder->get()->getResult();
    }


    public function get_schedule_by_id($id)
    {
        $builder = $this->db->table('timetable_class')->select('timetable_class.*,subject.name as subject_name,class.name as class_name,section.name as section_name');
        $builder->join('subject', 'subject.id = timetable_class.subject_id', 'inner');
        $builder->join('class', 'class.id = timetable_class.class_id', 'inner');
        $builder->join('section', 'section.id = timetable_class.section_id', 'inner');
        $builder->where('timetable_class.teacher_id', $id);  // Staff ID
        $builder->where('timetable_class.session_id', get_session_id());  // Academic session
        return $builder->get();
    }


    public function bankSave($data)
    {
        $builder = $this->db->table('staff_bank_account');
        $inser_data = array(
            'staff_id' => $data['staff_id'],
            'bank_name' => $data['bank_name'],
            'holder_name' => $data['account_name'],
            'bank_branch' => $data['bank_branch'],
            'bank_address' => $data['bank_address'],
            'ifsc_code' => $data['ifsc_code'],
            'account_no' => $data['account_no'],
        );
        if (isset($data['bank_id'])) {
            $builder->where('id', $data['bank_id']);
            $builder->update($inser_data);
        } else {
            $builder->insert($inser_data);
        }  
    }


    public function csvImport($row, $branchID, $userRole, $designationID, $departmentID)
    {
        $inser_data1 = array(
            'name' => $row['Name'],
            'sex' => $row['Gender'],
            'religion' => $row['Religion'],
            'blood_group' => $row['BloodGroup'],
            'birthday' => date("Y-m-d", strtotime($row['DateOfBirth'])),
            'joining_date' => date("Y-m-d", strtotime($row['JoiningDate'])),
            'qualification' => $row['Qualification'],
            'mobileno' => $row['MobileNo'],
            'present_address' => $row['PresentAddress'],
            'permanent_address' => $row['PermanentAddress'],
            'email' => $row['Email'],
            'designation' => $designationID,
            'department' => $departmentID,
            'branch_id' => $branchID,
            'photo' => 'defualt.png',
        );

        $inser_data2 = array(
            'username' => $row["Email"],
            'role' => $userRole,
        );

        // RANDOM STAFF ID GENERATE
        $inser_data1['staff_id'] = substr(app_generate_hash(), 3, 7);
        // SAVE EMPLOYEE INFORMATION IN THE DATABASE
        $this->db->table('staff')->insert($inser_data1);
        $employeeID = $this->db->insertID();

        // SAVE EMPLOYEE LOGIN CREDENTIAL INFORMATION IN THE DATABASE
        $inser_data2['active'] = 1;
        $inser_data2['user_id'] = $employeeID;
        $inser_data2['password'] = $this->app_lib->pass_hashed($row["Password"]);
        $this->db->table('login_credential')->insert($inser_data2);
        return true;
    }



    public function uploadImage($role) {
        $return_photo = 'defualt.png';
        $old_user_photo = $this->request->getVar('old_user_photo');

        $upload_path = ROOTPATH .'public/uploads/images/' . $role . '/';
        if (isset($_FILES["user_photo"]) && !empty($_FILES['user_photo']['name'])) {
            $rules = [
                'avatar' => [
                    'rules' => 'uploaded[user_photo]|max_size[user_photo,1024]|ext_in[avatar,png,jpg,gif]',
                    'errors' => [
                        'ext_in' => 'Invalid file extension',
                    ],
                ],
            ];

            if($this->validate($rules)) {
                $file = $this->request->getFile('user_photo');
                if ($file->isValid() && !$file->hasMoved()) {
                // need to unlink previous photo
                if (!empty($old_user_photo)) {
                    $unlink_path = ROOTPATH .'public/uploads/images/' . $role . '/';
                    if (file_exists($unlink_path . $old_user_photo)) {
                        @unlink($unlink_path . $old_user_photo);
                    }
                }
                $name = $file->getRandomName();

                if ($file->move($upload_path, $name)) {
                    // code...
                    $return_photo = $file->getName();
                }
            }
        }
            
        }else{
            if (!empty($old_user_photo)){
                $return_photo = $old_user_photo;
            }
        }
        return $return_photo;
    } /*End Method*/




} /*End Class*/