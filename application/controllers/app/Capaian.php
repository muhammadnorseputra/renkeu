<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Capaian extends CI_Controller
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
		if (!privilages('priv_default') && !privilages('priv_anggarankinerja')) :
			return show_404();
		endif;

		$this->load->model('ModelTarget', 'target');
		$this->load->model('ModelRealisasi', 'realisasi');
		$this->load->model('ModelSpj', 'spj');
		$this->load->model('ModelUsers', 'user');
	}

	public function index()
	{
		$programs = $this->target->program($this->session->userdata('part'), $this->session->userdata('tahun_anggaran'));
		$data = [
			'title' => 'Capaian Anggaran & Kinerja',
			'content' => 'pages/anggaran_kinerja/capaian',
			'programs' => $programs,
			'autoload_js' => [
				'template/backend/vendors/parsleyjs/dist/parsley.min.js',
				'template/custom-js/capaian.js',
			],
		];
		$this->load->view('layout/app', $data);
	}

	public function cetak($periode_id)
	{
		$periode_nama = $this->realisasi->getPeriodeById($periode_id)->row()->nama;
		$programs = $this->target->program($this->session->userdata('part'));

		$this->load->library('pdf');
		$this->pdf->setPaper('legal', 'landscape');
		$this->pdf->filename = 'SIMEV - Cetak Capaian Anggaran & Kinerja - ' . $periode_nama;

		$data = [
			'title' => 'Capaian Anggaran & Kinerja  - ' . $periode_nama,
			'programs' => $programs,
			'tw_id' => $periode_id,
			'tw_nama' => $periode_nama
		];
		$this->pdf->load_view('pages/anggaran_kinerja/capaian_cetak', $data);
	}

	public function laporan()
	{
		$data = [
			'title' => 'Laporan Anggaran & Kinerja',
			'content' => 'pages/anggaran_kinerja/laporan',
			'autoload_js' => [
				'template/backend/vendors/select2/dist/js/select2.full.min.js',
			],
			'autoload_css' => [
				'template/backend/vendors/select2/dist/css/select2.min.css',
			]
		];
		$this->load->view('layout/app', $data);
	}

	public function laporan_cetak()
	{
		$post = $this->input->post();
		$programs = $this->target->program($this->session->userdata('part'));

		$this->load->library('pdf');
		$this->pdf->setPaper('legal', 'landscape');
		$this->pdf->filename = 'SIMEV - Laporan Anggaran & Kinerja - Tahun ' . $post['tahun'];

		$data = [
			'title' => 'Laporan Anggaran & Kinerja - Tahun ' . $post['tahun'],
			'programs' => $programs,
			'tahun' => $post['tahun']
		];
		$this->pdf->load_view('pages/anggaran_kinerja/laporan_cetak', $data);
	}
}
