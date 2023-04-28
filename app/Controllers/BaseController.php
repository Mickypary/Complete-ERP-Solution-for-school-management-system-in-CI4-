<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Libraries\App_lib;


/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    public $application_model;
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ["url","form",];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;
    protected $session;


    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->session = \Config\Services::session();

        // For getting instances of the URL class for manipulation in App
        $currentURL = current_url();
        $this->uri = new \CodeIgniter\HTTP\URI($currentURL);

        // print_r((string)$this->uri);
        // print_r($this->uri->getPath());
        // echo (string)$this->uri->getPath();
        // echo (string)$this->uri->getHost();
        // echo (string)$this->uri->getTotalSegments();
        // echo (string)$this->uri->getSegment(3);

        

        // Declare anything here example models and controller;

        $this->db = \Config\Database::connect();


        $get_config = $this->db->table('global_settings')->where(array('id'=>1))->get()->getRowArray();
        $this->data['global_config'] = $get_config;

        $this->data['theme_config'] = $this->db->table('theme_settings')->where(array('id'=>1))->get()->getRowArray();
    }


}
