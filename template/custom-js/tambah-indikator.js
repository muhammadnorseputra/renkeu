$.ajaxSetup({ cache: false });
function sendAjax($url, $datas) {
	return $.ajax({
		type: "POST",
		url: $url,
		data: $datas,
		dataType: "json",
	}).done((result) => {
        return result == 1 ? sendAjax($data) : Promise.resolve(result);
    });
}

let $modal = $(".modal-indikator"),
	$form = $("form#formIndikator");

function TambahIndikator(label, id, url) {
	$modal.modal("show");
	$modal.find("#myModalLabel").text(`Indikator ${label}`);
	$modal.on("shown.bs.modal", function (e) {
		$form.attr("action", url);
		$form.on("submit", function (e) {
			e.preventDefault();
			$data = $(this).serializeArray();
			if ($(this).parsley().isValid()) {
				$data.push({ name: "id", value: id });
				// $.post(
				// 	url,
				// 	$data,
				// 	(response) => {
				// 		if (response === 200) {
				// 			window.location.reload();
				// 		}
				// 	},
				// 	"json"
				// );
                sendAjax(url, $data).then((result) => {
                    console.log(result)
                    if (result === 200) {
                        window.location.reload();
                    }
                })
			}
		});
	});
}
$modal.on("hidden.bs.modal", function (e) {
	$form[0].reset();
	$form.parsley().reset();
});
