<div class="row">
    <div class="col-md-12">
    <div class="x_panel">
        <div class="x_title">
        <h2>Tabel Messages</h2>
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
				<textarea name="message" id="message" class="resizable_textarea form-control border-0" cols="30" rows="5" placeholder="Ketik pesan anda disini ..."></textarea>
			</div>
			<div class="divider-dashed"></div>
			<div class="form-group row">
				<div class="col-md-6">
					<label class="control-label">Pilih Type <span class="text-danger">*</span></label>
					<select name="mode" class="form-control">
						<option value="">Choose option</option>
						<option value="SUCCESS">Success</option>
						<option value="WARNING">Warning</option>
						<option value="INFO">Info</option>
						<option value="DANGER">Danger</option>
					</select>
				</div>
				<div class="col-md-6">
					<label class="control-label">Pilih Mode <span class="text-danger">*</span></label>
					<select name="mode" class="form-control">
						<option value="">Choose option</option>
						<option value="GLOBAL">Global</option>
						<option value="PRIVATE_ALL">Private All</option>
						<option value="PRIVATE">Private</option>
					</select>
				</div>
			</div>
			<div class="form-group my-2 row">
			<div class="col-md-6">
				<label for="aktif">Status Aktif</label> <div class="clearfix"></div>
				<input type="checkbox" name="aktif" value="Y" class="js-switch" id="aktif" checked />
			</div>
			</div>
		<?= form_close() ?>
	</div>

	<div class="compose-footer py-3 bg-light">
	<button id="send" class="btn btn-success rounded-0" type="button"><i class="fa fa-send"></i> Simpan</button>
	</div>
</div>
<!-- /compose -->

<script>
	$(function() {

		var tableUsers = $("#table-messages").DataTable({
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
					"width": '5%'
				},
				{
					"targets": [4],
					"orderable": false,
					"className": "text-center",
					"width": '5%'
				},
				{
					"targets": [1],
					"orderable": false,
					"className": "text-left",
					"width": '8%'
				},
				{
					"targets": [2,3],
					"orderable": false,
					"className": "text-left"
				},
			],
			"dom": 'Bfrtip<"clear">l',
			"buttons": [
				{
					text: '<i class="fa fa-send mr-2"></i>Send Message',
					className: 'btn btn-success rounded-0 pull-left btn-compose'
				},
			],
			"language": {
				"lengthMenu": "_MENU_ Data per halaman",
				"zeroRecords": "Belum Ada Messages",
				"info": "Showing page _PAGE_ of _PAGES_",
				"infoEmpty": "Belum Ada Messages",
				"infoFiltered": "(filtered from _MAX_ total records)",
				"search": "Cari Pesan :",
				"paginate": {
						"previous": `<i class="fa fa-long-arrow-left"></i>`,
						"next": `<i class="fa fa-long-arrow-right"></i>`
					},
				"emptyTable": "No matching records found, please filter this data"
			},
		});
	})

</script>