var dashboardDataElement = document.getElementById('dashboard-chart-data');
if (!dashboardDataElement) {
    console.error('Data chart dashboard tidak tersedia.');
} else {
    var dashboardData = JSON.parse(dashboardDataElement.textContent);

function generateDatasetLabels(chart) {
    return chart.data.datasets.map(function(dataset, index) {
        return {
            text: dataset.label,
            fillStyle: dataset.backgroundColor,
            hidden: !chart.isDatasetVisible(index),
            lineCap: dataset.borderCapStyle,
            lineDash: dataset.borderDash,
            lineDashOffset: dataset.borderDashOffset,
            lineJoin: dataset.borderJoinStyle,
            lineWidth: dataset.borderWidth,
            strokeStyle: dataset.borderColor,
            datasetIndex: index
        };
    });
}

function generateDoughnutLabels(chart) {
    var dataset = chart.data.datasets[0];
    var meta = chart.getDatasetMeta(0);
    if (!dataset || !chart.data.labels) {
        return [];
    }

    return chart.data.labels.map(function(label, index) {
        return {
            text: label,
            fillStyle: Array.isArray(dataset.backgroundColor)
                ? dataset.backgroundColor[index]
                : dataset.backgroundColor,
            strokeStyle: Array.isArray(dataset.borderColor)
                ? dataset.borderColor[index]
                : dataset.borderColor,
            lineWidth: dataset.borderWidth,
            hidden: !meta.data[index] || meta.data[index].hidden,
            index: index
        };
    });
}

if (window.Chart && Chart.defaults && Chart.defaults.global && Chart.defaults.global.legend) {
    Chart.defaults.global.legend.labels = Chart.defaults.global.legend.labels || {};
    Chart.defaults.global.legend.labels.generateLabels = generateDatasetLabels;
}

$(function() {
    // CountUp animation for numeric values (rupiah, pegawai, %)
    document.querySelectorAll('.countup').forEach(function(el) {
        var target = parseFloat(el.getAttribute('data-value')) || 0;
        var decimals = parseInt(el.getAttribute('data-decimals') || '0', 10);
        var fmt = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
        var CountUpCls = (window.countUp && window.countUp.CountUp) || window.CountUp;
        if (!CountUpCls) return;
        var anim = new CountUpCls(el, target, {
            duration: 4.5,
            decimals: decimals,
            separator: '.',
            decimal: ',',
            formattingFn: function(n) {
                return fmt.format(n);
            }
        });
        if (!anim.error) anim.start();
    });

    let SPJMS = {
        label: "Realisasi SPJ MS",
        data: dashboardData['spj_ms'],
        lines: {
            fillColor: "rgba(30, 64, 175, 0.10)",
            lineWidth: 3
        },
        points: {
            fillColor: "#fff",
            lineWidth: 2,
            radius: 4
        }
    };

    let SPJTMS = {
        label: "Realisasi SPJ TMS",
        data: dashboardData['spj_tms'],
        lines: {
            fillColor: "rgba(239, 68, 68, 0.10)",
            lineWidth: 3
        },
        points: {
            fillColor: "#fff",
            lineWidth: 2,
            radius: 4
        }
    };

    let SPJBARU = {
        label: "Realisasi SPJ Baru",
        data: dashboardData['spj_baru'],
        lines: {
            fillColor: "rgba(245, 158, 11, 0.10)",
            lineWidth: 3
        },
        points: {
            fillColor: "#fff",
            lineWidth: 2,
            radius: 4
        }
    };

    let SPJCAIR = {
        label: "Realisasi SPJ Cair",
        data: dashboardData['spj_cair'],
        lines: {
            fillColor: "rgba(34, 197, 94, 0.10)",
            lineWidth: 3
        },
        points: {
            fillColor: "#fff",
            lineWidth: 2,
            radius: 4
        }
    };
    let options = {
        grid: {
            show: true,
            aboveData: true,
            color: "#6b7280",
            labelMargin: 12,
            axisMargin: 0,
            borderWidth: 0,
            minBorderMargin: 10,
            clickable: true,
            hoverable: true,
            autoHighlight: false,
            mouseActiveRadius: 20
        },

        series: {
            lines: {
                show: true,
                fill: true,
                lineWidth: 3,
                steps: false,
                // Smooth / curved line
                curvedLines: {
                    apply: true
                }
            },

            points: {
                show: true,
                radius: 4,
                symbol: "circle",
                lineWidth: 3,
                fill: true
            },

            shadowSize: 0
        },

        legend: {
            position: "ne",
            margin: [10, 10],
            noColumns: 2,
            labelBoxBorderColor: null,

            labelFormatter: function(label) {
                return label + "&nbsp;&nbsp;";
            }
        },

        colors: [
            "#1e40af",
            "#22c55e",
            "#ef4444",
            "#f59e0b",
        ],

        tooltip: {
            cssClass: "flotTip",
            show: true,

            content: function(label, x, y, item) {
                const ticks = item.series.xaxis.ticks;
                const namaBulan = (ticks && ticks[item.dataIndex] && ticks[item.dataIndex].label) || x;
                return `
                <div class="dashboard-chart-tooltip-title">
                    ${label}
                </div>

                <div class="dashboard-chart-tooltip-period">
                    ${namaBulan}
                </div>

                <div class="dashboard-chart-tooltip-value">
                    Rp. ${y.toString().replace(
                        /\B(?=(\d{3})+(?!\d))/g,
                        '.'
                    )}
                </div>
            `;
            }
        },

        yaxis: {
            min: 0,

            tickFormatter: function(v) {
                return "Rp. " + v.toString().replace(
                    /\B(?=(\d{3})+(?!\d))/g,
                    '.'
                );
            }
        },

        xaxis: {
            mode: "categories",
            tickLength: 0
        },

        // Kurangi efek animasi bawaan / shadow
        shadowSize: 0
    };

    // Status toggle for SPJ chart
    var statusMeta = {
        baru: {
            label: "Realisasi SPJ Baru",
            color: "#f59e0b",
            fill: "rgba(245, 158, 11, 0.10)",
            data: SPJBARU.data
        },
        ms: {
            label: "Realisasi SPJ MS",
            color: "#1e40af",
            fill: "rgba(30, 64, 175, 0.10)",
            data: SPJMS.data
        },
        tms: {
            label: "Realisasi SPJ TMS",
            color: "#ef4444",
            fill: "rgba(239, 68, 68, 0.10)",
            data: SPJTMS.data
        },
        cair: {
            label: "Realisasi SPJ Cair",
            color: "#22c55e",
            fill: "rgba(34, 197, 94, 0.10)",
            data: SPJCAIR.data
        }
    };

    function buildSeries(status) {
        var m = statusMeta[status];
        return {
            label: m.label,
            data: m.data,
            color: m.color,
            lines: {
                fillColor: m.fill,
                lineWidth: 3
            },
            points: {
                fillColor: "#fff",
                lineWidth: 2,
                radius: 4
            }
        };
    }

    // Label nilai rupiah di setiap titik data
    options.hooks = options.hooks || {};
    options.hooks.drawOverlay = options.hooks.drawOverlay || [];
    options.hooks.drawOverlay.push(function(plot, ctx) {
        var offset = plot.getPlotOffset();

        ctx.save();
        ctx.translate(offset.left, offset.top);
        ctx.fillStyle = "#263746";
        ctx.font = "bold 10px 'Open Sans', sans-serif";
        ctx.textAlign = "center";
        ctx.textBaseline = "bottom";

        plot.getData().forEach(function(series) {
            var datapoints = series.datapoints;
            if (!datapoints || !datapoints.points || !datapoints.pointsize) return;

            var points = datapoints.points;
            var ps = datapoints.pointsize;
            var axisx = series.xaxis;
            var axisy = series.yaxis;

            for (var i = 0; i < points.length; i += ps) {
                var x = points[i];
                var y = points[i + 1];
                if (x == null || y == null) continue;

                var cx = axisx.p2c(x);
                var cy = axisy.p2c(y);
                var text = "Rp. " + Number(y).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

                // Dekat batas atas plot label ditaruh di bawah titik agar tidak terpotong.
                if (cy < 18) {
                    ctx.textBaseline = "top";
                    ctx.fillText(text, cx, cy + 12);
                } else {
                    ctx.textBaseline = "bottom";
                    ctx.fillText(text, cx, cy - 10);
                }
            }
        });

        ctx.restore();
    });

    var $chart = $("#chart_transaksi");

    function drawChart(status) {
        $.plot($chart, [buildSeries(status)], options);
    }

    drawChart("cair");

    var toggleBtns = $("#chartStatusToggle button[data-status]");
    toggleBtns.click(function() {
        var status = $(this).data("status");
        toggleBtns.removeClass("active");
        $(this).addClass("active");
        drawChart(status);
    });

    $(window).on("resize", function() {
        drawChart($("#chartStatusToggle button.active").data("status"));
    });

    // Pie Charts
    var DataPieParts = {
        labels: dashboardData['part_label'],
        datasets: [{
            data: dashboardData['part_jumlah'],
            backgroundColor: [
                "#455C73",
                "#9B59B6",
                "#BDC3C7",
                "#26B99A",
            ],
            hoverBackgroundColor: [
                "#34495E",
                "#B370CF",
                "#CFD4D8",
                "#36CAAB",
            ],
            hoverOffset: 8
        }],
    };
    var pie = new Chart($(".canvasDoughnutOfParts"), {
        type: "doughnut",
        data: DataPieParts,
        options: {
            responsive: true,
            tooltips: {
                mode: 'single',
                callbacks: {
                    label: (ttItem, items) => (
                        `${items.labels[ttItem.index]}: Rp. ${items.datasets[ttItem.datasetIndex].data[ttItem.index].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}`
                    )
                }
            },
            legend: {
                display: true,
                position: 'top',
                labels: {
                    padding: 15,
                fontColor: '#666',
                generateLabels: generateDoughnutLabels
            }
            }
        }
    });

    // Bar
    const labels = dashboardData['part_label'];
    const data = {
        labels: labels,
        datasets: [{
                label: 'BARU',
                data: dashboardData['spj_count_baru'],
                backgroundColor: [
                    'rgba(255, 165, 0, 0.2)',
                    'rgba(255, 165, 0, 0.2)',
                    'rgba(255, 165, 0, 0.2)',
                    'rgba(255, 165, 0, 0.2)',
                ],
                borderColor: [
                    'rgb(255, 165, 0)',
                    'rgb(255, 165, 0)',
                    'rgb(255, 165, 0)',
                    'rgb(255, 165, 0)',
                ],

                borderWidth: 1
            },
            {
                label: 'APPROVE',
                data: dashboardData['spj_count_ms'],
                backgroundColor: [
                    'rgba(0, 0, 255, 0.2)',
                    'rgba(0, 0, 255, 0.2)',
                    'rgba(0, 0, 255, 0.2)',
                    'rgba(0, 0, 255, 0.2)',
                ],
                borderColor: [
                    'rgb(0, 0, 255)',
                    'rgb(0, 0, 255)',
                    'rgb(0, 0, 255)',
                    'rgb(0, 0, 255)',
                ],
                borderWidth: 1
            },
            {
                label: 'TMS',
                data: dashboardData['spj_count_tms'],
                backgroundColor: [
                    'rgba(255, 0, 0, 0.2)',
                    'rgba(255, 0, 0, 0.2)',
                    'rgba(255, 0, 0, 0.2)',
                    'rgba(255, 0, 0, 0.2)',
                ],
                borderColor: [
                    'rgb(255, 0, 0)',
                    'rgb(255, 0, 0)',
                    'rgb(255, 0, 0)',
                    'rgb(255, 0, 0)',
                ],
                borderWidth: 1
            },
            {
                label: 'CAIR',
                data: dashboardData['spj_count_cair'],
                backgroundColor: [
                    'rgba(0, 128, 128, 0.2)',
                    'rgba(0, 128, 128, 0.2)',
                    'rgba(0, 128, 128, 0.2)',
                    'rgba(0, 128, 128, 0.2)',
                ],
                borderColor: [
                    'rgb(0, 128, 128)',
                    'rgb(0, 128, 128)',
                    'rgb(0, 128, 128)',
                    'rgb(0, 128, 128)',
                ],
                borderWidth: 1
            },

        ]
    };
    const config = {
        type: 'horizontalBar',
        data: data,
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        precision: 0,
                        beginAtZero: true,
                    },
                }, ],
            },
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    padding: 15,
                    fontColor: '#666',
                    generateLabels: generateDatasetLabels
                }
            }
        },
    };

    new Chart(document.querySelector(".barChart"), config);

    // ---- Kelompok Belanja (Pie) & Jenis Belanja (Bar) ----
    const kelompok = dashboardData['kelompok_chart'];
    const jenis = dashboardData['jenis_chart'];
    const rp = v => "Rp. " + Number(v || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    const persen = (p, r) => p > 0 ? ((r / p) * 100).toFixed(1) + "%" : "0%";

    // Tooltip: nama + pagu + realisasi + persentase capaian (Chart.js v2.1 API: mode "single")
    const tooltip = (labels, pagu, realisasi) => ({
        mode: "single",
        callbacks: {
            title: items => {
                if (!items || items.length === 0) return '';
                const i = items[0].index;
                return i >= 0 && i < (labels ? labels.length : 0) ? labels[i] : '';
            },
            label: (item, data) => {
                if (!data || !data.datasets) return '';
                const i = item.index;
                const p = i >= 0 && i < (pagu ? pagu.length : 0) ? (Number(pagu[i]) || 0) : 0;
                const r = i >= 0 && i < (realisasi ? realisasi.length : 0) ? (Number(realisasi[
                    i]) || 0) : 0;
                return [
                    "Total Pagu: " + rp(p),
                    "Total Realisasi: " + rp(r),
                    "Persentase Capaian: " + persen(p, r)
                ];
            }
        }
    });

    new Chart(document.getElementById("chartKelompokBelanja"), {
        type: "horizontalBar",
        data: {
            labels: kelompok.labels,
            datasets: [{
                    label: "Pagu",
                    data: kelompok.pagu,
                    backgroundColor: "rgba(69, 92, 115, 0.8)",
                    borderColor: "#455C73",
                    borderWidth: 1
                },
                {
                    label: "Realisasi",
                    data: kelompok.realisasi,
                    backgroundColor: "rgba(38, 185, 154, 0.8)",
                    borderColor: "#26B99A",
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: value => rp(value)
                    }
                }],
                yAxes: [{
                    ticks: {
                        autoSkip: false
                    }
                }]
            },
            tooltips: tooltip(kelompok.labels, kelompok.pagu, kelompok.realisasi),
            legend: {
                display: true,
                position: "top",
                labels: {
                    padding: 15,
                    fontColor: "#666",
                    generateLabels: generateDatasetLabels
                }
            }
        }
    });

    // Bar chart untuk pagu vs realisasi per jenis belanja
    const barJenis = new Chart(document.getElementById("chartJenisBelanja"), {
        type: "bar",
        data: {
            labels: jenis.labels,
            datasets: [{
                    label: "Pagu",
                    data: jenis.pagu,
                    backgroundColor: "rgba(69, 92, 115, 0.7)",
                    borderColor: "#455C73",
                    borderWidth: 1
                },
                {
                    label: "Realisasi",
                    data: jenis.realisasi,
                    backgroundColor: "rgba(38, 185, 154, 0.7)",
                    borderColor: "#26B99A",
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: value => rp(value)
                    }
                }]
            },
            tooltips: tooltip(jenis.labels, jenis.pagu, jenis.realisasi),
            legend: {
                display: true,
                position: "top",
                labels: {
                    padding: 15,
                    fontColor: "#666",
                    generateLabels: generateDatasetLabels
                }
            }
        }
    });
})
}
