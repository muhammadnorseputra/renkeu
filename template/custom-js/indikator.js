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
		console.log(label);
		// Tampilkan hanya form_indikator jika dipilih
		if (label === "Sub Kegiatan") {
			$modal.find("#form_indikator").css("display", "block");
			$modal.find("#form_perubahan").css("display", "block");
		} else {
			$modal.find("#form_indikator").css("display", "none");
			$modal.find("#form_perubahan").css("display", "none");
		}
	});

	$form.on("submit", function (e) {
		e.preventDefault();
		let id = $(this).find("input[name='id']").val(),
			ref = $(this).find("input[name='ref']").val(),
			url = $(this).attr("action");
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
		$("select#bidang").val("").trigger("change");
	});

	// select part
	$("select#bidang").select2({
		placeholder: "Pilih Bidang",
		allowClear: false,
		tags: true,
		tokenSeparators: [",", " "],
		// maximumSelectionLength: 1,
		width: "100%",
		// theme: "classic",
		// dropdownParent: MODAL_KEGIATAN,
		ajax: {
			delay: 250,
			method: "post",
			url: `${_uri}/app/programs/getParts`,
			dataType: "json",
			data: function (params) {
				return {
					q: params.term, // search term
				};
			},
			cache: false,
			processResults: function (data) {
				// Transforms the top-level key of the response object from 'items' to 'results'
				return {
					results: data,
				};
			},
			// Additional AJAX parameters go here; see the end of this chapter for the full code of this example
		},
	});
});
