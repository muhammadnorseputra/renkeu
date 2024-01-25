<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelSpj extends CI_Model {
	public function getPeriode()
	{
		$this->db->select('*');
		$this->db->from('t_periode');
		$q = $this->db->get();
		return $q;
	}
	public function inbox()
    {
        $this->db->select('s.*, part.nama AS nama_part, program.nama AS nama_program, program.kode AS kode_program, kegiatan.nama AS nama_kegiatan, kegiatan.kode AS kode_kegiatan, sub_kegiatan.nama AS nama_sub_kegiatan, sub_kegiatan.kode AS kode_sub_kegiatan');
        $this->db->from('spj AS s');
        $this->db->join('ref_parts AS part', 's.fid_part=part.id');
        $this->db->join('ref_programs AS program', 's.fid_program=program.id');
        $this->db->join('ref_kegiatans AS kegiatan', 's.fid_kegiatan=kegiatan.id');
        $this->db->join('ref_sub_kegiatans AS sub_kegiatan', 's.fid_sub_kegiatan=sub_kegiatan.id');
        $this->db->where('s.entri_by_part', $this->session->userdata('part'));
		$this->db->where('s.is_status !=', 'SELESAI');
        $q = $this->db->get();
        return $q;
    }

    public function detail($whr)
    {
        $this->db->select('s.*, part.nama AS nama_part, program.nama AS nama_program, program.kode AS kode_program, kegiatan.nama AS nama_kegiatan, kegiatan.kode AS kode_kegiatan, sub_kegiatan.nama AS nama_sub_kegiatan, sub_kegiatan.kode AS kode_sub_kegiatan');
        $this->db->from('spj AS s');
        $this->db->join('ref_parts AS part', 's.fid_part=part.id');
        $this->db->join('ref_programs AS program', 's.fid_program=program.id');
        $this->db->join('ref_kegiatans AS kegiatan', 's.fid_kegiatan=kegiatan.id');
        $this->db->join('ref_sub_kegiatans AS sub_kegiatan', 's.fid_sub_kegiatan=sub_kegiatan.id');
        $this->db->where($whr);
        $q = $this->db->get();
        return $q;
    }

	public function riwayat()
    {
        $q = $this->db->get('spj_riwayat');
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

	// -------------------------------- datatable-verifikasi --------------------------//
    // set table
	protected $table = 'spj AS s';
	//set column field database for datatable orderable
	protected $column_order = array(null);
	//set column field database for datatable searchable 
	protected $column_search = array('kegiatan.kode');
	// default order 
	protected $order = array('s.id' => 'desc');
	// default select 
	protected $select_table = array('s.*, part.nama AS nama_part, program.nama AS nama_program, program.kode AS kode_program, kegiatan.nama AS nama_kegiatan, kegiatan.kode AS kode_kegiatan, sub_kegiatan.nama AS nama_sub_kegiatan, sub_kegiatan.kode AS kode_sub_kegiatan');
  
	private function _datatables()
	{
  
	$this->db->select($this->select_table, false);
	$this->db->from($this->table);
    $this->db->join('ref_parts AS part', 's.fid_part=part.id');
    $this->db->join('ref_programs AS program', 's.fid_program=program.id');
    $this->db->join('ref_kegiatans AS kegiatan', 's.fid_kegiatan=kegiatan.id');
    $this->db->join('ref_sub_kegiatans AS sub_kegiatan', 's.fid_sub_kegiatan=sub_kegiatan.id');
	if($this->session->userdata('role') === 'ADMIN'):
	$this->db->where_in('is_status', ['VERIFIKASI_ADMIN','APPROVE','TMS','BTL']);
	else:
	$this->db->where_in('is_status', ['VERIFIKASI']);
	endif;
	  $i = 0;
  
	  foreach ($this->column_search as $item) // loop column 
	  {
		if (@$_POST['search']['value']) // if datatable send POST for search
		{
  
		  if ($i === 0) // first loop
		  {
			$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
			$this->db->like($item, $_POST['search']['value']);
		  } else {
			$this->db->or_like($item, $_POST['search']['value']);
		  }
  
		  if (count($this->column_search) - 1 == $i) //last loop
			$this->db->group_end(); //close bracket
		}
		$i++;
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
	  $this->db->from($this->table);
	  return $this->db->count_all_results();
	}
	// -------------------------------- end-datatable --------------------------//

	// ----------------- datatable-verifikasi-selesai --------------------------//
	
	//set column field database for datatable orderable
	protected $column_order_verifikasi_selesai = array(null);
	//set column field database for datatable searchable 
	protected $column_search_verifikasi_selesai = array('kegiatan.kode');
	// default order 
	protected $order_verifikasi_selesai = array('id' => 'desc');

	private function _datatables_verifikasi_selesai()
	{
  
	$this->db->select('*');
	$this->db->from('spj_riwayat');
	if($this->session->userdata('role') != 'VERIFICATOR' && $this->session->userdata('role') != 'ADMIN' && $this->session->userdata('role') != 'SUPER_ADMIN' && $this->session->userdata('role') != 'SUPER_USER') {
		$this->db->where('entri_by_part', $this->session->userdata('part'));
	}

	  $i = 0;
  
	  foreach ($this->column_search_verifikasi_selesai as $item) // loop column 
	  {
		if (@$_POST['search']['value']) // if datatable send POST for search
		{
  
		  if ($i === 0) // first loop
		  {
			$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
			$this->db->like($item, $_POST['search']['value']);
		  } else {
			$this->db->or_like($item, $_POST['search']['value']);
		  }
  
		  if (count($this->column_search_verifikasi_selesai) - 1 == $i) //last loop
			$this->db->group_end(); //close bracket
		}
		$i++;
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
	  $this->db->from('spj_riwayat');
	  return $this->db->count_all_results();
	}
	// -------------------------------- end-datatable --------------------------//
}