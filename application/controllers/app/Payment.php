<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends CI_Controller
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
        if (!privilages('priv_default') && !privilages('priv_payment') || $this->session->userdata('is_valid_profile') === "0") :
            return show_404();
        endif;

        $this->load->model('ModelSpj', 'spj');
        $this->load->model('ModelPayment', 'payment');
        $this->load->model('ModelUsers', 'user');
        $this->load->model('ModelLog', 'historis');
    }

    public function index()
    {
        $data = [
            'title' => 'Payments',
            'content' => 'pages/spj/payments',
            'autoload_js' => [
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/blockUI/jquery.blockUI.js',
                'template/custom-js/payments.js',
            ],
            'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css'
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function ajaxTable()
    {
        $db = $this->payment->make_datatables();
        $data = array();
        $no = @$_POST['start'];

        foreach ($db as $r) {

            $status = $this->generate_status($r->status);
            $button = $this->generate_button($r);
            $tgl_approve_bendahara = $this->generate_approve_bendahara($r);

            $no++;
            $row = array();
            $row['no'] = $no;
            $row['no_buku'] = $r->nomor_pembukuan;
            $row['kode_uraian'] = "<br>" . $r->kode_uraian;
            $row['nama_uraian'] = $r->nama_part . '<br> - <b>' . $r->nama_uraian . '</b>';
            $row['periode'] = bulan($r->fid_periode);
            $row['tgl_approve'] = '<i class="fa fa-calendar"></i> ' . longdate_indo(substr($r->approve_at, 0, 10)) . ' <br> <i class="fa fa-clock-o"></i>' . substr($r->approve_at, 10, 6);
            $row['tgl_approve_bendahara'] = $tgl_approve_bendahara;
            $row['status'] = $status;
            $row['jumlah'] = $r->is_status === 'APPROVE' ? "<b class='text-success'> Rp. " . nominal((int) $r->jumlah) . "</b>" : "<b class='text-danger'> Rp. " . nominal((int) $r->jumlah) . "</b>";
            $row['action'] = $button;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'] ?? 0,
            "recordsTotal" => $this->payment->make_count_all(),
            "recordsFiltered" => $this->payment->make_count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function update()
    {
        $post = $this->input->post();
        $whr = [
            'token' => $post['token'],
        ];

        if ($post['verifikasi_status'] === 'CAIR') {
            $update = [
                'status' => 'CAIR',
                'cair_at' => DateTimeInput(),
                'approver_by' => $this->session->userdata('user_name'),
                'catatan' => null
            ];
            // insert log
            $info = [
                'token' => $post['token'],
                'status' => 'CAIR',
                'keterangan' => 'USULAN TELAH DI VERIFIKASI BENDAHARA - CAIR',
                'created_at' => DateTimeInput(),
                'created_by' => $this->session->userdata('user_name'),
                'tahun' => $this->session->userdata('tahun_anggaran'),
            ];

            $this->db->trans_start();
            $this->crud->update('spj_payment', $update, $whr);
            $this->historis->insert($info);
            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                $msg = [
                    'message' => 'SPJ gagal di proses',
                    'status' => false
                ];
                echo json_encode($msg);
                return false;
            }

            $msg = [
                'message' => 'SPJ telah di proses',
                'status' => true
            ];
            echo json_encode($msg);
            return false;
        }

        if ($post['verifikasi_status'] === 'PERBAIKAN') {
            $update = [
                'status' => 'PERBAIKAN',
                'perbaikan_at' => DateTimeInput(),
                'approver_by' => $this->session->userdata('user_name'),
                'catatan' => $post['catatan']
            ];
            // insert log
            $info = [
                'token' => $post['token'],
                'status' => 'PERBAIKAN',
                'keterangan' => 'USULAN TELAH DI VERIFIKASI BENDAHARA - PERBAIKAN (' . $post['catatan'] . ')',
                'created_at' => DateTimeInput(),
                'created_by' => $this->session->userdata('user_name'),
                'tahun' => $this->session->userdata('tahun_anggaran'),
            ];

            $this->db->trans_start();
            $this->crud->update('spj_payment', $update, $whr);
            $this->historis->insert($info);
            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                $msg = [
                    'message' => 'SPJ gagal diproses perbaikan !',
                    'status' => false
                ];
                echo json_encode($msg);
                return false;
            }

            $msg = [
                'message' => 'SPJ telah diproses perbaikan !',
                'status' => true
            ];
            echo json_encode($msg);
            return false;
        }

        if ($post['verifikasi_status'] === 'TOLAK') {
            $update = [
                'status' => 'TOLAK',
                'approver_by' => $this->session->userdata('user_name'),
                'tolak_at' => DateTimeInput(),
                'catatan' => $post['catatan']
            ];
            // insert log
            $info = [
                'token' => $post['token'],
                'status' => 'TOLAK',
                'keterangan' => 'USULAN TELAH DI VERIFIKASI BENDAHARA - TOLAK (' . $post['catatan'] . ')',
                'created_at' => DateTimeInput(),
                'created_by' => $this->session->userdata('user_name'),
                'tahun' => $this->session->userdata('tahun_anggaran'),
            ];

            $this->db->trans_start();
            $this->crud->update('spj_payment', $update, $whr);
            $this->historis->insert($info);
            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                $msg = [
                    'message' => 'SPJ gagal ditolak !',
                    'status' => false
                ];
                echo json_encode($msg);
                return false;
            }

            $msg = [
                'message' => 'SPJ berhasil ditolak !',
                'status' => true
            ];
            echo json_encode($msg);
            return false;
        }
    }

    public function batal()
    {
        $token = $this->input->post('id');
        $update = [
            'status' => 'PENDING',
            'pending_at' => DateTimeInput()
        ];

        $whr = [
            'token' => $token
        ];

        // insert log
        $info = [
            'token' => $token,
            'status' => 'PENDING',
            'keterangan' => 'USULAN TELAH DI VERIFIKASI BENDAHARA - DIPENDING',
            'created_at' => DateTimeInput(),
            'created_by' => $this->session->userdata('user_name'),
            'tahun' => $this->session->userdata('tahun_anggaran'),
        ];

        $this->db->trans_start();
        $this->crud->update('spj_payment', $update, $whr);
        $this->historis->insert($info);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            echo json_encode([
                'status' => false,
                'message' => 'Proses gagal dibatalkan',
            ]);
            return false;
        }

        echo json_encode([
            'status' => true,
            'message' => 'Proses berhasil dibatalkan !'
        ]);
    }

    private function generate_button($row)
    {
        $isProsesDisabled = in_array($row->status, ['CAIR', 'PERBAIKAN', 'TOLAK']) ? 'disabled' : '';
        $btnProses = '<button ' . $isProsesDisabled . ' type="button" onclick="ProsesApprover(this)" data-row="' . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . '"  class="btn btn-sm btn-primary p-2"><i class="fa fa-check-circle mr-2"></i> Proses</button>';

        if (!in_array($row->status, ['PENDING', 'PENDING - PERBAIKAN'])):
            $btnBatalProses = '<button type="button" onclick="BatalProsesApprover(this)" data-row="' . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . '"  class="btn btn-sm btn-danger p-2"><i class="fa fa-close mr-2"></i> Batalkan</button>';
        else:
            $btnBatalProses = '';
        endif;

        return $btnProses . $btnBatalProses;
    }

    private function generate_status($status)
    {
        // Daftar warna status (Bootstrap 5)
        $status_colors = [
            'CAIR' => 'success text-white',                // Hijau
            'PENDING' => 'secondary-animate text-white',   // Kuning
            'PENDING - PERBAIKAN' => 'warning', // Biru muda
            'PERBAIKAN' => 'warning text-dark',           // Biru
            'TOLAK' => 'danger  text-white',                // Merah
        ];

        $status_clean = strtoupper(trim($status));

        // Cek apakah status ada di daftar
        if (array_key_exists($status_clean, $status_colors)) {
            $color = $status_colors[$status_clean];
            return "<span class='badge p-2 bg-{$color}'>{$status_clean}</span>";
        }

        // Default jika tidak ditemukan
        return "<span class='badge bg-secondary'>UNKNOWN</span>";
    }

    public function generate_approve_bendahara($row)
    {
        switch ($row->status) {
            case 'CAIR':
                return '<i class="fa fa-calendar"></i> ' . longdate_indo(substr($row->cair_at, 0, 10)) . ' <br> <i class="fa fa-clock-o"></i>' . substr($row->cair_at, 10, 6);
                break;
            case 'PENDING':
                return '<i class="fa fa-calendar"></i> ' . longdate_indo(substr($row->pending_at, 0, 10)) . ' <br> <i class="fa fa-clock-o"></i>' . substr($row->pending_at, 10, 6);
            case 'PENDING - PERBAIKAN':
            case 'PERBAIKAN':
                return '<i class="fa fa-calendar"></i> ' . longdate_indo(substr($row->perbaikan_at, 0, 10)) . ' <br> <i class="fa fa-clock-o"></i>' . substr($row->perbaikan_at, 10, 6);
            case 'TOLAK':
                return '<i class="fa fa-calendar"></i> ' . longdate_indo(substr($row->tolak_at, 0, 10)) . ' <br> <i class="fa fa-clock-o"></i>' . substr($row->tolak_at, 10, 6);
            default:
                return '<i class="fa fa-calendar"></i> - <br> <i class="fa fa-clock-o"></i> -';
                break;
        }
    }
}
