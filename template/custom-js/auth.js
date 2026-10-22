$(document).ready(function () {
	var $containerMsg = $("#message");
	var $loginForm = $("#f_login");
	var $submitButton = $loginForm.find('button[type="submit"]').first();
	var submitButtonLabel = $submitButton.html();
	var isProcessing = false;

	function setLoginProcessing(processing) {
		$loginForm.attr("aria-busy", processing ? "true" : "false");
		$loginForm.find(':input:not([type="hidden"])').prop("disabled", processing);
		$submitButton
			.prop("disabled", processing)
			.html(
				processing
					? `<span style="display:inline-flex;align-items:center;justify-content:center;gap:8px"><img src="${_uri}/template/assets/loader/oval.svg" width="18" height="18" alt="">Memproses...</span>`
					: submitButtonLabel
			);
	}

	function showLoadingScreen(message) {
		var darkMode = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;
		$.blockUI({
			message: `<div role="status" aria-live="polite" style="display:flex;align-items:center;justify-content:center;gap:14px"><img src="${_uri}/template/assets/loader/motion-blur.svg" width="56" height="56" alt=""><span>${message}</span></div>`,
			css: {
				width: "auto",
				left: "50%",
				transform: "translateX(-50%)",
				padding: "18px 24px",
				backgroundColor: darkMode ? "#1e293b" : "#ffffff",
				border: `1px solid ${darkMode ? "#334155" : "#e5e7eb"}`,
				borderRadius: "14px",
				color: darkMode ? "#f8fafc" : "#1f2937",
				boxShadow: "0 12px 36px rgba(0, 0, 0, .2)",
				cursor: "wait",
			},
			overlayCSS: {
				backgroundColor: darkMode ? "#020617" : "#0f172a",
				opacity: 0.62,
				cursor: "wait",
			},
			baseZ: 2000,
			fadeIn: 150,
		});
	}

	function resetLoginProcessing() {
		if (!isProcessing) {
			return;
		}

		isProcessing = false;
		setLoginProcessing(false);
		$.unblockUI({ fadeOut: 150 });
	}

	$("input[name='username']").first().focus();
	$.validate({
		form: "#f_login",
		lang: "en",
		showErrorDialogs: false,
		focusFirstField: true,
		modules: "security, html5, sanitize",
		onError: function () {
			$containerMsg.html(`
			<div class="alert alert-danger" role="alert">
				<i class="bi bi-exclamation-circle me-2"></i> Auth access akun failed!
			</div>
			`);
			resetLoginProcessing();
		},
		onSuccess: function ($form) {
			if (isProcessing) {
				return false;
			}

			var _action = $form.attr("action");
			var _method = $form.attr("method");
			var _data = $form.serialize();
			$.ajax({
				url: _action,
				method: _method,
				data: _data,
				dataType: "json",
				beforeSend: function () {
					isProcessing = true;
					setLoginProcessing(true);
					showLoadingScreen("Memeriksa akun...");
				},
				success: function (response) {
					if (!response || typeof response !== "object") {
						$containerMsg.html(`
						<div class="alert alert-danger" role="alert">
							<i class="bi bi-exclamation-circle me-2"></i> Respons login tidak valid. Silakan coba lagi.
						</div>
						`);
						resetLoginProcessing();
						return;
					}

					if (response.valid == true) {
						$containerMsg.html(`
						<div class="alert alert-success" role="alert">
							<i class="bi bi-check-circle me-2"></i> ${response.msg}, mohon tunggu ...
						</div>
						`);
						showLoadingScreen("Login berhasil. Mengalihkan halaman...");
						$submitButton.html("Berhasil, mengalihkan...");
						setTimeout(function () {
							window.location.href = response.redirect;
						}, 2000);
						return;
					}

					$containerMsg.html(`
					<div class="alert alert-danger" role="alert">
						<i class="bi bi-exclamation-circle me-2"></i>${response.msg}
					</div>
					`);
					resetLoginProcessing();
				},
				error: function (err) {
					$containerMsg.html(`
					<div class="alert alert-danger" role="alert">
						${err.status} (${err.statusText})
					</div>
					`);
					resetLoginProcessing();
				},
			});
			return false;
		},
	});
});
