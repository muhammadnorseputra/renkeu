const FILTER_FORM_PAYMENT = $("#filterFormPayment");

var modalPayment = $("#modalPayment");
var tabelPayment = $("#table-spj-payment").DataTable({
	stateSave: true,
	processing: true,
	serverSide: true,
	paging: true,
	ordering: true,
	info: true,
	searching: true,
	orderCellsTop: true,
	deferRender: true,
	pagingType: "full_numbers",
	responsive: true,
	datatype: "json",
	order: [],
	scrollCollapse: false,
	lengthMenu: [
		[10, 25, 50, -1],
		[10, 25, 50, "All"],
	],
	ajax: {
		url: `${_uri}/app/payment/ajaxTable`,
		type: "POST",
		data: function (d) {
			d.filter_status =
				FILTER_FORM_PAYMENT.find("select[name='filter_status']").val() || "";
			d.filter_bidang =
				FILTER_FORM_PAYMENT.find("select[name='filter_bidang']").val() || "";
			d.filter_tanggal =
				FILTER_FORM_PAYMENT.find("input[name='filter_tanggal']").val() || "";
		},
	},
	columns: [
		{ data: "no", orderable: false },
		{ data: "no_verifikasi", orderable: true },
		{ data: "tgl_verifikasi", orderable: true },
		{ data: "no_bku", orderable: true },
		{ data: "tgl_bku", orderable: true },
		{ data: "kode_uraian", orderable: true },
		{ data: "nama_uraian", orderable: false },
		{ data: "periode", orderable: true },
		{ data: "tgl_approve", orderable: true },
		{ data: "tgl_approve_bendahara", orderable: false },
		{ data: "status", orderable: true },
		{ data: "jumlah", orderable: false },
		{ data: "action", width: "10%", orderable: false, searchable: false },
	],
	language: {
		paginate: {
			previous: `<i class="fa fa-long-arrow-left"></i>`,
			next: `<i class="fa fa-long-arrow-right"></i>`,
		},
	},
});

FILTER_FORM_PAYMENT.on("submit", function (e) {
	e.preventDefault();
	tabelPayment.ajax.reload();
});

async function ResetFilter() {
	FILTER_FORM_PAYMENT[0].reset();
	let newUrl = `${_uri}/app/payment/ajaxTable`;
	tabelPayment.ajax.url(newUrl).load();
}

async function ProsesApprover(btn) {
	// disabld button submit & select
	$("form#formApprover").find("button[type='submit']").prop("disabled", true);
	$("form#formApprover")
		.find("select[name='verifikasi_status']")
		.prop("disabled", true);

	// Tampilkan modal
	modalPayment.modal("show");

	// Temukan elemen body modal
	let $body = modalPayment.find(".modal-body > .load-data");
	let $token = modalPayment.find('input[name="token"]');

	// Tampilkan placeholder loading sementara
	$body.html(`
        <div class="text-center my-4">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    `);

	try {
		// Simulasi proses async, misalnya ambil data tambahan dari server
		//await new Promise((resolve) => setTimeout(resolve, 800)); // contoh delay

		// Ambil data JSON dari atribut tombol
		const row = await JSON.parse(btn.getAttribute("data-row"));

		// Setelah data siap, tampilkan isi tabel
		const content = TemplateTablePayment(row);
		$token.val(row.token);
		$body.html(content);
	} catch (error) {
		console.error(error);
		$body.html(`
            <div class="alert alert-danger" role="alert">
                Gagal memuat data pembayaran. Silakan coba lagi.
            </div>
        `);
	} finally {
		// disabld button submit
		$("form#formApprover")
			.find("button[type='submit']")
			.prop("disabled", false);
		$("form#formApprover")
			.find("select[name='verifikasi_status']")
			.prop("disabled", false);
	}
}

async function BatalProsesApprover(btn) {
	// ✅ Tambahkan konfirmasi sebelum submit
	const isConfirmed = confirm("Apakah Anda yakin ingin membatalkan proses ?");
	if (!isConfirmed) return;

	// Ambil data JSON dari atribut tombol
	const row = await JSON.parse(btn.getAttribute("data-row"));

	const url = `${_uri}/app/payment/batal`;
	const req = await fetch(url, {
		method: "POST",
		headers: { "Content-Type": "application/x-www-form-urlencoded" },
		body: new URLSearchParams({ id: row.token }),
	});

	const res = await req.json();

	if (res.status) {
		return $.notify(res.message, {
			timer: 800,
			delay: 800,
			type: "success",
			onShow: () => {
				tabelPayment.ajax.reload();
			},
		});
	}

	return $.notify(res.message, { type: "danger" });
}

modalPayment.on("hidden.bs.modal", function (e) {
	$("form#formApprover")
		.find("#verifikasi_catatan")
		.removeClass("d-block")
		.addClass("d-none");
	$("form#formApprover")[0].reset();
	$("form#formApprover").find('input[name="token"]').val("");
	$("form#formApprover").parsley().reset();
});

modalPayment.find("#verifikasi_status").on("change", function () {
	const $this = $(this);
	const val = $this.val();
	const $verifikasiCatatan = $("#verifikasi_catatan");
	const $verifikasiCair = $("#verifikasi_cair");
	const $catatan = $("textarea[name='catatan']");

	const isRejectedOrFix = val === "TOLAK" || val === "PERBAIKAN";
	const isCair = val === "CAIR";
	// Toggle tampilan input nomor dan tanggal BKU
	$verifikasiCair
		.toggleClass("d-none", !isCair)
		.toggleClass("d-block", isCair)
		.find("input")
		.prop("required", isCair);
	// Toggle tampilan catatan verifikasi
	$verifikasiCatatan.toggleClass("d-none", !isRejectedOrFix);
	$verifikasiCatatan.toggleClass("d-block", isRejectedOrFix);

	// Set atribut pada textarea
	$catatan.prop("required", isRejectedOrFix);
	$catatan.attr(
		"placeholder",
		isRejectedOrFix ? "Masukkan catatan verifikasi" : "",
	);
});

$("form#formApprover").on("submit", async function (e) {
	e.preventDefault();

	const form = $(this);
	const url = form.attr("action");

	// Validasi dengan Parsley
	if (!form.parsley().isValid()) return;

	// ✅ Tambahkan konfirmasi sebelum submit
	const isConfirmed = confirm("Apakah Anda yakin ingin menyimpan data ini?");
	if (!isConfirmed) return;

	try {
		// Ambil data form
		const formData = form.serialize();

		// Tampilkan loading state
		const submitBtn = form.find("[type=submit]");
		submitBtn
			.prop("disabled", true)
			.html('<i class="fa fa-spinner fa-spin"></i> Processing...');

		// Kirim data dengan fetch (POST)
		const response = await fetch(url, {
			method: "POST",
			headers: { "Content-Type": "application/x-www-form-urlencoded" },
			body: formData,
		});

		const res = await response.json();

		if (res.status) {
			return $.notify(res.message, {
				timer: 800,
				delay: 800,
				type: "success",
				onShow: () => {
					tabelPayment.ajax.reload();
					modalPayment.modal("hide");
				},
			});
		}

		$.notify(res.message, { type: "warning" });
	} catch (error) {
		console.error("Terjadi kesalahan:", error);
		$.notify(`Gagal memproses data. Silakan coba lagi. : ${error.message}`, {
			type: "danger",
			onShow: () => modalPayment.modal("hide"),
		});
	} finally {
		form.find("[type=submit]").prop("disabled", false).html("Submit");
	}
});

function TemplateTablePayment(row) {
	if (!row) return `Data is Empty`;

	let tanggalIndo = new Date(row.tanggal_verifikasi).toLocaleDateString(
		"id-ID",
		{
			day: "2-digit",
			month: "long",
			year: "numeric",
		},
	);

	return `
		<table class="table table-bordered">
			<tr>
				<td width="20%">Bidang / Bagian</td>
				<td>${row.nama_part}</td>
			</tr>
			<tr>
				<td width="15%">Uraian</td>
				<td>${row.nama_uraian}</td>
			</tr>
			<tr>
				<td width="15%">Jumlah</td>
				<td class="text-success">Rp. ${rupiah(row.jumlah)}</td>
			</tr>
			<tr>
				<td width="15%">Nomor Verifikasi</td>
				<td>${row.nomor_verifikasi}</td>
			</tr>
			<tr>
				<td width="15%">Tanggal Verifikasi</td>
				<td>${tanggalIndo}</td>
			</tr>
			<tr class="bg-light">
				<td width="20%">Catatan Verifikator</td>
				<td>${row.catatan_by_verify}</td>
			</tr>
		</table>
	`;
}

function rupiah(num) {
	return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

$("#filter_tanggal").daterangepicker({
	showDropdowns: false,
	autoApply: true,
	drops: "auto",
	opens: "center",
	autoUpdateInput: false,
	locale: {
		format: "DD/MM/YYYY",
		separator: " - ",
		cancelLabel: "Clear",
	},
	ranges: {
		Today: [moment(), moment()],
		Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
		"Last 7 Days": [moment().subtract(6, "days"), moment()],
		"Last 30 Days": [moment().subtract(29, "days"), moment()],
		"This Month": [moment().startOf("month"), moment().endOf("month")],
		"Last Month": [
			moment().subtract(1, "month").startOf("month"),
			moment().subtract(1, "month").endOf("month"),
		],
	},
});

$('#filter_tanggal').on(
	"apply.daterangepicker",
	function (ev, picker) {
		$(this).val(
			picker.startDate.format("DD/MM/YYYY") +
				" - " +
				picker.endDate.format("DD/MM/YYYY"),
		);
	},
);

$('#filter_tanggal').on(
	"cancel.daterangepicker",
	function (ev, picker) {
		$(this).val("");
	},
);

function UnduhData() {
	const filterStatus = FILTER_FORM_PAYMENT.find("select[name='filter_status']").val() || "";
	const filterBidang = FILTER_FORM_PAYMENT.find("select[name='filter_bidang']").val() || "";
	const filterTanggal = FILTER_FORM_PAYMENT.find("input[name='filter_tanggal']").val() || "";

	const params = new URLSearchParams({
		filter_status: filterStatus,
		filter_bidang: filterBidang,
		filter_tanggal: filterTanggal,
	});
	
	window.open(`${_uri}/app/payment/export?${params.toString()}`, "_blank");
}