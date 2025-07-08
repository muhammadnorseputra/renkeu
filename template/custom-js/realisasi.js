function PilihPeriode(id) {
	window.location.replace(`${_uri}/app/realisasi?periode=${id}`);
}

function InputRealisasi(id, periode) {
	let $modal = $(".modal-realisasi"),
		_ = $(this);
	$.getJSON(
		`${_uri}/app/realisasi/detailIndikator`,
		{ id: id, periode: periode },
		function (res) {
			$modal.modal("show");
			$modal.find("textarea[name='nama']").val(res.indikator.nama);
			$modal.find("input[name='id']").val(res.indikator.id);
			$modal
				.find("input[name='persentase']")
				.val(res?.realisasi?.persentase)
				.prop(
					"readonly",
					res?.target?.persentase == 0 || res?.target?.persentase == null
				)
				.prop("required", res?.target?.persentase == 0 ? false : true);
			$modal
				.find("input[name='jumlah_eviden']")
				.val(res?.realisasi?.eviden)
				.prop(
					"readonly",
					res?.target?.eviden_jumlah == 0 || res?.target?.eviden_jumlah == null
				)
				.prop("required", res?.target?.eviden_jumlah == 0 ? false : true);
			$modal
				.find("input[name='keterangan_eviden']")
				.val(res?.target?.eviden_jenis);
			$modal.find("textarea[name='link']").val(res?.realisasi?.eviden_link);
		}
	);
}

$("form#formRealisasi").on("submit", function (e) {
	e.preventDefault();
	let _ = $(this),
		data = _.serialize(),
		url = _.attr("action");

	if (_.parsley().isValid()) {
		$.post(
			url,
			data,
			function (res) {
				if (res.status) {
					window.location.reload();
				}
				return alert(res.message);
			},
			"json"
		);
	}
});

$(".modal-realisasi").on("hidden.bs.modal", function (e) {
	$("form#formRealisasi")[0].reset();
	$("form#formRealisasi").parsley().reset();
});
