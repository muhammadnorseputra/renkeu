<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelRealisasi extends CI_Model {
	public function kegiatans($programId, $partId=null)
    {
        $this->db->select('id,nama');
        $this->db->from('ref_kegiatans');
        if($partId != null) {
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
        $this->db->select('*');
        $this->db->from('ref_indikators');
        $this->db->where($whr);
        $q = $this->db->get();
        return $q;
    }
    public function getAlokasiPaguProgram($programId)
    {
        $this->db->select_sum('p.total_pagu_awal');
        $this->db->from('t_pagu AS p');
        $this->db->join('ref_sub_kegiatans AS sub', 'p.fid_sub_kegiatan=sub.id');
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
        $this->db->join('ref_sub_kegiatans AS sub', 'p.fid_sub_kegiatan=sub.id');
        $this->db->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id');
        $this->db->where('sub.fid_kegiatan', $kegiatanId);
        $q = $this->db->get();
        return $q;
    }
    public function getRealisasiKegiatan($periodeId,$kegiatanId) {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj AS s');
        $this->db->where('is_status', 'SELESAI');
        $this->db->where('fid_periode', $periodeId);
        $this->db->where('fid_kegiatan', $kegiatanId);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getRealisasiSubKegiatan($periodeId,$subKegiatanId) {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj AS s');
        $this->db->where('is_status', 'SELESAI');
        $this->db->where('fid_periode', $periodeId);
        $this->db->where('fid_sub_kegiatan', $subKegiatanId);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
}