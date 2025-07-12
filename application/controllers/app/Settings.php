<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends CI_Controller
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
		if (!privilages('priv_settings')):
			return show_404();
		endif;
	}

	public function index()
	{
		$db_setting = $this->db->order_by('order', 'asc')->get('t_settings');
		$db_periode = $this->db->get('t_periode');
		$data = [
			'title' => 'Settings',
			'content' => 'pages/admin/settings',
			'data' => $db_setting,
			'db_periode' => $db_periode,
			'autoload_js' => [
				'template/backend/vendors/switchery/dist/switchery.min.js',
				'template/custom-js/settings.js'
			],
			'autoload_css' => [
				'template/backend/vendors/switchery/dist/switchery.min.css'
			]
		];
		$this->load->view('layout/app', $data);
	}

	public function ubah($key)
	{
		$row = $this->crud->getWhere('t_settings', ['key' => decrypt_url($key)]);
		$data = [
			'title' => 'Settings - ' . decrypt_url($key),
			'content' => 'pages/admin/settings_ubah',
			'data' => $row->row()
		];
		$this->load->view('layout/app', $data);
	}

	public function updateAll()
	{
		$p = $this->input->post();
		$val = $p['val'];
		$key = $p['key'];

		$data = [];
		foreach ($key as $k => $v) {
			$data[] = [
				'key' => $v,
				'status' => @$val[$v] ? @$val[$v] : 'N'
			];
		}

		$db = $this->crud->updateAll('t_settings', $data, 'key');
		if ($db) {
			redirect(base_url('/app/settings/'), 'refresh');
			return false;
		}
		redirect(base_url('/app/settings/'), 'refresh');
	}

	public function updatePeriode()
	{
		$p = $this->input->post();
		$val = $p['val'];
		$key = $p['key'];

		$data = [];
		foreach ($key as $k => $v) {
			$data[] = [
				'id' => $v,
				'is_open' => @$val[$v] ? @$val[$v] : 'N'
			];
		}

		$db = $this->crud->updateAll('t_periode', $data, 'id');
		if ($db) {
			redirect(base_url('/app/settings/?tab=%23periode'), 'refresh');
			return false;
		}
		redirect(base_url('/app/settings/?tab=%23periode'), 'refresh');
	}

	public function update($key)
	{
		$p = $this->input->post();

		$data = [
			'val' => $p['val'],
			'deskripsi' => $p['desc']
		];

		$whr = [
			'key' => decrypt_url($key)
		];

		$db = $this->crud->update('t_settings', $data, $whr);
		if ($db) {
			redirect(base_url('/app/settings'));
			return false;
		}
		redirect(base_url('/app/settings/ubah/' . $key));
	}

	public function updateWithImage($key)
	{
		$p = $this->input->post();

		$path = './template/assets';
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 1000; // 1MB
		$config['file_ext_tolower'] = TRUE;
		$config['file_name'] = 'logo';
		$config['overwrite'] = TRUE;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('image')) {
			// redirect(base_url('/app/settings/ubah/'. $key));
			$msg = ['valid' => false, 'pesan' => $this->upload->display_errors()];
			echo json_encode($msg);
			return false;
		}

		$data = array('upload_data' => $this->upload->data());
		$image = $data['upload_data']['file_name'];

		$data_insert = [
			'val' => $image,
			'deskripsi' => $p['desc']
		];

		$whr = [
			'key' => decrypt_url($key)
		];

		$db = $this->crud->update('t_settings', $data_insert, $whr);
		if ($db) {
			redirect(base_url('/app/settings'));
			return false;
		}
		redirect(base_url('/app/settings/ubah/' . $key));
	}

	public function statuspagu()
	{
		$is_perbahan = $this->input->post('is_perubahan');
		$this->session->set_userdata([
			'is_perubahan' => $is_perbahan
		]);
		redirect($this->input->post('redirectTo'));
	}
}
