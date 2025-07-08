<?php
$periode_id = isset($_GET['periode']) ? $_GET['periode'] : $this->spj->getLastPeriode()->row()->id;
?>
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-print mr-2"></i> Laporan Anggaran & Kinerja</h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li class="d-flex justify-content-center align-items-center mr-2 "><a href="<?= base_url('app/capaian/cetak/' . $periode_id) ?>" target="_blank" class="print-link text-primary"><i class="fa fa-print"></i> Cetak</a></li>
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr class="text-center">
                        <th rowspan="3" class="align-middle">No</th>
                        <th rowspan="3" class="align-middle">Tujuan & Sasaran</th>
                        <th rowspan="3" class="align-middle sticky-col">Program/Kegiatan/Sub Kegiatan</th>
                        <th rowspan="3" class="align-middle">Indikator Kinerja</th>
                        <th colspan="2" class="align-middle">Target Tahun <?= $this->session->userdata('tahun_anggaran'); ?></th>
                        <th colspan="24" class="align-middle">Realisasi</th>
                        <!-- <th colspan="3"> Faktor - Faktor </th> -->
                    </tr>
                    <tr class="text-center">
                        <!-- Target Anggaran -->
                        <th class="align-middle" rowspan="2">Anggaran (Rp)</th>
                        <th class="align-middle" rowspan="2">Kinerja %</th>

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

                        <!-- <th class="align-middle" width="10%" rowspan="2">Faktor Pendorong</th>
                        <th class="align-middle" width="10%" rowspan="2">Faktor Penghambat</th>
                        <th class="align-middle" width="10%" rowspan="2">Tindak Lanjut</th> -->

                    </tr>
                    <tr>
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
                    $no_level_0 = "#";
                    $tujuan = $this->target->getTujuan(['t.tahun' => $this->session->userdata('tahun_anggaran')]);
                    foreach ($tujuan->result() as $t) :
                        $indikator_tujuan = $this->realisasi->getIndikator(['i.fid_tujuan' => $t->id], $this->session->userdata('part'));
                        $tr = "";
                        if ($indikator_tujuan->num_rows() > 0):
                            $indikator = $indikator_tujuan->result_array();
                            $toEnd = count($indikator);
                            foreach ($indikator as $key => $r) :
                                // Target Kinerja
                                if ($r['persentase'] === "0") {
                                    $indikator_input_count = $r['eviden_jumlah'];
                                    $indikator_input_view = $indikator_input_count . " " . $r['eviden_jenis'];
                                } else {
                                    $indikator_input_count = $r['persentase'];
                                    $indikator_input_view = $r['persentase'] . "%";
                                }

                                // Target Anggaran
                                $target_anggaran = $this->target->getAlokasiPaguTujuan($t->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
                                $target_kinerja = $indikator_input_count;

                                // Realisasi Anggaran & Kinerja Tahunan
                                $realisasi_anggaran = [];
                                $realisasi_kinerja = [];
                                for ($i = 1; $i <= 12; $i++) {
                                    // Realisasi Kinerja Januari
                                    $realisasi = $this->realisasi->getRealisasiByIndikatorId("$i", $r['indikator_id'])->row();
                                    if ($realisasi->persentase === "0") {
                                        $realisasi_kinerja[$i] = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                    } elseif ($realisasi->eviden === "0") {
                                        $realisasi_kinerja[$i] = $realisasi->persentase . "%";
                                    } else {
                                        $realisasi_kinerja[$i] = "-";
                                    }
                                    // Realisasi Anggaran Januari
                                    $realisasi_anggaran[$i] = $this->realisasi->getRealisasiTujuan("$i", $t->id, $this->session->userdata('tahun_anggaran'));
                                }

                                // Aksi
                                // $FaktorPendorong = $realisasi->faktor_pendorong === NULL ? '-' : $realisasi->faktor_pendorong;
                                // $FaktorPenghambat = $realisasi->faktor_penghambat === NULL ? '-' : $realisasi->faktor_penghambat;
                                // $TindakLanjut = $realisasi->tindak_lanjut === NULL ? '-' : $realisasi->tindak_lanjut;

                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "";
                                } elseif ($key === 0) { //first
                                    $tr .= "
                                        <td class='align-middle'>" . $r['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                    for ($i = 1; $i <= 12; $i++) {
                                        $tr .= "
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($realisasi_anggaran[$i]) . "</td>
                                        <td class='align-middle text-center'>" . $realisasi_kinerja[$i] . "</td>";
                                    }
                                } else { //middle
                                    $tr .= "
                                    <tr class='bg-warning'>
                                        <td class='align-middle'>" . $r['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                    </tr>";
                                }
                            endforeach;
                        endif;
                    ?>
                        <tr class="bg-warning">
                            <td class="text-center align-middle" rowspan="<?= @$toEnd ?>"><?= $no_level_0 ?></td>
                            <td class="align-middle" colspan="2" rowspan="<?= @$toEnd ?>"><?= $t->nama ?> </td>
                            <?= $tr ?>
                        </tr>
                        <?php
                        $no_level_0_1 = "#1";
                        $sasaran = $this->target->getSasaran(['fid_tujuan' => $t->id, 't.tahun' => $this->session->userdata('tahun_anggaran')]);
                        foreach ($sasaran->result() as $s) :
                            $indikator_sasaran = $this->realisasi->getIndikator(['i.fid_sasaran' => $s->id], $this->session->userdata('part'));
                            $tr = "";
                            if ($indikator_sasaran->num_rows() > 0):
                                $indikator = $indikator_sasaran->result_array();
                                $toEnd = count($indikator);
                                foreach ($indikator as $key => $r) :
                                    // Target Kinerja
                                    if ($r['persentase'] === "0") {
                                        $indikator_input_count = $r['eviden_jumlah'];
                                        $indikator_input_view = $indikator_input_count . " " . $r['eviden_jenis'];
                                    } else {
                                        $indikator_input_count = $r['persentase'];
                                        $indikator_input_view = $r['persentase'] . "%";
                                    }

                                    // Target Anggaran
                                    $target_anggaran = $this->target->getAlokasiPaguSasaran($s->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
                                    $target_kinerja = $indikator_input_count;

                                    // Realisasi Anggaran & Kinerja Tahunan
                                    $realisasi_anggaran = [];
                                    $realisasi_kinerja = [];
                                    for ($i = 1; $i <= 12; $i++) {
                                        // Realisasi Kinerja Januari
                                        $realisasi = $this->realisasi->getRealisasiByIndikatorId("$i", $r['indikator_id'])->row();
                                        if ($realisasi->persentase === "0") {
                                            $realisasi_kinerja[$i] = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                        } elseif ($realisasi->eviden === "0") {
                                            $realisasi_kinerja[$i] = $realisasi->persentase . "%";
                                        } else {
                                            $realisasi_kinerja[$i] = "-";
                                        }
                                        // Realisasi Anggaran Januari
                                        $realisasi_anggaran[$i] = $this->realisasi->getRealisasiSasaran("$i", $t->id, $this->session->userdata('tahun_anggaran'));
                                    }

                                    // Aksi
                                    // $FaktorPendorong = $realisasi->faktor_pendorong === NULL ? '-' : $realisasi->faktor_pendorong;
                                    // $FaktorPenghambat = $realisasi->faktor_penghambat === NULL ? '-' : $realisasi->faktor_penghambat;
                                    // $TindakLanjut = $realisasi->tindak_lanjut === NULL ? '-' : $realisasi->tindak_lanjut;

                                    $rowspan = $toEnd++;
                                    if (0 === --$toEnd) { //last
                                        $tr .= "";
                                    } elseif ($key === 0) { //first
                                        $tr .= "
                                        <td class='align-middle'>" . $r['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                        for ($i = 1; $i <= 12; $i++) {
                                            $tr .= "
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($realisasi_anggaran[$i]) . "</td>
                                        <td class='align-middle text-center'>" . $realisasi_kinerja[$i] . "</td>";
                                        }
                                    } else { //middle
                                        $tr .= "
                                    <tr class='bg-success text-white'></tr>
                                        <td class='align-middle'>" . $r['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                    </tr>";
                                    }
                                endforeach;
                            endif;
                        ?>
                            <tr class="bg-success text-white">
                                <td class="text-center align-middle" rowspan="<?= @$toEnd ?>"><?= $no_level_0_1 ?></td>
                                <td class="align-middle" colspan="2" rowspan="<?= @$toEnd ?>"><?= $s->nama ?> </td>
                                <?= $tr ?>
                            </tr>
                            <?php
                            $no_level_1 = 1;
                            $programs = $this->target->program($s->id, $this->session->userdata('part'), $this->session->userdata('tahun_anggaran'));
                            foreach ($programs->result() as $program) :
                                $indikator_program = $this->realisasi->getIndikator(['fid_program' => $program->id], $this->session->userdata('part'));
                                $tr = "";
                                if ($indikator_program->num_rows() > 0) :
                                    $indikator = $indikator_program->result_array();
                                    $toEnd = count($indikator);
                                    foreach ($indikator as $key => $ip) :
                                        // Target Kinerja
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


                                        // Aksi
                                        // $FaktorPendorong = $realisasi->faktor_pendorong === NULL ? '-' : $realisasi->faktor_pendorong;
                                        // $FaktorPenghambat = $realisasi->faktor_penghambat === NULL ? '-' : $realisasi->faktor_penghambat;
                                        // $TindakLanjut = $realisasi->tindak_lanjut === NULL ? '-' : $realisasi->tindak_lanjut;

                                        $rowspan = $toEnd++;
                                        if (0 === --$toEnd) { //last
                                            $tr .= "";
                                        } elseif ($key === 0) { //first
                                            $tr .= "
                                        <td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                            for ($i = 1; $i <= 12; $i++) {
                                                $tr .= "
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($realisasi_anggaran[$i]) . "</td>
                                        <td class='align-middle text-center'>" . $realisasi_kinerja[$i] . "</td>";
                                            }
                                        } else { //middle
                                            $tr .= "
                                    <tr class='bg-secondary text-white'>
                                        <td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                    </tr>";
                                        }
                                    endforeach;
                                endif;
                            ?>
                                <tr class="bg-secondary text-white">
                                    <td class="text-center align-middle" rowspan="<?= @$toEnd ?>"><?= $no_level_1 ?></td>
                                    <td rowspan="<?= @$toEnd ?>"></td>
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
                                    $indikator_kegiatan = $this->realisasi->getIndikator(['fid_kegiatan' => $kegiatan->id], $this->session->userdata('part'));
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

                                            // Aksi
                                            // $FaktorPendorong = $realisasi->faktor_pendorong === NULL ? '-' : $realisasi->faktor_pendorong;
                                            // $FaktorPenghambat = $realisasi->faktor_penghambat === NULL ? '-' : $realisasi->faktor_penghambat;
                                            // $TindakLanjut = $realisasi->tindak_lanjut === NULL ? '-' : $realisasi->tindak_lanjut;

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
                                            } else { //middle
                                                $tr .= "
                                    <tr class='bg-info text-white'>
                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>
                                        <td class='align-middle text-center'>" . $realisasi_kinerja . "</td>
                                        <td class='align-middle text-center'>" . $capaian_kinerja . " (%)</td>
                                    </tr>";
                                            }
                                        endforeach;
                                    endif;
                                ?>
                                    <tr class="bg-info text-white">
                                        <td class="text-center align-middle" rowspan="<?= @$toEnd ?>"><?= $no_level_1 . "." . $no_level_2 ?></td>
                                        <td rowspan="<?= @$toEnd ?>"></td>
                                        <td class="align-middle" rowspan="<?= @$toEnd ?>"><?= $kegiatan->nama ?></td>
                                        <?= $tr ?>
                                    </tr>
                                    <?php
                                    $sub_kegiatans = $this->realisasi->sub_kegiatans($kegiatan->id);
                                    $no_level_3 = 1;
                                    foreach ($sub_kegiatans->result() as $sub_kegiatan) :
                                        $indikator_sub_kegiatan = $this->realisasi->getIndikator(['fid_sub_kegiatan' => $sub_kegiatan->id], $this->session->userdata('part'));
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

                                                // Aksi
                                                // $FaktorPendorong = $realisasi->faktor_pendorong === NULL ? '-' : $realisasi->faktor_pendorong;
                                                // $FaktorPenghambat = $realisasi->faktor_penghambat === NULL ? '-' : $realisasi->faktor_penghambat;
                                                // $TindakLanjut = $realisasi->tindak_lanjut === NULL ? '-' : $realisasi->tindak_lanjut;

                                                $rowspan = $toEnd++;
                                                if (0 === --$toEnd) { //last
                                                    $tr .= "";
                                                } elseif ($key === 0) { //first
                                                    $tr .= "
                                        <td class='align-middle text-nowrap'>" . $isk['nama'] . " <i class='" . $isk['color'] . "'>(" . $isk['jenis_indikator'] . ")</i></td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                                    for ($i = 1; $i <= 12; $i++) {
                                                        $tr .= "
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($realisasi_anggaran[$i]) . "</td>
                                        <td class='align-middle text-center'>" . $realisasi_kinerja[$i] . "</td>";
                                                    }
                                                } else { //middle
                                                    $tr .= "
                                    <tr>
                                        <td class='align-middle text-nowrap'>" . $isk['nama'] . " <i class='" . $isk['color'] . "'>(" . $isk['jenis_indikator'] . ")</i></td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>";
                                                    for ($i = 1; $i <= 12; $i++) {
                                                        $tr .= "
                                        <td class='align-middle text-center'>" . $realisasi_kinerja[$i] . "</td>";
                                                    }
                                                }
                                            endforeach;
                                        endif;
                                    ?>
                                        <tr>
                                            <td class="text-center align-middle" rowspan="<?= @$toEnd ?>"><?= $no_level_1 . "." . $no_level_2 . "." . $no_level_3 ?></td>
                                            <td rowspan="<?= @$toEnd ?>"></td>
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
            <table class="table table-bordered">

                <tbody>
                    <tr>
                        <th colspan="2">Faktor pendorong keberhasilan kinerja :</th>
                    </tr>
                    <tr>
                        <td><?= $faktor->pendorong ?? "-"; ?></td>
                        <?php if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN'): ?>
                            <td width="5%" class="valign-middle text-center">
                                <button class="btn btn-sm btn-primary m-0" onclick="InputFaktor('<?= @$faktor->id ?>')"><i class="fa fa-edit"></i></button>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <th colspan="2">Faktor penghambat pencapaian kinerja :</th>
                    </tr>
                    <tr>
                        <td><?= $faktor->penghambat ?? "-"; ?></td>
                        <?php if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN'): ?>
                            <td width="5%" class="valign-middle text-center">
                                <button class="btn btn-sm btn-primary m-0" onclick="InputFaktor('<?= @$faktor->id ?>')" data-id=""><i class="fa fa-edit"></i></button>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <th colspan="2">
                            Tindak lanjut yang diperlukan :
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?= $faktor->tindak_lanjut ?? "-"; ?>
                        </td>
                        <?php if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN'): ?>
                            <td width="5%" class="valign-middle text-center">
                                <button class="btn btn-sm btn-primary m-0" onclick="InputFaktor('<?= @$faktor->id ?>')" data-id=""><i class="fa fa-edit"></i></button>
                            </td>
                        <?php endif; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Faktor -->
<div class="modal fade modal-faktor" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url("app/capaian/input_faktor"), ['id' => 'formFaktor', 'data-parsley-validate' => '']); ?>
        <input type="hidden" name="id">
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Input Faktor</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="pendorong">Faktor Pendorong <span class="text-danger">*</span></label>
                    <textarea name="pendorong" id="pendorong" cols="30" rows="5" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="penghambat">Faktor Penghambat <span class="text-danger">*</span></label>
                    <textarea name="penghambat" id="penghambat" cols="30" rows="5" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="tindak_lanjut">Tindak Lanjut <span class="text-danger">*</span></label>
                    <textarea name="tindak_lanjut" id="tindak_lanjut" cols="30" rows="5" class="form-control" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i class="fa fa-close mr-2"></i>Batal</button>
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>