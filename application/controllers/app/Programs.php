<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Programs extends CI_Controller {

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
        if(!privilages('priv_programs')):
            return show_404();
        endif;
    }
	
	public function index()
	{
        $data = [
			'title' => 'Program & Kegiatan',
            'content' => 'pages/admin/programs',
            'autoload_js' => [
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/list.min.js',
                'template/backend/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js'
			],
			'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css'
			]
        ];
		$this->load->view('layout/app', $data);
	}

    public function part() {
        $db = $this->crud->get('ref_parts');
        
        $btnAdd = '<div class="mb-3">
        <button data-toggle="modal" data-target=".modal-unor" class="btn rounded-0 btn-info"><i class="fa fa-plus mr-2"></i> Tambah : Unor</button>
        <button data-toggle="modal" data-target=".modal-part" class="btn rounded-0 btn-primary"><i class="fa fa-plus mr-2"></i> Tambah : Part</button>
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
                    '.$r->nama.'
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
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Parts</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function program()
    {
        $db = $this->crud->get('ref_programs');
        
        $btnAdd = '<div class="col-3 col-md-3">
            <button data-toggle="modal" data-target=".modal-program" class="btn btn-primary mt-3 pull-right rounded-0"><i class="fa fa-plus"></i> Tambah</button></div>
        ';
        $search = '<div class="col-5 col-md-3">Pencarian <input type="text" class="search form-control" /></div>';
        $pagging = '<div class="col-4 col-md-6">Halaman <ul class="pagination"></ul></div>';
        
        $html = '<div id="listProgram"><div class="row">'.$search.$pagging.$btnAdd."</div>";
        $html .= '<table class="table table-condensed table-hover">';
        $html .= '<thead><tr><th class="text-center">No</th><th>Judul</th><th>Hapus</th></tr></thead>';
        $html .= '<tbody class="list">';
        $no=1;
        foreach($db->result() as $r):
            $html .= '<tr>
                <td class="text-center">
                    '.$no.'
                </td>
                <td>
                    '.$r->nama.'
                </td>
                <td width="5%" class="text-center">
                    <button onclick="Hapus('.$r->id.',\''.base_url('app/programs/hapus/ref_programs').'\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
                </td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div>';
        
        if($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows().' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Program</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function kegiatan()
    {
        $db = $this->db->order_by('kode', 'asc')->get('ref_kegiatans');
        $btnAdd = '<div class="col-3 col-md-3">
            <button data-toggle="modal" data-target=".modal-kegiatan" class="btn btn-primary mt-3 pull-right rounded-0"><i class="fa fa-plus"></i> Tambah</button></div>
        ';
        $search = '<div class="col-5 col-md-3">Pencarian <input type="text" class="search form-control" /></div>';
        $pagging = '<div class="col-4 col-md-6">Halaman <ul class="pagination"></ul></div>';
        
        $html = '<div id="listKegiatan"><div class="row">'.$search.$pagging.$btnAdd."</div>";
        $html .= '<table class="table table-condensed table-hover">';
        $html .= '<thead><tr><th class="text-center">No</th><th>Kode</th><th>Judul</th><th>Hapus</th></tr></thead>';
        $html .= '<tbody class="list">';
        $no=1;
        foreach($db->result() as $r):
            $html .= '<tr>
                <td class="text-center">
                    '.$no.'
                </td>
                <td>
                    '.$r->kode.'
                </td>
                <td valign="middle">
                    '.strtoupper($r->nama).'
                </td>
                <td width="5%" class="text-center">
                    <button onclick="Hapus('.$r->id.',\''.base_url('app/programs/hapus/ref_kegiatans').'\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
                </td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div>';
        
        if($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows().' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Kegiatan</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function sub_kegiatan()
    {
        
            $db = $this->db->order_by('kode', 'asc')->get('ref_sub_kegiatans');
            $btnAdd = '<div class="col-12 col-md-3">
                <button data-toggle="modal" data-target=".modal-subkegiatan" class="btn btn-primary mt-3 pull-right rounded-0"><i class="fa fa-plus"></i> Tambah</button></div>
            ';
            $search = '<div class="col-6 col-md-3">Pencarian <input type="text" class="fuzzy-search form-control" /></div>';
            $pagging = '<div class="col-6 col-md-6">Halaman <ul class="pagination"></ul></div>';
            
            $html = '<div id="listSubKegiatan"><div class="row">'.$search.$pagging.$btnAdd."</div>";
            $html .= '<table class="table table-condensed table-hover">';
            $html .= '<thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th class="text-right">Pagu Awal</th>
                                <th class="text-right">Realisasi</th>
                                <th class="text-right">Pagu Akhir</th>
                                <th>Hapus</th>
                                <th>Edit</th>
                            </tr>
                        </thead>';
            $html .= '<tbody class="list">';
            $no=1;
            foreach($db->result() as $r):
                $html .= '<tr>
                    <td class="text-center">
                        '.$no.'
                    </td>
                    <td class="kode">
                        '.$r->kode.'
                    </td>
                    <td class="nama">
                        '.strtoupper($r->nama).'
                    </td>
                    <td class="nominal text-right">
                        Rp. '.nominal($r->total_pagu_before).'
                    </td>
                    <td class="nominal text-right">
                        Rp. '.nominal($r->total_pagu_realisasi).'
                    </td>
                    <td class="nominal text-right">
                        Rp. '.nominal($r->total_pagu_after).'
                    </td>
                    <td width="5%" class="text-center">
                        <button onclick="Hapus('.$r->id.',\''.base_url('app/programs/hapus/ref_sub_kegiatans').'\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
                    </td>
                    <td width="5%" class="text-center">
                        <a href="'.base_url('app/programs/ubah/'.$r->id.'/ref_sub_kegiatans').'" type="button" class="btn btn-info btn-sm rounded-0 m-0"><i class="fa fa-pencil"></i></a>
                    </td>
                </tr>';
                $no++;
            endforeach;
            $html .= '</tbody>';
            $html .= '</table></div>';
            
            if($db->num_rows() > 0):
                $data = ['result' => $html, 'msg' => $db->num_rows().' Data Ditemukan', 'code' => 200];
            else:
                $data = ['result' => $btnAdd, 'msg' => 'Data <b>Sub Kegiatan</b> Tidak Ditemukan', 'code' => 404];
            endif;
    
            echo json_encode($data);
        
    }

    public function getUnor() 
    {
        $q = $this->input->post('q');

        $db = $this->crud->getLikes('ref_unors', ['nama' => $q]);
        $all = [];
        if($db->num_rows() > 0):
            foreach($db->result() as $row):
                $data['id'] = $row->id;
                $data['text'] = $row->id." - ".$row->nama;
                $all[] = $data;
            endforeach;
        else:
            $all[] = ['id' => 0,  'text' => 'Maaf, Unor "'. strtoupper($q) .'" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getListUnor() 
    {
        $db = $this->crud->get('ref_unors');
        $all = [];
        if($db->num_rows() > 0):
            foreach($db->result() as $row):
                $data['id'] = $row->id;
                $data['text'] = $row->id." - ".$row->nama;
                $all[] = $data;
            endforeach;
        else:
            $all[] = ['id' => 0,  'text' => 'Maaf, Unor "'. strtoupper($q) .'" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getParts() 
    {
        $q = $this->input->post('q');

        $db = $this->crud->getLikes('ref_parts', ['nama' => $q]);
        $all = [];
        if($db->num_rows() > 0):
            foreach($db->result() as $row):
                $data['id'] = $row->id;
                $data['text'] = $row->id." - ".$row->nama;
                $all[] = $data;
            endforeach;
        else:
            $all[] = ['id' => 0,  'text' => 'Maaf, Bidang / Bagian "'. strtoupper($q) .'" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getProgram() 
    {
        $q = $this->input->post('q');

        $db = $this->crud->getLikes('ref_programs', ['nama' => $q]);
        $all = [];
        if($db->num_rows() > 0):
            foreach($db->result() as $row):
                $data['id'] = $row->id;
                $data['text'] = $row->id." - ".$row->nama;
                $all[] = $data;
            endforeach;
        else:
            $all[] = ['id' => 0,  'text' => 'Maaf, Program "'. strtoupper($q) .'" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    function ch_kegiatan($partid,$query) {
        $ch = [];
        // $db_kegiatan = $this->crud->getWhere('ref_kegiatans', ['fid_part' => $partid]);
        $db = $this->db->where(['fid_part' => $partid])->like('nama', $query)->get('ref_kegiatans');
        foreach($db->result() as $k) {
            $data['id'] = $k->id;
            $data['text'] = $k->kode." - ".$k->nama;
            $ch[] = $data;
        }
        return $ch;
    }

    public function getKegiatan() 
    {
        $q = $this->input->post('q');
        // $db = $this->crud->getLikes('ref_kegiatans', ['nama' => $q]);
        $db = $this->db->select('k.*,p.nama AS partnama, p.id AS partid')
        ->from('ref_kegiatans AS k')
        ->join('ref_parts AS p', 'k.fid_part=p.id', 'inner')
        ->like('k.nama', $q)
        ->group_by('k.fid_part')
        ->get();
        if($db->num_rows() > 0):
            $group = [];
            $db_part = $this->crud->get('ref_parts');
            foreach($db->result() as $row):
                $data['text'] = $row->partnama;
                $data['children'] = $this->ch_kegiatan($row->partid, $q);
                $group[] = $data;
            endforeach;
        else:
            $group[] = ['id' => 0,  'text' => 'Maaf, Kegiatan "'. strtoupper($q) .'" tidak ditemukan.'];
        endif;
        echo json_encode($group);
    }

    public function tambah($type)
    {
        if($type === 'unor')
        {
            $p = $this->input->post();
            $data = [
                'nama' => $p['unor']
            ];
            $db = $this->crud->insert('ref_unors', $data);
            if($db) 
            {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        } 

        if($type === 'part')
        {
            $p = $this->input->post();
            $data = [
                'nama' => $p['part'],
                'singkatan' => $p['part_singkatan']
            ];
            $db = $this->crud->insert('ref_parts', $data);
            if($db) 
            {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        } 

        if($type === 'kegiatan')
        {
            $p = $this->input->post();
            $data = [
                'fid_part' => $p['part'],
                'fid_program' => $p['program'],
                'kode' => $p['kode_kegiatan'],
                'nama' => $p['kegiatan']
            ];
            $db = $this->crud->insert('ref_kegiatans', $data);
            if($db) 
            {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        } 

        if($type === 'program')
        {
            $p = $this->input->post();
            $data = [
                'fid_unor' => $p['unor'],
                'nama' => $p['program']
            ];
            $db = $this->crud->insert('ref_programs', $data);
            if($db) 
            {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }

        if($type === 'subkegiatan')
        {
            $p = $this->input->post();
            $data = [
                'fid_kegiatan' => $p['kegiatan'],
                'kode' => $p['kode_subkegiatan'],
                'nama' => $p['subkegiatan']
            ];
            $db = $this->crud->insert('ref_sub_kegiatans', $data);
            if($db) 
            {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }
    }

    public function detail($tbl)
    {
        $uid = $this->input->get('id');
        $db = $this->crud->getWhere($tbl, ['id' => $uid]);
        $row = $db->row();
        echo json_encode($row);
    }

    public function detailv2($tbl, $id)
    {
        $db = $this->crud->getWhere($tbl, ['id' => $id]);
        $row = $db->row();
        return $row;
    }

    public function ubah($id,$tbl)
    {
        $detail = $this->detailv2($tbl,$id);
        $data = [
			'title' => $detail->nama,
            'content' => 'pages/admin/'.$tbl,
            'data' => $detail,
            'autoload_js' => [
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/backend/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js'
			],
			'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css'
			]
        ];
		$this->load->view('layout/app', $data);
    }

    public function update($tbl)
    {
        if($tbl === 'ref_unors')
        {
            $input = $this->input->post();
            $data = [
                'nama' => $input['unor']
            ];
            $whr = [
                'id' => $input['id']
            ];
            $db = $this->crud->update($tbl, $data, $whr);
            if($db)
            {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }

        if($tbl === 'ref_parts')
        {
            $input = $this->input->post();
            $data = [
                'nama' => $input['part'],
                'singkatan' => $input['part_singkatan']
            ];
            $whr = [
                'id' => $input['id']
            ];
            $db = $this->crud->update($tbl, $data, $whr);
            if($db)
            {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
        }

        if($tbl === 'ref_programs')
        {
            $input = $this->input->post();
            $data = [
                'fid_unor' => $input['unor'],
                'nama' => $input['program']
            ];
            $whr = [
                'id' => $id
            ];
            $db = $this->crud->update($tbl, $data, $whr);
            if($db)
            {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
        }
    }

    public function cek_kode($form)
    {
        if($form === 'subkegiatan') {
            $kode = $this->input->post('kode_subkegiatan');
            $db = $this->crud->getWhere('ref_sub_kegiatans', ['kode' => $kode]);
            if($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }

        if($form === 'namaprogram') {
            $kode = $this->input->post('program');
            $db = $this->crud->getWhere('ref_programs', ['nama' => $kode]);
            if($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }

        if($form === 'kegiatan') {
            $kode = $this->input->get('kode_kegiatan');
            $db = $this->crud->getWhere('ref_kegiatans', ['kode' => $kode]);
            if($db->num_rows() > 0) {
                 $this->output->set_status_header('400');
            } else {
                 $this->output->set_status_header('200');
            }
            return false;
        }
    }

    public function hapus($type)
    {
        if(isset($type))
        {
            $p = $this->input->post();
            $id = $p['id'];
            $db = $this->crud->deleteWhere($type, ['id' => $id]);
            if($db)
            {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
        }
    }
}
