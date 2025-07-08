<div class="row">
    <div class="col-md-12">
        <?= form_open(base_url("app/target/ubah_proses"), ['id' => 'formIndikatorUbah', 'data-parsley-validate' => ''], ['id' => $id_indikator]); ?>
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
            <select name="bidang[]" id="bidang" multiple="multiple" required data-parsley-errors-container="#help-block-bidang"></select>
            <div id="help-block-bidang"></div>
        </div>
        <div class="form-group">
            <label for="nama">Nama Indikator <span class="text-danger">*</span></label>
            <!-- <input type="text" name="nama" id="nama" class="form-control" required value="<?= $row->nama ?>"> -->
            <textarea name="nama" id="nama" cols="30" rows="6" class="form-control" required><?= $row->nama ?></textarea>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="persentase">Peserntase % <span class="text-danger">*</span></label>
                    <input type="number" name="persentase" id="persentase" class="form-control" required value="<?= $row->persentase ?>">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="jumlah_eviden">Jumlah Eviden <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_eviden" id="jumlah_eviden" class="form-control" required value="<?= $row->eviden_jumlah ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="keterangan_eviden">Keterangan Eviden <span class="text-danger">*</span></label>
                    <input type="text" name="keterangan_eviden" id="keterangan_eviden" class="form-control" required value="<?= $row->eviden_jenis ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-danger rounded-0" onclick="window.location.href='<?= base_url('app/target') ?>'"><i class="fa fa-close mr-2"></i>Batal</button>
            <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<script>
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
        const selectedBidang = <?= json_encode(explode(",", $row->fid_part)); ?>;

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