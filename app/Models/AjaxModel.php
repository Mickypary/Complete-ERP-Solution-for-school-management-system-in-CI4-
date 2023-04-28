<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\AjaxModel;

/**
 * 
 */
class AjaxModel extends Model
{
	public $db;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
	}


	public function getPayslip($id)
    {
        $builder = $this->db->table('payout_commission')->select('payout_commission.*,staff.name as staff_name,staff.staff_id,ifnull(staff_designation.name,"N/A") as designation_name,ifnull(staff_department.name,"N/A") as department_name,payment_type.name as pay_via_name');
        $builder->join('staff', 'staff.id = payout_commission.staff_id', 'left');
        $builder->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $builder->join('staff_department', 'staff_department.id = staff.department', 'left');
        $builder->join('payment_type', 'payment_type.id = payout_commission.pay_via', 'left');
        $builder->where('payout_commission.id', $id);
        return $builder->get()->getRowArray();
    }
}