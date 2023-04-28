<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class ParentsModel extends Model
{
	public $db;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
	}



	public function getSingleParent($id)
    {
        $this->db->select('parent.*,login_credential.role as role_id,login_credential.active,login_credential.username,login_credential.id as login_id, roles.name as role')
        ->from('parent')
        ->join('login_credential', 'login_credential.user_id = parent.id and login_credential.role = "6"', 'inner')
        ->join('roles', 'roles.id = login_credential.role', 'left')
        ->where('parent.id', $id);
        if (!is_superadmin_loggedin()) {
            $this->db->table()->where('parent.branch_id', get_loggedin_branch_id());
        }
        $query = $this->db->get();
        if ($this->db->affectedRows() == 0) {
            show_404();
        }
        return $query->getRowArray();
    }





} /*End Class*/