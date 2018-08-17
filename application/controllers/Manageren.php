<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manageren extends CI_Controller
{

    function __construct()
    {
        // this is your constructor
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->model('wasap_model');
        $this->load->library('grocery_CRUD');
    }

    public function index()
    {
        $this->grocery_crud->set_table('tbl_ren');

        $output = $this->grocery_crud->render();

        $this->_e_output((array)$output);
    }

    public function _e_output($output = null)
    {
        //$this->load->view('example', $output);
        $this->load->view('ren-crud', (array)$output);
    }
}


