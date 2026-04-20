const MODAL_UNGGAH_DOKUMEN = $("#unggahDokumen");
const MODAL_TAMBAH_CATATAN = $("#tambahCatatan");
const FILTER = $("#filterFormPengelolaanResiko");

// Reset form dan validasi saat modal ditutup
MODAL_UNGGAH_DOKUMEN.on("hidden.bs.modal", function () {
	MODAL_UNGGAH_DOKUMEN.find("form")[0].reset();
	MODAL_UNGGAH_DOKUMEN.find("form").parsley().reset();
});

MODAL_TAMBAH_CATATAN.on("hidden.bs.modal", function () {
	MODAL_TAMBAH_CATATAN.find("form")[0].reset();
	MODAL_TAMBAH_CATATAN.find("form").parsley().reset();
});

// Buttons Add
$.fn.dataTable.ext.buttons.add = {
	text: '<i class="fa fa-upload"></i> Unggah Dokumen',
	action: function (e, dt, node, config) {
		MODAL_UNGGAH_DOKUMEN.modal("show");
	},
	className: "btn btn-primary",
};

var tablePengelolaanResiko = $("#table-pengelolaan-resiko").DataTable({
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
		url: `${_uri}/app/datatables/pengelolaan_resiko`,
		type: "POST",
		data: function (d) {
			d.filter_bidang = FILTER.find("select[name='filter_bidang']").val() || "";
			d.filter_periode =
				FILTER.find("select[name='filter_periode']").val() || ""; // kirim value select box ke server
		},
	},
	columns: [
		{ data: "no", orderable: false },
		{ data: "bidang", orderable: true },
		{ data: "periode", orderable: true },
		{ data: "file", orderable: false },
		{ data: "status", orderable: false },
		{ data: "tahun", orderable: true },
		{ data: "user", orderable: false },
		{ data: "catatan", orderable: false },
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
	await tablePengelolaanResiko.ajax.reload();
});

MODAL_TAMBAH_CATATAN.find("form").on("submit", async function (e) {
	e.preventDefault();
	const form = this;
	const formData = new FormData(form);

	// Validasi menggunakan Parsley
	if (!$(form).parsley().isValid()) {
		return;
	}

	try {
		const resp = await fetch(`${_uri}/app/dokuments/simpan_catatan_pengelolaan_resiko`, {
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
			MODAL_TAMBAH_CATATAN.modal("hide");
			if (typeof tablePengelolaanResiko !== "undefined") {
				tablePengelolaanResiko.ajax.reload(null, false);
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

async function Catatan(id) {
	if (!id) return;
	try {
		
		const formData = new FormData();
		formData.append("id", id);

		const resp = await fetch(
			`${_uri}/app/dokuments/get_catatan_pengelolaan_resiko`,
			{
				method: "POST",
				headers: {
					"X-Requested-With": "XMLHttpRequest",
				},
				body: formData, // id dikirim sebagai FormData
			},
		);
		const data = await resp.json();
		if (resp.ok && data.status) {
			MODAL_TAMBAH_CATATAN.find("textarea[name='catatan']").val(data.catatan);
			MODAL_TAMBAH_CATATAN.find("input[name='id']").val(id);
			MODAL_TAMBAH_CATATAN.modal("show");
		} else {
			alert("Gagal mengambil catatan: " + data.pesan);
		}
	} catch (err) {
		alert("Terjadi kesalahan koneksi : " + err.message);
	}
}

async function ResetFilter() {
	FILTER[0].reset();
	let newUrl = `${_uri}/app/datatables/pengelolaan_resiko`;
	await tablePengelolaanResiko.ajax.url(newUrl).load();
}

async function HapusDokumen(id) {
	if (!id) return;
	if (!confirm("Apakah Anda yakin ingin menghapus dokumen ini?")) {
		return;
	}
	try {
		const formData = new FormData();
		formData.append("id", id);

		const resp = await fetch(
			`${_uri}/app/dokuments/delete_pengelolaan_resiko`,
			{
				method: "POST",
				headers: {
					"X-Requested-With": "XMLHttpRequest",
				},
				body: formData, // id dikirim sebagai FormData
			},
		);
		const data = await resp.json();
		if (resp.ok && data.status) {
			$.notify(data.pesan, {
				timer: 800,
				delay: 100,
				type: "success",
			});
			if (typeof tablePengelolaanResiko !== "undefined") {
				tablePengelolaanResiko.ajax.reload(null, false);
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

async function VerifikasiDokumen(id) {
	if (!id) return;
	try {
		const formData = new FormData();
		formData.append("id", id);

		const resp = await fetch(
			`${_uri}/app/dokuments/verifikasi_pengelolaan_resiko`,
			{
				method: "POST",
				headers: {
					"X-Requested-With": "XMLHttpRequest",
				},
				body: formData, // id dikirim sebagai FormData
			},
		);
		const data = await resp.json();
		if (resp.ok && data.status) {
			$.notify(data.pesan, {
				timer: 800,
				delay: 100,
				type: "success",
			});
			if (typeof tablePengelolaanResiko !== "undefined") {
				tablePengelolaanResiko.ajax.reload(null, false);
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