<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;

class Target extends CI_Controller
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
		if (!privilages('priv_default') && !privilages('priv_anggarankinerja') || !privilages('priv_target_kinerja')):
			return show_404();
		endif;

		$this->load->model('ModelTarget', 'target');
		$this->load->model('ModelCrud', 'crud');
	}

	public function index()
	{
		$jenis_indikator = $this->crud->get('ref_jenis_indikators')->result();
		$data = [
			'title' => 'Target Anggaran & Kinerja',
			'content' => 'pages/anggaran_kinerja/target',
			'jenis_indikator' => $jenis_indikator,
			'autoload_js' => [
				'template/backend/vendors/select2/dist/js/select2.full.min.js',
				'template/backend/vendors/parsleyjs/dist/parsley.min.js',
				'template/custom-js/indikator.js',
			],
			'autoload_css' => [
				'template/backend/vendors/select2/dist/css/select2.min.css'
			]
		];
		$this->load->view('layout/app', $data);
	}

	public function tambah_indikator()
	{
		$post = $this->input->post();

		$tabel = $post['ref'];
		$id = $post['id'];
		$nama_indikator = $post['nama'];
		$jenis_indikator = $post['jenis_indikator'];
		$perubahan = $post['perubahan'];
		$part_id = implode(",", $post['bidang']);

		if ($tabel === 'ref_tujuan') {
			$data = [
				'nama' => $nama_indikator,
				'fid_part' => $part_id,
				'fid_tujuan' => $id,
				'is_perubahan' => $perubahan,
				'tahun' => $this->session->userdata('tahun_anggaran')
			];
		} elseif ($tabel === 'ref_sasaran') {
			$data = [
				'nama' => $nama_indikator,
				'fid_part' => $part_id,
				'fid_sasaran' => $id,
				'is_perubahan' => $perubahan,
				'tahun' => $this->session->userdata('tahun_anggaran')
			];
		} elseif ($tabel === 'ref_programs') {
			$data = [
				'nama' => $nama_indikator,
				'fid_part' => $part_id,
				'fid_program' => $id,
				'is_perubahan' => $perubahan,
				'tahun' => $this->session->userdata('tahun_anggaran')
			];
		} elseif ($tabel === 'ref_kegiatans') {
			$data = [
				'nama' => $nama_indikator,
				'fid_part' => $part_id,
				'fid_kegiatan' => $id,
				'is_perubahan' => $perubahan,
				'tahun' => $this->session->userdata('tahun_anggaran')
			];
		} elseif ($tabel === 'ref_sub_kegiatans') {
			$data = [
				'nama' => $nama_indikator,
				'fid_part' => $part_id,
				'fid_sub_kegiatan' => $id,
				'fid_jenis_indikator' => $jenis_indikator,
				'is_perubahan' => $perubahan,
				'tahun' => $this->session->userdata('tahun_anggaran')
			];
		}

		$db = $this->crud->insert('ref_indikators', $data);
		if ($db) {
			$msg = 200;
		} else {
			$msg = 400;
		}

		echo json_encode($msg);
	}

	public function ubah($id, $table)
	{
		$row = $this->target->getIndikator(['i.id' => $id]);
		$jenis_indikator = $this->crud->get('ref_jenis_indikators');
		$data = [
			'title' => 'Ubah Indikator',
			'content' => 'pages/anggaran_kinerja/indikator_ubah',
			'id_indikator' => $id,
			'table' => $table,
			'jenis_indikator' => $jenis_indikator,
			'row' => $row->row(),
			'autoload_js' => [
				'template/backend/vendors/select2/dist/js/select2.full.min.js',
				'template/backend/vendors/parsleyjs/dist/parsley.min.js',
				'template/custom-js/indikator.js',
			],
			'autoload_css' => [
				'template/backend/vendors/select2/dist/css/select2.min.css'
			]
		];

		$this->load->view('layout/app', $data);
	}

	public function ubah_proses()
	{
		$post = $this->input->post();
		$part_id = implode(",", $post['bidang']);

		if (isset($post['jenis_indikator'])) {
			$data = [
				'nama' => $post['nama'],
				'fid_part' => $part_id,
				'fid_jenis_indikator' => $post['jenis_indikator']
			];
		} else {
			$data = [
				'nama' => $post['nama'],
				'fid_part' => $part_id,
			];
		}

		$whr = [
			'id' => $post['id']
		];

		$db = $this->crud->update('ref_indikators', $data, $whr);
		if ($db) {
			$msg = 200;
			$insert = [
				'fid_indikator' => $post['id'],
				'persentase' => $post['persentase'],
				'eviden_jumlah' => $post['jumlah_eviden'],
				'eviden_jenis' => $post['keterangan_eviden'],
				'tahun' => $post['tahun'],
				'created_by' => $this->session->userdata('user_name')
			];

			$update = [
				'persentase' => $post['persentase'],
				'eviden_jumlah' => $post['jumlah_eviden'],
				'eviden_jenis' => $post['keterangan_eviden'],
				'tahun' => $post['tahun'],
				'update_at' => DateTimeInput(),
				'update_by' => $this->session->userdata('user_name')
			];
			$dbcek = $this->crud->getWhere('t_target', ['fid_indikator' => $post['id']]);
			if ($dbcek->num_rows() > 0) {
				$this->crud->update('t_target', $update, ['fid_indikator' => $post['id']]);
			} else {
				$this->crud->insert('t_target', $insert);
			}
		} else {
			$msg = 400;
		}

		echo json_encode($msg);
	}

	public function cetak($tahun)
	{

		if ($this->session->userdata('role') === 'ADMIN'):
			$programs = $this->target->program(null, null, $this->session->userdata('tahun_anggaran'));
		else:
			$programs = $this->target->program(null, $this->session->userdata('part'), $this->session->userdata('tahun_anggaran'));
		endif;

		$data = [
			'title' => 'Target Anggaran & Kinerja  - Tahun ' . $tahun,
			'programs' => $programs,
			'tahun' => $tahun
		];

		$this->load->view('pages/anggaran_kinerja/target_cetak', $data);
	}

	public function hapus()
	{
		$id = $this->input->post('id');
		$db = $this->crud->deleteWhere('ref_indikators', ['id' => $id]);
		if ($db) {
			$this->crud->deleteWhere('t_realisasi', ['fid_indikator' => $id]);
			$this->crud->deleteWhere('t_target', ['fid_indikator' => $id]);
			$msg = 200;
		} else {
			$msg = 400;
		}

		echo json_encode($msg);
	}
}
