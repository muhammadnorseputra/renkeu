<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Select2 extends CI_Controller
{

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
    public function __construct()
    {
        parent::__construct();
        cek_session();
        //  CEK USER PRIVILAGES 
        if (!privilages('priv_default')):
            return show_404();
        endif;

        $this->load->model('ModelSelect2', 'select');
    }

    private function children($q)
    {
        $this->load->model('ModelSelect2', 'select');
        $ch = [];
        $db = $this->select->getChildProgram($q);
        foreach ($db->result() as $k) {
            $data['id'] = $k->uraian_id;
            $data['text'] = $k->uraian_kode . " - " . $k->uraian_nama;
            $ch[] = $data;
        }
        return $ch;
    }

    public function ajaxMultiProgram()
    {
        $q = $this->input->post('q');
        $db = $this->db->select('k.*,p.nama AS partnama, p.id AS partid, k.nama as kegiatan_nama')
            ->from('ref_kegiatans AS k')
            ->join('ref_parts AS p', 'k.fid_part=p.id')
            ->where('k.tahun', $this->session->userdata('tahun_anggaran'))
            ->where('p.id', $this->session->userdata('part'))
            ->group_by('p.id')
            ->get();

        if ($db->num_rows() > 0) :
            $group = [];
            // $db_part = $this->crud->get('ref_parts');
            foreach ($db->result() as $row) :
                $data['text'] = $row->partnama;
                // $data['children'] = $this->ch_kegiatan($row->partid, $q);
                $data['children'] = $this->children($q);
                $group[] = $data;
            endforeach;
        else :
            $group[] = ['id' => 0,  'text' => 'Maaf, Kegiatan "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($group);
    }

    public function ajaxKegiatan()
    {
        $search = $this->input->post('searchTerm');
        $refId = $this->input->post('refId');
        $refPart = $this->input->post('refPart');
        $db = $this->select->getKegiatan($refPart, $refId, $search)->result();
        $data = array();
        foreach ($db as $kegiatan) {
            $data[] = array("id" => $kegiatan->id, "text" => $kegiatan->kode . " - " . $kegiatan->nama, "kode" => $kegiatan->kode);
        }
        echo json_encode($data);
    }

    public function ajaxSubKegiatan()
    {
        $search = $this->input->post('searchTerm');
        $refId = $this->input->post('refId');
        $db = $this->select->getSubKegiatan($refId, $search)->result();
        $data = array();
        foreach ($db as $sub) {
            $data[] = array("id" => $sub->id, "text" => $sub->kode . " - " . $sub->nama, "kode" => $sub->kode);
        }
        echo json_encode($data);
    }

    public function ajaxUraianKegiatan()
    {
        $search = $this->input->post('searchTerm');
        $kegiatanId = $this->input->post('kegiatanId');
        $subKegiatanId = $this->input->post('subKegiatanId');
        $db = $this->select->getUraian($kegiatanId, $subKegiatanId, $search)->result();
        $all = array();
        foreach ($db as $u) {
            // $cek_spj_uraianid = $this->select->cekUraianIdBySpj($u->id);
            // if ($cek_spj_uraianid->num_rows() > 0) {
            //     $data['disabled'] = true;
            //     $status = '<span class="fa fa-ban text-danger"></span>';
            // } else {
            //     $data['disabled'] = false;
            //     $status = '';
            // }
            $data['id'] = $u->id;
            $data['text'] = $u->kode . " - " . $u->nama;
            $data['kode'] = $u->kode;
            $all[] = $data;
        }
        echo json_encode($all);
    }
}
