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

		// Pagu & Realisasi publik (agregat semua bidang, murni + perubahan)
		$ta = $tahun;
		$kelompok_chart = ['labels' => [], 'pagu_murni' => [], 'pagu_perubahan' => [], 'realisasi' => []];
		$total_pagu_murni = 0;
		$total_pagu_perubahan = 0;
		$total_realisasi = 0;

		$db_pagu_murni = $this->db->select('k.id, k.kode, k.nama, SUM(p.total_pagu_awal) as total')
			->from('ref_kelompok_belanja k')
			->join('ref_jenis_belanja j', 'j.fid_kelompok_belanja = k.id', 'left')
			->join('ref_uraians u', 'u.fid_jenis_belanja = j.id', 'left')
			->join('t_pagu p', 'p.fid_uraian = u.id AND p.tahun = ' . $this->db->escape($ta) . ' AND p.is_perubahan = \'0\'', 'left')
			->where('k.tahun', $ta)
			->group_by('k.id')
			->get();
		$pagu_murni = [];
		foreach ($db_pagu_murni->result() as $row) {
			$pagu_murni[$row->id] = ['kode' => $row->kode, 'nama' => $row->nama, 'total_pagu' => (int) $row->total];
		}

		$db_pagu_perubahan = $this->db->select('k.id, SUM(p.total_pagu_awal) as total')
			->from('ref_kelompok_belanja k')
			->join('ref_jenis_belanja j', 'j.fid_kelompok_belanja = k.id', 'left')
			->join('ref_uraians u', 'u.fid_jenis_belanja = j.id', 'left')
			->join('t_pagu p', 'p.fid_uraian = u.id AND p.tahun = ' . $this->db->escape($ta) . ' AND p.is_perubahan = \'1\'', 'left')
			->where('k.tahun', $ta)
			->group_by('k.id')
			->get();
		$pagu_perubahan = [];
		foreach ($db_pagu_perubahan->result() as $row) {
			$pagu_perubahan[$row->id] = ['total_pagu' => (int) $row->total];
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

		$kelompok_ids = array_unique(array_merge(array_keys($pagu_murni), array_merge(array_keys($pagu_perubahan), array_keys($real_kelompok))));
		foreach ($kelompok_ids as $id) {
			$kelompok_chart['labels'][] = $pagu_murni[$id]['nama'] ?? ($real_kelompok[$id]['nama'] ?? '-');
			$kelompok_chart['pagu_murni'][] = (int) ($pagu_murni[$id]['total_pagu'] ?? 0);
			$kelompok_chart['pagu_perubahan'][] = (int) ($pagu_perubahan[$id]['total_pagu'] ?? 0);
			$kelompok_chart['realisasi'][] = (int) ($real_kelompok[$id]['total_realisasi'] ?? 0);
			$total_pagu_murni += (int) ($pagu_murni[$id]['total_pagu'] ?? 0);
			$total_pagu_perubahan += (int) ($pagu_perubahan[$id]['total_pagu'] ?? 0);
			$total_realisasi += (int) ($real_kelompok[$id]['total_realisasi'] ?? 0);
		}

		$chart_harian = ['range' => null, 'tanggal' => [], 'usulan' => [], 'cair' => []];
		$cur_date = new DateTime('first day of this year');
		$end_date = new DateTime('last day of this year');
		$map_harian = ['usulan' => [], 'cair' => []];

		$db_harian = $this->db->select('sr.approve_at, sp.status, SUM(sr.jumlah) as total')
			->from('spj_riwayat sr')
			->join('spj_payment sp', 'sr.token = sp.token', 'left')
			->where('sr.tahun', $ta)
			->where('sr.is_status', 'APPROVE')
			->group_by('DATE(sr.approve_at), sp.status')
			->get();
		foreach ($db_harian->result() as $row) {
			$dt = substr($row->approve_at, 0, 10);
			if (empty($map_harian['usulan'][$dt])) {
				$chart_harian['tanggal'][] = $dt;
				$map_harian['usulan'][$dt] = 0;
				$map_harian['cair'][$dt] = 0;
			}
			$map_harian['cair'][$dt] += (float) $row->total;
		}

		$db_usulan = $this->db->select('DATE(entri_at) as tanggal, SUM(jumlah) as total')
			->from('spj')
			->where('tahun', $ta)
			->where_in('is_status', ['ENTRI', 'VERIFIKASI'])
			->group_by('DATE(entri_at)')
			->get();
		foreach ($db_usulan->result() as $row) {
			$dt = $row->tanggal;
			if (!isset($map_harian['usulan'][$dt])) {
				$chart_harian['tanggal'][] = $dt;
				$map_harian['usulan'][$dt] = 0;
				$map_harian['cair'][$dt] = 0;
			}
			$map_harian['usulan'][$dt] += (float) $row->total;
		}

		$chart_harian['tanggal'] = array_unique($chart_harian['tanggal']);
		sort($chart_harian['tanggal']);
		foreach ($chart_harian['tanggal'] as $dt) {
			$chart_harian['usulan'][] = $map_harian['usulan'][$dt];
			$chart_harian['cair'][] = $map_harian['cair'][$dt];
		}

	 	$chart_gbulan = [];
	 	for ($i = 1; $i <= 12; $i++) {
	 		$chart_gbulan[] = [$i, 0];
	 	}
	 	$db_gbulan = $this->db->select('sr.approve_at, SUM(sr.jumlah) as total')
	 		->from('spj_riwayat sr')
	 		->where('sr.tahun', $ta)
	 		->where('sr.is_status', 'APPROVE')
	 		->group_by('MONTH(sr.approve_at)')
	 		->get();
	 	foreach ($db_gbulan->result() as $row) {
	 		$m = (int) date('n', strtotime($row->approve_at));
	 		$chart_gbulan[$m - 1][1] += (float) $row->total;
	 	}
		$chart_gbulan = array_values($chart_gbulan);

		$data = [
			'content' => 'frontend/home',
			'jml_pegawai' => $jml_pegawai,
			'jml_bidang' => $jml_bidang,
			'tahun_anggaran' => $tahun,
			'team' => $team,
			'pagu_chart' => json_encode($kelompok_chart),
			'chart_harian' => json_encode($chart_harian),
			'chart_gbulan' => json_encode(@array_values($chart_gbulan)),
			'total_pagu_murni' => $total_pagu_murni,
			'total_pagu_perubahan' => $total_pagu_perubahan,
			'total_realisasi' => $total_realisasi,
			'pagu_aktif' => ($total_pagu_perubahan > 0 ? $total_pagu_perubahan : $total_pagu_murni),
			'persentase_capaian' => ($total_pagu_perubahan > 0 ? $total_pagu_perubahan : $total_pagu_murni) > 0 ? round($total_realisasi / ($total_pagu_perubahan > 0 ? $total_pagu_perubahan : $total_pagu_murni) * 100, 1) : 0,
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
