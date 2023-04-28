<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class BranchModel extends Model
{
	public $db;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
	}

	public function saveBranch($data)
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
        if (!isset($data['branch_id'])) {
            $this->db->table('branch')->insert($arrayBranch);
        } else {
            $this->db->table('branch')->where('id', $data['branch_id'])->update($arrayBranch);
        }

        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}