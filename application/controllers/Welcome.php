<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	public function index()
	{
		$jml_pegawai = $this->crud->getWhere('t_users', ['is_valid' => '1'])->num_rows();
		$jml_bidang = $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN'])->num_rows();
		$tahun_row = $this->crud->getWhere('t_settings', ['key' => 'tahun_anggaran', 'status' => 'Y'])->row();
		$tahun = $tahun_row ? $tahun_row->val : date('Y');
		
		$team = $this->db->query("SELECT u.nama, u.role, u.pic, r.nama AS bidang, r.singkatan FROM t_users u LEFT JOIN ref_parts r ON u.fid_part = r.id WHERE u.is_valid='1' AND u.is_block='N' ORDER BY FIELD(u.role,'SUPER_ADMIN','ADMIN','SUPER_USER','VERIFICATOR','USER','ARSIP_USER','BENDAHARA'), u.nama")->result();
		$data = [
			'content' => 'frontend/home',
			'jml_pegawai' => $jml_pegawai,
			'jml_bidang' => $jml_bidang,
			'tahun_anggaran' => $tahun,
			'team' => $team
		];
		$this->load->view('landingpage', $data);
	}

	public function page($path)
	{
		if($path === 'about') {
			$data = [
				'content' => 'frontend/about'
			];
		} elseif($path === 'featured') {
			$data = [
				'content' => 'frontend/featured'
			];
		} elseif($path === 'screenshot') {
			$data = [
				'content' => 'frontend/screenshot'
			];
		} elseif($path === 'team') {
			$data = [
				'content' => 'frontend/team'
			];
		} elseif($path === 'contact') {
			$data = [
				'content' => 'frontend/contact'
			];
		} else {
			$data = [
				'content' => 'errors'
			];
		}

		return $this->load->view('landingpage', $data);
	}
}
