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
        $this->load->library('email');

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
            $this->wasaplog($username, $msg, $url);

            // send email to Zulgo
            $this->send_email_log($username, $msg, $url);

            redirect($url);
        }
    }

    // to save wasap log for every link clicked
    // additional info -> time
    function wasaplog($username, $msg = '', $url)
    {
        // set array for data (field name, value) to insert to db
        $data_to_db = array(
        // application_id auto
        'username' => $username,
        'trackingcode' => $msg,
        'url' => $url,
         // lawyer id to assign lawyer
        'created_at' => date('Y-m-d H:i:s'),
        );

        $this->wasap_model->set_wasap_log($data_to_db);
    }

    function send_email_log($username, $msg, $url)
    {
        // send email to client
        $email_from = 'ijoi7007@gmail.com';
        $email_from_name = 'Robot Gohartanah';
        //$email_to = 'gohartanah@gmail.com'; // amik email klien
        //$email_to = $email_klien;
        $email_to = 'gohartanah@gmail.com';
        $email_subject = 'Username : ' . $username . ' Tracking Code : ' .  $msg;

        $message = 'Salam Tuan Zulgo Yg Rare of The Rare<br><br> ';
        $message .= 'Username : ' . $username;
        $message .= '<br>Tracking Code : ' . $msg;
        $message .= '<br>URL : ' . $url . '<br><br>';
        $message .= 'Sekian,<br><br>Robot Gohartanah<br><br>';

        $this->_send_mail_notification($email_from, $email_from_name, $email_to, $email_subject, $message);

    }

    // refinance form - send email to klien, send email to admin.
    public function _send_mail_notification($email_from, $email_from_name, $email_to, $email_subject, $message)
    {

        $this->email->from($email_from, $email_from_name);
        $this->email->to($email_to);
        $this->email->subject($email_subject);
        $this->email->message($message);

        // send the email
        if (!$this->email->send()) {
            return false;
        } else {
            return true;
        }
    }
}


