<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	public function index()
	{
		$jml_pegawai = $this->crud->getWhere('t_users', ['is_valid' => '1'])->num_rows();
		$jml_bidang = $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN'])->num_rows();
		$tahun_row = $this->crud->getWhere('t_settings', ['key' => 'tahun_anggaran', 'status' => 'Y'])->row();
		if ($tahun_row) {
			$tahun = $tahun_row->val;
		} else {
			$tahun_row2 = $this->db->select('tahun')->order_by('tahun', 'DESC')->limit(1)->get('ref_kelompok_belanja')->row();
			$tahun = $tahun_row2 ? $tahun_row2->tahun : date('Y');
		}
		
		$team = $this->db->query("SELECT u.nama, u.role, u.pic, r.nama AS bidang, r.singkatan FROM t_users u LEFT JOIN ref_parts r ON u.fid_part = r.id WHERE u.is_valid='1' AND u.is_block='N' ORDER BY FIELD(u.role,'SUPER_ADMIN','ADMIN','SUPER_USER','VERIFICATOR','USER','ARSIP_USER','BENDAHARA'), u.nama")->result();

		// Pagu & Realisasi publik (agregat semua bidang, anggaran murni)
		$ta = $tahun;
		$is_perubahan = '0';
		$kelompok_chart = ['labels' => [], 'pagu' => [], 'realisasi' => []];
		$total_pagu = 0;
		$total_realisasi = 0;

		$db_pagu_kel = $this->db->select('k.id, k.kode, k.nama, SUM(p.total_pagu_awal) as total')
			->from('ref_kelompok_belanja k')
			->join('ref_jenis_belanja j', 'j.fid_kelompok_belanja = k.id', 'left')
			->join('ref_uraians u', 'u.fid_jenis_belanja = j.id', 'left')
			->join('t_pagu p', 'p.fid_uraian = u.id AND p.tahun = ' . $this->db->escape($ta) . ' AND p.is_perubahan = ' . $this->db->escape($is_perubahan), 'left')
			->where('k.tahun', $ta)
			->group_by('k.id')
			->get();
		$pagu_kelompok = [];
		foreach ($db_pagu_kel->result() as $row) {
			$pagu_kelompok[$row->id] = ['kode' => $row->kode, 'nama' => $row->nama, 'total_pagu' => (int) $row->total];
		}

		$db_rk = $this->db->select('k.id, k.nama, SUM(s.jumlah) as total')
			->from('ref_kelompok_belanja k')
			->join('ref_jenis_belanja j', 'j.fid_kelompok_belanja = k.id', 'left')
			->join('ref_uraians u', 'u.fid_jenis_belanja = j.id', 'left')
			->join('spj s', 's.fid_uraian = u.id')
			->where('k.tahun', $ta)
			->where('s.tahun', $ta)
			->where('s.is_status', 'SELESAI')
			->group_by('k.id')
			->get();
		$real_kelompok = [];
		foreach ($db_rk->result() as $row) {
			$real_kelompok[$row->id] = ['nama' => $row->nama, 'total_realisasi' => (int) $row->total];
		}

		$kelompok_ids = array_unique(array_merge(array_keys($pagu_kelompok), array_keys($real_kelompok)));
		foreach ($kelompok_ids as $id) {
			$kelompok_chart['labels'][] = $pagu_kelompok[$id]['nama'] ?? ($real_kelompok[$id]['nama'] ?? '-');
			$kelompok_chart['pagu'][] = (int) ($pagu_kelompok[$id]['total_pagu'] ?? 0);
			$kelompok_chart['realisasi'][] = (int) ($real_kelompok[$id]['total_realisasi'] ?? 0);
			$total_pagu += (int) ($pagu_kelompok[$id]['total_pagu'] ?? 0);
			$total_realisasi += (int) ($real_kelompok[$id]['total_realisasi'] ?? 0);
		}

		$data = [
			'content' => 'frontend/home',
			'jml_pegawai' => $jml_pegawai,
			'jml_bidang' => $jml_bidang,
			'tahun_anggaran' => $tahun,
			'team' => $team,
			'pagu_chart' => json_encode($kelompok_chart),
			'total_pagu' => $total_pagu,
			'total_realisasi' => $total_realisasi,
			'persentase_capaian' => $total_pagu > 0 ? round(($total_realisasi / $total_pagu) * 100, 1) : 0,
		];
		$this->load->view('landingpage', $data);
	}

	public function page($path)
	{
		if($path === 'about') {
			$data = [
				'content' => 'frontend/about'
			];
		} elseif($path === 'featured') {
			$data = [
				'content' => 'frontend/featured'
			];
		} elseif($path === 'screenshot') {
			$data = [
				'content' => 'frontend/screenshot'
			];
		} elseif($path === 'team') {
			$data = [
				'content' => 'frontend/team'
			];
		} elseif($path === 'contact') {
			$data = [
				'content' => 'frontend/contact'
			];
		} else {
			$data = [
				'content' => 'errors'
			];
		}

		return $this->load->view('landingpage', $data);
	}
}
