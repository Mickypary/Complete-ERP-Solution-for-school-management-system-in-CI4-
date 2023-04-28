<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class SchoolModel extends Model
{
	public $db;
	public $request;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->request = \Config\Services::request();
	}

	public function getBranchID()
    {
        if (is_superadmin_loggedin()) {
            return $this->request->getGet('branch_id');
        } else {
            return get_loggedin_branch_id();
        }
    }


    public function branchUpdate($data)
    {
        $arrayBranch = array(
            'name' => $data['branch_name'],
            'school_name' => $data['school_name'],
            'email' => $data['email'],
            'mobileno' => $data['mobileno'],
            'currency' => $data['currency'],
            'symbol' => $data['currency_symbol'],
            'city' => $data['city'],
            'state' => $data['state'],
            'address' => $data['address'],
        );
        $this->db->table('branch')->where('id', $data['branch_id'])->update($arrayBranch);
    }



    public function get($table, $where_array = NULL, $single = false, $branch = false, $columns = '*')
    {
        $builder = $this->db->table($table)->select($columns);
        if (is_array($where_array)){
            $builder->where($where_array);
        }
        if ($branch == true) {
	        if (!is_superadmin_loggedin()) {
	            $builder->where("branch_id", get_loggedin_branch_id());
	        }
        }
        if ($single == true) {
            $method = 'getRowArray';
        } else {
            $method = 'getResultArray';
            $builder->orderBy('id', 'ASC');
        }
        $result = $builder->get()->$method();
		return $result;
    }










} /*End Class*/