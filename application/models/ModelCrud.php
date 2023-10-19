<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelCrud extends CI_Model {

    public function get($table) {
        return $this->db->get($table);
    }

	public function getWhere($table,$where)
    {
       return $this->db->get_where($table,$where); 
    }

    public function insert($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    public function update($table, $data, $where)
    {
        $this->db->where($where);
        return $this->db->update($table, $data);
    }

    public function deleteWhere($table, $where) 
    {
        $this->db->where($where);
        return $this->db->delete($table);
    }


}