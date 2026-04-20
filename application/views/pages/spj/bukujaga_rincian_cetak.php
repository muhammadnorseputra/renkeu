<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <title><?= $title ?></title>
    <style>
        @page {
            /* margin: 0.3cm 1cm 0.3cm 3.5cm; */
            margin: 0.3cm 1cm 0.3cm 1cm;
        }

        body {
            font-family: sans-serif;
            margin: 1.5cm 0;
            font-size: 0.8em;
        }

        #header,
        #footer {
            position: fixed;
            left: 0;
            right: 0;
            color: #aaa;
            font-size: 0.7em;
        }

        #header {
            top: 0;
            border-bottom: 0.1pt solid #aaa;
        }

        #footer {
            bottom: 0;
            border-top: 0.1pt solid #aaa;
        }

        span.page-number {
            float: right;
        }

        span.page-number:before {
            content: "Page " counter(page);
        }

        span.author {
            float: right;
            font-style: italic;
        }

        table {
            width: 100%;
            page-break-before: auto;
        }

        thead {
            background-color: #fff;
            font-size: 0.8em;
        }

        tbody {
            background-color: #fff;
        }

        th,
        td {
            padding: 3pt;
            border: 1pt solid #aaa;
        }

        table.collapse {
            border-collapse: collapse;
            border: 1pt solid #aaa;
        }

        table.collapse td {
            border: 1pt solid #aaa;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div id="header">
        <p><?= $post['kode_uraian'] ?> <?= $post['nama_uraian'] ?> <span class="author">Dicetak oleh : <?= $this->user->profile_username($this->session->userdata('user_name'))->row()->nama ?></span></p>
    </div>
    <div id="content">
        <table class="collapse">
            <tr>
                <td colspan="3" class="text-center">
                    <h2 style="margin:0; padding:0">PEMERINTAH KAB. BALANGAN</h2>
                    <h3 style="margin:0; padding:0"> BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA</h3>
                    <h4 style="margin:0; padding:0"><?= strtoupper($title) ?> </h4>
                    <small><i>Dicetak : <?= longdate_indo(date('Y-m-d')) ?></i></small>
                </td>
            </tr>
            <tr>
                <td width="15%">Bidang/Bagian</td>
                <td colspan="2"><?= $this->spj->getNama('ref_parts', $post['ref_part']) ?></td>
            </tr>
            <tr>
                <td>Program</td>
                <td width="15%"><?= $this->spj->getKode('ref_programs', $post['ref_program']) ?></td>
                <td><?= $this->spj->getNama('ref_programs', $post['ref_program']) ?></td>
            </tr>
            <tr>
                <td>Kegiatan</td>
                <td><?= $this->spj->getKode('ref_kegiatans', $post['ref_kegiatan']) ?></td>
                <td><?= $this->spj->getNama('ref_kegiatans', $post['ref_kegiatan']) ?></td>
            </tr>
            <tr>
                <td>Sub Kegiatan</td>
                <td><?= $this->spj->getKode('ref_sub_kegiatans', $post['ref_subkegiatan']) ?></td>
                <td><?= $this->spj->getNama('ref_sub_kegiatans', $post['ref_subkegiatan']) ?></td>
            </tr>
            <tr>
                <td>Rekening</td>
                <td><?= $this->spj->getKode('ref_uraians', $post['ref_uraian']) ?></td>
                <td><?= $this->spj->getNama('ref_uraians', $post['ref_uraian']) ?></td>
            </tr>
            <tr>
                <td>Pagu Anggaran</td>
                <?php $pagu = $this->spj->getPaguByUraianId($post['ref_uraian'], $this->session->userdata('tahun_anggaran'), $this->session->userdata('is_perubahan')) ?>
                <td colspan="2"><span style="color: green; font-weight: bold; font-size: 16px">Rp. <?= nominal($pagu); ?></span> <br>
                    <div style="color: #aaa;"><i>(<?= terbilang($pagu); ?>)</i></div>
                </td>
            </tr>
        </table>

        <table class="collapse">
            <thead>
                <tr>
                    <th rowspan="2" class="align-middle text-center">No</th>
                    <th rowspan="2" class="align-middle text-center">Tgl. Pembukuan</th>
                    <th rowspan="2" class="align-middle text-center">No. BKU</th>
                    <th rowspan="2" class="align-middle text-center">Uraian</th>
                    <th colspan="2" class="align-middle text-center">Realisasi Kegiatan</th>
                    <th rowspan="2" class="align-middle text-center">Sisa Anggaran</th>
                </tr>
                <tr>
                    <th class="text-center">LS</th>
                    <th class="align-middle text-center">UP/GU/TU</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $sisa = 0;
                $total_realisasi_ls = 0;
                $total_realisasi_not_ls = 0;
                foreach ($rincian as $r) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-start"><?= date_indo($r->tanggal_pembukuan); ?></td>
                        <td class="text-center"><?= $r->nomor_pembukuan; ?></td>
                        <td class="text-nowrap"><?= $r->uraian_spj; ?></td>
                        <td class="text-right">
                            <?php
                            $realisasi_ls = @$this->bukujaga->getRealisasiSPJByUraian($r->id, [
                                'fid_uraian' => $r->fid_uraian,
                                'tahun' => $ta,
                                'is_perubahan' => $is_perubahan,
                                'is_realisasi' => 'LS',
                                'is_status' => 'SELESAI'
                            ]);
                            $total_realisasi_ls += $realisasi_ls;
                            ?>
                            <div>
                                <span><?= nominal($realisasi_ls); ?></span>
                            </div>
                        </td>
                        <td class="text-right">
                            <?php
                            $realisasi_not_ls = @$this->bukujaga->getRealisasiSPJByUraian($r->id, [
                                'fid_uraian' => $r->fid_uraian,
                                'tahun' => $ta,
                                'is_perubahan' => $is_perubahan,
                                'is_realisasi !=' => 'LS',
                                'is_status' => 'SELESAI'
                            ]);
                            $total_realisasi_not_ls += $realisasi_not_ls;
                            ?>
                            <div class="d-flex justify-content-between">
                                <span><?= nominal($realisasi_not_ls); ?></span>
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="d-flex justify-content-between">
                                <span>
                                    <?php
                                    $sisa = $pagu - $r->jumlah;
                                    echo nominal($sisa);
                                    $pagu = $sisa;
                                    ?>
                                </span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="4" class="text-center">Total</th>
                    <th class="text-right">
                        <div class="d-flex justify-content-between">
                            <span><?= nominal($total_realisasi_ls); ?></span>
                        </div>
                    </th>
                    <th class="text-right">
                        <div class="d-flex justify-content-between">
                            <span><?= nominal($total_realisasi_not_ls); ?></span>
                        </div>
                    </th>
                    <th class="text-right">
                        <div class="d-flex justify-content-between">
                            <span><?= nominal($sisa); ?></span>
                        </div>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="footer">
        <p>Copyright <?= date('Y') ?> ::: SIMEV (<?= getSetting('version_app') ?>) <span class="page-number"></span></p>
    </div>
</body>

</html>