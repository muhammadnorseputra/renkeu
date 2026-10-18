// Transparansi Anggaran — chart publik (Pagu vs Realisasi).
// Chart.js 2.1.4 (bundle backend): sintaks v2.
(function () {
	var el = document.getElementById("pagu-chart-data");
	if (!el || typeof Chart === "undefined") return;

	var data = JSON.parse(el.textContent || "{}");
	var labels = data.labels || [];
	var paguMurni = data.pagu_murni || [];
	var paguPerubahan = data.pagu_perubahan || [];
	var real = data.realisasi || [];

	function fmtRp(v) {
		return "Rp. " + Number(v).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}

	function shortRp(v) {
		if (v >= 1e9) return "Rp. " + (v / 1e9).toFixed(1) + " M";
		if (v >= 1e6) return "Rp. " + (v / 1e6).toFixed(1) + " JT";
		if (v >= 1e3) return "Rp. " + (v / 1e3).toFixed(0) + " RB";
		return "Rp. " + v;
	}

	var doughCtx = document.getElementById("chartSerapanAnggaran");
	if (doughCtx) {
		var totalPagu = paguMurni.concat(paguPerubahan).reduce(function (a, b) {
			return a + b;
		}, 0);
		var totalReal = real.reduce(function (a, b) {
			return a + b;
		}, 0);
		var sisa = totalPagu - totalReal;

		new Chart(doughCtx, {
			type: "doughnut",
			data: {
				labels: ["Realisasi", "Sisa Pagu"],
				datasets: [
					{
						data: [totalReal, sisa > 0 ? sisa : 0],
						backgroundColor: ["#16a085", "#e2e8f0"],
						borderColor: ["#fff", "#fff"],
						borderWidth: 2,
					},
				],
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				cutoutPercentage: 70,
				legend: { position: "bottom", labels: { fontSize: 11 } },
				tooltips: {
					callbacks: {
						label: function (item, chartData) {
							return chartData.labels[item.index] + ": " + fmtRp(item.yLabel);
						},
					},
				},
			},
		});
	}

	// Chart Belanja Harian (line)
	var harianEl = document.getElementById("chartBelanjaHarian");
	if (harianEl && typeof Chart !== "undefined") {
		var harian = JSON.parse(harianEl.getAttribute("data-chart") || "{}");
		var tanggal = harian.tanggal || [];
		var usulan = harian.usulan || [];
		var cair = harian.cair || [];

		new Chart(harianEl, {
			type: "line",
			data: {
				labels: tanggal,
				datasets: [
					{
						label: "Usulan",
						data: usulan,
						borderColor: "#f1c40f",
						backgroundColor: "rgba(241,196,15,0.15)",
						borderWidth: 2,
						pointRadius: 3,
						fill: false,
					},
					{
						label: "Cair",
						data: cair,
						borderColor: "#16a085",
						backgroundColor: "rgba(22,160,133,0.15)",
						borderWidth: 2,
						pointRadius: 3,
						fill: false,
					},
				],
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				legend: { position: "top", labels: { fontSize: 11 } },
				tooltips: {
					mode: "index",
					intersect: false,
					callbacks: {
						label: function (item, chartData) {
							return (
								chartData.datasets[item.datasetIndex].label +
								": " +
								fmtRp(item.yLabel)
							);
						},
					},
				},
				scales: {
					xAxes: [
						{
							ticks: { fontSize: 9, maxRotation: 45, minRotation: 45 },
							gridLines: { display: false },
						},
					],
					yAxes: [
						{
							ticks: { beginAtZero: true, fontSize: 10, callback: shortRp },
							gridLines: { color: "#f1f5f9" },
						},
					],
				},
			},
		});
	}

	// Chart per tab: Pagu / Realisasi / Capaian (bar)
	function makeTabChart(id, datasets, yCallback) {
		var ctx = document.getElementById(id);
		if (!ctx) return null;
		return new Chart(ctx, {
			type: "bar",
			data: { labels: labels, datasets: datasets },
			options: {
				responsive: true,
				maintainAspectRatio: false,
				legend: {
					position: "top",
					labels: { fontSize: 11, usePointStyle: true },
				},
				tooltips: {
					mode: "index",
					intersect: false,
					callbacks: {
						label: function (item, chartData) {
							var ds = chartData.datasets[item.datasetIndex];
							if (ds.percent) return item.yLabel.toFixed(1) + "%";
							return ds.label + ": " + fmtRp(item.yLabel);
						},
					},
				},
				scales: {
					xAxes: [
						{
							ticks: {
								fontSize: 9,
								maxRotation: 45,
								minRotation: 45,
							},
							gridLines: { display: false },
						},
					],
					yAxes: [
						{
							ticks: { beginAtZero: true, fontSize: 10, callback: yCallback },
							gridLines: { color: "#f1f5f9" },
						},
					],
				},
			},
		});
	}

	var capaian = labels.map(function (l, i) {
		var aktif = paguPerubahan[i] > 0 ? paguPerubahan[i] : paguMurni[i];
		return aktif > 0 ? (real[i] / aktif) * 100 : 0;
	});

	var tabCharts = {
		"tab-pagu": null,
		"tab-realisasi": null,
		"tab-capaian": null,
	};
	function bindTabChart(paneId, make) {
		var pane = document.getElementById(paneId);
		if (!pane) return;
		pane.addEventListener("shown.bs.tab", function () {
			if (tabCharts[paneId]) {
				tabCharts[paneId].update();
			} else {
				tabCharts[paneId] = make();
			}
		});
	}

	bindTabChart("tab-pagu", function () {
		return makeTabChart("chartPaguKelompok", [
			{ label: "Pagu Murni", data: paguMurni, backgroundColor: "#e2e8f0", borderColor: "#cbd5e1", borderWidth: 1 },
			{ label: "Pagu Perubahan", data: paguPerubahan, backgroundColor: "#f1c40f", borderColor: "#d4ac0d", borderWidth: 1 },
		], shortRp);
	});
	// Tab Pagu aktif sejak awal: render langsung agar canvas tak terlihat 0-tinggi.
	tabCharts["tab-pagu"] = makeTabChart("chartPaguKelompok", [
		{ label: "Pagu Murni", data: paguMurni, backgroundColor: "#e2e8f0", borderColor: "#cbd5e1", borderWidth: 1 },
		{ label: "Pagu Perubahan", data: paguPerubahan, backgroundColor: "#f1c40f", borderColor: "#d4ac0d", borderWidth: 1 },
	], shortRp);
	bindTabChart("tab-realisasi", function () {
		return makeTabChart("chartRealisasiKelompok", [
			{ label: "Realisasi", data: real, backgroundColor: "#16a085", borderColor: "#148f77", borderWidth: 1 },
		], shortRp);
	});
	bindTabChart("tab-capaian", function () {
		return makeTabChart("chartCapaianKelompok", [
			{ label: "Capaian (%)", data: capaian, percent: true, backgroundColor: "#3498db", borderColor: "#2980b9", borderWidth: 1 },
		], function (v) { return v.toFixed(0) + "%"; });
	});
})();
