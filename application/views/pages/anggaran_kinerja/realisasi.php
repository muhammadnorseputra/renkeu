<?php
$periode_id = isset($_GET['periode']) ? $_GET['periode'] : $this->spj->getLastPeriode()->row()->id;
$periode_nama = $this->realisasi->getPeriodeById($periode_id)->row()->nama;
?>
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-dollar mr-2"></i> Realisasi Anggaran & Kinerja - <?= $periode_nama ?></h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li class="d-flex justify-content-center align-items-center mr-2"><a href="<?= base_url('app/realisasi/cetak/' . $periode_id) ?>" target="_blank" class="print-link text-primary"><i class="fa fa-print"></i> Cetak</a></li>
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="periode">Pilih Periode</label>
                    <select name="periode" id="periode" class="form-control rounded-0" onchange="PilihPeriode(this.value)">
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
                            <option value="<?= $periode->id ?>" <?= $disabled ?> <?= $selected ?>><?= $periode->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2" class="align-middle">Kode</th>
                        <th rowspan="2" class="align-middle">Tujuan & Sasaran</th>
                        <th rowspan="2" class="align-middle sticky-col">Program/Kegiatan/Sub Kegiatan</th>
                        <th rowspan="2" class="align-middle">Indikator Kinerja</th>
                        <th colspan="2">Realisasi</th>
                        <th colspan="2">Aksi</th>
                    </tr>
                    <tr class="text-center">
                        <th>Anggaran (Rp)</th>
                        <th>Kinerja</th>
                        <th>Input</th>
                        <th>Link</th>
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
                                // Aksi
                                if ($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'ADMIN') :
                                    $btn_input = '<button class="btn btn-primary btn-sm m-0" onclick="InputRealisasi(' . $r['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-pencil"></i></button>';
                                else:
                                    $btn_input = '';
                                endif;

                                // Realisasi by indikator
                                $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, $r['indikator_id'])->row();
                                if ($realisasi->persentase === "0") {
                                    $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                } elseif ($realisasi->eviden === "0") {
                                    $sum_realisasi = $realisasi->persentase . "%";
                                } else {
                                    $sum_realisasi = "-";
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
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiTujuan($periode_id, $t->id, $this->session->userdata('tahun_anggaran'))) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        ";
                                } else { //middle
                                    $tr .= "
                                    <tr class='bg-warning'>
                                        <td class='align-middle'>" . $r['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
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
                                    // Aksi
                                    if ($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'ADMIN') :
                                        $btn_input = '<button class="btn btn-primary btn-sm m-0" onclick="InputRealisasi(' . $r['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-pencil"></i></button>';
                                    else:
                                        $btn_input = '';
                                    endif;

                                    // Realisasi by indikator
                                    $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, $r['indikator_id'])->row();
                                    if ($realisasi->persentase === "0") {
                                        $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                    } elseif ($realisasi->eviden === "0") {
                                        $sum_realisasi = $realisasi->persentase . "%";
                                    } else {
                                        $sum_realisasi = "-";
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
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiSasaran($periode_id, $s->id, $this->session->userdata('tahun_anggaran'))) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        ";
                                    } else { //middle
                                        $tr .= "
                                    <tr class='bg-success text-white'>
                                        <td class='align-middle'>" . $r['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
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
                                        if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN') :
                                            $btn_input = '<button class="btn btn-primary btn-sm m-0" onclick="InputRealisasi(' . $ip['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-pencil"></i></button>';
                                        else:
                                            $btn_input = "";
                                        endif;

                                        $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, $ip['indikator_id'])->row();
                                        if ($realisasi->persentase === "0") {
                                            $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                        } elseif ($realisasi->eviden === "0") {
                                            $sum_realisasi = $realisasi->persentase . "%";
                                        } else {
                                            $sum_realisasi = "-";
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
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiProgram($periode_id, $program->id)) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        ";
                                        } else { //middle
                                            $tr .= "
                                    <tr class='bg-secondary text-white'>
                                        <td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
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
                                if ($this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('user_name') === 'kaban' || $this->session->userdata('role') === 'ADMIN') :
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
                                            if ($this->session->userdata('role') === 'USER') :
                                                $btn_input = '<button class="btn btn-warning btn-sm m-0" onclick="InputRealisasi(' . $ik['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-pencil"></i></button>';
                                            else:
                                                $btn_input = '';
                                            endif;
                                            $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, $ik['indikator_id'])->row();
                                            if ($realisasi->persentase === "0") {
                                                $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                            } elseif ($realisasi->eviden === "0") {
                                                $sum_realisasi = $realisasi->persentase . "%";
                                            } else {
                                                $sum_realisasi = "-";
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
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiKegiatan($periode_id, $kegiatan->id)) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>";
                                            } else { //middle
                                                $tr .= "
                                    <tr class='bg-info text-white'>
                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
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
                                                if ($this->session->userdata('role') === 'USER') :
                                                    $btn_input = '<button class="btn btn-light btn-sm m-0" onclick="InputRealisasi(' . $isk['indikator_id'] . ',' . $periode_id . ')"><i class="fa fa-pencil"></i></button>';
                                                else:
                                                    $btn_input = '';
                                                endif;
                                                $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, $isk['indikator_id'])->row();
                                                if ($realisasi->persentase === "0") {
                                                    $sum_realisasi = $realisasi->eviden . " " . $realisasi->eviden_jenis;
                                                } elseif ($realisasi->eviden === "0") {
                                                    $sum_realisasi = $realisasi->persentase . "%";
                                                } else {
                                                    $sum_realisasi = "-";
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
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($this->realisasi->getRealisasiSubKegiatan($periode_id, $sub_kegiatan->id)) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                        ";
                                                } else { //middle
                                                    $tr .= "
                                    <tr>
                                        <td class='align-middle'>" . $isk['nama'] . " <i class='" . $isk['color'] . "'>(" . $isk['jenis_indikator'] . ")</i></td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle text-center'>" . $btn_input . "</td>
                                        <td class='align-middle text-center'>" . $link . "</td>
                                    </tr>";
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
        </div>
    </div>
</div>

<!-- Modal Input Realisasi -->
<div class="modal fade modal-realisasi" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url("app/realisasi/input"), ['id' => 'formRealisasi', 'data-parsley-validate' => '']); ?>
        <input type="hidden" name="id">
        <input type="hidden" name="periode" value="<?= $periode_id ?>">
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Realisasi Kinerja</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama">Nama Indikator <span class="text-danger">*</span></label>
                    <textarea name="nama" id="nama" cols="30" rows="4" class="form-control" disabled></textarea>
                </div>
                <div class="form-group">
                    <label for="link">Link Bukti Dukung</label>
                    <textarea name="link" id="link" cols="30" rows="4" class="form-control"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="persentase">Persentase Hasil (%) <span class="text-danger">*</span></label>
                            <input type="text" name="persentase" id="persentase" class="form-control"
                                data-parsley-pattern="^\d+(\.\d+)?$"
                                data-parsley-pattern-message="Hanya boleh angka desimal dengan titik."
                                required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jumlah_eviden">Jumlah Eviden (Output) <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_eviden" id="jumlah_eviden" class="form-control"
                                data-parsley-pattern="^\d+(\.\d+)?$"
                                data-parsley-pattern-message="Hanya boleh angka desimal dengan titik."
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan_eviden">Jenis Eviden <span class="text-danger">*</span></label>
                            <input type="text" name="keterangan_eviden" id="keterangan_eviden" class="form-control" required readonly>
                        </div>
                    </div>
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