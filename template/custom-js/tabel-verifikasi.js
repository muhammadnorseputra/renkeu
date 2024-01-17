var tableVerifikasiSpj = $("#table-spj").DataTable({
	processing: true,
	serverSide: true,
	paging: true,
	ordering: true,
	info: true,
	searching: true,
	deferRender: true,
	// "pagingType": "full_numbers",
	responsive: true,
	datatype: "json",
	// "scrollY": "800px",
	scrollCollapse: true,
	lengthMenu: [
		[10, 25, 50, -1],
		[10, 25, 50, "All"],
	],
	order: [],
	ajax: {
		url: `${_uri}/app/spj/verifikasi`,
		type: "POST",
	},
	columnDefs: [
		{
			targets: [0,1,2,3,4,5],
			orderable: false,
			className: "text-left",
		},
	],
	language: {
		paginate: {
			previous: `<i class="fa fa-long-arrow-left"></i>`,
			next: `<i class="fa fa-long-arrow-right"></i>`,
		},
	},
});

function Selesai(token) {
	let msg = 'Apakah anda yakin akan menyelesaikan usulan tersebut ?';
	if(confirm(msg)) {
		$.post(`${_uri}/app/spj/verifikasi_proses_selesai`, {token: token}, function(res) {
			if(res.code === 200) {
				tableVerifikasiSpj.ajax.reload();
			}
		}, 'json')
		return false;
	}
}