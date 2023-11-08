<div class="row">
    <div class="col-md-7">
    <div class="x_panel">
        <div class="x_title">
        <h2>To : <?= $to ?></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <!-- CONTENT -->
            <?= form_open(base_url('app/messages/update'), ['id' => 'formMessage', 'class' => 'form-horizontal form-label-left', 'data-parsley-validate' => ''], ['uid' => encrypt_url($row->id), 'to' => encrypt_url($row->to)]); ?>
			<div class="form-group row">
				<div class="col-md-6">
					<label class="control-label">Pilih Type <span class="text-danger">*</span></label>
					<select name="type" class="form-control" required>
						<option value="">Choose option</option>
						<option value="SUCCESS" <?= $row->type === 'SUCCESS' ? 'selected' : ''; ?>>Success</option>
						<option value="WARNING" <?= $row->type === 'WARNING' ? 'selected' : ''; ?>>Penting</option>
						<option value="INFO" <?= $row->type === 'INFO' ? 'selected' : ''; ?>>Info</option>
						<option value="DANGER" <?= $row->type === 'DANGER' ? 'selected' : ''; ?>>Sangat Penting</option>
					</select>
				</div>
				<div class="col-md-6">
					<label class="control-label">Pilih Mode <span class="text-danger">*</span></label>
					<select name="mode" class="form-control" required>
						<option value="">Choose option</option>
						<option value="GLOBAL" <?= $row->mode === 'GLOBAL' ? 'selected' : ''; ?>>Global</option>
						<option value="PRIVATE_ALL" <?= $row->mode === 'PRIVATE_ALL' ? 'selected' : ''; ?>>Private All</option>
						<option value="PRIVATE" <?= $row->mode === 'PRIVATE' ? 'selected' : ''; ?>>Private</option>
					</select>
				</div>
			</div>
			<div class="form-group row">
					<div class="d-none" id="select_user">
						<label for="user">To</label>
						: <?= $to ?>
						<button type="button" role="button" class="btn btn-sm btn-info rounded-pill d-block pull-right" id="gantiUser"><i class="fa fa-user mr-1"></i> Ganti</button>
						<button type="button" role="button" class="btn btn-sm btn-danger rounded-pill d-none pull-right" id="gantiUserBatal"><i class="fa fa-close mr-1"></i> Batal</button>
						<p class="help-block">*) Silahkan pilih user jika tujuan diganti, jika tidak biarkan kosong !</p>
						<select name="user" id="user" class="select2_single form-control" disabled></select>
					</div>
			</div>
			<div class="divider-dashed"></div>
			<div class="form-group row">
				<textarea required data-parsley-trigger="keyup" name="message" id="message" class="resizable_textarea bg-light form-control border-0" cols="30" rows="10" placeholder="Ketik pesan anda disini ..."><?= $row->message ?></textarea>
			</div>
			<div class="divider-dashed"></div>
			
			<div class="form-group my-3 row">
				<div class="col-md-2">
					<label for="aktif">Status Aktif</label> <div class="clearfix"></div>
					<?php 
						$is_checked = $row->is_aktif === 'Y' ? 'checked' : '';
					?>
					<input type="checkbox" name="aktif" value="Y" class="js-switch" id="aktif" <?= $is_checked ?>/>
				</div>
            </div>
			<div class="divider-dashed"></div>
            <button class="btn btn-success rounded-0" type="submit"><i class="fa fa-send"></i> Simpan</button>
	        <button class="btn btn-danger rounded-0" type="button" onclick="window.history.back(-1)"><i class="fa fa-close"></i> Batal</button>
	        <button class="btn btn-info pull-right rounded-0" id="btnHapus" data-uid="<?= $row->id ?>" data-url="<?= base_url("app/messages/delete") ?>" type="button"><i class="fa fa-trash"></i> Hapus Permanent</button>
	
		    <?= form_close() ?>
            <!-- /CONTENT -->
        </div>
    </div>
    </div>
</div>

<script>
	$(() => {
		$("#formMessage").on("submit", function(e) {
			e.preventDefault();
			var _ = $(this),
			$url = _.attr('action'),
			$data = _.serialize();
			
			if(_.parsley().isValid()) {
				$.post($url, $data, function(result) {
					if(result.status === 200) {
						alert(result.pesan);
						window.location.href = '<?= base_url("app/messages") ?>';
					}
				}, 'json');
			}
			// console.log(form.serialize())
		});

		$('select[name="user"]').select2({
			placeholder: 'Pilih User (ketik username atau nama)',
			allowClear: false,
			width: "100%",
			// theme: "classic",
			dropdownParent: $('#formMessage'),
			templateResult: formatUserSelect2,
			ajax: {
				method: 'post',
				url: '<?= base_url("app/users/getAll") ?>',
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

		let getValueMode = $("select[name='mode']").val();
		if(getValueMode === 'PRIVATE') {
			$('#select_user').attr("class", "col-md-12 d-block");
		}

		$(document).on("change", "[name='mode']", function(e) {
			e.preventDefault();
			let _ = $(this),
			value = _.val();
			if(value === 'PRIVATE') {
				$('#select_user').attr("class", "col-md-12 d-block");
				return false;
			}
			$('#select_user').attr("class", "col-md-2 d-none");
			$('select[name="user"]').select2("val", "0");
		});

		$(document).on("click", "button#gantiUser", () => {
			$('select[name="user"]').prop("disabled", false).focus();
			$('button#gantiUserBatal').removeClass('d-none').addClass('d-block');
			$("button#gantiUser").removeClass('d-block').addClass('d-none');
		});

		$(document).on("click", "button#gantiUserBatal", () => {
			$('select[name="user"]').prop("disabled", true);
			$('button#gantiUserBatal').addClass('d-none').removeClass('d-block');
			$("button#gantiUser").addClass('d-block');
			$('select[name="user"]').select2("val", "0");
		})

		function formatUserSelect2(state) {
			if (!state.id) {
				return state.text;
			}
			var baseUrl = `${_uri}/template/assets/picture_akun`;
			var $state = $(
				`<span><img src="${baseUrl}/${state.picture}" width="20" height="20" class="img-circle mr-2" />${state.text} - <span class="small">${state.job}</span></span>`
			);
			return $state;
		};

		$(document).on("click", "button#btnHapus", function(e) {
			e.preventDefault();
			let _ = this,
			id = _.dataset.uid,
			url = _.dataset.url,
			warm = "Apakah anda yakin akan menghapus pesan tersebut secara permanent ?";

			let whr = {
				id: id
			};

			if(confirm(warm)) {
				$.post(url, whr, (res) => window.location.href = '<?= base_url("app/messages") ?>', 'json');
				return false;
			}
		})
	})
</script>