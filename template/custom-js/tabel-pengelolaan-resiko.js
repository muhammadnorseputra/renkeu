const MODAL_UNGGAH_DOKUMEN = $("#unggahDokumen");
const MODAL_TAMBAH_CATATAN = $("#tambahCatatan");
const FILTER = $("#filterFormPengelolaanResiko");

// Reset form dan validasi saat modal ditutup
MODAL_UNGGAH_DOKUMEN.on("hidden.bs.modal", function () {
	MODAL_UNGGAH_DOKUMEN.find("form")[0].reset();
	MODAL_UNGGAH_DOKUMEN.find("form").parsley().reset();
	resetUploadUI();
});

MODAL_TAMBAH_CATATAN.on("hidden.bs.modal", function () {
	MODAL_TAMBAH_CATATAN.find("form")[0].reset();
	MODAL_TAMBAH_CATATAN.find("form").parsley().reset();
});

/* ── Upload dropzone + progress ─────────────────────────────── */
const $dropzone  = $("#uploadDropzone");
const $fileInput = $("#file");
const $preview   = $("#uploadPreview");
const $progress  = $("#uploadProgress");
const $btnBrowse = $("#btnBrowseFile");
const $btnRemove = $("#btnRemoveFile");
const $btnUpload = $("#btnUpload");

$btnBrowse.on("click", () => $fileInput.trigger("click"));
$dropzone.on("click", function(e){ if(e.target===this||$(e.target).closest('.fa-cloud-upload').length) $fileInput.trigger("click"); });

$dropzone.on("dragover", function(e){ e.preventDefault(); $(this).addClass("drag-over"); });
$dropzone.on("dragleave drop", function(){ $(this).removeClass("drag-over"); });
$dropzone.on("drop", function(e){
	e.preventDefault();
	if(e.originalEvent.dataTransfer.files.length){
		$fileInput[0].files = e.originalEvent.dataTransfer.files;
		showFilePreview(e.originalEvent.dataTransfer.files[0]);
	}
});

$fileInput.on("change", function(){ if(this.files.length) showFilePreview(this.files[0]); });

function showFilePreview(file){
	var ext = file.name.split('.').pop().toLowerCase();
	var icon = ext==='pdf'?'fa-file-pdf-o text-danger':'fa-file-excel-o text-success';
	$dropzone.addClass("d-none");
	$preview.removeClass("d-none").find(".upload-preview-icon i").attr("class","fa "+icon);
	$("#previewFileName").text(file.name);
	$("#previewFileMeta").text(formatBytes(file.size));
	$btnUpload.prop("disabled", false);
}
function resetUploadUI(){
	$dropzone.removeClass("d-none");
	$preview.addClass("d-none");
	$progress.addClass("d-none");
	$("#progressBar").css("width","0%");
	$("#progressPercent").text("0%");
	$("#progressStatus").text("Mengunggah...");
	$btnUpload.prop("disabled", true).html('<i class="fa fa-upload mr-1"></i> Upload');
}
$btnRemove.on("click", function(){
	$fileInput.val("");
	resetUploadUI();
});

function formatBytes(bytes){
	if(bytes===0) return '0 B';
	var k=1024, sizes=['B','KB','MB','GB'];
	var i=Math.floor(Math.log(bytes)/Math.log(k));
	return parseFloat((bytes/Math.pow(k,i)).toFixed(2))+' '+sizes[i];
}

$btnUpload.on("click", function(){
	if(!$fileInput[0].files.length) return;
	var form = MODAL_UNGGAH_DOKUMEN.find("form")[0];
	if(!$(form).parsley().isValid()) { $(form).parsley().validate(); return; }
	// explicit radio check (parsley sometimes misses radio groups)
	if(!$('input[name="jenis_dokumen"]:checked').length){
		$.notify("Pilih jenis dokumen", {type:"danger"});
		return;
	}
	var formData = new FormData(form);
	var xhr = new XMLHttpRequest();

	$progress.removeClass("d-none");
	$btnUpload.prop("disabled", true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Mengunggah...');

	xhr.upload.onprogress = function(e){
		if(e.lengthComputable){
			var pct = Math.round((e.loaded/e.total)*100);
			$("#progressBar").css("width", pct+"%");
			$("#progressPercent").text(pct+"%");
		}
	};
	xhr.onload = function(){
		if(xhr.status>=200 && xhr.status<300){
			try {
				var resp = JSON.parse(xhr.responseText);
				if(resp.status){
					$.notify(resp.pesan, {type:"success", timer:1500});
					MODAL_UNGGAH_DOKUMEN.modal("hide");
					tablePengelolaanResiko.ajax.reload(null, false);
				} else {
					$.notify(resp.pesan, {type:"danger", timer:3000});
					resetUploadUI();
				}
			} catch(ex){
				$.notify("Gagal memproses respons server", {type:"danger"});
				resetUploadUI();
			}
		} else {
			$.notify("Upload gagal (HTTP "+xhr.status+")", {type:"danger"});
			resetUploadUI();
		}
	};
	xhr.onerror = function(){
		$.notify("Terjadi kesalahan koneksi", {type:"danger"});
		resetUploadUI();
	};
	xhr.open("POST", MODAL_UNGGAH_DOKUMEN.find("form").attr("action"));
	xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	xhr.send(formData);
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