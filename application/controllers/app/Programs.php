<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Programs extends CI_Controller
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
        if (!privilages('priv_programs')) :
            return show_404();
        endif;
        $this->load->model('modeltarget', 'target');
        
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
                'template/custom-js/rupiah.js',
                'template/backend/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js'
            ],
            'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css'
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function part()
    {
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
        $no = 1;
        foreach ($db->result() as $r) :
            $html .= '<tr>
                <td class="text-center">
                    ' . $no . '
                </td>
                <td>
                    ' . $r->nama . '
                </td>
                <td width="5%" class="text-center">
                    <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_parts') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
                </td>
                <td width="5%" class="text-center"><button onclick="Edit(' . $r->id . ',\'' . base_url("app/programs/detail/ref_parts") . '\',\'.modal-part-edit\')" type="button" class="btn btn-sm btn-light m-0"><i class="fa fa-pencil"></i></button></td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table>';

        if ($db->num_rows() > 0) :
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else :
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Parts</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function program()
    {
        $db = $this->crud->get('ref_programs');

        $btnAdd = '<div>
            <button data-toggle="modal" data-target=".modal-program" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        ';
        $search = '<div class="col-5 col-md-3">Pencarian <input type="text" class="search form-control" /></div>';
        $pagging = '<div class="col-4 col-md-6">Halaman <ul class="pagination"></ul></div>';

        $html = '<div id="listProgram"><div class="row">' . $search . $pagging . $btnAdd . "</div>";
        $html .= '<table class="table table-condensed table-hover">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Rekening</th>
                        <th>Nama Program</th>
                        <th>Alokasi Anggaran</th>
                    </tr>
                    </thead>';
        $html .= '<tbody class="list">';
        $no = 1;
        foreach ($db->result() as $r) :
            
            $totalPaguAwal = !empty($this->target->getAlokasiPaguProgram($r->id)->row()->total_pagu_awal) ? $this->target->getAlokasiPaguProgram($r->id)->row()->total_pagu_awal : 0;
            $button_hapus = '<td width="5%" class="text-center">
            <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_programs') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
        </td>';

            $html .= '<tr>
                <td class="text-center">
                    ' . $no . '
                </td>
                <td class="kode">
                    ' . $r->kode . '
                </td>
                <td class="nama">
                    ' . $r->nama . '
                </td>
                <td>
                    <b>'.@nominal($totalPaguAwal).'</b>
                </td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div>';

        if ($db->num_rows() > 0) :
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else :
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Program</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function kegiatan()
    {
        // get data kegiatan
        if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN') :
            $db = $this->db->order_by('kode', 'asc')->get('ref_kegiatans');
        else :
            $db = $this->db->order_by('kode', 'asc')->where('fid_part', $this->session->userdata('part'))->get('ref_kegiatans');
        endif;

        $btnAdd = '<div>
            <button data-toggle="modal" data-target=".modal-kegiatan" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button></div>
        ';
        $search = '<div class="col-5 col-md-3">Pencarian <input type="text" class="search form-control" /></div>';
        $pagging = '<div class="col-4 col-md-6">Halaman <ul class="pagination"></ul></div>';

        $html = '<div id="listKegiatan"><div class="row">' . $search . $pagging . $btnAdd . "</div>";
        $html .= '<table class="table table-condensed table-hover">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Rekening</th>
                        <th>Nama Kegiatan</th>
                        <th>Alokasi Anggaran (Rp)</th>
                    </tr>
                </thead>';
        $html .= '<tbody class="list">';
        $no = 1;
        foreach ($db->result() as $r) :
            $totalPaguAwal = !empty($this->target->getAlokasiPaguKegiatan($r->id)->row()->total_pagu_awal) ? $this->target->getAlokasiPaguKegiatan($r->id)->row()->total_pagu_awal : 0;
            $hapus = '
            <td width="5%" class="text-center">
                <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_kegiatans') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
            </td>';

            $html .= '<tr>
                <td class="text-center">
                    ' . $no . '
                </td>
                <td class="kode">
                    ' . $r->kode . '
                </td>
                <td valign="middle" class="nama">
                    ' . strtoupper($r->nama) . '
                </td>
                <td>
                    <b>'.@nominal($totalPaguAwal).'</b>
                </td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div>';

        if ($db->num_rows() > 0) :
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else :
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Kegiatan</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function sub_kegiatan()
    {
        if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN') :
            $db = $this->db->order_by('kode', 'asc')
                ->get('ref_sub_kegiatans AS sub');
        else :
            $db = $this->db->select('sub.id,sub.fid_kegiatan,sub.kode,sub.nama')
                ->order_by('sub.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id', 'inner')
                ->where('keg.fid_part', $this->session->userdata('part'))
                ->get('ref_sub_kegiatans AS sub');
        endif;
        $btnAdd = '<div>
                <button data-toggle="modal" data-target=".modal-subkegiatan" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button></div>
            ';
        $search = '<div class="col-6 col-md-3">Pencarian <input type="text" class="fuzzy-search form-control" /></div>';
        $pagging = '<div class="col-6 col-md-6">Halaman <ul class="pagination"></ul></div>';

        $html = '<div id="listSubKegiatan"><div class="row">' . $search . $pagging . $btnAdd . "</div>";
        $html .= '<table class="table table-condensed table-hover">';
        $html .= '<thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode Rekening</th>
                                <th>Nama Sub Kegiatan</th>
                                <th class="text-right">Alokasi Pagu (Rp)</th>
                                <th width="5%" class="text-right"></th>
                            </tr>
                        </thead>';
        $html .= '<tbody class="list">';
        $no = 1;
        foreach ($db->result() as $r) :
            //get alokasi pagu berdasarkan id kegiatan
            $pagu = $this->crud->getWhere('t_pagu', ['fid_sub_kegiatan' => $r->id])->row();
            $totalPaguAwal = !empty($pagu->total_pagu_awal) ? $pagu->total_pagu_awal : 0;

            $button_hapus = '<td width="5%" class="text-center">
                <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_sub_kegiatans') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
            </td>';
            $button_edit = '<td width="5%" class="text-center">
                <a href="' . base_url('app/programs/ubah/' . $r->id . '/ref_sub_kegiatans') . '" type="button" class="btn btn-info btn-sm rounded-0 m-0"><i class="fa fa-pencil"></i></a>
            </td>';

            $button_pagu = '<td width="10%" class="text-right">
            <div class="text-right">
            <b>' . nominal($totalPaguAwal) . '</b>
        </td>
        <td>
        <button onclick="InputPagu(' . $r->id . ',\'' . base_url('app/programs/input/ref_sub_kegiatans') . '\',\'' . $totalPaguAwal . '\')" type="button" class="btn btn-info btn-sm rounded m-0 ml-2"><i class="fa fa-money"></i></button>
        </div>
        </td>';

            $html .= '<tr>
                    <td class="text-center">
                        ' . $no . '
                    </td>
                    <td class="kode">
                        ' . $r->kode . '
                    </td>
                    <td>
                        <span class="nama">' . strtoupper($r->nama) . '</span>
                    </td>
                    ' . $button_pagu . '
                </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div>';

        if ($db->num_rows() > 0) :
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else :
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Sub Kegiatan</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function uraian()
    {
        if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN') :
            $db = $this->db->select('u.id,u.fid_kegiatan,u.kode,u.nama,keg.kode AS kode_kegiatan,keg.nama AS nama_kegiatan, sub.kode AS kode_sub_kegiatan,sub.nama AS nama_sub_kegiatan')
                ->order_by('u.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'u.fid_kegiatan=keg.id', 'inner')
                ->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id', 'inner')
                ->get('ref_uraians AS u');
        else :
            $db = $this->db->select('u.id,u.fid_kegiatan,u.kode,u.nama,keg.kode AS kode_kegiatan,keg.nama AS nama_kegiatan, sub.kode AS kode_sub_kegiatan,sub.nama AS nama_sub_kegiatan')
                ->order_by('u.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'u.fid_kegiatan=keg.id', 'inner')
                ->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id', 'inner')
                ->where('keg.fid_part', $this->session->userdata('part'))
                ->get('ref_uraians AS u');
        endif;

        $btnAdd = '<div>
            <button data-toggle="modal" data-target=".modal-uraian" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button></div>
        ';
        $search = '<div class="col-5 col-md-3">Pencarian <input type="text" class="search form-control" /></div>';
        $pagging = '<div class="col-4 col-md-6">Halaman <ul class="pagination"></ul></div>';

        $html = '<div id="listUraian"><div class="row">' . $search . $pagging . $btnAdd . "</div>";
        $html .= '<table class="table table-condensed table-hover">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Rekening</th>
                        <th>Nama Kegiatan/Sub Kegiatan/Uraian</th>
                        <th class="text-right">Alokasi Pagu (Rp)</th>
                        <th width="5%" class="text-right"></th>
                    </tr>
                </thead>';
        $html .= '<tbody class="list">';
        $no = 1;
        foreach ($db->result() as $r) ://get alokasi pagu berdasarkan id kegiatan
            $pagu = $this->crud->getWhere('t_pagu', ['fid_uraian' => $r->id])->row();
            $totalPaguAwal = !empty($pagu->total_pagu_awal) ? $pagu->total_pagu_awal : 0;

            $button_hapus = '<td width="5%" class="text-center">
            <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_uraians') . '\',\'URAIAN\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
        </td>';
            $button_pagu = '<td width="10%" class="text-right">
                <div class="text-right">
                <b>' . nominal($totalPaguAwal) . '</b>
            </td>
            <td>
            <button onclick="InputPagu(' . $r->id . ',\'' . base_url('app/programs/input/ref_uraians') . '\',\'' . $totalPaguAwal . '\')" type="button" class="btn btn-info btn-sm rounded m-0 ml-2"><i class="fa fa-money"></i></button>
            </div>
            </td>';
            $html .= '<tr>
                <td class="text-center">
                    ' . $no . '
                </td>
                <td class="kode">
                    ' . $r->kode_kegiatan . ' <br>
                    ' . $r->kode_sub_kegiatan . ' <br>
                    ' . $r->kode . '
                </td>
                <td valign="middle">
                    ' . ucwords($r->nama_kegiatan) . ' <br>
                    ' . ucwords($r->nama_sub_kegiatan) . ' <br>
                    <b class="nama">' . ucwords($r->nama) . '</b>
                </td>
                ' . $button_pagu . '
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div>';

        if ($db->num_rows() > 0) :
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else :
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Uraian</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function getUnor()
    {
        $q = $this->input->post('q');

        $db = $this->crud->getLikes('ref_unors', ['nama' => $q]);
        $all = [];
        if ($db->num_rows() > 0) :
            foreach ($db->result() as $row) :
                $data['id'] = $row->id;
                $data['text'] = $row->id . " - " . $row->nama;
                $all[] = $data;
            endforeach;
        else :
            $all[] = ['id' => 0,  'text' => 'Maaf, Unor "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getListUnor()
    {
        $db = $this->crud->get('ref_unors');
        $all = [];
        if ($db->num_rows() > 0) :
            foreach ($db->result() as $row) :
                $data['id'] = $row->id;
                $data['text'] = $row->id . " - " . $row->nama;
                $all[] = $data;
            endforeach;
        else :
            $all[] = ['id' => 0,  'text' => 'Maaf, Unor "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getParts()
    {
        $q = $this->input->post('q');

        $db = $this->crud->getLikes('ref_parts', ['nama' => $q]);
        $all = [];
        if ($db->num_rows() > 0) :
            foreach ($db->result() as $row) :
                if ($this->session->userdata('role') !== 'ADMIN' && $this->session->userdata('role') !== 'SUPER_ADMIN' && $this->session->userdata('role') !== 'VERIFICATOR') {
                    if ($this->session->userdata('part') !== $row->id) {
                        $data['disabled'] = true;
                        $data['selected'] = false;
                    } else {
                        $data['disabled'] = false;
                        $data['selected'] = true;
                    }
                }

                $data['id'] = $row->id;
                $data['text'] = $row->id . " - " . $row->nama;
                $all[] = $data;
            endforeach;
        else :
            $all[] = ['id' => 0,  'text' => 'Maaf, Bidang / Bagian "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getProgram()
    {
        $q = $this->input->post('q');

        // $db = $this->crud->getLikes('ref_programs', ['nama' => $q]);
        $db = $this->db->select('id,kode,nama')->from('ref_programs')->like('nama', $q)->or_like('kode', $q)->get();
        $all = [];
        if ($db->num_rows() > 0) :
            foreach ($db->result() as $row) :
                $data['id'] = $row->id;
                $data['text'] = $row->kode . " - " . $row->nama;
                $all[] = $data;
            endforeach;
        else :
            $all[] = ['id' => 0,  'text' => 'Maaf, Program "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    function ch_kegiatan($partid, $q)
    {
        $this->load->model('modelselect2', 'select');
        $ch = [];
        $db = $this->select->getChildKegiatan($q, $partid);
        foreach ($db->result() as $k) {
            $data['id'] = $k->id;
            $data['text'] = $k->kode . " - " . $k->nama;
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
            ->like('k.kode', $q)
            ->or_like('k.nama', $q)
            ->group_by('k.fid_part')
            ->get();
        if ($db->num_rows() > 0) :
            $group = [];
            $db_part = $this->crud->get('ref_parts');
            foreach ($db->result() as $row) :
                $data['text'] = $row->partnama;
                $data['children'] = $this->ch_kegiatan($row->partid, $q);
                $group[] = $data;
            endforeach;
        else :
            $group[] = ['id' => 0,  'text' => 'Maaf, Kegiatan "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($group);
    }

    public function tambah($type)
    {
        if ($type === 'unor') {
            $p = $this->input->post();
            $data = [
                'nama' => $p['unor']
            ];
            $db = $this->crud->insert('ref_unors', $data);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }

        if ($type === 'part') {
            $p = $this->input->post();
            $data = [
                'nama' => $p['part'],
                'singkatan' => $p['part_singkatan']
            ];
            $db = $this->crud->insert('ref_parts', $data);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }

        if ($type === 'kegiatan') {
            $p = $this->input->post();
            $data = [
                'fid_part' => $p['part'],
                'fid_program' => $p['program'],
                'kode' => $p['kode_kegiatan'],
                'nama' => $p['kegiatan']
            ];
            $db = $this->crud->insert('ref_kegiatans', $data);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }

        if ($type === 'program') {
            $p = $this->input->post();
            $data = [
                'fid_unor' => $p['unor'],
                'kode' => $p['kode_program'],
                'nama' => $p['program']
            ];
            $db = $this->crud->insert('ref_programs', $data);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }

        if ($type === 'subkegiatan') {
            $p = $this->input->post();
            $data = [
                'fid_kegiatan' => $p['kegiatan'],
                'kode' => $p['kode_subkegiatan'],
                'nama' => $p['subkegiatan']
            ];
            $db = $this->crud->insert('ref_sub_kegiatans', $data);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }

        if ($type === 'uraian') {
            $p = $this->input->post();
            $data = [
                'fid_kegiatan' => $p['kegiatan'],
                'fid_sub_kegiatan' => $p['subkegiatan'],
                'kode' => $p['kode_uraian'],
                'nama' => $p['nama_uraian']
            ];
            $db = $this->crud->insert('ref_uraians', $data);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }
    }

    public function input($type)
    {
        $post = $this->input->post();
        $id = $post['id'];
        $jml = get_only_numbers($post['jumlah']);
        $thn = date('Y');
        
        if($type === 'ref_sub_kegiatans')
        {
            $insert = [
                'fid_part' => $this->session->userdata('part'),
                'fid_sub_kegiatan' => $id,
                'is_perubahan' => 'N',
                'total_pagu_awal' => $jml,
                'tahun' => $thn,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('user_name')
            ];

            $update = [
                'total_pagu_awal' => $jml,
                'tahun' => $thn
            ];

            $whr = [
                'fid_sub_kegiatan' => $id
            ];
        } else if($type === 'ref_uraians') {
            $insert = [
                'fid_part' => $this->session->userdata('part'),
                'fid_uraian' => $id,
                'is_perubahan' => 'N',
                'total_pagu_awal' => $jml,
                'tahun' => $thn,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('user_name')
            ];

            $update = [
                'total_pagu_awal' => $jml,
                'tahun' => $thn
            ];

            $whr = [
                'fid_uraian' => $id
            ];
        }

        $cekid = $this->crud->getWhere('t_pagu', $whr)->num_rows();
        if($cekid > 0) {
            $db = $this->crud->update('t_pagu', $update, $whr);
        } else {
            $db = $this->crud->insert('t_pagu', $insert);
        }
        
        if($db)
        {
            $msg = 200;
        } else {
            $msg = 400;
        }

        echo json_encode($msg);
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

    public function ubah($id, $tbl)
    {
        $detail = $this->detailv2($tbl, $id);
        $data = [
            'title' => $detail->nama,
            'content' => 'pages/admin/' . $tbl,
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
        if ($tbl === 'ref_unors') {
            $input = $this->input->post();
            $data = [
                'nama' => $input['unor']
            ];
            $whr = [
                'id' => $input['id']
            ];
            $db = $this->crud->update($tbl, $data, $whr);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }

        if ($tbl === 'ref_parts') {
            $input = $this->input->post();
            $data = [
                'nama' => $input['part'],
                'singkatan' => $input['part_singkatan']
            ];
            $whr = [
                'id' => $input['id']
            ];
            $db = $this->crud->update($tbl, $data, $whr);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
        }

        if ($tbl === 'ref_programs') {
            $input = $this->input->post();
            $data = [
                'fid_unor' => $input['unor'],
                'nama' => $input['program']
            ];
            $whr = [
                'id' => $id
            ];
            $db = $this->crud->update($tbl, $data, $whr);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
        }
    }

    public function cek_kode($form)
    {
        if ($form === 'subkegiatan') {
            $kode = $this->input->post('kode_subkegiatan');
            $db = $this->crud->getWhere('ref_sub_kegiatans', ['kode' => $kode]);
            if ($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }

        if ($form === 'namaprogram') {
            $kode = $this->input->post('program');
            $db = $this->crud->getWhere('ref_programs', ['nama' => $kode]);
            if ($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }

        if ($form === 'kodeprogram') {
            $kode = $this->input->post('kode_program');
            $db = $this->crud->getWhere('ref_programs', ['kode' => $kode]);
            if ($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }

        if ($form === 'namauraian') {
            $kode = $this->input->post('nama_uraian');
            $kegiatan_id = $this->input->post('ref_kegiatan');
            $db = $this->db->select('u.nama,k.fid_part')
            ->from('ref_uraians AS u')
            ->join('ref_kegiatans AS k', 'u.fid_kegiatan=k.id')
            ->join('ref_parts AS p', 'k.fid_part=p.id')
            ->where('k.fid_part', $this->session->userdata('part'))
            ->where('u.nama', $kode)
            ->get();
            if ($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }

        if ($form === 'kodeuraian') {
            $kode = $this->input->post('kode_uraian');
            $db = $this->db->select('u.kode,k.fid_part')
            ->from('ref_uraians AS u')
            ->join('ref_kegiatans AS k', 'u.fid_kegiatan=k.id')
            ->join('ref_parts AS p', 'k.fid_part=p.id')
            ->where('k.fid_part', $this->session->userdata('part'))
            ->where('u.kode', $kode)
            ->get();
            if ($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }

        if ($form === 'kegiatan') {
            $kode = $this->input->get('kode_kegiatan');
            $db = $this->crud->getWhere('ref_kegiatans', ['kode' => $kode]);
            if ($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }
    }

    public function hapus($type)
    {
        if (isset($type)) {
            $p = $this->input->post();
            $id = $p['id'];
            $db = $this->crud->deleteWhere($type, ['id' => $id]);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
        }
    }
}
