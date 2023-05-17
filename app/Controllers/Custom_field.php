<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\CustomFieldModel;
use App\Libraries\App_lib;

/**
 * 
 */
class Custom_field extends BaseController
{
	public $custom_field_model;
	public $app_lib;
	
	function __construct()
	{
		helper(['custom_fields']);
		$this->custom_field_model = new CustomFieldModel();
		$this->app_lib = new App_lib();
	}

	public function index()
	{
		if (!get_permission('custom_field', 'is_view')) {
            access_denied();
        }
        $this->data['customfield'] = $this->app_lib->getTable('custom_field');
        $this->data['sub_page'] = 'custom_field/index';
        $this->data['main_menu'] = 'settings';
        $this->data['title'] = translate('custom_field');
        $this->load->view('layout/index', $this->data);
	}

	public function edit($id = '')
    {
        if (!get_permission('custom_field', 'is_edit')) {
            access_denied();
        }
        $this->data['customfield'] = $this->app_lib->getTable('custom_field', array('t.id' => $id), true);
        $this->data['sub_page'] = 'custom_field/edit';
        $this->data['main_menu'] = 'settings';
        $this->data['title'] = translate('custom_field');
        $this->load->view('layout/index', $this->data);
    }

    public function save()
    {
        if (isset($data['custom_field_id'])) {
            if (!get_permission('custom_field', 'is_edit')) {
                ajax_access_denied();
            }
        } else {
            if (!get_permission('custom_field', 'is_add')) {
                ajax_access_denied();
            }
        }
        if (is_superadmin_loggedin()) {
            $this->validation->setRule('branch_id', translate('branch'), 'required');
        }
        $this->validation->setRule('belongs_to', translate('belongs_to'), 'trim|required');
        $this->validation->setRule('field_label', translate('field_label'), 'trim|required');
        $this->validation->setRule('field_type', translate('field_type'), 'trim|required');
        $this->validation->setRule('bs_column', translate('bs_column'), 'trim|required');
        $this->validation->setRule('field_order', translate('field_order'), 'trim|required|numeric');
        $field_type = $this->request->getVar('field_type');
        $default_value = '';
        if ($field_type == 'dropdown') {
            $this->validation->setRule('dropdown_default_value', translate('default_value'), 'trim|required');
            $defaultValue = $this->request->getVar('dropdown_default_value');
        } elseif ($field_type == 'checkbox') {
            $defaultValue = $this->request->getVar('checkbox_default_value');
        } else {
            $defaultValue = $this->request->getVar('com_default_value');
        }
        if ($this->validation->withRequest($this->request)->run() !== false) {
            $this->custom_field_model->saveCustom($this->request->getVar(), $defaultValue);
            set_alert('success', translate('information_has_been_saved_successfully'));
            $url = base_url('custom_field');
            $array = array('status' => 'success', 'url' => $url);
        } else {
            $error = $this->validation->getErrors();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function delete($id = '')
    {
        // check access permission
        if (get_permission('custom_field', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $builder->where('branch_id', get_loggedin_branch_id());
            }
            $builder = $this->db->table('custom_field')->where('id', $id);
            $builder->delete();
            $builder2 = $this->db->table('custom_fields_values')->where('field_id', $id);
            $builder2->delete();
        } else {
            set_alert('error', translate('access_denied'));
        }
    }

    public function getFieldsByBranch()
    {
        $belongs_to = $this->request->getVar('belongs_to');
        echo render_custom_Fields($belongs_to);
    }

    public function status()
    {
        if (!get_permission('custom_field', 'is_edit')) {
            ajax_access_denied();
        }
        $id = $this->request->getVar('id');
        $status = $this->request->getVar('status');
        if ($status == 'true') {
            $arrayData['status'] = 1;
        } else {
            $arrayData['status'] = 0;
        }
        if (!is_superadmin_loggedin()) {
            $builder->where('branch_id', get_loggedin_branch_id());
        }
        $builder = $this->db->table('custom_field')->where('id', $id);
        $builder->update($arrayData);
        $return = array('msg' => translate('information_has_been_updated_successfully'), 'status' => true);
        echo json_encode($return);
    }


} /*End Class */