<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
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
        $this->load->model('ModelAuth', 'auth');
    }

    public function index()
    {
        if ($this->session->userdata('user_id') != ''):
            redirect(base_url('app/dashboard'));
            return false;
        endif;

        if ($this->session->userdata('pic') != '' && $this->session->userdata('user_name') != ''):
            redirect(base_url('/lockscreen'));
            return false;
        endif;

        $data = [
            'title' => 'Authentication - Digta Sunanpraja (Digitalisasi Penyusunan Laporan Capaian Kinerja)',
        ];
        $this->load->view('pages/login', $data);
    }

    public function lockScreen()
    {
        if ($this->session->userdata('user_id') != ''):
            redirect(base_url('app/dashboard'));
            return false;
        endif;

        if ($this->session->userdata('pic') == '' && $this->session->userdata('user_name') == ''):
            redirect(base_url('/'));
            return false;
        endif;

        $data = [
            'title' => 'Lock Screen - Emonev App'
        ];
        $this->load->view('pages/lockscreen', $data);
    }

    public function cek_akun()
    {
        $true_token = $this->session->csrf_token;
        if ($this->input->post('token') != $true_token):
            $this->output->set_status_header('403');
            $this->session->unset_userdata('csrf_token');
            show_error('This request rejected');
            return false;
        // $json_msg = ['valid' => false, 'msg' => 'Token Invalid', 'redirect' => base_url('console')];
        // return false;   
        endif;

        if (!empty($this->session->userdata('user_id'))):
            return redirect(base_url('app/dashboard'));
        endif;

        $username = trim($this->security->xss_clean($this->input->post('username', true)));
        $password = trim($this->security->xss_clean($this->input->post('pwd', true)));

        $pwd = sha1($password);
        // $where = array(
        //     'username' => $username,
        //     'password' => $pwd,
        //     // 'is_block' => 'N'
        // );
        $cek = $this->auth->cek_login('t_users', $username, $pwd);
        if ($cek->num_rows() > 0) {
            foreach ($cek->result() as $key) {
                $row = $key;
            }
            $data_session = array(
                'user_id' => encrypt_url($row->id),
                'user_name' => $username,
                'unor' => $row->fid_unor,
                'part' => $row->fid_part, // Bidang / Bagian
                'nip' => $row->nip,
                'nohp' => $row->nohp,
                'nama' => $row->nama,
                'pic' => $row->pic,
                'role' => $row->role,
                'jobdesk' => $row->jobdesk,
                'check_in' => DateTimeInput(),
                'check_out' => $row->check_out,
                'tahun_anggaran' => $this->input->post('tahun', true),
                'is_perubahan' => "0",
            );
            $this->db->update('t_users', ['check_in' => DateTimeInput()], ['id' => $row->id]);
            $this->session->set_userdata($data_session);
            $p_continue = $this->input->post('continue');
            $continue = isset($p_continue) ? $p_continue : base_url('app/dashboard');
            $json_msg = ['valid' => true, 'msg' => 'Auth success.', 'redirect' => $continue];
        } else {
            $json_msg = ['valid' => false, 'msg' => 'Auth gagal, akun tidak ditemukan.', 'redirect' => base_url('lockscreen')];
        }
        echo json_encode($json_msg);
    }

    public function removeSession()
    {
        clearstatcache();
        $redirectTo = isset($_GET['continue']) ? "?continue=" . $_GET['continue'] : '';
        $data = array('user_name', 'user_id', 'csrf_token');
        $this->db->update('t_users', ['check_out' => DateTimeInput()], ['id' => decrypt_url($this->session->userdata('user_id'))]);
        $this->session->unset_userdata($data);
        $this->session->sess_destroy();
        redirect(base_url('/login' . $redirectTo));
    }

    public function lockScreenAction()
    {
        clearstatcache();
        $redirectTo = isset($_GET['continue']) ? "/lockscreen?continue=" . urlencode($_GET['continue']) : '/lockscreen';
        $data = array('user_id', 'csrf_token');
        $this->db->update('t_users', ['check_out' => DateTimeInput()], ['id' => decrypt_url($this->session->userdata('user_id'))]);
        $this->session->unset_userdata($data);
        redirect(base_url('/login' . $redirectTo));
    }
}
