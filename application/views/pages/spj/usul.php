<div class="clearfix"></div>

<div class="usl-wrap">
<?php if (@$detail->is_status === 'VERIFIKASI' || @$detail->is_status === 'VERIFIKASI_ADMIN'): ?>
    <div class="alert alert-warning text-dark rounded-0" role="alert">
        <strong><i class="fa fa-lock mr-2"></i> Verifikasi </strong>, Usulan SPJ kamu dalam tahap verifikasi.
    </div>
<?php endif; ?>
<?php if (! empty($detail->catatan) && @$detail->is_status === 'ENTRI'): ?>
    <div class="alert alert-warning rounded-0 border text-dark d-flex justify-content-start align-items-start" role="alert">
        <i class="fa fa-exclamation-triangle mr-2 mt-1 text-danger"></i>
        <div><strong>Catatan Verifikator : </strong> <br> <?php echo $detail->catatan ?></div>
    </div>
<?php endif; ?>
<div class="usl-head">
            <div class="usl-head-main">
                <span class="usl-head-ico" aria-hidden="true"><i class="fa fa-file-text-o"></i></span>
                <div class="usl-head-txt">
                    <h2>Formulir Usul SPJ</h2>
                    <p>Surat Pertanggung Jawaban &mdash; lengkapi 5 langkah berikut</p>
                </div>
            </div>
            <div class="usl-head-side">
                <span class="usl-badge usl-badge--<?php echo (@$detail->is_status === 'ENTRI' || empty(@$detail->token)) ? 'edit' : 'lock' ?>">
                    <i class="fa <?php echo (@$detail->is_status === 'ENTRI' || empty(@$detail->token)) ? 'fa-pencil' : 'fa-lock' ?>" aria-hidden="true"></i>
                    <?php echo (@$detail->is_status ?: 'ENTRI') ?>
                </span>
                <span class="usl-head-step">Langkah <b id="usl-step-no">1</b>/5 &middot; <span id="usl-step-title">Entri Data</span></span>
            </div>
        </div>
        <div class="usl-progress" role="progressbar" aria-label="Kemajuan pengisian">
            <span class="usl-progress-bar" id="usl-progress-bar"></span>
        </div>
        <div id="wizard" class="form_wizard wizard_horizontal">
        <ul class="wizard_steps">
            <li>
                <a href="#step-1" data-title="Entri Data">
                    <span class="step_no">1</span>
                    <span class="step_descr">Entri Data<small>Kode &amp; nominal</small></span>
                </a>
            </li>
            <li>
                <a href="#step-2" data-title="Relasi Publik">
                    <span class="step_no">2</span>
                    <span class="step_descr">Relasi Publik<small>Penerima manfaat</small></span>
                </a>
            </li>
            <li>
                <a href="#step-3" data-title="Eviden">
                    <span class="step_no">3</span>
                    <span class="step_descr">Eviden<small>Unggah berkas SPJ</small></span>
                </a>
            </li>
            <li>
                <a href="#step-4" data-title="Review">
                    <span class="step_no">4</span>
                    <span class="step_descr">Review<small>Review usulan</small></span>
                </a>
            </li>
            <li>
                <a href="#step-5" data-title="Final">
                    <span class="step_no">5</span>
                    <span class="step_descr">Final<small>Verifikasi</small></span>
                </a>
            </li>
        </ul>
        <?php $disabled_status = (@$detail->is_status !== 'ENTRI') && (! empty(@$detail->token)) ? 'disabled' : ''; ?>
        <div id="step-1">
            <?php echo form_open(base_url('app/spj/prosesusul'), ['id' => 'form-step-1', 'class' => 'form-horizontal form-label-left', 'data-parsley-validate' => '']); ?>
            <input type="hidden" name="token" value="<?php echo @$detail->token ?>">
            <input type="hidden" name="ref_part" value="<?php echo @$detail->fid_part ?>">
            <input type="hidden" name="ref_program" value="<?php echo @$detail->fid_program ?>">
            <input type="hidden" name="ref_kegiatan" value="<?php echo @$detail->fid_kegiatan ?>">
            <input type="hidden" name="ref_subkegiatan" value="<?php echo @$detail->fid_sub_kegiatan ?>">
            <input type="hidden" name="ref_uraian" value="<?php echo @$detail->fid_uraian ?>">
            <div class="usl-scroll">
            <div class="usl-card">
                <div class="usl-card-hd"><i class="fa fa-file-text-o" aria-hidden="true"></i> Kode Uraian Kegiatan</div>
                <div class="usl-card-bd">
                    <div class="usl-f usl-f--kode">
                        <label for="uraian_kegiatan" class="usl-lbl">Kode Rekening <span class="text-danger">*</span></label>
                        <select name="uraian_kegiatan" id="uraian_kegiatan" class="form-control rounded-0" required="required" <?php echo $disabled_status ?>
                            data-parsley-errors-container="#help-block-uraian-kegiatan"
                            data-parsley-required-message="Kode / uraian wajib dipilih">
                            <option value="">Ketik kode atau nama uraian ...</option>
                            <?php if (! empty($detail->fid_uraian) && ! empty($detail->fid_program) && ! empty($detail->fid_kegiatan) && ! empty($detail->fid_sub_kegiatan)): ?>
                                <option value="<?php echo $detail->fid_uraian ?>" selected><?php echo (@$detail->fid_part ? $this->spj->getNama('ref_parts', $detail->fid_part) . ' › ' : '') ?><?php echo $this->spj->getNama('ref_programs', $detail->fid_program) ?> › <?php echo $this->spj->getNama('ref_kegiatans', $detail->fid_kegiatan) ?> › <?php echo $this->spj->getNama('ref_sub_kegiatans', $detail->fid_sub_kegiatan) ?> › <?php echo $this->spj->getNama('ref_uraians', $detail->fid_uraian) ?></option>
                            <?php endif; ?>
                        </select>
                        <div id="help-block-uraian-kegiatan" class="row col-md-12"></div>
                        <small class="form-text text-muted usl-hint">Hierarki: Bidang › Program › Kegiatan › Sub Kegiatan › Uraian</small>
                        <div id="loadKegiatan" class="usl-hier<?php echo empty($detail->fid_kegiatan) ? ' usl-hier--empty' : '' ?>">
                            <?php if (! empty($detail->fid_kegiatan)): ?>
                                <div class="usl-hier-item"><i class="fa fa-file-code-o" aria-hidden="true"></i><span><b><?php echo @$this->spj->getKode('ref_kegiatans', $detail->fid_kegiatan) ?></b> <button type="button" class="btn btn-xs btn-link p-0 ml-1 copy-text" data-copy="<?php echo htmlspecialchars((string) @$this->spj->getKode('ref_kegiatans', $detail->fid_kegiatan), ENT_QUOTES) ?>" title="Copy kode"><i class="fa fa-copy"></i></button> &middot; <?php echo @$this->spj->getNama('ref_kegiatans', $detail->fid_kegiatan) ?> <button type="button" class="btn btn-xs btn-link p-0 ml-1 copy-text" data-copy="<?php echo htmlspecialchars((string) @$this->spj->getNama('ref_kegiatans', $detail->fid_kegiatan), ENT_QUOTES) ?>" title="Copy nama"><i class="fa fa-copy"></i></button></span></div>
                                <div class="usl-hier-item"><i class="fa fa-file-code-o" aria-hidden="true"></i><span><b><?php echo @$this->spj->getKode('ref_sub_kegiatans', $detail->fid_sub_kegiatan) ?></b> <button type="button" class="btn btn-xs btn-link p-0 ml-1 copy-text" data-copy="<?php echo htmlspecialchars((string) @$this->spj->getKode('ref_sub_kegiatans', $detail->fid_sub_kegiatan), ENT_QUOTES) ?>" title="Copy kode"><i class="fa fa-copy"></i></button> &middot; <?php echo @$this->spj->getNama('ref_sub_kegiatans', $detail->fid_sub_kegiatan) ?> <button type="button" class="btn btn-xs btn-link p-0 ml-1 copy-text" data-copy="<?php echo htmlspecialchars((string) @$this->spj->getNama('ref_sub_kegiatans', $detail->fid_sub_kegiatan), ENT_QUOTES) ?>" title="Copy nama"><i class="fa fa-copy"></i></button></span></div>
                                <div class="usl-hier-item usl-hier-item--uraian"><i class="fa fa-check-circle" aria-hidden="true"></i><span><b><?php echo @$this->spj->getKode('ref_uraians', $detail->fid_uraian) ?></b> <button type="button" class="btn btn-xs btn-link p-0 ml-1 copy-text" data-copy="<?php echo htmlspecialchars((string) @$this->spj->getKode('ref_uraians', $detail->fid_uraian), ENT_QUOTES) ?>" title="Copy kode"><i class="fa fa-copy"></i></button> &middot; <?php echo @$this->spj->getNama('ref_uraians', $detail->fid_uraian) ?> <button type="button" class="btn btn-xs btn-link p-0 ml-1 copy-text" data-copy="<?php echo htmlspecialchars((string) @$this->spj->getNama('ref_uraians', $detail->fid_uraian), ENT_QUOTES) ?>" title="Copy nama"><i class="fa fa-copy"></i></button></span></div>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" name="koderek" id="koderek" value="<?php echo @$detail->koderek ?>">
                    </div>
                </div>
            </div>

            <div class="usl-card">
                <div class="usl-card-hd"><i class="fa fa-calendar" aria-hidden="true"></i> Periode &amp; Statistik Pagu</div>
                <div class="usl-card-bd">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="usl-f">
                                <label class="usl-lbl" for="periode">SPJ Periode <span class="text-danger">*</span></label>
                            <select name="periode" id="periode" class="form-control rounded-0" required <?php echo $disabled_status; ?>
                                data-parsley-errors-container="#help-block-bulan">
                                <option value="">-- Pilih Periode --</option>
                                <?php
                                    foreach ($this->spj->getPeriode()->result() as $periode):
                                        $is_status = $periode->is_open === 'Y' ? 'OPEN' : 'CLOSE';
                                        $disabled  = $periode->is_open !== 'Y' ? 'disabled' : '';
                                        $selected  = (isset($detail->fid_periode) && $detail->fid_periode == $periode->id) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $periode->id ?>" <?php echo $disabled ?> <?php echo $selected; ?>><?php echo $periode->nama ?> (<?php echo $is_status ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <div id="help-block-bulan"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="usl-f">
                                <label class="usl-lbl" for="tahun">SPJ Tahun <span class="text-danger">*</span></label>
                            <select name="tahun" id="tahun" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-tahun" <?php echo $disabled_status; ?>>
                                <option value="">Pilih Tahun</option>
                                <?php
                                    $year = date('Y');
                                    for ($i = $year - 1; $i <= $year + 1; $i++) {
                                        if (isset($detail->tahun)) {
                                            $selected = $detail->tahun == $i ? 'selected' : '';
                                        } else {
                                            $selected = $year == $i ? 'selected' : '';
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
                    // Hitung total pagu dan sisa pagu
                    $totalPaguAwal      = $this->target->getAlokasiPaguUraian(@$detail->fid_uraian, $this->session->userdata('is_perubahan'))->row()->total_pagu_awal ?? 0;
                    $totalRealisasiPagu = $totalPaguAwal - (int) $this->realisasi->getRealisasiTahunanUraian(@$detail->fid_uraian, ['VERIFIKASI', 'VERIFIKASI_ADMIN', 'SELESAI']);
                    $totalSisaPagu      = ($totalRealisasiPagu-@$detail->jumlah);
                    // Hitung sisa angkas / limit
                    $totalLimit        = $this->spj->getLimitPagu(@$detail->fid_uraian, @$detail->fid_periode)->row() ?: (object) ['total' => 0, 'periode' => ''];
                    $limitPeriode      = array_filter(explode(',', (string) $totalLimit->periode), 'strlen');
                    $totalSisaLimit    = (int) @$totalLimit->total - (int) $this->realisasi->getRealisasiByPeriode(@$detail->fid_uraian, ['VERIFIKASI', 'VERIFIKASI_ADMIN', 'SELESAI'], $limitPeriode);
                    // Render awal: saat ENTRI jumlah belum masuk hitungan server → langsung kurangi.
                    // data-start* tetap base (tanpa kurang jumlah) agar keyup JS tidak double-subtract.
                    $jmlInput          = (int) @$detail->jumlah;
                    $isEntri           = ($disabled_status === '');
                    $tampilSisaAnggaran = $isEntri ? ($totalRealisasiPagu - $jmlInput) : $totalRealisasiPagu;
                    $tampilSisaAngkas   = $isEntri ? ($totalSisaLimit - $jmlInput) : $totalSisaLimit;
                ?>
                <div class="usl-stats">
                    <div class="usl-stat"><span class="usl-stat-k">Jumlah Maksimum</span><span class="usl-stat-v" id="jumlah_max">Rp. <?php echo nominal($totalPaguAwal) ?></span></div>
                    <div class="usl-stat"><span class="usl-stat-k">Sisa Anggaran</span><span class="usl-stat-v" id="sisa_max">Rp. <?php echo nominal($tampilSisaAnggaran) ?></span></div>
                    <div class="usl-stat usl-stat--angkas"><span class="usl-stat-k">Sisa Angkas</span><span class="usl-stat-v" id="angkas">Rp. <?php echo nominal($tampilSisaAngkas) ?></span></div>
                </div>
                </div>
            </div>

            <div class="usl-card">
                <div class="usl-card-hd"><i class="fa fa-usd" aria-hidden="true"></i> Jumlah &amp; Uraian</div>
                <div class="usl-card-bd">
                    <div class="usl-f">
                        <label for="jumlah" class="usl-lbl">Jumlah <span class="text-danger">*</span></label>
                        <div class="usl-amount">
                            <span class="usl-amount-pre">Rp</span>
                            <input type="text" value="<?php echo @nominal($detail->jumlah) ?>"
                                data-start="<?php echo $totalRealisasiPagu ?>"
                                data-start-limit="<?php echo $totalSisaLimit ?>"
                                name="jumlah"
                                id="jumlah"
                                class="form-control usl-amount-inp"
                                required
                                placeholder="0"
                                data-parsley-errors-container="#help-block-jumlah"
                                <?php echo $disabled_status; ?>>
                        </div>
                        <div id="help-block-jumlah" class="row col-md-12"></div>
                    </div>
                    <div class="usl-f mt-3">
                        <label class="usl-lbl" for="uraian">Uraian / Untuk Pembayaran / Keterangan Kwitansi <span class="text-danger">*</span></label>
                        <textarea name="uraian" id="uraian" cols="30" rows="4" class="form-control usl-ta" required="required" placeholder="Tuliskan uraian pembayaran / keterangan kwitansi ..." <?php echo $disabled_status; ?>><?php echo @$detail->uraian ?></textarea>
                    </div>
                </div>
            </div>
            </div><!-- /.usl-scroll -->

            <div class="usl-actions">
                    <button class="btn btn-light rounded-0" onclick="window.location.href='<?php echo base_url('app/spj') ?>'" type="button"><i class="fa fa-arrow-left mr-2"></i> Kembali</button>
                    <?php if ((@$detail->is_status === 'ENTRI') || (empty(@$detail->token))): ?>
                        <button class="btn btn-primary rounded-0" type="submit"><i class="fa fa-save mr-2"></i> Simpan &amp; Lanjutkan</button>
                    <?php else: ?>
                        <button onclick="nextStep('<?php echo base_url('app/spj/buatusul?step=1&token=' . @$detail->token) ?>')" class="btn btn-primary rounded-0" type="button">Selanjutnya <i class="fa fa-arrow-right ml-2"></i></button>
                    <?php endif; ?>
                </div>
            <?php echo form_close() ?>

        </div>
        <div id="step-2">
            <div class="usl-scroll">
            <div class="usl-card">
                <div class="usl-card-hd"><i class="fa fa-users" aria-hidden="true"></i> Relasi Publik <span class="usl-card-sub">organisasi / instansi / perorangan yang menerima manfaat</span></div>
                <div class="usl-card-bd">
                    <table class="table table-bordered table-striped" id="table-penerima-manfaat">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th>Nama Organsasi/Instansi/Lembaga</th>
                                <th>Nama Perorangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            </div><!-- /.usl-scroll -->
            <div class="usl-actions">
                <button class="btn btn-light rounded-0" onclick="window.location.replace('<?php echo base_url('app/spj/buatusul?step=0&status=entri&token=' . @$detail->token) ?>')" type="button"><i class="fa fa-arrow-left mr-2"></i> Sebelumnya</button>
                <button onclick="uslNextStep2('<?php echo base_url('app/spj/buatusul?step=2&token=' . @$detail->token) ?>')" class="btn btn-primary rounded-0" type="button">Selanjutnya <i class="fa fa-arrow-right ml-2"></i></button>
            </div>
        </div>

        <div id="step-3">
            <?php echo form_open(base_url('app/spj/proseseviden'), ['id' => 'form-step-3', 'class' => 'form-horizontal form-label-left', 'data-parsley-validate' => '']); ?>
            <input type="hidden" name="token" value="<?php echo @$detail->token ?>">
            <div class="usl-scroll">
            <div class="usl-card">
                <div class="usl-card-hd"><i class="fa fa-paperclip" aria-hidden="true"></i> Scan SPPD <span class="usl-card-sub">berkas yang wajib dilampirkan</span></div>
                <div class="usl-card-bd">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="usl-lbl"><i class="fa fa-check-square-o" aria-hidden="true"></i> Dokumen Wajib</div>
                            <ol class="usl-checklist">
                                <li>Kuitansi bendahara</li>
                                <li>NPD</li>
                                <li>Undangan (bila ada)</li>
                                <li>Telaah staf</li>
                                <li>Surat Tugas</li>
                                <li>SPPD</li>
                                <li>Laporan perjadin dan foto (lampirannya)</li>
                                <li>Rincian perjalanan dinas</li>
                                <li>Surat pernyataan riil</li>
                                <li>Bukti dukung bill hotel, tiket pesawat, boarding dll</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <div class="usl-lbl"><i class="fa fa-info-circle" aria-hidden="true"></i> Catatan Penting</div>
                            <ul class="usl-checklist usl-checklist--note">
                                <li>Tanggal pada SPJ diisi semua (kuitansi dll)</li>
                                <li>Untuk sppd di scan bolak balik (urut halaman: depan lalu belakang)</li>
                                <li>Scan kuitansi bendahara WAJIB di halaman pertama untuk semua SPJ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
                <div class="usl-card">
                <div class="usl-card-hd"><i class="fa fa-cloud" aria-hidden="true"></i> Link Berkas</div>
                <div class="usl-card-bd">
                    <div class="usl-f">
                        <div class="usl-f--row">
                            <label class="usl-lbl" for="link">Link Dokumen <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-light usl-preview" id="btnClearLink" disabled><i class="fa fa-times"></i></button>
                        </div>
                        <textarea name="link" id="link" cols="30" rows="3" class="form-control usl-ta" required="required"
                            placeholder="https://..."
                            data-parsley-errors-container="#help-block-link"
                            data-parsley-pattern="^(https?:\/\/).+"
                            data-parsley-pattern-message="Link harus diawali http:// atau https://"
                            <?php echo $disabled_status ?>><?php echo @$detail->berkas_link ?></textarea>
                        <div id="help-block-link"></div>
                        <small class="form-text text-muted usl-hint">Pastikan link berstatus publik — dapat diakses verifikator tanpa login.</small>
                        <div class="usl-preview-box" id="uslPreviewBox" style="display:none">
                            <div class="usl-preview-link">
                                <div class="usl-preview-link__ico"><i class="fa fa-file-pdf-o"></i></div>
                                <div class="usl-preview-link__meta">
                                    <span class="usl-preview-link__lbl">Dokumen eviden</span>
                                    <span class="usl-preview-link__url" id="uslPreviewUrl">-</span>
                                </div>
                                <a id="uslPreviewOpenFoot" class="btn btn-primary btn-sm rounded-0 ml-auto" href="#" target="_blank" rel="noopener"><i class="fa fa-external-link mr-2"></i>Buka</a>
                            </div>
                            <small class="usl-preview-note"><i class="fa fa-info-circle mr-1"></i> Situs asal mungkin melarang preview di dalam halaman.</small>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- /.usl-scroll -->
            <div class="usl-actions">
                <button type="button" class="btn btn-light rounded-0" onclick="window.location.replace('<?php echo base_url('app/spj/buatusul?step=1&status=entri&token=' . @$detail->token) ?>')"><i class="fa fa-arrow-left mr-2"></i> Sebelumnya</button>
                <?php if ((@$detail->is_status === 'ENTRI') || (empty(@$detail->token))): ?>
                    <button type="submit" class="btn btn-primary rounded-0">Simpan &amp; Lanjutkan <i class="fa fa-arrow-right ml-2"></i></button>
                <?php else: ?>
                    <button onclick="nextStep('<?php echo base_url('app/spj/buatusul?step=3&token=' . @$detail->token) ?>')" class="btn btn-primary rounded-0" type="button">Selanjutnya <i class="fa fa-arrow-right ml-2"></i></button>
                <?php endif; ?>
            </div>
            <?php echo form_close() ?>
        </div>
        <div id="step-4">
            <?php echo form_open(base_url('app/spj/final'), ['id' => 'form-step-4', 'class' => 'form-horizontal form-label-left', 'data-parsley-validate' => '']); ?>
            <input type="hidden" name="token" value="<?php echo @$detail->token ?>">
            <?php
                $spj = $this->spj->detail(['token' => @$detail->token])->row() ?: (object) [];
            ?>
                <?php if (! empty($spj->is_status) && ! in_array($spj->is_status, ['SELESAI_TMS', 'SELESAI_BTL'])): ?>
                    <?php
                        $list_penerima = $this->spj->getListPenerimaManfaat(@$detail->token)->result();
                        $jml_penerima  = count($list_penerima);
                        $userusul      = $this->users->profile_username($spj->entri_by)->row() ?: (object) ['nama' => (string) @$spj->entri_by];
                    ?>
                    <div class="usl-note">
                        <i class="fa fa-info-circle"></i>
                        <div><strong>Perhatian!</strong> Silahkan cek kembali data usulan SPJ Anda sebelum difinalisasi.</div>
                    </div>
                    <div class="usl-scroll">
                    <div class="usl-card">
                        <div class="usl-card-hd"><i class="fa fa-sitemap" aria-hidden="true"></i> Kode &amp; Program</div>
                        <div class="usl-card-bd">
                            <div class="usl-dl">
                                <div class="usl-dl__row"><span class="usl-dl__k">Bidang / Bagian</span><span class="usl-dl__v"><?php echo @$spj->nama_part ?></span></div>
                                <div class="usl-dl__row"><span class="usl-dl__k">Program</span><span class="usl-dl__v"><div><b><?php echo @$spj->kode_program ?></b></div><div><?php echo strtoupper((string) @$spj->nama_program) ?></div></span></div>
                                <div class="usl-dl__row"><span class="usl-dl__k">Kegiatan</span><span class="usl-dl__v"><div><b><?php echo @$spj->kode_kegiatan ?></b></div><div><?php echo strtoupper((string) @$spj->nama_kegiatan) ?></div></span></div>
                                <div class="usl-dl__row"><span class="usl-dl__k">Sub Kegiatan</span><span class="usl-dl__v"><div><b><?php echo @$spj->kode_sub_kegiatan ?></b></div><div><?php echo strtoupper((string) @$spj->nama_sub_kegiatan) ?></div></span></div>
                                <div class="usl-dl__row"><span class="usl-dl__k">Uraian Rekening</span><span class="usl-dl__v"><div><b><?php echo (string) (@$spj->kode_uraian ?: @$spj->fid_uraian) ?></b></div><div><?php echo strtoupper((string) @$spj->nama_uraian) ?></div></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="usl-card">
                        <div class="usl-card-hd"><i class="fa fa-file-text-o" aria-hidden="true"></i> Detail SPJ</div>
                        <div class="usl-card-bd">
                            <div class="usl-dl">
                                <div class="usl-dl__row"><span class="usl-dl__k">Periode</span><span class="usl-dl__v"><?php echo (@$spj->bulan ? bulan($spj->bulan) : '-') ?></span></div>
                                <div class="usl-dl__row"><span class="usl-dl__k">Tahun</span><span class="usl-dl__v"><?php echo @$spj->tahun ?></span></div>
                                <div class="usl-dl__row"><span class="usl-dl__k">Uraian</span><span class="usl-dl__v"><?php echo @$spj->uraian ?></span></div>
                                <div class="usl-dl__row"><span class="usl-dl__k">Jumlah</span><span class="usl-dl__v usl-dl__v--jumlah">Rp. <b><?php echo nominal(@$spj->jumlah) ?></b></span></div>
                                <div class="usl-dl__row"><span class="usl-dl__k">Terbilang</span><span class="usl-dl__v usl-dl__v--terbilang"><?php echo empty($spj->jumlah) ? '-' : terbilang($spj->jumlah) ?></span></div>
                                <div class="usl-dl__row"><span class="usl-dl__k">Berkas (Link)</span><span class="usl-dl__v"><?php if (! empty($spj->berkas_link)): ?><a class="usl-link" href="<?php echo $spj->berkas_link ?>" target="_blank" rel="noopener" title="Buka dokumen"><i class="fa fa-external-link"></i> <?php echo $spj->berkas_link ?></a><?php else: ?>-<?php endif; ?></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="usl-card">
                        <div class="usl-card-hd"><i class="fa fa-users" aria-hidden="true"></i> Penerima Manfaat <span class="usl-card-sub"><?php echo $jml_penerima ?> penerima</span></div>
                        <div class="usl-card-bd">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped usl-tbl" id="tblPenerimaFinal">
                                    <thead>
                                        <tr>
                                            <th width="5%">No.</th>
                                            <th>Nama Organisasi/Instansi/Lembaga</th>
                                            <th>Nama Perorangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($jml_penerima > 0): $no = 1; ?>
                                            <?php foreach ($list_penerima as $penerima): ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $no++ ?>.</td>
                                                    <td><?php echo ! empty($penerima->organisasi) ? $penerima->organisasi : '-' ?></td>
                                                    <td><?php echo ! empty($penerima->perorangan) ? $penerima->perorangan : '-' ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">-- Data Penerima Manfaat Tidak Ada --</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if ($jml_penerima === 0): ?>
                                <div class="usl-note usl-note--danger">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    <div>Usulan SPJ harus memiliki minimal 1 (satu) penerima manfaat.</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="usl-card">
                        <div class="usl-card-hd"><i class="fa fa-user" aria-hidden="true"></i> Detail Pengusul</div>
                        <div class="usl-card-bd">
                            <div class="usl-dl">
                                <div class="usl-dl__row"><span class="usl-dl__k">Dientri oleh</span><span class="usl-dl__v"><?php echo @$userusul->nama ?> (<?php echo strtoupper((string) @$spj->entri_by) ?>)</span></div>
                                <div class="usl-dl__row"><span class="usl-dl__k">Tanggal / Jam</span><span class="usl-dl__v"><?php echo empty($spj->entri_at) ? '-' : longdate_indo(substr($spj->entri_at, 0, 10)) . ' / ' . substr($spj->entri_at, 10, 6) . ' WITA' ?></span></div>
                            </div>
                        </div>
                    </div>
                    </div><!-- /.usl-scroll -->
                    <div class="usl-actions">
                        <button type="button" class="btn btn-light rounded-0" onclick="window.location.replace('<?php echo base_url('app/spj/buatusul?step=2&status=entri&token=' . @$detail->token) ?>')"><i class="fa fa-arrow-left mr-2"></i> Sebelumnya</button>
                        <?php if ($jml_penerima > 0): ?>
                            <?php if ((@$detail->is_status === 'ENTRI') || (empty(@$detail->token))): ?>
                                <button class="btn btn-success rounded-0" type="submit">Finalkan <i class="fa fa-save ml-2"></i></button>
                            <?php else: ?>
                                <button onclick="nextStep('<?php echo base_url('app/spj/buatusul?step=4&token=' . @$detail->token) ?>')" class="btn btn-primary rounded-0" type="button">Selanjutnya <i class="fa fa-arrow-right ml-2"></i></button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php echo form_close(); ?>
        </div>
        <div id="step-5">
            <?php if (@$detail->is_status === 'VERIFIKASI' || @$detail->is_status === 'VERIFIKASI_ADMIN'): ?>
                <div class="usl-scroll">
                <div class="usl-card usl-card--done">
                    <div class="usl-card-bd">
                        <img class="usl-done-ico" src="<?php echo base_url('template/assets/icon/verifikasi.svg') ?>" alt="Verifikasi Admin">
                        <h2 class="usl-done-title">Usulan Dalam Proses Verifikasi <i class="fa fa-lock text-success"></i></h2>
                        <p class="usl-done-sub">SPJ Anda sedang ditinjau verifikator. Anda akan menerima notifikasi setelah proses selesai.</p>
                        <div class="usl-done-badges">
                            <span class="usl-badge usl-chip"><i class="fa fa-spinner fa-spin"></i> Menunggu verifikasi</span>
                            <span class="usl-badge usl-chip usl-chip--info"><i class="fa fa-file-text-o"></i> <?php echo @$spj->koderek ?></span>
                        </div>
                    </div>
                </div>
                </div><!-- /.usl-scroll -->
                <div class="usl-actions">
                    <button type="button" class="btn btn-light rounded-0" onclick="window.location.replace('<?php echo base_url('app/spj/buatusul?step=4&status=entri&token=' . @$detail->token) ?>')"><i class="fa fa-arrow-left mr-2"></i> Sebelumnya</button>
                    <button class="btn btn-primary rounded-0" onclick="window.location.href='<?php echo base_url('app/spj') ?>'">Buka Inbox <i class="fa fa-inbox ml-2"></i></button>
                </div>
            <?php else: ?>
                <?php if (empty($spj->is_status)): ?>
                    <div class="usl-note usl-note--danger usl-note--big">
                        <i class="fa fa-close"></i>
                        <div><strong>Mohon Maaf</strong>, Data usulan SPJ tidak ditemukan.</div>
                    </div>
                <?php else: ?>
                <div class="usl-note usl-note--danger usl-note--big">
                    <i class="fa fa-close"></i>
                    <div><strong>Mohon Maaf</strong>, Usulan SPJ dengan kode rekening "<?php echo $spj->koderek ?>" (<?php echo $spj->is_status ?>).</div>
                </div>
                <div class="usl-scroll">
                <?php if (! empty($spj->catatan)): ?>
                    <div class="usl-card">
                        <div class="usl-card-hd"><i class="fa fa-commenting-o" aria-hidden="true"></i> Alasan <span class="usl-card-sub">(<?php echo $spj->is_status ?>)</span></div>
                        <div class="usl-card-bd">
                            <div class="usl-reason"><?php echo $spj->catatan ?></div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="usl-card">
                    <div class="usl-card-hd"><i class="fa fa-file-text-o" aria-hidden="true"></i> Detail SPJ</div>
                    <div class="usl-card-bd">
                        <div class="usl-dl">
                            <div class="usl-dl__row"><span class="usl-dl__k">Kode Rekening</span><span class="usl-dl__v"><?php echo @$spj->koderek ?></span></div>
                            <div class="usl-dl__row"><span class="usl-dl__k">Periode</span><span class="usl-dl__v"><?php echo (@$spj->bulan ? bulan($spj->bulan) : '-') ?></span></div>
                            <div class="usl-dl__row"><span class="usl-dl__k">Tahun</span><span class="usl-dl__v"><?php echo @$spj->tahun ?></span></div>
                            <div class="usl-dl__row"><span class="usl-dl__k">Uraian</span><span class="usl-dl__v"><?php echo @$spj->uraian ?></span></div>
                            <div class="usl-dl__row"><span class="usl-dl__k">Jumlah</span><span class="usl-dl__v usl-dl__v--jumlah">Rp. <b><?php echo nominal(@$spj->jumlah) ?></b></span></div>
                            <div class="usl-dl__row"><span class="usl-dl__k">Terbilang</span><span class="usl-dl__v usl-dl__v--terbilang"><?php echo empty($spj->jumlah) ? '-' : terbilang($spj->jumlah) ?></span></div>
                            <div class="usl-dl__row"><span class="usl-dl__k">Berkas (Link)</span><span class="usl-dl__v"><?php if (! empty($spj->berkas_link)): ?><a class="usl-link" href="<?php echo $spj->berkas_link ?>" target="_blank" rel="noopener" title="Buka dokumen"><i class="fa fa-external-link"></i> <?php echo $spj->berkas_link ?></a><?php else: ?>-<?php endif; ?></span></div>
                        </div>
                    </div>
                </div>
                <?php if (! empty($spj->is_status) && in_array($spj->is_status, ['BTL', 'TMS', 'SELESAI_TMS', 'SELESAI_BTL'])): ?>
                    <?php $userver = $this->users->profile_username($spj->verify_by)->row() ?: (object) ['nama' => (string) @$spj->verify_by]; ?>
                    <div class="usl-card">
                        <div class="usl-card-hd"><i class="fa fa-user" aria-hidden="true"></i> Detail Verifikator</div>
                        <div class="usl-card-bd">
                            <div class="usl-dl">
                                <div class="usl-dl__row"><span class="usl-dl__k">Diverifikasi oleh</span><span class="usl-dl__v"><?php echo @$userver->nama ?> (<?php echo strtoupper((string) @$spj->verify_by) ?>)</span></div>
                                <div class="usl-dl__row"><span class="usl-dl__k">Tanggal / Jam</span><span class="usl-dl__v"><?php echo empty($spj->verify_at) ? '-' : longdate_indo(substr($spj->verify_at, 0, 10)) . ' / ' . substr($spj->verify_at, 10, 6) . ' WITA' ?></span></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                </div><!-- /.usl-scroll -->
                <div class="usl-actions">
                    <span></span>
                    <button class="btn btn-danger rounded-0" onclick="window.location.href='<?php echo base_url('app/spj') ?>'" type="button"><i class="fa fa-arrow-left mr-2"></i> Kembali</button>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- End SmartWizard Content -->

<!-- Modal Tambah Data Users -->
<div class="modal fade" id="tambah-data-users" tabindex="-1" aria-labelledby="tambah-data-usersLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-0">
      <?php echo form_open(base_url('app/spj/tambah_penerima'), ['id' => 'formRelasiPublik', 'data-parsley-validate' => true], [
    'token' => @$detail->token,
]) ?>
      <div class="modal-header">
        <h5 class="modal-title" id="tambah-data-usersLabel">Relasi Publik</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="organisasi">Organisasi/Instansi/Lembaga</label>
            <input type="text" name="organisasi" id="organisasi" class="form-control" placeholder="Masukkan Nama Organisasi/Instansi/Lembaga" required data-parsley-required-message="Nama organisasi wajib diisi">
        </div>
        <div class="form-group">
            <label for="perorangan">Nama Perorangan</label>
            <input type="text" name="perorangan" id="perorangan" class="form-control" placeholder="Masukkan Nama Perorangan"
            required data-parsley-required-message="Nama perorangan wajib diisi">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
    <?php echo form_close(); ?>
    </div>
  </div>
</div>