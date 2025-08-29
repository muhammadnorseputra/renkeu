<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <title><?= $title ?></title>
    <style media="print">
        @page {
            size: landscape;
            margin: 1cm;
        }

        .page-break-after-this {
            page-break-after: always;
        }

        body {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            font-size: 0.8em;
        }

        #header,
        #footer {
            position: static;
            left: 0;
            right: 0;
            color: #333;
            font-size: 0.8em;
        }

        #header {
            top: 0;
            border-bottom: 0.1pt solid #aaa;
        }

        #footer {
            bottom: 0;
            border-top: 0.1pt solid #aaa;
        }

        #content {
            margin-top: 1.5cm;
            margin-bottom: 1.5cm;
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
            font-size: 1em;
        }

        tbody {
            background-color: #fff;
        }

        th,
        td {
            padding: 8pt;
            border: 1pt solid #aaa;
        }

        table.collapse {
            border-collapse: collapse;
            border: 1pt solid #aaa;
        }

        table.collapse td {
            border: 1pt solid #aaa;
        }

        /* Hindari pemisahan baris tabel yang buruk */
        tbody tr {
            page-break-inside: avoid;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body onload="window.print()">

    <div id="content">
        <table class="collapse">
            <tr>
                <td colspan="5" class="text-center">
                    <h2 style="margin:0; padding:0">PEMERINTAH KAB. BALANGAN</h2>
                    <h3 style="margin:0; padding:0"> BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA</h3>
                    <h4 style="margin:0; padding:0"><?= strtoupper($title) ?> </h4>
                    <small><i>Dicetak : <?= longdate_indo(date('Y-m-d')) ?></i></small>
                </td>
            </tr>
            <tr>
                <td width="15%">Bidang/Bagian</td>
                <td colspan="3"><?= $this->target->getNama('ref_parts', $this->session->userdata('part')) ?></td>
                <td width="10%" class="text-center font-bold"><b><?= $tw_nama ?></b></td>
            </tr>
        </table>
        <table class="collapse">
            <thead>
                <tr class="text-center">
                    <th width="5%" rowspan="2" class="align-middle">No</th>
                    <th rowspan="2" class="align-middle sticky-col">Program/Kegiatan/Sub Kegiatan</th>
                    <th rowspan="2" width="60%" class="align-middle">Indikator Kinerja</th>
                    <th colspan="2">Realisasi</th>
                </tr>
                <tr class="text-center">
                    <th width="10%">Anggaran (Rp)</th>
                    <th width="10%">Kinerja</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_level_1 = 1;
                foreach ($programs->result() as $program) :
                    $indikator_program = $this->realisasi->getIndikator(['fid_program' => $program->id]);
                    $tr = "";
                    if ($indikator_program->num_rows() > 0) :
                        $indikator = $indikator_program->result_array();
                        $toEnd = count($indikator);
                        foreach ($indikator as $key => $ip) :
                            $isStatusVerifikasi = $this->realisasi->isStatusVerifikasi($tw_id, $ip['indikator_id']);
                            $realisasi = $this->realisasi->getRealisasiByIndikatorId($tw_id, null, $ip['indikator_id'], $tahun_anggaran)->row();
                            if ($realisasi->persentase === "0" && $realisasi->eviden_jenis !== "-" && $realisasi->status === 'SETUJU') {
                                $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                            } elseif ($realisasi->eviden === "0" && $realisasi->eviden_jenis === "-" && $realisasi->status === 'SETUJU') {
                                $sum_realisasi = $realisasi->persentase . "%";
                            } else {
                                $sum_realisasi = $isStatusVerifikasi;
                            }

                            $rowspan = $toEnd++;
                            if (0 === --$toEnd) { //last
                                $tr .= "";
                            } elseif ($key === 0) { //first
                                $tr .= "
                                        <td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiProgram($tw_id, null, $program->id)) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>";
                            } else { //middle
                                $tr .= "
                                    <tr style='background-color: orange;'>
                                        <td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                    </tr>";
                            }
                        endforeach;
                    else:
                        $tr .= "<td colspan='3' rowspan='" . $rowspan . "'></td><tr></tr>";
                    endif;
                ?>
                    <tr style='background-color: orange;'>
                        <td class="text-center align-middle" rowspan="<?= $toEnd ?>"><?= $no_level_1 ?></td>
                        <td class="align-middle" rowspan="<?= $toEnd ?>"><?= $program->nama ?> </td>
                        <?= $tr ?>
                    </tr>
                    <?php
                    if ($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('user_name') === 'kaban') :
                        $kegiatans = $this->realisasi->kegiatans($program->id);
                    else :
                        $kegiatans = $this->realisasi->kegiatans($program->id, $this->session->userdata('part'));
                    endif;

                    $no_level_2 = 1;
                    foreach ($kegiatans->result() as $kegiatan) :
                        $indikator_kegiatan = $this->realisasi->getIndikator(['fid_kegiatan' => $kegiatan->id]);
                        $tr = "";
                        if ($indikator_kegiatan->num_rows() > 0) :
                            $indikator_keg = $indikator_kegiatan->result_array();
                            $toEnd = count($indikator_keg);
                            foreach ($indikator_keg as $key => $ik) :
                                $isStatusVerifikasi = $this->realisasi->isStatusVerifikasi($tw_id, $ik['indikator_id']);
                                $realisasi = $this->realisasi->getRealisasiByIndikatorId($tw_id, null, $ik['indikator_id'], $tahun_anggaran)->row();
                                if ($realisasi->persentase === "0" && $realisasi->eviden_jenis !== "-" && $realisasi->status === 'SETUJU') {
                                    $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                } elseif ($realisasi->eviden === "0" && $realisasi->eviden_jenis === "-" && $realisasi->status === 'SETUJU') {
                                    $sum_realisasi = $realisasi->persentase . "%";
                                } else {
                                    $sum_realisasi = $isStatusVerifikasi;
                                }
                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "";
                                } elseif ($key === 0) { //first
                                    $tr .= "
                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiKegiatan($tw_id, null, $kegiatan->id)) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>";
                                } else { //middle
                                    $tr .= "
                                    <tr style='background-color: blue; color: white'>
                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                    </tr>";
                                }
                            endforeach;
                        else:
                            $tr .= "<td colspan='3' rowspan='" . $rowspan . "'></td><tr></tr>";
                        endif;
                    ?>
                        <tr style='background-color: blue; color: white'>
                            <td class="text-center align-middle" rowspan="<?= $toEnd ?>"><?= $no_level_1 . "." . $no_level_2 ?>
                            </td>
                            <td class="align-middle" rowspan="<?= $toEnd ?>"><?= $kegiatan->nama ?></td>
                            <?= $tr ?>
                        </tr>
                        <?php
                        $sub_kegiatans = $this->realisasi->sub_kegiatans($kegiatan->id);
                        $no_level_3 = 1;
                        foreach ($sub_kegiatans->result() as $sub_kegiatan) :
                            $indikator_sub_kegiatan = $this->realisasi->getIndikator(['fid_sub_kegiatan' => $sub_kegiatan->id]);
                            $tr = "";
                            if ($indikator_sub_kegiatan->num_rows() > 0) :
                                $indikator_sub = $indikator_sub_kegiatan->result_array();
                                $toEnd = count($indikator_sub);
                                foreach ($indikator_sub as $key => $isk) :
                                    $isStatusVerifikasi = $this->realisasi->isStatusVerifikasi($tw_id, $isk['indikator_id']);
                                    $realisasi = $this->realisasi->getRealisasiByIndikatorId($tw_id, null, $isk['indikator_id'], $tahun_anggaran)->row();
                                    if ($realisasi->persentase === "0" && $realisasi->eviden_jenis !== "-" && $realisasi->status === 'SETUJU') {
                                        $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                    } elseif ($realisasi->eviden === "0" && $realisasi->eviden_jenis === "-" && $realisasi->status === 'SETUJU') {
                                        $sum_realisasi = $realisasi->persentase . "%";
                                    } else {
                                        $sum_realisasi = $isStatusVerifikasi;
                                    }

                                    $rowspan = $toEnd++;
                                    if (0 === --$toEnd) { //last
                                        $tr .= "";
                                    } elseif ($key === 0) { //first
                                        $tr .= "
                                        <td class='align-middle'>" . $isk['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiSubKegiatan($tw_id, null, $sub_kegiatan->id)) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>";
                                    } else { //middle
                                        $tr .= "
                                    <tr>
                                        <td class='align-middle'>" . $isk['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                    </tr>";
                                    }
                                endforeach;
                            else:
                                $tr .= "<td colspan='3' rowspan='" . $rowspan . "'></td><tr></tr>";
                            endif;
                        ?>
                            <tr>
                                <td class="text-center align-middle" rowspan="<?= $toEnd ?>">
                                    <?= $no_level_1 . "." . $no_level_2 . "." . $no_level_3 ?></td>
                                <td class="align-middle" rowspan="<?= $toEnd ?>"><?= $sub_kegiatan->nama ?></td>
                                <?= $tr ?>
                            </tr>

                        <?php
                            $no_level_3++;
                        endforeach;
                        ?>
                    <?php
                        $no_level_2++;
                    endforeach;
                    ?>
                <?php
                    $no_level_1++;
                endforeach;
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>