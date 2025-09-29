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
        if (!privilages('priv_default')  && !privilages('priv_spj')):
            return show_404();
        endif;
        $this->load->model('ModelSpj', 'spj');
        $this->load->model('ModelTarget', 'target');
        $this->load->model('ModelRealisasi', 'realisasi');
    }

    public function index()
    {
        $data = [
            'title' => 'SPJ (Surat Pertanggung Jawaban)',
            'content' => 'pages/spj/index',
            'autoload_js' => [
                'template/custom-js/list.min.js',
                'template/custom-js/list-state.js',
                'template/custom-js/spj.js',
                'template/backend/vendors/datatables.net/js/jquery.dataTables.min.js',
                'template/backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js',
                'https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js',
                'https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js',
                'template/custom-js/blockUI/jquery.blockUI.js',
                'template/custom-js/tabel-verifikasi.js',
                'template/custom-js/tabel-verifikasi-selesai.js',
            ],
            'autoload_css' => [
                'template/backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
                'template/backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css',
                'https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css',
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
                        <th width="18%">Tanggal Entri <span class="sort" data-sort="entri_at">Sort</span></th>
                        <th>Berkas</th>
                        <th class="text-center" colspan="3">Aksi</th>
                    </tr>
                    </thead>';
        $html .= '<tbody class="list">';
        $no = 1;
        foreach ($db->result() as $r):
            // Catatan
            $catatan = isset($r->catatan) && !empty($r->catatan) && $r->is_status === 'ENTRI' ? '<span class="text-danger"><i class="fa fa-exclamation-triangle mr-2"></i> ' . substr($r->catatan, 0, 60) . '...</span>' : '';
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

            if ($r->is_status === 'VERIFIKASI' || $r->is_status === 'VERIFIKASI_ADMIN') {
                $detail = '<button onclick="window.location.replace(\'' . base_url('app/spj/buatusul?step=0&status=' . $r->is_status . '&token=' . $r->token) . '\')" type="button" class="btn btn-sm btn-success m-0 rounded-0"><i class="fa fa-eye"></i> <br> Detail</button>';
            } elseif ($r->is_status === 'APPROVE' || $r->is_status === 'TMS' || $r->is_status === 'SELESAI_TMS' || $r->is_status === 'BTL' || $r->is_status === 'SELESAI_BTL') {
                $detail = '<button onclick="window.location.replace(\'' . base_url('app/spj/buatusul?step=3&status=' . $r->is_status . '&token=' . $r->token) . '\')" type="button" class="btn btn-sm btn-success m-0 rounded-0"><i class="fa fa-eye"></i> <br> Detail</button>';
            } else {
                $detail = '
                        <td class="text-center">
                            <button onclick="window.location.replace(\'' . base_url('app/spj/buatusul?step=0&status=entri&token=' . $r->token) . '\')" type="button" class="btn btn-sm btn-primary m-0 rounded-0"><i class="fa fa-pencil"></i> <br> Ubah</button> 
                        </td>
                        <td class="text-center">
                            <button onclick="HapusUsulan(\'' . base_url('app/spj/hapususulan/' . $r->token) . '\')" type="button" class="btn btn-sm btn-danger m-0 rounded-0 pull-right"><i class="fa fa-trash"></i> <br> Hapus</button>
                        </td>
                    ';
            }
            $html .= '<tr>
                <td class="text-center">
                    ' . $no . '
                </td>
                <td class="kode">
                ' .$r->kode_sub_kegiatan . ' <br> ' . $r->kode_uraian . '
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
                    <i class="fa fa-calendar mr-1"></i> ' . longdate_indo(substr($r->entri_at, 0, 10)) . "<br>  <i class='fa fa-clock-o mr-1'></i> " . substr($r->entri_at, 10, 6).
                '</td>
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
            $row['kode'] = '<br>'. $r->kode_uraian;
            $row['uraian'] = $r->nama_sub_kegiatan .'<br> - <b>' . $r->nama_uraian . '</b>';
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
                'nomor_pembukuan' => $input['nomor'],
                'tanggal_pembukuan' => formatToSQL($input['tanggal']),
                'is_status' => 'VERIFIKASI_ADMIN',
                'is_realisasi' => $input['is_realisasi'],
                'catatan' => '',
                'verify_by' => $this->session->userdata('user_name'),
                'verify_at' => DateTimeInput(),
            ];
            $db = $this->crud->update('spj', $update, $whr);
        } elseif ($input['status'] == 'TMS' || $input['status'] == 'BTL') {
            $update = [
                'nomor_pembukuan' => '',
                'tanggal_pembukuan' => '',
                'is_realisasi' => '',
                'catatan' => $input['catatan'],
                'is_status' => $input['status'],
                'verify_by' => $this->session->userdata('user_name'),
                'verify_at' => DateTimeInput(),
            ];
            $db = $this->crud->update('spj', $update, $whr);
        } else {
            $update = [
                'is_status' => $input['status'],
                'catatan_by' => $this->session->userdata('role'),
                'catatan' => $input['catatan'],
            ];
            $db = $this->crud->update('spj', $update, $whr);
        }

        // Kirim Notifikasi WA ke user
        $send = $this->sendNotifyUser($getSpj, $input);

        if ($db && $send['success']) {
            if($input['status'] == 'MS' || $input['status'] == 'TMS' && $this->session->userdata('role') === 'VERIFICATOR'):
                $this->sendNotifyAdmin($getSpj, $input);
            endif;
            $msg = ['pesan' => 'Usulan SPJ Berhasil Di Proses', 'code' => 200, 'redirect' => base_url('app/spj/?tab=%23verifikasi')];
        } else {
            $msg = ['pesan' => 'Usulan SPJ Gagal Di Proses', 'code' => 400];
        }

        echo json_encode($msg);
    }

    private function sendNotifyUser($getSpj, $input)
    {
        // Kirim Notifikasi WA ke user
        $getUser = $this->users->profile_username($getSpj->entri_by)->row();
        $send = sendWaMessage('https://whatsapp.bkpsdm-info.com/message/send-text', 'notify', $getUser->nohp, '
*DIGTA SUNANPRAJA*
📢 *Notifikasi Usulan SPJ*
-------------
📝 Uraian   : ' . $getSpj->uraian . '  
💰 Jumlah   : Rp. ' . nominal($getSpj->jumlah) . ' 
⏰ Spj Bulan : ' . bulan(strtoupper($getSpj->bulan)) . '
📅 Tgl. Entri : ' . longdate_indo(substr($getSpj->entri_at, 0, 10)) . '
-------------
📌 Catatan : ' . (isset($input['catatan']) && !empty($input['catatan']) ? $input['catatan'] : '-') . '
No. BKU : ' . (isset($input['nomor']) && !empty($input['nomor']) ? $input['nomor'] : '-') . '
Tgl. BKU : ' . (isset($input['tanggal']) && !empty($input['tanggal']) ? longdate_indo(formatToSQL($input['tanggal'])) : '-') . '
Realisasi SPJ : ' . (isset($input['is_realisasi']) && !empty($input['is_realisasi']) ? $input['is_realisasi'] : '-') . '
Verifikator : ' . $this->session->userdata('user_name') . '
-------------
Telah diverifikasi dengan status *' . $input['status'] . '*. Selanjutnya akan di proses oleh ADMIN. 
Silahkan cek aplikasi Digta Sunanpraja.  

⚠️ Mohon untuk tidak dibalas, pesan ini dibuat secara otomatis (bot).  
' . longdate_indo(Date('Y-m-d')) . '
_by ' . $this->session->userdata('role') .' (' . $this->session->userdata('user_name') . ')_
        ');

        return $send;
    }

    private function sendNotifyAdmin($getSpj, $input, $user_admin = 'abduh')
    {
        // Kirim Notifikasi WA ke Admin
            $getAdmin = $this->users->profile_username($user_admin)->row();
            $sendAdmin = sendWaMessage('https://whatsapp.bkpsdm-info.com/message/send-text', 'notify', $getAdmin->nohp, '
*DIGTA SUNANPRAJA*
📢 *Notifikasi Usulan SPJ*
-------------
📝 Uraian   : ' . $getSpj->uraian . '  
💰 Jumlah   : Rp. ' . nominal($getSpj->jumlah) . ' 
⏰ Spj Bulan : ' . bulan(strtoupper($getSpj->bulan)) . '
📅 Tgl. Entri : ' . longdate_indo(substr($getSpj->entri_at, 0, 10)) . '
-------------
No. BKU : ' . (isset($input['nomor']) && !empty($input['nomor']) ? $input['nomor'] : '-') . '
Tgl. BKU : ' . (isset($input['tanggal']) && !empty($input['tanggal']) ? longdate_indo(formatToSQL($input['tanggal'])) : '-') . '
Realisasi SPJ : ' . (isset($input['is_realisasi']) && !empty($input['is_realisasi']) ? $input['is_realisasi'] : '-') . '
Verifikator : ' . $this->session->userdata('user_name') . '
Catatan : ' . (isset($input['catatan']) && !empty($input['catatan']) ? $input['catatan'] : '-') . '
-------------
Telah diverifikasi dengan status *' . $input['status'] . '*.  
Silahkan cek aplikasi Digta Sunanpraja.  

⚠️ Mohon untuk tidak dibalas, pesan ini dibuat secara otomatis (bot).  
' . longdate_indo(Date('Y-m-d')) . '
_by ' . $this->session->userdata('role') . ' (' . $this->session->userdata('user_name') . ')_
        ');

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

        $whr = [
            'token' => $detailUsul->token
        ];

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
            'nomor_pembukuan' => @$detailUsul->nomor_pembukuan,
            'bulan' => $detailUsul->bulan,
            'tahun' => $detailUsul->tahun,
            'tanggal_pembukuan' => @$detailUsul->tanggal_pembukuan,
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

        $db = $this->crud->update('spj', $update, $whr);

        $getUser = $this->users->profile_username($detailUsul->entri_by)->row();
        $send = sendWaMessage('https://whatsapp.bkpsdm-info.com/message/send-text', 'notify', $getUser->nohp, '
*DIGTA SUNANPRAJA*
📢 *Notifikasi Usulan SPJ*
-------------
📝 Uraian   : ' . $detailUsul->uraian . '  
💰 Jumlah   : Rp. ' . nominal($detailUsul->jumlah) . ' 
⏰ Spj Bulan : ' . bulan(strtoupper($detailUsul->bulan)) . '
📅 Tgl. Entri : ' . longdate_indo(substr($detailUsul->entri_at, 0, 10)) . '
-------------
Telah difinalisasi *ADMIN* dengan status *' . ($is_status === 'SELESAI' ? 'APPROVE' : $detailUsul->is_status) . '*.  
Silahkan cek aplikasi Digta Sunanpraja.  

⚠️ Mohon untuk tidak dibalas, pesan ini dibuat secara otomatis (bot).  
' . longdate_indo(Date('Y-m-d')) . '
_by ' . $this->session->userdata('role') . ' (' . $this->session->userdata('user_name') . ')_
        ');

        if ($db && $send['success']) {
            $this->crud->insert('spj_riwayat', $insert);
            $msg = ['pesan' => 'Oke', 'code' => 200];
        } else {
            $msg = ['pesan' => 'Gagal', 'code' => 400];
        }
        echo json_encode($msg);
    }

    public function verifikasi_selesai()
    {
        $db = $this->spj->make_datatables_verifikasi_selesai();
        $data = array();
        $no = @$_POST['start'];

        foreach ($db as $r) {

            $userusul = $this->users->profile_username($r->entri_by)->row();
            $status = $this->generate_status($r->is_status);
            $button = $this->generate_button($r);

            $no++;
            $row = array();
            $row['no'] = $no;
            $row['no_buku'] = $r->nomor_pembukuan;
            // $row[] = '<br>' . $r->kode_program . '<br>' . $r->kode_kegiatan . ' <br> ' . $r->kode_sub_kegiatan . ' <br> ' . $r->kode_uraian;
            $row['kode_uraian'] = "<br>" . $r->kode_uraian;
            // $row[] = '<b>' . $r->nama_part . '</b> <br>' . $r->nama_program . ' <br/>  ' . strtoupper($r->nama_kegiatan) . ' <br>  ' . $r->nama_sub_kegiatan . ' <br> <b>' . $r->nama_uraian . '</b>';
            $row['nama_uraian'] = $r->nama_sub_kegiatan.'<br> - <b>' . $r->nama_uraian . '</b>';
            $row['bidang'] = $r->nama_part;
            $row['periode'] = bulan($r->periode_id);
            $row['userinfo'] = '<i class="fa fa-calendar"></i> ' .longdate_indo(substr($r->entri_at, 0, 10)) . "<br>  <i class='fa fa-clock-o'></i> " . substr($r->entri_at, 10, 6) . " <i class='fa fa-user'></i> " . $userusul->nama;
            $row['tgl_approve'] = '<i class="fa fa-calendar"></i> '.longdate_indo(substr($r->approve_at, 0, 10)). ' <br> <i class="fa fa-clock-o"></i>'. substr($r->approve_at, 10, 6);
            $row['status'] = $status;
            $row['jumlah'] = $r->is_status === 'APPROVE' ? "<b class='text-success'> Rp. " . nominal((int) $r->jumlah) . "</b>" : "<b class='text-danger'> Rp. " . nominal((int) $r->jumlah) . "</b>";
            $row['action'] = $button;
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->spj->make_count_all_verifikasi_selesai(),
            "recordsFiltered" => $this->spj->make_count_filtered_verifikasi_selesai(),
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

    private function generate_button($r)
    {
        if($this->session->userdata('role') === 'VERIFICATOR'):
        $btn_rollback = '<a class="dropdown-item d-flex justify-content-between" href="#" onclick="Rollback(\'' . $r->token . '\')">
                                Rollback <i class="fa fa-repeat text-danger"></i></a>';
        else:
        $btn_rollback = '';
        endif;
        return '<div class="dropdown">
                            <button class="btn btn-sm btn-icon-only text-dark bg-white rounded" type="button" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              action <i class="fa fa-ellipsis-v ml-3"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                              <a class="dropdown-item d-flex justify-content-between" href="' . base_url('app/spj/verifikasi_selesai_detail/' . $r->token) . '">
                                Detail <i class="fa fa-eye"></i>
                              </a>
                              <a class="dropdown-item d-flex justify-content-between" href="' . $r->berkas_link . '" target="_blank">
                                Berkas <i class="fa fa-link"></i>
                              </a>
                              '.$btn_rollback.'
                            </div>
                        </div>';
    }

    public function verifikasi_selesai_detail($token)
    {
        $data = [
            'title' => 'Verifikasi Selesai',
            'content' => 'pages/spj/verifikasi_selesai_detail',
            'detail' => $this->spj->riwayat(['token' => $token])->row(),
        ];
        $this->load->view('layout/app', $data);
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
                'template/backend/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js',
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/blockUI/jquery.blockUI.js',
                'template/custom-js/spj_usul.js',
                'template/custom-js/rupiah.js',
            ],
            'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css'
            ]
        ];
        $this->load->view('layout/app', $data);
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
            'kode' => $kode_kegiatan->kode . "." . $kode_subkegiatan->kode . "." . $kode_uraian->kode,
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

    public function cek_angkas($uraian_id, $periode_id)
    {
        $jml = $this->input->post('jumlah');

        // perhitungan sisa pagu
        $sisa_pa = $this->cek_jumlah_pengajuan($uraian_id, $jml);

        // perhitungan limit pagu
        $totalLimit = $this->spj->getLimitPagu($uraian_id, $periode_id)->row();
        $totalRealisasiPaguByPeriode = $this->realisasi->getRealisasiByPeriode($uraian_id, ['VERIFIKASI', 'VERIFIKASI_ADMIN', 'SELESAI'], explode(",", @$totalLimit->periode));
        $sisa_limit = (@$totalLimit->total - $totalRealisasiPaguByPeriode);

        if ((get_only_numbers($jml) > $sisa_limit) || $sisa_pa === false) {
            echo json_encode(['jml' => get_only_numbers($jml), 'sisa' => $sisa_limit, 'sisa_pa' => $sisa_pa, 'code' => 400]);
            return $this->output->set_status_header('400');
        }

        echo json_encode(['jml' => get_only_numbers($jml), 'sisa' => $sisa_limit, 'sisa_pa' => $sisa_pa, 'code' => 200]);
        return $this->output->set_status_header('200');
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
                'jumlah' => get_only_numbers($input['jumlah'])
            ];
            $db = $this->crud->update('spj', $data, ['token' => $input['token']]);
            $isToken = $input['token'];
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
        }

        if ($db) {
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
            'is_status' => 'VERIFIKASI'
        ];
        $db = $this->crud->update('spj', $data, $whr);

        // notif wa
        $user = $this->crud->getWhere('spj', ['token' => $input['token']])->row();
        $getUser = $this->users->profile_username($user->entri_by)->row();
        $send = sendWaMessage('https://whatsapp.bkpsdm-info.com/message/send-text', 'notify', $getUser->nohp, '
*DIGTA SUNANPRAJA*
📢 *Notifikasi Usulan SPJ*
Halo ' . $getUser->nama . ', usulan SPJ anda telah dikirim selanjutnya akan di verifikasi.
-------------
📝 Uraian   : ' . $user->uraian . '
💰 Jumlah   : Rp. ' . nominal($user->jumlah) . '
⏰ Spj Bulan : ' . bulan(strtoupper($user->bulan)) . '
📌 Status   : '.$user->is_status.'
➡️ Link Berkas : ' . $user->berkas_link . '
-------------
⚠️ Mohon untuk tidak dibalas, pesan ini dibuat secara otomatis (bot).  
' . longdate_indo(Date('Y-m-d')) . '
        ');

        if ($db && $send['success']) {
            $status = ['msg' => 'Oke, berhasil dikirim', 'code' => 200, 'redirect' => base_url('app/spj/buatusul?step=2&status=verifikasi&token=' . $input['token'])];
        } else {
            $status = ['msg' => 'Gagal', 'code' => 400];
        }
        echo json_encode($status);
    }

    public function hapususulan($token)
    {

        $db = $this->crud->deleteWhere('spj', ['token' => $token]);
        if ($db) {
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
        
        if ($db) {
            $msg = ['pesan' => 'Oke', 'code' => 200];
            $this->crud->deleteWhere('spj_riwayat', ['token' => $token]);
        } else {
            $msg = ['pesan' => 'Gagal', 'code' => 400];
        }
        echo json_encode($msg);
    }
}
