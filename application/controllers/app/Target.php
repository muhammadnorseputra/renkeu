<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Target extends CI_Controller {

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
		
		$this->load->model('modeltarget', 'target');
		
    }
	
	public function index()
	{
		$programs = $this->crud->get('ref_programs');
        $data = [
			'title' => 'Target Anggaran & Kinerja',
            'content' => 'pages/anggaran_kinerja/target',
			'programs' => $programs,
			'autoload_js' => [
				'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/indikator.js',
            ],
        ];
		$this->load->view('layout/app', $data);
	}

	public function tambah_indikator()
	{
		$post = $this->input->post();

		$tabel = $post['ref'];
		$id = $post['id'];
		$nama_indikator = $post['nama'];

		if($tabel === 'ref_programs')
		{
			$data = [
				'nama' => $nama_indikator,
				'fid_program' => $id
			];
		} elseif($tabel === 'ref_kegiatans') {
			$data = [
				'nama' => $nama_indikator,
				'fid_kegiatan' => $id
			];
		} elseif($tabel === 'ref_sub_kegiatans') {
			$data = [
				'nama' => $nama_indikator,
				'fid_sub_kegiatan' => $id
			];
		}

		$db = $this->crud->insert('ref_indikators', $data);
		if($db)
        {
            $msg = 200;
        } else {
            $msg = 400;
        }

        echo json_encode($msg);
	}

	public function ubah($id, $table)
	{
		$row = $this->target->getIndikator(['i.id' => $id]);

		$data = [
			'title' => 'Ubah Indikator',
			'content' => 'pages/anggaran_kinerja/indikator_ubah',
			'id_indikator' => $id,
			'row' => $row->row(),
			'autoload_js' => [
				'template/backend/vendors/parsleyjs/dist/parsley.min.js',
				'template/custom-js/indikator.js',
			],
		];

		$this->load->view('layout/app', $data);
	}

	public function ubah_proses()
	{
		$post = $this->input->post();

		$data = [
			'nama' => $post['nama'],
		];

		$whr = [
			'id' => $post['id']
		];

		$db = $this->crud->update('ref_indikators', $data, $whr);
		if($db)
        {
            $msg = 200;
			$insert = [
				'fid_indikator' => $post['id'],
				'persentase' => $post['persentase'],
				'eviden_jumlah' => $post['jumlah_eviden'],
				'eviden_jenis' => $post['keterangan_eviden']
			];
			$dbcek = $this->crud->getWhere('t_target', ['fid_indikator' => $post['id']]);
			if($dbcek->num_rows() > 0) {
				$this->crud->update('t_target', $insert, ['fid_indikator' => $post['id']]);
			} else {
				$this->crud->insert('t_target', $insert);
			}
        } else {
            $msg = 400;
        }

        echo json_encode($msg);
	}


	public function hapus() {
		$id = $this->input->post('id');
		$db = $this->crud->deleteWhere('ref_indikators', ['id' => $id]);
		if($db)
        {
			$this->crud->deleteWhere('t_realisasi', ['fid_indikator' => $id]);
			$this->crud->deleteWhere('t_target', ['fid_indikator' => $id]);
            $msg = 200;
        } else {
            $msg = 400;
        }

        echo json_encode($msg);
	}
}
