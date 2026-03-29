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
	protected $column_order_kinerja_non_pk = array(null, 'r.nama_part', 't.tahun');
	// default order 
	protected $order_kinerja_non_pk = array('t.id' => 'desc');

	private function _datatables_kinerja_non_pk()
	{

		$this->db->select('t.id, t.is_jenis, t.nama_dokumen, t.file_path, t.tahun, r.nama AS nama_part, t.created_by, t.created_at');
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

}