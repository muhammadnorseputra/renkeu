<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bukujaga extends CI_Controller
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
        if (!privilages('priv_default') && !privilages('priv_bukujaga') || $this->session->userdata('is_valid_profile') === "0") :
            return show_404();
        endif;

        $this->load->model('ModelSpj', 'spj');
        $this->load->model('ModelBukujaga', 'bukujaga');
        $this->load->model('ModelUsers', 'user');
        $this->load->model('ModelTarget', 'target');
    }

    public function index()
    {
        $data = [
            'title' => 'Buku Jaga Kegiatan',
            'content' => 'pages/spj/bukujaga',
            'programs' => $this->target->program(null, $this->session->userdata('part'), $this->session->userdata('tahun_anggaran')),
            'autoload_js' => [
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/blockUI/jquery.blockUI.js',
                'template/custom-js/bukujaga.js',
            ],
            'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css'
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function rincian()
    {
        $data = [
            'title' => 'Rincian Kegiatan',
            // 'content' => 'pages/spj/bukujaga_rincian',
            'content' => 'in-development',
            'proggres' => 70,
            'programs' => $this->target->program(null, $this->session->userdata('part'), $this->session->userdata('tahun_anggaran')),
            'autoload_js' => [
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/blockUI/jquery.blockUI.js',
                'template/custom-js/bukujaga_rincian.js',
            ],
            'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css'
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function carikode()
    {
        $input = $this->input->post();
        $kode_kegiatan = $this->crud->getWhere('ref_kegiatans', ['id' => $input['kegiatan']])->row();
        $kode_subkegiatan = $this->crud->getWhere('ref_sub_kegiatans', ['id' => $input['sub_kegiatan']])->row();
        $kode_uraian = @$this->crud->getWhere('ref_uraians', ['id' => $input['rekening']])->row();

        $data = [
            'part_id' => $input['part'],
            'program_id' => $input['program'],
            'kegiatan_id' => $input['kegiatan'],
            'subkegiatan_id' => $input['sub_kegiatan'],
            'uraian_id' => @$input['rekening'],
            'kode_kegiatan' => $kode_kegiatan->kode,
            'kode_subkegiatan' => $kode_subkegiatan->kode,
            'kode_uraian' => @$kode_uraian->kode,
            'kode' => $kode_kegiatan->kode . "." . $kode_subkegiatan->kode
        ];
        echo json_encode($data);
    }

    public function view()
    {
        $post = $this->input->post();
        $uraians = $this->bukujaga->getUraianBySubKegiatan($post['ref_subkegiatan']);
        $data = [
            'post' => $post,
            'uraians' => $uraians->result()
        ];
        $template = $this->load->view("pages/spj/bukujaga_detail", $data);
        return $template;
    }

    public function view_rincian()
    {
        $post = $this->input->post();
        $rincian = $this->bukujaga->getSPJByUraian($post['ref_uraian']);
        $data = [
            'post' => $post,
            'rincian' => $rincian->result()
        ];
        $template = $this->load->view("pages/spj/bukujaga_rincian_detail", $data);
        return $template;
    }

    public function cetak()
    {
        $post = $this->input->post();
        $uraians = $this->bukujaga->getUraianBySubKegiatan($post['ref_subkegiatan']);

        $this->load->library('pdf');
        $this->pdf->setPaper('legal', 'landscape');
        $this->pdf->filename = 'SIMEV - Cetak Bukujaga - ' . $post['kodesub'] . ' ' . $post['namasub'] . '.pdf';


        $data = [
            'title' => 'Cetak Buku Jaga - ' . $post['kodesub'] . ' ' . $post['namasub'],
            'post' => $post,
            'uraians' => $uraians->result()
        ];
        $this->pdf->load_view('pages/spj/bukujaga_cetak', $data);
    }

    public function cetak_rincian() 
    {
        $post = $this->input->post();
        $rincian = $this->bukujaga->getSPJByUraian($post['ref_uraian']);

        $this->load->library('pdf');
        $this->pdf->setPaper('legal', 'landscape');
        $this->pdf->filename = 'SIMEV - Cetak Rincian Bukujaga - ' . $post['kode_uraian'] . ' ' . $post['nama_uraian'] . '.pdf';
        $data = [
            'title' => 'Cetak Rincian Buku Jaga - ' . $post['kode_uraian'] . ' ' . $post['nama_uraian'],
            'post' => $post,
            'ta' => $this->session->userdata('tahun_anggaran'),
            'is_perubahan' => $this->session->userdata('is_perubahan'),
            'rincian' => $rincian->result()
        ];
        $this->pdf->load_view('pages/spj/bukujaga_rincian_cetak', $data);
    }
}
