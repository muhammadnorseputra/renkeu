<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ModelTarget extends CI_Model
{
    public function getNama($tbl, $id)
    {
        return $this->db->get_where($tbl, ['id' => $id])->row()->nama;
    }
    public function program($partid)
    {
        $this->db->select('p.*');
        $this->db->from('ref_parts AS q');
        $this->db->join('ref_programs AS p', 'q.fid_program=p.id');
        if ($this->session->userdata('role') !== 'SUPER_ADMIN' && $this->session->userdata('user_name') !== 'kaban' && $this->session->userdata('role') !== 'ADMIN') :
            $this->db->where('q.id', $partid);
        endif;
        $this->db->group_by('p.id');
        $q = $this->db->get();
        return $q;
    }
    public function kegiatans($programId, $partId = null)
    {
        $this->db->select('id,nama');
        $this->db->from('ref_kegiatans');
        if ($partId != null) {
            $this->db->where('fid_part', $partId);
        }
        $this->db->where('fid_program', $programId);
        $q = $this->db->get();
        return $q;
    }
    public function sub_kegiatans($kegiatanId)
    {
        $this->db->select('id,nama');
        $this->db->from('ref_sub_kegiatans');
        $this->db->where('fid_kegiatan', $kegiatanId);
        $q = $this->db->get();
        return $q;
    }
    public function getPagu($whr)
    {
        $this->db->select('total_pagu_awal');
        $this->db->from('t_pagu');
        $this->db->where($whr);
        $q = $this->db->get();
        return $q->row();
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
    public function getAlokasiPaguProgram($programId)
    {
        $this->db->select_sum('p.total_pagu_awal');
        $this->db->from('t_pagu AS p');
        $this->db->join('ref_uraians AS u', 'p.fid_uraian=u.id');
        $this->db->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id');
        $this->db->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id');
        $this->db->join('ref_programs AS pro', 'keg.fid_program=pro.id');
        $this->db->where('keg.fid_program', $programId);
        $q = $this->db->get();
        return $q;
    }
    public function getAlokasiPaguKegiatan($kegiatanId)
    {
        $this->db->select_sum('p.total_pagu_awal');
        $this->db->from('t_pagu AS p');
        $this->db->join('ref_uraians AS u', 'p.fid_uraian=u.id');
        $this->db->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id');
        $this->db->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id');
        $this->db->where('sub.fid_kegiatan', $kegiatanId);
        $q = $this->db->get();
        return $q;
    }
    public function getAlokasiPaguSubKegiatan($subKegiatanId)
    {
        $this->db->select_sum('p.total_pagu_awal');
        $this->db->from('t_pagu AS p');
        $this->db->join('ref_uraians AS u', 'p.fid_uraian=u.id');
        $this->db->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id');
        $this->db->where('u.fid_sub_kegiatan', $subKegiatanId);
        $q = $this->db->get();
        return $q;
    }
    public function getAlokasiPaguUraian($uraian_id)
    {
        $this->db->select_sum('p.total_pagu_awal');
        $this->db->from('t_pagu AS p');
        $this->db->join('ref_uraians AS u', 'p.fid_uraian=u.id');
        $this->db->where('p.fid_uraian', $uraian_id);
        $q = $this->db->get();
        return $q;
    }
}
