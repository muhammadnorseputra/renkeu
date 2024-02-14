<div class="clearfix"></div>
<?php if (@$detail->is_status === 'VERIFIKASI'  || @$detail->is_status === 'VERIFIKASI_ADMIN') : ?>

    <div class="alert alert-warning text-dark rounded-0" role="alert">
        <strong><i class="fa fa-lock mr-2"></i> Verifikasi </strong>, Usulan SPJ kamu masih dalam tahap verifikasi.
    </div>
<?php endif; ?>
<!-- Smart Wizard -->
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-inbox mr-2"></i> Formulir Usul SPJ (Surat Pertanggung Jawaban)</h2>
        <div class="clearfix"></div>
    </div>
    <div id="wizard" class="form_wizard wizard_horizontal">
        <ul class="wizard_steps">
            <li>
                <a href="#step-1">
                    <span class="step_no">1</span>
                    <span class="step_descr">
                        Entri<br />
                        <small>Entri Data</small>
                    </span>
                </a>
            </li>
            <li>
                <a href="#step-2">
                    <span class="step_no">2</span>
                    <span class="step_descr">
                        Eviden<br />
                        <small>Upload Berkas SPJ</small>
                    </span>
                </a>
            </li>
            <li>
                <a href="#step-3">
                    <span class="step_no">3</span>
                    <span class="step_descr">
                        Verifikasi<br />
                        <small>Verifikasi Admin</small>
                    </span>
                </a>
            </li>
            <li>
                <a href="#step-4">
                    <span class="step_no">4</span>
                    <span class="step_descr">
                        Selesai<br />
                        <small>Review Usulan</small>
                    </span>
                </a>
            </li>
        </ul>
        <?php $disabled_status =  (@$detail->is_status !== 'ENTRI') && (!empty(@$detail->token)) ? 'disabled' : ''; ?>
        <div id="step-1">
            <?= form_open(base_url('app/spj/prosesusul'), ['id' => 'step-1', 'class' => 'form-horizontal form-label-left', 'data-parsley-validate' => '']); ?>
            <input type="hidden" name="token" value="<?= @$detail->token ?>">
            <input type="hidden" name="ref_part" value="<?= @$detail->fid_part ?>">
            <input type="hidden" name="ref_program" value="<?= @$detail->fid_program ?>">
            <input type="hidden" name="ref_kegiatan" value="<?= @$detail->fid_kegiatan ?>">
            <input type="hidden" name="ref_subkegiatan" value="<?= @$detail->fid_sub_kegiatan ?>">
            <input type="hidden" name="ref_uraian" value="<?= @$detail->fid_uraian ?>">
            <div class="col-md-10 center-margin">
                <div class="input-group">
                    <label for="koderek" class="row col-md-12">Kode Rekening <span class="text-danger ml-1 mr-1">*</span></label>
                    <input type="text" value="<?= @$detail->koderek ?>" readonly name="koderek" id="koderek" onclick="showModalSearchKode()" <?= $disabled_status ?> class="form-control col-md-6" required="required" data-parsley-errors-container="#help-block-koderek"> <button type="button" class="btn btn-light rounded-0 ml-1" data-toggle="modal" data-target="#modelSearchKode" <?= $disabled_status ?>><i class="fa fa-search"></i> Cari Kode</button>
                    <div id="help-block-koderek" class="row col-md-12"></div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="col-form-label label-align" for="bulan">SPJ Periode</label>
                            <select name="periode" id="periode" class="form-control rounded-0" required <?= $disabled_status ?>>
                                <?php
                                foreach ($this->spj->getPeriode()->result() as $periode) :
                                    $is_status = $periode->is_open === 'Y' ? 'OPEN' : 'CLOSE';
                                    $disabled = $periode->is_open !== 'Y' ? 'disabled' : '';
                                ?>
                                    <option value="<?= $periode->id ?>" <?= $disabled ?>><?= $periode->nama ?> (<?= $is_status ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <div id="help-block-bulan" class="row col-md-12"></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="col-form-label label-align" for="bulan">SPJ Bulan</label>
                            <select name="bulan" id="bulan" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-bulan" <?= $disabled_status ?>>
                                <option value="">Pilih Bulan</option>
                                <?php
                                $m = date('m');
                                foreach (bulanIndo() as $month => $monthName) {
                                    if (isset($detail->bulan)) {
                                        $selected = $detail->bulan == $month ? 'selected' : '';
                                    } else {
                                        $selected = date('m') == $month ? 'selected' : '';
                                    }
                                    echo '<option value="' . $month . '" ' . $selected . '>' . $monthName . '</option>';
                                }
                                ?>
                            </select>
                            <div id="help-block-bulan" class="row col-md-12"></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="col-form-label label-align" for="tahun">SPJ Tahun</label>
                            <select name="tahun" id="tahun" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-tahun" <?= $disabled_status ?>>
                                <option value="">Pilih Tahun</option>
                                <?php
                                $year = date('Y');
                                for ($i = $year; $i <= $year + 3; $i++) {
                                    if (isset($detail->tahun)) {
                                        $selected = $detail->tahun == $i ? 'selected' : '';
                                    } else {
                                        $selected = date('Y') == $i ? 'selected' : '';
                                    }
                                    echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                                }
                                ?>
                            </select>
                            <div id="help-block-tahun" class="row col-md-12"></div>
                        </div>
                    </div>
                </div>
                <?php
                $totalPaguAwal = !empty($this->target->getAlokasiPaguUraian(@$detail->fid_uraian)->row()->total_pagu_awal) ? $this->target->getAlokasiPaguUraian(@$detail->fid_uraian)->row()->total_pagu_awal : 0;
                $totalRealisasiPagu = $this->realisasi->getRealisasiTahunanUraian(@$detail->fid_uraian, 'SELESAI');
                $totalSisaPagu = ($totalPaguAwal - $totalRealisasiPagu);
                ?>
                <div class="divider-dashed"></div>
                <div class="form-group d-flex">
                    <div class="pr-5 border-right">
                        <b>Jumlah Maksimum</b>
                        <h5 id="jumlah_max"><?= nominal($totalPaguAwal) ?></h5>
                    </div>
                    <div class="pl-5">
                        <b>Sisa Anggaran</b>
                        <h5 id="sisa_max"><?= nominal($totalSisaPagu) ?></h5>
                    </div>
                </div>
                <div class="divider-dashed"></div>

                <div class="form-group">
                    <label for="jumlah" class="row col-md-12">Jumlah <span class="text-danger ml-1 mr-1">*</span></label>
                    <input type="text" value="<?= @nominal($detail->jumlah) ?>" data-start="<?= $totalPaguAwal ?>" name="jumlah" id="jumlah" class="form-control col-md-6" required data-parsley-errors-container="#help-block-jumlah" data-parsley-remote="<?= base_url('app/spj/cek_jumlah_pengajuan/' . @$detail->fid_uraian) ?>" data-parsley-remote-reverse="false" data-parsley-remote-options='{ "type": "POST" }' data-parsley-remote-message="Jumlah yang dimasukan melebihi batas maksimum." data-parsley-pattern="^(([0-9.]?)*)+$"  data-parsley-trigger="focusout" <?= $disabled_status ?>>
                    <div id="help-block-jumlah" class="row col-md-12"></div>
                </div>

                <div class="clearfix"></div>
                <div class="form-group">
                    <label class="col-form-label label-align" for="uraian">Uraian/Untuk Pembayaran/Keterangan Kwitansi <span class="text-danger">*</span></label>
                    <textarea name="uraian" id="uraian" cols="30" rows="5" class="form-control" required="required" <?= $disabled_status ?>><?= @$detail->uraian ?></textarea>
                </div>

                <button class="btn btn-danger rounded-0 pull-left mt-3" onclick="window.location.href='<?= base_url('app/spj') ?>'" type="button"><i class="fa fa-arrow-left mr-3"></i> Kembali </button>
                <?php if ((@$detail->is_status === 'ENTRI') || (empty(@$detail->token))) : ?>
                    <div class="form-group">
                        <button class="btn btn-primary rounded-0 pull-left mt-3" type="submit"><i class="fa fa-save mr-2"></i> Simpan & Lanjutkan</button>
                    </div>
                <?php else : ?>
                    <button onclick="nextStep('<?= base_url('app/spj/buatusul?step=1&token=' . @$detail->token) ?>')" class="btn btn-primary rounded-0 pull-left mt-3" type="button"> Selanjutnya <i class="fa fa-arrow-right ml-2"></i></button>
                <?php endif; ?>
            </div>
            <?= form_close() ?>

        </div>
        <div id="step-2">
            <?= form_open(base_url('app/spj/proseseviden'), ['id' => 'step-2', 'class' => 'form-horizontal form-label-left', 'data-parsley-validate' => '']); ?>
            <input type="hidden" name="token" value="<?= @$detail->token ?>">
            <div class="col-md-10 center-margin">
                <div class="alert alert-warning rounded-0" role="alert">
                    <strong>Penting :</strong> Usahakan link yang diberikan berstatus publik, dapat diakses saat pengecekan oleh verifikator.
                </div>
                <div class="form-group">
                    <label class="col-form-label label-align" for="link">Link Berkas <span class="text-danger">*</span></label>
                    <textarea name="link" id="link" cols="30" rows="3" class="form-control" required="required" <?= $disabled_status ?>><?= @$detail->berkas_link ?></textarea>
                </div>
                <button type="button" class="btn btn-primary rounded-0" onclick="window.location.replace('<?= base_url('app/spj/buatusul?step=0&status=entri&token=' . @$detail->token) ?>')"><i class="fa fa-arrow-left mr-2"></i> Sebelumnya </button>
                <?php if ((@$detail->is_status === 'ENTRI') || (empty(@$detail->token))) : ?>
                    <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i> Kirim Usulan</button>
                    <button class="btn btn-danger rounded-0 pull-right" onclick="window.location.href='<?= base_url('app/spj') ?>'" type="button"><i class="fa fa-close mr-3"></i> Batalkan </button>
                <?php else : ?>
                    <button onclick="nextStep('<?= base_url('app/spj/buatusul?step=2&token=' . @$detail->token) ?>')" class="btn btn-primary rounded-0 pull-right mt-3" type="button"> Selanjutnya <i class="fa fa-arrow-right ml-2"></i></button>
                <?php endif; ?>
            </div>
            <?= form_close() ?>
        </div>
        <div id="step-3" class="text-center">
            <?php if (@$detail->is_status === 'VERIFIKASI' || @$detail->is_status === 'VERIFIKASI_ADMIN') : ?>
                <div class="container">
                    <div class="col-md-12">
                        <img src="<?= base_url('template/assets/icon/verifikasi.svg') ?>" alt="Verifikasi Admin" width="50%">
                        <h2 class="StepTitle">Usulan Dalam Proses Verifikasi <i class="fa fa-lock text-success ml-2"></i></h2>
                        <button class="btn btn-warning rounded-0" onclick="window.location.href='<?= base_url('app/spj') ?>'">Kembali <i class="fa fa-arrow-right ml-3"></i> </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div id="step-4">
            <?php if (@$detail->is_status === 'TMS' || @$detail->is_status === 'BTL' || @$detail->is_status === 'SELESAI' || @$detail->is_status === 'SELESAI_TMS' || @$detail->is_status === 'SELESAI_BTL') : ?>
                <div class="col-md-10 center-margin">
                    <?php
                    $spj = $this->spj->detail(['token' => @$detail->token])->row();
                    ?>
                    <?php if (@$spj->is_status === 'APPROVE') : ?>
                        <div class="alert alert-success rounded-0" role="alert">
                            <strong><i class="fa fa-check-circle mr-2"></i> Selamat</strong>, Usulan SPJ dengan kode rekening "<?= $spj->koderek ?>" telah disetujui.
                        </div>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>
                                        Bidang / Bagian
                                    </td>
                                    <td rowspan="2">

                                    </td>
                                    <td>
                                        <?= $spj->nama_part ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Program
                                    </td>
                                    <td>
                                        <?= $spj->nama_program ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Kegiatan
                                    </td>
                                    <td class="text-right">
                                        <?= $spj->kode_kegiatan ?>
                                    </td>
                                    <td>
                                        <?= strtoupper($spj->nama_kegiatan) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Sub Kegiatan
                                    </td>
                                    <td class="text-right">
                                        <?= $spj->kode_sub_kegiatan ?>
                                    </td>
                                    <td>
                                        <?= strtoupper($spj->nama_sub_kegiatan) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-dark text-center" colspan="3">Detail SPJ (Surat Pertanggung Jawaban)</td>
                                </tr>
                                <tr>
                                    <td>
                                        Kode Rekening
                                    </td>
                                    <td colspan="2">
                                        <?= $spj->koderek ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        SPJ Bulan
                                    </td>
                                    <td colspan="2">
                                        <?= bulan($spj->bulan) ?> / <?= $spj->tahun ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Uraian
                                    </td>
                                    <td colspan="2">
                                        <?= $spj->uraian ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Jumlah
                                    </td>
                                    <td colspan="2">
                                        <h5>Rp. <b><?= nominal($spj->jumlah) ?></b></h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Berkas (Link)
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= $spj->berkas_link ?>" target="_blank" title="Open Link"><i class="fa fa-link"></i> Open</a>
                                    </td>
                                    <td>
                                        <?= $spj->berkas_link ?> <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-dark text-center" colspan="3">Detail Verificator</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <?php
                                        $userusul = $this->users->profile_username($spj->approve_by)->row();
                                        ?>
                                        Diapprove oleh : <?= $userusul->nama ?> (<?= strtoupper($spj->approve_by) ?>) <br>
                                        Tanggal / Jam : <?= longdate_indo(substr($spj->approve_at, 0, 10)) ?> / <?= substr($spj->approve_at, 10, 6) ?> WITA
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <div class="alert alert-danger rounded-0" role="alert">
                            <strong><i class="fa fa-close mr-2"></i> Mohon Maaf</strong>, Usulan SPJ dengan kode rekening "<?= $spj->koderek ?>" (<?= $spj->is_status ?>).
                        </div>
                        <div class="alert alert-light rounded-0 border" role="alert">
                            <strong>Alasan (<?= $spj->is_status ?>) : </strong> <br> <?= !empty($spj->catatan) ? $spj->catatan : '-' ?>
                        </div>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="bg-light text-dark text-center" colspan="3">Detail SPJ (Surat Pertanggung Jawaban)</td>
                                </tr>
                                <tr>
                                    <td>
                                        Kode Rekening
                                    </td>
                                    <td colspan="2">
                                        <?= $spj->koderek ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        SPJ Bulan
                                    </td>
                                    <td colspan="2">
                                        <?= bulan($spj->bulan) ?> / <?= $spj->tahun ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Uraian
                                    </td>
                                    <td colspan="2">
                                        <?= $spj->uraian ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Jumlah
                                    </td>
                                    <td colspan="2">
                                        <h5>Rp. <b><?= nominal($spj->jumlah) ?></b></h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Berkas (Link)
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= $spj->berkas_link ?>" target="_blank" title="Open Link"><i class="fa fa-link"></i> Open</a>
                                    </td>
                                    <td>
                                        <?= $spj->berkas_link ?> <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-dark text-center" colspan="3">Detail Verificator</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <?php if ($spj->is_status === 'BTL' || $spj->is_status === 'TMS' || $spj->is_status === 'SELESAI_TMS' || $spj->is_status === 'SELESAI_BTL') : ?>
                                            <?php
                                            $userusul = $this->users->profile_username($spj->verify_by)->row();
                                            ?>
                                            Diverifikasi oleh : <?= $userusul->nama ?> (<?= strtoupper($spj->verify_by) ?>) <br>
                                            Tanggal / Jam : <?= longdate_indo(substr($spj->verify_at, 0, 10)) ?> / <?= substr($spj->verify_at, 10, 6) ?> WITA
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-danger rounded-0 pull-left mt-3" onclick="window.location.href='<?= base_url('app/spj') ?>'" type="button"><i class="fa fa-arrow-left mr-3"></i> Kembali </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- End SmartWizard Content -->

<style>
    .actionBar {
        display: none;
    }
</style>

<!-- The Modal -->
<div class="modal" id="modelSearchKode" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-0">

            <form class="form-horizontal" action="<?= base_url('app/spj/carikode') ?>" method="post" id="formCariKode" data-parsley-validate>
                <!-- Modal Header -->
                <div class="modal-header bg-success text-white rounded-0">
                    <h4 class="modal-title">Cari Kode</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="part">Pilih Bidang <span class="text-danger">*</span></label>
                        <select name="part" id="part" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-part">
                            <option value="">Pilih Bidang</option>
                            <?php
                            foreach ($list_bidang as $bid) :
                                $selected = $detail->fid_part === $bid->id ? 'selected' : '';
                                $disabled = $this->session->userdata('part') !== $bid->id ? 'disabled' : '';
                                echo '<option value="' . $bid->id . '" ' . $selected . ' ' . $disabled . '>' . $bid->nama . '</option>';
                            endforeach;
                            ?>
                        </select>
                        <div id="help-block-part"></div>
                    </div>
                    <div class="form-group">
                        <label for="program">Pilih Program <span class="text-danger">*</span></label>
                        <select name="program" id="program" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-program">
                            <option value="">Pilih Program</option>
                            <?php
                            foreach ($list_program as $program) :
                                $selected = $detail->fid_program == $program->id ? 'selected' : '';
                                echo '<option value="' . $program->id . '" ' . $selected . '>' . $program->kode . ' - ' . $program->nama . '</option>';
                            endforeach;
                            ?>
                        </select>
                        <div id="help-block-program"></div>
                    </div>
                    <div class="form-group">
                        <label for="kegiatan">Pilih Kegiatan <span class="text-danger">*</span></label>
                        <select name="kegiatan" id="kegiatan" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-kegiatan">
                            <option value="">Pilih Kegiatan</option>
                        </select>
                        <div id="help-block-kegiatan"></div>
                    </div>
                    <div class="form-group">
                        <label for="sub_kegiatan">Pilih Sub Kegiatan <span class="text-danger">*</span></label>
                        <select name="sub_kegiatan" id="sub_kegiatan" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-sub-kegiatan">
                            <option value="">Pilih Sub Kegiatan</option>
                        </select>
                        <div id="help-block-sub-kegiatan"></div>
                    </div>
                    <div class="form-group">
                        <label for="uraian_kegiatan">Pilih Uraian Kegiatan <span class="text-danger">*</span></label>
                        <select name="uraian_kegiatan" id="uraian_kegiatan" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-uraian-kegiatan">
                            <option value="">Pilih Uraian Kegiatan</option>
                        </select>
                        <div id="help-block-uraian-kegiatan"></div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success rounded-0">Pilih</button>
                </div>

            </form>
        </div>
    </div>
</div>