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
?>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item float-right">
                <a class="nav-link pb-4 font-weight-bold <?= $inbox ?>" style="font-size:16px" id="inbox-tab" data-toggle="tab" href="#inbox" role="tab" aria-controls="inbox" aria-selected="<?= $is_active_inbox ?>"><i class="fa fa-inbox mr-2"></i>Utama</a>
            </li>
            <?php if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || privilages('priv_verifikasi')) : ?>
                <li class="nav-item ml-2">
                    <a class="nav-link pb-4 font-weight-bold <?= $verifikasi ?>" style="font-size:16px" id="verifikasi-tab" data-toggle="tab" href="#verifikasi" role="tab" aria-controls="verifikasi" aria-selected="<?= $is_active_verifikasi ?>"><i class="fa fa-lock mr-2"></i>Verifikasi</a>
                </li>
            <?php endif; ?>
            <?php if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || privilages('priv_riwayat_spj')) : ?>
                <li class="nav-item ml-2">
                    <a class="nav-link pb-4 font-weight-bold <?= $selesai ?>" style="font-size:16px" id="selesai-tab" data-toggle="tab" href="#selesai" role="tab" aria-controls="selesai" aria-selected="<?= $is_active_selesai ?>"><i class="fa fa-check-circle mr-2"></i>Selesai</a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="x_panel" style="border-top:0">
            <div class="x_content">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane <?= $inbox ?> <?= $is_show_inbox ?>" id="inbox" role="tabpanel" aria-labelledby="inbox-tab"></div>
                    <?php if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || privilages('priv_verifikasi')) : ?>
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
                    <?php if ($this->session->userdata('role') === 'VERIFICATOR' || $this->session->userdata('role') === 'ADMIN' || $this->session->userdata('role') === 'ARSIP_USER' || privilages('priv_riwayat_spj')) : ?>
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
                                            <th>Status</th>
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
                                            <th data-priority="2"></th>
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