<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Libraries\App_lib;
use App\Models\ApplicationModel;

/**
 * 
 */
class ParentsModel extends Model
{
	public $db;
    public $app_lib;
    public $application_model;
    public $request;
	
	function __construct()
	{
        parent::__construct();
		$this->db = \Config\Database::connect();
        $this->app_lib = new App_lib();
        $this->application_model = new ApplicationModel();
        $this->request = \Config\Services::request();
	}

    // moderator parents all information
    public function saveParent($data)
    {
        $inser_data1 = array(
            'branch_id' => $this->application_model->get_branch_id(),
            'name' => $data['name'],
            'relation' => $data['relation'],
            'father_name' => $data['father_name'],
            'mother_name' => $data['mother_name'],
            'occupation' => $data['occupation'],
            'income' => $data['income'],
            'education' => $data['education'],
            'email' => $data['email'],
            'mobileno' => $data['mobileno'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'photo' => $this->uploadImage('parent'),
            'facebook_url' => $data['facebook'],
            'linkedin_url' => $data['linkedin'],
            'twitter_url' => $data['twitter'],
        );
        $inser_data2 = array('username' => $data["email"]);
        if (!isset($data['parent_id']) && empty($data['parent_id'])) {
            // save employee information in the database
            $this->db->table('parent')->insert($inser_data1);
            $parent_id = $this->db->insertID();
            // save employee login credential information in the database
            $inser_data2['role'] = 6;
            $inser_data2['active'] = 1;
            $inser_data2['user_id'] = $parent_id;
            $inser_data2['password'] = $this->app_lib->pass_hashed($data["password"]);
            $this->db->table('login_credential')->insert($inser_data2);
            return $parent_id;
        } else {
            $builder = $this->db->table('parent')->where('id', $data['parent_id']);
            $builder->update($inser_data1);
            // update login credential information in the database
            $builder = $this->db->table('login_credential')->where(array('role' => 6, 'user_id' => $data['parent_id']));
            $builder->update($inser_data2);
        }

        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function childsResult($parent_id)
    {
        $builder = $this->db->table('enroll as e')->select('s.id,s.photo, CONCAT(s.first_name, " ", s.last_name) as fullname,c.name as class_name,se.name as section_name')
        ->join('student as s', 'e.student_id = s.id', 'inner')
        ->join('login_credential as l', 'l.user_id = s.id and l.role = 7', 'inner')
        ->join('class as c', 'e.class_id = c.id', 'left')
        ->join('section as se', 'e.section_id=se.id', 'left')
        ->where('s.parent_id', $parent_id)
        ->where('l.active', 1);
        return $builder->get()->getResultArray();
    }

    // get parent all details
    public function getParentList($branchID = null, $active = 1)
    {
        $builder = $this->db->table('parent')->select('parent.*,login_credential.active as active')
        ->join('login_credential', 'login_credential.user_id = parent.id and login_credential.role = "6"', 'inner')
        ->where('login_credential.active', $active);
        if (!empty($branchID)) {
           $builder->where('parent.branch_id', $branchID);
        }
        $builder->orderBy('parent.id', 'ASC');
        return $builder->get()->getResult();
    }



	public function getSingleParent($id)
    {
        $builder = $this->db->table('parent')->select('parent.*,login_credential.role as role_id,login_credential.active,login_credential.username,login_credential.id as login_id, roles.name as role')
        ->join('login_credential', 'login_credential.user_id = parent.id and login_credential.role = "6"', 'inner')
        ->join('roles', 'roles.id = login_credential.role', 'left')
        ->where('parent.id', $id);
        if (!is_superadmin_loggedin()) {
            $builder->where('parent.branch_id', get_loggedin_branch_id());
        }
        $query = $builder->get();
        if ($this->db->affectedRows() == 0) {
            show_404();
        }
        return $query->getRowArray();
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