<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class RoleModel extends Model
{
	public $db;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
	}


	function getRoleList()
    {
        $builder = $this->db->table('roles')->select('*');
        $builder->whereNotIn('id', array(1,6,7));
        $r = $builder->get()->getResultArray();
        return $r;  
    }

    function getModulesList()
    {
        $builder = $this->db->table('permission_modules')->orderBy('sorted', 'ASC');
        return $builder->get()->getResultArray(); 
    }

    // role save and update function
    public function save_roles($data)
    {
    	$builder = $this->db->table('roles');
        $insertData = array(
            'name' => $data['role'],
            'prefix' => strtolower(str_replace(' ', '', $data['role'])),
        );

        if (!isset($data['id']) && empty($data['id'])) {
            $insertData['is_system'] = 0;
            $builder->insert($insertData);
        } else {
            $builder->where('id', $data['id']);
            $builder->update($insertData);
        }
    }


    // check permissions function
    public function check_permissions($module_id = '', $role_id = '')
    {
        $sql = "SELECT permission.*, staff_privileges.id as staff_privileges_id,staff_privileges.is_add,staff_privileges.is_edit,staff_privileges.is_view,staff_privileges.is_delete FROM permission LEFT JOIN staff_privileges ON staff_privileges.permission_id = permission.id and staff_privileges.role_id = " . $this->db->escape($role_id) . " WHERE permission.module_id = " . $this->db->escape($module_id) . " ORDER BY permission.id ASC";
        $query = $this->db->query($sql);
        return $query->getResultArray();
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





} /*End CLass */