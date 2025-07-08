<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use Rakit\Validation\Validator;

class Anggarankinerja extends RestController
{

    private $validator;
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('ModelTarget', 'target');
        $this->load->model('ModelRealisasi', 'realisasi');
        $this->load->model('ModelSpj', 'spj');
        $this->load->model('ModelUsers', 'user');
        $this->load->model('ModelApi', 'api');
        $this->validator = new Validator;
    }

    public function periode_get()
    {
        $is_open = $this->query('is_open') ?? 'Y';
        $tahun_anggaran = $this->query('tahun_anggaran');
        $validation = $this->validator->validate($_GET, [
            'is_open' => 'required|in:Y,N',
            'tahun_anggaran' => 'required|numeric|digits:4',
        ]);
        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ]);
            die();
        }

        $periode = $this->api->getPeriode($is_open, $tahun_anggaran);
        if ($periode->num_rows() === 0) {
            return $this->response([
                'status' => false,
                'message' => 'Data Not Found'
            ], RestController::HTTP_NOT_FOUND);
        }

        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'periode' => $periode->result()
            ]
        ];
        return $this->response($data, RestController::HTTP_OK);
    }

    public function tujuan_get()
    {
        // Query
        $unor_id = $this->query('unor_id');
        $tahun_anggaran = $this->query('tahun_anggaran');

        $validation = $this->validator->validate($_GET, [
            // 'unor_id'   => 'required|numeric',
            'tahun_anggaran' => 'required|numeric|digits:4'
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ], RestController::HTTP_INTERNAL_ERROR);
            die();
        }
        $tujuan = $this->api->tujuan($unor_id, $tahun_anggaran);

        if ($tujuan->num_rows() === 0) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => 'Data Empty'
            ], RestController::HTTP_NOT_FOUND);
            die();
        }

        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'tujuan' => $tujuan->result(),
            ]
        ];

        return $this->response($data, RestController::HTTP_OK);
    }

    public function tujuan_post()
    {
        $tujuan = $this->post('tujuan');
        $is_perubahan = $this->query('is_perubahan');
        $tahun_anggaran = $this->query('tahun_anggaran');
        $periode_id = $this->query('periode');
        $indikator_id = $this->query('indikator');

        $validation = $this->validator->validate($this->post() + $this->query(), [
            'is_perubahan'   => 'required|numeric|digits:1',
            'tahun_anggaran' => 'required|numeric|digits:4',
            'tujuan' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ]);
            die();
        }

        // Target
        $target_anggaran = $this->target->getAlokasiPaguTujuan($tujuan, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal;

        // Realisasi
        $realisasi_kinerja = [];
        $realisasi_anggaran = [];
        for ($i = 1; $i <= 12; $i++) {
            $realisasi_kinerja[$i] = $this->realisasi->getRealisasiByIndikatorId($i, $indikator_id)->row();
            $realisasi_anggaran[$i] = (int)  $this->realisasi->getRealisasiTujuan($i, $tujuan, $tahun_anggaran);
        }


        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'target' => (int) $target_anggaran,
                'realisasi_anggaran' => $realisasi_anggaran,
                'realisasi_kinerja' => $realisasi_kinerja
            ]
        ];

        return $this->response($data, RestController::HTTP_OK);
    }

    public function sasaran_get()
    {
        // Query
        $tujuan_id = $this->query('tujuan_id');
        $tahun_anggaran = $this->query('tahun_anggaran');

        $validation = $this->validator->validate($_GET, [
            'tujuan_id'   => 'required|numeric',
            'tahun_anggaran' => 'required|numeric|digits:4'
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ], RestController::HTTP_INTERNAL_ERROR);
            die();
        }

        $sasaran = $this->api->sasaran($tujuan_id, $tahun_anggaran);

        if ($sasaran->num_rows() === 0) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => 'Data Empty'
            ], RestController::HTTP_NOT_FOUND);
            die();
        }

        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'sasaran' => $sasaran->result(),
            ]
        ];

        return $this->response($data, RestController::HTTP_OK);
    }

    public function sasaran_post()
    {
        $sasaran = $this->post('sasaran');
        $is_perubahan = $this->query('is_perubahan');
        $tahun_anggaran = $this->query('tahun_anggaran');
        $periode_id = $this->query('periode');
        $indikator_id = $this->query('indikator');

        $validation = $this->validator->validate($this->post() + $this->query(), [
            'is_perubahan'   => 'required|numeric|digits:1',
            'tahun_anggaran' => 'required|numeric|digits:4',
            'sasaran' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ]);
            die();
        }

        // Target
        $target_anggaran = $this->target->getAlokasiPaguSasaran($sasaran, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal;

        // Realisasi
        $realisasi_kinerja = [];
        $realisasi_anggaran = [];
        for ($i = 1; $i <= 12; $i++) {
            $realisasi_kinerja[$i] = $this->realisasi->getRealisasiByIndikatorId($i, $indikator_id)->row();
            $realisasi_anggaran[$i] = (int) $this->realisasi->getRealisasiSasaran($i, $sasaran, $tahun_anggaran);
        }
        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'target' => (int) $target_anggaran,
                'realisasi_anggaran' => $realisasi_anggaran,
                'realisasi_kinerja' => $realisasi_kinerja
            ]
        ];

        return $this->response($data, RestController::HTTP_OK);
    }

    public function program_get()
    {

        // Query parameters
        $is_perubahan = $this->query('is_perubahan');
        $tahun_anggaran = $this->query('tahun_anggaran');
        $part = $this->query('part');
        $sasaran = $this->query('sasaran_id');

        $validation = $this->validator->validate($_GET, [
            'is_perubahan'   => 'required|numeric|digits:1',
            'tahun_anggaran' => 'required|numeric|digits:4',
            'sasaran_id' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ]);
            die();
        }

        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'programs' => $this->api->program($part, $sasaran, $tahun_anggaran)->result(),
            ]
        ];

        return $this->response($data, RestController::HTTP_OK);
    }

    public function program_post()
    {
        $program = $this->post('program');
        $is_perubahan = $this->query('is_perubahan');
        $tahun_anggaran = $this->query('tahun_anggaran');
        $periode_id = $this->query('periode');
        $indikator_id = $this->query('indikator');

        $validation = $this->validator->validate($this->post() + $this->query(), [
            'is_perubahan'   => 'required|numeric|digits:1',
            'tahun_anggaran' => 'required|numeric|digits:4',
            'program' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ]);
            die();
        }

        // Target
        $target_anggaran = $this->target->getAlokasiPaguProgram($program, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal;

        // Realisasi
        $realisasi_kinerja = [];
        $realisasi_anggaran = [];
        for ($i = 1; $i <= 12; $i++) {
            $realisasi_kinerja[$i] = $this->realisasi->getRealisasiByIndikatorId($i, $indikator_id)->row();
            $realisasi_anggaran[$i] = (int) $this->realisasi->getRealisasiProgram($i, $program);
        }

        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'target' => (int) $target_anggaran,
                'realisasi_anggaran' => $realisasi_anggaran,
                'realisasi_kinerja' => $realisasi_kinerja
            ]
        ];

        return $this->response($data, RestController::HTTP_OK);
    }

    public function kegiatan_get()
    {
        // Query parameters
        $program = $this->query('program');
        $part = $this->query('part');

        $validation = $this->validator->validate($_GET, [
            'program' => 'required|numeric',
            'part'   => 'numeric',
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ]);
            die();
        }
        try {
            $kegiatan = $this->realisasi->kegiatans($program, $part);

            if ($kegiatan->num_rows() === 0) {
                return $this->response([
                    'status' => false,
                    'message' => 'Data Not Found'
                ], RestController::HTTP_NOT_FOUND);
            }
            $data = [
                'status' => true,
                'message' => 'Data retrieved successfully',
                'data' => [
                    'kegiatans' => $kegiatan->result()
                ]
            ];

            return $this->response($data, RestController::HTTP_OK);
        } catch (Exception $e) {
            return $this->response([
                'status' => false,
                'message' => $e->getMessage()
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

    public function kegiatan_post()
    {
        $kegiatan = $this->post('kegiatan');
        $is_perubahan = $this->query('is_perubahan');
        $tahun_anggaran = $this->query('tahun_anggaran');
        $periode_id = $this->query('periode');
        $indikator_id = $this->query('indikator');

        $validation = $this->validator->validate($this->post() + $this->query(), [
            'is_perubahan'   => 'required|numeric|digits:1',
            'tahun_anggaran' => 'required|numeric|digits:4',
            'kegiatan' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ]);
            die();
        }

        // Target
        $target_anggaran = $this->target->getAlokasiPaguKegiatan($kegiatan, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal;

        // Realisasi
        $realisasi_kinerja = [];
        $realisasi_anggaran = [];
        for ($i = 1; $i <= 12; $i++) {
            $realisasi_kinerja[$i] = $this->realisasi->getRealisasiByIndikatorId($i, $indikator_id)->row();
            $realisasi_anggaran[$i] = (int) $this->realisasi->getRealisasiKegiatan($i, $kegiatan);
        }
        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'target' => (int) $target_anggaran,
                'realisasi_anggaran' => $realisasi_anggaran,
                'realisasi_kinerja' => $realisasi_kinerja
            ]
        ];

        return $this->response($data, RestController::HTTP_OK);
    }

    public function subkegiatan_get()
    {
        // Query parameters
        $kegiatan = $this->query('kegiatan');

        $validation = $this->validator->validate($_GET, [
            'kegiatan' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ]);
            die();
        }
        try {
            $sub_kegiatan = $this->realisasi->sub_kegiatans($kegiatan);

            if ($sub_kegiatan->num_rows() === 0) {
                return $this->response([
                    'status' => false,
                    'message' => 'Data Not Found'
                ], RestController::HTTP_NOT_FOUND);
            }
            $data = [
                'status' => true,
                'message' => 'Data retrieved successfully',
                'data' => [
                    'sub_kegiatans' => $sub_kegiatan->result()
                ]
            ];

            return $this->response($data, RestController::HTTP_OK);
        } catch (Exception $e) {
            return $this->response([
                'status' => false,
                'message' => $e->getMessage()
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

    public function subkegiatan_post()
    {
        $sub_kegiatan = $this->post('subkegiatan');
        $is_perubahan = $this->query('is_perubahan');
        $tahun_anggaran = $this->query('tahun_anggaran');
        $periode_id = $this->query('periode');
        $indikator_id = $this->query('indikator');

        $validation = $this->validator->validate($this->post() + $this->query(), [
            'is_perubahan'   => 'required|numeric|digits:1',
            'tahun_anggaran' => 'required|numeric|digits:4',
            'subkegiatan' => 'required|numeric'
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ]);
            die();
        }

        // Target
        $target_anggaran = $this->target->getAlokasiPaguSubKegiatan($sub_kegiatan, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal;

        // Realisasi
        $realisasi_kinerja = [];
        $realisasi_anggaran = [];
        for ($i = 1; $i <= 12; $i++) {
            $realisasi_kinerja[$i] = $this->realisasi->getRealisasiByIndikatorId($i, $indikator_id)->row();
            $realisasi_anggaran[$i] = (int) $this->realisasi->getRealisasiSubKegiatan($i, $sub_kegiatan);
        }
        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'target' => (int) $target_anggaran,
                'realisasi_anggaran' => $realisasi_anggaran,
                'realisasi_kinerja' => $realisasi_kinerja
            ]
        ];

        return $this->response($data, RestController::HTTP_OK);
    }

    public function indikator_get()
    {
        // Query parameters
        $type = $this->query('type');
        $id = $this->query('id');

        $validation = $this->validator->validate($_GET, [
            'id'   => 'required|numeric',
            'type' => 'required|in:fid_tujuan,fid_sasaran,fid_program,fid_kegiatan,fid_sub_kegiatan'
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'message' => 'Data retrieved failed',
                'errors' => $validation->errors()->toArray()
            ]);
        }

        try {
            // Indikator Program
            $indikator = $this->realisasi->getIndikator([$type => $id], null);

            if ($indikator->num_rows() === 0) {
                return $this->response([
                    'status' => false,
                    'message' => 'Data Not Found'
                ], RestController::HTTP_NOT_FOUND);
            }

            $data = [
                'status' => true,
                'message' => 'Data retrieved successfully',
                'data' => [
                    'indikator' => $indikator->result()
                ]
            ];

            return $this->response($data, RestController::HTTP_OK);
        } catch (Exception $e) {
            return $this->response([
                'status' => false,
                'message' => $e->getMessage()
            ], RestController::HTTP_BAD_REQUEST);
        }
    }
}
