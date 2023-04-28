<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\AjaxModel;
use App\Models\ApplicationModel;

/**
 * 
 */
class Ajax extends BaseController
{
	public $ajax_model;
	public $db;
    public $application_model;
	
	function __construct()
	{
		$this->ajax_model = new AjaxModel();
		$this->db = \Config\Database::connect();
        $this->application_model = new ApplicationModel();
	}





	public function getDataByBranch()
    {
        $html = "";
        $table = $this->request->getVar('table');
        $branch_id = $this->application_model->get_branch_id();
        if (!empty($branch_id)) {
            $result = $this->db->table($table)->select('id,name')->where('branch_id', $branch_id)->get()->getResultArray();
            if (count($result)) {
                $html .= "<option value=''>" . translate('select') . "</option>";
                foreach ($result as $row) {
                    $html .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select_branch_first') . '</option>';
        }
        echo $html;
    }




	public function department_details()
    {
        if (get_permission('department', 'is_edit')) {
            // id in getVar is the ajax data request name in data object data{ id: id} where the first id is the request name
            $id = $this->request->getVar('id');
            $builder = $this->db->table('staff_department')->where('id', $id);
            $query = $builder->get();
            $result = $query->getRowArray();
            echo json_encode($result);
        }
    }

    public function designation_details()
    {
        if (get_permission('designation', 'is_edit')) {
            $id = $this->request->getVar('id');
            $builder = $this->db->table('staff_designation')->where('id', $id);
            $query = $builder->get();
            $result = $query->getRowArray();
            echo json_encode($result);
        }
    }







} /*End Class */