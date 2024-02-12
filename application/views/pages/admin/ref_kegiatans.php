<div class="row">
    <div class="container">
        <div class="col-md-8 mt-4">
        <?= form_open(base_url('/app/programs/update/ref_kegiatans'), ['id' => 'formKegiatans'], ['uid' => $data->id]); ?>
        <input type="hidden" name="part" value="<?= $this->session->userdata('part') ?>">
                <div class="form-group">
                    <?php 
                    $ref_program = $this->crud->getWhere('ref_programs', ['id' => $data->fid_program])->row();
                    ?>
                    <button type="button" role="button" class="btn btn-sm btn-info rounded-pill d-block pull-right" id="gantiUser"><i class="fa fa-pencil mr-1"></i> Ubah</button>
                    <button type="button" role="button" class="btn btn-sm btn-danger rounded-pill d-none pull-right" id="gantiUserBatal"><i class="fa fa-close mr-1"></i> Batal</button>
                    <h6><?= $ref_program->nama ?></h6>
                    <hr>            
                </div>
                <div class="form-group">
                    <label for="program">Pilih Program</label>
                    <select name="program" id="program" data-parsley-errors-container="#help-block-program" disabled></select>
                    <div id="help-block-program" class="mt-1 text-secondary text-right">*) apabila tidak di ganti, biarkan kosong bagian ini.</div>
                </div>
                <div class="divider-dashed"></div>
                <div class="form-group">
                    <label for="kode_kegiatan">Kode Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" id="kode_kegiatan" name="kode_kegiatan" value="<?= $data->kode ?>" class="form-control" required data-parsley-pattern="^(([0-9.]?)*)+$" data-parsley-remote="<?= base_url('app/programs/cek_kode/kegiatan') ?>" data-parsley-remote-reverse="false" data-parsley-remote-message="Kode Kegiatan sudah pernah digunakan !" data-parsley-trigger="change">
                </div>
                <div class="form-group">
                    <label for="kegiatan">Nama Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" id="kegiatan" name="kegiatan" value="<?= $data->nama ?>" class="form-control" required data-parsley-trigger="keyup">
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
        // select program
        $('select[name="program"]').select2({
            placeholder: 'Pilih Program',
			width: "100%",
            ajax: {
                delay: 250,
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

        $(document).on("click", "button#gantiUser", () => {
			$('select[name="program"]').prop("disabled", false).focus();
			$('button#gantiUserBatal').removeClass('d-none').addClass('d-block');
			$("button#gantiUser").removeClass('d-block').addClass('d-none');
		});

		$(document).on("click", "button#gantiUserBatal", () => {
			$('select[name="program"]').prop("disabled", true);
			$('button#gantiUserBatal').addClass('d-none').removeClass('d-block');
			$("button#gantiUser").addClass('d-block');
			$('select[name="program"]').select2("val", "0");
		});

        let $form = $("form#formKegiatans");
        $form.on("submit", function(e) {
            e.preventDefault();
            let _ = $(this),
                data = _.serialize();

            $.post(_.attr('action'), data, function(res){
                if(res === 200) {
                    window.location.replace(`${_uri}/app/programs?tab=%23kegiatan`)
                }
            }, 'json');
        })
    })
</script>