<div class="row pegawai-page-shell">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Mapping Pegawai</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php if ($api_ok): ?>
                    <div class="pegawai-summary">
                        <div class="summary-text">
                            <i class="fa fa-check-circle text-success"></i>
                            <span><?= $total_asn ?> pegawai berhasil diambil dari SILKA.</span>
                        </div>
                        <div class="summary-badges">
                            <span class="badge badge-primary">PNS: <?= count($pegawai_pns) ?></span>
                            <span class="badge badge-warning">PPPK: <?= count($pegawai_pppk) ?></span>
                            <span class="badge badge-dark">Total: <?= $total_asn ?></span>
                            <span class="badge badge-success"><i class="fa fa-check-circle"></i> Sudah Sync: <?= $sudah_sync ?></span>
                            <span class="badge badge-danger"><i class="fa fa-times-circle"></i> Belum Sync: <?= $belum_sync ?></span>
                        </div>
                    </div>

                    <!-- Tabs PNS / PPPK -->
                    <ul class="nav pegawai-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-pns" role="tab">
                                <i class="fa fa-user"></i> PNS
                                <span class="badge badge-primary"><?= count($pegawai_pns) ?></span>
                                <span class="badge badge-success" title="Sudah sync"><?= $synced_pns ?> sync</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-pppk" role="tab">
                                <i class="fa fa-user-o"></i> PPPK
                                <span class="badge badge-warning"><?= count($pegawai_pppk) ?></span>
                                <span class="badge badge-success" title="Sudah sync"><?= $synced_pppk ?> sync</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" style="margin-top: 15px;">
                        <!-- Tab PNS -->
                        <div class="tab-pane fade show active" id="tab-pns" role="tabpanel">
                            <div class="tab-card">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Pangkat</th>
                                            <th>Gol. Ruang</th>
                                            <th>Mapping</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pegawai_pns)): ?>
                                            <?php $no = 1; ?>
                                            <?php foreach ($pegawai_pns as $p): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $p['nip_baru'] ?></td>
                                                    <td><?= $p['nama_lengkap'] ?></td>
                                                    <td><?= $p['jabatan'] ?></td>
                                                    <td><?= $p['pangkat'] ?></td>
                                                    <td><?= $p['golru'] ?></td>
                                                    <td>
                                                        <?php $mapped = in_array($p['nip_baru'], $mapped_nips); ?>
                                                        <button type="button" class="btn btn-xs btn-mapping <?= $mapped ? 'btn-warning' : 'btn-primary' ?>"
                                                            data-nip="<?= $p['nip_baru'] ?>"
                                                            data-nama="<?= htmlspecialchars($p['nama_lengkap'], ENT_QUOTES) ?>"
                                                            data-jabatan="<?= htmlspecialchars($p['jabatan'], ENT_QUOTES) ?>"
                                                            data-pangkat="<?= htmlspecialchars($p['pangkat'], ENT_QUOTES) ?>"
                                                            data-golru="<?= htmlspecialchars($p['golru'], ENT_QUOTES) ?>"
                                                            data-jenis="PNS"
                                                            data-bidang="<?= isset($mapped_bidang[$p['nip_baru']]) ? $mapped_bidang[$p['nip_baru']] : '' ?>"
                                                            data-created-at="<?= isset($mapped_pegawai[$p['nip_baru']]) ? $mapped_pegawai[$p['nip_baru']]->created_at : '' ?>"
                                                            data-created-by="<?= isset($mapped_pegawai[$p['nip_baru']]) ? $mapped_pegawai[$p['nip_baru']]->created_by : '' ?>"
                                                            data-updated-at="<?= isset($mapped_pegawai[$p['nip_baru']]) ? $mapped_pegawai[$p['nip_baru']]->updated_at : '' ?>"
                                                            data-updated-by="<?= isset($mapped_pegawai[$p['nip_baru']]) ? $mapped_pegawai[$p['nip_baru']]->updated_by : '' ?>">
                                                            <i class="fa fa-link"></i> <?= $mapped ? 'Mapping Ulang' : 'Mapping' ?>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data PNS.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab PPPK -->
                        <div class="tab-pane fade" id="tab-pppk" role="tabpanel">
                            <div class="tab-card">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIP</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Gol. Ruang</th>
                                            <th>Mapping</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pegawai_pppk)): ?>
                                            <?php $no = 1; ?>
                                            <?php foreach ($pegawai_pppk as $p): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $p['nipppk'] ?></td>
                                                    <td><?= $p['nama_lengkap'] ?></td>
                                                    <td><?= $p['jabatan'] ?></td>
                                                    <td><?= $p['golru'] ?></td>
                                                    <td>
                                                        <?php $mapped = in_array($p['nipppk'], $mapped_nips); ?>
                                                        <button type="button" class="btn btn-xs btn-mapping <?= $mapped ? 'btn-warning' : 'btn-primary' ?>"
                                                            data-nip="<?= $p['nipppk'] ?>"
                                                            data-nama="<?= htmlspecialchars($p['nama_lengkap'], ENT_QUOTES) ?>"
                                                            data-jabatan="<?= htmlspecialchars(isset($p['jabatan']) ? $p['jabatan'] : '', ENT_QUOTES) ?>"
                                                            data-pangkat="<?= htmlspecialchars(isset($p['pangkat']) ? $p['pangkat'] : '', ENT_QUOTES) ?>"
                                                            data-golru="<?= htmlspecialchars(isset($p['golru']) ? $p['golru'] : '', ENT_QUOTES) ?>"
                                                            data-jenis="PPPK"
                                                            data-bidang="<?= isset($mapped_bidang[$p['nipppk']]) ? $mapped_bidang[$p['nipppk']] : '' ?>"
                                                            data-created-at="<?= isset($mapped_pegawai[$p['nipppk']]) ? $mapped_pegawai[$p['nipppk']]->created_at : '' ?>"
                                                            data-created-by="<?= isset($mapped_pegawai[$p['nipppk']]) ? $mapped_pegawai[$p['nipppk']]->created_by : '' ?>"
                                                            data-updated-at="<?= isset($mapped_pegawai[$p['nipppk']]) ? $mapped_pegawai[$p['nipppk']]->updated_at : '' ?>"
                                                            data-updated-by="<?= isset($mapped_pegawai[$p['nipppk']]) ? $mapped_pegawai[$p['nipppk']]->updated_by : '' ?>">
                                                            <i class="fa fa-link"></i> <?= $mapped ? 'Mapping Ulang' : 'Mapping' ?>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data PPPK.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted small">
                        <i class="fa fa-database"></i> Sumber: silka.balangankab.go.id
                    </p>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-triangle"></i> Gagal mengambil data dari API SILKA. Silakan coba lagi nanti.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mapping Pegawai -->
<div class="modal fade mapping-modal" id="modalMapping" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-link"></i> Mapping Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formMapping">
                <div class="modal-body">
                    <div class="mapping-hero">
                        <div class="mapping-avatar">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="mapping-identity">
                            <div class="mapping-kicker">Data ASN</div>
                            <h5 class="mapping-name" id="map_nama">-</h5>
                            <p class="mapping-sub"><span id="map_jabatan">-</span> • <span id="map_pangkat">-</span></p>
                        </div>
                        <span class="mapping-chip" id="map_jenis_chip">PNS</span>
                    </div>

                    <div class="mapping-form-grid">
                        <div class="mapping-field">
                            <label>ID (NIP)</label>
                            <input type="text" class="form-control" id="map_id" readonly>
                        </div>
                        <div class="mapping-field">
                            <label>Golongan</label>
                            <input type="text" class="form-control" id="map_golru" readonly>
                        </div>
                        <div class="mapping-field full">
                            <label>Bidang</label>
                            <select class="form-control" id="map_bidang">
                                <option value="">-- Pilih Bidang --</option>
                                <?php foreach ($list_bidang as $b): ?>
                                    <option value="<?= $b->id ?>"><?= $b->nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" id="map_jenis">
                    <input type="hidden" id="map_nama_value">
                    <input type="hidden" id="map_jabatan_value">
                    <input type="hidden" id="map_pangkat_value">

                    <div class="mapping-audit">
                        <div class="mapping-audit-head">
                            <i class="fa fa-history"></i> Riwayat Sinkronisasi
                        </div>
                        <div class="mapping-audit-grid">
                            <div class="mapping-meta">
                                <div class="meta-title"><i class="fa fa-clock-o text-primary"></i> Sync At</div>
                                <div class="meta-value" id="map_sync_at">-</div>
                            </div>
                            <div class="mapping-meta">
                                <div class="meta-title"><i class="fa fa-user text-primary"></i> Sync By</div>
                                <div class="meta-value" id="map_sync_by">-</div>
                            </div>
                            <div class="mapping-meta">
                                <div class="meta-title"><i class="fa fa-history text-info"></i> Updated At</div>
                                <div class="meta-value" id="map_updated_at">-</div>
                            </div>
                            <div class="mapping-meta">
                                <div class="meta-title"><i class="fa fa-user-edit text-info"></i> Updated By</div>
                                <div class="meta-value" id="map_updated_by">-</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnBatalSync">
                        <i class="fa fa-trash"></i> <span>Batal Sync</span>
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSync">
                        <i class="fa fa-refresh"></i> <span>Sync</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

