<?php
$periode_id = isset($_GET['periode']) ? $_GET['periode'] : $this->spj->getLastPeriode()->row()->id;
?>
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-dollar mr-2"></i> Target Anggaran & Kinerja</h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li class="d-flex justify-content-center align-items-center mr-2"><a
                    href="<?= base_url('app/target/cetak/' . $this->session->userdata('tahun_anggaran')) ?>"
                    class="print-link text-primary" target="_blank"><i class="fa fa-print"></i> Cetak</a></li>
            <li class="d-flex justify-content-center align-items-center mr-2"><a
                    href="<?= base_url('app/export/target/' . $this->session->userdata('tahun_anggaran')) ?>"
                    class="print-link text-info"><i class="fa fa-download"></i> Export</a></li>
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
        <table class="table table-bordered table-responsive-md" id="tableTarget">
            <thead class="bg-light top-0" style="position: sticky; top: 0; z-index: 1;">
                <tr class="text-center">
                    <th rowspan="2" class="align-middle">Kode</th>
                    <th rowspan="2" class="align-middle">Tujuan & Sasaran</th>
                    <th rowspan="3" class="align-middle sticky-col">Program/Kegiatan/Sub Kegiatan</th>
                    <th rowspan="2" class="align-middle">Indikator Kinerja</th>
                    <th colspan="2">Target</th>
                    <th colspan="2">Aksi</th>
                </tr>
                <tr class="text-center">
                    <th>Anggaran (Rp)</th>
                    <th class="align-middle">Kinerja</th>
                    <th class="align-middle">U</th>
                    <th class="align-middle">H</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no_level_0 = "#";
                $tujuan = $this->target->getTujuan(['t.tahun' => $this->session->userdata('tahun_anggaran')]);
                foreach ($tujuan->result() as $t) :
                    $indikator_tujuan = $this->target->getIndikator(['i.fid_tujuan' => $t->id, 'i.fid_periode' => $periode_id], null);
                    $tr = "";
                    $rowspan = "";
                    if ($indikator_tujuan->num_rows() > 0):
                        $indikator = $indikator_tujuan->result_array();
                        $toEndTujuan = count($indikator);
                        foreach ($indikator as $key => $r) :
                            // Action Edit dan Hapus
                            if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN') :
                                $button_hapus = '<button class="btn btn-danger btn-sm m-0" id="HapusIndikator" data-id="' . $r['indikator_id'] . '" data-label="Tujuan" type="button"><i class="fa fa-trash"></i></button>';
                                $button_ubah = '<button class="btn btn-info btn-sm m-0" onclick="window.location.replace(\'' . base_url("app/target/ubah/" . $r['indikator_id'] . "/ref_tujuan/" . $periode_id) . '\')" type="button"><i class="fa fa-pencil"></i></button>';
                            else :
                                $button_hapus = '';
                                $button_ubah = '';
                            endif;

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
                                            <td class='align-middle'>" . $r['nama'] . "</td>
                                            <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . @nominal($this->target->getAlokasiPaguTujuan($t->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal) . "</td>
                                            <td class='align-middle text-center'>" . $indikator_input . "</td>
                                            <td class='align-middle text-center'>" . $button_ubah . "</td>
                                            <td class='align-middle text-center'>" . $button_hapus . "</td>";
                            } else { //middle
                                $tr .= "
                                            <tr class='bg-warning'>
                                                <td class='align-middle'>" . $r['nama'] . "</td>
                                                <td class='align-middle text-center'>" . $indikator_input . "</td>
                                                <td class='align-middle text-center'>" . $button_ubah . "</td>
                                                <td class='align-middle text-center'>" . $button_hapus . "</td>
                                            </tr>";
                            }
                        endforeach;
                    else:
                        $tr .= "<td colspan='5' rowspan='" . $rowspan . "'></td>
                                        <tr></tr>";
                    endif;
                ?>
                    <tr class="bg-warning text-dark">
                        <td class="text-center" rowspan="<?= @$toEndTujuan ?>"><?= $no_level_0++ ?></td>
                        <td class="text-left" colspan="2" rowspan="<?= @$toEndTujuan ?>"><?= $t->nama; ?></td>
                        <?= $tr; ?>
                    </tr>
                    <?php
                    $no_level_0_1 = "#1";
                    $sasaran = $this->target->getSasaran(['fid_tujuan' => $t->id, 't.tahun' => $this->session->userdata('tahun_anggaran')]);
                    foreach ($sasaran->result() as $s) :
                        $indikator_sasaran = $this->target->getIndikator(['i.fid_sasaran' => $s->id, 'i.fid_periode' => $periode_id], null);
                        $tr = "";
                        $rowspan = "";
                        if ($indikator_sasaran->num_rows() > 0):
                            $indikator = $indikator_sasaran->result_array();
                            $toEndSasaran = count($indikator);
                            foreach ($indikator as $key => $rs) :
                                // Action Edit dan Hapus
                                if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN') :
                                    $button_hapus = '<button class="btn btn-danger btn-sm m-0" id="HapusIndikator" data-id="' . $rs['indikator_id'] . '" data-label="Sasaran" type="button"><i class="fa fa-trash"></i></button>';
                                    $button_ubah = '<button class="btn btn-info btn-sm m-0" onclick="window.location.replace(\'' . base_url("app/target/ubah/" . $rs['indikator_id'] . "/ref_sasaran/" . $periode_id) . '\')" type="button"><i class="fa fa-pencil"></i></button>';
                                else :
                                    $button_hapus = '';
                                    $button_ubah = '';
                                endif;

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
                                    $tr .= "
                                            <td class='align-middle'>" . $rs['nama'] . "</td>
                                            <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . @nominal($this->target->getAlokasiPaguSasaran($s->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal) . "</td>
                                            <td class='align-middle text-center'>" . $indikator_input . "</td>
                                            <td class='align-middle text-center'>" . $button_ubah . "</td>
                                            <td class='align-middle text-center'>" . $button_hapus . "</td>";
                                } else { //middle
                                    $tr .= "
                                            <tr class='bg-success'>
                                                <td class='align-middle'>" . $rs['nama'] . "</td>
                                                <td class='align-middle text-center'>" . $indikator_input . "</td>
                                                <td class='align-middle text-center'>" . $button_ubah . "</td>
                                                <td class='align-middle text-center'>" . $button_hapus . "</td>
                                            </tr>";
                                }
                            endforeach;
                        else:
                            $tr .= "<td colspan='5' rowspan='" . $rowspan . "'></td>
                                        <tr></tr>";
                        endif;
                    ?>
                        <tr class="bg-success text-white">
                            <td class="text-center" rowspan="<?= @$toEndSasaran ?>"><?= $no_level_0_1 ?></td>
                            <td class="text-left text-wrap" rowspan="<?= @$toEndSasaran ?>" colspan="2"><?= $s->nama; ?></td>
                            <?= $tr; ?>
                        </tr>
                        <?php
                        $no_level_1 = 1;
                        $programs = $this->target->program($s->id, $this->session->userdata('part'), $this->session->userdata('tahun_anggaran'));
                        foreach ($programs->result() as $program) :
                            $indikator_program = $this->target->getIndikator(['i.fid_program' => $program->id, 'i.fid_periode' => $periode_id], null);
                            $tr = "";
                            $rowspan = "";
                            if ($indikator_program->num_rows() > 0):
                                $indikator = $indikator_program->result_array();
                                $toEndProgram = count($indikator);
                                foreach ($indikator as $key => $ip) :

                                    if ($this->session->userdata('role') === 'USER') :
                                        $button_hapus = '<button class="btn btn-danger btn-sm m-0" id="HapusIndikator" data-id="' . $ip['indikator_id'] . '" data-label="Program" type="button"><i class="fa fa-trash"></i></button>';
                                        $button_ubah = '<button class="btn btn-info btn-sm m-0" onclick="window.location.replace(\'' . base_url("app/target/ubah/" . $ip['indikator_id'] . "/ref_programs/" . $periode_id) . '\')" type="button"><i class="fa fa-pencil"></i></button>';
                                    else :
                                        $button_hapus = '';
                                        $button_ubah = '';
                                    endif;

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
                                                <td class='align-middle'>" . $ip['nama'] . "</td>
                                                <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . @nominal($this->target->getAlokasiPaguProgram($program->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal) . "</td>
                                                <td class='align-middle text-center'>" . $indikator_input . "</td>
                                                <td class='align-middle text-center'>" . $button_ubah . "</td>
                                                <td class='align-middle text-center'>" . $button_hapus . "</td>";
                                    } else { //middle
                                        $tr .= "
                                                <tr class='bg-secondary text-white'>
                                                    <td class='align-middle'>" . $ip['nama'] . "</td>
                                                    <td class='align-middle text-center'>" . $indikator_input . "</td>
                                                    <td class='align-middle text-center'>" . $button_ubah . "</td>
                                                    <td class='align-middle text-center'>" . $button_hapus . "</td>
                                                </tr>";
                                    }
                                endforeach;
                            else:
                                $tr .= "<td colspan='5' rowspan='" . $rowspan . "'></td>
                                        <tr></tr>";
                            endif;
                        ?>
                            <tr class="bg-secondary text-white">
                                <td class="text-center align-middle" rowspan="<?= @$toEndProgram ?>"><?= $no_level_1 ?></td>
                                <td class="align-middle bg-success border-success" rowspan="<?= @$toEndProgram ?>"></td>
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
                                $indikator_kegiatan = $this->target->getIndikator(['i.fid_kegiatan' => $kegiatan->id, 'i.fid_periode' => $periode_id], null);
                                $tr = "";
                                $rowspan = "";
                                if ($indikator_kegiatan->num_rows() > 0):
                                    $indikator = $indikator_kegiatan->result_array();
                                    $toEndKegiatan = count($indikator);
                                    foreach ($indikator as $key => $ik) :
                                        if ($this->session->userdata('role') === 'USER') :
                                            $button_hapus = '<button class="btn btn-danger btn-sm m-0" id="HapusIndikator" data-id="' . $ik['indikator_id'] . '" data-label="Kegiatan" type="button"><i class="fa fa-trash"></i></button>';
                                            $button_ubah = '<button class="btn btn-info btn-sm m-0" onclick="window.location.replace(\'' . base_url("app/target/ubah/" . $ik['indikator_id'] . "/ref_kegiatans/" . $periode_id) . '\')" type="button"><i class="fa fa-pencil"></i></button>';
                                        else:
                                            $button_hapus = '';
                                            $button_ubah = '';
                                        endif;

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
                                                        <td class='align-middle'>" . $ik['nama'] . "</td>
                                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . @nominal($this->target->getAlokasiPaguKegiatan($kegiatan->id, $this->session->userdata('is_perubahan'), $this->session->userdata('tahun_anggaran'))->row()->total_pagu_awal) . "</td>
                                                        <td class='align-middle text-center'>" . $indikator_input . "</td>
                                                        <td class='align-middle text-center'>" . $button_ubah . "</td>
                                                        <td class='align-middle text-center'>" . $button_hapus . "</td>";
                                        } else { //middle
                                            $tr .= "
                                                        <tr class='bg-info text-white'>
                                                            <td class='align-middle'>" . $ik['nama'] . "</td>
                                                            <td class='align-middle text-center'>" . $indikator_input . "</td>
                                                            <td class='align-middle text-center'>" . $button_ubah . "</td>
                                                            <td class='align-middle text-center'>" . $button_hapus . "</td>
                                                        </tr>";
                                        }
                                    endforeach;
                                else:
                                    $tr .= "<td colspan='5' rowspan='" . $rowspan . "'></td>
                                                <tr></tr>";
                                endif;
                            ?>
                                <tr class="bg-info text-white">
                                    <td class="text-center align-middle" rowspan="<?= @$toEndKegiatan ?>"><?= $no_level_1 . "." . $no_level_2 ?>
                                    </td>
                                    <td class="align-middle bg-success border-success" rowspan="<?= @$toEndKegiatan ?>"></td>
                                    <td class="align-middle" rowspan="<?= @$toEndKegiatan ?>"><?= $kegiatan->nama ?></td>
                                    <?= $tr ?>
                                </tr>
                                <?php
                                $sub_kegiatans = $this->target->sub_kegiatans($kegiatan->id);
                                $no_level_3 = 1;
                                foreach ($sub_kegiatans->result() as $sub_kegiatan) :
                                    $indikator_sub_kegiatan = $this->target->getIndikator(['i.fid_sub_kegiatan' => $sub_kegiatan->id, 'i.fid_periode' => $periode_id], null);
                                    $tr = "";
                                    $rowspan = "";
                                    if ($indikator_sub_kegiatan->num_rows() > 0):
                                        $indikator = $indikator_sub_kegiatan->result_array();
                                        $toEndSubKegiatan = count($indikator);
                                        foreach ($indikator as $key => $isk) :
                                            if ($this->session->userdata('role') === 'USER') :
                                                $button_hapus = '<button class="btn btn-danger btn-sm m-0" id="HapusIndikator" data-id="' . $isk['indikator_id'] . '" data-label="Sub Kegiatan" type="button"><i class="fa fa-trash"></i></button>';
                                                $button_ubah = '<button class="btn btn-info btn-sm m-0" onclick="window.location.replace(\'' . base_url("app/target/ubah/" . $isk['indikator_id'] . "/ref_sub_kegiatans/" . $periode_id) . '\')" type="button"><i class="fa fa-pencil"></i></button>';
                                            else:
                                                $button_hapus = '';
                                                $button_ubah = '';
                                            endif;

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
                                        <td class='align-middle'>" . $isk['nama'] . " <i class='" . $isk['color'] . "'>(" . $isk['jenis_indikator'] . ")</i></td>
                                        <td rowspan='" . $rowspan . "' class='align-middle text-right'>" . @nominal($this->target->getAlokasiPaguSubKegiatan($sub_kegiatan->id, $this->session->userdata('is_perubahan'), $this->session->userdata("tahun_anggaran"))->row()->total_pagu_awal) . "</td>
                                        <td class='align-middle text-center'>" . $indikator_input . "</td>
                                        <td class='align-middle text-center'>" . $button_ubah . "</td>
                                        <td class='align-middle text-center'>" . $button_hapus . "</td>";
                                            } else { //middle
                                                $tr .= "
                                    <tr>
                                        <td class='align-middle'>" . $isk['nama'] . " <i class='" . $isk['color'] . "'>(" . $isk['jenis_indikator'] . ")</i></td>
                                        <td class='align-middle text-center'>" . $indikator_input . "</td>
                                        <td class='align-middle text-center'>" . $button_ubah . "</td>
                                        <td class='align-middle text-center'>" . $button_hapus . "</td>
                                    </tr>";
                                            }
                                        endforeach;
                                    else:
                                        $tr .= "<td colspan='5' rowspan='" . $rowspan . "'></td>
                                        <tr></tr>";
                                    endif;
                                ?>
                                    <tr>
                                        <td class="text-center align-middle" rowspan="<?= @$toEndSubKegiatan ?>">
                                            <?= $no_level_1 . "." . $no_level_2 . "." . $no_level_3 ?></td>

                                        <td class="align-middle bg-success border-bottom-0" rowspan="<?= @$toEndSubKegiatan ?>"></td>
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
</div>

<!-- Modal Tambah Indikator -->
<div class="modal fade modal-indikator" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url("app/target/tambah_indikator"), ['id' => 'formIndikator', 'data-parsley-validate' => '']); ?>
        <input type="hidden" name="id">
        <input type="hidden" name="ref">
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Processing ...</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama">Nama Indikator <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                <div class="form-group" id="form_indikator" style="display: none;">
                    <label for="jenis_indikator">Jenis Indikator</label>
                    <select name="jenis_indikator" id="jenis_indikator" class="form-control">
                        <option value="">Pilih Jenis Indikator</option>
                        <?php
                        foreach ($jenis_indikator as $ji) :
                        ?>
                            <option value="<?= $ji->id ?>"><?= $ji->nama ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" id="form_perubahan" style="display: none;">
                    <label for="perubahan">Target Pada Anggaran ?</label>
                    <select name="perubahan" id="perubahan" class="form-control" readonly>
                        <option value="">Pilih</option>
                        <option value="1" <?= $this->session->userdata('is_perubahan') == 1 ? 'selected' : ''; ?>>
                            Perubahan</option>
                        <option value="0" <?= $this->session->userdata('is_perubahan') == 0 ? 'selected' : ''; ?>>Murni
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="bidang">Penanggung Jawab <span class="text-danger">*</span></label>
                    <select name="bidang[]" id="bidang" multiple="multiple" required
                        data-parsley-errors-container="#help-block-bidang"></select>
                    <div id="help-block-bidang"></div>
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