<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class ApplicationModel extends Model
{
	public $db;
    public $request;
    public $validation;
	
	function __construct()
	{
		// code...
		$this->db = \Config\Database::connect();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
	}


    public function get_branch_id()
    {
        if (is_superadmin_loggedin()) {
            // gets it from ajax form data for any name with branch_id
            return $this->request->getPost('branch_id');
        } else {
            return get_loggedin_branch_id();
        }
    }


    public function profilePicUpload()
    {
        if (isset($_FILES["user_photo"]) && !empty($_FILES['user_photo']['name'])) {
            $file_size = $_FILES["user_photo"]["size"];
            $file_name = $_FILES["user_photo"]["name"];
            $allowedExts = array('jpg', 'jpeg', 'png');
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            if ($files = filesize($_FILES['user_photo']['tmp_name'])) {
                if (!in_array(strtolower($extension), $allowedExts)) {
                    // $this->validation->set_message('handle_upload', translate('this_file_type_is_not_allowed'));
                    return false;
                }
                if ($file_size > 2097152) {
                    // $this->validation->set_message('handle_upload', translate('file_size_shoud_be_less_than') . " 2048KB.");
                    return false;
                }
            } else {
                // $this->validation->set_message('handle_upload', translate('error_reading_the_file'));
                return false;
            }
            return true;
        }
    }


    public function getSingle($table, $id = NULL, $single = false)
    {
        if ($single == true) {
            $method = 'getRow';
        } else {
            $method = 'getResult';
        }
        $q = $this->db->query("SELECT * FROM " . $table . " WHERE id = " . $this->db->escape($id));
        return $q->$method();
    }

	public function getUserNameByRoleID($roleID, $userID = '')
    {
        if ($roleID == 6) {
            $sql = "SELECT name,email,photo,branch_id FROM parent WHERE id = " . $this->db->escape($userID);
            return $this->db->query($sql)->getRowArray();
        } elseif ($roleID == 7) {
            $sql = "SELECT student.id, CONCAT(student.first_name,' ',student.last_name) as name, student.email, student.photo, enroll.branch_id FROM student INNER JOIN enroll ON enroll.student_id = student.id WHERE student.id = " . $this->db->escape($userID);
            return $this->db->query($sql)->getRowArray();
        } else {
            $sql = "SELECT name,email,photo,branch_id FROM staff WHERE id = " . $this->db->escape($userID);
            return $this->db->query($sql)->getRowArray();
        }
    }

    public function getLangImage($id = '', $thumb = true)
    {
        $file_path = 'public/uploads/language_flags/flag_' . $id . '_thumb.png';
        if (file_exists($file_path)) {
            if ($thumb == true) {
                $image_url = base_url($file_path);
            } else {
                $image_url = base_url('public/uploads/language_flags/flag_' . $id . '.png');
            }
        } else {
            if ($thumb == true) {
                $image_url = base_url('public/uploads/language_flags/defualt_thumb.png');
            } else {
                $image_url = base_url('public/uploads/language_flags/defualt.png');
            }
        }
        return $image_url;
    }


    // unread message alert in topbar
    public function unread_message_alert()
    {
        $activeUser = loggedin_role_id() . '-' . get_loggedin_user_id();
        $activeUser = $this->db->escape($activeUser);
        $sql = "SELECT id,body,created_at,IF(sender = " . $activeUser . ", 'sent','inbox') as `msg_type`,IF(sender = " . $activeUser . ", reciever,sender) as `get_user` FROM message WHERE (sender = " . $activeUser . " AND trash_sent = 0 AND reply_status = 1) OR (reciever = " . $activeUser . " AND trash_inbox = 0 AND read_status = 0) ORDER BY id DESC";
        $result = $this->db->query($sql)->getResultArray();
        foreach ($result as $key => $value) {
           $result[$key]['message_details'] =  $this->getMessage_details($value['get_user']);
        }
        return $result;
    }


    public function getMessage_details($user_id)
    {
        $getUser = explode('-', $user_id);
        $userRoleID = $getUser[0];
        $userID = $getUser[1];
        $userType = '';
        if ($userRoleID == 6) {
            $userType = 'parent';
            $getUSER = $this->db->query("SELECT name,photo FROM parent WHERE id = " . $this->db->escape($userID))->getRowArray();
        } elseif ($userRoleID == 7) {
            $userType = 'student';
            $getUSER = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) as name,photo FROM  student WHERE id = " . $this->db->escape($userID))->getRowArray();
        } else {
            $userType = 'staff';
            $getUSER = $this->db->query("SELECT name,photo FROM staff WHERE id = " . $this->db->escape($userID))->getRowArray();
        }
        $arrayData = array(
            'imgPath' => get_image_url($userType, $getUSER['photo']), 
            'userName' => $getUSER['name'], 
        );
        return $arrayData;
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

                if ($file->move($upload_path, $file->getRandomName())) {
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


    public function smsServiceProvider($branch_id)
    {
        $builder = $this->db->table('sms_credential')->select('sms_api_id');
        $builder->where('branch_id', $branch_id);
        $builder->where('is_active', 1);
        $r = $builder->get()->getRowArray();
        if ($r == "") {
            return 'disabled';
        } else {
           return  $r['sms_api_id'];
        }
    }


    public function getStudentListByClassSection($classID = '', $sectionID = '', $branchID = '', $deactivate = false, $rollOrder = false)
    {
        $builder = $this->db->table('enroll as e')->select('e.*,s.photo, CONCAT(s.first_name, " ", s.last_name) as fullname,s.register_no,s.parent_id,s.email,s.mobileno,s.blood_group,s.birthday,s.admission_date,l.active,c.name as class_name,se.name as section_name')
        ->join('student as s', 'e.student_id = s.id', 'inner')
        ->join('login_credential as l', 'l.user_id = s.id and l.role = 7', 'inner')
        ->join('class as c', 'e.class_id = c.id', 'left')
        ->join('section as se', 'e.section_id=se.id', 'left')
        ->where('e.class_id', $classID)
        ->where('e.branch_id', $branchID)
        ->where('e.session_id', get_session_id());
        if ($rollOrder == true) {
            $builder->orderBy('e.roll', 'ASC');
        } else {
            $builder->orderBy('s.id', 'ASC');
        }
        if ($sectionID != 'all') {
            $builder->where('e.section_id', $sectionID);
        }
        if ($deactivate == true) {
            $builder->where('l.active', 0);
        }
        return $builder->get()->getResultArray();
    }



} /*End Class*/