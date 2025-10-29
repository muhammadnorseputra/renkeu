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
				`<div class="text-center my-5"><i class="fa fa-folder-open mb-4" style="font-size: 64px"></i> <br> <div class="clearfix"></div><br> "${data.msg}" Silahkan klik tombol buat usul spj</div>
				<div class="row d-flex justify-content-center">
					${data.result}
				</div>`
			);
			NProgress.done();
			return false;
		}
		$("#inbox").html(data.result);
		NProgress.done();
		var spjList = new List("spjList", option);
	});

	$(document).on("click", "#myTab a[href='#inbox']", async function (e) {
		let _ = $(this),
			href = _.attr("href");
		// console.log(_.attr('href'))
		const url = new URL(window.location.href);
		url.searchParams.set("tab", href);
		history.pushState({}, "", url);
		NProgress.start();
		await getInbox().then((data) => {
			if (data.code === 404) {
				$("#inbox").html(
					`<div class="text-center my-5">
						<span class="fa fa-folder-open mb-4" style="font-size: 64px"></span>
						<br> <br> "${data.msg}" Silahkan klik tombol buat usul spj
					</div>
					<div class="row d-flex justify-content-center">
						${data.result}
					</div>
					`
				);
				NProgress.done();
				return false;
			}
			$("#inbox").html(data.result);
			NProgress.done();
            var spjList = new List("spjList", option);
		});
	});

    $(document).on("click", "#myTab a[href='#verifikasi']", async function (e) {
		let _ = $(this),
			href = _.attr("href");
		// console.log(_.attr('href'))
		const url = new URL(window.location.href);
		url.searchParams.set("tab", href);
		history.pushState({}, "", url);
		await tableVerifikasiSpj.ajax.reload();
	});

	$(document).on("click", "#myTab a[href='#selesai']", async function (e) {
		let _ = $(this),
			href = _.attr("href");
		// console.log(_.attr('href'))
		const url = new URL(window.location.href);
		url.searchParams.set("tab", href);
		history.pushState({}, "", url);
		await tableVerifikasiSpjSelesai.ajax.reload();
	});

	$(document).on("click", "#myTab a[href='#payment']", async function (e) {
		let _ = $(this),
			href = _.attr("href");
		// console.log(_.attr('href'))
		const url = new URL(window.location.href);
		url.searchParams.set("tab", href);
		history.pushState({}, "", url);
		await tabelPayment.ajax.reload();
	});

	var option = {
		valueNames: ["nama"],
		searchColumns: ["nama", "kode"],
		page: 10,
		pagination: [
			{
				item: "<li class='page-item rounded-0'><a class='page page-link rounded-0' href='#'></a></li>",
			},
		],
	};
});

async function LogHistoris(token) {
	// tampilkan loading di modal-body
	$("#modalLogHistoris .modal-body").html(
		"<div class='text-center p-3'>Loading...</div>"
	);
	// buka modal lebih awal agar user lihat proses loading
	$("#modalLogHistoris").modal("show");

	try {
		const req = await fetch(`${_uri}/app/spj/log_historis/${token}`);
		const res = await req.json();

		// ganti loading dengan data hasil request
		$("#modalLogHistoris .modal-body").html(res.result);
	} catch (error) {
		// tampilkan pesan error jika gagal
		$("#modalLogHistoris .modal-body").html(
			`<div class='text-danger p-3'>Terjadi kesalahan saat memuat data. (${error.message})</div>`
		);
	}
}

async function HapusUsulan(url) {
	if (confirm("Apakah anda yakin akan menghapus usulan tersebut ?")) {
		try {
			const req = await fetch(url, {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded",
				},
				body: "", // sama seperti $.post(url, {}) → kirim body kosong
			});

			const res = await req.json();

			if (res !== 200)
			{
				alert(res.msg || "Hapus GAGAL");
				return false;
			}

			window.location.reload();
			
		} catch (err) {
			alert("Terjadi kesalahan: " + err.message);
		}
		return false;
	}
}
