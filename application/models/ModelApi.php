<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelApi extends CI_Model
{
    public function program($partid = "", $ta)
    {
        $this->db->select('p.*');
        $this->db->from('ref_programs AS p');
        $this->db->join('ref_parts AS q', 'q.fid_program=p.id', 'left');
        if (!empty($partid)):
            $this->db->where('q.id', $partid);
        endif;
        $this->db->where('p.tahun', $ta);
        $this->db->group_by('p.id');
        $q = $this->db->get();
        return $q;
    }

    public function getPeriode($is_open, $ta)
    {
        $this->db->select('id,nama');
        $this->db->from('t_periode');
        $this->db->where('tahun', $ta);
        $this->db->where('is_open', $is_open);
        $this->db->order_by('id', 'asc');
        $q = $this->db->get();
        return $q;
    }

    public function getIndikator($whr)
    {
        $this->db->select('t.*,i.id AS indikator_id, i.nama');
        $this->db->from('ref_indikators AS i');
        $this->db->join('t_target AS t', 't.fid_indikator=i.id', 'left');
        $this->db->where($whr);
        $this->db->order_by('i.id', 'asc');
        $q = $this->db->get();
        return $q;
    }
}
