<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spj extends CI_Controller {

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
        if(!privilages('priv_default')):
            return show_404();
        endif;
        $this->load->model('modelspj', 'spj');
        
    }
	
	public function index()
	{
        $data = [
			'title' => 'SPJ (Surat Pertanggung Jawaban)',
            'content' => 'pages/spj/index',
            'autoload_js' => [
                'template/custom-js/list.min.js',
                'template/custom-js/spj.js',
                'template/backend/vendors/datatables.net/js/jquery.dataTables.min.js',
				'template/backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'template/backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js',
                'template/custom-js/tabel-verifikasi.js',
                'template/custom-js/tabel-verifikasi-selesai.js',
            ],
            'autoload_css' => [
				'template/backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'template/backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css',
			]
        ];
		$this->load->view('layout/app', $data);
	}

    public function inbox() {
        $db = $this->spj->inbox();
        
        $btnAdd = '<div>
        <button class="btn btn-primary rounded-0" onclick="window.location.href=\''.base_url("app/spj/buatusul").'\'"><i class="fa fa-plus mr-2"></i> Buat Usul SPJ</button>
                </div>
                ';
        $search = '<div class="col-5 col-md-3">Pencarian <input type="text" class="search form-control" /></div>';
        $pagging = '<div class="col-4 col-md-6">Halaman <ul class="pagination"></ul></div>';

        $html = '<div id="spjList"><div class="row mb-3">'.$search.$pagging.$btnAdd."</div>";
        $html .= '<table class="table table-condensed table-hover table-responsive">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>Kode</th>
                        <th>Bidang/Program/Kegiatan/Sub Kegiatan</th>
                        <th>Jumlah (Rp)</th>
                        <th>Status</th>
                        <th>Eviden</th>
                        <th></th></tr>
                    </thead>';
        $html .= '<tbody class="list">';
        $no=1;
        foreach($db->result() as $r):
            if($r->is_status === 'ENTRI') {
                $status = '<span class="badge p-2 badge-secondary"><i class="fa fa-edit mr-2"></i> ENTRI</span>';
            } elseif($r->is_status === 'VERIFIKASI' || $r->is_status === 'VERIFIKASI_ADMIN') {
                $status = '<span class="badge p-2 badge-primary"><i class="fa fa-lock mr-2"></i> VERIFIKASI</span>';
            } elseif($r->is_status === 'APPROVE') {
                $status = '<span class="badge p-2 badge-success"><i class="fa fa-check-circle mr-2"></i> APPROVE</span>';
            } elseif($r->is_status === 'BTL') {
                $status = '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> BTL</span>';
            } else {
                $status = '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> TMS</span>';
            }

            if(isset($r->berkas_link)) {
                $link = '<a href="'.$r->berkas_link.'" target="_blank" class="btn btn-sm btn-warning rounded-0"><i class="fa fa-link"></i> <br> Berkas</a>';
            } else {
                $link = '-';
            }

            if($r->is_status === 'VERIFIKASI' || $r->is_status === 'VERIFIKASI_ADMIN'){
                $detail = '<button onclick="window.location.replace(\''.base_url('app/spj/buatusul?step=0&status='.$r->is_status.'&token='.$r->token).'\')" type="button" class="btn btn-sm btn-success m-0 rounded-0"><i class="fa fa-eye"></i> <br> Detail</button>';
            } elseif($r->is_status === 'APPROVE' || $r->is_status === 'TMS' || $r->is_status === 'BTL') {
                $detail = '<button onclick="window.location.replace(\''.base_url('app/spj/buatusul?step=3&status='.$r->is_status.'&token='.$r->token).'\')" type="button" class="btn btn-sm btn-success m-0 rounded-0"><i class="fa fa-eye"></i> <br> Detail</button>';
            } else {
                $detail = '<button onclick="window.location.replace(\''.base_url('app/spj/buatusul?step=0&status=entri&token='.$r->token).'\')" type="button" class="btn btn-sm btn-primary m-0 rounded-0"><i class="fa fa-pencil"></i> <br> Ubah</button>';
            }
            $html .= '<tr>
                <td class="text-center">
                    '.$no.'
                </td>
                <td class="kode">
                    <br> '.$r->kode_kegiatan.' <br> '.$r->kode_sub_kegiatan.'
                </td>
                <td class="nama">
                <b>'.$r->nama_part.'</b> <br>  '.$r->nama_program.' <br/>  '.strtoupper($r->nama_kegiatan).' <br>  '.$r->nama_sub_kegiatan.'
                </td>
                <td>
                    <b>'.nominal($r->jumlah).'</b>
                </td>
                <td>
                    '.$status.'
                </td>
                <td width="5%" class="text-center">
                    '.$link.'
                </td>
                <td width="5%" class="text-center">
                    '.$detail.'
                </td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div>';
        
        if($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows().' Data Ditemukan', 'code' => 200];
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
            
            if($r->is_status === 'VERIFIKASI_ADMIN' || $r->is_status === 'TMS' || $r->is_status === 'BTL' || privilages('priv_approve')) {
                $selesai = '<button type="button" onclick="Selesai(\''.$r->token.'\')" class="btn btn-sm btn-success m-0 rounded-0"><i class="fa fa-check-circle"></i> <br> Selesai</button>';
                $detail = '<button onclick="window.location.href = \''.base_url('app/spj/verifikasi_usul/'.$r->token).'\'" type="button" class="btn btn-sm btn-warning m-0 rounded-0"><i class="fa fa-pencil"></i> <br> Ubah</button>';
            } else {
                $detail = '<button onclick="window.location.href = \''.base_url('app/spj/verifikasi_usul/'.$r->token).'\'" type="button" class="btn btn-sm btn-primary m-0 rounded-0"><i class="fa fa-eye"></i> <br> Detail</button>';
                $selesai = '';
            } 
            
            if($r->is_status === 'ENTRI') {
                $status = '<span class="badge p-2 badge-secondary"><i class="fa fa-edit mr-2"></i> ENTRI</span>';
            } elseif($r->is_status === 'VERIFIKASI') {
                $status = '<span class="badge p-2 badge-primary"><i class="fa fa-lock mr-2"></i> VERIFIKASI</span>';
            } elseif($r->is_status === 'VERIFIKASI_ADMIN') {
                $status = '<span class="badge p-2 badge-primary"><i class="fa fa-lock mr-2"></i> VERIFIKASI ADMIN</span>';
            } elseif($r->is_status === 'APPROVE') {
                $status = '<span class="badge p-2 badge-success"><i class="fa fa-check-circle mr-2"></i> APPROVE</span>';
            } elseif($r->is_status === 'BTL') {
                $status = '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> BTL</span>';
            } elseif($r->is_status === 'TMS') {
                $status = '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> TMS</span>';
            } else {
                $status = '<span class="badge p-2 badge-success"><i class="fa fa-check-circle mr-2"></i> SELESAI</span>';
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<br><br>'.$r->kode_kegiatan.' <br> '.$r->kode_sub_kegiatan;
            $row[] = '<b>'.$r->nama_part.'</b> <br>'.$r->nama_program.' <br/>  '.strtoupper($r->nama_kegiatan).' <br>  '.$r->nama_sub_kegiatan;
            $row[] = longdate_indo(substr($r->entri_at,0,10))."<br>(".$userusul->nama.")<hr>".$status;
            $row[] = "<b>".nominal($r->jumlah)."</b>";
            $row[] = $detail." ".$selesai;
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

        $whr = [
            'token' => $token
        ];

        if($input['status'] == 'MS') {
            $update = [
                'nomor_pembukuan' => $input['nomor'],
                'tanggal_pembukuan' => formatToSQL($input['tanggal']),
                'is_status' => 'VERIFIKASI_ADMIN',
                'is_realisasi' => $input['is_realisasi'],
                'catatan' => '',
                'verify_by' => $this->session->userdata('user_name'),
                'verify_at' => date('Y-m-d H:i:s'),
            ];
            $db = $this->crud->update('spj', $update, $whr);
        } elseif($input['status'] == 'TMS' || $input['status'] == 'BTL'){
            $update = [
                'nomor_pembukuan' => '',
                'tanggal_pembukuan' => '',
                'is_realisasi' => '',
                'catatan' => $input['catatan'],
                'is_status' => $input['status'],
                'verify_by' => $this->session->userdata('user_name'),
                'verify_at' => date('Y-m-d H:i:s'),
            ];
            $db = $this->crud->update('spj', $update, $whr);
        } else {
            $update = [
                'is_status' => $input['status']
            ];
            $db = $this->crud->update('spj', $update, $whr);
        }

        if($db) {
            $msg = ['pesan' => 'Usulan SPJ Berhasil Di Proses', 'code' => 200, 'redirect' => base_url('app/spj/?tab=%23verifikasi')];
            
        } else {
            $msg = ['pesan' => 'Usulan SPJ Gagal Di Proses', 'code' => 400];
        }

        echo json_encode($msg);
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

        $kode_kegiatan = $this->spj->getKode('ref_kegiatans', $detailUsul->fid_kegiatan);
        $kode_sub_kegiatan = $this->spj->getKode('ref_sub_kegiatans', $detailUsul->fid_sub_kegiatan);
        
        $whr = [
            'token' => $detailUsul->token
        ];

        if($detailUsul->is_status === 'BTL') {
            $is_status = 'SELESAI_BTL';
        } elseif($detailUsul->is_status === 'TMS') {
            $is_status = 'SELESAI_TMS';
        } else {
            $is_status = 'SELESAI';
        }

        $update = [
            'is_status' => $is_status,
            'approve_by' => $this->session->userdata('user_name'),
            'approve_at' => date('Y-m-d H:i:s'),
        ];

        $insert = [
            'token' => $detailUsul->token,
            'nama_part' => $nama_part,
            'nama_program' => $nama_program,
            'nama_kegiatan' => $nama_kegiatan,
            'nama_sub_kegiatan' => $nama_sub_kegiatan,
            'kode_kegiatan' => $kode_kegiatan,
            'kode_sub_kegiatan' => $kode_sub_kegiatan,
            'koderek' => $detailUsul->koderek,
            'nomor_pembukuan' => $detailUsul->nomor_pembukuan,
            'bulan' => $detailUsul->bulan,
            'tahun' => $detailUsul->tahun,
            'tanggal_pembukuan' => $detailUsul->tanggal_pembukuan,
            'jumlah' => $detailUsul->jumlah,
            'uraian' => $detailUsul->uraian,
            'is_status' => $is_status === 'SELESAI' ? 'APPROVE' : $detailUsul->is_status,
            'is_realisasi' => $detail->is_realisasi,
            'catatan' => $detailUsul->catatan,
            'approve_by' => $is_status === 'SELESAI' ? $this->session->userdata('user_name') : $detailUsul->approve_by,
            'approve_at' => $is_status === 'SELESAI' ? date('Y-m-d H:i:s') : $detailUsul->approve_at,
            'entri_at' => $detailUsul->entri_at,
            'entri_by' => $detailUsul->entri_by,
            'entri_by_part' => $detailUsul->entri_by_part,
            'verify_at' => $detailUsul->verify_at,
            'verify_by' => $detailUsul->verify_by,
            'berkas_file' => $detailUsul->berkas_file,
            'berkas_link' => $detailUsul->berkas_link
        ];

        $db = $this->crud->update('spj', $update, $whr);
        if($db) {
            $this->crud->insert('spj_riwayat', $insert);
            $msg = ['pesan' => 'Oke', 'code' => 200];
        } else {
            $msg = ['pesan' => 'Gagal', 'code' => 400];
        }
        echo json_encode($msg);
    }

    public function verifikasi_selesai() {
        $db = $this->spj->make_datatables_verifikasi_selesai();
        $data = array();
        $no = @$_POST['start'];
        foreach ($db as $r) {

            if($r->is_status === 'ENTRI') {
                $status = '<span class="badge p-2 badge-secondary"><i class="fa fa-edit mr-2"></i> ENTRI</span>';
            } elseif($r->is_status === 'VERIFIKASI' || $r->is_status === 'VERIFIKASI_ADMIN') {
                $status = '<span class="badge p-2 badge-primary"><i class="fa fa-lock mr-2"></i> VERIFIKASI</span>';
            } elseif($r->is_status === 'APPROVE') {
                $status = '<span class="badge p-2 badge-success"><i class="fa fa-check-circle mr-2"></i> APPROVE</span>';
            } elseif($r->is_status === 'BTL') {
                $status = '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> BTL</span>';
            } elseif($r->is_status === 'TMS') {
                $status = '<span class="badge p-2 badge-danger"><i class="fa fa-close mr-2"></i> TMS</span>';
            } else {
                $status = '<span class="badge p-2 badge-success"><i class="fa fa-check-circle mr-2"></i> SELESAI</span>';
            }

            $userusul = $this->users->profile_username($r->entri_by)->row();
        
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<br><br>'.$r->kode_kegiatan.' <br> '.$r->kode_sub_kegiatan;
            $row[] = '<b>'.$r->nama_part.'</b> <br>'.$r->nama_program.' <br/>  '.strtoupper($r->nama_kegiatan).' <br>  '.$r->nama_sub_kegiatan;
            $row[] = longdate_indo(substr($r->entri_at,0,10))."<br>(".$userusul->nama.")";
            $row[] = $status;
            $row[] = "<b>".nominal($r->jumlah)."</b>";
            $row[] = '<button onclick="window.location.href = \''.base_url('app/spj/verifikasi_selesai_detail/'.$r->token).'\'" type="button" class="btn btn-sm btn-primary m-0 rounded-0"><i class="fa fa-eye"></i> <br> Detail</button>';
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
        if($getToken) {
            $detail = $this->crud->getWhere('spj', ['token' => $_GET['token']])->row();
        }

        $data = [
			'title' => 'Entri Usul - SPJ (Surat Pertanggung Jawaban)',
            'content' => 'pages/spj/usul',
            'list_bidang' => $this->crud->get('ref_parts')->result(),
            'list_program' => $this->crud->get('ref_programs')->result(),
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

    public function carikode() {
        $input = $this->input->post();
        $kode_kegiatan = $this->crud->getWhere('ref_kegiatans', ['id' => $input['kegiatan']])->row();
        $kode_subkegiatan = $this->crud->getWhere('ref_sub_kegiatans', ['id' => $input['sub_kegiatan']])->row();

        $data = [
            'part_id' => $input['part'],
            'program_id' => $input['program'],
            'kegiatan_id' => $input['kegiatan'],
            'subkegiatan_id' => $input['sub_kegiatan'],
            'kode_kegiatan' => $kode_kegiatan->kode,
            'kode_subkegiatan' => $kode_subkegiatan->kode,
            'kode' => $kode_kegiatan->kode.".".$kode_subkegiatan->kode
        ];
        echo json_encode($data);
    }
    public function prosesusul()
    {
        $input = $this->input->post();
        if(!empty($input['token'])) {
            $data = [
                'fid_part' => $input['ref_part'],
                'fid_program' => $input['ref_program'],
                'fid_kegiatan' => $input['ref_kegiatan'],
                'fid_sub_kegiatan' => $input['ref_subkegiatan'],
                'koderek' => $input['koderek'],
                'bulan' => $input['bulan'],
                'tahun' => $input['tahun'],
                'uraian' => $input['uraian'],
                'jumlah' => get_only_numbers($input['jumlah'])
            ];
            $db = $this->crud->update('spj', $data, ['token' => $input['token']]);
            $isToken = $input['token'];
        } else {
            $data = [
                'token' => generateRandomString(18),
                'fid_part' => $input['ref_part'],
                'fid_program' => $input['ref_program'],
                'fid_kegiatan' => $input['ref_kegiatan'],
                'fid_sub_kegiatan' => $input['ref_subkegiatan'],
                'koderek' => $input['koderek'],
                'bulan' => $input['bulan'],
                'tahun' => $input['tahun'],
                'uraian' => $input['uraian'],
                'jumlah' => get_only_numbers($input['jumlah']),
                'entri_at' => date('Y:m:d H:i:s'),
                'entri_by' => $this->session->userdata('user_name'),
                'entri_by_part' => $this->session->userdata('part')
            ];
            $db = $this->crud->insert('spj',$data);
            $isToken = $data['token'];
        }

        if($db)
        {
            $status = ['msg' => 'Oke', 'code' => 200, 'redirect' => base_url('app/spj/buatusul?step=1&status=entri&token='.$isToken)];
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
        if($db)
        {
            $status = ['msg' => 'Oke', 'code' => 200, 'redirect' => base_url('app/spj/buatusul?step=2&status=verifikasi&token='.$input['token'])];
        } else {
            $status = ['msg' => 'Gagal', 'code' => 400];
        }
        echo json_encode($status);
    }
}
