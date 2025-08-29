<?php
$periode_id = isset($_GET['periode']) ? $_GET['periode'] : $this->spj->getLastPeriode()->row()->id;
$periode_nama = $this->realisasi->getPeriodeById($periode_id)->row()->nama;
?>
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-dollar mr-2"></i> Realisasi Anggaran & Kinerja - <?= $periode_nama ?></h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li class="d-flex justify-content-center align-items-center mr-2"><a
                    href="<?= base_url('app/realisasi/cetak/' . $periode_id) ?>" target="_blank"
                    class="print-link text-primary"><i class="fa fa-print"></i> Cetak</a></li>
            <li class="d-flex justify-content-center align-items-center mr-2"><a
                    href="<?= base_url('app/export/realisasi/' . $periode_id) ?>" class="print-link text-info"><i
                        class="fa fa-download"></i> Export</a></li>
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="periode">Pilih Periode</label>
                    <select name="periode" id="periode" class="form-control rounded-0"
                        onchange="PilihPeriode(this.value)">
                        <?php
                        foreach ($this->spj->getPeriode()->result() as $periode) :
                            $is_status = $periode->is_open === 'Y' ? 'OPEN' : 'CLOSE';
                            $disabled = $periode->is_open !== 'Y' ? 'disabled' : '';
                            if (isset($_GET['periode']) && $_GET['periode'] === $periode->id && $periode->is_open === 'Y') {
                                $selected = "selected";
                            } else {
                                $selected = "";
                            }
                        ?>
                            <option value="<?= $periode->id ?>" <?= $disabled ?> <?= $selected ?>><?= $periode->nama ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-responsive-md" id="tableRealisasi">
            <thead class="bg-light top-0" style="position: sticky; top: 0; z-index: 1;">
                <tr class="text-center">
                    <th rowspan="2" class="align-middle">Kode</th>
                    <th rowspan="2" class="align-middle">Tujuan & Sasaran</th>
                    <th rowspan="2" class="align-middle sticky-col">Program/Kegiatan/Sub Kegiatan</th>
                    <th rowspan="2" class="align-middle">Indikator Kinerja</th>
                    <th colspan="2">Realisasi</th>
                    <th colspan="3">Aksi</th>
                </tr>
                <tr class="text-center">
                    <th>Anggaran (Rp)</th>
                    <th>Kinerja</th>
                    <th>Input</th>
                    <th>Link</th>
                    <?php if (privilages('priv_verify_kinerja')): ?>
                        <th>Verifikasi</th>
                    <?php else: ?>
                        <th>Catatan</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_level_0 = "#";
                $tujuan = $this->target->getTujuan(['t.tahun' => $this->session->userdata('tahun_anggaran')]);
                foreach ($tujuan->result() as $t) :
                    $indikator_tujuan = $this->realisasi->getIndikator(['i.fid_tujuan' => $t->id], $this->session->userdata('part'));
                    $tr = "";
                    $rowspan = 1;
                    if ($indikator_tujuan->num_rows() > 0):
                        $indikator = $indikator_tujuan->result_array();
                        $toEnd = count($indikator);
                        foreach ($indikator as $key => $r) :
                            $isStatusVerifikasi = $this->realisasi->isStatusVerifikasi($periode_id, $r['indikator_id']);
                            // Aksi
                            if (($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'ADMIN') && ($isStatusVerifikasi === 'ENTRI' || $isStatusVerifikasi === 'ENTRI_ULANG')) :
                                $btn_input = '<button class="btn btn-primary btn-sm m-0" onclick="InputRealisasi(' . $r['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-pencil"></i></button>';
                            else:
                                $btn_input = '';
                            endif;

                            // Realisasi by indikator
                            $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, null, $r['indikator_id'], $tahun_anggaran)->row();
                            if ($realisasi->is_jenis === "2" && $realisasi->status === 'SETUJU') {
                                $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                            } elseif ($realisasi->is_jenis === "1" && $realisasi->status === 'SETUJU') {
                                $sum_realisasi = $realisasi->persentase . "%";
                            } else {
                                $sum_realisasi = $isStatusVerifikasi;
                            }

                            // Button Note
                            if ($isStatusVerifikasi === 'ENTRI_ULANG') {
                                $btn_note = '<button class="btn btn-danger btn-sm m-0" onclick="ViewNote(' . $r['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-info-circle"></i></button>';
                            } else {
                                $btn_note = '';
                            }

                            // Button Verifikasi
                            if (($isStatusVerifikasi === 'VERIFIKASI' || $isStatusVerifikasi === 'SETUJU') && privilages('priv_verify_kinerja')) {
                                $btn_verifikasi = '<button class="btn btn-default bg-white btn-sm m-0" onclick="Verifikasi(' . $r['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-edit"></i></button>';
                            } else {
                                $btn_verifikasi = '';
                            }

                            // Link
                            if ($realisasi->eviden_link !== null || !empty($realisasi->eviden_link)) {
                                $link = '<a href="' . $realisasi->eviden_link . '" target="_blank" class="btn btn-warning btn-sm m-0"><i class="fa fa-link"></i></a>';
                            } else {
                                $link = "";
                            }

                            // Row
                            $rowspan = $toEnd++;
                            if (0 === --$toEnd) { //last
                                $tr .= "";
                            } elseif ($key === 0) { //first
                                $tr .= "
                                        <td class='align-middle'>" . $r['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiTujuan($periode_id, null, $t->id, $tahun_anggaran)) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        <td class='align-middle text-center'>" . $btn_verifikasi . $btn_note . "</td>
                                        ";
                            } else { //middle
                                $tr .= "
                                    <tr class='bg-warning'>
                                        <td class='align-middle'>" . $r['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi  . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        <td class='align-middle text-center'>" . $btn_verifikasi . $btn_note . "</td>
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
                        <tr></tr>";
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
                        $indikator_sasaran = $this->realisasi->getIndikator(['i.fid_sasaran' => $s->id], null);
                        $tr = "";
                        if ($indikator_sasaran->num_rows() > 0):
                            $indikator = $indikator_sasaran->result_array();
                            $toEnd = count($indikator);
                            foreach ($indikator as $key => $r) :
                                $isStatusVerifikasi = $this->realisasi->isStatusVerifikasi($periode_id, $r['indikator_id']);
                                // Aksi
                                if (($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'ADMIN') && ($isStatusVerifikasi === 'ENTRI' || $isStatusVerifikasi === 'ENTRI_ULANG')) :
                                    $btn_input = '<button class="btn btn-primary btn-sm m-0" onclick="InputRealisasi(' . $r['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-pencil"></i></button>';
                                else:
                                    $btn_input = '';
                                endif;

                                // Realisasi by indikator
                                $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, null, $r['indikator_id'], $tahun_anggaran)->row();
                                if ($realisasi->is_jenis === "2" && $realisasi->status === 'SETUJU') {
                                    $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                } elseif ($realisasi->is_jenis === "1" && $realisasi->status === 'SETUJU') {
                                    $sum_realisasi = $realisasi->persentase . "%";
                                } else {
                                    $sum_realisasi = $isStatusVerifikasi;
                                }

                                // Button Note
                                if ($isStatusVerifikasi === 'ENTRI_ULANG') {
                                    $btn_note = '<button class="btn btn-danger btn-sm m-0" onclick="ViewNote(' . $r['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-info-circle"></i></button>';
                                } else {
                                    $btn_note = '';
                                }

                                // Button Verifikasi
                                if (($isStatusVerifikasi === 'VERIFIKASI' || $isStatusVerifikasi === 'SETUJU') && privilages('priv_verify_kinerja')) {
                                    $btn_verifikasi = '<button class="btn btn-default bg-white btn-sm m-0" onclick="Verifikasi(' . $r['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-edit"></i></button>';
                                } else {
                                    $btn_verifikasi = '';
                                }

                                // Link
                                if ($realisasi->eviden_link !== null || !empty($realisasi->eviden_link)) {
                                    $link = '<a href="' . $realisasi->eviden_link . '" target="_blank" class="btn btn-warning btn-sm m-0"><i class="fa fa-link"></i></a>';
                                } else {
                                    $link = "";
                                }

                                // Row
                                $rowspan = $toEnd++;
                                if (0 === --$toEnd) { //last
                                    $tr .= "";
                                } elseif ($key === 0) { //first
                                    $tr .= "
                                        <td class='align-middle'>" . $r['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiSasaran($periode_id, null, $s->id, $this->session->userdata('tahun_anggaran'))) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        <td class='align-middle text-center'>" . $btn_verifikasi . $btn_note . "</td>
                                        ";
                                } else { //middle
                                    $tr .= "
                                    <tr class='bg-success text-white'>
                                        <td class='align-middle'>" . $r['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        <td class='align-middle text-center'>" . $btn_verifikasi . $btn_note . "</td>
                                    </tr>";
                                }
                            endforeach;
                        else:
                            $tr .= "
                        <td colspan='6' rowspan='" . $rowspan . "'></td>
                        <tr></tr>";
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
                            if ($this->session->userdata('role') === 'USER') {
                                $indikator_program = $this->realisasi->getIndikator(['fid_program' => $program->id], $this->session->userdata('part'));
                            } else {
                                $indikator_program = $this->realisasi->getIndikator(['fid_program' => $program->id], null);
                            }
                            $tr = "";
                            if ($indikator_program->num_rows() > 0) :
                                $indikator = $indikator_program->result_array();
                                $toEnd = count($indikator);
                                foreach ($indikator as $key => $ip) :
                                    $isStatusVerifikasi = $this->realisasi->isStatusVerifikasi($periode_id, $ip['indikator_id']);
                                    if ($this->session->userdata('role') === 'USER' && ($isStatusVerifikasi === 'ENTRI' || $isStatusVerifikasi === 'ENTRI_ULANG')) :
                                        $btn_input = '<button class="btn btn-primary btn-sm m-0" onclick="InputRealisasi(' . $ip['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-pencil"></i></button>';
                                    else:
                                        $btn_input = "";
                                    endif;

                                    $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, null, $ip['indikator_id'], $tahun_anggaran)->row();

                                    if ($realisasi->is_jenis === "2" && $realisasi->status === 'SETUJU') {
                                        $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                    } elseif ($realisasi->is_jenis === "1" && $realisasi->status === 'SETUJU') {
                                        $sum_realisasi = $realisasi->persentase . "%";
                                    } else {
                                        $sum_realisasi = $isStatusVerifikasi;
                                    }

                                    // Button Note
                                    if ($isStatusVerifikasi === 'ENTRI_ULANG') {
                                        $btn_note = '<button class="btn btn-danger btn-sm m-0" onclick="ViewNote(' . $ip['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-info-circle"></i></button>';
                                    } else {
                                        $btn_note = '';
                                    }

                                    // Button Verifikasi
                                    if (($isStatusVerifikasi === 'VERIFIKASI' || $isStatusVerifikasi === 'SETUJU') && privilages('priv_verify_kinerja')) {
                                        $btn_verifikasi = '<button class="btn btn-default bg-white btn-sm m-0" onclick="Verifikasi(' . $ip['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-edit"></i></button>';
                                    } else {
                                        $btn_verifikasi = '';
                                    }

                                    // Link
                                    if ($realisasi->eviden_link !== null || !empty($realisasi->eviden_link)) {
                                        $link = '<a href="' . $realisasi->eviden_link . '" target="_blank" class="btn btn-warning btn-sm m-0"><i class="fa fa-link"></i></a>';
                                    } else {
                                        $link = "";
                                    }

                                    $rowspan = $toEnd++;
                                    if (0 === --$toEnd) { //last
                                        $tr .= "";
                                    } elseif ($key === 0) { //first
                                        $tr .= "
                                        <td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiProgram($periode_id, null, $program->id)) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        <td class='align-middle text-center'>" . $btn_verifikasi . $btn_note . "</td>
                                        ";
                                    } else { //middle
                                        $tr .= "
                                    <tr class='bg-secondary text-white'>
                                        <td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        <td class='align-middle text-center'>" . $btn_verifikasi . $btn_note . "</td>
                                    </tr>";
                                    }
                                endforeach;
                            else:
                                $tr .= "
                                    <td colspan='6' rowspan='" . $rowspan . "'></td>
                                    <tr></tr>";
                            endif;
                        ?>
                            <tr class="bg-secondary text-white">
                                <td class="text-center align-middle" rowspan="<?= @$toEnd ?>"><?= $no_level_1 ?></td>
                                <td rowspan="<?= @$toEnd ?>"></td>
                                <td class="align-middle" rowspan="<?= @$toEnd ?>"><?= $program->nama ?> </td>
                                <?= $tr ?>
                            </tr>
                            <?php
                            if ($this->session->userdata('role') === 'ADMIN') :
                                $kegiatans = $this->realisasi->kegiatans($program->id);
                            else :
                                $kegiatans = $this->realisasi->kegiatans($program->id, $this->session->userdata('part'));
                            endif;

                            $no_level_2 = 1;
                            foreach ($kegiatans->result() as $kegiatan) :
                                if ($this->session->userdata('role') === 'ADMIN') :
                                    $indikator_kegiatan = $this->realisasi->getIndikator(['fid_kegiatan' => $kegiatan->id], null);
                                else:
                                    $indikator_kegiatan = $this->realisasi->getIndikator(
                                        ['fid_kegiatan' => $kegiatan->id],
                                        $this->session->userdata('part')
                                    );
                                endif;
                                $tr = "";
                                if ($indikator_kegiatan->num_rows() > 0) :
                                    $indikator_keg = $indikator_kegiatan->result_array();
                                    $toEnd = count($indikator_keg);
                                    foreach ($indikator_keg as $key => $ik) :
                                        $isStatusVerifikasi = $this->realisasi->isStatusVerifikasi($periode_id, $ik['indikator_id']);
                                        if ($this->session->userdata('role') === 'USER' && ($isStatusVerifikasi === 'ENTRI' || $isStatusVerifikasi === 'ENTRI_ULANG')) :
                                            $btn_input = '<button class="btn btn-warning btn-sm m-0" onclick="InputRealisasi(' . $ik['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-pencil"></i></button>';
                                        else:
                                            $btn_input = '';
                                        endif;
                                        $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, null, $ik['indikator_id'], $tahun_anggaran)->row();
                                        if ($realisasi->is_jenis === "2" && $isStatusVerifikasi === 'SETUJU') {
                                            $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                        } elseif ($realisasi->is_jenis === "1" && $isStatusVerifikasi === 'SETUJU') {
                                            $sum_realisasi = $realisasi->persentase . "%";
                                        } else {
                                            $sum_realisasi = $isStatusVerifikasi;
                                        }

                                        // Button Note
                                        if ($isStatusVerifikasi === 'ENTRI_ULANG') {
                                            $btn_note = '<button class="btn btn-danger btn-sm m-0" onclick="ViewNote(' . $ik['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-info-circle"></i></button>';
                                        } else {
                                            $btn_note = '';
                                        }

                                        // Button Verifikasi
                                        if (($isStatusVerifikasi === 'VERIFIKASI' || $isStatusVerifikasi === 'SETUJU') && privilages('priv_verify_kinerja')) {
                                            $btn_verifikasi = '<button class="btn btn-default bg-white btn-sm m-0" onclick="Verifikasi(' . $ik['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-edit"></i></button>';
                                        } else {
                                            $btn_verifikasi = '';
                                        }

                                        // Link
                                        if (!empty($realisasi->eviden_link)) {
                                            $link = '<a href="' . $realisasi->eviden_link . '" target="_blank" class="btn btn-warning btn-sm m-0"><i class="fa fa-link"></i></a>';
                                        } else {
                                            $link = "";
                                        }

                                        $rowspan = $toEnd++;
                                        if (0 === --$toEnd) { //last
                                            $tr .= "";
                                        } elseif ($key === 0) { //first
                                            $tr .= "
                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiKegiatan($periode_id, null, $kegiatan->id)) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi  . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        <td class='align-middle text-center'>" . $btn_verifikasi . $btn_note . "</td>";
                                        } else { //middle
                                            $tr .= "
                                    <tr class='bg-info text-white'>
                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        <td class='align-middle text-center'>" . $btn_verifikasi . $btn_note . "</td>
                                    </tr>";
                                        }
                                    endforeach;
                                else:
                                    $tr .= "
                        <td colspan='6' rowspan='" . $rowspan . "'></td>
                        <tr></tr>";
                                endif;
                            ?>
                                <tr class="bg-info text-white">
                                    <td class="text-center align-middle" rowspan="<?= @$toEnd ?>"><?= $no_level_1 . "." . $no_level_2 ?>
                                    </td>
                                    <td rowspan="<?= @$toEnd ?>"></td>
                                    <td class="align-middle" rowspan="<?= @$toEnd ?>"><?= $kegiatan->nama ?></td>
                                    <?= $tr ?>
                                </tr>
                                <?php
                                $sub_kegiatans = $this->realisasi->sub_kegiatans($kegiatan->id);
                                $no_level_3 = 1;
                                foreach ($sub_kegiatans->result() as $sub_kegiatan) :
                                    if ($this->session->userdata('role') === 'USER') :
                                        $indikator_sub_kegiatan = $this->realisasi->getIndikator(['fid_sub_kegiatan' => $sub_kegiatan->id], $this->session->userdata('part'));
                                    else:
                                        $indikator_sub_kegiatan = $this->realisasi->getIndikator(['fid_sub_kegiatan' => $sub_kegiatan->id], null);
                                    endif;
                                    $tr = "";
                                    if ($indikator_sub_kegiatan->num_rows() > 0) :
                                        $indikator_sub = $indikator_sub_kegiatan->result_array();
                                        $toEnd = count($indikator_sub);
                                        foreach ($indikator_sub as $key => $isk) :
                                            $isStatusVerifikasi = $this->realisasi->isStatusVerifikasi($periode_id, $isk['indikator_id']);
                                            if ($this->session->userdata('role') === 'USER' && ($isStatusVerifikasi === 'ENTRI' || $isStatusVerifikasi === 'ENTRI_ULANG')) :
                                                $btn_input = '<button class="btn btn-light btn-sm m-0" onclick="InputRealisasi(' . $isk['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-pencil"></i></button>';
                                            else:
                                                $btn_input = '';
                                            endif;
                                            $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, null, $isk['indikator_id'], $tahun_anggaran)->row();
                                            if ($realisasi->is_jenis === "2" && $isStatusVerifikasi === 'SETUJU') {
                                                $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                            } elseif ($realisasi->is_jenis === "1" && $isStatusVerifikasi === 'SETUJU') {
                                                $sum_realisasi = $realisasi->persentase . "%";
                                            } else {
                                                $sum_realisasi = $isStatusVerifikasi;
                                            }

                                            // Button Note
                                            if ($isStatusVerifikasi === 'ENTRI_ULANG') {
                                                $btn_note = '<button class="btn btn-danger btn-sm m-0" onclick="ViewNote(' . $isk['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-info-circle"></i></button>';
                                            } else {
                                                $btn_note = '';
                                            }

                                            // Button Verifikasi
                                            if (($isStatusVerifikasi === 'VERIFIKASI' || $isStatusVerifikasi === 'SETUJU') && privilages('priv_verify_kinerja')) {
                                                $btn_verifikasi = '<button class="btn btn-default bg-white btn-sm m-0" onclick="Verifikasi(' . $isk['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-edit"></i></button>';
                                            } else {
                                                $btn_verifikasi = '';
                                            }

                                            // Link
                                            if ($realisasi->eviden_link !== null || !empty($realisasi->eviden_link)) {
                                                $link = '<a href="' . $realisasi->eviden_link . '" target="_blank" class="btn btn-warning btn-sm m-0"><i class="fa fa-link"></i></a>';
                                            } else {
                                                $link = "";
                                            }

                                            $rowspan = $toEnd++;
                                            if (0 === --$toEnd) { //last
                                                $tr .= "";
                                            } elseif ($key === 0) { //first
                                                $tr .= "
                                        <td class='align-middle'>" . $isk['nama'] . " <i class='" . $isk['color'] . "'>(" . $isk['jenis_indikator'] . ")</i></td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiSubKegiatan($periode_id, null, $sub_kegiatan->id)) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi  . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        <td class='align-middle text-center'>" . $btn_verifikasi . $btn_note . "</td>
                                        ";
                                            } else { //middle
                                                $tr .= "
                                    <tr>
                                        <td class='align-middle'>" . $isk['nama'] . " <i class='" . $isk['color'] . "'>(" . $isk['jenis_indikator'] . ")</i></td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        <td class='align-middle text-center'>" . $btn_verifikasi . $btn_note . "</td>
                                    </tr>";
                                            }
                                        endforeach;
                                    else:
                                        $tr .= "
                        <td colspan='6' rowspan='" . $rowspan . "'></td>
                        <tr></tr>";
                                    endif;
                                ?>
                                    <tr>
                                        <td class="text-center align-middle" rowspan="<?= @$toEnd ?>">
                                            <?= $no_level_1 . "." . $no_level_2 . "." . $no_level_3 ?></td>
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
    </div>
</div>

<!-- Modal Input Realisasi -->
<div class="modal fade modal-realisasi" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url("app/realisasi/input"), ['id' => 'formRealisasi', 'data-parsley-validate' => '']); ?>
        <input type="hidden" name="id">
        <input type="hidden" name="is_jenis">
        <input type="hidden" name="periode" value="<?= $periode_id ?>">
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Realisasi Kinerja</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="catatan-verify" style="display: none;"></div>
                <div class="form-group">
                    <label for="nama">Nama Indikator <span class="text-danger">*</span></label>
                    <textarea name="nama" id="nama" cols="30" rows="4" class="form-control" disabled></textarea>
                </div>
                <div class="form-group">
                    <label for="link">Link Bukti Dukung</label>
                    <textarea name="link" id="link" cols="30" rows="4" class="form-control"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-3" id="formPersentase" style="display:none;">
                        <div class="form-group">
                            <label for="persentase">Persentase Hasil (%) <span class="text-danger">*</span></label>
                            <input type="text" name="persentase" id="persentase" class="form-control"
                                data-parsley-pattern="^\d+(\.\d+)?$"
                                data-parsley-pattern-message="Hanya boleh angka desimal dengan titik." required>
                        </div>
                    </div>
                    <div class="col-md-3" id="formEviden" style="display:none;">
                        <div class="form-group">
                            <label for="jumlah_eviden">Jumlah Eviden (Output) <span class="text-danger">*</span></label>
                            <input type="text" name="jumlah_eviden" id="jumlah_eviden" class="form-control"
                                data-parsley-pattern="^\d+(\.\d+)?$"
                                data-parsley-pattern-message="Hanya boleh angka desimal dengan titik." required>
                        </div>
                    </div>
                    <div class="col-md-6" id="formKeteranganEviden" style="display:none;">
                        <div class="form-group">
                            <label for="keterangan_eviden">Jenis Eviden <span class="text-danger">*</span></label>
                            <input type="text" name="keterangan_eviden" id="keterangan_eviden" class="form-control"
                                required readonly>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i
                        class="fa fa-close mr-2"></i>Batal</button>
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal View Catatan -->
<div class="modal fade modal-note" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Catatan</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i
                        class="fa fa-close mr-2"></i>Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
<div class="modal fade modal-verifikasi" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Verifikasi Realisasi</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <?= form_open(base_url("app/realisasi/verifikasi"), ['id' => 'formVerifikasi', 'data-parsley-validate' => '']); ?>
            <input type="hidden" name="id">
            <input type="hidden" name="periode" value="<?= $periode_id ?>">
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <td width="20%">Periode</td>
                        <td width="3%">:</td>
                        <td id="verifikasi_periode"></td>
                    </tr>
                    <tr>
                        <td width="20%">Nama Indikator</td>
                        <td width="3%">:</td>
                        <td id="verifikasi_nama"></td>
                    </tr>
                    <tr class="bg-light persentase">
                        <td>Persentase</td>
                        <td>:</td>
                        <td id="verifikasi_persentase"></td>
                    </tr>
                    <tr class="bg-light non-persentase">
                        <td>Jumlah Eviden</td>
                        <td>:</td>
                        <td id="verifikasi_eviden"></td>
                    </tr>
                    <tr class="bg-light non-persentase">
                        <td>Jenis Eviden</td>
                        <td>:</td>
                        <td id="verifikasi_jenis_eviden"></td>
                    </tr>
                    <tr>
                        <td>Link Bukti Dukung</td>
                        <td>:</td>
                        <td id="verifikasi_link"></td>
                    </tr>
                </table>
                <div class="form-group">
                    <label for="verifikasi_status">Status Verifikasi</label>
                    <select name="verifikasi_status" id="verifikasi_status" class="form-control" required>
                        <option value="VERIFIKASI">-- Pilih Status --</option>
                        <option value="SETUJU">Setuju</option>
                        <option value="ENTRI_ULANG">Tolak</option>
                    </select>
                </div>
                <div class="form-group d-none" id="verifikasi_catatan">
                    <label for="catatan">Alasan Tolak</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i
                            class="fa fa-close mr-2"></i>Batal</button>
                    <button type="submit" class="btn btn-success rounded-0"><i
                            class="fa fa-save mr-2"></i>Simpan</button>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>