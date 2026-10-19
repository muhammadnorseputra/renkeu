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

		// Pagu & Realisasi publik per level hierarki (tab level di halaman depan)
		$ta = (int) $tahun;

		// Total keseluruhan (tanpa join)
		$total_pagu_murni     = (int) $this->db->select('COALESCE(SUM(total_pagu_awal),0) as t')->where('tahun', $ta)->where('is_perubahan', '0')->get('t_pagu')->row()->t;
		$total_pagu_perubahan = (int) $this->db->select('COALESCE(SUM(total_pagu_awal),0) as t')->where('tahun', $ta)->where('is_perubahan', '1')->get('t_pagu')->row()->t;
		$total_realisasi      = (int) $this->db->select('COALESCE(SUM(jumlah),0) as t')->where('tahun', $ta)->where('is_status', 'SELESAI')->get('spj')->row()->t;

		// Level -> [tabel ref, join chain untuk pagu/realisasi (l = alias tabel level), filter list]
		$levels = [
			'bidang'       => ['label' => 'Bidang',       'table' => 'ref_parts',   'cols' => 'id, singkatan AS kode, nama', 'where' => "singkatan != 'KABAN'", 'order' => 'id'],
			'kelompok'     => ['label' => 'Kelompok',     'table' => 'ref_kelompok_belanja', 'where' => 'tahun = ' . $ta, 'order' => 'kode'],
			'jenis'        => ['label' => 'Jenis',        'table' => 'ref_jenis_belanja',    'where' => 'tahun = ' . $ta, 'order' => 'kode'],
			'uraian'       => ['label' => 'Uraian',       'table' => 'ref_uraians',          'where' => "tahun = $ta AND is_aktif = 'Y'", 'order' => 'kode', 'group_by' => 'kode', 'multi_bidang' => true],
			'program'      => ['label' => 'Program',      'table' => 'ref_programs',         'where' => 'tahun = ' . $ta, 'order' => 'kode'],
			'kegiatan'     => ['label' => 'Kegiatan',     'table' => 'ref_kegiatans',        'where' => 'tahun = ' . $ta, 'order' => 'kode'],
			'sub_kegiatan' => ['label' => 'Sub Kegiatan', 'table' => 'ref_sub_kegiatans',    'where' => 'tahun = ' . $ta, 'order' => 'kode'],
		];

		// Rantai join dari t_pagu/spj ke tabel level (l = alias tabel level pada tiap query).
		$joins = [
			'bidang'       => ["ref_parts l ON l.id = %s.fid_part"],
			'kelompok'     => ["ref_uraians u ON u.id = %s.fid_uraian AND u.tahun = $ta", "ref_jenis_belanja j ON j.id = u.fid_jenis_belanja AND j.tahun = $ta", "ref_kelompok_belanja l ON l.id = j.fid_kelompok_belanja AND l.tahun = $ta"],
			'jenis'        => ["ref_uraians u ON u.id = %s.fid_uraian AND u.tahun = $ta", "ref_jenis_belanja l ON l.id = u.fid_jenis_belanja AND l.tahun = $ta"],
			'uraian'       => ["ref_uraians l ON l.id = %s.fid_uraian AND l.tahun = $ta AND l.is_aktif = 'Y'"],
			'program'      => ["ref_uraians u ON u.id = %s.fid_uraian AND u.tahun = $ta", "ref_kegiatans kg ON kg.id = u.fid_kegiatan AND kg.tahun = $ta", "ref_programs l ON l.id = kg.fid_program AND l.tahun = $ta"],
			'kegiatan'     => ["ref_uraians u ON u.id = %s.fid_uraian AND u.tahun = $ta", "ref_kegiatans l ON l.id = u.fid_kegiatan AND l.tahun = $ta"],
			'sub_kegiatan' => ["ref_uraians u ON u.id = %s.fid_uraian AND u.tahun = $ta", "ref_sub_kegiatans l ON l.id = u.fid_sub_kegiatan AND l.tahun = $ta"],
		];

		$level_data = [];
		foreach ($levels as $key => $cfg) {
			$pm_map = $pp_map = $rl_map = [];

			// --- uraian khusus: group by kode, hanya kode yang muncul di >1 bidang, tambah TOTAL ---
			if ($key === 'uraian') {
				$uraian_join = "ref_uraians l ON l.id = %s.fid_uraian AND l.tahun = $ta AND l.is_aktif = 'Y'
					JOIN ref_kegiatans kg ON kg.id = l.fid_kegiatan AND kg.tahun = $ta
					JOIN ref_parts pt ON pt.id = kg.fid_part";
				$pagu_chain = sprintf($uraian_join, 'p');
				$real_chain = sprintf($uraian_join, 's');

				// Semua kode (tanpa HAVING) untuk map nilai
				foreach ($this->db->query("SELECT l.kode AS kode, COALESCE(SUM(p.total_pagu_awal),0) AS total FROM t_pagu p JOIN $pagu_chain WHERE p.tahun = $ta AND p.is_perubahan = '0' GROUP BY l.kode")->result() as $r) {
					$pm_map[$r->kode] = (int) $r->total;
				}
				foreach ($this->db->query("SELECT l.kode AS kode, COALESCE(SUM(p.total_pagu_awal),0) AS total FROM t_pagu p JOIN $pagu_chain WHERE p.tahun = $ta AND p.is_perubahan = '1' GROUP BY l.kode")->result() as $r) {
					$pp_map[$r->kode] = (int) $r->total;
				}
				foreach ($this->db->query("SELECT l.kode AS kode, COALESCE(SUM(s.jumlah),0) AS total FROM spj s JOIN $real_chain WHERE s.tahun = $ta AND s.is_status = 'SELESAI' GROUP BY l.kode")->result() as $r) {
					$rl_map[$r->kode] = (int) $r->total;
				}

				// Nama per kode
				foreach ($this->db->query("SELECT DISTINCT l.kode AS kode, l.nama AS nama FROM ref_uraians l WHERE l.tahun = $ta AND l.is_aktif = 'Y'")->result() as $r) {
					$nama_map[$r->kode] = $r->nama;
				}

				// Kode yang muncul di >1 bidang (union dari 3 sumber)
				$multi_sql = "SELECT l.kode FROM t_pagu p JOIN $pagu_chain WHERE p.tahun = $ta AND p.is_perubahan = '0' GROUP BY l.kode HAVING COUNT(DISTINCT pt.id) > 1
					UNION
					SELECT l.kode FROM t_pagu p JOIN $pagu_chain WHERE p.tahun = $ta AND p.is_perubahan = '1' GROUP BY l.kode HAVING COUNT(DISTINCT pt.id) > 1
					UNION
					SELECT l.kode FROM spj s JOIN $real_chain WHERE s.tahun = $ta AND s.is_status = 'SELESAI' GROUP BY l.kode HAVING COUNT(DISTINCT pt.id) > 1";
				$multi_kode = [];
				foreach ($this->db->query($multi_sql)->result() as $r) {
					$multi_kode[] = $r->kode;
				}

				$rows = [];
				foreach ($multi_kode as $kode) {
					$pm = $pm_map[$kode] ?? 0;
					$pp = $pp_map[$kode] ?? 0;
					$rl = $rl_map[$kode] ?? 0;
					$aktif = $pp > 0 ? $pp : $pm;
					$rows[] = [
						'kode' => $kode,
						'nama' => $nama_map[$kode] ?? '',
						'pagu_murni' => $pm,
						'pagu_perubahan' => $pp,
						'realisasi' => $rl,
						'capaian' => $aktif > 0 ? round($rl / $aktif * 100, 2) : 0,
						'is_total' => false,
					];
				}

				$total_pm = array_sum(array_column($rows, 'pagu_murni'));
				$total_pp = array_sum(array_column($rows, 'pagu_perubahan'));
				$total_rl = array_sum(array_column($rows, 'realisasi'));
				$total_aktif = $total_pp > 0 ? $total_pp : $total_pm;
				$rows[] = [
					'kode' => '',
					'nama' => 'TOTAL',
					'pagu_murni' => $total_pm,
					'pagu_perubahan' => $total_pp,
					'realisasi' => $total_rl,
					'capaian' => $total_aktif > 0 ? round($total_rl / $total_aktif * 100, 2) : 0,
					'is_total' => true,
				];

				$level_data[$key] = [
					'label' => $cfg['label'],
					'rows' => $rows,
					'chart' => [
						'labels' => array_column($rows, 'nama'),
						'pagu_murni' => array_column($rows, 'pagu_murni'),
						'pagu_perubahan' => array_column($rows, 'pagu_perubahan'),
						'realisasi' => array_column($rows, 'realisasi'),
						'capaian' => array_column($rows, 'capaian'),
					],
				];
				continue;
			}
			$pagu_chain = implode(' JOIN ', array_map(function ($j) { return sprintf($j, 'p'); }, $joins[$key]));
			$real_chain = implode(' JOIN ', array_map(function ($j) { return sprintf($j, 's'); }, $joins[$key]));

			// Pagu murni
			foreach ($this->db->query("SELECT l.id AS id, COALESCE(SUM(p.total_pagu_awal),0) AS total FROM t_pagu p JOIN $pagu_chain WHERE p.tahun = $ta AND p.is_perubahan = '0' GROUP BY l.id")->result() as $r) {
				$pm_map[$r->id] = (int) $r->total;
			}
			// Pagu perubahan
			foreach ($this->db->query("SELECT l.id AS id, COALESCE(SUM(p.total_pagu_awal),0) AS total FROM t_pagu p JOIN $pagu_chain WHERE p.tahun = $ta AND p.is_perubahan = '1' GROUP BY l.id")->result() as $r) {
				$pp_map[$r->id] = (int) $r->total;
			}
			// Realisasi (spj SELESAI, tanpa filter is_perubahan)
			foreach ($this->db->query("SELECT l.id AS id, COALESCE(SUM(s.jumlah),0) AS total FROM spj s JOIN $real_chain WHERE s.tahun = $ta AND s.is_status = 'SELESAI' GROUP BY l.id")->result() as $r) {
				$rl_map[$r->id] = (int) $r->total;
			}

			$db_list = $this->db->select($cfg['cols'] ?? 'id, kode, nama')->from($cfg['table']);
			if ($cfg['where'] !== '') {
				$db_list->where($cfg['where'], null, false);
			}
			$db_list = $db_list->order_by($cfg['order'], 'ASC')->get();

			$rows = [];
			foreach ($db_list->result() as $r) {
				$pm = $pm_map[$r->id] ?? 0;
				$pp = $pp_map[$r->id] ?? 0;
				$rl = $rl_map[$r->id] ?? 0;
				$aktif = $pp > 0 ? $pp : $pm;
				$rows[] = [
					'kode' => $r->kode,
					'nama' => $r->nama,
					'pagu_murni' => $pm,
					'pagu_perubahan' => $pp,
					'realisasi' => $rl,
					'capaian' => $aktif > 0 ? round($rl / $aktif * 100, 2) : 0,
					'is_total' => false,
				];
			}

			$level_data[$key] = [
				'label' => $cfg['label'],
				'rows' => $rows,
				'chart' => [
					'labels' => array_column($rows, 'nama'),
					'pagu_murni' => array_column($rows, 'pagu_murni'),
					'pagu_perubahan' => array_column($rows, 'pagu_perubahan'),
					'realisasi' => array_column($rows, 'realisasi'),
					'capaian' => array_column($rows, 'capaian'),
				],
			];
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
			'level_data' => $level_data,
			'chart_harian' => json_encode($chart_harian),
			'chart_gbulan' => json_encode(@array_values($chart_gbulan)),
			'total_pagu_murni' => $total_pagu_murni,
			'total_pagu_perubahan' => $total_pagu_perubahan,
			'total_realisasi' => $total_realisasi,
			'pagu_aktif' => ($total_pagu_perubahan > 0 ? $total_pagu_perubahan : $total_pagu_murni),
			'persentase_capaian' => ($total_pagu_perubahan > 0 ? $total_pagu_perubahan : $total_pagu_murni) > 0 ? round($total_realisasi / ($total_pagu_perubahan > 0 ? $total_pagu_perubahan : $total_pagu_murni) * 100, 2) : 0,
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
