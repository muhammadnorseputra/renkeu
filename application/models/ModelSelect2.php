<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelSelect2 extends CI_Model {
    public function getKegiatan($refPart, $refId, $search)
    {
        $this->db->select('id, kode, nama');
        $this->db->from('ref_kegiatans');
        if($this->session->userdata('role') !== 'SUPER_ADMIN' 
        && $this->session->userdata('role') !== 'SUPER_USER' 
        && $this->session->userdata('role') !== 'VERIFICATOR' 
        && $this->session->userdata('role') !== 'ADMIN'):
            $this->db->where('fid_part', $refPart);
        endif;
        $this->db->where('fid_program', $refId);
        if(!empty($search)) {
            $this->db->like('kode', $search);
            $this->db->or_like('nama', $search);
        }
        $q = $this->db->get();
        return $q;
    }
    public function getChildKegiatan($q, $partid)
    {
        $this->db->select('id,fid_part,kode,nama');
        $this->db->from('ref_kegiatans');
        $this->db->like('nama', $q);
        if(!empty($q)) {
            $this->db->or_like('kode', $q);
        }
        $this->db->where(['fid_part' => $partid]);
        $q = $this->db->get();

        return $q;
    }
	public function getSubKegiatan($refId, $search)
    {
        $this->db->select('id, kode, nama');
        $this->db->from('ref_sub_kegiatans');
        $this->db->where('fid_kegiatan', $refId);
        if(!empty($search)) {
            $this->db->like('kode', $search);
            $this->db->or_like('nama', $search);
        }
        $q = $this->db->get();
        return $q;
    }
    public function getUraian($kegiatanId, $subKegiatanId, $search)
    {
        $this->db->select('id, kode, nama');
        $this->db->from('ref_uraians');
        $this->db->where('fid_kegiatan', $kegiatanId);
        $this->db->where('fid_sub_kegiatan', $subKegiatanId);
        if(!empty($search)) {
            $this->db->like('kode', $search);
            $this->db->or_like('nama', $search);
        }

            $q = $this->db->get();
        return $q;
    }
    public function cekUraianIdBySpj($uid) 
    {
        $this->db->select('id');
        $this->db->from('spj');
        $this->db->where('fid_uraian', $uid);
        $this->db->where('is_status !=', 'SELESAI');
        $this->db->where('is_status !=', 'SELESAI_TMS');
        $this->db->where('is_status !=', 'SELESAI_BTL');
        $q = $this->db->get();
        return $q;
    }
}