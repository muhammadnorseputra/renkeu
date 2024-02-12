$(document).ajaxStart(function(e) {
	$.blockUI({
		message: `<img src="${_uri}/template/assets/loader/motion-blur.svg" width="120">`,
		css: { backgroundColor: "transparent", borderColor: "transparent" },
	});
})
$(document).ajaxStop($.unblockUI);
$(document).ready(function () {
	var $containerMsg = $("#message");
	$("input[name='username']").focus();
	$.validate({
		form: "#f_login",
		lang: "en",
		showErrorDialogs: true,
		modules: "security, html5, sanitize, toggleDisabled",
		disabledFormFilter: 'form.toggle-disabled',
        // validateOnEvent: false,
		onError: function ($form) {
			$containerMsg.html(`
			<div class="alert alert-danger" role="alert">
				<i class="icon-block mr-2"></i> Auth access akun failed!
			</div>
			`);
			$('button[type="submit"]').prop("disabled", false).html(`Masuk`);
			
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
					$('button[type="submit"]').prop("disabled", true).html(
						`<div class="d-flex justify-content-center align-items-center"><span class="mr-2"><img width="15" src="${_uri}/template/assets/loader/oval.svg"></span> <span>Processing ...</span></div>`
					);
				},
				success: function(response) {
					

					if (response.valid == true) {
						$containerMsg.html(`
						<div class="alert alert-success" role="alert">
							<i class="icon-check-circle mr-2"></i> ${response.msg}, mohon tunggu ...
						</div>
						`);
						setTimeout(() => {
							window.location.href = response.redirect;
						}, 2000)
						return false;
					}
					$containerMsg.html(`
					<div class="alert alert-danger" role="alert">
						<i class="icon-block mr-2"></i>${response.msg}
					</div>
					`);
					$('button[type="submit"]').prop("disabled", false).html(`Masuk`);
				},
				error: function(err) {
					$containerMsg.html(`
					<div class="alert alert-danger" role="alert">
						${err.status} (${err.statusText}
					</div>
					`);
					$('button[type="submit"]').prop("disabled", false).html(`Masuk`);
				},
			});
			return false; // Will stop the submission of the form
			// $form.removeClass('toggle-disabled');
			$form.get(0).reset();
		},
	});

	$(".toggle-password").click(function () {
        var passwordInput = $(".password-input");
        var icon = $(this);
        if (passwordInput.attr("type") == "password") {
            passwordInput.attr("type", "text");
            icon.removeClass("icon-eye").addClass("icon-eye-slash");
        } else {
            passwordInput.attr("type", "password");
            icon.removeClass("icon-eye-slash").addClass("icon-eye");
        }
    });
});
