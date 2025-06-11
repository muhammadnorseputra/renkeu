<?php
$periode_id = isset($_GET['periode']) ? $_GET['periode'] : $this->spj->getLastPeriode()->row()->id;
?>
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-percent mr-2"></i> Persentase Capaian Per Bulan</h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li class="d-flex justify-content-center align-items-center mr-2 "><a href="<?= base_url('app/capaian/cetak/' . $periode_id) ?>" target="_blank" class="print-link text-primary"><i class="fa fa-print"></i> Cetak</a></li>
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
            <table class="table table-bordered table-sm">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2" class="align-middle">No</th>
                        <th rowspan="2" class="align-middle sticky-col">Program/Kegiatan/Sub Kegiatan</th>
                        <th rowspan="2" class="align-middle">Indikator Kinerja</th>
                        <th colspan="2" class="align-middle">Target</th>
                        <th colspan="2" class="align-middle">Realisasi</th>
                        <th colspan="2">Persentase Capaian %</th>
                        <th colspan="4" class="align-middle">Aksi</th>
                    </tr>
                    <tr class="text-center">
                        <th>Anggaran (Rp)</th>
                        <th>Kinerja %</th>
                        <th>Anggaran (Rp)</th>
                        <th>Kinerja %</th>
                        <th class="align-middle">Anggaran</th>
                        <th class="align-middle">Kinerja</th>
                        <th class="align-middle" width="30%">Faktor Pendorong</th>
                        <th class="align-middle" width="30%">Faktor Penghambat</th>
                        <th class="align-middle" width="30%">Tindak Lanjut</th>
                        <th class="align-middle">Input</th>
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

                                // Target Anggaran & Kinerja
                                $target_anggaran = $this->target->getAlokasiPaguProgram($program->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
                                $target_kinerja = $indikator_input_count;

                                // Realisasi
                                $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, $ip['indikator_id'])->row();
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

                                // Realisasi Anggaran & Kinerja
                                $realisasi_anggaran = $this->realisasi->getRealisasiProgram($periode_id, $program->id);
                                $realisasi_kinerja = $sum_realisasi_count;

                                // Capaian
                                @$capaian_anggaran = round(($realisasi_anggaran / $target_anggaran) * 100, 2);
                                @$capaian_kinerja = round(($realisasi_kinerja / $target_kinerja) * 100, 2);

                                // Aksi
                                $FaktorPendorong = $realisasi->faktor_pendorong === NULL ? '-' : $realisasi->faktor_pendorong;
                                $FaktorPenghambat = $realisasi->faktor_penghambat === NULL ? '-' : $realisasi->faktor_penghambat;
                                $TindakLanjut = $realisasi->tindak_lanjut === NULL ? '-' : $realisasi->tindak_lanjut;
                                $ButtonDisabled = $realisasi_anggaran === 0 || ($realisasi_kinerja === 0) ? 'disabled' : '';
                                $ButtonInput = '<button class="btn btn-danger btn-sm" onclick="InputFaktor(' . $ip['indikator_id'] . ')" ' . $ButtonDisabled . '><i class="fa fa-pencil"></i></button>';

                                $rowspan = $toEnd++;
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
                                        <td class='align-middle text-center'>" . $capaian_kinerja . " (%)</td>
                                        <td class='align-middle'>" . $FaktorPendorong . "</td>
                                        <td class='align-middle'>" . $FaktorPenghambat . "</td>
                                        <td class='align-middle'>" . $TindakLanjut . "</td>
                                        <td class='align-middle'>" . $ButtonInput . "</td>";
                                } else { //middle
                                    $tr .= "
                                    <tr class='bg-warning'>
                                        <td class='align-middle'>" . $ip['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi . "</td>
                                        <td class='align-middle'>" . $FaktorPendorong . "</td>
                                        <td class='align-middle'>" . $FaktorPenghambat . "</td>
                                        <td class='align-middle'>" . $TindakLanjut . "</td>
                                        <td class='align-middle'>" . $ButtonInput . "</td>
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
                                    $target_anggaran = $this->target->getAlokasiPaguKegiatan($kegiatan->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
                                    $target_kinerja = $indikator_input_count;

                                    // Realisasi
                                    $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, $ik['indikator_id'])->row();
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
                                    $realisasi_anggaran = $this->realisasi->getRealisasiKegiatan($periode_id, $kegiatan->id);
                                    $realisasi_kinerja = $sum_realisasi_count;

                                    // Capaian
                                    @$capaian_anggaran = round(($realisasi_anggaran / $target_anggaran) * 100, 2);
                                    @$capaian_kinerja = round(($realisasi_kinerja / $target_kinerja) * 100, 2);

                                    // Aksi
                                    $FaktorPendorong = $realisasi->faktor_pendorong === NULL ? '-' : $realisasi->faktor_pendorong;
                                    $FaktorPenghambat = $realisasi->faktor_penghambat === NULL ? '-' : $realisasi->faktor_penghambat;
                                    $TindakLanjut = $realisasi->tindak_lanjut === NULL ? '-' : $realisasi->tindak_lanjut;
                                    $ButtonDisabled = $realisasi_anggaran === 0 || ($realisasi_kinerja === 0) ? 'disabled' : '';
                                    $ButtonInput = '<button class="btn btn-danger btn-sm" onclick="InputFaktor(' . $ik['indikator_id'] . ')" ' . $ButtonDisabled . '><i class="fa fa-pencil"></i></button>';

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
                                        <td class='align-middle text-center'>" . $capaian_kinerja . " (%)</td>
                                        <td class='align-middle'>" . $FaktorPendorong . "</td>
                                        <td class='align-middle'>" . $FaktorPenghambat . "</td>
                                        <td class='align-middle'>" . $TindakLanjut . "</td>
                                        <td class='align-middle'>" . $ButtonInput . "</td>";
                                    } else { //middle
                                        $tr .= "
                                    <tr class='bg-primary text-white'>
                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi_view . "</td>
                                        <td class='align-middle text-center'>" . $capaian_kinerja . " (%)</td>
                                        <td class='align-middle'>" . $FaktorPendorong . "</td>
                                        <td class='align-middle'>" . $FaktorPenghambat . "</td>
                                        <td class='align-middle'>" . $TindakLanjut . "</td>
                                        <td class='align-middle'>" . $ButtonInput . "</td>
                                    </tr>";
                                    }
                                endforeach;
                            endif;
                        ?>
                            <tr class="bg-primary text-white">
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

                                        $target_anggaran = $this->target->getAlokasiPaguSubKegiatan($sub_kegiatan->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal;
                                        $target_kinerja = $indikator_input_count;

                                        // Realisasi
                                        $realisasi = $this->realisasi->getRealisasiByIndikatorId($periode_id, $isk['indikator_id'])->row();
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

                                        $realisasi_anggaran = $this->realisasi->getRealisasiSubKegiatan($periode_id, $sub_kegiatan->id);
                                        $realisasi_kinerja = $sum_realisasi_count;

                                        // Capaian
                                        @$capaian_anggaran = round(($realisasi_anggaran / $target_anggaran) * 100, 2);
                                        @$capaian_kinerja = round(($realisasi_kinerja / $target_kinerja) * 100, 2);

                                        // Aksi
                                        $FaktorPendorong = $realisasi->faktor_pendorong === NULL ? '-' : $realisasi->faktor_pendorong;
                                        $FaktorPenghambat = $realisasi->faktor_penghambat === NULL ? '-' : $realisasi->faktor_penghambat;
                                        $TindakLanjut = $realisasi->tindak_lanjut === NULL ? '-' : $realisasi->tindak_lanjut;
                                        $ButtonDisabled = $realisasi_anggaran === 0 || ($realisasi_kinerja === 0) ? 'disabled' : '';
                                        $ButtonInput = '<button class="btn btn-danger btn-sm" onclick="InputFaktor(' . $isk['indikator_id'] . ')" ' . $ButtonDisabled . '><i class="fa fa-pencil"></i></button>';

                                        $rowspan = $toEnd++;
                                        if (0 === --$toEnd) { //last
                                            $tr .= "";
                                        } elseif ($key === 0) { //first
                                            $tr .= "
                                        <td class='align-middle text-nowrap'>" . $isk['nama'] . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($target_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . nominal($realisasi_anggaran) . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi_view . "</td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . $capaian_anggaran . " (%)</td>
                                        <td class='align-middle'>" . $capaian_kinerja . " (%)</td>
                                        <td class='align-middle'>" . $FaktorPendorong . "</td>
                                        <td class='align-middle'>" . $FaktorPenghambat . "</td>
                                        <td class='align-middle'>" . $TindakLanjut . "</td>
                                        <td class='align-middle'>" . $ButtonInput . "</td>";
                                        } else { //middle
                                            $tr .= "
                                    <tr>
                                        <td class='align-middle text-nowrap'>" . $isk['nama'] . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input_view . "</td>
                                        <td class='align-middle text-center'>" . $sum_realisasi_view . "</td>
                                        <td class='align-middle'>" . $capaian_kinerja . " (%)</td>
                                        <td class='align-middle'>" . $FaktorPendorong . "</td>
                                        <td class='align-middle'>" . $FaktorPenghambat . "</td>
                                        <td class='align-middle'>" . $TindakLanjut . "</td>
                                        <td class='align-middle'>" . $ButtonInput . "</td>
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
    </div>
</div>

<!-- Modal Faktor -->
<div class="modal fade modal-faktor" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url("app/realisasi/input_faktor"), ['id' => 'formFaktor', 'data-parsley-validate' => '']); ?>
        <input type="hidden" name="id">
        <input type="hidden" name="periode" value="<?= $periode_id ?>">
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Input Faktor</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="faktor_pendorong">Faktor Pendorong <span class="text-danger">*</span></label>
                    <textarea name="faktor_pendorong" id="faktor_pendorong" cols="30" rows="5" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="faktor_penghambat">Faktor Penghambat <span class="text-danger">*</span></label>
                    <textarea name="faktor_penghambat" id="faktor_penghambat" cols="30" rows="5" class="form-control" required></textarea>
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