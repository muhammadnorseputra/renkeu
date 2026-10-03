/* UI halaman Verifikasi Usul SPJ (spj-verifikasi.css).
   Submit + konfirmasi + efek loading tetap dipegang spj_verifikasi.js. */
(function ($) {
	"use strict";

	// busy: overlay penuh + teks
	function vrfBusy(on, text) {
		$("#vrfBusyText").text(text || "Memproses…");
		$("#vrfBusy").toggleClass("on", !!on);
	}

	// salin teks ke clipboard + feedback ikon check
	function copyText(btn, txt) {
		var done = function () {
			btn.find("i").removeClass("fa-copy").addClass("fa-check");
			setTimeout(function () {
				btn.find("i").removeClass("fa-check").addClass("fa-copy");
			}, 1200);
		};
		if (navigator.clipboard) {
			navigator.clipboard.writeText(txt).then(done);
		} else {
			var $t = $("<textarea>").val(txt).appendTo("body").select();
			document.execCommand("copy");
			$t.remove();
			done();
		}
	}

	// dock submit form pada tab yang sedang aktif (id form sama di 3 tab).
	$("#vrfProses").on("click", function () {
		$(".tab-pane.active form").submit();
	});

	// copy kode rekening
	$("#vrfCopyRek").on("click", function () {
		copyText($(this), $("#vrfRek").text());
	});

	// copy kode program/kegiatan/sub kegiatan
	$(document).on("click", ".vrf-copy-kode", function () {
		copyText($(this), $(this).data("kode") + "");
	});

	// show/hide nilai
	$("#vrfNilaiToggle").on("click", function () {
		var $btn = $(this), $n = $("#vrfNilai"), $i = $btn.find("i"),
			show = !$n.data("show");
		$n.data("show", show).css("filter", show ? "none" : "blur(2.5px)");
		$i.attr("class", show ? "fa fa-eye" : "fa fa-eye-slash");
		$btn.attr("aria-pressed", show ? "true" : "false");
		$n.attr("title", show ? "Sembunyikan nilai" : "Tampilkan nilai");
	});
	$("#vrfNilai").on("click", function () {
		$("#vrfNilaiToggle").trigger("click");
	});

	// tanggal + nomor: tandai ada isi (tampilkan tombol clear) + clear custom
	function vrfSyncClear($input, $wrap) {
		$wrap.toggleClass("has-value", !!$input.val())
			.toggleClass("no-clear", $input.prop("disabled"));
	}
	var $tgl = $("#tanggal"), $tglWrap = $("#vrfDateWrap");
	var $nomor = $("#nomor"), $nomorWrap = $("#vrfNomorWrap");
	$tgl.on("dp.change change input", function () { vrfSyncClear($tgl, $tglWrap); });
	$nomor.on("change input", function () { vrfSyncClear($nomor, $nomorWrap); });
	vrfSyncClear($tgl, $tglWrap);
	vrfSyncClear($nomor, $nomorWrap);
	$("#vrfDateClear").on("click", function () {
		$tgl.val("").trigger("change");
		vrfSyncClear($tgl, $tglWrap);
		$tgl.focus();
	});
	$("#vrfNomorClear").on("click", function () {
		$nomor.val("").trigger("change");
		vrfSyncClear($nomor, $nomorWrap);
		$nomor.focus();
	});

	// toggle preview berkas: muat iframe hanya saat dibuka (hemat loading)
	$("#vrfBerkas").on("click", function () {
		var $p = $("#vrfPreview"), $f = $p.find("#vrfPreviewFrame");
		$p.toggleClass("d-none");
		if ($p.hasClass("d-none") || !$f.length) return;
		if ($f.attr("src") === "about:blank") {
			$("#vrfFrameLoading").addClass("on");
			vrfBusy(true, "Memuat berkas…");
			$f.one("load", function () {
				$("#vrfFrameLoading").removeClass("on");
				vrfBusy(false);
			}).attr("src", $f.data("src"));
			// jaring pengaman: iframe dari GDrive/office kadang tidak fire load
			setTimeout(function () {
				$("#vrfFrameLoading").removeClass("on");
				vrfBusy(false);
			}, 8000);
		} else {
			vrfBusy(true, "Memuat berkas…");
			$f.attr("src", $f.attr("src")); // force reload
			$("#vrfFrameLoading").addClass("on");
			setTimeout(function () {
				$("#vrfFrameLoading").removeClass("on");
				vrfBusy(false);
			}, 3000);
		}
	});
})(jQuery);