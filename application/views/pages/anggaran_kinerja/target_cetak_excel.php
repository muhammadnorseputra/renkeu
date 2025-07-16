<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=" . $title . ".xls");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <title><?= $title ?></title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            margin: 10px auto;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #3c3c3c;
            padding: 3px 8px;
            vertical-align: middle;
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
                    <th rowspan="2">Program/Kegiatan/Sub Kegiatan</th>
                    <th rowspan="2" width="60%">Indikator Kinerja</th>
                    <th colspan="2">Target</th>
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
                    $indikator_program = $this->target->getIndikator(['i.fid_program' => $program->id]);
                    $tr = "";
                    if ($indikator_program->num_rows() > 0):
                        $indikator = $indikator_program->result_array();
                        $toEnd = count($indikator);
                        foreach ($indikator as $key => $ip) :
                            if ($ip['persentase'] === "0") {
                                $indikator_input = $ip['eviden_jumlah'] . " " . $ip['eviden_jenis'];
                            } else {
                                $indikator_input = $ip['persentase'] . "%";
                            }
                            $rowspan = $toEnd++;
                            if ($key === --$toEnd) { //last
                                $tr .= "";
                            } elseif ($key === 0) { //first
                                $tr .= "
                                <td>" . $ip['nama'] . "</td>
                                <td rowspan='" . $rowspan . "' class=' text-right'>" . @nominal($this->target->getAlokasiPaguProgram($program->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal) . "</td>
                                <td class='text-center'>" . $indikator_input . "</td>
                            ";
                            } else { //middle
                                $tr .= "<tr style='background-color: orange;'>
                                        <td>" . $ip['nama'] . "</td>
                                        <td class='text-center'>" . $indikator_input . "</td>
                                    </tr>
                            ";
                            }
                        endforeach;
                    else:
                        $tr .= "<td rowspan='" . $rowspan . "'></td>
                                        <td rowspan='" . $rowspan . "'></td>
                                        <td rowspan='" . $rowspan . "'></td>";
                    endif;
                ?>
                    <tr style='background-color: orange;'>
                        <td class="text-center" rowspan="<?= $toEnd ?>"><?= $no_level_1 ?></td>
                        <td class="" rowspan="<?= $toEnd ?>"><?= $program->nama ?></td>
                        <?= $tr ?>
                    </tr>
                    <?php
                    if ($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'SUPER_USER' || $this->session->userdata('role') === 'ADMIN') :
                        $kegiatans = $this->target->kegiatans($program->id);
                    else :
                        $kegiatans = $this->target->kegiatans($program->id, $this->session->userdata('part'));
                    endif;
                    $no_level_2 = 1;
                    foreach ($kegiatans->result() as $kegiatan) :
                        $indikator_kegiatan = $this->target->getIndikator(['i.fid_kegiatan' => $kegiatan->id]);
                        $tr = "";
                        if ($indikator_kegiatan->num_rows() > 0):
                            $indikator_keg = $indikator_kegiatan->result_array();
                            $toEnd = count($indikator_keg);
                            foreach ($indikator_keg as $key => $ik) :
                                if ($ik['persentase'] === "0") {
                                    $indikator_input = $ik['eviden_jumlah'] . " " . $ik['eviden_jenis'];
                                } else {
                                    $indikator_input = $ik['persentase'] . "%";
                                }
                                $rowspan = $toEnd++;
                                if ($key === --$toEnd) { //last
                                    $tr .= "";
                                } elseif ($key === 0) { //first
                                    $tr .= "
                                <td>" . $ik['nama'] . "</td>
                                <td rowspan='" . $rowspan . "' class=' text-right'>" . @nominal($this->target->getAlokasiPaguKegiatan($kegiatan->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal) . "</td>
                                <td class='text-center'>" . $indikator_input . "</td>";
                                } else { //middle
                                    $tr .= "<tr style='background-color: blue; color: white'>
                                <td>" . $ik['nama'] . "</td>
                                <td class='text-center'>" . $indikator_input . "</td></tr>";
                                }
                            endforeach;
                        else:
                            $tr .= "<td rowspan='" . $rowspan . "'></td>
                                        <td rowspan='" . $rowspan . "'></td>
                                        <td rowspan='" . $rowspan . "'></td>";
                        endif;
                    ?>
                        <tr style='background-color: blue; color: white'>
                            <td class="text-center" rowspan="<?= $toEnd ?>"><?= $no_level_1 . "." . $no_level_2 ?></td>
                            <td class="" rowspan="<?= $toEnd ?>"><?= $kegiatan->nama ?></td>
                            <?= $tr ?>
                        </tr>
                        <?php
                        $sub_kegiatans = $this->target->sub_kegiatans($kegiatan->id);
                        $no_level_3 = 1;
                        foreach ($sub_kegiatans->result() as $sub_kegiatan) :
                            $indikator_sub_kegiatan = $this->target->getIndikator(['i.fid_sub_kegiatan' => $sub_kegiatan->id]);
                            $tr = "";
                            if ($indikator_sub_kegiatan->num_rows() > 0):
                                $indikator = $indikator_sub_kegiatan->result_array();
                                $toEnd = count($indikator);
                                foreach ($indikator as $key => $isk) :
                                    if ($isk['persentase'] === "0") {
                                        $indikator_input = $isk['eviden_jumlah'] . " " . $isk['eviden_jenis'];
                                    } else {
                                        $indikator_input = $isk['persentase'] . "%";
                                    }
                                    $rowspan = $toEnd++;
                                    if (0 === --$toEnd) { //last
                                        $tr .= "";
                                    } elseif ($key === 0) { //first
                                        $tr .= "
                                        <td>" . $isk['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class=' text-right'>" . @nominal($this->target->getAlokasiPaguSubKegiatan($sub_kegiatan->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal) . "</td>
                                        <td class='text-center'>" . $indikator_input . "</td>";
                                    } else { //middle
                                        $tr .= "
                                    <tr>
                                        <td>" . $isk['nama'] . "</td>
                                        <td class='text-center'>" . $indikator_input . "</td>
                                    </tr>";
                                    }
                                endforeach;
                            else:
                                $tr .= "<td rowspan='" . $rowspan . "'></td>
                                        <td rowspan='" . $rowspan . "'></td>
                                        <td rowspan='" . $rowspan . "'></td>";
                            endif;
                        ?>
                            <tr>
                                <td class="text-center" rowspan="<?= $toEnd ?>"><?= $no_level_1 . "." . $no_level_2 . "." . $no_level_3 ?></td>
                                <td class="" rowspan="<?= $toEnd ?>"><?= $sub_kegiatan->nama ?></td>
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
    <div id="footer">
        <p>Copyright <?= date('Y') ?> ::: SIMEV (<?= getSetting('version_app') ?>)</p>
    </div>
</body>

</html>