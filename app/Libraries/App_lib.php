<?php

namespace App\Libraries;

use CodeIgniter\I18n\Time;

/**
 * 
 */
class App_lib
{
	public $db;
	public $email;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->email = \Config\Services::email();
	}


    public function get_credential_id($user_id, $staff = 'staff')
    {
        $builder = $this->db->table('login_credential')->select('id');
        if ($staff == 'staff') {
            $builder->whereNotIn('role', array(6, 7));
        } elseif ($staff == 'parent') {
            $builder->where('role', 6);
        } elseif ($staff == 'student') {
            $builder->where('role', 7);
        }
        $builder->where('user_id', $user_id);
        $result = $builder->get('login_credential')->getRowArray();
        return $result['id'];
    }


	public function pass_hashed($password)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        return $hashed;
    }

    public function generateCSRF()
    {
        return '<input type="hidden" name="' . csrf_token()  . '" value="' . csrf_hash() . '" />';
    }

    
	public function verify_password($password, $encrypt_password)
	{
		$hashed = password_verify($password, $encrypt_password);
		return $hashed;
	}

	function timezone_list()
    {
        static $timezones = null;
        if ($timezones === null) {
            $timezones = [];
            $offsets = [];
            $now = new \DateTime('now', new \DateTimeZone('UTC'));
                foreach (\DateTimeZone::listIdentifiers() as $timezone) {
                $now->setTimezone(new \DateTimeZone($timezone));
                $offsets[] = $offset = $now->getOffset();
                $timezones[$timezone] = '(' . $this->format_GMT_offset($offset) . ') ' . $this->format_timezone_name($timezone);
            }
            array_multisort($offsets, $timezones);
        }
        return $timezones;
    }

    public function getSelectList($table, $all = '')
    {
        $arrayData = array("" => translate('select'));
        if ($all == 'all') {
            $arrayData['all'] = translate('all_select');
        }
        $result = $this->db->table($table)->get()->getResult();
        foreach ($result as $row) {
            $arrayData[$row->id] = $row->name;
        }
        return $arrayData;
    }


    public function getRoles($arra_id = [1, 6, 7])
    {
        if ($arra_id !='all') {
            $builder = $this->db->table('roles')->whereNotIn('id', $arra_id);
        }
        $rolelist = $builder->get()->getResult();
        $role_array = array('' => translate('select'));
        foreach ($rolelist as $role) {
            $role_array[$role->id] = $role->name;
        }
        return $role_array;
    }


    public function getDepartment($branch_id = '')
    {
        if (empty($branch_id)) {
            $array = array('' => translate('select_branch_first'));
        } else {
            $builder = $this->db->table('staff_department')->where('branch_id', $branch_id);
            $result = $builder->get()->getResult();
            $array = array('' => translate('select'));
            foreach ($result as $row) {
                $array[$row->id] = $row->name;
            }
        }
        return $array;
    }


    public function getDesignation($branch_id = '')
    {
        if ($branch_id == '') {
            $array = array('' => translate('select_branch_first'));
        } else {
            $builder = $this->db->table('staff_designation')->where('branch_id', $branch_id);
            $result = $builder->get()->getResult();
            $array = array('' => translate('select'));
            foreach ($result as $row) {
                $array[$row->id] = $row->name;
            }
        }
        return $array;
    }


    public function getBloodgroup()
    {
        $blood_group = array(
            '' => translate('select'),
            'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'O+' => 'O+',
            'O-' => 'O-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
        );
        return $blood_group;
    }


    public function get_document_category()
    {
        $category = array(
            '' => translate('select'),
            '1' => "Resume File",
            '2' => "Offer Letter",
            '3' => "Joining Letter",
            '4' => "Experience Certificate",
            '5' => "Resignation Letter",
            '6' => "Other Documents",
        );
        return $category;
    }


    function format_GMT_offset($offset)
    {
        $hours = intval($offset / 3600);
        $minutes = abs(intval($offset % 3600 / 60));
        return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
    }

    function format_timezone_name($name)
    {
        $name = str_replace('/', ', ', $name);
        $name = str_replace('_', ' ', $name);
        $name = str_replace('St ', 'St. ', $name);
        return $name;
    }


    public function getAnimationslist()
    {
        $animations = array(
            'fadeIn' => "fadeIn",
            'fadeInUp' => "fadeInUp",
            'fadeInDown' => "fadeInDown",
            'fadeInLeft' => "fadeInLeft",
            'fadeInRight' => "fadeInRight",
            'bounceIn' => "bounceIn",
            'rotateInUpLeft' => "rotateInUpLeft",
            'rotateInDownLeft' => "rotateInDownLeft",
            'rotateInUpRight' => "rotateInUpRight",
            'rotateInDownRight' => "rotateInDownRight",
        );
        return $animations;
    }


    public function getDateformat()
    {
        $date = array(
            "Y-m-d" => "yyyy-mm-dd",
            "Y/m/d" => "yyyy/mm/dd",
            "Y.m.d" => "yyyy.mm.dd",
            "d-M-Y" => "dd-mmm-yyyy",
            "d/M/Y" => "dd/mmm/yyyy",
            "d.M.Y" => "dd.mmm.yyyy",
            "d-m-Y" => "dd-mm-yyyy",
            "d/m/Y" => "dd/mm/yyyy",
            "d.m.Y" => "dd.mm.yyyy",
            "m-d-Y" => "mm-dd-yyyy",
            "m/d/Y" => "mm/dd/yyyy",
            "m.d.Y" => "mm.dd.yyyy",
        );
        return $date;
    }


    function getTable($table, $where = "", $single = FALSE)
    {
        $builder = $this->db->table("$table as t");
        if ($where != NULL) {
            $builder->where($where);
        }
        if (!is_superadmin_loggedin()) {
            $builder->where("branch_id", get_loggedin_branch_id());
        }
        if ($single == TRUE) {
            $method = "getRowArray";
        } else {
            $builder->orderBy("id", "asc");
            $method = "getResultArray";
        }
        $builder->select("t.*,b.name as branch_name");
        // $this->CI->db->from("$table as t");
        $builder->join("branch as b", "b.id = t.branch_id", "left");
        $query = $builder->get();
        return $query->$method();
    }




} /*End Class*/