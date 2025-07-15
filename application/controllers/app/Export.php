<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Export extends CI_Controller
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
    protected $excel;
    protected $tahun_anggaran;
    protected $part;

    public function __construct()
    {
        parent::__construct();
        cek_session();
        //  CEK USER PRIVILAGES 
        if (!privilages('priv_default')):
            return show_404();
        endif;
        $this->excel = new Spreadsheet();
        $this->load->model('ModelExport', 'export');
        $this->load->model('ModelCrud', 'crud');
        $this->load->model('ModelTarget', 'target');
        $this->tahun_anggaran = $this->session->userdata('tahun_anggaran');
        $this->part = $this->session->userdata('part');
    }

    public function program()
    {
        $db = $this->target->program(null, $this->part, $this->tahun_anggaran);

        $sheet = $this->excel->getActiveSheet();
        $sheet->setCellValue('A1', 'ID_PROGRAM');
        $sheet->setCellValue('B1', 'KODE');
        $sheet->setCellValue('C1', 'NAMA');
        $sheet->setCellValue('D1', 'TAHUN');

        // options
        $sheet->getDefaultColumnDimension()->setAutoSize(true);
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FF4F81BD')
            )
        ]);

        $protection = $sheet->getProtection();
        $allowed = $protection->verify(date('d-m-Y'));

        if ($allowed === false) {
            throw new Exception("Incorrect password");
        }

        $col = 2;
        foreach ($db->result() as $row):
            $sheet->setCellValue('A' . $col, $row->id);
            $sheet->setCellValueExplicit('B' . $col, $row->kode, DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $col, $row->nama);
            $sheet->setCellValue('D' . $col, $row->tahun);
            $col++;
        endforeach;

        $partId = $this->part;
        $partName = $this->export->getPartName($partId)->nama;

        $writer = new Xlsx($this->excel);
        $filename = 'PROGRAM-' . $partName . '-' . $this->tahun_anggaran;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function kegiatan()
    {

        if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN') :
            $db = $this->db->select('k.id,k.fid_part,k.fid_program,k.kode,k.nama,k.tahun,part.nama as nama_part, prog.nama as nama_program')
                ->join('ref_parts as part', 'k.fid_part=part.id')
                ->join('ref_programs as prog', 'k.fid_program=prog.id')
                ->order_by('k.kode', 'asc')
                ->where('k.tahun', $this->tahun_anggaran)
                ->get('ref_kegiatans as k');
        else :
            $db = $this->db->select('k.id,k.fid_part,k.fid_program,k.kode,k.nama,k.tahun,part.nama as nama_part, prog.nama as nama_program')
                ->join('ref_parts as part', 'k.fid_part=part.id')
                ->join('ref_programs as prog', 'k.fid_program=prog.id')
                ->order_by('k.kode', 'asc')
                ->where('k.fid_part', $this->part)
                ->where('k.tahun', $this->tahun_anggaran)
                ->get('ref_kegiatans as k');
        endif;

        $sheet = $this->excel->getActiveSheet();
        $sheet->setCellValue('A1', 'ID_KEGIATAN');
        $sheet->setCellValue('B1', 'FID_PART');
        $sheet->setCellValue('C1', 'NAMA_PART');
        $sheet->setCellValue('D1', 'FID_PROGRAM');
        $sheet->setCellValue('E1', 'NAMA_PROGRAM');
        $sheet->setCellValue('F1', 'KODE');
        $sheet->setCellValue('G1', 'NAMA');
        $sheet->setCellValue('H1', 'TAHUN');

        // options
        $sheet->getDefaultColumnDimension()->setAutoSize(true);
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FF4F81BD')
            )
        ]);

        $protection = $sheet->getProtection();
        $allowed = $protection->verify(date('d-m-Y'));

        if ($allowed === false) {
            throw new Exception("Incorrect password");
        }

        $col = 2;
        foreach ($db->result() as $row):
            $sheet->setCellValue('A' . $col, $row->id);
            $sheet->setCellValue('B' . $col, $row->fid_part);
            $sheet->setCellValue('C' . $col, $row->nama_part);
            $sheet->setCellValue('D' . $col, $row->fid_program);
            $sheet->setCellValue('E' . $col, $row->nama_program);
            $sheet->setCellValueExplicit('F' . $col, $row->kode, DataType::TYPE_STRING);
            $sheet->setCellValue('G' . $col, $row->nama);
            $sheet->setCellValue('H' . $col, $row->tahun);
            $col++;
        endforeach;

        $partId = $this->part;
        $partName = $this->export->getPartName($partId)->nama;

        $writer = new Xlsx($this->excel);
        $filename = 'KEGIATAN-' . $partName . '-' . $this->tahun_anggaran;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function sub_kegiatan()
    {
        if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN') :
            $db = $this->db->select('sub.*,keg.nama_kegiatan')->order_by('sub.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id', 'inner')
                ->where('sub.tahun', $this->tahun_anggaran)
                ->get('ref_sub_kegiatans AS sub');
        else :
            $db = $this->db->select('sub.id,sub.fid_kegiatan,sub.kode,sub.nama,sub.tahun,keg.nama as nama_kegiatan')
                ->order_by('sub.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'sub.fid_kegiatan=keg.id', 'inner')
                ->where('keg.fid_part', $this->part)
                ->where('sub.tahun', $this->tahun_anggaran)
                ->get('ref_sub_kegiatans AS sub');
        endif;

        $sheet = $this->excel->getActiveSheet();
        $sheet->setCellValue('A1', 'ID_SUB_KEGIATAN');
        $sheet->setCellValue('B1', 'FID_KEGIATAN');
        $sheet->setCellValue('C1', 'NAMA_KEGIATAN');
        $sheet->setCellValue('D1', 'KODE');
        $sheet->setCellValue('E1', 'NAMA');
        $sheet->setCellValue('F1', 'TAHUN');

        // options
        $sheet->getDefaultColumnDimension()->setAutoSize(true);
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FF4F81BD')
            )
        ]);

        $protection = $sheet->getProtection();
        $allowed = $protection->verify(date('d-m-Y'));

        if ($allowed === false) {
            throw new Exception("Incorrect password");
            return false;
        }

        $col = 2;
        foreach ($db->result() as $row):
            $sheet->setCellValue('A' . $col, $row->id);
            $sheet->setCellValue('B' . $col, $row->fid_kegiatan);
            $sheet->setCellValue('C' . $col, $row->nama_kegiatan);
            $sheet->setCellValueExplicit('D' . $col, $row->kode, DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $col, $row->nama);
            $sheet->setCellValue('F' . $col, $row->tahun);
            $col++;
        endforeach;

        $partId = $this->part;
        $partName = $this->export->getPartName($partId)->nama;

        $writer = new Xlsx($this->excel);
        $filename = 'SUB-KEGIATAN-' . $partName . '-' . $this->tahun_anggaran;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function uraian()
    {
        if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN') :
            $db = $this->db->select('u.id,u.fid_kegiatan,u.fid_sub_kegiatan,u.kode,u.nama,u.tahun,keg.kode AS kode_kegiatan,keg.nama AS nama_kegiatan, keg.fid_part, sub.kode AS kode_sub_kegiatan,sub.nama AS nama_sub_kegiatan')
                ->order_by('u.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'u.fid_kegiatan=keg.id', 'inner')
                ->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id', 'inner')
                ->where('u.tahun', $this->tahun_anggaran)
                ->get('ref_uraians AS u');
        else :
            $db = $this->db->select('u.id,u.fid_kegiatan,u.fid_sub_kegiatan,u.kode,u.nama,u.tahun,keg.kode AS kode_kegiatan,keg.nama AS nama_kegiatan, keg.fid_part, sub.kode AS kode_sub_kegiatan,sub.nama AS nama_sub_kegiatan')
                ->order_by('u.kode', 'asc')
                ->join('ref_kegiatans AS keg', 'u.fid_kegiatan=keg.id', 'inner')
                ->join('ref_sub_kegiatans AS sub', 'u.fid_sub_kegiatan=sub.id', 'inner')
                ->where('keg.fid_part', $this->part)
                ->where('u.tahun', $this->tahun_anggaran)
                ->get('ref_uraians AS u');
        endif;

        $sheet = $this->excel->getActiveSheet();
        $sheet->setCellValue('A1', 'ID_URAIAN');
        $sheet->setCellValue('B1', 'FID_PART');
        $sheet->setCellValue('C1', 'FID_KEGIATAN');
        $sheet->setCellValue('D1', 'NAMA_KEGIATAN');
        $sheet->setCellValue('E1', 'FID_SUB_KEGIATAN');
        $sheet->setCellValue('F1', 'NAMA_SUB_KEGIATAN');
        $sheet->setCellValue('G1', 'KODE');
        $sheet->setCellValue('H1', 'NAMA');
        $sheet->setCellValue('I1', 'TOTAL_PAGU_AWAL');
        $sheet->setCellValue('J1', 'TOTAL_PAGU_PERUBAHAN');
        $sheet->setCellValue('K1', 'TAHUN');

        // options
        $sheet->getDefaultColumnDimension()->setAutoSize(true);
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FF4F81BD')
            )
        ]);

        $protection = $sheet->getProtection();
        $allowed = $protection->verify(date('d-m-Y'));

        if ($allowed === false) {
            throw new Exception("Incorrect password");
            return false;
        }

        $col = 2;
        foreach ($db->result() as $row):
            $pagu_awal = $this->crud->getWhere('t_pagu', ['fid_uraian' => $row->id, 'is_perubahan' => '0'])->row();
            $paguPerubahan = $this->crud->getWhere('t_pagu', ['fid_uraian' => $row->id, 'is_perubahan' => '1'])->row();

            $totalPaguAwal = !empty($pagu_awal->total_pagu_awal) ? $pagu_awal->total_pagu_awal : 0;
            $totalPaguPerubahan = !empty($paguPerubahan->total_pagu_awal) ? $paguPerubahan->total_pagu_awal : 0;

            $sheet->setCellValue('A' . $col, $row->id);
            $sheet->setCellValue('B' . $col, $row->fid_part);
            $sheet->setCellValue('C' . $col, $row->fid_kegiatan);
            $sheet->setCellValue('D' . $col, $row->nama_kegiatan);
            $sheet->setCellValue('E' . $col, $row->fid_sub_kegiatan);
            $sheet->setCellValue('F' . $col, $row->nama_sub_kegiatan);
            $sheet->setCellValueExplicit('G' . $col, $row->kode, DataType::TYPE_STRING);
            $sheet->setCellValue('H' . $col, $row->nama);
            $sheet->setCellValueExplicit('I' . $col, nominal($totalPaguAwal), DataType::TYPE_STRING2);
            $sheet->setCellValueExplicit('J' . $col, nominal($totalPaguPerubahan), DataType::TYPE_STRING2);
            $sheet->setCellValue('K' . $col, $row->tahun);
            $col++;
        endforeach;

        $partId = $this->part;
        $partName = $this->export->getPartName($partId)->nama;

        $writer = new Xlsx($this->excel);
        $filename = 'URAIAN-' . $partName . '-' . $this->tahun_anggaran;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
