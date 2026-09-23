<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Verifikasi Hasil Kinerja</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <!-- Tabs: Per Bidang / Rekapitulasi -->
                <ul class="nav vk-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab-bidang" role="tab">
                            <i class="fa fa-building-o"></i> Per Bidang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab-rekap" role="tab">
                            <i class="fa fa-table"></i> Rekapitulasi Seluruh Pegawai
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Tab Per Bidang (Accordion) -->
                    <div class="tab-pane fade show active" id="tab-bidang" role="tabpanel">
                        <!-- Accordion per Bidang -->
                        <div id="accordionBidang">
                    <?php
                    $bidang_icons = ['fa-building-o', 'fa-bar-chart', 'fa-users', 'fa-cogs', 'fa-graduation-cap', 'fa-briefcase', 'fa-database', 'fa-line-chart'];
                    foreach ($bidang as $idx => $b):
                        $icon = $bidang_icons[$idx % count($bidang_icons)];
                        $jml  = count($b['pegawai']);
                    ?>
                        <div class="card accordion-card mb-2 shadow-sm">
                            <div class="card-header py-0 border-0" id="heading-<?= $idx ?>">
                                <a class="d-flex justify-content-between align-items-center text-decoration-none accordion-link py-3" data-toggle="collapse" data-parent="#accordionBidang" href="#collapse-<?= $idx ?>" aria-expanded="false" aria-controls="collapse-<?= $idx ?>">
                                    <span class="d-flex align-items-center">
                                        <span class="bidang-icon mr-3">
                                            <i class="fa <?= $icon ?>"></i>
                                        </span>
                                        <span>
                                            <span class="font-weight-bold text-dark d-block"><?= $b['nama'] ?></span>
                                            <small class="text-muted"><?= $b['singkatan'] ?></small>
                                        </span>
                                    </span>
                                    <span class="d-flex align-items-center">
                                        <span class="badge badge-primary badge-pill mr-3 px-3 py-2">
                                            <i class="fa fa-user-o mr-1"></i><?= $jml ?> pegawai
                                        </span>
                                        <i class="fa fa-chevron-down text-muted accordion-chevron"></i>
                                    </span>
                                </a>
                            </div>
                            <div id="collapse-<?= $idx ?>" class="collapse" role="tabpanel" aria-labelledby="heading-<?= $idx ?>">
                                <div class="card-body p-0">
                                    <?php if (empty($b['pegawai'])): ?>
                                        <div class="text-center py-4">
                                            <i class="fa fa-user-o fa-2x text-muted d-block mb-2"></i>
                                            <span class="text-muted">Belum ada pegawai di bidang ini.</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="pl-3">No</th>
                                                        <th>NIP</th>
                                                        <th>Nama</th>
                                                        <th>Jabatan</th>
                                                        <th>Pangkat</th>
                                                        <th>Jenis</th>
                                                        <th class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; ?>
                                                    <?php foreach ($b['pegawai'] as $p): ?>
                                                        <tr>
                                                            <td class="pl-3"><?= $no++ ?></td>
                                                            <td class="font-weight-bold"><?= $p->nip ?></td>
                                                            <td><?= $p->nama_lengkap ?></td>
                                                            <td><?= $p->jabatan ?></td>
                                                            <td><?= $p->pangkat ?></td>
                                                            <td>
                                                                <?php if ($p->jenis === 'PPPK'): ?>
                                                                    <span class="badge badge-warning">PPPK</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-primary">PNS</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-success btn-xs btn-verifikasi"
                                                                    data-nip="<?= $p->nip ?>"
                                                                    data-nama="<?= htmlspecialchars($p->nama_lengkap, ENT_QUOTES) ?>"
                                                                    title="Verifikasi">
                                                                    <i class="fa fa-check"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-secondary btn-xs btn-rekap"
                                                                    data-nip="<?= $p->nip ?>"
                                                                    data-nama="<?= htmlspecialchars($p->nama_lengkap, ENT_QUOTES) ?>"
                                                                    title="Rekapitulasi">
                                                                    <i class="fa fa-list-alt"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

                    <!-- Tab Rekapitulasi Seluruh Pegawai -->
                    <div class="tab-pane fade" id="tab-rekap" role="tabpanel">
                        <table class="table table-bordered table-hover text-center" id="tableRekapAll" style="width: 100%;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Pangkat</th>
                                    <th>Jenis</th>
                                    <th>Bidang</th>
                                    <th>Singkatan</th>
                                    <th>TW I</th>
                                    <th>TW II</th>
                                    <th>TW III</th>
                                    <th>TW IV</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Kinerja -->
<script>
var vkPrivEdit = <?= $priv_edit ? 'true' : 'false' ?>;
var vkTahun = <?= json_encode($this->session->userdata('tahun_anggaran')) ?>;
</script>
<div class="modal fade" id="modalVerifikasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-check-circle text-success"></i> Verifikasi Kinerja SAKIPRA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formVerifikasi">
                <div class="modal-body">
                    <!-- Info pegawai -->
                    <div class="alert alert-light border mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">NIP</small>
                                <div class="font-weight-bold" id="v_nip">-</div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Nama</small>
                                <div class="font-weight-bold" id="v_nama">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Pilih Periode Triwulan -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold"><i class="fa fa-calendar text-primary mr-1"></i> Periode Triwulan</label>
                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons" id="periodeGroup">
                            <?php
                            $periode_list = [
                                'TW1' => ['Triwulan I', 'Jan - Mar'],
                                'TW2' => ['Triwulan II', 'Apr - Jun'],
                                'TW3' => ['Triwulan III', 'Jul - Sep'],
                                'TW4' => ['Triwulan IV', 'Okt - Des'],
                            ];
                            foreach ($periode_list as $k => $v): ?>
                                <label class="btn btn-outline-primary periode-btn" data-periode="<?= $k ?>">
                                    <input type="radio" name="periode" value="<?= $k ?>" autocomplete="off">
                                    <span class="periode-icon d-block mb-1"><i class="fa fa-circle-o text-warning"></i></span>
                                    <span class="d-block font-weight-bold"><?= $v[0] ?></span>
                                    <small class="d-block text-muted"><?= $v[1] ?></small>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Checklist per triwulan -->
                    <div class="position-relative" id="checklistArea">
                        <div class="row">
                            <?php
                            $checklist = [
                                'unggah_kinerja_harian' => ['Unggah Kinerja Harian', 'Berdasarkan data pada aplikasi E-kinerja BKN dan tagging ke indikator kinerja yang diintervensi', 'fa-cloud-upload'],
                                'target_realisasi'      => ['Penginputan Target dan Realisasi', 'Input target dan realisasi kinerja', 'fa-bullseye'],
                                'masalah_tindak_lanjut' => ['Penginputan Masalah dan Tindak Lanjut (MTL)', 'Input masalah dan tindak lanjut', 'fa-exclamation-triangle'],
                                'diskusi_kinerja'       => ['Keterisian Diskusi Kinerja', 'Diskusi kinerja terisi', 'fa-comments'],
                                'data_dukung'           => ['Unggah Data Dukung Kinerja', 'Unggah data dukung kinerja', 'fa-paperclip'],
                                'simpulan_capaian'      => ['Simpulan Capaian Kinerja', 'Simpulan capaian kinerja', 'fa-flag-checkered'],
                            ];
                            foreach ($checklist as $key => $item): ?>
                                <div class="col-md-6">
                                    <div class="card mb-2 shadow-sm">
                                        <div class="card-body py-2 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="fa <?= $item[2] ?> text-primary mr-2 fa-lg"></i>
                                                <div>
                                                    <div class="font-weight-bold small"><?= $item[0] ?></div>
                                                    <small class="text-muted"><?= $item[1] ?></small>
                                                </div>
                                            </div>
                                            <?php if ($priv_edit): ?>
                                                <label class="switch mb-0">
                                                    <input type="checkbox" class="chk-verifikasi" name="<?= $key ?>" value="Y">
                                                    <span class="slider round"></span>
                                                </label>
                                            <?php else: ?>
                                                <span class="badge badge-secondary chk-status" data-key="<?= $key ?>">Tidak</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Overlay loading -->
                        <div class="checklist-loading d-none">
                            <div class="spinner-border text-primary" role="status"></div>
                            <span class="ml-2 font-weight-bold text-primary">Memuat data...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSimpanVerifikasi">
                        <i class="fa fa-save"></i> <span>Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Rekapitulasi Verifikasi -->
<div class="modal fade" id="modalRekap" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title"><i class="fa fa-list-alt mr-1"></i> Rekapitulasi Verifikasi Hasil Kinerja</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Info pegawai -->
                <div class="alert alert-light border mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">NIP</small>
                            <div class="font-weight-bold" id="r_nip">-</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Nama</small>
                            <div class="font-weight-bold" id="r_nama">-</div>
                        </div>
                    </div>
                </div>

                <!-- Loading -->
                <div class="text-center py-5 d-none" id="rekapLoading">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="font-weight-bold text-primary mt-2">Memuat rekapitulasi...</div>
                </div>

                <!-- Tabel rekap -->
                <div class="table-responsive d-none" id="rekapTableWrap">
                    <table class="table table-bordered table-hover text-center mb-0" id="rekapTable">
                        <thead class="thead-dark">
                            <tr>
                                <th class="align-middle" rowspan="2">No</th>
                                <th class="align-middle" rowspan="2">Checklist</th>
                                <th colspan="4">Triwulan</th>
                                <th class="align-middle" rowspan="2">Total</th>
                            </tr>
                            <tr>
                                <th>I <small class="d-block text-muted">Jan-Mar</small></th>
                                <th>II <small class="d-block text-muted">Apr-Jun</small></th>
                                <th>III <small class="d-block text-muted">Jul-Sep</small></th>
                                <th>IV <small class="d-block text-muted">Okt-Des</small></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <!-- Info pembuat / pengupdate -->
                    <div class="alert alert-light border mt-3 mb-0" id="rekapAudit">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted"><i class="fa fa-user-plus mr-1"></i>Dibuat oleh</small>
                                <div class="font-weight-bold" id="r_created_by">-</div>
                                <small class="text-muted" id="r_created_at"></small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted"><i class="fa fa-user-edit mr-1"></i>Diupdate oleh</small>
                                <div class="font-weight-bold" id="r_updated_by">-</div>
                                <small class="text-muted" id="r_updated_at"></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kosong -->
                <div class="text-center py-5 d-none" id="rekapEmpty">
                    <i class="fa fa-inbox fa-3x text-muted d-block mb-2"></i>
                    <span class="text-muted">Belum ada data verifikasi untuk pegawai ini.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
