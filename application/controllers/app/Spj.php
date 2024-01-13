<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Spj extends CI_Controller {

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
        if(!privilages('priv_default')):
            return show_404();
        endif;
    }
	
	public function index()
	{
        $data = [
			'title' => 'SPJ (Surat Pertanggung Jawaban)',
            'content' => 'pages/spj/index',
            'autoload_js' => [
                'template/custom-js/spj.js'
            ]
        ];
		$this->load->view('layout/app', $data);
	}

    public function inbox() {
        $db = $this->crud->get('spj');
        
        $btnAdd = '<div class="mb-3">
        <button class="btn btn-primary rounded-0" onclick="window.location.href=\''.base_url("app/spj/buatusul").'\'"><i class="fa fa-plus mr-2"></i> Buat Usul SPJ</button>
                </div>
                ';
        $html = $btnAdd;
        $html .= '<table class="table table-condensed table-hover table-responsive">';
        $html .= '<thead><tr><th class="text-center">No</th><th>Judul</th><th>Hapus</th><th>Edit</th></tr></thead>';
        $html .= '<tbody>';
        $no=1;
        foreach($db->result() as $r):
            $html .= '<tr>
                <td class="text-center">
                    '.$no.'
                </td>
                <td>
                    '.$r->fid_part.'
                </td>
                <td width="5%" class="text-center">
                    <button onclick="Hapus('.$r->id.',\''.base_url('app/programs/hapus/ref_parts').'\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
                </td>
                <td width="5%" class="text-center"><button onclick="Edit('.$r->id.',\''.base_url("app/programs/detail/ref_parts").'\',\'.modal-part-edit\')" type="button" class="btn btn-sm btn-light m-0"><i class="fa fa-pencil"></i></button></td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table>';
        
        if($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows().' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Usulan <b>SPJ</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);  
    }

    public function buatusul()
    {
        $data = [
			'title' => 'Usul - SPJ (Surat Pertanggung Jawaban)',
            'content' => 'pages/spj/usul',
            'list_bidang' => $this->crud->get('ref_parts')->result(),
            'list_program' => $this->crud->get('ref_programs')->result(),
            'list_kegiatan' => $this->crud->getWhere('ref_kegiatans', ['fid_part' => $this->session->userdata('part')])->result(),
            'autoload_js' => [
                'template/backend/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js',
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/spj_usul.js',
            ],
			'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css'
			]
        ];
		$this->load->view('layout/app', $data);
    }

    public function prosesusul()
    {
        $input = $this->input->post();
        $data = [
            'fid_part' => $input['part'],
            'fid_program' => $input['program'],
            'fid_kegiatan' => $input['kegiatan'],
            'fid_sub_kegiatan' => $input['sub_kegiatan'],
            'koderek' => $input['koderek'],
            'bulan' => $input['bulan'],
            'tahun' => $input['tahun'],
            'uraian' => $input['uraian'],
            'jumlah' => $input['jumlah']
        ];
        // $db = $this->crud->insert('spj',$data);
        // if($db)
        // {
        //     $status = ['msg' => 'Oke', 'code' => 200];
        // } else {
        //     $status = ['msg' => 'Gagal', 'code' => 400];
        // }
        echo json_encode($data);
    }
}
