<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\SmsModel;
use App\Libraries\App_lib;

/**
 * 
 */
class FeesModel extends Model
{
	public $sms_model;
	public $request;
	public $validation;
	public $app_lib;
	
	function __construct()
	{
		parent::__construct();
		$this->sms_model = new SmsModel();
		$this->request = \Config\Services::request();
		$this->validation = \Config\Services::validation();
		$this->app_lib = new App_lib();
	}


	public function feeFineCalculation($allocationID, $typeID)
    {
        $builder = $this->db->table('fee_allocation as a')->select('fd.amount,fd.due_date,f.*')
        ->join('fee_groups_details as fd', 'fd.fee_groups_id = a.group_id and fd.fee_type_id = ' . $this->db->escape($typeID), 'left')
        ->join('fee_fine as f', 'f.group_id = fd.fee_groups_id and f.type_id = fd.fee_type_id', 'inner')
        ->where('a.id', $allocationID)
        ->where('f.session_id', get_session_id());
        $getDB = $builder->get()->getRowArray();
        if (is_array($getDB) && count($getDB)) {
            $dueDate = $getDB['due_date'];
            if (strtotime($dueDate) < strtotime(date('Y-m-d'))) {
                $feeAmount = $getDB['amount'];
                $feeFrequency = $getDB['fee_frequency'];
                $fineValue = $getDB['fine_value'];
                if ($getDB['fine_type'] == 1) {
                    $fineAmount = $fineValue;
                } else {
                    $fineAmount = ($feeAmount / 100) * $fineValue;
                }
                $now = time(); // or your date as well
                $dueDate = strtotime($dueDate);
                $datediff = $now - $dueDate;
                $overDay = round($datediff / (60 * 60 * 24));
                if ($feeFrequency != 0) {
                    $fineAmount = ($overDay / $feeFrequency) * $fineAmount;
                }
                return $fineAmount;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }


    public function getStudentAllocationList($classID, $sectionID, $groupID, $branchID)
    {
        $sql = "SELECT e.*, s.photo, CONCAT(s.first_name, ' ', s.last_name) as fullname, s.gender, s.register_no, s.parent_id, s.email, s.mobileno, IFNULL(fa.id, 0) as allocation_id
        FROM enroll as e LEFT JOIN student as s ON e.student_id = s.id LEFT JOIN login_credential as l ON l.user_id = s.id AND l.role = '7' LEFT JOIN
        fee_allocation as fa ON fa.student_id=e.student_id AND fa.group_id = " . $this->db->escape($groupID) . " AND
        fa.session_id= " . $this->db->escape(get_session_id()) . " WHERE e.class_id = " . $this->db->escape($classID) .
        " AND e.branch_id = " . $this->db->escape($branchID) . " AND e.session_id = " . $this->db->escape(get_session_id());
        if ($sectionID != 'all') {
            $sql .= " AND e.section_id =" . $this->db->escape($sectionID);
        }
        $sql .= " ORDER BY s.id ASC";
        return $this->db->query($sql)->getResultArray();
    }


    public function getInvoiceStatus($studentID)
    {
        $status = "";
        $sql = "SELECT SUM(fee_groups_details.amount) as total, min(fee_allocation.id) as inv_no FROM fee_allocation LEFT JOIN fee_groups_details ON
        fee_groups_details.fee_groups_id = fee_allocation.group_id LEFT JOIN fees_type ON fees_type.id = fee_groups_details.fee_type_id WHERE
        fee_allocation.student_id = " . $this->db->escape($studentID) . " AND fee_allocation.session_id = " . $this->db->escape(get_session_id());
        $balance = $this->db->query($sql)->getRowArray();
        $invNo = str_pad($balance['inv_no'], 4, '0', STR_PAD_LEFT);

        $sql = "SELECT IFNULL(SUM(fee_payment_history.amount), 0) as amount, IFNULL(SUM(fee_payment_history.discount), 0) as discount, IFNULL(SUM(fee_payment_history.fine), 0) as fine FROM
        fee_payment_history LEFT JOIN fee_allocation ON fee_payment_history.allocation_id = fee_allocation.id WHERE
        fee_allocation.student_id = " . $this->db->escape($studentID) . " AND fee_allocation.session_id = " . $this->db->escape(get_session_id());
        $paid = $this->db->query($sql)->getRowArray();

        if ($paid['amount'] == 0) {
            $status = 'unpaid';
        } elseif ($balance['total'] == ($paid['amount'] + $paid['discount'])) {
            $status = 'total';
        } elseif ($paid['amount'] > 1) {
            $status = 'partly';
        }
        return array('status' => $status, 'invoice_no' => $invNo);
    }


    public function getInvoiceDetails($studentID)
    {
        $sql = "SELECT fee_allocation.id as allocation_id, fees_type.name, fee_groups_details.amount, fee_groups_details.due_date, fee_groups_details.fee_type_id FROM fee_allocation LEFT JOIN
        fee_groups_details ON fee_groups_details.fee_groups_id = fee_allocation.group_id LEFT JOIN fees_type ON fees_type.id = fee_groups_details.fee_type_id WHERE
        fee_allocation.student_id = " . $this->db->escape($studentID) . " AND fee_allocation.session_id = " . $this->db->escape(get_session_id());
        return $this->db->query($sql)->getResultArray();
    }


    public function getInvoiceBasic($studentID)
    {
        $builder = $this->db->table('enroll as e')->select('s.id,e.branch_id,s.first_name,s.last_name,s.email as student_email,s.current_address as student_address,c.name as class_name,b.school_name,b.email as school_email,b.mobileno as school_mobileno,b.address as school_address')
        ->join('student as s', 's.id = e.student_id', 'inner')
        ->join('class as c', 'c.id = e.class_id', 'left')
        ->join('branch as b', 'b.id = e.branch_id', 'left')
        ->where('e.student_id', $studentID);
        return $builder->get()->getRowArray();
    }


    public function getStudentFeeDeposit($allocationID, $typeID)
    {
        $sqlDeposit = "SELECT IFNULL(SUM(amount), '0.00') as total_amount, IFNULL(SUM(discount), '0.00') as total_discount, IFNULL(SUM(fine), '0.00') as total_fine FROM
        fee_payment_history WHERE allocation_id = " . $this->db->escape($allocationID) . " AND type_id = " . $this->db->escape($typeID);
        return $this->db->query($sqlDeposit)->getRowArray();
    }


    public function getPaymentHistory($allocationID, $groupID)
    {
        $builder = $this->db->table('fee_payment_history as h')->select('h.*,t.name,t.fee_code,pt.name as payvia')
        ->join('fees_type as t', 't.id = h.type_id', 'left')
        ->join('payment_types as pt', 'pt.id = h.pay_via', 'left')
        ->where('h.allocation_id', $allocationID)
        ->orderBy('h.id', 'asc');
        return $this->db->get()->getResultArray();
    }


    public function typeSave($data)
    {
    	$builder = $this->db->table('fees_type');
        $arrayData = array(
            'branch_id' => $this->application_model->get_branch_id(),
            'name' => $data['type_name'],
            'fee_code' => strtolower(str_replace(' ', '-', $data['type_name'])),
            'description' => $data['description'],
        );
        if (!isset($data['type_id'])) {
            $builder->insert($arrayData);
        } else {
            $builder->where('id', $data['type_id']);
            $builder->update($arrayData);
        }
    }


    // add partly of the fee
    public function add_fees($data, $id = '')
    {
    	$builder = $this->db->table('fee_invoice');
        $total_due = get_type_name_by_id('fee_invoice', $id, 'total_due');
        $payment_amount = $data['amount'];
        if (($payment_amount <= $total_due) && ($payment_amount > 0)) {
            $arrayHistory = array(
                'fee_invoice_id' => $id,
                'collect_by' => get_user_stamp(),
                'remarks' => $data['remarks'],
                'method' => $data['method'],
                'amount' => $payment_amount,
                'date' => date("Y-m-d"),
                'session_id' => get_session_id(),
            );
            $this->db->table('payment_history')->insert($arrayHistory);

            if ($total_due <= $payment_amount) {
                $builder->where('id', $id);
                $builder->update(array('status' => 2));
            } else {
                $builder->where('id', $id);
                $builder->update(array('status' => 1));
            }
            $builder->where('id', $id);
            $builder->set('total_paid', 'total_paid + ' . $payment_amount, false);
            $builder->set('total_due', 'total_due - ' . $payment_amount, false);
            $builder->update();

            // send payment confirmation sms
            $arrayHistory['student_id'] = $data['student_id'];
            $arrayHistory['timestamp'] = date("Y-m-d");
            $this->sms_model->send_sms($arrayHistory, 2);
            return true;
        } else {
            return false;
        }
    }


    public function getInvoiceList($class_id, $section_id, $branch_id)
    {
        $builder = $this->db->table('fee_allocation as fa')->select('e.student_id,e.roll,s.first_name,s.last_name,s.register_no,s.mobileno,c.name as class_name,se.name as section_name')
        ->join('enroll as e', 'e.student_id = fa.student_id', 'inner')
        ->join('student as s', 's.id = e.student_id', 'left')
        ->join('class as c', 'c.id = e.class_id', 'left')
        ->join('section as se', 'se.id = e.section_id', 'left')
        ->where('fa.branch_id', $branch_id)
        ->where('fa.session_id', get_session_id())
        ->where('e.class_id', $class_id);
        if ($section_id != 'all') {
            $builder->where('e.section_id', $section_id);
        }
        $builder->groupBy('fa.student_id');
        $builder->orderBy('e.id', 'asc');
        $result = $builder->get()->getResultArray();
        foreach ($result as $key => $value) {
            $result[$key]['feegroup'] = $this->getfeeGroup($value['student_id']);
        }
        return $result;
    }


    public function getDueInvoiceList($class_id, $section_id, $feegroup_id, $fee_feetype_id)
    {
        $sql = "SELECT IFNULL(SUM(h.amount), '0') as total_amount, IFNULL(SUM(h.discount), '0') as total_discount, gd.amount as full_amount, gd.due_date, e.student_id, e.roll, s.first_name, s.last_name,
        s.register_no, s.mobileno, c.name as class_name, se.name as section_name FROM fee_allocation as fa LEFT JOIN fee_payment_history as h ON h.allocation_id = fa.id and h.type_id = " .
        $this->db->escape($fee_feetype_id) . " INNER JOIN fee_groups_details as gd ON gd.fee_groups_id = fa.group_id and gd.fee_type_id = " . $this->db->escape($fee_feetype_id) . " INNER JOIN
        enroll as e ON e.student_id = fa.student_id LEFT JOIN student as s ON s.id = e.student_id LEFT JOIN class as c ON c.id = e.class_id LEFT JOIN section as se ON se.id = e.section_id WHERE
        fa.group_id = " . $this->db->escape($feegroup_id) . " AND fa.session_id = " . $this->db->escape(get_session_id()) . " AND e.class_id = " . $this->db->escape($class_id);
        
        if ($section_id != 'all') {
            $sql .= " AND e.section_id = " . $this->db->escape($section_id);
        }
        $sql .= " GROUP BY  fa.student_id ORDER BY e.id ASC";
        $result = $this->db->query($sql)->getResultArray();

        foreach ($result as $key => $value) {
            $result[$key]['feegroup'] = $this->getfeeGroup($value['student_id']);
        }
        return $result;
    }


    public function getDueReport($class_id='', $section_id='')
    {
        $builder = $this->db->table('fee_allocation as fa')->select('fa.id as allocation_id,sum(gd.amount) as total_fees,e.student_id,e.roll,s.first_name,s.last_name,s.register_no,s.mobileno,c.name as class_name,se.name as section_name')
        ->join('fee_groups_details as gd', 'gd.fee_groups_id = fa.group_id', 'left')
        ->join('enroll as e', 'e.student_id = fa.student_id', 'inner')
        ->join('student as s', 's.id = e.student_id', 'left')
        ->join('class as c', 'c.id = e.class_id', 'left')
        ->join('section as se', 'se.id = e.section_id', 'left')
        ->where('fa.session_id', get_session_id())
        ->where('e.class_id', $class_id);
        if (!empty($section_id)){
            $builder->where('e.section_id', $section_id);
        }
        $builder->groupBy('fa.student_id');
        $builder->orderBy('e.roll', 'asc');
        $result = $builder->get()->getResultArray();
        foreach ($result as $key => $value) {
            $result[$key]['payment'] = $this->getPaymentDetails($value['student_id']);
        }
        return $result;
    }


    function getPaymentDetails($student_id)
    {
        $builder = $this->db->table('fee_allocation')->select('IFNULL(SUM(amount), 0) as total_paid, IFNULL(SUM(discount), 0) as total_discount, IFNULL(SUM(fine), 0) as total_fine')
        ->join('fee_payment_history', 'fee_payment_history.allocation_id = fee_allocation.id', 'left')
        ->where('fee_allocation.student_id', $student_id);
        return  $builder->get()->getRowArray();
    }


    public function getStuPaymentHistory($classID='', $SectionID='', $paymentVia, $start, $end, $branchID, $onlyFine=false)
    {
        $builder = $this->db->table('fee_payment_history as h')->select('h.*,ft.name as type_name,e.student_id,e.roll,s.first_name,s.last_name,s.register_no,s.mobileno,c.name as class_name,se.name as section_name,pt.name as pay_via')
        ->join('fee_allocation as fa', 'fa.id = h.allocation_id', 'inner')
        ->join('fees_type as ft', 'ft.id = h.type_id', 'left')
        ->join('enroll as e', 'e.student_id = fa.student_id', 'inner')
        ->join('student as s', 's.id = e.student_id', 'left')
        ->join('class as c', 'c.id = e.class_id', 'left')
        ->join('section as se', 'se.id = e.section_id', 'left')
        ->join('payment_types as pt', 'pt.id = h.pay_via', 'left')
        ->where('fa.session_id', get_session_id())
        ->where('h.date  >=', $start)
        ->where('h.date <=', $end)
        ->where('e.branch_id', $branchID);
        if ($onlyFine == true) {
           $builder->where('h.fine !=', 0);
        }
        if (!empty($classID)){
            $builder->where('e.class_id', $classID);
        }
        if (!empty($SectionID)){
            $builder->where('e.section_id', $SectionID);
        }
        if ($paymentVia != 'all') {
            if ($paymentVia == 'online') {
               $builder->where('h.collect_by', 'online');
            } else {
                $builder->where('h.collect_by !=', 'online');
            }
        }
        $builder->orderBy('h.id', 'asc');
        $result = $builder->get()->getResultArray();
        return $result;
    }


    public function getStuPaymentReport($classID='', $sectionID, $studentID, $typeID, $start, $end, $branchID)
    {
        $builder = $this->db->table('fee_payment_history as h')->select('h.*,gd.due_date,ft.name as type_name,e.student_id,e.roll,s.first_name,s.last_name,s.register_no,pt.name as pay_via')
        ->join('fee_allocation as fa', 'fa.id = h.allocation_id', 'inner')
        ->join('fees_type as ft', 'ft.id = h.type_id', 'left')
        ->join('fee_groups_details as gd', 'gd.fee_groups_id = fa.group_id and gd.fee_type_id = h.type_id', 'left')
        ->join('enroll as e', 'e.student_id = fa.student_id', 'inner')
        ->join('student as s', 's.id = e.student_id', 'left')
        ->join('payment_types as pt', 'pt.id = h.pay_via', 'left')
        ->where('fa.session_id', get_session_id())
        ->where('h.date  >=', $start)
        ->where('h.date <=', $end)
        ->where('e.branch_id', $branchID)
        ->where('e.class_id', $classID);
        if (!empty($typeID)) {
            $typeID = explode("|", $typeID);
            $builder->where('h.type_id', $typeID[1]);
        }
        if (!empty($studentID)) {
            $builder->where('e.student_id', $studentID);
        }
        $builder->where('e.section_id', $sectionID);
        $builder->orderBy('h.id', 'asc');
        $result = $builder->get()->getResultArray();
        return $result;
    }


    function getfeeGroup($studentID)
    {
        $builder = $this->db->table('fee_allocation as fa')->select('g.name');
        $builder->join('fee_groups as g', 'g.id = fa.group_id', 'inner');
        $builder->where('fa.student_id', $studentID);
        $builder->where('fa.session_id', get_session_id());
        return $builder->get()->getResultArray();
    }


    function reminderSave($data)
    {
    	$builder = $this->db->table('fees_reminder')->insert($arrayData);
        $arrayData = array(
            'frequency' => $data['frequency'], 
            'days' => $data['days'], 
            'student' => (isset($data['chk_student']) ? 1 : 0), 
            'guardian' => (isset($data['chk_guardian']) ? 1 : 0), 
            'message' => $data['message'], 
            'branch_id' => $data['branch_id'], 
        );
        if (!isset($data['reminder_id'])) {
            $builder->insert($arrayData);
        } else {
            $builder->where('id', $data['reminder_id']);
            $builder->update($arrayData);
        }  
    }


    function getFeeReminderByDate($date, $branch_id)
    {
        $builder = $this->db->table('fee_groups_details')->select('fee_groups_details.*,fees_type.name')
        ->join('fees_type', 'fees_type.id = fee_groups_details.fee_type_id', 'inner')
        ->where('fee_groups_details.due_date', $date)
        ->where('fees_type.branch_id', $branch_id)
        ->orderBy('fee_groups_details.id', 'asc');
        return $builder->get()->getResultArray();
    }


    function getStudentsListReminder($groupID='', $typeID='')
    {
        $sessionID = get_type_name_by_id('global_settings', 1, 'session_id');
        $builder = $this->db->table('fee_allocation as a')->select('a.id as allocation_id,CONCAT(s.first_name," ",s.last_name) as child_name,s.mobileno as child_mobileno,pr.name as guardian_name,pr.mobileno as guardian_mobileno')
        ->join('student as s','s.id = a.student_id', 'inner')
        ->join('parent as pr','pr.id = s.parent_id', 'left')
        ->where('a.group_id', $groupID)
        ->where('a.session_id', $sessionID);
        $result = $builder->get()->getResultArray();
        foreach ($result as $key => $value) {
            $result[$key]['payment'] = $this->getPaymentDetailsByTypeID($value['allocation_id'], $typeID);
        }
        return $result;
    }


    function getPaymentDetailsByTypeID($allocationID, $typeID)
    {
        $builder = $this->db->table('fee_payment_history')->select('IFNULL(SUM(amount), 0) as total_paid, IFNULL(SUM(discount), 0) as total_discount')
        ->where('allocation_id', $allocationID)
        ->where('type_id', $typeID);
        return $builder->get()->getRowArray();
    }


    public function depositAmountVerify($amount)
    {
        if ($amount != "") {
            $typeID = $this->request->getVar('fees_type');
            $feesType = explode("|", $typeID);
            $remainAmount = $this->getBalance($feesType[0], $feesType[1]);
            $discount = (isset($_POST['discount_amount']) ? $_POST['discount_amount'] : 0);
            $depositAmount = $amount + $discount;
            if ($remainAmount['balance'] < $depositAmount) {
                $this->validation->set_message('deposit_verify', 'Amount cannot be greater than the remaining.');
                return false;
            } else {
                return true;
            }
        }
        return true;
    }


    public function getBalance($allocationID, $typeID)
    {
        $groupsID = get_type_name_by_id('fee_allocation', $allocationID, 'group_id');
        $getType = $this->db->table('fee_groups_details')->select('amount')->where(array('fee_groups_id' => $groupsID, 'fee_type_id' => $typeID))->get()->getRowArray();

        $builder = $this->db->table('fee_payment_history as p')->select('IFNULL(sum(p.amount), 0) as total_amount,IFNULL(sum(p.discount), 0) as total_discount,IFNULL(sum(p.fine), 0) as total_fine')
        ->where('p.allocation_id', $allocationID)
        ->where('p.type_id', $typeID);
        $paid = $builder->get()->getRowArray();
        $balance = $getType['amount'] - ($paid['total_amount'] + $paid['total_discount']);
        $total_fine = $paid['total_fine'];
        return array('balance' => $balance, 'fine' => $total_fine);
    }


    // voucher transaction save function
    public function saveTransaction($data)
    {
        $branchID   = $this->application_model->get_branch_id();
        $accountID  = $data['account_id'];
        $date       = $data['date'];
        $amount     = $data['amount'];

        // get the current balance of the selected account
        $qbal   = $this->app_lib->get_table('accounts', $accountID, true);
        $cbal   = $qbal['balance'];
        $bal    = $cbal + $amount;
        // query system voucher head / insert
        $arrayHead = array(
            'name'      => 'Student Fees Collection',
            'type'      => 'income',
            'system'    => 1,
            'branch_id' => $branchID
        );
        $builder = $this->db->table('voucher_head')->where($arrayHead);
        $query =$builder->get();
        if ($query->getNumRows() > 0) {
            $voucher_headID = $query->getRow()->id;
        } else {
            $builder->insert($arrayHead);
            $voucher_headID = $this->db->insertID();
        }
        // query system transactions / insert
        $arrayTransactions =array(
            'account_id'        => $accountID,
            'voucher_head_id'   => $voucher_headID,
            'type'              => 'deposit',
            'system'            => 1,
            'date'              => date("Y-m-d", strtotime($date)),
            'branch_id'         => $branchID
        );
        $builder = $this->db->table('transactions')->where($arrayTransactions);
        $query = $builder->get();
        if ($query->getNumRows() == 1) {
            $builder->set('amount', 'amount+' . $amount, FALSE);
            $builder->set('cr', 'cr+' . $amount, FALSE);
            $builder->set('bal', $bal);
            $builder->where('id', $query->getRow()->id);
            $builder->update('transactions');
        } else {
            $arrayTransactions['ref']           = '';
            $arrayTransactions['amount']        = $amount;
            $arrayTransactions['dr']            = 0;
            $arrayTransactions['cr']            = $amount;
            $arrayTransactions['bal']           = $bal;
            $arrayTransactions['pay_via']       = 5;
            $arrayTransactions['description']   = date("d-M-Y", strtotime($date)) . " Total Fees Collection";
            $builder->insert($arrayTransactions);
        }

        $builder = $this->db->table('accounts')->where('id', $accountID);
        $builder->update(array('balance' => $bal));
    }




} /* End Class*/