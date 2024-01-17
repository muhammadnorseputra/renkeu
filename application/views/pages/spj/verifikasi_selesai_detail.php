<div class="page-title">
    <div class="title_left">
        <h3><i class="fa fa-check-circle mr-2"></i> Verifikasi Selesai</h3>
    </div>

    <div class="title_right">
        <div class="col-md-2 col-6 form-group row pull-right top_search">
            <button class="btn btn-danger rounded-0" onclick="window.location.href='<?= base_url('app/spj?tab=%23selesai') ?>'">Kembali<i class="fa fa-arrow-right ml-3"></i> </button>
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div class="x_panel">
    <!-- <div class="x_title">
        <h2>SPJ (Surat Pertanggung Jawaban)</h2>
        <div class="clearfix"></div>
    </div> -->
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>
                    Bidang / Bagian
                </td>
                <td rowspan="2">

                </td>
                <td>
                    <?= $detail->nama_part ?>
                </td>
            </tr>
            <tr>
                <td>
                    Program
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
</div>
