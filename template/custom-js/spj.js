$(function () {
	// fungsi get data inbox spj
	async function getInbox() {
		const req = await fetch(`${_uri}/app/spj/inbox`);
		const res = await req.json();
		return res;
	}
	// fungsi mengambil param tab di url
	let tab_active = urlParams.get("tab");

	// Initial load
	$('#inbox').html(`<div class="d-flex justify-content-center align-items-center align-self-center py-4"><img src="${_uri}/template/assets/loader/motion-blur.svg" alt="Loading" class="mr-3" width="40"><h4>Loading data, mohon tunggu.</h4></div>`);
	
	// jika tab inbox aktif
	getInbox().then((data) => {
		if (data.code === 404) {
			$("#inbox").html(
				`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <div class="clearfix"></div><br> "${data.msg}" Silahkan klik tombol buat usul spj</div>`
			);
			NProgress.done();
			return false;
		}
		$("#inbox").html(data.result);
		NProgress.done();
		var spjList = new List("spjList", option);
	});

	$(document).on("click", "#myTab a[href='#inbox']", function (e) {
		let _ = $(this),
			href = _.attr("href");
		// console.log(_.attr('href'))
		const url = new URL(window.location.href);
		url.searchParams.set("tab", href);
		history.pushState({}, "", url);
		NProgress.start();
		getInbox().then((data) => {
			if (data.code === 404) {
				$("#inbox").html(
					`<div class="text-center my-5"><span class="fa fa-folder-open mb-4" style="font-size: 64px"></span> <br> ${data.result} <br> "${data.msg}" Silahkan klik tombol buat usul spj</div>`
				);
				NProgress.done();
				return false;
			}
			$("#inbox").html(data.result);
			NProgress.done();
            var spjList = new List("spjList", option);
		});
	});

    $(document).on("click", "#myTab a[href='#verifikasi']", function (e) {
		let _ = $(this),
			href = _.attr("href");
		// console.log(_.attr('href'))
		const url = new URL(window.location.href);
		url.searchParams.set("tab", href);
		history.pushState({}, "", url);
		tableVerifikasiSpj.ajax.reload();
	});

	$(document).on("click", "#myTab a[href='#selesai']", function (e) {
		let _ = $(this),
			href = _.attr("href");
		// console.log(_.attr('href'))
		const url = new URL(window.location.href);
		url.searchParams.set("tab", href);
		history.pushState({}, "", url);
		tableVerifikasiSpjSelesai.ajax.reload();
	});

	var option = {
		valueNames: ["nama"],
		searchColumns: ["nama", "kode"],
		page: 8,
		pagination: [
			{
				item: "<li class='page-item rounded-0'><a class='page page-link rounded-0' href='#'></a></li>",
			},
		],
	};
});

function HapusUsulan(url) {
	if(confirm('Apakah anda yakin akan menghapus usulan tersebut ?')) {
		$.post(url, {}, function(res) {
			res === 200 ? window.location.reload() : alert('Hapus GAGAL');
		}, 'json');
		return false;
	}
}