// Transparansi Anggaran — chart publik (Pagu vs Realisasi).
// Chart.js 2.1.4 (bundle backend): sintaks v2.
(function () {
	var el = document.getElementById("pagu-chart-data");
	if (!el || typeof Chart === "undefined") return;

	var data = JSON.parse(el.textContent || "{}");
	var labels = data.labels || [];
	var pagu = data.pagu || [];
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

	var barCtx = document.getElementById("chartPaguKelompok");
	if (barCtx) {
		new Chart(barCtx, {
			type: "bar",
			data: {
				labels: labels,
				datasets: [
					{
						label: "Pagu",
						data: pagu,
						backgroundColor: "#e2e8f0",
						borderColor: "#cbd5e1",
						borderWidth: 1,
					},
					{
						label: "Realisasi",
						data: real,
						backgroundColor: "#16a085",
						borderColor: "#148f77",
						borderWidth: 1,
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
							ticks: { fontSize: 10, maxRotation: 45, minRotation: 45 },
							gridLines: { display: false },
						},
					],
					yAxes: [
						{
							ticks: {
								beginAtZero: true,
								fontSize: 10,
								callback: shortRp,
							},
							gridLines: { color: "#f1f5f9" },
						},
					],
				},
			},
		});
	}

	var doughCtx = document.getElementById("chartSerapanAnggaran");
	if (doughCtx) {
		var totalPagu = pagu.reduce(function (a, b) {
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
})();
