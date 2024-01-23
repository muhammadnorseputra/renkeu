<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelAuth extends CI_Model {
	public function cek_login($table, $user, $pass)
    {
        $this->db->where('username', $user)->where('password', $pass);
        // $this->db->or_where('nohp', $user)->where('password', $pass);
        // $this->db->or_where('nip', $user)->where('password', $pass);
        return $this->db->get($table);
    }
}