<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

defined('BASEPATH') or exit('No direct script access allowed');

class Programs extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
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
        if (! privilages('priv_default') && ! privilages('priv_programs') || $this->session->userdata('is_valid_profile') === "0"):
            return show_404();
        endif;
        $this->load->model('ModelTarget', 'target');
        $this->tahun_anggaran = $this->session->userdata('tahun_anggaran');
    }

    public function index()
    {
        $data = [
            'title'        => 'Program & Kegiatan',
            'content'      => 'pages/admin/programs',
            'autoload_js'  => [
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/backend/vendors/datatables.net/js/jquery.dataTables.min.js',
                'template/backend/vendors/TreeTables-master/treeTable.js',
                'template/custom-js/list.min.js',
                'template/custom-js/list-state.js',
                'template/custom-js/rupiah.js',
                'template/backend/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js',
                'template/custom-js/blockUI/jquery.blockUI.js',
            ],
            'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css',
                'template/backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
                'template/backend/vendors/TreeTables-master/tree-table.css',
            ],
        ];
        $this->load->view('layout/app', $data);
    }

    public function unor()
    {
        $db = $this->crud->get('ref_unors');

        $btnAdd  = '<div class="mb-3">
        <button data-toggle="modal" data-target=".modal-unor" class="btn rounded-0 btn-info"><i class="fa fa-plus mr-2"></i> Tambah : Unor</button>
                </div>
                ';
        $html  = $btnAdd;
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action">';
        $html .= '<thead><tr><th class="text-center">No</th><th>Judul</th><th>Hapus</th><th>Edit</th></tr></thead>';
        $html .= '<tbody>';
        $no    = 1;
        foreach ($db->result() as $r):
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

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Parts</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function part()
    {
        $db = $this->crud->get('ref_parts');

        $btnAdd  = '<div class="mb-3">
        <button data-toggle="modal" data-target=".modal-part" class="btn rounded-0 btn-primary"><i class="fa fa-plus mr-2"></i> Tambah : Part</button>
                </div>
                ';
        $html  = $btnAdd;
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action">';
        $html .= '<thead><tr><th class="text-center">No</th><th>Judul</th><th>Hapus</th><th>Edit</th></tr></thead>';
        $html .= '<tbody>';
        $no    = 1;
        foreach ($db->result() as $r):
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

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Parts</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function tujuan()
    {
        $db = $this->crud->getWhere('ref_tujuan', ['tahun' => $this->session->userdata('tahun_anggaran')]);

        $btnAdd  = '<div class="mb-3">
        <button data-toggle="modal" data-target=".modal-tujuan" class="btn rounded-0 btn-info"><i class="fa fa-plus mr-2"></i> Tambah Tujuan</button>
                </div>
                ';
        $html  = $btnAdd;
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action">';
        $html .= '<thead><tr><th class="text-center">No</th><th>Judul</th><th>Hapus</th><th>Edit</th></tr></thead>';
        $html .= '<tbody>';
        $no    = 1;
        foreach ($db->result() as $r):
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

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Tujuan</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function sasaran()
    {
        $db = $this->crud->getWhere('ref_sasaran', ['tahun' => $this->session->userdata('tahun_anggaran')]);

        $btnAdd  = '<div class="mb-3">
        <button data-toggle="modal" data-target=".modal-sasaran" class="btn rounded-0 btn-primary"><i class="fa fa-plus mr-2"></i> Tambah Sasaran</button>
                </div>
                ';
        $html  = $btnAdd;
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action">';
        $html .= '<thead><tr><th class="text-center">No</th><th>Judul</th><th>Hapus</th><th>Edit</th></tr></thead>';
        $html .= '<tbody>';
        $no    = 1;
        foreach ($db->result() as $r):
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

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Sasaran</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    private function onlyAdminBelanja()
    {
        $role = $this->session->userdata('role');
        if ($role !== 'ADMIN' && $role !== 'SUPER_ADMIN') {
            return show_404();
        }
    }

    // tabel referensi belanja (kelompok & jenis) hanya untuk ADMIN/SUPER_ADMIN
    private function onlyAdminBelanjaTable($type)
    {
        if (in_array($type, ['ref_kelompok_belanja', 'ref_jenis_belanja'], true)) {
            $this->onlyAdminBelanja();
        }
    }

    public function jenis()
    {
        $this->onlyAdminBelanja();
        $this->db->select('j.*, k.nama as kelompok_nama, k.kode as kode_kelompok');
        $this->db->from('ref_jenis_belanja j');
        $this->db->join('ref_kelompok_belanja k', 'j.fid_kelompok_belanja = k.id', 'left');
        $this->db->where('j.tahun', $this->session->userdata('tahun_anggaran'));
        $db = $this->db->get();

        // agregat pagu awal & perubahan dari t_pagu via uraian
        $paguAwal = [];
        $dbA      = $this->db->select('u.fid_jenis_belanja, SUM(p.total_pagu_awal) AS total')
            ->join('ref_uraians u', 'p.fid_uraian=u.id')
            ->where('p.is_perubahan', '0')
            ->where('u.tahun', $this->session->userdata('tahun_anggaran'))
            ->group_by('u.fid_jenis_belanja')
            ->get('t_pagu p');
        foreach ($dbA->result() as $row):
            $paguAwal[$row->fid_jenis_belanja] = $row->total;
        endforeach;

        $paguPerubahan = [];
        $dbB           = $this->db->select('u.fid_jenis_belanja, SUM(p.total_pagu_awal) AS total')
            ->join('ref_uraians u', 'p.fid_uraian=u.id')
            ->where('p.is_perubahan', '1')
            ->where('u.tahun', $this->session->userdata('tahun_anggaran'))
            ->group_by('u.fid_jenis_belanja')
            ->get('t_pagu p');
        foreach ($dbB->result() as $row):
            $paguPerubahan[$row->fid_jenis_belanja] = $row->total;
        endforeach;

        $btnAdd  = '<div class="float-right">
        <button data-toggle="modal" data-target=".modal-jenis" class="btn rounded-0 btn-info mt-3"><i class="fa fa-plus mr-2"></i> Tambah Jenis Belanja</button>
                </div>
                ';
        $search = '<div class="col-5 col-md-4">Pencarian <div class="input-group">
                        <input type="text" class="search form-control" />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary rounded-0 reset-listjs" data-target="listJenis" title="Reset pencarian &amp; halaman"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div></div>';
        $pagging    = '<div class="col-4 col-md-3">Halaman <ul class="pagination"></ul></div>';
        $btnOptions = '<div class="col-md-5">' . $btnAdd . '</div>';

        $total_awal        = 0;
        $total_perubahan   = 0;

        $html  = '<div id="listJenis"><div class="row">' . $search . $pagging . $btnOptions . "</div>";
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action">';
        $html .= '<thead><tr><th class="text-center">No</th><th>Kode Kelompok</th><th>Kelompok</th><th>Kode</th><th>Judul</th><th class="text-right">Alokasi Anggaran Awal (Rp)</th><th class="text-right">Alokasi Anggaran Perubahan (Rp)</th><th class="text-right">Selisih (Berkurang / Bertambah)</th><th class="text-center">Uraian</th><th>Hapus</th><th>Edit</th></tr></thead>';
        $html .= '<tbody class="list">';
        $no    = 1;
        foreach ($db->result() as $r):
            $pAwal      = $paguAwal[$r->id] ?? 0;
            $pPerubahan = $paguPerubahan[$r->id] ?? 0;
            $total_awal += $pAwal;
            $total_perubahan += $pPerubahan;

            $selisih     = $pPerubahan - $pAwal;
            $tanda       = $selisih > 0 ? '+' : ($selisih < 0 ? '-' : '');
            $warnaClass  = $selisih > 0 ? 'text-success' : ($selisih < 0 ? 'text-danger' : '');
            $hasil       = $tanda . nominal(abs($selisih));

            $html .= '<tr>
						                <td class="text-center">
						                    ' . $no . '
						                </td>
						                <td class="kode_kelompok">
						                    <div class="d-flex align-items-center">
						                        <span class="mr-2">' . htmlspecialchars($r->kode_kelompok) . '</span>
						                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1 copy-code" data-copy="' . htmlspecialchars($r->kode_kelompok) . '" title="Salin kode kelompok" style="line-height:1"><i class="fa fa-fw fa-copy"></i></button>
						                    </div>
						                </td>
						                <td class="kelompok">
						                    ' . htmlspecialchars($r->kelompok_nama) . '
						                </td>
						                <td class="kode">
						                    <div class="d-flex align-items-center">
						                        <span class="mr-2">' . htmlspecialchars($r->kode) . '</span>
						                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1 copy-code" data-copy="' . htmlspecialchars($r->kode) . '" title="Salin kode jenis" style="line-height:1"><i class="fa fa-fw fa-copy"></i></button>
						                    </div>
						                </td>
						                <td class="nama">
						                    ' . htmlspecialchars($r->nama) . '
						                </td>
						                <td class="text-right">Rp. ' . nominal($pAwal) . '</td>
						                <td class="text-right">Rp. ' . nominal($pPerubahan) . '</td>
						                <td class="text-right"><b class="' . $warnaClass . '">' . $hasil . '</b></td>
						                <td width="5%" class="text-center">
						                    <button data-id="' . $r->id . '" data-nama="' . htmlspecialchars($r->nama, ENT_QUOTES) . '" type="button" class="btn btn-info btn-sm rounded-0 m-0 btn-detail-uraian" title="Lihat uraian terkait"><i class="fa fa-eye"></i></button>
						                </td>
						                <td width="5%" class="text-center">
						                    <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_jenis_belanja') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
						                </td>
						                <td width="5%" class="text-center"><button onclick="Edit(' . $r->id . ',\'' . base_url("app/programs/detail/ref_jenis_belanja") . '\',\'.modal-jenis-edit\')" type="button" class="btn btn-sm btn-light m-0"><i class="fa fa-pencil"></i></button></td>
						            </tr>';
            $no++;
        endforeach;
        $selisihTotal     = $total_perubahan - $total_awal;
        $tandaTotal       = $selisihTotal > 0 ? '+' : ($selisihTotal < 0 ? '-' : '');
        $warnaClassTotal  = $selisihTotal > 0 ? 'text-success' : ($selisihTotal < 0 ? 'text-danger' : '');
        $hasilTotal       = $tandaTotal . nominal(abs($selisihTotal));

        $html .= '
            <tr>
                <td colspan="5" class="text-right align-middle"><b>Total</b></td>
                <td class="text-right align-middle"><b>Rp. ' . nominal($total_awal) . '</b></td>
                <td class="text-right align-middle"><b>Rp. ' . nominal($total_perubahan) . '</b></td>
                <td class="text-right align-middle"><b class="' . $warnaClassTotal . '">' . $hasilTotal . '</b></td>
                <td colspan="3"></td>
            </tr>
        ';
        $html .= '</tbody>';
        $html .= '</table></div></div>';

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Jenis Belanja</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function kelompok()
    {
        $this->onlyAdminBelanja();
        $db = $this->crud->getWhere('ref_kelompok_belanja', ['tahun' => $this->session->userdata('tahun_anggaran')]);

        // agregat pagu awal & perubahan dari t_pagu via uraian -> jenis -> kelompok
        $paguAwal = [];
        $dbA      = $this->db->select('j.fid_kelompok_belanja, SUM(p.total_pagu_awal) AS total')
            ->join('ref_uraians u', 'p.fid_uraian=u.id')
            ->join('ref_jenis_belanja j', 'u.fid_jenis_belanja=j.id')
            ->where('p.is_perubahan', '0')
            ->where('u.tahun', $this->session->userdata('tahun_anggaran'))
            ->group_by('j.fid_kelompok_belanja')
            ->get('t_pagu p');
        foreach ($dbA->result() as $row):
            $paguAwal[$row->fid_kelompok_belanja] = $row->total;
        endforeach;

        $paguPerubahan = [];
        $dbB           = $this->db->select('j.fid_kelompok_belanja, SUM(p.total_pagu_awal) AS total')
            ->join('ref_uraians u', 'p.fid_uraian=u.id')
            ->join('ref_jenis_belanja j', 'u.fid_jenis_belanja=j.id')
            ->where('p.is_perubahan', '1')
            ->where('u.tahun', $this->session->userdata('tahun_anggaran'))
            ->group_by('j.fid_kelompok_belanja')
            ->get('t_pagu p');
        foreach ($dbB->result() as $row):
            $paguPerubahan[$row->fid_kelompok_belanja] = $row->total;
        endforeach;

        $btnAdd  = '<div class="float-right">
        <button data-toggle="modal" data-target=".modal-kelompok" class="btn rounded-0 btn-info mt-3"><i class="fa fa-plus mr-2"></i> Tambah Kelompok Belanja</button>
                </div>
                ';
        $search = '<div class="col-5 col-md-4">Pencarian <div class="input-group">
                        <input type="text" class="search form-control" />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary rounded-0 reset-listjs" data-target="listKelompok" title="Reset pencarian &amp; halaman"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div></div>';
        $pagging    = '<div class="col-4 col-md-3">Halaman <ul class="pagination"></ul></div>';
        $btnOptions = '<div class="col-md-5">' . $btnAdd . '</div>';

        $total_awal      = 0;
        $total_perubahan = 0;

        $html  = '<div id="listKelompok"><div class="row">' . $search . $pagging . $btnOptions . "</div>";
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action">';
        $html .= '<thead><tr><th class="text-center">No</th><th>Kode</th><th>Judul</th><th class="text-right">Alokasi Anggaran Awal (Rp)</th><th class="text-right">Alokasi Anggaran Perubahan (Rp)</th><th class="text-right">Selisih (Berkurang / Bertambah)</th><th>Hapus</th><th>Edit</th></tr></thead>';
        $html .= '<tbody class="list">';
        $no    = 1;
        foreach ($db->result() as $r):
            $pAwal      = $paguAwal[$r->id] ?? 0;
            $pPerubahan = $paguPerubahan[$r->id] ?? 0;
            $total_awal += $pAwal;
            $total_perubahan += $pPerubahan;

            $selisih     = $pPerubahan - $pAwal;
            $tanda       = $selisih > 0 ? '+' : ($selisih < 0 ? '-' : '');
            $warnaClass  = $selisih > 0 ? 'text-success' : ($selisih < 0 ? 'text-danger' : '');
            $hasil       = $tanda . nominal(abs($selisih));

            $html .= '<tr>
						                <td class="text-center">
						                    ' . $no . '
						                </td>
						                <td class="kode">
						                    ' . htmlspecialchars($r->kode) . '
						                </td>
						                <td class="nama">
						                    ' . htmlspecialchars($r->nama) . '
						                </td>
						                <td class="text-right">Rp. ' . nominal($pAwal) . '</td>
						                <td class="text-right">Rp. ' . nominal($pPerubahan) . '</td>
						                <td class="text-right"><b class="' . $warnaClass . '">' . $hasil . '</b></td>
						                <td width="5%" class="text-center">
						                    <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/ref_kelompok_belanja') . '\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
						                </td>
						                <td width="5%" class="text-center"><button onclick="Edit(' . $r->id . ',\'' . base_url("app/programs/detail/ref_kelompok_belanja") . '\',\'.modal-kelompok-edit\')" type="button" class="btn btn-sm btn-light m-0"><i class="fa fa-pencil"></i></button></td>
						            </tr>';
            $no++;
        endforeach;
        $selisihTotal     = $total_perubahan - $total_awal;
        $tandaTotal       = $selisihTotal > 0 ? '+' : ($selisihTotal < 0 ? '-' : '');
        $warnaClassTotal  = $selisihTotal > 0 ? 'text-success' : ($selisihTotal < 0 ? 'text-danger' : '');
        $hasilTotal       = $tandaTotal . nominal(abs($selisihTotal));

        $html .= '
            <tr>
                <td colspan="3" class="text-right align-middle"><b>Total</b></td>
                <td class="text-right align-middle"><b>Rp. ' . nominal($total_awal) . '</b></td>
                <td class="text-right align-middle"><b>Rp. ' . nominal($total_perubahan) . '</b></td>
                <td class="text-right align-middle"><b class="' . $warnaClassTotal . '">' . $hasilTotal . '</b></td>
                <td colspan="2"></td>
            </tr>
        ';
        $html .= '</tbody>';
        $html .= '</table></div></div>';

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Kelompok Belanja</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function program()
    {
        $db     = $this->target->program(null, $this->session->userdata('part'), $this->session->userdata('tahun_anggaran'));
        $btnAdd = '<div class="float-right">
        <a class="btn btn-info mt-3 rounded-0" href="' . base_url('app/export/program') . '"><i class="fa fa-download"></i> Export</a>
            <button data-toggle="modal" data-target=".modal-program" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        ';
        $search = '<div class="col-5 col-md-4">Pencarian <div class="input-group">
                        <input type="text" class="search form-control" />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary rounded-0 reset-listjs" data-target="listProgram" title="Reset pencarian &amp; halaman"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div></div>';
        $pagging    = '<div class="col-4 col-md-3">Halaman <ul class="pagination"></ul></div>';
        $btnOptions = '<div class="col-md-5">' . $btnAdd . '</div>';

        $html  = '<div id="listProgram"><div class="row">' . $search . $pagging . $btnOptions . "</div>";
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action table-bordered">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Rekening</th>
                        <th>Nama Program</th>
                        <th class="text-center">Ubah</th>
                        <th class="text-right">Alokasi Anggaran Awal (Rp)</th>
                        <th class="text-right">Alokasi Anggaran Perubahan (Rp)</th>
                        <th class="text-right" colspan="2">Selisih (Berkurang / Bertambah)</th>
                    </tr>
                    </thead>';
        $html .= '<tbody class="list">';
        $no    = 1;
        foreach ($db->result() as $r):

            $totalPaguAwal      = $this->target->getAlokasiPaguProgram($r->id, "0", $this->tahun_anggaran)->row()->total_pagu_awal ?? 0;
            $totalPaguPerubahan = $this->target->getAlokasiPaguProgram($r->id, "1", $this->tahun_anggaran)->row()->total_pagu_awal ?? 0;

            $disabled_edit = ($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'ADMIN') ? '' : 'disabled';
            $button_edit   = '<td width="5%" class="text-center">
						                <button onclick="window.location.href = \'' . base_url('app/programs/ubah/' . $r->id . '/ref_programs') . '\'" type="button" class="btn btn-info btn-sm rounded-0 m-0" ' . $disabled_edit . '><i class="fa fa-pencil"></i></button>
						            </td>';

            // Hitung selisih
            $selisih = $totalPaguPerubahan - $totalPaguAwal;
            // Tambahkan tanda + jika positif, otomatis - jika negatif
            $tanda = $selisih > 0 ? '+' : ($selisih < 0 ? '-' : '');
            // Tentukan class warna
            $warnaClass = $selisih > 0 ? 'text-success' : ($selisih < 0 ? 'text-danger' : '');
            // Nominal selalu dalam bentuk absolut supaya tanda tidak dobel
            $hasil = $tanda . nominal(abs($selisih));

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
						                <td>
						                    <div class="d-flex justify-content-between">
						                        <b>Rp.</b> <b>' . @nominal($totalPaguAwal) . '</b>
						                    </div>
						                </td>
						                <td>
						                    <div class="d-flex justify-content-between">
						                        <b>Rp.</b> <b>' . @nominal($totalPaguPerubahan) . '</b>
						                    </div>
						                </td>
						                <td class="text-right">
						                    <div class="d-flex justify-content-between">
						                        <b>Rp.</b><b class="' . $warnaClass . '">' . $hasil . '</b>
						                    </div>
						                </td>
						            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div></div>';

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Program</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function kegiatan()
    {
        // get data kegiatan
        if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN'):
            $db = $this->db->order_by('kode', 'asc')->where('tahun', $this->session->userdata('tahun_anggaran'))->get('ref_kegiatans');
        else:
            $db = $this->db->order_by('kode', 'asc')->where('fid_part', $this->session->userdata('part'))->where('tahun', $this->session->userdata('tahun_anggaran'))->get('ref_kegiatans');
        endif;

        $btnAdd = '<div class="float-right">
            <a class="btn btn-info mt-3 rounded-0" href="' . base_url('app/export/kegiatan') . '"><i class="fa fa-download"></i> Export</a>
            <button data-toggle="modal" data-target=".modal-kegiatan" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        ';
        $search = '<div class="col-5 col-md-4">Pencarian <div class="input-group">
                        <input type="text" class="search form-control" />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary rounded-0 reset-listjs" data-target="listKegiatan" title="Reset pencarian &amp; halaman"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div></div>';
        $pagging = '<div class="col-4 col-md-3">Halaman <ul class="pagination"></ul></div>';

        $btnOptions = '<div class="col-md-5">' . $btnAdd . '</div>';

        $html  = '<div id="listKegiatan"><div class="row">' . $search . $pagging . $btnOptions . "</div>";
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action table-bordered">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Rekening</th>
                        <th>Nama Kegiatan</th>
                        <th>Ubah</th>
                        <th class="text-right">Alokasi Anggaran Awal (Rp)</th>
                        <th class="text-right">Alokasi Anggaran Perubahan (Rp)</th>
                        <th class="text-right" colspan="2">Selisih (Berkurang / Bertambah)</th>
                    </tr>
                </thead>';
        $html .= '<tbody class="list">';
        $no    = 1;
        foreach ($db->result() as $r):

            $totalPaguAwal      = $this->target->getAlokasiPaguKegiatan($r->id, "0", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal ?? 0;
            $totalPaguPerubahan = $this->target->getAlokasiPaguKegiatan($r->id, "1", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal ?? 0;

            $button_edit = '<td width="5%" class="text-center">
						                                <a href="' . base_url('app/programs/ubah/' . $r->id . '/ref_kegiatans') . '" type="button" class="btn btn-info btn-sm rounded-0 m-0"><i class="fa fa-pencil"></i></a>
						                            </td>';

            // Hitung selisih
            $selisih = $totalPaguPerubahan - $totalPaguAwal;
            // Tambahkan tanda + jika positif, otomatis - jika negatif
            $tanda = $selisih > 0 ? '+' : ($selisih < 0 ? '-' : '');
            // Tentukan class warna
            $warnaClass = $selisih > 0 ? 'text-success' : ($selisih < 0 ? 'text-danger' : '');
            // Nominal selalu dalam bentuk absolut supaya tanda tidak dobel
            $hasil = $tanda . nominal(abs($selisih));

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
						                <td>
						                    <div class="d-flex justify-content-between">
						                        <b>Rp.</b><b>' . @nominal($totalPaguAwal) . '</b>
						                    </div>
						                </td>
						                <td>
						                    <div class="d-flex justify-content-between">
						                        <b>Rp.</b><b>' . @nominal($totalPaguPerubahan) . '</b>
						                    </div>
						                </td>
						                <td class="text-right">
						                    <div class="d-flex justify-content-between">
						                        <b>Rp.</b><b class="' . $warnaClass . '">' . $hasil . '</b>
						                    </div>
						                </td>
						            </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div></div>';

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Kegiatan</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function sub_kegiatan()
    {
        if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN'):
            $db = $this->db->order_by('kode', 'asc')
                ->where('tahun', $this->session->userdata('tahun_anggaran'))
                ->get('ref_sub_kegiatans AS sub');
        else:
            $db = $this->db->select('sub.id,sub.fid_kegiatan,sub.kode,sub.nama')
                ->order_by('sub.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id', 'inner')
                ->where('keg.fid_part', $this->session->userdata('part'))
                ->where('sub.tahun', $this->session->userdata('tahun_anggaran'))
                ->get('ref_sub_kegiatans AS sub');
        endif;
        $btnAdd = '<div class="float-right">
                        <a class="btn btn-info mt-3 rounded-0" href="' . base_url('app/export/sub_kegiatan') . '"><i class="fa fa-download"></i> Export</a>
                        <button data-toggle="modal" data-target=".modal-subkegiatan" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button>
                    </div>
            ';
        $search = '<div class="col-6 col-md-4">Pencarian <div class="input-group">
                        <input type="text" class="fuzzy-search form-control" />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary rounded-0 reset-listjs" data-target="listSubKegiatan" title="Reset pencarian &amp; halaman"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div></div>';
        $pagging = '<div class="col-6 col-md-3">Halaman <ul class="pagination"></ul></div>';

        $button_option = '<div class="col-md-5">' . $btnAdd . '</div>';

        $html  = '<div id="listSubKegiatan"><div class="row">' . $search . $pagging . $button_option . "</div>";
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action table-bordered">';
        $html .= '<thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode Rekening</th>
                                <th>Nama Sub Kegiatan</th>
                                <th width="5%" class="text-right">Ubah</th>
                                <th class="text-right">Alokasi Pagu Awal (Rp)</th>
                                <th class="text-right">Alokasi Pagu Perubahan (Rp)</th>
                                <th class="text-right" colspan="2">Selisih (Berkurang / Bertambah)</th>
                            </tr>
                        </thead>';
        $html .= '<tbody class="list">';
        $no    = 1;
        foreach ($db->result() as $r):
            //get alokasi pagu berdasarkan id kegiatan
            // $pagu = $this->crud->getWhere('t_pagu', ['fid_sub_kegiatan' => $r->id])->row();
            // $totalPaguAwal = !empty($pagu->total_pagu_awal) ? $pagu->total_pagu_awal : 0;
            $totalPaguAwal      = $this->target->getAlokasiPaguSubKegiatan($r->id, "0", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal ?? 0;
            $totalPaguPerubahan = $this->target->getAlokasiPaguSubKegiatan($r->id, "1", $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal ?? 0;

            $button_edit = '<td width="5%" class="text-center">
						                                <a href="' . base_url('app/programs/ubah/' . $r->id . '/ref_sub_kegiatans') . '" type="button" class="btn btn-info btn-sm rounded-0 m-0"><i class="fa fa-pencil"></i></a>
						                            </td>';
            $alokasi_pagu           = nominal($totalPaguAwal);
            $alokasi_pagu_perubahan = nominal($totalPaguPerubahan);

            // Hitung selisih
            $selisih = $totalPaguPerubahan - $totalPaguAwal;
            // Tambahkan tanda + jika positif, otomatis - jika negatif
            $tanda = $selisih > 0 ? '+' : ($selisih < 0 ? '-' : '');
            // Tentukan class warna
            $warnaClass = $selisih > 0 ? 'text-success' : ($selisih < 0 ? 'text-danger' : '');
            // Nominal selalu dalam bentuk absolut supaya tanda tidak dobel
            $hasil = $tanda . nominal(abs($selisih));

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
						                    <td>
						                        <div class="d-flex justify-content-between">
						                            <b>Rp.</b><b>' . $alokasi_pagu . '</b>
						                        </div>
						                    </td>
						                    <td>
						                        <div class="d-flex justify-content-between">
						                            <b>Rp.</b><b>' . $alokasi_pagu_perubahan . '</b>
						                        </div>
						                    </td>
						                    <td class="text-right">
						                        <div class="d-flex justify-content-between">
						                            <b>Rp.</b><b class="' . $warnaClass . '">' . $hasil . '</b>
						                        </div>
						                    </td>
						                </tr>';
            $no++;
        endforeach;
        $html .= '</tbody>';
        $html .= '</table></div></div>';

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Sub Kegiatan</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function uraian()
    {
        if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN'):
            $db = $this->db->select('u.id,u.fid_kegiatan,u.fid_jenis_belanja,u.kode,u.nama,u.is_aktif,keg.kode AS kode_kegiatan,keg.nama AS nama_kegiatan, sub.kode AS kode_sub_kegiatan,sub.nama AS nama_sub_kegiatan, j.kode AS kode_jenis,j.nama AS nama_jenis, kel.kode AS kode_kelompok,kel.nama AS nama_kelompok')
                ->order_by('u.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'u.fid_kegiatan=keg.id', 'inner')
                ->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id', 'inner')
                ->join('ref_jenis_belanja AS j', 'u.fid_jenis_belanja=j.id', 'left')
                ->join('ref_kelompok_belanja AS kel', 'j.fid_kelompok_belanja=kel.id', 'left')
                ->where('u.tahun', $this->session->userdata('tahun_anggaran'))
                ->get('ref_uraians AS u');
        else:
            $db = $this->db->select('u.id,u.fid_kegiatan,u.fid_jenis_belanja,u.kode,u.nama,u.is_aktif,keg.kode AS kode_kegiatan,keg.nama AS nama_kegiatan, sub.kode AS kode_sub_kegiatan,sub.nama AS nama_sub_kegiatan, j.kode AS kode_jenis,j.nama AS nama_jenis, kel.kode AS kode_kelompok,kel.nama AS nama_kelompok')
                ->order_by('u.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'u.fid_kegiatan=keg.id', 'inner')
                ->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id', 'inner')
                ->join('ref_jenis_belanja AS j', 'u.fid_jenis_belanja=j.id', 'left')
                ->join('ref_kelompok_belanja AS kel', 'j.fid_kelompok_belanja=kel.id', 'left')
                ->where('keg.fid_part', $this->session->userdata('part'))
                ->where('u.tahun', $this->session->userdata('tahun_anggaran'))
                ->get('ref_uraians AS u');
        endif;

        $btnAdd = '<div class="float-right">
            <button data-toggle="modal" data-target=".modal-uraian" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        ';

        $btnRekon = '<div class="float-right">
            <button data-toggle="modal" data-target=".modal-rekon" class="btn btn-success mt-3 rounded-0"><i class="fa fa-database"></i> Rekonsiliasi Anggaran</button>
            </div>
        ';

        $btnExport = '<div class="float-right"><a class="btn btn-info mt-3 rounded-0" href="' . base_url('app/export/uraian') . '"><i class="fa fa-download"></i> Export</a></div>';

        $search = '<div class="col-5 col-md-4">Pencarian <div class="input-group">
                            <input type="text" class="search form-control" />
                            <div class="input-group-append">
                                <button type="button" class="btn btn-secondary rounded-0 reset-listjs" data-target="listUraian" title="Reset pencarian &amp; halaman"><i class="fa fa-refresh"></i></button>
                            </div>
                        </div></div>';
        $pagging       = '<div class="col-4 col-md-3">Halaman <ul class="pagination"></ul></div>';
        $button_option = '<div class="col-md-5">' . $btnAdd . $btnRekon . $btnExport . '</div>';

        $is_admin        = ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN');
        $th_status_aktif = $is_admin ? '<th class="text-center">Status Aktif</th>' : '';

        $html  = '<div id="listUraian"><div class="row">' . $search . $pagging . $button_option . "</div>";
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action table-bordered">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Jenis Belanja</th>
                        <th>Kode Rekening</th>
                        <th>Nama Kegiatan/Sub Kegiatan/Uraian</th>
                        <th>Total SPJ</th>
                        ' . $th_status_aktif . '
                        <th class="text-center" colspan="2">Ubah | Hapus</th>
                        <th class="text-right" colspan="2">Alokasi Pagu Awal (Rp)</th>
                        <th class="text-right" colspan="2">Alokasi Pagu Perubahan (Rp)</th>
                        <th class="text-right" colspan="2">Selisih (Berkurang / Bertambah)</th>
                    </tr>
                </thead>';
        $html                     .= '<tbody class="list">';
        $no                        = 1;
        $total_all_pagu            = 0;
        $total_all_pagu_perubahan  = 0;
        $warnaClassTotal           = '';
        $hasilTotal                = 0;

        foreach ($db->result() as $r):
            //get jumlah spj berdasarkan id uraian
            $jmlSpj = $this->crud->getWhere('spj', ['fid_uraian' => $r->id])->num_rows();
            //get alokasi pagu berdasarkan id kegiatan
            $pagu          = $this->crud->getWhere('t_pagu', ['fid_uraian' => $r->id, 'is_perubahan' => '0'])->row();
            $paguPerubahan = $this->crud->getWhere('t_pagu', ['fid_uraian' => $r->id, 'is_perubahan' => '1'])->row();

            $totalPaguPerubahan = $paguPerubahan->total_pagu_awal ?? 0;
            $totalPaguAwal      = $pagu->total_pagu_awal ?? 0;

            // Switch toggle is_aktif - only show for ADMIN and SUPER_ADMIN roles
            $switch_aktif = '';
            if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN'):
                $is_checked   = (isset($r->is_aktif) && $r->is_aktif === 'Y') ? 'checked' : '';
                $switch_aktif = '<td class="text-center align-middle" width="6%">
		                                    <div class="d-inline-flex align-items-center justify-content-center position-relative">
		                                        <label class="switch-toggle mb-0" title="' . ($r->is_aktif === 'Y' ? 'Aktif' : 'Tidak Aktif') . '">
		                                            <input type="checkbox" class="toggle-is-aktif" data-id="' . $r->id . '" ' . $is_checked . '>
		                                            <span class="slider round"></span>
		                                        </label>
		                                        <i class="fa fa-spinner fa-spin text-dark switch-loader d-none position-absolute" style="font-size: 13px;"></i>
		                                    </div>
		                                </td>';
            endif;

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
            $is_disabled_pagu_awal  = $this->session->userdata('is_perubahan') === "1" ? 'disabled' : '';
            $total_all_pagu        += $totalPaguAwal;
            $button_pagu            = '<td width="10%" class="text-right"
						                                <div class="text-right">
						                                        <div class="d-flex justify-content-between">
						                                            <b>Rp.</b><b>' . nominal($totalPaguAwal) . '</b>
						                                        </div>
						                                        <td class="text-center">
						                                            <button onclick="InputPagu(' . $r->id . ',\'' . base_url('app/programs/input/ref_uraians') . '\',\'' . $totalPaguAwal . '\',0)" type="button" class="btn btn-info btn-sm rounded m-0" ' . $is_disabled_pagu_awal . '><i class="fa fa-money"></i></button>
						                                        </td>
						                                    </div>
						                            </td>';

            // Pagu Perubahan
            $is_disabled_pagu_perubahan  = $this->session->userdata('is_perubahan') === "0" ? 'disabled' : '';
            $total_all_pagu_perubahan   += $totalPaguPerubahan;
            $button_pagu_perubahan       = '<td width="10%" class="text-right">
						                                        <div class="text-right">
						                                            <div class="d-flex justify-content-between">
						                                                <b>Rp.</b><b>' . nominal($totalPaguPerubahan) . '</b>
						                                            </div>
						                                            <td class="text-center">
						                                                <button onclick="InputPagu(' . $r->id . ',\'' . base_url('app/programs/input/ref_uraians') . '\',\'' . $totalPaguPerubahan . '\',1,\'' . $totalPaguAwal . '\')" type="button" class="btn btn-info btn-sm rounded m-0" ' . $is_disabled_pagu_perubahan . '><i class="fa fa-money"></i></button>
						                                            </td>
						                                        </div>
						                                    </td>';

            // Hitung selisih
            $selisih = $totalPaguPerubahan - $totalPaguAwal;
            // Tambahkan tanda + jika positif, otomatis - jika negatif
            $tanda = $selisih > 0 ? '+' : ($selisih < 0 ? '-' : '');
            // Tentukan class warna
            $warnaClass = $selisih > 0 ? 'text-success' : ($selisih < 0 ? 'text-danger' : '');
            // Nominal selalu dalam bentuk absolut supaya tanda tidak dobel
            $hasil = $tanda . nominal(abs($selisih));

            // Hitung total selisih
            $total_selisih = $total_all_pagu_perubahan - $total_all_pagu;
            // Tentukan tanda dan warna
            $tandaTotal      = $total_selisih > 0 ? '+' : ($total_selisih < 0 ? '-' : '');
            $warnaClassTotal = $total_selisih > 0 ? 'text-success' : ($total_selisih < 0 ? 'text-danger' : '');
            // Format hasil total
            $hasilTotal = $tandaTotal . nominal(abs($total_selisih));

            $html .= '<tr>
						                <td class="text-center">
						                    ' . $no . '
						                </td>
						                <td valign="middle">
						                    ' . ($r->kode_jenis ? htmlspecialchars($r->kode_jenis) . ' - ' : '') . ($r->nama_jenis ? htmlspecialchars($r->nama_jenis) : '-') . '
						                </td>
						                <td>
						                    ' . $r->kode_kegiatan . ' <br>
						                    ' . $r->kode_sub_kegiatan . ' <br>
						                    <b class="kode">' . $r->kode . '</b>
						                    <button type="button" class="btn btn-sm py-0 px-1 copy-code" data-copy="' . htmlspecialchars($r->kode, ENT_QUOTES) . '" title="Salin kode rekening" style="line-height:1;border:none;background:none;color:#6c757d"><i class="fa fa-fw fa-copy"></i></button>
						                </td>
						                <td valign="middle">
						                    ' . ucwords($r->nama_kegiatan) . ' <br>
						                    ' . ucwords($r->nama_sub_kegiatan) . ' <br>
						                    <b class="nama">' . ucwords($r->nama) . '</b>
						                </td>
						                <td class="text-center">' . $jmlSpj . '</td>
						                ' . $switch_aktif . '
						                ' . $button_edit . '
						                ' . $button_pagu . '
						                ' . $button_pagu_perubahan . '
						                <td class="text-right">
						                    <div class="d-flex justify-content-between">
						                        <b>Rp.</b><b class="' . $warnaClass . '">' . $hasil . '</b>
						                    </div>
						                </td>
						            </tr>';
            $no++;
        endforeach;
        $colspan_total  = $is_admin ? 8 : 7;
        $html          .= '
            <tr>
                <td colspan="' . $colspan_total . '" class="text-right align-middle"><b>Total</b></td>
                <td colspan="2"><div class="d-flex justify-content-between"><b>Rp.</b><b>Rp. ' . nominal($total_all_pagu) . '</b></div></td>
                <td colspan="2"><div class="d-flex justify-content-between"><b>Rp.</b><b>Rp. ' . nominal($total_all_pagu_perubahan) . '</b></div></td>
                <td><div class="d-flex justify-content-between"><b>Rp.</b><b class="' . $warnaClassTotal . '">' . $hasilTotal . '</b></div></td>
            </tr>
        ';
        $html .= '</tbody>';
        $html .= '</table></div></div>';

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Uraian</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function toggle_aktif_uraian()
    {
        $id       = $this->input->post('id');
        $is_aktif = $this->input->post('is_aktif');

        if (! $id || ! $is_aktif) {
            echo json_encode(['status' => false, 'message' => 'Parameter tidak lengkap']);
            return;
        }

        $uraian = $this->crud->getWhere('ref_uraians', ['id' => $id])->row();
        if (! $uraian) {
            echo json_encode(['status' => false, 'message' => 'Data uraian tidak ditemukan']);
            return;
        }

        $val    = ($is_aktif === 'Y') ? 'Y' : 'N';
        $update = $this->crud->update('ref_uraians', ['is_aktif' => $val], ['id' => $id]);

        if ($update) {
            echo json_encode(['status' => true, 'is_aktif' => $val, 'message' => 'Status is_aktif berhasil diperbarui']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal memperbarui status is_aktif']);
        }
    }

    public function uraian_limit()
    {
        if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN'):
            $db = $this->db->select('l.*, u.id as uraian_id,u.fid_kegiatan,u.kode,u.nama,keg.kode AS kode_kegiatan,keg.nama AS nama_kegiatan, sub.kode AS kode_sub_kegiatan,sub.nama AS nama_sub_kegiatan')
                ->order_by('u.id,l.periode', 'desc')
                ->join('ref_uraians AS u', 'l.fid_uraian=u.id', 'inner')
                ->join('ref_kegiatans AS keg', 'u.fid_kegiatan=keg.id', 'inner')
                ->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id', 'inner')
                ->where('l.tahun', $this->session->userdata('tahun_anggaran'))
                ->where('l.is_perubahan', $this->session->userdata('is_perubahan'))
                ->get('t_pagu_limit AS l');
        else:
            $db = $this->db->select('l.*, u.id as uraian_id,u.fid_kegiatan,u.kode,u.nama,keg.kode AS kode_kegiatan,keg.nama AS nama_kegiatan, sub.kode AS kode_sub_kegiatan,sub.nama AS nama_sub_kegiatan')
                ->order_by('u.id,l.periode', 'desc')
                ->join('ref_uraians AS u', 'l.fid_uraian=u.id', 'inner')
                ->join('ref_kegiatans AS keg', 'u.fid_kegiatan=keg.id', 'inner')
                ->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id', 'inner')
                ->where('l.fid_part', $this->session->userdata('part'))
                ->where('l.tahun', $this->session->userdata('tahun_anggaran'))
                ->where('l.is_perubahan', $this->session->userdata('is_perubahan'))
                ->get('t_pagu_limit AS l');
        endif;

        $btnAdd = '<div class="float-right">
            <button data-toggle="modal" data-target=".modal-limit" class="btn btn-primary mt-3 rounded-0"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        ';
        $button_option = '<div class="col-md-3">' . $btnAdd . '</div>';

        $search = '<div class="col-5 col-md-4">Pencarian <div class="input-group">
                        <input type="text" class="search form-control" />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary rounded-0 reset-listjs" data-target="listLimit" title="Reset pencarian &amp; halaman"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div></div>';
        $pagging = '<div class="col-4 col-md-3">Halaman <ul class="pagination"></ul></div>';

        $html  = '<div id="listLimit"><div class="row">' . $search . $pagging . $button_option . "</div>";
        $html .= '<div class="table-responsive"><table class="table jambo_table bulk_action table-bordered">';
        $html .= '<thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Rekening</th>
                        <th>Nama Kegiatan/Sub Kegiatan/Uraian</th>
                        <th class="text-right">Sisa Limit Pagu Awal / Perubahan</th>
                        <th class="text-right" colspan="3">Jml. Anggaran KAS</th>
                        <th class="text-center">Utk. Periode</th>
                    </tr>
                </thead>';
        $html            .= '<tbody class="list">';
        $no               = 1;
        $total_limit_kas  = 0;
        foreach ($db->result() as $r):
            $getTotalLimit = $this->db->select('id,total')
                ->order_by('periode', 'asc')
                ->where('fid_uraian', $r->uraian_id)
                ->where('tahun', $this->session->userdata('tahun_anggaran'))
                ->where('is_perubahan', $this->session->userdata('is_perubahan'))
                ->get('t_pagu_limit')
                ->result();

            $getTotalPaguAwal = $this->crud->getWhere('t_pagu', ['fid_uraian' => $r->uraian_id, 'is_perubahan' => $this->session->userdata('is_perubahan')])->row()->total_pagu_awal ?? 0;

            // get limit kas berdasarkan id uraian
            $total_limit  = $r->total ?? 0;
            // get total limit kas
            $total_limit_kas += $total_limit;

            $limit_per_uraian = 0;
            foreach ($getTotalLimit as $key => $value) {
                $limit_per_uraian                   += $value->total;
                $total_limit_per_uraian[$value->id]  = $limit_per_uraian;
            }

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
						                <td class="text-right">
						                    <b>' . nominal(($getTotalPaguAwal - $total_limit_per_uraian[$r->id])) . '</b>
						                </td>
						                <td width="10%" class="text-right">
						                    <b>' . ($total_limit === 0 ? 'UNLIMITED' : nominal($total_limit)) . '</b>
						                </td>
						                    <td class="text-center">
						                        <button onclick="UpdateLimit(' . $r->id . ',\'' . $total_limit . '\',\'' . $r->uraian_id . '\')" type="button" class="btn btn-info btn-sm rounded m-0"><i class="fa fa-money"></i></button>
						                    </td>
						                    <td class="text-center">
						                        <button onclick="Hapus(' . $r->id . ',\'' . base_url('app/programs/hapus/t_pagu_limit') . '\',\'LIMIT ANGGARAN\')" type="button" class="btn btn-danger btn-sm rounded-0 m-0"><i class="fa fa-trash"></i></button>
						                    </td>
						                </td>
						                <td class="text-center">' . bulan_range($r->periode) . '</td>
						            </tr>';
            $no++;
        endforeach;
        $html .= '
            <tr>
                <td colspan="4" class="text-right align-middle"><b>Total</b></td>
                <td colspan="4" class="text-left"><b>Rp. ' . nominal($total_limit_kas) . '</b></td>
            </tr>
        ';
        $html .= '</tbody>';
        $html .= '</table></div></div>';

        if ($db->num_rows() > 0):
            $data = ['result' => $html, 'msg' => $db->num_rows() . ' Data Ditemukan', 'code' => 200];
        else:
            $data = ['result' => $btnAdd, 'msg' => 'Data <b>Limit Anggaran</b> Tidak Ditemukan', 'code' => 404];
        endif;

        echo json_encode($data);
    }

    public function getTujuan()
    {
        $q = $this->input->post('q');

        // $db = $this->crud->getLikes('ref_tujuan', ['nama' => $q]);
        $db  = $this->db->like('nama', $q)->where('tahun', $this->tahun_anggaran)->get('ref_tujuan');
        $all = [];
        if ($db->num_rows() > 0):
            foreach ($db->result() as $row):
                $data['id']   = $row->id;
                $data['text'] = $row->id . " - " . $row->nama;
                $all[]        = $data;
            endforeach;
        else:
            $all[] = ['id' => 0, 'text' => 'Maaf, Tujuan "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getKelompok()
    {
        $this->onlyAdminBelanja();
        $q = $this->input->post('q');

        $db  = $this->db->group_start()->like('kode', $q)->or_like('nama', $q)->group_end()
            ->where('tahun', $this->tahun_anggaran)->get('ref_kelompok_belanja');
        $all = [];
        if ($db->num_rows() > 0):
            foreach ($db->result() as $row):
                $data['id']   = $row->id;
                $data['text'] = $row->kode . " - " . $row->nama;
                $all[]        = $data;
            endforeach;
        else:
            $all[] = ['id' => 0, 'text' => 'Maaf, Kelompok "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getJenis()
    {
        $q = $this->input->post('q');

            $db  = $this->db->select('j.id, CONCAT(j.kode, " - ", j.nama) AS text')
                ->group_start()->like('j.kode', $q)->or_like('j.nama', $q)->group_end()
                ->where('j.tahun', $this->tahun_anggaran)->get('ref_jenis_belanja j');
            $all = [];
            if ($db->num_rows() > 0):
                foreach ($db->result() as $row):
                    $all[] = ['id' => $row->id, 'text' => $row->text];
                endforeach;
            else:
                $all[] = ['id' => 0, 'text' => 'Maaf, Jenis Belanja "' . strtoupper($q) . '" tidak ditemukan.'];
            endif;
            echo json_encode($all);
        }

        public function getUnor()
        {
            $q = $this->input->post('q');

            $db = $this->crud->getLikes('ref_unors', ['nama' => $q]);
        $all = [];
        if ($db->num_rows() > 0):
            foreach ($db->result() as $row):
                $data['id']   = $row->id;
                $data['text'] = $row->id . " - " . $row->nama;
                $all[]        = $data;
            endforeach;
        else:
            $all[] = ['id' => 0, 'text' => 'Maaf, Unor "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getSasaran()
    {
        $q = $this->input->post('q');

        // $db = $this->crud->getLikes('ref_sasaran', ['nama' => $q]);
        $db  = $this->db->where('tahun', $this->tahun_anggaran)->like('nama', $q)->get('ref_sasaran');
        $all = [];
        if ($db->num_rows() > 0):
            foreach ($db->result() as $row):
                $data['id']   = $row->id;
                $data['text'] = $row->id . " - " . $row->nama;
                $all[]        = $data;
            endforeach;
        else:
            $all[] = ['id' => 0, 'text' => 'Maaf, Sasaran "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getListUnor()
    {
        $db  = $this->crud->get('ref_unors');
        $all = [];
        if ($db->num_rows() > 0):
            foreach ($db->result() as $row):
                $data['id']   = $row->id;
                $data['text'] = $row->id . " - " . $row->nama;
                $all[]        = $data;
            endforeach;
        else:
            $all[] = ['id' => 0, 'text' => 'Maaf, Unor tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getParts()
    {
        $q = $this->input->post('q');

        $db  = $this->crud->getLikes('ref_parts', ['nama' => $q]);
        $all = [];
        if ($db->num_rows() > 0):
            foreach ($db->result() as $row):
                if ($this->session->userdata('role') !== 'ADMIN' && $this->session->userdata('role') !== 'SUPER_ADMIN' && $this->session->userdata('role') !== 'VERIFICATOR') {
                    if ($this->session->userdata('part') !== $row->id) {
                        $data['disabled'] = true;
                        $data['selected'] = false;
                    } else {
                        $data['disabled'] = false;
                        $data['selected'] = true;
                    }
                }

                $data['id']   = $row->id;
                $data['text'] = $row->id . " - " . $row->nama;
                $all[]        = $data;
            endforeach;
        else:
            $all[] = ['id' => 0, 'text' => 'Maaf, Bidang / Bagian "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function getProgram()
    {
        $q      = $this->input->post('q');
        $partid = $this->session->userdata('part');

        // $db = $this->crud->getLikes('ref_programs', ['nama' => $q]);

        if ($this->session->userdata('role') === 'USER'):
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
        if ($db->num_rows() > 0):
            foreach ($db->result() as $row):
                $data['id']   = $row->id;
                $data['text'] = $row->kode . " - " . $row->nama;
                $all[]        = $data;
            endforeach;
        else:
            $all[] = ['id' => 0, 'text' => 'Maaf, Program "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($all);
    }

    public function ch_kegiatan($partid, $q)
    {
        $this->load->model('ModelSelect2', 'select');
        $ch = [];
        $db = $this->select->getChildKegiatan($q, $partid, $this->session->userdata('tahun_anggaran'));
        foreach ($db->result() as $k) {
            $data['id']   = $k->id;
            $data['text'] = $k->kode . " - " . $k->nama;
            $ch[]         = $data;
        }
        return $ch;
    }

    public function getKegiatan()
    {
        $q = $this->input->post('q');
        // $db = $this->crud->getLikes('ref_kegiatans', ['nama' => $q]);
        if ($this->session->userdata('role') !== 'VERIFICATOR' && $this->session->userdata('role') !== 'SUPER_USER' && $this->session->userdata('role') !== 'SUPER_ADMIN' && $this->session->userdata('role') !== 'ADMIN'):
            $db = $this->db->select('k.*,p.nama AS partnama, p.id AS partid')
                ->from('ref_kegiatans AS k')
                ->join('ref_parts AS p', 'k.fid_part=p.id', 'inner')
                ->where('p.id', $this->session->userdata('part'))
                ->where('k.tahun', $this->session->userdata('tahun_anggaran'))
                ->group_by('k.fid_part')
                ->get();
        else:
            $db = $this->db->select('k.*,p.nama AS partnama, p.id AS partid')
                ->from('ref_kegiatans AS k')
                ->join('ref_parts AS p', 'k.fid_part=p.id', 'inner')
                ->where('k.tahun', $this->session->userdata('tahun_anggaran'))
                ->like('k.kode', $q)
                ->or_like('k.nama', $q)
                ->group_by('k.fid_part')
                ->get();
        endif;
        if ($db->num_rows() > 0):
            $group = [];
            // $db_part = $this->crud->get('ref_parts');
            foreach ($db->result() as $row):
                $data['text'] = $row->partnama;
                // $data['children'] = $this->ch_kegiatan($row->partid, $q);
                $data['children'] = $this->ch_kegiatan($row->partid, $q);
                $group[]          = $data;
            endforeach;
        else:
            $group[] = ['id' => 0, 'text' => 'Maaf, Kegiatan "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($group);
    }

    private function ch_uraian($sub_kegiatanid, $q, $ta, $is_perubahan)
    {
        $this->load->model('ModelSelect2', 'select');
        $ch = [];
        $db = $this->select->getChildUraian($sub_kegiatanid, $q, $ta, $is_perubahan);
        foreach ($db->result() as $u) {
            $data['id']   = $u->uraian_id;
            $data['text'] = '<p class="m-0 pl-3"> ' . $u->uraian_kode . " | " . $u->uraian_nama . ' | Rp. ' . nominal($u->pagu) . '</p>';
            $ch[]         = $data;
        }
        return $ch;
    }

    public function getUraian()
    {
        $q  = $this->input->post('q');
        $db = $this->db->select('u.*, k.nama AS kegiatan, k.id AS kegiatanid, sub.nama AS sub_kegiatan, sub.id AS sub_kegiatanid')
            ->from('ref_uraians AS u')
            ->join('ref_kegiatans AS k', 'u.fid_kegiatan=k.id', 'inner')
            ->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id', 'inner')
            ->where('u.tahun', $this->session->userdata('tahun_anggaran'))
            ->where('k.fid_part', $this->session->userdata('part'))
            ->group_start()
            ->like('u.nama', $q)
            ->or_like('u.kode', $q)
            ->group_end()
            ->group_by('u.fid_kegiatan')
            ->group_by('u.fid_sub_kegiatan')
            ->get();
        if ($db->num_rows() > 0):
            $group = [];
            foreach ($db->result() as $row):
                $data['text']     = '<p class="bg-warning m-0 p-2">' . $row->kegiatan . '</p><p class="bg-light m-0 pl-3 py-2 sticky-top"> ' . $row->sub_kegiatan . '</p>';
                $data['children'] = $this->ch_uraian($row->sub_kegiatanid, $q, $this->session->userdata('tahun_anggaran'), $this->session->userdata('is_perubahan'));
                $group[]          = $data;
            endforeach;
        else:
            $group[] = ['id' => 0, 'text' => 'Maaf, Uraian "' . strtoupper($q) . '" tidak ditemukan.'];
        endif;
        echo json_encode($group);
    }

    public function tambah($type)
    {
        if ($type === 'unor') {
            $p    = $this->input->post();
            $data = [
                'nama' => $p['unor'],
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
            $p    = $this->input->post();
            $data = [
                'fid_unor' => $p['unor'],
                'nama'     => $p['tujuan'],
                'tahun'    => $this->session->userdata('tahun_anggaran'),
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
            $p    = $this->input->post();
            $data = [
                'fid_tujuan' => $p['tujuan'],
                'nama'       => $p['sasaran'],
                'tahun'      => $this->session->userdata('tahun_anggaran'),
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

        if ($type === 'kelompok') {
            $this->onlyAdminBelanja();
            $p    = $this->input->post();
            $kode = trim($p['kode'] ?? '');
            $nama = trim($p['kelompok'] ?? '');
            if ($kode === '' || $nama === '') {
                echo json_encode(400);
                return false;
            }
            $ada = $this->crud->getWhere('ref_kelompok_belanja', ['kode' => $kode, 'tahun' => $this->session->userdata('tahun_anggaran')])->num_rows();
            if ($ada > 0) {
                echo json_encode(400);
                return false;
            }
            $data = [
                'kode'  => $kode,
                'nama'  => $nama,
                'tahun' => $this->session->userdata('tahun_anggaran'),
            ];
            $db = $this->crud->insert('ref_kelompok_belanja', $data);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }

        if ($type === 'jenis') {
            $this->onlyAdminBelanja();
            $p                    = $this->input->post();
            $fid_kelompok_belanja = trim($p['kelompok'] ?? '');
            $kode                 = trim($p['kode'] ?? '');
            $nama                 = trim($p['jenis'] ?? '');
            if ($fid_kelompok_belanja === '' || $kode === '' || $nama === '') {
                echo json_encode(400);
                return false;
            }
            $ada = $this->crud->getWhere('ref_jenis_belanja', ['kode' => $kode, 'tahun' => $this->session->userdata('tahun_anggaran')])->num_rows();
            if ($ada > 0) {
                echo json_encode(400);
                return false;
            }
            $data = [
                'fid_kelompok_belanja' => $fid_kelompok_belanja,
                'kode'                 => $kode,
                'nama'                 => $nama,
                'tahun'                => $this->session->userdata('tahun_anggaran'),
            ];
            $db = $this->crud->insert('ref_jenis_belanja', $data);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
            return false;
        }

        if ($type === 'part') {
            $p    = $this->input->post();
            $data = [
                'fid_program' => $p['program'],
                'nama'        => $p['part'],
                'singkatan'   => $p['part_singkatan'],
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
            $p    = $this->input->post();
            $data = [
                'fid_part'    => $p['part'],
                'fid_program' => $p['program'],
                'kode'        => $p['kode_kegiatan'],
                'nama'        => $p['kegiatan'],
                'tahun'       => $this->session->userdata('tahun_anggaran'),
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
            $p    = $this->input->post();
            $data = [
                'fid_part'    => implode(",", $p['bidang']),
                'fid_sasaran' => $p['sasaran'],
                'kode'        => $p['kode_program'],
                'nama'        => $p['program'],
                'tahun'       => $this->session->userdata('tahun_anggaran'),
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
            $p    = $this->input->post();
            $data = [
                'fid_kegiatan' => $p['kegiatan'],
                'kode'         => $p['kode_subkegiatan'],
                'nama'         => $p['subkegiatan'],
                'tahun'        => $this->session->userdata('tahun_anggaran'),
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
            $p    = $this->input->post();
            $data = [
                'fid_kegiatan'     => $p['kegiatan'],
                'fid_sub_kegiatan' => $p['subkegiatan'],
                'fid_jenis_belanja' => $p['jenis_belanja'],
                'kode'             => $p['kode_uraian'],
                'nama'             => $p['nama_uraian'],
                'is_aktif'         => isset($p['is_aktif']) && $p['is_aktif'] === 'Y' ? 'Y' : 'N',
                'tahun'            => $this->session->userdata('tahun_anggaran'),
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
        $id   = $post['id'];
        $jml  = get_only_numbers($post['jumlah']);
        $thn  = $this->session->userdata('tahun_anggaran');

        if ($type === 'ref_sub_kegiatans') {
            $insert = [
                'fid_part'         => $this->session->userdata('part'),
                'fid_sub_kegiatan' => $id,
                'total_pagu_awal'  => $jml,
                'tahun'            => $thn,
                'created_at'       => DateTimeInput(),
                'created_by'       => $this->session->userdata('user_name'),
            ];

            $update = [
                'total_pagu_awal' => $jml,
                'tahun'           => $thn,
            ];

            $whr = [
                'fid_sub_kegiatan' => $id,
            ];
        } else if ($type === 'ref_uraians') {

            if ($post['is_perubahan'] === "1") {
                $insert = [
                    'is_perubahan'    => "1",
                    'fid_part'        => $this->session->userdata('part'),
                    'fid_uraian'      => $id,
                    'total_pagu_awal' => $jml,
                    'tahun'           => $thn,
                    'created_at'      => DateTimeInput(),
                    'created_by'      => $this->session->userdata('user_name'),
                ];

                $update = [
                    'total_pagu_awal' => $jml,
                    'tahun'           => $thn,
                ];

                $whr = [
                    'fid_uraian'   => $id,
                    'is_perubahan' => "1",
                ];
            } else {
                $insert = [
                    'fid_part'        => $this->session->userdata('part'),
                    'fid_uraian'      => $id,
                    'total_pagu_awal' => $jml,
                    'tahun'           => $thn,
                    'created_at'      => DateTimeInput(),
                    'created_by'      => $this->session->userdata('user_name'),
                ];

                $update = [
                    'total_pagu_awal' => $jml,
                    'tahun'           => $thn,
                ];

                $whr = [
                    'fid_uraian'   => $id,
                    'is_perubahan' => "0",
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

    public function input_limit()
    {
        $post = $this->input->post();
        $jml  = get_only_numbers($post['jumlah']);
        $thn  = $this->session->userdata('tahun_anggaran');

        $validate = $this->getSisaLimit($post['uraian']);
        if ($jml > $validate['sisa_limit']) {
            echo json_encode([
                'status'  => false,
                'message' => 'Jumlah melebihi sisa limit !',
            ]);
            return false;
            die();
        }

        $insert = [
            'fid_part'     => $this->session->userdata('part'),
            'fid_uraian'   => $post['uraian'],
            'is_perubahan' => $this->session->userdata('is_perubahan'),
            'total'        => $jml,
            'periode'      => implode(",", $post['periode']),
            'tahun'        => $thn,
            'created_at'   => DateTimeInput(),
            'created_by'   => $this->session->userdata('user_name'),
        ];

        $db = $this->crud->insert('t_pagu_limit', $insert);
        if (! $db) {
            echo json_encode([
                'status'  => false,
                'message' => 'Gagal menambahkan limit !',
            ]);
            return false;
        }

        echo json_encode([
            'status'  => true,
            'message' => 'Limit berhasil ditambahkan',
        ]);
    }

    public function update_limit()
    {
        $post = $this->input->post();
        $id   = $post['id'];
        $jml  = get_only_numbers($post['jumlah']);

        $update = [
            'total'      => $jml,
            'updated_at' => DateTimeInput(),
            'updated_by' => $this->session->userdata('user_name'),
        ];

        // Handle periode update if provided
        if (isset($post['periode']) && is_array($post['periode']) && count($post['periode']) > 0) {
            $update['periode'] = implode(",", $post['periode']);
        }

        $whr = [
            'id' => $id,
        ];

        $cekid = $this->crud->getWhere('t_pagu_limit', $whr)->num_rows();
        if ($cekid === 0) {
            echo json_encode([
                'status'  => false,
                'message' => 'Limit tidak ditemukan !',
            ]);
            return false;
        }

        $db = $this->crud->update('t_pagu_limit', $update, $whr);

        if (! $db) {
            echo json_encode([
                'status'  => false,
                'message' => 'Update limit gagal',
            ]);
            return false;
        }

        echo json_encode([
            'status'  => true,
            'message' => 'Update limit berhasil',
        ]);
    }

    public function detail_limit_periode()
    {
        $id  = $this->input->get('id');
        $row = $this->crud->getWhere('t_pagu_limit', ['id' => $id])->row();

        $selected = array_filter(array_map('intval', explode(',', $row->periode ?? '')));

        // Periode milik limit lain pada uraian yang sama -> tidak boleh dipilih ulang
        $used = [];
        if ($row) {
            foreach ($this->crud->getWhere('t_pagu_limit', ['fid_uraian' => $row->fid_uraian])->result() as $r) {
                if ((int) $r->id === (int) $id) {
                    continue;
                }
                $used = array_merge($used, array_map('intval', explode(',', $r->periode ?? '')));
            }
        }
        $used = array_unique(array_filter($used));

        $options = [];
        foreach ($this->db->order_by('id', 'asc')->get('t_periode')->result() as $p) {
            $options[] = [
                'id'       => (int) $p->id,
                'nama'     => $p->nama,
                'selected' => in_array((int) $p->id, $selected),
                'disabled' => in_array((int) $p->id, $used),
            ];
        }

        echo json_encode(['options' => $options]);
    }

    public function detail($tbl)
    {
        $this->onlyAdminBelanjaTable($tbl);
        $uid = $this->input->get('id');
        $db  = $this->crud->getWhere($tbl, ['id' => $uid]);
        $row = $db->row();
        echo json_encode($row);
    }

    public function uraian_by_jenis()
    {
        $this->onlyAdminBelanja();
        $id = $this->input->get('id');
        if (!$id) {
            echo json_encode(['code' => 404, 'msg' => 'ID tidak valid', 'result' => '']);
            return;
        }

        $db = $this->db->select('u.id,u.kode,u.nama,keg.nama AS nama_kegiatan,sub.nama AS nama_sub_kegiatan')
            ->order_by('u.kode', 'asc')
            ->join('ref_kegiatans AS keg', 'u.fid_kegiatan=keg.id', 'left')
            ->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id', 'left')
            ->where('u.fid_jenis_belanja', $id)
            ->get('ref_uraians AS u');

        $paguAwal      = [];
        $paguPerubahan = [];
        $total_awal    = 0;
        $total_perubahan = 0;
        if ($db->num_rows() > 0):
            $ids = [];
            foreach ($db->result() as $row):
                $ids[] = $row->id;
            endforeach;
            $dbA = $this->db->select('p.fid_uraian, SUM(p.total_pagu_awal) AS total')
                ->where('p.is_perubahan', '0')
                ->where_in('p.fid_uraian', $ids)
                ->group_by('p.fid_uraian')
                ->get('t_pagu AS p');
            foreach ($dbA->result() as $row):
                $paguAwal[$row->fid_uraian] = $row->total;
            endforeach;
            $dbB = $this->db->select('p.fid_uraian, SUM(p.total_pagu_awal) AS total')
                ->where('p.is_perubahan', '1')
                ->where_in('p.fid_uraian', $ids)
                ->group_by('p.fid_uraian')
                ->get('t_pagu AS p');
            foreach ($dbB->result() as $row):
                $paguPerubahan[$row->fid_uraian] = $row->total;
            endforeach;
        endif;

        $html = '<div class="table-responsive"><table class="table table-striped jambo_table"><thead><tr><th>#</th><th>Kode</th><th>Uraian</th><th>Kegiatan</th><th>Sub Kegiatan</th><th class="text-right">Alokasi Anggaran Awal (Rp)</th><th class="text-right">Alokasi Anggaran Perubahan (Rp)</th><th class="text-right">Selisih (Berkurang / Bertambah)</th></tr></thead><tbody>';
        $no   = 1;
        if ($db->num_rows() > 0):
            foreach ($db->result() as $r):
                $pAwal      = $paguAwal[$r->id] ?? 0;
                $pPerubahan = $paguPerubahan[$r->id] ?? 0;
                $total_awal += $pAwal;
                $total_perubahan += $pPerubahan;

                $selisih    = $pPerubahan - $pAwal;
                $tanda      = $selisih > 0 ? '+' : ($selisih < 0 ? '-' : '');
                $warnaClass = $selisih > 0 ? 'text-success' : ($selisih < 0 ? 'text-danger' : '');
                $hasil      = $tanda . nominal(abs($selisih));

                $html .= '<tr>
                    <td>' . $no . '</td>
                    <td>' . htmlspecialchars($r->kode) . '</td>
                    <td>' . htmlspecialchars($r->nama) . '</td>
                    <td>' . htmlspecialchars($r->nama_kegiatan) . '</td>
                    <td>' . htmlspecialchars($r->nama_sub_kegiatan) . '</td>
                    <td class="text-right">Rp. ' . nominal($pAwal) . '</td>
                    <td class="text-right">Rp. ' . nominal($pPerubahan) . '</td>
                    <td class="text-right"><b class="' . $warnaClass . '">' . $hasil . '</b></td>
                </tr>';
                $no++;
            endforeach;

            $selisihTotal     = $total_perubahan - $total_awal;
            $tandaTotal       = $selisihTotal > 0 ? '+' : ($selisihTotal < 0 ? '-' : '');
            $warnaClassTotal  = $selisihTotal > 0 ? 'text-success' : ($selisihTotal < 0 ? 'text-danger' : '');
            $hasilTotal       = $tandaTotal . nominal(abs($selisihTotal));

            $html .= '
            <tr>
                <td colspan="5" class="text-right align-middle"><b>Total</b></td>
                <td class="text-right align-middle"><b>Rp. ' . nominal($total_awal) . '</b></td>
                <td class="text-right align-middle"><b>Rp. ' . nominal($total_perubahan) . '</b></td>
                <td class="text-right align-middle"><b class="' . $warnaClassTotal . '">' . $hasilTotal . '</b></td>
            </tr>';
        else:
            $html .= '<tr><td colspan="8" class="text-center text-muted">Tidak ada uraian terkait jenis belanja ini.</td></tr>';
        endif;
        $html .= '</tbody></table></div>';

        echo json_encode(['code' => 200, 'msg' => 'OK', 'result' => $html]);
    }

    public function detailv2($tbl, $id)
    {
        $this->onlyAdminBelanjaTable($tbl);
        $db  = $this->crud->getWhere($tbl, ['id' => $id]);
        $row = $db->row();
        return $row;
    }

    public function ubah($id, $tbl)
    {
        $detail = $this->detailv2($tbl, $id);
        $data   = [
            'title'        => $detail->nama,
            'content'      => 'pages/admin/' . $tbl,
            'data'         => $detail,
            'autoload_js'  => [
                'template/backend/vendors/select2/dist/js/select2.full.min.js',
                'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/backend/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js',
            ],
            'autoload_css' => [
                'template/backend/vendors/select2/dist/css/select2.min.css',
            ],
        ];
        $this->load->view('layout/app', $data);
    }

    public function update($tbl)
    {
        if ($tbl === 'ref_unors') {
            $input = $this->input->post();
            $data  = [
                'nama' => $input['unor'],
            ];
            $whr = [
                'id' => $input['id'],
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
            $data  = [
                'fid_program' => $input['program'],
                'nama'        => $input['part'],
                'singkatan'   => $input['part_singkatan'],
            ];
            $whr = [
                'id' => $input['id'],
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
            $data  = [
                'nama' => $input['tujuan'],
            ];
            $whr = [
                'id' => $input['id'],
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
            $data  = [
                'nama' => $input['sasaran'],
            ];
            $whr = [
                'id' => $input['id'],
            ];
            $db = $this->crud->update($tbl, $data, $whr);
            if ($db) {
                $msg = 200;
            } else {
                $msg = 400;
            }
            echo json_encode($msg);
        }

        if ($tbl === 'ref_kelompok_belanja') {
            $this->onlyAdminBelanja();
            $input = $this->input->post();
            $kode  = trim($input['kode'] ?? '');
            $nama  = trim($input['kelompok'] ?? '');
            if ($kode === '' || $nama === '') {
                echo json_encode(400);
                return false;
            }
            $duplikat = $this->db->where('kode', $kode)
                ->where('tahun', $this->session->userdata('tahun_anggaran'))
                ->where('id !=', $input['id'])
                ->get('ref_kelompok_belanja')->num_rows();
            if ($duplikat > 0) {
                echo json_encode(400);
                return false;
            }
            $data = [
                'kode' => $kode,
                'nama' => $nama,
            ];
            $whr = [
                'id' => $input['id'],
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

        if ($tbl === 'ref_jenis_belanja') {
                    $this->onlyAdminBelanja();
                    $input = $this->input->post();
            $fid_kelompok_belanja = trim($input['kelompok'] ?? '');
            $kode                 = trim($input['kode'] ?? '');
            $nama                 = trim($input['jenis'] ?? '');
            if ($fid_kelompok_belanja === '' || $kode === '' || $nama === '') {
                echo json_encode(400);
                return false;
            }
            $duplikat = $this->db->where('kode', $kode)
                ->where('tahun', $this->session->userdata('tahun_anggaran'))
                ->where('id !=', $input['id'])
                ->get('ref_jenis_belanja')->num_rows();
            if ($duplikat > 0) {
                echo json_encode(400);
                return false;
            }
            $data = [
                'fid_kelompok_belanja' => $fid_kelompok_belanja,
                'kode'                 => $kode,
                'nama'                 => $nama,
            ];
            $whr = [
                'id' => $input['id'],
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
                    'kode'         => $input['kode_subkegiatan'],
                    'nama'         => $input['subkegiatan'],
                ];
            } else {
                $data = [
                    'kode' => $input['kode_subkegiatan'],
                    'nama' => $input['subkegiatan'],
                ];
            }
            $whr = [
                'id' => $input['uid'],
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
                    'kode'        => $input['kode_kegiatan'],
                    'nama'        => $input['kegiatan'],
                ];
            } else {
                $data = [
                    'kode' => $input['kode_kegiatan'],
                    'nama' => $input['kegiatan'],
                ];
            }
            $whr = [
                'id' => $input['uid'],
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
                    'fid_part'    => implode(",", $input['bidang']),
                    'fid_sasaran' => $input['sasaran'],
                    'kode'        => $input['kode_program'],
                    'nama'        => $input['program'],

                ];
            } else {
                $data = [
                    'fid_part' => implode(",", $input['bidang']),
                    'kode'     => $input['kode_program'],
                    'nama'     => $input['program'],
                ];
            }
            $whr = [
                'id' => $input['uid'],
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
                    'fid_kegiatan'     => $input['kegiatan'],
                    'fid_sub_kegiatan' => $input['subkegiatan'],
                    'kode'             => $input['kode_uraian'],
                    'nama'             => $input['nama_uraian'],
                ];
                if (!empty($input['jenis_belanja'])) {
                    $data['fid_jenis_belanja'] = $input['jenis_belanja'];
                }
            } else {
                $data = [
                    'kode' => $input['kode_uraian'],
                    'nama' => $input['nama_uraian'],
                ];
                if (!empty($input['jenis_belanja'])) {
                    $data['fid_jenis_belanja'] = $input['jenis_belanja'];
                }
            }
            $whr = [
                'id' => $input['uid'],
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
            $db   = $this->crud->getWhere('ref_sub_kegiatans', ['kode' => $kode, 'tahun' => $this->session->userdata('tahun_anggaran')]);
            if ($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }

        if ($form === 'namaprogram') {
            $kode = $this->input->post('program');
            $db   = $this->crud->getWhere('ref_programs', ['nama' => $kode, 'tahun' => $this->session->userdata('tahun_anggaran')]);
            if ($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }

        if ($form === 'kodeprogram') {
            $kode = $this->input->post('kode_program');
            $db   = $this->crud->getWhere('ref_programs', ['kode' => $kode, 'tahun' => $this->session->userdata('tahun_anggaran')]);
            if ($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }

        if ($form === 'namauraian') {
            $kode        = $this->input->post('nama_uraian');
            $kegiatan_id = $this->input->post('ref_kegiatan');
            $db          = $this->db->select('u.nama,k.fid_part')
                ->from('ref_uraians AS u')
                ->join('ref_kegiatans AS k', 'u.fid_kegiatan=k.id')
                ->join('ref_parts AS p', 'k.fid_part=p.id')
                ->where('k.fid_part', $this->session->userdata('part'))
                ->where('u.nama', $kode)
                ->where('k.tahun', $this->session->userdata('tahun_anggaran'))
                ->get();
            if ($db->num_rows() > 0) {
                $this->output->set_status_header('400');
            } else {
                $this->output->set_status_header('200');
            }
            return false;
        }

        // if ($form === 'kodeuraian') {
        //     $kode = $this->input->post('kode_uraian');
        //     $db = $this->db->select('u.kode,k.fid_part')
        //         ->from('ref_uraians AS u')
        //         ->join('ref_kegiatans AS k', 'u.fid_kegiatan=k.id')
        //         ->join('ref_parts AS p', 'k.fid_part=p.id')
        //         ->where('k.fid_part', $this->session->userdata('part'))
        //         ->where('u.kode', $kode)
        //         ->get();
        //     if ($db->num_rows() > 0) {
        //         $this->output->set_status_header('400');
        //     } else {
        //         $this->output->set_status_header('200');
        //     }
        //     return false;
        // }

        if ($form === 'kegiatan') {
            $kode = $this->input->get('kode_kegiatan');
            $db   = $this->crud->getWhere('ref_kegiatans', ['kode' => $kode, 'tahun' => $this->session->userdata('tahun_anggaran')]);
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
        $this->onlyAdminBelanjaTable($type);
        if (isset($type)) {
            $p  = $this->input->post();
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

    private function getSisaLimit($id)
    {
        $getTotalLimit = $this->db->select('id,SUM(total) as total_limit')
            ->order_by('periode', 'asc')
            ->where('fid_uraian', $id)
            ->where('tahun', $this->session->userdata('tahun_anggaran'))
            ->where('is_perubahan', $this->session->userdata('is_perubahan'))
            ->get('t_pagu_limit')
            ->row();

        $getTotalPaguAwal = $this->crud->getWhere('t_pagu', ['fid_uraian' => $id, 'is_perubahan' => $this->session->userdata('is_perubahan'), 'tahun' => $this->session->userdata('tahun_anggaran')])->row()->total_pagu_awal ?? 0;

        $realisasi = $this->db->select('id,SUM(jumlah) as total_realisasi')
            ->where('fid_uraian', $id)
            ->where('tahun', $this->session->userdata('tahun_anggaran'))
            ->where('is_status', 'SELESAI')
            ->get('spj')
            ->row();

        $sisaLimit = ($getTotalPaguAwal - $getTotalLimit->total_limit - $realisasi->total_realisasi);

        return [
            'total_pagu_awal' => (int) $getTotalPaguAwal,
            'total_limit'     => $getTotalLimit,
            'total_realisasi' => (int) $realisasi->total_realisasi,
            'sisa_limit'      => (int) $sisaLimit,
        ];
    }

    public function sisaLimit()
    {
        $uraian_id = $this->input->post('id');
        $data      = $this->getSisaLimit($uraian_id);

        echo json_encode([
            'message'         => 'Sisa Limit Anggaran',
            'total_pagu_awal' => (int) $data['total_pagu_awal'],
            'total_limit'     => $data['total_limit'],
            'sisa_limit'      => (int) $data['sisa_limit'],
            'total_realisasi' => (int) $data['total_realisasi'],
        ]);
    }

    public function rekon_anggaran()
    {
        if (! empty($_FILES['file']['tmp_name'])) {
            $file_tmp = $_FILES['file']['tmp_name'];

            // Baca file langsung dari tmp_name
            $spreadsheet = IOFactory::load($file_tmp);
            $sheetData   = $spreadsheet->getActiveSheet()->toArray();

            if (count($sheetData) < 2) {
                echo json_encode([
                    'status'  => false,
                    'message' => "Data tidak lengkap atau kosong.",
                ]);
                return;
            }

            // Ambil baris pertama sebagai header
            $headers = $sheetData[0];
            $data    = [];

            // Mulai dari baris ke-2 untuk isi data
            for ($i = 1; $i < count($sheetData); $i++) {
                $row      = $sheetData[$i];
                $rowAssoc = [];

                foreach ($headers as $index => $headerName) {
                    $rowAssoc[$headerName] = isset($row[$index]) ? $row[$index] : null;
                }
                $data[] = $rowAssoc;
            }

            $is_perubahan = $this->session->userdata('is_perubahan');
            $result       = [];

            if (! empty($data)) {
                foreach ($data as $row) {
                    $fid_uraian = $row['ID_URAIAN'];
                    $total_pagu = $is_perubahan === "0"
                        ? get_only_numbers($row['TOTAL_PAGU_AWAL'])
                        : get_only_numbers($row['TOTAL_PAGU_PERUBAHAN']);

                    // Cek apakah kombinasi fid_uraian + is_perubahan sudah ada
                    $exists = $this->db->where('fid_uraian', $fid_uraian)
                        ->where('is_perubahan', $is_perubahan)
                        ->get('t_pagu')
                        ->num_rows() > 0;

                    $saveData = [
                        'fid_part'        => $this->session->userdata('part'),
                        'fid_uraian'      => $fid_uraian,
                        'is_perubahan'    => $is_perubahan,
                        'total_pagu_awal' => $total_pagu,
                        'tahun'           => $this->session->userdata('tahun_anggaran'),
                        'created_at'      => DateTimeInput(),
                        'created_by'      => $this->session->userdata('user_name'),
                    ];

                    if ($exists) {
                        // update jika sudah ada
                        $this->db->where('fid_uraian', $fid_uraian);
                        $this->db->where('is_perubahan', $is_perubahan);
                        $this->db->update('t_pagu', $saveData);
                    } else {
                        // insert jika belum ada
                        $this->db->insert('t_pagu', $saveData);
                    }

                    $result[] = $saveData;
                }
            }

            if (! empty($result)) {
                echo json_encode(['status' => true, 'message' => 'Rekonsiliasi Anggaran Berhasil']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Tidak ada data yang diproses']);
            }
        } else {
            echo json_encode([
                'status'  => false,
                'message' => "Tidak ada file yang diupload.",
            ]);
        }
    }
}
