function PilihPeriode(id) {
	window.location.replace(`${_uri}/app/realisasi?periode=${id}`);
}

function InputRealisasi(id, periode) {
	let $modal = $(".modal-realisasi"),
		_ = $(this);
	$.getJSON(
		`${_uri}/app/realisasi/detailIndikator`,
		{ id: id, periode: periode },
		function (res) {
			$modal.modal("show");
			$modal.find("textarea[name='nama']").val(res.indikator.nama);
			$modal.find("input[name='is_jenis']").val(res.target.is_jenis);
			$modal.find("input[name='id']").val(res.indikator.id);
			if (res?.target?.is_jenis === "1") {
				// Show form persentase
				$modal.find("#formPersentase").show();
				$modal.find("#formEviden").hide();
				$modal.find("#formKeteranganEviden").hide();
			}

			if (res?.target?.is_jenis === "2") {
				// Show form persentase
				$modal.find("#formPersentase").hide();
				$modal.find("#formEviden").show();
				$modal.find("#formKeteranganEviden").show();
			}

			if (
				res?.realisasi?.status === "ENTRI_ULANG" &&
				res?.realisasi?.catatan_verify !== ""
			) {
				$modal
					.find("#catatan-verify")
					.html(
						`
					<div class="alert alert-warning rounded-0 border text-dark d-flex justify-content-start align-items-start" role="alert">
						<i class="fa fa-exclamation-triangle mr-2 mt-1 text-danger"></i>
						<div><strong>Catatan Verifikator : </strong> <br> ${res.realisasi.catatan_verify}</div>
					</div>
					`
					)
					.show();
			} else {
				$modal.find("#catatan-verify").hide();
			}
			$modal
				.find("input[name='persentase']")
				.val(res?.realisasi?.persentase)
				.prop("readonly", res?.target?.is_jenis !== "1")
				.prop("required", res?.target?.is_jenis !== "1" ? false : true);
			$modal
				.find("input[name='jumlah_eviden']")
				.val(res?.realisasi?.eviden)
				.prop("readonly", res?.target?.is_jenis !== "2")
				.prop("required", res?.target?.is_jenis !== "2" ? false : true);
			$modal
				.find("input[name='keterangan_eviden']")
				.val(res?.target?.eviden_jenis)
				.prop("readonly", res?.target?.is_jenis !== "2")
				.prop("required", res?.target?.is_jenis !== "2" ? false : true);
			$modal.find("textarea[name='link']").val(res?.realisasi?.eviden_link);
		}
	);
}

function ViewNote(id, periode) {
	let $modal = $(".modal-note"),
		_ = $(this);
	$.getJSON(
		`${_uri}/app/realisasi/detailNote`,
		{ id: id, periode: periode },
		function (res) {
			$modal.modal("show");
			$modal.find(".modal-body").html(res?.note);
		}
	);
}

function Verifikasi(id, periode) {
	let $modal = $(".modal-verifikasi"),
		_ = $(this);
	$.getJSON(
		`${_uri}/app/realisasi/detailVerifikasi`,
		{ id: id, periode: periode },
		function (res) {
			$modal.modal("show");
			$modal
				.find("tr.persentase")
				.css(
					"display",
					res.realisasi.is_jenis === "2"
						? "none"
						: ""
				);
			$modal
				.find("tr.non-persentase")
				.css("display", res.realisasi.is_jenis === "1" ? "none" : "");
			$modal.find("input[name='id']").val(res.indikator.id);
			$modal.find("#verifikasi_periode").text(res?.periode?.nama);
			$modal.find("#verifikasi_nama").text(res?.indikator?.nama);
			$modal
				.find("#verifikasi_persentase")
				.text(`${res?.realisasi?.persentase}%`);
			$modal.find("#verifikasi_eviden").text(res?.realisasi?.eviden);
			$modal
				.find("#verifikasi_jenis_eviden")
				.text(res?.realisasi?.eviden_jenis);
			$modal
				.find("#verifikasi_link")
				.html(
					`<a href="${res?.realisasi?.eviden_link}" target="_blank">${res?.realisasi?.eviden_link}</a>`
				);
			$modal
				.find("select[name='verifikasi_status']")
				.val(res?.realisasi?.status);
		}
	);
}

$("form#formRealisasi").on("submit", function (e) {
	e.preventDefault();
	let _ = $(this),
		data = _.serialize(),
		url = _.attr("action");

	if (_.parsley().isValid()) {
		$.post(
			url,
			data,
			function (res) {
				if (res.status) {
					window.location.reload();
				}
				return alert(res.message);
			},
			"json"
		);
	}
});

$(".modal-realisasi").on("hidden.bs.modal", function (e) {
	$("form#formRealisasi")[0].reset();
	$("form#formRealisasi").parsley().reset();
});
$(".modal-note").on("hidden.bs.modal", function (e) {
	$(this).find(".modal-body").html("");
});
$("select#verifikasi_status").on("change", function (e) {
	let _ = $(this),
		val = _.val(),
		verifikasiCatatan = $("#verifikasi_catatan"),
		catatan = $("textarea[name='catatan']");
	if (val === "ENTRI_ULANG") {
		verifikasiCatatan.removeClass("d-none").addClass("d-block");
		catatan.attr("required", true);
		catatan.attr("placeholder", "Masukkan catatan verifikasi");
	} else {
		catatan.attr("required", false);
		verifikasiCatatan.removeClass("d-block").addClass("d-none");
	}
});
$("form#formVerifikasi").on("submit", function (e) {
	e.preventDefault();
	let _ = $(this),
		data = _.serialize(),
		url = _.attr("action");

	if (_.parsley().isValid()) {
		$.post(
			url,
			data,
			function (res) {
				if (res.status) {
					window.location.reload();
				}
				return alert(res.message);
			},
			"json"
		);
	}
});
$(".modal-verifikasi").on("hidden.bs.modal", function (e) {
	$("form#formVerifikasi")
		.find("#verifikasi_catatan")
		.removeClass("d-block")
		.addClass("d-none");
	$("form#formVerifikasi")[0].reset();
	$("form#formVerifikasi").parsley().reset();
});
