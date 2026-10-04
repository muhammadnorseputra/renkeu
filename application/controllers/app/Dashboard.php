<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/userguide3/general/urls.html
     */
    public $ta;
    public $is_perubahan;

    public function __construct()
    {
        parent::__construct();
        cek_session();
        //  CEK USER PRIVILAGES
        if (! privilages('priv_default')):
            return show_404();
        endif;
        $this->load->model('ModelSpj', 'spj');
        $this->load->model('ModelTarget', 'target');
        $this->load->model('ModelRealisasi', 'realisasi');
        $this->load->model('ModelUsers', 'user');
        $this->ta           = $this->session->userdata('tahun_anggaran');
        $this->is_perubahan = $this->session->userdata('is_perubahan');
    }

    public function index()
    {
        // Panel Dashboard
        $db_program            = $this->crud->getWhere('ref_programs', ['tahun' => $this->ta]);
        $db_pegawai_mapping    = $this->crud->get('pegawai');
        $ProgramTotalPaguAwal  = 0;
        $ProgramTotalRealisasi = 0;
        foreach ($db_program->result() as $r):
            $ProgramTotalPaguAwal  += $this->target->getAlokasiPaguProgram($r->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
            $ProgramTotalRealisasi += $this->realisasi->getRealisasiTahunProgram($r->kode, $this->ta);
        endforeach;
        $persentase_capaian  = @($ProgramTotalRealisasi / $ProgramTotalPaguAwal) * 100;

        // Chart
        $db_transaksi = $this->spj->TopTransaksiSPJ(5);
        $spj_ms       = [];
        $spj_tms      = [];
        $spj_baru     = [];
        $spj_cair     = [];
        for ($i = 1; $i <= 12; $i++) {
            $jumlah      = $this->spj->TransaksiSpjBulanan($i, $this->ta) ?? 0;
            $jumlah_tms  = $this->spj->TransaksiSpjBulananNonMs($i, 'TMS', $this->ta) ?? 0;
            $jumlah_baru = $this->spj->TransaksiSpjBaru($i, $this->ta) ?? 0;
            $jumlah_cair = $this->spj->TransaksiSpjCair($i, $this->ta) ?? 0;
            $spj_ms[]    = [bulan($i), $jumlah];
            $spj_tms[]   = [bulan($i), $jumlah_tms];
            $spj_baru[]  = [bulan($i), $jumlah_baru];
            $spj_cair[]  = [bulan($i), $jumlah_cair];
        }
        // $db_triwulan = $this->spj->getPeriode();
        $db_parts       = $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN']);
        $label          = [];
        $part_jumlah    = [];
        $spj_count_ms   = [];
        $spj_count_tms  = [];
        $spj_count_baru = [];
        $spj_count_cair = [];
        foreach ($db_parts->result() as $part):
            $label[]          = $part->singkatan;
            $jumlah           = $this->spj->getRealisasiSpjByPart($part->id, $this->ta) ?? 0;
            $part_jumlah[]    = (int) $jumlah;
            $spj_count_ms[]   = (int) $this->spj->getJumlahSpjByPart($part->id, 'APPROVE', $this->ta) ?? 0;
            $spj_count_tms[]  = (int) $this->spj->getJumlahSpjByPart($part->id, 'TMS', $this->ta) ?? 0;
            $spj_count_baru[] = (int) $this->spj->getJumlahSpjByPartBaru($part->id, $this->ta) ?? 0;
            $spj_count_cair[] = (int) $this->spj->getJumlahSpjByStatusCair($part->id, 'CAIR', $this->ta) ?? 0;
        endforeach;

        // --- Agregat Kelompok & Jenis Belanja ---
        $is_perubahan = $this->session->userdata('is_perubahan');
        $ta           = $this->ta;

        // PAGU per Kelompok (murni / perubahan mengikuti sesi login)
        $pagu_kelompok = [];
        $db_pagu_kel = $this->db->select('k.id, k.kode, k.nama, SUM(p.total_pagu_awal) as total')
            ->from('ref_kelompok_belanja k')
            ->join('ref_jenis_belanja j', 'j.fid_kelompok_belanja = k.id', 'left')
            ->join('ref_uraians u', 'u.fid_jenis_belanja = j.id', 'left')
            ->join('t_pagu p', 'p.fid_uraian = u.id AND p.tahun = ' . $this->db->escape($ta) . ' AND p.is_perubahan = ' . $this->db->escape($is_perubahan), 'left')
            ->where('k.tahun', $ta)
            ->group_by('k.id')
            ->get();
        foreach ($db_pagu_kel->result() as $row):
            $pagu_kelompok[$row->id] = ['kode' => $row->kode, 'nama' => $row->nama, 'total_pagu' => (int) $row->total];
        endforeach;

        // PAGU per Jenis (murni / perubahan mengikuti sesi login)
        $pagu_jenis = [];
        $db_pagu_j = $this->db->select('j.id, j.kode, j.nama, SUM(p.total_pagu_awal) as total')
            ->from('ref_jenis_belanja j')
            ->join('ref_uraians u', 'u.fid_jenis_belanja = j.id', 'left')
            ->join('t_pagu p', 'p.fid_uraian = u.id AND p.tahun = ' . $this->db->escape($ta) . ' AND p.is_perubahan = ' . $this->db->escape($is_perubahan), 'left')
            ->where('j.tahun', $ta)
            ->group_by('j.id')
            ->get();
        foreach ($db_pagu_j->result() as $row):
            $pagu_jenis[$row->id] = ['kode' => $row->kode, 'nama' => $row->nama, 'total_pagu' => (int) $row->total];
        endforeach;

        // REALISASI per Kelompok (via spj_riwayat.kode_uraian -> ref_uraians.kode -> jenis -> kelompok)
        $real_kelompok = [];
        $db_rk = $this->db->select('k.id, k.nama, SUM(s.jumlah) as total')
            ->from('ref_kelompok_belanja k')
            ->join('ref_jenis_belanja j', 'j.fid_kelompok_belanja = k.id', 'left')
            ->join('ref_uraians u', 'u.fid_jenis_belanja = j.id', 'left')
            ->join('spj_riwayat s', "s.kode_uraian = u.kode AND s.is_status = 'APPROVE' AND s.tahun = " . $this->db->escape($ta))
            ->where('k.tahun', $ta)
            ->group_by('k.id')
            ->get();
        foreach ($db_rk->result() as $row):
            $real_kelompok[$row->id] = ['nama' => $row->nama, 'total_realisasi' => $row->total ?? 0];
        endforeach;

        // REALISASI per Jenis
        $real_jenis = [];
        $db_rj = $this->db->select('j.id, j.nama, SUM(s.jumlah) as total')
            ->from('ref_jenis_belanja j')
            ->join('ref_uraians u', 'u.fid_jenis_belanja = j.id', 'left')
            ->join('spj_riwayat s', "s.kode_uraian = u.kode AND s.is_status = 'APPROVE' AND s.tahun = " . $this->db->escape($ta))
            ->where('j.tahun', $ta)
            ->group_by('j.id')
            ->get();
        foreach ($db_rj->result() as $row):
            $real_jenis[$row->id] = ['nama' => $row->nama, 'total_realisasi' => $row->total ?? 0];
        endforeach;

        // Gabung pagu + realisasi jadi array siap chart
        $kelompok_ids = array_unique(array_merge(array_keys($pagu_kelompok), array_keys($real_kelompok)));
        $kelompok_chart = ['labels' => [], 'pagu' => [], 'realisasi' => []];
        foreach ($kelompok_ids as $id):
            $nama = $pagu_kelompok[$id]['nama'] ?? ($real_kelompok[$id]['nama'] ?? '-');
            $kelompok_chart['labels'][]    = $nama;
            $kelompok_chart['pagu'][]      = (int) ($pagu_kelompok[$id]['total_pagu'] ?? 0);
            $kelompok_chart['realisasi'][] = (int) ($real_kelompok[$id]['total_realisasi'] ?? 0);
        endforeach;

        $jenis_ids = array_unique(array_merge(array_keys($pagu_jenis), array_keys($real_jenis)));
        $jenis_chart = ['labels' => [], 'pagu' => [], 'realisasi' => []];
        foreach ($jenis_ids as $id):
            $nama = $pagu_jenis[$id]['nama'] ?? ($real_jenis[$id]['nama'] ?? '-');
            $jenis_chart['labels'][]    = $nama;
            $jenis_chart['pagu'][]      = (int) ($pagu_jenis[$id]['total_pagu'] ?? 0);
            $jenis_chart['realisasi'][] = (int) ($real_jenis[$id]['total_realisasi'] ?? 0);
        endforeach;

        $limit_anggaran = $this->spj->LimitTransaksiTriwulan($this->ta);

        $data = [
            'title'        => 'Dashboard',
            'content'      => 'pages/dashboard',
            'panel'        => [
                'program_total_pagu'      => $ProgramTotalPaguAwal,
                'program_total_realisasi' => $ProgramTotalRealisasi,
                'jumlah_pegawai'          => $db_pegawai_mapping->num_rows(),
                'persentase_capaian'      => $persentase_capaian,
            ],
            'chart'        => [
                'top_transaksi'    => $db_transaksi->result(),
                'spj_ms'           => json_encode($spj_ms),
                'spj_tms'          => json_encode($spj_tms),
                'spj_baru'         => json_encode($spj_baru),
                'spj_cair'         => json_encode($spj_cair),
                'triwulan_1'       => $this->spj->TransaksiTriwulan(["01", "02", "03"], $this->ta),
                'triwulan_2'       => $this->spj->TransaksiTriwulan(["04", "05", "06"], $this->ta),
                'triwulan_3'       => $this->spj->TransaksiTriwulan(["07", "08", "09"], $this->ta),
                'triwulan_4'       => $this->spj->TransaksiTriwulan(["10", "11", "12"], $this->ta),
                'limit_triwulan_1' => $limit_anggaran->triwulan_1,
                'limit_triwulan_2' => $limit_anggaran->triwulan_2,
                'limit_triwulan_3' => $limit_anggaran->triwulan_3,
                'limit_triwulan_4' => $limit_anggaran->triwulan_4,
                'part_label'       => json_encode($label),
                'part_jumlah'      => json_encode($part_jumlah),
                'spj_count_ms'     => json_encode($spj_count_ms),
                'spj_count_tms'    => json_encode($spj_count_tms),
                'spj_count_baru'   => json_encode($spj_count_baru),
                'spj_count_cair'   => json_encode($spj_count_cair),
                'kelompok_chart'   => json_encode($kelompok_chart),
                'jenis_chart'      => json_encode($jenis_chart),
            ],
            'autoload_css' => [
                'template/backend/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css',
            ],
            'autoload_js'  => [
                'template/backend/vendors/Chart.js/dist/Chart.bundle.min.js',
                'template/backend/vendors/jquery-sparkline/dist/jquery.sparkline.min.js',
                'template/backend/vendors/Flot/jquery.flot.js',
                'template/backend/vendors/Flot/jquery.flot.resize.js',
                'template/backend/vendors/Flot/jquery.flot.categories.js',
                'template/backend/vendors/Flot/jquery.flot.tooltip.js',
                'template/backend/vendors/Flot/jquery.flot.navigate.js',
                'template/backend/vendors/Flot/jquery.flot.selection.js',
                'template/backend/vendors/Flot/jquery.flot.threshold.js',
                'template/backend/vendors/flot.curvedlines/curvedLines.js',
                'template/backend/vendors/DateJS/build/date.js',
                'template/backend/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js',
                'template/custom-js/dashboard.js',
            ],
        ];
        $this->load->view('layout/app', $data);
    }

    public function laporan_pdf()
    {

        $data = [
            "dataku" => [
                "nama" => "Petani Kode",
                "url"  => "http://petanikode.com",
            ],
        ];

        $this->load->library('pdf');

        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = "laporan-petanikode.pdf";
        $this->pdf->load_view('laporan', $data);
    }

    public function statuspagu()
    {
        $is_perbahan = $this->input->post('is_perubahan');
        $this->session->set_userdata([
            'is_perubahan' => $is_perbahan,
        ]);
        redirect($this->input->post('redirectTo'));
    }

    public function cekProfile()
    {
        $userId       = decrypt_url($this->session->userdata('user_id'));
        $dbCekProfile = $this->user->profile_user_id($userId);

        $profile = $dbCekProfile->row();
        if ($dbCekProfile && $profile->nohp === "" || $profile->is_valid === "0") {
            echo json_encode([
                'status'  => false,
                'message' => 'Profile tidak lengkap ! (No. HP)',
                'data'    => '<p>Untuk meningkatkan keamanan akun Anda, silakan lengkapi informasi profil Anda dengan data yang valid dan terbaru.</p>
                <p>Silakan lengkapi dan perbarui informasi profil Anda.</p>
                <button type="button" class="btn btn-danger rounded-0" onclick="window.location.href=\'' . base_url('/app/account') . '\'">Update Profile Disini.</button>',
            ]);
            return false;
        }

        if ($dbCekProfile && $profile->nip === "" || $profile->is_valid === "0") {
            echo json_encode([
                'status'  => false,
                'message' => 'Profile tidak lengkap ! (NIP/NIK)',
                'data'    => '<p>Untuk meningkatkan keamanan akun Anda, silakan lengkapi informasi profil Anda dengan data yang valid dan terbaru.</p>
                <p>Silakan lengkapi dan perbarui informasi profil Anda.</p>
                <button type="button" class="btn btn-danger rounded-0" onclick="window.location.href=\'' . base_url('/app/account') . '\'">Update Profile Disini.</button>',
            ]);
            return false;
        }

        echo json_encode([
            'status'  => true,
            'message' => 'Profile lengkap !',
        ]);
    }
}
