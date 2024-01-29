$(function() {
    $('#tanggal').datetimepicker({
        format: 'DD-MM-YYYY'
    });
    $("form#formVerifikasi").on("submit", function (e) {
        e.preventDefault();
        let _ = $(this),
            action = _.attr("action"),
            data = _.serialize(),
            status = _.find("input[name='status']").val();
            let msg = `Apakah anda yakin akan ${status} usulan tersebut ?`;
        if (_.parsley().isValid()) {
            if(confirm(msg)) {
                $.post(
                    action,
                    data,
                    function (res) {
                        alert(res.pesan)
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

    let INDIKATOR = $("select[name='indikator']"),
        REALISASI = $("#rincian_indikator");
    if(INDIKATOR.val() != "" && INDIKATOR.val() != "TIDAK") {
        REALISASI.css({display: "block"});
    } else {
        $("input[name='persentase']").attr('data-parsley-excluded', true);
        $("input[name='eviden']").attr('data-parsley-excluded', true);
        $("input[name='jenis_eviden']").attr('data-parsley-excluded', true);
    }

    INDIKATOR.on("change", function() {
        let _ = $(this);
        if(_.val() != "" && _.val() != "TIDAK") {
            REALISASI.css({display: "block"});
            $("input[name='persentase']").attr('data-parsley-excluded', false);
            $("input[name='eviden']").attr('data-parsley-excluded', false);
            $("input[name='jenis_eviden']").attr('data-parsley-excluded', false);
            return false;
        }
        REALISASI.css({display: "none"});
        $("input[name='persentase']").attr('data-parsley-excluded', true);
        $("input[name='eviden']").attr('data-parsley-excluded', true);
        $("input[name='jenis_eviden']").attr('data-parsley-excluded', true);
    });
})