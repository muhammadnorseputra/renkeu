<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelApi extends CI_Model
{
    public function tujuan($unor_id, $ta)
    {
        $this->db->select('*');
        $this->db->from('ref_tujuan');
        if (isset($unor_id)) {
            $this->db->where('fid_unor', $unor_id);
        }
        $this->db->where('tahun', $ta);
        $q = $this->db->get();
        return $q;
    }

    public function sasaran($tujuan_id, $ta)
    {
        $this->db->select('*');
        $this->db->from('ref_sasaran');
        if (isset($tujuan_id)) {
            $this->db->where('fid_tujuan', $tujuan_id);
        }
        $this->db->where('tahun', $ta);
        $q = $this->db->get();
        return $q;
    }

    public function program($partid = "", $sasaranId,  $ta)
    {
        $this->db->select('p.*');
        $this->db->from('ref_programs AS p');

        // Join only if $partid is not empty
        if (!empty($partid)) {
            $this->db->join('ref_parts AS q', "FIND_IN_SET(" . $this->db->escape_str($partid) . ", p.fid_part) > 0", 'inner');
        }

        if (!empty($partid)) {
            // Optional: if you want to ensure q.id matches exactly
            $this->db->where('q.id', $partid);
        }

        if (!empty($sasaranId)) {
            $this->db->where('p.fid_sasaran', $sasaranId);
        }

        $this->db->where('p.tahun', $ta);
        $this->db->group_by('p.id');

        return $this->db->get();
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
