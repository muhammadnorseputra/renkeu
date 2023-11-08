<div class="row">
    <div class="col-md-12">
    <div class="x_panel">
        <div class="x_title">
        <h2>Tabel Notify</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
        </div>
        <div class="x_content">
        <div class="row">
            <!-- CONTENT -->
            <div class="col-sm-12">
				<div class="card-box table-responsive">
					<table id="table-messages" class="table table-condensed dt-responsive nowrap" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="text-center">No</th>
								<th>Status</th>
								<th>Mode</th>
                                <th>To</th>
								<th>Message</th>
								<th class="block">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
            </div>
            <!-- /CONTENT -->
        </div>
        </div>
    </div>
    </div>
</div>

<!-- compose -->
<div class="compose col-md-6">
	<div class="compose-header">
	New Message
	<button type="button" class="close compose-close">
		<span>×</span>
	</button>
	</div>

	<div class="compose-body">
		<?= form_open(base_url('app/messages/insert'), ['id' => 'formMessage', 'class' => 'form-horizontal form-label-left']); ?>
			<div class="form-group row">
				<div class="col-md-6">
					<label class="control-label">Pilih Type <span class="text-danger">*</span></label>
					<select name="type" class="form-control" required="required">
						<option value="">Choose option</option>
						<option value="SUCCESS">Success</option>
						<option value="WARNING">Penting</option>
						<option value="INFO">Info</option>
						<option value="DANGER">Sangat Penting</option>
					</select>
				</div>
				<div class="col-md-6">
					<label class="control-label">Pilih Mode <span class="text-danger">*</span></label>
					<select name="mode" id="mode" class="form-control" required="required">
						<option value="">Choose option</option>
						<option value="GLOBAL">Global</option>
						<option value="PRIVATE_ALL">Private All</option>
						<option value="PRIVATE">Private</option>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<div class="d-none" id="select_user">
					<label for="user">To</label>
					<select name="user" id="user" required="required" class="select2_single form-control" tabindex="-1"></select>
				</div>
			</div>
			<div class="divider-dashed"></div>
			<div class="form-group row">
				<textarea required="required" name="message" id="message" class="resizable_textarea form-control border-0" cols="30" rows="10" placeholder="Ketik pesan anda disini ..."></textarea>
			</div>
			<div class="divider-dashed"></div>
			
			<div class="form-group my-3 row">
				<div class="col-md-2">
					<label for="aktif">Status Aktif</label> <div class="clearfix"></div>
					<input data-parsley-validate-if-empty="true" type="checkbox" name="aktif" value="Y" class="js-switch" id="aktif" checked/>
				</div>
			</div>
		<?= form_close() ?>
	</div>

	<div class="compose-footer py-3 bg-light">
	<button id="send" class="btn btn-success rounded-0" type="button"><i class="fa fa-send"></i> Simpan</button>
	<button class="btn btn-danger rounded-0 compose-close" type="button"><i class="fa fa-close"></i> Batal</button>
	</div>
</div>
<!-- /compose -->
<script>
	$(function() {

		var tableMessage = $("#table-messages").DataTable({
			"processing": true,
			"serverSide": true,
			"paging": true,
			"ordering": true,
			"info": true,
			"searching": true,
			"deferRender": true,
			// "pagingType": "full_numbers",
			"responsive": true,
			"datatype": "json",
			// "scrollY": "800px",
			"scrollCollapse": true,
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			],
			"order": [],
			"ajax": {
				"url": `${_uri}/app/messages/ajax`,
				"type": "POST"
			},
			"columnDefs": [
				{
					"targets": [0],
					"orderable": false,
					"className": "text-center",
					"width": '5%',
					"createdCell": function (td, cellData, rowData, row, col) {
						if ( rowData[1] === 'Unpublish' ) {
							$(td).addClass('bg-danger text-white cursor-disabled').css({
								cursor: 'not-allowed',
								opacity: 0.3,
								userSelect: 'none'
							})
						}
					},
				},
				{
					"targets": [5],
					"orderable": false,
					"className": "text-center",
					"width": '5%',
				},
				{
					"targets": [2],
					"orderable": false,
					"className": "text-center",
					"width": '5%'
				},
				{
					"targets": [3],
					"orderable": false,
					"className": "text-left",
					"width": '15%'
				},
				{
					"targets": [1],
					"orderable": false,
					"className": "text-left",
					"width": '8%'
				},
				{
					"targets": [1,4],
					"orderable": false,
					"className": "text-left"
				},
			],
			
			// "createdRow": function( row, data, dataIndex ) {
			// 	if ( data[1] === "N" ) {
			// 		$(row).addClass('bg-light text-secondary cursor-disabled').css({
			// 			cursor: 'not-allowed',
			// 			opacity: 0.6,
			// 			userSelect: 'none'
			// 		});
			// 	}
			// console.log(row);
			// },
			"dom": 'Bfrtlip',
			"buttons": [
				{
					text: '<i class="fa fa-send mr-2"></i>Send Message',
					className: 'btn btn-success rounded-0 pull-left btn-compose',
				},
				{
					text: '<i class="fa fa-repeat mr-2"></i>Reload',
					className: 'btn btn-secondary rounded-0 pull-left',
					action: function ( e, dt, node, config ) {
						dt.ajax.reload();
					}
				},
				{
					text: '<i class="fa fa-filter"></i>',
					className: 'btn btn-info rounded-0 pull-left',
					action: function ( e, dt, node, config ) {
						console.log('oke')
					}
				},
			],
			"language": {
				"lengthMenu": "_MENU_ Data per halaman",
				"zeroRecords": "Belum Ada Notify",
				"info": "Showing page _PAGE_ of _PAGES_",
				"infoEmpty": "_MAX_ records",
				"infoFiltered": "(filtered from _MAX_ total records)",
				"search": "Cari Pesan :",
				"paginate": {
						"previous": `<i class="fa fa-long-arrow-left"></i>`,
						"next": `<i class="fa fa-long-arrow-right"></i>`
					},
				"emptyTable": "No matching records found, please filter this data"
			},
		});

		$(document).on("change", "[name='mode']", function(e) {
			e.preventDefault();
			let _ = $(this),
			value = _.val();
			if(value === 'PRIVATE') {
				$('#select_user').attr("class", "col-md-12 d-block");
				$('select[name="user"]').attr("required", "required");
				return false;
			}
			$('#select_user').attr("class", "col-md-2 d-none");
			$('select[name="user"]').removeAttr("required");
			$('select[name="user"]').select2("val", "0");
		});

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

		$('select[name="user"]').select2({
			placeholder: 'Pilih User (ketik username atau nama)',
			allowClear: true,
			width: "100%",
			minimumInputLength: 1,
			// theme: "classic",
			dropdownParent: $('#formMessage'),
			templateResult: formatUserSelect2,
			templateSelection: formatUserSelect2,
			ajax: {
				delay: 350,
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

		function reset(form) {
			form.reset();
			$("#formMessage").parsley().reset();
			$("select[name='user']").empty().trigger('change');
			$('#select_user').attr("class", "col-md-2 d-none");
		}

		
		$(document).on("click", "button#send", function(e) {
			e.preventDefault();
			let _ = $(this),
			compose = $(".compose"),
			form  = $("#formMessage");
			let $url = form.attr('action'),
			$data = form.serialize();

			form.parsley().validate({force: true});
			if(form.parsley().isValid()) {
				$.post($url, $data, function(result) {
					if(result.status === 200) {
						reset(form[0]);
						tableMessage.ajax.reload();
						compose.slideToggle();
					}
				}, 'json');
			}
			// console.log(form.serialize())
		});

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
				$.post(url, whr, (res) => tableMessage.ajax.reload(), 'json');
				return false;
			}
		})
	})

</script>