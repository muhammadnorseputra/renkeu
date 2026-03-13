
<?php $filter_tanggal = isset($_GET['filter_tanggal']) ? $_GET['filter_tanggal'] : null ?>

<?= form_open(base_url("app/spj/monitor"), ['class' => 'form-horizontal', 'method' => 'GET']); ?>
<div class="border p-2 mb-3 bg-light rounded-bottom">
    <div class="row">
        <div class="col-md-3">
            <fieldset>
                <div class="control-group ">
                <label for="filter_tanggal">Filter Tanggal</label>
                    <div class="controls">
                        <div class="input-prepend input-group">
                            <input type="text" autocomplete="off" name="filter_tanggal" id="filter_tanggal" class="form-control" value="<?= $filter_tanggal ?>" />
                        </div>
                        
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-md-2">
            <button type="submit" id="btn_filter" class="btn btn-primary mt-4"><i class="fa fa-filter"></i> Terapkan</button>
            <button type="button" class="btn btn-secondary mt-4" onclick="window.location.href = '<?= base_url('app/spj/monitor') ?>'"><i class="fa fa-repeat"></i> Reset</button>
        </div>
    </div>
</div>
<?= form_close(); ?>
<div class="row"
    style="display: flex; flex-wrap: nowrap; overflow-x: auto; gap: 0px; padding-bottom: 10px;">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <?php  
            if(in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
                $alokasiPagu = $this->spj->getTotalPaguMurniByPart(null, $tahun_anggaran);
            } else {
                $alokasiPagu = $this->spj->getTotalPaguMurniByPart($part, $tahun_anggaran);
            }
            ?>
            <div class="count">Alokasi Pagu Murni</div>
            <h3><?= nominal($alokasiPagu); ?></h3>
            <p>Pagu Anggaran Murni</p>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <?php
            if(in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
                $alokasiPaguPerubahan = $this->spj->getTotalPaguPerubahanByPart(null, $tahun_anggaran);
            } else {
                $alokasiPaguPerubahan = $this->spj->getTotalPaguPerubahanByPart($part, $tahun_anggaran);
            }
            ?>
            <div class="icon"><i class="fa fa-money"></i></div>
            <div class="count">Alokasi Pagu Perubahan</div>
            <h3><?= nominal($alokasiPaguPerubahan); ?></h3>
            <p>Pagu Anggaran Perubahan</p>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <?php
            if(in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
                $totalRealisasi = $this->spj->getTotalRealisasiByPart(null, $filter_tanggal, $tahun_anggaran);
            } else {
                $totalRealisasi = $this->spj->getTotalRealisasiByPart($part, $filter_tanggal, $tahun_anggaran);
            }
            ?>
            <div class="icon"><i class="fa fa-list-alt"></i></div>
            <div class="count">Realisasi Belanja</div>
            <h3><?= nominal($totalRealisasi); ?></h3>
            <p>Realisasi Anggaran</p>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-bar-chart"></i></div>
            <div class="count">Capaian Realisasi</div>
            <!-- hitung capaian berdasarkan realisasi dan target murni atau perubahan -->
            <h3>
                <?= number_format(
                    ($this->session->userdata('is_perubahan') ?
                        $alokasiPaguPerubahan :
                        $alokasiPagu) > 0
                        ? ($totalRealisasi /
                            ($this->session->userdata('is_perubahan') ?
                                $alokasiPaguPerubahan :
                                $alokasiPagu)) * 100
                        : 0,
                    2
                ) . '%'; ?>
            </h3>

            <p>Capaian</p>
        </div>
    </div>
</div>
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-line-chart mr-2"></i> Belanja Harian</h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" style="display: none;">
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-line-chart mr-2"></i> Belanja Bidang</h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <table class="table jambo_table bulk_action table-bordered">
            <thead>
                <tr>
                    <th rowspan="3" class="align-middle text-center">No</th>
                    <th rowspan="3" class="align-middle text-center">Bidang/Bagian</th>
                    <th colspan="10" class="align-middle text-center">SPJ Berdasarkan Status</th>
                </tr>
                <tr>
                    <th colspan="2" class="align-middle text-center bg-info">Usulan</th>
                    <th colspan="2" class="align-middle text-center bg-secondary">Persetujuan/Tolak</th>
                    <th colspan="5" class="align-middle text-center bg-success">Bendahara</th>
                    <th rowspan="2" class="align-middle text-center">Capaian</th>
                </tr>
                <tr>
                    <th class="align-middle text-center">Baru</th>
                    <th class="align-middle text-center">Verifikasi</th>
                    <th class="align-middle text-center">Approval</th>
                    <th class="align-middle text-center">TMS</th>
                    <th class="align-middle text-center">Perbaikan</th>
                    <th class="align-middle text-center">Pending (Perbaikan)</th>
                    <th class="align-middle text-center">Pending</th>
                    <th class="align-middle text-center">Cair</th>
                    <th class="align-middle text-center">Gagal Cair</th>
                </tr>
                <tr>
                    <th class="align-middle text-center">1</th>
                    <th class="align-middle text-center">2</th>
                    <th class="align-middle text-center">3</th>
                    <th class="align-middle text-center">4</th>
                    <th class="align-middle text-center">5</th>
                    <th class="align-middle text-center">6</th>
                    <th class="align-middle text-center">7</th>
                    <th class="align-middle text-center">8</th>
                    <th class="align-middle text-center">9</th>
                    <th class="align-middle text-center">10</th>
                    <th class="align-middle text-center">11</th>
                    <th class="align-middle text-center">12 (5/Pagu) * 100%</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($listpart) > 0): ?>
                    <?php 
                    $no = 1;
                    
                    $totalUsulanBaru = 0;
                    $totalUsulanVerifikasi = 0;
                    $totalPersetujuan = 0;
                    $totalTolak = 0;
                    $totalPerbaikan = 0;
                    $totalPendingPerbaikan = 0;
                    $totalPending = 0;
                    $totalCair = 0;
                    $totalTolakBendahara = 0;

                    foreach ($listpart as $part): 
                    // Pagu Murni atau Perubahan
                    $totalPaguPerubahan = $this->spj->getTotalPaguPerubahanByPart($part->id, $tahun_anggaran);
                    $totalPaguMurni = $this->spj->getTotalPaguMurniByPart($part->id, $tahun_anggaran);

                    // Usulan Baru/Verifikasi
                    $usulanBaru = $this->spj->getTotalRealisasiByPartAndStatus($part->id, $filter_tanggal, $tahun_anggaran, ['ENTRI']);
                    $usulanVerifikasi = $this->spj->getTotalRealisasiByPartAndStatus($part->id, $filter_tanggal, $tahun_anggaran, ['VERIFIKASI', 'VERIFIKASI_ADMIN']);
                    $totalUsulanBaru += $usulanBaru;
                    $totalUsulanVerifikasi += $usulanVerifikasi;

                    //Persetujuan/Tolak
                    $persetujuan = $this->spj->getTotalRealisasiByPartAndStatusAdmin($part->id, $filter_tanggal, $tahun_anggaran, ['APPROVE']);
                    $tolak = $this->spj->getTotalRealisasiByPartAndStatusAdmin($part->id, $filter_tanggal, $tahun_anggaran, ['TMS', 'BTL']);
                    $totalPersetujuan += $persetujuan;
                    $totalTolak += $tolak;

                    // Bedahara
                    $perbaikan = $this->spj->getTotalRealisasiByPartAndStatusBendahara($part->id, $filter_tanggal, $tahun_anggaran, ['PERBAIKAN']);
                    $pendingPerbaikan = $this->spj->getTotalRealisasiByPartAndStatusBendahara($part->id, $filter_tanggal, $tahun_anggaran, ['PENDING - PERBAIKAN']);
                    $pending = $this->spj->getTotalRealisasiByPartAndStatusBendahara($part->id, $filter_tanggal, $tahun_anggaran, ['PENDING']);
                    $cair = $this->spj->getTotalRealisasiByPartAndStatusBendahara($part->id, $filter_tanggal, $tahun_anggaran, ['CAIR']);
                    $tolakBendahara = $this->spj->getTotalRealisasiByPartAndStatusBendahara($part->id, $filter_tanggal, $tahun_anggaran, ['TOLAK']);
                    $totalPerbaikan += $perbaikan;
                    $totalPendingPerbaikan += $pendingPerbaikan;
                    $totalPending += $pending;
                    $totalCair += $cair;
                    $totalTolakBendahara += $tolakBendahara;

                    // Capaian
                    if ($is_perubahan ? $totalPaguPerubahan : $totalPaguMurni > 0) {
                        $capaian = (($persetujuan)/($is_perubahan ? $totalPaguPerubahan : $totalPaguMurni)) * 100;
                    } else {
                        $capaian = 0;
                    }
                    ?>
                        <tr>
                            <!-- No -->
                            <td class="text-center"><?= $no++ ?></td>
                            <!-- Bidang/Bagian -->
                            <td><?= $part->nama ?></td>
                            <!-- Usulan Baru -->
                            <td class="text-right">
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span><?= nominal($usulanBaru); ?></span>
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span><?= nominal($usulanVerifikasi); ?></span>
                                </div>    
                            </td>
                            <!-- Persetujuan/Tolak -->
                            <td class="text-right">
                                <div class="d-flex justify-content-between text-success">
                                    <span>Rp.</span>
                                    <span><?= nominal($persetujuan); ?></span>
                                </div>    
                            </td>
                            <td class="text-right">
                                <div class="d-flex justify-content-between text-danger">
                                    <span>Rp.</span>
                                    <span><?= nominal($tolak); ?></span>
                                </div>    
                            </td>
                            <!-- Bendahara -->
                            <td class="text-right">
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span> <?= nominal($perbaikan); ?></span>
                                </div>    
                           </td>
                            <td class="text-right">
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span><?= nominal($usulanBaru); ?></span>
                                </div>    
                            <?= nominal($pendingPerbaikan); ?></td>
                            <td class="text-right">
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span><?= nominal($pending); ?></span>
                                </div>    
                            </td>
                            <td class="text-right">
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span><?= nominal($cair); ?></span>
                                </div>    
                            </td>
                            <td class="text-right">
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span><?= nominal($tolak); ?></span>
                                </div>    
                            </td>
                            <!-- Capaian -->
                            <td class="text-right">
                                <?= number_format($capaian, 2) . '%'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-center font-weight-bold">Total</td>
                        <td class="text-right font-weight-bold">
                        <div class="d-flex justify-content-between">
                            <span>Rp.</span>
                            <span><?= nominal($totalUsulanBaru); ?></span>
                        </div>     
                        </td>
                        <td class="text-right font-weight-bold">
                        <div class="d-flex justify-content-between">
                            <span>Rp.</span>
                            <span><?= nominal($totalUsulanVerifikasi); ?></span>
                        </div>     
                        </td>
                        <td class="text-right font-weight-bold">
                        <div class="d-flex justify-content-between text-success">
                            <span>Rp.</span>
                            <span><?= nominal($totalPersetujuan); ?></span>
                        </div>     
                        </td>
                        <td class="text-right font-weight-bold">
                        <div class="d-flex justify-content-between text-danger">
                            <span>Rp.</span>
                            <span><?= nominal($totalTolak); ?></span>
                        </div>     
                        </td>
                        <td class="text-right font-weight-bold">
                        <div class="d-flex justify-content-between">
                            <span>Rp.</span>
                            <span><?= nominal($totalPerbaikan); ?></span>
                        </div>     
                        </td>
                        <td class="text-right font-weight-bold">
                        <div class="d-flex justify-content-between">
                            <span>Rp.</span>
                            <span><?= nominal($totalPendingPerbaikan); ?></span>
                        </div>     
                        </td>
                        <td class="text-right font-weight-bold">
                        <div class="d-flex justify-content-between">
                            <span>Rp.</span>
                            <span><?= nominal($totalPending); ?></span>
                        </div>     
                        </td>
                        <td class="text-right font-weight-bold">
                        <div class="d-flex justify-content-between">
                            <span>Rp.</span>
                            <span><?= nominal($totalCair); ?></span>
                        </div>     
                        </td>
                        <td class="text-right font-weight-bold">
                        <div class="d-flex justify-content-between">
                            <span>Rp.</span>
                            <span><?= nominal($totalTolakBendahara); ?></span>
                        </div>     
                        </td>
                        <td class="text-right font-weight-bold">
                            <?php  
                            $totalCapaian = @($totalPersetujuan/($is_perubahan ? $alokasiPaguPerubahan : $alokasiPagu)) * 100;
                            ?>
                            <?= number_format($totalCapaian, 2) . '%'; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="9">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-line-chart mr-2"></i> Belanja Program</h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <table class="table jambo_table bulk_action table-bordered">
            <thead>
                <tr>
                    <th class="align-middle text-center">No</th>
                    <th class="align-middle text-center">Program</th>
                    <th class="align-middle text-center">Total Pagu</th>
                    <th class="align-middle text-center">Total Realisasi</th>
                    <th class="align-middle text-center">Sisa Anggaran</th>
                    <th class="align-middle text-center">Capaian</th>
                </tr>
                <tr>
                    <th class="align-middle text-center">1</th>
                    <th class="align-middle text-center">2</th>
                    <th class="align-middle text-center">3</th>
                    <th class="align-middle text-center">4</th>
                    <th class="align-middle text-center">5</th>
                    <th class="align-middle text-center">6 (4/3) * 100%</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($programs): ?>
                    <?php 
                    $no = 1;
                    $totalPaguProgram = 0;
                    $totalRealisasiProgram = 0;
                    foreach ($programs->result() as $program): 
                    $paguProgram = $this->target->getAlokasiPaguProgram($program->id, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal ?? 0;
                    
                    if(in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
                        $realisasiProgram = $this->spj->getRealisasiByPartAndProgram(null, $filter_tanggal, $program->id, $tahun_anggaran);
                    } else {
                        $realisasiProgram = $this->spj->getRealisasiByPartAndProgram($part->id, $filter_tanggal, $program->id, $tahun_anggaran);
                    }
                    

                    $sisaAnggaran = $paguProgram - $realisasiProgram;
                    $capaian = $paguProgram > 0 ? ($realisasiProgram / $paguProgram) * 100 : 0;
                    $totalPaguProgram += $paguProgram;
                    $totalRealisasiProgram += $realisasiProgram;
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $program->nama ?></td>
                            <td>Rp. <?= nominal($paguProgram) ?></td>
                            <td class="text-right">Rp. <?= nominal($realisasiProgram) ?></td>
                            <td class="text-right">Rp. <?= nominal($sisaAnggaran) ?></td>
                            <td class="text-right"><?= number_format($capaian, 2) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-center font-weight-bold">Total</td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguProgram) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalRealisasiProgram) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguProgram - $totalRealisasiProgram) ?></td>
                        <td class="text-right font-weight-bold"><?= $totalPaguProgram > 0 ? number_format(($totalRealisasiProgram / $totalPaguProgram) * 100, 2) : 0 ?>%</td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="2">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-line-chart mr-2"></i> Belanja Kegiatan</h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <table class="table jambo_table bulk_action table-bordered">
            <thead>
                <tr>
                    <th class="align-middle text-center">No</th>
                    <th class="align-middle text-center">Kegiatan</th>
                    <th class="align-middle text-center">Total Pagu</th>
                    <th class="align-middle text-center">Total Realisasi</th>
                    <th class="align-middle text-center">Sisa Anggaran</th>
                    <th class="align-middle text-center">Capaian</th>
                </tr>

                <tr>
                    <th class="align-middle text-center">1</th>
                    <th class="align-middle text-center">2</th>
                    <th class="align-middle text-center">3</th>
                    <th class="align-middle text-center">4</th>
                    <th class="align-middle text-center">5</th>
                    <th class="align-middle text-center">6 (4/3) * 100%</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($kegiatans): ?>
                    <?php 
                    $no = 1;
                    $totalPaguKegiatan = 0;
                    $totalRealisasiPaguKegiatan = 0;
                    foreach ($kegiatans->result() as $kegiatan): 
                    $paguKegiatan = $this->target->getAlokasiPaguKegiatan($kegiatan->id, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal ?? 0;
                    $totalPaguKegiatan += $paguKegiatan;

                    if(in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
                        $realisasiKegiatan = $this->spj->getRealisasiByPartAndKegiatan(null, $filter_tanggal, $kegiatan->id, $tahun_anggaran);
                    } else {
                        $realisasiKegiatan = $this->spj->getRealisasiByPartAndKegiatan($part->id, $filter_tanggal, $kegiatan->id, $tahun_anggaran);
                    }

                    $totalRealisasiPaguKegiatan += $realisasiKegiatan;

                    $sisaAnggaran = $paguKegiatan - $realisasiKegiatan;
                    $capaian = $paguKegiatan > 0 ? ($realisasiKegiatan / $paguKegiatan) * 100 : 0;
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $kegiatan->nama ?></td>
                            <td class="text-right">Rp. <?= nominal($paguKegiatan) ?></td>
                            <td class="text-right">
                                Rp. <?= nominal($realisasiKegiatan) ?>
                            </td>
                            <td class="text-right">Rp. <?= nominal($sisaAnggaran) ?></td>
                            <td class="text-right"><?= number_format($capaian, 2) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-center font-weight-bold">Total</td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguKegiatan) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalRealisasiPaguKegiatan) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguKegiatan - $totalRealisasiPaguKegiatan) ?></td>
                        <td class="text-right font-weight-bold"><?= $totalPaguKegiatan > 0 ? number_format(($totalRealisasiPaguKegiatan / $totalPaguKegiatan) * 100, 2) : 0 ?>%</td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="2">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-line-chart mr-2"></i> Belanja Sub Kegiatan</h2>
        <ul class="nav navbar-right panel_toolbox d-flex justify-content-center align-items-center space-x-3">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <table class="table jambo_table bulk_action table-bordered">
            <thead>
                <tr>
                    <th class="align-middle text-center">No</th>
                    <th class="align-middle text-center">Sub Kegiatan</th>
                    <th class="align-middle text-center">Total Pagu</th>
                    <th class="align-middle text-center">Total Realisasi</th>
                    <th class="align-middle text-center">Sisa Anggaran</th>
                    <th class="align-middle text-center">Capaian</th>
                </tr>

                <tr>
                    <th class="align-middle text-center">1</th>
                    <th class="align-middle text-center">2</th>
                    <th class="align-middle text-center">3</th>
                    <th class="align-middle text-center">4</th>
                    <th class="align-middle text-center">5</th>
                    <th class="align-middle text-center">6 (4/3) * 100%</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($sub_kegiatans): ?>
                    <?php 
                    $no = 1;
                    $totalPaguSubKegiatan = 0;
                    $totalRealisasiSubKegiatan = 0;
                    foreach ($sub_kegiatans->result() as $sub_kegiatan): 
                        $paguSubKegiatan = $this->target->getAlokasiPaguSubKegiatan($sub_kegiatan->id, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal ?? 0;
                        $totalPaguSubKegiatan += $paguSubKegiatan;

                        if(in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
                            $realisasiSubKegiatan = $this->spj->getRealisasiByPartAndSubKegiatan(null, $filter_tanggal, $sub_kegiatan->id, $tahun_anggaran);
                        } else {
                            $realisasiSubKegiatan = $this->spj->getRealisasiByPartAndSubKegiatan($part->id, $filter_tanggal, $sub_kegiatan->id, $tahun_anggaran);
                        }

                        $totalRealisasiSubKegiatan += $realisasiSubKegiatan;
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $sub_kegiatan->nama ?></td>
                            <td class="text-right">
                                Rp. <?= nominal($paguSubKegiatan) ?>
                            </td>
                            <td class="text-right">
                                Rp. <?= nominal($realisasiSubKegiatan) ?>
                            </td>
                            <td class="text-right">
                                Rp. <?= nominal($paguSubKegiatan - $realisasiSubKegiatan) ?>
                            </td>
                            <td class="text-right">
                                <?php
                                $totalRealisasiPerSubKegiatan = $realisasiSubKegiatan ?? 0;
                                $capaian = $paguSubKegiatan > 0 ? ($totalRealisasiPerSubKegiatan / $paguSubKegiatan) * 100 : 0;
                                echo number_format($capaian, 2) . '%';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>`
                    <tr>
                        <td colspan="2" class="text-center font-weight-bold">Total</td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguSubKegiatan) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalRealisasiSubKegiatan) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguSubKegiatan - $totalRealisasiSubKegiatan) ?></td>
                        <td class="text-right font-weight-bold"><?= $totalPaguSubKegiatan > 0 ? number_format(($totalRealisasiSubKegiatan / $totalPaguSubKegiatan) * 100, 2) : 0 ?>%</td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="2">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
