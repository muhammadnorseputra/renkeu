<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <title><?= $title ?></title>
    <style>
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
                <td colspan="7" class="text-center">
                    <h2 style="margin:0; padding:0">PEMERINTAH KAB. BALANGAN</h2>
                    <h3 style="margin:0; padding:0"> BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA</h3>
                    <h4 style="margin:0; padding:0"><?= strtoupper($title) ?> </h4>
                    <small><i>Dicetak : <?= longdate_indo(date('Y-m-d')) ?></i></small>
                </td>
            </tr>
            <tr>
                <td width="10%">Bidang/Bagian</td>
                <td colspan="3"><?= $this->target->getNama('ref_parts', $this->session->userdata('part')) ?></td>
                <td width="5%" class="text-center font-bold"><b><?= $tahun ?></b></td>
            </tr>
        </table>
        <table class="collapse">
            <thead>
                <tr class="text-center">
                    <th rowspan="2" class="align-middle" width="2%">No</th>
                    <th rowspan="2" class="align-middle">Program/Kegiatan/Sub Kegiatan</th>
                    <th rowspan="2" class="align-middle">Indikator Kinerja</th>
                    <th colspan="2" class="align-middle">Target</th>

                    <th colspan="2" class="align-middle">Januari</th>
                    <th colspan="2" class="align-middle">Februari</th>
                    <th colspan="2" class="align-middle">Maret</th>
                    <th colspan="2" class="align-middle">April</th>
                    <th colspan="2" class="align-middle">Mei</th>
                    <th colspan="2" class="align-middle">Juni</th>
                    <th colspan="2" class="align-middle">Juli</th>
                    <th colspan="2" class="align-middle">Agustus</th>
                    <th colspan="2" class="align-middle">September</th>
                    <th colspan="2" class="align-middle">Oktober</th>
                    <th colspan="2" class="align-middle">November</th>
                    <th colspan="2" class="align-middle">Desember</th>
                    <th rowspan="2">Penanggung Jawab</th>
                </tr>
                <tr>
                    <!-- Target Anggaran - Januari -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - Januari -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - Februari -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - Maret -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - April -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - Mei -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - Juni -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - Juli -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - Agustus -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - September -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - Oktober -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - November -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
                    <!-- Target Anggaran - Desember -->
                    <th class="align-middle text-center">A</th>
                    <th class="align-middle text-center">K</th>
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
                            // Target
                            if ($ip['persentase'] === "0") {
                                $indikator_input_count = $ip['eviden_jumlah'];
                                $indikator_input_view = $indikator_input_count . " " . $ip['eviden_jenis'];
                            } else {
                                $indikator_input_count = $ip['persentase'];
                                $indikator_input_view = $ip['persentase'] . "%";
                            }

                            // Target Anggaran
                            $target_anggaran = $this->target->getAlokasiPaguProgram($program->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
                            $target_kinerja = $indikator_input_count;

                            // Realisasi Anggaran & Kinerja Tahunan
                            $realisasi_anggaran = [];
                            $realisasi_kinerja = [];
                            for ($i = 1; $i <= 12; $i++) {
                                // Realisasi Kinerja
                                $realisasi = $this->realisasi->getRealisasiByIndikatorId("$i", $ip['indikator_id'])->row();
                                if ($realisasi->persentase === "0") {
                                    $realisasi_kinerja[$i] = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                } elseif ($realisasi->eviden === "0") {
                                    $realisasi_kinerja[$i] = $realisasi->persentase . "%";
                                } else {
                                    $realisasi_kinerja[$i] = "-";
                                }
                                // Realisasi Anggaran
                                $realisasi_anggaran[$i] = $this->realisasi->getRealisasiProgram("$i", $program->id);
                            }


                            $rowspan = $toEnd++;
                            if (0 === --$toEnd) { //last
                                $tr .= "";
                            } elseif ($key === 0) { //first
                                $tr .= "<td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                for ($i = 1; $i <= 12; $i++) {
                                    $tr .= "
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($realisasi_anggaran[$i]) . "</td>
                                        <td class='align-middle text-center'>" . $realisasi_kinerja[$i] . "</td>";
                                }
                                $tr .= "<td>" . $ip['part_name'] . "</td>";
                            } else { //middle
                                $tr .= "
                                    <tr style='background-color: orange;'>
                                        <td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                for ($i = 1; $i <= 12; $i++) {
                                    $tr .= "<td class='align-middle text-center'>" . $realisasi_kinerja[$i] . "</td>";
                                }
                                $tr .= "<td>" . $ip['part_name'] . "</td>";
                                $tr .= "</tr>";
                            }
                        endforeach;
                    else:
                        $tr .= "
                                <td rowspan='" . $rowspan . "' colspan='28'></td>
                                <tr></tr>";
                    endif;
                ?>
                    <tr style='background-color: orange;'>
                        <td class="text-center align-middle" rowspan="<?= @$toEnd ?>"><?= $no_level_1 ?></td>
                        <td class="align-middle" rowspan="<?= @$toEnd ?>"><?= $program->nama ?> </td>
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
                                // Target
                                if ($ik['persentase'] === "0") {
                                    $indikator_input_count = $ik['eviden_jumlah'];
                                    $indikator_input_view = $ik['eviden_jumlah'] . " " . $ik['eviden_jenis'];
                                } else {
                                    $indikator_input_count = $ik['persentase'];
                                    $indikator_input_view = $ik['persentase'] . "%";
                                }

                                $target_anggaran = $this->target->getAlokasiPaguKegiatan($kegiatan->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
                                $target_kinerja = $indikator_input_count;

                                // Realisasi Anggaran & Kinerja Tahunan
                                $realisasi_anggaran = [];
                                $realisasi_kinerja = [];
                                for ($i = 1; $i <= 12; $i++) {
                                    // Realisasi Kinerja
                                    $realisasi = $this->realisasi->getRealisasiByIndikatorId("$i", $ik['indikator_id'])->row();
                                    if ($realisasi->persentase === "0") {
                                        $realisasi_kinerja[$i] = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                    } elseif ($realisasi->eviden === "0") {
                                        $realisasi_kinerja[$i] = $realisasi->persentase . "%";
                                    } else {
                                        $realisasi_kinerja[$i] = "-";
                                    }
                                    // Realisasi Anggaran
                                    $realisasi_anggaran[$i] = $this->realisasi->getRealisasiKegiatan("$i", $kegiatan->id);
                                }



                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "";
                                } elseif ($key === 0) { //first
                                    $tr .= "
                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                    for ($i = 1; $i <= 12; $i++) {
                                        $tr .= "
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($realisasi_anggaran[$i]) . "</td>
                                        <td class='align-middle text-center'>" . $realisasi_kinerja[$i] . "</td>";
                                    }
                                    $tr .= "<td>" . $ik['part_name'] . "</td>";
                                } else { //middle
                                    $tr .= "
                                    <tr  style='background-color: blue; color: white;'>
                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                    for ($i = 1; $i <= 12; $i++) {
                                        $tr .= "
                                                <td class='align-middle text-center'>" . $realisasi_kinerja[$i] . "</td>
                                                            ";
                                    }
                                    $tr .= "<td>" . $ik['part_name'] . "</td>";
                                    $tr .= "</tr>";
                                }
                            endforeach;
                        else:
                            $tr .= "
                                <td rowspan='" . $rowspan . "' colspan='28'></td><tr></tr>";
                        endif;
                    ?>
                        <tr style='background-color: blue; color: white;'>
                            <td class="text-center align-middle" rowspan="<?= @$toEnd ?>"><?= $no_level_1 . "." . $no_level_2 ?></td>
                            <td class="align-middle" rowspan="<?= @$toEnd ?>"><?= $kegiatan->nama ?></td>
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
                                    // Target
                                    if ($isk['persentase'] === "0") {
                                        $indikator_input_count = $isk['eviden_jumlah'];
                                        $indikator_input_view = $indikator_input_count . " " . $isk['eviden_jenis'];
                                    } else {
                                        $indikator_input_count = $isk['persentase'];
                                        $indikator_input_view = $isk['persentase'] . "%";
                                    }

                                    $target_anggaran = $this->target->getAlokasiPaguSubKegiatan($sub_kegiatan->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
                                    $target_kinerja = $indikator_input_count;

                                    // Realisasi Anggaran & Kinerja Tahunan
                                    $realisasi_anggaran = [];
                                    $realisasi_kinerja = [];
                                    for ($i = 1; $i <= 12; $i++) {
                                        // Realisasi Kinerja
                                        $realisasi = $this->realisasi->getRealisasiByIndikatorId("$i", $isk['indikator_id'])->row();
                                        if ($realisasi->persentase === "0") {
                                            $realisasi_kinerja[$i] = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                        } elseif ($realisasi->eviden === "0") {
                                            $realisasi_kinerja[$i] = $realisasi->persentase . "%";
                                        } else {
                                            $realisasi_kinerja[$i] = "-";
                                        }
                                        // Realisasi Anggaran
                                        $realisasi_anggaran[$i] = $this->realisasi->getRealisasiSubKegiatan("$i", $sub_kegiatan->id);
                                    }

                                    $rowspan = $toEnd++;
                                    if (0 === --$toEnd) { //last
                                        $tr .= "";
                                    } elseif ($key === 0) { //first
                                        $tr .= "
                                        <td class='align-middle'>" . $isk['nama'] . " <i class='" . $isk['color'] . "'>(" . $isk['jenis_indikator'] . ")</i></td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                        for ($i = 1; $i <= 12; $i++) {
                                            $tr .= "
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($realisasi_anggaran[$i]) . "</td>
                                        <td class='align-middle text-center'>" . $realisasi_kinerja[$i] . "</td>";
                                        }
                                        $tr .= "<td>" . $isk['part_name'] . "</td>";
                                    } else { //middle
                                        $tr .= "
                                    <tr>
                                        <td class='align-middle'>" . $isk['nama'] . " <i class='" . $isk['color'] . "'>(" . $isk['jenis_indikator'] . ")</i></td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                        for ($i = 1; $i <= 12; $i++) {
                                            $tr .= "
                                        <td class='align-middle text-center'>" . $realisasi_kinerja[$i] . "</td>";
                                        }
                                        $tr .= "<td>" . $isk['part_name'] . "</td>";
                                        $tr .= "</tr>";
                                    }
                                endforeach;
                            else:
                                $tr .= "
                                <td rowspan='" . $rowspan . "' colspan='28'></td><tr></tr>";
                            endif;
                        ?>
                            <tr>
                                <td class="text-center align-middle" rowspan="<?= @$toEnd ?>"><?= $no_level_1 . "." . $no_level_2 . "." . $no_level_3 ?></td>
                                <td class="align-middle" rowspan="<?= @$toEnd ?>"><?= $sub_kegiatan->nama ?></td>
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