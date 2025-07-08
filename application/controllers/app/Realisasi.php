<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Realisasi extends CI_Controller
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
		if (!privilages('priv_default')  && !privilages('priv_anggarankinerja')):
			return show_404();
		endif;

		$this->load->model('ModelRealisasi', 'realisasi');
		$this->load->model('ModelSpj', 'spj');
		$this->load->model('ModelTarget', 'target');
	}

	public function index()
	{
		$data = [
			'title' => 'Realisasi Anggaran & Kinerja',
			'content' => 'pages/anggaran_kinerja/realisasi',
			'autoload_js' => [
				'template/backend/vendors/parsleyjs/dist/parsley.min.js',
				'template/custom-js/realisasi.js',
			],
		];
		$this->load->view('layout/app', $data);
	}

	public function detailIndikator()
	{
		$id = $this->input->get('id');
		$periode = $this->input->get('periode');
		$db = $this->crud->getWhere('ref_indikators', ['id' => $id]);
		$realisasi = $this->crud->getWhere('t_realisasi', ['fid_indikator' => $id, 'fid_periode' => $periode]);
		$target = $this->crud->getWhere('t_target', ['fid_indikator' => $id, 'tahun' => $this->session->userdata('tahun_anggaran')]);
		if ($db->num_rows() > 0) {
			$data = [
				'indikator' => $db->row(),
				'realisasi' => $realisasi->row(),
				'target' => $target->row()
			];
		} else {
			$data = null;
		}
		echo json_encode($data);
	}

	public function detailRealisasi()
	{
		$id = $this->input->get('id');
		$db = $this->crud->getWhere('t_realisasi', ['fid_indikator' => $id]);
		if ($db->num_rows() > 0) {
			$data = $db->row();
		} else {
			$data = null;
		}
		echo json_encode($data);
	}

	public function input()
	{
		$post = $this->input->post();

		$target = $this->target->getTarget($post['id']);

		if (isset($post['persentase']) && $post['persentase'] > $target->persentase) {
			echo json_encode([
				'message' => 'Realisasi melebihi target persentase !',
				'status' => false
			]);
			return false;
			die();
		}

		if (isset($post['jumlah_eviden']) &&  $post['jumlah_eviden'] > $target->eviden_jumlah) {
			echo json_encode([
				'message' => 'Realisasi melebihi target eviden !',
				'status' => false
			]);
			return false;
			die();
		}

		$insert = [
			'tahun' => $this->session->userdata('tahun_anggaran'),
			'fid_indikator' => $post['id'],
			'fid_periode' => $post['periode'],
			'persentase' => isset($post['persentase']) ? $post['persentase'] : 0,
			'eviden' => isset($post['jumlah_eviden']) ? $post['jumlah_eviden'] : 0,
			'eviden_link' => $post['link'],
			'eviden_jenis' => $post['keterangan_eviden'],
		];

		$update = [
			'tahun' => $this->session->userdata('tahun_anggaran'),
			'persentase' => isset($post['persentase']) ? $post['persentase'] : 0,
			'eviden' => isset($post['jumlah_eviden']) ? $post['jumlah_eviden'] : 0,
			'eviden_jenis' => $post['keterangan_eviden'],
			'eviden_link' => $post['link'],
		];

		$whr = [
			'fid_indikator' => $post['id'],
			'fid_periode' => $post['periode']
		];

		$cekrealisasi = $this->crud->getWhere('t_realisasi', $whr);
		if ($cekrealisasi->num_rows() > 0) {
			$db = $this->crud->update('t_realisasi', $update, $whr);
		} else {
			$db = $this->crud->insert('t_realisasi', $insert);
		}

		if ($db) {
			$msg = [
				'message' => 'Realisasi berhasil diperbaharui !',
				'status' => true
			];
		} else {
			$msg = [
				'message' => 'Realisasi gagal diperbaharui !',
				'status' => false
			];
		}
		echo json_encode($msg);
	}

	public function cetak($periode_id)
	{
		$periode_nama = $this->realisasi->getPeriodeById($periode_id)->row()->nama;
		$programs = $this->target->program(null, null, $this->session->userdata('tahun_anggaran'));

		$this->load->library('pdf');
		$this->pdf->setPaper('legal', 'landscape');
		$this->pdf->filename = 'SIMEV - Cetak Realisasi Anggaran & Kinerja - ' . $periode_nama;

		$data = [
			'title' => 'Realisasi Anggaran & Kinerja  - ' . $periode_nama,
			'programs' => $programs,
			'tw_id' => $periode_id,
			'tw_nama' => $periode_nama
		];
		$this->pdf->load_view('pages/anggaran_kinerja/realisasi_cetak', $data);
	}

	public function input_faktor()
	{
		$post = $this->input->post();
		$update = [
			'faktor_pendorong' => $post['faktor_pendorong'],
			'faktor_penghambat' => $post['faktor_penghambat'],
			'tindak_lanjut' => $post['tindak_lanjut']
		];

		$whr = [
			'fid_indikator' => $post['id'],
			'fid_periode' => $post['periode']
		];

		$db = $this->crud->update('t_realisasi', $update, $whr);
		if ($db) {
			$msg = 200;
		} else {
			$msg = 400;
		}
		echo json_encode($msg);
	}
}
