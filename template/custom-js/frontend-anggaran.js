// Transparansi Anggaran — chart publik. Chart.js 2.x: sintaks v2.
(function () {
	if (typeof Chart === "undefined") return;

	function fmtRp(v) { return "Rp. " + Number(v).toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); }
	function shortRp(v) {
		if (v >= 1e9) return "Rp. " + (v / 1e9).toFixed(1) + " M";
		if (v >= 1e6) return "Rp. " + (v / 1e6).toFixed(1) + " JT";
		if (v >= 1e3) return "Rp. " + (v / 1e3).toFixed(0) + " RB";
		return "Rp. " + v;
	}
	function pad(n) { return n < 10 ? "0" + n : "" + n; }

	// Inline plugin: gambar nilai titik Usulan/Cair langsung di chart
	var dataLabelsPlugin = {
		id: "pointValueLabels",
		afterDatasetsDraw: function (chart) {
			var ctx = chart.ctx;
			var colors = ["#8d6e00", "#0e7a5f"];
			ctx.save();
			ctx.font = 'bold 8px "Open Sans", sans-serif';
			ctx.textAlign = "center";
			ctx.lineWidth = 2;
			for (var d = 0; d < chart.data.datasets.length; d++) {
				var meta = chart.getDatasetMeta(d);
				for (var i = 0; i < meta.data.length; i++) {
					var v = chart.data.datasets[d].data[i];
					if (!v) continue;
					var el = meta.data[i];
					var x = el._view.x;
					var y = el._view.y;
					var stagger = (i % 2 === 0) ? 0 : -4;
					var ly = d === 0 ? y - 10 + stagger : y + 18 - stagger;
					var label = shortRp(v);
					var w = ctx.measureText(label).width;
					ctx.strokeStyle = "rgba(255,255,255,0.8)";
					ctx.strokeText(label, x, ly);
					ctx.fillStyle = colors[d];
					ctx.fillText(label, x, ly);
				}
			}
			ctx.restore();
		}
	};

	var harianEl = document.getElementById("chartBelanjaHarian");
	if (!harianEl) return;

	var harian = JSON.parse(harianEl.getAttribute("data-chart") || "{}");
	var tahun = parseInt(harianEl.getAttribute("data-tahun") || String(new Date().getFullYear()), 10);
	var bulanAktif = new Date().getMonth() + 1;

	var perHari = {};
	(harian.tanggal || []).forEach(function (dt, i) {
		perHari[dt.substring(0, 10)] = {
			usulan: Number((harian.usulan || [])[i] || 0),
			cair: Number((harian.cair || [])[i] || 0),
		};
	});

	function hariDalamBulan(bln) { return new Date(tahun, bln, 0).getDate(); }

	var chart = new Chart(harianEl, {
		type: "line",
		plugins: [dataLabelsPlugin],
		data: {
			labels: [],
			datasets: [
				{ label: "Usulan", data: [], borderColor: "#f1c40f", backgroundColor: "rgba(241,196,15,0.15)", borderWidth: 2, pointRadius: 3, fill: false },
				{ label: "Cair", data: [], borderColor: "#16a085", backgroundColor: "rgba(22,160,133,0.15)", borderWidth: 2, pointRadius: 3, fill: false },
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

	function renderBulan(bln) {
		var akhir = hariDalamBulan(bln);
		var labels = [], usulan = [], cair = [];

		for (var d = 1; d <= akhir; d++) {
			var dt = tahun + "-" + pad(bln) + "-" + pad(d);
			var r = perHari[dt] || { usulan: 0, cair: 0 };
			labels.push("Tgl " + d);
			usulan.push(r.usulan);
			cair.push(r.cair);
		}

		chart.data.labels = labels;
		chart.data.datasets[0].data = usulan;
		chart.data.datasets[1].data = cair;
		chart.update();
	}

	function onBulanChange() {
		var sel = document.getElementById("filterBulanHarian");
		renderBulan(parseInt(sel.value, 10));
	}

	renderBulan(bulanAktif);

	var elBulan = document.getElementById("filterBulanHarian");
	var $elBulan = window.jQuery ? window.jQuery(elBulan) : null;

	if ($elBulan && window.jQuery.fn.select2) {
		$elBulan.select2({ width: 180, minimumResultsForSearch: -1, dropdownAutoWidth: true });
		$elBulan.on("change", onBulanChange);
	} else {
		elBulan.addEventListener("change", onBulanChange);
	}
})();
