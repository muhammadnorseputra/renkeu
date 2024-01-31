<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Capaian extends CI_Controller {

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
		
		$this->load->model('modeltarget', 'target');
		$this->load->model('modelrealisasi', 'realisasi');
		$this->load->model('modelspj', 'spj');
		
    }
	
	public function index()
	{
		$programs = $this->crud->get('ref_programs');
        $data = [
			'title' => 'Capaian Anggaran & Kinerja',
            'content' => 'pages/anggaran_kinerja/capaian',
			'programs' => $programs
        ];
		$this->load->view('layout/app', $data);
	}
}
