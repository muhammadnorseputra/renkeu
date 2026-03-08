<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Uploads extends CI_Controller
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
            'ModelUploads' => 'uploads',
        ]);
	}

    public function pengelolaan_resiko()
    {
        $getFileName = $_FILES['file']['name'];
        $periode = $this->input->post('periode');
        $tahun = $this->session->userdata('tahun_anggaran');
        $part_id = $this->session->userdata('part');
        $namapart = $this->crud->getWhere('ref_parts', ['id' => $part_id])->row()->singkatan;
        $namafile = 'Pengelolaan-Resiko-' . $namapart .'-'. $periode . '-' . $tahun .'-'. generateRandomString();

        
        
        // validasi form dan upload file ke folder /template/upload/dokumen_pengelolaan_resiko/
		$config = [
			'upload_path'   => './template/upload/dokumen_pengelolaan_resiko/',
			'allowed_types' => 'pdf',
			'max_size'      => 2120, // 2MB
			'file_name'     => $namafile,
			'overwrite'     => true
		];

        $this->load->library('upload', $config);

		if (!$this->upload->do_upload('file')) {
			$this->session->set_flashdata('alert_type', 'error');
			$this->session->set_flashdata('alert_msg', $this->upload->display_errors());
			return redirect(base_url('app/dokuments/pengelolaan_resiko'));
		}

        // Cek apakah sudah ada file untuk part dan tahun yg sama
		$existing = $this->crud->getWhere('t_dokumen_pengelolaan_resiko', ['fid_part' => $part_id, 'periode' => $periode, 'tahun' => $tahun, 'created_by' => $this->session->userdata('user_name')]);
        // jika sudah ada, hapus file lama dari server
        if ($existing->num_rows() > 0) {
            $oldFile = $existing->row()->file_path;
            $oldFilePath = FCPATH . 'template/upload/dokumen_pengelolaan_resiko/' . $oldFile;
            if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        $upload_data = $this->upload->data();

		$data = [
            'periode' => $periode,
			'fid_part' => $part_id,
            'nama_dokumen_ori' => $getFileName,
			'nama_dokumen' => $namafile,
			'file_path' => $upload_data['file_name'],
			'ukuran_file' => $upload_data['file_size'],
			'tipe_file' => $upload_data['file_type'],
			'tahun' => $tahun,
			'created_by' => $this->session->userdata('user_name'),
			'created_at' => DateTimeInput()
		];

        // jika sudah ada record, lakukan update; jika belum, insert baru
		if ($existing->num_rows() > 0) {
			$db = $this->crud->update('t_dokumen_pengelolaan_resiko', $data, ['fid_part' => $part_id, 'tahun' => $tahun, 'periode' => $periode, 'created_by' => $this->session->userdata('user_name')]);
            if($db) {
                $this->session->set_flashdata('alert_type', 'success');
                $this->session->set_flashdata('alert_msg', 'Dokument Pengelolaan Resiko berhasil diperbarui');
            } else {
                $this->session->set_flashdata('alert_type', 'error');
                $this->session->set_flashdata('alert_msg', 'Gagal memperbarui Dokument Pengelolaan Resiko');
            }
            return redirect(base_url('app/dokuments/pengelolaan_resiko'));
		}

        $db = $this->crud->insert('t_dokumen_pengelolaan_resiko', $data);
        if($db) {
            $this->session->set_flashdata('alert_type', 'success');
            $this->session->set_flashdata('alert_msg', 'Pengelolaan Resiko berhasil diunggah');
        } else {
            $this->session->set_flashdata('alert_type', 'error');
            $this->session->set_flashdata('alert_msg', 'Gagal mengunggah Pengelolaan Resiko');
        }
        return redirect(base_url('app/dokuments/pengelolaan_resiko'));
    }

}