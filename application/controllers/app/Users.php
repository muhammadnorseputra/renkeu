<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

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
        if(!privilages('priv_users')):
            return show_404();
        endif;
    }
	
	public function index()
	{	
        $data = [
			'title' => 'Users',
            'content' => 'pages/admin/users',
			'autoload_js' => [
				'template/backend/vendors/datatables.net/js/jquery.dataTables.min.js',
				'template/backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
				'template/backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js',
                'template/backend/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',
                'template/backend/vendors/datatables.net-buttons/js/dataTables.buttons.min.js'
			],
			'autoload_css' => [
				'template/backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
				'template/backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css',
                'template/backend/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css'
			]
        ];
		$this->load->view('layout/app', $data);
	}

    public function getAll()
    {
        $q = $this->input->post('q');

        
        if(!isset($q) || $q === "") {
            echo json_encode([['id' => 0,  'text' => 'Silahkan ketikan username atau nama pengguna']]);
            return false;
        }

        $db = $this->crud->getLikesWithOr('t_users', ['username' => $q, 'nama' => $q]);
        $all = [];
        if($db->num_rows() > 0):
            foreach($db->result() as $row):
                
                if($row->username === $this->session->userdata('user_name')) {
                    $data['disabled'] = true;
                }

                $data['id'] = $row->id;
                $data['text'] = $row->nama." (".$row->username.")";
                $data['picture'] = $row->pic;
                $data['job'] = $row->jobdesk;
                $all[] = $data;
            endforeach;
        else:
            $all[] = ['id' => 0,  'text' => 'Maaf, user tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }   

	public function role($id)
    {
        $row = $this->users->profile_id(encrypt_url($id))->row();
        $role_name = $row->role;
        if($role_name == 'SUPER_USER'):
            $role_color = '<span class="badge badge-success">'.$role_name.'</span>';
        elseif($role_name == 'SUPER_ADMIN'):
            $role_color = '<span class="badge badge-danger">'.$role_name.'</span>';
        elseif($role_name == 'ADMIN'):
            $role_color = '<span class="badge badge-info">'.$role_name.'</span>';
        elseif($role_name == 'USER'):
            $role_color = '<span class="badge badge-dark">'.$role_name.'</span>';
        elseif($role_name == 'VERIFICATOR'):
            $role_color = '<span class="badge badge-warning">'.$role_name.'</span>';
        else:
            $role_color = '<span class="badge badge-default">'.$role_name.'</span>';
        endif;
        return $role_color;
    }

	public function ajax_users()
    {
        $db = $this->users->make_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($db as $r) {
            $pic = "<img src='".base_url('template/assets/picture_akun/'.$r->pic.'?time='.date('H:i:s'))."' width='30' class='rounded'>";
            if($r->is_block == 'N'):
                $btn_is_block = '<a id="btn-block" data-val="Y" data-uid="'.encrypt_url($r->id).'" data-href="'.base_url('/app/users/update_status').'" href="!#is_block" class="dropdown-item d-flex justify-content-between">
                                    Non Active <i class="fa fa-ban text-danger"></i>
                                 </a>';
            else:
                $btn_is_block = '<a id="btn-block" data-val="N" data-uid="'.encrypt_url($r->id).'" data-href="'.base_url('/app/users/update_status').'" href="!#is_block" class="dropdown-item d-flex justify-content-between">
                                    Active <i class="fa fa-check-circle text-success"></i>
                                 </a>';
            endif;
            if($r->is_restricted == 'N'):
                $btn_is_restricted = '<a id="btn-restricted" data-val="Y" data-uid="'.encrypt_url($r->id).'" class="dropdown-item d-flex justify-content-between" data-href="'.base_url('/app/users/update_status/restricted').'" href="!#is_restrected">
                                        Restrected <i class="fa fa-exclamation-triangle text-danger"></i>
                                      </a>';
            else:
                $btn_is_restricted = '<a id="btn-restricted" data-val="N" data-uid="'.encrypt_url($r->id).'" class="dropdown-item d-flex justify-content-between" data-href="'.base_url('/app/users/update_status/restricted').'" href="!#is_restrected">
                                        Off Restrected <i class="fa fa-exclamation-triangle text-success"></i>
                                      </a>';
            endif;
            $button = '<div class="dropdown">
                            <button class="btn btn-sm btn-icon-only text-dark bg-white" type="button" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                              '.$btn_is_block.'
                              '.$btn_is_restricted.'
                              <a class="dropdown-item d-flex justify-content-between" href="'.base_url("/app/users/u/".encrypt_url($r->id)).'">
                                Edit <i class="fa fa-edit small"></i> 
                              </a>
                              <a class="dropdown-item d-flex justify-content-between" href="'.base_url("/app/users/privilages/".encrypt_url($r->id)).'">
                                Privilages <i class="fa fa-unlock-alt"></i>
                              </a>
                              <a id="resspwd" data-path="users/resspwd/'.encrypt_url($r->id).'" data-uid="'.encrypt_url($r->id).'" class="dropdown-item d-flex justify-content-between" href="!#resspwd">
                                Reset Password <i class="fa fa-key text-warning"></i>
                              </a>
                            </div>
                        </div>';
            $is_block = $r->is_block == 'Y' ? '<i class="fa fa-check-circle text-success"></i>' : '<i class="fa fa-close text-danger"></i>';
            $is_restrected = $r->is_restricted == 'Y' ? '<i class="fa fa-check-circle text-success"></i>' : '<i class="fa fa-close text-danger"></i>';
            // $check_in = '<span class="text-sm">'.jamServer($r->check_in).' | '.TanggalIndo($r->check_in).'</span>';
            // $check_out = '<span class="text-sm">'.jamServer($r->check_out).' | '.TanggalIndo($r->check_out).'</span>';
            $nama_bidang = @$this->users->part_detail($r->fid_part) != null ? @$this->users->part_detail($r->fid_part) : '';

            $no++;
            $row = array();
            $row[] = $pic;
            $row[] = "<b>".ucwords($r->nama)."</b><br>".$nama_bidang;
            $row[] = $r->username;
            $row[] = $this->role($r->id);
            $row[] = $is_block;
            $row[] = $is_restrected;
            $row[] = $button;

            $data[] = $row;
        }

        $output = array(
                "draw" => @$_POST['draw'],
                "recordsTotal" => $this->users->make_count_all(),
                "recordsFiltered" => $this->users->make_count_filtered(),
                "data" => $data,
            );
        //output to json format
        echo json_encode($output);
    }

    public function new()
    {
        $data = [
            'title' => 'User Baru',
            'content' => 'pages/admin/users_baru'
        ];
        $this->load->view('layout/app', $data); 
    }

    // Check if username exists
    public function check_username_exists($username){
        $this->form_validation->set_message('check_username_exists', 'Username Sudah dipakai. Silahkan gunakan username lain');
         if($this->users->check_username_exists($username)){
             return true;
         } else {
             return false;
         }
    }

    public function insert()
    {
        // Req post
        $p = $this->input->post();
        
        // Valid Form
        // $this->form_validation->set_rules('photo', 'Photo', 'required');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|callback_check_username_exists');
        $this->form_validation->set_rules('role', 'Role', 'required');
        $this->form_validation->set_rules('pwd', 'Password', 'trim|required|min_length[4]|max_length[12]');

        if ($this->form_validation->run() == false) {
            $msg = ['valid' => false, 'pesan' => validation_errors()];
        } else {
            // Config Image
            $user_nama = strtolower($p['username']);
            $path = './template/assets/picture_akun';
            $config['upload_path'] = $path;  
            $config['allowed_types'] = 'jpg|jpeg|png'; 
            $config['max_size'] = 1000; // 1MB
            $config['file_ext_tolower'] = TRUE;
            $config['file_name'] = $user_nama;
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config); 
            if(!$this->upload->do_upload('photo'))  
            {  
                $msg = ['valid' => false, 'pesan' => $this->upload->display_errors()];  
            } 
            else {
                $data = array('upload_data' => $this->upload->data());
                $image= $data['upload_data']['file_name'];
                $data_insert = [
                    'pic' => $image,
                    'nama' => $p['nama'],
                    'username' => $p['username'],
                    'nip' => $p['nip'],
                    'nohp' => $p['nohp'],
                    'password' => sha1($p['pwd']),
                    'jobdesk' => $p['jobdesk'],
                    'role' => $p['role'],
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $db = $this->users->insert('t_users', $data_insert);
                if($db)
                {
                 $row = $this->users->profile_username($data_insert['username'])->row();
                 $msg = ['valid' => true, 'pesan' => 'User baru berhasil ditambahkan.', 'redirectTo' => base_url('app/users/privilages/'.encrypt_url($row->id))];
                } else {
                    $msg = ['valid' => false, 'pesan' => 'User Gagal Ditambahkan, server tidak meresponse'];  
                }
            }
        }
        echo json_encode($msg);
    }
    
    public function update_status($ch='')
    {
        $p = $this->input->post();
        $whr = ['id' => decrypt_url($p['uid'])];
        if($ch === 'restricted') {
            $data = ['is_restricted' => $p['status']];
        } else {
            $data = ['is_block' => $p['status']];
        }
        $db = $this->users->update_tbl('t_users',$data,$whr);
        if($db)
        {
            $valid = ['valid' => true];
        } else {
            $valid = ['valid' => false];
        }
        echo json_encode($valid);
    }

    public function privilages($uid)
    {
        $data = [
            'title' => 'Privilages',
            'content' => 'pages/admin/users_privilages',
            'uid' => decrypt_url($uid),
            'user' => $this->users->profile_id($uid)->row(),
            'autoload_css' => [
                'template/backend/vendors/switchery/dist/switchery.min.css'
            ],
            'autoload_js' => [
                'template/backend/vendors/switchery/dist/switchery.min.js'
            ]
        ];

        $this->load->view('layout/app', $data); 
    }

    public function privilages_update()
    {
        $p = $this->input->post();
        $uid = decrypt_url($p['uid']);
        $type = $this->input->post('f_type');

        if($type === 'privilage')
        {
            $data = [
                'fid_user' => $uid,
                'priv_default' => !empty($p['priv_default']) ? $p['priv_default'] : "N",
                'priv_users' => !empty($p['priv_users']) ? $p['priv_users'] : "N",
                'priv_settings' => !empty($p['priv_settings']) ? $p['priv_settings'] : "N",
                'priv_notify' => !empty($p['priv_notify']) ? $p['priv_notify'] : "N",
                'priv_programs' => !empty($p['priv_programs']) ? $p['priv_programs'] : "N",
                'priv_verifikasi' => !empty($p['priv_verifikasi']) ? $p['priv_verifikasi'] : "N",
                'priv_approve' => !empty($p['priv_approve']) ? $p['priv_approve'] : "N",
                'priv_spj' => !empty($p['priv_spj']) ? $p['priv_spj'] : "N",
                'priv_riwayat_spj' => !empty($p['priv_riwayat_spj']) ? $p['priv_riwayat_spj'] : "N",
                'priv_bukujaga' => !empty($p['priv_bukujaga']) ? $p['priv_bukujaga'] : "N",
                'priv_anggarankinerja' => !empty($p['priv_anggarankinerja']) ? $p['priv_anggarankinerja'] : "N",
            ];
            $tbl = 't_privilages';
            $cek_privilage = $this->users->get_privilages_count($tbl,$uid);
            if($cek_privilage->num_rows() == 0) {
                $db = $this->users->insert($tbl,$data);
            } else {
                $db = $this->users->update_tbl($tbl,$data,['fid_user' => $uid]);
            }
            if($db)
            {
                // $msg = ['valid' => true, 'pesan' => 'Set Privilages Berhasil'];
                $this->session->set_flashdata(['pesan' => 'Set Privilages Berhasil', 'pesan_type' => 'success']);
            } else {
                // $msg = ['valid' => false, 'pesan' => 'Set Privilages Gagal'];
                $this->session->set_flashdata(['pesan' => 'Set Privilages Gagal', 'pesan_type' => 'danger']);
            }

        }
        // echo json_encode($data);
        redirect(base_url('app/users/privilages/'.$p['uid']));
    }

    public function update_profile($uid)
    {
        $profile = $this->users->profile_id($uid);
        $parts = $this->crud->get('ref_parts');
        $unors = $this->crud->get('ref_unors');

        if($profile->num_rows() === 0) {
            redirect(base_url('/app/users'));
            return false;
        }
        $user_id = decrypt_url($uid);
        $data = [
            'title' => '@'.ucwords($profile->row()->nama).' - '.$profile->row()->role,
            'content' => 'pages/admin/users_ubah',
            'uid' => $uid,
            'user' => $profile->row(),
            'user_id' => $user_id,
            'parts' => $parts,
            'unors' => $unors,
            'autoload_js' => [
                'template/custom-js/blockUI/jquery.blockUI.js',
                'template/custom-js/blockUI/default.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
            ]
        ];
        $this->load->view('layout/app', $data); 
    }

    public function update_profile_aksi()
    {
        $p = $this->input->post();
        $user_id = $p['uid'];
        $profile = $this->users->profile_id($p['uid'])->row();
        $user_nama = $profile->username;
        $nama = $p['nama'];
        $role = $p['role'];
        $whr = ['id' => decrypt_url($p['uid'])]; 
        $path = './template/assets/picture_akun';
        if(!empty($_FILES["file"]["name"]))  
        {  
             $config['upload_path'] = $path;  
             $config['allowed_types'] = 'jpg|jpeg|png'; 
             $config['max_size'] = 1000; // 1MB
             $config['file_ext_tolower'] = TRUE;
             $config['file_name'] = $user_nama."-".$user_id;
             $config['overwrite'] = TRUE;
             
             $this->load->library('upload', $config);  

             if(!$this->upload->do_upload('file'))  
             {  
                $msg = ['valid' => false, 'pesan' => $this->upload->display_errors()];  
             }  
             else  
             {  
                 $data = array('upload_data' => $this->upload->data());
                 $image= $data['upload_data']['file_name'];

                 $userdata = [
                    'nama' => $nama, 
                    'fid_unor' => $p['unor'],
                    'fid_part' => $p['part'], 
                    'nip' => $p['nip'], 
                    'nohp' => $p['nohp'], 
                    'role' => $role, 
                    'pic' => $image, 
                    'jobdesk' => $p['jobdesk'],
                    'is_block' => 'N'
                ]; 
                 
                 $result = $this->users->update($userdata,$whr);
                  
                 if($result)
                 {
                    $msg = ['valid' => true, 'pesan' => 'Profile berhasil di perbaharui', 'redirectTo' => base_url("app/users")];
                 } else {
                    $msg = ['valid' => false, 'pesan' => 'Update profil gagal'];
                 }
             } 
        } else {
            $userdata = [
                'nama' => $nama, 
                'fid_unor' => $p['unor'],
                'fid_part' => $p['part'], 
                'nip' => $p['nip'], 
                'nohp' => $p['nohp'], 
                'role' => $role, 
                'jobdesk' => $p['jobdesk'],
                'is_block' => 'N'
            ];
            $result= $this->users->update($userdata,$whr);
            if($result)
             {
                $msg = ['valid' => true, 'pesan' => 'Profile berhasil di perbaharui', 'redirectTo' => base_url("app/users")];
             } else {
                $msg = ['valid' => false, 'pesan' => 'Update profil gagal'];
             }
        }  
        echo json_encode($msg); 
    }

    public function goToPage()
    {
        $path = $this->input->post('path');
        $uid = $this->input->post('uid');
        if(!$uid && $path != null) {
            $msg = ['redirectTo' => base_url('/app/users')];
        } else {
            $msg = ['redirectTo' => $path];
        }
        echo json_encode($msg);
    }

    public function resspwd($uid)
    {
        if($this->users->profile_id($uid)->num_rows() === 0) {
            redirect(base_url('app/users'));
            return false;
        }

        $data = [
            'title' => 'Reset Password',
            'content' => 'pages/admin/users_resspwd',
            'uid' => $uid,
            'user_id' => decrypt_url($uid),
            'user' => $this->users->profile_id($uid)->row()
        ];
        $this->load->view('layout/app', $data); 
    }

    public function resspwd_aksi()
    {
        $p = $this->input->post();
        $this->form_validation->set_rules('newpwd', 'Password', 'trim|required|min_length[4]|max_length[12]');
        $this->form_validation->set_rules('newpwd_confirm', 'Re-Type Password', 'required|matches[newpwd]');
        $this->form_validation->set_rules('username_confirm', 'Username Confirm', 'required');
        if ($this->form_validation->run() == false) {
            $msg = ['valid' => false, 'pesan' => validation_errors()];
        } else {
            if($p['username_confirm'] === $this->session->userdata('user_name')):
                $uid = decrypt_url($p['uid']);
                $data = [
                    'password' => sha1($p['newpwd'])
                ];
                $whr = ['id' => $uid];

                $db = $this->users->update_tbl('t_users',$data,$whr);
                if($db) {
                    $msg = ['valid' => true, 'pesan' => 'Password '.$p["user_name"].' Berhasil Diubah.', 'redirectUrl' => base_url('app/users')];
                } else {
                    $msg = ['valid' => false, 'pesan' => 'Ops!, password '.$p["user_name"].' gagal diubah.'];
                }
            else:
                $msg = ['valid' => false, 'pesan' => 'Konfirmasi Username Tidak Valid'];
            endif;
        }
        echo json_encode($msg);
    }
}
