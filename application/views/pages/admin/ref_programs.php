<div class="row">
    <div class="container">
        <div class="col-md-8 mt-4">
            <?= form_open(base_url('/app/programs/update/ref_programs'), ['id' => 'formPrograms'], ['uid' => $data->id]); ?>
            <div class="form-group">
                <?php 
                $ref_unor = $this->crud->getWhere('ref_unors', ['id' => $data->fid_unor])->row();
                ?>
                <button type="button" role="button" class="btn btn-sm btn-info rounded-pill d-block pull-right" id="gantiUser"><i class="fa fa-pencil mr-1"></i> Ubah</button>
                <button type="button" role="button" class="btn btn-sm btn-danger rounded-pill d-none pull-right" id="gantiUserBatal"><i class="fa fa-close mr-1"></i> Batal</button>
                <h6><?= $ref_unor->nama ?></h6>
                <hr>            
            </div>
            <div class="form-group">
                <label for="unor">Pilih Unit Organisasi <span class="text-danger">*</span></label>
                <select name="unor" id="unor" data-parsley-errors-container="#help-block-unor" disabled></select>
                <div id="help-block-unor"></div>
            </div>
            <div class="divider-dashed"></div>
            <div class="form-group">
                <label for="kode_program">Kode Program <span class="text-danger">*</span></label>
                <input type="text" id="kode_program" name="kode_program" value="<?= $data->kode ?>" class="form-control" required data-parsley-pattern="^(([0-9.]?)*)+$" data-parsley-remote="<?= base_url('app/programs/cek_kode/kodeprogram') ?>" data-parsley-remote-reverse="false" data-parsley-remote-options='{ "type": "POST" }' data-parsley-remote-message="Kode Program sudah pernah digunakan !" data-parsley-trigger="change">
            </div>
            <div class="form-group">
                <label for="program">Nama Program <span class="text-danger">*</span></label>
                <input type="text" id="program" name="program" value="<?= $data->nama ?>" class="form-control" required data-parsley-remote="<?= base_url('app/programs/cek_kode/namaprogram') ?>" data-parsley-remote-reverse="false" data-parsley-remote-options='{ "type": "POST" }' data-parsley-remote-message="Nama Program sudah pernah digunakan !" data-parsley-trigger="keyup">
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
        // select Unit Organisasi
        $('select[name="unor"]').select2({
            placeholder: 'Pilih Unor',
            allowClear: true,
            // maximumSelectionLength: 1,
            width: "100%",
            // theme: "classic",
            dropdownParent: $("form#formPrograms"),
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

        $(document).on("click", "button#gantiUser", () => {
			$('select[name="unor"]').prop("disabled", false).focus();
			$('button#gantiUserBatal').removeClass('d-none').addClass('d-block');
			$("button#gantiUser").removeClass('d-block').addClass('d-none');
		});

		$(document).on("click", "button#gantiUserBatal", () => {
			$('select[name="unor"]').prop("disabled", true);
			$('button#gantiUserBatal').addClass('d-none').removeClass('d-block');
			$("button#gantiUser").addClass('d-block');
			$('select[name="unor"]').select2("val", "0");
		});

        let $form = $("form#formPrograms");
        $form.on("submit", function(e) {
            e.preventDefault();
            let _ = $(this),
                data = _.serialize();

            $.post(_.attr('action'), data, function(res){
                if(res === 200) {
                    window.location.replace(`${_uri}/app/programs?tab=%23program`)
                }
            }, 'json');
        })
    })
</script>