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
            <div class="count">Rp. <span class="countup"
                    data-value="<?php echo (float) $panel['program_total_pagu'] ?>">0</span></div>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-dollar"></i></div>
            <h3>Realisasi </h3>
            <p>Realisasi Pagu Anggaran.</p>
            <hr>
            <div class="count">Rp. <span class="countup"
                    data-value="<?php echo (float) $panel['program_total_realisasi'] ?>">0</span></div>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6  ">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-flag"></i></div>
            <h3>Pegawai </h3>
            <p>Jumlah Pegawai Mapping.</p>
            <hr>
            <div class="count"><span class="countup"
                    data-value="<?php echo (float) $panel['jumlah_pegawai'] ?>">0</span> pegawai</div>
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
                        <div class="progress m-0 dashboard-progress-full">
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
<div class="row dashboard-transaction-row" id="tour_chart_transaksi">
    <div class="col-md-12">
        <div class="x_panel ui-ribbon-container dashboard-transaction-panel">
            <div class="ui-ribbon-wrapper">
                <div class="ui-ribbon">
                    <?php echo $this->session->userdata('tahun_anggaran'); ?>
                </div>
            </div>
            <div class="x_title dashboard-transaction-header">
                <div class="dashboard-title-left">
                    <h2 class="d-inline-block mb-0">Trend Realisasi</h2>
                    <div id="chartStatusToggle" class="d-inline-block ml-3">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-warning" data-status="baru"><i
                                    class="fa fa-file-text-o mr-1"></i> BARU</button>
                            <button type="button" class="btn btn-outline-primary" data-status="ms"><i
                                    class="fa fa-check-circle mr-1"></i> MS</button>
                            <button type="button" class="btn btn-outline-danger" data-status="tms"><i
                                    class="fa fa-close mr-1"></i> TMS</button>
                            <button type="button" class="btn btn-outline-success active" data-status="cair"><i
                                    class="fa fa-money mr-1"></i> CAIR</button>
                        </div>
                    </div>
                </div>
                <ul class="nav navbar-right panel_toolbox chart-export">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle chart-export-toggle" title="Export chart" aria-label="Export chart"
                            data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-download" aria-hidden="true"></i><span>Export</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-header">Unduh grafik sebagai</li>
                            <li><a class="export-chart" data-target="tour_chart_transaksi" data-format="png"><i
                                        class="fa fa-file-image-o"></i> PNG</a></li>
                            <li><a class="export-chart" data-target="tour_chart_transaksi" data-format="jpg"><i
                                        class="fa fa-file-image-o"></i> JPG</a></li>
                        </ul>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="col-12 ">
                    <div class="dashboard-transaction-chart-container">
                        <div id="chart_transaksi" class="dashboard-transaction-canvas"></div>
                    </div>

                    <div class="quarter-summary-grid">
                        <?php foreach ([1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'] as $quarter => $roman):
                            $limit = (float) $chart['limit_triwulan_' . $quarter];
                            $tw_jumlah = (float) $chart['triwulan_' . $quarter];
                            $pct = $limit > 0 ? ($tw_jumlah / $limit) * 100 : 0;
                            $budgetType = $quarter === 4 ? 'PERUBAHAN' : 'MURNI';
                            $progressPct = min(100, max(0, $pct));
                        ?>
                            <div class="quarter-summary-card<?php echo $quarter === 4 ? ' is-revision' : ''; ?>">
                                <div class="quarter-summary-heading">
                                    <span class="quarter-summary-name">Triwulan <?php echo $roman; ?></span>
                                    <span class="quarter-summary-type"><?php echo $budgetType; ?></span>
                                </div>
                                <span class="quarter-summary-label">Pagu</span>
                                <h3 class="quarter-summary-pagu">Rp. <span class="countup"
                                        data-value="<?php echo $limit; ?>">0</span></h3>
                                <div class="quarter-summary-details">
                                    <div class="quarter-summary-realization">
                                        <span class="quarter-summary-label">Realisasi</span>
                                        <strong>Rp. <span class="countup"
                                                data-value="<?php echo $tw_jumlah; ?>">0</span></strong>
                                    </div>
                                    <div class="quarter-summary-achievement">
                                        <span class="quarter-summary-label">Capaian</span>
                                        <strong><?php echo @round($pct, 2); ?>%</strong>
                                    </div>
                                </div>
                                <div class="progress quarter-summary-progress">
                                    <div class="progress-bar" role="progressbar"
                                        data-transitiongoal="<?php echo $progressPct; ?>"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Realisasi Baru -->
<div class="row" id="tour_top_transaksi">
    <div class="col-md-12">
    <div class="x_panel">
            <div class="ui-ribbon-container">
                <div class="ui-ribbon-wrapper">
                    <div class="ui-ribbon">
                        <?php echo $this->session->userdata('tahun_anggaran'); ?>
                    </div>
                </div>
            </div>
            <div class="x_title">
                <h2>Top Realisasi Baru</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="dashboard-top-transaksi-grid">
                    <?php
                        foreach ($chart['top_transaksi'] as $top):
                            $profile = $this->user->profile_username($top->entri_by)->row();
                            $tglsql  = substr($top->entri_at, 0, 10);
                            if ($top->is_status === 'APPROVE') {
                                $status = 'badge-primary';
                            } elseif ($top->is_status === 'BTL' || $top->is_status === 'TMS') {
                                $status = 'badge-danger';
                            } else {
                                $status = 'badge-primary';
                            }
                    ?>
                        <div class="dashboard-top-transaksi-card">
                            <div class="dashboard-top-transaksi-head">
                                <img class="dashboard-top-transaksi-avatar"
                                    src="<?php echo base_url('template/assets/picture_akun/' . $profile->pic) ?>"
                                    alt="<?php echo $profile->username ?>">
                                <div class="dashboard-top-transaksi-ident">
                                    <span class="dashboard-top-transaksi-nama"
                                        title="<?php echo ucwords(strtolower($profile->nama)) ?>"><?php echo ucwords(strtolower($profile->nama)) ?></span>
                                    <small><?php echo $top->singkatan; ?> | <?php echo longdate_indo($tglsql) ?></small>
                                </div>
                                <span class="badge <?php echo $status; ?> dashboard-top-transaksi-status"><?php echo $top->is_status; ?></span>
                            </div>
                            <div class="dashboard-top-transaksi-jumlah">Rp. <?php echo nominal($top->jumlah) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                <ul class="nav navbar-right panel_toolbox chart-export">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle chart-export-toggle" title="Export chart" aria-label="Export chart"
                            data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-download" aria-hidden="true"></i><span>Export</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-header">Unduh grafik sebagai</li>
                            <li><a class="export-chart" data-target="tour_chart_belanja" data-format="png"><i
                                        class="fa fa-file-image-o"></i> PNG</a></li>
                            <li><a class="export-chart" data-target="tour_chart_belanja" data-format="jpg"><i
                                        class="fa fa-file-image-o"></i> JPG</a></li>
                        </ul>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-center">Jenis Belanja (Pagu vs Realisasi) <small>(TA
                                <?php echo html_escape($this->session->userdata('tahun_anggaran')); ?>)</small></h5>
                        <canvas id="chartJenisBelanja" height="250"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-center">Kelompok Belanja (Pagu vs Realisasi) <small>(TA
                                <?php echo html_escape($this->session->userdata('tahun_anggaran')); ?>)</small></h5>
                        <p class="text-center text-muted small mb-1">
                            <span class="mr-2"><i class="fa fa-square dashboard-legend-pagu"></i> Pagu</span>
                            <span><i class="fa fa-square dashboard-legend-realisasi"></i> Realisasi</span>
                        </p>
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
                <ul class="nav navbar-right panel_toolbox chart-export">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle chart-export-toggle" title="Export chart" aria-label="Export chart"
                            data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-download" aria-hidden="true"></i><span>Export</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-header">Unduh grafik sebagai</li>
                            <li><a class="export-chart" data-target="tour_chart_part" data-format="png"><i
                                        class="fa fa-file-image-o"></i> PNG</a></li>
                            <li><a class="export-chart" data-target="tour_chart_part" data-format="jpg"><i
                                        class="fa fa-file-image-o"></i> JPG</a></li>
                        </ul>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-6">
                        <canvas class="canvasDoughnutOfParts dashboard-parts-chart" width="300"></canvas>
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
<?php
$dashboardChartData = [];
foreach ([
    'spj_ms', 'spj_tms', 'spj_baru', 'spj_cair', 'part_label', 'part_jumlah',
    'spj_count_baru', 'spj_count_ms', 'spj_count_tms', 'spj_count_cair',
    'kelompok_chart', 'jenis_chart'
] as $chartKey) {
    $dashboardChartData[$chartKey] = json_decode($chart[$chartKey], true);
}
?>
<script id="dashboard-chart-data" type="application/json"><?php echo json_encode($dashboardChartData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?></script>
