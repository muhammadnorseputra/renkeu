const MODAL_UNGGAH_DOKUMEN = $("#unggahDokumen");
const MODAL_VERIFY = $("#verifikasiDokumen");
const FILTER = $("#filterFormKinerjaNonPK");
const FORM_VERIFY = $("form#formVerifikasiDokumenNonPK");

// Reset form dan validasi saat modal ditutup
MODAL_UNGGAH_DOKUMEN.on("hidden.bs.modal", function () {
	MODAL_UNGGAH_DOKUMEN.find("form")[0].reset();
	MODAL_UNGGAH_DOKUMEN.find("form").parsley().reset();
});

MODAL_VERIFY.on("hidden.bs.modal", function () {
	MODAL_VERIFY.find("form")[0].reset();
	MODAL_VERIFY.find("form").parsley().reset();
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
		{ data: "periode", orderable: true },
		{ data: "jenis", orderable: false },
		{ data: "file", orderable: false },
		{ data: "user", orderable: false },
		{ data: "tahun", orderable: true },
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
	await tableKinerjaNonPK.ajax.reload();
});

async function ResetFilter() {
	FILTER[0].reset();
	let newUrl = `${_uri}/app/datatables/kinerja_non_pk`;
	await tableKinerjaNonPK.ajax.url(newUrl).load();
}

async function VerifikasiDokumen(id, nama_dokumen, periode, is_kunci, catatan) {
	if (!nama_dokumen || !periode || !id) return;
	MODAL_VERIFY.modal("show");
	MODAL_VERIFY.find("input[name='nama_dokumen']").val(nama_dokumen);
	MODAL_VERIFY.find("input[name='periode']").val(periode);
	MODAL_VERIFY.find("input[name='id']").val(id);
	MODAL_VERIFY.find("select[name='is_kunci']").val(is_kunci) // set pilihan status verifikasi
	MODAL_VERIFY.find("textarea[name='catatan']").val(catatan) // set catatan verifikator
}

FORM_VERIFY.on("submit", async function (e) {
	e.preventDefault();
	let formData = new FormData(this);
	// cek validasi dari parsley
	if (!FORM_VERIFY.parsley().isValid()) {
		return $.notify("Periksa kembali inputan Anda.", {
			timer: 800,
			delay: 100,
			type: "warning",
		});
	}

	try {
		const resp = await fetch(`${_uri}/app/dokuments/verifikasi_dokument_non_pk`, {
			method: "POST",
			body: formData,
		});
		const result = await resp.json();

		if(!resp.ok) {
			return $.notify(
				result.message || "Terjadi kesalahan saat memverifikasi dokumen.",
				{
					timer: 800,
					delay: 100,
					type: "danger",
				},
			);
		}

		if (!result.status) { 
			return $.notify(result.message, {
				timer: 800,
				delay: 100,
				type: "danger",
			});
		}

		MODAL_VERIFY.modal("hide");
		await tableKinerjaNonPK.ajax.reload();
		$.notify(result.message, {
			timer: 800,
			delay: 100,
			type: "success",
		});
		
	} catch (error) {
		alert("Terjadi kesalahan saat memverifikasi dokumen. Silakan coba lagi." + error.message);
	}
})