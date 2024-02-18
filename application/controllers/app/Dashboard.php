<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
        $this->load->model('ModelSpj', 'spj');
        $this->load->model('ModelTarget', 'target');
		$this->load->model('ModelRealisasi', 'realisasi');
		$this->load->model('ModelUsers', 'user');
    }
	
	public function index()
	{
		// Panel Dashboard
		$db_program = $this->crud->get('ref_programs');
		$db_indikator = $this->crud->get('ref_indikators');
		$ProgramTotalPaguAwal = 0;
		$ProgramTotalRealisasi = 0;
		foreach ($db_program->result() as $r) :
		$ProgramTotalPaguAwal += $this->target->getAlokasiPaguProgram($r->id)->row()->total_pagu_awal;
		$ProgramTotalRealisasi += $this->realisasi->getRealisasiTahunProgram($r->kode);
        endforeach;
		$persentase_capaian = ($ProgramTotalRealisasi/$ProgramTotalPaguAwal)*100;

		// Chart
		$db_transaksi = $this->spj->TopTransaksiSPJ();
		$spj_ms = [];
		$spj_tms = [];
		$spj_btl = [];
		for($i=1; $i<=12; $i++){ 
			$jumlah = @$this->spj->TransaksiSpjBulanan($i) != null ? @$this->spj->TransaksiSpjBulanan($i) : 0;
			$jumlah_tms = @$this->spj->TransaksiSpjBulananNonMs($i, 'TMS') != null ? @$this->spj->TransaksiSpjBulananNonMs($i, 'TMS') : 0;
			$jumlah_btl = @$this->spj->TransaksiSpjBulananNonMs($i, 'BTL') != null ? @$this->spj->TransaksiSpjBulananNonMs($i, 'BTL') : 0;
			$spj_ms[] = [bulan($i), $jumlah]; 
			$spj_tms[] = [bulan($i), $jumlah_tms];
			$spj_btl[] = [bulan($i), $jumlah_btl];

		}
		$db_triwulan = $this->spj->getPeriode();
		$db_parts = $this->crud->getWhere('ref_parts', ['singkatan !=' => 'KABAN']);
		$label = [];
		$part_jumlah = [];
		$spj_count_ms = [];
		$spj_count_tms = [];
		$spj_count_btl = [];
		foreach($db_parts->result() as $part):
			$label[] = $part->singkatan;
			$jumlah = @$this->spj->getRealisasiSpjByPart($part->id) != null ? @$this->spj->getRealisasiSpjByPart($part->id) : 0;
			$part_jumlah[] = (int) $jumlah;
			$spj_count_ms[] = (int) @$this->spj->getJumlahSpjByPart($part->id, 'APPROVE');
			$spj_count_tms[] = (int) @$this->spj->getJumlahSpjByPart($part->id, 'TMS');
			$spj_count_btl[] = (int) @$this->spj->getJumlahSpjByPart($part->id, 'BTL');
		endforeach;

		$data = [
			'title' => 'Dashboard',
            'content' => 'pages/dashboard',
			'panel' => [
				'program_total_pagu' => $ProgramTotalPaguAwal,
				'program_total_realisasi' => $ProgramTotalRealisasi,
				'jumlah_indikator' => $db_indikator->num_rows(),
				'persentase_capaian' => $persentase_capaian
			],
			'chart' => [
				'top_transaksi' => $db_transaksi->result(),
				'spj_ms' => json_encode($spj_ms),
				'spj_tms' => json_encode($spj_tms),
				'spj_btl' => json_encode($spj_btl),
				'triwulan' => $db_triwulan->result(),
				'part_label' => json_encode($label),
				'part_jumlah' => json_encode($part_jumlah),
				'spj_count_ms' => json_encode($spj_count_ms),
				'spj_count_tms' => json_encode($spj_count_tms),
				'spj_count_btl' => json_encode($spj_count_btl),
			],
			'autoload_css' => [
				'template/backend/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css'
			],
			'autoload_js' => [
                'template/backend/vendors/Chart.js/dist/Chart.bundle.min.js',
                'template/backend/vendors/jquery-sparkline/dist/jquery.sparkline.min.js',
                'template/backend/vendors/Flot/jquery.flot.js',
                'template/backend/vendors/Flot/jquery.flot.resize.js',
                'template/backend/vendors/Flot/jquery.flot.categories.js',
                'template/backend/vendors/Flot/jquery.flot.tooltip.js',
                'template/backend/vendors/DateJS/build/date.js',
				'template/backend/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js'
            ]
        ];
		$this->load->view('layout/app', $data);
	}

	public function laporan_pdf(){

		$data = array(
			"dataku" => array(
				"nama" => "Petani Kode",
				"url" => "http://petanikode.com"
			)
		);
	
		$this->load->library('pdf');
	
		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "laporan-petanikode.pdf";
		$this->pdf->load_view('laporan', $data);
	
	
	}
}
