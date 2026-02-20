const MODAL = $("#unggahDokumen");
const MODAL_CATATAN = $("#tambahCatatan");
const FILTER_FORM_PAJAK = $("#filterFormPajak");

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

var tableRekapPajak = $("#table-rekap-pajak").DataTable({
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
		url: `${_uri}/app/spj/get_rekap_pajak`,
		type: "POST",
		data: function (d) {
			d.filter_bidang = FILTER_FORM_PAJAK.find("select[name='filter_bidang']").val() || ""; // kirim value select box ke server
		}
	},
	columns: [
		{ data: "no", orderable: false },
		{ data: "bidang", orderable: true },
		{ data: "periode", orderable: true },
		{ data: "jenis_dokumen", orderable: true },
		{ data: "tahun", orderable: true },
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

FILTER_FORM_PAJAK.on("submit", async function (e) {
	e.preventDefault();
	await tableRekapPajak.ajax.reload();
});

async function ResetFilter() {
	FILTER_FORM_PAJAK[0].reset();
	let newUrl = `${_uri}/app/spj/get_rekap_pajak`;
	await tableRekapPajak.ajax.url(newUrl).load();
}

async function VerifikasiDokumen(id) {
	if (!id) return;
	try {
		const formData = new FormData();
		formData.append("id", id);

		const resp = await fetch(`${_uri}/app/spj/verifikasi_dokumen_pajak`, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: formData, // id dikirim sebagai FormData
		});
		const data = await resp.json();
		if (resp.ok && data.status) {
			$.notify(data.pesan, {
				timer: 800,
				delay: 100,
				type: "success",
			});
			if (typeof tableRekapPajak !== "undefined") {
				tableRekapPajak.ajax.reload(null, false);
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

async function CatatanDokumen(id, catatan) {
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
		const resp = await fetch(`${_uri}/app/spj/tambah_catatan_pajak`, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: formData,
		});
		const data = await resp.json();
		if (resp.ok && data.status) {
			$.notify(data.pesan, {
				timer: 800,
				delay: 100,
				type: "success",
			});
			MODAL_CATATAN.modal("hide");
			if (typeof tableRekapPajak !== "undefined") {
				tableRekapPajak.ajax.reload(null, false);
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