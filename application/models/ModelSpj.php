<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelSpj extends CI_Model
{

	public function getPeriode()
	{
		$this->db->select('*');
		$this->db->from('t_periode');
		$q = $this->db->get();
		return $q;
	}
	public function getPeriodeAktif()
	{
		$this->db->select('*');
		$this->db->from('t_periode');
		$this->db->where('is_open', 'Y');
		$q = $this->db->get();
		return $q;
	}
	public function getLastPeriode()
	{
		$this->db->select('*');
		$this->db->from('t_periode');
		$this->db->limit(1);
		$this->db->order_by('id', 'asc');
		$this->db->where('is_open', 'Y');
		$q = $this->db->get();
		return $q;
	}
	public function getIndikator()
	{
		return $this->db->select('id,nama')->from('ref_indikators')->order_by('id', 'asc')->get();
	}
	public function getIndikatorByToken($token)
	{
		$this->db->select('r.*');
		$this->db->from('t_realisasi AS r');
		$this->db->join('ref_indikators AS i', 'r.fid_indikator=i.id');
		$this->db->join('spj', 'r.fid_token=spj.token');
		$this->db->where('spj.token', $token);
		$q = $this->db->get();
		return $q;
	}
	public function inbox()
	{
		$this->db->select('s.*, part.nama AS nama_part, program.nama AS nama_program, program.kode AS kode_program, kegiatan.nama AS nama_kegiatan, kegiatan.kode AS kode_kegiatan, sub_kegiatan.nama AS nama_sub_kegiatan, sub_kegiatan.kode AS kode_sub_kegiatan,uraian.nama AS nama_uraian, uraian.kode AS kode_uraian, p.id as periode_id');
		$this->db->from('spj AS s');
		$this->db->join('t_periode AS p', 's.fid_periode=p.id');
		$this->db->join('ref_parts AS part', 's.fid_part=part.id');
		$this->db->join('ref_programs AS program', 's.fid_program=program.id');
		$this->db->join('ref_kegiatans AS kegiatan', 's.fid_kegiatan=kegiatan.id');
		$this->db->join('ref_sub_kegiatans AS sub_kegiatan', 's.fid_sub_kegiatan=sub_kegiatan.id');
		$this->db->join('ref_uraians AS uraian', 's.fid_uraian=uraian.id');
		$this->db->where('s.entri_by_part', $this->session->userdata('part'));
		$this->db->where('s.entri_by', $this->session->userdata('user_name'));
		$this->db->where('s.tahun', $this->session->userdata('tahun_anggaran'));
		$this->db->where('s.is_status !=', 'SELESAI');
		$this->db->where('s.is_status !=', 'SELESAI_TMS');
		$this->db->where('s.is_status !=', 'SELESAI_BTL');
		$this->db->order_by('s.id', 'desc');
		$q = $this->db->get();
		return $q;
	}

	public function detail($whr)
	{
		$this->db->select('s.*, part.nama AS nama_part, program.nama AS nama_program, program.kode AS kode_program, kegiatan.nama AS nama_kegiatan, kegiatan.kode AS kode_kegiatan, sub_kegiatan.nama AS nama_sub_kegiatan, sub_kegiatan.kode AS kode_sub_kegiatan, p.nama as periode');
		$this->db->from('spj AS s');
		$this->db->join('ref_parts AS part', 's.fid_part=part.id');
		$this->db->join('ref_programs AS program', 's.fid_program=program.id');
		$this->db->join('ref_kegiatans AS kegiatan', 's.fid_kegiatan=kegiatan.id');
		$this->db->join('ref_sub_kegiatans AS sub_kegiatan', 's.fid_sub_kegiatan=sub_kegiatan.id');
		$this->db->join('t_periode as p', 's.fid_periode=p.id');
		$this->db->where($whr);
		$q = $this->db->get();
		return $q;
	}

	public function riwayat($whr)
	{
		$q = $this->db->get_where('spj_riwayat', $whr);
		return $q;
	}

	public function riwayat_payment($whr)
	{
		$q = $this->db->get_where('spj_payment', $whr);
		return $q;
	}

	public function getNama($tbl, $id)
	{
		return $this->db->get_where($tbl, ['id' => $id])->row()->nama;
	}

	public function getKode($tbl, $id)
	{
		return $this->db->get_where($tbl, ['id' => $id])->row()->kode;
	}

	public function getPaguByUraianId($uraian_id, $ta, $is_perubahan)
	{
		$this->db->select('total_pagu_awal');
		$this->db->from('t_pagu');
		$this->db->where('fid_uraian', $uraian_id);
		$this->db->where('tahun', $ta);
		$this->db->where('is_perubahan', $is_perubahan);
		$q = $this->db->get();
		return $q->row()->total_pagu_awal;
	}

	public function TopTransaksiSPJ($limit)
	{
		$this->db->select('r.jumlah,r.entri_by,r.entri_at,r.is_status,p.singkatan');
		$this->db->from('spj_riwayat AS r');
		$this->db->join('ref_parts AS p', 'r.entri_by_part=p.id');
		$this->db->where('tahun', $this->session->userdata('tahun_anggaran'));
		$this->db->limit($limit);
		$this->db->order_by('r.id', 'desc');
		$q = $this->db->get();
		return $q;
	}

	public function TransaksiSpjBulanan($bulan, $ta)
	{
		$this->db->select_sum('r.jumlah');
		$this->db->from('spj_riwayat AS r');
		$this->db->join('t_periode AS p', 'r.fid_periode=p.id');
		$this->db->where('p.id', $bulan);
		$this->db->where('r.tahun', $ta);
		$this->db->where('r.is_status', 'APPROVE');
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	public function TransaksiSpjBulananNonMs($bulan, $status, $ta)
	{
		$this->db->select_sum('r.jumlah');
		$this->db->from('spj_riwayat AS r');
		$this->db->join('t_periode AS p', 'r.fid_periode=p.id');
		$this->db->where('p.id', $bulan);
		$this->db->where('r.is_status', $status);
		$this->db->where('r.tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	// Jumlah transaksi SPJ Baru (Status Entri)
	public function TransaksiSpjBaru($bulan, $ta)
	{
		$this->db->select_sum('r.jumlah');
		$this->db->from('spj AS r');
		$this->db->join('t_periode AS p', 'r.fid_periode=p.id');
		$this->db->where('r.is_status', 'ENTRI');
		$this->db->where('p.id', $bulan);
		$this->db->where('r.tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	// Jumlah transaksi SPJ sudah CAIR
	public function TransaksiSpjCair($bulan, $ta)
	{
		$this->db->select_sum('s.jumlah');
		$this->db->from('spj_payment AS r');
		$this->db->join('spj_riwayat as s', 'r.token=s.token');
		$this->db->join('t_periode AS p', 's.fid_periode=p.id');
		$this->db->where('r.status', 'CAIR');
		$this->db->where('p.id', $bulan);
		$this->db->where('r.tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	public function LimitTransaksiTriwulan($tahun, $is_perubahan)
	{
		$query = $this->db->query("
				SELECT 
					SUM(CASE 
						WHEN FIND_IN_SET('1', periode) 
						OR FIND_IN_SET('2', periode) 
						OR FIND_IN_SET('3', periode) 
						THEN total ELSE 0 END) AS triwulan_1,
					SUM(CASE 
						WHEN FIND_IN_SET('4', periode) 
						OR FIND_IN_SET('5', periode) 
						OR FIND_IN_SET('6', periode) 
						THEN total ELSE 0 END) AS triwulan_2,
					SUM(CASE 
						WHEN FIND_IN_SET('7', periode) 
						OR FIND_IN_SET('8', periode) 
						OR FIND_IN_SET('9', periode) 
						THEN total ELSE 0 END) AS triwulan_3,
					SUM(CASE 
						WHEN FIND_IN_SET('10', periode) 
						OR FIND_IN_SET('11', periode) 
						OR FIND_IN_SET('12', periode) 
						THEN total ELSE 0 END) AS triwulan_4
				FROM t_pagu_limit WHERE tahun = '".$tahun."' AND is_perubahan = '".$is_perubahan."'
			");
		return $query->row();
	}

	public function TransaksiTriwulan($triwulan, $ta)
	{
		$this->db->select_sum('r.jumlah');
		$this->db->from('spj_riwayat as r');
		$this->db->join('t_periode AS p', 'r.fid_periode=p.id');
		$this->db->where_in('p.id', $triwulan);
		$this->db->where('r.is_status', 'APPROVE');
		$this->db->where('r.tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	public function getRealisasiSpjByPart($part, $ta)
	{
		$this->db->select_sum('jumlah');
		$this->db->from('spj_riwayat');
		$this->db->where('entri_by_part', $part);
		$this->db->where('is_status', 'APPROVE');
		$this->db->where('tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	public function getJumlahSpjByPart($part, $status, $ta)
	{
		$this->db->select('id');
		$this->db->from('spj_riwayat');
		$this->db->where('entri_by_part', $part);
		$this->db->where('is_status', $status);
		$this->db->where('tahun', $ta);
		$q = $this->db->get();
		return $q->num_rows();
	}

	public function getJumlahSpjByPartBaru($part, $ta)
	{
		$this->db->select('id');
		$this->db->from('spj');
		$this->db->where('entri_by_part', $part);
		$this->db->where('is_status', 'ENTRI');
		$this->db->where('tahun', $ta);
		$q = $this->db->get();
		return $q->num_rows();
	}

	public function getJumlahSpjByStatusCair($part, $status, $ta)
	{
		$this->db->select('sp.id');
		$this->db->from('spj_payment as sp');
		$this->db->join('spj_riwayat as sr', 'sp.token=sr.token');
		$this->db->where('sr.entri_by_part', $part);
		$this->db->where('sp.status', $status);
		$this->db->where('sp.tahun', $ta);
		$q = $this->db->get();
		return $q->num_rows();
	}

	public function getLimitPagu($uraian_id, $periode_id)
	{
		$this->db->select('total, periode');
		$this->db->from('t_pagu_limit');
		$this->db->where('fid_uraian', $uraian_id);
		$this->db->where("FIND_IN_SET('{$periode_id}', periode) >", 0);
		$q = $this->db->get();
		return $q;
	}


	// -------------------------------- datatable-verifikasi --------------------------//
	// set table
	protected $table = 'spj AS s';
	//set column field database for datatable orderable
	protected $column_order = array(null, 'uraian.kode', null, 's.fid_periode', null, 's.entri_at');
	// default order 
	protected $order = array('s.entri_perbaikan_at' => 'desc', 's.entri_at' => 'desc');
	// default select 
	protected $select_table = array('s.*, part.nama AS nama_part, program.nama AS nama_program, program.kode AS kode_program, kegiatan.nama AS nama_kegiatan, kegiatan.kode AS kode_kegiatan, sub_kegiatan.nama AS nama_sub_kegiatan, sub_kegiatan.kode AS kode_sub_kegiatan, uraian.nama AS nama_uraian, uraian.kode AS kode_uraian');

	private function _datatables()
	{

		$this->db->select($this->select_table, false);
		$this->db->from($this->table);
		$this->db->join('ref_parts AS part', 's.fid_part=part.id');
		$this->db->join('ref_programs AS program', 's.fid_program=program.id');
		$this->db->join('ref_kegiatans AS kegiatan', 's.fid_kegiatan=kegiatan.id');
		$this->db->join('ref_sub_kegiatans AS sub_kegiatan', 's.fid_sub_kegiatan=sub_kegiatan.id');
		$this->db->join('ref_uraians AS uraian', 's.fid_uraian=uraian.id');
		$this->db->where('s.tahun', $this->session->userdata('tahun_anggaran'));
		if ($this->session->userdata('role') === 'VERIFICATOR'):
			$this->db->where_in('is_status', ['VERIFIKASI','VERIFIKASI_ADMIN', 'APPROVE', 'TMS', 'BTL']);
		endif;

		// Pencarian global
		if (!empty($_POST['search']['value'])) {
			$search = strtolower($_POST['search']['value']);
			$this->db->group_start()
				->like('LOWER(uraian.kode)', $search)
				->or_like('LOWER(uraian.nama)', $search)
				->or_like('LOWER(part.nama)', $search)
				->group_end();
		}

		// Pencarian per kolom
		foreach ($_POST['columns'] as $index => $col) {
			if (!empty($col['search']['value'])) {
				$search_term = strtolower($col['search']['value']);
				switch ($index) {
					case 1:
						$this->db->like('LOWER(uraian.kode)', $search_term);
						break;
					case 2:
						$this->db->like('LOWER(uraian.nama)', $search_term);
						break;
					case 4:
						$this->db->like('LOWER(part.nama)', $search_term);
						break;
				}
			}
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function make_datatables()
	{
		$this->_datatables();
		if (@$_POST['length'] != -1)
			$this->db->limit(@$_POST['length'], @$_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function make_count_filtered()
	{
		$this->_datatables();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function make_count_all()
	{
		$this->_datatables();
		return $this->db->count_all_results();
	}
	// -------------------------------- end-datatable --------------------------//

	// ----------------- datatable-verifikasi-selesai --------------------------//

	//set column field database for datatable orderable
	protected $column_order_verifikasi_selesai = array('spj_riwayat.id', 'spj_riwayat.nomor_pembukuan', 'spj_riwayat.kode_uraian', 'spj_riwayat.nama_uraian', 'spj_riwayat.nama_bidang', 'spj_riwayat.fid_periode', 'spj_riwayat.entri_at', 'spj_riwayat.approve_at', 'spj_riwayat.is_status', 'spj_riwayat.jumlah');

	// default order 
	protected $order_verifikasi_selesai = array('spj_riwayat.approve_at' => 'desc');

	private function _datatables_verifikasi_selesai($filter)
	{

		$this->db->select('spj_riwayat.*,t_periode.nama, t_periode.id as periode_id,spj_payment.status');
		$this->db->from('spj_riwayat');
		$this->db->join('t_periode', 'spj_riwayat.fid_periode=t_periode.id');
		$this->db->join('spj_payment', 'spj_riwayat.token=spj_payment.token', 'left');
		$this->db->where('spj_riwayat.tahun', $this->session->userdata('tahun_anggaran'));

		// filter bedasarkan role
		if ($this->session->userdata('role') === 'USER') {
			$this->db->where('spj_riwayat.entri_by_part', $this->session->userdata('part'));
		}

		// filter berdasarkan bidang
		if (!empty($filter['filter_bidang'])) {
			$this->db->where('spj_riwayat.entri_by_part', $filter['filter_bidang']);
		}

		// Pencarian global
		if (!empty($_POST['search']['value'])) {
			$search = strtolower($_POST['search']['value']);
			$this->db->group_start()
				->like('LOWER(spj_riwayat.nomor_pembukuan)', $search)
				->or_like('LOWER(spj_riwayat.kode_uraian)', $search)
				->or_like('LOWER(spj_riwayat.nama_uraian)', $search)
				->or_like('LOWER(spj_riwayat.nama_part)', $search)
				->group_end();
		}

		// Pencarian per kolom
		foreach ($_POST['columns'] as $index => $col) {
			if (!empty($col['search']['value'])) {
				$search_term = strtolower($col['search']['value']);
				switch ($index) {
					case 1:
						$this->db->like('LOWER(spj_riwayat.nomor_pembukuan)', $search_term);
						break;
					case 2:
						$this->db->like('LOWER(spj_riwayat.kode_uraian)', $search_term);
						break;
					case 3:
						$this->db->like('LOWER(spj_riwayat.nama_uraian)', $search_term);
						break;
					case 4: // kolom status
						$this->db->where('LOWER(spj_riwayat.nama_part)', $search_term);
						break;
				}
			}
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order_verifikasi_selesai[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order_verifikasi_selesai)) {
			$order = $this->order_verifikasi_selesai;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function make_datatables_verifikasi_selesai($filter)
	{
		$this->_datatables_verifikasi_selesai($filter);
		if (@$_POST['length'] != -1)
			$this->db->limit(@$_POST['length'], @$_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function make_count_filtered_verifikasi_selesai($filter)
	{
		$this->_datatables_verifikasi_selesai($filter);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function make_count_all_verifikasi_selesai($filter)
	{
		$this->_datatables_verifikasi_selesai($filter);
		return $this->db->count_all_results();
	}
	// -------------------------------- end-datatable --------------------------//


	// ----------------- datatable-penerima-manfaat --------------------------//

	//set column field database for datatable orderable
	protected $column_order_penerima_manfaat = array('rp.id', 'rp.organisasi', 'rp.perorangan');
	// default order 
	protected $order_penerima_manfaat = array('rp.id' => 'desc');

	private function _datatables_penerima_manfaat($token)
	{

		$this->db->select('rp.id, rp.organisasi, rp.perorangan, s.is_status');
		$this->db->from('spj_relasi_publik AS rp');
		$this->db->join('spj as s', 'rp.token=s.token');
		$this->db->where('rp.token', $token);

		// Pencarian global
		if (!empty($_POST['search']['value'])) {
			$search = strtolower($_POST['search']['value']);
			$this->db->group_start()
				->like('LOWER(rp.organisasi)', $search)
				->or_like('LOWER(rp.perorangan)', $search)
				->group_end();
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order_penerima_manfaat[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order_penerima_manfaat)) {
			$order = $this->order_penerima_manfaat;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function make_datatables_penerima_manfaat($token)
	{
		$this->_datatables_penerima_manfaat($token);
		if (@$_POST['length'] != -1)
			$this->db->limit(@$_POST['length'], @$_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function make_count_filtered_penerima_manfaat($token)
	{
		$this->_datatables_penerima_manfaat($token);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function make_count_all_penerima_manfaat($token)
	{
		$this->_datatables_penerima_manfaat($token);
		return $this->db->count_all_results();
	}
	// -------------------------------- end-datatable --------------------------//

	// Monitoring SPJ
	public function getAllParts()
	{
		return $this->db->select('id,nama')->from('ref_parts')->order_by('id', 'asc')->get();
	}

	// get total pagu murni berdasarkan part
	public function getTotalPaguMurniByPart($part = null, $ta)
	{
		$this->db->select_sum('total_pagu_awal');
		$this->db->from('t_pagu');
		if($part !== null)
		{
			$this->db->where('fid_part', $part);
		}
		$this->db->where('tahun', $ta);
		$this->db->where('is_perubahan', '0');
		$q = $this->db->get();
		return $q->row()->total_pagu_awal;
	}

	public function getTotalPaguPerubahanByPart($part = null, $ta, $is_perubahan = '1')
	{
		$this->db->select_sum('total_pagu_awal');
		$this->db->from('t_pagu');
		if($part !== null)
		{
			$this->db->where('fid_part', $part);
		}
		$this->db->where('tahun', $ta);
		$this->db->where('is_perubahan', $is_perubahan);
		$q = $this->db->get();
		return $q->row()->total_pagu_awal;
	}

	// get total realisasi berdasarkan part
	public function getTotalRealisasiByPart($part = null, $filter_tanggal = null, $ta)
	{
		$this->db->select_sum('jumlah');
		$this->db->from('spj_riwayat');
		if($part !== null)
		{
			$this->db->where('entri_by_part', $part);
		}

		if($filter_tanggal !== null)
		{
			$tanggal = explode(' - ', $filter_tanggal);
            $start_date = DateTime::createFromFormat('d/m/Y', $tanggal[0])->format('Y-m-d');
            $end_date = DateTime::createFromFormat('d/m/Y', $tanggal[1])->format('Y-m-d');

			$this->db->where('DATE(approve_at) >=', $start_date);
            $this->db->where('DATE(approve_at) <=', $end_date);
		}

		$this->db->where('is_status', 'APPROVE');
		$this->db->where('tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	// get total realiasi berdasarkan part dana status SPJ (BARU, VERIFIKASI, PENDING, CAIR, TMS)
	public function getTotalRealisasiByPartAndStatus($part, $filter_tanggal = null, $ta, $status)
	{
		$this->db->select_sum('jumlah');
		$this->db->from('spj');
		if($filter_tanggal !== null)
		{
			$tanggal = explode(' - ', $filter_tanggal);
            $start_date = DateTime::createFromFormat('d/m/Y', $tanggal[0])->format('Y-m-d');
            $end_date = DateTime::createFromFormat('d/m/Y', $tanggal[1])->format('Y-m-d');

			if($status === 'ENTRI') {
				$this->db->where('DATE(entri_at) >=', $start_date);
				$this->db->where('DATE(entri_at) <=', $end_date);
			}

			if(in_array($status, ['VERIFIKASI', 'VERIFIKASI_ADMIN'])) {
				$this->db->where('DATE(verify_at) >=', $start_date);
				$this->db->where('DATE(verify_at) <=', $end_date);
			}
			
		}
		$this->db->where('fid_part', $part);
		$this->db->where_in('is_status', $status);
		$this->db->where('tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	public function getTotalRealisasiByPartAndStatusAdmin($part, $filter_tanggal = null, $ta, $is_status)
	{
		$this->db->select_sum('jumlah');
		$this->db->from('spj_riwayat');
		if($filter_tanggal !== null)
		{
			$tanggal = explode(' - ', $filter_tanggal);
            $start_date = DateTime::createFromFormat('d/m/Y', $tanggal[0])->format('Y-m-d');
            $end_date = DateTime::createFromFormat('d/m/Y', $tanggal[1])->format('Y-m-d');


			$this->db->where('DATE(approve_at) >=', $start_date);
            $this->db->where('DATE(approve_at) <=', $end_date);
		}
		$this->db->where('entri_by_part', $part);
		$this->db->where_in('is_status', $is_status);
		$this->db->where('tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	public function getTotalRealisasiByPartAndStatusBendahara($part, $filter_tanggal = null, $ta, $is_status)
	{
		$this->db->select_sum('spj_riwayat.jumlah');
		$this->db->from('spj_riwayat');
		$this->db->join('spj_payment', 'spj_riwayat.token=spj_payment.token');
		if($filter_tanggal !== null)
		{
			$tanggal = explode(' - ', $filter_tanggal);
            $start_date = DateTime::createFromFormat('d/m/Y', $tanggal[0])->format('Y-m-d');
            $end_date = DateTime::createFromFormat('d/m/Y', $tanggal[1])->format('Y-m-d');

			$this->db->where('DATE(spj_riwayat.approve_at) >=', $start_date);
            $this->db->where('DATE(spj_riwayat.approve_at) <=', $end_date);
		}
		$this->db->where('spj_riwayat.entri_by_part', $part);
		$this->db->where_in('spj_payment.status', $is_status);
		$this->db->where('spj_riwayat.tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	public function getRealisasiByPartAndProgram($part = null, $filter_tanggal = null, $program_id, $ta)
	{
		$this->db->select_sum('jumlah');
		$this->db->from('spj');
		if($part !== null)
		{
			$this->db->where('fid_part', $part);
		}

		if($filter_tanggal !== null)
		{
			$tanggal = explode(' - ', $filter_tanggal);
            $start_date = DateTime::createFromFormat('d/m/Y', $tanggal[0])->format('Y-m-d');
            $end_date = DateTime::createFromFormat('d/m/Y', $tanggal[1])->format('Y-m-d');

			$this->db->where('DATE(approve_at) >=', $start_date);
            $this->db->where('DATE(approve_at) <=', $end_date);
		}

		$this->db->where('fid_program', $program_id);
		$this->db->where('is_status', 'SELESAI');
		$this->db->where('tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	public function getRealisasiByPartAndKegiatan($part = null, $filter_tanggal = null,  $kegiatan_id, $ta)
	{
		$this->db->select_sum('jumlah');
		$this->db->from('spj');
		if($part !== null)
		{
			$this->db->where('fid_part', $part);
		}
		
		if($filter_tanggal !== null)
		{
			$tanggal = explode(' - ', $filter_tanggal);
            $start_date = DateTime::createFromFormat('d/m/Y', $tanggal[0])->format('Y-m-d');
            $end_date = DateTime::createFromFormat('d/m/Y', $tanggal[1])->format('Y-m-d');

			$this->db->where('DATE(approve_at) >=', $start_date);
            $this->db->where('DATE(approve_at) <=', $end_date);
		}

		$this->db->where('fid_kegiatan', $kegiatan_id);
		$this->db->where('is_status', 'SELESAI');
		$this->db->where('tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	public function getRealisasiByPartAndSubKegiatan($part = null, $filter_tanggal = null, $sub_kegiatan_id, $ta)
	{
		$this->db->select_sum('jumlah');
		$this->db->from('spj');
		if($part !== null)
		{
			$this->db->where('fid_part', $part);
		}

		if($filter_tanggal !== null)
		{
			$tanggal = explode(' - ', $filter_tanggal);
            $start_date = DateTime::createFromFormat('d/m/Y', $tanggal[0])->format('Y-m-d');
            $end_date = DateTime::createFromFormat('d/m/Y', $tanggal[1])->format('Y-m-d');

			$this->db->where('DATE(approve_at) >=', $start_date);
            $this->db->where('DATE(approve_at) <=', $end_date);
		}

		$this->db->where('fid_sub_kegiatan', $sub_kegiatan_id);
		$this->db->where('is_status', 'SELESAI');
		$this->db->where('tahun', $ta);
		$q = $this->db->get();
		return $q->row()->jumlah;
	}

	public function getListPenerimaManfaat($token)
	{
		$this->db->select('*');
		$this->db->from('spj_relasi_publik');
		$this->db->where('token', $token);
		$q = $this->db->get();
		return $q;
	}

	// ----------------- datatable-rekap-perjadin --------------------------//

	//set column field database for datatable orderable
	protected $column_order_rekap_perjadin = array('t.id', 't.nama_dokumen', 't.bulan', 't.tahun');
	// default order 
	protected $order_rekap_perjadin = array('t.id' => 'desc');

	private function _datatables_rekap_perjadin($filter)
	{

		$this->db->select('t.id, t.nama_dokumen, t.file_path, t.bulan, t.tahun, r.nama AS nama_part, t.is_kunci, t.catatan, t.created_by, t.created_at');
		$this->db->from('t_dokumen_perjadin AS t');
		$this->db->join('ref_parts as r', 't.fid_part=r.id');
		if($this->session->userdata('role') === 'USER') {
			$this->db->where('t.tahun', $this->session->userdata('tahun_anggaran'));
			$this->db->where('t.fid_part', $this->session->userdata('part'));
		}

		if(!empty($filter['filter_bulan'])) {
			$this->db->where('t.bulan', $filter['filter_bulan']);
		}

		if(!empty($filter['filter_bidang'])) {
			$this->db->where('t.fid_part', $filter['filter_bidang']);
		}

		// Pencarian global
		if (!empty($_POST['search']['value'])) {
			$search = strtolower($_POST['search']['value']);
			$this->db->group_start()
				->like('LOWER(t.nama_dokumen)', $search)
				->or_like('LOWER(t.bulan)', $search)
				->group_end();
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order_rekap_perjadin[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order_rekap_perjadin)) {
			$order = $this->order_rekap_perjadin;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function make_datatables_rekap_perjadin($filter)
	{
		$this->_datatables_rekap_perjadin($filter);
		if (@$_POST['length'] != -1)
			$this->db->limit(@$_POST['length'], @$_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function make_count_filtered_rekap_perjadin($filter)
	{
		$this->_datatables_rekap_perjadin($filter);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function make_count_all_rekap_perjadin($filter)
	{
		$this->_datatables_rekap_perjadin($filter);
		return $this->db->count_all_results();
	}
	// -------------------------------- end-datatable --------------------------//
	
	// ----------------- datatable-rekap-pajak --------------------------//

	//set column field database for datatable orderable
	protected $column_order_rekap_pajak = array('t.id', 'r.nama', 't.periode', 't.jenis_dokumen', 't.tahun');
	// default order 
	protected $order_rekap_pajak = array('t.id' => 'desc');

	private function _datatables_rekap_pajak()
	{

		$this->db->select('t.id, t.nama_dokumen, t.jenis_dokumen, t.file_path, t.periode, t.tahun, r.nama AS nama_part, t.is_kunci, t.catatan, t.created_by');
		$this->db->from('t_dokumen_pajak AS t');
		$this->db->join('ref_parts as r', 't.fid_part=r.id');
		if($this->session->userdata('role') === 'USER') {
			$this->db->where('t.tahun', $this->session->userdata('tahun_anggaran'));
			$this->db->where('t.fid_part', $this->session->userdata('part'));
		}

		if(!empty($_POST['filter_bidang'])) {
			$this->db->where('t.fid_part', $_POST['filter_bidang']);
		}

		if(!empty($_POST['filter_periode'])) {
			$this->db->where('t.periode', $_POST['filter_periode']);
		}

		// Pencarian global
		if (!empty($_POST['search']['value'])) {
			$search = strtolower($_POST['search']['value']);
			$this->db->group_start()
				->like('LOWER(t.nama_dokumen)', $search)
				->or_like('LOWER(t.periode)', $search)
				->group_end();
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order_rekap_pajak[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order_rekap_pajak)) {
			$order = $this->order_rekap_pajak;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function make_datatables_rekap_pajak()
	{
		$this->_datatables_rekap_pajak();
		if (@$_POST['length'] != -1)
			$this->db->limit(@$_POST['length'], @$_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function make_count_filtered_rekap_pajak()
	{
		$this->_datatables_rekap_pajak();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function make_count_all_rekap_pajak()
	{
		$this->_datatables_rekap_pajak();
		return $this->db->count_all_results();
	}
	// -------------------------------- end-datatable --------------------------//

}