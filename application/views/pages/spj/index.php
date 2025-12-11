<?php
$tab = isset($_GET['tab']) ? $_GET['tab'] : '#inbox';

if (urldecode($tab) === '#inbox') {
    $inbox = 'active';
    $is_active_inbox = true;
    $is_show_inbox = "show";
} else {
    $is_show_inbox = "";
    $is_active_inbox = false;
    $inbox = '';
}

if (urldecode($tab) === '#verifikasi') {
    $verifikasi = 'active';
    $is_active_verifikasi = true;
    $is_show_verifikasi = "show";
} else {
    $is_show_verifikasi = "";
    $is_active_verifikasi = false;
    $verifikasi = '';
}

if (urldecode($tab) === '#selesai') {
    $selesai = 'active';
    $is_active_selesai = true;
    $is_show_selesai = "show";
} else {
    $is_show_selesai = "";
    $is_active_selesai = false;
    $selesai = '';
}

if (urldecode($tab) === '#payment') {
    $payment = 'active';
    $is_active_payment = true;
    $is_show_payment = "show";
} else {
    $is_show_payment = "";
    $is_active_payment = false;
    $payment = '';
}
?>
<div class="row"
    style="display: flex; flex-wrap: nowrap; overflow-x: auto; gap: 10px; padding-bottom: 10px;">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-inbox"></i></div>
            <div class="count">Input - Baru</div>
            <h3><?= $data['jml_spj_baru']; ?></h3>
            <p>Jumlah SPJ Diusulkan</p>
        </div>
    </div>

    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-edit"></i></div>
            <div class="count">Input - Perbaikan</div>
            <h3 class="text-warning"><?= $data['jml_spj_perbaikan']; ?></h3>
            <p>Jumlah SPJ Diusulkan Dalam Perbaikan</p>
        </div>
    </div>

    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-clock-o"></i></div>
            <div class="count">Proses - Verifikasi</div>
            <h3 class="text-primary"><?= $data['jml_spj_verifikasi']; ?></h3>
            <p>Jumlah SPJ Dalam Proses Verifikasi</p>
        </div>
    </div>

    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-check-circle"></i></div>
            <div class="count">Proses - Approval</div>
            <h3 class="text-success"><?= $data['jml_spj_verifikasi_admin']; ?></h3>
            <p>Jumlah SPJ Dalam Proses Verifikasi Admin</p>
        </div>
    </div>

    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6"
        style="flex: 0 0 auto; min-width: 250px;">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-university"></i></div>
            <div class="count">Proses - Pending</div>
            <h3 class="text-info"><?= $data['jml_spj_verifikasi_admin']; ?></h3>
            <p>Jumlah SPJ Dalam Proses Pencairan</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item float-right">
                <a class="nav-link pb-4 font-weight-bold <?= $inbox ?>" style="font-size:16px" id="inbox-tab" data-toggle="tab" href="#inbox" role="tab" aria-controls="inbox" aria-selected="<?= $is_active_inbox ?>"><i class="fa fa-inbox mr-2"></i>Utama</a>
            </li>
            <?php if (privilages('priv_verifikasi')) : ?>
                <li class="nav-item ml-2">
                    <a class="nav-link pb-4 font-weight-bold <?= $verifikasi ?>" style="font-size:16px" id="verifikasi-tab" data-toggle="tab" href="#verifikasi" role="tab" aria-controls="verifikasi" aria-selected="<?= $is_active_verifikasi ?>"><i class="fa fa-lock mr-2"></i>Verifikasi</a>
                </li>
            <?php endif; ?>
            <?php if (privilages('priv_riwayat_spj')) : ?>
                <li class="nav-item ml-2">
                    <a class="nav-link pb-4 font-weight-bold <?= $selesai ?>" style="font-size:16px" id="selesai-tab" data-toggle="tab" href="#selesai" role="tab" aria-controls="selesai" aria-selected="<?= $is_active_selesai ?>"><i class="fa fa-check-circle mr-2"></i>Selesai</a>
                </li>
            <?php endif; ?>
            <?php if (privilages('priv_payment')) : ?>
                <li class="nav-item ml-2">
                    <a class="nav-link pb-4 font-weight-bold <?= $payment ?>" style="font-size:16px" id="payment-tab" data-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="<?= $is_active_payment ?>"><i class="fa fa-credit-card mr-2"></i>Pembayaran</a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="x_panel" style="border-top:0">
            <div class="x_content">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane <?= $inbox ?> <?= $is_show_inbox ?>" id="inbox" role="tabpanel" aria-labelledby="inbox-tab"></div>
                    <?php if (privilages('priv_verifikasi')) : ?>
                        <div class="tab-pane <?= $verifikasi ?> <?= $is_show_verifikasi ?>" id="verifikasi" role="tabpanel" aria-labelledby="verifikasi-tab">
                            <div class="table-responsive">
                                <table id="table-spj" class="table dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle" width="5%">No</th>
                                            <th>Kode</th>
                                            <th>Uraian</th>
                                            <th>SPJ Periode/Bulan</th>
                                            <th>Bidang/Bagian</th>
                                            <th>User Usul</th>
                                            <th>Status</th>
                                            <th data-priority="2">Jumlah (Rp)</th>
                                            <th data-priority="1"></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle" width="5%">#</th>
                                            <th class="filterhead"></th>
                                            <th class="filterhead"></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th data-priority="2"></th>
                                            <th data-priority="1"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (privilages('priv_riwayat_spj')) : ?>
                        <div class="tab-pane <?= $selesai ?> <?= $is_show_selesai ?>" id="selesai" role="tabpanel" aria-labelledby="selesai-tab">
                            <div class="table-responsive">
                                <table id="table-spj-selesai" class="table dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No. Urut</th>
                                            <th>No. BKU</th>
                                            <th>Kode</th>
                                            <th data-priority="1">Uraian</th>
                                            <th>Bidang/Bagian</th>
                                            <th>Periode/SPJ Bulan</th>
                                            <th>User Usul</th>
                                            <th data-priority="3">Tanggal Finalisasi</th>
                                            <th>Status - Admin</th>
                                            <th>Status - Bendahara</th>
                                            <th data-priority="2">Jumlah (Rp)</th>
                                            <th data-priority="1"></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" width="5%">#</th>
                                            <th class="filterhead"></th>
                                            <th class="filterhead"></th>
                                            <th class="filterhead" data-priority="1"></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th data-priority="3"></th>
                                            <th></th>
                                            <th></th>
                                            <th data-priority="2"></th>
                                            <th data-priority="1"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (privilages('priv_payment')) : ?>
                        <div class="tab-pane <?= $payment ?> <?= $is_show_payment ?>" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                            <div class="table-responsive">
                                <table id="table-spj-payment" class="table dt-responsive nowrap jambo_table bulk_action" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th>No. BKU</th>
                                            <th>Kode</th>
                                            <th data-priority="1">Uraian</th>
                                            <th>Periode/SPJ Bulan</th>
                                            <th data-priority="3">Tanggal Approval Admin</th>
                                            <th data-priority="4">Tanggal Proses Bendahara</th>
                                            <th>Status</th>
                                            <th data-priority="2">Jumlah (Rp)</th>
                                            <th data-priority="1"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal" id="modalLogHistoris" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded-0">

            <!-- Modal Header -->
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title">Log Historis</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
            </div>

        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal" id="modalPayment" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-0">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white rounded-0">
                <h4 class="modal-title">Payment - Status</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <?= form_open(base_url('/app/payment/update'), ['id' => 'formApprover', 'data-parsley-validate' => ''], ['token' => '']); ?>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="load-data"></div>
                <div class="form-group">
                    <label for="verifikasi_status">Status Approver</label>
                    <select name="verifikasi_status" id="verifikasi_status" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="CAIR" selected>CAIR</option>
                        <option value="PERBAIKAN">PERBAIKAN</option>
                        <option value="TOLAK">TOLAK</option>
                    </select>
                </div>
                <div class="form-group d-none" id="verifikasi_catatan">
                    <label for="catatan">Alasan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-0" data-dismiss="modal"><i
                        class="fa fa-close mr-2"></i>Batal</button>
                <button type="submit" class="btn btn-success rounded-0"><i
                        class="fa fa-save mr-2"></i>Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>