<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\ApplicationModel;
use App\Libraries\App_lib;

/**
 * 
 */
class StudentModel extends Model
{
	public $db;
    public $request;
    public $application_model;
    public $app_lib;

    public $table      = 'parent';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
     protected $returnType     = 'array';
	
	function __construct()
	{
        parent::__construct();
        
		$this->db = \Config\Database::connect();
        $this->request = \Config\Services::request();
        $this->application_model = new ApplicationModel();
        $this->app_lib = new App_lib();
	}


    // moderator student all information
    public function Studsave($data)
    {
        $hostelID = empty($data['hostel_id']) ? 0 : $data['hostel_id'];
        $roomID = empty($data['room_id']) ? 0 : $data['room_id'];
        $previous_details = array(
            'school_name' => $data['school_name'],
            'qualification' => $data['qualification'],
            'remarks' => $data['previous_remarks'],
        );
        $inser_data1 = array(
            'register_no' => $data['register_no'],
            'admission_date' => date("Y-m-d", strtotime($data['admission_date'])),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'birthday' => date("Y-m-d", strtotime($data['birthday'])),
            'religion' => $data['religion'],
            'caste' => $data['caste'],
            'blood_group' => $data['blood_group'],
            'mother_tongue' => $data['mother_tongue'],
            'current_address' => $data['current_address'],
            'permanent_address' => $data['permanent_address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'mobileno' => $data['mobileno'],
            'category_id' => $data['category_id'],
            'email' => $data['email'],
            'parent_id' => $data['parent_id'],
            'route_id' => $data['route_id'],
            'vehicle_id' => $data['vehicle_id'],
            'hostel_id' => $hostelID,
            'room_id' => $roomID,
            'previous_details' => json_encode($previous_details),
            'photo' => $this->uploadImage('student'),
        );
        $inser_data2 = array('username' => $data["email"]);

        // moderator guardian all information
        if (!isset($data['student_id']) && empty($data['student_id'])) {
            if (!isset($data['guardian_chk'])) {
                // add new guardian all information in db
                $arrayParent = array(
                    'name' => $data['grd_name'],
                    'relation' => $data['grd_relation'],
                    'father_name' => $data['father_name'],
                    'mother_name' => $data['mother_name'],
                    'occupation' => $data['grd_occupation'],
                    'income' => $data['grd_income'],
                    'education' => $data['grd_education'],
                    'email' => $data['grd_email'],
                    'mobileno' => $data['grd_mobileno'],
                    'address' => $data['grd_address'],
                    'city' => $data['grd_city'],
                    'state' => $data['grd_state'],
                    'branch_id' => $this->application_model->get_branch_id(),
                    'photo' => 'defualt.png',
                );
                $builder = $this->db->table('parent')->insert($arrayParent);
                $parentID = $this->db->insertID();
                $parent_credential = array(
                    'username' => $data["grd_email"],
                    'role' => 6,
                    'user_id' => $parentID,
                    'password' => $this->app_lib->pass_hashed($data["grd_password"]),
                );
                $this->db->table('login_credential')->insert($parent_credential);
            } else {
                $parentID = $data['parent_id'];
            }

            $inser_data1['parent_id'] = $parentID;
            // insert student all information in the database
            $builder = $this->db->table('student')->insert($inser_data1);
            $student_id = $this->db->insertID();
            // save student login credential information in the database
            $inser_data2['role'] = 7;
            $inser_data2['user_id'] = $student_id;
            $inser_data2['password'] = $this->app_lib->pass_hashed($data["password"]);
            $this->db->table('login_credential')->insert($inser_data2);
            return $student_id;
        } else {
            // update student all information in the database
            $inser_data1['parent_id'] = $data['parent_id'];
            $builder = $this->db->table('student')->where('id', $data['student_id']);
            $builder->update($inser_data1);

            // update login credential information in the database
            $builder = $this->db->table('login_credential')->where('user_id', $data['student_id']);
            $builder->where('role', 7);
            $builder->update($inser_data2);
        }
    }




	public function getSingleStudent($id = '')
    {
        $builder = $this->db->table('enroll as e')->select('s.*,l.active,e.class_id,e.section_id,e.id as enrollid,e.roll,e.branch_id,e.session_id,c.name as class_name,se.name as section_name,sc.name as category_name')
        ->join('student as s', 'e.student_id = s.id', 'left')
        ->join('login_credential as l', 'l.user_id = s.id and l.role = 7', 'inner')
        ->join('class as c', 'e.class_id = c.id', 'left')
        ->join('section as se', 'e.section_id = se.id', 'left')
        ->join('student_category as sc', 's.category_id=sc.id', 'left')
        ->where('s.id', $id)
        ->where('e.session_id', get_session_id());
        if (!is_superadmin_loggedin()) {
            $builder->where('e.branch_id', get_loggedin_branch_id());
        }
        $query = $builder->get();
        if ($this->db->affectedRows() == 0) {
            show_404();
        }
        return $query->getRowArray();
    }


    public function regSerNumber()
    {
        $schoolyearID = get_session_id();
        $regno = $this->db->table('schoolyear')->where('id', $schoolyearID)->get()->getRow()->school_year;
        $regno = str_replace('-', '/', $regno);
        $regno = strstr($regno,"/", true);

        $prefix = '';
        $config = $this->db->table('global_settings')->select('institution_code,reg_prefix')->where(array('id' => 1))->get()->getRow();
        if ($config->reg_prefix == 'on') {
            $prefix = $config->institution_code;
        }
        $result = $this->db->table('student')->select("max(id) as id")->get()->getRowArray();
        $id = $result["id"];
        if (!empty($id)) {
            // preg_replace(pattern, replacement, subject)
            $maxNum = str_pad($id + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $maxNum = '00001';
        }
        return ($prefix . $regno .$maxNum);
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


    public function getFeeProgress($id)
    {
        $builder = $this->db->table('fee_allocation as a')->select('IFNULL(SUM(gd.amount), 0) as totalfees,IFNULL(SUM(p.amount), 0) as totalpay,IFNULL(SUM(p.discount),0) as totaldiscount')
        ->join('fee_groups_details as gd', 'gd.fee_groups_id = a.group_id', 'inner')
        ->join('fee_payment_history as p', 'p.allocation_id = a.id and p.type_id = gd.fee_type_id', 'left')
        ->where('a.student_id', $id)
        ->where('a.session_id', get_session_id());
        $r = $builder->get()->getRowArray();
        $total_amount = floatval($r['totalfees']);
        $total_paid = floatval($r['totalpay'] + $r['totaldiscount']);
        if ($total_paid != 0) {
            $percentage = ($total_paid / $total_amount) * 100;
            return number_format($percentage);
        } else {
            return 0;
        }
    }


    public function csvImport($row, $classID, $sectionID, $branchID)
    {
        $getParent = $this->db->table('parent')->select('id')->where(array('branch_id' => $branchID, 'email' => $row['GuardianEmail']))->get()->getRowArray();
        if (count((array)$getParent)) {
            $parentID = $getParent['id'];
        } else {
            // add new guardian all information in db
            $arrayParent = array(
                'name' => $row['GuardianName'],
                'relation' => $row['GuardianRelation'],
                'father_name' => $row['FatherName'],
                'mother_name' => $row['MotherName'],
                'occupation' => $row['GuardianOccupation'],
                'mobileno' => $row['GuardianMobileNo'],
                'address' => $row['GuardianAddress'],
                'email' => $row['GuardianEmail'],
                'branch_id' => $branchID,
                'photo' => 'defualt.png',
            );
            $this->db->table('parent')->insert($arrayParent);
            $parentID = $this->db->insertID();
            $parent_credential = array(
                'username' => $row["GuardianEmail"],
                'role' => 6,
                'user_id' => $parentID,
                'password' => $this->app_lib->pass_hashed($row["GuardianPassword"]),
            );
            $this->db->table('login_credential')->insert($parent_credential);
        }

        $inser_data1 = array(
            'first_name' => $row['FirstName'],
            'last_name' => $row['LastName'],
            'blood_group' => $row['BloodGroup'],
            'gender' => $row['Gender'],
            'birthday' => date("Y-m-d", strtotime($row['Birthday'])),
            'mother_tongue' => $row['MotherTongue'],
            'religion' => $row['Religion'],
            'parent_id' => $parentID,
            'caste' => $row['Caste'],
            'mobileno' => $row['Phone'],
            'city' => $row['City'],
            'state' => $row['State'],
            'current_address' => $row['PresentAddress'],
            'permanent_address' => $row['PermanentAddress'],
            'category_id' => $row['CategoryID'],
            'admission_date' => date("Y-m-d", strtotime($row['AdmissionDate'])),
            // 'register_no' => substr(app_generate_hash(), 4, 7),
            'register_no' => $this->regSerNumber(),
            'photo' => 'defualt.png',
            'email' => $row['StudentEmail'],
        );
        //save all student information in the database file
        $this->db->table('student')->insert($inser_data1);
        $studentID = $this->db->insertID();
        //save student login credential
        $inser_data2 = array(
            'username' => $row["StudentEmail"],
            'role' => 7,
            'user_id' => $studentID,
            'password' => $this->app_lib->pass_hashed($row["StudentPassword"]),
        );
        $this->db->table('login_credential')->insert($inser_data2);

        //save student enroll information in the database file
        $arrayEnroll = array(
            'student_id' => $studentID,
            'class_id' => $classID,
            'section_id' => $sectionID,
            'branch_id' => $branchID,
            'roll' => $row['Roll'],
            'session_id' => get_session_id(),
        );
        $this->db->table('enroll')->insert($arrayEnroll);
    }


    public function getStudentList($classID = '', $sectionID = '', $branchID = '', $deactivate = false)
    {
        $builder = $this->db->table('enroll as e')->select('e.*,s.photo, CONCAT(s.first_name, " ", s.last_name) as fullname,s.register_no,s.parent_id,s.email,s.blood_group,s.birthday,l.active,c.name as class_name,se.name as section_name')
        ->join('student as s', 'e.student_id = s.id', 'inner')
        ->join('login_credential as l', 'l.user_id = s.id and l.role = 7', 'inner')
        ->join('class as c', 'e.class_id = c.id', 'left')
        ->join('section as se', 'e.section_id=se.id', 'left')
        ->where('e.class_id', $classID)
        ->where('e.branch_id', $branchID)
        ->where('e.session_id', get_session_id())
        ->orderBy('s.id', 'ASC');
        if ($sectionID != 'all') {
            $builder->where('e.section_id', $sectionID);
        }
        if ($deactivate == true) {
            $builder->where('l.active', 0);
        }
        return $builder->get();
    }


    public function getSearchStudentList($search_text)
    {
        $builder = $this->db->table('enroll as e')->select('e.*,s.photo,s.first_name,s.last_name,s.register_no,s.parent_id,s.email,s.blood_group,s.birthday,c.name as class_name,se.name as section_name,sp.name as parent_name')
        ->join('student as s', 'e.student_id = s.id', 'left')
        ->join('class as c', 'e.class_id = c.id', 'left')
        ->join('section as se', 'e.section_id=se.id', 'left')
        ->join('parent as sp', 'sp.id = s.parent_id', 'left')
        ->where('e.session_id', get_session_id());
        if (!is_superadmin_loggedin()) {
            $builder->where('e.branch_id', get_loggedin_branch_id());
        }
        $builder->groupStart();
        $builder->like('s.first_name', $search_text);
        $builder->orLike('s.last_name', $search_text);
        $builder->orLike('s.register_no', $search_text);
        $builder->orLike('s.email', $search_text);
        $builder->orLike('e.roll', $search_text);
        $builder->orLike('s.blood_group', $search_text);
        $builder->orLike('sp.name', $search_text);
        $builder->groupEnd();
        $builder->orderBy('s.id', 'desc');
        return $builder->get();
    }



} /*End Class*/