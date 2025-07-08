<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use Rakit\Validation\Validator;

class Realisasi extends RestController
{

    private $validator;
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('ModelSpj', 'spj');
        $this->load->model('ModelTarget', 'target');
        $this->load->model('ModelRealisasi', 'realisasi');
        $this->load->model('ModelUsers', 'user');
        $this->validator = new Validator;
    }


    public function index_get()
    {
        // Query parameters
        $is_perubahan = $this->query('is_perubahan');
        $tahun_anggaran = $this->query('tahun_anggaran');

        $validation = $this->validator->validate($_GET, [
            'is_perubahan'   => 'required|numeric|digits:1',
            'tahun_anggaran' => 'required|numeric|digits:4'
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ]);
            die();
        }

        // Panel Dashboard
        $db_program = $this->crud->get('ref_programs');
        $db_indikator = $this->crud->getWhere('ref_indikators', ['tahun' => $tahun_anggaran]);
        $ProgramTotalPaguAwal = 0;
        $ProgramTotalRealisasi = 0;
        foreach ($db_program->result() as $r) :
            $ProgramTotalPaguAwal += $this->target->getAlokasiPaguProgram($r->id, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal;
            $ProgramTotalRealisasi += $this->realisasi->getRealisasiTahunProgram($r->kode, $tahun_anggaran);
        endforeach;
        $persentase_capaian = @($ProgramTotalRealisasi / $ProgramTotalPaguAwal) * 100;

        // Realisasi Bulanan
        $spj_ms = [];
        $spj_tms = [];
        $spj_btl = [];
        for ($i = 1; $i <= 12; $i++) {
            $jumlah = @$this->spj->TransaksiSpjBulanan($i, $tahun_anggaran) != null ? @$this->spj->TransaksiSpjBulanan($i, $tahun_anggaran) : 0;
            $jumlah_tms = @$this->spj->TransaksiSpjBulananNonMs($i, 'TMS', $tahun_anggaran) != null ? @$this->spj->TransaksiSpjBulananNonMs($i, 'TMS', $tahun_anggaran) : 0;
            $jumlah_btl = @$this->spj->TransaksiSpjBulananNonMs($i, 'BTL', $tahun_anggaran) != null ? @$this->spj->TransaksiSpjBulananNonMs($i, 'BTL', $tahun_anggaran) : 0;
            $spj_ms[] = [bulan($i), (int) $jumlah];
            $spj_tms[] = [bulan($i), (int) $jumlah_tms];
            $spj_btl[] = [bulan($i), (int) $jumlah_btl];
        }

        // Realisasi By Part
        $db_parts = $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN']);
        $label = [];
        $part_jumlah = [];
        $spj_count_ms = [];
        $spj_count_tms = [];
        $spj_count_btl = [];
        foreach ($db_parts->result() as $part) :
            $label[] = $part->singkatan;
            $jumlah = @$this->spj->getRealisasiSpjByPart($part->id, $tahun_anggaran) != null ? @$this->spj->getRealisasiSpjByPart($part->id, $tahun_anggaran) : 0;
            $part_jumlah[] = (int) $jumlah;
            $spj_count_ms[] = (int) @$this->spj->getJumlahSpjByPart($part->id, 'APPROVE', $tahun_anggaran);
            $spj_count_tms[] = (int) @$this->spj->getJumlahSpjByPart($part->id, 'TMS', $tahun_anggaran);
            $spj_count_btl[] = (int) @$this->spj->getJumlahSpjByPart($part->id, 'BTL', $tahun_anggaran);
        endforeach;

        $response = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'jumlah_indikator' => $db_indikator->num_rows(),
                'total_pagu_awal' => $ProgramTotalPaguAwal,
                'total_realisasi' => $ProgramTotalRealisasi,
                'persentase' => number_format(round($persentase_capaian, 2), 2),
                'realisasi_bulanan' => [
                    'spj_ms' => $spj_ms,
                    'spj_tms' => $spj_tms,
                    'spj_btl' => $spj_btl,
                ],
                'realisasi_bidang' => [
                    'bidang' => $label,
                    'jumlah' => $part_jumlah,
                    'spj_count_ms' => $spj_count_ms,
                    'spj_count_tms' => $spj_count_tms,
                    'spj_count_btl' => $spj_count_btl,
                ],
                'realisasi_triwulan' => [
                    'triwulan_1' => (int) $this->spj->TransaksiTriwulan(["01", "02", "03"], $tahun_anggaran),
                    'triwulan_2' => (int) $this->spj->TransaksiTriwulan(["04", "05", "06"], $tahun_anggaran),
                    'triwulan_3' => (int) $this->spj->TransaksiTriwulan(["07", "08", "09"], $tahun_anggaran),
                    'triwulan_4' => (int) $this->spj->TransaksiTriwulan(["10", "11", "12"], $tahun_anggaran),
                ]
            ]
        ];
        return $this->response($response, RestController::HTTP_OK);
    }
}
