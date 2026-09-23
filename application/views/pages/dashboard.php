<div class="clearfix"></div>
<div class="alert alert-primary" role="alert">
    Selamat datang kembali <strong><?php echo $this->session->userdata('nama') ?></strong> [ Login as <b><?php echo strtolower($this->session->userdata('role')); ?></b> ] <br>
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
            <div class="count">Rp. <?php echo nominal($panel['program_total_pagu']) ?></div>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-dollar"></i></div>
            <h3>Realisasi </h3>
            <p>Realisasi Pagu Anggaran.</p>
            <hr>
            <div class="count">Rp. <?php echo nominal($panel['program_total_realisasi']) ?></div>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-flag"></i></div>
            <h3>Indikator </h3>
            <p>Jumlah Indikator Outcome/Output.</p>
            <hr>
            <div class="count"><?php echo $panel['jumlah_indikator'] ?> indikator</div>
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
                            <div class="progress-bar bg-blue" role="progressbar" data-transitiongoal="<?php echo @round($panel['persentase_capaian'], 2) ?>"></div>
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
                <h2>Trend Realisasi </h2>
                <!-- <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul> -->
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
                                $limit          = $chart['limit_triwulan_1'];
                                $tw_jumlah      = $chart['triwulan_1'];
                                $percentase     = @($tw_jumlah / $limit) * 100;
                                $percentase_cek = ($percentase != 0) ? $percentase : '';
                            ?>
                            <span>TOTAL TRIWULAN I</span>
                            <h2>Rp. <?php echo @nominal($tw_jumlah); ?></span></h2>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?php echo @round($percentase_cek, 2) ?>"></div>
                            </div>
                        </div>
                        <div class="col-3 col-sm-3 col-md-3 tile">
                            <?php
                                $limit          = $chart['limit_triwulan_2'];
                                $tw_jumlah      = $chart['triwulan_2'];
                                $percentase     = @($tw_jumlah / $limit) * 100;
                                $percentase_cek = ($percentase != 0) ? $percentase : '';
                            ?>
                            <span>TOTAL TRIWULAN II</span>
                            <h2>Rp. <?php echo @nominal($tw_jumlah); ?></span></h2>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?php echo @round($percentase_cek, 2) ?>"></div>
                            </div>
                        </div>
                        <div class="col-3 col-sm-3 col-md-3 tile">
                            <?php
                                $limit          = $chart['limit_triwulan_3'];
                                $tw_jumlah      = $chart['triwulan_3'];
                                $percentase     = @($tw_jumlah / $limit) * 100;
                                $percentase_cek = ($percentase != 0) ? $percentase : '';
                            ?>
                            <span>TOTAL TRIWULAN III</span>
                            <h2>Rp. <?php echo @nominal($tw_jumlah); ?></span></h2>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?php echo @round($percentase_cek, 2) ?>"></div>
                            </div>
                        </div>
                        <div class="col-3 col-sm-3 col-md-3 tile">
                            <?php
                                $limit          = $chart['limit_triwulan_4'];
                                $tw_jumlah      = $chart['triwulan_4'];
                                $percentase     = @($tw_jumlah / $limit) * 100;
                                $percentase_cek = ($percentase != 0) ? $percentase : '';
                            ?>
                            <span>TOTAL TRIWULAN IV</span>
                            <h2>Rp. <?php echo @nominal($tw_jumlah); ?></span></h2>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?php echo @round($percentase_cek, 2) ?>"></div>
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
                <h2>New Realisasi </h2>
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
                                <img class="aero" src="<?php echo base_url('template/assets/picture_akun/' . $profile->pic) ?>" alt="<?php echo $profile->username ?>" width="25">
                            </a>
                            <div class="media-body">
                                <a class="title" href="#" data-toggle="tooltip" data-placement="right" title="<?php echo ucwords(strtolower($profile->nama)) ?>"><small><?php echo $top->singkatan; ?> | <?php echo longdate_indo($tglsql) ?></small></a>
                                <p><strong>Rp. <?php echo nominal($top->jumlah) ?> </strong></p>
                                <p><small><?php echo $profile->nama ?></small><span style="float:right"><?php echo $status ?></span></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
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
<div class="modal" id="modalInfoProfile" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
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
            lineWidth: 2,
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

        content: function(label, x, y) {
            return `
                <div style="
                    font-size: 12px;
                    font-weight: 600;
                    margin-bottom: 4px;
                ">
                    ${label}
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

        $.plot($("#chart_transaksi"), [SPJBARU, SPJMS, SPJTMS, SPJCAIR], options);

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
                        label: (ttItem, items) => (`${items.labels[ttItem.index]}: Rp. ${items.datasets[ttItem.datasetIndex].data[ttItem.index].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}`)
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
    })
</script>