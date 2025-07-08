<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <tr>
                <td width="15%">Bidang/Bagian</td>
                <td colspan="2"><?= $this->spj->getNama('ref_parts', $post['ref_part']) ?></td>
            </tr>
            <tr>
                <td>Program</td>
                <td width="15%"><?= $this->spj->getKode('ref_programs', $post['ref_program']) ?></td>
                <td><?= $this->spj->getNama('ref_programs', $post['ref_program']) ?></td>
            </tr>
            <tr>
                <td>Kegiatan</td>
                <td><?= $this->spj->getKode('ref_kegiatans', $post['ref_kegiatan']) ?></td>
                <td><?= $this->spj->getNama('ref_kegiatans', $post['ref_kegiatan']) ?></td>
            </tr>
            <tr>
                <td>Sub Kegiatan</td>
                <td><?= $this->spj->getKode('ref_sub_kegiatans', $post['ref_subkegiatan']) ?></td>
                <td><?= $this->spj->getNama('ref_sub_kegiatans', $post['ref_subkegiatan']) ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle text-center">No</th>
                        <th rowspan="2" class="align-middle text-center">Kode Rekening</th>
                        <th rowspan="2" class="align-middle text-center">Uraian</th>
                        <th rowspan="2" class="align-middle text-center">Pagu Anggaran</th>
                        <th colspan="2" class="align-middle text-center">Realisasi Kegiatan</th>
                        <th rowspan="2" class="align-middle text-center">Sisa Anggaran</th>
                    </tr>
                    <tr>
                        <th class="text-center">LS</th>
                        <th class="align-middle text-center">UP/GU/TU</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $total_pagu = 0;
                    $total_realisasi_ls = 0;
                    $total_realisasi_not_ls = 0;
                    $total_sisa_anggaran = 0;
                    foreach ($uraians as $uraian):

                    ?>
                        <tr>
                            <td class="text-center"><?= $no ?></td>
                            <td class="text-center"><?= $uraian->kode ?></td>
                            <td class="text-nowrap"><?= $uraian->nama ?></td>
                            <td class="text-right">
                                <?php
                                $pagu = @$this->bukujaga->getPagu(['fid_uraian' => $uraian->id])->total_pagu_awal;
                                $total_pagu += $pagu;
                                echo nominal($pagu);
                                ?>
                            </td>
                            <td class="text-right">
                                <?php
                                $realisasi_ls = @$this->bukujaga->getPaguRealisasi(['fid_uraian' => $uraian->id, 'is_realisasi' => 'LS', 'is_status' => 'SELESAI'])->jumlah;
                                $total_realisasi_ls += $realisasi_ls;
                                echo nominal($realisasi_ls);
                                ?>
                            </td>
                            <td class="text-right">
                                <?php
                                $realisasi_not_ls = @$this->bukujaga->getPaguRealisasi(['fid_uraian' => $uraian->id, 'is_realisasi !=' => 'LS', 'is_status' => 'SELESAI'])->jumlah;
                                $total_realisasi_not_ls += $realisasi_not_ls;
                                echo nominal($realisasi_not_ls);
                                ?>
                            </td>
                            <td class="text-right">
                                <?php
                                $realisasi = ($realisasi_ls + $realisasi_not_ls);
                                $sisa_anggaran = ($pagu - $realisasi);
                                $total_sisa_anggaran += $sisa_anggaran;
                                echo nominal($sisa_anggaran);
                                ?>
                            </td>
                        </tr>
                    <?php $no++;
                    endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-right"><b>Total</b></td>
                        <td class="text-right"><b><?= nominal($total_pagu) ?></b></td>
                        <td class="text-right"><b><?= nominal($total_realisasi_ls) ?></b></td>
                        <td class="text-right"><b><?= nominal($total_realisasi_not_ls) ?></b></td>
                        <td class="text-right"><b><?= nominal($total_sisa_anggaran) ?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?= form_open(
            base_url('app/bukujaga/cetak'),
            ['target' => '_blank'],
            [
                'kodesub' => $this->spj->getKode('ref_sub_kegiatans', $post['ref_subkegiatan']),
                'namasub' => $this->spj->getNama('ref_sub_kegiatans', $post['ref_subkegiatan']),
                'ref_part' => $post['ref_part'],
                'ref_program' => $post['ref_program'],
                'ref_kegiatan' => $post['ref_kegiatan'],
                'ref_subkegiatan' => $post['ref_subkegiatan'],
            ]
        ) ?>
        <button type="submit" class="btn btn-danger btn-sm rounded-0"><i class="fa fa-print mr-2"></i> Cetak</button>
        <?= form_close() ?>
    </div>
</div>