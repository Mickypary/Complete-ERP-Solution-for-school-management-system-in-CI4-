<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * 
 */
class Settings extends BaseController
{
	public $db;
	public $session;
	
	function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->session = \Config\Services::session();
		helper(['form']);
		
		// $data['theme_config'] = $this->db->table('theme_settings')->where(array('id'=>1))->get()->getRowArray();

		// date_default_timezone_set($get_config['timezone']);
	}

	public function index()
	{
		return redirect()->to(base_url());
	}

	public function universal()
	{
		$data = [];

		if (!is_loggedin()) {
			session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

		if (!get_permission('global_settings', 'is_view')) {
            access_denied();
        }

		if ($this->request->getMethod() == 'post') {
            if (!get_permission('global_settings', 'is_edit')) {
                access_denied();
            }
        }

        $config = array();
        if ($this->request->getVar('submit') == 'setting') {
        	foreach($this->request->getVar() as $input => $value) {
        		if ($input == 'submit') {
        			continue;
        		}
        		$config[$input] = $value;
        		unset($config['/settings/universal']);
        	}

        	if (empty($config['reg_prefix'])) {
                $config['reg_prefix'] = false;
            }
            $this->db->table('global_settings')->where('id', 1)->update($config);
            // $this->session->setFlashdata('success', translate('the_configuration_has_been_updated'));
            set_alert('success', translate('the_configuration_has_been_updated'));
            return redirect()->to(current_url());
        }

        if ($this->request->getVar('submit') == 'theme') {
            foreach ($this->request->getVar() as $input => $value) {
                if ($input == 'submit') {
                    continue;
                }
                $config[$input] = $value;
                unset($config['/settings/universal']);
            }
            $builder = $this->db->table('theme_settings')->where('id', 1);
            $builder->update($config);
            set_alert('success', translate('the_configuration_has_been_updated'));
            $this->session->setFlashdata('active', 2);
            return redirect()->to(current_url());
        }

        if ($this->request->getVar('submit') == 'logo') {
            move_uploaded_file($_FILES['logo_file']['tmp_name'], 'public/uploads/app_image/logo.png');
            move_uploaded_file($_FILES['text_logo']['tmp_name'], ROOTPATH .'public/uploads/app_image/logo-small.png');
            move_uploaded_file($_FILES['print_file']['tmp_name'], 'public/uploads/app_image/printing-logo.png');
            move_uploaded_file($_FILES['report_card']['tmp_name'], 'public/uploads/app_image/report-card-logo.png');

            move_uploaded_file($_FILES['slider_1']['tmp_name'], 'public/uploads/login_image/slider_1.jpg');
            move_uploaded_file($_FILES['slider_2']['tmp_name'], 'public/uploads/login_image/slider_2.jpg');
            move_uploaded_file($_FILES['slider_3']['tmp_name'], 'public/uploads/login_image/slider_3.jpg');

            set_alert('success', translate('the_configuration_has_been_updated'));
            $this->session->setFlashdata('active', 3);
            return redirect()->to(current_url());
        }



		$this->data['title'] = translate('global_settings');
        $this->data['sub_page'] = 'settings/universal';
        $this->data['main_menu'] = 'settings';
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
		return view('layout/index', $this->data);
	}








} /*End Class */