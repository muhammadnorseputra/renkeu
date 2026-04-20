<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spj extends CI_Controller
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
        if (!privilages('priv_default')  && !privilages('priv_spj') || $this->session->userdata('is_valid_profile') === "0"):
            return show_404();
        endif;
        $this->load->model('ModelSpj', 'spj');
        $this->load->model('ModelTarget', 'target');
        $this->load->model('ModelRealisasi', 'realisasi');
        $this->load->model('ModelLog', 'historis');
        $this->load->helper('telegram');
    }

    public function index()
    {
        $jmlSpjEntri = $this->crud->getWhere('spj', ['is_status' => 'ENTRI', 'catatan' => null, 'fid_part' => $this->session->userdata('part'), 'tahun' => $this->session->userdata('tahun_anggaran')])->num_rows() ?? 0;

        $jmlSpjEntriPerbaikan = $this->crud->getWhere('spj', ['is_status' => 'ENTRI', 'catatan !=' => null, 'fid_part' => $this->session->userdata('part'), 'tahun' => $this->session->userdata('tahun_anggaran')])->num_rows() ?? 0;
        $jmlSpjVerfikasi = $this->crud->getWhere('spj', ['is_status' => 'VERIFIKASI', 'fid_part' => $this->session->userdata('part'), 'tahun' => $this->session->userdata('tahun_anggaran')])->num_rows() ?? 0;
        $jmlSpjApprove = $this->crud->getWhere('spj', ['is_status' => 'VERIFIKASI_ADMIN', 'fid_part' => $this->session->userdata('part'), 'tahun' => $this->session->userdata('tahun_anggaran')])->num_rows() ?? 0;

        if(in_array($this->session->userdata('role'), ['VERIFICATOR', 'ADMIN'])) {
            $list_bidang = $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN'])->result();
        } else {
            $list_bidang = $this->crud->getWhere('ref_parts', ['id' => $this->session->userdata('part')])->result();
        }
        
        $data = [
            'title' => 'SPJ (Surat Pertanggung Jawaban)',
            'content' => 'pages/spj/index',
            'list_bidang' => $list_bidang,
            'data' => [
                'jml_spj_baru' => $jmlSpjEntri,
                'jml_spj_perbaikan' => $jmlSpjEntriPerbaikan,
                'jml_spj_verifikasi' => $jmlSpjVerfikasi,
                'jml_spj_verifikasi_admin' => $jmlSpjApprove,
            ],
            'autoload_js' => [
                'template/custom-js/list.min.js',
                'template/custom-js/list-state.js',
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/backend/vendors/moment/min/moment.min.js',
                'template/backend/vendors/bootstrap-daterangepicker/daterangepicker.js',
                'template/custom-js/blockUI/jquery.blockUI.js',
                'template/custom-js/tabel-verifikasi.js',
                'template/custom-js/tabel-verifikasi-selesai.js',
                'template/custom-js/tabel-payments.js',
                'template/custom-js/spj.js',
            ],
            'autoload_css' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.css',
                'template/backend/vendors/bootstrap-daterangepicker/daterangepicker.css',
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function inbox()
    {
        $db = $this->spj->inbox();

        $btnAdd = '<div class="col-md-3 mt-3">
        <button class="btn btn-primary rounded-0 float-right btn-block" onclick="window.location.href=\'' . base_url("app/spj/buatusul") . '\'"><i class="fa fa-plus mr-2"></i> Buat Usulan Baru</button>
                </div>
                ';
        $search = '<div class="col-5 col-md-3">Pencarian <input type="search" class="search form-control" placeholder="Ketik nama rincian..." /></div>';
        $pagging = '<div class="col-4 col-md-6">Halaman <ul class="pagination"></ul></div>';

        $html = '<div id="spjList"><div class="row">' . $search . $pagging . $btnAdd . "</div>";
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>Kode</th>
                        <th>Uraian</th>
                        <th width="8%">SPJ Bulan</th>
                        <th width="10%">Jumlah (Rp)</th>
                        <th>Status</th>
                        <th width="18%">Tanggal Entri</th>
                        <th>Jumlah Penerima</th>
                        <th>Berkas</th>
                        <th class="text-center" colspan="3">Aksi</th>
                    </tr>
                    </thead>';
        $html .= '<tbody class="list">';
        $no = 1;
        foreach ($db->result() as $r):
            // Catatan
            $catatan = isset($r->catatan) && !empty($r->catatan) && $r->is_status === 'ENTRI' ? '<span class="text-danger"><i class="fa fa-exclamation-triangle mr-2"></i> ' . substr($r->catatan, 0, 70) . '...</span>' : '';
            // Status
            if ($r->is_status === 'ENTRI') {
                $status = '<span class="badge p-2 badge-secondary"><i class="fa fa-edit mr-2"></i> ENTRI</span>';
            } elseif ($r->is_status === 'VERIFIKASI') {
                $status = '<span class="badge p-2 badge-primary"><i class="fa fa-lock mr-2"></i> VERIFIKASI</span>';
            } elseif ($r->is_status === 'VERIFIKASI_ADMIN') {
                $status = '<span class="badge p-2 badge-info"><i class="fa fa-lock mr-2"></i> VERIFIKASI ADMIN</span>';
            } elseif ($r->is_status === 'APPROVE') {
                $status = '<span class="badge p-2 badge-success"><i class="fa fa-check-circle mr-2"></i> APPROVE</span>';
            } elseif ($r->is_status === 'BTL') {
                $status = '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> BTL</span>';
            } else {
                $status = '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> TMS</span>';
            }

            if (isset($r->berkas_link) && !empty($r->berkas_link)) {
                $link = '<a href="' . $r->berkas_link . '" target="_blank"><i class="fa fa-link"></i> <br> Berkas</a>';
            } else {
                $link = '<i class="text-secondary">Kosong</i>';
            }

            // cek di riwayat apakah ada atau tidak
            $history = $this->crud->getWhere('spj_riwayat', ['token' => $r->token])->num_rows();
            // jika ada riwayat maka tidak bisa di hapus
            if ($history > 0) {
                $isDeleteDisabled = 'disabled';
            } else {
                $isDeleteDisabled = '';
            }

            if ($r->is_status === 'VERIFIKASI' || $r->is_status === 'VERIFIKASI_ADMIN') {
                $detail = '<button onclick="window.location.replace(\'' . base_url('app/spj/buatusul?step=3&status=' . $r->is_status . '&token=' . $r->token) . '\')" type="button" class="btn btn-sm btn-success m-0 rounded-0"><i class="fa fa-eye"></i> <br> Detail</button>';
            } elseif ($r->is_status === 'APPROVE' || $r->is_status === 'TMS' || $r->is_status === 'SELESAI_TMS' || $r->is_status === 'BTL' || $r->is_status === 'SELESAI_BTL') {
                $detail = '<button onclick="window.location.replace(\'' . base_url('app/spj/buatusul?step=4&status=' . $r->is_status . '&token=' . $r->token) . '\')" type="button" class="btn btn-sm btn-success m-0 rounded-0"><i class="fa fa-eye"></i> <br> Detail</button>';
            } else {
                $detail = '
                        <td class="text-center">
                            <button onclick="window.location.replace(\'' . base_url('app/spj/buatusul?step=0&status=entri&token=' . $r->token) . '\')" type="button" class="btn btn-sm btn-primary m-0 rounded-0"><i class="fa fa-pencil"></i> <br> Ubah</button> 
                        </td>
                        <td class="text-center">
                            <button onclick="HapusUsulan(\'' . base_url('app/spj/hapususulan/' . $r->token) . '\')" type="button" class="btn btn-sm btn-danger m-0 rounded-0 pull-right"
                            ' . $isDeleteDisabled . '>
                                <i class="fa fa-trash"></i> <br> Hapus
                            </button>
                        </td>
                    ';
            }

            // jumlah penerima manfaat
                $db_jml_penerima_manfaat = $this->crud->getWhere('spj_relasi_publik', ['token' => $r->token])->num_rows();
    
                if ($db_jml_penerima_manfaat > 0) {
                    $jml_penerima_manfaat = '<span class="text-info"><i class="fa fa-users mr-2"></i> ' . $db_jml_penerima_manfaat . '</span>';
                } else 
                {
                    $jml_penerima_manfaat = '<span class="text-secondary"><i class="fa fa-users mr-2"></i> 0</span>';
                }

            $html .= '<tr>
                <td class="text-center">
                    ' . $no . '
                </td>
                <td class="kode">
                ' . $r->kode_sub_kegiatan . ' <br> ' . $r->kode_uraian . '
                </td>
                <td class="nama">
                ' . $r->nama_sub_kegiatan . ' <br>- <b>' . $r->nama_uraian . '</b>
                </td>
                <td>
                    ' . bulan(strtoupper($r->periode_id)) . '
                </td>
                <td>
                    <b class="text-success">Rp. ' . nominal($r->jumlah) . '</b>
                </td>
                <td>
                    ' . $status . ' <br> ' . $catatan . '
                </td>
                <td>
                    <i class="fa fa-calendar mr-1"></i> ' . longdate_indo(substr($r->entri_at, 0, 10)) . "<br>  <i class='fa fa-clock-o mr-1'></i> " . substr($r->entri_at, 10, 6) .
                '</td>
                <td>
                    ' . $jml_penerima_manfaat . '
                </td>   
                <td width="5%" class="text-center">
                    ' . $link . '
                </td>
                <td width="5%" class="text-center">
                    ' . $detail . '
                </td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div></div>';

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Usulan <b>SPJ</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function verifikasi()
    {
        $db = $this->spj->make_datatables();
        $data = array();
        $no = @$_POST['start'];

        foreach ($db as $r) {

            $userusul = $this->users->profile_username($r->entri_by)->row();

            if ($r->is_status === 'VERIFIKASI_ADMIN' || $r->is_status === 'TMS' || $r->is_status === 'BTL' || privilages('priv_approve')) {
                $selesai = '<button type="button" onclick="Selesai(\'' . $r->token . '\')" class="btn btn-sm btn-success m-0 rounded-0"><i class="fa fa-check-circle"></i> <br> Selesai</button>';
                $detail = '<button onclick="window.location.href = \'' . base_url('app/spj/verifikasi_usul/' . $r->token) . '\'" type="button" class="btn btn-sm btn-warning m-0 rounded-0"><i class="fa fa-pencil"></i> <br> Ubah</button>';
            } else {
                $detail = '<button onclick="window.location.href = \'' . base_url('app/spj/verifikasi_usul/' . $r->token) . '\'" type="button" class="btn btn-sm btn-link m-0 rounded-0"><i class="fa fa-check-circle"></i> Verifikasi</button>';
                $selesai = '';
            }

            if ($r->is_status === 'ENTRI') {
                $status = '<span class="badge p-2 badge-secondary"><i class="fa fa-edit mr-2"></i> ENTRI</span>';
            } elseif ($r->is_status === 'VERIFIKASI') {
                $status = '<span class="badge p-2 badge-primary"><i class="fa fa-lock mr-2"></i> VERIFIKASI</span>';
            } elseif ($r->is_status === 'VERIFIKASI_ADMIN') {
                $status = '<span class="badge p-2 badge-primary"><i class="fa fa-lock mr-2"></i> VERIFIKASI ADMIN</span>';
            } elseif ($r->is_status === 'APPROVE') {
                $status = '<span class="badge p-2 badge-success"><i class="fa fa-check-circle mr-2"></i> APPROVE</span>';
            } elseif ($r->is_status === 'BTL') {
                $status = '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> BTL</span>';
            } elseif ($r->is_status === 'TMS') {
                $status = '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> TMS</span>';
            } else {
                $status = '<span class="badge p-2 badge-success"><i class="fa fa-check-circle mr-2"></i> SELESAI</span>';
            }

            $no++;
            $row = array();
            $row['no'] = $no;
            $row['kode'] = '<br>' . $r->kode_uraian;
            $row['uraian'] = $r->nama_sub_kegiatan . '<br> - <b>' . $r->nama_uraian . '</b>';
            $row['periode'] = bulan($r->fid_periode);
            $row['bidang'] = $r->nama_part;
            $row['userinfo'] = '<i class="fa fa-calendar"></i> ' . longdate_indo(substr($r->entri_at, 0, 10)) . "<br>  <i class='fa fa-clock-o'></i> " . substr($r->entri_at, 10, 6) . " <i class='fa fa-user'></i> " . $userusul->nama;
            $row['status'] = $status;
            $row['jumlah'] = "<b class='text-success'> Rp. " . nominal($r->jumlah) . "</b>";
            $row['action'] = $detail . " " . $selesai;
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->spj->make_count_all(),
            "recordsFiltered" => $this->spj->make_count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function verifikasi_usul($token)
    {
        $data = [
            'title' => 'Verifikasi Usul SPJ',
            'content' => 'pages/spj/verifikasi',
            'detail' => $this->spj->detail(['token' => $token])->row(),
            'autoload_js' => [
                'template/backend/vendors/moment/min/moment.min.js',
                'template/backend/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/blockUI/jquery.blockUI.js',
                'template/custom-js/spj_verifikasi.js',
            ],
            'autoload_css' => [
                'template/backend/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function verifikasi_proses()
    {
        $input = $this->input->post();
        $token = $input['token'];
        $getSpj = $this->crud->getWhere('spj', ['token' => $token])->row();

        $whr = [
            'token' => $token
        ];

        if ($input['status'] == 'MS') {
            $update = [
                'nomor_verifikasi' => $input['nomor'],
                'tanggal_verifikasi' => formatToSQL($input['tanggal']),
                'is_status' => 'VERIFIKASI_ADMIN',
                'is_realisasi' => $input['is_realisasi'],
                'catatan' => '',
                'verify_by' => $this->session->userdata('user_name'),
                'verify_at' => DateTimeInput(),
            ];
            $db = $this->crud->update('spj', $update, $whr);
            // insert log
            $info = [
                'token' => $token,
                'status' => 'VERIFIKASI',
                'keterangan' => 'USULAN TELAH DI VERIFIKASI - MEMENUHI SYARAT (MS)',
                'created_at' => DateTimeInput(),
                'created_by' => $this->session->userdata('user_name'),
            ];
        } elseif ($input['status'] == 'TMS' || $input['status'] == 'BTL') {
            $update = [
                'nomor_verifikasi' => '',
                'tanggal_verifikasi' => '',
                'is_realisasi' => '',
                'catatan' => $input['catatan'],
                'is_status' => $input['status'],
                'verify_by' => $this->session->userdata('user_name'),
                'verify_at' => DateTimeInput(),
            ];
            $db = $this->crud->update('spj', $update, $whr);
            // insert log
            $info = [
                'token' => $token,
                'status' => 'VERIFIKASI',
                'keterangan' => 'USULAN TELAH DI VERIFIKASI - ' . strtoupper($input['status']) . ' (' . $input['catatan'] . ')',
                'created_at' => DateTimeInput(),
                'created_by' => $this->session->userdata('user_name'),
                'tahun' => $this->session->userdata('tahun_anggaran'),
            ];
        } else {
            $update = [
                'is_status' => $input['status'],
                'catatan_by' => $this->session->userdata('role'),
                'catatan' => $input['catatan'],
            ];
            $db = $this->crud->update('spj', $update, $whr);
            // insert log
            $info = [
                'token' => $token,
                'status' => 'VERIFIKASI',
                'keterangan' => 'USULAN TELAH DI VERIFIKASI - ' . strtoupper($input['status']) . ' (' . $input['catatan'] . ')',
                'created_at' => DateTimeInput(),
                'created_by' => $this->session->userdata('user_name'),
                'tahun' => $this->session->userdata('tahun_anggaran'),
            ];
        }

        // Kirim Notifikasi WA ke user
        $send = $this->sendNotifyUser($getSpj, $input);

        $log = $this->historis->insert($info);

        if ($db && $send && $log) {
            if ($input['status'] == 'MS' || $input['status'] == 'TMS' && $this->session->userdata('role') === 'VERIFICATOR'):
                $this->sendNotifyAdmin($getSpj, $input);
            endif;
            $msg = ['pesan' => 'Usulan SPJ Berhasil Di Proses', 'code' => 200, 'redirect' => base_url('app/spj/?tab=%23verifikasi'), 'send_wa' => $send];
        } else {
            $msg = ['pesan' => 'Usulan SPJ Gagal Di Proses', 'code' => 400];
        }

        echo json_encode($msg);
    }

    private function sendNotifyUser($getSpj, $input)
    {
        // Kirim Notifikasi WA ke user
        $getUser = $this->users->profile_username($getSpj->entri_by)->row();
        $session = $this->session->userdata();

        // Keterangan No BKU jika status MS
        if ($input['status'] === 'MS') {
            $note_tambahan = 'No. Verifikasi : ' . (isset($input['nomor']) && !empty($input['nomor']) ? $input['nomor'] : '-') . '
Tgl. Verifikasi : ' . (isset($input['tanggal']) && !empty($input['tanggal']) ? longdate_indo(formatToSQL($input['tanggal'])) : '-') . '
Realisasi SPJ : ' . (isset($input['is_realisasi']) && !empty($input['is_realisasi']) ? $input['is_realisasi'] : '-') . '';
            $is_proses_admin = 'Selanjutnya akan diverifikasi oleh Admin.';
        } else {
            $note_tambahan = '';
            $is_proses_admin = '';
        }

        $send = TeleSendMessage($getUser->telegram_id, TemplateMessageHasilVerifikasi($getSpj, $input, $note_tambahan, $is_proses_admin, $session));

        return $send;
    }

    private function sendNotifyAdmin($getSpj, $input, $user_admin = 'abduh')
    {
        // Kirim Notifikasi WA ke Admin
        $session = $this->session->userdata();
        $getAdmin = $this->users->profile_username($user_admin)->row();
        $sendAdmin = TeleSendMessage($getAdmin->telegram_id, TemplateMessageHasilVerifikasiAdmin($getSpj, $input, $session));

        return $sendAdmin;
    }

    public function verifikasi_proses_selesai()
    {
        $input = $this->input->post();
        $token = $input['token'];
        $detailUsul = $this->crud->getWhere('spj', ['token' => $token])->row();

        $nama_part = $this->spj->getNama('ref_parts', $detailUsul->fid_part);
        $nama_program = $this->spj->getNama('ref_programs', $detailUsul->fid_program);
        $nama_kegiatan = $this->spj->getNama('ref_kegiatans', $detailUsul->fid_kegiatan);
        $nama_sub_kegiatan = $this->spj->getNama('ref_sub_kegiatans', $detailUsul->fid_sub_kegiatan);
        $nama_uraian = $this->spj->getNama('ref_uraians', $detailUsul->fid_uraian);

        $kode_kegiatan = $this->spj->getKode('ref_kegiatans', $detailUsul->fid_kegiatan);
        $kode_sub_kegiatan = $this->spj->getKode('ref_sub_kegiatans', $detailUsul->fid_sub_kegiatan);
        $kode_uraian = $this->spj->getKode('ref_uraians', $detailUsul->fid_uraian);
        $kode_program = $this->spj->getKode('ref_programs', $detailUsul->fid_program);


        if ($detailUsul->is_status === 'BTL') {
            $is_status = 'SELESAI_BTL';
        } elseif ($detailUsul->is_status === 'TMS') {
            $is_status = 'SELESAI_TMS';
        } else {
            $is_status = 'SELESAI';
        }

        $update = [
            'is_status' => $is_status,
            'approve_by' => $this->session->userdata('user_name'),
            'approve_at' => DateTimeInput(),
        ];

        $whr = [
            'token' => $detailUsul->token
        ];

        $insert = [
            'token' => $detailUsul->token,
            'fid_periode' => $detailUsul->fid_periode,
            'nama_part' => $nama_part,
            'nama_program' => $nama_program,
            'nama_kegiatan' => $nama_kegiatan,
            'nama_sub_kegiatan' => $nama_sub_kegiatan,
            'nama_uraian' => $nama_uraian,
            'kode_program' => $kode_program,
            'kode_kegiatan' => $kode_kegiatan,
            'kode_sub_kegiatan' => $kode_sub_kegiatan,
            'kode_uraian' => $kode_uraian,
            'koderek' => $detailUsul->koderek,
            'nomor_verifikasi' => @$detailUsul->nomor_verifikasi,
            'bulan' => $detailUsul->bulan,
            'tahun' => $detailUsul->tahun,
            'tanggal_verifikasi' => @$detailUsul->tanggal_verifikasi,
            'jumlah' => $detailUsul->jumlah,
            'uraian' => $detailUsul->uraian,
            'is_status' => $is_status === 'SELESAI' ? 'APPROVE' : $detailUsul->is_status,
            'is_realisasi' => @$detailUsul->is_realisasi,
            'catatan' => $detailUsul->catatan,
            'approve_by' => $is_status === 'SELESAI' ? $this->session->userdata('user_name') : $detailUsul->approve_by,
            'approve_at' => $is_status === 'SELESAI' ? DateTimeInput()  : $detailUsul->approve_at,
            'entri_at' => $detailUsul->entri_at,
            'entri_by' => $detailUsul->entri_by,
            'entri_by_part' => $detailUsul->entri_by_part,
            'verify_at' => $detailUsul->verify_at,
            'verify_by' => $detailUsul->verify_by,
            'berkas_file' => $detailUsul->berkas_file,
            'berkas_link' => $detailUsul->berkas_link
        ];

        $new_payment = [
            'token' => $detailUsul->token,
            'status' => 'PENDING',
            'pending_at' => DateTimeInput(),
            'tahun' => $this->session->userdata('tahun_anggaran'),
        ];

        // insert log
        $info = [
            'token' => $detailUsul->token,
            'status' => 'APPROVAL',
            'keterangan' => 'USULAN TELAH DI VERIFIKASI ADMIN (' . $is_status . ')',
            'created_at' => DateTimeInput(),
            'created_by' => $this->session->userdata('user_name'),
            'tahun' => $this->session->userdata('tahun_anggaran'),
        ];

        $this->db->trans_start(); // mulai transaksi otomatis
        $session = $this->session->userdata();
        $getUser = $this->users->profile_username($detailUsul->entri_by)->row();
        $send_message = TeleSendMessage($getUser->telegram_id, TemplateMessageApproval($detailUsul, $is_status, $session));
        $update_to_spj = $this->crud->update('spj', $update, $whr);
        $save_to_riwayat = $this->crud->insert('spj_riwayat', $insert);
        $save_to_log = $this->historis->insert($info);

        $cek_payment = $this->crud->getWhere('spj_payment', ['token' => $detailUsul->token])->num_rows();
        if ($cek_payment > 0) {
            // update payment jadi pending lagi
            $new_payment_update = [
                'status' => 'PENDING - PERBAIKAN',
                'pending_at' => DateTimeInput(),
            ];
            $save_to_payment = $this->crud->update('spj_payment', $new_payment_update, ['token' => $detailUsul->token]);
        } else {
            // insert payment baru
            $save_to_payment = $this->crud->insert('spj_payment', $new_payment);
        }
        $this->db->trans_complete(); // selesai transaksi

        if (
            $this->db->trans_status() === FALSE ||
            !$update_to_spj || !$send_message || !$save_to_log || !$save_to_riwayat || !$save_to_payment
        ) {
            // Rollback otomatis kalau ada yang gagal
            $msg = ['pesan' => 'Gagal', 'code' => 400, 'send_wa' => $send_message];
        } else {
            // Commit otomatis kalau semua sukses
            $msg = ['pesan' => 'Oke', 'code' => 200, 'send_wa' => $send_message];
        }

        echo json_encode($msg);
    }


    public function verifikasi_selesai()
    {
        $filter = $this->input->get();
        $db = $this->spj->make_datatables_verifikasi_selesai($filter);
        $data = array();
        $no = @$_POST['start'];

        foreach ($db as $r) {

            $userusul = $this->users->profile_username($r->entri_by)->row();
            $status = $this->generate_status($r->is_status);
            $status_bendahara = $this->generate_status_by_bendahara($r->status);
            $button = $this->generate_button($r);

            $no++;
            $row = array();
            $row['no'] = $no;
            $row['no_verifikasi'] = $r->nomor_verifikasi;
            $row['no_buku'] = $r->nomor_pembukuan;
            // $row[] = '<br>' . $r->kode_program . '<br>' . $r->kode_kegiatan . ' <br> ' . $r->kode_sub_kegiatan . ' <br> ' . $r->kode_uraian;
            $row['kode_uraian'] = "<br>" . $r->kode_uraian;
            // $row[] = '<b>' . $r->nama_part . '</b> <br>' . $r->nama_program . ' <br/>  ' . strtoupper($r->nama_kegiatan) . ' <br>  ' . $r->nama_sub_kegiatan . ' <br> <b>' . $r->nama_uraian . '</b>';
            $row['nama_uraian'] = $r->nama_sub_kegiatan . '<br> - <b>' . $r->nama_uraian . '</b>';
            $row['bidang'] = $r->nama_part;
            $row['periode'] = bulan($r->periode_id);
            $row['userinfo'] = '<i class="fa fa-calendar"></i> ' . longdate_indo(substr($r->entri_at, 0, 10)) . "<br>  <i class='fa fa-clock-o'></i> " . substr($r->entri_at, 10, 6) . " <i class='fa fa-user'></i> " . $userusul->nama;
            $row['tgl_approve'] = '<i class="fa fa-calendar"></i> ' . longdate_indo(substr($r->approve_at, 0, 10)) . ' <br> <i class="fa fa-clock-o"></i>' . substr($r->approve_at, 10, 6);
            $row['status'] = $status;
            $row['status_bendahara'] = $status_bendahara;
            $row['jumlah'] = $r->is_status === 'APPROVE' ? "<b class='text-success'> Rp. " . nominal((int) $r->jumlah) . "</b>" : "<b class='text-danger'> Rp. " . nominal((int) $r->jumlah) . "</b>";
            $row['action'] = $button;
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->spj->make_count_all_verifikasi_selesai($filter),
            "recordsFiltered" => $this->spj->make_count_filtered_verifikasi_selesai($filter),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function generate_status($status_code)
    {
        switch ($status_code) {
            case 'ENTRI':
                return '<span class="badge p-2 badge-secondary"><i class="fa fa-edit mr-2"></i> ENTRI</span>';
            case 'VERIFIKASI':
            case 'VERIFIKASI_ADMIN':
                return '<span class="badge p-2 badge-primary"><i class="fa fa-lock mr-2"></i> VERIFIKASI</span>';
            case 'APPROVE':
                return '<span class="badge p-2 badge-success"><i class="fa fa-check-circle mr-2"></i> APPROVE</span>';
            case 'BTL':
                return '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> BTL</span>';
            case 'TMS':
                return '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> TMS</span>';
            default:
                return '<span class="badge p-2 badge-success"><i class="fa fa-check-circle mr-2"></i> SELESAI</span>';
        }
    }

    private function generate_status_by_bendahara($status)
    {
        switch ($status) {
            case 'CAIR':
                return '<span class="badge p-2 badge-success"><i class="fa fa-check-circle mr-2"></i> CAIR</span>';
            case 'PENDING':
                return '<span class="badge p-2 badge-secondary"><i class="fa fa-lock mr-2"></i> PENDING</span>';
            case 'PENDING - PERBAIKAN':
                return '<span class="badge p-2 badge-secondary"><i class="fa fa-lock mr-2"></i> PENDING - PERBAIKAN</span>';
            case 'PERBAIKAN':
                return '<span class="badge p-2 badge-warning"><i class="fa fa-check-circle mr-2"></i> PERBAIKAN</span>';
            case 'TOLAK':
                return '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> TOLAK</span>';
            default:
                return '<span class="badge p-2 badge-light"><i class="fa fa-info-circle mr-2"></i> BELUM DIVERIFIKASI</span>';
        }
    }

    private function generate_button($r)
    {
        // cek apakah status pada tabel spj_payment === 'PERBAIKAN' atau 'TOLAK'
        $payment_status = $this->crud->getWhere('spj_payment', ['token' => $r->token])->row();
        if ($payment_status && in_array($payment_status->status, ['CAIR', 'PENDING'])) {
            $isRollBackDisabled = 'disabled bg-light text-secondary';
        } else {
            $isRollBackDisabled = 'bg-danger text-white';
        }

        // Button Rollback hanya untuk verifikator
        if ($this->session->userdata('role') === 'VERIFICATOR'):
            $btn_rollback = '<button class="dropdown-item d-flex justify-content-between ' . $isRollBackDisabled . '" onclick="Rollback(\'' . $r->token . '\')">
                                Rollback <i class="fa fa-repeat text-warning"></i></button>';
        else:
            $btn_rollback = '';
        endif;

        // Button Log
        $btn_log = '<a class="dropdown-item d-flex justify-content-between text-info" href="#" onclick="LogHistoris(\'' . $r->token . '\')">
                        Log <i class="fa fa-file-text text-info"></i></a>';

        return '<div class="dropdown">
                            <button class="btn btn-sm btn-icon-only text-dark bg-white rounded" type="button" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              action <i class="fa fa-ellipsis-v ml-3"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                              <a class="dropdown-item d-flex justify-content-between" href="' . base_url('app/spj/verifikasi_selesai_detail/' . $r->token) . '">
                                Detail <i class="fa fa-eye"></i>
                              </a>
                              <a class="dropdown-item d-flex justify-content-between text-success" href="' . $r->berkas_link . '" target="_blank">
                                Berkas <i class="fa fa-link"></i>
                              </a>
                              ' . $btn_rollback . '
                                ' . $btn_log . '
                            </div>
                        </div>';
    }

    public function verifikasi_selesai_detail($token)
    {
        $data = [
            'title' => 'Verifikasi Selesai',
            'content' => 'pages/spj/verifikasi_selesai_detail',
            'detail' => $this->spj->riwayat(['token' => $token])->row(),
            'payment' => $this->spj->riwayat_payment(['token' => $token])->row()
        ];
        $this->load->view('layout/app', $data);
    }

    public function log_historis($token)
    {
        $data = $this->historis->getWhere(['token' => $token]);
        if ($data->num_rows() > 0):
            $html = '<ul class="list-unstyled timeline">';
            foreach ($data->result() as $r):

                // Tentukan warna tag berdasarkan status
                $status = strtolower($r->status);
                switch ($status) {
                    case 'rollback':
                        $color = 'text-warning'; // hijau
                        break;
                    case 'tolak':
                        $color = 'text-danger'; // hijau
                        break;
                    case 'verifikasi':
                        $color = 'text-primary'; // merah
                        break;
                    case 'approval':
                        $color = 'text-success'; // kuning
                        break;
                    case 'pending':
                        $color = 'text-secondary'; // kuning
                        break;
                    case 'cair':
                        $color = 'text-info'; // kuning
                        break;
                    default:
                        $color = 'text-dark'; // biru (default)
                        break;
                }

                $html .= '<li>
                    <div class="block">
                        <div class="block_content">
                            <h2 class="title ' . $color . '">
                                <a>' . htmlspecialchars(ucwords($r->status)) . '</a>
                            </h2>
                            <div class="byline">
                                <span>' . longdate_indo(substr($r->created_at, 0, 10)) . ' ' . substr($r->created_at, 10, 6) . '</span> 
                                by <a>' . htmlspecialchars($r->created_by) . '</a>
                            </div>
                            <p class="excerpt">' . htmlspecialchars($r->keterangan) . '</p>
                        </div>
                    </div>
                  </li>';
            endforeach;
            $html .= '</ul>';
            $data = ['result' => $html, 'msg' => 'Data Historis Ditemukan', 'code' => 200];
        else:
            $html = '<ul class="list-unstyled timeline">
                <li>
                    <div class="block text-center">
                        <p>Data Historis Tidak Ditemukan</p>
                    </div>
                </li>
            </ul>';
            $data = ['result' => $html, 'msg' => 'Data Historis Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function buatusul()
    {
        $getToken = isset($_GET['token']);
        if ($getToken) {
            $detail = $this->crud->getWhere('spj', ['token' => $_GET['token']])->row();
        }

        $data = [
            'title' => 'Entri Usul - SPJ (Surat Pertanggung Jawaban)',
            'content' => 'pages/spj/usul',
            'list_bidang' => $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN'])->result(),
            'list_program' => $this->target->program(null, $this->session->userdata('part'), $this->session->userdata('tahun_anggaran'))->result(),
            'detail' => @$detail,
            'autoload_js' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.js',
                'template/backend/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js',
                'template/backend/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js',
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/blockUI/jquery.blockUI.js',
                'template/custom-js/spj_usul.js',
                'template/custom-js/tabel-penerima-manfaat.js',
                'template/custom-js/rupiah.js',
            ],
            'autoload_css' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.css',
                'template/backend/vendors/select2/dist/css/select2.min.css',
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function final()
    {
        $input = $this->input->post();
        $token = $input['token'];

        $update = [
            'is_status' => 'VERIFIKASI',
        ];

        $whr = [
            'token' => $token
        ];

        // notif wa
        $user = $this->crud->getWhere('spj', ['token' => $input['token']])->row();
        $penerima = $this->crud->getWhere('spj_relasi_publik', ['token' => $input['token']]);
        $getUser = $this->users->profile_username($user->entri_by)->row();
        $send = TeleSendMessage($getUser->telegram_id, TemplateMessageFinal($getUser, $user, $penerima->num_rows()));

        $info = [
            'token' => $input['token'],
            'status' => 'KIRIM USULAN',
            'keterangan' => 'USULAN DIKIRIM',
            'created_at' => DateTimeInput(),
            'created_by' => $this->session->userdata('user_name'),
            'tahun' => $this->session->userdata('tahun_anggaran'),
        ];

        $db = $this->crud->update('spj', $update, $whr);
        $log = $this->historis->insert($info);

        if ($db && $log && $send) {
            $msg = ['msg' => 'Usulan SPJ Berhasil Di Finalisasi', 'status' => true, 'redirect' => base_url('app/spj/buatusul?step=4&status=VERIFIKASI&token=' . $input['token']), 'send_wa' => $send];
        } else {
            $msg = ['msg' => 'Usulan SPJ Gagal Di Finalisasi', 'status' => false, 'send_wa' => $send];
        }

        header('Content-Type: application/json');
        echo json_encode($msg);
    }

    public function carikode()
    {
        $input = $this->input->post();
        $kode_kegiatan = $this->crud->getWhere('ref_kegiatans', ['id' => $input['kegiatan']])->row();
        $kode_subkegiatan = $this->crud->getWhere('ref_sub_kegiatans', ['id' => $input['sub_kegiatan']])->row();
        $kode_uraian = $this->crud->getWhere('ref_uraians', ['id' => $input['uraian_kegiatan']])->row();

        // Pagu Awal
        $totalPaguAwal = $this->target->getAlokasiPaguUraian($input['uraian_kegiatan'], $this->session->userdata('is_perubahan'))->row()->total_pagu_awal ?? 0;
        $totalRealisasiPagu = $this->realisasi->getRealisasiTahunanUraian($input['uraian_kegiatan'], ['VERIFIKASI', 'VERIFIKASI_ADMIN', 'SELESAI']);
        $totalSisaPagu = ($totalPaguAwal - $totalRealisasiPagu);

        $data = [
            'part_id' => $input['part'],
            'program_id' => $input['program'],
            'kegiatan_id' => $input['kegiatan'],
            'subkegiatan_id' => $input['sub_kegiatan'],
            'uraian_id' => $input['uraian_kegiatan'],
            'kode_kegiatan' => $kode_kegiatan->kode,
            'nama_kegiatan' => $kode_kegiatan->nama,
            'kode_subkegiatan' => $kode_subkegiatan->kode,
            'nama_subkegiatan' => $kode_subkegiatan->nama,
            'kode_uraian' => $kode_uraian->kode,
            'nama_uraian' => $kode_uraian->nama,
            'kode' => $kode_uraian->kode,
            'pagu' => [
                'total_pa' => $totalPaguAwal,
                'realisasi_pa' => $totalRealisasiPagu,
                'total_sisa_pa' => (int) $totalSisaPagu,
            ]
        ];
        echo json_encode($data);
    }

    public function cek_jumlah_pengajuan($uraian_id, $jml)
    {
        // perhitungan limit pagu
        $totalPaguAwal = $this->target->getAlokasiPaguUraian($uraian_id, $this->session->userdata('is_perubahan'))->row()->total_pagu_awal ?? 0;
        $totalRealisasiPagu = $this->realisasi->getRealisasiTahunanUraian($uraian_id, ['VERIFIKASI', 'VERIFIKASI_ADMIN', 'SELESAI']);
        $totalSisaPagu = ($totalPaguAwal - $totalRealisasiPagu);

        if (get_only_numbers($jml) > $totalSisaPagu) {
            return false;
        }

        return $totalSisaPagu;
    }

    public function get_penerima_manfaat($token)
    {
        $db = $this->spj->make_datatables_penerima_manfaat($token);
        $data = array();
        $no = @$_POST['start'];

        foreach ($db as $r) {

            $no++;
            $row = array();
            $row['no'] = $no;
            $row['organisasi'] = $r->organisasi;
            $row['perorangan'] = $r->perorangan;
            if($r->is_status === 'ENTRI') {
                $row['action'] = '<button class="btn btn-sm btn-danger" onclick="HapusPenerimaManfaat(' . $r->id . ')"><i class="fa fa-trash"></i></button>';
            } else {
                $row['action'] = '<span class="badge badge-secondary p-2">Diverifikasi</span>';
            }
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->spj->make_count_all_penerima_manfaat($token),
            "recordsFiltered" => $this->spj->make_count_filtered_penerima_manfaat($token),
            "data" => $data,
        );
        //output to json format
        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function tambah_penerima_manfaat()
    {
        $input = $this->input->post();

        $this->db->trans_start(); // mulai transaction

        $data = [
            'token'       => $input['token'],
            'organisasi'  => $input['organisasi'],
            'perorangan'  => $input['perorangan'],
            'tahun'       => $this->session->userdata('tahun_anggaran'),
            'created_at'  => DateTimeInput(),
            'created_by'  => $this->session->userdata('user_name'),
        ];

        $this->crud->insert('spj_relasi_publik', $data);

        $this->db->trans_complete(); // selesaikan transaction

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            $msg = [
                'pesan' => 'Penerima Manfaat Gagal Ditambahkan',
                'status'  => false
            ];

        } else {

            $this->db->trans_commit();

            $msg = [
                'pesan' => 'Penerima Manfaat Berhasil Ditambahkan',
                'status' => true
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($msg);

    }

    public function hapus_penerima_manfaat()
    {
        $input = $this->input->post();
        $id = $input['id'];

        $this->db->trans_start(); // mulai transaction
        $this->crud->deleteWhere('spj_relasi_publik', ['id' => $id]);
        $this->db->trans_complete(); // selesaikan transaction
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = [
                'pesan' => 'Penerima Manfaat Gagal Dihapus',
                'status'  => false
            ];
        } else {
            $this->db->trans_commit();
            $msg = [
                'pesan' => 'Penerima Manfaat Berhasil Dihapus',
                'status' => true
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($msg);
    }

    public function cek_angkas($uraian_id, $periode_id = null)
    {
        $jml = $this->input->get('jumlah');

        // perhitungan sisa pagu
        $sisa_pa = $this->cek_jumlah_pengajuan($uraian_id, $jml);

        // ambil limit pagu
        $totalLimit = $this->spj->getLimitPagu($uraian_id, $periode_id)->row();

        // handle jika data kosong
        $totalLimitTotal   = isset($totalLimit->total) ? (int)$totalLimit->total : 0;
        $totalLimitPeriode = isset($totalLimit->periode) ? explode(",", $totalLimit->periode) : null;

        // ambil realisasi
        $totalRealisasiPaguByPeriode = $this->realisasi->getRealisasiByPeriode(
            $uraian_id,
            ['VERIFIKASI', 'VERIFIKASI_ADMIN', 'SELESAI'],
            $totalLimitPeriode
        );

        // hitung sisa limit
        $sisa_limit = $totalLimitTotal - (int)$totalRealisasiPaguByPeriode;

        // pastikan jml dalam bentuk angka murni
        $jml_clean = (int)get_only_numbers($jml);

        // validasi
        if ($jml_clean > $sisa_limit || $sisa_pa === false) {
            echo json_encode([
                'jml'     => $jml_clean,
                'sisa'    => $sisa_limit,
                'sisa_pa' => $sisa_pa,
                'status'    => false
            ]);
            return @$this->output->set_status_header(400);
        }

        // jika valid
        echo json_encode([
            'jml'     => @$jml_clean,
            'sisa'    => $sisa_limit,
            'sisa_pa' => $sisa_pa,
            'status'    => true
        ]);
        return @$this->output->set_status_header(200);
    }

    public function prosesusul()
    {
        $input = $this->input->post();

        // Limit Pagu
        $search_periode = $input['periode'];
        $totalLimit = $this->spj->getLimitPagu($input['ref_uraian'], $search_periode)->row();
        $totalRealisasiPaguByPeriode = $this->realisasi->getRealisasiByPeriode($input['ref_uraian'], ['VERIFIKASI', 'VERIFIKASI_ADMIN', 'SELESAI'], explode(",", @$totalLimit->periode));
        $totalSisaLimit = (@$totalLimit->total - $totalRealisasiPaguByPeriode);

        if (get_only_numbers($input['jumlah']) > $totalSisaLimit) {
            $status = [
                'msg' => 'Gagal, SPJ melebihi batas anggaran KAS : ' . nominal(@$totalLimit->total),
                'code' => 400,
                'data' => [
                    'total_limit' => $totalLimit,
                    'total_realisasi' => $totalRealisasiPaguByPeriode,
                    'total_sisa_limit' => $totalSisaLimit
                ]
            ];
            echo json_encode($status);
            return false;
            die();
        }

        if (!empty($input['token'])) {
            $data = [
                'fid_periode' => $input['periode'],
                'fid_part' => $input['ref_part'],
                'fid_program' => $input['ref_program'],
                'fid_kegiatan' => $input['ref_kegiatan'],
                'fid_sub_kegiatan' => $input['ref_subkegiatan'],
                'fid_uraian' => $input['ref_uraian'],
                'koderek' => $input['koderek'],
                'bulan' => date("m"),
                'tahun' => $input['tahun'],
                'uraian' => $input['uraian'],
                'jumlah' => get_only_numbers($input['jumlah']),
                'entri_perbaikan_at' => DateTimeInput(),
                'entri_perbaikan_by' => $this->session->userdata('user_name'),
            ];
            $db = $this->crud->update('spj', $data, ['token' => $input['token']]);
            $isToken = $input['token'];
            // insert log
            $info = [
                'token' => $input['token'],
                'status' => 'ENTRI',
                'keterangan' => 'USULAN (DIPERBAIKI)',
                'created_at' => DateTimeInput(),
                'created_by' => $this->session->userdata('user_name'),
                'tahun' => $this->session->userdata('tahun_anggaran'),
            ];
        } else {
            $data = [
                'token' => generateRandomString(18),
                'fid_periode' => $input['periode'],
                'fid_part' => $input['ref_part'],
                'fid_program' => $input['ref_program'],
                'fid_kegiatan' => $input['ref_kegiatan'],
                'fid_sub_kegiatan' => $input['ref_subkegiatan'],
                'fid_uraian' => $input['ref_uraian'],
                'is_perubahan' => $this->session->userdata('is_perubahan'),
                'koderek' => $input['koderek'],
                'bulan' => date("m"),
                'tahun' => $input['tahun'],
                'uraian' => $input['uraian'],
                'jumlah' => get_only_numbers($input['jumlah']),
                'entri_at' => DateTimeInput(),
                'entri_by' => $this->session->userdata('user_name'),
                'entri_by_part' => $this->session->userdata('part')
            ];
            $db = $this->crud->insert('spj', $data);
            $isToken = $data['token'];
            // insert log
            $info = [
                'token' => $data['token'],
                'status' => 'ENTRI',
                'keterangan' => 'USULAN (BARU)',
                'created_at' => DateTimeInput(),
                'created_by' => $this->session->userdata('user_name'),
                'tahun' => $this->session->userdata('tahun_anggaran'),
            ];
        }

        $log = $this->historis->insert($info);
        if ($db && $log) {
            $status = [
                'msg' => 'Oke, berhasil disimpan',
                'code' => 200,
                'redirect' => base_url('app/spj/buatusul?step=1&status=entri&token=' . $isToken),
                'data' => [
                    'total_limit' => $totalLimit,
                    'total_realisasi' => $totalRealisasiPaguByPeriode,
                    'total_sisa_limit' => $totalSisaLimit
                ]
            ];
        } else {
            $status = ['msg' => 'Gagal', 'code' => 400];
        }
        echo json_encode($status);
    }

    public function proseseviden()
    {
        $input = $this->input->post();
        $whr = [
            'token' => $input['token']
        ];
        $data = [
            'berkas_link' => $input['link'],
        ];
        $db = $this->crud->update('spj', $data, $whr);

        if ($db) {
            $status = ['msg' => 'Prosess, berhasil disimpan', 'code' => 200, 'redirect' => base_url('app/spj/buatusul?step=3&status=review&token=' . $input['token'])];
        } else {
            $status = ['msg' => 'Gagal', 'code' => 400];
        }
        echo json_encode($status);
    }

    public function hapususulan($token)
    {

        $db = $this->crud->deleteWhere('spj', ['token' => $token]);
        $deleteHistori = $this->crud->deleteWhere('historis_spj', ['token' => $token]);
        if ($db && $deleteHistori) {
            $msg = 200;
        } else {
            $msg = 400;
        }
        echo json_encode($msg);
    }

    public function rollback()
    {
        $token = $this->input->post('token');
        $db = $this->crud->update('spj', ['is_status' => 'VERIFIKASI'], ['token' => $token]);

        $info = [
            'token' => $token,
            'status' => 'ROLLBACK',
            'keterangan' => 'USULAN DI ROLLBACK - VERIFIKASI',
            'created_at' => DateTimeInput(),
            'created_by' => $this->session->userdata('user_name'),
            'tahun' => $this->session->userdata('tahun_anggaran'),
        ];

        $log = $this->historis->insert($info);
        if ($db && $log) {
            $msg = ['pesan' => 'Oke', 'code' => 200];
            $this->crud->deleteWhere('spj_riwayat', ['token' => $token]);
        } else {
            $msg = ['pesan' => 'Gagal', 'code' => 400];
        }
        echo json_encode($msg);
    }

    public function monitor()
    {
        $tahun_anggaran = $this->session->userdata('tahun_anggaran');
        $is_perubahan = $this->session->userdata('is_perubahan');
        $part = $this->session->userdata('part');

        if(in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
            $listpart = $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN'])->result();
        } else {
            $listpart = $this->crud->getWhere('ref_parts', ['id' => $part])->result();
        }

        if(in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
            $listprogram = $this->target->program(null, null, $tahun_anggaran);
        } else {
            $listprogram = $this->target->program(null, $part, $tahun_anggaran);
        }

        if(in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
            $listkegiatan = $this->db->order_by('kode', 'asc')
                                ->where('tahun', $tahun_anggaran)
                                ->get('ref_kegiatans');
        } else {
            $listkegiatan = $this->db->order_by('kode', 'asc')
                                ->where('fid_part', $part)
                                ->where('tahun', $tahun_anggaran)
                                ->get('ref_kegiatans');
        }

        if(in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
            $listsubkegiatan = $this->db->select('sk.id, sk.kode, sk.nama')
                                    ->order_by('sk.kode', 'asc')
                                    ->join('ref_kegiatans as k', 'sk.fid_kegiatan = k.id')
                                    ->where('sk.tahun', $tahun_anggaran)
                                    ->get('ref_sub_kegiatans as sk');
        } else {
            $listsubkegiatan = $this->db->select('sk.id, sk.kode, sk.nama')
                                    ->order_by('sk.kode', 'asc')
                                    ->join('ref_kegiatans as k', 'sk.fid_kegiatan = k.id')
                                    ->where('k.fid_part', $part)
                                    ->where('sk.tahun', $tahun_anggaran)
                                    ->get('ref_sub_kegiatans as sk');
        }

        $data = [
            'title' => 'Monitor SPJ (Surat Pertanggung Jawaban)',
            'content' => 'pages/spj/monitor',
            'tahun_anggaran' => $tahun_anggaran,
            'is_perubahan' => $is_perubahan,
            'part' => $part,
            'listpart' => $listpart,
            'programs' => $listprogram,
            'kegiatans' => $listkegiatan,
            'sub_kegiatans' => $listsubkegiatan,
            'autoload_js' => [
                'template/backend/vendors/moment/min/moment.min.js',
                'template/backend/vendors/bootstrap-daterangepicker/daterangepicker.js',
                'template/custom-js/spj_monitor.js',
            ],
            'autoload_css' => [
                'template/backend/vendors/bootstrap-daterangepicker/daterangepicker.css',
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function autocomplete($type)
    {
        $query = $this->input->get('query');
        $suggestions = [];

        if ($type === 'organisasi') {
            $results = $this->db->select('organisasi')->like('organisasi', $query)->from('spj_relasi_publik')->group_by('organisasi')->get()->result();
            foreach ($results as $row) {
                $suggestions[] = [
                    'value' => $row->organisasi,
                    'data' => $row->organisasi
                ];
            }
        }

        if ($type === 'perorangan') {
            $results = $this->db->select('perorangan')->like('perorangan', $query)->from('spj_relasi_publik')->group_by('perorangan')->get()->result();
            foreach ($results as $row) {
                $suggestions[] = [
                    'value' => $row->perorangan,
                    'data' => $row->perorangan
                ];
            }
        }

        echo json_encode(['suggestions' => $suggestions]);
    }

    public function rekap_perjadin()
    {
        $data = [
            'title' => 'Rekap Perjadin SPJ (Surat Pertanggung Jawaban)',
            'content' => 'pages/spj/rekap_perjadin',
            'tahun_anggaran' => $this->session->userdata('tahun_anggaran'),
            'is_perubahan' => $this->session->userdata('is_perubahan'),
            'list_bidang' => $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN'])->result(),
            'autoload_js' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/blockUI/jquery.blockUI.js',
                'template/custom-js/tabel-perjadin.js',
            ],
            'autoload_css' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.css',
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function get_rekap_perjadin()
    {
        $filter = $this->input->get();
        $db = $this->spj->make_datatables_rekap_perjadin($filter);
        $data = array();
        $no = @$_POST['start'];

        foreach ($db as $r) {

            $filename = $r->file_path;
            $server_path = FCPATH . 'template/upload/dokumen_perjadin/' . $filename;
            $public_url  = base_url('template/upload/dokumen_perjadin/' . $filename);
            $file_exists = file_exists($server_path) && is_file($server_path);
            
            if ($file_exists):
                $unduh = '<a href="' . $public_url . '" target="_blank" rel="noopener" class="btn btn-success" title="Unduh dokumen PK">
                    <i class="fa fa-download mr-1"></i> Unduh
                </a>';
            else:
                $unduh = '<button class="btn btn-outline-secondary" disabled title="File belum tersedia">
                    <i class="fa fa-download mr-1"></i> Unduh
                </button>';
            endif;

            if(in_array($this->session->userdata('role'), ['ADMIN', 'VERIFICATOR'])):
                if($r->is_kunci == 0):
                $verifikasi = '<button type="button" class="btn btn-primary" onclick="VerifikasiDokumen(' . $r->id . ')" title="Verifikasi Dokumen">
                    <i class="fa fa-check mr-1"></i> Verifikasi
                </button>';
                else:
                    $verifikasi = '<button type="button" class="btn btn-danger" title="Unverifikasi Dokumen" onclick="VerifikasiDokumen(' . $r->id . ')">
                        <i class="fa fa-times mr-1"></i> Unverifikasi
                    </button>';
                endif;
                $btnCatatan = '
                    <button type="button" class="btn btn-info" title="Catatan Dokumen" onclick="CatatanDokumen(' . $r->id . ',\''.$r->catatan.'\')">
                        <i class="fa fa-edit mr-1"></i> Tambakan Catatan
                    </button>
                ';
            else:
                $verifikasi = '';
                $btnCatatan = '';
            endif;

            if(in_array($this->session->userdata('role'), ['USER']) && $r->is_kunci == 0 && $r->created_by == $this->session->userdata('user_name')):
                $btnDelete = '
                    <button type="button" class="btn btn-danger" title="Hapus Dokumen" onclick="HapusDokumen(' . $r->id . ')">
                        <i class="fa fa-trash mr-1"></i> Hapus
                    </button>
                    ';
            else:
                $btnDelete = '';
            endif;


            $btnAksi = '
            <!-- Download / Status -->
            <div class="d-flex align-items-center" style="gap:.5rem; white-space:nowrap;">
                ' . $unduh . '
                ' . $verifikasi . '
                ' . $btnCatatan . '
                ' . $btnDelete . '
            </div>
            ';

            $terverifikasi = $r->is_kunci == 1 ? '<span class="badge badge-success p-2"><i class="fa fa-check-circle mr-1"></i> Terverifikasi</span>' : '<span class="badge badge-warning p-2"><i class="fa fa-exclamation-circle mr-1"></i> Belum Diverifikasi</span>';

            $no++;
            $row = array();
            $row['no'] = $no;
            $row['bidang'] = $r->nama_part;
            $row['bulan'] = bulan($r->bulan) .' <br/> '. $terverifikasi;
            $row['tahun'] = $r->tahun;
            $row['user'] = $r->created_by;
            $row['time'] = time_ago_id($r->created_at, [
                'show_time' => true,
            ]);
            $row['catatan'] = $r->catatan ?? '-';
            $row['action'] = $btnAksi;
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->spj->make_count_all_rekap_perjadin($filter),
            "recordsFiltered" => $this->spj->make_count_filtered_rekap_perjadin($filter),
            "data" => $data,
        );
        //output to json format
        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function delete_dokumen_perjadin()
    {
        $id = $this->input->post('id');
        $dok = $this->crud->getWhere('t_dokumen_perjadin', ['id' => $id])->row();

        if (!$dok) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        $filePath = FCPATH . 'template/upload/dokumen_perjadin/' . $dok->file_path;

        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
        }

        $db = $this->crud->deleteWhere('t_dokumen_perjadin', ['id' => $id]);

        if ($db) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Dokumen berhasil dihapus', 'status' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Gagal menghapus dokumen', 'status' => false]);
        }
    }

    public function upload_rekap_perjadin()
    {
        $getFileName = $_FILES['file']['name'];
        $bulan = $this->input->post('bulan');
        $tahun = $this->session->userdata('tahun_anggaran');
        $part_id = $this->session->userdata('part');
        $namapart = $this->crud->getWhere('ref_parts', ['id' => $part_id])->row()->singkatan;
        $namafile = 'Rekapitulasi Perjalanan Dinas-' . $namapart . '-' . $bulan . '-' . $tahun.'-'. generateRandomString().'-'.$this->session->userdata('user_name');

        // Cek apakah sudah ada file untuk part, bulan, tahun dan created_by yg sama
		// $existing = $this->crud->getWhere('t_dokumen_perjadin', ['fid_part' => $part_id, 'bulan' => $bulan, 'tahun' => $tahun, 'created_by' => $this->session->userdata('user_name')]);
        // jika sudah ada, hapus file lama dari server
        // if ($existing->num_rows() > 0) {
        //     $oldFile = $existing->row()->file_path;
        //     $oldFilePath = FCPATH . 'template/upload/dokumen_perjadin/' . $oldFile;
        //     if (file_exists($oldFilePath) && is_file($oldFilePath)) {
        //         unlink($oldFilePath);
        //     }
        // }
        

        // validasi form dan upload file ke folder /template/upload/dokumen_perjadin/
		$config = [
			'upload_path'   => './template/upload/dokumen_perjadin/',
			'allowed_types' => 'pdf|xls|xlsx',
			'max_size'      => 2120, // 2MB
			'file_name'     => $namafile,
			'overwrite'     => true
		];

        $this->load->library('upload', $config);

		if (!$this->upload->do_upload('file')) {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_msg', $this->upload->display_errors());
			return redirect(base_url('app/spj/rekap_perjadin'));
		}

        $upload_data = $this->upload->data();

		$data = [
            'bulan' => $bulan,
			'fid_part' => $part_id,
            'nama_dokumen_ori' => $getFileName,
			'nama_dokumen' => $namafile,
			'file_path' => $upload_data['file_name'],
			'ukuran_file' => $upload_data['file_size'],
			'tipe_file' => $upload_data['file_type'],
			'tahun' => $tahun,
			'created_by' => $this->session->userdata('user_name'),
			'created_at' => DateTimeInput()
		];

        // jika sudah ada record, lakukan update; jika belum, insert baru
		// if ($existing->num_rows() > 0) {
		// 	$db = $this->crud->update('t_dokumen_perjadin', $data, ['fid_part' => $part_id, 'tahun' => $tahun, 'bulan' => $bulan, 'created_by' => $this->session->userdata('user_name')]);
        //     if($db) {
        //         $this->session->set_flashdata('alert_type', 'success');
        //         $this->session->set_flashdata('alert_msg', 'Rekap Perjadin berhasil diperbarui');
        //     } else {
        //         $this->session->set_flashdata('alert_type', 'error');
        //         $this->session->set_flashdata('alert_msg', 'Gagal memperbarui Rekap Perjadin');
        //     }
        //     return redirect(base_url('app/spj/rekap_perjadin'));
		// }
        

        $db = $this->crud->insert('t_dokumen_perjadin', $data);
        if($db) {
            $this->session->set_flashdata('alert_type', 'success');
            $this->session->set_flashdata('alert_msg', 'Rekap Perjadin berhasil diunggah');
        } else {
            $this->session->set_flashdata('alert_type', 'error');
            $this->session->set_flashdata('alert_msg', 'Gagal mengunggah Rekap Perjadin');
        }
        return redirect(base_url('app/spj/rekap_perjadin'));
    }

    public function verifikasi_dokumen_perjadin()
    {
        $id = $this->input->post('id');

        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'ID dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        // ambil record saat ini
        $dok = $this->crud->getWhere('t_dokumen_perjadin', ['id' => $id])->row();
        if (!$dok) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        // toggle is_kunci: jika 1 jadi 0, jika 0 jadi 1
        $current = (int) $dok->is_kunci;
        $new_value = $current === 1 ? 0 : 1;

        $status_text = $new_value === 1 ? 'Terverifikasi' : 'Belum Terverifikasi';

        // transaction
        $this->db->trans_begin();
        $this->crud->update('t_dokumen_perjadin', ['is_kunci' => $new_value, 'catatan' => ''], ['id' => $id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = ['pesan' => 'Gagal memverifikasi dokumen', 'status' => false];
        } else {
            $this->db->trans_commit();
            $msg = ['pesan' => 'Berhasil memperbarui status verifikasi - '. $status_text, 'status' => true, 'is_kunci' => $new_value];
        }

        header('Content-Type: application/json');
        echo json_encode($msg);
    }

    public function tambah_catatan_perjadin()
    {
        $id = $this->input->post('id');
        $catatan = $this->input->post('catatan');

        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'ID dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        // transaction
        $this->db->trans_begin();
        $this->crud->update('t_dokumen_perjadin', ['catatan' => $catatan], ['id' => $id]); 
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Gagal menambahkan catatan', 'status' => false]);
        } else {
            $this->db->trans_commit();
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Catatan berhasil ditambahkan', 'status' => true]);
        }
    }

    public function rekap_pajak()
    {
        $data = [
            'title' => 'Rekap Pajak Pusat & Daerah',
            'content' => 'pages/spj/rekap_pajak',
            'tahun_anggaran' => $this->session->userdata('tahun_anggaran'),
            'is_perubahan' => $this->session->userdata('is_perubahan'),
            'list_bidang' => $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN'])->result(),
            'autoload_js' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/tabel-pajak.js',
            ],
            'autoload_css' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.css',
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function get_rekap_pajak()
    {
        $db = $this->spj->make_datatables_rekap_pajak();
        $data = array();
        $no = @$_POST['start'];

        foreach ($db as $r) {

            $filename = $r->file_path;
            $server_path = FCPATH . 'template/upload/dokumen_pajak/' . $filename;
            $public_url  = base_url('template/upload/dokumen_pajak/' . $filename);
            $file_exists = file_exists($server_path) && is_file($server_path);
            
            if ($file_exists):
                $unduh = '<a href="' . $public_url . '" target="_blank" rel="noopener" class="btn btn-success" title="Unduh dokumen PK">
                    <i class="fa fa-download mr-1"></i> Unduh
                </a>';
            else:
                $unduh = '<button class="btn btn-outline-secondary" disabled title="File belum tersedia">
                    <i class="fa fa-download mr-1"></i> Unduh
                </button>';
            endif;

            if(in_array($this->session->userdata('role'), ['ADMIN', 'VERIFICATOR'])):
                if($r->is_kunci == 0):
                $verifikasi = '<button type="button" class="btn btn-primary" onclick="VerifikasiDokumen(' . $r->id . ')" title="Verifikasi Dokumen">
                    <i class="fa fa-check mr-1"></i> Verifikasi
                </button>';
                else:
                    $verifikasi = '<button type="button" class="btn btn-danger" title="Unverifikasi Dokumen" onclick="VerifikasiDokumen(' . $r->id . ')">
                        <i class="fa fa-times mr-1"></i> Unverifikasi
                    </button>';
                endif;
                $btnCatatan = '
                    <button type="button" class="btn btn-info" title="Catatan Dokumen" onclick="CatatanDokumen(' . $r->id . ',\''.$r->catatan.'\')">
                        <i class="fa fa-edit mr-1"></i> Tambakan Catatan
                    </button>
                ';
            else:
                $verifikasi = '';
                $btnCatatan = '';
            endif;

            if(in_array($this->session->userdata('role'), ['USER']) && $r->is_kunci == 0 && $r->created_by == $this->session->userdata('user_name')):
                $btnDelete = '
                    <button type="button" class="btn btn-danger" title="Hapus Dokumen" onclick="HapusDokumen(' . $r->id . ')">
                        <i class="fa fa-trash mr-1"></i> Hapus
                    </button>
                    ';
            else:
                $btnDelete = '';
            endif;


            $btnAksi = '
            <!-- Download / Status -->
            <div class="d-flex align-items-center" style="gap:.5rem; white-space:nowrap;">
                ' . $unduh . '
                ' . $verifikasi . '
                ' . $btnCatatan . '
                ' . $btnDelete . '
            </div>
            ';

            $terverifikasi = $r->is_kunci == 1 ? '<span class="badge badge-success p-2"><i class="fa fa-check-circle mr-1"></i> Terverifikasi</span>' : '<span class="badge badge-warning p-2"><i class="fa fa-exclamation-circle mr-1"></i> Belum Diverifikasi</span>';

            $no++;
            $row = array();
            $row['no'] = $no;
            $row['bidang'] = $r->nama_part;
            $row['periode'] = $r->periode .' <br/> '. $terverifikasi;
            $row['jenis_dokumen'] = $r->jenis_dokumen;
            $row['tahun'] = $r->tahun;
            $row['user'] = $r->created_by;
            $row['catatan'] = $r->catatan ?? '-';
            $row['action'] = $btnAksi;
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->spj->make_count_all_rekap_pajak(),
            "recordsFiltered" => $this->spj->make_count_filtered_rekap_pajak(),
            "data" => $data,
        );
        //output to json format
        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function upload_rekap_pajak()
    {
        $getFileName = $_FILES['file']['name'];
        $periode = $this->input->post('periode');
        $tahun = $this->session->userdata('tahun_anggaran');
        $jenis_dokumen = $this->input->post('jenis_dokumen');
        $part_id = $this->session->userdata('part');
        $namapart = $this->crud->getWhere('ref_parts', ['id' => $part_id])->row()->singkatan;
        $namafile = 'Rekapitulasi-' . $namapart .'-'. $jenis_dokumen . '-' . $periode . '-' . $tahun .'-'. generateRandomString();

        // Cek apakah sudah ada file untuk part dan tahun yg sama
		$existing = $this->crud->getWhere('t_dokumen_pajak', ['fid_part' => $part_id, 'jenis_dokumen' => $jenis_dokumen, 'periode' => $periode, 'tahun' => $tahun, 'created_by' => $this->session->userdata('user_name')]);
        // jika sudah ada, hapus file lama dari server
        if ($existing->num_rows() > 0) {
            $oldFile = $existing->row()->file_path;
            $oldFilePath = FCPATH . 'template/upload/dokumen_pajak/' . $oldFile;
            if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
        
        // validasi form dan upload file ke folder /template/upload/dokumen_pajak/
		$config = [
			'upload_path'   => './template/upload/dokumen_pajak/',
			'allowed_types' => 'pdf|xls|xlsx',
			'max_size'      => 2120, // 2MB
			'file_name'     => $namafile,
			'overwrite'     => true
		];

        $this->load->library('upload', $config);

		if (!$this->upload->do_upload('file')) {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_msg', $this->upload->display_errors());
			return redirect(base_url('app/spj/rekap_pajak'));
		}

        $upload_data = $this->upload->data();

		$data = [
            'periode' => $periode,
            'jenis_dokumen' => $jenis_dokumen,
			'fid_part' => $part_id,
            'nama_dokumen_ori' => $getFileName,
			'nama_dokumen' => $namafile,
			'file_path' => $upload_data['file_name'],
			'ukuran_file' => $upload_data['file_size'],
			'tipe_file' => $upload_data['file_type'],
			'tahun' => $tahun,
			'created_by' => $this->session->userdata('user_name'),
			'created_at' => DateTimeInput()
		];

        // jika sudah ada record, lakukan update; jika belum, insert baru
		if ($existing->num_rows() > 0) {
			$db = $this->crud->update('t_dokumen_pajak', $data, ['fid_part' => $part_id, 'tahun' => $tahun, 'periode' => $periode, 'jenis_dokumen' => $jenis_dokumen, 'created_by' => $this->session->userdata('user_name')]);
            if($db) {
                $this->session->set_flashdata('alert_type', 'success');
                $this->session->set_flashdata('alert_msg', 'Rekap Pajak berhasil diperbarui');
            } else {
                $this->session->set_flashdata('alert_type', 'error');
                $this->session->set_flashdata('alert_msg', 'Gagal memperbarui Rekap Pajak');
            }
            return redirect(base_url('app/spj/rekap_pajak'));
		}

        $db = $this->crud->insert('t_dokumen_pajak', $data);
        if($db) {
            $this->session->set_flashdata('alert_type', 'success');
            $this->session->set_flashdata('alert_msg', 'Rekap Pajak berhasil diunggah');
        } else {
            $this->session->set_flashdata('alert_type', 'error');
            $this->session->set_flashdata('alert_msg', 'Gagal mengunggah Rekap Pajak');
        }
        return redirect(base_url('app/spj/rekap_pajak'));
    }

    public function verifikasi_dokumen_pajak()
    {
        $id = $this->input->post('id');

        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'ID dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        // ambil record saat ini
        $dok = $this->crud->getWhere('t_dokumen_pajak', ['id' => $id])->row();
        if (!$dok) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        // toggle is_kunci: jika 1 jadi 0, jika 0 jadi 1
        $current = (int) $dok->is_kunci;
        $new_value = $current === 1 ? 0 : 1;

        $status_text = $new_value === 1 ? 'Terverifikasi' : 'Belum Terverifikasi';

        // transaction
        $this->db->trans_begin();
        $this->crud->update('t_dokumen_pajak', ['is_kunci' => $new_value, 'catatan' => ''], ['id' => $id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = ['pesan' => 'Gagal memverifikasi dokumen', 'status' => false];
        } else {
            $this->db->trans_commit();
            $msg = ['pesan' => 'Berhasil memperbarui status verifikasi - '. $status_text, 'status' => true, 'is_kunci' => $new_value];
        }

        header('Content-Type: application/json');
        echo json_encode($msg);
    }

    public function tambah_catatan_pajak()
    {
        $id = $this->input->post('id');
        $catatan = $this->input->post('catatan');

        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'ID dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        // transaction
        $this->db->trans_begin();
        $this->crud->update('t_dokumen_pajak', ['catatan' => $catatan], ['id' => $id]); 
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Gagal menambahkan catatan', 'status' => false]);
        } else {
            $this->db->trans_commit();
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Catatan berhasil ditambahkan', 'status' => true]);
        }
    }

    public function delete_dokumen_pajak()
    {
        $id = $this->input->post('id');
        $dok = $this->crud->getWhere('t_dokumen_pajak', ['id' => $id])->row();

        if (!$dok) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        $filePath = FCPATH . 'template/upload/dokumen_pajak/' . $dok->file_path;

        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
        }

        $db = $this->crud->deleteWhere('t_dokumen_pajak', ['id' => $id]);

        if ($db) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Dokumen berhasil dihapus', 'status' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Gagal menghapus dokumen', 'status' => false]);
        }
    }

}