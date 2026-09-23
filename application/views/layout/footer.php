    <!-- Bootstrap -->
    <script src="<?= base_url('template/backend/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- FastClick -->
    <script src="<?= base_url('template/backend/vendors/fastclick/lib/fastclick.js') ?>"></script>
    <!-- NProgress -->
    <script src="<?= base_url('template/backend/vendors/nprogress/nprogress.js') ?>"></script>
    <!-- jQuery custom content scroller -->
    <script src="<?= base_url('template/backend/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') ?>"></script>
    <!-- Js Notify -->
    <script src="<?= base_url('template/custom-js/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>
    <!-- Bootstrap Progressbar -->
    <script src="<?= base_url('template/backend/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') ?>"></script>
    <!-- Custom Theme Scripts -->
    <script src="<?= base_url('template/backend/build/js/custom.js') ?>"></script>
    <script src="<?= base_url('template/custom-js/admin.js') ?>"></script>

    <?php
    if (isset($autoload_js)) {
      foreach ($autoload_js as $script):
        echo tagscript($script);
      endforeach;
    }
    ?>

    <script>
    (function () {
        var canvas = document.getElementById('chartBelanjaHarian');
        if (!canvas || typeof Chart === 'undefined') return;

        var chartData = JSON.parse(canvas.getAttribute('data-chart') || '{}');
        chartData.usulan = chartData.usulan || [];
        chartData.verifikasi = chartData.verifikasi || [];
        chartData.cair = chartData.cair || [];

        // Generate all dates in filter range (inclusive)
        var allDates = [];
        if (chartData.range && chartData.range.start && chartData.range.end) {
            var cur = new Date(chartData.range.start + 'T00:00:00');
            var end = new Date(chartData.range.end + 'T00:00:00');
            while (cur <= end) {
                var y = cur.getFullYear();
                var m = ('0' + (cur.getMonth() + 1)).slice(-2);
                var d = ('0' + cur.getDate()).slice(-2);
                allDates.push(y + '-' + m + '-' + d);
                cur.setDate(cur.getDate() + 1);
            }
        } else {
            chartData.usulan.forEach(function (d) { if (allDates.indexOf(d.tanggal) === -1) allDates.push(d.tanggal); });
            chartData.verifikasi.forEach(function (d) { if (allDates.indexOf(d.tanggal) === -1) allDates.push(d.tanggal); });
            chartData.cair.forEach(function (d) { if (allDates.indexOf(d.tanggal) === -1) allDates.push(d.tanggal); });
            allDates.sort();
        }

        function mapData(arr) {
            var map = {};
            arr.forEach(function (d) { map[d.tanggal] = d.total; });
            return allDates.map(function (t) { return map[t] || 0; });
        }

        function formatRp(val) {
            return 'Rp. ' + val.toLocaleString('id-ID');
        }

        var labels = allDates.map(function (d) {
            var parts = d.split('-');
            return parts[2] + '/' + parts[1];
        });

        // No data fallback
        if (labels.length === 0) {
            var ctx = canvas.getContext('2d');
            canvas.width = canvas.parentElement.offsetWidth || 800;
            canvas.height = 200;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.font = '14px Arial';
            ctx.fillStyle = '#999';
            ctx.fillText('Tidak ada data untuk periode ini', canvas.width / 2, canvas.height / 2);
            return;
        }

        // Too many days -> thin bars, hide value labels
        var manyDays = allDates.length > 60;

        var usulanData = mapData(chartData.usulan);
        var verifikasiData = mapData(chartData.verifikasi);
        var cairData = mapData(chartData.cair);

        // Summary cards
        var sumU = usulanData.reduce(function (a, b) { return a + b; }, 0);
        var sumV = verifikasiData.reduce(function (a, b) { return a + b; }, 0);
        var sumC = cairData.reduce(function (a, b) { return a + b; }, 0);
        if (sumU + sumV + sumC > 0) {
            document.getElementById('sumUsulan').textContent = formatRp(sumU);
            document.getElementById('sumVerifikasi').textContent = formatRp(sumV);
            document.getElementById('sumCair').textContent = formatRp(sumC);
            document.getElementById('chartSummary').style.display = '';
        }

        // Datalabels plugin: draw value above each point
        var totalPlugin = {
            afterDatasetsDraw: function (chart) {
                var ctx2 = chart.chart.ctx;
                ctx2.save();
                ctx2.font = '10px Arial';
                ctx2.textAlign = 'center';
                ctx2.textBaseline = 'bottom';
                chart.data.datasets.forEach(function (ds, i) {
                    var meta2 = chart.getDatasetMeta(i);
                    meta2.data.forEach(function (bar, index) {
                        var val = ds.data[index];
                        if (val > 0 && !manyDays) {
                            ctx2.fillStyle = ds.borderColor;
                            ctx2.fillText(formatRp(val), bar.x, bar.y - 8);
                        }
                    });
                });
                ctx2.restore();
            }
        };

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Usulan',
                        data: usulanData,
                        backgroundColor: 'rgba(41,128,185,0.8)',
                        borderColor: '#2980b9',
                        borderWidth: 1
                    },
                    {
                        label: 'Verifikasi',
                        data: verifikasiData,
                        backgroundColor: 'rgba(243,156,18,0.8)',
                        borderColor: '#f39c12',
                        borderWidth: 1
                    },
                    {
                        label: 'Cair',
                        data: cairData,
                        backgroundColor: 'rgba(39,174,96,0.8)',
                        borderColor: '#27ae60',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                barPercentage: manyDays ? 0.5 : 0.8,
                categoryPercentage: manyDays ? 0.6 : 0.8,
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        fontSize: 12,
                        fontStyle: 'bold'
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: { display: false },
                        ticks: { fontSize: 11, fontStyle: 'bold' }
                    }],
                    yAxes: [{
                        gridLines: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            beginAtZero: true,
                            fontSize: 11,
                            callback: function (value) {
                                if (value >= 1000000000) return 'Rp. ' + (value / 1000000000).toFixed(1) + ' M';
                                if (value >= 1000000) return 'Rp. ' + (value / 1000000).toFixed(1) + ' JT';
                                if (value >= 1000) return 'Rp. ' + (value / 1000).toFixed(0) + ' RB';
                                return 'Rp. ' + value;
                            }
                        }
                    }]
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleFontSize: 12,
                    bodyFontSize: 12,
                    cornerRadius: 6,
                    padding: 10,
                    callbacks: {
                        title: function (items, data) {
                            var d = (allDates[items[0].index] || '').split('-');
                            return d.length === 3 ? 'Tanggal ' + d[2] + '/' + d[1] + '/' + d[0] : data.labels[items[0].index];
                        },
                        label: function (item, data) {
                            return ' ' + data.datasets[item.datasetIndex].label + ': ' + formatRp(item.yLabel);
                        },
                        footer: function (items, data) {
                            var total = items.reduce(function (sum, item) { return sum + (item.yLabel || 0); }, 0);
                            return 'Total: ' + formatRp(total);
                        }
                    }
                },
                plugins: [totalPlugin]
            }
        });
    })();
    </script>
    </body>

    </html>