const MODAL_UNGGAH_DOKUMEN = $("#unggahDokumen");
const FILTER = $("#filterFormKinerjaNonPK");

// Reset form dan validasi saat modal ditutup
MODAL_UNGGAH_DOKUMEN.on("hidden.bs.modal", function () {
	MODAL_UNGGAH_DOKUMEN.find("form")[0].reset();
	MODAL_UNGGAH_DOKUMEN.find("form").parsley().reset();
});

// Buttons Add
$.fn.dataTable.ext.buttons.add = {
	text: '<i class="fa fa-upload"></i> Unggah Dokumen',
	action: function (e, dt, node, config) {
		MODAL_UNGGAH_DOKUMEN.modal("show");
	},
	className: "btn btn-primary",
};

var tableKinerjaNonPK = $("#table-kinerja-non-pk").DataTable({
	stateSave: false,
	processing: true,
	serverSide: true,
	paging: true,
	ordering: true,
	info: true,
	searching: true,
	orderCellsTop: false,
	deferRender: true,
	pagingType: "full_numbers",
	responsive: true,
	datatype: "json",
	order: [],
	scrollCollapse: false,
	ajax: {
		url: `${_uri}/app/datatables/kinerja_non_pk`,
		type: "POST",
		data: function (d) {
			d.filter_bidang = FILTER.find("select[name='filter_bidang']").val() || "";
		},
	},
	columns: [
		{ data: "no", orderable: false },
		{ data: "bidang", orderable: true },
		{ data: "jenis", orderable: false },
		{ data: "file", orderable: false },
		{ data: "user", orderable: false },
		{ data: "tahun", orderable: true },
		{
			data: "action",
			orderable: false,
			searchable: false,
		},
	],
	layout: {
		topStart: [
			{
				buttons: ["add"],
			},
			{
				buttons: ["colvis"],
			},
		],
		bottomStart: ["info"],
		bottomEnd: ["paging", "pageLength"],
	},
	language: {
		paginate: {
			previous: `<i class="fa fa-long-arrow-left"></i>`,
			next: `<i class="fa fa-long-arrow-right"></i>`,
		},
		searching: "Cari:",
		lengthMenu: "Tampilkan _MENU_ entri",
		info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
		infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
		infoFiltered: "(disaring dari _MAX_ total entri)",
	},
});

FILTER.on("submit", async function (e) {
	e.preventDefault();
	await tableKinerjaNonPK.ajax.reload();
});

async function ResetFilter() {
	FILTER[0].reset();
	let newUrl = `${_uri}/app/datatables/kinerja_non_pk`;
	await tableKinerjaNonPK.ajax.url(newUrl).load();
}