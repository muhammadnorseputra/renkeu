<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Datatables extends CI_Controller
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
		if (!privilages('priv_default') && $this->session->userdata('is_valid_profile') === "0"):
			return show_404();
		endif;

        $this->load->model([
            'ModelDatatables' => 'datatables',
        ]);
	}

    public function pengelolaan_resiko()
    {
        $db = $this->datatables->make_datatables_pengelolaan_resiko();
        $data = array();
        $no = @$_POST['start'];

        foreach ($db as $r) {

            $filename = $r->file_path;
            $server_path = FCPATH . 'template/upload/dokumen_pengelolaan_resiko/' . $filename;
            $public_url  = base_url('template/upload/dokumen_pengelolaan_resiko/' . $filename);
            $file_exists = file_exists($server_path) && is_file($server_path);
            
            if ($file_exists):
                $unduh = '<a href="' . $public_url . '" target="_blank" rel="noopener" class="btn btn-success" title="Unduh dokumen Pengelolaan Resiko">
                    <i class="fa fa-download mr-1"></i> Unduh
                </a>';
            else:
                $unduh = '<button class="btn btn-outline-secondary" disabled title="File belum tersedia">
                    <i class="fa fa-download mr-1"></i> Unduh
                </button>';
            endif;

            if(in_array($this->session->userdata('role'), ['USER']) && $r->is_kunci == 0 && $r->created_by === $this->session->userdata('user_name')):
                $btnDelete = '
                    <button type="button" class="btn btn-danger" title="Hapus Dokumen" onclick="HapusDokumen(' . $r->id . ')">
                        <i class="fa fa-trash mr-1"></i> Hapus
                    </button>
                    ';
            else:
                $btnDelete = '';
            endif;

            if(in_array($this->session->userdata('role'), ['ADMIN', 'VERIFICATOR'])):
                if($r->is_kunci == 0):
                    $btnVerifikasi = '<button type="button" class="btn btn-primary" onclick="VerifikasiDokumen(' . $r->id . ')" title="Verifikasi Dokumen">
                    <i class="fa fa-check mr-1"></i> Verifikasi
                </button>';
                else:
                    $btnVerifikasi = '<button type="button" class="btn btn-danger" title="Unverifikasi Dokumen" onclick="VerifikasiDokumen(' . $r->id . ')">
                        <i class="fa fa-times mr-1"></i> Unverifikasi
                    </button>';
                endif;
            else:
                $btnVerifikasi = '';
            endif;


            $btnAksi = '
            <!-- Download / Status -->
            <div class="d-flex align-items-center" style="gap:.5rem; white-space:nowrap;">
                ' . $unduh . '
                ' . $btnDelete . '
                ' . $btnVerifikasi . '
            </div>
            ';

            $terverifikasi = $r->is_kunci == 1 ? '<span class="badge badge-success p-2"><i class="fa fa-check-circle mr-1"></i> Terverifikasi</span>' : '<span class="badge badge-warning p-2"><i class="fa fa-exclamation-circle mr-1"></i> Belum Terverifikasi</span>';

            $no++;
            $row = array();
            $row['no'] = $no;
            $row['bidang'] = $r->nama_part;
            $row['periode'] = $r->periode;
            $row['tahun'] = $r->tahun;
            $row['user'] = $r->created_by;
            $row['file'] = '<span class="badge badge-light p-2"><i class="fa fa-file mr-2"></i> ' . $r->nama_dokumen . '</span> ' . $terverifikasi;
            $row['action'] = $btnAksi;
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->datatables->make_count_all_pengelolaan_resiko(),
            "recordsFiltered" => $this->datatables->make_count_filtered_pengelolaan_resiko(),
            "data" => $data,
        );
        //output to json format
        header('Content-Type: application/json');
        echo json_encode($output);
    }

}