<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class StudentModel extends Model
{
	public $db;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
	}




	public function getSingleStudent($id = '')
    {
        $this->db->select('s.*,l.active,e.class_id,e.section_id,e.id as enrollid,e.roll,e.branch_id,e.session_id,c.name as class_name,se.name as section_name,sc.name as category_name');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 'e.student_id = s.id', 'left');
        $this->db->join('login_credential as l', 'l.user_id = s.id and l.role = 7', 'inner');
        $this->db->join('class as c', 'e.class_id = c.id', 'left');
        $this->db->join('section as se', 'e.section_id = se.id', 'left');
        $this->db->join('student_category as sc', 's.category_id=sc.id', 'left');
        $this->db->where('s.id', $id);
        if (!is_superadmin_loggedin()) {
            $this->db->where('e.branch_id', get_loggedin_branch_id());
        }
        $query = $this->db->get();
        if ($this->db->affectedRows() == 0) {
            show_404();
        }
        return $query->getRowArray();
    }



} /*End Class*/