<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-book mr-2"></i> Buku Jaga Kegiatan</h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li><button class="btn btn-sm btn-light rounded-full px-2 py-0 mx-2 my-0" onclick="return introJs().start()"><i class="fa fa-info-circle"></i></button></li>
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            <div class="col-12 col-md-6">
                <?= form_open(base_url('app/bukujaga/view'), ['id' => 'formBukuJaga', 'class' => 'form-horizontal form-label-left', 'data-parsley-validate' => '', 'data-parsley-errors-messages-disabled' => '']) ?>
                <input type="hidden" name="ref_part">
                <input type="hidden" name="ref_program">
                <input type="hidden" name="ref_kegiatan">
                <input type="hidden" name="ref_subkegiatan">
                <div class="form-group row">
                    <label for="koderek" class="row col-md-12">Kode Program/Kegiatan/Sub Kegiatan <span class="text-danger ml-1 mr-1">*</span></label>
                    <input type="text" readonly name="koderek" id="koderek" class="form-control col-md-6" required="required" onclick="showModal()" data-title="Cari Kode" data-intro="Silahkan klik pada kolom pencarian untuk mencari kode Program/Kegiatan/Sub Kegiatan">
                    <button type="button" class="btn border btn-light rounded-0" data-toggle="modal" data-target="#modelSearchKode"><i class="fa fa-search"></i></button>
                    <button type="submit" class="btn border btn-info rounded-0" data-title="Submit" data-intro="Klik tombol lihat untuk memproses"><i class="fa fa-download mr-2"></i> Lihat</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
        <div id="displayData"></div>
    </div>
</div>

<!-- The Modal -->
<div class="modal" id="modelSearchKode" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-0">

            <form class="form-horizontal" action="<?= base_url('app/bukujaga/carikode') ?>" method="post" id="formCariKode" data-parsley-validate>
                <input type="hidden" name="part" value="<?= $this->session->userdata('part'); ?>">
                <!-- Modal Header -->
                <div class="modal-header bg-success text-white rounded-0">
                    <h4 class="modal-title">Cari Kode</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="program">Pilih Program <span class="text-danger">*</span></label>
                        <select name="program" id="program" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-program">
                            <option value="">Pilih Program</option>
                            <?php
                            foreach ($programs->result() as $program) :
                                echo '<option value="' . $program->id . '">' . $program->kode . ' - ' . $program->nama . '</option>';
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
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success rounded-0">Pilih</button>
                </div>

            </form>
        </div>
    </div>
</div>