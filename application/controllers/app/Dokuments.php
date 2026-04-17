<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dokuments extends CI_Controller
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

		$this->load->model('ModelTarget', 'target');
		$this->load->model('ModelSpj', 'spj');
	}

    public function pengelolaan_resiko()
    {
		$data = [
			'title' => 'Dokumen Pengelolaan Resiko',
			'content' => 'pages/anggaran_kinerja/upload_pengelolaan_resiko',
            'list_bidang' => $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN'])->result(),
			'autoload_js' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.js',
				'template/backend/vendors/select2/dist/js/select2.full.min.js',
				'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/tabel-pengelolaan-resiko.js',
			],
            'autoload_css' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.css',
            ]
		];
		$this->load->view('layout/app', $data);
    }

    public function delete_pengelolaan_resiko()
    {
        $id = $this->input->post('id');
        $dok = $this->crud->getWhere('t_dokumen_pengelolaan_resiko', ['id' => $id])->row();

        if (!$dok) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        $filePath = FCPATH . 'template/upload/dokumen_pengelolaan_resiko/' . $dok->file_path;

        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
        }

        $db = $this->crud->deleteWhere('t_dokumen_pengelolaan_resiko', ['id' => $id]);

        if ($db) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Dokumen berhasil dihapus', 'status' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Gagal menghapus dokumen', 'status' => false]);
        }
    }

    public function verifikasi_pengelolaan_resiko()
    {
        $id = $this->input->post('id');

        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'ID dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        // ambil record saat ini
        $dok = $this->crud->getWhere('t_dokumen_pengelolaan_resiko', ['id' => $id])->row();
        if (!$dok) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        // toggle is_kunci: jika 1 jadi 0, jika 0 jadi 1
        $current = (int) $dok->is_kunci;
        $new_value = $current === 1 ? "0" : "1";

        $status_text = $new_value === 1 ? 'Terverifikasi' : 'Belum Terverifikasi';

        // transaction
        $this->db->trans_begin();
        $this->crud->update('t_dokumen_pengelolaan_resiko', ['is_kunci' => $new_value, 'catatan' => ''], ['id' => $id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $msg = ['pesan' => 'Gagal memverifikasi dokumen', 'status' => false];
        } else {
            $this->db->trans_commit();
            $msg = ['pesan' => 'Berhasil memperbarui status verifikasi - '. $status_text, 'status' => true, 'is_kunci' => $new_value];
        }

        header('Content-Type: application/json');
        echo json_encode($msg);

    }

    public function get_catatan_pengelolaan_resiko()
    {
        $id = $this->input->post('id');
        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'ID dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        $dok = $this->crud->getWhere('t_dokumen_pengelolaan_resiko', ['id' => $id])->row();
        if (!$dok) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode(['pesan' => 'Berhasil mengambil catatan', 'status' => true, 'catatan' => $dok->catatan]);
    }

    public function simpan_catatan_pengelolaan_resiko()
    {
        $id = $this->input->post('id');
        $catatan = $this->input->post('catatan');

        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'ID dokumen tidak ditemukan', 'status' => false]);
            return;
        }

        // transaction
        $this->db->trans_begin();
        $this->crud->update('t_dokumen_pengelolaan_resiko', ['catatan' => $catatan], ['id' => $id]);
        $this->db->trans_commit();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            header('Content-Type: application/json');
            echo json_encode(['pesan' => 'Gagal menyimpan catatan', 'status' => false]);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode(['pesan' => 'Berhasil menyimpan catatan', 'status' => true]);
    }

    public function perjanjian_kerja()
	{
		$data = [
			'title' => 'Dokumen Perjanjian Kinerja',
			'content' => 'pages/anggaran_kinerja/upload_pk',
            'list_bidang' => $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN'])->result(),
			'autoload_js' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.js',
				'template/backend/vendors/select2/dist/js/select2.full.min.js',
				'template/backend/vendors/parsleyjs/dist/parsley.min.js',
                'template/custom-js/tabel-kinerja-non-pk.js',
			],
            'autoload_css' => [
                'https://cdn.datatables.net/v/bs4/dt-2.3.7/af-2.7.1/b-3.2.6/b-colvis-3.2.6/b-html5-3.2.6/b-print-3.2.6/cr-2.1.2/cc-1.2.1/date-1.6.3/fc-5.0.5/fh-4.0.6/kt-2.12.2/r-3.0.8/rg-1.6.0/rr-1.5.1/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.min.css',
            ]
		];
		$this->load->view('layout/app', $data);
	}

    public function verifikasi_dokument_non_pk()
    {
        $post = $this->input->post();
        $id = $post['id'];
        $is_kunci = $post['is_kunci'];
        $catatan = $post['catatan'] ?? '';

        if (empty($id) || !isset($is_kunci)) {
            header('Content-Type: application/json');
            echo json_encode(['message' => 'ID dokumen atau status verifikasi tidak ditemukan', 'status' => false]);
            return; 
        }

        // transaction
        $this->db->trans_begin();
        $this->crud->update('t_dokumen_pk', ['is_kunci' => $is_kunci, 'catatan' => $catatan], ['id' => $id]);
        $this->db->trans_commit();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Gagal memverifikasi dokumen', 'status' => false]);
            return;
        }

        $status_text = $is_kunci == 1 ? 'Terverifikasi' : 'Belum Diverifikasi';
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Berhasil memperbarui status verifikasi - '. $status_text, 'status' => true, 'is_kunci' => $is_kunci]);
    }
}