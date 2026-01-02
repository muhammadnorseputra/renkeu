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

	public function LimitTransaksiTriwulan($tahun)
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
				FROM t_pagu_limit WHERE tahun = $tahun
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
	protected $order = array('s.created_at');
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
		if ($this->session->userdata('role') === 'ADMIN'):
			$this->db->where_in('is_status', ['VERIFIKASI_ADMIN', 'APPROVE', 'TMS', 'BTL']);
		else:
			$this->db->where_in('is_status', ['VERIFIKASI']);
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

	private function _datatables_verifikasi_selesai()
	{

		$this->db->select('spj_riwayat.*,t_periode.nama, t_periode.id as periode_id,spj_payment.status');
		$this->db->from('spj_riwayat');
		$this->db->join('t_periode', 'spj_riwayat.fid_periode=t_periode.id');
		$this->db->join('spj_payment', 'spj_riwayat.token=spj_payment.token', 'left');
		$this->db->where('spj_riwayat.tahun', $this->session->userdata('tahun_anggaran'));
		if ($this->session->userdata('role') === 'USER') {
			$this->db->where('entri_by_part', $this->session->userdata('part'));
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

	function make_datatables_verifikasi_selesai()
	{
		$this->_datatables_verifikasi_selesai();
		if (@$_POST['length'] != -1)
			$this->db->limit(@$_POST['length'], @$_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function make_count_filtered_verifikasi_selesai()
	{
		$this->_datatables_verifikasi_selesai();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function make_count_all_verifikasi_selesai()
	{
		$this->_datatables_verifikasi_selesai();
		return $this->db->count_all_results();
	}
	// -------------------------------- end-datatable --------------------------//
}
