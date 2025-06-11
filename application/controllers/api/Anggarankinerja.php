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

    public function program_get()
    {

        // Query parameters
        $is_perubahan = $this->query('is_perubahan');
        $tahun_anggaran = $this->query('tahun_anggaran');
        $part = $this->query('part');

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

        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'programs' => $this->api->program($part, $tahun_anggaran)->result(),
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
        $realisasi_kinerja = $this->realisasi->getRealisasiByIndikatorId($periode_id, $indikator_id)->row();
        $realisasi_anggaran = $this->realisasi->getRealisasiProgram($periode_id, $program);

        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'target' => (int) $target_anggaran,
                'realisasi_anggaran' => (int) $realisasi_anggaran,
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
        $realisasi_kinerja = $this->realisasi->getRealisasiByIndikatorId($periode_id, $indikator_id)->row();
        $realisasi_anggaran = $this->realisasi->getRealisasiKegiatan($periode_id, $kegiatan);

        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'target' => (int) $target_anggaran,
                'realisasi_anggaran' => (int) $realisasi_anggaran,
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
        $sub_kegiatan = $this->post('sub_kegiatan');
        $is_perubahan = $this->query('is_perubahan');
        $tahun_anggaran = $this->query('tahun_anggaran');
        $periode_id = $this->query('periode');
        $indikator_id = $this->query('indikator');

        $validation = $this->validator->validate($this->post() + $this->query(), [
            'is_perubahan'   => 'required|numeric|digits:1',
            'tahun_anggaran' => 'required|numeric|digits:4',
            'sub_kegiatan' => 'required|numeric'
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
        $realisasi_kinerja = $this->realisasi->getRealisasiByIndikatorId($periode_id, $indikator_id)->row();
        $realisasi_anggaran = $this->realisasi->getRealisasiSubKegiatan($periode_id, $sub_kegiatan);

        $data = [
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => [
                'target' => (int) $target_anggaran,
                'realisasi_anggaran' => (int) $realisasi_anggaran,
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
            'type' => 'required|in:fid_program,fid_kegiatan,fid_sub_kegiatan'
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
            $indikator = $this->realisasi->getIndikator([$type => $id]);

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
