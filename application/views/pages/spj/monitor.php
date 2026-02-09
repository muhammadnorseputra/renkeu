<div class="row"
    style="display: flex; flex-wrap: nowrap; overflow-x: auto; gap: 0px; padding-bottom: 10px;">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <div class="count">Alokasi Pagu</div>
            <h3><?= nominal($this->spj->getTotalPaguMurniByPart($part, $tahun_anggaran)); ?></h3>
            <p>Pagu Anggaran Murni</p>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-money"></i></div>
            <div class="count">Alokasi Pagu</div>
            <h3><?= nominal($this->spj->getTotalPaguPerubahanByPart($part, $tahun_anggaran)); ?></h3>
            <p>Pagu Anggaran Perubahan</p>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-list-alt"></i></div>
            <div class="count">Realisasi Belanja</div>
            <h3><?= nominal($this->spj->getTotalRealisasiByPart($part, $tahun_anggaran)); ?></h3>
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
                        $this->spj->getTotalPaguPerubahanByPart($part, $tahun_anggaran) :
                        $this->spj->getTotalPaguMurniByPart($part, $tahun_anggaran)) > 0
                        ? ($this->spj->getTotalRealisasiByPart($part, $tahun_anggaran) /
                            ($this->session->userdata('is_perubahan') ?
                                $this->spj->getTotalPaguPerubahanByPart($part, $tahun_anggaran) :
                                $this->spj->getTotalPaguMurniByPart($part, $tahun_anggaran))) * 100
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
            <li><a class="collapse-link"><i class="fa fa-chevron-down"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="x_content" style="display: none;">
        <table class="table jambo_table bulk_action table-bordered">
            <thead class="top-0" style="position: sticky; top: 0; z-index: 1;">
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
                    <?php $no = 1;
                    foreach ($listpart as $part): ?>
                        <tr>
                            <!-- No -->
                            <td class="text-center"><?= $no++ ?></td>
                            <!-- Bidang/Bagian -->
                            <td><?= $part->nama ?></td>
                            <!-- Usulan Baru -->
                            <td class="text-right">Rp. <?= nominal($this->spj->getTotalRealisasiByPartAndStatus($part->id, $tahun_anggaran, ['ENTRI'])); ?></td>
                            <td class="text-right">Rp. <?= nominal($this->spj->getTotalRealisasiByPartAndStatus($part->id, $tahun_anggaran, ['VERIFIKASI', 'VERIFIKASI_ADMIN'])); ?></td>
                            <!-- Persetujuan/Tolak -->
                            <td class="text-right">Rp. <?= nominal($this->spj->getTotalRealisasiByPartAndStatusAdmin($part->id, $tahun_anggaran, ['APPROVE'])); ?></td>
                            <td class="text-right">Rp. <?= nominal($this->spj->getTotalRealisasiByPartAndStatusAdmin($part->id, $tahun_anggaran, ['TMS', 'BTL'])); ?></td>
                            <!-- Bendahara -->
                            <td class="text-right">Rp. <?= nominal($this->spj->getTotalRealisasiByPartAndStatusBendahara($part->id, $tahun_anggaran, ['PERBAIKAN'])); ?></td>
                            <td class="text-right">Rp. <?= nominal($this->spj->getTotalRealisasiByPartAndStatusBendahara($part->id, $tahun_anggaran, ['PENDING - PERBAIKAN'])); ?></td>
                            <td class="text-right">Rp. <?= nominal($this->spj->getTotalRealisasiByPartAndStatusBendahara($part->id, $tahun_anggaran, ['PENDING'])); ?></td>
                            <td class="text-right">Rp. <?= nominal($this->spj->getTotalRealisasiByPartAndStatusBendahara($part->id, $tahun_anggaran, ['CAIR'])); ?></td>
                            <td class="text-right">Rp. <?= nominal($this->spj->getTotalRealisasiByPartAndStatusBendahara($part->id, $tahun_anggaran, ['TOLAK'])); ?></td>
                            <!-- Capaian -->
                            <td class="text-right">
                                <?php
                                if ($is_perubahan ? $this->spj->getTotalPaguPerubahanByPart($part->id, $tahun_anggaran) : $this->spj->getTotalPaguMurniByPart($part->id, $tahun_anggaran) > 0) {
                                    $capaian = ($this->spj->getTotalRealisasiByPartAndStatusAdmin($part->id, $tahun_anggaran, ['APPROVE']) /
                                        ($is_perubahan ? $this->spj->getTotalPaguPerubahanByPart($part->id, $tahun_anggaran) : $this->spj->getTotalPaguMurniByPart($part->id, $tahun_anggaran))) * 100;
                                } else {
                                    $capaian = 0;
                                }

                                echo number_format($capaian, 2) . '%';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
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
                    <th rowspan="3" class="align-middle text-center">No</th>
                    <th rowspan="3" class="align-middle text-center">Program</th>
                    <th class="align-middle text-center">Total Pagu</th>
                    <th class="align-middle text-center">Total Realisasi</th>
                    <th class="align-middle text-center">Sisa Anggaran</th>
                    <th class="align-middle text-center">Capaian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($programs): ?>
                    <?php $no = 1;
                    foreach ($programs->result() as $program): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $program->nama ?></td>
                            <td>Pagu</td>
                            <td class="text-right">Rp. <?= nominal($this->spj->getRealisasiByPartAndProgram($part->id, $program->id, $tahun_anggaran)) ?></td>
                        </tr>
                    <?php endforeach; ?>
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
                    <th rowspan="3" class="align-middle text-center">No</th>
                    <th rowspan="3" class="align-middle text-center">Kegiatan</th>
                    <th class="align-middle text-center">Total Pagu</th>
                    <th class="align-middle text-center">Total Realisasi</th>
                    <th class="align-middle text-center">Sisa Anggaran</th>
                    <th class="align-middle text-center">Capaian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($kegiatans): ?>
                    <?php $no = 1;
                    foreach ($kegiatans->result() as $kegiatan): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $kegiatan->nama ?></td>
                            <td class="text-right">Rp. <?= nominal($this->spj->getRealisasiByPartAndKegiatan($part->id, $kegiatan->id, $tahun_anggaran)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="2">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>