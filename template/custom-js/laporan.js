function PilihPeriode(id) {
	window.location.replace(`${_uri}/app/capaian?periode=${id}`);
}

var MODAL_FAKTOR = $(".modal-faktor");

function InputFaktor(id) {
	MODAL_FAKTOR.modal("show");
	$.getJSON(`${_uri}/app/capaian/detail_faktor`, { id: id }, function (res) {
		MODAL_FAKTOR.find("input[name='id']").val(id);
		MODAL_FAKTOR.find("textarea[name='pendorong']").val(res.pendorong);
		MODAL_FAKTOR.find("textarea[name='penghambat']").val(res.penghambat);
		MODAL_FAKTOR.find("textarea[name='tindak_lanjut']").val(res.tindak_lanjut);
	});
}

$("form#formFaktor").on("submit", function (e) {
	e.preventDefault();
	let _ = $(this),
		data = _.serialize(),
		url = _.attr("action");

	if (_.parsley().isValid()) {
		$.post(
			url,
			data,
			function (res) {
				if (res === 200) {
					window.location.reload();
				}
				// console.log(res);
			},
			"json"
		);
	}
});

MODAL_FAKTOR.on("hidden.bs.modal", function (e) {
	$("form#formFaktor")[0].reset();
	$("form#formFaktor").parsley().reset();
});
