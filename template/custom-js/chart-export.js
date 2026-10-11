// Export chart (Flot / Chart.js) menjadi gambar PNG/JPG.
// Pakai: <a class="export-chart" data-target="idElemen" data-format="png|jpg">Export</a>
// atau exportChart(document.getElementById("id"), "nama-file", "png");
(function () {
	function pad(n) {
		return n < 10 ? "0" + n : n;
	}
	function stamp() {
		var d = new Date();
		return (
			d.getFullYear() +
			pad(d.getMonth() + 1) +
			pad(d.getDate()) +
			"-" +
			pad(d.getHours()) +
			pad(d.getMinutes())
		);
	}

	function exportChart(el, name, format) {
		var cs = Array.prototype.slice.call(
			(el || document).querySelectorAll("canvas"),
		);
		if (!cs.length) {
			if (typeof uiAlert === "function")
				uiAlert("Tidak ada chart untuk diexport.");
			return;
		}
		var gap = 16;
		var w = 0,
			h = 0;
		cs.forEach(function (c) {
			w += c.width;
			if (c.height > h) h = c.height;
		});
		w += gap * (cs.length - 1);

		var out = document.createElement("canvas");
		out.width = w;
		out.height = h;
		var ctx = out.getContext("2d");
		ctx.fillStyle = "#fff";
		ctx.fillRect(0, 0, w, h);
		var x = 0;
		cs.forEach(function (c) {
			ctx.drawImage(c, x, Math.round((h - c.height) / 2));
			x += c.width + gap;
		});

		var mime =
			format === "jpg" || format === "jpeg" ? "image/jpeg" : "image/png";
		var ext = format === "jpg" || format === "jpeg" ? "jpg" : "png";

		var a = document.createElement("a");
		a.download = (name || "chart") + "-" + stamp() + "." + ext;
		a.href = out.toDataURL(mime, 0.92);
		document.body.appendChild(a);
		a.click();
		a.remove();
	}

	$(document).on("click", ".export-chart", function (e) {
		e.preventDefault();
		var id = $(this).data("target");
		var fmt = $(this).data("format") || "png";
		exportChart(document.getElementById(id), id, fmt);
	});

	window.exportChart = exportChart;
})();
