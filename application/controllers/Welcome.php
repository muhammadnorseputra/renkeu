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
		$data = [
			'content' => 'frontend/home'
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
