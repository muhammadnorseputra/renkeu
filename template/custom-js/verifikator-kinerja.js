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
                    url: _uri + '/app/verifikatorKinerja/get_rekap_all',
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
                url: _uri + '/app/verifikatorKinerja/get_periode_terisi',
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
                url: _uri + '/app/verifikatorKinerja/get_rekap',
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
                    url: _uri + '/app/verifikatorKinerja/get_verifikasi',
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

        // Dropzone rekon: klik, drag & drop, preview, hapus
        var $dropzone = $('#rekonDropzone');
        var $dropEmpty = $('#rekonDropEmpty');
        var $dropFile = $('#rekonDropFile');
        var $rekonFile = $('#rekonFile');
        var rekonSelectedFile = null;

        function formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            var k = 1024, sizes = ['B', 'KB', 'MB', 'GB'], i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        function showRekonFile(file) {
            rekonSelectedFile = file;
            $('#rekonFileName').text(file.name);
            $('#rekonFileSize').text(formatBytes(file.size));
            $dropEmpty.addClass('d-none');
            $dropFile.removeClass('d-none');
        }
        function clearRekonFile() {
            rekonSelectedFile = null;
            $rekonFile.val('');
            $dropEmpty.removeClass('d-none');
            $dropFile.addClass('d-none');
        }
        $dropzone.on('click', function (e) {
            if ($(e.target).closest('#rekonRemoveFile').length) return;
            // klik buatan pada input #rekonFile ikut bubble ke handler ini; stop agar tidak rekursif
            if (e.target.id === 'rekonFile') return;
            $rekonFile.trigger('click');
        });
        $rekonFile.on('change', function () {
            if (this.files.length) showRekonFile(this.files[0]);
        });
        $('#rekonRemoveFile').on('click', function (e) {
            e.stopPropagation();
            clearRekonFile();
        });
        $dropzone.on('dragover', function (e) {
            e.preventDefault();
            $dropzone.addClass('dragover');
        });
        $dropzone.on('dragleave drop', function () {
            $dropzone.removeClass('dragover');
        });
        $dropzone.on('drop', function (e) {
            e.preventDefault();
            var files = e.originalEvent.dataTransfer.files;
            if (files.length) showRekonFile(files[0]);
        });

        // Submit rekonsiliasi data SAKIPRA (dengan progress upload)
        $('#formRekonSakipra').on('submit', async function (e) {
            e.preventDefault();
            var $btn = $('#btnRekonSakipra');
            var $result = $('#rekonResult');
            var $progress = $('#rekonProgress');
            var $bar = $('#rekonProgressBar');
            var $pct = $('#rekonProgressPct');
            var $label = $('#rekonProgressLabel');
            var $info = $('#rekonProgressInfo');

            if (!rekonSelectedFile) {
                $result.removeClass('d-none alert-success alert-danger').addClass('alert-warning')
                    .html('<i class="fa fa-exclamation-triangle mr-1"></i>File Excel belum dipilih.');
                return;
            }

            $btn.prop('disabled', true);
            $btn.find('i').removeClass('fa-refresh').addClass('fa-spinner fa-spin');
            $result.addClass('d-none');
            $progress.removeClass('d-none done');
            $bar.css('width', '0%').attr('aria-valuenow', 0);
            $pct.text('0%');
            $label.text('Mengunggah file...');
            $info.text('');

            // FormData manual: hindari duplikasi entry 'file' (input + rekonSelectedFile)
            var formData = new FormData();
            formData.append('file', rekonSelectedFile);

            try {
                const res = await $.ajax({
                    url: _uri + '/app/verifikatorKinerja/rekon_sakipra',
                    type: 'post',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function (evt) {
                            if (!evt.lengthComputable) return;
                            var percent = Math.round((evt.loaded / evt.total) * 100);
                            $bar.css('width', percent + '%').attr('aria-valuenow', percent);
                            $pct.text(percent + '%');
                            $info.text(formatBytes(evt.loaded) + ' / ' + formatBytes(evt.total));
                            if (percent >= 100) {
                                $label.text('Memproses data...');
                            }
                        }, false);
                        return xhr;
                    }
                });

                $bar.css('width', '100%').attr('aria-valuenow', 100);
                $pct.text('100%');
                $progress.addClass('done');
                $label.text(res.status ? 'Selesai' : 'Gagal');
                $info.text(res.pesan);

                $result.removeClass('d-none').addClass(res.status ? 'alert-success' : 'alert-danger')
                    .html('<i class="fa ' + (res.status ? 'fa-check-circle' : 'fa-times-circle') + ' mr-1"></i>' + res.pesan);
                if (res.status) {
                    $('#formRekonSakipra')[0].reset();
                    clearRekonFile();
                }
            } catch (err) {
                $progress.addClass('done');
                $label.text('Gagal');
                $result.removeClass('d-none').addClass('alert-danger')
                    .html('<i class="fa fa-times-circle mr-1"></i>Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                $btn.prop('disabled', false);
                $btn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-refresh');
            }
        });

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
                    url: _uri + '/app/verifikatorKinerja/save_verifikasi',
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
