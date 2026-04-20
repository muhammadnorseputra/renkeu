const MODAL_CATATAN_KINERJA = $("#tambahCatatanKinerja");
const MODAL_FAKTOR_KINERJA = $("#tambahFaktorKinerja");
const MODAL_NOTE = $(".modal-note");
const MODAL_REALISASI = $(".modal-realisasi");
const MODAL_VERIFIKASI = $(".modal-verifikasi");

// Reset form dan validasi saat modal ditutup
MODAL_CATATAN_KINERJA.on("hidden.bs.modal", function () {
	MODAL_CATATAN_KINERJA.find("form")[0].reset();
	MODAL_CATATAN_KINERJA.find("form").parsley().reset();
});

// Reset form dan validasi saat modal ditutup
MODAL_FAKTOR_KINERJA.on("hidden.bs.modal", function () {
	MODAL_FAKTOR_KINERJA.find("form")[0].reset();
	MODAL_FAKTOR_KINERJA.find("form").parsley().reset();
});

MODAL_NOTE.on("hidden.bs.modal", function (e) {
	$(this).find(".modal-body").html("");
});

MODAL_REALISASI.on("hidden.bs.modal", function (e) {
	$("form#formRealisasi")[0].reset();
	$("form#formRealisasi").parsley().reset();
});

MODAL_VERIFIKASI.on("hidden.bs.modal", function (e) {
	$("form#formVerifikasi")
		.find("#verifikasi_catatan")
		.removeClass("d-block")
		.addClass("d-none");
	$("form#formVerifikasi")[0].reset();
	$("form#formVerifikasi").parsley().reset();
});

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
				res?.realisasi?.status === "ENTRI" ||
				(res?.realisasi?.status === "ENTRI_ULANG" &&
					res?.realisasi?.catatan_verify !== "")
			) {
				$modal
					.find("#catatan-verify")
					.html(
						`
					<div class="alert alert-warning rounded-0 border text-dark d-flex justify-content-start align-items-start" role="alert">
						<i class="fa fa-exclamation-triangle mr-2 mt-1 text-danger"></i>
						<div><strong>Catatan Verifikator : </strong> <br> ${res.realisasi.catatan_verify}</div>
					</div>
					`,
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
		},
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
				.css("display", res.realisasi.is_jenis === "2" ? "none" : "");
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
					`<a href="${res?.realisasi?.eviden_link}" target="_blank">${res?.realisasi?.eviden_link}</a>`,
				);
			$modal
				.find("select[name='verifikasi_status']")
				.val(res?.realisasi?.status);
		},
	);
}

MODAL_REALISASI.find("form").on("submit", async function (e) {
	e.preventDefault();
	const form = this;
	const formData = new FormData(form);

	// Validasi menggunakan Parsley
	if (!$(form).parsley().isValid()) {
		return;
	}

	const clickedButton = $(document.activeElement);
	const status = clickedButton.data("status") || "DRAF";
	formData.append("status_entri", status);

	try {
		const resp = await fetch(`${_uri}/app/realisasi/simpanRealisasi`, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: formData,
		});
		const res = await resp.json();

		if (!resp.ok && !res.status) {
			return alert(res.message);
		}

		window.location.reload();
	} catch (err) {
		alert("Terjadi kesalahan koneksi : " + err.message);
	}
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

MODAL_VERIFIKASI.find("form").on("submit", async function (e) {
	e.preventDefault();

	const form = this;
	const formData = new FormData(form);

	// Validasi menggunakan Parsley
	if (!$(form).parsley().isValid()) {
		return;
	}

	try {
		const resp = await fetch(`${_uri}/app/realisasi/verifikasi`, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: formData,
		});

		const res = await resp.json();
		if (resp.ok && res.status) {
			window.location.reload();
		} else {
			return alert(res.message);
		}
	} catch (err) {
		alert("Terjadi kesalahan koneksi : " + err.message);
	}
});

MODAL_CATATAN_KINERJA.find("form").on("submit", async function (e) {
	e.preventDefault();
	const form = this;
	const formData = new FormData(form);

	// Validasi menggunakan Parsley
	if (!$(form).parsley().isValid()) {
		return;
	}

	try {
		const resp = await fetch(`${_uri}/app/realisasi/simpanCatatanKinerja`, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: formData,
		});

		const data = await resp.json();
		if (!resp.ok && !data.status) {
			return $.notify(data.message, {
				timer: 800,
				delay: 100,
				type: "danger",
			});
		}

		$.notify(data.message, {
			timer: 800,
			delay: 100,
			type: "success",
		});
		MODAL_CATATAN_KINERJA.modal("hide");
		// window.location.reload();
	} catch (err) {
		alert("Terjadi kesalahan koneksi : " + err.message);
	}
});

MODAL_FAKTOR_KINERJA.find("form").on("submit", async function (e) {
	e.preventDefault();
	const form = this;
	const formData = new FormData(form);

	// Validasi menggunakan Parsley
	if (!$(form).parsley().isValid()) {
		return;
	}

	try {
		const resp = await fetch(`${_uri}/app/realisasi/simpanFaktorKinerja`, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: formData,
		});

		const data = await resp.json();
		if (!resp.ok && !data.status) {
			return $.notify(data.message, {
				timer: 800,
				delay: 100,
				type: "danger",
			});
		}

		$.notify(data.message, {
			timer: 800,
			delay: 100,
			type: "success",
		});
		MODAL_FAKTOR_KINERJA.modal("hide");
	} catch (err) {
		alert("Terjadi kesalahan koneksi : " + err.message);
	}
});

async function PilihPeriode(id) {
	window.location.replace(`${_uri}/app/realisasi?periode=${id}`);
}

async function FaktorKinerja(indikator_id, periode_id, realisasi_id) {
	if (!realisasi_id) return;
	try {
		const formData = new FormData();
		formData.append("realisasi_id", realisasi_id);
		formData.append("indikator_id", indikator_id);
		formData.append("periode_id", periode_id);

		const resp = await fetch(`${_uri}/app/realisasi/getFaktorKinerja`, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: formData, // id dikirim sebagai FormData
		});
		const data = await resp.json();
		if (resp.ok && data.status) {
			MODAL_FAKTOR_KINERJA.find("textarea[name='faktor_pendorong']").val(
				data.faktor_pendorong,
			);
			MODAL_FAKTOR_KINERJA.find("textarea[name='faktor_penghambat']").val(
				data.faktor_penghambat,
			);
			MODAL_FAKTOR_KINERJA.find("textarea[name='tindak_lanjut']").val(
				data.tindak_lanjut,
			);
			MODAL_FAKTOR_KINERJA.find("input[name='realisasi_id']").val(realisasi_id);
			MODAL_FAKTOR_KINERJA.modal("show");
		} else {
			alert("Gagal mengambil catatan: " + data.pesan);
		}
	} catch (err) {
		alert("Terjadi kesalahan koneksi : " + err.message);
	}
}

async function CatatanKinerja(indikator_id, periode_id, realisasi_id) {
	if (!realisasi_id) return;
	try {
		const formData = new FormData();
		formData.append("realisasi_id", realisasi_id);
		formData.append("indikator_id", indikator_id);
		formData.append("periode_id", periode_id);

		const resp = await fetch(`${_uri}/app/realisasi/getCatatanKinerja`, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: formData, // id dikirim sebagai FormData
		});
		const data = await resp.json();
		if (resp.ok && data.status) {
			MODAL_CATATAN_KINERJA.find("textarea[name='catatan']").val(data.catatan);
			MODAL_CATATAN_KINERJA.find("input[name='realisasi_id']").val(
				realisasi_id,
			);
			MODAL_CATATAN_KINERJA.modal("show");
		} else {
			alert("Gagal mengambil catatan: " + data.pesan);
		}
	} catch (err) {
		alert("Terjadi kesalahan koneksi : " + err.message);
	}
}

async function ViewNote(id, periode) {
	let $modal = $(".modal-note"),
		_ = $(this);
	try {
		const resp = await fetch(
			`${_uri}/app/realisasi/detailNote?id=${id}&periode=${periode}`,
			{
				headers: {
					"X-Requested-With": "XMLHttpRequest",
				},
			},
		);
		const res = await resp.json();
		if (resp.ok && res.status) {
			$modal.modal("show");
			$modal.find(".modal-body").html(res?.note);
		} else {
			alert("Gagal mengambil catatan: " + res.message);
		}
	} catch (err) {
		alert("Terjadi kesalahan koneksi : " + err.message);
	}
}
