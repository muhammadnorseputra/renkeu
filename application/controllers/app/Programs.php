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
    public $tahun_anggaran;
    public function __construct()
    {
        parent::__construct();
        cek_session();
        //  CEK USER PRIVILAGES 
        if (!privilages('priv_default') && !privilages('priv_programs')) :
            return show_404();
        endif;
        $this->load->model('ModelTarget', 'target');
        $this->tahun_anggaran = $this->session->userdata('tahun_anggaran');
    }

    public function index()
    {
        $data = [
            'title' => 'Program & Kegiatan',
            'content' => 'pages/admin/programs',
            'autoload_js' => [
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/backend/vendors/datatables.net/js/jquery.dataTables.min.js',
                'template/backend/vendors/TreeTables-master/treeTable.js',
                'template/custom-js/list.min.js',
                'template/custom-js/rupiah.js',
                'template/backend/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js'
            ],
            'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css',
                'template/backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
                'template/backend/vendors/TreeTables-master/tree-table.css',
            ]
        ];
        $this->load->view('layout/app', $data);
    }

    public function unor()
    {
        $db = $this->crud->get('ref_unors');

        $btnAdd = '<div class="mb-3">
        <button data-toggle="modal" data-target=".modal-unor" class="btn rounded-0 btn-info"><i class="fa fa-plus mr-2"></i> Tambah : Unor</button>
                </div>
                ';
        $html = $btnAdd;
        $html .= '<div class="table-responsive"><table class="table table-condensed table-hover">';
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
                    <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_unor') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
                </td>
                <td width="5%" class="text-center"><button onclick="Edit(' . $r->id . ',\'' . base_url("app/programs/detail/ref_unors") . '\',\'.modal-unor-edit\')" type="button" class="btn btn-sm btn-light m-0"><i class="fa fa-pencil"></i></button></td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div>';

        if ($db->num_rows() > 0) :
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else :
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Parts</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function part()
    {
        $db = $this->crud->get('ref_parts');

        $btnAdd = '<div class="mb-3">
        <button data-toggle="modal" data-target=".modal-part" class="btn rounded-0 btn-primary"><i class="fa fa-plus mr-2"></i> Tambah : Part</button>
                </div>
                ';
        $html = $btnAdd;
        $html .= '<div class="table-responsive"><table class="table table-condensed table-hover">';
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
        $html .= '</table></div>';

        if ($db->num_rows() > 0) :
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else :
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Parts</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function tujuan()
    {
        $db = $this->crud->getWhere('ref_tujuan', ['tahun' => $this->session->userdata('tahun_anggaran')]);

        $btnAdd = '<div class="mb-3">
        <button data-toggle="modal" data-target=".modal-tujuan" class="btn rounded-0 btn-info"><i class="fa fa-plus mr-2"></i> Tambah Tujuan</button>
                </div>
                ';
        $html = $btnAdd;
        $html .= '<div class="table-responsive"><table class="table table-condensed table-hover">';
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
                    <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_tujuan') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
                </td>
                <td width="5%" class="text-center"><button onclick="Edit(' . $r->id . ',\'' . base_url("app/programs/detail/ref_tujuan") . '\',\'.modal-tujuan-edit\')" type="button" class="btn btn-sm btn-light m-0"><i class="fa fa-pencil"></i></button></td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div>';

        if ($db->num_rows() > 0) :
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else :
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Tujuan</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function sasaran()
    {
        $db = $this->crud->getWhere('ref_sasaran', ['tahun' => $this->session->userdata('tahun_anggaran')]);

        $btnAdd = '<div class="mb-3">
        <button data-toggle="modal" data-target=".modal-sasaran" class="btn rounded-0 btn-primary"><i class="fa fa-plus mr-2"></i> Tambah Sasaran</button>
                </div>
                ';
        $html = $btnAdd;
        $html .= '<div class="table-responsive"><table class="table table-condensed table-hover">';
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
                    <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_sasaran') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
                </td>
                <td width="5%" class="text-center"><button onclick="Edit(' . $r->id . ',\'' . base_url("app/programs/detail/ref_sasaran") . '\',\'.modal-sasaran-edit\')" type="button" class="btn btn-sm btn-light m-0"><i class="fa fa-pencil"></i></button></td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div>';

        if ($db->num_rows() > 0) :
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else :
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Sasaran</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function program()
    {
        $db = $this->target->program(null, $this->session->userdata('part'), $this->session->userdata('tahun_anggaran'));
        if ($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'ADMIN') :
            $btnAdd = '<div>
            <button data-toggle="modal" data-target=".modal-program" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        ';
        else :
            $btnAdd = '';
        endif;
        $search = '<div class="col-5 col-md-3">Pencarian <input type="text" class="search form-control" /></div>';
        $pagging = '<div class="col-4 col-md-6">Halaman <ul class="pagination"></ul></div>';

        $html = '<div id="listProgram"><div class="row">' . $search . $pagging . $btnAdd . "</div>";
        $html .= '<div class="table-responsive"><table class="table table-condensed table-hover table-bordered">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Rekening</th>
                        <th>Nama Program</th>
                        <th class="text-center">Ubah</th>
                        <th class="text-right">Alokasi Anggaran Awal (Rp)</th>
                        <th class="text-right">Alokasi Anggaran Perubahan (Rp)</th>
                    </tr>
                    </thead>';
        $html .= '<tbody class="list">';
        $no = 1;
        foreach ($db->result() as $r) :

            $totalPaguAwal = !empty($this->target->getAlokasiPaguProgram($r->id, "0", $this->tahun_anggaran)->row()->total_pagu_awal) ? $this->target->getAlokasiPaguProgram($r->id, "0", $this->tahun_anggaran)->row()->total_pagu_awal : 0;
            $totalPaguPerubahan = !empty($this->target->getAlokasiPaguProgram($r->id, "1", $this->tahun_anggaran)->row()->total_pagu_awal) ? $this->target->getAlokasiPaguProgram($r->id, "1", $this->tahun_anggaran)->row()->total_pagu_awal : 0;
            $button_hapus = '<td width="5%" class="text-center">
            <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_programs') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
        </td>';

            $disabled_edit = ($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'ADMIN') ? '' : 'disabled';
            $button_edit = '<td width="5%" class="text-center">
                <button onclick="window.location.href = \'' . base_url('app/programs/ubah/' . $r->id . '/ref_programs') . '\'" type="button" class="btn btn-info btn-sm rounded-0 m-0" ' . $disabled_edit . '><i class="fa fa-pencil"></i></button>
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
                ' . $button_edit . '
                <td class="text-right">
                    <b>' . @nominal($totalPaguAwal) . '</b>
                </td>
                <td class="text-right">
                    <b>' . @nominal($totalPaguPerubahan) . '</b>
                </td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div></div>';

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
            $db = $this->db->order_by('kode', 'asc')->where('tahun', $this->session->userdata('tahun_anggaran'))->get('ref_kegiatans');
        else :
            $db = $this->db->order_by('kode', 'asc')->where('fid_part', $this->session->userdata('part'))->where('tahun', $this->session->userdata('tahun_anggaran'))->get('ref_kegiatans');
        endif;

        $btnAdd = '<div>
            <button data-toggle="modal" data-target=".modal-kegiatan" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button></div>
        ';
        $search = '<div class="col-5 col-md-3">Pencarian <input type="text" class="search form-control" /></div>';
        $pagging = '<div class="col-4 col-md-6">Halaman <ul class="pagination"></ul></div>';

        $html = '<div id="listKegiatan"><div class="row">' . $search . $pagging . $btnAdd . "</div>";
        $html .= '<div class="table-responsive"><table class="table table-condensed table-hover table-bordered">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Rekening</th>
                        <th>Nama Kegiatan</th>
                        <th>Ubah</th>
                        <th class="text-right">Alokasi Anggaran Awal (Rp)</th>
                        <th class="text-right">Alokasi Anggaran Perubahan (Rp)</th>
                    </tr>
                </thead>';
        $html .= '<tbody class="list">';
        $no = 1;
        foreach ($db->result() as $r) :
            $totalPaguAwal = !empty($this->target->getAlokasiPaguKegiatan($r->id, "0", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal) ? $this->target->getAlokasiPaguKegiatan($r->id, "0", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal : 0;
            $totalPaguPerubahan = !empty($this->target->getAlokasiPaguKegiatan($r->id, "1", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal) ? $this->target->getAlokasiPaguKegiatan($r->id, "1", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal : 0;
            $hapus = '
            <td width="5%" class="text-center">
                <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_kegiatans') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
            </td>';

            $button_edit = '<td width="5%" class="text-center">
                <a href="' . base_url('app/programs/ubah/' . $r->id . '/ref_kegiatans') . '" type="button" class="btn btn-info btn-sm rounded-0 m-0"><i class="fa fa-pencil"></i></a>
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
                ' . $button_edit . '
                <td class="text-right">
                    <b>' . @nominal($totalPaguAwal) . '</b>
                </td>
                <td class="text-right">
                    <b>' . @nominal($totalPaguPerubahan) . '</b>
                </td>
            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div></div>';

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
                ->where('tahun', $this->session->userdata('tahun_anggaran'))
                ->get('ref_sub_kegiatans AS sub');
        else :
            $db = $this->db->select('sub.id,sub.fid_kegiatan,sub.kode,sub.nama')
                ->order_by('sub.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id', 'inner')
                ->where('keg.fid_part', $this->session->userdata('part'))
                ->where('sub.tahun', $this->session->userdata('tahun_anggaran'))
                ->get('ref_sub_kegiatans AS sub');
        endif;
        $btnAdd = '<div>
                <button data-toggle="modal" data-target=".modal-subkegiatan" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button></div>
            ';
        $search = '<div class="col-6 col-md-3">Pencarian <input type="text" class="fuzzy-search form-control" /></div>';
        $pagging = '<div class="col-6 col-md-6">Halaman <ul class="pagination"></ul></div>';

        $html = '<div id="listSubKegiatan"><div class="row">' . $search . $pagging . $btnAdd . "</div>";
        $html .= '<div class="table-responsive"><table class="table table-condensed table-hover table-bordered">';
        $html .= '<thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode Rekening</th>
                                <th>Nama Sub Kegiatan</th>
                                <th width="5%" class="text-right">Ubah</th>
                                <th class="text-right">Alokasi Pagu Awal (Rp)</th>
                                <th class="text-right">Alokasi Pagu Perubahan (Rp)</th>
                            </tr>
                        </thead>';
        $html .= '<tbody class="list">';
        $no = 1;
        foreach ($db->result() as $r) :
            //get alokasi pagu berdasarkan id kegiatan
            // $pagu = $this->crud->getWhere('t_pagu', ['fid_sub_kegiatan' => $r->id])->row();
            // $totalPaguAwal = !empty($pagu->total_pagu_awal) ? $pagu->total_pagu_awal : 0;
            $totalPaguAwal = !empty($this->target->getAlokasiPaguSubKegiatan($r->id, "0", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal) ? $this->target->getAlokasiPaguSubKegiatan($r->id, "0", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal : 0;
            $totalPaguPerubahan = !empty($this->target->getAlokasiPaguSubKegiatan($r->id, "1", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal) ? $this->target->getAlokasiPaguSubKegiatan($r->id, "1", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal : 0;

            $button_hapus = '<td width="5%" class="text-center">
                <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_sub_kegiatans') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
            </td>';
            $button_edit = '<td width="5%" class="text-center">
                <a href="' . base_url('app/programs/ubah/' . $r->id . '/ref_sub_kegiatans') . '" type="button" class="btn btn-info btn-sm rounded-0 m-0"><i class="fa fa-pencil"></i></a>
            </td>';
            $alokasi_pagu = nominal($totalPaguAwal);
            $alokasi_pagu_perubahan = nominal($totalPaguPerubahan);
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
                    ' . $button_edit . '
                    <td class="text-right">
                    <b>' . $alokasi_pagu . '</b>
                    </td>
                    <td class="text-right">
                    <b>' . $alokasi_pagu_perubahan . '</b>
                    </td>
                </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div></div>';

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
                ->where('u.tahun', $this->session->userdata('tahun_anggaran'))
                ->get('ref_uraians AS u');
        else :
            $db = $this->db->select('u.id,u.fid_kegiatan,u.kode,u.nama,keg.kode AS kode_kegiatan,keg.nama AS nama_kegiatan, sub.kode AS kode_sub_kegiatan,sub.nama AS nama_sub_kegiatan')
                ->order_by('u.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'u.fid_kegiatan=keg.id', 'inner')
                ->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id', 'inner')
                ->where('keg.fid_part', $this->session->userdata('part'))
                ->where('u.tahun', $this->session->userdata('tahun_anggaran'))
                ->get('ref_uraians AS u');
        endif;

        $btnAdd = '<div>
            <button data-toggle="modal" data-target=".modal-uraian" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button></div>
        ';
        $search = '<div class="col-5 col-md-3">Pencarian <input type="text" class="search form-control" /></div>';
        $pagging = '<div class="col-4 col-md-6">Halaman <ul class="pagination"></ul></div>';

        $html = '<div id="listUraian"><div class="row">' . $search . $pagging . $btnAdd . "</div>";
        $html .= '<div class="table-responsive"><table class="table table-condensed table-hover table-bordered">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Rekening</th>
                        <th>Nama Kegiatan/Sub Kegiatan/Uraian</th>
                        <th>Total SPJ</th>
                        <th class="text-center" colspan="2">Ubah | Hapus</th>
                        <th class="text-right" colspan="2">Alokasi Pagu Awal (Rp)</th>
                        <th class="text-right" colspan="2">Alokasi Pagu Perubahan (Rp)</th>
                    </tr>
                </thead>';
        $html .= '<tbody class="list">';
        $no = 1;
        $total_all_pagu = 0;
        $total_all_pagu_perubahan = 0;
        foreach ($db->result() as $r) :
            //get jumlah spj berdasarkan id uraian
            $jmlSpj = $this->crud->getWhere('spj', ['fid_uraian' => $r->id])->num_rows();
            //get alokasi pagu berdasarkan id kegiatan
            $pagu = $this->crud->getWhere('t_pagu', ['fid_uraian' => $r->id, 'is_perubahan' => '0'])->row();
            $paguPerubahan = $this->crud->getWhere('t_pagu', ['fid_uraian' => $r->id, 'is_perubahan' => '1'])->row();

            $totalPaguPerubahan = !empty($paguPerubahan->total_pagu_awal) ? $paguPerubahan->total_pagu_awal : 0;
            $totalPaguAwal = !empty($pagu->total_pagu_awal) ? $pagu->total_pagu_awal : 0;


            if ($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'SUPER_USER' || $this->session->userdata('role') === 'VERIFICATOR'):
                $button_hapus = '<td width="5%" class="text-center">
            <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_uraians') . '\',\'URAIAN\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
        </td>';
            else:
                $button_hapus = '<td></td>';
            endif;
            $button_edit = '<td width="5%" class="text-center">
                <a href="' . base_url('app/programs/ubah/' . $r->id . '/ref_uraians') . '" type="button" class="btn btn-info btn-sm rounded-0 m-0"><i class="fa fa-pencil"></i></a>
                ' . $button_hapus . '
            </td>';

            // Pagu Awal
            $is_disabled_pagu_awal = $this->session->userdata('is_perubahan') === "1" ? 'disabled' : '';
            $total_all_pagu += $totalPaguAwal;
            $button_pagu = '<td width="10%" class="text-right"
                <div class="text-right">
                <b>' . nominal($totalPaguAwal) . '</b>
            </td>
            <td class="text-center">
            <button onclick="InputPagu(' . $r->id . ',\'' . base_url('app/programs/input/ref_uraians') . '\',\'' . $totalPaguAwal . '\',0)" type="button" class="btn btn-info btn-sm rounded m-0" ' . $is_disabled_pagu_awal . '><i class="fa fa-money"></i></button>
            </div>
            </td>';

            // Pagu Perubahan
            $is_disabled_pagu_perubahan = $this->session->userdata('is_perubahan') === "0" ? 'disabled' : '';

            $total_all_pagu_perubahan += $totalPaguPerubahan;
            $button_pagu_perubahan = '<td width="10%" class="text-right">
                <div class="text-right">
                <b>' . nominal($totalPaguPerubahan) . '</b>
            </td>
            <td class="text-center">
            <button onclick="InputPagu(' . $r->id . ',\'' . base_url('app/programs/input/ref_uraians') . '\',\'' . $totalPaguPerubahan . '\',1)" type="button" class="btn btn-info btn-sm rounded m-0" ' . $is_disabled_pagu_perubahan . '><i class="fa fa-money"></i></button>
            </div>
            </td>';
            $html .= '<tr>
                <td class="text-center">
                    ' . $no . '
                </td>
                <td>
                    ' . $r->kode_kegiatan . ' <br>
                    ' . $r->kode_sub_kegiatan . ' <br>
                    <b class="kode">' . $r->kode . '</b>
                </td>
                <td valign="middle">
                    ' . ucwords($r->nama_kegiatan) . ' <br>
                    ' . ucwords($r->nama_sub_kegiatan) . ' <br>
                    <b class="nama">' . ucwords($r->nama) . '</b>
                </td>
                <td class="text-center">' . $jmlSpj . '</td>
                ' . $button_edit . '
                ' . $button_pagu . '
                ' . $button_pagu_perubahan . '
            </tr>';
            $no++;
        endforeach;
        $html .= '
            <tr>
                <td colspan="6" class="text-right align-middle"><b>Total</b></td>
                <td colspan="2" class="text-left"><b>Rp. ' . nominal($total_all_pagu) . '</b></td>
                <td colspan="2" class="text-left"><b>Rp. ' . nominal($total_all_pagu_perubahan) . '</b></td>
            </tr>
        ';
        $html .= '</tbody>';
        $html .= '</table></div></div>';

        if ($db->num_rows() > 0) :
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else :
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Uraian</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function getTujuan()
    {
        $q = $this->input->post('q');

        // $db = $this->crud->getLikes('ref_tujuan', ['nama' => $q]);
        $db = $this->db->like('nama', $q)->where('tahun', $this->tahun_anggaran)->get('ref_tujuan');
        $all = [];
        if ($db->num_rows() > 0) :
            foreach ($db->result() as $row) :
                $data['id'] = $row->id;
                $data['text'] = $row->id . " - " . $row->nama;
                $all[] = $data;
            endforeach;
        else :
            $all[] = ['id' => 0,  'text' => 'Maaf, Tujuan "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($all);
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

    public function getSasaran()
    {
        $q = $this->input->post('q');

        $db = $this->crud->getLikes('ref_sasaran', ['nama' => $q]);
        $all = [];
        if ($db->num_rows() > 0) :
            foreach ($db->result() as $row) :
                $data['id'] = $row->id;
                $data['text'] = $row->id . " - " . $row->nama;
                $all[] = $data;
            endforeach;
        else :
            $all[] = ['id' => 0,  'text' => 'Maaf, Sasaran "' . strtoupper($q) . '" tidak ditemukan.'];
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
            $all[] = ['id' => 0,  'text' => 'Maaf, Unor tidak ditemukan.'];
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
        $partid = $this->session->userdata('part');

        // $db = $this->crud->getLikes('ref_programs', ['nama' => $q]);

        if ($this->session->userdata('role') === 'USER') :
            $db = $this->db->select('p.id,p.kode,p.nama,p.tahun')
                ->from('ref_programs AS p')
                ->join('ref_parts AS q', "FIND_IN_SET({$partid}, p.fid_part) > 0", 'inner')
                ->group_start()
                ->like('p.nama', $q)
                ->or_like('p.kode', $q)
                ->group_by('p.id')
                ->group_end()
                ->where('p.tahun', $this->session->userdata('tahun_anggaran'))
                ->get();
        else:
            $db = $this->db->select('p.id,p.kode,p.nama,p.tahun')
                ->from('ref_programs AS p')
                ->group_start()
                ->like('p.nama', $q)
                ->or_like('p.kode', $q)
                ->group_by('p.id')
                ->group_end()
                ->where('p.tahun', $this->session->userdata('tahun_anggaran'))
                ->get();
        endif;
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
        $this->load->model('ModelSelect2', 'select');
        $ch = [];
        $db = $this->select->getChildKegiatan($q, $partid, $this->session->userdata('tahun_anggaran'));
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
        if ($this->session->userdata('role') !== 'VERIFICATOR' && $this->session->userdata('role') !== 'SUPER_USER' && $this->session->userdata('role') !== 'SUPER_ADMIN' && $this->session->userdata('role') !== 'ADMIN') :
            $db = $this->db->select('k.*,p.nama AS partnama, p.id AS partid')
                ->from('ref_kegiatans AS k')
                ->join('ref_parts AS p', 'k.fid_part=p.id', 'inner')
                ->where('p.id', $this->session->userdata('part'))
                ->where('k.tahun', $this->session->userdata('tahun_anggaran'))
                ->group_by('k.fid_part')
                ->get();
        else :
            $db = $this->db->select('k.*,p.nama AS partnama, p.id AS partid')
                ->from('ref_kegiatans AS k')
                ->join('ref_parts AS p', 'k.fid_part=p.id', 'inner')
                ->where('k.tahun', $this->session->userdata('tahun_anggaran'))
                ->like('k.kode', $q)
                ->or_like('k.nama', $q)
                ->group_by('k.fid_part')
                ->get();
        endif;
        if ($db->num_rows() > 0) :
            $group = [];
            // $db_part = $this->crud->get('ref_parts');
            foreach ($db->result() as $row) :
                $data['text'] = $row->partnama;
                // $data['children'] = $this->ch_kegiatan($row->partid, $q);
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

        if ($type === 'tujuan') {
            $p = $this->input->post();
            $data = [
                'fid_unor' => $p['unor'],
                'nama' => $p['tujuan'],
                'tahun' => $this->session->userdata('tahun_anggaran')
            ];
            $db = $this->crud->insert('ref_tujuan', $data);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }

        if ($type === 'sasaran') {
            $p = $this->input->post();
            $data = [
                'fid_tujuan' => $p['tujuan'],
                'nama' => $p['sasaran'],
                'tahun' => $this->session->userdata('tahun_anggaran')
            ];
            $db = $this->crud->insert('ref_sasaran', $data);
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
                'fid_program' => $p['program'],
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
                'nama' => $p['kegiatan'],
                'tahun' => $this->session->userdata('tahun_anggaran')
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
                'fid_part' => implode(",", $p['bidang']),
                'fid_sasaran' => $p['sasaran'],
                'kode' => $p['kode_program'],
                'nama' => $p['program'],
                'tahun' => $this->session->userdata('tahun_anggaran')
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
                'nama' => $p['subkegiatan'],
                'tahun' => $this->session->userdata('tahun_anggaran')
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
                'nama' => $p['nama_uraian'],
                'tahun' => $this->session->userdata('tahun_anggaran')
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
        $thn = $this->session->userdata('tahun_anggaran');

        if ($type === 'ref_sub_kegiatans') {
            $insert = [
                'fid_part' => $this->session->userdata('part'),
                'fid_sub_kegiatan' => $id,
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
        } else if ($type === 'ref_uraians') {

            if ($post['is_perubahan'] === "1") {
                $insert = [
                    'is_perubahan' => "1",
                    'fid_part' => $this->session->userdata('part'),
                    'fid_uraian' => $id,
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
                    'fid_uraian' => $id,
                    'is_perubahan' => "1"
                ];
            } else {
                $insert = [
                    'fid_part' => $this->session->userdata('part'),
                    'fid_uraian' => $id,
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
                    'fid_uraian' => $id,
                    'is_perubahan' => "0"
                ];
            }
        }

        $cekid = $this->crud->getWhere('t_pagu', $whr)->num_rows();
        if ($cekid > 0) {
            $db = $this->crud->update('t_pagu', $update, $whr);
        } else {
            $db = $this->crud->insert('t_pagu', $insert);
        }

        if ($db) {
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
                'fid_program' => $input['program'],
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

        if ($tbl === 'ref_tujuan') {
            $input = $this->input->post();
            $data = [
                'nama' => $input['tujuan'],
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

        if ($tbl === 'ref_sasaran') {
            $input = $this->input->post();
            $data = [
                'nama' => $input['sasaran'],
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

        // if ($tbl === 'ref_programs') {
        //     $input = $this->input->post();
        //     $data = [
        //         'fid_unor' => $input['unor'],
        //         'nama' => $input['program']
        //     ];
        //     $whr = [
        //         'id' => $id
        //     ];
        //     $db = $this->crud->update($tbl, $data, $whr);
        //     if ($db) {
        //         $msg = 200;
        //     } else {
        //         $msg = 400;
        //     }
        //     echo json_encode($msg);
        // }

        if ($tbl === 'ref_sub_kegiatans') {
            $input = $this->input->post();
            if (isset($input['kegiatan'])) {
                $data = [
                    'fid_kegiatan' => $input['kegiatan'],
                    'kode' => $input['kode_subkegiatan'],
                    'nama' => $input['subkegiatan']
                ];
            } else {
                $data = [
                    'kode' => $input['kode_subkegiatan'],
                    'nama' => $input['subkegiatan']
                ];
            }
            $whr = [
                'id' => $input['uid']
            ];
            $db = $this->crud->update($tbl, $data, $whr);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
        }

        if ($tbl === 'ref_kegiatans') {
            $input = $this->input->post();
            if (isset($input['program'])) {
                $data = [
                    'fid_program' => $input['program'],
                    'kode' => $input['kode_kegiatan'],
                    'nama' => $input['kegiatan']
                ];
            } else {
                $data = [
                    'kode' => $input['kode_kegiatan'],
                    'nama' => $input['kegiatan']
                ];
            }
            $whr = [
                'id' => $input['uid']
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
            if (isset($input['sasaran'])) {
                $data = [
                    'fid_part' => implode(",", $input['bidang']),
                    'fid_sasaran' => $input['sasaran'],
                    'kode' => $input['kode_program'],
                    'nama' => $input['program'],

                ];
            } else {
                $data = [
                    'fid_part' => implode(",", $input['bidang']),
                    'kode' => $input['kode_program'],
                    'nama' => $input['program']
                ];
            }
            $whr = [
                'id' => $input['uid']
            ];
            $db = $this->crud->update($tbl, $data, $whr);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
        }

        if ($tbl === 'ref_uraians') {
            $input = $this->input->post();
            if (isset($input['kegiatan']) && isset($input['subkegiatan'])) {
                $data = [
                    'fid_kegiatan' => $input['kegiatan'],
                    'fid_sub_kegiatan' => $input['subkegiatan'],
                    'kode' => $input['kode_uraian'],
                    'nama' => $input['nama_uraian']
                ];
            } else {
                $data = [
                    'kode' => $input['kode_uraian'],
                    'nama' => $input['nama_uraian']
                ];
            }
            $whr = [
                'id' => $input['uid']
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
