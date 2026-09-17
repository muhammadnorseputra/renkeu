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
        </div>
    </div>
</div>

<!-- Modal Verifikasi Kinerja -->
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

<style>
    /* Accordion */
    .accordion-card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .accordion-card .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        cursor: pointer;
        transition: background .3s;
    }
    .accordion-card .card-header:hover {
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    }
    .accordion-card .card-header[aria-expanded="true"] {
        background: linear-gradient(135deg, #e8f0fe 0%, #dbe7fb 100%);
    }
    .bidang-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a73e8, #4285f4);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 2px 6px rgba(26, 115, 232, .3);
        flex-shrink: 0;
    }
    .accordion-chevron {
        transition: transform .3s;
    }
    .accordion-card .card-header[aria-expanded="true"] .accordion-chevron {
        transform: rotate(180deg);
    }

    /* Toggle switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .3s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }
    .switch input:checked + .slider {
        background-color: #28a745;
    }
    .switch input:checked + .slider:before {
        transform: translateX(20px);
    }

    /* Overlay loading checklist */
    .checklist-loading {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5;
        border-radius: 4px;
    }
</style>

<script>
    $(document).ready(function () {
        var tahun = new Date().getFullYear();
        var privEdit = <?= $priv_edit ? 'true' : 'false' ?>;

        // Klik tombol Checklist: tampilkan modal verifikasi
        $('.btn-verifikasi').on('click', function () {
            var nip = $(this).data('nip');
            $('#v_nip').text(nip);
            $('#v_nama').text($(this).data('nama'));
            $('#modalVerifikasi').modal('show');

            // Reset checklist & periode
            $('.chk-verifikasi').prop('checked', false);
            $('.chk-status').removeClass('badge-success badge-secondary').addClass('badge-secondary').text('Tidak');
            $('.periode-btn').removeClass('active');
            $('.periode-btn input').prop('checked', false);
            $('.periode-icon i').removeClass('fa-check-circle text-success').addClass('fa-circle-o text-warning');

            // Sembunyikan tombol Simpan jika read-only
            if (!privEdit) {
                $('#btnSimpanVerifikasi').hide();
            } else {
                $('#btnSimpanVerifikasi').show();
            }

            // Auto-select periode yang sudah terisi di DB (jika ada)
            $.ajax({
                url: _uri + '/app/verifikatorkinerja/get_periode_terisi',
                type: 'post',
                data: { nip: nip, tahun: tahun },
                dataType: 'json',
                success: function (res) {
                    var periode = 'TW1';
                    if (res.status && res.periode && res.periode.length) {
                        periode = res.periode[0];
                    }
                    // Icon status per periode: hijau jika ada ceklist, kuning jika kosong
                    if (res.status_periode) {
                        $.each(res.status_periode, function (p, st) {
                            var $icon = $('.periode-btn[data-periode="' + p + '"] .periode-icon i');
                            if ($icon.length) {
                                $icon.removeClass('fa-circle-o text-warning fa-check-circle text-success')
                                    .addClass(st === 'Y' ? 'fa-check-circle text-success' : 'fa-circle-o text-warning');
                            }
                        });
                    }
                    // Auto-select radio periode
                    $('.periode-btn[data-periode="' + periode + '"]').addClass('active');
                    $('.periode-btn[data-periode="' + periode + '"] input').prop('checked', true);

                    // Load data verifikasi per NIP + periode
                    loadVerifikasi(nip, periode);
                },
                error: function () {
                    loadVerifikasi(nip, 'TW1');
                }
            });
        });

        // Ganti periode: load ulang checklist
        $('.periode-btn').on('click', function () {
            loadVerifikasi($('#v_nip').text(), $(this).data('periode'));
        });

        // Klik tombol Rekap: tampilkan modal rekapitulasi
        $('.btn-rekap').on('click', function () {
            var nip = $(this).data('nip');
            $('#r_nip').text(nip);
            $('#r_nama').text($(this).data('nama'));
            $('#modalRekap').modal('show');

            $('#rekapTable tbody').empty();
            $('#rekapTableWrap').addClass('d-none');
            $('#rekapEmpty').addClass('d-none');
            $('#rekapLoading').removeClass('d-none');

            $.ajax({
                url: _uri + '/app/verifikatorkinerja/get_rekap',
                type: 'post',
                data: { nip: nip, tahun: tahun },
                dataType: 'json',
                success: function (res) {
                    $('#rekapLoading').addClass('d-none');
                    if (!res.status || !res.data || !Object.keys(res.data).length) {
                        $('#rekapEmpty').removeClass('d-none');
                        return;
                    }
                    renderRekap(res.data);
                    if (res.audit) {
                        $('#r_created_by').text(res.audit.created_by || '-');
                        $('#r_created_at').text(res.audit.created_at ? 'pada ' + res.audit.created_at : '');
                        $('#r_updated_by').text(res.audit.updated_by || '-');
                        $('#r_updated_at').text(res.audit.updated_at ? 'pada ' + res.audit.updated_at : '');
                    }
                    $('#rekapTableWrap').removeClass('d-none');
                },
                error: function () {
                    $('#rekapLoading').addClass('d-none');
                    $.notify('Gagal memuat rekapitulasi. Silakan coba lagi.', {
                        timer: 800,
                        delay: 100,
                        type: 'danger'
                    });
                }
            });
        });

        function renderRekap(matrix) {
            var $tbody = $('#rekapTable tbody');
            var periodeKeys = ['TW1', 'TW2', 'TW3', 'TW4'];
            var checklist = [
                ['unggah_kinerja_harian', 'Unggah Kinerja Harian', 'fa-cloud-upload'],
                ['target_realisasi', 'Penginputan Target dan Realisasi', 'fa-bullseye'],
                ['masalah_tindak_lanjut', 'Penginputan Masalah dan Tindak Lanjut (MTL)', 'fa-exclamation-triangle'],
                ['diskusi_kinerja', 'Keterisian Diskusi Kinerja', 'fa-comments'],
                ['data_dukung', 'Unggah Data Dukung Kinerja', 'fa-paperclip'],
                ['simpulan_capaian', 'Simpulan Capaian Kinerja', 'fa-flag-checkered']
            ];

            var no = 1;
            $.each(checklist, function (i, item) {
                var total = 0;
                var $cells = '';
                $.each(periodeKeys, function (j, p) {
                    var val = (matrix[p] && matrix[p][item[0]]) || 'N';
                    if (val === 'Y') total++;
                    $cells += '<td>' +
                        (val === 'Y'
                            ? '<span class="badge badge-success badge-pill px-3 py-2"><i class="fa fa-check mr-1"></i>Ya</span>'
                            : '<span class="badge badge-secondary badge-pill px-3 py-2"><i class="fa fa-times mr-1"></i>Tidak</span>') +
                        '</td>';
                });
                $tbody.append(
                    '<tr>' +
                    '<td class="align-middle">' + no++ + '</td>' +
                    '<td class="text-left align-middle"><i class="fa ' + item[2] + ' text-primary mr-2"></i>' + item[1] + '</td>' +
                    $cells +
                    '<td class="align-middle"><span class="badge ' + (total === 4 ? 'badge-success' : total > 0 ? 'badge-warning' : 'badge-secondary') + ' badge-pill px-3 py-2">' + total + '/4</span></td>' +
                    '</tr>'
                );
            });
        }

        async function loadVerifikasi(nip, periode) {
            periode = periode || $('.periode-btn input:checked').val() || 'TW1';
            $('.chk-verifikasi').prop('checked', false);
            $('.chk-status').removeClass('badge-success badge-secondary').addClass('badge-secondary').text('Tidak');

            // Tampilkan loading
            $('.checklist-loading').removeClass('d-none');
            $('.chk-verifikasi').prop('disabled', true);

            try {
                // Delay 1 detik agar loading terlihat
                await new Promise(resolve => setTimeout(resolve, 1000));

                const res = await $.ajax({
                    url: _uri + '/app/verifikatorkinerja/get_verifikasi',
                    type: 'post',
                    data: { nip: nip, periode: periode, tahun: tahun },
                    dataType: 'json'
                });

                if (res.status && res.data) {
                    $.each(res.data, function (key, val) {
                        if (val === 'Y') {
                            var $chk = $('.chk-verifikasi[name="' + key + '"]');
                            if ($chk.length) {
                                $chk.prop('checked', true);
                            }
                            var $st = $('.chk-status[data-key="' + key + '"]');
                            if ($st.length) {
                                $st.removeClass('badge-secondary').addClass('badge-success').text('Ya');
                            }
                        }
                    });
                }
            } catch (err) {
                $.notify('Gagal memuat data verifikasi. Silakan coba lagi.', {
                    timer: 800,
                    delay: 100,
                    type: 'danger'
                });
            } finally {
                $('.checklist-loading').addClass('d-none');
                $('.chk-verifikasi').prop('disabled', false);
            }
        }

        // Submit form verifikasi
        $('#formVerifikasi').on('submit', async function (e) {
            e.preventDefault();
            var $btn = $('#btnSimpanVerifikasi');
            var $icon = $btn.find('i');
            var $label = $btn.find('span');
            $btn.prop('disabled', true);
            $icon.removeClass('fa-save').addClass('fa-spinner fa-spin');
            $label.text('Menyimpan...');

            var data = {
                nip: $('#v_nip').text(),
                periode: $('.periode-btn input:checked').val() || 'TW1',
                tahun: tahun
            };
            $('.chk-verifikasi').each(function () {
                data[$(this).attr('name')] = $(this).is(':checked') ? 'Y' : 'N';
            });

            try {
                const res = await $.ajax({
                    url: _uri + '/app/verifikatorkinerja/save_verifikasi',
                    type: 'post',
                    data: data,
                    dataType: 'json'
                });

                if (res.status) {
                    $('#modalVerifikasi').modal('hide');
                    $.notify(res.pesan, {
                        timer: 800,
                        delay: 100,
                        type: 'success'
                    });
                } else {
                    $.notify(res.pesan, {
                        timer: 800,
                        delay: 100,
                        type: 'danger'
                    });
                }
            } catch (err) {
                $.notify('Terjadi kesalahan. Silakan coba lagi.', {
                    timer: 800,
                    delay: 100,
                    type: 'danger'
                });
            } finally {
                $btn.prop('disabled', false);
                $icon.removeClass('fa-spinner fa-spin').addClass('fa-save');
                $label.text('Simpan');
            }
        });
    });
</script>