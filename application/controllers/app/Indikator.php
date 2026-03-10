<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Indikator extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/userguide3/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        cek_session();
        //  CEK USER PRIVILAGES 
        if (!privilages('priv_default') && !privilages('priv_anggarankinerja') || !privilages('priv_target_kinerja') || $this->session->userdata('is_valid_profile') === "0"):
            return show_404();
        endif;

        $this->load->model('ModelTarget', 'target');
        $this->load->model('ModelIndikator', 'indikator');
    }

    public function index()
    {
        $data = [
            'title' => 'Indikator Kinerja',
            'content' => 'pages/anggaran_kinerja/indikator',
            'autoload_js' => [
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/tabel-indikator.js',
                'template/backend/vendors/datatables.net/js/jquery.dataTables.min.js',
                'template/backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
                'template/backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js',
                'template/backend/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',
                'template/backend/vendors/datatables.net-buttons/js/dataTables.buttons.min.js'
            ],
            'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css',
                'template/backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
                'template/backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css',
                'template/backend/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css'
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function baru()
    {
        $part = $this->session->userdata('part');
        $tujuan = $this->crud->getWhere('ref_tujuan', ['tahun' => $this->session->userdata('tahun_anggaran')]);
        $sasaran = $this->crud->getWhere('ref_sasaran', ['tahun' => $this->session->userdata('tahun_anggaran')]);
        $program = $this->db->where('tahun', $this->session->userdata('tahun_anggaran'))->where("FIND_IN_SET('{$part}', fid_part) >", 0)->get('ref_programs');
        $kegiatan = $this->crud->getWhere('ref_kegiatans', ['tahun' => $this->session->userdata('tahun_anggaran'), 'fid_part' => $part]);

        $getData = $this->session->userdata('indikator_data');
        $getRef = $this->session->userdata('indikator_referensi');
        $data = [
            'title' => 'Tambah Indikator Kinerja',
            'content' => 'pages/anggaran_kinerja/indikator_tambah',
            'data' => [
                'tujuans' => $tujuan,
                'sasarans' => $sasaran,
                'programs' => $program,
                'kegiatans' => $kegiatan,
                'result' => $getData,
                'ref' => $getRef,
                'jenis_indikator' => $this->crud->get('ref_jenis_indikators')
            ],
            'autoload_js' => [
                'template/backend/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js',
                'template/backend/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js',
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
            ],
            'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css',
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function step($step)
    {
        if ($step !== '1' && $step !== '2' && $step !== '3' && $step !== '4') {
            show_404();
            return;
        }

        if ($step === '1') {
            $referensi = $this->input->post('referensi');
            $this->session->set_userdata('indikator_referensi', $referensi);

            redirect('app/indikator/baru?step=1');
            return;
        }

        if ($step === '2') {
            // proses simpan data step 2
            $postData = $this->input->post();
            $this->session->set_userdata('indikator_data', $postData);
            // setelah selesai, redirect ke step 3
            redirect('app/indikator/baru?step=2');
            return;
        }
    }

    public function step_ubah($step, $id)
    {

        if ($step === '1') {
            $referensi = $this->input->post('referensi');
            $this->session->set_userdata('indikator_referensi', $referensi);

            redirect('app/indikator/ubah/'.$id.'?step=1');
            return;
        }

        if ($step === '2') {
            // proses simpan data step 2
            $postData = $this->input->post();
            $this->session->set_userdata('indikator_data', $postData);
            // setelah selesai, redirect ke step 3
            redirect('app/indikator/ubah/'.$id.'?step=2');
            return;
        }
    }

    public function autocomplete($type)
    {
        $query = $this->input->get('query');
        $suggestions = [];

        if ($type === 'indikator') {
            $results = $this->db->like('nama', $query)->where('fid_part', $this->session->userdata('part'))->from('ref_indikators')->group_by('nama')->get()->result();
            foreach ($results as $row) {
                $suggestions[] = [
                    'value' => $row->nama,
                    'data' => $row->id
                ];
            }
        }

        echo json_encode(['suggestions' => $suggestions]);
    }

    public function simpan()
    {
        $post = $this->input->post();

        // Ambil periode (bisa array atau string)
        $periodeList = isset($post['periode'])
            ? (is_array($post['periode']) ? $post['periode'] : array_map('trim', explode(',', $post['periode'])))
            : [];

        if (empty($periodeList)) {
            echo json_encode([
                'success' => false,
                'message' => 'Periode belum dipilih.'
            ]);
            return;
        }

        // Tentukan kolom tujuan/sasaran berdasarkan ref
        $fid_tujuan = null;
        $fid_sasaran = null;
        $fid_program = null;
        $fid_kegiatan = null;
        $fid_sub_kegiatan = null;

        if (isset($post['ref'])) {
            if ($post['ref'] === 'Tujuan') {
                $fid_tujuan = $post['ref_tujuan'] ?? null;
            } elseif ($post['ref'] === 'Sasaran') {
                $fid_sasaran = $post['ref_sasaran'] ?? null;
            } elseif ($post['ref'] === 'Program') {
                $fid_program = $post['ref_program'] ?? null;
            } elseif ($post['ref'] === 'Kegiatan') {
                $fid_kegiatan = $post['ref_kegiatan'] ?? null;
            } elseif ($post['ref'] === 'SubKegiatan') {
                $fid_sub_kegiatan = $post['ref_sub_kegiatan'] ?? null;
            }
        }

        // Siapkan array batch insert
        $batchData = [];

        foreach ($periodeList as $periode) {
            $batchData[] = [
                'nama' => $post['nama_indikator'],
                'fid_periode' => $periode,
                'fid_part' => $this->session->userdata('part'),
                'fid_jenis_indikator' => $post['jenis_indikator'],
                'fid_tujuan' => $fid_tujuan,
                'fid_sasaran' => $fid_sasaran,
                'fid_program' => $fid_program,
                'fid_kegiatan' => $fid_kegiatan,
                'fid_sub_kegiatan' => $fid_sub_kegiatan,
                'tahun' => $this->session->userdata('tahun_anggaran'),
            ];
        }

        // Eksekusi insert_batch
        $this->db->trans_start();
        $this->db->insert_batch('ref_indikators', $batchData);
        $this->db->trans_complete();

        // Cek hasil transaksi
        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menyimpan data indikator.',
                'redirect_url' => base_url('app/indikator/baru?step=2&status=gagal')
            ]);
            return;
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Data indikator berhasil disimpan.',
            'redirect_url' => base_url('app/indikator/baru?step=3&status=berhasil')
        ]);
        $this->session->unset_userdata('indikator_data');
        $this->session->unset_userdata('indikator_referensi');
    }

    public function update()
    {
        // Implementasi fungsi update indikator di sini
        $post = $this->input->post();

        // Ambil data dari form dan lakukan update pada database
        // Tentukan kolom tujuan/sasaran berdasarkan ref
        $fid_tujuan = null;
        $fid_sasaran = null;
        $fid_program = null;
        $fid_kegiatan = null;
        $fid_sub_kegiatan = null;

        if (isset($post['ref'])) {
            if ($post['ref'] === 'Tujuan') {
                $fid_tujuan = $post['ref_tujuan'] ?? null;
            } elseif ($post['ref'] === 'Sasaran') {
                $fid_sasaran = $post['ref_sasaran'] ?? null;
            } elseif ($post['ref'] === 'Program') {
                $fid_program = $post['ref_program'] ?? null;
            } elseif ($post['ref'] === 'Kegiatan') {
                $fid_kegiatan = $post['ref_kegiatan'] ?? null;
            } elseif ($post['ref'] === 'SubKegiatan') {
                $fid_sub_kegiatan = $post['ref_sub_kegiatan'] ?? null;
            }
        }
        
        $data = [
            'nama' => $post['nama_indikator'],
            'fid_periode' => $post['periode'],
            'fid_jenis_indikator' => $post['jenis_indikator'],
            'fid_tujuan' => $fid_tujuan,
            'fid_sasaran' => $fid_sasaran,
            'fid_program' => $fid_program,
            'fid_kegiatan' => $fid_kegiatan,
            'fid_sub_kegiatan' => $fid_sub_kegiatan,
        ];

        // Eksekusi update
        $this->db->trans_start();
        $this->db->update('ref_indikators', $data, ['id' => $post['id']]);
        $this->db->trans_complete();

        // Cek hasil transaksi
        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal memperbaharui data indikator.',
                'redirect_url' => base_url('app/indikator/ubah/' . $post['id'] . '?step=2&status=gagal')
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Data indikator berhasil diperbaharui.',
            'redirect_url' => base_url('app/indikator/ubah/' . $post['id'] . '?step=3&status=berhasil')
        ]);
        $this->session->unset_userdata('indikator_data');
        $this->session->unset_userdata('indikator_referensi');
        // Setelah selesai, redirect ke halaman yang sesuai
    }

    public function datatable()
    {
        $db = $this->indikator->make_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($db as $r) {

            $isTargetIsset = $this->crud->getWhere('t_target', ['fid_indikator' => $r->id]);
            $disabledDeleted = $isTargetIsset->num_rows() > 0 ? 'disabled' : '';

            $action = '
            <div class="btn-group btn-group-sm" role="group" aria-label="...">
                <a data-id="' . $r->id . '" href="#" class="btn btn-detail btn-primary" type="button">Detail</a>
                <a href="'.base_url('app/indikator/ubah/'.$r->id).'" class="btn btn-edit btn-success" type="button">Edit</a>
                <a data-id="' . $r->id . '" href="#" class="btn btn-delete btn-danger ' . $disabledDeleted . '" type="button">Hapus</a>
            </div>
            ';

            $no++;
            $row = array();
            $row['no'] = $no;
            $row['nama'] = $this->referensi_indikator($r);
            $row['periode'] = bulan($r->fid_periode);
            $row['nama_jenis_indikator'] = '<b class="' . $r->color . '">' . $r->nama_jenis_indikator . '</b>';
            $row['tahun'] = $r->tahun;
            $row['action'] = $action;

            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->indikator->make_count_all(),
            "recordsFiltered" => $this->indikator->make_count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function referensi_indikator($r)
    {
        // Cek urutan prioritas: tujuan > sasaran > program > kegiatan > sub_kegiatan
        if (!empty($r->fid_tujuan)) {
            return '<div class="bg-warning p-2"><b>Tujuan</b><br/> ' . ($r->nama_tujuan ?? '-') . '</div> <div class="bg-light text-dark p-2"> - ' . $r->nama . '</div>';
        } elseif (!empty($r->fid_sasaran)) {
            return '<div class="bg-success p-2 text-white"><b>Sasaran</b><br/>  ' . ($r->nama_sasaran ?? '-') . '</div> <div class="bg-light text-dark p-2"> - ' . $r->nama . '</div>';
        } elseif (!empty($r->fid_program)) {
            return '<div class="bg-secondary p-2 text-white"><b>Program</b><br/>  ' . ($r->nama_program ?? '-') . '</div> <div class="bg-light text-dark p-2"> - ' . $r->nama . '</div>';
        } elseif (!empty($r->fid_kegiatan)) {
            return '<div class="bg-info p-2 text-white"><b>Kegiatan</b><br/>  ' . ($r->nama_kegiatan ?? '-') . '</div> <div class="bg-light text-dark p-2"> - ' . $r->nama . '</div>';
        } elseif (!empty($r->fid_sub_kegiatan)) {
            return '<div class="bg-light p-2 text-secondary"><b>Sub Kegiatan</b><br/>  ' . ($r->nama_sub_kegiatan ?? '-') . '</div><div class="bg-light text-dark p-2"> - ' . $r->nama . '</div>';
        } else {
            return '-';
        }
    }

    public function detail($id)
    {

        // Pastikan hanya bisa diakses via AJAX
        if (!$this->input->is_ajax_request()) {
            show_error('No direct script access allowed', 403);
            die();
        }

        // Pastikan $id valid
        if (empty($id)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'ID tidak valid.'
                ]));
        }

        // Ambil data indikator
        $indikator = $this->crud->getWhere('ref_indikators', ['id' => $id])->row();
        if (!$indikator) {
            return $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Indikator tidak ditemukan.'
                ]));
        }


        $nama_jenis_indikator = $this->crud->getWhere('ref_jenis_indikators', ['id' => $indikator->fid_jenis_indikator])->row();

        // Siapkan template detail indikator
        $templateDetailIndikator = '
        
            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%;">Nama Indikator</th>
                    <td>' . htmlspecialchars($indikator->nama) . '</td>
                </tr>
                <tr>
                    <th>Jenis Indikator</th>
                    <td><span class="' . $nama_jenis_indikator->color . '">' . htmlspecialchars(@$nama_jenis_indikator->nama) . '</span></td>
                </tr>
                <tr>
                    <th>Periode</th>
                    <td>' . bulan($indikator->fid_periode) . '</td>
                </tr>
                <tr>
                    <th>Tahun</th>
                    <td>' . htmlspecialchars($indikator->tahun) . '</td>
                </tr>
            </table>';

        // Ambil data target terkait indikator ini
        $targets = $this->crud->getWhere('t_target', ['fid_indikator' => $id])->result();

        // siapkan juga template target
        if (count($targets) > 0) {
            $templateDetailIndikator .= '
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center">Target Indikator</th>
                    </tr>  
                    <tr>
                        <th width="20%">Output/Outcome</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($targets as $target) {
                if ($target->is_jenis === "1"):
                    $templateDetailIndikator .= '
                    <tr>
                        <td>Persentase (%)</td>
                        <td>' . $target->persentase . '</td>
                    </tr>';
                else:
                    $templateDetailIndikator .= '
                    <tr>
                        <td>Jumlah</td>
                        <td>' . $target->eviden_jumlah . ' ' . $target->eviden_jenis . '</td>
                    </tr>';
                endif;
            }
            $templateDetailIndikator .= '
                </tbody>
            </table>';
        } else {
            $templateDetailIndikator .= '<div class="alert alert-info" role="alert">
                Tidak ada target terkait untuk indikator ini.
            </div>';
        }

        // Siapkan response
        $response = [
            'success' => true,
            'message' => 'Data indikator berhasil diambil.',
            'data' => [
                'template' => $templateDetailIndikator
            ]
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function ubah($id)
    {
        // Implementasi fungsi ubah indikator di sini
        $part = $this->session->userdata('part');
        $detail = $this->crud->getWhere('ref_indikators', ['id' => $id, 'fid_part' => $part])->row();
        if (!$detail) {
            show_404();
            return;
        }

        $tujuan = $this->crud->getWhere('ref_tujuan', ['tahun' => $this->session->userdata('tahun_anggaran')]);
        $sasaran = $this->crud->getWhere('ref_sasaran', ['tahun' => $this->session->userdata('tahun_anggaran')]);
        $program = $this->db->where("FIND_IN_SET('{$part}', fid_part) >", 0)->get('ref_programs');
        $kegiatan = $this->crud->getWhere('ref_kegiatans', ['tahun' => $this->session->userdata('tahun_anggaran'), 'fid_part' => $part]);

        $getData = $this->session->userdata('indikator_data');
        $getRef = $this->session->userdata('indikator_referensi');
        $data = [
            'title' => 'Ubah Indikator Kinerja',
            'content' => 'pages/anggaran_kinerja/indikator_ubah',
            'detail' => $detail,
            'data' => [
                'tujuans' => $tujuan,
                'sasarans' => $sasaran,
                'programs' => $program,
                'kegiatans' => $kegiatan,
                'result' => $getData,
                'ref' => $getRef,
                'jenis_indikator' => $this->crud->get('ref_jenis_indikators')
            ],
            'autoload_js' => [
                'template/backend/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js',
                'template/backend/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js',
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
            ],
            'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css',
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function delete($id)
    {
        $method = $this->input->method(TRUE);
        if ($method !== 'DELETE' && $this->input->post('_method') !== 'DELETE') {
            show_error('Invalid request method', 405);
            die();
        }

        // Pastikan hanya bisa diakses via AJAX
        if (!$this->input->is_ajax_request()) {
            show_error('No direct script access allowed', 403);
            die();
        }

        // Pastikan $id valid
        if (empty($id) || !is_numeric($id)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'ID tidak valid.'
                ]));
        }

        // Proses hapus data
        $deleted = $this->indikator->delete($id);

        if ($deleted) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => true,
                    'message' => 'Indikator berhasil dihapus.'
                ]));
        } else {
            return $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Gagal menghapus indikator. Coba lagi nanti.'
                ]));
        }
    }

    public function getSubKegiatan($kegiatanId)
    {
        $subKegiatans = $this->target->sub_kegiatans($kegiatanId);
        $options = '<option value="">-- Pilih Sub Kegiatan --</option>';
        foreach ($subKegiatans->result() as $subKegiatan) {
            $options .= '<option value="' . $subKegiatan->id . '">' . $subKegiatan->nama . '</option>';
        }
        echo json_encode($options);
    }
}
