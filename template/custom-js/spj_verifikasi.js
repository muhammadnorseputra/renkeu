/* ---------- UI helpers ---------- */
/* uiConfirm kini global: template/custom-js/ui-confirm.js (modal menarik).
   Dipakai tetap callback: uiConfirm(msg, onYes, { title, yes, yesClass }). */

function uiNotify(msg, type) {
	$.notify(msg, { type: type || "info", timer: 3000 });
}

$(function () {
	$("#tanggal").datetimepicker({
		format: "DD-MM-YYYY",
		useCurrent: false,
		showTodayButton: true,
		showClear: false, // tombol clear custom (#vrfDateClear)
		icons: {
			time: "fa fa-clock-o",
			date: "fa fa-calendar",
			up: "fa fa-chevron-up",
			down: "fa fa-chevron-down",
			previous: "fa fa-chevron-left",
			next: "fa fa-chevron-right",
			today: "fa fa-calendar-check-o",
			clear: "fa fa-trash",
			close: "fa fa-times",
		},
	});
	$("form#formVerifikasi").on("submit", function (e) {
		e.preventDefault();
		let _button = $(this).find("button[type=submit]");
		if (!_button.length) _button = $(".vrf-dock #vrfProses"); // tombol dock, tanpa submit bawaan
		let _ = $(this),
			action = _.attr("action"),
			data = _.serialize(),
			status = _.find("input[name='status']").val();
		let msg = `Apakah anda yakin akan ${status} usulan tersebut ?`;
		if (!_.parsley().isValid()) return;
		uiConfirm(
			msg,
			function () {
				_button
					.data("prev", _button.html())
					.html('<i class="fa fa-spinner fa-spin mr-1"></i>Memproses...')
					.prop("disabled", true);
				$.blockUI({
					message: `<img src="${_uri}/template/assets/loader/motion-blur.svg" width="120">`,
					css: { backgroundColor: "transparent", borderColor: "transparent" },
				});
				try {
					$.post(
						action,
						data,
						function (res) {
							if (res.code === 200) {
								uiNotify(res.pesan, "success");
								window.location.replace(res.redirect);
							} else {
								uiNotify(res.pesan, "danger");
								$.unblockUI();
								_button.html(_button.data("prev")).prop("disabled", false);
							}
						},
						"json",
					).fail(function () {
						$.unblockUI();
						_button.html(_button.data("prev")).prop("disabled", false);
						uiNotify("Koneksi gagal, silakan coba lagi.", "danger");
					});
				} catch (error) {
					$.unblockUI();
					_button.html(_button.data("prev")).prop("disabled", false);
					uiNotify(String(error), "danger");
				}
			},
			{ title: "Konfirmasi Verifikasi", yes: "Ya, Proses" },
		);
		return false;
	});
});

function Selesai(token) {
	uiConfirm(
		"Apakah anda yakin akan menyelesaikan usulan tersebut ?",
		function () {
			$.blockUI({
				message: `<img src="${_uri}/template/assets/loader/motion-blur.svg" width="120">`,
				css: { backgroundColor: "transparent", borderColor: "transparent" },
			});
			try {
				$.post(
					`${_uri}/app/spj/verifikasi_proses_selesai`,
					{ token: token },
					function (res) {
						if (res.code === 200) {
							uiNotify(res.pesan, "success");
							window.history.back(-1);
						} else {
							uiNotify(res.pesan, "danger");
							$.unblockUI();
						}
					},
					"json",
				).fail(function () {
					$.unblockUI();
					uiNotify("Koneksi gagal, silakan coba lagi.", "danger");
				});
			} catch (error) {
				$.unblockUI();
				uiNotify(String(error), "danger");
			}
		},
		{
			title: "Selesaikan Usulan",
			yes: "Ya, Selesaikan",
			yesClass: "btn-success",
		},
	);
	return false;
}
