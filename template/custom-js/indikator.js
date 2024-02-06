$(function () {
	let $modal = $(".modal-indikator"),
		$form = $("form#formIndikator");

	$("button#TambahIndikator").bind("click", function (e) {
		e.preventDefault();
		let _ = $(this),
			id = _.data("id"),
			label = _.data("label"),
			ref = _.data("ref");

		$modal.modal("show");
		$modal.find("#myModalLabel").text(`Indikator ${label}`);
		$modal.find("input[name='id']").val(id);
		$modal.find("input[name='ref']").val(ref);
	});

	$form.on("submit", function (e) {
		e.preventDefault();
		let id = $(this).find("input[name='id']").val(),
		ref = $(this).find("input[name='ref']").val(),
		url = $(this).attr('action');
		let $data = $(this).serializeArray();
		if ($(this).parsley().isValid()) {
			$data.push({ name: "id", value: id }, { name: "ref", value: ref });
			$.post(
				url,
				$data,
				(response) => {
					if (response === 200) {
						window.location.reload();
					}
				},
				"json"
			);
		}
	});

	$("button#HapusIndikator").bind("click", function (e) {
		e.preventDefault();
		let _ = $(this),
			id = _.data("id"),
			label = _.data("label");
		let $data = {
			id: id,
		};
		let msg = `Apakah anda yakin akan menghapus indikator ${label}`;

		if (confirm(msg)) {
			$.post(
				`${_uri}/app/target/hapus`,
				$data,
				(response) => {
					if (response === 200) {
						window.location.reload();
					}
				},
				"json"
			);
			return false;
		}
	});

	$modal.on("hidden.bs.modal", function (e) {
		$form[0].reset();
		$form.parsley().reset();
	});
});
