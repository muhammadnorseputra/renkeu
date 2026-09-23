$(document).ready(function () {
        var tahun = window.vkTahun || new Date().getFullYear();
        var privEdit = window.vkPrivEdit || false;

        // DataTable rekapitulasi seluruh pegawai
        if ($.fn.DataTable) {
            var rekapTable = $('#tableRekapAll').DataTable({
                processing: true,
                serverSide: true,
                responsive: {
                    details: {
                        type: 'column',
                        target: 0
                    }
                },
                order: [],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
                ajax: {
                    url: _uri + '/app/verifikatorkinerja/get_rekap_all',
                    type: 'POST'
                },
                columnDefs: [
                    // No: expand/collapse toggle + selalu tampil
                    { targets: 0, orderable: false, className: 'text-center control', width: '30px', responsivePriority: 1 },
                    // Kolom yang selalu tampil: NIP, Nama, TW I-IV, Total, Status
                    { targets: 1, orderable: true, className: 'text-left', responsivePriority: 2 },
                    { targets: 2, orderable: true, className: 'text-left', responsivePriority: 2 },
                    { targets: [8, 9, 10, 11], orderable: false, className: 'text-center', responsivePriority: 3 },
                    { targets: 12, orderable: false, className: 'text-center', responsivePriority: 3 },
                    { targets: 13, orderable: false, className: 'text-center', responsivePriority: 3 },
                    // Kolom yang collapse ke child row saat layar sempit: Jabatan, Pangkat, Jenis, Bidang
                    { targets: [3, 4, 5, 6], orderable: true, className: 'text-left', visible: false, responsivePriority: 10 },
                    { targets: 7, orderable: true, className: 'text-left', responsivePriority: 10 }
                ],
                buttons: [
                    { extend: 'colvis', text: '<i class="fa fa-columns"></i> Kolom', className: 'btn btn-sm btn-outline-primary' },
                    {
                        text: '<i class="fa fa-refresh"></i> Refresh',
                        className: 'btn btn-sm btn-outline-success',
                        action: function () { rekapTable.ajax.reload(null, false); }
                    }
                ],
                dom: "<'row'<'col-sm-6'l><'col-sm-6'B>>" +
                     "<'row'<'col-sm-6'i><'col-sm-6'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-5'p><'col-sm-7'>>",
                language: {
                    processing: 'Memuat data...',
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ pegawai',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(disaring dari _MAX_ total)',
                    zeroRecords: 'Data tidak ditemukan',
                    paginate: { first: 'Awal', last: 'Akhir', next: 'Berikutnya', previous: 'Sebelumnya' },
                    buttons: { colvis: 'Kolom', colvisRestore: 'Pulihkan' }
                }
            });

            // Sesuaikan lebar kolom saat tab rekap dibuka (init dalam tab tersembunyi)
            $('a[href="#tab-rekap"]').on('shown.bs.tab', function () {
                rekapTable.columns.adjust().responsive.recalc();
            });
        }

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
