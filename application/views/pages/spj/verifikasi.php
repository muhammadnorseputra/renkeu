<div class="page-title">
    <div class="title_left">
        <h3><i class="fa fa-check-circle mr-2"></i> Verifikasi Usul SPJ</h3>
    </div>
</div>

<div class="clearfix"></div>

<div class="x_panel">
    <!-- <div class="x_title">
        <h2>SPJ (Surat Pertanggung Jawaban)</h2>
        <div class="clearfix"></div>
    </div> -->
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>
                    Bidang / Bagian
                </td>
                <td rowspan="1">

                </td>
                <td>
                    <?= $detail->nama_part ?>
                </td>
            </tr>
            <tr>
                <td>
                    Program
                </td>
                <td class="text-right">
                    <?= $detail->kode_program ?>
                </td>
                <td>
                    <?= $detail->nama_program ?>
                </td>
            </tr>
            <tr>
                <td>
                    Kegiatan
                </td>
                <td class="text-right">
                    <?= $detail->kode_kegiatan ?>
                </td>
                <td>
                    <?= strtoupper($detail->nama_kegiatan) ?>
                </td>
            </tr>
            <tr>
                <td>
                    Sub Kegiatan
                </td>
                <td class="text-right">
                    <?= $detail->kode_sub_kegiatan ?>
                </td>
                <td>
                    <?= strtoupper($detail->nama_sub_kegiatan) ?>
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
                    <?= $detail->koderek ?>
                </td>
            </tr>
            <tr>
                <td>
                    SPJ Bulan
                </td>
                <td colspan="2">
                    <?= $detail->periode ?> / <?= $detail->tahun ?>
                </td>
            </tr>
            <tr>
                <td>
                    Keterangan
                </td>
                <td colspan="2">
                    <?= $detail->uraian ?>
                </td>
            </tr>
            <tr>
                <td>
                    Jumlah
                </td>
                <td colspan="2">
                    <h5>Rp. <b><?= nominal($detail->jumlah) ?></b></h5>
                </td>
            </tr>
            <tr>
                <td>
                    Berkas (Link)
                </td>
                <td class="text-center">
                    <a href="<?= $detail->berkas_link ?>" target="_blank" title="Open Link"><i class="fa fa-link"></i> Open</a>
                </td>
                <td>
                    <?= $detail->berkas_link ?> <br>
                </td>
            </tr>
            <tr>
                <td class="bg-light text-dark text-center" colspan="3">Detail Pengguna</td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            $userusul = $this->users->profile_username($detail->entri_by)->row();
                            ?>
                            Dientri oleh : <?= $userusul->nama ?> (<?= strtoupper($detail->entri_by) ?>) <br>
                            Tanggal / Jam : <?= longdate_indo(substr($detail->entri_at, 0, 10)) ?> / <?= substr($detail->entri_at, 10, 6) ?> WITA
                        </div>
                        <?php
                        if ($this->session->userdata('role') === 'ADMIN'):
                        ?>
                            <?php
                            $userverfikasi = $this->users->profile_username($detail->verify_by)->row();
                            ?>
                            Diverify oleh : <?= $userverfikasi->nama ?> (<?= strtoupper($detail->verify_by) ?>) <br>
                            Tanggal / Jam : <?= longdate_indo(substr($detail->verify_at, 0, 10)) ?> / <?= substr($detail->verify_at, 10, 6) ?> WITA
                            <div class="col-md-6">

                            </div>
                        <?php endif; ?>
                    </div>

                </td>
            </tr>
            <tr>
                <td class="bg-light text-dark text-center" colspan="3">Perbaikan Usulan</td>
            </tr>
            <tr>
                <td class="text-center" valign="middle">Perbaikan <br> (Ubah Status Usulan)</td>
                <td colspan="3">
                    <div class="row">
                        <div class="col-md-4">
                            <?=
                            form_open(base_url('app/spj/verifikasi_proses'), ['id' => 'formVerifikasi', 'class' => 'form-horizontal', 'data-parsley-validate' => '', 'data-parsley-errors-messages-disabled' => ''], ['status' => 'UBAH_STATUS', 'token' => $detail->token]);
                            ?>
                            <?php
                            if ($this->session->userdata('role') === 'VERIFICATOR' && isset($detail->catatan) && $detail->catatan_by === 'ADMIN'):
                            ?>
                                <div class="alert alert-warning rounded-0 border text-dark d-flex justify-content-start align-items-start" role="alert">
                                    <i class="fa fa-exclamation-triangle mr-2 mt-1 text-danger"></i>
                                    <div><strong>Catatan Admin : </strong> <br> <?= !empty($detail->catatan) ? $detail->catatan : '-' ?></div>
                                </div>
                            <?php
                            elseif ($this->session->userdata('role') === 'VERIFICATOR' && isset($detail->catatan) && $detail->catatan_by === 'VERIFICATOR'):
                            ?>
                                <div class="alert alert-warning rounded-0 border text-dark d-flex justify-content-start align-items-start" role="alert">
                                    <i class="fa fa-exclamation-triangle mr-2 mt-1 text-danger"></i>
                                    <div><strong>Catatan Verifikator : </strong> <br> <?= !empty($detail->catatan) ? $detail->catatan : '-' ?></div>
                                </div>
                            <?php
                            endif;
                            ?>
                            <div class="form-group">
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">Pilih Status</option>
                                    <?php if ($this->session->userdata('role') === 'VERIFICATOR'): ?>
                                        <option value="ENTRI" <?= $detail->is_status == 'ENTRI' ? 'selected' : '' ?>>ENTRI</option>
                                    <?php endif; ?>
                                    <?php if ($this->session->userdata('role') === 'ADMIN'): ?>
                                        <option value="VERIFIKASI" <?= $detail->is_status == 'VERIFIKASI' ? 'selected' : '' ?>>VERIFIKASI</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="catatan"><b>Keterangan Perbaikan :</b></label>
                                <textarea name="catatan" id="catatan" cols="5" rows="3" class="form-control" placeholder="Masukan keterangan perbaikan" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-0"><i class="fa fa-save mr-2"></i> Proses</button>
                            <?=
                            form_close();
                            ?>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="bg-light text-dark text-center" colspan="3">Proses Usulan</td>
            </tr>
            <tr class="bg-light">
                <td class="text-center" valign="middle">MS <br> (Memenuhi Syarat)</td>
                <td colspan="2">
                    <?=
                    form_open(base_url('app/spj/verifikasi_proses'), ['id' => 'formVerifikasi', 'class' => 'form-horizontal', 'data-parsley-validate' => '', 'data-parsley-errors-messages-disabled' => ''], ['status' => 'MS', 'token' => $detail->token]);

                    $disabled_tms = $this->session->userdata('role') !== 'VERIFICATOR' ? 'disabled' : '';
                    ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nomor"><b>Nomor Buku :</b></label>
                                <input type="text" name="nomor" class="form-control" value="<?= $detail->nomor_pembukuan ?>" required="required" <?= $disabled_tms ?>>
                            </div>
                            <label for="tanggal"><b>Tanggal Buku :</b></label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </span>
                                <input type="text" name="tanggal" class="form-control date" id="tanggal" value="<?= $detail->tanggal_pembukuan ?>" required="required" <?= $disabled_tms ?>>
                            </div>
                            <div class="form-group">
                                <label for="is_realisasi"><b>Status Realisasi :</b></label>
                                <select name="is_realisasi" id="is_realisasi" class="form-control rounded-0" required <?= $disabled_tms ?>>
                                    <option value="">Status Realisasi</option>
                                    <option value="LS" <?= $detail->is_realisasi === 'LS' ? 'selected' : '' ?>>LS</option>
                                    <option value="UP" <?= $detail->is_realisasi === 'UP' ? 'selected' : '' ?>>UP</option>
                                    <option value="GU" <?= $detail->is_realisasi === 'GU' ? 'selected' : '' ?>>GU</option>
                                    <option value="TU" <?= $detail->is_realisasi === 'TU' ? 'selected' : '' ?>>TU</option>
                                </select>
                            </div>
                            <button class="btn btn-secondary rounded-0" type="button" onclick="window.location.href='<?= base_url('app/spj?tab=%23verifikasi') ?>'">Batal</button>
                            <button type="submit" class="btn btn-success rounded-0 pull-right" <?= $disabled_tms ?>><i class="fa fa-save mr-2"></i> Proses</button>
                        </div>
                    </div>

                    <?=
                    form_close();
                    ?>
                </td>
            </tr>
            <tr>
                <td class="text-center" valign="middle">TMS <br> (Tidak Memenuhi Syarat)</td>
                <td colspan="2">
                    <?=
                    form_open(base_url('app/spj/verifikasi_proses'), ['id' => 'formVerifikasi', 'class' => 'form-horizontal', 'data-parsley-validate' => '', 'data-parsley-errors-messages-disabled' => ''], ['status' => 'TMS', 'token' => $detail->token]);
                    ?>
                    <?php
                    $tms_keterangan = $detail->is_status == 'TMS' ? $detail->catatan : '';
                    $disabled_tms = $this->session->userdata('role') !== 'VERIFICATOR' ? 'disabled' : '';
                    ?>
                    <textarea name="catatan" id="catatan" cols="5" rows="3" class="form-control" placeholder="Masukan keterangan TMS" <?= $disabled_tms ?>><?= $tms_keterangan ?></textarea>
                    <button class="btn btn-secondary rounded-0 mt-2" type="button" onclick="window.location.href='<?= base_url('app/spj?tab=%23verifikasi') ?>'">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-0 mt-2" <?= $disabled_tms ?>><i class="fa fa-save mr-2"></i> Proses</button>
                    <?=
                    form_close();
                    ?>
                </td>
            </tr>
            <!-- <tr>
                <td class="text-center" valign="middle">BTL <br> (Berkas Tidak Lengkap)</td>
                <td colspan="2">
                    <?=
                    form_open(base_url('app/spj/verifikasi_proses'), ['id' => 'formVerifikasi', 'class' => 'form-horizontal', 'data-parsley-validate' => '', 'data-parsley-errors-messages-disabled' => ''], ['status' => 'BTL', 'token' => $detail->token]);
                    ?>
                    <?php
                    $btl_keterangan = $detail->is_status == 'BTL' ? $detail->catatan : '';
                    ?>
                    <textarea name="catatan" id="catatan" cols="5" rows="2" class="form-control" placeholder="Masukan keterangan BTL"><?= $btl_keterangan ?></textarea>
                    <button type="submit" class="btn btn-danger rounded-0 mt-2"><i class="fa fa-save mr-2"></i> Proses</button>
                    <?=
                    form_close();
                    ?>
                </td>
            </tr> -->
        </tbody>
    </table>
</div>