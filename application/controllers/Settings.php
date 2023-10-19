<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

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
    }
	
	public function index()
	{
        return show_404();
	}

	public function updatevalue() {
		$i = $this->input->post();
		$whr = [
			'key' => $i['keyword']
		];
		$data = [
			'val' => $i['value']
		];
		$db = $this->db->update('t_settings', $data, $whr);
		if($db) {
			$msg = ['valid' => true, 'value' => $data];
		} else {
			$msg = ['valid' => false, 'value' => $data];
		}
		echo json_encode($msg);
	}
}
