function PilihPeriode(id) {
    window.location.replace(`${_uri}/app/capaian?periode=${id}`);
}

var MODAL_FAKTOR = $(".modal-faktor");

function InputFaktor(id) {
    $.getJSON(`${_uri}/app/realisasi/detailRealisasi`, {id: id}, function(res) {
        MODAL_FAKTOR.find("input[name='id']").val(id);
        MODAL_FAKTOR.find("textarea[name='faktor_pendorong']").val(res.faktor_pendorong);
        MODAL_FAKTOR.find("textarea[name='faktor_penghambat']").val(res.faktor_penghambat);
        MODAL_FAKTOR.find("textarea[name='tindak_lanjut']").val(res.tindak_lanjut);
        MODAL_FAKTOR.modal('show');
        console.log(res)
    })
}

$("form#formFaktor").on("submit", function(e) {
    e.preventDefault();
    let _ = $(this),
    data = _.serialize(),
    url = _.attr('action');

    if(_.parsley().isValid()) {
        $.post(url, data, function(res) {
            if(res === 200) {
                window.location.reload();
            }
            // console.log(res);
        }, 'json')
    }

})

MODAL_FAKTOR.on("hidden.bs.modal", function (e) {
    $("form#formFaktor")[0].reset();
    $("form#formFaktor").parsley().reset();
});