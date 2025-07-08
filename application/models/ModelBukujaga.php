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
    public function getPagu($whr)
    {
        $this->db->select('total_pagu_awal');
        $this->db->from('t_pagu');
        $this->db->where($whr);
        $this->db->where('is_perubahan', $this->session->userdata('is_perubahan'));
        $q = $this->db->get();
        return $q->row();
    }
    public function getPaguRealisasi($whr)
    {
        $this->db->select_sum('jumlah');
        $this->db->from('spj');
        $this->db->where($whr);
        $this->db->where('is_perubahan', $this->session->userdata('is_perubahan'));
        $q = $this->db->get();
        return $q->row();
    }
}
