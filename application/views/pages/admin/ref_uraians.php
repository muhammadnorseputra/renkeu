<div class="row">
    <div class="container">
        <div class="col-md-8 mt-4">
            <?= form_open(base_url('/app/programs/update/ref_uraians'), ['id' => 'formUraians'], ['uid' => $data->id]); ?>
                <div class="form-group">
                    <label for="kegiatan">Pilih Kegiatan</label>
                    <select name="kegiatan" id="kegiatan" data-parsley-errors-container="#help-block-kegiatan"></select>
                    <div id="kegiatan" class="mt-1 text-secondary text-right">*) apabila tidak di ganti, biarkan kosong bagian ini.</div>
                </div>
                <div class="form-group">
                    <label for="subkegiatan">Pilih Sub Kegiatan</label>
                    <select name="subkegiatan" id="subkegiatan" class="form-control" style="width:100%" data-parsley-errors-container="#help-block-subkegiatan"></select>
                    <div id="subkegiatan" class="mt-1 text-secondary text-right">*) apabila tidak di ganti, biarkan kosong bagian ini.</div>
                </div>
                <div class="divider-dashed"></div>
                <div class="form-group">
                    <label for="kode_uraian">Kode Uraian <span class="text-danger">*</span></label>
                    <input type="text" id="kode_uraian"  value="<?= $data->kode ?>" name="kode_uraian" class="form-control" 
                    required 
                    data-parsley-pattern="^(([0-9.]?)*)+$" 
                    data-parsley-trigger="focusout">
                </div>
                <div class="form-group">
                    <label for="nama_uraian">Uraian <span class="text-danger">*</span></label>
                    <input type="text" id="nama_uraian"  value="<?= $data->nama ?>" name="nama_uraian" class="form-control" 
                    required 
                    data-parsley-whitespace="squish"
                    data-parsley-trigger="focusout">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
                    <button type="button" class="btn btn-danger rounded-0" onclick="window.history.back(-1)"><i class="fa fa-repeat mr-2"></i>Batal</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(function() {
        // select kegiatan
        $('select[name="kegiatan"]').select2({
            placeholder: 'Pilih Kegiatan',
            allowClear: true,
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

        let $form = $("form#formUraians");
        $form.on("submit", function(e) {
            e.preventDefault();
            let _ = $(this),
                data = _.serialize();

            $.post(_.attr('action'), data, function(res){
                if(res === 200) {
                    window.location.replace(`${_uri}/app/programs?tab=%23uraian`)
                }
            }, 'json');
        })
    })
</script>