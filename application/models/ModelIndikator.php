<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelIndikator extends CI_Model
{
    // set table
    protected $table = 'ref_indikators as i';
    //set column field database for datatable orderable
    protected $column_order = array('id', 'nama', 'fid_periode', 'fid_jenis_indikator');
    //set column field database for datatable searchable 
    protected $column_search = array('i.nama');
    // default order 
    protected $order = array('id' => 'desc');
    // default select 
    protected $select_table = array(
        'i.*',
        'ji.nama as nama_jenis_indikator',
        'ji.color',
        't.nama as nama_tujuan',
        's.nama as nama_sasaran',
        'p.nama as nama_program',
        'k.nama as nama_kegiatan',
        'sk.nama as nama_sub_kegiatan'
    );

    private function _datatables()
    {

        $this->db->select($this->select_table);
        $this->db->from($this->table);
        $this->db->join('ref_jenis_indikators as ji', 'i.fid_jenis_indikator=ji.id', 'left');
        $this->db->join('ref_tujuan as t', 'i.fid_tujuan=t.id', 'left');
        $this->db->join('ref_sasaran as s', 'i.fid_sasaran=s.id', 'left');
        $this->db->join('ref_programs as p', 'i.fid_program=p.id', 'left');
        $this->db->join('ref_kegiatans as k', 'i.fid_kegiatan=k.id', 'left');
        $this->db->join('ref_sub_kegiatans as sk', 'i.fid_sub_kegiatan=sk.id', 'left');
        $this->db->where('i.fid_part', $this->session->userdata('part'));
        $this->db->where('fid_periode', $_POST['periode']);
        $this->db->where('i.tahun', $this->session->userdata('tahun_anggaran'));

        if (isset($_POST['type']) && $_POST['type'] === 'Tujuan') {
            return $this->db->where('i.fid_tujuan !=', null);
        }

        if (isset($_POST['type']) && $_POST['type'] === 'Sasaran') {
            return $this->db->where('i.fid_sasaran !=', null);
        }

        if (isset($_POST['type']) && $_POST['type'] === 'Program') {
            return $this->db->where('i.fid_program !=', null);
        }

        if (isset($_POST['type']) && $_POST['type'] === 'Kegiatan') {
            return $this->db->where('i.fid_kegiatan !=', null);
        }

        if (isset($_POST['type']) && $_POST['type'] === 'SubKegiatan') {
            return $this->db->where('i.fid_sub_kegiatan !=', null);
        }

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
        $this->_datatables();
        return $this->db->count_all_results();
    }
    // -------------------------------- end-datatable --------------------------//

    protected $model = 'ref_indikators'; // ganti sesuai nama tabel kamu

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->model);
    }

    public function getReferensiTujuan($id)
    {
        return $this->db->get_where('ref_tujuan', ['id' => $id]);
    }

    public function getReferensiSasaran($id)
    {
        return $this->db->get_where('ref_sasaran', ['id' => $id]);
    }

    public function getReferensiProgram($id)
    {
        return $this->db->get_where('ref_programs', ['id' => $id]);
    }

    public function getReferensiKegiatan($id)
    {
        return $this->db->get_where('ref_kegiatans', ['id' => $id]);
    }

    public function getReferensiSubKegiatan($id)
    {
        return $this->db->get_where('ref_sub_kegiatans', ['id' => $id]);
    }

    public function getJenisIndikator($id)
    {
        return $this->db->get_where('ref_jenis_indikators', ['id' => $id]);
    }
}
