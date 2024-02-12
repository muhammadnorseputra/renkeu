function PilihPeriode(id) {
    window.location.replace(`${_uri}/app/realisasi?periode=${id}`);
}

function InputRealisasi(id) {
    let $modal = $(".modal-realisasi"),
    _ = $(this);
    $.getJSON(`${_uri}/app/realisasi/detailIndikator`, {id: id}, function(res) {
        $modal.modal('show')
        $modal.find("textarea[name='nama']").val(res.indikator.nama);
        $modal.find("input[name='id']").val(res.indikator.id);
        $modal.find("input[name='persentase']").val(res.realisasi.persentase);
        $modal.find("input[name='jumlah_eviden']").val(res.realisasi.eviden);
        $modal.find("input[name='keterangan_eviden']").val(res.realisasi.eviden_jenis);
    })
}

$("form#formRealisasi").on("submit", function(e) {
    e.preventDefault();
    let _ = $(this),
    data = _.serialize(),
    url = _.attr('action');

    if(_.parsley().isValid()) {
        $.post(url, data, function(res) {
            if(res === 200) {
                window.location.reload();
            }
        }, 'json')
    }

})

$(".modal-realisasi").on("hidden.bs.modal", function (e) {
    $("form#formRealisasi")[0].reset();
    $("form#formRealisasi").parsley().reset();
});