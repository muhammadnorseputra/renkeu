$(document).ready(function () {
	$("input[name='username']").focus();
	$.validate({
		form: "#f_login",
		lang: "en",
		showErrorDialogs: true,
		modules: "security, html5, sanitize, toggleDisabled",
		disabledFormFilter: 'form.toggle-disabled',
        // validateOnEvent: false,
		onError: function ($form) {
			$("html").block({
				message: "<h1>Error Login</h1>",
				overlayCSS: { backgroundColor: "#fff" },
				timeout: 1000,
				onBlock: function () {
					alert("Auth access akun failed!");
					$('button[type="submit"]').html(`Masuk`);
				},
				css: {
					padding: 20,
					margin: 20,
					fontSize: 20,
					borderRadius: 0,
					boxShadow: "0 0 3px #444",
					textAlign: "center",
					color: "red",
					backgroundColor: "#fff",
					border: false,
					cursor: "wait",
				},
			});
		},
		onSuccess: function ($form) {
			var _action = $form.attr("action");
			var _method = $form.attr("method");
			var _data = $form.serialize();
			$.ajax({
				url: _action,
				method: _method,
				data: _data,
				dataType: "json",
				beforeSend: function () {
					$('button[type="submit"]').html(
						`<div class="d-flex justify-content-center align-items-center"><span class="mr-2"><img width="15" src="${_uri}/template/assets/loader/oval.svg"></span> <span>Processing ...</span></div>`
					);
				},
				success: call_success,
				error: call_error,
			});
			return false; // Will stop the submission of the form
			// $form.removeClass('toggle-disabled');
			$form.get(0).reset();
		},
	});

	function call_success(response) {
		$("html").block({
			message: `${response.msg}`,
			overlayCSS: { backgroundColor: "#fff" },
			timeout: 1000,
			onBlock: function () {
				if (response.valid == true) {
					window.location.href = response.redirect;
				}
				$('button[type="submit"]').html(`Masuk`);
			},
			css: {
				padding: 20,
				margin: 20,
				fontSize: 20,
				borderRadius: 0,
				top: '10%',
				boxShadow: "0 2px 3em #ccc",
				textAlign: "center",
				color: "#000",
				backgroundColor: "#fff",
				border: "1px solid #eee",
				cursor: "wait",
			},
		});
	}

	function call_error(error) {
		$("html").block({
			message: `${error.status} (${error.statusText})`,
			overlayCSS: { backgroundColor: "#fff" },
			css: {
				padding: 20,
				margin: 20,
				fontSize: 20,
				top: '10%',
				textAlign: "center",
				borderRadius: 0,
				boxShadow: "0 2px 3em #ccc",
				color: "red",
				backgroundColor: "#fff",
				cursor: "wait",
				border: "1px solid #eee"
			},
			timeout: 1000,
			onBlock: function () {
				$('button[type="submit"]').html(`Masuk`);
			},
		});
	}

	// $(".toggle-password").click(function () {
	// 	$(this).toggleClass("fa-eye fa-eye-slash");
	// 	var input = $($(this).attr("toggle"));
	// 	var textPw = $("small.text_pw");
	// 	if (input.attr("type") == "password") {
	// 		input.attr("type", "text");
	// 		textPw.text("Hide Password");
	// 	} else {
	// 		input.attr("type", "password");
	// 		textPw.text("Show Password");
	// 	}
	// });
});
