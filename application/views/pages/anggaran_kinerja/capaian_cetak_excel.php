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

        .bg-warning {
            background-color: orange;
        }

        .bg-info {
            background-color: blue;
        }

        .text-white {
            color: #fff;
        }
    </style>
</head>

<body>
    <div id="content">
        <table class="collapse">
            <tr>
                <td colspan="12" class="text-center">
                    <h2 style="margin:0; padding:0">PEMERINTAH KAB. BALANGAN</h2>
                    <h3 style="margin:0; padding:0"> BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA</h3>
                    <h4 style="margin:0; padding:0"><?= strtoupper($title) ?> </h4>
                    <small><i>Dicetak : <?= longdate_indo(date('Y-m-d')) ?></i></small>
                </td>
            </tr>
            <tr>
                <td width="15%">Bidang/Bagian</td>
                <td colspan="10"><?= $this->target->getNama('ref_parts', $this->session->userdata('part')) ?></td>
                <td width="10%" class="text-center font-bold"><b><?= $tw_nama ?></b></td>
            </tr>
        </table>
        <table class="collapse">
            <thead>
                <tr class="text-center">
                    <th rowspan="2" class="align-middle">No</th>
                    <th rowspan="2" class="sticky-col">Program/Kegiatan/Sub Kegiatan</th>
                    <th rowspan="2" class="align-middle">Indikator Kinerja</th>
                    <th colspan="2" class="align-middle">Target</th>
                    <th colspan="2" class="align-middle">Realisasi</th>
                    <th colspan="2">Persentase Capaian %</th>
                    <th colspan="3" class="align-middle">Faktor - Faktor</th>
                </tr>
                <tr class="text-center">
                    <th>Anggaran (Rp)</th>
                    <th>Kinerja %</th>
                    <th>Anggaran (Rp)</th>
                    <th>Kinerja %</th>
                    <th class="align-middle">Anggaran</th>
                    <th class="align-middle">Kinerja</th>
                    <th class="align-middle">Faktor Pendorong</th>
                    <th class="align-middle">Faktor Penghambat</th>
                    <th class="align-middle">Tindak Lanjut</th>
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

                            $target_anggaran = $this->target->getAlokasiPaguProgram($program->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
                            $target_kinerja = $indikator_input_count;

                            // Realisasi
                            $realisasi = $this->realisasi->getRealisasiByIndikatorId($tw_id, $ip['indikator_id'])->row();
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

                            $realisasi_anggaran = $this->realisasi->getRealisasiProgram($tw_id, $program->id);
                            $realisasi_kinerja = $sum_realisasi_count;

                            // Capaian
                            @$capaian_anggaran = round(($realisasi_anggaran / $target_anggaran) * 100, 2);
                            @$capaian_kinerja = round(($realisasi_kinerja / $target_kinerja) * 100, 2);

                            // Aksi
                            $FaktorPendorong = $realisasi->faktor_pendorong === NULL ? '-' : $realisasi->faktor_pendorong;
                            $FaktorPenghambat = $realisasi->faktor_penghambat === NULL ? '-' : $realisasi->faktor_penghambat;
                            $TindakLanjut = $realisasi->tindak_lanjut === NULL ? '-' : $realisasi->tindak_lanjut;

                            $rowspan = $toEnd++;
                            if (0 === --$toEnd) { //last
                                $tr .= "";
                            } elseif ($key === 0) { //first
                                $tr .= "
                                        <td>" . $ip['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='text-center'>" . $indikator_input_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='text-right'>" . nominal($realisasi_anggaran) . "</td>
                                        <td class='text-center'>" . $sum_realisasi_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='text-center'>" . $capaian_anggaran . " (%)</td>
                                        <td class='text-center'>" . $capaian_kinerja . " (%)</td>
                                        <td>" . $FaktorPendorong . "</td>
                                        <td>" . $FaktorPenghambat . "</td>
                                        <td>" . $TindakLanjut . "</td>";
                            } else { //middle
                                $tr .= "
                                    <tr style='background-color: orange; color: black;'>
                                        <td>" . $ip['nama'] . "</td>
                                        <td class='text-center'>" . $indikator_input_view . "</td>
                                        <td class='text-center'>" . $sum_realisasi_view . "</td>
                                        <td class='text-center'>" . $capaian_anggaran . " (%)</td>
                                        <td>" . $FaktorPendorong . "</td>
                                        <td>" . $FaktorPenghambat . "</td>
                                        <td>" . $TindakLanjut . "</td>
                                    </tr>";
                            }
                        endforeach;
                    else:
                        $tr .= "
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>";
                    endif;
                ?>
                    <tr style='background-color: orange; color: black;'>
                        <td style="text-align: center;" rowspan="<?= $toEnd ?>"><?= $no_level_1 ?></td>
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
                                $target_anggaran = $this->target->getAlokasiPaguKegiatan($kegiatan->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
                                $target_kinerja = $indikator_input_count;

                                // Realisasi
                                $realisasi = $this->realisasi->getRealisasiByIndikatorId($tw_id, $ik['indikator_id'])->row();
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
                                $realisasi_anggaran = $this->realisasi->getRealisasiKegiatan($tw_id, $kegiatan->id);
                                $realisasi_kinerja = $sum_realisasi_count;

                                // Capaian
                                @$capaian_anggaran = round(($realisasi_anggaran / $target_anggaran) * 100, 2);
                                @$capaian_kinerja = round(($realisasi_kinerja / $target_kinerja) * 100, 2);

                                // Aksi
                                $FaktorPendorong = $realisasi->faktor_pendorong === NULL ? '-' : $realisasi->faktor_pendorong;
                                $FaktorPenghambat = $realisasi->faktor_penghambat === NULL ? '-' : $realisasi->faktor_penghambat;
                                $TindakLanjut = $realisasi->tindak_lanjut === NULL ? '-' : $realisasi->tindak_lanjut;

                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "";
                                } elseif ($key === 0) { //first
                                    $tr .= "
                                        <td>" . $ik['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='text-center'>" . $indikator_input_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='text-right'>" . nominal($realisasi_anggaran) . "</td>
                                        <td class='text-center'>" . $sum_realisasi_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='text-center'>" . $capaian_anggaran . " (%)</td>
                                        <td class='text-center'>" . $capaian_kinerja . " (%)</td>
                                        <td>" . $FaktorPendorong . "</td>
                                        <td>" . $FaktorPenghambat . "</td>
                                        <td>" . $TindakLanjut . "</td>";
                                } else { //middle
                                    $tr .= "
                                    <tr style='background-color: blue; color: white'>
                                        <td>" . $ik['nama'] . "</td>
                                        <td class='text-center'>" . $indikator_input_view . "</td>
                                        <td class='text-center'>" . $sum_realisasi_view . "</td>
                                        <td class='text-center'>" . $capaian_kinerja . " (%)</td>
                                        <td>" . $FaktorPendorong . "</td>
                                        <td>" . $FaktorPenghambat . "</td>
                                        <td>" . $TindakLanjut . "</td>
                                    </tr>";
                                }
                            endforeach;
                        else:
                            $tr .= "
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>";
                        endif;
                    ?>
                        <tr style='background-color: blue; color: white'>
                            <td style="text-align: center;" rowspan="<?= $toEnd ?>"><?= $no_level_1 . "." . $no_level_2 ?></td>
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

                                    $target_anggaran = $this->target->getAlokasiPaguSubKegiatan($sub_kegiatan->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
                                    $target_kinerja = $indikator_input_count;

                                    // Realisasi
                                    $realisasi = $this->realisasi->getRealisasiByIndikatorId($tw_id, $isk['indikator_id'])->row();
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

                                    $realisasi_anggaran = $this->realisasi->getRealisasiSubKegiatan($tw_id, $sub_kegiatan->id);
                                    $realisasi_kinerja = $sum_realisasi_count;

                                    // Capaian
                                    @$capaian_anggaran = round(($realisasi_anggaran / $target_anggaran) * 100, 2);
                                    @$capaian_kinerja = round(($realisasi_kinerja / $target_kinerja) * 100, 2);

                                    // Aksi
                                    $FaktorPendorong = $realisasi->faktor_pendorong === NULL ? '-' : $realisasi->faktor_pendorong;
                                    $FaktorPenghambat = $realisasi->faktor_penghambat === NULL ? '-' : $realisasi->faktor_penghambat;
                                    $TindakLanjut = $realisasi->tindak_lanjut === NULL ? '-' : $realisasi->tindak_lanjut;

                                    $rowspan = $toEnd++;
                                    if (0 === --$toEnd) { //last
                                        $tr .= "";
                                    } elseif ($key === 0) { //first
                                        $tr .= "
                                        <td>" . $isk['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='text-center'>" . $indikator_input_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='text-right'>" . nominal($realisasi_anggaran) . "</td>
                                        <td class='text-center'>" . $sum_realisasi_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='text-center'>" . $capaian_anggaran . " (%)</td>
                                        <td class='text-center'>" . $capaian_kinerja . " (%)</td>
                                        <td>" . $FaktorPendorong . "</td>
                                        <td>" . $FaktorPenghambat . "</td>
                                        <td>" . $TindakLanjut . "</td>";
                                    } else { //middle
                                        $tr .= "
                                    <tr>
                                        <td>" . $isk['nama'] . "</td>
                                        <td class='text-center'>" . $indikator_input_view . "</td>
                                        <td class='text-center'>" . $sum_realisasi_view . "</td>
                                        <td class='text-center'>" . $capaian_kinerja . " (%)</td>
                                        <td>" . $FaktorPendorong . "</td>
                                        <td>" . $FaktorPenghambat . "</td>
                                        <td>" . $TindakLanjut . "</td>
                                    </tr>";
                                    }
                                endforeach;
                            else:
                                $tr .= "
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>
                                <td rowspan='" . $rowspan . "'></td>";
                            endif;

                        ?>
                            <tr>
                                <td style="text-align: center;" rowspan="<?= $toEnd ?>"><?= $no_level_1 . "." . $no_level_2 . "." . $no_level_3 ?></td>
                                <td class="align-middle" rowspan="<?= $toEnd ?>"><?= $sub_kegiatan->nama ?> </td>
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