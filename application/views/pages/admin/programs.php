<div class="row">
    <div class="col-md-12">
        <?php
        $tab = isset($_GET['tab']) ? $_GET['tab'] : '#program';

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
        ?>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <?php
            if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'VERIFICATOR') :
            ?>
                <li class="nav-item float-right">
                    <a class="nav-link <?= $part ?>" title="Bidang / Bagian" style="font-size:16px; font-weight: bold" id="part-tab" data-toggle="tab" href="#part" role="tab" aria-controls="part" aria-selected="<?= $is_active_part ?>"><i class="fa fa-tasks mr-2"></i>Unor/Bidang/Bagian</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link <?= $program ?>" title="Program & Kegiatan" style="font-size:16px; font-weight: bold" id="program-tab" data-toggle="tab" href="#program" role="tab" aria-controls="program" aria-selected="<?= $is_active_program ?>"><span class="badge badge-secondary">1.</span> Program</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $kegiatan ?>" title="Kegiatan" style="font-size:16px; font-weight: bold" id="kegiatan-tab" data-toggle="tab" href="#kegiatan" role="tab" aria-controls="kegiatan" aria-selected="<?= $is_active_kegiatan ?>"><span class="badge badge-secondary">2.</span> Kegiatan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $subkegiatan ?>" title="Sub Kegiatan" style="font-size:16px; font-weight: bold" id="subkegiatan-tab" data-toggle="tab" href="#subkegiatan" role="tab" aria-controls="subkegiatan" aria-selected="<?= $is_active_subkegiatan ?>"><span class="badge badge-secondary">3.</span> Sub Kegiatan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $uraian ?>" title="Uraian Kegiatan" style="font-size:16px; font-weight: bold" id="uraian-tab" data-toggle="tab" href="#uraian" role="tab" aria-controls="uraian" aria-selected="<?= $is_active_uraian ?>"><span class="badge badge-secondary">4.</span> Uraian Kegiatan</a>
            </li>
        </ul>
        <div class="x_panel" style="border-top:0">
            <div class="x_content">
                <div class="tab-content" id="myTabContent">
                    <?php
                    if ($this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'SUPER_ADMIN' || $this->session->userdata('role') === 'VERIFICATOR') :
                    ?>
                        <div class="tab-pane <?= $part ?> <?= $is_show_part ?>" id="part" role="tabpanel" aria-labelledby="part-tab"></div>
                    <?php endif; ?>
                    <div class="tab-pane <?= $program ?> <?= $is_show_program ?>" id="program" role="tabpanel" aria-labelledby="program-tab"></div>
                    <div class="tab-pane <?= $kegiatan ?> <?= $is_show_kegiatan ?>" id="kegiatan" role="tabpanel" aria-labelledby="kegiatan-tab"></div>
                    <div class="tab-pane <?= $subkegiatan ?> <?= $is_show_subkegiatan ?>" id="subkegiatan" role="tabpanel" aria-labelledby="subkegiatan-tab"></div>
                    <div class="tab-pane <?= $uraian ?> <?= $is_show_uraian ?>" id="uraian" role="tabpanel" aria-labelledby="uraian-tab"></div>
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
                    <div id="help-block-program"></div>
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
                <div class="listUnor"></div>
                <div class="divider-dashed"></div>
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
                    <div id="help-block-program"></div>
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
                    <label for="unor">Pilih Unit Organisasi <span class="text-danger">*</span></label>
                    <select name="unor" id="unor" required data-parsley-errors-container="#help-block-unor"></select>
                    <div id="help-block-unor"></div>
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
                    <div id="help-block-program"></div>
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
                    <div id="help-block-kegiatan"></div>
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
                    <div id="help-block-kegiatan"></div>
                </div>
                <div class="form-group">
                    <label for="subkegiatan">Pilih Sub Kegiatan <span class="text-danger">*</span></label>
                    <select name="subkegiatan" id="subkegiatan" style="width:100%" required data-parsley-errors-container="#help-block-subkegiatan"></select>
                    <div id="help-block-subkegiatan"></div>
                </div>
                <div class="divider-dashed"></div>
                <div class="form-group">
                    <label for="kode_uraian">Kode Uraian <span class="text-danger">*</span></label>
                    <input type="text" id="kode_uraian" name="kode_uraian" class="form-control" required data-parsley-remote="<?= base_url('app/programs/cek_kode/kodeuraian') ?>" data-parsley-remote-reverse="false" data-parsley-remote-options='{ "type": "POST" }' data-parsley-remote-message="Kode Uraian sudah pernah digunakan !" data-parsley-pattern="^(([0-9.]?)*)+$" data-parsley-trigger="focusout">
                </div>
                <div class="form-group">
                    <label for="nama_uraian">Uraian <span class="text-danger">*</span></label>
                    <input type="text" id="nama_uraian" name="nama_uraian" class="form-control" required data-parsley-whitespace="squish" data-parsley-remote="<?= base_url('app/programs/cek_kode/namauraian') ?>" data-parsley-remote-reverse="false" data-parsley-remote-options='{ "type": "POST" }' data-parsley-remote-message="Nama Uraian sudah pernah digunakan !" data-parsley-trigger="focusout">
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
        <?= form_open('#', ['id' => 'formAlokasiPagu', 'data-parsley-validate' => '']); ?>
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



<script>
    $(function() {
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
        // Initial load
        $('#kegiatan,#subkegiatan,#part,#uraian,#program').html(`<div class="d-flex justify-content-center align-items-center align-self-center py-4"><img src="${_uri}/template/assets/loader/motion-blur.svg" alt="Loading" class="mr-3" width="40"><h4>Loading data, mohon tunggu.</h4></div>`);
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
            getListPart().then((data) => {
                if (data.code === 404) {
                    $('#part').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('#part').html(data.result);
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
            getListPart().then((data) => {
                if (data.code === 404) {
                    $('#part').html(`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}"</div>`);
                    NProgress.done()
                    return false;
                }
                $('#part').html(data.result);
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
        var option = {
            valueNames: ['nama', 'kode'],
            searchColumns: ['nama', 'kode'],
            page: 5,
            pagination: [{
                item: "<li class='page-item rounded-0'><a class='page page-link rounded-0' href='#'></a></li>"
            }],
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

        // $(":input").inputmask();
        let total_pagu = MODAL_SUBKEGIATAN.find('input[name="total_pagu"]');
        $(total_pagu).inputmask("decimal", {
            radixPoint: ",",
            groupSeparator: ",",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            prefix: ''
        });

        $('select#part, select#kegiatan, select#subkegiatan').each(function() {
            $(this).select2({
                dropdownParent: $(this).parent()
            });
        })

        // select Unit Organisasi
        $('select[name="unor"]').select2({
            placeholder: 'Pilih Unor',
            allowClear: true,
            // maximumSelectionLength: 1,
            width: "100%",
            // theme: "classic",
            dropdownParent: MODAL_PROGRAM,
            // templateResult: formatUserSelect2,
            ajax: {
                // delay: 250,
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
        $('select[name="part"]').select2({
            placeholder: 'Pilih Badan / Bagian / Bidang',
            allowClear: true,
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


        function listUnor() {
            $.getJSON('<?= base_url('app/programs/getListUnor') ?>', (res) => {
                let data = '';
                res.forEach((r, i) => {
                    data += `<tr>
                                <td valign="middle" class="">${r.text.toUpperCase()}</td>
                                <td width="5%"><button type="button" onclick="Hapus(${r.id},'${_uri}/app/programs/hapus/ref_unors')" class="btn btn-danger btn-danger m-0"><i class="fa fa-trash"></i></button></td>
                                <td width="5%"><button onclick="Edit('${_uri}/app/programs/detail/ref_unors','.modal-unor-edit')" type="button" class="btn btn-light m-0"><i class="fa fa-pencil"></i></button></td>
                            </tr>`;
                })
                LIST_UNOR.html(`
                    <div class="table-responsive p-3 shadow mb-3">
                        <h5>Daftar Unor</h5>
                        <table class="table table-condensed table-bordered">
                            <tbody>
                                ${data}
                            </tbody>
                        </table>
                    </div>
                `);
            });
            return false;
        }

        MODAL_UNOR.on('show.bs.modal', (e) => {
            listUnor();
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

        MODAL_URAIAN.on('hidden.bs.modal', function(e) {
            FORM_URAIAN[0].reset();
            FORM_URAIAN.parsley().reset();
            $('select[name="kegiatan"]').val('').trigger('change');
            $('select[name="sub_kegiatan"]').val('').trigger('change');
        })

        FORM_KEGIATAN.parsley();
        FORM_KEGIATAN.on("submit", function(e) {
            e.preventDefault();
            $url = $(this).attr('action');
            $data = FORM_KEGIATAN.serialize();
            // if(FORM_KEGIATAN.parsley().isValid()) {
            $.post($url, $data, (response) => {
                if (response === 200) {
                    window.location.reload();
                }
            }, 'json');
            // }
        });

        FORM_PROGRAM.parsley();
        FORM_PROGRAM.on("submit", function(e) {
            e.preventDefault();
            $url = $(this).attr('action');
            $data = FORM_PROGRAM.serialize();
            // if(FORM_PROGRAM.parsley().isValid()) {
            $.post($url, $data, (response) => {
                if (response === 200) {
                    window.location.reload();
                }
            }, 'json');
            // }
        });

        FORM_SUBKEGIATAN.parsley();
        FORM_SUBKEGIATAN.on("submit", function(e) {
            e.preventDefault();
            $url = $(this).attr('action');
            $data = FORM_SUBKEGIATAN.serialize();
            // if(FORM_SUBKEGIATAN.parsley().isValid()) {
            $.post($url, $data, (response) => {
                if (response === 200) {
                    window.location.reload();
                }
            }, 'json');
            // }
            return false;
        });

        FORM_URAIAN.parsley();
        FORM_URAIAN.on("submit", function(e) {
            e.preventDefault();
            $url = $(this).attr('action');
            $data = FORM_URAIAN.serialize();
            // if(FORM_URAIAN.parsley().isValid()) {
            $.post($url, $data, (response) => {
                if (response === 200) {
                    window.location.reload();
                    FORM_URAIAN[0].reset();
                }
            }, 'json');
            // }
            return false;
        });

        FORM_PART.parsley();
        FORM_PART.on("submit", function(e) {
            e.preventDefault();
            $url = $(this).attr('action');
            $data = FORM_PART.serialize();
            if (FORM_PART.parsley().isValid()) {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    }
                }, 'json');
            }
            return false;
        });

        FORM_UNOR.parsley();
        FORM_UNOR.on("submit", function(e) {
            e.preventDefault();
            $url = $(this).attr('action');
            $data = FORM_UNOR.serialize();
            if (FORM_UNOR.parsley().isValid()) {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        // window.location.reload();
                        listUnor();
                        FORM_UNOR[0].reset();
                    }
                }, 'json');
            }
            return false;
        });

        FORM_UNOR_EDIT.on("submit", function(e) {
            e.preventDefault();
            $url = $(this).attr('action');
            $data = FORM_UNOR_EDIT.serialize();
            if (FORM_UNOR_EDIT.parsley().isValid()) {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        // window.location.reload();
                        listUnor();
                        FORM_UNOR_EDIT[0].reset();
                        MODAL_UNOR_EDIT.modal('hide');
                    }
                }, 'json');
            }
            return false;
        });

        FORM_PART_EDIT.on("submit", function(e) {
            e.preventDefault();
            $url = $(this).attr('action');
            $data = FORM_PART_EDIT.serialize();
            if (FORM_PART_EDIT.parsley().isValid()) {
                $.post($url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    }
                }, 'json');
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

    function InputPagu(id, url, paguAwal) {
        let $modal = $(".modal-alokasipagu"),
            $form = $modal.find("form#formAlokasiPagu");
        $modal.modal('show');

        $modal.on('shown.bs.modal', function(e) {
            $form.find('input[name="jumlah"]').val(formatRupiah(paguAwal));
            $form.on("submit", function(e) {
                e.preventDefault();
                $data = $(this).serializeArray();
                if ($(this).parsley().isValid()) {
                    $data.push({
                        "name": "id",
                        "value": id
                    });
                    $.post(url, $data, (response) => {
                        if (response === 200) {
                            window.location.reload();
                        }
                    }, 'json');
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