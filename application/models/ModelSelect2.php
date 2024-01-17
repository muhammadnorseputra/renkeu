<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelSelect2 extends CI_Model {
    public function getKegiatan($refPart, $refId, $search)
    {
        $this->db->select('id, kode, nama');
        $this->db->from('ref_kegiatans');
        if(!empty($search)) {
            $this->db->like('kode', $search);
            $this->db->or_like('nama', $search);
        }
            $this->db->where('fid_part', $refPart);
            $this->db->where('fid_program', $refId);

            $q = $this->db->get();
        return $q;
    }
	public function getSubKegiatan($refId, $search)
    {
        $this->db->select('id, kode, nama');
        $this->db->from('ref_sub_kegiatans');
        if(!empty($search)) {
            $this->db->like('kode', $search);
            $this->db->or_like('nama', $search);
        }
        $this->db->where('fid_kegiatan', $refId);
        $q = $this->db->get();
        return $q;
    }
    public function getUraian($kegiatanId, $subKegiatanId, $search)
    {
        $this->db->select('id, kode, nama');
        $this->db->from('ref_uraians');
        if(!empty($search)) {
            $this->db->like('kode', $search);
            $this->db->or_like('nama', $search);
        }
            $this->db->where('fid_kegiatan', $kegiatanId);
            $this->db->where('fid_sub_kegiatan', $subKegiatanId);

            $q = $this->db->get();
        return $q;
    }
}