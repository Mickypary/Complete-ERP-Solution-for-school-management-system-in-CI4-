<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\ApplicationModel;

/**
 * 
 */
class CustomFieldModel extends Model
{
	public $application_model;
	public $db;
	
	function __construct()
	{
		$this->application_model = new ApplicationModel();
		$this->db = \Config\Database::connect();
	}

	public function saveCustom($data, $defaultValue)
    {
	    $branchID = $this->application_model->get_branch_id();
	    $required = isset($_POST['chk_required']) ? 1 : 0;
	    $show_table = isset($_POST['chk_show_table']) ? 1 : 0;
	    $status = isset($_POST['chk_active']) ? 1 : 0;
	    $insertData = array(
	        'form_to' 		=> $data['belongs_to'],
	        'field_label' 	=> $data['field_label'],
	        'field_type' 	=> $data['field_type'],
	        'default_value' => $defaultValue,
	        'required' 		=> $required,
	        'status' 		=> $status,
	        'show_on_table' => $show_table,
	        'field_order' 	=> $data['field_order'],
	        'bs_column' 	=> $data['bs_column'],
	        'branch_id' 	=> $branchID,
	    );
	    if (isset($data['custom_field_id'])) {
            $builder = $this->db->table('custom_field')->where('id', $data['custom_field_id']);
            $builder->update($insertData);
	    } else {
	    	$builder->insert($insertData);
	    }
    }
}