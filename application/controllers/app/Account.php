<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends CI_Controller
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
        if (!privilages('priv_default')) :
            return show_404();
        endif;
    }

    public function index()
    {
        $name = $this->session->userdata('nama');
        $uid = $this->session->userdata('user_id');
        $data = [
            'title' => $name . ' - Account',
            'content' => 'pages/account',
            'detail' => $this->users->profile_id($uid)->row(),
            'autoload_js' => [
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/backend/vendors/parsleyjs/dist/i18n/id.js',
                'template/backend/vendors/parsleyjs/dist/i18n/id.extra.js',
                'template/custom-js/blockUI/jquery.blockUI.js',
                'template/custom-js/blockUI/default.js',
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    function update_profile_basic()
    {
        $user_id = $this->input->post('uid');
        $profile = $this->users->profile_id($user_id)->row();
        $user_nama = $profile->username;
        $nama = $this->input->post('nama');
        $nip = $this->input->post('nip');
        $nohp = $this->input->post('nohp');
        $whr = ['id' => decrypt_url($user_id)];
        $path = './template/assets/picture_akun';
        if (!empty($_FILES["file"]["name"])) {
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'webp|jpg|jpeg|png';
            $config['max_size'] = 1000; // 1MB
            $config['file_ext_tolower'] = TRUE;
            $config['file_name'] = $user_nama . "-" . $user_id;
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file')) {
                $msg = ['valid' => false, 'pesan' => $this->upload->display_errors()];
            } else {
                $data = array('upload_data' => $this->upload->data());
                $image = $data['upload_data']['file_name'];

                $userdata = ['nama' => $nama, 'nip' => $nip, 'nohp' => $nohp, 'pic' => $image];

                $result = $this->users->update($userdata, $whr);

                if ($result) {
                    $msg = ['valid' => true, 'pesan' => 'Profile berhasil di perbaharui, silahkan relog untuk melihat perubahan.', 'redirectTo' => urlencode(base_url("app/account"))];
                } else {
                    $msg = ['valid' => false, 'pesan' => 'Update profil gagal', 'redirectTo' => false];
                }
            }
        } elseif (($nama == $profile->nama) && ($nip == $profile->nip) && ($nohp == $profile->nohp) && (empty($_FILES["file"]["name"]))) {
            $msg = ['valid' => false, 'pesan' => 'Tidak ada perubahan', 'redirectTo' => false];
        } else {
            $userdata = ['nama' => $nama, 'nip' => $nip, 'nohp' => $nohp,];
            $result = $this->users->update($userdata, $whr);
            if ($result) {
                $msg = ['valid' => true, 'pesan' => 'Profile berhasil di perbaharui, silahkan relog untuk melihat perubahan.', 'redirectTo' => urlencode(base_url("app/account"))];
            } else {
                $msg = ['valid' => false, 'pesan' => 'Update profil gagal', 'redirectTo' => false];
            }
        }
        echo json_encode($msg);
    }

    public function update_profile_pwd()
    {
        $user_id = $this->session->userdata('user_id');
        $profile = $this->users->profile_id($user_id)->row();
        $true_token = $this->session->csrf_token;
        $p = $this->input->post();

        if ($p['token'] != $true_token) :
            $this->output->set_status_header('403');
            $this->session->unset_userdata('csrf_token');
            show_error('This request rejected');
            return false;
        endif;

        if ($p) {
            // user submitted the form
            $pwd_old_db = $profile->password;
            $pwd_old_post = sha1($p['old_pwd']);
            $pwd_new_post = sha1($p['new_pwd']);
            if ($pwd_old_post == $pwd_old_db) {
                $this->form_validation->set_rules('new_pwd', 'New Password', 'trim|required|min_length[4]|max_length[12]');
                if ($this->form_validation->run() == false) {
                    $msg = ['valid' => false, 'pesan' => validation_errors()];
                } else {
                    $data = ['password' => $pwd_new_post];
                    $whr = ['id' => $profile->id];
                    $db = $this->users->update_pwd('t_users', $data, $whr);
                    if ($db) {
                        $msg = ['valid' => true, 'pesan' => 'Password Telah Diperbaharui, silahkan relog untuk menggunakannya.', 'redirectTo' => urlencode(base_url("app/account"))];
                    } else {
                        $msg = ['valid' => false, 'pesan' => 'Password Gagal Diperbaharui, server tidak meresponse'];
                    }
                }
            } else {
                $msg = ['valid' => false, 'pesan' => 'Old Password Is Invalid'];
            }
        } else {
            $msg = ['valid' => false, 'pesan' => 'Form is empty !'];
        }
        echo json_encode($msg);
    }
}
