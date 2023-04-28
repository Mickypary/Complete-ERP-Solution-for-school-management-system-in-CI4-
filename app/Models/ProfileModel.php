<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class ProfileModel extends Model
{
	public $db;
    public $request;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
        $this->request = \Config\Services::request();
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



    // moderator staff all information
    public function staffUpdate($data)
    {
        $update_data = array(
            'name' => $data['name'],
            'sex' => $data['sex'],
            'religion' => $data['religion'],
            'blood_group' => $data['blood_group'],
            'birthday' => $data["birthday"],
            'mobileno' => $data['mobile_no'],
            'present_address' => $data['present_address'],
            'permanent_address' => $data['permanent_address'],
            'photo' => $this->uploadImage('staff'),
            'email' => $data['email'],
            'facebook_url' => $data['facebook'],
            'linkedin_url' => $data['linkedin'],
            'twitter_url' => $data['twitter'],
        );
        if (is_admin_loggedin()) {
            $update_data['joining_date'] = date("Y-m-d", strtotime($data['joining_date']));
            $update_data['designation'] = $data['designation_id'];
            $update_data['department'] = $data['department_id'];
            $update_data['qualification'] = $data['qualification'];
        }
        // UPDATE ALL INFORMATION IN THE DATABASE
        $builder = $this->db->table('staff')->where('id', get_loggedin_user_id());
        $builder->update($update_data);

        // UPDATE LOGIN CREDENTIAL INFORMATION IN THE DATABASE
        $builder = $this->db->table('login_credential')->where('user_id', get_loggedin_user_id());
        $builder->whereNotIn('role', array(6,7));
        $builder->update(array('username' => $data['email']));
    }
    


	// moderator staff all information
    public function parentUpdate($data)
    {
        $update_data = array(
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

        // UPDATE ALL INFORMATION IN THE DATABASE
        $this->db->table('parent')->where('id', get_loggedin_user_id())->update($update_data);

        // UPDATE LOGIN CREDENTIAL INFORMATION IN THE DATABASE
        $this->db->table('login_credential')->where('user_id', get_loggedin_user_id())->where('role', 6)->update(array('username' => $data['email']));
    }


    public function studentUpdate($data)
    {
        $update_data1 = array(
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
            'email' => $data['email'],
            'photo' => $this->uploadImage('student'),
        );

        // update student all information in the database
        $this->db->where('id', get_loggedin_user_id());
        $this->db->update('student', $update_data1);

        // update login credential information in the database
        $this->db->where('user_id', get_loggedin_user_id());
        $this->db->where('role', 7);
        $this->db->update('login_credential', array('username' => $data['email']));
    }





} /*End Class*/