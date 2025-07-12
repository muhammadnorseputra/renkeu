$(function () {
	$("#tanggal").datetimepicker({
		format: "DD-MM-YYYY",
	});
	$("form#formVerifikasi").on("submit", function (e) {
		e.preventDefault();
		let _ = $(this),
			action = _.attr("action"),
			data = _.serialize(),
			status = _.find("input[name='status']").val();
		let msg = `Apakah anda yakin akan ${status} usulan tersebut ?`;
		if (_.parsley().isValid()) {
			if (confirm(msg)) {
				$.post(
					action,
					data,
					function (res) {
						alert(res.pesan);
						if (res.code === 200) {
							window.location.replace(res.redirect);
						}
					},
					"json"
				);
				return false;
			}
		}
	});
});

function Selesai(token) {
	let msg = "Apakah anda yakin akan menyelesaikan usulan tersebut ?";
	if (confirm(msg)) {
		$.post(
			`${_uri}/app/spj/verifikasi_proses_selesai`,
			{ token: token },
			function (res) {
				if (res.code === 200) {
					window.history.back(-1);
				}
			},
			"json"
		);
		return false;
	}
}
