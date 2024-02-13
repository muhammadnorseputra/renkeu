<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-print mr-2"></i> Laporan Anggaran & Kinerja</h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <?= form_open(base_url('app/capaian/laporan_cetak'), ['id' => 'formLaporan', 'target' => '_blank']) ?>
        <div class="col-md-2">
        <div class="form-group">
                            <select name="tahun" id="tahun" class="select2_single form-control" required="required" data-parsley-errors-container="#help-block-tahun">
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
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary rounded-0"><i class="fa fa-print mr-2"></i> Cetak</button>
        </div>
        <?= form_close() ?>
    </div>
</div>