<style>
/* Switch toggle styling */
.switch-toggle {
    position: relative;
    display: inline-block;
    width: 38px;
    height: 20px;
    margin-bottom: 0;
    vertical-align: middle;
}

.switch-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}

.switch-toggle .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .3s;
    border-radius: 20px;
}

.switch-toggle .slider:before {
    position: absolute;
    content: "";
    height: 14px;
    width: 14px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

.switch-toggle input:checked+.slider {
    background-color: #26B99A;
}

.switch-toggle input:checked+.slider:before {
    transform: translateX(18px);
}

.switch-toggle input:disabled+.slider {
    opacity: 0.5;
    cursor: not-allowed;
}

.switch-toggle.is-loading {
    opacity: 0.35;
    pointer-events: none;
}

.switch-loader {
    pointer-events: none;
    z-index: 2;
}

.budget-pills {
    gap: 10px;
    padding: 10px;
    border: 1px solid #dbeafe;
    border-radius: 18px;
    background: linear-gradient(135deg, #f8fbff 0%, #eefaf5 100%);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, .8), 0 8px 24px rgba(31, 78, 121, .08);
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
}

.budget-pills .nav-item {
    margin: 0 !important;
}

.budget-pills .nav-link {
    display: inline-flex;
    align-items: center;
    height: 42px;
    padding: 0 16px !important;
    border: 0 !important;
    border-radius: 999px !important;
    color: #52677a;
    background: rgba(255, 255, 255, .72);
    box-shadow: 0 1px 2px rgba(15, 23, 42, .06);
    transition: .2s ease;
    white-space: nowrap;
}

.budget-pills .nav-link i,
.budget-pills .nav-link span.fa {
    color: #26B99A !important;
}

.budget-pills .nav-link:hover {
    color: #1f4e79;
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(31, 78, 121, .12);
}

.budget-pills .nav-link.active {
    color: #fff !important;
    background: linear-gradient(135deg, #1f4e79 0%, #26B99A 100%) !important;
    box-shadow: 0 10px 24px rgba(38, 185, 154, .28);
}

.budget-pills .nav-link.active i,
.budget-pills .nav-link.active span.fa {
    color: #fff !important;
}

.budget-panel {
    border: 0 !important;
    margin-top: 12px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
    overflow: hidden;
}

/* Modal Rekonsiliasi */
.modal-rekon .modal-content {
    border: 0 !important;
}

.modal-rekon .bg-gradient-success {
    background: linear-gradient(135deg, #1a8a5c 0%, #26B99A 100%) !important;
}

.modal-rekon .rounded-lg {
    border-radius: 12px !important;
}

.modal-rekon .icon-wrapper {
    width: 56px;
    height: 56px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.modal-rekon .select-arrow {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    pointer-events: none;
}

.modal-rekon .custom-select {
    appearance: none;
    -webkit-appearance: none;
    padding-right: 40px;
}

/* File Upload Area */
.file-upload-wrapper {
    cursor: pointer;
    transition: all 0.2s ease;
    background: #f8f9fa;
}

.file-upload-wrapper:hover {
    border-color: #26B99A !important;
    background: #f0faf6;
}

.file-upload-wrapper.dragover {
    border-color: #26B99A !important;
    background: #e6f9f1;
    transform: scale(1.01);
}

.file-upload-wrapper .upload-icon {
    opacity: 0.6;
    transition: opacity 0.2s;
}

.file-upload-wrapper:hover .upload-icon {
    opacity: 1;
}
</style>
<div class="row">
    <div class="col-md-12">
        <?php
            $tab = isset($_GET['tab']) ? $_GET['tab'] : '#program';

            if (urldecode($tab) === '#tujuan_sasaran') {
                $tujuan_sasaran           = 'active';
                $is_active_tujuan_sasaran = true;
                $is_show_tujuan_sasaran   = "show";
            } else {
                $is_show_tujuan_sasaran   = "";
                $is_active_tujuan_sasaran = false;
                $tujuan_sasaran           = '';
            }

            if (urldecode($tab) === '#kelompok') {
                $kelompok           = 'active';
                $is_active_kelompok = true;
                $is_show_kelompok   = "show";
            } else {
                $is_show_kelompok   = "";
                $is_active_kelompok = false;
                $kelompok           = '';
            }

            if (urldecode($tab) === '#jenis') {
                $jenis           = 'active';
                $is_active_jenis = true;
                $is_show_jenis   = "show";
            } else {
                $is_show_jenis   = "";
                $is_active_jenis = false;
                $jenis           = '';
            }

            if (urldecode($tab) === '#part') {
                $part           = 'active';
                $is_active_part = true;
                $is_show_part   = "show";
            } else {
                $is_show_part   = "";
                $is_active_part = false;
                $part           = '';
            }

            if (urldecode($tab) === '#program') {
                $program           = 'active';
                $is_active_program = true;
                $is_show_program   = "show";
            } else {
                $is_show_program   = "";
                $is_active_program = false;
                $program           = '';
            }

            if (urldecode($tab) === '#kegiatan') {
                $kegiatan           = 'active';
                $is_active_kegiatan = true;
                $is_show_kegiatan   = "show";
            } else {
                $is_show_kegiatan   = "";
                $is_active_kegiatan = false;
                $kegiatan           = '';
            }

            if (urldecode($tab) === '#subkegiatan') {
                $is_show_subkegiatan   = "show";
                $subkegiatan           = 'active';
                $is_active_subkegiatan = true;
            } else {
                $is_show_subkegiatan   = "";
                $subkegiatan           = '';
                $is_active_subkegiatan = false;
            }

            if (urldecode($tab) === '#uraian') {
                $is_show_uraian   = "show";
                $uraian           = 'active';
                $is_active_uraian = true;
            } else {
                $is_show_uraian   = "";
                $uraian           = '';
                $is_active_uraian = false;
            }

            if (urldecode($tab) === '#limit') {
                $is_show_limit   = "show";
                $limit           = 'active';
                $is_active_limit = true;
            } else {
                $is_show_limit   = "";
                $limit           = '';
                $is_active_limit = false;
            }
        ?>
        <ul class="nav nav-pills budget-pills" id="myTab" role="tablist"
            style="overflow-x: auto; display: flex; flex-wrap: nowrap;">
            <?php
                if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN'):
            ?>
            <li class="nav-item mr-2">
                <a class="nav-link pb-4 font-weight-bold <?php echo $part ?>" title="Bidang / Bagian"
                    style="font-size:16px;" id="part-tab" data-toggle="tab" href="#part" role="tab" aria-controls="part"
                    aria-selected="<?php echo $is_active_part ?>"><i class="fa fa-tasks mr-2"></i>Unor/Bidang/Bagian</a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link pb-4 font-weight-bold <?php echo $tujuan_sasaran ?>" title="Tujuan & Sasaran"
                    style="font-size:16px;" id="tujuan_sasaran-tab" data-toggle="tab" href="#tujuan_sasaran" role="tab"
                    aria-controls="tujuan_sasaran" aria-selected="<?php echo $is_active_tujuan_sasaran ?>"><span
                        class="fa fa-book mr-2"></span> Tujuan & Sasaran</a>
            </li>
            <?php endif; ?>
            <li class="nav-item mr-2">
                <a class="nav-link pb-4 font-weight-bold <?php echo $program ?>" title="Program & Kegiatan"
                    style="font-size:16px;" id="program-tab" data-toggle="tab" href="#program" role="tab"
                    aria-controls="program" aria-selected="<?php echo $is_active_program ?>"><span
                        class="fa fa-book mr-2"></span> Program</a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link pb-4 font-weight-bold <?php echo $kegiatan ?>" title="Kegiatan"
                    style="font-size:16px;" id="kegiatan-tab" data-toggle="tab" href="#kegiatan" role="tab"
                    aria-controls="kegiatan" aria-selected="<?php echo $is_active_kegiatan ?>"><span
                        class="fa fa-file-code-o mr-2"></span> Kegiatan</a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link pb-4 font-weight-bold <?php echo $subkegiatan ?>" title="Sub Kegiatan"
                    style="font-size:16px;" id="subkegiatan-tab" data-toggle="tab" href="#subkegiatan" role="tab"
                    aria-controls="subkegiatan" aria-selected="<?php echo $is_active_subkegiatan ?>"><span
                        class="fa fa-file-o mr-2 text-success"></span> Sub Kegiatan</a>
            </li>
            <?php if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN'): ?>
            <li class="nav-item mr-2">
                <a class="nav-link pb-4 font-weight-bold <?php echo $kelompok ?>" title="Kelompok"
                    style="font-size:16px;" id="kelompok-tab" data-toggle="tab" href="#kelompok" role="tab"
                    aria-controls="kelompok" aria-selected="<?php echo $is_active_kelompok ?>"><span
                        class="fa fa-folder-open mr-2"></span> Kelompok</a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link pb-4 font-weight-bold <?php echo $jenis ?>" title="Jenis"
                    style="font-size:16px;" id="jenis-tab" data-toggle="tab" href="#jenis" role="tab"
                    aria-controls="jenis" aria-selected="<?php echo $is_active_jenis ?>"><span
                        class="fa fa-tags mr-2"></span> Jenis</a>
            </li>
            <?php endif; ?>
            <?php if (getSetting('ENTRI_URAIAN')): ?>
            <li class="nav-item mr-2">
                <a class="nav-link pb-4 font-weight-bold <?php echo $uraian ?>" title="Uraian Kegiatan"
                    style="font-size:16px;" id="uraian-tab" data-toggle="tab" href="#uraian" role="tab"
                    aria-controls="uraian" aria-selected="<?php echo $is_active_uraian ?>"><span
                        class="fa fa-files-o mr-2 text-info"></span> Uraian
                    Kegiatan</a>
            </li>
            <?php endif; ?>
            <?php if (getSetting('ENTRI_ANGKAS')): ?>
            <li class="nav-item">
                <a class="nav-link pb-4 font-weight-bold <?php echo $limit ?>" title="Limit Anggaran"
                    style="font-size:16px;" id="limit-tab" data-toggle="tab" href="#limit" role="tab"
                    aria-controls="limit" aria-selected="<?php echo $is_active_limit ?>"><span
                        class="fa fa-file-o mr-2 text-info"></span>Angkas</a>
            </li>
            <?php endif; ?>
        </ul>
        <div class="x_panel budget-panel">
            <div class="x_content">
                <div class="tab-content" id="myTabContent">
                    <?php
                        if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'VERIFICATOR'):
                    ?>
                    <div class="tab-pane <?php echo $part ?> <?php echo $is_show_part ?>" id="part" role="tabpanel"
                        aria-labelledby="part-tab">
                        <div class="listUnor"></div>
                        <div class="divider-dashed"></div>
                        <div class="listPart"></div>
                    </div>
                    <?php endif; ?>
                    <div class="tab-pane <?php echo $program ?> <?php echo $is_show_program ?>" id="program"
                        role="tabpanel" aria-labelledby="program-tab"></div>
                    <div class="tab-pane <?php echo $tujuan_sasaran ?> <?php echo $is_show_tujuan_sasaran ?>"
                        id="tujuan_sasaran" role="tabpanel" aria-labelledby="tujuan_sasaran-tab">
                        <div class="listTujuan"></div>
                        <div class="divider-dashed"></div>
                        <div class="listSasaran"></div>
                    </div>
                    <div class="tab-pane <?php echo $kegiatan ?> <?php echo $is_show_kegiatan ?>" id="kegiatan"
                        role="tabpanel" aria-labelledby="kegiatan-tab"></div>
                    <div class="tab-pane <?php echo $subkegiatan ?> <?php echo $is_show_subkegiatan ?>" id="subkegiatan"
                        role="tabpanel" aria-labelledby="subkegiatan-tab"></div>
                    <?php if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN'): ?>
                    <div class="tab-pane <?php echo $kelompok ?> <?php echo $is_show_kelompok ?>" id="kelompok"
                        role="tabpanel" aria-labelledby="kelompok-tab">
                        <div class="listKelompok"></div>
                    </div>
                    <div class="tab-pane <?php echo $jenis ?> <?php echo $is_show_jenis ?>" id="jenis"
                        role="tabpanel" aria-labelledby="jenis-tab">
                        <div class="listJenis"></div>
                    </div>
                    <?php endif; ?>
                    <div class="tab-pane <?php echo $uraian ?> <?php echo $is_show_uraian ?>" id="uraian"
                        role="tabpanel" aria-labelledby="uraian-tab"></div>
                    <div class="tab-pane <?php echo $limit ?> <?php echo $is_show_limit ?>" id="limit" role="tabpanel"
                        aria-labelledby="limit-tab"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Unor -->
<div class="modal fade modal-unor-edit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/update/ref_unors'), ['id' => 'formUnorEdit', 'data-parsley-validate' => ''], ['id' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Edit Unit Organisasi</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="unor">Nama Unor <span class="text-danger">*</span></label>
                    <input type="text" name="unor" id="unor" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Edit Part -->
<div class="modal fade modal-part-edit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/update/ref_parts/'), ['id' => 'formPartEdit', 'data-parsley-validate' => ''], ['id' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Edit Badan / Bidang / Bagian</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="program">Pilih Program <span class="text-danger">*</span></label>
                    <select name="program" id="program" required
                        data-parsley-errors-container="#help-block-program"></select>
                    <div id="help-block-program" class="help-block"></div>
                </div>
                <div class="form-group">
                    <label for="part-nama">Nama Badan / Bidang / Bagian <span class="text-danger">*</span></label>
                    <input type="text" name="part" id="part-nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="part-singkatan">Nama Singkatan <span class="text-danger">*</span></label>
                    <input type="text" name="part_singkatan" id="part-singkatan" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Edit Tujuan -->
<div class="modal fade modal-tujuan-edit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/update/ref_tujuan/'), ['id' => 'formTujuanEdit', 'data-parsley-validate' => ''], ['id' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Edit Tujuan</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="tujuan-nama">Isi Tujuan <span class="text-danger">*</span></label>
                    <input type="text" name="tujuan" id="tujuan-nama" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Edit Sasaran -->
<div class="modal fade modal-sasaran-edit" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/update/ref_sasaran/'), ['id' => 'formSasaranEdit', 'data-parsley-validate' => ''], ['id' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Edit Sasaran</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="sasaran-nama">Isi Sasaran <span class="text-danger">*</span></label>
                    <input type="text" name="sasaran" id="sasaran-nama" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Unor -->
<div class="modal fade modal-unor" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/tambah/unor'), ['id' => 'formUnor']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Unit Organisasi</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="unor">Nama Unor <span class="text-danger">*</span></label>
                    <input type="text" name="unor" id="unor" class="form-control"
                        placeholder="Masukan nama unor baru disini ..." required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Part -->
<div class="modal fade modal-part" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/tambah/part'), ['id' => 'formPart']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Badan / Bidang / Bagian</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="program">Pilih Program <span class="text-danger">*</span></label>
                    <select name="program" id="program" required
                        data-parsley-errors-container="#help-block-program"></select>
                    <div id="help-block-program" class="help-block"></div>
                </div>
                <div class="form-group">
                    <label for="part-nama">Nama Badan / Bidang / Bagian <span class="text-danger">*</span></label>
                    <input type="text" name="part" id="part-nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="part-singkatan">Nama Singkatan <span class="text-danger">*</span></label>
                    <input type="text" name="part_singkatan" id="part-singkatan" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Tujuan -->
<div class="modal fade modal-tujuan" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/tambah/tujuan'), ['id' => 'formTujuan']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-info text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Tujuan</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="unor">Pilih Unor <span class="text-danger">*</span></label>
                    <select name="unor" id="unor" required data-parsley-errors-container="#help-block-unor"></select>
                    <div id="help-block-unor" class="help-block"></div>
                </div>
                <div class="form-group">
                    <label for="tujuan-nama">Isi Tujuan <span class="text-danger">*</span></label>
                    <input type="text" name="tujuan" id="tujuan-nama" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Sasaran -->
<div class="modal fade modal-sasaran" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/tambah/sasaran'), ['id' => 'formSasaran']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-primary text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Sasaran</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="tujuan">Pilih Tujuan <span class="text-danger">*</span></label>
                    <select name="tujuan" id="tujuan" required
                        data-parsley-errors-container="#help-block-tujuan"></select>
                    <div id="help-block-tujuan" class="help-block"></div>
                </div>
                <div class="form-group">
                    <label for="sasaran-nama">Isi Sasaran <span class="text-danger">*</span></label>
                    <input type="text" name="sasaran" id="sasaran-nama" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Kelompok -->
<div class="modal fade modal-kelompok" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/tambah/kelompok'), ['id' => 'formKelompok']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-info text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Kelompok Belanja</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="kelompok-kode">Kode Kelompok <span class="text-danger">*</span></label>
                    <input type="text" name="kode" id="kelompok-kode" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="kelompok-nama">Isi Kelompok Belanja <span class="text-danger">*</span></label>
                    <input type="text" name="kelompok" id="kelompok-nama" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Edit Kelompok -->
<div class="modal fade modal-kelompok-edit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/update/ref_kelompok_belanja'), ['id' => 'formKelompokEdit', 'data-parsley-validate' => ''], ['id' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Edit Kelompok Belanja</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="kelompok-kode-edit">Kode Kelompok <span class="text-danger">*</span></label>
                    <input type="text" name="kode" id="kelompok-kode-edit" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="kelompok-nama-edit">Isi Kelompok Belanja <span class="text-danger">*</span></label>
                    <input type="text" name="kelompok" id="kelompok-nama-edit" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Jenis -->
<div class="modal fade modal-jenis" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/tambah/jenis'), ['id' => 'formJenis']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-info text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Jenis Belanja</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="jenis-kelompok">Pilih Kelompok <span class="text-danger">*</span></label>
                    <select name="kelompok" id="jenis-kelompok" required
                        data-parsley-errors-container="#help-block-jenis-kelompok"></select>
                    <div id="help-block-jenis-kelompok" class="help-block"></div>
                </div>
                <div class="form-group">
                    <label for="jenis-kode">Kode Jenis <span class="text-danger">*</span></label>
                    <input type="text" name="kode" id="jenis-kode" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="jenis-nama">Isi Jenis Belanja <span class="text-danger">*</span></label>
                    <input type="text" name="jenis" id="jenis-nama" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Edit Jenis -->
<div class="modal fade modal-jenis-edit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/update/ref_jenis_belanja'), ['id' => 'formJenisEdit', 'data-parsley-validate' => ''], ['id' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Edit Jenis Belanja</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="jenis-kelompok-edit">Pilih Kelompok <span class="text-danger">*</span></label>
                    <select name="kelompok" id="jenis-kelompok-edit" required
                        data-parsley-errors-container="#help-block-jenis-kelompok-edit"></select>
                    <div id="help-block-jenis-kelompok-edit" class="help-block"></div>
                </div>
                <div class="form-group">
                    <label for="jenis-kode-edit">Kode Jenis <span class="text-danger">*</span></label>
                    <input type="text" name="kode" id="jenis-kode-edit" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="jenis-nama-edit">Isi Jenis Belanja <span class="text-danger">*</span></label>
                    <input type="text" name="jenis" id="jenis-nama-edit" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Program -->
<div class="modal fade modal-program" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/tambah/program'), ['id' => 'formProgram']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Program</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="sasaran">Pilih Sasaran <span class="text-danger">*</span></label>
                    <select name="sasaran" id="sasaran" required
                        data-parsley-errors-container="#help-block-sasaran"></select>
                    <div id="help-block-sasaran" class="help-block"></div>
                </div>
                <div class="divider-dashed"></div>
                <div class="form-group">
                    <label for="kode_program">Kode Program <span class="text-danger">*</span></label>
                    <input type="text" id="kode_program" name="kode_program" class="form-control" required
                        data-parsley-pattern="^(([0-9.]?)*)+$"
                        data-parsley-remote="<?php echo base_url('app/programs/cek_kode/kodeprogram') ?>"
                        data-parsley-remote-reverse="false" data-parsley-remote-options='{ "type": "POST" }'
                        data-parsley-remote-message="Kode Program sudah pernah digunakan !"
                        data-parsley-trigger="change">
                </div>
                <div class="form-group">
                    <label for="program">Nama Program <span class="text-danger">*</span></label>
                    <input type="text" id="program" name="program" class="form-control" required
                        data-parsley-remote="<?php echo base_url('app/programs/cek_kode/namaprogram') ?>"
                        data-parsley-remote-reverse="false" data-parsley-remote-options='{ "type": "POST" }'
                        data-parsley-remote-message="Nama Program sudah pernah digunakan !"
                        data-parsley-trigger="keyup">
                </div>
                <div class="form-group">
                    <label for="bidang">Pilih Bidang <span class="text-danger">*</span></label>
                    <select name="bidang[]" id="bidang" multiple="multiple" required
                        data-parsley-errors-container="#help-block-bidang"></select>
                    <div id="help-block-bidang" class="help-block"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Kegiatan -->
<div class="modal fade modal-kegiatan" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/tambah/kegiatan'), ['id' => 'formKegiatan']); ?>
        <input type="hidden" name="part" value="<?php echo $this->session->userdata('part') ?>">
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Kegiatan</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <div class="form-group">
                    <label for="part">Pilih Badan / Bagian / Bidang <span class="text-danger">*</span></label>
                    <select name="part" id="part" required data-parsley-errors-container="#help-block-part"></select>
                    <div id="help-block-part"></div>
                </div> -->
                <div class="form-group">
                    <label for="program">Pilih Program <span class="text-danger">*</span></label>
                    <select name="program" id="program" required
                        data-parsley-errors-container="#help-block-program"></select>
                    <div id="help-block-program" class="help-block"></div>
                </div>
                <div class="divider-dashed"></div>
                <div class="form-group">
                    <label for="kode_kegiatan">Kode Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" id="kode_kegiatan" name="kode_kegiatan" class="form-control" required
                        data-parsley-pattern="^(([0-9.]?)*)+$"
                        data-parsley-remote="<?php echo base_url('app/programs/cek_kode/kegiatan') ?>"
                        data-parsley-remote-reverse="false"
                        data-parsley-remote-message="Kode Kegiatan sudah pernah digunakan !"
                        data-parsley-trigger="change">
                </div>
                <div class="form-group">
                    <label for="kegiatan">Nama Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" id="kegiatan" name="kegiatan" class="form-control" required
                        data-parsley-trigger="keyup">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Sub Kegiatan -->
<div class="modal fade modal-subkegiatan" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/tambah/subkegiatan'), ['id' => 'formSubKegiatan']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Sub Kegiatan</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="kegiatan">Pilih Kegiatan <span class="text-danger">*</span></label>
                    <select name="kegiatan" id="kegiatan" required
                        data-parsley-errors-container="#help-block-kegiatan"></select>
                    <div id="help-block-kegiatan" class="help-block"></div>
                </div>
                <div class="divider-dashed"></div>
                <div class="form-group">
                    <label for="kode_subkegiatan">Kode Sub Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="kode_subkegiatan" class="form-control" required
                        data-parsley-remote="<?php echo base_url('app/programs/cek_kode/subkegiatan') ?>"
                        data-parsley-remote-reverse="false" data-parsley-remote-options='{ "type": "POST" }'
                        data-parsley-remote-message="Kode Sub Kegiatan sudah pernah digunakan !"
                        data-parsley-pattern="^(([0-9.]?)*)+$" data-parsley-trigger="focusout">
                </div>
                <div class="form-group">
                    <label for="subkegiatan">Nama Sub Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="subkegiatan" class="form-control" required data-parsley-trigger="keyup">
                </div>
                <!-- <div class="form-group">
                    <label for="total_pagu">Total Pagu Anggaran <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text rounded-0" id="basic-addon1">Rp.</span>
                        </div>
                        <input type="text" name="total_pagu" id="total_pagu" class="form-control" required data-parsley-trigger="keyup" data-parsley-errors-container="#help-block-total-pagu">
                    </div>
                    <div id="help-block-total-pagu"></div>
                </div> -->
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Uraian -->
<div class="modal fade modal-uraian" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('/app/programs/tambah/uraian'), ['id' => 'formUraian']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Uraian</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="kegiatan">Pilih Kegiatan <span class="text-danger">*</span></label>
                    <select name="kegiatan" id="kegiatan" required
                        data-parsley-errors-container="#help-block-kegiatan"></select>
                    <div id="help-block-kegiatan" class="help-block"></div>
                </div>
                <div class="form-group">
                    <label for="subkegiatan">Pilih Sub Kegiatan <span class="text-danger">*</span></label>
                    <select name="subkegiatan" id="subkegiatan" style="width:100%" required
                        data-parsley-errors-container="#help-block-subkegiatan"></select>
                    <div id="help-block-subkegiatan" class="help-block"></div>
                </div>
                <div class="form-group">
                    <label for="jenis_belanja">Pilih Jenis Belanja <span class="text-danger">*</span></label>
                    <select name="jenis_belanja" id="jenis_belanja" style="width:100%" required
                        data-parsley-errors-container="#help-block-jenis-belanja"></select>
                    <div id="help-block-jenis-belanja" class="help-block"></div>
                </div>
                <div class="divider-dashed"></div>
                <div class="form-group">
                    <label for="kode_uraian">Kode Uraian <span class="text-danger">*</span></label>
                    <input type="text" id="kode_uraian" name="kode_uraian" class="form-control" required
                        data-parsley-pattern="^(([0-9.]?)*)+$" data-parsley-trigger="focusout">
                </div>
                <div class="form-group">
                    <label for="nama_uraian">Uraian <span class="text-danger">*</span></label>
                    <input type="text" id="nama_uraian" name="nama_uraian" class="form-control" required
                        data-parsley-trigger="focusout">
                </div>
                <div class="form-group">
                    <label class="d-block mb-1">Status Aktif</label>
                    <label class="switch-toggle mb-0" title="Aktif">
                        <input type="checkbox" name="is_aktif" value="Y" checked>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Alokasi Pagu Kegiatan -->
<div class="modal fade modal-alokasipagu" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <?php echo form_open('#', ['id' => 'formAlokasiPagu', 'data-parsley-validate' => ''], ['is_perubahan' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Alokasi Pagu Anggaran</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group d-none" id="pagu-perubahan-toggle">
                    <label class="d-block mb-2">Pagu Perubahan</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipe_pagu_perubahan" id="paguTetap"
                            value="tetap">
                        <label class="form-check-label font-weight-bold" for="paguTetap">
                            <i class="fa fa-lock mr-1"></i>Pagu Tetap
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipe_pagu_perubahan" id="paguBerubah"
                            value="berubah" checked>
                        <label class="form-check-label font-weight-bold" for="paguBerubah">
                            <i class="fa fa-pencil mr-1"></i>Berubah
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah Pagu</label>
                    <input type="text" name="jumlah" id="jumlah" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i
                        class="fa fa-close mr-2"></i>Batal</button>
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Limit Anggaran -->
<div class="modal fade modal-limit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?php echo form_open(base_url('app/programs/input_limit'), ['id' => 'formLimit', 'data-parsley-validate' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Input Anggaran KAS</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="uraian">Cari Uraian <span class="text-danger">*</span></label>
                    <select name="uraian" id="uraian" required
                        data-parsley-errors-container="#help-block-uraian"></select>
                    <div id="help-block-uraian" class="help-block"></div>
                </div>
                <div class="row my-4">
                    <div class="col-md-12 d-flex flex-wrap">
                        <div id="total-pagu-awal" class="p-3 mr-2 mb-2 shadow rounded-xl border border-success">0</div>
                        <div id="sisa-limit-uraian" class="p-3 mr-2 mb-2 shadow rounded-xl border border-warning">0
                        </div>
                        <div id="total-limit-uraian" class="p-3 mb-2 shadow rounded-xl border border-danger">0</div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="jumlah">Jumlah KAS</label>
                            <input type="text" name="jumlah" id="jumlah" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="periode">Periode <span class="text-danger">*</span></label>
                            <select name="periode[]" id="periode" multiple="multiple" required
                                data-parsley-errors-container="#help-block-periode">
                            </select>
                            <div id="help-block-periode" class="help-block"></div>
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
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Update Limit Anggaran -->
<div class="modal fade modal-update-limit" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog">
        <?php echo form_open(base_url('app/programs/update_limit'), ['id' => 'formUpdateLimit', 'data-parsley-validate' => ''], ['id' => '', 'uraian_id' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Perbaharui Limit</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="jumlah">Jumlah KAS</label>
                    <input type="text" name="jumlah" id="jumlah" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="periode_update">Periode</label>
                    <select name="periode[]" id="periode_update" multiple="multiple" required class="form-control"
                        data-parsley-errors-container="#help-block-periode-update" style="width: 100%;">
                    </select>
                    <div id="help-block-periode-update" class="help-block"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i
                        class="fa fa-close mr-2"></i>Batal</button>
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal Rekonsiliasi Anggaran -->
<div class="modal fade modal-rekon" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <?php echo form_open_multipart(base_url('app/programs/rekon_anggaran'), ['id' => 'formRekonAnggaran', 'data-parsley-validate' => '']); ?>
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-success text-white border-0 rounded-top py-4 px-4">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-white bg-opacity-20 rounded-circle p-3 mr-3">
                        <i class="fa fa-file-excel-o fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="modal-title mb-0 fw-semibold">Rekonsiliasi Anggaran</h4>
                        <small class="opacity-75">Upload file Excel untuk rekonsiliasi data anggaran</small>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-1" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info border-0 bg-light-info text-info mb-4 rounded-lg" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="fa fa-info-circle fa-lg mr-3 mt-1"></i>
                        <div>
                            <strong>Format File:</strong> .xlsx atau .xls (maksimal 5MB)
                            <br><small class="text-muted">Pastikan struktur kolom sesuai template yang
                                disediakan</small>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="anggaran" class="form-label fw-medium text-dark mb-2">Jenis Anggaran <span
                            class="text-danger">*</span></label>
                    <div class="position-relative">
                        <select name="anggaran" id="anggaran" required
                            class="form-control form-control-lg custom-select"
                            data-parsley-errors-container="#help-block-anggaran" aria-readonly="true" disabled>
                            <option value="">-- Pilih Jenis Anggaran --</option>
                            <option value="1"
                                <?php echo $this->session->userdata('is_perubahan') === "1" ? "selected" : ""; ?>>
                                Perubahan</option>
                            <option value="0"
                                <?php echo $this->session->userdata('is_perubahan') === "0" ? "selected" : ""; ?>>
                                Murni</option>
                        </select>
                        <div class="select-arrow"><i class="fa fa-chevron-down"></i></div>
                    </div>
                    <div id="help-block-anggaran" class="help-block text-danger mt-2"></div>
                    <small class="form-text text-muted">Jenis anggaran ditentukan oleh periode aktif saat ini</small>
                </div>

                <div class="form-group mb-4">
                    <label for="excelFile" class="form-label fw-medium text-dark mb-2">File Excel <span
                            class="text-danger">*</span></label>
                    <div class="file-upload-wrapper border-2 border-dashed border-secondary rounded-lg p-4 text-center transition-all"
                        id="fileUploadArea">
                        <input type="file" class="form-control form-control-file d-none" id="excelFile" name="file"
                            accept=".xlsx,.xls" required data-parsley-excluded="false"
                            data-parsley-errors-container="#help-block-file" aria-describedby="fileHelp">
                        <div class="upload-icon mb-3">
                            <i class="fa fa-cloud-upload fa-3x text-secondary"></i>
                        </div>
                        <h6 class="fw-medium text-dark mb-1">Seret & lepas file di sini</h6>
                        <p class="text-muted mb-2">atau klik untuk memilih file</p>
                        <small class="text-muted d-block" id="fileHelp">Format: .xlsx, .xls | Maks: 5MB</small>
                        <div class="selected-file-info d-none mt-3 p-2 bg-light rounded text-left">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-file-excel-o text-success fa-lg mr-3"></i>
                                    <div>
                                        <div class="fw-medium" id="selectedFileName"></div>
                                        <small class="text-muted" id="selectedFileSize"></small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-link text-danger p-0" id="removeFile">
                                    <i class="fa fa-times"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="help-block-file" class="help-block text-danger mt-2"></div>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="confirmRecon" required>
                    <label class="form-check-label text-dark" for="confirmRecon">
                        Saya memastikan data pada file Excel sudah benar dan siap direkonsiliasi
                    </label>
                    <div id="help-block-confirm" class="help-block text-danger mt-2"></div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0 px-4 py-3 rounded-bottom">
                <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-dismiss="modal">
                    <i class="fa fa-close mr-2"></i>Batal
                </button>
                <button type="submit" class="btn btn-success btn-lg px-5" id="btnSubmitRecon">
                    <i class="fa fa-save mr-2"></i>Submit Rekonsiliasi
                </button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<!-- Modal Konfirmasi Hapus -->
<div class="modal fade modal-hapus" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <div class="modal-body p-5 text-center position-relative">
                <button type="button" class="close position-absolute" style="top:15px;right:20px;" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle bg-danger" style="width:90px;height:90px;box-shadow:0 8px 20px rgba(220,53,69,.4);">
                    <i class="fa fa-trash fa-2x text-white"></i>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">Hapus Data?</h4>
                <p class="text-muted mb-1" id="hapus-message">Apakah anda yakin akan menghapus data tersebut ?</p>
                <small class="text-danger"><i class="fa fa-info-circle mr-1"></i>Data yang dihapus tidak dapat dikembalikan</small>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex">
                <button type="button" class="btn btn-light border flex-fill py-2 font-weight-bold" data-dismiss="modal">
                    <i class="fa fa-close mr-2"></i>Batal
                </button>
                <button type="button" class="btn btn-danger flex-fill py-2 font-weight-bold" id="btn-confirm-hapus" style="box-shadow:0 4px 12px rgba(220,53,69,.4);">
                    <i class="fa fa-trash mr-2"></i>Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Uraian Jenis Belanja -->
<div class="modal fade modal-uraian-jenis" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-0">
            <div class="modal-header bg-info text-white rounded-0">
                <h4 class="modal-title" id="myModalLabelDetailUraian">Detail Uraian Jenis Belanja</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="detail-uraian-jenis-loading" class="text-center py-5">
                    <i class="fa fa-spinner fa-pulse fa-3x text-primary"></i>
                </div>
                <div id="detail-uraian-jenis-content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    async function loadUraianJenis(id, namaJenis) {
        $('#detail-uraian-jenis-loading').show();
        $('#detail-uraian-jenis-content').empty().hide();
        $('#myModalLabelDetailUraian').text(`Detail Uraian - ${namaJenis}`);
        try {
            const req = await fetch(`${_uri}/app/programs/uraian_by_jenis?id=${id}`);
            const res = await req.json();
            $('#detail-uraian-jenis-loading').hide();
            if (res.code === 200) {
                $('#detail-uraian-jenis-content').html(res.result).show();
            } else {
                $('#detail-uraian-jenis-content').html(`<div class="alert alert-danger mb-0">${res.msg}</div>`).show();
            }
        } catch (e) {
            $('#detail-uraian-jenis-loading').hide();
            $('#detail-uraian-jenis-content').html(`<div class="alert alert-danger mb-0">Gagal memuat data uraian</div>`).show();
        }
    }

    window.DetailUraian = function(id, namaJenis) {
        loadUraianJenis(id, namaJenis);
        $('.modal-uraian-jenis').modal('show');
    };

    $(document).on('click', '.btn-detail-uraian', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        DetailUraian(id, nama);
    });
});

$(function() {
    async function getListTujuan() {
        const req = await fetch(`${_uri}/app/programs/tujuan`);
        const res = await req.json();
        return res;
    }

    async function getListSasaran() {
        const req = await fetch(`${_uri}/app/programs/sasaran`);
        const res = await req.json();
        return res;
    }

    async function getListKelompok() {
        const req = await fetch(`${_uri}/app/programs/kelompok`);
        const res = await req.json();
        return res;
    }

    async function getListJenis() {
        const req = await fetch(`${_uri}/app/programs/jenis`);
        const res = await req.json();
        return res;
    }

    async function getListUnor() {
        const req = await fetch(`${_uri}/app/programs/unor`);
        const res = await req.json();
        return res;
    }

    async function getListPart() {
        const req = await fetch(`${_uri}/app/programs/part`);
        const res = await req.json();
        return res;
    }

    async function getListProgram() {
        const req = await fetch(`${_uri}/app/programs/program`);
        const res = await req.json();
        return res;
    }

    async function getListKegiatan() {
        const req = await fetch(`${_uri}/app/programs/kegiatan`);
        const res = await req.json();
        return res;
    }

    async function getListSubKegiatan() {
        const req = await fetch(`${_uri}/app/programs/sub_kegiatan`);
        const res = await req.json();
        return res;
    }

    async function getListUraian() {
        const req = await fetch(`${_uri}/app/programs/uraian`);
        const res = await req.json();
        return res;
    }

    async function getListLimit() {
        const req = await fetch(`${_uri}/app/programs/uraian_limit`);
        const res = await req.json();
        return res;
    }
    // Initial load
    $('.listTujuan,.listSasaran,.listKelompok,.listJenis,.listPart,#kegiatan,#subkegiatan,#uraian,#program,#limit').html(
        `<div class="d-flex justify-content-center align-items-center align-self-center py-4"><img src="${_uri}/template/assets/loader/motion-blur.svg" alt="Loading" class="mr-3" width="40"><h4>Loading data, mohon tunggu.</h4></div>`
    );
    // Get Tab Active
    let tab_active = urlParams.get('tab');
    // if tab active same as url
    if (tab_active === '#kegiatan') {
        getListKegiatan().then((data) => {
            if (data.code === 404) {
                $('#kegiatan').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done();
                return false;
            }
            $('#kegiatan').html(data.result);
            NProgress.done()
            var subKegiatanList = new List('listKegiatan', option);
            $('#listKegiatan').data('listjs', subKegiatanList);
        });
    } else if (tab_active === '#subkegiatan') {
        getListSubKegiatan().then((data) => {
            if (data.code === 404) {
                $('#subkegiatan').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('#subkegiatan').html(data.result);
            NProgress.done()
            var subKegiatanList = new List('listSubKegiatan', option);
            $('#listSubKegiatan').data('listjs', subKegiatanList);
        });
    } else if (tab_active === '#part') {
        getListUnor().then((data) => {
            if (data.code === 404) {
                $('.listUnor').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listUnor').html(data.result);
            NProgress.done()
            var unorList = new List('listUnor', option);
            $('#listUnor').data('listjs', unorList);
        });
        getListPart().then((data) => {
            if (data.code === 404) {
                $('.listPart').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listPart').html(data.result);
            NProgress.done()
            var partList = new List('listPart', option);
            $('#listPart').data('listjs', partList);
        });
    } else if (tab_active === '#uraian') {
        getListUraian().then((data) => {
            if (data.code === 404) {
                $('#uraian').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('#uraian').html(data.result);
            NProgress.done()
            var uraianList = new List('listUraian', option);
            $('#listUraian').data('listjs', uraianList);
        });
    } else if (tab_active === '#limit') {
        getListLimit().then((data) => {
            if (data.code === 404) {
                $('#limit').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('#limit').html(data.result);
            NProgress.done()
            var listLimit = new List('listLimit', option);
            $('#listLimit').data('listjs', listLimit);
        });
    } else if (tab_active === '#tujuan_sasaran') {
        getListTujuan().then((data) => {
            if (data.code === 404) {
                $('.listTujuan').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listTujuan').html(data.result);
            NProgress.done()
            var tujuanList = new List('listTujuan', option);
            $('#listTujuan').data('listjs', tujuanList);
        });
        getListSasaran().then((data) => {
            if (data.code === 404) {
                $('.listSasaran').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listSasaran').html(data.result);
            NProgress.done()
            var sasaranList = new List('listSasaran', option);
            $('#listSasaran').data('listjs', sasaranList);
        });

    } else if (tab_active === '#kelompok') {
        getListKelompok().then((data) => {
            if (data.code === 404) {
                $('.listKelompok').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listKelompok').html(data.result);
            NProgress.done()
            var kelompokList = new List('listKelompok', option);
            $('#listKelompok').data('listjs', kelompokList);
        });

    } else if (tab_active === '#jenis') {
        getListJenis().then((data) => {
            if (data.code === 404) {
                $('.listJenis').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listJenis').html(data.result);
            NProgress.done()
            var jenisList = new List('listJenis', option);
            $('#listJenis').data('listjs', jenisList);
        });

    } else {
        getListProgram().then((data) => {
            if (data.code === 404) {
                $('#program').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('#program').html(data.result);
            NProgress.done()
            var programList = new List('listProgram', option);
            $('#listProgram').data('listjs', programList);
        });
    }

    $(document).on("click", "#myTab a[href='#tujuan_sasaran']", function(e) {
        let _ = $(this),
            href = _.attr('href');
        // console.log(_.attr('href'))
        const url = new URL(window.location.href);
        url.searchParams.set('tab', href);
        history.pushState({}, "", url);
        NProgress.start();
        document.title = _.attr('title');
        getListTujuan().then((data) => {
            if (data.code === 404) {
                $('.listTujuan').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listTujuan').html(data.result);
            NProgress.done()
            var tujuanList = new List('listTujuan', option);
            $('#listTujuan').data('listjs', tujuanList);
        });
        getListSasaran().then((data) => {
            if (data.code === 404) {
                $('.listSasaran').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listSasaran').html(data.result);
            NProgress.done()
            var sasaranList = new List('listSasaran', option);
            $('#listSasaran').data('listjs', sasaranList);
        });
    })

    $(document).on("click", "#myTab a[href='#kelompok']", function(e) {
        let _ = $(this),
            href = _.attr('href');
        const url = new URL(window.location.href);
        url.searchParams.set('tab', href);
        history.pushState({}, "", url);
        NProgress.start();
        document.title = _.attr('title');
        getListKelompok().then((data) => {
            if (data.code === 404) {
                $('.listKelompok').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listKelompok').html(data.result);
            NProgress.done()
            var kelompokList = new List('listKelompok', option);
            $('#listKelompok').data('listjs', kelompokList);
        });
    })

    $(document).on("click", "#myTab a[href='#jenis']", function(e) {
        let _ = $(this),
            href = _.attr('href');
        const url = new URL(window.location.href);
        url.searchParams.set('tab', href);
        history.pushState({}, "", url);
        document.title = _.attr('title');
        NProgress.start();
        getListJenis().then((data) => {
            if (data.code === 404) {
                $('.listJenis').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listJenis').html(data.result);
            NProgress.done()
            var jenisList = new List('listJenis', option);
            $('#listJenis').data('listjs', jenisList);
        });
    })

    $(document).on("click", "#myTab a[href='#part']", function(e) {
        let _ = $(this),
            href = _.attr('href');
        // console.log(_.attr('href'))
        const url = new URL(window.location.href);
        url.searchParams.set('tab', href);
        history.pushState({}, "", url);
        NProgress.start();
        document.title = _.attr('title');
        // window.location.replace(url);
        getListUnor().then((data) => {
            if (data.code === 404) {
                $('.listUnor').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listUnor').html(data.result);
            NProgress.done()
            var unorList = new List('listUnor', option);
            $('#listUnor').data('listjs', unorList);
        });
        getListPart().then((data) => {
            if (data.code === 404) {
                $('.listPart').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('.listPart').html(data.result);
            NProgress.done()
            var partList = new List('listPart', option);
            $('#listPart').data('listjs', partList);
        });
    })

    $(document).on("click", "#myTab a[href='#program']", function(e) {
        let _ = $(this),
            href = _.attr('href');
        // console.log(_.attr('href'))
        const url = new URL(window.location.href);
        url.searchParams.set('tab', href);
        history.pushState({}, "", url);
        NProgress.start();
        document.title = _.attr('title');
        // window.location.replace(url);
        getListProgram().then((data) => {
            if (data.code === 404) {
                $('#program').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('#program').html(data.result);
            NProgress.done()
            var programList = new List('listProgram', option);
            $('#listProgram').data('listjs', programList);
        });
    })

    $(document).on("click", "#myTab a[href='#kegiatan']", function(e) {
        let _ = $(this),
            href = _.attr('href');
        // console.log(_.attr('href'))
        const url = new URL(window.location.href);
        url.searchParams.set('tab', href);
        history.pushState({}, "", url);
        NProgress.start();
        document.title = _.attr('title');
        // window.location.replace(url);
        getListKegiatan().then((data) => {
            if (data.code === 404) {
                $('#kegiatan').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('#kegiatan').html(data.result);
            NProgress.done()
            var subKegiatanList = new List('listKegiatan', option);
            $('#listKegiatan').data('listjs', subKegiatanList);
        });
    })

    $(document).on("click", "#myTab a[href='#subkegiatan']", function(e) {
        let _ = $(this),
            href = _.attr('href');
        // console.log(_.attr('href'))
        const url = new URL(window.location.href);
        url.searchParams.set('tab', href);
        history.pushState({}, "", url);
        NProgress.start();
        document.title = _.attr('title');
        // window.location.replace(url);
        getListSubKegiatan().then((data) => {
            if (data.code === 404) {
                $('#subkegiatan').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('#subkegiatan').html(data.result);
            NProgress.done();
            var subKegiatanList = new List('listSubKegiatan', option);
            $('#listSubKegiatan').data('listjs', subKegiatanList);
        });
    })

    $(document).on("click", "#myTab a[href='#uraian']", function(e) {
        let _ = $(this),
            href = _.attr('href');
        // console.log(_.attr('href'))
        const url = new URL(window.location.href);
        url.searchParams.set('tab', href);
        history.pushState({}, "", url);
        document.title = _.attr('title');
        NProgress.start();
        // window.location.replace(url);
        getListUraian().then((data) => {
            if (data.code === 404) {
                $('#uraian').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('#uraian').html(data.result);
            NProgress.done();
            var uraianList = new List('listUraian', option);
            $('#listUraian').data('listjs', uraianList);
        });
    })

    $(document).on("click", "#myTab a[href='#limit']", function(e) {
        let _ = $(this),
            href = _.attr('href');
        // console.log(_.attr('href'))
        const url = new URL(window.location.href);
        url.searchParams.set('tab', href);
        history.pushState({}, "", url);
        document.title = _.attr('title');
        NProgress.start();
        // window.location.replace(url);
        getListLimit().then((data) => {
            if (data.code === 404) {
                $('#limit').html(
                    `<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`
                );
                NProgress.done()
                return false;
            }
            $('#limit').html(data.result);
            NProgress.done();
            var listLimit = new List('listLimit', option);
            $('#listLimit').data('listjs', listLimit);
        });
    })

    var option = {
        valueNames: ['nama', 'kode'],
        searchColumns: ['nama', 'kode'],
        page: 10,
        pagination: true,
        pagination: [{
            item: "<li class='page-item rounded-0'><a class='page page-link rounded-0' href='#'></a></li>"
        }],
        searchDelay: 350,
        indexAsync: true
    };

    $(document).on("click", ".reset-listjs", function() {
        var $wrap = $('#' + $(this).data('target'));
        var list = $wrap.data('listjs');
        var searchInput = $wrap.find('input.search, input.fuzzy-search');
        searchInput.val('');
        if (list) {
            list.search('');
            list.show(1, option.page);
        } else {
            $wrap.find('ul.pagination .page').first().trigger('click');
        }
        // Clear saved state for this list
        try {
            var states = JSON.parse(localStorage.getItem('listjs_state') || '{}');
            var listId = $wrap.attr('id');
            if (states[listId]) {
                delete states[listId];
                localStorage.setItem('listjs_state', JSON.stringify(states));
            }
        } catch (e) {}
    });
})
</script>

<script>
$(function() {

    // section edit modal
    var MODAL_UNOR = $(".modal-unor"),
        LIST_UNOR = MODAL_UNOR.find(".listUnor"),
        FORM_UNOR = MODAL_UNOR.find("form#formUnor");

    var MODAL_UNOR_EDIT = $(".modal-unor-edit"),
        FORM_UNOR_EDIT = MODAL_UNOR_EDIT.find("form#formUnorEdit");

    var MODAL_PART_EDIT = $(".modal-part-edit"),
        FORM_PART_EDIT = MODAL_PART_EDIT.find("form#formPartEdit");

    // section add modal
    var MODAL_TUJUAN = $(".modal-tujuan"),
        FORM_TUJUAN = MODAL_TUJUAN.find("form#formTujuan");

    var MODAL_TUJUAN_EDIT = $(".modal-tujuan-edit"),
        FORM_TUJUAN_EDIT = MODAL_TUJUAN_EDIT.find("form#formTujuanEdit");

    var MODAL_SASARAN = $(".modal-sasaran"),
        FORM_SASARAN = MODAL_SASARAN.find("form#formSasaran");

    var MODAL_SASARAN_EDIT = $(".modal-sasaran-edit"),
        FORM_SASARAN_EDIT = MODAL_SASARAN_EDIT.find("form#formSasaranEdit");

    var MODAL_KELOMPOK = $(".modal-kelompok"),
        FORM_KELOMPOK = MODAL_KELOMPOK.find("form#formKelompok");

    var MODAL_KELOMPOK_EDIT = $(".modal-kelompok-edit"),
        FORM_KELOMPOK_EDIT = MODAL_KELOMPOK_EDIT.find("form#formKelompokEdit");

    var MODAL_JENIS = $(".modal-jenis"),
        FORM_JENIS = MODAL_JENIS.find("form#formJenis");

    var MODAL_JENIS_EDIT = $(".modal-jenis-edit"),
        FORM_JENIS_EDIT = MODAL_JENIS_EDIT.find("form#formJenisEdit");

    var MODAL_PART = $(".modal-part"),
        FORM_PART = MODAL_PART.find("form#formPart");

    var MODAL_KEGIATAN = $(".modal-kegiatan"),
        FORM_KEGIATAN = MODAL_KEGIATAN.find("form#formKegiatan");

    var MODAL_PROGRAM = $(".modal-program"),
        FORM_PROGRAM = MODAL_PROGRAM.find("form#formProgram");

    var MODAL_SUBKEGIATAN = $(".modal-subkegiatan"),
        FORM_SUBKEGIATAN = MODAL_SUBKEGIATAN.find("form#formSubKegiatan");

    var MODAL_URAIAN = $(".modal-uraian"),
        FORM_URAIAN = MODAL_URAIAN.find("form#formUraian");

    var MODAL_LIMIT = $(".modal-limit"),
        FORM_LIMIT = MODAL_LIMIT.find("form#formLimit");

    var MODAL_UPDATE_LIMIT = $(".modal-update-limit"),
        FORM_UPDATE_LIMIT = MODAL_UPDATE_LIMIT.find("form#formUpdateLimit");

    // expose untuk fungsi global (mis. UpdateLimit di luar closure)
    window.MODAL_UPDATE_LIMIT = MODAL_UPDATE_LIMIT;
    window.FORM_UPDATE_LIMIT = FORM_UPDATE_LIMIT;

    var MODAL_REKON_ANGGARAN = $(".modal-rekon"),
        FORM_REKON_ANGGARAN = MODAL_REKON_ANGGARAN.find("form#formRekonAnggaran");

    // $('input[name="jumlah"]').inputmask("decimal", {
    //     alias: "numeric",
    //     groupSeparator: ".",
    //     autoGroup: true,
    //     digits: 0,
    //     digitsOptional: false,
    //     prefix: "",
    //     placeholder: "0",
    //     rightAlign: false,
    //     allowMinus: false,
    //     integerDigits: 15 // batas maksimal angka, bisa diubah
    // });

    // let total_pagu = MODAL_SUBKEGIATAN.find('input[name="total_pagu"]');
    // $(total_pagu).inputmask("decimal", {
    //     alias: "numeric",
    //     groupSeparator: ".",
    //     autoGroup: true,
    //     digits: 0,
    //     digitsOptional: false,
    //     prefix: "",
    //     placeholder: "0",
    //     rightAlign: false,
    //     allowMinus: false,
    //     integerDigits: 15 // batas maksimal angka, bisa diubah
    // });

    $('select#part, select#kegiatan, select#subkegiatan').each(function() {
        $(this).select2({
            dropdownParent: $(this).parent()
        });
    })

    // select Tujuan
    $('select[name="tujuan"]').select2({
        placeholder: 'Pilih Tujuan',
        allowClear: true,
        // maximumSelectionLength: 1,
        width: "100%",
        // theme: "classic",
        dropdownParent: MODAL_SASARAN,
        // templateResult: formatUserSelect2,
        ajax: {
            delay: 250,
            method: 'post',
            url: '<?php echo base_url("app/programs/getTujuan") ?>',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // search term
                };
            },
            cache: true,
            processResults: function(data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: data
                };
            }
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });

    // select Kelompok Belanja (modal Jenis Tambah)
    $('#jenis-kelompok').select2({
        placeholder: 'Pilih Kelompok',
        allowClear: true,
        width: "100%",
        dropdownParent: MODAL_JENIS,
        ajax: {
            delay: 250,
            method: 'post',
            url: '<?php echo base_url("app/programs/getKelompok") ?>',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // search term
                };
            },
            cache: true,
            processResults: function(data) {
                return {
                    results: data
                };
            }
        }
    });

    // select Kelompok Belanja (modal Jenis Edit)
    $('#jenis-kelompok-edit').select2({
        placeholder: 'Pilih Kelompok',
        allowClear: true,
        width: "100%",
        dropdownParent: MODAL_JENIS_EDIT,
        ajax: {
            delay: 250,
            method: 'post',
            url: '<?php echo base_url("app/programs/getKelompok") ?>',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // search term
                };
            },
            cache: true,
            processResults: function(data) {
                return {
                    results: data
                };
            }
        }
    });

    // select Sasaran
    $('select[name="sasaran"]').select2({
        placeholder: 'Pilih Sasaran',
        allowClear: true,
        // maximumSelectionLength: 1,
        width: "100%",
        // theme: "classic",
        dropdownParent: MODAL_PROGRAM,
        // templateResult: formatUserSelect2,
        ajax: {
            delay: 250,
            method: 'post',
            url: '<?php echo base_url("app/programs/getSasaran") ?>',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // search term
                };
            },
            cache: true,
            processResults: function(data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: data
                };
            }
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });

    // select Unor
    $('select[name="unor"]').select2({
        placeholder: 'Pilih Unor',
        allowClear: true,
        // maximumSelectionLength: 1,
        width: "100%",
        // theme: "classic",
        dropdownParent: MODAL_TUJUAN,
        // templateResult: formatUserSelect2,
        ajax: {
            delay: 250,
            method: 'post',
            url: '<?php echo base_url("app/programs/getUnor") ?>',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // search term
                };
            },
            cache: true,
            processResults: function(data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: data
                };
            }
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });

    // select part
    $('select#bidang').select2({
        placeholder: 'Pilih Bidang',
        allowClear: false,
        tags: true,
        tokenSeparators: [',', ' '],
        // maximumSelectionLength: 1,
        width: "100%",
        // theme: "classic",
        // dropdownParent: MODAL_KEGIATAN,
        ajax: {
            delay: 250,
            method: 'post',
            url: '<?php echo base_url("app/programs/getParts") ?>',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // search term
                };
            },
            cache: false,
            processResults: function(data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: data
                };
            }
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });

    // select program
    $('select[name="program"]').select2({
        placeholder: 'Pilih Program',
        // allowClear: true,
        // maximumSelectionLength: 1,
        width: "100%",
        // theme: "classic",
        // dropdownParent: MODAL_KEGIATAN,
        // templateResult: formatUserSelect2,
        ajax: {
            // delay: 250,
            method: 'post',
            url: '<?php echo base_url("app/programs/getProgram") ?>',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // search term
                };
            },
            cache: false,
            processResults: function(data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: data
                };
            }
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });

    // select kegiatan
    $('select[name="kegiatan"]').select2({
        placeholder: 'Pilih Kegiatan',
        // allowClear: true,
        // maximumSelectionLength: 1,
        width: "100%",
        // theme: "classic",
        // dropdownParent: MODAL_SUBKEGIATAN,
        // templateResult: formatUserSelect2,
        ajax: {
            delay: 350,
            method: 'post',
            url: '<?php echo base_url("app/programs/getKegiatan") ?>',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // search term
                };
            },
            cache: true,
            processResults: function(data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: data
                };
            }
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });

    $('select[name="kegiatan"]').on("change", function() {
        let id = $(this).val();
        // select subkegiatan
        $('select[name="subkegiatan"]').val('').trigger('change')
        $('select[name="subkegiatan"]').select2({
            placeholder: 'Pilih Sub Kegiatan',
            allowClear: true,
            // maximumSelectionLength: 1,
            width: "100%",
            // theme: "classic",
            // dropdownParent: MODAL_SUBKEGIATAN,
            // templateResult: formatUserSelect2,
            ajax: {
                delay: 350,
                method: 'post',
                url: '<?php echo base_url("app/select2/ajaxSubKegiatan") ?>',
                dataType: 'json',
                data: function(params) {
                    return {
                        searchTerm: params.term, // search term
                        refId: id
                    };
                },
                cache: true,
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data
                    };
                }
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }
        });
    });

    // select jenis belanja (modal Uraian)
    $('select[name="jenis_belanja"]').select2({
        placeholder: 'Pilih Jenis Belanja',
        allowClear: true,
        width: "100%",
        dropdownParent: MODAL_URAIAN,
        ajax: {
            delay: 350,
            method: 'post',
            url: '<?php echo base_url("app/programs/getJenis") ?>',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // search term
                };
            },
            cache: true,
            processResults: function(data) {
                return {
                    results: data
                };
            }
        }
    });

    // select uraian
    $('select[name="uraian"]').select2({
        placeholder: 'Cari Uraian',
        // allowClear: true,
        // maximumSelectionLength: 1,
        width: "100%",
        // theme: "classic",
        // dropdownParent: MODAL_SUBKEGIATAN,
        // templateResult: formatUserSelect2,
        escapeMarkup: function(markup) {
            return markup;
        },
        ajax: {
            delay: 350,
            method: 'post',
            url: '<?php echo base_url("app/programs/getUraian") ?>',
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, // search term
                };
            },
            cache: true,
            processResults: function(data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: data
                };
            },
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });

    // Format angka jadi Rupiah
    function formatRupiah(angka) {
        return Number(angka || 0).toLocaleString("id-ID");
    }

    // Balikannya: hapus titik, spasi, koma, lalu jadi Number
    function unformatRupiah(rupiah) {
        return Number(String(rupiah).replace(/[^0-9]/g, ""));
    }

    $("select#uraian").on("change", function() {

        let $totalLPagu = MODAL_LIMIT.find("#total-pagu-awal")
        let $totalLimit = MODAL_LIMIT.find("#total-limit-uraian")
        let $sisaLimit = MODAL_LIMIT.find("#sisa-limit-uraian")
        let $jml_limit = MODAL_LIMIT.find("input[name='jumlah']")


        $.ajax({
            method: 'post',
            dataType: 'json',
            url: `${_uri}/app/programs/sisaLimit`,
            beforeSend: function() {
                $totalLPagu.html(`Loading ...`);
                $totalLimit.html(`Loading ...`);
                $sisaLimit.html(`Loading ...`);
            },
            data: {
                id: $(this).val() || ''
            },
            success: function(res) {
                let paguAwal = Number(res?.total_pagu_awal || 0).toLocaleString("id-ID");
                let total = Number(res?.total_limit.total_limit || 0).toLocaleString(
                    "id-ID");
                let sisa = Number(res?.sisa_limit || 0).toLocaleString("id-ID");
                $totalLPagu.html(`Total Pagu <br> <h4>Rp. ${paguAwal}</h4>`);
                $totalLimit.html(`Total Angkas <br><h4>Rp. ${total}</h4>`);
                $sisaLimit.html(`Sisa Pagu <br> <h4>Rp. ${sisa}</h4>`);

                $jml_limit.on("keyup", function(e) {
                    let hitung_sisa = (res?.sisa_limit - unformatRupiah($(this)
                        .val()));
                    $sisaLimit.html(
                        `Sisa Pagu <br> <h4>Rp. ${formatRupiah(hitung_sisa)}</h4>`
                    );
                    let hitung_total_limit = (Number(res?.total_limit
                        ?.total_limit || 0) + (unformatRupiah($(this)
                        .val())))
                    $totalLimit.html(
                        `Total Angkas <br> <h4>Rp. ${formatRupiah(hitung_total_limit)}</h4>`
                    );
                })

            },
            error: function(err) {
                $.notify(err.responseText, {
                    timer: 2000,
                    delay: 100,
                    type: "danger"
                });
            }
        })
        // reset form after change uraian
        $('select#periode').val(null).trigger('change')
        $jml_limit.val(0);
    })

    // select periode (tambah limit)
    $("select#periode").select2({
        placeholder: "Pilih Periode",
        allowClear: false,
        tags: false,
        tokenSeparators: [",", " "],
        // maximumSelectionLength: 1,
        width: "100%",
        // theme: "classic",
        // dropdownParent: MODAL_KEGIATAN,
        ajax: {
            delay: 250,
            method: "post",
            url: `${_uri}/app/select2/ajaxPeriode`,
            dataType: "json",
            data: function(params) {
                return {
                    q: params.term, // search term
                    uraian_id: $("select#uraian").val() || '' // kirim ID uraian
                };
            },
            cache: true,
            processResults: function(data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: data,
                };
            },
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        },
    });

    // select periode (update limit)
    $("select#periode_update").select2({
        placeholder: "Pilih Periode",
        allowClear: false,
        tags: false,
        tokenSeparators: [",", " "],
        width: "100%",
        ajax: {
            delay: 250,
            method: "post",
            url: `${_uri}/app/select2/ajaxPeriode`,
            dataType: "json",
            data: function(params) {
                return {
                    q: params.term,
                    uraian_id: FORM_UPDATE_LIMIT.find('input[name="uraian_id"]').val() || '',
                    exclude_limit_id: FORM_UPDATE_LIMIT.find('input[name="id"]').val() || ''
                };
            },
            cache: true,
            processResults: function(data) {
                return {
                    results: data
                };
            },
        },
    });

    MODAL_PROGRAM.on('hidden.bs.modal', function(e) {
        FORM_PROGRAM[0].reset()
        FORM_PROGRAM.parsley().reset();
        $('select[name="unor"]').val('').trigger('change');
    })

    MODAL_KEGIATAN.on('hidden.bs.modal', function(e) {
        FORM_KEGIATAN[0].reset();
        FORM_KEGIATAN.parsley().reset();
        $('select[name="part"]').val('').trigger('change');
        $('select[name="program"]').val('').trigger('change');
    })

    MODAL_SUBKEGIATAN.on('hidden.bs.modal', function(e) {
        FORM_SUBKEGIATAN[0].reset();
        FORM_SUBKEGIATAN.parsley().reset();
        $('select[name="kegiatan"]').val('').trigger('change');
    })

    MODAL_TUJUAN.on('hidden.bs.modal', function(e) {
        FORM_TUJUAN[0].reset();
        FORM_TUJUAN.parsley().reset();
    })

    MODAL_SASARAN.on('hidden.bs.modal', function(e) {
        FORM_SASARAN[0].reset();
        FORM_SASARAN.parsley().reset();
    })

    MODAL_KELOMPOK.on('hidden.bs.modal', function(e) {
        FORM_KELOMPOK[0].reset();
        FORM_KELOMPOK.parsley().reset();
    })

    MODAL_KELOMPOK_EDIT.on('hidden.bs.modal', function(e) {
        FORM_KELOMPOK_EDIT[0].reset();
        FORM_KELOMPOK_EDIT.parsley().reset();
    })

    MODAL_JENIS.on('hidden.bs.modal', function(e) {
        FORM_JENIS[0].reset();
        FORM_JENIS.parsley().reset();
    })

    MODAL_JENIS_EDIT.on('hidden.bs.modal', function(e) {
        FORM_JENIS_EDIT[0].reset();
        FORM_JENIS_EDIT.parsley().reset();
    })

    MODAL_PART.on('hidden.bs.modal', function(e) {
        FORM_PART[0].reset();
        FORM_PART.parsley().reset();
    })

    MODAL_UNOR.on('hidden.bs.modal', function(e) {
        FORM_UNOR[0].reset();
        FORM_UNOR.parsley().reset();
    })

    MODAL_PART_EDIT.on('hidden.bs.modal', function(e) {
        FORM_PART_EDIT[0].reset();
        FORM_PART_EDIT.parsley().reset();
    })

    MODAL_TUJUAN_EDIT.on('hidden.bs.modal', function(e) {
        FORM_TUJUAN_EDIT[0].reset();
        FORM_TUJUAN_EDIT.parsley().reset();
    })

    MODAL_SASARAN_EDIT.on('hidden.bs.modal', function(e) {
        FORM_SASARAN_EDIT[0].reset();
        FORM_SASARAN_EDIT.parsley().reset();
    })

    MODAL_URAIAN.on('hidden.bs.modal', function(e) {
        FORM_URAIAN[0].reset();
        FORM_URAIAN.parsley().reset();
        // Scope ke modal uraian: id "kegiatan"/"subkegiatan" dipakai juga modal lain
        MODAL_URAIAN.find('select[name="kegiatan"]').val('').trigger('change');
        MODAL_URAIAN.find('select[name="subkegiatan"]').val('').trigger('change');
        MODAL_URAIAN.find('select[name="jenis_belanja"]').val('').trigger('change');
    })

    MODAL_LIMIT.on('hidden.bs.modal', function(e) {
        FORM_LIMIT[0].reset()
        FORM_LIMIT.parsley().reset();
        $('select#periode').val('').trigger('change');
        $('select#uraian').val('').trigger('change');
    })

    MODAL_UPDATE_LIMIT.on('hidden.bs.modal', function(e) {
        FORM_UPDATE_LIMIT[0].reset()
        FORM_UPDATE_LIMIT.parsley().reset();
        $('select#periode_update').empty().trigger('change');
    })

    FORM_KEGIATAN.parsley();
    FORM_KEGIATAN.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_KEGIATAN.serialize();
        $button = $(this).find('button[type="submit"]');
        $button.html("processing ...").prop("disabled", true);
        try {
            $.post($url, $data, (response) => {
                if (response === 200) {
                    window.location.reload();
                }
            }, 'json');
        } catch (err) {
            $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
            return (err);
        }
        return false;
    });

    FORM_PROGRAM.parsley();
    FORM_PROGRAM.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_PROGRAM.serialize();
        $button = $(this).find('button[type="submit"]');
        $button.html("processing ...").prop("disabled", true);
        try {
            $.post($url, $data, (response) => {
                if (response === 200) {
                    window.location.reload();
                }
            }, 'json');
        } catch (err) {
            $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
            $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
        }
        return false;
    });

    FORM_SUBKEGIATAN.parsley();
    FORM_SUBKEGIATAN.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_SUBKEGIATAN.serialize();
        $button = $(this).find('button[type="submit"]');
        $button.html("processing ...").prop("disabled", true);
        try {
            $.post($url, $data, (response) => {
                if (response === 200) {
                    window.location.reload();
                }
            }, 'json');
        } catch (err) {
            $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
            $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
        }
        return false;
    });

    FORM_URAIAN.parsley();
    FORM_URAIAN.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_URAIAN.serialize();
        $button = $(this).find('button[type="submit"]');
        $button.html("processing ...").prop("disabled", true);
        try {
            $.post($url, $data, (response) => {
                if (response === 200) {
                    window.location.reload();
                }
            }, 'json');
        } catch (err) {
            $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
            $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
        }
        return false;
    });

    FORM_LIMIT.parsley();
    FORM_LIMIT.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_LIMIT.serialize();
        $button = $(this).find('button[type="submit"]');
        $button.html("processing ...").prop("disabled", true);
        try {
            $.post($url, $data, (response) => {
                if (response.status) {
                    return window.location.reload();
                }
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(response.message, {timer: 2000, delay: 100, type: "danger"});
                return;
            }, 'json');
        } catch (err) {
            $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
            $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
        }
        return false;
    });

    FORM_UPDATE_LIMIT.parsley();
    FORM_UPDATE_LIMIT.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_UPDATE_LIMIT.serialize();
        $button = $(this).find('button[type="submit"]');
        $button.html("processing ...").prop("disabled", true);
        try {
            $.post($url, $data, (response) => {
                if (response.status) {
                    return window.location.reload();
                }
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(response.message, {timer: 2000, delay: 100, type: "danger"});
                return;
            }, 'json');
        } catch (err) {
            $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
            $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
        }
        return false;
    });

    FORM_REKON_ANGGARAN.parsley();

    // File upload interaction for Rekonsiliasi
    (function() {
        const $area = $('#fileUploadArea');
        const $input = $('#excelFile');
        const $info = $area.find('.selected-file-info');
        const $fileName = $('#selectedFileName');
        const $fileSize = $('#selectedFileSize');
        const $removeBtn = $('#removeFile');
        const $helpBlock = $('#help-block-file');

        function formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function showFile(file) {
            $fileName.text(file.name);
            $fileSize.text(formatSize(file.size));
            $area.addClass('has-file');
            $info.removeClass('d-none');
            $area.find('.upload-icon, h6, p').addClass('d-none');
            $area.find('small#fileHelp').addClass('d-none');
            $helpBlock.text('');
            $input.removeClass('parsley-error');
        }

        function resetFile() {
            $input.val('');
            $area.removeClass('has-file');
            $info.addClass('d-none');
            $area.find('.upload-icon, h6, p').removeClass('d-none');
            $area.find('small#fileHelp').removeClass('d-none');
        }

        $area.on('click', function(e) {
            if (!$(e.target).closest('#removeFile, .selected-file-info').length) {
                $input.trigger('click');
            }
        });

        $input.on('change', function() {
            const file = this.files[0];
            if (file) {
                const allowed = ['.xlsx', '.xls'];
                const ext = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
                if (!allowed.includes(ext)) {
                    $helpBlock.text('Format file tidak didukung. Gunakan .xlsx atau .xls');
                    resetFile();
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    $helpBlock.text('Ukuran file melebihi 5MB');
                    resetFile();
                    return;
                }
                showFile(file);
            }
        });

        $area.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $area.addClass('dragover');
        });

        $area.on('dragleave dragend', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $area.removeClass('dragover');
        });

        $area.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $area.removeClass('dragover');
            const file = e.originalEvent.dataTransfer.files[0];
            if (file) {
                $input[0].files = e.originalEvent.dataTransfer.files;
                $input.trigger('change');
            }
        });

        $removeBtn.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            resetFile();
        });

        // Reset file display when modal hidden
        MODAL_REKON_ANGGARAN.on('hidden.bs.modal', function() {
            FORM_REKON_ANGGARAN[0].reset();
            FORM_REKON_ANGGARAN.parsley().reset();
            resetFile();
            $helpBlock.text('');
            $('#help-block-confirm').text('');
            $('#confirmRecon').prop('checked', false);
        });
    })();

    FORM_REKON_ANGGARAN.on("submit", function(e) {
        e.preventDefault();

        let $form = $(this);
        let $url = $form.attr('action');
        let $button = $form.find('button[type="submit"]');

        const $fileInput = $('#excelFile');
        if ($fileInput[0].files.length === 0) {
            $('#help-block-file').text('Silakan pilih file Excel terlebih dahulu');
            $fileInput.focus();
            return false;
        }
        if (!$('#confirmRecon').is(':checked')) {
            $('#help-block-confirm').text('Centang konfirmasi sebelum submit');
            return false;
        }
        $('#help-block-file, #help-block-confirm').text('');

        // Gunakan FormData untuk file + data
        let formData = new FormData(this);

        $button.html("processing ...").prop("disabled", true);

        try {
            $.ajax({
                url: $url,
                type: "POST",
                data: formData,
                processData: false, // penting: jangan ubah data
                contentType: false, // penting: biar browser set otomatis multipart/form-data
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $.notify(response.message, {timer: 2000, delay: 100, type: "danger"});
                        return window.location.reload();
                    }
                    $button.prop("disabled", false).html(
                        '<i class="fa fa-save mr-2"></i>Submit Rekonsiliasi');
                    $.notify(response.message, {timer: 2000, delay: 100, type: "danger"});
                },
                error: function(xhr, status, error) {
                    $button.prop("disabled", false).html(
                        '<i class="fa fa-save mr-2"></i>Submit Rekonsiliasi');
                    $.notify("Terjadi kesalahan: " + error, {timer: 2000, delay: 100, type: "danger"});
                }
            });
        } catch (err) {
            $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Submit Rekonsiliasi');
            $.notify("Error: " + err, {timer: 2000, delay: 100, type: "danger"});
        }

        return false;
    });


    FORM_TUJUAN.parsley();
    FORM_TUJUAN.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_TUJUAN.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_TUJUAN.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });

    FORM_SASARAN.parsley();
    FORM_SASARAN.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_SASARAN.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_SASARAN.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });

    FORM_KELOMPOK.parsley();
    FORM_KELOMPOK.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_KELOMPOK.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_KELOMPOK.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    } else {
                        $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                        $.notify('Gagal menyimpan. Kode kelompok mungkin sudah digunakan.', {timer: 2000, delay: 100, type: "danger"});
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });

    FORM_KELOMPOK_EDIT.parsley();
    FORM_KELOMPOK_EDIT.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_KELOMPOK_EDIT.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_KELOMPOK_EDIT.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    } else {
                        $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                        $.notify('Gagal menyimpan. Kode kelompok mungkin sudah digunakan.', {timer: 2000, delay: 100, type: "danger"});
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });

    FORM_JENIS.parsley();
    FORM_JENIS.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_JENIS.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_JENIS.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    } else {
                        $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                        $.notify('Gagal menyimpan. Kode jenis mungkin sudah digunakan.', {timer: 2000, delay: 100, type: "danger"});
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });

    FORM_JENIS_EDIT.parsley();
    FORM_JENIS_EDIT.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_JENIS_EDIT.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_JENIS_EDIT.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    } else {
                        $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                        $.notify('Gagal menyimpan. Kode jenis mungkin sudah digunakan.', {timer: 2000, delay: 100, type: "danger"});
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });

    FORM_PART.parsley();
    FORM_PART.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_PART.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_PART.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });

    FORM_UNOR.parsley();
    FORM_UNOR.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_UNOR.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_UNOR.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        // window.location.reload();
                        listUnor();
                        FORM_UNOR[0].reset();
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });

    FORM_UNOR_EDIT.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_UNOR_EDIT.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_UNOR_EDIT.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });

    FORM_PART_EDIT.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_PART_EDIT.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_PART_EDIT.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });

    FORM_TUJUAN_EDIT.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_TUJUAN_EDIT.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_TUJUAN_EDIT.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {

                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });

    FORM_SASARAN_EDIT.on("submit", function(e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $data = FORM_SASARAN_EDIT.serialize();
        $button = $(this).find('button[type="submit"]');
        if (FORM_SASARAN_EDIT.parsley().isValid()) {
            $button.html("processing ...").prop("disabled", true);
            try {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    }
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
            }
        }
        return false;
    });


    // window.ParsleyValidator.addValidator('cekcode', {
    //     validateString: function(value)
    //     {
    //         return $.post('<?php echo base_url('app/programs/cek_kode/subkegiatan') ?>', {kode: value}, (res) => {
    //             return res;
    //         }, 'json')
    //     }
    // }).addMessage('en', 'cekcode', 'Kode Sub Kegiatan sudah pernah digunakan !');

})

function UpdateLimit(id, paguLimit, uraian_id) {
    let $modal = $(".modal-update-limit"),
        $form = $modal.find("form#formUpdateLimit");
    // Bersihkan state sebelum tampilkan
    $form[0].reset();
    $form.parsley().reset();
    $modal.find('select#periode_update').empty().trigger('change');
    // Bind handler baru tanpa menumpuk handler lama
    $modal.off('shown.bs.modal').on('shown.bs.modal', function(e) {
        $form.find('input[name="id"]').val(id);
        $form.find('input[name="uraian_id"]').val(uraian_id);
        $form.find('input[name="jumlah"]').val(formatRupiah(paguLimit));
        // Fetch detail periode & auto-select tags sesuai DB (t_pagu_limit.periode)
        let $sel = $form.find('select#periode_update');
        $.get(`${_uri}/app/programs/detail_limit_periode?id=${id}`, function(res) {
            if (res && res.options && res.options.length) {
                res.options.forEach(function(o) {
                    let opt = new Option(o.nama, o.id, o.selected, o.selected);
                    opt.disabled = !!o.disabled && !o.selected;
                    $sel.append(opt);
                });
                $sel.trigger('change');
            }
        }, 'json');
    });
    $modal.modal('show');
}

function InputPagu(id, url, paguAwal, is_perubahan, paguMurni) {
    let $modal = $(".modal-alokasipagu"),
        $form = $modal.find("form#formAlokasiPagu");
    $modal.modal('show');

    $modal.on('shown.bs.modal', function(e) {
        // pagu murni untuk opsi Tetap (fallback ke paguAwal jika tidak dikirim)
        let murni = (typeof paguMurni !== 'undefined' && paguMurni !== null && paguMurni !== '') ? paguMurni :
            paguAwal;
        // Tampilkan toggle hanya untuk perubahan
        if (is_perubahan == 1) {
            $form.find('#pagu-perubahan-toggle').removeClass('d-none');
            $form.find('#paguBerubah').prop('checked', true);
            $form.find('input[name="jumlah"]').val(formatRupiah(paguAwal));
            $form.find('input[name="is_perubahan"]').val(1);
        } else {
            $form.find('#pagu-perubahan-toggle').addClass('d-none');
            $form.find('input[name="jumlah"]').val(formatRupiah(paguAwal));
            $form.find('input[name="is_perubahan"]').val(is_perubahan);
        }
        // Handler switch pagu perubahan
        $form.find('input[name="tipe_pagu_perubahan"]').off('change').on('change', function() {
            if ($(this).val() === 'tetap') {
                $form.find('input[name="jumlah"]').val(formatRupiah(murni));
                $form.find('input[name="is_perubahan"]').val(0);
            } else {
                $form.find('input[name="jumlah"]').val(formatRupiah(paguAwal));
                $form.find('input[name="is_perubahan"]').val(1);
            }
        });
        $form.on("submit", function(e) {
            e.preventDefault();
            $data = $(this).serializeArray();
            $button = $(this).find('button[type="submit"]');
            $button.html("processing ...").prop("disabled", true);
            if ($(this).parsley().isValid()) {
                $data.push({
                    "name": "id",
                    "value": id
                });
                try {
                    $.post(url, $data, (response) => {
                        if (response === 200) {
                            window.location.reload();
                        }
                    }, 'json');
                } catch (err) {
                    $button.html('<i class="fa fa-save mr-2"></i>Simpan').prop("disabled", false);
                    $.notify(err, {timer: 2000, delay: 100, type: "danger"});
            return;
                }
            }
        })
    })

    $modal.on('hidden.bs.modal', function(e) {
        $form.attr('action', '#');
        $form[0].reset();
        $form.parsley().reset();
        $form.find('#pagu-perubahan-toggle').addClass('d-none');
        $form.find('input[name="tipe_pagu_perubahan"]').off('change');
    })
}

function Edit(id, url, target) {

    if (target === '.modal-unor-edit') {
        let _ = $(target);
        let input = _.find('input[name="unor"]');
        let input_uid = _.find('input[name="id"]');
        _.modal('show');
        $.getJSON(url, {
            id: id
        }, (res) => {
            input.val(res.nama);
            input_uid.val(res.id);
        });
        return false;
    }

    if (target === '.modal-part-edit') {
        let _ = $(target);
        let $form = _.find("#formPartEdit");
        _.modal('show');
        $.getJSON(url, {
            id: id
        }, (res) => {
            _.find('select[name="program"]').val(res.fid_program).trigger("change");
            _.find('input[name="part"]').val(res.nama);
            _.find('input[name="part_singkatan"]').val(res.singkatan);
            _.find('input[name="id"]').val(res.id)
        });
        return false;
    }

    if (target === '.modal-tujuan-edit') {
        let _ = $(target);
        let $form = _.find("#formTujuanEdit");
        _.modal('show');
        $.getJSON(url, {
            id: id
        }, (res) => {
            _.find('input[name="tujuan"]').val(res.nama);
            _.find('input[name="id"]').val(res.id);
        });
        return false;
    }

    if (target === '.modal-sasaran-edit') {
        let _ = $(target);
        let $form = _.find("#formSasaranEdit");
        _.modal('show');
        $.getJSON(url, {
            id: id
        }, (res) => {
            _.find('input[name="sasaran"]').val(res.nama);
            _.find('input[name="id"]').val(res.id);
        });
        return false;
    }

    if (target === '.modal-kelompok-edit') {
        let _ = $(target);
        _.modal('show');
        $.getJSON(url, {
            id: id
        }, (res) => {
            _.find('input[name="kode"]').val(res.kode);
            _.find('input[name="kelompok"]').val(res.nama);
            _.find('input[name="id"]').val(res.id);
        });
        return false;
    }

    if (target === '.modal-jenis-edit') {
        let _ = $(target);
        let $kelompok = _.find('#jenis-kelompok-edit');
        _.modal('show');
        $.getJSON(url, {
            id: id
        }, (res) => {
            // select2 ajax tidak punya opsi terpilih -> label kelompok diambil dari daftar kelompok (q kosong)
            $.post('<?php echo base_url("app/programs/getKelompok") ?>', { q: '' }, (rows) => {
                $kelompok.empty().append('<option value=""></option>');
                const match = (rows || []).find(r => r.id == res.fid_kelompok_belanja);
                if (match) {
                    $kelompok.append(new Option(match.text, res.fid_kelompok_belanja, true, true));
                }
                $kelompok.val(res.fid_kelompok_belanja).trigger('change.select2');
            }, 'json');
            _.find('input[name="kode"]').val(res.kode);
            _.find('input[name="jenis"]').val(res.nama);
            _.find('input[name="id"]').val(res.id);
        });
        return false;
    }

    // console.log(input)
}

function Hapus(id, url, label = 'DATA') {
    $('#hapus-message').text(`Apakah anda yakin akan menghapus ${label} tersebut ?`);
    const $modal = $('.modal-hapus').modal('show');
    $modal.off('click.hapus').on('click.hapus', '#btn-confirm-hapus', function() {
        $modal.off('click.hapus').modal('hide');
        $.post(url, {
            id: id
        }, (res) => {
            window.location.reload();
        }, 'json')
    });
}

// salin kode ke clipboard + feedback ikon check
$(document).on('click', '.copy-code', function() {
    const btn = $(this),
        txt = btn.data('copy') + '';
    const done = function() {
        btn.find('i').removeClass('fa-copy').addClass('fa-check');
        setTimeout(() => btn.find('i').removeClass('fa-check').addClass('fa-copy'), 1200);
    };
    if (navigator.clipboard) {
        navigator.clipboard.writeText(txt).then(done);
    } else {
        const $t = $('<textarea>').val(txt).appendTo('body').select();
        document.execCommand('copy');
        $t.remove();
        done();
    }
});

$(document).on('change', '.toggle-is-aktif', async function() {
    const $checkbox = $(this);
    const id = $checkbox.data('id');
    const is_aktif = $checkbox.is(':checked') ? 'Y' : 'N';
    const $container = $checkbox.closest('div');
    const $label = $checkbox.closest('.switch-toggle');
    const $loader = $container.find('.switch-loader');

    // Tampilkan efek loading & nonaktifkan input
    $checkbox.prop('disabled', true);
    $label.addClass('is-loading');
    $loader.removeClass('d-none');
    if (typeof NProgress !== 'undefined') NProgress.start();

    try {
        const body = new URLSearchParams();
        body.append('id', id);
        body.append('is_aktif', is_aktif);

        const response = await fetch(`${_uri}/app/programs/toggle_aktif_uraian`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: body.toString()
        });

        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status}`);
        }

        const res = await response.json();

        if (res && res.status) {
            $label.attr('title', is_aktif === 'Y' ? 'Aktif' : 'Tidak Aktif');
        } else {
            $.notify(res && res.message ? res.message : 'Gagal mengubah status', {timer: 2000, delay: 100, type: "danger"});
            $checkbox.prop('checked', is_aktif !== 'Y');
        }
    } catch (error) {
        console.error('Error toggling is_aktif:', error);
        $checkbox.prop('checked', is_aktif !== 'Y');
        $.notify('Terjadi kesalahan koneksi saat memperbarui status.', {timer: 2000, delay: 100, type: "danger"});
    } finally {
        // Hentikan efek loading & aktifkan kembali input
        $checkbox.prop('disabled', false);
        $label.removeClass('is-loading');
        $loader.addClass('d-none');
        if (typeof NProgress !== 'undefined') NProgress.done();
    }
});

// Mangatse :)
</script>