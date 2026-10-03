let $formStep = $("form#form-step-1");

var getStep = urlParams.get("step");
var getToken = urlParams.get("token");

if (getStep == "") {
	isStep = 0;
} else {
	isStep = getStep;
}
$("#wizard").smartWizard({
	// Properties
	selected: isStep,
	keyNavigation: false, // Enable/Disable key navigation(left and right keys are used if enabled)
	enableAllSteps: false, // Enable/Disable all steps on first load
	transitionEffect: "slide", // Effect on navigation, none/fade/slide/slideleft
	contentURL: null, // specifying content url enables ajax content loading
	contentURLData: null, // override ajax query parameters
	contentCache: false, // cache step contents, if false content is fetched always from ajax url
	cycleSteps: false, // cycle step navigation
	enableFinishButton: false, // makes finish button enabled always
	hideButtonsOnDisabled: true, // when the previous/next/finish buttons are disabled, hide them instead
	errorSteps: [], // array of step numbers to highlighting as error steps
	labelNext: "Selanjutnya", // label for Next button
	labelPrevious: "Sebelumnya", // label for Previous button
	labelFinish: "Selesai", // label for Finish button
	noForwardJumping: true,
	ajaxType: "POST",
	// Events
	onLeaveStep: null, // triggers when leaving a step
	onShowStep: function ($steps, context) {
		uslSetProgress(context.toStep - 1);
	}, // perbarui header "Langkah n/5" + progress bar
	onFinish: null, // triggers when Finish button is clicked
	buttonOrder: ["next", "prev", "finish"], // button order, to hide a button remove it from the list
});

// header wizard: langkah aktif + progress bar
function uslSetProgress(idx) {
	const $items = $("#wizard").find("ul.wizard_steps li a");
	const total = $items.length || 5;
	const cur = (idx || 0) + 1;
	$("#usl-step-no").text(cur);
	const title = $items.eq(idx || 0).data("title");
	if (title) $("#usl-step-title").text(title);
	$("#usl-progress-bar").css("width", Math.round((cur / total) * 100) + "%");
}
uslSetProgress(parseInt(isStep, 10) || 0);

function nextStep(path) {
	return (window.location.href = path);
}

// toast alert menarik (tanpa dependensi)
function uslAlert(msg, type) {
	const ico = type === "ok" ? "fa-check-circle" : "fa-exclamation-triangle";
	const $el = $(
		`<div class="usl-alert usl-alert--${type || "warn"}">` +
			`<span class="usl-alert-ico"><i class="fa ${ico}"></i></span>` +
			`<span class="usl-alert-txt">${msg}</span>` +
			`<button type="button" class="usl-alert-x" aria-label="Tutup"><i class="fa fa-times"></i></button>` +
			`<span class="usl-alert-bar"></span>` +
		`</div>`
	);
	$("body").append($el);
	requestAnimationFrame(() => $el.addClass("usl-alert--in"));
	const t = setTimeout(() => uslAlertClose($el), 3200);
	$el.find(".usl-alert-x").on("click", () => {
		clearTimeout(t);
		uslAlertClose($el);
	});
}
function uslAlertClose($el) {
	$el.removeClass("usl-alert--in").addClass("usl-alert--out");
	setTimeout(() => $el.remove(), 300);
}

// step-2: penerima manfaat minimal 1, kalau kosong blokir + alert
function uslNextStep2(path) {
	let total = 0;
	if (typeof tablePenerimaManfaat !== "undefined" && tablePenerimaManfaat) {
		total = tablePenerimaManfaat.page.info().recordsTotal;
	}
	if (total < 1) {
		uslAlert("Relasi Publik minimal 1 (satu) penerima manfaat. Silakan tambah data terlebih dahulu.", "warn");
		return false;
	}
	return nextStep(path);
}

$("form#form-step-1").on("submit", async function (e) {
	e.preventDefault();

	let _ = $(this),
		action = _.attr("action"),
		data = _.serialize(),
		$button = _.find('button[type="submit"]');

	if (_.parsley().isValid()) {
		$button.html('<i class="fa fa-circle-o-notch fa-spin mr-2"></i>processing ...').prop("disabled", true);

		// tampilkan loader
		$.blockUI({
			message: `<img src="${_uri}/template/assets/loader/motion-blur.svg" width="120">`,
			css: { backgroundColor: "transparent", borderColor: "transparent" },
		});

		try {
			// kirim form dengan fetch
			const req = await fetch(action, {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded",
				},
				body: data,
			});

			const res = await req.json();

			$.blockUI({
				message: res.msg,
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
				onUnblock: function () {
					if (res.code === 200) {
						return window.location.replace(res.redirect);
					}
					$button
						.prop("disabled", false)
						.html('<i class="fa fa-save mr-2"></i> Simpan & Lanjutkan');
				},
			});
		} catch (err) {
			$button
				.prop("disabled", false)
				.html('<i class="fa fa-save mr-2"></i> Simpan & Lanjutkan');
			return alert("Terjadi kesalahan: " + err.message);
		}
	}
});

$("form#form-step-3").on("submit", async function (e) {
	e.preventDefault();

	let _ = $(this),
		action = _.attr("action"),
		data = _.serialize(), // tetap pakai serialize jQuery
		$button = _.find('button[type="submit"]');

	if (_.parsley().isValid()) {
		$button.html('<i class="fa fa-circle-o-notch fa-spin mr-2"></i>processing ...').prop("disabled", true);

		// tampilkan blockUI loader
		$.blockUI({
			message: `<img src="${_uri}/template/assets/loader/motion-blur.svg" width="120">`,
			css: { backgroundColor: "transparent", borderColor: "transparent" },
		});

		try {
			// kirim form pakai fetch (POST)
			const req = await fetch(action, {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded",
				},
				body: data,
			});

			const res = await req.json();

			$.blockUI({
				message: res.msg,
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
				onUnblock: function () {
					if (res.code === 200) {
						return window.location.replace(res.redirect);
					}
					$button
						.prop("disabled", false)
						.html('<i class="fa fa-save mr-2"></i> Kirim Usulan');
				},
			});
		} catch (err) {
			$button
				.prop("disabled", false)
				.html('<i class="fa fa-save mr-2"></i> Kirim Usulan');
			return alert("Terjadi kesalahan: " + err.message);
		}
		return false;
	}
});


function uslConfirm(o) {
	return new Promise(function (resolve) {
		var html =
			'<div class="usl-confirm">' +
			'<div class="usl-confirm__ico"><i class="fa ' + (o.icon || "fa-question-circle") + '"></i></div>' +
			'<div class="usl-confirm__t">' + (o.title || "Konfirmasi") + "</div>" +
			'<div class="usl-confirm__x">' + (o.text || "") + "</div>" +
			'<div class="usl-confirm__b">' +
			'<button type="button" class="btn btn-light" data-c="0">' + (o.no || "Kembali") + "</button>" +
			'<button type="button" class="btn btn-success" data-c="1">' + (o.ok || "Lanjutkan") + "</button>" +
			"</div></div>";
		$.blockUI({
			message: html,
			css: { border: "none", padding: "0", background: "transparent", cursor: "default", width: "auto" },
			overlayCSS: { backgroundColor: "rgba(15,23,42,.72)", cursor: "pointer" },
			onBlock: function () {
				var $c = $(".usl-confirm");
				$c.find("[data-c]").on("click", function () {
					// resolve dulu, baru unblock — onUnblock resolve(false) harus kalah
					resolve($(this).data("c") == 1);
					$.unblockUI();
				});
				$c.find('[data-c="1"]').focus();
			},
			onUnblock: function () {
				resolve(false);
			},
		});
	});
}

$("form#form-step-4").on("submit", async function (e) { 
	e.preventDefault();
	
	let _ = $(this),
		action = _.attr("action"),
		data = _.serialize(),
		$button = _.find('button[type="submit"]');

	const _ok = await uslConfirm({
		icon: "fa-check-circle",
		title: "Finalkan Usulan SPJ?",
		text: "Pastikan seluruh data yang sudah diinput sudah benar. Setelah difinalisasi, data tidak dapat diubah lagi.",
		ok: "Ya, finalkan",
		no: "Kembali",
	});
	if (!_ok) return false;

	$button.html('<i class="fa fa-circle-o-notch fa-spin mr-2"></i>processing ...').prop("disabled", true);
	// tampilkan blockUI loader
	$.blockUI({
		message: `<img src="${_uri}/template/assets/loader/motion-blur.svg" width="120">`,
		css: { backgroundColor: "transparent", borderColor: "transparent" },
	});

	try {
		// kirim form pakai fetch (POST)
		const req = await fetch(action, {
			method: "POST",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
			},
			body: data,
		});

		const res = await req.json();

		if (!res.status) {
			return alert(res.msg);
		}

		$.blockUI({
			message: res.msg,
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
			onUnblock: function () {
				if (res.status) {
					return window.location.replace(res.redirect);
				}
				$button
					.prop("disabled", false)
					.html('Finalkan <i class="fa fa-save ml-2"></i>');
			},
		});
	} catch (err) {
		$button
			.prop("disabled", false)
			.html('Finalkan <i class="fa fa-save ml-2"></i>');
		return alert("Terjadi kesalahan: " + err.message);
	}
	return false;
});
function rupiah(num) {
	return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function newRealisasi(start, end) {
	var jml = start - end;
	var convert = rupiah(jml);
	return $("form#form-step-1")
		.find("#sisa_max")
		.html(`Rp. ${convert} <i class="text-danger fa fa-level-down"></i>`);
}
function newLimit(start, end) {
	var jml = start - end;
	var convert = rupiah(jml);
	return $("form#form-step-1")
		.find("#angkas")
		.html(`Rp. ${convert} <i class="text-danger fa fa-level-down"></i>`);
}

$("input[name='jumlah']").on("keyup", async function (e) {
	let start = $(this).attr("data-start");
	let start_limit = $(this).attr("data-start-limit");
	let jml = $(this).val();
	await newRealisasi(start, jml.split(".").join(""));
	await newLimit(start_limit, jml.split(".").join(""));
});
// pilih kode langsung dari step-1 (tanpa modal): pilih uraian → ambil hierarki + pagu
let uslReqSeq = 0; // guard: request lama jangan timpa request baru
$("select#uraian_kegiatan").on("change", async function (e) {
	let _ = $(this),
		uraian_id = _.val();
	if (!uraian_id) return;

	const seq = ++uslReqSeq;
	const $hier = $formStep.find("#loadKegiatan");
	const $stat = $formStep.find("#jumlah_max, #sisa_max");

	$("input[name='jumlah']").val("");
	$formStep.find('select[name="periode"],select[name="tahun"]').prop("disabled", true);
	_.prop("disabled", true);

	// shimmer selama memuat hierarki + pagu
	$hier
		.removeClass("usl-hier--empty")
		.html(
			["d0 w80", "d1 w60", "d2 w80"]
				.map(
					(c) =>
						`<div class="usl-hier-item"><span class="usl-hier-dot ${c.split(" ")[0]}"></span><span class="usl-skel usl-skel--line ${c.split(" ")[1]}"></span></div>`,
				)
				.join(""),
		);
	$stat
		.addClass("usl-stat--load")
		.html('<span class="usl-skel usl-skel--val"></span>');

	try {
		const req = await fetch(`${_uri}/app/spj/carikode`, {
			method: "POST",
			headers: {
				"Content-Type": "application/x-www-form-urlencoded",
			},
			body: `uraian_kegiatan=${encodeURIComponent(uraian_id)}`,
		});
		if (!req.ok) throw new Error(`HTTP ${req.status}`);
		const res = await req.json();
		if (seq !== uslReqSeq) return; // ada pilih lebih baru → abaikan

		$formStep.find("#loadKegiatan").removeClass("usl-hier--empty").html(`
						<div class="usl-hier-item"><span class="usl-hier-dot d0"></span><i class="fa fa-file-code-o" aria-hidden="true"></i><span>${res.nama_kegiatan}</span></div>
						<div class="usl-hier-item"><span class="usl-hier-dot d1"></span><i class="fa fa-file-code-o" aria-hidden="true"></i><span>${res.nama_subkegiatan}</span></div>
						<div class="usl-hier-item usl-hier-item--uraian"><span class="usl-hier-dot d2"></span><i class="fa fa-check-circle" aria-hidden="true"></i><span>${res.nama_uraian}</span></div>
						`);
		$formStep.find('input[name="koderek"]').val(res.kode);
		$formStep.find('input[name="ref_part"]').val(res.part_id);
		$formStep.find('input[name="ref_program"]').val(res.program_id);
		$formStep.find('input[name="ref_kegiatan"]').val(res.kegiatan_id);
		$formStep.find('input[name="ref_subkegiatan"]').val(res.subkegiatan_id);
		$formStep.find('input[name="ref_uraian"]').val(res.uraian_id);

		$formStep
			.find("#jumlah_max")
			.removeClass("usl-stat--load")
			.html(
				`Rp. ${rupiah(
					res.pagu.total_pa,
				)} <i class="text-success fa fa-external-link-square"></i>`,
			);
		$formStep
			.find("#sisa_max")
			.removeClass("usl-stat--load")
			.html(
				`Rp. ${rupiah(
					res.pagu.total_sisa_pa,
				)} <i class="text-danger fa fa-level-down"></i>`,
			);
		$formStep.find("#angkas").html("Rp. 0 <i class=\"text-danger fa fa-level-down\"></i>");
		$("input[name='jumlah']").attr({
			"data-start": res.pagu.total_sisa_pa,
			"data-start-limit": 0,
		});

		$formStep
			.find('select[name="periode"]')
			.prop("disabled", false)
			.val("")
			.trigger("change");
		$formStep.find('select[name="tahun"]').prop("disabled", false);
		$formStep.parsley().reset();
	} catch (err) {
		if (seq !== uslReqSeq) return;
		$formStep.find("#loadKegiatan").addClass("usl-hier--empty").html("");
		$formStep.find('select[name="periode"]').prop("disabled", true);
		$formStep.find('select[name="tahun"]').prop("disabled", true);
		$stat
			.removeClass("usl-stat--load")
			.html('<span class="text-danger">Gagal memuat pagu</span>');
		_.val("").trigger("change");
		alert("Terjadi kesalahan: " + err.message);
	} finally {
		if (seq === uslReqSeq) _.prop("disabled", false);
	}
});

function formatResults(res) {
	if (!res.id) {
		return res.text;
	}
	if (!res.kode) {
		return res.text;
	}
	var $data = `${res.kode} - ${res.text}`;
	return $data;
}

async function cekAngkas(uraian_id, periode_id) {
	try {
		const req = await fetch(
			`${_uri}/app/spj/cek_angkas/${uraian_id}/${periode_id}`,
		);
		const res = await req.json();

		if (res.status) {
			$("input[name='jumlah']").attr({
				"data-start": res.sisa_pa,
				"data-start-limit": res.sisa,
			});
			// ponytail: base baru → re-apply jumlah tersimpan agar sisa tetap dikurangi
			const $j = $("input[name='jumlah']");
			const cur = $j.val() ? $j.val().split(".").join("") : "";
			if (cur) {
				await newRealisasi(res.sisa_pa, cur);
				await newLimit(res.sisa, cur);
			} else {
				$("#angkas").html(
					`Rp. ${rupiah(res.sisa)} <i class="text-danger fa fa-level-down"></i>`,
				);
				$("#sisa_max").html(
					`Rp. ${rupiah(res.sisa_pa)} <i class="text-danger fa fa-level-down"></i>`,
				);
			}
		} else {
			$("#angkas").html(`Rp. 0 <i class="text-danger fa fa-level-down"></i>`);
		}
	} catch (error) {
		console.error("Error cekAngkas:", error);
		$("#angkas").html(`<span class="text-danger">Gagal memuat data</span>`);
	}
}

$("select[name='periode']").on("change", async function () {
	try {
		let _ = $(this);
		let uraian_id = $formStep.find('input[name="ref_uraian"]').val();
		let periode_id = _.val();

		// tunggu sampai cekAngkas selesai
		await cekAngkas(uraian_id, periode_id);

		// update atribut input jumlah
		$("input[name='jumlah']").attr({
			"data-parsley-remote": `${_uri}/app/spj/cek_angkas/${uraian_id}/${periode_id}`,
			"data-parsley-remote-trigger": "focusout,change",
			"data-parsley-remote-reverse": "false",
			"data-parsley-remote-options": '{ "type": "GET" }',
			"data-parsley-remote-message":
				"Jumlah yang dimasukan melebihi batas maksimum.",
			"data-parsley-pattern": "^(([0-9.]?)*)+$",
			disabled: false,
		});

		// enable textarea uraian
		$formStep.find('textarea[name="uraian"]').prop("disabled", false);
	} catch (error) {
		console.error("Error saat memproses perubahan periode:", error);
	}
});

$(function () {
	$(
		"select[name='periode'],select[name='bulan'],select[name='tahun']",
	).select2();

	const $uraian = $("select[name='uraian_kegiatan']");

	// backdrop blur di belakang kartu Kode Uraian Kegiatan
	$uraian.on("select2:open", function () {
		if (!$("#usl-select2-mask").length) {
			$("<div>", { id: "usl-select2-mask" })
				.appendTo("body")
				.on("click", function () { $uraian.select2("close"); });
		}
		$uraian.closest(".usl-card").addClass("usl-card--focus");
	}).on("select2:close", function () {
		$("#usl-select2-mask").remove();
		$uraian.closest(".usl-card").removeClass("usl-card--focus");
	});

	$uraian.select2({
		width: "100%",
		dropdownCssClass: "uraian-drop",
		allowClear: true,
		placeholder: "Ketik kode atau nama uraian ...",
		ajax: {
			url: `${_uri}/app/select2/ajaxUraianCari`,
			type: "post",
			dataType: "json",
			delay: 250,
			data: function (params) {
				return { searchTerm: params.term, page: params.page || 1 };
			},
			processResults: function (data, params) {
				params.page = params.page || 1;
				return { results: data.results, pagination: data.pagination };
			},
			cache: true,
			// skeleton shimmer saat request (term baru maupun page berikutnya)
			transport: function (params, success, failure) {
				const SKELETON = 3;
				let $drop = $(".uraian-drop");
				let $skel = $();
				for (let i = 0; i < SKELETON; i++) {
					$skel = $skel.add(
						$("<li>", { class: "uraian-skel" }).append(
							$("<span>", { class: "uraian-skel-badge" }),
							$("<div>", { class: "uraian-skel-body" }).append(
								$("<div>", { class: "uraian-skel-line w70" }),
								$("<div>", { class: "uraian-skel-line w45" }),
								$("<div>", { class: "uraian-skel-line w90" }),
								$("<div>", { class: "uraian-skel-line w60" }),
							),
						),
					);
				}
				// page 1 = hasil baru → ganti isi list; page >1 = infinite scroll → append
				if ((params.data.page || 1) === 1) {
					$drop.find(".select2-results__options").empty();
				}
				$drop.find(".select2-results__options").append($skel);
				const clear = () =>
					$drop
						.find(".select2-results__options .uraian-skel")
						.remove();
				$.ajax(params)
					.done(function (data) {
						clear();
						success(data);
					})
					.fail(function () {
						clear();
						failure();
					});
			},
		},
		templateResult: function (res) {
			if (!res.id) return res.text;
			let $box = $("<div>", { class: "uraian-opt" });
			$box.append(
				$("<span>", { class: "uraian-no" }).text(res.no || ""),
			);
			let $body = $("<div>", { class: "uraian-body" });
			$body.append(
				$("<div>", { class: "uraian-title" }).text(res.text),
			);
			let $steps = $("<div>", { class: "uraian-steps" });
			$.each(res.levels || [], function (i, lv) {
				$steps.append(
					$("<div>", { class: "uraian-step d" + i }).append(
						$("<span>", { class: "uraian-lvl" }).text(lv.t),
						$("<span>", { class: "uraian-stepname" }).text(lv.n),
					),
				);
			});
			$body.append($steps);
			$box.append($body);
			return $box;
		},
		templateSelection: function (res) {
			if (!res || !res.id) return res ? res.text : "";
			// single-select: tampilkan sebagai pill tag
			return $("<span>", { class: "uraian-tag" }).append(
				res.no ? $("<span>", { class: "uraian-tag-no" }).text(res.no) : $(),
				$("<span>", { class: "uraian-tag-txt" }).text(res.text),
			);
		},
	});

	$("input#organisasi").autocomplete({
		serviceUrl: `${_uri}/app/spj/autocomplete/organisasi`,
		minChars: 2,
		deferRequestBy: 300,
	});

	$("input#perorangan").autocomplete({
		serviceUrl: `${_uri}/app/spj/autocomplete/perorangan`,
		minChars: 2,
		deferRequestBy: 300,
	});

	const MODAL_RELASI_PUBLIK = $("#tambah-data-users");

// preview link eviden (step-3): tampil otomatis saat link http(s) valid
const $link = $("textarea#link"),
	$btnClear = $("#btnClearLink");
function uslSyncPreview() {
	const url = ($link.val() || "").trim();
	const ok = /^https?:\/\/.+/i.test(url);
	$btnClear.prop("disabled", !($link.val() || "").length);
	if (ok) {
		$("#uslPreviewOpenFoot").attr("href", url);
		$("#uslPreviewUrl").text(url);
		$("#uslPreviewBox").show();
	} else {
		$("#uslPreviewBox").hide();
	}
}
$link.on("input", uslSyncPreview);
uslSyncPreview();
// Clear link
$btnClear.on("click", function () {
	$link.val("");
	if ($link.parsley) $link.parsley().reset();
	$("#uslPreviewBox").hide();
	uslSyncPreview();
	$link.focus();
});
	const FORM_RELASI_PUBLIK = $("#formRelasiPublik");
	MODAL_RELASI_PUBLIK.on("hidden.bs.modal", function (e) {
		FORM_RELASI_PUBLIK[0].reset();
		FORM_RELASI_PUBLIK.parsley().reset();
	});

	FORM_RELASI_PUBLIK.on("submit", async function (e) {
		e.preventDefault();

		const formData = new FormData(this);
		try {
			const response = await fetch(`${_uri}/app/spj/tambah_penerima_manfaat`, {
				method: "POST",
				body: formData,
				headers: {
					"X-Requested-With": "XMLHttpRequest",
				},
			});

			const result = await response.json();

			if (result.status) {
				$.blockUI({
					message: result.pesan,
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

				// reset form
				FORM_RELASI_PUBLIK[0].reset();
				FORM_RELASI_PUBLIK.parsley().reset();

				// tutup modal (jika pakai bootstrap 4)
				MODAL_RELASI_PUBLIK.modal("hide");
				// optional: reload table/data
				// location.reload();
				tablePenerimaManfaat.ajax.reload(); // reload datatable tanpa reset paging
				return false;
			}

			$.blockUI({
				message: result.pesan,
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
		} catch (error) {
			console.error("Error:", error);
			alert("Terjadi kesalahan saat menyimpan data.");
		}
	});
});
