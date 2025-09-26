var intro = introJs();
var modalInfoProfile = $("#modalInfoProfile");

async function cekProfile() {
	const response = await fetch(`${_uri}/app/dashboard/cekProfile`, {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
	});
	const data = await response.json(); // Asumsi respons JSON
	return data;
}

// Jalankan setelah halaman selesai diload
document.addEventListener("DOMContentLoaded", async () => {
	try {
		const data = await cekProfile();
		if (!data.status) {
			modalInfoProfile.find(".modal-body").html(data.data);
			modalInfoProfile.modal("show"); // pakai jQuery bootstrap modal
		}
		console.log(data);
	} catch (error) {
		console.error("Gagal cek profile:", error);
	}
});


var options = {
	nextLabel: "Selanjutnya",
	prevLabel: "Sebelumnya",
	doneLabel: "Selesai",
	dontShowAgainLabel: "Jangan lihat ini lagi.",
	dontShowAgain: true,
};

intro.setOptions({
	...options,
	steps: [
		{
			title: "Dashboard Overview",
			intro: "Welcome to the dashboard tour! 👋",
		},
		{
			title: "Tahun Anggaran",
			element: document.querySelector("#tahun_anggaran"),
			intro: "Tahun anggaran aktif saat ini yang sedang berjalan.",
		},
		{
			title: "Status Perubahan",
			element: document.querySelector("#is_perubahan"),
			intro: "Status perubahan anggaran saat ini.",
		},
		{
			title: "Profile User",
			element: document.querySelector("#profile"),
			intro: "Informasi pengguna aplikasi.",
		},
		{
			title: "Indikator Anggaran dan Kinerja",
			element: document.querySelector("#panel"),
			intro:
				"Indikator Anggaran dan Kinerja, Terdapat Target, Realisasi, Indikator, dan Capaian.",
			position: "bottom",
		},
		{
			title: "Charts",
			element: document.querySelector("#tour_chart_transaksi"),
			intro: "Grafik trend realisasi anggaran.",
			position: "bottom",
		},
		{
			title: "Charts Part",
			element: document.querySelector("#tour_chart_part"),
			intro: "Realisasi Terbaru Berdasarkan Bidang / Bagian",
			position: "bottom",
			scrollTo: "tooltip",
		},
		{
			title: "Navigasi Aplikasi",
			element: document.querySelector("#tour_navbar"),
			intro: "Navigasi aplikasi untuk mengakses fitur-fitur utama.",
			position: "right",
			scrollTo: "tooltip",
		},
	],
});
intro
	.onbeforechange(async () => {
		return new Promise((resolve) => {
			console.log("Performing I/O...");
			setInterval(resolve, 500);
		});
	})
	.start();
