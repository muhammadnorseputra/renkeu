<div class="row">
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
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i> <?= $total_asn ?> pegawai berhasil diambil dari SILKA.
                        <span class="pull-right">
                            <span class="badge badge-primary">PNS: <?= count($pegawai_pns) ?></span>
                            <span class="badge badge-warning">PPPK: <?= count($pegawai_pppk) ?></span>
                            <span class="badge badge-dark">Total: <?= count($pegawai_pns) + count($pegawai_pppk) ?></span>
                        </span>
                    </div>

                    <!-- Tabs PNS / PPPK -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-pns" role="tab">
                                <i class="fa fa-user"></i> PNS
                                <span class="badge badge-primary"><?= count($pegawai_pns) ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-pppk" role="tab">
                                <i class="fa fa-user-o"></i> PPPK
                                <span class="badge badge-warning"><?= count($pegawai_pppk) ?></span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" style="margin-top: 15px;">
                        <!-- Tab PNS -->
                        <div class="tab-pane fade show active" id="tab-pns" role="tabpanel">
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
                                                        data-bidang="<?= isset($mapped_bidang[$p['nip_baru']]) ? $mapped_bidang[$p['nip_baru']] : '' ?>">
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

                        <!-- Tab PPPK -->
                        <div class="tab-pane fade" id="tab-pppk" role="tabpanel">
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
                                                        data-jabatan="<?= htmlspecialchars($p['jabatan'], ENT_QUOTES) ?>"
                                                        data-pangkat="<?= htmlspecialchars($p['golru'], ENT_QUOTES) ?>"
                                                        data-golru="<?= htmlspecialchars($p['golru'], ENT_QUOTES) ?>"
                                                        data-jenis="PPPK"
                                                        data-bidang="<?= isset($mapped_bidang[$p['nipppk']]) ? $mapped_bidang[$p['nipppk']] : '' ?>">
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
<div class="modal fade" id="modalMapping" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-link"></i> Mapping Pegawai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formMapping">
                <div class="modal-body">
                    <div class="form-group">
                        <label>ID (NIP)</label>
                        <input type="text" class="form-control" id="map_id" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" id="map_nama" readonly>
                    </div>
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" id="map_jabatan" readonly>
                    </div>
                    <div class="form-group">
                        <label>Pangkat</label>
                        <input type="text" class="form-control" id="map_pangkat" readonly>
                    </div>
                    <div class="form-group">
                        <label>Golongan</label>
                        <input type="text" class="form-control" id="map_golru" readonly>
                    </div>
                    <input type="hidden" id="map_jenis">
                    <div class="form-group">
                        <label>Bidang</label>
                        <select class="form-control" id="map_bidang">
                            <option value="">-- Pilih Bidang --</option>
                            <?php foreach ($list_bidang as $b): ?>
                                <option value="<?= $b->id ?>"><?= $b->nama ?></option>
                            <?php endforeach; ?>
                        </select>
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

<script>
    $(document).ready(function () {
        // Klik tombol Mapping: isi form modal dari data-* baris
        $('.btn-mapping').on('click', function () {
            $('#map_id').val($(this).data('nip'));
            $('#map_nama').val($(this).data('nama'));
            $('#map_jabatan').val($(this).data('jabatan'));
            $('#map_pangkat').val($(this).data('pangkat'));
            $('#map_golru').val($(this).data('golru'));
            $('#map_jenis').val($(this).data('jenis'));
            $('#map_bidang').val($(this).data('bidang') || '');
            $('#modalMapping').modal('show');
        });

        // Klik Batal Sync: hapus data pegawai dari tabel pegawai
        $('#btnBatalSync').on('click', function () {
            var nip = $('#map_id').val();
            if (!nip) {
                alert('NIP tidak ditemukan.');
                return;
            }
            if (!confirm('Yakin ingin menghapus data pegawai NIP ' + nip + ' dari mapping?')) {
                return;
            }
            var $btn = $(this);
            var $icon = $btn.find('i');
            var $label = $btn.find('span');
            $btn.prop('disabled', true);
            $icon.removeClass('fa-trash').addClass('fa-spinner fa-spin');
            $label.text('Menghapus...');

            $.ajax({
                url: _uri + '/app/pegawai/delete_mapping',
                type: 'post',
                data: { nip: nip },
                dataType: 'json',
                success: function (res) {
                    if (res.status) {
                        $('#modalMapping').modal('hide');
                        location.reload();
                    } else {
                        alert(res.pesan);
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                },
                complete: function () {
                    $btn.prop('disabled', false);
                    $icon.removeClass('fa-spinner fa-spin').addClass('fa-trash');
                    $label.text('Batal Sync');
                }
            });
        });

        // Submit form mapping
        $('#formMapping').on('submit', async function (e) {
            e.preventDefault();
            var $btn = $('#btnSync');
            var $icon = $btn.find('i');
            var $label = $btn.find('span');
            $btn.prop('disabled', true);
            $icon.removeClass('fa-refresh').addClass('fa-spinner fa-spin');
            $label.text('Menyimpan...');

            try {
                const res = await $.ajax({
                    url: _uri + '/app/pegawai/save_mapping',
                    type: 'post',
                    data: {
                        nip: $('#map_id').val(),
                        nama: $('#map_nama').val(),
                        jabatan: $('#map_jabatan').val(),
                        pangkat: $('#map_pangkat').val(),
                        jenis: $('#map_jenis').val(),
                        bidang: $('#map_bidang').val()
                    },
                    dataType: 'json'
                });

                if (res.status) {
                    $('#modalMapping').modal('hide');
                    location.reload();
                } else {
                    alert(res.pesan);
                }
            } catch (err) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                $btn.prop('disabled', false);
                $icon.removeClass('fa-spinner fa-spin').addClass('fa-refresh');
                $label.text('Sync');
            }
        });
    });
</script>