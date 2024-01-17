<div class="row">
    <div class="col-md-12">
    <div class="x_panel">
        <div class="x_title">
        <h2>Tabel Users</h2>
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
					<table id="table-users" class="table table-condensed dt-responsive nowrap" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Photo</th>
								<th>Nama</th>
								<th>Role</th>
								<th>Is Block</th>
								<th>Is Restricted</th>
								<th>Aksi</th>
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

<script>
	$(function() {
		var tableUsers = $("#table-users").DataTable({
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
				"url": `${_uri}/app/users/ajax_users`,
				"type": "POST"
			},
			"columnDefs": [{
				"targets": [0,1,2,3,4],
				"orderable": false,
				"className": "text-left"
			},
			{
				"targets": [5],
				"orderable": false,
				"className": "text-center"
			}],
			"dom": 'Bfrtlip',
			"buttons": [
				{
					text: '<i class="fa fa-plus mr-2"></i>Tambah',
					className: 'btn btn-success rounded-0 pull-left',
					action: function ( e, dt, node, config ) {
						window.location.href='<?= base_url('app/users/new') ?>'
					}
				},
				{
					text: '<i class="fa fa-repeat mr-2"></i>Reload',
					className: 'btn btn-secondary rounded-0 pull-left',
					action: function ( e, dt, node, config ) {
						dt.ajax.reload();
					}
				},
			],
			"language": {
				"lengthMenu": "_MENU_ Data per halaman",
				"zeroRecords": "Belum Ada Users",
				"info": "Showing page _PAGE_ of _PAGES_",
				"infoEmpty": "Belum Ada Users",
				"infoFiltered": "(filtered from _MAX_ total records)",
				"search": "Cari Users",
				"paginate": {
						"previous": `<i class="fa fa-long-arrow-left"></i>`,
						"next": `<i class="fa fa-long-arrow-right"></i>`
					},
				"emptyTable": "No matching records found, please filter this data"
			},
		});
		$(document).on("click", "a#btn-restricted", function(event){
			event.preventDefault();
			var $this = this;
			var $uid = $this.dataset.uid;
			var $url = $this.dataset.href;
			var $val = $this.dataset.val;
			var $data = {status: $val, uid: $uid};
			$.post($url,$data,is_status,'json');
			// console.log($val);
		});
	
		$(document).on("click", "a#btn-block", function(event){
			event.preventDefault();
			var $this = this;
			var $uid = $this.dataset.uid;
			var $url = $this.dataset.href;
			var $val = $this.dataset.val;
			var $data = {status: $val, uid: $uid};
			$.post($url,$data,is_status,'json');
			// console.log($val);
		});
	
		function is_status(res)
		{
			NProgress.start();
			if(res.valid === true)
			{
				tableUsers.ajax.reload();
				NProgress.done();
			}
		}
	
		$(document).on("click", "a#resspwd", function(event){
			event.preventDefault();
			var $this = this;
			var $uid = $this.dataset.uid;
			var $path = $this.dataset.path;
			var $url = `${_uri}/app/users/goToPage`;
			var $data = {uid: $uid, path: $path};
			$.post($url,$data,response,'json');
		});
	
		function response(res) {
			window.location.href = res.redirectTo;
			console.log(res);
		}
	})

</script>