<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ModelTarget extends CI_Model
{
    public function getNama($tbl, $id)
    {
        return $this->db->get_where($tbl, ['id' => $id])->row()->nama;
    }

    public function getTarget($indikator_id)
    {
        return $this->db->select('persentase, eviden_jumlah')->from('t_target')->where('fid_indikator', $indikator_id)->get()->row();
    }

    public function program($sasaran_id = null, $part_id = null, $ta)
    {
        $this->db->select('p.*');
        $this->db->from('ref_programs AS p');
        $this->db->join('ref_sasaran AS q', 'p.fid_sasaran=q.id', 'left');
        $this->db->where('p.tahun', $ta);
        if ($sasaran_id !== null) {
            $this->db->where('p.fid_sasaran', $sasaran_id);
        }
        if (!empty($part_id) && $this->session->userdata('role') === "USER") {
            $this->db->where("FIND_IN_SET('{$part_id}', p.fid_part) >", 0);
        }
        $this->db->group_by('p.id');
        // $this->db->order_by('p.id', 'desc');
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

    public function getTujuan($whr)
    {
        $this->db->select('t.*');
        $this->db->from('ref_tujuan AS t');
        $this->db->where($whr);
        $this->db->order_by('t.id', 'asc');
        $q = $this->db->get();
        return $q;
    }

    public function getSasaran($whr)
    {
        $this->db->select('t.*');
        $this->db->from('ref_sasaran AS t');
        $this->db->where($whr);
        $this->db->order_by('t.id', 'asc');
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
    public function getIndikator($whr, $part_id = null)
    {
        $this->db->select('t.*,i.fid_part,i.fid_jenis_indikator, i.id AS indikator_id, i.nama, j.nama AS jenis_indikator, j.color');
        $this->db->from('ref_indikators AS i');
        $this->db->join('ref_jenis_indikators AS j', 'i.fid_jenis_indikator=j.id', 'left');
        $this->db->join('t_target AS t', 't.fid_indikator=i.id', 'left');
        $this->db->where($whr);
        if (!empty($part_id) && $this->session->userdata('role') === 'USER') {
            $this->db->where("FIND_IN_SET('{$part_id}', i.fid_part) >", 0);
        }
        $this->db->order_by('i.id', 'asc');
        $q = $this->db->get();
        return $q;
    }

    public function getAlokasiPaguTujuan($tujuanId, $is_perubahan = "0", $ta)
    {
        $this->db->select_sum('p.total_pagu_awal');
        $this->db->from('t_pagu AS p');
        $this->db->join('ref_uraians AS u', 'p.fid_uraian=u.id');
        $this->db->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id');
        $this->db->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id');
        $this->db->join('ref_programs AS pro', 'keg.fid_program=pro.id');
        $this->db->join('ref_sasaran AS s', 'pro.fid_sasaran=s.id');
        $this->db->where('s.fid_tujuan', $tujuanId);
        $this->db->where('p.tahun', $ta);
        $this->db->where('p.is_perubahan', $is_perubahan);
        $q = $this->db->get();
        return $q;
    }

    public function getAlokasiPaguSasaran($sasaranId, $is_perubahan = "0", $ta)
    {
        $this->db->select_sum('p.total_pagu_awal');
        $this->db->from('t_pagu AS p');
        $this->db->join('ref_uraians AS u', 'p.fid_uraian=u.id');
        $this->db->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id');
        $this->db->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id');
        $this->db->join('ref_programs AS pro', 'keg.fid_program=pro.id');
        $this->db->where('pro.fid_sasaran', $sasaranId);
        $this->db->where('p.tahun', $ta);
        $this->db->where('p.is_perubahan', $is_perubahan);
        $q = $this->db->get();
        return $q;
    }
    public function getAlokasiPaguProgram($programId, $is_perubahan = "0", $ta)
    {
        $this->db->select_sum('p.total_pagu_awal');
        $this->db->from('t_pagu AS p');
        $this->db->join('ref_uraians AS u', 'p.fid_uraian=u.id');
        $this->db->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id');
        $this->db->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id');
        $this->db->join('ref_programs AS pro', 'keg.fid_program=pro.id');
        $this->db->where('keg.fid_program', $programId);
        $this->db->where('p.tahun', $ta);
        $this->db->where('p.is_perubahan', $is_perubahan);
        $q = $this->db->get();
        return $q;
    }
    public function getAlokasiPaguKegiatan($kegiatanId, $is_perubahan = "0", $ta)
    {
        $this->db->select_sum('p.total_pagu_awal');
        $this->db->from('t_pagu AS p');
        $this->db->join('ref_uraians AS u', 'p.fid_uraian=u.id');
        $this->db->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id');
        $this->db->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id');
        $this->db->where('sub.fid_kegiatan', $kegiatanId);
        $this->db->where('p.tahun', $ta);
        $this->db->where('p.is_perubahan', $is_perubahan);
        $q = $this->db->get();
        return $q;
    }
    public function getAlokasiPaguSubKegiatan($subKegiatanId, $is_perubahan = "0", $ta)
    {
        $this->db->select_sum('p.total_pagu_awal');
        $this->db->from('t_pagu AS p');
        $this->db->join('ref_uraians AS u', 'p.fid_uraian=u.id');
        $this->db->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id');
        $this->db->where('u.fid_sub_kegiatan', $subKegiatanId);
        $this->db->where('p.tahun', $ta);
        $this->db->where('p.is_perubahan', $is_perubahan);
        $q = $this->db->get();
        return $q;
    }
    public function getAlokasiPaguUraian($uraian_id, $is_perubahan = "0")
    {
        $this->db->select_sum('p.total_pagu_awal');
        $this->db->from('t_pagu AS p');
        $this->db->join('ref_uraians AS u', 'p.fid_uraian=u.id');
        $this->db->where('p.fid_uraian', $uraian_id);
        $this->db->where('p.is_perubahan', $is_perubahan);
        $q = $this->db->get();
        return $q;
    }
    public function getPaguPerubahan($id)
    {
        $this->db->select('total_pagu_awal');
        $this->db->from('t_pagu');
        $this->db->where('id', $id);
        $this->db->where('is_perubahan', "1");
        $this->db->where('tahun', $this->session->userdata('tahun_anggaran'));
        $q = $this->db->get();
        return $q->row();
    }
}
