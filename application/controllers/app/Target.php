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
            'content' => 'pages/target/index',
			'programs' => $programs,
			'autoload_js' => [
				'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/tambah-indikator.js',
            ],
        ];
		$this->load->view('layout/app', $data);
	}

	public function tambah_indikator($tabel)
	{
		$post = $this->input->post();

		$id = $post['id'];
		$nama_indikator = $post['nama'];
		$persentase = $post['persentase'];
		$jumlah_eviden = $post['jumlah_eviden'];
		$keterangan_eviden = $post['keterangan_eviden'];

		if($tabel === 'ref_programs')
		{
			$data = [
				'nama' => $nama_indikator,
				'fid_program' => $id,
				'kinerja_persentase' => $persentase,
				'kinerja_eviden' => $jumlah_eviden,
				'keterangan_eviden' => $keterangan_eviden
			];
		} elseif($tabel === 'ref_kegiatans') {
			$data = [
				'nama' => $nama_indikator,
				'fid_kegiatan' => $id,
				'kinerja_persentase' => $persentase,
				'kinerja_eviden' => $jumlah_eviden,
				'keterangan_eviden' => $keterangan_eviden
			];
		} elseif($tabel === 'ref_sub_kegiatans') {
			$data = [
				'nama' => $nama_indikator,
				'fid_sub_kegiatan' => $id,
				'kinerja_persentase' => $persentase,
				'kinerja_eviden' => $jumlah_eviden,
				'keterangan_eviden' => $keterangan_eviden
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
}
