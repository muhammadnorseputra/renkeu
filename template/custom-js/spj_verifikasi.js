/* ---------- UI helpers: modal Bootstrap + bootstrap-notify (library yang sudah ada) ---------- */
function uiConfirm(msg, onYes, opts) {
	opts = opts || {};
	var id = "uiConfirmModal";
	var $m = $("#" + id);
	if (!$m.length) {
		$m = $(
			'<div class="modal fade" id="' + id + '" tabindex="-1" role="dialog" data-backdrop="static">' +
				'<div class="modal-dialog modal-dialog-centered" role="document">' +
					'<div class="modal-content">' +
						'<div class="modal-header py-2">' +
							'<h6 class="modal-title"><i class="fa fa-question-circle text-primary mr-1"></i><span class="ui-c-title"></span></h6>' +
							'<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>' +
						'</div>' +
						'<div class="modal-body py-3"><p class="mb-0 ui-c-msg"></p></div>' +
						'<div class="modal-footer py-2">' +
							'<button type="button" class="btn btn-light border btn-sm" data-dismiss="modal">Batal</button>' +
							'<button type="button" class="btn btn-primary btn-sm ui-c-yes"></button>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>'
		).appendTo("body");
	}
	$m.find(".ui-c-title").text(opts.title || "Konfirmasi");
	$m.find(".ui-c-msg").text(msg);
	$m.find(".ui-c-yes")
		.removeClass("btn-primary btn-success")
		.addClass(opts.yesClass || "btn-primary")
		.text(opts.yes || "Ya, Proses");
	$m.off("click.uiConfirm").on("click.uiConfirm", ".ui-c-yes", function () {
		$m.modal("hide");
		if (typeof onYes === "function") onYes();
	});
	$m.modal("show");
}

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
			close: "fa fa-times"
		}
	});
	$("form#formVerifikasi").on("submit", function (e) {
		e.preventDefault();
		let _button = $(this).find("button[type=submit]");
		if (! _button.length) _button = $(".vrf-dock #vrfProses"); // tombol dock, tanpa submit bawaan
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
						"json"
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
			{ title: "Konfirmasi Verifikasi", yes: "Ya, Proses" }
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
					"json"
				).fail(function () {
					$.unblockUI();
					uiNotify("Koneksi gagal, silakan coba lagi.", "danger");
				});
			} catch (error) {
				$.unblockUI();
				uiNotify(String(error), "danger");
			}
		},
		{ title: "Selesaikan Usulan", yes: "Ya, Selesaikan", yesClass: "btn-success" }
	);
	return false;
}
