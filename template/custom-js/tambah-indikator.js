function TambahIndikator(label, id, url) {
    let $modal = $(".modal-indikator"),
        $form  = $("form#formIndikator");
    $modal.modal('show');
    $modal.find("#myModalLabel").text(`Indikator ${label}`);
    $modal.on('shown.bs.modal', function(e) {
        $form.attr("action", url);
        $form.on("submit", function(e) {
            e.preventDefault();
            $data = $(this).serializeArray();
            if ($(this).parsley().isValid()) {
                $data.push({ "name": "id", "value": id });
                $.post(url, $data, (response) => {
                    if (response === 200) {
                        window.location.reload();
                    }
                }, 'json');
            }
        })
    })
}