<div class="clearfix"></div>
<div class="alert alert-primary" role="alert">
    Selamat datang kembali <strong><?php echo $this->session->userdata('nama') ?></strong> [ Login as
    <b><?php echo strtolower($this->session->userdata('role')); ?></b> ] <br>
    <?php echo $this->users->part_detail($this->session->userdata('part')); ?> <br>
</div>
<!-- Panel Chart -->
<div class="row" id="panel">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-bullhorn"></i></div>
            <h3>Target </h3>
            <p>Target Pagu Anggaran.</p>
            <hr>
            <div class="count">Rp. <span class="countup" data-value="<?php echo (float) $panel['program_total_pagu'] ?>">0</span></div>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-dollar"></i></div>
            <h3>Realisasi </h3>
            <p>Realisasi Pagu Anggaran.</p>
            <hr>
            <div class="count">Rp. <span class="countup" data-value="<?php echo (float) $panel['program_total_realisasi'] ?>">0</span></div>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-flag"></i></div>
            <h3>Pegawai </h3>
            <p>Jumlah Pegawai Mapping.</p>
            <hr>
            <div class="count"><span class="countup" data-value="<?php echo (float) $panel['jumlah_pegawai'] ?>">0</span> pegawai</div>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-line-chart"></i></div>
            <h3>Capaian </h3>
            <p>Persentase Capaian.</p>
            <hr>
            <div class="row">
                <div class="col-md-5">
                    <div class="count"><?php echo @round($panel['persentase_capaian'], 2) ?> %</div>
                </div>
                <div class="col-md-7 px-3 px-md-4">
                    <!-- <small>Progres Capaian 100%</small> -->
                    <div class="mt-md-2">
                        <div class="progress m-0" style="width: 100%;">
                            <div class="progress-bar bg-blue" role="progressbar"
                                data-transitiongoal="<?php echo @round($panel['persentase_capaian'], 2) ?>"></div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
<div class="clearfix"></div>
<!-- Transaction Chart -->
<div class="row" id="tour_chart_transaksi">
    <div class="col-md-9">
        <div class="x_panel ui-ribbon-container">
            <div class="ui-ribbon-wrapper">
                <div class="ui-ribbon">
                    <?php echo $this->session->userdata('tahun_anggaran'); ?>
                </div>
            </div>
            <div class="x_title">
                <h2 class="d-inline-block mb-0">Trend Realisasi </h2>
                <div id="chartStatusToggle" class="d-inline-block ml-3">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-warning" data-status="baru"><i class="fa fa-file-text-o mr-1"></i> BARU</button>
                        <button type="button" class="btn btn-outline-primary" data-status="ms"><i class="fa fa-check-circle mr-1"></i> MS</button>
                        <button type="button" class="btn btn-outline-danger" data-status="tms"><i class="fa fa-close mr-1"></i> TMS</button>
                        <button type="button" class="btn btn-outline-success active" data-status="cair"><i class="fa fa-money mr-1"></i> CAIR</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-12 ">
                    <div class="demo-container" style="height:280px">
                        <div id="chart_transaksi" class="demo-placeholder"></div>
                    </div>
                    <div class="tiles">
                        <div class="col-3 col-sm-3 col-md-3 tile">
                            <?php
                                $limit     = (float) $chart['limit_triwulan_1'];
                                $tw_jumlah = (float) $chart['triwulan_1'];
                                if ($limit <= 0) {
                                    $pct = 0;
                                } else {
                                    $pct = ($tw_jumlah / $limit) * 100;
                                }
                            ?>
                            <span>TOTAL TRIWULAN I</span>
                            <h2>Rp. <span class="countup" data-value="<?php echo (float) $tw_jumlah ?>">0</span></h2>
                            <div class="text-muted small mb-1 mt-0" style="display:block; line-height:1.2;"><strong><?php echo @round($pct, 2) ?>%</strong> capaian</div>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar"
                                    data-transitiongoal="<?php echo @round($pct, 2) ?>"></div>
                            </div>
                        </div>
                        <div class="col-3 col-sm-3 col-md-3 tile">
                            <?php
                                $limit     = (float) $chart['limit_triwulan_2'];
                                $tw_jumlah = (float) $chart['triwulan_2'];
                                if ($limit <= 0) {
                                    $pct = 0;
                                } else {
                                    $pct = ($tw_jumlah / $limit) * 100;
                                }
                            ?>
                            <span>TOTAL TRIWULAN II</span>
                            <h2>Rp. <span class="countup" data-value="<?php echo (float) $tw_jumlah ?>">0</span></h2>
                            <div class="text-muted small mb-1 mt-0" style="display:block; line-height:1.2;"><strong><?php echo @round($pct, 2) ?>%</strong> capaian</div>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar"
                                    data-transitiongoal="<?php echo @round($pct, 2) ?>"></div>
                            </div>
                        </div>
                        <div class="col-3 col-sm-3 col-md-3 tile">
                            <?php
                                $limit     = (float) $chart['limit_triwulan_3'];
                                $tw_jumlah = (float) $chart['triwulan_3'];
                                if ($limit <= 0) {
                                    $pct = 0;
                                } else {
                                    $pct = ($tw_jumlah / $limit) * 100;
                                }
                            ?>
                            <span>TOTAL TRIWULAN III</span>
                            <h2>Rp. <span class="countup" data-value="<?php echo (float) $tw_jumlah ?>">0</span></h2>
                            <div class="text-muted small mb-1 mt-0" style="display:block; line-height:1.2;"><strong><?php echo @round($pct, 2) ?>%</strong> capaian</div>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar"
                                    data-transitiongoal="<?php echo @round($pct, 2) ?>"></div>
                            </div>
                        </div>
                        <div class="col-3 col-sm-3 col-md-3 tile">
                            <?php
                                $limit     = (float) $chart['limit_triwulan_4'];
                                $tw_jumlah = (float) $chart['triwulan_4'];
                                if ($limit <= 0) {
                                    $pct = 0;
                                } else {
                                    $pct = ($tw_jumlah / $limit) * 100;
                                }
                            ?>
                            <span>TOTAL TRIWULAN IV</span>
                            <h2>Rp. <span class="countup" data-value="<?php echo (float) $tw_jumlah ?>">0</span></h2>
                            <div class="text-muted small mb-1 mt-0" style="display:block; line-height:1.2;"><strong><?php echo @round($pct, 2) ?>%</strong> capaian</div>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar"
                                    data-transitiongoal="<?php echo @round($pct, 2) ?>"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="x_panel ui-ribbon-container">
            <div class="ui-ribbon-wrapper">
                <div class="ui-ribbon">
                    <?php echo $this->session->userdata('tahun_anggaran'); ?>
                </div>
            </div>
            <div class="x_title">
                <h2>Top Realisasi Baru </h2>
                <!-- <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul> -->
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="list-unstyled top_profiles scroll-view">
                    <?php
                        foreach ($chart['top_transaksi'] as $top):
                            $profile = $this->user->profile_username($top->entri_by)->row();
                            $tglsql  = substr($top->entri_at, 0, 10);
                            if ($top->is_status === 'APPROVE') {
                                $status = '<span class="badge badge-primary"><i class="fa fa-check-circle" title="APPROVE"></i></span>';
                            } elseif ($top->is_status === 'BTL') {
                            $status = '<span class="badge badge-danger"><i class="fa fa-close"></i> BTL</span>';
                        } elseif ($top->is_status === 'TMS') {
                            $status = '<span class="badge badge-danger"><i class="fa fa-close"></i> TMS</span>';
                        } else {
                            $status = '<span class="badge badge-primary"><i class="fa fa-check-circle"></i></span>';
                        }
                    ?>
                    <li class="media event">
                        <a class="pull-left border-aero profile_thumb">
                            <img class="aero"
                                src="<?php echo base_url('template/assets/picture_akun/' . $profile->pic) ?>"
                                alt="<?php echo $profile->username ?>" width="25">
                        </a>
                        <div class="media-body">
                            <a class="title" href="#" data-toggle="tooltip" data-placement="right"
                                title="<?php echo ucwords(strtolower($profile->nama)) ?>"><small><?php echo $top->singkatan; ?>
                                    | <?php echo longdate_indo($tglsql) ?></small></a>
                            <p><strong>Rp. <?php echo nominal($top->jumlah) ?> </strong></p>
                            <p><small><?php echo $profile->nama ?></small><span
                                    style="float:right"><?php echo $status ?></span></p>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Kelompok & Jenis Belanja -->
<div class="row" id="tour_chart_belanja">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Kelompok Belanja & Jenis Belanja</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-center">Jenis Belanja (Pagu vs Realisasi)</h5>
                        <canvas id="chartJenisBelanja" height="250"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-center">Kelompok Belanja (Pagu vs Realisasi)</h5>
                        <canvas id="chartKelompokBelanja" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Parts Chart -->
<div class="row" id="tour_chart_part">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Realisasi of parts </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-6">
                        <canvas class="canvasDoughnutOfParts" width="300" style="margin:0"></canvas>
                        <p class="text-center my-3">Realisasi Anggaran Per Bidang/Bagian</p>
                    </div>
                    <div class="col-md-6">
                        <canvas width="300" class="barChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal" id="modalInfoProfile" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content rounded-0">
            <!-- Modal Header -->
            <div class="modal-header bg-danger text-white rounded-0">
                <h4 class="modal-title">Mohon Perhatian !</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<script>
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
            formattingFn: function(n) { return fmt.format(n); }
        });
        if (!anim.error) anim.start();
    });

    let SPJMS = {
        label: "Realisasi SPJ MS",
        data: <?php echo $chart['spj_ms'] ?>,
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
        data: <?php echo $chart['spj_tms'] ?>,
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
        data: <?php echo $chart['spj_baru'] ?>,
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
        data: <?php echo $chart['spj_cair'] ?>,
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
                <div style="
                    font-size: 12px;
                    font-weight: 600;
                    margin-bottom: 4px;
                ">
                    ${label}
                </div>

                <div style="
                    font-size: 12px;
                    font-weight: 500;
                    margin-bottom: 4px;
                ">
                    ${namaBulan}
                </div>

                <div style="
                    font-size: 15px;
                    font-weight: 700;
                ">
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
        baru: { label: "Realisasi SPJ Baru", color: "#f59e0b", fill: "rgba(245, 158, 11, 0.10)", data: SPJBARU.data },
        ms:   { label: "Realisasi SPJ MS",   color: "#1e40af", fill: "rgba(30, 64, 175, 0.10)", data: SPJMS.data },
        tms:  { label: "Realisasi SPJ TMS",  color: "#ef4444", fill: "rgba(239, 68, 68, 0.10)", data: SPJTMS.data },
        cair: { label: "Realisasi SPJ Cair", color: "#22c55e", fill: "rgba(34, 197, 94, 0.10)", data: SPJCAIR.data }
    };

    function buildSeries(status) {
        var m = statusMeta[status];
        return {
            label: m.label,
            data: m.data,
            color: m.color,
            lines: { fillColor: m.fill, lineWidth: 3 },
            points: { fillColor: "#fff", lineWidth: 2, radius: 4 }
        };
    }

    $.plot($("#chart_transaksi"), [buildSeries("cair")], options);

    var toggleBtns = $("#chartStatusToggle button[data-status]");
    toggleBtns.click(function() {
        var status = $(this).data("status");
        toggleBtns.removeClass("active");
        $(this).addClass("active");
        $.plot($("#chart_transaksi"), [buildSeries(status)], options);
    });

    // Pie Charts
    var DataPieParts = {
        labels: <?php echo $chart['part_label'] ?>,
        datasets: [{
            data: <?php echo $chart['part_jumlah'] ?>,
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
                    fontColor: '#666'
                }
            }
        }
    });

    // Bar
    const labels = <?php echo $chart['part_label'] ?>;
    const data = {
        labels: labels,
        datasets: [{
                label: 'BARU',
                data: <?php echo $chart['spj_count_baru'] ?>,
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
                data: <?php echo $chart['spj_count_ms'] ?>,
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
                data: <?php echo $chart['spj_count_tms'] ?>,
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
                data: <?php echo $chart['spj_count_cair'] ?>,
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
                    fontColor: '#666'
                }
            }
        },
    };

    new Chart($(".barChart"), config);

    // ---- Kelompok Belanja (Pie) & Jenis Belanja (Bar) ----
    const kelompok = <?php echo $chart['kelompok_chart'] ?>;
    const jenis = <?php echo $chart['jenis_chart'] ?>;
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
                const r = i >= 0 && i < (realisasi ? realisasi.length : 0) ? (Number(realisasi[i]) || 0) : 0;
                return [
                    "Total Pagu: " + rp(p),
                    "Total Realisasi: " + rp(r),
                    "Persentase Capaian: " + persen(p, r)
                ];
            }
        }
    });

    // Warna per kelompok: hue dari hash nama (stabil tiap reload, otomatis beda untuk kelompok baru)
    const hashHue = s => { let h = 0; for (let i = 0; i < s.length; i++) h = (h * 31 + s.charCodeAt(i)) >>> 0; return h % 360; };
    const pieColors = { pagu: [], real: [] };
    for (let i = 0; i < kelompok.labels.length; i++) {
        const h = hashHue((kelompok.labels[i] || "").trim() + i);
        pieColors.pagu.push(`hsl(${h}, 55%, 42%)`);
        pieColors.real.push(`hsl(${h}, 55%, 66%)`);
    }

    // Pie chart untuk pagu vs realisasi per kelompok belanja
    const pieKelompok = new Chart(document.getElementById("chartKelompokBelanja"), {
        type: "pie",
        data: {
            labels: kelompok.labels,
            datasets: [
                {
                    label: "Pagu",
                    data: kelompok.pagu,
                    backgroundColor: pieColors.pagu,
                    borderColor: "#fff",
                    borderWidth: 1
                },
                {
                    label: "Realisasi",
                    data: kelompok.realisasi,
                    backgroundColor: pieColors.real,
                    borderColor: "#fff",
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            tooltips: tooltip(kelompok.labels, kelompok.pagu, kelompok.realisasi),
            legend: {
                display: true,
                position: "top",
                labels: { padding: 15, fontColor: "#666" }
            }
        }
    });

    // Bar chart untuk pagu vs realisasi per jenis belanja
    const barJenis = new Chart(document.getElementById("chartJenisBelanja"), {
        type: "bar",
        data: {
            labels: jenis.labels,
            datasets: [
                {
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
                labels: { padding: 15, fontColor: "#666" }
            }
        }
    });
})
</script>