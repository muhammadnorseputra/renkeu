<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class VerifikatorKinerja extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        //  CEK USER PRIVILAGES
        if (!privilages('priv_default') && !privilages('priv_verify_kinerja')):
            return show_404();
        endif;
    }

    public function index()
    {
        // Bidang sesuai hak akses: priv_verify_kinerja = Y lihat semua, lainnya hanya bidang sendiri
        if (privilages('priv_verify_kinerja')) {
            $bidang_list = $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN'])->result();
        } else {
            $bidang_list = $this->crud->getWhere('ref_parts', ['id' => $this->session->userdata('part')])->result();
        }

        // Pegawai yang sudah di-mapping, join nama bidang
        $this->db->select('p.*, r.nama AS bidang_nama, r.singkatan AS bidang_singkatan');
        $this->db->from('pegawai p');
        $this->db->join('ref_parts r', 'r.id = p.fid_part', 'left');
        $this->db->order_by('r.id', 'ASC');
        $pegawai = $this->db->get()->result();

        // Kelompokkan per bidang
        $per_bidang = [];
        foreach ($bidang_list as $b) {
            $per_bidang[$b->id] = [
                'id'       => $b->id,
                'nama'     => $b->nama,
                'singkatan'=> $b->singkatan,
                'pegawai'  => [],
            ];
        }
        foreach ($pegawai as $p) {
            if (isset($per_bidang[$p->fid_part])) {
                $per_bidang[$p->fid_part]['pegawai'][] = $p;
            }
        }

        $data = [
            'title'     => 'Verifikasi Hasil Kinerja',
            'content'   => 'pages/verifikator_kinerja',
            'bidang'    => array_values($per_bidang),
            'priv_edit' => privilages('priv_verifikasi_kinerja'),
            'autoload_css' => [
                'template/custom-css/verifikator-kinerja.css',
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/b-3.2.4/r-3.0.3/datatables.min.css',
            ],
            'autoload_js' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/b-3.2.4/r-3.0.3/datatables.min.js',
                'https://cdn.datatables.net/buttons/3.2.4/js/buttons.colVis.min.js',
                'template/custom-js/verifikator-kinerja.js',
            ],
        ];
        $this->load->view('layout/app', $data);
    }

    /**
     * Ambil daftar indikator yang sudah dipilih pegawai dari pegawai_indikators.
     */
    public function get_indikator_pegawai()
    {
        $nip   = $this->input->post('nip');
        $tahun = $this->session->userdata('tahun_anggaran');

        $rows = $this->db
            ->select('pi.id AS fid_pegawai_indikator, i.nama AS indikator_nama, ji.nama AS jenis_nama')
            ->from('pegawai_indikators pi')
            ->join('ref_indikators i', 'i.id = pi.fid_indikator', 'inner')
            ->join('ref_jenis_indikators ji', 'ji.id = i.fid_jenis_indikator', 'inner')
            ->where('pi.nip', $nip)
            ->order_by('ji.id', 'ASC')
            ->order_by('i.id', 'ASC')
            ->get()
            ->result();

        foreach ($rows as $r) {
            if ($nip) {
                $periode = $this->db->select('periode')
                    ->where('nip', $nip)
                    ->where('tahun', $tahun)
                    ->where('fid_pegawai_indikator', (int) $r->fid_pegawai_indikator)
                    ->get('t_verify_kinerja')->result();
                $r->bulan_terisi = array_values(array_unique(array_column($periode, 'periode')));
            } else {
                $r->bulan_terisi = [];
            }
        }

        echo json_encode(['status' => true, 'data' => $rows]);
    }

    /**
     * Ambil daftar periode yang sudah terisi untuk NIP + tahun + fid_pegawai_indikator
     */
    public function get_periode_terisi()
    {
        $nip         = $this->input->post('nip');
        $ip          = $this->input->post('fid_pegawai_indikator');
        $tahun       = $this->session->userdata('tahun_anggaran');

        $rows = $this->db
            ->where('nip', $nip)
            ->where('tahun', $tahun)
            ->where('fid_pegawai_indikator', (int) $ip)
            ->get('t_verify_kinerja')
            ->result();

        $periode = array_unique(array_column($rows, 'periode'));

        $bulan_list = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $status_periode = [];
        foreach ($bulan_list as $b) {
            $st = 'N';
            foreach ($rows as $r) {
                if ($r->periode === $b) {
                    foreach (['unggah_kinerja_harian','target_realisasi','masalah_tindak_lanjut','diskusi_kinerja','data_dukung','simpulan_capaian'] as $f) {
                        if ($r->{$f} === 'Y') { $st = 'Y'; break; }
                    }
                }
                if ($st === 'Y') break;
            }
            $status_periode[$b] = $st;
        }

        $first = '01';
        foreach ($bulan_list as $b) {
            if (!in_array($b, $periode, true)) { $first = $b; break; }
        }

        echo json_encode(['status' => true, 'periode' => $periode, 'status_periode' => $status_periode, 'first' => $first]);
    }

    /**
     * Ambil data verifikasi per NIP + periode + fid_pegawai_indikator + tahun
     */
    public function get_verifikasi()
    {
        $nip         = $this->input->post('nip');
        $periode     = $this->input->post('periode');
        $fid_ind     = $this->input->post('fid_pegawai_indikator');
        $tahun       = $this->session->userdata('tahun_anggaran');

        $row = $this->db
            ->where('nip', $nip)
            ->where('periode', $periode)
            ->where('tahun', $tahun)
            ->where('fid_pegawai_indikator', (int) $fid_ind)
            ->get('t_verify_kinerja')
            ->row();

        echo json_encode(['status' => true, 'data' => $row ?: null]);
    }

    /**
     * Rekap verifikasi per NIP + tahun (semua periode) — per indikator
     */
    public function get_rekap()
    {
        $nip     = $this->input->post('nip');
        $fid_ind = $this->input->post('fid_pegawai_indikator');
        $tahun   = $this->session->userdata('tahun_anggaran');

        $where = ['nip' => $nip, 'tahun' => $tahun];
        if ($fid_ind !== null && $fid_ind !== '') {
            $where['fid_pegawai_indikator'] = (int) $fid_ind;
        }

        $rows = $this->crud->getWhere('t_verify_kinerja', $where)->result();

        $fields = [
            'unggah_kinerja_harian',
            'target_realisasi',
            'masalah_tindak_lanjut',
            'diskusi_kinerja',
            'data_dukung',
            'simpulan_capaian',
        ];

        // Matriks: periode => field => Y/N
        $matrix = [];
        $audit  = ['created_by' => null, 'created_at' => null, 'updated_by' => null, 'updated_at' => null];
        foreach ($rows as $r) {
            $matrix[$r->periode] = [];
            foreach ($fields as $f) {
                $matrix[$r->periode][$f] = $r->{$f};
            }
            // Audit: ambil yang terbaru (updated_at jika ada, else created_at)
            $ts = $r->updated_at ?: $r->created_at;
            if ($audit['updated_at'] === null || strtotime($ts) > strtotime($audit['updated_at'])) {
                $audit['created_by'] = $r->created_by;
                $audit['created_at'] = $r->created_at;
                $audit['updated_by'] = $r->updated_by;
                $audit['updated_at'] = $r->updated_at;
            }
        }

        echo json_encode(['status' => true, 'data' => $matrix, 'audit' => [
            'created_by' => $audit['created_by'],
            'created_at' => datetime_indo($audit['created_at']),
            'updated_by' => $audit['updated_by'],
            'updated_at' => datetime_indo($audit['updated_at']),
        ]]);
    }

    /**
     * Simpan verifikasi per NIP + periode + fid_pegawai_indikator + tahun (upsert)
     */
    public function save_verifikasi()
    {
        $nip     = $this->input->post('nip');
        $periode = $this->input->post('periode');
        $fid_ind = $this->input->post('fid_pegawai_indikator');
        $tahun   = $this->session->userdata('tahun_anggaran');

        if (empty($nip) || empty($periode) || empty($tahun)) {
            echo json_encode(['status' => false, 'pesan' => 'Data NIP, periode, dan tahun wajib diisi.']);
            return;
        }

        if (empty($fid_ind) || !is_numeric($fid_ind)) {
            echo json_encode(['status' => false, 'pesan' => 'Indikator wajib dipilih.']);
            return;
        }

        $fields = [
            'unggah_kinerja_harian',
            'target_realisasi',
            'masalah_tindak_lanjut',
            'diskusi_kinerja',
            'data_dukung',
            'simpulan_capaian',
        ];

        $data = [];
        foreach ($fields as $f) {
            $data[$f] = $this->input->post($f) === 'Y' ? 'Y' : 'N';
        }

        $user = $this->session->userdata('user_name') ?: 'system';

        $exists = $this->crud->getWhere('t_verify_kinerja', [
            'nip'                   => $nip,
            'periode'               => $periode,
            'tahun'                 => $tahun,
            'fid_pegawai_indikator' => (int) $fid_ind,
        ])->row();

        if ($exists) {
            $data['updated_at'] = DateTimeInput();
            $data['updated_by'] = $user;
            $ok = $this->crud->update('t_verify_kinerja', $data, ['id' => $exists->id]);
        } else {
            $data['periode']               = $periode;
            $data['tahun']                 = $tahun;
            $data['nip']                   = $nip;
            $data['fid_pegawai_indikator'] = (int) $fid_ind;
            $data['created_at']            = DateTimeInput();
            $data['created_by']            = $user;
            $ok = $this->crud->insert('t_verify_kinerja', $data);
        }

        echo json_encode([
            'status' => $ok,
            'pesan'  => $ok ? 'Data verifikasi berhasil disimpan.' : 'Gagal menyimpan data verifikasi.',
        ]);
    }

    /**
     * Rekap verifikasi semua pegawai (server-side DataTable)
     */
    public function get_rekap_all()
    {
        $this->load->model('ModelDatatables', 'datatables');
        $db = $this->datatables->make_datatables_rekap_verifikasi_all();
        $data = array();
        $no = @$_POST['start'];

        foreach ($db as $r) {
            $row = array();
            $row[] = ++$no;
            $row[] = $r->nip;
            $row[] = $r->nama_lengkap;
            $row[] = $r->jabatan;
            $row[] = $r->pangkat;
            $row[] = $r->jenis;
            $row[] = $r->bidang_nama;
            $row[] = $r->bidang_singkatan;
            $jml_ind = max(1, (int) ($r->jml_indikator ?? 0));
            $tw_full = '<span class="text-success font-weight-bold"><i class="fa fa-check-circle"></i> ' . (18 * $jml_ind) . '/' . (18 * $jml_ind) . '</span>';
            $tw_cell = function ($c) use ($tw_full, $jml_ind) {
                return $c >= 18 * $jml_ind ? $tw_full : '<span class="text-muted">' . $c . '/' . (18 * $jml_ind) . '</span>';
            };
            $row[] = '<span class="badge badge-info">' . (int) ($r->jml_indikator ?? 0) . '</span>';
            $row[] = $tw_cell($r->tw1_count);
            $row[] = $tw_cell($r->tw2_count);
            $row[] = $tw_cell($r->tw3_count);
            $row[] = $tw_cell($r->tw4_count);
            $row[] = $r->total_count . '/' . (72 * $jml_ind);
            $row[] = $r->status_label === 'Lengkap'
                ? '<span class="badge badge-success">Lengkap</span>'
                : '<span class="badge badge-warning">Belum Lengkap</span>';
            $data[] = $row;
        }

        $output = array(
            "draw"            => intval($_POST['draw']),
            "recordsTotal"    => $this->datatables->make_count_all_rekap_verifikasi_all(),
            "recordsFiltered" => $this->datatables->make_count_filtered_rekap_verifikasi_all(),
            "data"            => $data,
        );
        echo json_encode($output);
    }

    /**
     * Download template Excel rekonsiliasi data SAKIPRA
     */
    public function download_template_rekon()
    {
        if (!privilages('priv_verifikasi_kinerja')) {
            return show_404();
        }

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $headers = [
            'NIP', 'NAMA', 'BIDANG', 'INDIKATOR', 'INDIKATOR_NAMA', 'periode', 'unggah_kinerja_harian', 'target_realisasi',
            'masalah_tindak_lanjut', 'diskusi_kinerja', 'data_dukung', 'simpulan_capaian',
        ];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue(chr(65 + $i) . '1', $h);
        }

        // Header style
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4F81BD']],
        ]);

        // Pegawai yang sudah di-mapping (punya bidang, selain KABAN) - sama seperti tab Per Bidang
        $pegawai = $this->db->select('p.nip, p.nama_lengkap, r.nama AS bidang_nama')
            ->from('pegawai p')
            ->join('ref_parts r', 'r.id = p.fid_part')
            ->where('r.singkatan !=', 'KABAN')
            ->order_by('r.id, p.nama_lengkap', 'ASC')
            ->get()->result();

        // Indikator per pegawai: id pegawai_indikators + nama indikator
        $map_ind = [];
        foreach ($this->db->select('pi.id, pi.nip, i.nama AS indikator_nama')
            ->from('pegawai_indikators pi')
            ->join('ref_indikators i', 'i.id = pi.fid_indikator', 'inner')
            ->get()->result() as $m) {
            $map_ind[$m->nip][] = $m;
        }

        // Status verifikasi existing per NIP+fid_indikator+periode
        $existing = [];
        foreach ($this->crud->getWhere('t_verify_kinerja', ['tahun' => $this->session->userdata('tahun_anggaran')])->result() as $v) {
            $existing[$v->nip . '|' . $v->fid_pegawai_indikator . '|' . $v->periode] = $v;
        }

        $bulan_list = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $indikator = ['unggah_kinerja_harian', 'target_realisasi', 'masalah_tindak_lanjut', 'diskusi_kinerja', 'data_dukung', 'simpulan_capaian'];
        $row = 2;
        foreach ($pegawai as $p) {
            if (empty($map_ind[$p->nip])) continue;
            foreach ($map_ind[$p->nip] as $mi) {
                foreach ($bulan_list as $periode) {
                    $v = $existing[$p->nip . '|' . $mi->id . '|' . $periode] ?? null;
                    $sheet->setCellValueExplicit('A' . $row, (string) $p->nip, DataType::TYPE_STRING);
                    $sheet->setCellValue('B' . $row, $p->nama_lengkap);
                    $sheet->setCellValue('C' . $row, $p->bidang_nama);
                    $sheet->setCellValue('D' . $row, (int) $mi->id);
                    $sheet->setCellValue('E' . $row, $mi->indikator_nama);
                    $sheet->setCellValueExplicit('F' . $row, $periode, DataType::TYPE_STRING);
                    foreach ($indikator as $k => $f) {
                        // Kolom G..L (7..12)
                        $sheet->setCellValue(chr(71 + $k) . $row, $v ? $v->{$f} : '');
                    }
                    $row++;
                }
            }
        }
        $lastRow = $row - 1;

        // Select box periode: 01-12
        $dvPeriode = new DataValidation();
        $dvPeriode->setType(DataValidation::TYPE_LIST)
            ->setFormula1('"01,02,03,04,05,06,07,08,09,10,11,12"')
            ->setAllowBlank(true)
            ->setShowErrorMessage(true)
            ->setErrorTitle('Periode tidak valid')
            ->setError('Pilih 01 sampai 12.');
        $sheet->setDataValidation('F2:F' . $lastRow, $dvPeriode);

        // Select box 6 checklist: Y / N
        $dvYN = new DataValidation();
        $dvYN->setType(DataValidation::TYPE_LIST)
            ->setFormula1('"Y,N"')
            ->setAllowBlank(true)
            ->setShowErrorMessage(true)
            ->setErrorTitle('Nilai tidak valid')
            ->setError('Hanya boleh diisi Y atau N.');
        $sheet->setDataValidation('G2:L' . $lastRow, $dvYN);

        // NIP sebagai teks agar digit tidak berubah desimal
        $sheet->getStyle('A2:A' . $lastRow)->getNumberFormat()->setFormatCode('@');
        $sheet->getDefaultColumnDimension()->setAutoSize(true);

        $writer   = new Xlsx($spreadsheet);
        $filename = 'TEMPLATE-REKON-SAKIPRA-' . ($this->session->userdata('tahun_anggaran') ?: date('Y'));
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Rekonsiliasi data SAKIPRA: upload Excel checklist verifikasi (bulk upsert)
     */
    public function rekon_sakipra()
    {
        if (!privilages('priv_verifikasi_kinerja')) {
            echo json_encode(['status' => false, 'pesan' => 'Anda tidak memiliki hak akses.']);
            return;
        }

        if (empty($_FILES['file']['tmp_name'])) {
            echo json_encode(['status' => false, 'pesan' => 'File Excel belum dipilih.']);
            return;
        }

        if ($_FILES['file']['size'] > 5 * 1024 * 1024) {
            echo json_encode(['status' => false, 'pesan' => 'Ukuran file maksimal 5MB.']);
            return;
        }

        try {
            $spreadsheet = IOFactory::load($_FILES['file']['tmp_name']);
            $rows = $spreadsheet->getActiveSheet()->toArray();
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'pesan' => 'File tidak dapat dibaca. Pastikan format .xlsx/.xls.']);
            return;
        }

        if (count($rows) < 2) {
            echo json_encode(['status' => false, 'pesan' => 'Data tidak lengkap atau kosong.']);
            return;
        }

        // Header -> lowercase, normalisasi spasi/strip
        $headers = array_map(function ($h) {
            return strtolower(trim(str_replace([' ', '-'], '_', (string) $h)));
        }, $rows[0]);

        $required = ['nip', 'indikator', 'periode', 'unggah_kinerja_harian', 'target_realisasi', 'masalah_tindak_lanjut', 'diskusi_kinerja', 'data_dukung', 'simpulan_capaian'];
        foreach ($required as $col) {
            if (!in_array($col, $headers, true)) {
                echo json_encode(['status' => false, 'pesan' => 'Kolom "' . $col . '" tidak ditemukan pada file.']);
                return;
            }
        }

        $tahun = $this->session->userdata('tahun_anggaran');
        $user  = $this->session->userdata('user_name') ?: 'system';
        $now   = DateTimeInput();
        $valid_periode = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $fields = ['unggah_kinerja_harian', 'target_realisasi', 'masalah_tindak_lanjut', 'diskusi_kinerja', 'data_dukung', 'simpulan_capaian'];

        // Daftar NIP yang terdaftar di tabel pegawai (untuk validasi)
        $valid_nip = [];
        foreach ($this->db->select('nip')->get('pegawai')->result() as $r) {
            $valid_nip[trim((string) $r->nip)] = true;
        }

        // id pegawai_indikators yang valid (untuk validasi kolom INDIKATOR)
        $valid_ind = [];
        foreach ($this->db->select('id')->get('pegawai_indikators')->result() as $r) {
            $valid_ind[(int) $r->id] = true;
        }

        $proses = 0;
        $skip   = 0;
        $gagal  = [];

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $get = function ($col) use ($headers, $row) {
                $idx = array_search($col, $headers, true);
                if ($idx === false) return null;
                $val = $row[$idx];
                // NIP dari Excel bisa berupa float (notasi ilmiah) - kembalikan sebagai string digit
                if (is_float($val)) return number_format($val, 0, '', '');
                return trim((string) $val);
            };

            $nip     = $get('nip');
            $fid_ind = (int) $get('indikator');
            $periode = $get('periode');

            if ($nip === '' || !in_array($periode, $valid_periode, true)) {
                $gagal[] = 'Baris ' . ($i + 1) . ': NIP kosong atau periode tidak valid (01-12).';
                continue;
            }

            if (!isset($valid_nip[$nip])) {
                $gagal[] = 'Baris ' . ($i + 1) . ': NIP ' . $nip . ' tidak terdaftar.';
                continue;
            }

            if ($fid_ind <= 0 || !isset($valid_ind[$fid_ind])) {
                $gagal[] = 'Baris ' . ($i + 1) . ': INDIKATOR kosong atau tidak valid.';
                continue;
            }

            // Skip jika 6 kolom indikator semuanya kosong
            $raw = [];
            $allEmpty = true;
            foreach ($fields as $f) {
                $raw[$f] = $get($f);
                if ($raw[$f] !== '' && $raw[$f] !== null) {
                    $allEmpty = false;
                }
            }
            if ($allEmpty) {
                $skip++;
                continue;
            }

            $data = [
                'nip'                   => $nip,
                'periode'               => $periode,
                'tahun'                 => $tahun,
                'fid_pegawai_indikator' => $fid_ind,
            ];
            foreach ($fields as $f) {
                $data[$f] = strtoupper($raw[$f]) === 'Y' ? 'Y' : 'N';
            }

            $exists = $this->crud->getWhere('t_verify_kinerja', [
                'nip'                   => $nip,
                'periode'               => $periode,
                'tahun'                 => $tahun,
                'fid_pegawai_indikator' => $fid_ind,
            ])->row();

            if ($exists) {
                $data['updated_at'] = $now;
                $data['updated_by'] = $user;
                $ok = $this->crud->update('t_verify_kinerja', $data, ['id' => $exists->id]);
            } else {
                $data['created_at'] = $now;
                $data['created_by'] = $user;
                $ok = $this->crud->insert('t_verify_kinerja', $data);
            }

            if ($ok) {
                $proses++;
            } else {
                $gagal[] = 'Baris ' . ($i + 1) . ': gagal menyimpan (NIP ' . $nip . ').';
            }
        }

        echo json_encode([
            'status' => $proses > 0,
            'pesan'  => $proses > 0
                ? $proses . ' data berhasil direkonsiliasi.' . ($skip ? ' ' . $skip . ' baris kosong dilewati.' : '') . (count($gagal) ? ' ' . count($gagal) . ' baris gagal.' : '')
                : ($skip ? 'Tidak ada data yang diproses. ' . $skip . ' baris kosong dilewati.' : 'Tidak ada data yang diproses.'),
            'proses' => $proses,
            'skip'   => $skip,
            'gagal'  => $gagal,
        ]);
    }
}