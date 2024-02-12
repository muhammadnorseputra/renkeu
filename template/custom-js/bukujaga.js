var $formBukuJaga = $("form#formBukuJaga"),
	$formCariKode = $("form#formCariKode"),
	$container = $("#displayData"),
	$modal = $("#modelSearchKode");

var options = {
	nextLabel: "Selanjutnya",
	prevLabel: "Sebelumnya",
	doneLabel: "Selesai",
	dontShowAgainLabel: "Jangan lihat ini lagi.",
};
var intro = introJs(".x_panel")
	.setOption("dontShowAgain", true)
	.setOptions(options)
	.start();

function showModal() {
	$modal.modal("show");
}

$formCariKode.on("submit", function (e) {
	e.preventDefault();
	let _ = $(this),
		action = _.attr("action"),
		data = _.serialize();
	if (_.parsley().isValid()) {
		$.post(
			action,
			data,
			function (res) {
				$formBukuJaga.find('input[name="koderek"]').val(res.kode);
				$formBukuJaga.find('input[name="ref_part"]').val(res.part_id);
				$formBukuJaga.find('input[name="ref_program"]').val(res.program_id);
				$formBukuJaga.find('input[name="ref_kegiatan"]').val(res.kegiatan_id);
				$formBukuJaga
					.find('input[name="ref_subkegiatan"]')
					.val(res.subkegiatan_id);
				$modal.modal("hide");
			},
			"json"
		);
	}
});

$formBukuJaga.on("submit", function (e) {
	e.preventDefault();
	let _ = $(this),
		$data = _.serialize(),
		$url = _.attr("action");
	if (_.parsley().isValid()) {
		$.blockUI({
			message: `<img src="${_uri}/template/assets/loader/motion-blur.svg" width="120">`,
			css: { backgroundColor: "transparent", borderColor: "transparent" },
		});
		$.post(
			$url,
			$data,
			function (res) {
				setTimeout(() => {
					$container.html(res);
					$.unblockUI();
				}, 1000);
			},
			"html"
		);
	}
});

function ajaxKegiatan(programId, partId) {
	$("select[name='kegiatan']").select2({
		// minimumInputLength: 3,
		width: "100%",
		dropdownParent: $("#modelSearchKode"),
		allowClear: false,
		ajax: {
			url: `${_uri}/app/select2/ajaxKegiatan`,
			type: "post",
			dataType: "json",
			delay: 350,
			data: function (params) {
				return {
					searchTerm: params.term,
					refId: programId,
					refPart: partId,
				};
			},
			processResults: function (response) {
				return {
					results: response,
				};
			},
			cache: false,
		},
	});
}

function ajaxSubKegiatan(kegiatanId) {
	$("select[name='sub_kegiatan']").select2({
		dropdownParent: $("#modelSearchKode"),
		allowClear: false,
		ajax: {
			url: `${_uri}/app/select2/ajaxSubKegiatan`,
			type: "post",
			dataType: "json",
			delay: 350,
			data: function (params) {
				return {
					searchTerm: params.term,
					refId: kegiatanId,
				};
			},
			processResults: function (response) {
				return {
					results: response,
				};
			},
			cache: false,
		},
	});
}
$(
	"select[name='program'],select[name='kegiatan'],select[name='sub_kegiatan']"
).select2({
	width: "100%",
	dropdownParent: $("#modelSearchKode"),
});

$("select[name='program']").on("change", function () {
	$("select[name='kegiatan']").val("").trigger("change");
	var id_program = $("select[name='program']").val();
	var id_part = $("input[name='part']").val();
	ajaxKegiatan(id_program, id_part);
});

$("select[name='kegiatan']").on("change", function () {
	let id = $(this).val();
	$("select[name='sub_kegiatan']").val("").trigger("change");
	ajaxSubKegiatan(id);
});
