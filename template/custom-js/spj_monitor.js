$("#filter_tanggal").daterangepicker({
	showDropdowns: false,
	autoApply: true,
	drops: "auto",
	opens: "right",
	autoUpdateInput: false,
	locale: {
		format: "DD/MM/YYYY",
		separator: " - ",
		cancelLabel: "Clear",
	},
	ranges: {
		Today: [moment(), moment()],
		Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
		"Last 7 Days": [moment().subtract(6, "days"), moment()],
		"Last 30 Days": [moment().subtract(29, "days"), moment()],
		"This Month": [moment().startOf("month"), moment().endOf("month")],
		"Last Month": [
			moment().subtract(1, "month").startOf("month"),
			moment().subtract(1, "month").endOf("month"),
		],
	},
});

$("#filter_tanggal").on("apply.daterangepicker", function (ev, picker) {
	$(this).val(
		picker.startDate.format("DD/MM/YYYY") +
			" - " +
			picker.endDate.format("DD/MM/YYYY"),
	);
	// auto-submit: chart & cards update by filter
	$(this).closest("form").submit();
});

$("#filter_tanggal").on("cancel.daterangepicker", function (ev, picker) {
	$(this).val("");
});

// Chart Belanja Harian (line smooth, toggle status CAIR / USUL)
(function () {
	var canvas = document.getElementById("chartBelanjaHarian");
	if (!canvas || typeof Chart === "undefined") return;

	var chartData = JSON.parse(canvas.getAttribute("data-chart") || "{}");
	chartData.cair = chartData.cair || [];
	chartData.usulan = chartData.usulan || [];
	chartData.pending = chartData.pending || [];

	var STATUS = {
		cair: {
			label: "Cair",
			color: "#27ae60",
			fill: "rgba(39,174,96,0.15)",
			tip: "#7ee2a8",
			summaryLabel: "Total Cair",
			summaryClass: "text-success",
		},
		usulan: {
			label: "Usulan",
			color: "#2980b9",
			fill: "rgba(41,128,185,0.15)",
			tip: "#7fbde8",
			summaryLabel: "Total Usulan",
			summaryClass: "text-info",
		},
		pending: {
			label: "Pending",
			color: "#e67e22",
			fill: "rgba(230,126,34,0.15)",
			tip: "#f5b07c",
			summaryLabel: "Total Pending",
			summaryClass: "text-warning",
		},
	};
	var currentStatus = "cair";

	// Generate all dates in filter range (inclusive), else union dataset dates
	var allDates = [];
	function pushDate(d) {
		if (d && allDates.indexOf(d) === -1) allDates.push(d);
	}
	if (chartData.range && chartData.range.start && chartData.range.end) {
		var cur = new Date(chartData.range.start + "T00:00:00");
		var end = new Date(chartData.range.end + "T00:00:00");
		while (cur <= end) {
			var y = cur.getFullYear();
			var m = ("0" + (cur.getMonth() + 1)).slice(-2);
			var dd = ("0" + cur.getDate()).slice(-2);
			pushDate(y + "-" + m + "-" + dd);
			cur.setDate(cur.getDate() + 1);
		}
	} else {
		chartData.cair.forEach(function (d) {
			pushDate(d.tanggal);
		});
		chartData.usulan.forEach(function (d) {
			pushDate(d.tanggal);
		});
		chartData.pending.forEach(function (d) {
			pushDate(d.tanggal);
		});
		allDates.sort();
	}

	function mapData(arr) {
		var map = {};
		arr.forEach(function (d) {
			map[d.tanggal] = d.total;
		});
		return allDates.map(function (t) {
			return map[t] || 0;
		});
	}

	function formatRp(val) {
		return "Rp. " + val.toLocaleString("id-ID");
	}

	var labels = allDates.map(function (d) {
		var parts = d.split("-");
		return parts[2] + "/" + parts[1];
	});

	// No data fallback
	if (labels.length === 0) {
		var ctx = canvas.getContext("2d");
		canvas.width = canvas.parentElement.offsetWidth || 800;
		canvas.height = 200;
		ctx.textAlign = "center";
		ctx.textBaseline = "middle";
		ctx.font = "14px Arial";
		ctx.fillStyle = "#999";
		ctx.fillText(
			"Tidak ada data untuk periode ini",
			canvas.width / 2,
			canvas.height / 2,
		);
		return;
	}

	// Too many days -> hide value labels
	var manyDays = allDates.length > 60;

	var dataByStatus = {
		cair: mapData(chartData.cair),
		usulan: mapData(chartData.usulan),
		pending: mapData(chartData.pending),
	};

	// Summary card
	var sumEl = document.getElementById("sumCair");
	var sumLabel = document.getElementById("sumLabel");
	var sumWrap = document.getElementById("chartSummary");

	function updateSummary() {
		var total = dataByStatus[currentStatus].reduce(function (a, b) {
			return a + b;
		}, 0);
		var meta = STATUS[currentStatus];
		if (sumEl) {
			sumEl.textContent = formatRp(total);
			sumEl.className = "mb-0 " + meta.summaryClass;
		}
		if (sumLabel) sumLabel.textContent = meta.summaryLabel;
		if (sumWrap) sumWrap.style.display = total > 0 ? "" : "none";
	}

	// Datalabels plugin: draw value above each point
	var totalPlugin = {
		afterDatasetsDraw: function (chart) {
			var ctx2 = chart.chart.ctx;
			ctx2.save();
			ctx2.font = "10px Arial";
			ctx2.textAlign = "center";
			ctx2.textBaseline = "bottom";
			chart.data.datasets.forEach(function (ds, i) {
				var meta2 = chart.getDatasetMeta(i);
				meta2.data.forEach(function (pt, index) {
					var val = ds.data[index];
					if (val > 0 && !manyDays) {
						ctx2.fillStyle = ds.borderColor;
						ctx2.fillText(formatRp(val), pt.x, pt.y - 8);
					}
				});
			});
			ctx2.restore();
		},
	};

	// Smooth tooltip element (append to body, like Chart.js sample)
	var tooltipEl = document.getElementById("chartTooltip");
	if (!tooltipEl) {
		tooltipEl = document.createElement("div");
		tooltipEl.id = "chartTooltip";
		tooltipEl.style.cssText = "position:absolute;display:none;background:rgba(0,0,0,0.85);color:#fff;border-radius:10px;padding:12px 16px;font-size:13px;pointer-events:none;z-index:9999;box-shadow:0 4px 12px rgba(0,0,0,0.25);transition:all .15s ease;white-space:nowrap;line-height:1.5;";
		document.body.appendChild(tooltipEl);
	}

	var chart = new Chart(canvas, {
		type: "line",
		data: {
			labels: labels,
			datasets: [
				{
					label: STATUS[currentStatus].label,
					data: dataByStatus[currentStatus],
					borderColor: STATUS[currentStatus].color,
					backgroundColor: STATUS[currentStatus].fill,
					borderWidth: 3,
					pointRadius: 4,
					pointHoverRadius: 6,
					pointBackgroundColor: STATUS[currentStatus].color,
					pointBorderColor: "#fff",
					pointBorderWidth: 2,
					fill: true,
					tension: 0.4,
				},
			],
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			animation: { duration: 600, easing: "easeOutQuart" },
			legend: { display: false },
			scales: {
				xAxes: [
					{
						gridLines: { display: false },
						ticks: { fontSize: 11, fontStyle: "bold" },
					},
				],
				yAxes: [
					{
						gridLines: {
							color: "rgba(0,0,0,0.05)",
							drawBorder: false,
						},
						ticks: {
							beginAtZero: true,
							fontSize: 11,
							callback: function (value) {
								if (value >= 1000000000)
									return "Rp. " + (value / 1000000000).toFixed(1) + " M";
								if (value >= 1000000)
									return "Rp. " + (value / 1000000).toFixed(1) + " JT";
								if (value >= 1000)
									return "Rp. " + (value / 1000).toFixed(0) + " RB";
								return "Rp. " + value;
							},
						},
					},
				],
			},
			tooltips: {
				enabled: false,
				mode: "label",
				intersect: false,
				callbacks: {
					title: function (items) {
						var d = (allDates[items[0].index] || "").split("-");
						return d.length === 3
							? "Tanggal " + d[2] + "/" + d[1] + "/" + d[0]
							: "";
					},
					label: function (item) {
						return STATUS[currentStatus].label + ": " + formatRp(item.yLabel);
					},
				},
				// Chart.js 2.1.4: custom, bukan external
				custom: function (tooltip) {
					if (!tooltip.opacity) {
						tooltipEl.style.opacity = "0";
						tooltipEl.style.display = "none";
						return;
					}
					tooltipEl.innerHTML =
						'<div style="font-weight:700;margin-bottom:6px;border-bottom:1px solid rgba(255,255,255,0.2);padding-bottom:6px;">' +
						(tooltip.title || []).join("") +
						"</div>" +
						'<div style="color:' +
						STATUS[currentStatus].tip +
						';"><b>' +
						(tooltip.body || []).join("") +
						"</b></div>";
					tooltipEl.style.display = "block";
					tooltipEl.style.opacity = "1";
					var pos = this._chart.canvas.getBoundingClientRect();
					tooltipEl.style.left = pos.left + window.pageXOffset + tooltip.x + "px";
					tooltipEl.style.top =
						pos.top + window.pageYOffset + tooltip.y + "px";
					tooltipEl.style.transform =
						"translate(-50%," + (tooltip.yAlign === "top" ? "0" : "-100%") + ")";
				},
			},
			plugins: [totalPlugin],
		},
	});

	// Toggle status CAIR / USUL (animasi smooth via chart.update())
	var toggleBtns = document.querySelectorAll(
		"#chartStatusToggle button[data-status]",
	);

	function setStatus(status) {
		if (!STATUS[status] || status === currentStatus) return;
		currentStatus = status;
		var meta = STATUS[status];
		for (var i = 0; i < toggleBtns.length; i++) {
			var b = toggleBtns[i];
			b.classList.toggle(
				"active",
				b.getAttribute("data-status") === status,
			);
		}
		var ds = chart.data.datasets[0];
		ds.data = dataByStatus[status];
		ds.label = meta.label;
		ds.borderColor = meta.color;
		ds.backgroundColor = meta.fill;
		ds.pointBackgroundColor = meta.color;
		chart.update(); // animasikan transisi data
		updateSummary();
	}

	for (var i = 0; i < toggleBtns.length; i++) {
		toggleBtns[i].addEventListener("click", function () {
			setStatus(this.getAttribute("data-status"));
		});
	}

	updateSummary();
})();

// Sticky header tabel: top = bawah navbar sticky + tinggi baris header di atasnya (per tabel)
(function () {
	function stickyBottom() {
		var b = 0;
		var els = document.querySelectorAll(".sticky-top");
		for (var i = 0; i < els.length; i++) {
			var r = els[i].getBoundingClientRect();
			if (r.bottom > b && r.top <= 0) b = r.bottom;
		}
		if (!b) {
			var n = document.querySelector(".top_nav .nav_menu");
			b = n ? n.getBoundingClientRect().bottom : 0;
		}
		return b;
	}

	function applySticky() {
		var tops = document.querySelectorAll(".x_panel .table thead");
		for (var t = 0; t < tops.length; t++) {
			var rows = tops[t].rows;
			var top = stickyBottom();
			for (var i = 0; i < rows.length; i++) {
				var ths = rows[i].children;
				for (var k = 0; k < ths.length; k++) ths[k].style.top = top + "px";
				top += rows[i].getBoundingClientRect().height;
			}
		}
	}

	applySticky();
	setTimeout(applySticky, 300);
	$(window).on("resize", applySticky);
	$(window).on("scroll", applySticky);
})();
