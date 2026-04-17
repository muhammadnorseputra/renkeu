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
                Unggah Dokumen Kinerja Non Perjanjian Kerja (PK) dalam format PDF yang di kompresi dengan ZIP/RAR. Maksimal ukuran file adalah 2MB. File yang diunggah akan menimpa file sebelumnya untuk bidang dan tahun anggaran yang sama.
            </div>
            <?php else: ?>
            <div class="alert alert-warning text-dark" role="alert">
                <i class="fa fa-exclamation-triangle mr-2"></i>
                Fitur unggah Dokumen Kinerja Non Perjanjian Kerja (PK) saat ini telah dinonaktifkan oleh administrator.
            </div>
        <?php endif; ?>
        <?php if(in_array($this->session->userdata('role'), ['ADMIN', 'VERIFICATOR', 'SUPER_ADMIN'])): ?>
        <?= form_open(base_url('app/dokuments/filter_kinerja_non_pk'), ['class' => 'form-horizontal border p-3 mb-3 mx-2 bg-light', 'id' => 'filterFormKinerjaNonPK', 'data-parsley-validate' => '']) ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filter_bidang">Filter Bidang</label>
                        <select name="filter_bidang" id="filter_bidang" class="form-control">
                            <option value="">Semua Bidang</option>
                            <?php foreach ($list_bidang as $bidang) { ?>
                                <option value="<?= $bidang->id ?>"><?= $bidang->nama ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <!-- Button submit filter -->
                <div class="col-md-3 align-self-center">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter mr-1"></i> Filter</button>
                    <!-- Button reset filter -->
                    <button type="button" class="btn btn-secondary ml-2" onclick="ResetFilter()"><i class="fa fa-repeat mr-1"></i> Reset Filter</button>
                </div>
            </div>
        <?= form_close(); ?>
        <?php endif; ?>
        <table class="table table-borderless table-hover" id="table-kinerja-non-pk">
            <thead class="thead-light">
                <tr>
                    <th style="width:5%">No</th>
                    <th>Bidang</th>
                    <th>Periode</th>
                    <th>Jenis</th>
                    <th>File</th>
                    <th>Upload By</th>
                    <th>Tahun</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Unggah Dokumen -->
<div class="modal fade" id="unggahDokumen" tabindex="-1" aria-labelledby="unggahDokumenLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <?= form_open_multipart('app/uploads/kinerja_non_pk', ['class' => 'needs-validation', 'novalidate' => '', 'data-parsley-validate' => '']) ?>
      <div class="modal-header">
        <h5 class="modal-title" id="unggahDokumenLabel">Unggah Dokumen Kinerja Non PK</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- untuk unggah dokumen / perbaikan dokumen silahkan upload ulang -->
        <div class="alert alert-info" role="alert">
        Jika ada perbaikan dokumen, silahkan unggah ulang dengan memilih file yang benar pada jenis yang sama.
        </div>
        <div class="form-group">
            <label for="periode">Periode</label>
            <select name="periode" id="periode" class="form-control" required>
                <option value="">Pilih Periode</option>
                <optgroup label="Triwulan">
                    <option value="TW1">Triwulan 1</option>
                    <option value="TW2">Triwulan 2</option>
                    <option value="TW3">Triwulan 3</option>
                    <option value="TW4">Triwulan 4</option>
                </optgroup>
             </select>
        </div>
        <div class="form-group">
            <label for="jenis_dokumen">Jenis Dokumen</label>
            <select name="jenis_dokumen" id="jenis_dokumen" class="form-control" required>
                <option value="">Pilih Jenis Dokumen</option>
                <option value="IKI">IKI</option>
                <option value="MONEV">Monev IKI</option>
            </select>
        </div>
        <div class="form-group">
            <label for="file">Pilih File PDF / Excel</label>
            <input type="file" class="form-control-file" id="file" name="file" accept=".pdf,.xlsx" required>
        </div>
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Upload</button>
    </div>
    <?= form_close(); ?>
    </div>
  </div>
</div>

<!-- Modal Verifikasi Dokumen -->
<div class="modal fade" id="verifikasiDokumen" tabindex="-1" aria-labelledby="verifikasiDokumenLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <?= form_open_multipart('app/dokuments/verifikasi_dokument_non_pk', ['class' => 'needs-validation', 'id' => 'formVerifikasiDokumenNonPK', 'novalidate' => '', 'data-parsley-validate' => ''], ['id' => '']) ?>
      <div class="modal-header">
        <h5 class="modal-title" id="verifikasiDokumenLabel">Verifikasi Dokumen Kinerja Non PK</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="nama_dokumen">Nama Dokumen</label>
            <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" disabled>
        </div>
        <div class="form-group">
            <label for="periode">Periode</label>
            <input type="text" class="form-control" id="periode" name="periode" disabled>
        </div>
        <div class="form-group">
            <label for="is_kunci">Status Verifikasi</label>
            <select name="is_kunci" id="is_kunci" class="form-control" required>
                <option value="">Pilih Status Verifikasi</option>
                <option value="1">Setuju</option>
                <option value="0">Tolak/Perbaikan</option>
            </select>
        </div>
        <div class="form-group">
            <label for="catatan">Catatan Verifikator (Opsional)</label>
            <textarea name="catatan" id="catatan" class="form-control" rows="6" placeholder="Masukkan catatan jika menolak atau meminta perbaikan"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
    <?= form_close(); ?>
    </div>
  </div>
</div>