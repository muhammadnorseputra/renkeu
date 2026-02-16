const FILTER_FORM = $("#filterForm");

$.fn.dataTable.ext.buttons.reload = {
	text: "<i class='fa fa-repeat'></i> Reload Data",
	action: function (e, dt, node, config) {
		dt.ajax.reload();
	},
};

var tableVerifikasiSpjSelesai = $("#table-spj-selesai").DataTable({
	stateSave: true, // ini menyimpan filter, search, pagination
	processing: true,
	serverSide: true,
	paging: true,
	ordering: true,
	info: true,
	searching: false,
	select: true,
	orderCellsTop: true,
	deferRender: true,
	responsive: true,
	datatype: "json",
	// scrollY: "",
	order: [[7, "desc"]], //default order
	scrollCollapse: false,
	lengthMenu: [
		[10, 25, 50, -1],
		[10, 25, 50, "All"],
	],
	ajax: {
		url: `${_uri}/app/spj/verifikasi_selesai`,
		type: "POST",
	},
	columns: [
		{ data: "no", orderable: false },
		{ data: "no_buku", orderable: true },
		{ data: "kode_uraian", orderable: true },
		{ data: "nama_uraian", orderable: false },
		{ data: "bidang", className: "align-middle", orderable: false },
		{ data: "periode", orderable: true },
		{ data: "userinfo", orderable: true },
		{ data: "tgl_approve", orderable: true },
		{ data: "status", orderable: false },
		{ data: "status_bendahara", orderable: false },
		{ data: "jumlah", orderable: false },
		{ data: "action", width: "10%", orderable: false, searchable: false },
	],
	initComplete: function (settings, json) {
		var indexColumn = 0;
		var api = this.api();
		var table = $(api.table().container()); // container spesifik tabel

		// search per kolom + restore dari state
		api.columns([1, 2, 3]).every(function () {
			var column = this;
			var input = document.createElement("input");

			$(input)
				.attr("placeholder", "Search")
				.attr("type", "search")
				.addClass("form-control form-control-sm")
				.appendTo(
					$(".filterhead:eq(" + indexColumn + ")", table).empty(), // hanya cari di tabel ini
				)
				.on("change", function () {
					column.search($(this).val(), false, false, true).draw();
				});

			// restore nilai dari stateSave
			var state = api.state.loaded();
			if (state && state.columns[indexColumn + 1].search.search) {
				$(input).val(state.columns[indexColumn + 1].search.search);
			}

			indexColumn++;
		});
	},
	language: {
		paginate: {
			previous: `<i class="fa fa-long-arrow-left"></i>`,
			next: `<i class="fa fa-long-arrow-right"></i>`,
		},
	},
	layout: {
		topStart: ["pageLength"],
		topEnd: [
			{
				buttons: ["colvis", "reload"],
			},
		],
		bottomStart: ["info"],
		bottomEnd: ["paging"],
	},
});

function Rollback(token) {
	if (confirm("Yakin ingin rollback data SPJ ini?")) {
		$.ajax({
			url: `${_uri}/app/spj/rollback`,
			type: "POST",
			data: { token: token },
			dataType: "json",
			success: function (data) {
				if (data.code == 200) {
					alert(data.pesan);
					tableVerifikasiSpjSelesai.ajax.reload();
				} else {
					alert(data.pesan);
				}
			},
			error: function (xhr, status, error) {
				alert("Terjadi kesalahan: " + error);
			},
		});
	}
	return false;
}

FILTER_FORM.on("submit", function (e) {
	e.preventDefault();
	let formData = $(this).serialize();
	let newUrl = `${_uri}/app/spj/verifikasi_selesai?${formData}`;
	tableVerifikasiSpjSelesai.ajax.url(newUrl).load();
});

async function ResetFilter() {
	FILTER_FORM[0].reset();
	let newUrl = `${_uri}/app/spj/verifikasi_selesai`;
	tableVerifikasiSpjSelesai.ajax.url(newUrl).load();
}