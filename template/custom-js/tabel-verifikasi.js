var tableVerifikasiSpj = $("#table-spj").DataTable({
	stateSave: true, // ini menyimpan filter, search, pagination
	processing: true,
	serverSide: true,
	paging: true,
	ordering: true,
	info: true,
	searching: true,
	deferRender: true,
	// "pagingType": "full_numbers",
	responsive: false,
	orderCellsTop: true,
	datatype: "json",
	// "scrollY": "800px",
	scrollCollapse: false,
	lengthMenu: [
		[10, 25, 50, -1],
		[10, 25, 50, "All"],
	],
	order: [], //default order
	ajax: {
		url: `${_uri}/app/spj/verifikasi`,
		type: "POST",
	},
	columns: [
		{ data: "no", orderable: false },
		{ data: "kode", orderable: true },
		{ data: "uraian", orderable: false },
		{ data: "periode", orderable: true },
		{ data: "bidang", className: "align-middle", orderable: false },
		{ data: "userinfo", orderable: true },
		{ data: "status", orderable: false },
		{ data: "jumlah", orderable: false },
		{
			data: "action",
			width: "10%",
			orderable: false,
			searchable: false,
		},
	],
	initComplete: function (settings, json) {
		var indexColumn = 0;
		var api = this.api();
		var table = $(api.table().container()); // container spesifik tabel

		// search per kolom + restore dari state
		api.columns([1, 2]).every(function () {
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
		addStatusFilterSPJ(this, 4); // 7 = index kolom status
	},
	language: {
		paginate: {
			previous: `<i class="fa fa-long-arrow-left"></i>`,
			next: `<i class="fa fa-long-arrow-right"></i>`,
		},
	},
});

function addStatusFilterSPJ(table, columnIndex) {
	var api = table.api();

	// buat element select
	var filterSelect = $(`
        <label style="margin-left:10px;">
            Hanya Tampilkan:
            <select id="statusFilterSPJ" class="form-control form-control-sm" style="display:inline-block; width:auto; margin-left:5px;">
                <option value="">Semua</option>
            </select>
        </label>
    `);

	// sisipkan ke samping search box
	$("#table-spj_wrapper .dataTables_filter").append(filterSelect);

	// ambil data unik dari kolom (ambil text saja, bukan HTML)
	api
		.column(columnIndex)
		.data()
		.unique()
		.sort()
		.each(function (d) {
			if (d) {
				// ambil plain text dari HTML badge
				var text = $("<div>").html(d).text().trim();
				if ($("#statusFilterSPJ option[value='" + text + "']").length === 0) {
					$("#statusFilterSPJ").append(
						`<option value="${text}">${text}</option>`
					);
				}
			}
		});

	// event listener
	$("#statusFilterSPJ").on("change", function () {
		var val = $(this).val();
		if (val) {
			// exact match (regex ^...$)
			api
				.column(columnIndex)
				.search(val, true, false)
				.draw();
		} else {
			api.column(columnIndex).search("").draw();
		}
	});
}

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