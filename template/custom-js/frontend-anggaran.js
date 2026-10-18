// Transparansi Anggaran — chart publik. Chart.js 2.1.4 (bundle backend): sintaks v2.
(function () {
	if (typeof Chart === "undefined") return;
	function fmtRp(v) { return "Rp. " + Number(v).toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
	function shortRp(v) {
		if (v >= 1e9) return "Rp. " + (v / 1e9).toFixed(1) + " M";
		if (v >= 1e6) return "Rp. " + (v / 1e6).toFixed(1) + " JT";
		if (v >= 1e3) return "Rp. " + (v / 1e3).toFixed(0) + " RB";
		return "Rp. " + v;
	}
	var harianEl = document.getElementById("chartBelanjaHarian");
	if (!harianEl) return;
	var harian = JSON.parse(harianEl.getAttribute("data-chart") || "{}");
	new Chart(harianEl, {
		type: "line",
		data: {
			labels: harian.tanggal || [],
			datasets: [
				{ label: "Usulan", data: harian.usulan || [], borderColor: "#f1c40f", backgroundColor: "rgba(241,196,15,0.15)", borderWidth: 2, pointRadius: 3, fill: false },
				{ label: "Cair", data: harian.cair || [], borderColor: "#16a085", backgroundColor: "rgba(22,160,133,0.15)", borderWidth: 2, pointRadius: 3, fill: false },
			],
		},
		options: {
			responsive: true, maintainAspectRatio: false,
			legend: { position: "top", labels: { fontSize: 11 } },
			tooltips: { mode: "index", intersect: false, callbacks: { label: function (item, chartData) { return chartData.datasets[item.datasetIndex].label + ": " + fmtRp(item.yLabel); } } },
			scales: {
				xAxes: [{ ticks: { fontSize: 9, maxRotation: 45, minRotation: 45 }, gridLines: { display: false } }],
				yAxes: [{ ticks: { beginAtZero: true, fontSize: 10, callback: shortRp }, gridLines: { color: "#f1f5f9" } }],
			},
		},
	});
})();
