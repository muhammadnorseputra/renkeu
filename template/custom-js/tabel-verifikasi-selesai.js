var tableVerifikasiSpjSelesai = $("#table-spj-selesai").DataTable({
	stateSave: true, // ini menyimpan filter, search, pagination
	processing: true,
	serverSide: true,
	paging: true,
	ordering: true,
	info: true,
	searching: true,
	select: {
		style: "single",
	},
	orderCellsTop: true,
	deferRender: true,
	pagingType: "full_numbers",
	responsive: false,
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
					$(".filterhead:eq(" + indexColumn + ")", table).empty() // hanya cari di tabel ini
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
		// panggil function buat filter selectbox
		addStatusFilterSpjSelesai(this, 4); // 7 = index kolom status
	},
	language: {
		paginate: {
			previous: `<i class="fa fa-long-arrow-left"></i>`,
			next: `<i class="fa fa-long-arrow-right"></i>`,
		},
	},
});

function addStatusFilterSpjSelesai(table, columnIndex) {
	var api = table.api();

	// buat element select
	var filterSelect = $(`
		<label style="margin-left:10px;">
			Hanya Tampilkan:
			<select id="statusFilterSpjSelesai" class="form-control form-control-sm" style="display:inline-block; width:auto; margin-left:5px;">
				<option value="">Semua</option>
			</select>
		</label>
	`);

	// sisipkan ke samping search box
	$("#table-spj-selesai_wrapper .dataTables_filter").append(filterSelect);

	// ambil data unik dari kolom
	api
		.column(columnIndex)
		.data()
		.unique()
		.sort()
		.each(function (d) {
			if (d) {
				$("#statusFilterSpjSelesai").append(`<option value="${d}">${d}</option>`);
			}
		});

	// event listener
	$("#statusFilterSpjSelesai").on("change", function () {
		api.column(columnIndex).search(this.value).draw();
	});
}

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