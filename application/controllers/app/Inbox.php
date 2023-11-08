<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox extends CI_Controller {

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
        $data = [
			'title' => 'Inboxs',
            'content' => 'pages/inbox',
        ];
		$this->load->view('layout/app', $data);
	}

	public function list()
	{
		
		$notify_private = $this->notify->getNotify('t_notify', ['mode' => 'PRIVATE', 'is_aktif' => 'Y', 'to' => decrypt_url($this->session->userdata('user_id'))]);
		$notify_private_all = $this->notify->getNotify('t_notify', ['mode' => 'PRIVATE_ALL', 'is_aktif' => 'Y']);
		$notify_all = array_merge($notify_private->result(), $notify_private_all->result());
		sort($notify_all);
		$notify_count = count($notify_all);

		if($notify_count > 0) {
			$data = [];
			$html = '';
			foreach($notify_all as $row):
				$jenis = ($row->mode === 'PRIVATE') ? 'Message' : 'Pengumuman';
				$color = ($row->mode === 'PRIVATE') ? '' : 'text-success';
				$html .= '
				<a href="javascript:(0)" onclick="MailDetail('.$row->id.')">
					<div class="mail_list '.$color.'">
						<div class="left">
							<i class="fa fa-star"></i>
						</div>
						<div class="right">
							<h3>'.$jenis.'<small>'.jamServer($row->created_at).'</small></h3>
							<p>'.substr($row->message,0,50).' ...</p>
						</div>
					</div>
				</a>
				';
				$data = [
					'html' => $html,
					'status' => "{$notify_count} Data Ditemukan",
					'valid' => true
				];
			endforeach;
		} else {
			$data = ['html' => 'Data Kosong', 'status' => "Data Tidak Ditemukan", 'valid' => false];
		}
		echo json_encode($data);
	}

	public function mailById($id)
	{
		$db = $this->db->get_where('t_notify', ['id' => $id]);
		$html = "Mail <b>{$db->num_rows()}</b> Tidak Ditemukan, silahkan pilih pada daftar inbox";
		if($db->num_rows() > 0) {
			$row = $db->row();

			$mailDate = jamServer($row->created_at).' '.TanggalIndo($row->created_at);
			$mailJenis = ($row->mode === 'PRIVATE') ? 'Message' : 'Pengumuman';

			$html = "<div class='mail_heading row'>
						<div class='col-md-8'>
							<div class='btn-group'>
								<button class='btn btn-sm btn-default' type='button' data-placement='top' data-toggle='tooltip' data-original-title='Trash'><i class='fa fa-trash-o'></i></button>
							</div>
						</div>
						<div class='col-md-4 text-right'>
							<p class='date'> {$mailDate}</p>
						</div>
						<div class='col-md-12'>
							<h4>{$mailJenis}</h4>
						</div>
					</div>
					<div class='view-mail'>
						{$row->message}
					</div>";
		}
		echo json_encode($html);
	}
}
