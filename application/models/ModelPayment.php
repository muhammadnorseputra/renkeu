<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ModelPayment extends CI_Model
{
    // ----------------- datatable-verifikasi-selesai --------------------------//

    //set column field database for datatable orderable
    protected $column_order = array('sr.nomor_pembukuan', 'sr.kode_uraian', 'sr.fid_periode', 'sr.approve_at', 'sp.cair_at', 'sp.pending_at', 'sp.status');
    //set column field database for datatable searchable 
    protected $column_search = array('sr.nomor_pembukuan', 'sr.kode_uraian', 'sr.nama_uraian');
    // default order 
    protected $order = array('sp.pending_at' => 'desc');

    private function _datatables()
    {

        $this->db->select('sp.*, sr.nomor_pembukuan, sr.kode_uraian, sr.nama_uraian, sr.nama_part, sr.fid_periode,sr.entri_at,sr.approve_at,sr.entri_by,sr.is_status,sr.jumlah, sr.catatan as catatan_by_verify, sr.nomor_verifikasi, sr.tanggal_verifikasi');
        $this->db->from('spj_payment as sp');
        $this->db->join('spj_riwayat as sr', 'sp.token=sr.token');
        $this->db->where('sp.tahun', $this->session->userdata('tahun_anggaran'));

        // Add filter
        if (in_array($_POST['filter_status'], ['CAIR', 'PERBAIKAN', 'TOLAK'])) {
            $this->db->where('status', $_POST['filter_status']);
        } else {
            $this->db->where_in('status', ['PENDING', 'PENDING - PERBAIKAN']);
        }


        // Search
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

        // Order
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
}
