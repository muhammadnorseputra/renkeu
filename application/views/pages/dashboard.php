<div class="clearfix"></div>
<div class="alert alert-success" role="alert">
    Selamat datang kembali <strong><?= $this->session->userdata('nama') ?></strong> [ Login as <b><?= strtolower($this->session->userdata('role')); ?></b> ] <br>
    <?= $this->users->part_detail($this->session->userdata('part')); ?> <br>
</div>
<!-- Panel Chart -->
<div class="row">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-bullhorn"></i></div>
            <h3>Target </h3>
            <p>Target Pagu Anggaran.</p>
            <hr>
            <div class="count">Rp. <?= nominal($panel['program_total_pagu']) ?></div>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-dollar"></i></div>
            <h3>Realisasi </h3>
            <p>Realisasi Pagu Anggaran.</p>
            <hr>
            <div class="count">Rp. <?= nominal($panel['program_total_realisasi']) ?></div>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-flag"></i></div>
            <h3>Indikator </h3>
            <p>Jumlah Indikator Outcome/Output.</p>
            <hr>
            <div class="count"><?= $panel['jumlah_indikator'] ?> indikator</div>
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
                    <div class="count"><?= @round($panel['persentase_capaian'], 2) ?> %</div>
                </div>
                <div class="col-md-7 px-3 px-md-4">
                    <!-- <small>Progres Capaian 100%</small> -->
                    <div class="mt-md-2">
                        <div class="progress m-0" style="width: 100%;">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="<?= @round($panel['persentase_capaian'], 2) ?>"></div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
<div class="clearfix"></div>
<!-- Transaction Chart -->
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Trend Realisasi </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-md-9 col-sm-12 ">
                    <div class="demo-container" style="height:280px">
                        <div id="chart_transaksi" class="demo-placeholder"></div>
                    </div>
                    <div class="tiles">
                        <div class="col-3 col-sm-3 col-md-3 tile">
                            <?php
                            $limit = $chart['limit_triwulan_1'];
                            $tw_jumlah = $chart['triwulan_1'];
                            $percentase =  @($tw_jumlah / $limit) * 100;
                            $percentase_cek = ($percentase != 0) ? $percentase : '';
                            ?>
                            <span>TOTAL TRIWULAN I</span>
                            <h2>Rp. <?= @nominal($tw_jumlah); ?> | <span class="text-danger">Rp. <?= @nominal($limit); ?></span></h2>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?= @round($percentase_cek, 2) ?>"></div>
                            </div>
                        </div>
                        <div class="col-3 col-sm-3 col-md-3 tile">
                            <?php
                            $limit = $chart['limit_triwulan_2'];
                            $tw_jumlah = $chart['triwulan_2'];
                            $percentase =  @($tw_jumlah / $limit) * 100;
                            $percentase_cek = ($percentase != 0) ? $percentase : '';
                            ?>
                            <span>TOTAL TRIWULAN II</span>
                            <h2>Rp. <?= @nominal($tw_jumlah); ?> | <span class="text-danger">Rp. <?= @nominal($limit); ?></span></h2>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?= @round($percentase_cek, 2) ?>"></div>
                            </div>
                        </div>
                        <div class="col-3 col-sm-3 col-md-3 tile">
                            <?php
                            $limit = $chart['limit_triwulan_3'];
                            $tw_jumlah = $chart['triwulan_3'];
                            $percentase =  @($tw_jumlah / $limit) * 100;
                            $percentase_cek = ($percentase != 0) ? $percentase : '';
                            ?>
                            <span>TOTAL TRIWULAN III</span>
                            <h2>Rp. <?= @nominal($tw_jumlah); ?> | <span class="text-danger">Rp. <?= @nominal($limit); ?></span></h2>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?= @round($percentase_cek, 2) ?>"></div>
                            </div>
                        </div>
                        <div class="col-3 col-sm-3 col-md-3 tile">
                            <?php
                            $limit = $chart['limit_triwulan_4'];
                            $tw_jumlah = $chart['triwulan_4'];
                            $percentase =  @($tw_jumlah / $limit) * 100;
                            $percentase_cek = ($percentase != 0) ? $percentase : '';
                            ?>
                            <span>TOTAL TRIWULAN IV</span>
                            <h2>Rp. <?= @nominal($tw_jumlah); ?> | <span class="text-danger">Rp. <?= @nominal($limit); ?></span></h2>
                            <div class="progress progress_sm m-0" style="width: 100%;">
                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?= @round($percentase_cek, 2) ?>"></div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-3 col-sm-12 ">
                    <div>
                        <div class="x_title">
                            <h4>Top New Realisasi</h4>
                            <div class="clearfix"></div>
                        </div>
                        <ul class="list-unstyled top_profiles scroll-view">
                            <?php
                            foreach ($chart['top_transaksi'] as $top) :
                                $profile = $this->user->profile_username($top->entri_by)->row();
                                $tglsql = substr($top->entri_at, 0, 10);
                                if ($top->is_status === 'APPROVE') {
                                    $status = '<span class="badge badge-success"><i class="fa fa-check-circle" title="APPROVE"></i></span>';
                                } elseif ($top->is_status === 'BTL') {
                                    $status = '<span class="badge badge-danger"><i class="fa fa-close"></i> BTL</span>';
                                } elseif ($top->is_status === 'TMS') {
                                    $status = '<span class="badge badge-danger"><i class="fa fa-close"></i> TMS</span>';
                                } else {
                                    $status = '<span class="badge badge-success"><i class="fa fa-check-circle"></i></span>';
                                }
                            ?>
                                <li class="media event">
                                    <a class="pull-left border-aero profile_thumb">
                                        <img class="aero" src="<?= base_url('template/assets/picture_akun/' . $profile->pic) ?>" alt="<?= $profile->username ?>" width="25">
                                    </a>
                                    <div class="media-body">
                                        <a class="title" href="#" data-toggle="tooltip" data-placement="right" title="<?= ucwords(strtolower($profile->nama)) ?>"><small><?= $top->singkatan; ?></small></a>
                                        <p><strong>Rp. <?= nominal($top->jumlah) ?> </strong></p>
                                        <p><small><?= longdate_indo($tglsql) ?></small><span style="float:right"><?= $status ?></span></p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Parts Chart -->
<div class="row">
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
<script>
    $(function() {
        let SPJMS = {
            label: "Realisasi SPJ MS",
            data: <?= $chart['spj_ms'] ?>,
            lines: {
                fillColor: "rgba(150, 202, 89, 0.12)"
            },
            points: {
                fillColor: "#fff"
            },
        };
        let SPJTMS = {
            label: "Realisasi SPJ TMS",
            data: <?= $chart['spj_tms'] ?>,
            lines: {
                fillColor: "rgba(255, 0, 0, 0.12)"
            },
            points: {
                fillColor: "#fff"
            },
        };
        let SPJBTL = {
            label: "Realisasi SPJ BTL",
            data: <?= $chart['spj_btl'] ?>,
            lines: {
                fillColor: "rgba(255, 165, 0, 0.12)"
            },
            points: {
                fillColor: "#fff"
            },
        };
        let options = {
            grid: {
                show: !0,
                aboveData: !0,
                color: "#3f3f3f",
                labelMargin: 20,
                axisMargin: 0,
                borderWidth: 0,
                borderColor: null,
                minBorderMargin: 1,
                clickable: !0,
                hoverable: !0,
                autoHighlight: !0,
                mouseActiveRadius: 50,
            },
            series: {
                lines: {
                    show: !0,
                    fill: !0,
                    lineWidth: 2,
                    steps: !1
                },
                points: {
                    show: !0,
                    radius: 4,
                    symbol: "circle",
                    lineWidth: 2
                },
            },
            legend: {
                position: "ne",
                margin: [0, -60],
                noColumns: 0,
                labelBoxBorderColor: null,
                labelFormatter: function(e, a) {
                    return e + "&nbsp;&nbsp;";
                },
                width: 20,
                height: 1,
            },
            colors: [
                "green",
                "red",
                "orange",
                "#6f7a8a",
                "#f7cb38",
                "#5a8022",
                "#2c7282",
            ],
            shadowSize: !0,
            tooltip: {
                cssClass: "flotTip",
                show: !0,
                content: function(label, x, y) {
                    return `${x}: ${y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}`;
                }
            },
            yaxis: {
                min: 0,
                tickFormatter: function(v, axis) {
                    return `<span>Rp. ${v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</span>`;
                }
            },
            xaxis: {
                mode: "categories"
            },
        };

        $.plot($("#chart_transaksi"), [SPJMS, SPJTMS, SPJBTL], options);

        // Pie Charts
        var DataPieParts = {
            labels: <?= $chart['part_label'] ?>,
            datasets: [{
                data: <?= $chart['part_jumlah'] ?>,
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
        const labels = <?= $chart['part_label'] ?>;
        const data = {
            labels: labels,
            datasets: [{
                    label: 'APPROVE',
                    data: <?= $chart['spj_count_ms'] ?>,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                    ],
                    borderColor: [
                        'rgb(75, 192, 192)',
                        'rgb(75, 192, 192)',
                        'rgb(75, 192, 192)',
                        'rgb(75, 192, 192)',
                    ],
                    borderWidth: 1
                },
                {
                    label: 'TMS',
                    data: <?= $chart['spj_count_tms'] ?>,
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
                    label: 'BTL',
                    data: <?= $chart['spj_count_btl'] ?>,
                    backgroundColor: [
                        'rgba(255, 102, 0, 0.2)',
                        'rgba(255, 102, 0, 0.2)',
                        'rgba(255, 102, 0, 0.2)',
                        'rgba(255, 102, 0, 0.2)',
                    ],
                    borderColor: [
                        'rgb(255, 102, 0)',
                        'rgb(255, 102, 0)',
                        'rgb(255, 102, 0)',
                        'rgb(255, 102, 0)',
                    ],
                    borderWidth: 1
                }
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