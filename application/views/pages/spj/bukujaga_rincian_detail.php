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
            <tr>
                <td>Rekening</td>
                <td><?= $this->spj->getKode('ref_uraians', $post['ref_uraian']) ?></td>
                <td><?= $this->spj->getNama('ref_uraians', $post['ref_uraian']) ?></td>
            </tr>
            <tr>
                <td>Pagu Anggaran</td>
                <?php $pagu = $this->spj->getPaguByUraianId($post['ref_uraian'], $this->session->userdata('tahun_anggaran'), $this->session->userdata('is_perubahan')) ?>
                <td colspan="2"><span class="text-success font-weight-bold">Rp. <?= nominal($pagu); ?></span> <br>
                    <div class="text-sm text-secondary"><i>(<?= terbilang($pagu); ?>)</i></div>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table jambo_table bulk_action table-bordered">
                <thead class="top-0" style="position: sticky; top: 0; z-index: 1;">
                    <tr>
                        <th rowspan="2" class="align-middle text-center">No</th>
                        <th rowspan="2" class="align-middle text-center">Tgl. Pembukuan</th>
                        <th rowspan="2" class="align-middle text-center">No. BKU</th>
                        <th rowspan="2" class="align-middle text-center">Uraian</th>
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
                    $sisa = 0;
                    $total_realisasi_ls = 0;
                    $total_realisasi_not_ls = 0;
                    $ta = $this->session->userdata('tahun_anggaran');
                    $is_perubahan = $this->session->userdata('is_perubahan');
                    foreach ($rincian as $row):
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-start"><?= date_indo($row->tanggal_pembukuan); ?></td>
                            <td class="text-center"><?= $row->nomor_pembukuan; ?></td>
                            <td class="text-nowrap"><?= $row->uraian_spj; ?></td>
                            <td class="text-right">
                                <?php
                                $realisasi_ls = @$this->bukujaga->getRealisasiSPJByUraian($row->id, [
                                    'fid_uraian' => $row->fid_uraian,
                                    'tahun' => $ta,
                                    'is_perubahan' => $is_perubahan,
                                    'is_realisasi' => 'LS',
                                    'is_status' => 'SELESAI'
                                ]);
                                $total_realisasi_ls += $realisasi_ls;
                                ?>
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span><?= nominal($realisasi_ls); ?></span>
                                </div>
                            </td>
                            <td class="text-right">
                                <?php
                                $realisasi_not_ls = @$this->bukujaga->getRealisasiSPJByUraian($row->id, [
                                    'fid_uraian' => $row->fid_uraian,
                                    'tahun' => $ta,
                                    'is_perubahan' => $is_perubahan,
                                    'is_realisasi !=' => 'LS',
                                    'is_status' => 'SELESAI'
                                ]);
                                $total_realisasi_not_ls += $realisasi_not_ls;
                                ?>
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span><?= nominal($realisasi_not_ls); ?></span>
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="d-flex justify-content-between">
                                    <span>Rp.</span>
                                    <span>
                                        <?php
                                        $sisa = $pagu - $row->jumlah;
                                        echo nominal($sisa);
                                        $pagu = $sisa;
                                        ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    ?>
                    <tr>
                        <th colspan="4" class="text-center">Total</th>
                        <th class="text-right">
                            <div class="d-flex justify-content-between">
                                <span>Rp.</span>
                                <span><?= nominal($total_realisasi_ls); ?></span>
                            </div>
                        </th>
                        <th class="text-right">
                            <div class="d-flex justify-content-between">
                                <span>Rp.</span>
                                <span><?= nominal($total_realisasi_not_ls); ?></span>
                            </div>
                        </th>
                        <th class="text-right">
                            <div class="d-flex justify-content-between">
                                <span>Rp.</span>
                                <span><?= nominal($sisa); ?></span>
                            </div>
                        </th>
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
        <button type="submit" class="btn btn-danger btn-sm rounded-0"><i class="fa fa-print mr-2"></i> Cetak Belanja Kegiatan</button>
        <?= form_close() ?>
        <?= form_open(
            base_url('app/bukujaga/cetak_rincian'),
            ['target' => '_blank'],
            [
                'kode_uraian' => $this->spj->getKode('ref_uraians', $post['ref_uraian']),
                'nama_uraian' => $this->spj->getNama('ref_uraians', $post['ref_uraian']),
                'ref_part' => $post['ref_part'],
                'ref_program' => $post['ref_program'],
                'ref_kegiatan' => $post['ref_kegiatan'],
                'ref_subkegiatan' => $post['ref_subkegiatan'],
                'ref_uraian' => $post['ref_uraian'],
            ]
        ) ?>
        <button type="submit" class="btn btn-secondary btn-sm rounded-0"><i class="fa fa-print mr-2"></i> Cetak Rincian Belanja</button>
        <?= form_close() ?>
    </div>
</div>