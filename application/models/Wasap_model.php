<?php

/**
 * Created by PhpStorm.
 * User: zulhisham
 * Date: 6/30/17
 * Time: 10:28 PM
 */
class Wasap_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function get_ren_detail($username)
    {
        //where statement for select list of banker from table Users
        $this->db->select('*');
        $this->db->where('username', $username);

        $query = $this->db->get('tbl_wasap');

        if ($query->num_rows() == 1)
        {
            return $query->row();
        }
        return NULL;
    }
    
}
