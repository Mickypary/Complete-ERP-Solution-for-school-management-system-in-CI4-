<?php

namespace App\Controllers;

use CodeIgniter\Controller;


/**
 * 
 */
class Translations extends BaseController
{
	public $image;
	public $dbforge;
	
	function __construct()
	{
		$this->image = \Config\Services::image();
		$this->dbforge = \Config\Database::forge();
	}

	public function index()
	{
		if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

		if (!get_permission('translations', 'is_view')) {
			access_denied();
		}

		$this->data['edit_language'] = '';
        $this->data['sub_page'] = 'language/index';
        $this->data['main_menu'] = 'settings';
        $this->data['title'] = translate('translations');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
                'vendor/bootstrap-toggle/css/bootstrap-toggle.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
                'vendor/bootstrap-toggle/js/bootstrap-toggle.min.js',
            ),
        );
        return view('layout/index', $this->data);
	}


	public function set_language($action = '')
    {
        if (is_loggedin()) {
            $this->session->set('set_lang', $action);
            if (!empty($_SERVER['HTTP_REFERER'])) {
            	return redirect()->to($_SERVER['HTTP_REFERER']);
            } else {
                return redirect()->to(base_url('dashboard'));
            }
        } else {
            return redirect()->to(base_url());
        }
    }


    public function update()
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }
        
        if (!get_permission('translations', 'is_edit')) {
            access_denied();
        }
        $language = esc($this->request->getGet('lang'));
        if (!empty($language)) {
            $query_language = $this->db->query("SELECT `id`, `word`, `$language` FROM `languages`");
            if ($this->request->getVar('submit') == 'update') {
                if ($query_language->getNumRows() > 0) {
                    $words = $query_language->getResult();
                    foreach ($words as $row) {
                        $word = $this->request->getVar('word_' . $row->word);
                        if (!empty($word)) {
                            $builder = $this->db->table('languages')->where('word', $row->word);
                            $builder->update(array($language => $word));
                        }
                    }
                    $builder = $this->db->table('language_list')->where('lang_field', $language);
                    $builder->update(array(
                        'updated_at' => date('Y-m-d H:i:s'),
                    ));
                }
                set_alert('success', translate('information_has_been_updated_successfully'));
                return redirect()->to(base_url('translations'));
            }
            $this->data['select_language'] = $language;
            $this->data['query_language'] = $query_language;
            $this->data['sub_page'] = 'language/index';
            $this->data['main_menu'] = 'settings';
            $this->data['title'] = translate('translations');
            return view('layout/index', $this->data);
        } else {
            $this->session->set('last_page', current_url());
            return redirect()->to(base_url());
        }
    }


	public function submitted_data($action = '', $id = '')
    {
    	if (!is_loggedin()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(base_url().'authentication');
        }

        if ($action == 'create') {
            if (!get_permission('translations', 'is_add')) {
                access_denied();
            }
            $language = $this->request->getVar('name');
            $builder = $this->db->table('language_list')->insert(array('name' => ucfirst($language)));
            $id = $this->db->insertID();

            if (!empty($_FILES["flag"]["name"])) {
                move_uploaded_file($_FILES['flag']['tmp_name'], 'uploads/language_flags/flag_' . $id . '.png');
                $this->create_thumb('uploads/language_flags/flag_' . $id . '.png');
            }

            // $language = 'lang_' . $id;
            $language = strtolower($language);
            $builder = $this->db->table('language_list')->where('id', $id);
            $builder->update(array(
                'lang_field' => $language,
            ));

            $fields = array(
                $language => array(
                    'type' => 'LONGTEXT',
                    'collation' => 'utf8_unicode_ci',
                    'null' => true,
                    'default' => '',
                ),
            );

            $res = $this->dbforge->addColumn('languages', $fields);
            if ($res == true) {
                set_alert('success', translate('information_has_been_saved_successfully'));
            } else {
                set_alert('error', translate('information_add_failed'));
            }
            return redirect()->to('translations');
        }

        if ($action == 'rename') {
            if (!get_permission('translations', 'is_edit')) {
                access_denied();
            }
            $language = $this->request->getVar('rename');
            $builder = $this->db->table('language_list')->where('id', $id);
            $builder->update(array(
                'name' => $language,
            ));

            if (!empty($_FILES["flag"]["name"])) {
                move_uploaded_file($_FILES['flag']['tmp_name'], 'public/uploads/language_flags/flag_' . $id . '.png');
                $this->create_thumb('public/uploads/language_flags/flag_' . $id . '.png');
            }

            set_alert('success', translate('information_has_been_updated_successfully'));
            return redirect()->to('translations');
        }

        if ($action == 'delete') {
            if (!get_permission('translations', 'is_delete')) {
                access_denied();
            }
            $lang = $this->db->select('lang_field')->where('id', $id)->get('language_list')->row()->lang_field;
            $this->load->dbforge();
            $this->dbforge->drop_column('languages', $lang);
            $this->db->where('id', $id);
            $this->db->delete('language_list');
            if (file_exists('uploads/language_flags/flag_' . $id . '.png')) {
                unlink('uploads/language_flags/flag_' . $id . '.png');
                unlink('uploads/language_flags/flag_' . $id . '_thumb.png');
            }
        }
    }


    public function create_thumb($source)
    {
        ini_set('memory_limit', '-1');
        $this->image->withFile($source)->resize(32,22,true,'height')->save(FCPATH . $source);
    }


	/* language publish/unpublished */
    public function status()
    {
        if (is_superadmin_loggedin()) {
            $id = $this->request->getVar('lang_id');
            $status = $this->request->getVar('status');
            if ($status == 'true') {
                $array_data['status'] = 1;
                $message = translate('language_published');
            } else {
                $array_data['status'] = 0;
                $message = translate('language_unpublished');
            }
            $builder = $this->db->table('language_list')->where('id', $id);
            $builder->update($array_data);
            echo $message;
        }
    }


    public function get_details()
    {
        $id = $this->request->getVar('id');
        $builder = $this->db->table('language_list')->where('id', $id);
        $query = $builder->get();
        $result = $query->getRowArray();
        echo json_encode($result);
    }




} /* End Class */