<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\ApplicationModel;

use Stripe;

/**
 * 
 */
class SmsModel extends Model
{
	public $application_model;
	public $db;
	
	function __construct()
	{
		$this->application_model = new ApplicationModel();
		$this->db = \Config\Database::connect();
	}


	// common function for sending sms
    public function send_sms($data = '', $id = '')
    {
        $branchID = $this->application_model->get_branch_id();
        $sms_api = $this->application_model->smsServiceProvider($branchID);
        $template = $this->db->table('sms_template_details')->getWhere(array('template_id' => $id, 'branch_id' => $branchID))->getRowArray();
        if (($template['notify_student'] == 1 || $template['notify_parent'] == 1) && $sms_api != 'disabled') {
            $student = $this->application_model->getstudentdetails($data['student_id']);
            $text = str_replace('{name}', $student['first_name'] . ' ' . $student['last_name'], $template['template_body']);
            $text = str_replace('{register_no}', $student['register_no'], $text);
            $text = str_replace('{admission_date}', $student['admission_date'], $text);
            $text = str_replace('{class}', $student['class_name'], $text);
            $text = str_replace('{section}', $student['section_name'], $text);
            $text = str_replace('{roll}', $student['roll'], $text);

            if ($id == 2) {
                $text = str_replace('{paid_amount}', $data['amount'], $text);
                $text = str_replace('{paid_date}', _d($data['paid_date']), $text);
            }

            if ($id == 4 || $id == 5) {
                $exam = $this->db->table('exam')->select('name,term_id')->where('id', $data['exam_id'])->get()->getRow();
                $subject_name = $this->db->table('subject')->select('name')->where('id', $data['subject_id'])->get()->getRow()->name;
                if (!empty($exam->term_id)) {
                    $term_name = $this->db->table('exam_term')->select('name')->where('id', $exam->term_id)->get()->getRow()->name;
                }
                $text = str_replace('{exam_name}', $exam->name, $text);
                $text = str_replace('{term_name}', $term_name, $text);
                $text = str_replace('{subject}', $subject_name, $text);
                if (!empty($data['mark'])) {
                    $text = str_replace('{marks}', $data['mark'], $text);
                }
            }

            if ($template['notify_student'] == 1) {
                if (!empty($student['mobileno'])) {
                    $this->_send($sms_api, $student['mobileno'], $text);
                }
            }

            if ($template['notify_parent'] == 1) {
                if (!empty($student['parent_id'])) {
                    $parent = $this->db->table('parent')->select('mobileno')->where('id', $student['parent_id'])->get()->getRowArray();
                    if (!empty($parent['mobileno'])) {
                        $this->_send($sms_api, $parent['mobileno'], $text);
                    }
                }
            }
        }
    }


    public function sendLiveClass($data)
    {
        $template = $this->db->table('sms_template_details')->getWhere(array('template_id' => 7, 'branch_id' => $data['branch_id']))->getRowArray();
        $sms_api = $this->application_model->smsServiceProvider($data['branch_id']);
        if (($template['notify_student'] == 1 || $template['notify_parent'] == 1) && $sms_api != 'disabled') {
            $text = str_replace('{name}', $data['fullname'], $template['template_body']);
            $text = str_replace('{register_no}', $data['register_no'], $text);
            $text = str_replace('{admission_date}', $data['admission_date'], $text);
            $text = str_replace('{class}', $data['class_name'], $text);
            $text = str_replace('{section}', $data['section_name'], $text);
            $text = str_replace('{date_of_live_class}', $data['date_of_live_class'], $text);
            $text = str_replace('{start_time}', $data['start_time'], $text);
            $text = str_replace('{end_time}', $data['end_time'], $text);
            $text = str_replace('{host_by}', $data['host_by'], $text);
            if ($template['notify_student'] == 1) {
                if (!empty($data['mobileno'])) {
                    $this->_send($sms_api, $data['mobileno'], $text);
                }
            }
            if ($template['notify_parent'] == 1) {
                if (!empty($data['parent_id'])) {
                    $parent = $this->db->table('parent')->select('mobileno')->where('id', $data['parent_id'])->get()->getRowArray();
                    if (!empty($parent['mobileno'])) {
                        $this->_send($sms_api, $parent['mobileno'], $text);
                    }
                }
            }
        }
    }


    public function _send($sms_api, $receiver, $text)
    {
        if ($sms_api == 2) {
            $res = $this->clickatell->send_message($receiver, $text);
        } elseif ($sms_api == 1) {
            $get = $this->twilio->get_twilio();
            $from = $get['number'];
            $res = $this->twilio->sms($from, $receiver, $text);
        } elseif ($sms_api == 4) {
            $res = $this->bulk->send($receiver, $text);
        } elseif ($sms_api == 3) {
            $res = $this->msg91->send($receiver, $text);
        } elseif ($sms_api == 5) {
            $res = $this->textlocal->sendSms(array($receiver), $text);
        }
    }



} /*End Class*/