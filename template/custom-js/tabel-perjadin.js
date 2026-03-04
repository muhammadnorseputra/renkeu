const MODAL = $("#unggahDokumen");
const MODAL_CATATAN = $("#tambahCatatan");
const FILTER_FORM = $("#filterForm");

MODAL.on("hidden.bs.modal", function () {
	MODAL.find("form")[0].reset();
	MODAL.find("form").parsley().reset();
});

MODAL_CATATAN.on("hidden.bs.modal", function () {
	MODAL_CATATAN.find("form")[0].reset();
	MODAL_CATATAN.find("form").parsley().reset();
});


$.fn.dataTable.ext.buttons.add = {
	text: '<i class="fa fa-upload"></i> Unggah Dokumen',
	action: function (e, dt, node, config) {
		MODAL.modal("show");
	},
	className: "btn btn-primary",
};

var tableRekapPerjadin = $("#table-rekap-perjadin").DataTable({
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
		url: `${_uri}/app/spj/get_rekap_perjadin`,
		type: "POST",
	},
	columns: [
		{ data: "no", orderable: false },
		{ data: "bidang", orderable: true },
		{ data: "bulan", orderable: true },
		{ data: "tahun", orderable: true },
		{ data: "user", orderable: false },
		{
			data: "catatan",
			orderable: false,
			className: "text-start text-danger",
		},
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

async function VerifikasiDokumen(id) { 
	if (!id) return;
	try {
		const formData = new FormData();
		formData.append('id', id);

		const resp = await fetch(`${_uri}/app/spj/verifikasi_dokumen_perjadin`, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: formData, // id dikirim sebagai FormData
		});
		const data = await resp.json();
		if (resp.ok && (data.status)) {
			$.notify(data.pesan, {
				timer: 800,
				delay: 100,
				type: "success",
			});
			if (typeof tableRekapPerjadin !== "undefined") {
				tableRekapPerjadin.ajax.reload(null, false);
			}
		} else {
			$.notify(data.pesan, {
				timer: 800,
				delay: 100,
				type: "danger",
			});
		}
	} catch (err) {
		alert("Terjadi kesalahan koneksi : " + err.message);
	}
}

async function CatatanDokumen(id, catatan)
{
	if (!id) return;
	MODAL_CATATAN.modal("show");
	MODAL_CATATAN.find("input[name='id']").val(id);
	MODAL_CATATAN.find("textarea[name='catatan']").val(catatan);
}

MODAL_CATATAN.find("form").on("submit", async function (e) {
	e.preventDefault();
	const form = this;
	const formData = new FormData(form);

	// Validasi menggunakan Parsley
	if (!$(form).parsley().isValid()) {
		return;
	}

	try {
		const resp = await fetch(`${_uri}/app/spj/tambah_catatan_perjadin`, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: formData,
		});
		const data = await resp.json();
		if (resp.ok && (data.status)) {
			$.notify(data.pesan, {
				timer: 800,
				delay: 100,
				type: "success",
			});
			MODAL_CATATAN.modal("hide");
			if (typeof tableRekapPerjadin !== "undefined") {
				tableRekapPerjadin.ajax.reload(null, false);
			}
		} else {
			$.notify(data.pesan, {
				timer: 800,
				delay: 100,
				type: "danger",
			});
		}
	} catch (err) {
		alert("Terjadi kesalahan koneksi : " + err.message);
	}
});

FILTER_FORM.on("submit", function (e) {
	e.preventDefault();
	let formData = $(this).serialize();
	let newUrl = `${_uri}/app/spj/get_rekap_perjadin?${formData}`;
	tableRekapPerjadin.ajax.url(newUrl).load();
});

async function ResetFilter() {
	FILTER_FORM[0].reset();
	let newUrl = `${_uri}/app/spj/get_rekap_perjadin`;
	tableRekapPerjadin.ajax.url(newUrl).load();
}