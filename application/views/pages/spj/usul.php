<div class="clearfix"></div>
<?php if (@$detail->is_status === 'VERIFIKASI'  || @$detail->is_status === 'VERIFIKASI_ADMIN') : ?>
    <div class="alert alert-warning text-dark rounded-0" role="alert">
        <strong><i class="fa fa-lock mr-2"></i> Verifikasi </strong>, Usulan SPJ kamu dalam tahap verifikasi.
    </div>
<?php endif; ?>
<?php if (isset($detail->catatan) && @$detail->is_status === 'ENTRI' && !empty($detail->catatan)): ?>
    <div class="alert alert-warning rounded-0 border text-dark d-flex justify-content-start align-items-start" role="alert">
        <i class="fa fa-exclamation-triangle mr-2 mt-1 text-danger"></i>
        <div><strong>Catatan Verifikator : </strong> <br> <?= !empty($detail->catatan) ? $detail->catatan : '-' ?></div>
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
                        Relasi Publik<br />
                        <small>Penerima Manfaat</small>
                    </span>
                </a>
            </li>
            <li>
                <a href="#step-3">
                    <span class="step_no">3</span>
                    <span class="step_descr">
                        Eviden<br />
                        <small>Unggah Berkas SPJ</small>
                    </span>
                </a>
            </li>
            <li>
                <a href="#step-4">
                    <span class="step_no">4</span>
                    <span class="step_descr">
                        Review<br />
                        <small>Review Usulan</small>
                    </span>
                </a>
            </li>
            <li>
                <a href="#step-5">
                    <span class="step_no">5</span>
                    <span class="step_descr">
                        Final<br />
                        <small>Verifikasi</small>
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
                    <label for="koderek" class="row col-md-12">Kode <span class="text-danger ml-1 mr-1">*</span></label>
                    <input type="text" value="<?= @$detail->koderek ?>" readonly name="koderek" id="koderek" onclick="showModalSearchKode()" <?= $disabled_status ?> class="form-control col-md-6" required="required" data-parsley-errors-container="#help-block-koderek"> <button type="button" class="btn btn-light rounded-0 ml-1" data-toggle="modal" data-target="#modelSearchKode" <?= $disabled_status ?>><i class="fa fa-search"></i> Cari Rincian</button>
                    <div id="help-block-koderek" class="row col-md-12"></div>
                </div>
                <?php if (!empty($detail->fid_kegiatan)): ?>
                    <div class="row">
                        <div class="col-md-12" id="loadKegiatan">
                            <ul class="list-unstyled d-lg-flex flex-column justify-content-start font-weight-bold">
                                <li class="d-inline-flex align-items-center"><i class="fa fa-file-code-o text-warning mr-2 fa-2x" aria-hidden="true"></i> <?= @$this->spj->getNama('ref_kegiatans', $detail->fid_kegiatan) ?></li>
                                <li class="d-inline-flex align-items-center my-2"><i class="fa fa-file-code-o text-info mr-2 fa-2x" aria-hidden="true"></i> <?= @$this->spj->getNama('ref_sub_kegiatans', $detail->fid_sub_kegiatan) ?></li>
                                <li class="d-inline-flex align-items-center"><i class="fa fa-file-code-o text-success ml-md- mr-2 fa-2x" aria-hidden="true"></i> <?= @$this->spj->getNama('ref_uraians', $detail->fid_uraian) ?> <i class="fa fa-check-circle text-success ml-2"></i></li>
                            </ul>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <div class="col-md-12" id="loadKegiatan"></div>
                    </div>
                <?php endif; ?>
                <div class="divider-dashed"></div>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="col-form-label label-align" for="bulan">SPJ Periode <span class="text-danger">*</span></label>
                            <select name="periode" id="periode" class="form-control rounded-0" required <?= $disabled_status; ?>
                                data-parsley-errors-container="#help-block-bulan">
                                <option value="">-- Pilih Periode --</option>
                                <?php
                                foreach ($this->spj->getPeriode()->result() as $periode) :
                                    $is_status = $periode->is_open === 'Y' ? 'OPEN' : 'CLOSE';
                                    $disabled = $periode->is_open !== 'Y' ? 'disabled' : '';
                                    $selected = (isset($detail->fid_periode) && $detail->fid_periode == $periode->id) ? 'selected' : '';
                                ?>
                                    <option value="<?= $periode->id ?>" <?= $disabled ?> <?= $selected; ?>><?= $periode->nama ?> (<?= $is_status ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <div id="help-block-bulan"></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="col-form-label label-align" for="tahun">SPJ Tahun <span class="text-danger">*</span></label>
                            <select name="tahun" id="tahun" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-tahun" <?= $disabled_status; ?>>
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
                $totalPaguAwal = !empty($this->target->getAlokasiPaguUraian(@$detail->fid_uraian, $this->session->userdata('is_perubahan'))->row()->total_pagu_awal) ? $this->target->getAlokasiPaguUraian(@$detail->fid_uraian)->row()->total_pagu_awal : 0;
                $totalRealisasiPagu =  $totalPaguAwal - $this->realisasi->getRealisasiTahunanUraian(@$detail->fid_uraian, ['VERIFIKASI', 'VERIFIKASI_ADMIN', 'SELESAI']);
                $totalSisaPagu = ($totalRealisasiPagu - @$detail->jumlah);
                // Hitung sisa angkas / limit
                $totalLimit = $this->spj->getLimitPagu(@$detail->fid_uraian, @$detail->fid_periode)->row();
                $totalRealisasiPaguByPeriode = $this->realisasi->getRealisasiByPeriode(@$detail->fid_uraian, ['VERIFIKASI', 'VERIFIKASI_ADMIN', 'SELESAI'], explode(",", @$totalLimit->periode));
                $totalSisaLimit = (@$totalLimit->total - $totalRealisasiPaguByPeriode);
                ?>
                <div class="divider-dashed"></div>
                <div class="form-group d-flex">
                    <div class="pr-5  p-3 shadow rounded">
                        <b>Jumlah Maksimum</b>
                        <h5 id="jumlah_max">Rp. <?= nominal($totalPaguAwal) ?></h5>
                    </div>
                    <div class="ml-3 p-3 rounded shadow">
                        <b>Sisa Anggaran</b>
                        <h5 id="sisa_max">Rp. <?= nominal($totalRealisasiPagu) ?></h5>
                    </div>
                    <div class="ml-3 p-3 rounded shadow">
                        <b>Sisa Angkas</b>
                        <h5 id="angkas">Rp. <?= nominal($totalSisaLimit) ?></h5>
                    </div>
                </div>
                <div class="divider-dashed"></div>

                <div class="form-group">
                    <label for="jumlah" class="row col-md-12">Jumlah <span class="text-danger ml-1 mr-1">*</span></label>
                    <input type="text" value="<?= @nominal($detail->jumlah) ?>"
                        data-start="<?= $totalRealisasiPagu ?>"
                        data-start-limit="<?= $totalSisaLimit ?>"
                        name="jumlah"
                        id="jumlah"
                        class="form-control col-md-6"
                        required
                        data-parsley-errors-container="#help-block-jumlah"
                        <?= $disabled_status; ?>>
                    <div id="help-block-jumlah" class="row col-md-12"></div>
                </div>

                <div class="clearfix"></div>
                <div class="form-group">
                    <label class="col-form-label label-align" for="uraian">Uraian/Untuk Pembayaran/Keterangan Kwitansi <span class="text-danger">*</span></label>
                    <textarea name="uraian" id="uraian" cols="30" rows="5" class="form-control" required="required" <?= $disabled_status; ?>><?= @$detail->uraian ?></textarea>
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
            <div class="col-md-10 center-margin">
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
            <button type="button" class="btn btn-secondary rounded-0 pull-left" onclick="window.location.replace('<?= base_url('app/spj/buatusul?step=0&status=entri&token=' . @$detail->token) ?>')"><i class="fa fa-arrow-left mr-2"></i> Sebelumnya </button>
            <button onclick="nextStep('<?= base_url('app/spj/buatusul?step=2&token=' . @$detail->token) ?>')" class="btn btn-primary rounded-0" type="button"> Selanjutnya <i class="fa fa-arrow-right ml-2"></i></button>
        </div>
        </div>
        <div id="step-3">
            <?= form_open(base_url('app/spj/proseseviden'), ['id' => 'step-3', 'class' => 'form-horizontal form-label-left', 'data-parsley-validate' => '']); ?>
            <input type="hidden" name="token" value="<?= @$detail->token ?>">
            <div class="col-md-10 center-margin">
                <div class="alert alert-info rounded-0" role="alert">
                    <strong>Penting :</strong> Usahakan link yang diberikan berstatus publik, dapat diakses saat pengecekan oleh verifikator.
                </div>
                <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pertama-tab" data-toggle="tab" href="#pertama" role="tab" aria-controls="pertama" aria-selected="false">Scan SPPD</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="pertama" role="tabpanel" aria-labelledby="pertama-tab">
                        <div class="row">
                            <div class="col-md-3">
                                <ol>
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
                            <div class="col-md-5">
                                <ul>
                                    <li>Tanggal pada SPJ diisi semua (kuitansi dll)</li>
                                    <li>Untuk sppd di scan bolak balik (Untuk sppd bolak baliknya urut halamannya. Lembar depan dan lembar belakang.)</li>
                                    <li>Untuk scanan kuitansi bendahara WAJIB di halaman pertama untuk semua SPJ</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 center-margin">
                <div class="form-group">
                    <label class="col-form-label label-align" for="link">Link Berkas <span class="text-danger">*</span></label>
                    <textarea name="link" id="link" cols="30" rows="3" class="form-control" required="required"
                        data-parsley-pattern="^(https?:\/\/).+"
                        data-parsley-pattern-message="Link harus diawali http:// atau https://"
                        <?= $disabled_status ?>><?= @$detail->berkas_link ?></textarea>
                </div>
                <button type="button" class="btn btn-secondary rounded-0" onclick="window.location.replace('<?= base_url('app/spj/buatusul?step=1&status=entri&token=' . @$detail->token) ?>')"><i class="fa fa-arrow-left mr-2"></i> Sebelumnya </button>
                <?php if ((@$detail->is_status === 'ENTRI') || (empty(@$detail->token))) : ?>
                    <button type="submit" class="btn btn-primary rounded-0"> Simpan & Lanjutkan <i class="fa fa-arrow-right mr-2"></i></button>
                <?php else : ?>
                    <button onclick="nextStep('<?= base_url('app/spj/buatusul?step=3&token=' . @$detail->token) ?>')" class="btn btn-primary rounded-0" type="button"> Selanjutnya <i class="fa fa-arrow-right ml-2"></i></button>
                <?php endif; ?>
            </div>
            <?= form_close() ?>
        </div>
        <div id="step-4">
            <?= form_open(base_url('app/spj/final'), ['id' => 'step-4', 'class' => 'form-horizontal form-label-left', 'data-parsley-validate' => '']); ?>
            <input type="hidden" name="token" value="<?= @$detail->token ?>">
            <div class="col-md-10 center-margin">
                <?php
                $spj = $this->spj->detail(['token' => @$detail->token])->row();
                ?>
                <?php if (!in_array(@$spj->is_status, ['SELESAI_TMS', 'SELESAI_BTL'])) : ?>
                    <div class="alert alert-info rounded-0" role="alert">
                        <strong><i class="fa fa-check-circle mr-2"></i> Perhatian !</strong> Silahkan cek kembali data usulan SPJ anda sebelum difinalisasi.
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
                            <tr class="bg-light text-dark text-center">
                                <td colspan="3">Detail SPJ (Surat Pertanggung Jawaban)</td>
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
                            <tr class="bg-light text-dark text-center">
                                <td colspan="3">Daftar Penerima Manfaat</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">No.</th>
                                                <th>Nama Organsasi/Instansi/Lembaga</th>
                                                <th>Nama Perorangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $list_penerima = $this->spj->getListPenerimaManfaat(@$detail->token)->result();
                                            if (count($list_penerima) > 0) :
                                                $no = 1;
                                                foreach ($list_penerima as $penerima) :
                                            ?>
                                                    <tr>
                                                        <td class="text-center"><?= $no++; ?>.</td>
                                                        <td><?= !empty($penerima->organisasi) ? $penerima->organisasi : '-'; ?></td>
                                                        <td><?= !empty($penerima->perorangan) ? $penerima->perorangan : '-'; ?></td>
                                                    </tr>
                                                <?php
                                                endforeach;
                                            else :
                                                ?>
                                                <tr>
                                                    <td colspan="3" class="text-center">-- Data Penerima Manfaat Tidak Ada --</td>
                                                </tr>
                                            <?php
                                            endif;
                                            ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr class="bg-light text-dark text-center">
                                <td colspan="3">Detail Pengguna</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <?php
                                    $userusul = $this->users->profile_username($spj->entri_by)->row();
                                    ?>
                                    Dientri oleh : <?= $userusul->nama ?> (<?= strtoupper($spj->entri_by) ?>) <br>
                                    Tanggal / Jam : <?= longdate_indo(substr($spj->entri_at, 0, 10)) ?> / <?= substr($spj->entri_at, 10, 6) ?> WITA
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <?php if(count($list_penerima) > 0): ?>
                                        <?php if((@$detail->is_status === 'ENTRI') || (empty(@$detail->token))): ?>
                                        <button class="btn btn-success rounded-0" type="submit"> Finalkan <i class="fa fa-save ml-2"></i></button>
                                        <?php else: ?>    
                                        <button onclick="nextStep('<?= base_url('app/spj/buatusul?step=4&token=' . @$detail->token) ?>')" class="btn btn-primary rounded-0" type="button"> Selanjutnya <i class="fa fa-arrow-right ml-2"></i></button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                    <div class="alert alert-danger" role="alert">
                                        <i class="fa fa-exclamation-triangle mr-2"></i> Mohon Maaf, Usulan SPJ harus memiliki minimal 1 (satu) penerima manfaat.
                                    </div>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-secondary rounded-0 pull-left" onclick="window.location.replace('<?= base_url('app/spj/buatusul?step=2&status=entri&token=' . @$detail->token) ?>')"><i class="fa fa-arrow-left mr-2"></i> Sebelumnya </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>                    
                <?php endif; ?>
            </div>
            <?= form_close(); ?>
        </div>
        <div id="step-5">
            <?php if (@$detail->is_status === 'VERIFIKASI' || @$detail->is_status === 'VERIFIKASI_ADMIN') : ?>
                <div class="container text-center">
                        <img src="<?= base_url('template/assets/icon/verifikasi.svg') ?>" alt="Verifikasi Admin" width="30%">
                        <h2 class="StepTitle">Usulan Dalam Proses Verifikasi <i class="fa fa-lock text-success ml-2"></i></h2>
                        <button type="button" class="btn btn-secondary rounded-0" onclick="window.location.replace('<?= base_url('app/spj/buatusul?step=3&status=entri&token=' . @$detail->token) ?>')"><i class="fa fa-arrow-left mr-2"></i> Sebelumnya </button>
                        <button class="btn btn-primary rounded-0" onclick="window.location.href='<?= base_url('app/spj') ?>'">Buka Inbox <i class="fa fa-inbox ml-2"></i> </button>
                    </div>
                </div>
            <?php else: ?>
            <div class="alert alert-danger rounded-0" role="alert">
                        <strong><i class="fa fa-close mr-2"></i> Mohon Maaf</strong>, Usulan SPJ dengan kode rekening "<?= $spj->koderek ?>" (<?= $spj->is_status ?>).
                    </div>
                    <div class="alert alert-light rounded-0 border" role="alert">
                        <strong>Alasan (<?= $spj->is_status ?>) : </strong> <br> <?= !empty($spj->catatan) ? $spj->catatan : '-' ?>
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="bg-light text-dark text-center">
                                <td colspan="3">Detail SPJ (Surat Pertanggung Jawaban)</td>
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
                            <tr class="bg-light text-dark text-center">
                                <td colspan="3">Detail Verificator</td>
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
                    <!-- <div class="form-group">
                        <label for="uraian">Pilih Uraian <span class="text-danger">*</span></label>
                        <select name="uraian" id="uraian" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-uraian">
                            <option value="">Pilih Uraian</option>
                        </select>
                        <div id="help-block-uraian"></div>
                    </div> -->
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success rounded-0">Pilih</button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Data Users -->
<div class="modal fade" id="tambah-data-users" tabindex="-1" aria-labelledby="tambah-data-usersLabel" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-0">
      <?= form_open(base_url('app/spj/tambah_penerima'), ['id' => 'formRelasiPublik', 'data-parsley-validate' => true], [
            'token' => @$detail->token
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
    <?= form_close(); ?>
    </div>
  </div>
</div>