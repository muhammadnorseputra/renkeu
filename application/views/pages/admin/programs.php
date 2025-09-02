<div class="row">
    <div class="col-md-12">
        <?php
        $tab = isset($_GET['tab']) ? $_GET['tab'] : '#program';

        if (urldecode($tab) === '#tujuan_sasaran') {
            $tujuan_sasaran = 'active';
            $is_active_tujuan_sasaran = true;
            $is_show_tujuan_sasaran = "show";
        } else {
            $is_show_tujuan_sasaran = "";
            $is_active_tujuan_sasaran = false;
            $tujuan_sasaran = '';
        }

        if (urldecode($tab) === '#part') {
            $part = 'active';
            $is_active_part = true;
            $is_show_part = "show";
        } else {
            $is_show_part = "";
            $is_active_part = false;
            $part = '';
        }

        if (urldecode($tab) === '#program') {
            $program = 'active';
            $is_active_program = true;
            $is_show_program = "show";
        } else {
            $is_show_program = "";
            $is_active_program = false;
            $program = '';
        }

        if (urldecode($tab) === '#kegiatan') {
            $kegiatan = 'active';
            $is_active_kegiatan = true;
            $is_show_kegiatan = "show";
        } else {
            $is_show_kegiatan = "";
            $is_active_kegiatan = false;
            $kegiatan = '';
        }

        if (urldecode($tab) === '#subkegiatan') {
            $is_show_subkegiatan = "show";
            $subkegiatan = 'active';
            $is_active_subkegiatan = true;
        } else {
            $is_show_subkegiatan = "";
            $subkegiatan = '';
            $is_active_subkegiatan = false;
        }

        if (urldecode($tab) === '#uraian') {
            $is_show_uraian = "show";
            $uraian = 'active';
            $is_active_uraian = true;
        } else {
            $is_show_uraian = "";
            $uraian = '';
            $is_active_uraian = false;
        }

        if (urldecode($tab) === '#limit') {
            $is_show_limit = "show";
            $limit = 'active';
            $is_active_limit = true;
        } else {
            $is_show_limit = "";
            $limit = '';
            $is_active_limit = false;
        }
        ?>
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="overflow-x: auto; display: flex; flex-wrap: nowrap;">
            <?php
            if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN') :
            ?>
                <li class="nav-item mr-2">
                    <a class="nav-link pb-4 font-weight-bold <?= $part ?>" title="Bidang / Bagian" style="font-size:16px;" id="part-tab" data-toggle="tab" href="#part" role="tab" aria-controls="part" aria-selected="<?= $is_active_part ?>"><i class="fa fa-tasks mr-2"></i>Unor/Bidang/Bagian</a>
                </li>
                <li class="nav-item mr-2">
                    <a class="nav-link pb-4 font-weight-bold <?= $tujuan_sasaran ?>" title="Tujuan & Sasaran" style="font-size:16px;" id="tujuan_sasaran-tab" data-toggle="tab" href="#tujuan_sasaran" role="tab" aria-controls="tujuan_sasaran" aria-selected="<?= $is_active_tujuan_sasaran ?>"><span class="fa fa-book mr-2"></span> Tujuan & Sasaran</a>
                </li>

            <?php endif; ?>
            <li class="nav-item mr-2">
                <a class="nav-link pb-4 font-weight-bold <?= $program ?>" title="Program & Kegiatan" style="font-size:16px;" id="program-tab" data-toggle="tab" href="#program" role="tab" aria-controls="program" aria-selected="<?= $is_active_program ?>"><span class="fa fa-book mr-2"></span> Program</a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link pb-4 font-weight-bold <?= $kegiatan ?>" title="Kegiatan" style="font-size:16px;" id="kegiatan-tab" data-toggle="tab" href="#kegiatan" role="tab" aria-controls="kegiatan" aria-selected="<?= $is_active_kegiatan ?>"><span class="fa fa-file-code-o mr-2"></span> Kegiatan</a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link pb-4 font-weight-bold <?= $subkegiatan ?>" title="Sub Kegiatan" style="font-size:16px;" id="subkegiatan-tab" data-toggle="tab" href="#subkegiatan" role="tab" aria-controls="subkegiatan" aria-selected="<?= $is_active_subkegiatan ?>"><span class="fa fa-file-o mr-2 text-success"></span> Sub Kegiatan</a>
            </li>
            <?php if (getSetting('ENTRI_URAIAN')): ?>
                <li class="nav-item mr-2">
                    <a class="nav-link pb-4 font-weight-bold <?= $uraian ?>" title="Uraian Kegiatan" style="font-size:16px;" id="uraian-tab" data-toggle="tab" href="#uraian" role="tab" aria-controls="uraian" aria-selected="<?= $is_active_uraian ?>"><span class="fa fa-files-o mr-2 text-info"></span> Uraian Kegiatan</a>
                </li>
            <?php endif; ?>
            <?php if (getSetting('ENTRI_ANGKAS')): ?>
                <li class="nav-item">
                    <a class="nav-link pb-4 font-weight-bold <?= $limit ?>" title="Limit Anggaran" style="font-size:16px;" id="limit-tab" data-toggle="tab" href="#limit" role="tab" aria-controls="limit" aria-selected="<?= $is_active_limit ?>"><span class="fa fa-file-o mr-2 text-info"></span>Angkas</a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="x_panel" style="border-top:0">
            <div class="x_content">
                <div class="tab-content" id="myTabContent">
                    <?php
                    if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'VERIFICATOR') :
                    ?>
                        <div class="tab-pane <?= $part ?> <?= $is_show_part ?>" id="part" role="tabpanel" aria-labelledby="part-tab">
                            <div class="listUnor"></div>
                            <div class="divider-dashed"></div>
                            <div class="listPart"></div>
                        </div>
                    <?php endif; ?>
                    <div class="tab-pane <?= $program ?> <?= $is_show_program ?>" id="program" role="tabpanel" aria-labelledby="program-tab"></div>
                    <div class="tab-pane <?= $tujuan_sasaran ?> <?= $is_show_tujuan_sasaran ?>" id="tujuan_sasaran" role="tabpanel" aria-labelledby="tujuan_sasaran-tab">
                        <div class="listTujuan"></div>
                        <div class="divider-dashed"></div>
                        <div class="listSasaran"></div>
                    </div>
                    <div class="tab-pane <?= $kegiatan ?> <?= $is_show_kegiatan ?>" id="kegiatan" role="tabpanel" aria-labelledby="kegiatan-tab"></div>
                    <div class="tab-pane <?= $subkegiatan ?> <?= $is_show_subkegiatan ?>" id="subkegiatan" role="tabpanel" aria-labelledby="subkegiatan-tab"></div>
                    <div class="tab-pane <?= $uraian ?> <?= $is_show_uraian ?>" id="uraian" role="tabpanel" aria-labelledby="uraian-tab"></div>
                    <div class="tab-pane <?= $limit ?> <?= $is_show_limit ?>" id="limit" role="tabpanel" aria-labelledby="limit-tab"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Unor -->
<div class="modal fade modal-unor-edit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/update/ref_unors'), ['id' => 'formUnorEdit', 'data-parsley-validate' => ''], ['id' => '']); ?>
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
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Edit Part -->
<div class="modal fade modal-part-edit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/update/ref_parts/'), ['id' => 'formPartEdit', 'data-parsley-validate' => ''], ['id' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Edit Badan / Bidang / Bagian</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="program">Pilih Program <span class="text-danger">*</span></label>
                    <select name="program" id="program" required data-parsley-errors-container="#help-block-program"></select>
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
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Edit Tujuan -->
<div class="modal fade modal-tujuan-edit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/update/ref_tujuan/'), ['id' => 'formTujuanEdit', 'data-parsley-validate' => ''], ['id' => '']); ?>
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
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Edit Sasaran -->
<div class="modal fade modal-sasaran-edit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/update/ref_sasaran/'), ['id' => 'formSasaranEdit', 'data-parsley-validate' => ''], ['id' => '']); ?>
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
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Unor -->
<div class="modal fade modal-unor" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/tambah/unor'), ['id' => 'formUnor']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Unit Organisasi</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="unor">Nama Unor <span class="text-danger">*</span></label>
                    <input type="text" name="unor" id="unor" class="form-control" placeholder="Masukan nama unor baru disini ..." required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Part -->
<div class="modal fade modal-part" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/tambah/part'), ['id' => 'formPart']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Badan / Bidang / Bagian</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="program">Pilih Program <span class="text-danger">*</span></label>
                    <select name="program" id="program" required data-parsley-errors-container="#help-block-program"></select>
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
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Tujuan -->
<div class="modal fade modal-tujuan" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/tambah/tujuan'), ['id' => 'formTujuan']); ?>
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
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Sasaran -->
<div class="modal fade modal-sasaran" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/tambah/sasaran'), ['id' => 'formSasaran']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-primary text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Sasaran</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="tujuan">Pilih Tujuan <span class="text-danger">*</span></label>
                    <select name="tujuan" id="tujuan" required data-parsley-errors-container="#help-block-tujuan"></select>
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
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Program -->
<div class="modal fade modal-program" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/tambah/program'), ['id' => 'formProgram']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Program</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="sasaran">Pilih Sasaran <span class="text-danger">*</span></label>
                    <select name="sasaran" id="sasaran" required data-parsley-errors-container="#help-block-sasaran"></select>
                    <div id="help-block-sasaran" class="help-block"></div>
                </div>
                <div class="divider-dashed"></div>
                <div class="form-group">
                    <label for="kode_program">Kode Program <span class="text-danger">*</span></label>
                    <input type="text" id="kode_program" name="kode_program" class="form-control" required data-parsley-pattern="^(([0-9.]?)*)+$" data-parsley-remote="<?= base_url('app/programs/cek_kode/kodeprogram') ?>" data-parsley-remote-reverse="false" data-parsley-remote-options='{ "type": "POST" }' data-parsley-remote-message="Kode Program sudah pernah digunakan !" data-parsley-trigger="change">
                </div>
                <div class="form-group">
                    <label for="program">Nama Program <span class="text-danger">*</span></label>
                    <input type="text" id="program" name="program" class="form-control" required data-parsley-remote="<?= base_url('app/programs/cek_kode/namaprogram') ?>" data-parsley-remote-reverse="false" data-parsley-remote-options='{ "type": "POST" }' data-parsley-remote-message="Nama Program sudah pernah digunakan !" data-parsley-trigger="keyup">
                </div>
                <div class="form-group">
                    <label for="bidang">Pilih Bidang <span class="text-danger">*</span></label>
                    <select name="bidang[]" id="bidang" multiple="multiple" required data-parsley-errors-container="#help-block-bidang"></select>
                    <div id="help-block-bidang" class="help-block"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Kegiatan -->
<div class="modal fade modal-kegiatan" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/tambah/kegiatan'), ['id' => 'formKegiatan']); ?>
        <input type="hidden" name="part" value="<?= $this->session->userdata('part') ?>">
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
                    <select name="program" id="program" required data-parsley-errors-container="#help-block-program"></select>
                    <div id="help-block-program" class="help-block"></div>
                </div>
                <div class="divider-dashed"></div>
                <div class="form-group">
                    <label for="kode_kegiatan">Kode Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" id="kode_kegiatan" name="kode_kegiatan" class="form-control" required data-parsley-pattern="^(([0-9.]?)*)+$" data-parsley-remote="<?= base_url('app/programs/cek_kode/kegiatan') ?>" data-parsley-remote-reverse="false" data-parsley-remote-message="Kode Kegiatan sudah pernah digunakan !" data-parsley-trigger="change">
                </div>
                <div class="form-group">
                    <label for="kegiatan">Nama Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" id="kegiatan" name="kegiatan" class="form-control" required data-parsley-trigger="keyup">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Sub Kegiatan -->
<div class="modal fade modal-subkegiatan" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/tambah/subkegiatan'), ['id' => 'formSubKegiatan']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Sub Kegiatan</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="kegiatan">Pilih Kegiatan <span class="text-danger">*</span></label>
                    <select name="kegiatan" id="kegiatan" required data-parsley-errors-container="#help-block-kegiatan"></select>
                    <div id="help-block-kegiatan" class="help-block"></div>
                </div>
                <div class="divider-dashed"></div>
                <div class="form-group">
                    <label for="kode_subkegiatan">Kode Sub Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="kode_subkegiatan" class="form-control" required data-parsley-remote="<?= base_url('app/programs/cek_kode/subkegiatan') ?>" data-parsley-remote-reverse="false" data-parsley-remote-options='{ "type": "POST" }' data-parsley-remote-message="Kode Sub Kegiatan sudah pernah digunakan !" data-parsley-pattern="^(([0-9.]?)*)+$" data-parsley-trigger="focusout">
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
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Uraian -->
<div class="modal fade modal-uraian" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('/app/programs/tambah/uraian'), ['id' => 'formUraian']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Tambah Uraian</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="kegiatan">Pilih Kegiatan <span class="text-danger">*</span></label>
                    <select name="kegiatan" id="kegiatan" required data-parsley-errors-container="#help-block-kegiatan"></select>
                    <div id="help-block-kegiatan" class="help-block"></div>
                </div>
                <div class="form-group">
                    <label for="subkegiatan">Pilih Sub Kegiatan <span class="text-danger">*</span></label>
                    <select name="subkegiatan" id="subkegiatan" style="width:100%" required data-parsley-errors-container="#help-block-subkegiatan"></select>
                    <div id="help-block-subkegiatan" class="help-block"></div>
                </div>
                <div class="divider-dashed"></div>
                <div class="form-group">
                    <label for="kode_uraian">Kode Uraian <span class="text-danger">*</span></label>
                    <input type="text" id="kode_uraian" name="kode_uraian" class="form-control" required data-parsley-pattern="^(([0-9.]?)*)+$" data-parsley-trigger="focusout">
                </div>
                <div class="form-group">
                    <label for="nama_uraian">Uraian <span class="text-danger">*</span></label>
                    <input type="text" id="nama_uraian" name="nama_uraian" class="form-control" required data-parsley-trigger="focusout">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>

        </div>
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Tambah Alokasi Pagu Kegiatan -->
<div class="modal fade modal-alokasipagu" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <?= form_open('#', ['id' => 'formAlokasiPagu', 'data-parsley-validate' => ''], ['is_perubahan' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Alokasi Pagu Anggaran</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="jumlah">Jumlah Pagu</label>
                    <input type="text" name="jumlah" id="jumlah" class="form-control" required>
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

<!-- Modal Tambah Limit Anggaran -->
<div class="modal fade modal-limit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <?= form_open(base_url('app/programs/input_limit'), ['id' => 'formLimit', 'data-parsley-validate' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Input Anggaran KAS</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="uraian">Cari Uraian <span class="text-danger">*</span></label>
                    <select name="uraian" id="uraian" required data-parsley-errors-container="#help-block-uraian"></select>
                    <div id="help-block-uraian" class="help-block"></div>
                </div>
                <div class="row my-4">
                    <div class="col-md-12 d-flex flex-wrap">
                        <div id="total-pagu-awal" class="p-3 mr-2 mb-2 shadow rounded-xl border border-success">0</div>
                        <div id="sisa-limit-uraian" class="p-3 mr-2 mb-2 shadow rounded-xl border border-warning">0</div>
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
                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i class="fa fa-close mr-2"></i>Batal</button>
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Update Limit Anggaran -->
<div class="modal fade modal-update-limit" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <?= form_open(base_url('app/programs/update_limit'), ['id' => 'formUpdateLimit', 'data-parsley-validate' => ''], ['id' => '', 'uraian_id' => '']); ?>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i class="fa fa-close mr-2"></i>Batal</button>
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<!-- Modal Rekonsiliasi Anggaran -->
<div class="modal fade modal-rekon" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog">
        <?= form_open_multipart(base_url('app/programs/rekon_anggaran'), ['id' => 'formRekonAnggaran', 'data-parsley-validate' => '']); ?>
        <div class="modal-content rounded-0">
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title" id="myModalLabel">Rekonsiliasi Anggaran</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="anggaran">Utk. Anggaran</label>
                    <select name="anggaran" id="anggaran" required class="form-control" data-parsley-errors-container="#help-block-anggaran" aria-readonly="true" disabled>
                        <option value="">-- Anggaran --</option>
                        <option value="1" <?= $this->session->userdata('is_perubahan') === "1" ? "selected" : ""; ?>>Perubahan</option>
                        <option value="0" <?= $this->session->userdata('is_perubahan') === "0" ? "selected" : ""; ?>>Murni</option>
                    </select>
                    <div id="help-block-anggaran" class="help-block"></div>
                </div>
                <div class="form-group">
                    <label for="excelFile">Pilih File Excel</label>
                    <input type="file" class="form-control form-control-file" id="excelFile" name="file" accept=".xlsx,.xls" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i class="fa fa-close mr-2"></i>Batal</button>
                <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Submit</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
<script>
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
        $('.listTujuan,.listSasaran,.listPart,#kegiatan,#subkegiatan,#uraian,#program,#limit').html(`<div class="d-flex justify-content-center align-items-center align-self-center py-4"><img src="${_uri}/template/assets/loader/motion-blur.svg" alt="Loading" class="mr-3" width="40"><h4>Loading data, mohon tunggu.</h4></div>`);
        // Get Tab Active
        let tab_active = urlParams.get('tab');
        // if tab active same as url
        if (tab_active === '#kegiatan') {
            getListKegiatan().then((data) => {
                if (data.code === 404) {
                    $('#kegiatan').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done();
                    return false;
                }
                $('#kegiatan').html(data.result);
                NProgress.done()
                var subKegiatanList = new List('listKegiatan', option);
            });
        } else if (tab_active === '#subkegiatan') {
            getListSubKegiatan().then((data) => {
                if (data.code === 404) {
                    $('#subkegiatan').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('#subkegiatan').html(data.result);
                NProgress.done()
                var subKegiatanList = new List('listSubKegiatan', option);
            });
        } else if (tab_active === '#part') {
            getListUnor().then((data) => {
                if (data.code === 404) {
                    $('.listUnor').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('.listUnor').html(data.result);
                NProgress.done()
                var unorList = new List('listUnor', option);
            });
            getListPart().then((data) => {
                if (data.code === 404) {
                    $('.listPart').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('.listPart').html(data.result);
                NProgress.done()
            });
        } else if (tab_active === '#uraian') {
            getListUraian().then((data) => {
                if (data.code === 404) {
                    $('#uraian').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('#uraian').html(data.result);
                NProgress.done()
                var uraianList = new List('listUraian', option);
            });
        } else if (tab_active === '#limit') {
            getListLimit().then((data) => {
                if (data.code === 404) {
                    $('#limit').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('#limit').html(data.result);
                NProgress.done()
                var listLimit = new List('listLimit', option);
            });
        } else if (tab_active === '#tujuan_sasaran') {
            getListTujuan().then((data) => {
                if (data.code === 404) {
                    $('.listTujuan').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('.listTujuan').html(data.result);
                NProgress.done()
                var tujuanList = new List('listTujuan', option);
            });
            getListSasaran().then((data) => {
                if (data.code === 404) {
                    $('.listSasaran').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('.listSasaran').html(data.result);
                NProgress.done()
                var sasaranList = new List('listSasaran', option);
            });

        } else {
            getListProgram().then((data) => {
                if (data.code === 404) {
                    $('#program').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('#program').html(data.result);
                NProgress.done()
                var programList = new List('listProgram', option);
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
                    $('.listTujuan').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('.listTujuan').html(data.result);
                NProgress.done()
                var tujuanList = new List('listTujuan', option);
            });
            getListSasaran().then((data) => {
                if (data.code === 404) {
                    $('.listSasaran').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('.listSasaran').html(data.result);
                NProgress.done()
                var sasaranList = new List('listSasaran', option);
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
                    $('.listUnor').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('.listUnor').html(data.result);
                NProgress.done()
                var unorList = new List('listUnor', option);
            });
            getListPart().then((data) => {
                if (data.code === 404) {
                    $('.listPart').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('.listPart').html(data.result);
                NProgress.done()
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
                    $('#program').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('#program').html(data.result);
                NProgress.done()
                var programList = new List('listProgram', option);
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
                    $('#kegiatan').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('#kegiatan').html(data.result);
                NProgress.done()
                var subKegiatanList = new List('listKegiatan', option);
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
                    $('#subkegiatan').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('#subkegiatan').html(data.result);
                NProgress.done();
                var subKegiatanList = new List('listSubKegiatan', option);
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
                    $('#uraian').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('#uraian').html(data.result);
                NProgress.done();
                var uraianList = new List('listUraian', option);
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
                    $('#limit').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('#limit').html(data.result);
                NProgress.done();
                var listLimit = new List('listLimit', option);
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
                url: '<?= base_url("app/programs/getTujuan") ?>',
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
                url: '<?= base_url("app/programs/getSasaran") ?>',
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
                url: '<?= base_url("app/programs/getUnor") ?>',
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
                url: '<?= base_url("app/programs/getParts") ?>',
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
                url: '<?= base_url("app/programs/getProgram") ?>',
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
                url: '<?= base_url("app/programs/getKegiatan") ?>',
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
                    url: '<?= base_url("app/select2/ajaxSubKegiatan") ?>',
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
                url: '<?= base_url("app/programs/getUraian") ?>',
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
                    let total = Number(res?.total_limit.total_limit || 0).toLocaleString("id-ID");
                    let sisa = Number(res?.sisa_limit || 0).toLocaleString("id-ID");
                    $totalLPagu.html(`Total Pagu <br> <h4>Rp. ${paguAwal}</h4>`);
                    $totalLimit.html(`Total Angkas <br><h4>Rp. ${total}</h4>`);
                    $sisaLimit.html(`Sisa Pagu <br> <h4>Rp. ${sisa}</h4>`);

                    $jml_limit.on("keyup", function(e) {
                        let hitung_sisa = (res?.sisa_limit - unformatRupiah($(this).val()));
                        $sisaLimit.html(`Sisa Pagu <br> <h4>Rp. ${formatRupiah(hitung_sisa)}</h4>`);
                        let hitung_total_limit = (Number(res?.total_limit?.total_limit || 0) + (unformatRupiah($(this).val())))
                        $totalLimit.html(`Total Angkas <br> <h4>Rp. ${formatRupiah(hitung_total_limit)}</h4>`);
                    })

                },
                error: function(err) {
                    return alert(err.responseText)
                }
            })
            // reset form after change uraian
            $('select#periode').val(null).trigger('change')
            $jml_limit.val(0);
        })

        // select periode
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
            $('select[name="kegiatan"]').val('').trigger('change');
            $('select[name="sub_kegiatan"]').val('').trigger('change');
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
        })

        MODAL_REKON_ANGGARAN.on('hidden.bs.modal', function(e) {
            FORM_REKON_ANGGARAN[0].reset()
            FORM_REKON_ANGGARAN.parsley().reset();
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
                return alert(err);
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
                return alert(err);
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
                return alert(err);
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
                    return alert(response.message);
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                return alert(err);
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
                    return alert(response.message);
                }, 'json');
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                return alert(err);
            }
            return false;
        });

        FORM_REKON_ANGGARAN.parsley();
        FORM_REKON_ANGGARAN.on("submit", function(e) {
            e.preventDefault();

            let $form = $(this);
            let $url = $form.attr('action');
            let $button = $form.find('button[type="submit"]');

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
                            alert(response.message);
                            return window.location.reload();
                        }
                        $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                        alert(response.message);
                    },
                    error: function(xhr, status, error) {
                        $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                        alert("Terjadi kesalahan: " + error);
                    }
                });
            } catch (err) {
                $button.prop("disabled", false).html('<i class="fa fa-save mr-2"></i>Simpan');
                alert("Error: " + err);
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
                    return alert(err);
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
                    return alert(err);
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
                    return alert(err);
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
                    return alert(err);
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
                    return alert(err);
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
                    return alert(err);
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
                    return alert(err);
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
                    return alert(err);
                }
            }
            return false;
        });


        // window.ParsleyValidator.addValidator('cekcode', {
        //     validateString: function(value)
        //     {
        //         return $.post('<?= base_url('app/programs/cek_kode/subkegiatan') ?>', {kode: value}, (res) => {
        //             return res;
        //         }, 'json')
        //     }
        // }).addMessage('en', 'cekcode', 'Kode Sub Kegiatan sudah pernah digunakan !');

    })

    function UpdateLimit(id, paguLimit, uraian_id) {
        let $modal = $(".modal-update-limit"),
            $form = $modal.find("form#formUpdateLimit");
        $modal.modal('show');
        $modal.on('shown.bs.modal', function(e) {
            $form.find('input[name="id"]').val(id);
            $form.find('input[name="uraian_id"]').val(uraian_id);
            $form.find('input[name="jumlah"]').val(formatRupiah(paguLimit));
        })
    }

    function InputPagu(id, url, paguAwal, is_perubahan) {
        let $modal = $(".modal-alokasipagu"),
            $form = $modal.find("form#formAlokasiPagu");
        $modal.modal('show');

        $modal.on('shown.bs.modal', function(e) {
            $form.find('input[name="jumlah"]').val(formatRupiah(paguAwal));
            $form.find('input[name="is_perubahan"]').val(is_perubahan);
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
                        return alert(err);
                    }
                }
            })
        })

        $modal.on('hidden.bs.modal', function(e) {
            $form.attr('action', '#');
            $form[0].reset();
            $form.parsley().reset();
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

        // console.log(input)
    }

    function Hapus(id, url, label = 'DATA') {
        let text = `Apakah anda yakin akan menghapus ${label} tersebut ?`;
        if (confirm(text)) {
            $.post(url, {
                id: id
            }, (res) => {
                window.location.reload();
            }, 'json')
        }
    }

    // Mangatse :)
</script>