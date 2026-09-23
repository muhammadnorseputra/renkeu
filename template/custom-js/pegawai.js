/* ============================================================
   Pegawai Page Scripts
   ============================================================ */
$(document).ready(function () {
    // Klik tombol Mapping: isi form modal dari data-* baris
    $('.btn-mapping').on('click', function () {
        var jenis = $(this).data('jenis') || 'PNS';
        var nama = $(this).data('nama') || '-';
        var jabatan = $(this).data('jabatan') || '-';
        var pangkat = $(this).data('pangkat') || '-';
        var golru = $(this).data('golru') || '-';
        var nip = $(this).data('nip') || '';

        $('#map_id').val(nip);
        $('#map_nama').text(nama);
        $('#map_jabatan').text(jabatan);
        $('#map_pangkat').text(pangkat);
        $('#map_golru').val(golru);
        $('#map_jenis').val(jenis);
        $('#map_nama_value').val(nama);
        $('#map_jabatan_value').val(jabatan);
        $('#map_pangkat_value').val(pangkat);
        $('#map_jenis_chip').text(jenis).css({
            'background': jenis === 'PNS' ? 'rgba(46, 125, 50, 0.12)' : 'rgba(234, 153, 12, 0.12)',
            'color': jenis === 'PNS' ? '#256029' : '#9a5d00',
            'border-color': jenis === 'PNS' ? 'rgba(46, 125, 50, 0.16)' : 'rgba(234, 153, 12, 0.2)'
        });
        $('#map_bidang').val($(this).data('bidang') || '');
        $('#map_sync_at').text($(this).data('created-at') || '-');
        $('#map_sync_by').text($(this).data('created-by') || '-');
        $('#map_updated_at').text($(this).data('updated-at') || '-');
        $('#map_updated_by').text($(this).data('updated-by') || '-');
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
                    nama: $('#map_nama_value').val(),
                    jabatan: $('#map_jabatan_value').val(),
                    pangkat: $('#map_pangkat_value').val(),
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