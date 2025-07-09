<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelSelect2 extends CI_Model
{
    public function marge($search, $refPart, $refId)
    {
        $this->db->select('k.id as kegiatan_id, k.kode as kegiatan_kode, k.nama as kegiatan_nama, s.id as sub_kegiatan_id, s.kode as sub_kegiatan_kode, s.nama as sub_kegiatan_nama, u.id as uraian_id, u.kode as uraian_kode, u.nama as uraian_nama');
        $this->db->from('ref_kegiatans as k');
        $this->db->join('ref_sub_kegiatans as s', 's.fid_kegiatan=k.id', 'left');
        $this->db->join('ref_uraians as u', 'u.fid_sub_kegiatan=s.id', 'left');
        if ($this->session->userdata('role') === 'USER'):
            $this->db->where('k.fid_part', $refPart);
        endif;
        $this->db->where('k.fid_program', $refId);
        if (!empty($search)) {
            $this->db->like('u.kode', $search);
            $this->db->or_like('u.nama', $search);
        }
        $q = $this->db->get();
        return $q;
    }

    public function getKegiatan($refPart, $refId, $search)
    {
        $this->db->select('id, kode, nama');
        $this->db->from('ref_kegiatans');
        if ($this->session->userdata('role') === 'USER'):
            $this->db->where('fid_part', $refPart);
        endif;
        $this->db->where('fid_program', $refId);
        if (!empty($search)) {
            $this->db->like('kode', $search);
            $this->db->or_like('nama', $search);
        }
        $q = $this->db->get();
        return $q;
    }
    public function getChildKegiatan($q, $partid, $ta)
    {
        $this->db->select('id,fid_part,kode,nama');
        $this->db->from('ref_kegiatans');
        $this->db->like('nama', $q);
        if (!empty($q)) {
            $this->db->or_like('kode', $q);
        }
        if (isset($ta)) {
            $this->db->where('tahun', $ta);
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
        if (!empty($search)) {
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
        if (!empty($search)) {
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
