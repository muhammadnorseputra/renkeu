<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelSettings extends CI_Model {
	public function getSetting($where)
    {
       $db = $this->db->get_where('t_settings',$where);
       if($db->num_rows() > 0):
        $row = $db->row();
        $result = $row->val;
       else:
        $result = '';
       endif; 
       return $result;
    }
}