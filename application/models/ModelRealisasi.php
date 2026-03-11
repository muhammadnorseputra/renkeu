<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelRealisasi extends CI_Model
{
    public function getPeriodeById($id)
    {
        return $this->db->select('nama')->get_where('t_periode', ['id' => $id]);
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
    public function getIndikator($whr, $part_id = null)
    {
        $this->db->select('t.*,i.id AS indikator_id, i.nama, j.nama AS jenis_indikator, j.color, p.singkatan AS part_name');
        $this->db->from('ref_indikators AS i');
        $this->db->join('t_target AS t', 't.fid_indikator=i.id', 'left');
        $this->db->join('ref_jenis_indikators AS j', 'i.fid_jenis_indikator=j.id', 'left');
        $this->db->join('ref_parts AS p', 'i.fid_part=p.id', 'left');
        $this->db->where($whr);
        if (!empty($part_id)) {
            $this->db->where("FIND_IN_SET('{$part_id}', i.fid_part) >", 0);
        }
        $this->db->order_by('i.id', 'asc');
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
    public function getRealisasiSasaran($periode_start = '', $periode_end = '', $sasaranId, $ta)
    {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj AS s');
        $this->db->join('ref_programs AS r', 's.fid_program=r.id');
        $this->db->join('ref_sasaran AS sa', 'r.fid_sasaran=sa.id');
        $this->db->where('s.is_status', 'SELESAI');
        if (!empty($periode_start) && !empty($periode_end)) {
            $this->db->where('s.fid_periode >=', $periode_start);
            $this->db->where('s.fid_periode <=', $periode_end);
        }
        if (!empty($periode_start) && empty($periode_end)) {
            $this->db->where('s.fid_periode', $periode_start);
        }
        $this->db->where('sa.id', $sasaranId);
        $this->db->where('s.tahun', $ta);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getRealisasiTujuan($periode_start = '', $periode_end = '', $tujuanId, $ta)
    {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj AS s');
        $this->db->join('ref_programs AS r', 's.fid_program=r.id');
        $this->db->join('ref_sasaran AS sa', 'r.fid_sasaran=sa.id');
        $this->db->join('ref_tujuan AS t', 'sa.fid_tujuan=t.id');
        $this->db->where('s.is_status', 'SELESAI');
        if (!empty($periode_start) && !empty($periode_end)) {
            $this->db->where('s.fid_periode >=', $periode_start);
            $this->db->where('s.fid_periode <=', $periode_end);
        }
        if (!empty($periode_start) && empty($periode_end)) {
            $this->db->where('s.fid_periode', $periode_start);
        }
        $this->db->where('t.id', $tujuanId);
        $this->db->where('s.tahun', $ta);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getRealisasiProgram($periode_start = '', $periode_end = '', $programId)
    {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj AS s');
        $this->db->where('is_status', 'SELESAI');
        if (!empty($periode_start) && !empty($periode_end)) {
            $this->db->where('s.fid_periode >=', $periode_start);
            $this->db->where('s.fid_periode <=', $periode_end);
        }
        if (!empty($periode_start) && empty($periode_end)) {
            $this->db->where('s.fid_periode', $periode_start);
        }
        $this->db->where('fid_program', $programId);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getRealisasiTahunProgram($kode_program, $ta)
    {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj_riwayat AS s');
        $this->db->where('s.is_status', 'APPROVE');
        $this->db->where('s.kode_program', $kode_program);
        $this->db->where('s.tahun', $ta);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getRealisasiKegiatan($periode_start = '', $periode_end = '', $kegiatanId)
    {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj AS s');
        $this->db->where('is_status', 'SELESAI');
        if (!empty($periode_start) && !empty($periode_end)) {
            $this->db->where('s.fid_periode >=', $periode_start);
            $this->db->where('s.fid_periode <=', $periode_end);
        }
        if (!empty($periode_start) && empty($periode_end)) {
            $this->db->where('s.fid_periode', $periode_start);
        }
        $this->db->where('fid_kegiatan', $kegiatanId);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getRealisasiTahunanKegiatan($kegiatanId)
    {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj AS s');
        $this->db->where('is_status', 'SELESAI');
        $this->db->where('fid_kegiatan', $kegiatanId);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getRealisasiSubKegiatan($periode_start = '', $periode_end = '', $subKegiatanId)
    {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj AS s');
        $this->db->where('is_status', 'SELESAI');
        if (!empty($periode_start) && !empty($periode_end)) {
            $this->db->where('s.fid_periode >=', $periode_start);
            $this->db->where('s.fid_periode <=', $periode_end);
        }
        if (!empty($periode_start) && empty($periode_end)) {
            $this->db->where('s.fid_periode', $periode_start);
        }
        $this->db->where('fid_sub_kegiatan', $subKegiatanId);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getRealisasiTahunanSubKegiatan($subKegiatanId)
    {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj AS s');
        $this->db->where('is_status', 'SELESAI');
        $this->db->where('fid_sub_kegiatan', $subKegiatanId);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getRealisasiTahunanUraian($uraian_id, $status)
    {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj AS s');
        if (count($status) > 0):
            $this->db->where_in('is_status', $status);
        else:
            $this->db->where_in('is_status !=', $status);
        endif;
        $this->db->where('is_status !=', 'SELESAI_TMS');
        $this->db->where('is_status !=', 'SELESAI_BTL');
        $this->db->where('fid_uraian', $uraian_id);
        $this->db->where('tahun', $this->session->userdata('tahun_anggaran'));
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getRealisasiByPeriode($uraian_id, $status, $periode)
    {
        $this->db->select_sum('s.jumlah');
        $this->db->from('spj AS s');
        if (count($status) > 0):
            $this->db->where_in('is_status', $status);
        else:
            $this->db->where_in('is_status !=', $status);
        endif;
        $this->db->where('is_status !=', 'SELESAI_TMS');
        $this->db->where('is_status !=', 'SELESAI_BTL');
        $this->db->where('fid_uraian', $uraian_id);
        $this->db->where('tahun', $this->session->userdata('tahun_anggaran'));
        $this->db->where_in("fid_periode", $periode);
        $q = $this->db->get();
        return $q->row()->jumlah;
    }
    public function getRealisasiByIndikatorId($periode_start = '', $periode_end = '', $indikator_id, $ta)
    {
        $this->db->select_sum('persentase');
        $this->db->select_sum('eviden');
        $this->db->select('eviden_jenis, eviden_link, faktor_pendorong, faktor_penghambat, tindak_lanjut, is_jenis, status');
        $this->db->from('t_realisasi');
        $this->db->where('fid_indikator', $indikator_id);
        if (!empty($periode_start) && !empty($periode_end)) {
            $this->db->where('fid_periode >=', $periode_start);
            $this->db->where('fid_periode <=', $periode_end);
            $this->db->where('status', 'SETUJU');
        }
        if (!empty($periode_start) && empty($periode_end)) {
            $this->db->where('fid_periode', $periode_start);
        }
        $this->db->where('tahun', $ta);
        $q = $this->db->get();
        return $q;
    }

    public function getAkumulasiRealisasi($tahun, $indikator_id)
    {
        $this->db->select_sum('persentase');
        $this->db->select_sum('eviden');
        $this->db->select('eviden_jenis, eviden_link, faktor_pendorong, faktor_penghambat, tindak_lanjut, status');
        $this->db->from('t_realisasi');
        $this->db->where(['fid_indikator' => $indikator_id, 'tahun' => $tahun, 'status' => 'SETUJU']);
        $q = $this->db->get();
        return $q;
    }

    public function getRealisasiTahunanByIndikatorId($indikator_id)
    {
        $this->db->select_sum('persentase');
        $this->db->select_sum('eviden');
        $this->db->select('eviden_jenis,faktor_pendorong,faktor_penghambat,tindak_lanjut');
        $this->db->from('t_realisasi');
        $this->db->where(['fid_indikator' => $indikator_id]);
        $q = $this->db->get();
        return $q;
    }

    public function getFaktors($tahun, $id = "")
    {
        $this->db->select('*');
        $this->db->from('t_faktors');
        $this->db->where('tahun', $tahun);
        if (!empty($id)) {
            $this->db->where('id', $id);
        }
        $q = $this->db->get();
        return $q->row();
    }

    public function faktor($indikator_id, $periode)
    {
        $this->db->select('faktor_pendorong, faktor_penghambat, tindak_lanjut');
        $this->db->from('t_realisasi');
        $this->db->where('fid_indikator', $indikator_id);
        $this->db->where('fid_periode', $periode);
        $this->db->where('status', 'SETUJU');
        $q = $this->db->get();
        return $q->row();
    }

    public function isStatusVerifikasi($periode_id, $indikator_id)
    {
        $this->db->select('status');
        $this->db->from('t_realisasi');
        $this->db->where(['fid_periode' => $periode_id, 'fid_indikator' => $indikator_id]);
        $q = $this->db->get();
        return $q->row()->status ?? 'ENTRI';
    }
}