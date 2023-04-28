<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * 
 */
class DashboardModel extends Model
{
	public $db;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
	}

	public function getStaffCounter($role='', $branchID = '')
    {
        $builder = $this->db->table('staff')->select('count(staff.id) as snumber');
        $builder->join('login_credential', 'login_credential.user_id = staff.id', 'inner');
        $builder->whereNotIn('login_credential.role', [1]);
        if (!empty($role)) {
            $builder->where('login_credential.role', $role);
        }else{
            $builder->whereNotIn('login_credential.role', array(1,3,6,7));
        }
        if (!empty($branchID)) {
            $builder->where('staff.branch_id', $branchID);
        }
        return $builder->get()->getRowArray();
    }


    /* total academic students strength classes divided into charts */
    public function getStudentByClass($branchID = '')
    {
        $builder = $this->db->table('enroll as e')->select('IFNULL(COUNT(e.student_id), 0) as total_student, c.name as class_name');
        $builder->join('class as c', 'c.id = e.class_id', 'inner');
        $builder->groupBy('e.class_id');
        if (!empty($branchID)) {
            $builder->where('e.branch_id', $branchID);
        }

        $query = $builder->get();
        $data = array();
        if ($query->getNumRows() > 0) {
            $students = $query->getResult();
            foreach ($students as $row) {
                $data[] = ['value' => floatval($row->total_student), 'name' => $row->class_name];
            }
        } else {
            $data[] = ['value' => 0, 'name' => translate('not_found_anything')];
        }
        return $data;
    }


    /* annual academic fees summary charts */
    public function annualFeessummaryCharts($branchID = '', $studentID = '')
    {
        $total_fee = array();
        $total_paid = array();
        $total_due = array();
        for ($month = 1; $month <= 12; $month++) {
            $sql = "SELECT IFNULL(SUM(gd.amount), 0) as total_amount,(SELECT SUM(h.amount) FROM fee_payment_history as h where h.allocation_id = fa.id and h.type_id = gd.fee_type_id) as total_paid,(SELECT SUM(h.discount) FROM fee_payment_history as h where h.allocation_id = fa.id and h.type_id = gd.fee_type_id) as total_discount FROM fee_allocation as fa INNER JOIN fee_groups_details as gd ON gd.fee_groups_id = fa.group_id WHERE MONTH(gd.due_date) = " . $this->db->escape($month) . " AND YEAR(gd.due_date) = YEAR(CURDATE()) AND fa.session_id = " . $this->db->escape(get_session_id());
            if (!empty($branchID))
               $sql .= " AND fa.branch_id = " . $this->db->escape($branchID);
            if (!empty($studentID))
               $sql .= " AND fa.student_id = " . $this->db->escape($studentID);
            $row = $this->db->query($sql)->getRow();
            $total_fee[] = floatval($row->total_amount);
            $total_paid[] = floatval($row->total_paid);
            $total_due[] = floatval($row->total_amount - ($row->total_paid + $row->total_discount));
        };
        return array(
            'total_fee' => $total_fee,
            'total_paid' => $total_paid,
            'total_due' => $total_due,
        );
    }


    /* monthly academic cash book transaction charts */
    public function getIncomeVsExpense($branchID = '')
    {
        $query = 'SELECT IFNULL(SUM(dr),0) as dr, IFNULL(SUM(cr),0) as cr FROM transactions WHERE month(DATE) = MONTH(now()) AND year(DATE) = YEAR(now())';
        if (!empty($branchID)) {
            $query .= " AND branch_id = " . $this->db->escape($branchID);
        }
        $r = $this->db->query($query)->getRowArray();
        return array(['name' => translate("expense"), 'value' => $r['dr']], ['name' => translate("income"), 'value' => $r['cr']]);
    }


    /* annual academic fees summary charts */
    public function getWeekendAttendance($branchID = '')
    {
        $days = array();
        $employee_att = array();
        $student_att = array();
        $now = new \DateTime("6 days ago");
        $interval = new \DateInterval('P1D'); // 1 Day interval
        $period = new \DatePeriod($now, $interval, 6); // 7 Days
        foreach ($period as $day) {
            $days[] = $day->format("d-M");
            $builder = $this->db->table('student_attendance')->select('id');
            if (!empty($branchID)) {
                $builder->where('branch_id', $branchID);
            }

            $builder->where('date = "' . $day->format('Y-m-d') . '" AND (status = "P" OR status = "L")');
            $student_att[]['y'] = $builder->get()->getNumRows();

            $builder = $this->db->table('staff_attendance')->select('id');
            if (!empty($branchID)) {
                $builder->where('branch_id', $branchID);
            }

            $builder->where('date = "' . $day->format('Y-m-d') . '" AND (status = "P" OR status = "L")');
            $employee_att[]['y'] = $builder->get()->getNumRows();
        }
        return array(
            'days' => $days,
            'employee_att' => $employee_att,
            'student_att' => $student_att,
        );
    }


    public function getMonthlyAdmission($branchID = '')
    {
        $builder = $this->db->table('student as s')->select('s.id');
        $builder->join('enroll as e', 'e.student_id = s.id', 'inner');
        $builder->where('s.admission_date BETWEEN DATE_SUB(CURDATE() ,INTERVAL 1 MONTH) AND CURDATE()');
        if (!empty($branchID)) {
            $builder->where('e.branch_id', $branchID);
        }
        return $builder->get()->getNumRows();
    }


    public function getVoucher($branchID = '')
    {
        $builder = $this->db->table('transactions')->select('id');
        if (!empty($branchID)) {
            $builder->where('branch_id', $branchID);
        }
        $builder->where('date BETWEEN DATE_SUB(CURDATE() ,INTERVAL 1 MONTH) AND CURDATE()');
        return $builder->get()->getNumRows();
    }


    public function get_transport_route($branchID = '')
    {
        if (!empty($branchID)) {
            $builder = $this->db->table('transport_route')->where('branch_id', $branchID);
            return $builder->get()->getNumRows();
        }
        
    }


    public function get_total_student($branchID = '')
    {
        $builder = $this->db->table('enroll')->select('id');
        if (!empty($branchID))
            $builder->where('branch_id', $branchID);
        return $builder->get()->getNumRows();
    }







} /*End Class */