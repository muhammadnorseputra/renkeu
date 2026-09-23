<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_session();
        //  CEK USER PRIVILAGES
        if (!privilages('priv_default') && !privilages('priv_anggarankinerja')):
            return show_404();
        endif;
    }

    public function index()
    {
        // Ambil data PNS dari API SILKA (default UNOR 1120)
        $response_pns = silka_get_pegawai_by_unor('1120');
        $pegawai_pns  = isset($response_pns['data']) ? $response_pns['data'] : [];

        // Ambil data PPPK dari endpoint khusus PPPK
        $response_pppk = silka_get_pppk();
        $pegawai_pppk  = isset($response_pppk['data']) ? $response_pppk['data'] : [];

        // NIP yang sudah ter-mapping di tabel pegawai
        $mapped = $this->crud->get('pegawai')->result();
        $mapped_nips = array_column($mapped, 'nip');
        $mapped_bidang = [];
        $mapped_pegawai = [];
        foreach ($mapped as $m) {
            $mapped_bidang[$m->nip] = $m->fid_part;
            $mapped_pegawai[$m->nip] = $m;
        }

        $synced_pns  = count(array_intersect($mapped_nips, array_column($pegawai_pns, 'nip_baru')));
        $synced_pppk = count(array_intersect($mapped_nips, array_column($pegawai_pppk, 'nipppk')));
        $sudah_sync  = $synced_pns + $synced_pppk;
        $total_asn   = count($pegawai_pns) + count($pegawai_pppk);
        $belum_sync  = $total_asn - $sudah_sync;

        $data = [
            'title'          => 'Mapping Pegawai',
            'content'        => 'pages/pegawai',
            'pegawai_pns'    => $pegawai_pns,
            'pegawai_pppk'   => $pegawai_pppk,
            'total_asn'      => $total_asn,
            'sudah_sync'     => $sudah_sync,
            'belum_sync'     => $belum_sync,
            'synced_pns'     => $synced_pns,
            'synced_pppk'    => $synced_pppk,
            'list_bidang'    => $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN'])->result(),
            'mapped_nips'    => $mapped_nips,
            'mapped_bidang'  => $mapped_bidang,
            'mapped_pegawai' => $mapped_pegawai,
            'message'        => isset($response_pns['message']) ? $response_pns['message'] : '',
            'api_ok'         => isset($response_pns['status']) && $response_pns['status'] === true,
            'autoload_css'   => [
                'template/custom-css/pegawai.css',
            ],
            'autoload_js'    => [
                'template/custom-js/pegawai.js',
            ],
        ];
        $this->load->view('layout/app', $data);
    }

    /**
     * Simpan / update mapping pegawai ke tabel pegawai (kunci: NIP).
     * Jika NIP sudah ada -> update, jika belum -> insert.
     */
    public function save_mapping()
    {
        $nip     = trim($this->input->post('nip'));
        $nama    = trim($this->input->post('nama'));
        $jabatan = trim($this->input->post('jabatan'));
        $pangkat = trim($this->input->post('pangkat'));
        $jenis   = $this->input->post('jenis');
        $bidang  = $this->input->post('bidang');

        if ($nip === '' || $nama === '' || $bidang === '') {
            echo json_encode(['status' => false, 'pesan' => 'NIP, Nama, dan Bidang wajib diisi.']);
            return;
        }

        // Validasi jenis: hanya PNS atau PPPK
        if (!in_array($jenis, ['PNS', 'PPPK'])) {
            $jenis = 'PNS';
        }

        $user = $this->session->userdata('user_name');
        $now  = date('Y-m-d H:i:s');

        $data = [
            'nip'          => $nip,
            'nama_lengkap' => $nama,
            'jabatan'      => $jabatan,
            'pangkat'      => $pangkat,
            'jenis'        => $jenis,
            'fid_part'     => (int) $bidang,
            'updated_at'   => $now,
            'updated_by'   => $user,
        ];

        $exists = $this->crud->getWhere('pegawai', ['nip' => $nip]);

        if ($exists->num_rows() > 0) {
            $ok = $this->crud->update('pegawai', $data, ['nip' => $nip]);
            $pesan = 'Data pegawai berhasil diperbarui.';
        } else {
            $data['created_at'] = $now;
            $data['created_by'] = $user;
            $ok = $this->crud->insert('pegawai', $data);
            $pesan = 'Data pegawai berhasil disimpan.';
        }

        echo json_encode(['status' => $ok, 'pesan' => $ok ? $pesan : 'Gagal menyimpan data.']);
    }

    /**
     * Hapus data pegawai dari tabel pegawai berdasarkan NIP.
     */
    public function delete_mapping()
    {
        $nip = trim($this->input->post('nip'));

        if ($nip === '') {
            echo json_encode(['status' => false, 'pesan' => 'NIP wajib diisi.']);
            return;
        }

        $exists = $this->crud->getWhere('pegawai', ['nip' => $nip]);

        if ($exists->num_rows() === 0) {
            echo json_encode(['status' => false, 'pesan' => 'Data pegawai tidak ditemukan.']);
            return;
        }

        $ok = $this->crud->deleteWhere('pegawai', ['nip' => $nip]);

        echo json_encode(['status' => $ok, 'pesan' => $ok ? 'Data pegawai berhasil dihapus.' : 'Gagal menghapus data.']);
    }

    /**
     * Ambil data pegawai berdasarkan Unit Organisasi (UNOR) dari API SILKA.
     *
     * @param  string $unor_id  ID unit organisasi (default: 1120)
     * @return array            Data pegawai dari SILKA
     */
    public function get_pegawai_by_unor($unor_id = '1120')
    {
        return silka_get_pegawai_by_unor($unor_id);
    }
}