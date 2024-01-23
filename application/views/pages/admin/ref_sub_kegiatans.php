<div class="row">
    <div class="container">
        <div class="col-md-8 mt-4">
        <?php 
        $tbl = $this->uri->segment(5);
        ?>
        <?= 
        form_open(base_url('/app/programs/update/ref_sub_kegiatans'), ['id' => $tbl, 'data-parsley-validate' => ''], ['uid' => $data->id]);
         ?>
         <div class="form-group">
            <?php 
            $ref_kegiatan = $this->crud->getWhere('ref_kegiatans', ['id' => $data->fid_kegiatan])->row();
            ?>
            <button type="button" role="button" class="btn btn-sm btn-info rounded-pill d-block pull-right" id="gantiUser"><i class="fa fa-pencil mr-1"></i> Ubah</button>
			<button type="button" role="button" class="btn btn-sm btn-danger rounded-pill d-none pull-right" id="gantiUserBatal"><i class="fa fa-close mr-1"></i> Batal</button>
            <h6><?= $ref_kegiatan->nama ?></h6>
						
         </div>
         <hr>
            <div class="form-gorup">
                <label for="kegiatan">Pilih Kegiatan</label>
                <select name="kegiatan" id="kegiatan" data-parsley-errors-container="#help-block-kegiatan" disabled></select>
                <div id="help-block-kegiatan"></div>
            </div>
            <div class="form-group mt-3">
                    <label for="kode_subkegiatan">Kode Sub Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="kode_subkegiatan"  value="<?= $data->kode ?>" class="form-control" 
                    required
                    data-parsley-pattern="^(([0-9.]?)*)+$" 
                    data-parsley-trigger="focusout">
                </div>
                <div class="form-group">
                    <label for="subkegiatan">Nama Sub Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="subkegiatan" value="<?= $data->nama ?>" class="form-control" required data-parsley-trigger="keyup">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success rounded-0"><i class="fa fa-save mr-2"></i>Simpan</button>
                    <button type="button" class="btn btn-danger rounded-0" onclick="window.history.back(-1)"><i class="fa fa-repeat mr-2"></i>Batal</button>
                </div>
        <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('select[name="kegiatan"]').select2({
			placeholder: 'Pilih Kegiatan',
			width: "100%",
			ajax: {
				// delay: 350,
				method: 'post',
				url: '<?= base_url("app/programs/getKegiatan") ?>',
				dataType: 'json',
				data: function (params) {
					return {
						q: params.term, // search term
					};
				},
				cache: true,
				processResults: function (data) {
				// Transforms the top-level key of the response object from 'items' to 'results'
					return {
						results: data
					};
				}
				// Additional AJAX parameters go here; see the end of this chapter for the full code of this example
			}
		});
        $(document).on("click", "button#gantiUser", () => {
			$('select[name="kegiatan"]').prop("disabled", false).focus();
			$('button#gantiUserBatal').removeClass('d-none').addClass('d-block');
			$("button#gantiUser").removeClass('d-block').addClass('d-none');
		});

		$(document).on("click", "button#gantiUserBatal", () => {
			$('select[name="kegiatan"]').prop("disabled", true);
			$('button#gantiUserBatal').addClass('d-none').removeClass('d-block');
			$("button#gantiUser").addClass('d-block');
			$('select[name="kegiatan"]').select2("val", "0");
		})

        let $form = $("form#ref_sub_kegiatans");
        $form.on("submit", function(e) {
            e.preventDefault();
            let _ = $(this),
                data = _.serialize();

            $.post(_.attr('action'), data, function(res){
                if(res === 200) {
                    window.location.replace(`${_uri}/app/programs?tab=%23subkegiatan`)
                }
            }, 'json');
        })
    })
</script>