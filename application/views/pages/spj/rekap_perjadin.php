<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-book mr-2"></i> Rekapitulasi Perjalanan Dinas</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <?php if ($this->session->flashdata('alert_msg')): ?>
            <div class="d-flex alert alert-<?= $this->session->flashdata('alert_type') === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-<?= $this->session->flashdata('alert_type') === 'success' ? 'check-circle' : 'exclamation-circle' ?> mr-2"></i>
                <?= $this->session->flashdata('alert_msg') ?>
            </div>
        <?php endif; ?>

        <div class="alert alert-info" role="alert">
            <i class="fa fa-info-circle mr-2"></i>
            Unggah Dokumen dalam format PDF / Excel. Maksimal ukuran file adalah 2MB. File yang diunggah akan menimpa file sebelumnya untuk bulan dan tahun anggaran yang sama.
            Silahkan unduh template yang sudah disediakan untuk memastikan format file yang benar. <br/> <a href="<?= base_url('/template/template_rekap_perjadin.xlsx') ?>" class="alert-link btn btn-sm btn-warning"><i class="fa fa-file"></i> Unduh Template</a>.
        </div>
        <div class="alert alert-warning" role="alert">
            <i class="fa fa-exclamation-triangle mr-2"></i>
            Pastikan file yang diunggah sudah benar, karena jika sudah dikunci oleh admin, file tidak dapat diubah atau dihapus.
        </div>
        <?php if(in_array($this->session->userdata('role'), ['ADMIN', 'VERIFICATOR'])): ?>
        <?= form_open(base_url('app/spj/filter_rekap_perjadin'), ['class' => 'form-horizontal border p-3 mb-3 mx-2 bg-light', 'id' => 'filterForm', 'data-parsley-validate' => '']) ?>
            <div class="row">
                <div class="col-md-3 border-right">
                    <div class="form-group">
                        <label for="filter_bulan">Filter Bulan</label>
                        <select name="filter_bulan" id="filter_bulan" class="form-control">
                            <option value="">Semua Bulan</option>
                            <?php foreach (bulanIndo() as $key => $value) {
                                $selected = ($key == date('m')) ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            } ?>
                        </select>
                    </div>
                </div>
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
        <table class="table table-borderless table-hover" id="table-rekap-perjadin">
                <thead class="thead-light">
                    <tr>
                        <th style="width:5%">No</th>
                        <th>Bidang</th>
                        <th>Bulan</th>
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
        <?= form_open_multipart('app/spj/upload_rekap_perjadin', ['class' => 'needs-validation', 'novalidate' => '', 'data-parsley-validate' => '']) ?>
      <div class="modal-header">
        <h5 class="modal-title" id="unggahDokumenLabel">Unggah Dokumen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- untuk unggah dokumen / perbaikan dokumen silahkan upload ulang -->
         <div class="alert alert-info" role="alert">
            Jika ada perbaikan dokumen, silahkan unggah ulang dengan memilih file yang benar pada bulan dan tahun yang sama.
         </div>
        <div class="form-group">
            <label for="file">Pilih File PDF</label>
            <input type="file" class="form-control-file" id="file" name="file" accept=".pdf" required>
        </div>
        <div class="form-group">
            <label for="bulan">Rekapitulasi Bulan</label>
            <select class="form-control" id="bulan" name="bulan" required>
                <?php foreach (bulanIndo() as $key => $value) {
                    $bulanSekarang = date('m');
                    $selected = ($key == $bulanSekarang) ? 'selected' : '';

                    // Cek apakah sudah ada file untuk bulan dan tahun yg sama
                    $getIsKunci = $this->crud->getWhere('t_dokumen_perjadin', ['bulan' => $key, 'tahun' => $this->session->userdata('tahun_anggaran'), 'fid_part' => $this->session->userdata('part')]);
                    $disabled = ($getIsKunci && $getIsKunci->row()->is_kunci) ? 'disabled' : '';
                    echo '<option value="'.$key.'" '.$selected.' '.$disabled.'>'.$value.'</option>';

                } ?>
            </select>
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

<!-- Modal Tambah Catatan -->
<div class="modal fade" id="tambahCatatan" tabindex="-1" aria-labelledby="tambahCatatanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <?= form_open_multipart('app/spj/catatan_rekap_perjadin', ['class' => 'needs-validation', 'novalidate' => '', 'data-parsley-validate' => '']) ?>
        <input type="hidden" name="id" id="id">
        <div class="modal-header">
            <h5 class="modal-title" id="tambahCatatanLabel">Tambah Catatan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="catatan">Berikan Catatan</label>
                <textarea name="catatan" cols="30" rows="5" id="catatan" class="form-control" placeholder="Masukkan catatan disini..." required></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Simpan Catatan</button>
        </div>
        <?= form_close(); ?>
    </div>
  </div>
</div>