<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wasap extends CI_Controller
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


    public function manage()
    {
        $this->grocery_crud->set_table('tbl_wasap');

        $output = $this->grocery_crud->render();

        $this->_e_output((array)$output);
    }

    public function _e_output($output = null)
    {
        //$this->load->view('example', $output);
        $this->load->view('link-crud', (array)$output);
    }

    function wasapto($username, $msg = NULL)
    {
        $url = 'https://api.whatsapp.com/send?phone=';

        $ren_row = $this->wasap_model->get_ren_detail($username);

        if(is_null($ren_row))
        {
            echo "Invalid Link Wasap. Sila laporkan kpd En Zulgo ya.. tq";
        }
        else
        {
            // assign detail to variable

            $ren_phone = $ren_row->phone;
            $ren_name = ucwords($ren_row->name);
            $ren_title = ucwords($ren_row->title);

            // append phone no.
            $url .= '6' . $ren_phone;
            $url .= '&text=';

            // Salam En Zulhisham (iRealty) safasf
            $text = 'Salam ' . $ren_title . ' ' . $ren_name . ' ( gohartanah.com )';

            // check custom msg
            if (!is_null($msg)) {
                $text .= ' - ' . $msg;
                $url .= rawurlencode($text);
            }
            else
            {
                $url .= rawurlencode($text);
            }

            // call function to insert wasap log

            redirect($url);
        }
    }
}


