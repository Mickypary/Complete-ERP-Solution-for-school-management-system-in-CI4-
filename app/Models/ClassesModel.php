<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\ApplicationModel;

/**
 * 
 */
class ClassesModel extends Model
{
	public $db;
	public $application_model;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->application_model = new ApplicationModel();
	}


	public function getTeacherAllocation($branch_id = '')
    {
        $builder = $this->db->table('teacher_allocation as ta')->select('ta.*,st.name as teacher_name,st.staff_id as teacher_id,c.name as class_name,c.branch_id,s.name as section_name');
        $builder->join('staff as st', 'st.id = ta.teacher_id', 'left');
        $builder->join('class as c', 'c.id = ta.class_id', 'left');
        $builder->join('section as s', 's.id = ta.section_id', 'left');
        $builder->orderBy('ta.id', 'ASC');
        $builder->where('ta.session_id', get_session_id());
        if (!empty($branch_id)) {
            $builder->where('c.branch_id', $branch_id);
        }
        return $builder->get();
    }


    public function teacherAllocationSave($data)
    {
        $arrayData = array(
            'branch_id'     => $this->application_model->get_branch_id(),
            'session_id'    => get_session_id(),
            'class_id'      => $data['class_id'],
            'section_id'    => $data['section_id'],
            'teacher_id'    => $data['staff_id'],
        );
        $builder = $this->db->table('teacher_allocation');
        if (!isset($data['allocation_id'])) {
            if (get_permission('assign_class_teacher', 'is_add')) {
                $builder->insert($arrayData);
            }
            set_alert('success', translate('information_has_been_saved_successfully'));
        } else {
            if (get_permission('assign_class_teacher', 'is_edit')) {
                $builder->where('id', $data['allocation_id']);
                $builder->update($arrayData);
            }
            set_alert('success', translate('information_has_been_updated_successfully'));
        }
        unset($arrayData['teacher_id']);
        $builder = $this->db->table('subject_assign')->where($arrayData);
        $builder->update(array('teacher_id' => $data['staff_id']));
    }



} /* End Class */

