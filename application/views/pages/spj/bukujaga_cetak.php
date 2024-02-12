<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <title><?= $title ?></title>
    <style>
        @page {
            margin: 0.3cm 1cm 0.3cm 3.5cm;
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
            page-break-before:auto;
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
        <p><?= $post['kodesub'] ?> <?= $post['namasub'] ?> <span class="author">Dicetak oleh : <?= $this->user->profile_username($this->session->userdata('user_name'))->row()->nama ?></span></p>
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
                <td colspan="2"><?= $this->spj->getNama('ref_parts',$post['ref_part']) ?></td>
            </tr>
            <tr>
                <td>Program</td>
                <td width="15%"><?= $this->spj->getKode('ref_programs',$post['ref_program']) ?></td>
                <td><?= $this->spj->getNama('ref_programs',$post['ref_program']) ?></td>
            </tr>
            <tr>
                <td>Kegiatan</td>
                <td><?= $this->spj->getKode('ref_kegiatans',$post['ref_kegiatan']) ?></td>
                <td><?= $this->spj->getNama('ref_kegiatans',$post['ref_kegiatan']) ?></td>
            </tr>
            <tr>
                <td>Sub Kegiatan</td>
                <td><?= $this->spj->getKode('ref_sub_kegiatans',$post['ref_subkegiatan']) ?></td>
                <td><?= $this->spj->getNama('ref_sub_kegiatans',$post['ref_subkegiatan']) ?></td>
            </tr>
        </table>

        <table class="collapse">
            <thead>
                <tr>
                    <th rowspan="2" class="align-middle text-center">No</th>
                    <th rowspan="2" class="align-middle text-center">Kode Rekening</th>
                    <th rowspan="2" class="align-middle text-center">Uraian</th>
                    <th rowspan="2" class="align-middle text-center">Pagu Anggaran</th>
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
                    $no=1;
                    $total_pagu = 0;
                    $total_realisasi_ls = 0;
                    $total_realisasi_not_ls = 0;
                    $total_sisa_anggaran = 0;
                    foreach($uraians as $uraian):

                ?>
                <tr>
                    <td class="text-center"><?= $no ?></td>
                    <td><?= $uraian->kode ?></td>
                    <td class="text-nowrap"><?= $uraian->nama ?></td>
                    <td class="text-right">
                        <?php  
                            $pagu = @$this->bukujaga->getPagu(['fid_uraian' => $uraian->id])->total_pagu_awal;
                            $total_pagu += $pagu;
                            echo nominal($pagu);
                        ?>
                    </td>
                    <td class="text-right">
                        <?php 
                            $realisasi_ls = @$this->bukujaga->getPaguRealisasiLs(['fid_uraian' => $uraian->id, 'is_realisasi' => 'LS'])->jumlah;
                            $total_realisasi_ls += $realisasi_ls;
                            echo nominal($realisasi_ls);
                        ?>
                    </td>
                    <td class="text-right">
                    <?php 
                            $realisasi_not_ls = @$this->bukujaga->getPaguRealisasiLs(['fid_uraian' => $uraian->id, 'is_realisasi !=' => 'LS'])->jumlah;
                            $total_realisasi_not_ls += $realisasi_not_ls;
                            echo nominal($realisasi_not_ls);
                        ?>
                    </td>
                    <td class="text-right">
                        <?php  
                            $realisasi = $realisasi_ls != 0 ? $realisasi_ls : $realisasi_not_ls;
                            $sisa_anggaran = ($pagu-$realisasi);
                            $total_sisa_anggaran += $sisa_anggaran;
                            echo nominal($sisa_anggaran);
                        ?>
                    </td>
                </tr>
                <?php $no++; endforeach; ?>
                <tr>
                    <td colspan="3" class="text-right"><b>Total</b></td>
                    <td class="text-right"><b><?= nominal($total_pagu) ?></b></td>
                    <td class="text-right"><b><?= nominal($total_realisasi_ls) ?></b></td>
                    <td class="text-right"><b><?= nominal($total_realisasi_not_ls) ?></b></td>
                    <td class="text-right"><b><?= nominal($total_sisa_anggaran) ?></b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="footer">
        <p>Copyright <?= date('Y') ?> ::: SIMEV (<?= getSetting('version_app') ?>) <span class="page-number"></span></p>
    </div>
</body>

</html>