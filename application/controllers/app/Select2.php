<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Select2 extends CI_Controller {

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
        if(!privilages('priv_default')):
            return show_404();
        endif;

        $this->load->model('ModelSelect2', 'select');
    }

	public function ajaxKegiatan()
	{
        $search = $this->input->post('searchTerm');
        $refId = $this->input->post('refId');
        $refPart = $this->input->post('refPart');
        $db = $this->select->getKegiatan($refPart, $refId, $search)->result();
        $data = array();
        foreach ($db as $kegiatan) {
            $data[] = array("id" => $kegiatan->id, "text" => $kegiatan->kode." - ".$kegiatan->nama, "kode" => $kegiatan->kode);
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
            $data[] = array("id" => $sub->id, "text" => $sub->kode." - ".$sub->nama, "kode" => $sub->kode);
        }
        echo json_encode($data);
	}

    public function ajaxUraianKegiatan()
    {
        $search = $this->input->post('searchTerm');
        $kegiatanId = $this->input->post('kegiatanId');
        $subKegiatanId = $this->input->post('subKegiatanId');
        $db = $this->select->getUraian($kegiatanId, $subKegiatanId, $search)->result();
        $data = array();
        foreach ($db as $u) {
            $data[] = array("id" => $u->id, "text" => $u->kode." - ".$u->nama, "kode" => $u->kode);
        }
        echo json_encode($data);
    }
}
