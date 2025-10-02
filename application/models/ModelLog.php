<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelLog extends CI_Model {

    public function insert($data)
    {
        $this->db->trans_start();
        $this->db->insert('historis_spj', $data);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function getWhere($where)
    {
        return $this->db->order_by('created_at', 'asc')->get_where('historis_spj', $where);
    }
}