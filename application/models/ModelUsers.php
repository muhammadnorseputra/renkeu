<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelUsers extends CI_Model {
	// set table
	protected $table = 't_users';
	//set column field database for datatable orderable
	protected $column_order = array(null, null,null,'role',null,null,null);
	//set column field database for datatable searchable 
	protected $column_search = array('nama');
	// default order 
	protected $order = array('id' => 'desc');
	// default select 
	protected $select_table = array('*');
  
	private function _datatables()
	{
  
	$this->db->select($this->select_table);
	$this->db->from($this->table);
	  
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

	public function profile()
	{
		return $this->db->get('t_users');
	}
    public function profile_id($user_id)
	{
		return $this->db->get_where('t_users', ['id' => decrypt_url($user_id)]);
	}
	public function profile_username($user_name)
	{
		return $this->db->get_where('t_users', ['username' => $user_name]);
	}
	public function part_detail($partId) {
		return $this->db->get_where('ref_parts', ['id' => $partId])->row()->nama;
	}
    public function update($data,$whr)
	{
        $result= $this->db->where($whr)->update('t_users',$data);
        return $result;
    }
    public function update_pwd($tbl,$data,$whr)
    {
    	return $this->db->where($whr)->update($tbl, $data);
    }

	public function get_privilages($user_id)
	{
		$q = $this->db->get_where('t_privilages', ['fid_user' => decrypt_url($user_id)]);
		if($q->num_rows() > 0) {
			$query = $q->row_array();
		} else {
			$query = 0;
		}
		return $query;
	}

	public function cek_privilages($uid)
    {
      $q= $this->db->get_where('t_privilages', ['fid_user' => $uid]);
      if($q->num_rows() > 0)
      {
        $r = $q->row();
      } else {
        $r = null;
      }
      return $r;
    }

	public function insert($tbl,$data)
    {
      return $this->db->insert($tbl, $data);;
    }

	public function check_username_exists($username)
	{
		$query = $this->db->get_where('t_users', ['username' => $username]);
		if(empty($query->row())){
		return true;
		} else {
		return false;
		}

	}

	public function get_privilages_count($tbl,$user_id)
	{
		$q = $this->db->get_where($tbl, ['fid_user' => $user_id]);
		return $q;
	}

	public function update_tbl($tbl, $data,$whr)
	{
		$result= $this->db->where($whr)->update($tbl,$data);
		return $result;
	}
}