<div class="page-title">
    <div class="title_left">
        <h3><i class="fa fa-inbox mr-2"></i> Formulir Usul SPJ</h3>
    </div>

    <div class="title_right">
        <div class="col-md-2 col-6 form-group row pull-right top_search">
            <button class="btn btn-danger rounded-0" onclick="window.location.href='<?= base_url('app/spj') ?>'">Batal<i class="fa fa-arrow-right ml-3"></i> </button>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<!-- Smart Wizard -->
<div class="x_panel">
    <div class="x_title">
        <h2>SPJ (Surat Pertanggung Jawaban)</h2>
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
        <div id="step-1">
            <?= form_open(base_url('app/spj/prosesusul'), ['id' => 'step-1','class' => 'form-horizontal form-label-left', 'data-parsley-validate' => '']); ?>
                    <div class="col-md-10 center-margin">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label label-align" for="part">Pilih Bidang</label>
                                    <select name="part" id="part" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-part">
                                        <option value="">Pilih Bidang</option>
                                        <?php  
                                            foreach($list_bidang as $bid):
                                                // $selected = $this->session->userdata('part') === $bid->id ? 'selected' : '';
                                                echo '<option value="'.$bid->id.'">'.$bid->nama.'</option>';
                                            endforeach;
                                        ?>
                                    </select>
                                    <div id="help-block-part"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label label-align" for="program">Pilih Program <span class="text-danger">*</span></label>
                                    <select name="program" id="program" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-program">
                                        <option value="">Pilih Program</option>
                                        <?php  
                                            foreach($list_program as $program):
                                                // $selected = $this->session->userdata('unor') === $program->fid_unor ? 'selected' : '';
                                                echo '<option value="'.$program->id.'">'.$program->nama.'</option>';
                                            endforeach;
                                        ?>
                                    </select>
                                    <div id="help-block-program"></div>
                                </div>
                            </div>
                        </div>                
                        <div class="form-group">
                            <label class="col-form-label label-align" for="kegiatan">Pilih Kegiatan <span class="text-danger">*</span></label>
                            <select name="kegiatan" id="kegiatan" class="form-control" required="required" data-parsley-errors-container="#help-block-kegiatan">
                                <option value="">Pilih Kegiatan</option>
                            </select>
                            <div id="help-block-kegiatan"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label label-align" for="sub_kegiatan">Pilih Sub Kegiatan <span class="text-danger">*</span></label>
                            <select name="sub_kegiatan" id="sub_kegiatan" class="form-control" required="required" data-parsley-errors-container="#help-block-sub-kegiatan">
                                <option value="">Pilih Sub Kegiatan</option>
                            </select>
                            <div id="help-block-sub-kegiatan"></div>
                        </div>
                        <div class="form-group row">
                            <label for="koderek" class="row col-md-12">Koderek <span class="text-danger ml-1 mr-1">*</span></label>
                            <input type="text" name="koderek" id="koderek" class="form-control col-md-6" required="required" data-parsley-errors-container="#help-block-koderek">
                            <div id="help-block-koderek" class="row col-md-12"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-form-label label-align" for="bulan">SPJ Bulan</label>
                                    <select name="bulan" id="bulan" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-bulan">
                                        <option value="">Pilih Bulan</option>
                                        <?php 
                                            $m = date('m');
                                            foreach(bulanIndo() as $month => $monthName) {
                                                $selected = date('m') === $month ? 'selected' : '';
                                                echo '<option value="'.$month.'" '.$selected.'>'.$monthName.'</option>';
                                            }
                                        ?>
                                    </select>
                                    <div id="help-block-bulan" class="row col-md-12"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-form-label label-align" for="tahun">SPJ Tahun</label>
                                    <select name="tahun" id="tahun" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-tahun">
                                        <option value="">Pilih Tahun</option>
                                        <?php 
                                            $year = date('Y');
                                            for($i=$year;$i<=$year+3;$i++) {
                                                $selected = date('Y') === $i ? 'selected' : '';
                                                echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                                            }
                                        ?>
                                    </select>
                                    <div id="help-block-tahun" class="row col-md-12"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label label-align" for="uraian">Uraian <span class="text-danger">*</span></label>
                            <textarea name="uraian" id="uraian" cols="30" rows="5" class="form-control" required="required"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="jumlah" class="row col-md-12">Jumlah <span class="text-danger ml-1 mr-1">*</span></label>
                            <input type="text" name="jumlah" id="jumlah" class="form-control col-md-6" required="required" data-parsley-errors-container="#help-block-jumlah">
                            <div id="help-block-jumlah" class="row col-md-12"></div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary rounded-0 pull-right mt-3" type="submit"><i class="fa fa-save mr-2"></i> Simpan & Lanjutkan</button>
                        </div>
                    </div>
            <?= form_close() ?>

        </div>
        <div id="step-2">
        <h2 class="StepTitle">Step 2 Content</h2>
        <p>
            do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
            fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
            in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
        </div>
        <div id="step-3">
        <h2 class="StepTitle">Step 3 Content</h2>
        <p>
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
            eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
            in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
        </div>
        <div id="step-4">
        <h2 class="StepTitle">Step 4 Content</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
            in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
            in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
        </div>

    </div>
</div>
<!-- End SmartWizard Content -->

<style>
    .actionBar {
        display: none;
    }
</style>