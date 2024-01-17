var tableVerifikasiSpjSelesai = $("#table-spj-selesai").DataTable({
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
		url: `${_uri}/app/spj/verifikasi_selesai`,
		type: "POST",
	},
	columnDefs: [
		{
			targets: [0,1,2,3,4,5,6],
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
