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
        content: "Page "counter(page);
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
                <td colspan="2"><?= $this->target->getNama('ref_parts', $this->session->userdata('part')) ?></td>
                <td width="5%"><b>Tahun</b></td>
                <td width="6%" class="text-center font-bold"><b><?= $tahun ?></b></td>
            </tr>
        </table>
        <table class="collapse">
            <thead>
                <tr class="text-center">
                    <th rowspan="2" width="5%">No</th>
                    <th rowspan="2">Tujuan & Sasaran</th>
                    <th rowspan="2">Program/Kegiatan/Sub Kegiatan</th>
                    <th rowspan="2">Indikator Kinerja</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_level_0 = "#";
                $tujuan = $this->target->getTujuan(['t.tahun' => $this->session->userdata('tahun_anggaran')]);
                foreach ($tujuan->result() as $t) :
                    $indikator_tujuan = $this->target->getIndikator(['i.fid_tujuan' => $t->id, 'i.fid_periode' => 1, 'i.tahun' => $this->session->userdata('tahun_anggaran')], null);
                    $tr = "";
                    $rowspan = "";
                    if ($indikator_tujuan->num_rows() > 0):
                        $indikator = $indikator_tujuan->result_array();
                        $toEndTujuan = count($indikator);
                        foreach ($indikator as $key => $r) :

                            // Output Indikator Presentase
                            if ($r['is_jenis'] === "2") {
                                $indikator_input = $r['eviden_jumlah'] . " " . $r['eviden_jenis'];
                            } elseif ($r['is_jenis'] === "1") {
                                $indikator_input = $r['persentase'] . "%";
                            } else {
                                $indikator_input = "~";
                            }

                            // Rowspan untuk Indikator
                            $rowspan = $toEndTujuan++;
                            if (0 === --$toEndTujuan) { //last
                                $tr .= "";
                            } elseif ($key === 0) { //first
                                $tr .= "
                                            <td class='align-middle'>" . $r['nama'] . "</td>";
                            } else { //middle
                                $tr .= "
                                            <tr class='bg-warning'>
                                                <td class='align-middle'>" . $r['nama'] . "</td>
                                            </tr>";
                            }
                        endforeach;
                    else:
                        $tr .= "<td colspan='6' rowspan='" . $rowspan . "'></td>
                                <tr></tr>";
                    endif;
                ?>
                <tr bgcolor="orange" class="text-white">
                    <td class="text-center" rowspan="<?= @$toEndTujuan ?>"><?= $no_level_0++ ?></td>
                    <td class="text-left" colspan="2" rowspan="<?= @$toEndTujuan ?>"><?= $t->nama; ?>
                    </td>
                    <?= $tr; ?>
                </tr>
                <?php
                    $no_level_0_1 = "#1";
                    $sasaran = $this->target->getSasaran(['fid_tujuan' => $t->id, 't.tahun' => $this->session->userdata('tahun_anggaran')]);
                    foreach ($sasaran->result() as $s) :
                        $indikator_sasaran = $this->target->getIndikator(['i.fid_sasaran' => $s->id, 'i.fid_periode' => 1, 'i.tahun' => $this->session->userdata('tahun_anggaran')], null);
                        $tr = "";
                        $rowspan = "";
                        if ($indikator_sasaran->num_rows() > 0):
                            $indikator = $indikator_sasaran->result_array();
                            $toEndSasaran = count($indikator);
                            foreach ($indikator as $key => $rs) :

                                // Output Indikator Presentase
                                if ($rs['is_jenis'] === "2") {
                                    $indikator_input = $rs['eviden_jumlah'] . " " . $rs['eviden_jenis'];
                                } elseif ($rs['is_jenis'] === "1") {
                                    $indikator_input = $rs['persentase'] . "%";
                                } else {
                                    $indikator_input = "~";
                                }

                                // Rowspan untuk Indikator
                                $rowspan = $toEndSasaran++;
                                if (0 === --$toEndSasaran) { //last
                                    $tr .= "";
                                } elseif ($key === 0) { //first
                                    $tr .= "<td class='align-middle'>" . $rs['nama'] . "</td>";
                                } else { //middle
                                    $tr .= "
                                            <tr class='bg-success'>
                                                <td class='align-middle'>" . $rs['nama'] . "</td>
                                            </tr>";
                                }
                            endforeach;
                        else:
                            $tr .= "<td colspan='6' rowspan='" . $rowspan . "'></td>
                                        <tr></tr>";
                        endif;
                    ?>
                <tr style="background-color: #28a745; color: white;">
                    <td class="text-center" rowspan="<?= @$toEndSasaran ?>"><?= $no_level_0_1 ?></td>
                    <td class="text-left text-wrap" rowspan="<?= @$toEndSasaran ?>" colspan="2">
                        <?= $s->nama; ?></td>
                    <?= $s->nama; ?></td>
                    <?= $tr; ?>
                </tr>
                <?php
                        $no_level_1 = 1;
                        $programs = $this->target->program($s->id, $this->session->userdata('part'), $this->session->userdata('tahun_anggaran'));
                        foreach ($programs->result() as $program) :
                            $indikator_program = $this->target->getIndikator(['i.fid_program' => $program->id, 'i.fid_periode' => 1, 'i.tahun' => $this->session->userdata('tahun_anggaran')], null);
                            $tr = "";
                            $rowspan = "";
                            if ($indikator_program->num_rows() > 0):
                                $indikator = $indikator_program->result_array();
                                $toEndProgram = count($indikator);
                                foreach ($indikator as $key => $ip) :

                                    if ($ip['is_jenis'] === "2") {
                                        $indikator_input = $ip['eviden_jumlah'] . " " . $ip['eviden_jenis'];
                                    } elseif ($ip['is_jenis'] === "1") {
                                        $indikator_input = $ip['persentase'] . "%";
                                    } else {
                                        $indikator_input = "~";
                                    }

                                    $rowspan = $toEndProgram++;
                                    if (0 === --$toEndProgram) { //last
                                        $tr .= "";
                                    } elseif ($key === 0) { //first
                                        $tr .= "
                                                <td class='align-middle'>" . $ip['nama'] . "</td>";
                                    } else { //middle
                                        $tr .= "
                                                <tr class='bg-secondary text-white'>
                                                    <td class='align-middle'>" . $ip['nama'] . "</td>
                                                </tr>";
                                    }
                                endforeach;
                            else:
                                $tr .= "<td colspan='6' rowspan='" . $rowspan . "'></td>
                                        <tr></tr>";
                            endif;
                        ?>
                <tr style="background-color: gray; color: white;">
                    <td class="text-center align-middle" rowspan="<?= @$toEndProgram ?>"><?= $no_level_1 ?></td>
                    <td style="border: 0; background-color: #28a745;"></td>
                    <td class="align-middle" rowspan="<?= @$toEndProgram ?>"><?= $program->nama ?></td>
                    <?= $tr ?>
                </tr>
                <?php
                            if ($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('user_name') === 'kaban' || $this->session->userdata('role') === 'ADMIN') :
                                $kegiatans = $this->target->kegiatans($program->id);
                            else:
                                $kegiatans = $this->target->kegiatans($program->id, $this->session->userdata('part'));
                            endif;

                            $no_level_2 = 1;
                            foreach ($kegiatans->result() as $kegiatan) :
                                $indikator_kegiatan = $this->target->getIndikator(['i.fid_kegiatan' => $kegiatan->id, 'i.fid_periode' => 1, 'i.tahun' => $this->session->userdata('tahun_anggaran')], null);
                                $tr = "";
                                $rowspan = "";
                                if ($indikator_kegiatan->num_rows() > 0):
                                    $indikator = $indikator_kegiatan->result_array();
                                    $toEndKegiatan = count($indikator);
                                    foreach ($indikator as $key => $ik) :

                                        if ($ik['is_jenis'] === "2") {
                                            $indikator_input = $ik['eviden_jumlah'] . " " . $ik['eviden_jenis'];
                                        } elseif ($ik['is_jenis'] === "1") {
                                            $indikator_input = $ik['persentase'] . "%";
                                        } else {
                                            $indikator_input = "~";
                                        }

                                        $rowspan = $toEndKegiatan++;
                                        if (0 === --$toEndKegiatan) { //last
                                            $tr .= "";
                                        } elseif ($key === 0) { //first
                                            $tr .= "
                                                        <td class='align-middle'>" . $ik['nama'] . "</td>";
                                        } else { //middle
                                            $tr .= "
                                                        <tr class='bg-info text-white'>
                                                            <td class='align-middle'>" . $ik['nama'] . "</td>
                                                        </tr>";
                                        }
                                    endforeach;
                                else:
                                    $tr .= "<td colspan='6' rowspan='" . $rowspan . "'></td>
                                                <tr></tr>";
                                endif;
                            ?>
                <tr style="background-color: blue; color: white;">
                    <td class="text-center align-middle" rowspan="<?= @$toEndKegiatan ?>">
                        <?= $no_level_1 . "." . $no_level_2 ?>
                    </td>
                    <td style="border: 0; background-color: #28a745;"></td>
                    <td class="align-middle" rowspan="<?= @$toEndKegiatan ?>"><?= $kegiatan->nama ?></td>
                    <?= $tr ?>
                </tr>
                <?php
                                $sub_kegiatans = $this->target->sub_kegiatans($kegiatan->id);
                                $no_level_3 = 1;
                                foreach ($sub_kegiatans->result() as $sub_kegiatan) :
                                    $indikator_sub_kegiatan = $this->target->getIndikator(['i.fid_sub_kegiatan' => $sub_kegiatan->id, 'i.fid_periode' => 1, 'i.tahun' => $this->session->userdata('tahun_anggaran')], null);
                                    $tr = "";
                                    $rowspan = "";
                                    if ($indikator_sub_kegiatan->num_rows() > 0):
                                        $indikator = $indikator_sub_kegiatan->result_array();
                                        $toEndSubKegiatan = count($indikator);
                                        foreach ($indikator as $key => $isk) :

                                            if ($isk['is_jenis'] === "2") {
                                                $indikator_input = $isk['eviden_jumlah'] . " " . $isk['eviden_jenis'];
                                            } elseif ($isk['is_jenis'] === "1") {
                                                $indikator_input = $isk['persentase'] . "%";
                                            } else {
                                                $indikator_input = "~";
                                            }

                                            $rowspan = $toEndSubKegiatan++;
                                            if (0 === --$toEndSubKegiatan) { //last
                                                $tr .= "";
                                            } elseif ($key === 0) { //first
                                                $tr .= "
                                        <td class='align-middle'>" . $isk['nama'] . " <i class='" . $isk['color'] . "'>(" . $isk['jenis_indikator'] . ")</i></td>";
                                            } else { //middle
                                                $tr .= "
                                    <tr>
                                        <td class='align-middle'>" . $isk['nama'] . " <i class='" . $isk['color'] . "'>(" . $isk['jenis_indikator'] . ")</i></td>
                                    </tr>";
                                            }
                                        endforeach;
                                    else:
                                        $tr .= "<td colspan='6' rowspan='" . $rowspan . "'></td>
                                        <tr></tr>";
                                    endif;
                                ?>
                <tr>
                    <td class="text-center align-middle" rowspan="<?= @$toEndSubKegiatan ?>">
                        <?= $no_level_1 . "." . $no_level_2 . "." . $no_level_3 ?></td>
                    <td style="border: 0; background-color: #28a745;"></td>
                    <td class="align-middle" rowspan="<?= @$toEndSubKegiatan ?>"><?= $sub_kegiatan->nama ?></td>
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
                <?php
                        $no_level_0_1++;
                    endforeach;
                    ?>
                <?php
                    $no_level_0++;
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>