<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\RoleModel;

/**
 * 
 */
class Role extends BaseController
{
	public $role_model;
	public $validation;
	
	function __construct()
	{
		$this->validation = \Config\Services::validation();



		$this->role_model = new RoleModel();
        if (!is_superadmin_loggedin()) {
            access_denied();
        }
	}


	// new role add
    public function index()
    {
        if (isset($_POST['save'])) {
            $rules = array(
                'role' => [
                    'label' => 'Role Name',
                    'rules' => 'required|unique_name_role[name]',
                    'errors' => [
                        'unique_name_role' => 'This Role Name already exists.',
                    ],
                ],
            );
            $this->validation->setRules($rules);
            // $this->validation->setRule('role', translate('role_name'), 'required|unique_name_role[name]');
            if ($this->validation->withRequest($this->request)->run() == false) {
 
                $this->data['validation_error'] = true;
            } else {
                // update information in the database
                $data = $this->request->getVar();
                $this->role_model->save_roles($data);
                set_alert('success', translate('information_has_been_saved_successfully'));
                return redirect()->to(base_url('role'));
            }
        }
        $this->data['roles'] = $this->role_model->getRoleList();
        $this->data['title'] = translate('roles');
        $this->data['sub_page'] = 'role/index';
        $this->data['main_menu'] = 'settings';
        return view('layout/index', $this->data);
    }


    // role edit
    public function edit($id)
    {
        if (isset($_POST['save'])) {
            $rules = array(
                'role' => [
            		'label' => 'Role Name',
            		'rules' => 'required|unique_name_role[name]',
            		'errors' => [
            			'unique_name_role' => 'This Role Name already exists.',
            		],
            	],
            );

            $this->validation->setRules($rules);
            if ($this->validation->withRequest($this->request)->run() == false) {
                $this->data['validation_error'] = true;
            } else {
                // SAVE ROLE INFORMATION IN THE DATABASE
                $data = $this->request->getVar();
                $this->role_model->save_roles($data);
                set_alert('success', translate('information_has_been_updated_successfully'));
                return redirect()->to(base_url('role'));
            }
        }
        $this->data['roles'] = $this->role_model->get('roles', array('id' => $id), true);
        $this->data['title'] = translate('roles');
        $this->data['sub_page'] = 'role/edit';
        $this->data['main_menu'] = 'test';
        return view('layout/index', $this->data);
    }

    // role delete in DB
    public function delete($role_id)
    {
        $systemRole = array(1, 2, 3, 4, 5, 6, 7);
        if (!in_array($role_id, $systemRole)) {
            $builder = $this->db->table('roles')->where('id', $role_id);
            $builder->delete();
        }
    }


    public function permission($role_id)
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

    	$builder = $this->db->table('staff_privileges');

        $roleList = $this->role_model->getRoleList();
        $allowRole = array_column($roleList, 'id');
        if (!in_array($role_id, $allowRole)) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            $role_id = $this->request->getVar('role_id');
            $privileges = $this->request->getVar('privileges');
            // echo "<pre>";
            // print_r($privileges);
            // die();
            foreach ($privileges as $key => $value) {
                $is_add = (isset($value['add']) ? 1 : 0);
                $is_edit = (isset($value['edit']) ? 1 : 0);
                $is_view = (isset($value['view']) ? 1 : 0);
                $is_delete = (isset($value['delete']) ? 1 : 0);
                $arrayData = array(
                    'role_id' => $role_id,
                    'permission_id' => $key,
                    'is_add' => $is_add,
                    'is_edit' => $is_edit,
                    'is_view' => $is_view,
                    'is_delete' => $is_delete,
                );
                $exist_privileges = $builder->select('id')->limit(1)->where(array('role_id' => $role_id, 'permission_id' => $key))->get()->getNumRows();
                // print_r($exist_privileges);
                // die();
                if ($exist_privileges > 0) {
                    $builder->where(array('role_id' => $role_id, 'permission_id' => $key))->update($arrayData);
                } else {
                    $builder->insert($arrayData);
                }
            }
            set_alert('success', translate('information_has_been_updated_successfully'));
            return redirect()->to(base_url('role/permission/' . $role_id));
        }
        $this->data['role_id'] = $role_id;
        $this->data['modules'] = $this->role_model->getModulesList();
        $this->data['title'] = translate('roles');
        $this->data['sub_page'] = 'role/permission';
        $this->data['main_menu'] = 'settings';
        return view('layout/index', $this->data);
    }





} /* End CLass*/