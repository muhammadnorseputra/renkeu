var getToken = urlParams.get("token");
const MODAL_RELASI_PUBLIK = $("#tambah-data-users");

$.fn.dataTable.ext.buttons.add = {
	text: '<i class="fa fa-users"></i> Tambah Data',
	action: function (e, dt, node, config) {
		MODAL_RELASI_PUBLIK.modal("show");
	},
	className: "btn btn-primary",
};

var tablePenerimaManfaat = $("#table-penerima-manfaat").DataTable({
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
		url: `${_uri}/app/spj/get_penerima_manfaat/${getToken}`,
		type: "POST",
	},
	layout: {
		topStart: [
			{
				buttons: ["add"],
			},
		],
		bottomStart: ["info"],
		bottomEnd: ["paging", "pageLength"],
	},
	columns: [
		{ data: "no", orderable: false },
		{ data: "organisasi", orderable: true },
		{ data: "perorangan", orderable: true },
		{
			data: "action",
			orderable: false,
			searchable: false,
		},
	],
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

function HapusPenerimaManfaat(id) {
	if (confirm("Yakin akan menghapus data tersebut?")) {
		$.ajax({
			url: `${_uri}/app/spj/hapus_penerima_manfaat`,
			type: "POST",
			data: { id: id },
			dataType: "json",
			success: function (data) {
				if (data.status) {
					$.blockUI({
						message: data.pesan,
						fadeIn: 700,
						fadeOut: 700,
						timeout: 2000,
						showOverlay: false,
						center: true,
						css: {
							border: "none",
							padding: "12px",
							backgroundColor: "#000",
							"-webkit-border-radius": "10px",
							"-moz-border-radius": "10px",
							opacity: 0.6,
							color: "#fff",
						},
					});
					tablePenerimaManfaat.ajax.reload();
					return false;
				}
				alert(data.pesan);
			},
			error: function (xhr, status, error) {
				alert("Terjadi kesalahan: " + error);
			},
		});
	}
	return;
}
