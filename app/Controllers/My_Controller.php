<?php 


namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * 
 */
class MY_Controller extends Controller
{
	public $db;
	
	function __construct()
	{
		parent::__construct();
		$this->db = \Config\Database::connect();

		$get_config = $this->db->table('global_settings')->where(array('id'=>1))->get()->getRowArray();
        $this->data['global_config'] = $get_config;
	}
}