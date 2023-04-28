<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\ApplicationModel;

/**
 * 
 */
class EmailModel extends Model
{
	public $db;
	public $email;
    public $application_model;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->email = \Config\Services::email();
        $this->application_model = new ApplicationModel();
	}

    public function sentStaffRegisteredAccount($data)
    {
        $emailTemplate = $this->getEmailTemplates(1);
        if ($emailTemplate['notified'] == 1) {
            $role_name = get_type_name_by_id('roles', $data['user_role']);
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{name}", $data['name'], $message);
            $message = str_replace("{login_email}", $data['email'], $message);
            $message = str_replace("{password}", $data['password'], $message);
            $message = str_replace("{user_role}", $role_name, $message);
            $message = str_replace("{login_url}", base_url(), $message);
            $this->email->setFrom('mikipary@grenvilleschool.com', 'MrichTech');
            $this->email->setTo($data['email']);
            $this->email->setSubject($emailTemplate['subject']);
            $this->email->setMessage($message);
            if ($this->email->send()) {
                return true;
            }else {
                $emailerrors = $this->email->printDebugger(['headers']);
                print_r($emailerrors);
                die();
            }   
        }
    }

    public function sentStaffSalaryPay($data)
    {
        $emailTemplate = $this->getEmailTemplates(5);
        if ($emailTemplate['notified'] == 1) {
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{name}", $data['name'], $message);
            $message = str_replace("{month_year}", $data['month_year'], $message);
            $message = str_replace("{payslip_no}", $data['bill_no'], $message);
            $message = str_replace("{payslip_url}", $data['payslip_url'], $message);
            $this->email->setFrom('mikipary@grenvilleschool.com', 'MrichTech');
            $this->email->setTo($data['recipient']);
            $this->email->setSubject($emailTemplate['subject']);
            $this->email->setMessage($message);
            if ($this->email->send()) {
                return true;
            }else {
                $emailerrors = $this->email->printDebugger(['headers']);
                print_r($emailerrors);
                die();
            }    
        }
    }

    public function sentAdvanceSalary($data)
    {
        $email_alert = false;
        if ($data['status'] == 2) {
            //send advance salary approve email
            $emailTemplate = $this->getEmailTemplates(9);
            if ($emailTemplate['notified'] == 1) {
                $email_alert = true;
            }
        } elseif ($data['status'] == 3) {
            //send advance salary reject email
            $emailTemplate = $this->getEmailTemplates(10);
            if ($emailTemplate['notified'] == 1) {
                $email_alert = true;
            }
        }
        if ($email_alert == true) {
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{applicant_name}", $data['staff_name'], $message);
            $message = str_replace("{deduct_motnh}", date("F Y", strtotime($data['deduct_motnh'])), $message);
            $message = str_replace("{comments}", $data['comments'], $message);
            $message = str_replace("{amount}", $data['amount'], $message);
            $msgData['recipient'] = $data['email'];
            $msgData['subject'] = $emailTemplate['subject'];
            $msgData['message'] = $message;
            $this->sendEmail($msgData);
        }
    }

    public function sentLeaveRequest($data)
    {
        $email_alert = false;
        if ($data['status'] == 2) {
            //send leave salary approve email
            $emailTemplate = $this->getEmailTemplates(7);
            if ($emailTemplate['notified'] == 1) {
                $email_alert = true;
            }
        } elseif ($data['status'] == 3) {
            //send leave salary reject email
            $emailTemplate = $this->getEmailTemplates(8);
            if ($emailTemplate['notified'] == 1) {
                $email_alert = true;
            }
        }
        if ($email_alert == true) {
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{applicant_name}", $data['applicant'], $message);
            $message = str_replace("{start_date}", _d($data['start_date']), $message);
            $message = str_replace("{end_date}", _d($data['end_date']), $message);
            $message = str_replace("{comments}", $data['comments'], $message);
            $msgData['recipient'] = $data['email'];
            $msgData['subject'] = $emailTemplate['subject'];
            $msgData['message'] = $message;
            $this->sendEmail($msgData);
        }
    }

    public function sentAward($data)
    {
        $emailTemplate = $this->getEmailTemplates(6);
        if ($emailTemplate['notified'] == 1) {
            $userdata = $this->application_model->getUserNameByRoleID($data['role_id'], $data['user_id']);
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{winner_name}", $userdata['name'], $message);
            $message = str_replace("{award_name}", $data['award_name'], $message);
            $message = str_replace("{gift_item}", $data['gift_item'], $message);
            $message = str_replace("{award_reason}", $data['award_reason'], $message);
            $message = str_replace("{given_date}", date("Y-m-d", strtotime($data['given_date'])), $message);
            $msgData['recipient'] = $userdata['email'];
            $msgData['subject'] = $emailTemplate['subject'];
            $msgData['message'] = $message;
            $this->sendEmail($msgData);
        }
    }


	public function sentForgotPassword($data)
    {
        $emailTemplate = $this->db->table('email_templates_details')->where(['template_id' => 2, 'branch_id' => $data['branch_id']])->get()->getRowArray();
        if ($emailTemplate['notified'] == 1) {
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{username}", $data['username'] , $message);
            $message = str_replace("{name}", $data['name'], $message);
            $message = str_replace("{reset_url}", $data['reset_url'], $message);
            $this->email->setFrom('mikipary@grenvilleschool.com', 'MrichTech');
            $this->email->setTo($data['email']);
            $this->email->setSubject($emailTemplate['subject']);
            $this->email->setMessage($message);
            if ($this->email->send()) {
                return true;
            }else {
                $emailerrors = $this->email->printDebugger(['headers']);
                print_r($emailerrors);
                die();
            }        

        }
    }


    public function sendEmail($data)
    {
        $branchID = $this->application_model->get_branch_id();
        $getConfig = $this->db->get_where('email_config', array('id' => 1))->row_array();
        if ($getConfig['protocol'] == 'smtp') {
            $config = array(
                'smtp_host'     => trim($getConfig['smtp_host']),
                'smtp_port'     => trim($getConfig['smtp_port']),
                'smtp_user'     => trim($getConfig['smtp_user']),
                'smtp_pass'     => trim($getConfig['smtp_pass']),
                'smtp_crypto'   => $getConfig['smtp_encryption'],
            );
        }

        $config['protocol']     = 'smtp';
        $config['useragent']    = "CodeIgniter";
        $config['mailtype']     = "html";
        $config['newline']      = "\r\n";
        $config['charset']      = 'utf-8';
        $config['wordwrap']     = true;
        $config['smtp_timeout'] = 30;
        $this->load->library('email', $config);
        $this->email->from($getConfig['email'], get_global_setting('institute_name'));
        $this->email->to($data['recipient']);
        $this->email->subject($data['subject']);
        $this->email->message($data['message']);
        if ($this->email->send(true)) {
            return true;
        } else {
            return false;
        }
    }


    public function getEmailTemplates($id)
    {
        $branchID = $this->application_model->get_branch_id();
        $builder = $this->db->table('email_templates_details as td')->select('td.*');
        $builder->where('td.template_id', $id);
        $builder->where('td.branch_id', $branchID);
        return $builder->get()->getRowArray();
    }




} /* End Class*/