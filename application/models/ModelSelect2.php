<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelSelect2 extends CI_Model
{
    public function getChildProgram($q)
    {
        $this->db->select('u.id as uraian_id, u.kode as uraian_kode, u.nama as uraian_nama, p.nama as program_nama');
        $this->db->from('ref_uraians as u');
        $this->db->join('ref_sub_kegiatans as s', 'u.fid_sub_kegiatan=s.id');
        $this->db->join('ref_kegiatans as k', 's.fid_kegiatan=k.id');
        $this->db->join('ref_programs as p', 'k.fid_program=p.id');
        // $this->db->where("FIND_IN_SET('{$part}', p.fid_part) >", 0);
        // $this->db->where('p.tahun', $ta);
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('u.kode', $q);
            $this->db->or_like('u.nama', $q);
            $this->db->group_end();
        }
        $this->db->group_by('u.id');
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
        $this->db->select('u.id, u.kode, u.nama');
        $this->db->from('ref_uraians as u');
        $this->db->join('ref_kegiatans as k', 'u.fid_kegiatan=k.id');
        $this->db->where('u.fid_kegiatan', $kegiatanId);
        $this->db->where('u.fid_sub_kegiatan', $subKegiatanId);
        if (!empty($search)) {
            $this->db->like('u.kode', $search);
            $this->db->or_like('u.nama', $search);
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
