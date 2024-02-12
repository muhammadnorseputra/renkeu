<?php  
if($detail->is_status === 'ENTRI') {
    $status = '<span class="badge p-2 badge-secondar pull-right"><i class="fa fa-edit mr-2"></i> ENTRI</span>';
} elseif($detail->is_status === 'VERIFIKASI' || $detail->is_status === 'VERIFIKASI_ADMIN') {
    $status = '<span class="badge p-2 badge-primary text-white pull-right"><i class="fa fa-lock mr-2"></i> VERIFIKASI</span>';
} elseif($detail->is_status === 'APPROVE') {
    $status = '<span class="badge p-2 badge-success text-white pull-right"><i class="fa fa-check-circle mr-2"></i> APPROVE</span>';
} elseif($detail->is_status === 'BTL') {
    $status = '<span class="badge p-2 badge-danger text-white pull-right "><i class="fa fa-close mr-2"></i> BTL</span>';
} else {
    $status = '<span class="badge p-2 badge-danger text-white  pull-right"><i class="fa fa-close mr-2"></i> TMS</span>';
}
?>
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-check-circle mr-2"></i> Verifikasi Selesai </h2><?= $status ?>
        <div class="clearfix"></div>
    </div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>
                    Bidang / Bagian
                </td>
                <td colspan="2">
                    <?= $detail->nama_part ?>
                </td>
            </tr>
            <tr>
                <td>
                    Program
                </td>
                <td class="text-right">
                    <?= $detail->kode_program ?>
                </td>
                <td>
                    <?= $detail->nama_program ?>
                </td>
            </tr>
            <tr>
                <td>
                    Kegiatan
                </td>
                <td class="text-right">
                    <?= $detail->kode_kegiatan ?>
                </td>
                <td>
                    <?= strtoupper($detail->nama_kegiatan) ?>
                </td>
            </tr>
            <tr>
                <td>
                    Sub Kegiatan
                </td>
                <td class="text-right">
                    <?= $detail->kode_sub_kegiatan ?>
                </td>
                <td>
                    <?= strtoupper($detail->nama_sub_kegiatan) ?>
                </td>
            </tr>
            <tr>
                <td class="bg-light text-dark text-center" colspan="3">Detail SPJ (Surat Pertanggung Jawaban)</td>
            </tr>
            <tr>
                    <td>
                        Kode Rekening
                    </td>
                    <td colspan="2">
                        <?= $detail->koderek ?>
                    </td>
            </tr>
            <tr>
                    <td>
                        SPJ Bulan
                    </td>
                    <td colspan="2">
                        <?= bulan($detail->bulan) ?> / <?= $detail->tahun ?>
                    </td>
            </tr>
            <tr>
                <td>
                    Uraian
                </td>
                <td colspan="2">
                    <?= $detail->uraian ?>
                </td>
            </tr>
            <tr>
                <td>
                    Jumlah
                </td>
                <td colspan="2">
                <h5>Rp.    <b><?= nominal($detail->jumlah) ?></b></h5>
                </td>
            </tr>
            <tr>
                <td>
                    Berkas (Link)
                </td>
                <td class="text-center">
                    <a href="<?= $detail->berkas_link ?>" target="_blank" title="Open Link"><i class="fa fa-link"></i> Open</a>
                </td>
                <td>
                    <?= $detail->berkas_link ?> <br>
                </td>
            </tr>
            <?php if($detail->is_status  !== 'APPROVE'): ?>
            <tr>
                <td class="bg-light text-dark text-center" colspan="3">Alasan <?= $detail->is_status ?></td>
            </tr>
            <tr>
                <td colspan="3" class="text-center bg-danger text-white">
                    <b><?= $detail->catatan ?></b>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td class="bg-light text-dark text-center" colspan="3">Detail Pengguna</td>
            </tr>
            <tr>
                <td colspan="3">
                    
                    <div class="row">
                        <div class="col-md-4 border-right">
                            <?php $userusul = $this->users->profile_username($detail->entri_by)->row(); ?>
                            Dientri oleh : <?= $userusul->nama ?> (<?= strtoupper($detail->entri_by) ?>) <br>
                            Tanggal / Jam : <?= longdate_indo(substr($detail->entri_at,0,10)) ?> / <?= substr($detail->entri_at,10,6) ?> WITA
                        </div>
                        <div class="col-md-4 border-right">
                            <?php $userusul = $this->users->profile_username($detail->verify_by)->row(); ?>
                            Diverifikasi oleh : <?= $userusul->nama ?> (<?= strtoupper($detail->verify_by) ?>) <br>
                            Tanggal / Jam : <?= longdate_indo(substr($detail->verify_at,0,10)) ?> / <?= substr($detail->verify_at,10,6) ?> WITA
                        </div>
                        <?php if($detail->is_status  === 'APPROVE'): ?>
                        <div class="col-md-4">
                            <?php $userusul = $this->users->profile_username($detail->approve_by)->row(); ?>
                            Diapprove oleh : <?= $userusul->nama ?> (<?= strtoupper($detail->approve_by) ?>) <br>
                            Tanggal / Jam : <?= longdate_indo(substr($detail->approve_at,0,10)) ?> / <?= substr($detail->approve_at,10,6) ?> WITA
                        </div>
                        <?php endif; ?>
                    </div>
                    
                </td>
            </tr>

        </tbody>
    </table>
    <button class="btn btn-danger rounded-0" onclick="window.location.href='<?= base_url('app/spj?tab=%23selesai') ?>'"><i class="fa fa-arrow-left mr-3"></i> Kembali </button>

</div>