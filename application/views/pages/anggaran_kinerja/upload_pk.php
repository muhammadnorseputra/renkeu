<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-book mr-2"></i> Dokumen Perjanjian Kinerja</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <?php if ($this->session->flashdata('alert_msg')): ?>
            <div class="alert alert-<?= $this->session->flashdata('alert_type') === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-<?= $this->session->flashdata('alert_type') === 'success' ? 'check-circle' : 'exclamation-circle' ?> mr-2"></i>
                <?= $this->session->flashdata('alert_msg') ?>
            </div>
        <?php endif; ?>

        <?php if(getSetting('DokumenPK')): ?>
            <div class="alert alert-info" role="alert">
                <i class="fa fa-info-circle mr-2"></i>
                Unggah dokumen Perjanjian Kinerja (PK) dalam format PDF. Maksimal ukuran file adalah 2MB. File yang diunggah akan menimpa file sebelumnya untuk bidang dan tahun anggaran yang sama.
            </div>
            <?php else: ?>
            <div class="alert alert-warning text-dark" role="alert">
                <i class="fa fa-exclamation-triangle mr-2"></i>
                Fitur unggah dokumen Perjanjian Kinerja (PK) saat ini telah dinonaktifkan oleh administrator.
            </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-borderless table-hover">
                <thead class="thead-light">
                    <tr>
                        <th style="width:5%">No</th>
                        <th style="width:30%">Bidang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($bidang->result() as $row) : ?>
                        <?php
                            $filename = 'PK_' . $row->id . '_' . $this->session->userdata('tahun_anggaran') . '.pdf';
                            $server_path = FCPATH . 'template/upload/dokumen_pk/' . $filename;
                            $public_url  = base_url('template/upload/dokumen_pk/' . $filename);
                            $file_exists = file_exists($server_path) && is_file($server_path);
                            $uid = 'file_' . $row->id;
                        ?>
                        <tr>
                            <td class="align-middle"><?= $no++ ?></td>
                            <td class="align-middle"><?= htmlspecialchars($row->nama) ?></td>
                            <td>
                                <div class="d-flex align-items-center" style="gap:0.75rem; flex-wrap:wrap;">
                                    <?php if(getSetting('DokumenPK')): ?>
                                    <!-- Modern Upload Card -->
                                    <form action="<?= base_url('app/target/upload_pk') ?>" method="post" enctype="multipart/form-data" class="d-flex align-items-center" style="gap:.5rem; flex:1; min-width:0;">
                                        <input type="hidden" name="part_id" value="<?= $row->id ?>">

                                        <div class="custom-file" style="flex:1; min-width:0;">
                                            <input type="file" name="dokumen_pk" id="<?= $uid ?>" accept="application/pdf" class="custom-file-input" required style="display:none;">
                                            <label for="<?= $uid ?>" class="btn btn-outline-secondary btn-block text-truncate mb-0" style="text-align:left; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                <i class="fa fa-file-pdf-o text-danger mr-2" aria-hidden="true"></i>
                                                <span class="file-label">Pilih file PDF...</span>
                                            </label>
                                        </div>

                                        <button type="submit" class="btn btn-primary" style="white-space:nowrap;">
                                            <i class="fa fa-upload mr-1"></i> Upload
                                        </button>
                                    </form>
                                    <?php endif; ?>

                                    <!-- Download / Status -->
                                    <div class="d-flex align-items-center" style="gap:.5rem; white-space:nowrap;">
                                        <?php if ($file_exists): ?>
                                            <a href="<?= $public_url ?>" target="_blank" rel="noopener" class="btn btn-success" title="Unduh dokumen PK">
                                                <i class="fa fa-download mr-1"></i> Unduh
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-outline-secondary" disabled title="File belum tersedia">
                                                <i class="fa fa-download mr-1"></i> Unduh
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Inline JS to improve UX: show chosen filename and support keyboard/drag lightly -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[type="file"][name="dokumen_pk"]').forEach(function (input) {
        var label = input.closest('.custom-file')?.querySelector('.file-label') || null;
        if (!label) return;

        input.addEventListener('change', function (e) {
            var f = e.target.files[0];
            if (f) {
                label.textContent = f.name;
                label.title = f.name;
            } else {
                label.textContent = 'Pilih file PDF...';
            }
        });

        // allow clicking the visible label to open file dialog
        var visibleLabel = input.closest('.custom-file')?.querySelector('label[for="' + input.id + '"]');
        if (visibleLabel) {
            visibleLabel.addEventListener('keydown', function (ev) {
                if (ev.key === 'Enter' || ev.key === ' ') {
                    ev.preventDefault();
                    input.click();
                }
            });
        }
    });
});
</script>