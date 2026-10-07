/* ---------- uiConfirm / uiAlert: modal konfirmasi global (Bootstrap) ----------
   Dua gaya pemakaian:
   1) Callback : uiConfirm("pesan", function(){ ... }, { title, yes, yesClass, variant })
   2) Promise  : const ok = await uiConfirm({ title, text, ok, no, variant, icon })
   uiAlert(msg, opts) -> promise, hanya tombol OK.
*/
(function (window, $) {
	"use strict";

	var VARIANTS = {
		primary: { ico: "fa-question-circle", cls: "uic--primary" },
		danger: { ico: "fa-trash-o", cls: "uic--danger" },
		success: { ico: "fa-check-circle", cls: "uic--success" },
		warning: { ico: "fa-exclamation-triangle", cls: "uic--warning" },
		info: { ico: "fa-info-circle", cls: "uic--info" },
	};

	function ensure() {
		var $m = $("#uiConfirmModal");
		if ($m.length) return $m;
		$m = $(
			'<div class="modal fade uic" id="uiConfirmModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">' +
				'<div class="modal-dialog modal-dialog-centered uic-dialog" role="document">' +
				'<div class="modal-content uic-content">' +
				'<div class="uic-body">' +
				'<div class="uic-ico"><i class="fa"></i></div>' +
				'<h5 class="uic-title"></h5>' +
				'<p class="uic-text"></p>' +
				"</div>" +
				'<div class="uic-foot">' +
				'<button type="button" class="btn uic-no" data-dismiss="modal"></button>' +
				'<button type="button" class="btn uic-yes"></button>' +
				"</div>" +
				"</div>" +
				"</div>" +
				"</div>",
		).appendTo("body");
		return $m;
	}

	window.uiConfirm = function (msg, onYes, opts) {
		var o,
			cb = null;
		if (msg && typeof msg === "object") {
			o = msg;
		} else {
			o = opts || {};
			o.text = msg;
			cb = typeof onYes === "function" ? onYes : null;
		}

		var variant = VARIANTS[o.variant] || VARIANTS.primary;
		var $m = ensure();
		var $content = $m.find(".uic-content");

		$content
			.removeClass(
				"uic--primary uic--danger uic--success uic--warning uic--info",
			)
			.addClass(variant.cls);
		$m.find(".uic-ico i").attr("class", "fa " + (o.icon || variant.ico));
		$m.find(".uic-title").text(o.title || "Konfirmasi");
		$m.find(".uic-text").html(o.text || "");

		var noLabel = o.no === null ? null : o.no || "Batal";
		$m.find(".uic-no")
			.toggle(noLabel !== null)
			.text(noLabel || "");
		$m.find(".uic-yes")
			.removeClass("btn-primary btn-danger btn-success btn-warning btn-info")
			.addClass(o.yesClass || "btn-primary")
			.text(o.ok || o.yes || "Ya, Lanjutkan");

		return new Promise(function (resolve) {
			var settled = false;
			function done(val) {
				if (settled) return;
				settled = true;
				$m.off(".uic");
				$m.modal("hide");
				if (val && cb) cb();
				resolve(val);
			}
			$m.off(".uic");
			$m.on("click.uic", ".uic-yes", function () {
				done(true);
			});
			$m.on("hidden.bs.modal.uic", function () {
				done(false);
			});
			$m.modal("show");
			setTimeout(function () {
				$m.find(".uic-yes").focus();
			}, 200);
		});
	};

	window.uiAlert = function (msg, opts) {
		opts = opts || {};
		return window.uiConfirm({
			title: opts.title || "Informasi",
			text: msg,
			variant: opts.variant || "info",
			ok: opts.ok || "Mengerti",
			no: null,
		});
	};
})(window, jQuery);
