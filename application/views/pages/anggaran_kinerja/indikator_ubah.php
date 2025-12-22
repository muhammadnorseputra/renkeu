<div class="accordion" id="accordion1" role="tablist" aria-multiselectable="true">

    <?= form_open(base_url("app/target/ubah_proses"), ['id' => 'formIndikatorUbah', 'data-parsley-validate' => '']); ?>
    <?php 
    foreach ($periode->result() as $key => $value):
        $detailIndikator = $this->target->getDetailIndikator($id_indikator, $value->id); 
        $detailTarget = $this->target->getDetailTarget($id_indikator, $value->id);
    ?>
    <input type="hidden" name="id_indikator[<?= $detailIndikator->id ?? '' ?>]" value="<?= $detailIndikator->id ?? '' ?>">
    <input type="hidden" name="id_target[<?= $detailTarget->id ?? '' ?>]" value="<?= $detailTarget->id ?? '' ?>">

        <div class="panel">
            <a class="panel-heading collapsed" role="tab" id="heading-<?= $key ?>" data-toggle="collapse" data-parent="#accordion1" href="#collapse-<?= $value->id ?>" aria-expanded="false" aria-controls="collapseOne">
                <h4 class="panel-title"><?= $value->nama ?></h4>
            </a>
            <div id="collapse-<?= $value->id ?>" class="panel-collapse in collapse <?= $periode_id === $value->id ? 'show' : '' ?>" role="tabpanel" aria-labelledby="headingOne" style="">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label label-align" for="tahun">Target Tahun</label>
                                <select name="tahun" id="tahun" class="form-control" required="required" data-parsley-errors-container="#help-block-tahun">
                                    <option value="">Pilih Tahun</option>
                                    <?php
                                    $year = date('Y');
                                    for ($i = $year; $i <= $year + 3; $i++) {
                                        $selected = date('Y') == $i ? 'selected' : 'disabled';
                                        echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                                <div id="help-block-tahun" class="row col-md-12"></div>
                            </div>
                            <?php if ($table === 'ref_sub_kegiatans'): ?>
                                <div class="form-group">
                                    <label for="jenis_indikator">Jenis Indikator <span class="text-danger">*</span></label>
                                    <select name="jenis_indikator" id="jenis_indikator" class="form-control" required>
                                        <option value="">-- Pilih Jenis Indikator --</option>
                                        <?php foreach ($jenis_indikator->result() as $j):
                                            $selected = $row->fid_jenis_indikator === $j->id ? 'selected' : '';
                                        ?>
                                            <option value="<?= $j->id; ?>" <?= $selected; ?>><?= $j->nama; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="bidang">Penanggung Jawab <span class="text-danger">*</span></label>
                                <select name="bidang[]" id="bidang" multiple="multiple" data-parsley-errors-container="#help-block-bidang"></select>
                                <div id="help-block-bidang"></div>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama Indikator <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" class="form-control" value="<?= $detailIndikator->nama ?? "-" ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="is_jenis_<?= $value->id ?>">Jenis Output <span class="text-danger">*</span></label>
                                        <select name="is_jenis_<?= $value->id ?>" id="is_jenis_<?= $value->id ?>" class="form-control jenis-output" data-bulan="<?= $value->id ?>">
                                            <option value="">-- Pilih Jenis Output --</option>
                                            <option value="1" <?= @$detailTarget->is_jenis === "1" ? "selected" : ""; ?>>Persentase (%)</option>
                                            <option value="2" <?= @$detailTarget->is_jenis === "2" ? "selected" : "" ?>>Jumlah Eviden</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2" id="formPersentase_<?= $value->id ?>" style="display:none;">
                                    <div class="form-group">
                                        <label for="persentase_<?= $value->id ?>">Peserntase % <span class="text-danger">*</span></label>
                                        <input type="text" name="persentase_<?= $value->id ?>" id="persentase_<?= $value->id ?>" class="form-control"
                                            data-parsley-pattern="^\d+(\.\d+)?$"
                                            data-parsley-pattern-message="Hanya boleh angka desimal dengan titik."
                                        
                                            value="<?= $detailTarget->persentase ?? "0" ?>">
                                    </div>
                                </div>
                                <div class="col-md-2" id="formEviden_<?= $value->id ?>" style="display:none;">
                                    <div class="form-group">
                                        <label for="jumlah_eviden_<?= $value->id ?>">Jumlah Eviden <span class="text-danger">*</span></label>
                                        <input type="text" name="jumlah_eviden_<?= $value->id ?>" id="jumlah_eviden_<?= $value->id ?>" class="form-control"
                                            data-parsley-pattern="^\d+(\.\d+)?$"
                                            data-parsley-pattern-message="Hanya boleh angka desimal dengan titik."
                                        
                                            value="<?= $detailTarget->eviden_jumlah ?? "0" ?>">
                                    </div>
                                </div>
                                <div class="col-md-3" id="formKeteranganEviden_<?= $value->id ?>" style="display:none;">
                                    <div class="form-group">
                                        <label for="keterangan_eviden_<?= $value->id ?>">Keterangan Eviden <span class="text-danger">*</span></label>
                                        <input type="text" name="keterangan_eviden_<?= $value->id ?>" id="keterangan_eviden_<?= $value->id ?>" class="form-control" value="<?= $detailTarget->eviden_jenis ?? "-" ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <hr />
    <div class="form-group">
        <button type="button" class="btn btn-danger rounded-0" onclick="window.location.href='<?= base_url('app/target') ?>'"><i class="fa fa-close mr-2"></i>Batal</button>
        <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
    </div>
    <?= form_close(); ?>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".jenis-output").forEach(function(select) {
            let bulan = select.dataset.bulan;
            let formPersentase = document.getElementById("formPersentase_" + bulan);
            let formEviden = document.getElementById("formEviden_" + bulan);
            let formKet = document.getElementById("formKeteranganEviden_" + bulan);

            function toggleFields() {
                if (select.value === "1") {
                    formPersentase.style.display = "block";
                    formEviden.style.display = "none";
                    formKet.style.display = "none";
                } else if (select.value === "2") {
                    formPersentase.style.display = "none";
                    formEviden.style.display = "block";
                    formKet.style.display = "block";
                } else {
                    formPersentase.style.display = "none";
                    formEviden.style.display = "none";
                    formKet.style.display = "none";
                }
            }

            toggleFields();
            select.addEventListener("change", toggleFields);
        });
    });

    $(function() {
        let $form = $("form#formIndikatorUbah");
        $form.on("submit", function(e) {
            e.preventDefault();
            $data = $(this).serialize();
            if ($(this).parsley().isValid()) {
                $.post(
                    $(this).attr('action'),
                    $data,
                    (response) => {
                        if (response === 200) {
                            window.location.href = `${_uri}/app/target`;
                        }
                    },
                    "json"
                );
            }
        });


        // select part
        const selectedBidang = <?= json_encode(explode(",", $this->target->getDetailIndikator($id_indikator, $periode_id)->fid_part)); ?>;

        // Manually add selected options to the select (in case they are not loaded yet)
        selectedBidang.forEach(function(id) {
            const option = new Option(id, id, true, true);
            $('#bidang').append(option).trigger('change');
        });

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
                delay: 350,
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
    })
</script>