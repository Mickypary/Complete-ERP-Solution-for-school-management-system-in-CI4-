<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class SubjectModel extends Model
{
	protected $table      = 'teacher_allocation';
    protected $primaryKey = 'id';
    
	function __construct()
	{
		parent::__construct();
	}

	// get subjects assign list
    public function getAssignList()
    {
        $builder = $this->db->table('subject_assign as sa')->select('sa.class_id,sa.section_id,sa.branch_id,b.name as branch_name,c.name as class_name,s.name as section_name')
        ->join('branch as b', 'b.id = sa.branch_id', 'left')
        ->join('class as c', 'c.id = sa.class_id', 'left')
        ->join('section as s', 's.id = sa.section_id', 'left')
        ->groupBy(array('sa.class_id', 'sa.section_id', 'sa.branch_id'));
        if (!is_superadmin_loggedin()) {
            $builder->where('sa.branch_id', get_loggedin_branch_id());
        }
        $result = $builder->get()->getResultArray();
        return $result;
    }

    // get subject list by class id and section id
    public function get_subject_list($class_id, $section_id)
    {
        $builder = $this->db->table('subject_assign as sa')->select('sa.subject_id,s.name')
        ->join('subject as s', 's.id = sa.subject_id', 'left')
        ->where('sa.class_id', $class_id)
        ->where('sa.section_id', $section_id);
        $subjects = $builder->get()->getResult();
        $name_list = '';
        foreach ($subjects as $row) {
            $name_list .= '- ' . $row->name . '<br>';
        }
        return $name_list;
    }

    // get teacher assign list
    public function getTeacherAssignList()
    {
        $sql = "SELECT sa.*, c.name as class_name, s.name as section_name, sb.name as subject_name, t.name as teacher_name, t.department, sd.name as department_name FROM subject_assign as sa LEFT JOIN class as c ON c.id = sa.class_id LEFT JOIN section as s ON s.id = sa.section_id LEFT JOIN subject as sb ON sb.id = sa.subject_id LEFT JOIN staff as t ON t.id = sa.teacher_id LEFT JOIN staff_department as sd ON sd.id = t.department WHERE sa.teacher_id != 0";
        if (!is_superadmin_loggedin()) {
            $sql .= " AND sa.branch_id = " . $this->db->escape(get_loggedin_branch_id());
        }
        $sql .= " ORDER BY sa.id ASC";
        $result = $this->db->query($sql)->getResult();
        return $result;
    }




} /* End Class*/