<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Rakit\Validation\Validator;

class Whatsapp extends CI_Controller {

    protected $validator;
    protected $base_url_api;
	public function __construct()
    {
        parent::__construct();
        cek_session();
        //  CEK USER PRIVILAGES 
        if(!privilages('priv_default') && !privilages('priv_notify')):
            return show_404();
        endif;
        $this->load->model('ModelWhatsapp', 'wa');
        $this->load->helper('qrcode');
        $this->validator = new Validator();
        $this->base_url_api = 'https://whatsapp.bkpsdm-info.com'; // ganti dengan base url aplikasi Anda
    }

    public function index()
    {
        $sessions = $this->wa->getSessions();
        $data = [
            'title' => 'Whatsapp Notify',
            'content' => 'pages/admin/whatsapp',
            'sessions' => $sessions,
            'autoload_js' => [
            ],
            'autoload_css' => [
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function create()
    {

        $session_name = $this->input->post('session_name');
        $validation = $this->validator->validate($_POST, [
            'session_name' => 'required|lowercase',
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors()->firstOfAll();
            $errorString = implode('<br>', $errors); // gabung dengan <br> biar rapi di view
            $this->session->set_flashdata('error', $errorString);
            return redirect('app/whatsapp');
        }

        try {
            $response = api_qr_code($this->base_url_api.'/session/start', ['session' => $session_name]);
            $res = json_decode($response);

            if(!$res || $res->qr == '') {
                $this->session->set_flashdata('error', $res->message);
                return redirect('app/whatsapp');
            }
    
            $savedb = $this->wa->createSession([
                'fid_user' => decrypt_url($this->session->userdata('user_id')),
                'name' => $session_name,
                'as' => generateRandomString(10),
                'code' => $res->qr,
                'created_at' => DateTimeInput(),
                'created_by' => $this->session->userdata('user_name')
            ]);
    
            if(!$savedb) {
                $this->session->set_flashdata('error', 'Failed to save session '.$session_name);
                return redirect('app/whatsapp');
            }
    
            $this->session->set_flashdata('qr', $res->qr);
            $this->session->set_flashdata('success', 'Session '.$session_name.' created successfully');
            
            redirect('app/whatsapp');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error: '.$e->getMessage());
            return redirect('app/whatsapp');
        }
    }

    public function stop($session = '')
    {
        if ($session == '') {
            $this->session->set_flashdata('error', 'Session is required');
            redirect('app/whatsapp');
        }
        try {
            $session = decrypt_url($session);
            $response = api_curl_get($this->base_url_api.'/session/logout?session='.$session);
            $res = json_decode($response);
            if ($res && $res->data == 'success') {
                $this->session->set_flashdata('success', 'Session '.$session.' stopped successfully');
                $this->wa->stopSession($session);
            } else {
                $this->session->set_flashdata('error', $res->message);
            }
            redirect('app/whatsapp');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error: '.$e->getMessage());
            return redirect('app/whatsapp');
        }
    }
}