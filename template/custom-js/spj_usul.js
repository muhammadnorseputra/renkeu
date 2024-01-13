$("#wizard").smartWizard({
	// Properties
	selected: 0,
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

$("form#step-1").on("submit", function(e) {
    e.preventDefault();
    let _ = $(this),
    action = _.attr('action'),
    data = _.serialize();
    if(_.parsley().isValid()) {
        // $.post(action,data,function(res){
        //     console.log(res);
        // }, 'json');
        // console.log('OK')
    }
	// $("#wizard").smartWizard("goForward");
})


function formatResults (res) {
    if(!res.id) {
        return res.text;
    }
    if(!res.kode) {
        return res.text;
    }
    var $data = `${res.kode} - ${res.text}`
    return $data;
};

$(function() {
    $("select").select2({
        dropdownParent: $('#wizard')
    });

    $("select[name='part'],select[name='program']").on('change', function(){
        let id_program = $("select[name='program']").val();
        let id_part = $("select[name='part']").val();
        $("select[name='kegiatan']").val("").trigger("change");
        $("select[name='kegiatan']").select2({
            // minimumInputLength: 3,
            allowClear: false,
            ajax: {
                url: `${_uri}/app/select2/ajaxKegiatan`,
                type: 'post',
                dataType: 'json',
                delay: 200,
                data: function(params) {
                    return {
                        searchTerm: params.term,
                        refId: id_program,
                        refPart: id_part
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: false
            },
            templateResult: formatResults,
            // templateSelection: formatResults
        })
    });

    $("select[name='kegiatan']").on('change', function(){
        let id = $(this).val();
        $("select[name='sub_kegiatan']").val('').trigger('change');
        $("select[name='sub_kegiatan']").select2({
            // minimumInputLength: 3,
            allowClear: false,
            ajax: {
                url: `${_uri}/app/select2/ajaxSubKegiatan`,
                type: 'post',
                dataType: 'json',
                delay: 200,
                data: function(params) {
                    return {
                        searchTerm: params.term,
                        refId: id
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: false
            },
            templateResult: formatResults,
            // templateSelection: formatResults
        })
    });
})