<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelBukujaga extends CI_Model
{
    public function getUraianBySubKegiatan($sub_kegiatan_id)
    {
        $this->db->select('*');
        $this->db->from('ref_uraians');
        $this->db->where('fid_sub_kegiatan', $sub_kegiatan_id);
        $q = $this->db->get();
        return $q;
    }
    public function getSPJByUraian($uraian_id) {
        $this->db->select('s.id, s.fid_uraian, s.nomor_pembukuan, s.tanggal_pembukuan, s.uraian as uraian_spj, s.jumlah');
        $this->db->from('spj as s');
        $this->db->where('s.fid_uraian', $uraian_id);
        $this->db->where('s.is_status', 'SELESAI');
        $q = $this->db->get();
        return $q;
    }
    public function getRealisasiSPJByUraian($spj_id, $whr) {
        $this->db->select('jumlah');
        $this->db->from('spj');
        $this->db->where('id', $spj_id);
        $this->db->where($whr);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getPagu($whr, $ta, $is_perubahan)
    {
        $this->db->select('total_pagu_awal');
        $this->db->from('t_pagu');
        $this->db->where($whr);
        $this->db->where('is_perubahan', $is_perubahan);
        $this->db->where('tahun', $ta);
        $q = $this->db->get();
        return $q->row();
    }
    public function getPaguRealisasi($whr, $ta)
    {
        $this->db->select_sum('jumlah');
        $this->db->from('spj');
        $this->db->where($whr);
        $this->db->where('tahun', $ta);
        $q = $this->db->get();
        return $q->row();
    }
}
