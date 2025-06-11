<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <title><?= $title ?></title>
    <style>
        @page {
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
            font-size: 0.9em;
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
        <p><?= $title ?> <span class="author">Dicetak oleh : <?= $this->user->profile_username($this->session->userdata('user_name'))->row()->nama ?></span></p>
    </div>
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
                <td width="10%" class="text-center font-bold"><b><?= $tahun ?></b></td>
            </tr>
        </table>
        <table class="collapse" id="indikator">
            <thead>
                <tr class="text-center">
                    <th rowspan="2" class="align-middle" width="2%">No</th>
                    <th rowspan="2" class="align-middle sticky-col" width="10%">Program/Kegiatan/Sub Kegiatan</th>
                    <th rowspan="2" class="align-middle">Indikator Kinerja</th>
                    <th colspan="2" class="align-middle">Target</th>
                    <?php
                    foreach ($this->spj->getPeriodeAktif()->result() as $periode) :
                    ?>
                        <th colspan="2" class="align-middle" width="10%"><?= $periode->nama ?></th>
                    <?php endforeach; ?>
                    <th colspan="2">Persentase Capaian %</th>
                </tr>
                <tr class="text-center">
                    <th width="3%">A (Rp)</th>
                    <th width="3%">K %</th>
                    <?php
                    foreach ($this->spj->getPeriodeAktif()->result() as $periode) :
                    ?>
                        <th width="2%">A (Rp)</th>
                        <th width="2%">K %</th>
                    <?php endforeach; ?>
                    <th width="5%" class="align-middle">A (Rp)</th>
                    <th width="5%" class="align-middle">K %</th>
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

                            $target_anggaran = $this->target->getAlokasiPaguProgram($program->id, $this->session->userdata('is_perubahan'))->row()->total_pagu_awal;
                            $target_kinerja = $indikator_input_count;

                            $rowspan = $toEnd++;
                            // Looping triwulan
                            $tr2 = "";
                            foreach ($this->spj->getPeriodeAktif()->result() as $periode) :
                                // Realisasi
                                $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode->id, $ip['indikator_id'])->row();
                                if ($realisasi->persentase === "0") {
                                    $sum_realisasi_count = $realisasi->eviden;
                                    $sum_realisasi_view = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                } elseif ($realisasi->eviden === "0") {
                                    $sum_realisasi_count = $realisasi->persentase;
                                    $sum_realisasi_view = $realisasi->persentase . "%";
                                } else {
                                    $sum_realisasi_count = 0;
                                    $sum_realisasi_view = "-";
                                }

                                $realisasi_anggaran = $this->realisasi->getRealisasiProgram($periode->id, $program->id);
                                $realisasi_kinerja = $sum_realisasi_count;
                            endforeach;

                            // Capaian
                            @$capaian_anggaran = round(($realisasi_anggaran / $target_anggaran) * 100, 2);
                            @$capaian_kinerja = round(($realisasi_kinerja / $target_kinerja) * 100, 2);

                            if (0 === --$toEnd) { //last
                                $tr .= "";
                            } elseif ($key === 0) { //first
                                $tr .= "
                                        <td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($realisasi_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . $capaian_anggaran . " (%)</td>
                                        <td class='align-middle text-center'>" . $capaian_kinerja . " (%)</td>";
                            } else { //middle
                                $tr .= "
                                    <tr class='bg-warning'>
                                        <td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                    </tr>";
                            }
                        endforeach;
                    endif;
                ?>
                    <tr class="bg-warning">
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
                                // Target
                                if ($ik['persentase'] === "0") {
                                    $indikator_input_count = $ik['eviden_jumlah'];
                                    $indikator_input_view = $ik['eviden_jumlah'] . " " . $ik['eviden_jenis'];
                                } else {
                                    $indikator_input_count = $ik['persentase'];
                                    $indikator_input_view = $ik['persentase'] . "%";
                                }
                                $target_anggaran = $this->target->getAlokasiPaguKegiatan($kegiatan->id, $this->session->userdata('is_perubahan'))->row()->total_pagu_awal;
                                $target_kinerja = $indikator_input_count;

                                // Realisasi
                                $realisasi = $this->realisasi->getRealisasiTahunanByIndikatorId($ik['indikator_id'])->row();
                                if ($realisasi->persentase === "0") {
                                    $sum_realisasi_count = $realisasi->eviden;
                                    $sum_realisasi_view = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                } elseif ($realisasi->eviden === "0") {
                                    $sum_realisasi_count = $realisasi->persentase;
                                    $sum_realisasi_view = $realisasi->persentase . "%";
                                } else {
                                    $sum_realisasi_count = 0;
                                    $sum_realisasi_view = "-";
                                }
                                $realisasi_anggaran = $this->realisasi->getRealisasiTahunanKegiatan($kegiatan->id);
                                $realisasi_kinerja = $sum_realisasi_count;

                                // Capaian
                                @$capaian_anggaran = round(($realisasi_anggaran / $target_anggaran) * 100, 2);
                                @$capaian_kinerja = round(($realisasi_kinerja / $target_kinerja) * 100, 2);


                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "";
                                } elseif ($key === 0) { //first
                                    $tr .= "
                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($realisasi_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . $capaian_anggaran . " (%)</td>
                                        <td class='align-middle text-center'>" . $capaian_kinerja . " (%)</td>";
                                } else { //middle
                                    $tr .= "
                                    <tr class='bg-info text-white'>
                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi_view . "</td>
                                        <td class='align-middle text-center'>" . $capaian_kinerja . " (%)</td>
                                    </tr>";
                                }
                            endforeach;
                        endif;
                    ?>
                        <tr class="bg-info text-white">
                            <td class="text-center align-middle" rowspan="<?= $toEnd ?>"><?= $no_level_1 . "." . $no_level_2 ?></td>
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
                                    // Target
                                    if ($isk['persentase'] === "0") {
                                        $indikator_input_count = $isk['eviden_jumlah'];
                                        $indikator_input_view = $indikator_input_count . " " . $isk['eviden_jenis'];
                                    } else {
                                        $indikator_input_count = $isk['persentase'];
                                        $indikator_input_view = $isk['persentase'] . "%";
                                    }

                                    $target_anggaran = $this->target->getAlokasiPaguSubKegiatan($sub_kegiatan->id, $this->session->userdata('is_perubahan'))->row()->total_pagu_awal;
                                    $target_kinerja = $indikator_input_count;

                                    $rowspan = $toEnd++;
                                    // Looping triwulan
                                    $realisasi_anggaran = 0;
                                    $realisasi_kinerja = 0;
                                    $td_sub = "";
                                    $td_sub_sub = "";
                                    foreach ($this->spj->getPeriodeAktif()->result() as $periode) :
                                        // Realisasi
                                        $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode->id, $isk['indikator_id'])->row();
                                        if ($realisasi->persentase === "0") {
                                            $sum_realisasi_count = $realisasi->eviden;
                                            $sum_realisasi_view = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                        } elseif ($realisasi->eviden === "0") {
                                            $sum_realisasi_count = $realisasi->persentase;
                                            $sum_realisasi_view = $realisasi->persentase . "%";
                                        } else {
                                            $sum_realisasi_count = 0;
                                            $sum_realisasi_view = "-";
                                        }

                                        $realisasi_anggaran = $this->realisasi->getRealisasiSubKegiatan($periode->id, $sub_kegiatan->id);
                                        $realisasi_kinerja += $sum_realisasi_count;
                                        $td_sub .= "<td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($realisasi_anggaran) . "</td>";
                                        $td_sub .= "<td class='align-middle text-center'>" . $sum_realisasi_view . "</td>";
                                    endforeach;
                                    // Capaian
                                    @$capaian_anggaran = round(($realisasi_anggaran / $target_anggaran) * 100, 2);
                                    @$capaian_kinerja = round(($realisasi_kinerja / $target_kinerja) * 100, 2);

                                    if (0 === --$toEnd) { //last
                                        $tr .= "";
                                    } elseif ($key === 0) { //first
                                        $tr .= "
                                        <td class='align-middle'>" . $isk['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                        $tr .= $td_sub;
                                        $tr .= "
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . $capaian_anggaran . " (%)</td>
                                        <td class='align-middle text-center'>" . $capaian_kinerja . " (%)</td>
                                        ";
                                    } else { //middle
                                        $tr .= "
                                    <tr>
                                        <td class='align-middle'>" . $isk['nama'] . "</td>";
                                        // $tr .= $td_sub;
                                        $tr .= "
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>
                                        <td class='align-middle text-center'>" . $capaian_kinerja . " (%)</td>
                                    </tr>";
                                    }
                                endforeach;
                            endif;
                        ?>
                            <tr>
                                <td class="text-center align-middle" rowspan="<?= $toEnd ?>"><?= $no_level_1 . "." . $no_level_2 . "." . $no_level_3 ?></td>
                                <td class="align-middle" rowspan="<?= $toEnd ?>"><?= $sub_kegiatan->nama ?></td>
                                <?= $tr ?>
                            </tr>
                            <?php
                            $row_all = $no_level_1 + $no_level_2 + $no_level_3;
                            if ($row_all % 5 == 0) :
                            ?>
                                <div style="page-break-before:always;"></div>
                            <?php endif; ?>
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
        <p>Copyright <?= date('Y') ?> ::: SIMEV (<?= getSetting('version_app') ?>) <span class="page-number"></span></p>
    </div>
</body>

</html>