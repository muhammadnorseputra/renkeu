<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelNotify extends CI_Model {
	public function getNotify($table,$where)
    {
       return $this->db->order_by('created_at','desc')->get_where($table,$where); 
    }
}