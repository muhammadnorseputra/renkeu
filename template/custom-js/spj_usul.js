var getStep = urlParams.get("step");
if (getStep == "") {
	isStep = 0;
} else {
	isStep = getStep;
}
$("#wizard").smartWizard({
	// Properties
	selected: isStep,
	keyNavigation: false, // Enable/Disable key navigation(left and right keys are used if enabled)
	enableAllSteps: false, // Enable/Disable all steps on first load
	transitionEffect: "slide", // Effect on navigation, none/fade/slide/slideleft
	contentURL: null, // specifying content url enables ajax content loading
	contentURLData: null, // override ajax query parameters
	contentCache: false, // cache step contents, if false content is fetched always from ajax url
	cycleSteps: false, // cycle step navigation
	enableFinishButton: false, // makes finish button enabled always
	hideButtonsOnDisabled: true, // when the previous/next/finish buttons are disabled, hide them instead
	errorSteps: [], // array of step numbers to highlighting as error steps
	labelNext: "Selanjutnya", // label for Next button
	labelPrevious: "Sebelumnya", // label for Previous button
	labelFinish: "Selesai", // label for Finish button
	noForwardJumping: true,
	ajaxType: "POST",
	// Events
	onLeaveStep: null, // triggers when leaving a step
	onShowStep: null, // triggers when showing a step
	onFinish: null, // triggers when Finish button is clicked
	buttonOrder: ["next", "prev", "finish"], // button order, to hide a button remove it from the list
});

function nextStep(path) {
	return (window.location.href = path);
}
function showModalSearchKode() {
	$("#modelSearchKode").modal('show');
}

$("form#step-1").on("submit", function (e) {
	e.preventDefault();
	let _ = $(this),
		action = _.attr("action"),
		data = _.serialize();
	if (_.parsley().isValid()) {
		$.blockUI({
			message: `<img src="${_uri}/template/assets/loader/motion-blur.svg" width="120">`,
			css: { backgroundColor: "transparent", borderColor: "transparent" },
		});
		setTimeout(function () {
			$.post(
				action,
				data,
				function (res) {
					if (res.code === 200) {
						window.location.replace(res.redirect);
					}
				},
				"json"
			);
		}, 2000);
	}
});

$("form#step-2").on("submit", function (e) {
	e.preventDefault();
	let _ = $(this),
		action = _.attr("action"),
		data = _.serialize();
	let msg = "Apakah anda yakin akan mengirim usulan tersebut ?";
	if (_.parsley().isValid()) {
		if (confirm(msg)) {
			$.blockUI({
				message: `<img src="${_uri}/template/assets/loader/motion-blur.svg" width="120">`,
				css: { backgroundColor: "transparent", borderColor: "transparent" },
			});
			setTimeout(function () {
				$.post(
					action,
					data,
					function (res) {
						if (res.code === 200) {
							window.location.replace(res.redirect);
						}
					},
					"json"
				);
			}, 2000);
			return false;
		}
	}
});

function rupiah(num) {
	return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function newRealisasi(start, end) {
	var jml = start-end;
	var convert = rupiah(jml);
	return $("form#step-1").find('h5#sisa_max').html(`Rp. ${convert} <i class="text-danger fa fa-level-down"></i>`);
}

$("input[name='jumlah']").on('keyup', function(e) {
	let start = $(this).attr('data-start');
	let end = $(this).val();
	return newRealisasi(start, end.split('.').join(""));
});
$("form#formCariKode").on("submit", function (e) {
	e.preventDefault();
	$("input[name='jumlah']").val('');
	let _ = $(this),
		action = _.attr("action"),
		data = _.serialize();
	let $formStep = $("form#step-1");
	let $modal = $("#modelSearchKode");
	if (_.parsley().isValid()) {
		$.post(
			action,
			data,
			function (res) {
				$formStep.find('input[name="koderek"]').val(res.kode);
				$formStep.find('input[name="ref_part"]').val(res.part_id);
				$formStep.find('input[name="ref_program"]').val(res.program_id);
				$formStep.find('input[name="ref_kegiatan"]').val(res.kegiatan_id);
				$formStep.find('input[name="ref_subkegiatan"]').val(res.subkegiatan_id);
				$formStep.find('input[name="ref_uraian"]').val(res.uraian_id);
				
				$formStep.find('h5#jumlah_max').html(`Rp. ${rupiah(res.pagu.total_sisa_pa)} <i class="text-success fa fa-external-link-square"></i>`);
				$formStep.find('h5#sisa_max').html(`Rp. ${rupiah(res.pagu.total_sisa_pa)} <i class="text-danger fa fa-level-down"></i>`);
				
				$("input[name='jumlah']").attr('data-parsley-remote', `${_uri}/app/spj/cek_jumlah_pengajuan/${res.uraian_id}`);
				$("input[name='jumlah']").attr('data-start', res.pagu.total_sisa_pa);
				// $("input[name='jumlah']").attr('max', res.pagu.total_sisa_pa);
				
				$modal.modal("hide");
			},
			"json"
		);
	}
});

function formatResults(res) {
	if (!res.id) {
		return res.text;
	}
	if (!res.kode) {
		return res.text;
	}
	var $data = `${res.kode} - ${res.text}`;
	return $data;
}

$(function () {
	$(
		"select[name='part'],select[name='program'],select[name='kegiatan'],select[name='sub_kegiatan'],select[name='uraian_kegiatan']"
	).select2({
		width: "100%",
		dropdownParent: $("#modelSearchKode"),
	});

	$("select[name='periode'],select[name='bulan'],select[name='tahun']").select2();

	let $modal = $("#modelSearchKode");

	function select2Kegiatan(programId, partId) {
		$("select[name='kegiatan']").select2({
			// minimumInputLength: 3,
			width: "100%",
			dropdownParent: $("#modelSearchKode"),
			allowClear: false,
			ajax: {
				url: `${_uri}/app/select2/ajaxKegiatan`,
				type: "post",
				dataType: "json",
				delay: 200,
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
			// templateResult: formatResults,
			// templateSelection: formatResults
		});
	}
	select2Kegiatan(
		$modal.find('select[name="program"]').val(),
		$modal.find('select[name="part"]').val()
	);
	$("select[name='part'],select[name='program']").on("change", function () {
		$("select[name='kegiatan']").val("").trigger("change");
		var id_program = $("select[name='program']").val();
		var id_part = $("select[name='part']").val();
		select2Kegiatan(id_program, id_part);
	});

	function select2SubKegiatan(kegiatanId) {
		$("select[name='sub_kegiatan']").select2({
			// minimumInputLength: 3,
			dropdownParent: $("#modelSearchKode"),
			allowClear: false,
			ajax: {
				url: `${_uri}/app/select2/ajaxSubKegiatan`,
				type: "post",
				dataType: "json",
				delay: 200,
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
			// templateResult: formatResults,
			// templateSelection: formatResults
		});
	}

	$("select[name='kegiatan']").on("change", function () {
		let id = $(this).val();
		$("select[name='sub_kegiatan']").val("").trigger("change");
		select2SubKegiatan(id);
	});

	function select2UraianKegiatan(kegiatanId, subKegiatanId) {
		$("select[name='uraian_kegiatan']").select2({
			// minimumInputLength: 3,
			dropdownParent: $("#modelSearchKode"),
			allowClear: false,
			ajax: {
				url: `${_uri}/app/select2/ajaxUraianKegiatan`,
				type: "post",
				dataType: "json",
				delay: 350,
				data: function (params) {
					return {
						searchTerm: params.term,
						kegiatanId: kegiatanId,
						subKegiatanId: subKegiatanId,
					};
				},
				processResults: function (response) {
					return {
						results: response,
					};
				},
				cache: false,
				
			},
			escapeMarkup: function(m) {
			return m;
			}
			// templateResult: formatResults,
			// templateSelection: formatResults
		});
	}

	$("select[name='kegiatan'],select[name='sub_kegiatan']").on(
		"change",
		function () {
			// let id = $(this).val();
			var kegiatanId = $("select[name='kegiatan']").val();
			var subKegiatanId = $("select[name='sub_kegiatan']").val();
			select2UraianKegiatan(kegiatanId, subKegiatanId);
		}
	);
});
