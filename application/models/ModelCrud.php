<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelCrud extends CI_Model {

    public function get($table) {
        return $this->db->get($table);
    }

	public function getWhere($table,$where)
    {
       return $this->db->get_where($table,$where); 
    }

    public function getWhereIn($table,$where)
    {
       return $this->db->where_in($where)->get($table); 
    }

    public function getLikes($table, $likes)
    {
        return $this->db->like($likes)->get($table);
    }

    public function getLikesWithOr($table, $likes)
    {
        return $this->db->like($likes)->or_like($likes)->get($table);
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

    public function updateAll($table, $data)
    {
        // $this->db->where_in('key', $whr);
        return $this->db->update_batch($table, $data, 'key');
    }

    public function deleteWhere($table, $where) 
    {
        $this->db->where($where);
        return $this->db->delete($table);
    }


}