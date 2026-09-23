<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelDatatables extends CI_Model
{

    // ----------------- datatable-pengelolaan-resiko --------------------------//

	//set column field database for datatable orderable
	protected $column_order_pengelolaan_resiko = array(null, 'r.nama', 't.periode', 't.jenis_dokumen', 't.tahun');
	// default order 
	protected $order_pengelolaan_resiko = array('t.id' => 'desc');

	private function _datatables_pengelolaan_resiko()
	{

		$this->db->select('t.id, t.nama_dokumen, t.nama_dokumen_ori, t.file_path, t.is_kunci, t.periode, t.tahun, r.nama AS nama_part, t.catatan, t.created_by, t.is_jenis');
		$this->db->from('t_dokumen_pengelolaan_resiko AS t');
		$this->db->join('ref_parts as r', 't.fid_part=r.id');
        $this->db->where('t.tahun', $this->session->userdata('tahun_anggaran'));

		if($this->session->userdata('role') === 'USER') {
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
			$this->db->order_by($this->column_order_pengelolaan_resiko[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order_pengelolaan_resiko)) {
			$order = $this->order_pengelolaan_resiko;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function make_datatables_pengelolaan_resiko()
	{
		$this->_datatables_pengelolaan_resiko();
		if (@$_POST['length'] != -1)
			$this->db->limit(@$_POST['length'], @$_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function make_count_filtered_pengelolaan_resiko()
	{
		$this->_datatables_pengelolaan_resiko();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function make_count_all_pengelolaan_resiko()
	{
		$this->_datatables_pengelolaan_resiko();
		return $this->db->count_all_results();
	}
	// -------------------------------- end-datatable --------------------------//


	// ----------------- datatable-kinerja-non-pk --------------------------//

	//set column field database for datatable orderable
	protected $column_order_kinerja_non_pk = array(null, 'r.nama_part', 't.periode', 't.tahun');
	// default order 
	protected $order_kinerja_non_pk = array('t.id' => 'desc');

	private function _datatables_kinerja_non_pk()
	{

		$this->db->select('t.id, t.is_jenis, t.is_kunci, t.periode, t.nama_dokumen, t.nama_dokumen_ori, t.file_path, t.tahun, r.nama AS nama_part, t.catatan, t.created_by, t.created_at');
		$this->db->from('t_dokumen_pk AS t');
		$this->db->join('ref_parts as r', 't.fid_part=r.id');
        $this->db->where('t.tahun', $this->session->userdata('tahun_anggaran'));
		$this->db->where('t.is_perubahan', $this->session->userdata('is_perubahan'));

		if($this->session->userdata('role') === 'USER') {
			$this->db->where('t.fid_part', $this->session->userdata('part'));
		}

		if(!empty($_POST['filter_bidang'])) {
			$this->db->where('t.fid_part', $_POST['filter_bidang']);
		}

		// Pencarian global
		if (!empty($_POST['search']['value'])) {
			$search = strtolower($_POST['search']['value']);
			$this->db->group_start()
				->like('LOWER(t.nama_dokumen)', $search)
				->or_like('LOWER(t.tahun)', $search)
				->group_end();
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order_kinerja_non_pk[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order_kinerja_non_pk)) {
			$order = $this->order_kinerja_non_pk;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function make_datatables_kinerja_non_pk()
	{
		$this->_datatables_kinerja_non_pk();
		if (@$_POST['length'] != -1)
			$this->db->limit(@$_POST['length'], @$_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function make_count_filtered_kinerja_non_pk()
	{
		$this->_datatables_kinerja_non_pk();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function make_count_all_kinerja_non_pk()
	{
		$this->_datatables_kinerja_non_pk();
		return $this->db->count_all_results();
	}
	// -------------------------------- end-datatable --------------------------//

	// ----------------- datatable-rekap-verifikasi-all --------------------------//

	protected $column_order_rekap_verifikasi_all = array(null, 'p.nip', 'p.nama_lengkap', 'p.jabatan', 'p.pangkat', 'p.jenis', 'r.nama', 'r.singkatan', 'tw1_count', 'tw2_count', 'tw3_count', 'tw4_count', 'total_count', 'status_label');
	protected $order_rekap_verifikasi_all = array('created_at' => 'desc', 'updated_at' => 'desc');

	private function _datatables_rekap_verifikasi_all()
	{
		$year = $this->session->userdata('tahun_anggaran');

		$this->db->select("p.nip, p.nama_lengkap, p.jabatan, p.pangkat, p.jenis,
			r.nama AS bidang_nama, r.singkatan AS bidang_singkatan,
			SUM(CASE WHEN v.periode='TW1' THEN (v.unggah_kinerja_harian='Y')+(v.target_realisasi='Y')+(v.masalah_tindak_lanjut='Y')+(v.diskusi_kinerja='Y')+(v.data_dukung='Y')+(v.simpulan_capaian='Y') ELSE 0 END) AS tw1_count,
			SUM(CASE WHEN v.periode='TW2' THEN (v.unggah_kinerja_harian='Y')+(v.target_realisasi='Y')+(v.masalah_tindak_lanjut='Y')+(v.diskusi_kinerja='Y')+(v.data_dukung='Y')+(v.simpulan_capaian='Y') ELSE 0 END) AS tw2_count,
			SUM(CASE WHEN v.periode='TW3' THEN (v.unggah_kinerja_harian='Y')+(v.target_realisasi='Y')+(v.masalah_tindak_lanjut='Y')+(v.diskusi_kinerja='Y')+(v.data_dukung='Y')+(v.simpulan_capaian='Y') ELSE 0 END) AS tw3_count,
			SUM(CASE WHEN v.periode='TW4' THEN (v.unggah_kinerja_harian='Y')+(v.target_realisasi='Y')+(v.masalah_tindak_lanjut='Y')+(v.diskusi_kinerja='Y')+(v.data_dukung='Y')+(v.simpulan_capaian='Y') ELSE 0 END) AS tw4_count,
			COALESCE(SUM((v.unggah_kinerja_harian='Y')+(v.target_realisasi='Y')+(v.masalah_tindak_lanjut='Y')+(v.diskusi_kinerja='Y')+(v.data_dukung='Y')+(v.simpulan_capaian='Y')), 0) AS total_count,
			IF(COALESCE(SUM((v.unggah_kinerja_harian='Y')+(v.target_realisasi='Y')+(v.masalah_tindak_lanjut='Y')+(v.diskusi_kinerja='Y')+(v.data_dukung='Y')+(v.simpulan_capaian='Y')), 0) >= 24, 'Lengkap', 'Belum Lengkap') AS status_label,
			MAX(v.created_at) AS created_at, MAX(v.updated_at) AS updated_at");
		$this->db->from('pegawai AS p');
		$this->db->join('ref_parts AS r', 'p.fid_part = r.id', 'left');
		$this->db->join("t_verify_kinerja AS v", "v.nip = p.nip AND v.tahun = '$year'", 'left');

		if ($this->session->userdata('role') === 'USER') {
			$this->db->where('p.fid_part', $this->session->userdata('part'));
		}

		$this->db->group_by('p.nip, p.nama_lengkap, p.jabatan, p.pangkat, p.jenis, r.nama, r.singkatan');

		if (!empty($_POST['search']['value'])) {
			$search = $_POST['search']['value'];
			$this->db->group_start()
				->like('p.nip', $search)
				->or_like('p.nama_lengkap', $search)
				->or_like('p.jabatan', $search)
				->or_like('p.pangkat', $search)
				->or_like('p.jenis', $search)
				->or_like('r.nama', $search)
				->or_like('r.singkatan', $search)
				->group_end();
		}

		if (isset($_POST['order'])) {
			$this->db->order_by($this->column_order_rekap_verifikasi_all[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
			foreach ($this->order_rekap_verifikasi_all as $col => $dir) {
				$this->db->order_by($col, $dir);
			}
		}
	}

	function make_datatables_rekap_verifikasi_all()
	{
		$this->_datatables_rekap_verifikasi_all();
		if (@$_POST['length'] != -1)
			$this->db->limit(@$_POST['length'], @$_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function make_count_filtered_rekap_verifikasi_all()
	{
		$this->_datatables_rekap_verifikasi_all();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function make_count_all_rekap_verifikasi_all()
	{
		$year = $this->session->userdata('tahun_anggaran');
		$this->db->from('pegawai AS p');
		if ($this->session->userdata('role') === 'USER') {
			$this->db->where('p.fid_part', $this->session->userdata('part'));
		}
		return $this->db->count_all_results();
	}
	// -------------------------------- end-datatable --------------------------//

}