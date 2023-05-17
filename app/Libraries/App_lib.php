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


    public function getStaffList($branch_id = '', $role='')
    {
        $builder = $this->db->table('staff as s');
        if (empty($branch_id)) {
            $array = array('' => translate('select_branch_first'));
        } else {
            $builder->select('s.id,s.name,s.staff_id');
            $builder->join('login_credential as l', 'l.user_id = s.id and l.role != 6 and l.role != 7', 'inner');
            if (!empty($branch_id)) {
                $builder->where('s.branch_id', $branch_id);
            }
            if (!empty($role)) {
                $builder->whereIn('l.role', array($role));
            }
            $result = $builder->get()->getResult();
            $array = array('' => translate('select'));
            foreach ($result as $row) {
                $array[$row->id] = $row->name . ' (' . $row->staff_id . ')';
            }
        }
        return $array;
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
        $result = $builder->get()->getRowArray();
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


    public function getClass($branch_id = '')
    {
        $builder = $this->db->table('teacher_allocation');
        if (empty($branch_id)) {
            $array = array('' => translate('select_branch_first'));
        } else {
            if (loggedin_role_id() == 3) {
                $builder->select('class.id,class.name');
                $builder->join('class', 'class.id = teacher_allocation.class_id', 'left');
                $builder->where('teacher_allocation.teacher_id', get_loggedin_user_id());
                $builder->where('teacher_allocation.session_id', get_session_id());
                $result = $builder->get()->getResult();
            } else {
                $builder = $this->db->table('class')->where('branch_id', $branch_id);
                $result = $builder->get()->getResult();
            }
            $array = array('' => translate('select'));
            foreach ($result as $row) {
                $array[$row->id] = $row->name;
            }
        }
        return $array;
    }


    public function getSections($class_id = '', $all = false, $multi = false)
    {
        if (empty($class_id)) {
            $array = array('' => translate('select_class_first'));
        } else {
            if (loggedin_role_id() == 3) {
                $result = $this->db->table('teacher_allocation')->select('teacher_allocation.section_id,section.name')
                    ->join('section', 'section.id = teacher_allocation.section_id', 'left')
                    ->where(array('teacher_allocation.class_id' => $class_id,
                        'teacher_allocation.teacher_id' => get_loggedin_user_id(),
                        'teacher_allocation.session_id' => get_session_id()))
                    ->get()->getResult();
            } else {
                $builder = $this->db->table('sections_allocation')->where('class_id', $class_id);
                $result = $builder->get()->getResult(); 
            }
            if ($multi == false) {
                $array = array('' => translate('select'));
            }
            if ($all == true && loggedin_role_id() != 3) {
                $array['all'] = translate('all_sections');
            }
            foreach ($result as $row) {
                $array[$row->section_id] = get_type_name_by_id('section', $row->section_id);
            }
        }
        return $array;
    }


    function get_table($table, $id = NULL, $single = FALSE)
    {
        $builder = $this->db->table($table);

        if ($single == TRUE) {
            $method = 'getRowArray';
        } else {
            $builder->orderBy('id', 'ASC');
            $method = 'getResultArray';
        }
        if ($id != NULL) {
            $builder->where('id', $id);
        }
        $query = $builder->get();
        return $query->$method();
    }


    public function getRoomByHostel($hostel_id = '')
    {
        if ($hostel_id == '') {
            $array = array('' => translate('first_select_the_hostel'));
        } else {
            $builder = $this->db->table('hostel_room')->where('hostel_id', $hostel_id);
            $result = $builder->get()->getResult();
            $array = array('' => translate('select'));
            foreach ($result as $row) {
                $array[$row->id] = $row->name . ' ('. get_type_name_by_id('hostel_category', $row->category_id).')';
            }
        }
        return $array;
    }


    public function getVehicleByRoute($route_id = '')
    {
        if ($route_id == '') {
            $array = array('' => translate('first_select_the_route'));
        } else {
            $builder = $this->db->table('transport_assign')->where('route_id', $route_id);
            $result = $builder->get()->getResult();
            $array = array('' => translate('select'));
            foreach ($result as $row) {
                $array[$row->vehicle_id] = get_type_name_by_id('transport_vehicle', $row->vehicle_id, 'vehicle_no');
            }
        }
        return $array;
    }


    public function getStudentCategory($branch_id = '')
    {
        if (empty($branch_id)) {
            $array = array('' => translate('select_branch_first'));
        } else {
            $builder = $this->db->table('student_category')->where('branch_id', $branch_id);
            $result = $builder->get()->getResult();
            $array = array('' => translate('select'));
            foreach ($result as $row) {
                $array[$row->id] = $row->name;
            }
        }
        return $array;
    }


    public function getSelectByBranch($table, $branch_id = '', $all = false, $where = '')
    {
        $builder = $this->db->table($table);
        if (empty($branch_id)) {
            $array = array('' => translate('select_branch_first'));
        } else {
            if (is_array($where)) {
                $builder->where($where);
            }
            $builder->where('branch_id', $branch_id);
            $result = $builder->get()->getResult();
            $array = array('' => translate('select'));
            if ($all == true) {
                $array['all'] = translate('all_select');
            }
            foreach ($result as $row) {
                $array[$row->id] = $row->name;
            }
        }
        return $array;
    }

    public function check_branch_restrictions($table, $id = '') {
        if (empty($id)) {
             access_denied();
        }
        if (!is_superadmin_loggedin()) {
            $query = $this->db->table($table)->select('id,branch_id')->where('id', $id)->limit(1)->get();
            if ($query->getNumRows() != 0) {
                $branch_id = $query->getRow()->branch_id;
                if ($branch_id != $this->session->get('loggedin_branch')) {
                    access_denied();
                }
            }
        }
    }




} /*End Class*/