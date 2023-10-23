<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        cek_session();
        $this->load->model('ModelMessages', 'messages');
    }
	
	public function index()
	{
        $data = [
			'title' => 'Messages - Emonev App',
            'content' => 'pages/admin/messages',
            'autoload_js' => [
				'template/backend/vendors/datatables.net/js/jquery.dataTables.min.js',
				'template/backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'template/backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js',
                'template/backend/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',
                'template/backend/vendors/datatables.net-buttons/js/dataTables.buttons.min.js',
                'template/backend/vendors/switchery/dist/switchery.min.js',
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js'
			],
			'autoload_css' => [
				'template/backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'template/backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css',
                'template/backend/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',
                'template/backend/vendors/switchery/dist/switchery.min.css',
                'template/backend/vendors/select2/dist/css/select2.min.css'
			]
        ];
		$this->load->view('layout/app', $data);
	}

    public function ajax()
    {
        $db = $this->messages->make_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($db as $r) {
            $button = '<div class="dropdown">
                            <button class="btn btn-sm btn-icon-only text-dark bg-white" type="button" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                              <button id="btnEdit" data-uid="'.$r->id.'" data-url="'.base_url("app/messages/edit").'" class="dropdown-item d-flex justify-content-between">
                                Edit <i class="fa fa-edit"></i> 
                              </button>
                              <button id="btnHapus" data-uid="'.$r->id.'" data-url="'.base_url("app/messages/delete").'" class="dropdown-item d-flex justify-content-between">
                                Hapus <i class="fa fa-trash text-danger"></i> 
                              </button>
                            </div>
                        </div>';
            $user = $this->users->profile_id(encrypt_url($r->to))->row();
            // $to = $r->mode !== 'GLOBAL' ? 'ALL' : ucwords($user->nama);
            if($r->mode === 'GLOBAL') {
                $to = 'ALL (GLOBAL)';
            } elseif ($r->mode === 'PRIVATE_ALL') {
                $to = 'ALL';
            }else {
                $to = ucwords($user->nama);
            }

            if($r->mode === 'GLOBAL' || $r->mode === 'PRIVATE_ALL') {
                $mode = '<i class="fa fa-users text-success"></i>';
            } else {
                $mode = '<i class="fa fa-user"></i>';
            }

            $dateat = jamServer($r->created_at) .' | '. TanggalIndo($r->created_at);
            
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $r->is_aktif === 'Y' ? 'Published' : 'Unpublish';
            $row[] = $mode;
            $row[] = $to;
            $row[] = limitText($r->message,100) . "<br/>".$dateat;
            $row[] = $button;

            $data[] = $row;
        }

        $output = array(
                "draw" => @$_POST['draw'],
                "recordsTotal" => $this->messages->make_count_all(),
                "recordsFiltered" => $this->messages->make_count_filtered(),
                "data" => $data,
            );
        //output to json format
        echo json_encode($output);
    }

    public function insert()
    {
        $p = $this->input->post();
        $aktif = isset($p['aktif']) ? $p['aktif'] : 'N';
        $user = isset($p['user']) ? $p['user'] : null;

        $data = [
            'type' => $p['type'],
            'to' => $user,
            'mode' => $p['mode'],
            'message' => $p['message'],
            'is_aktif' => $aktif
        ];
        $db = $this->crud->insert('t_notify', $data);
        if($db) {
            $msg = ['status' => 200, 'pesan' => 'Notify berhasil ditambahkan'];
        } else {
            $msg = ['status' => 401, 'pesan' => 'Notify gagal ditambahkan'];
        }
        echo json_encode($msg);
    }

    public function delete() {
        $id = $this->input->post('id');
        $db = $this->crud->deleteWhere('t_notify', ['id' => $id]);
        if($db) {
            $msg = ['status' => 200, 'pesan' => 'Notify berhasil dihapus'];
        } else {
            $msg = ['status' => 401, 'pesan' => 'Notify gagal dihapus'];
        }
        echo json_encode($msg);
    }

}
