<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <title><?= $title ?></title>
    <?php
    // guard: view bisa di-render tanpa filter dari controller
    $filter_tanggal = isset($filter_tanggal) ? $filter_tanggal : null;
    $cetak          = isset($cetak) ? $cetak : 'bidang';
    $cetak_label    = isset($cetak_label) ? $cetak_label : 'Belanja Bidang';
    ?>
    <style>
    @page {
        margin: 0.3cm 1cm;
    }

    body {
        font-family: sans-serif;
        margin: 1cm 0;
        font-size: 0.8em;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        page-break-inside: auto;
    }

    thead {
        display: table-header-group;
    }

    tr {
        page-break-inside: avoid;
    }

    th,
    td {
        padding: 3pt;
        border: 1pt solid #aaa;
    }

    th {
        background-color: #f2f2f2;
        text-align: center;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .font-weight-bold {
        font-weight: bold;
    }

    h2,
    h3,
    h4 {
        margin: 0;
        padding: 0;
    }

    .kop {
        text-align: center;
        margin-bottom: 10pt;
        border-bottom: 2pt solid #34495E;
        padding-bottom: 6pt;
    }

    .meta {
        margin-bottom: 10pt;
        font-size: 0.9em;
    }

    .meta td {
        border: none;
        padding: 1pt 4pt 1pt 0;
    }

    .summary {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12pt;
        font-size: 0.9em;
    }

    .summary td {
        border: 1pt solid #aaa;
        padding: 6pt;
        text-align: center;
        width: 25%;
    }

    .summary .label {
        font-weight: bold;
        display: block;
        margin-bottom: 3pt;
    }

    .summary .value {
        font-size: 1.1em;
        font-weight: bold;
        color: #34495E;
    }
    </style>
</head>

<body>
    <div class="kop">
        <h2>MONITOR SPJ (SURAT PERTANGGUNG JAWABAN)</h2>
        <h3>TAHUN ANGGARAN <?= $tahun_anggaran ?></h3>
        <h4><?= strtoupper($cetak_label) ?></h4>
    </div>
    <table class="meta">
        <tr>
            <td>Dicetak pada</td>
            <td>:</td>
            <td><?= $cetak_datetime ?> (Waktu Server)</td>
        </tr>
        <tr>
            <td>Filter Tanggal</td>
            <td>:</td>
            <td><?= $filter_tanggal ? htmlspecialchars($filter_tanggal) : 'Semua Tanggal' ?></td>
        </tr>
        <tr>
            <td>Periode Anggaran</td>
            <td>:</td>
            <td><?= $is_perubahan ? 'Perubahan' : 'Murni' ?></td>
        </tr>
    </table>

    <?php
    // Ringkasan pagu & realisasi (sama seperti tile di monitor.php)
    $part_scope = in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN']) ? null : $part;

    $alokasiPagu          = $this->spj->getTotalPaguMurniByPart($part_scope, $tahun_anggaran);
    $alokasiPaguPerubahan = $this->spj->getTotalPaguPerubahanByPart($part_scope, $tahun_anggaran);
    $totalRealisasi       = $this->spj->getTotalRealisasiByPart($part_scope, $filter_tanggal, $tahun_anggaran);

    $paguAktif = $is_perubahan ? $alokasiPaguPerubahan : $alokasiPagu;
    $capaian   = $paguAktif > 0 ? ($totalRealisasi / $paguAktif) * 100 : 0;
    ?>
    <table class="summary">
        <tr>
            <td>
                <span class="label">Alokasi Pagu Murni</span>
                <span class="value">Rp. <?= nominal($alokasiPagu) ?></span>
            </td>
            <td>
                <span class="label">Alokasi Pagu Perubahan</span>
                <span class="value">Rp. <?= nominal($alokasiPaguPerubahan) ?></span>
            </td>
            <td>
                <span class="label">Realisasi Belanja</span>
                <span class="value">Rp. <?= nominal($totalRealisasi) ?></span>
            </td>
            <td>
                <span class="label">Capaian Realisasi</span>
                <span class="value"><?= number_format($capaian, 2) ?>%</span>
            </td>
        </tr>
    </table>

    <?php if ($cetak == 'bidang'): ?>
        <table>
            <thead>
                <tr>
                    <th rowspan="3">No</th>
                    <th rowspan="3">Bidang/Bagian</th>
                    <th colspan="10">SPJ Berdasarkan Status</th>
                    <th rowspan="3">Capaian</th>
                </tr>
                <tr>
                    <th colspan="2">Usulan</th>
                    <th colspan="2">Persetujuan/Tolak</th>
                    <th colspan="5">Bendahara</th>
                </tr>
                <tr>
                    <th>Baru</th>
                    <th>Verifikasi</th>
                    <th>Approval</th>
                    <th>TMS</th>
                    <th>Perbaikan</th>
                    <th>Pending (Perbaikan)</th>
                    <th>Pending</th>
                    <th>Cair</th>
                    <th>Gagal Cair</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($listpart) > 0): ?>
                    <?php
                    $no = 1;

                    $totalUsulanBaru       = 0;
                    $totalUsulanVerifikasi = 0;
                    $totalPersetujuan      = 0;
                    $totalTolak            = 0;
                    $totalPerbaikan        = 0;
                    $totalPendingPerbaikan = 0;
                    $totalPending          = 0;
                    $totalCair             = 0;
                    $totalTolakBendahara   = 0;

                    if (in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
                        $alokasiPagu            = $this->spj->getTotalPaguMurniByPart(null, $tahun_anggaran);
                        $alokasiPaguPerubahan   = $this->spj->getTotalPaguPerubahanByPart(null, $tahun_anggaran);
                    } else {
                        $alokasiPagu            = $this->spj->getTotalPaguMurniByPart($part, $tahun_anggaran);
                        $alokasiPaguPerubahan   = $this->spj->getTotalPaguPerubahanByPart($part, $tahun_anggaran);
                    }

                    foreach ($listpart as $row_part):
                        // Pagu Murni atau Perubahan
                        $totalPaguPerubahan = $this->spj->getTotalPaguPerubahanByPart($row_part->id, $tahun_anggaran);
                        $totalPaguMurni     = $this->spj->getTotalPaguMurniByPart($row_part->id, $tahun_anggaran);

                        // Usulan Baru/Verifikasi
                        $usulanBaru             = $this->spj->getTotalRealisasiByPartAndStatus($row_part->id, $filter_tanggal, $tahun_anggaran, ['ENTRI']);
                        $usulanVerifikasi       = $this->spj->getTotalRealisasiByPartAndStatus($row_part->id, $filter_tanggal, $tahun_anggaran, ['VERIFIKASI', 'VERIFIKASI_ADMIN']);
                        $totalUsulanBaru       += $usulanBaru;
                        $totalUsulanVerifikasi += $usulanVerifikasi;

                        // Persetujuan/Tolak
                        $persetujuan       = $this->spj->getTotalRealisasiByPartAndStatusAdmin($row_part->id, $filter_tanggal, $tahun_anggaran, ['APPROVE']);
                        $tolak             = $this->spj->getTotalRealisasiByPartAndStatusAdmin($row_part->id, $filter_tanggal, $tahun_anggaran, ['TMS', 'BTL']);
                        $totalPersetujuan += $persetujuan;
                        $totalTolak       += $tolak;

                        // Bendahara
                        $perbaikan              = $this->spj->getTotalRealisasiByPartAndStatusBendahara($row_part->id, $filter_tanggal, $tahun_anggaran, ['PERBAIKAN']);
                        $pendingPerbaikan       = $this->spj->getTotalRealisasiByPartAndStatusBendahara($row_part->id, $filter_tanggal, $tahun_anggaran, ['PENDING - PERBAIKAN']);
                        $pending                = $this->spj->getTotalRealisasiByPartAndStatusBendahara($row_part->id, $filter_tanggal, $tahun_anggaran, ['PENDING']);
                        $cair                   = $this->spj->getTotalRealisasiByPartAndStatusBendahara($row_part->id, $filter_tanggal, $tahun_anggaran, ['CAIR']);
                        $tolakBendahara         = $this->spj->getTotalRealisasiByPartAndStatusBendahara($row_part->id, $filter_tanggal, $tahun_anggaran, ['TOLAK']);
                        $totalPerbaikan        += $perbaikan;
                        $totalPendingPerbaikan += $pendingPerbaikan;
                        $totalPending          += $pending;
                        $totalCair             += $cair;
                        $totalTolakBendahara   += $tolakBendahara;

                        // Capaian
                        $paguCapaian = $is_perubahan ? $totalPaguPerubahan : $totalPaguMurni;
                        $capaian     = $paguCapaian > 0 ? ($persetujuan / $paguCapaian) * 100 : 0;
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $row_part->nama ?></td>
                            <td class="text-right">Rp. <?= nominal($usulanBaru) ?></td>
                            <td class="text-right">Rp. <?= nominal($usulanVerifikasi) ?></td>
                            <td class="text-right">Rp. <?= nominal($persetujuan) ?></td>
                            <td class="text-right">Rp. <?= nominal($tolak) ?></td>
                            <td class="text-right">Rp. <?= nominal($perbaikan) ?></td>
                            <td class="text-right">Rp. <?= nominal($pendingPerbaikan) ?></td>
                            <td class="text-right">Rp. <?= nominal($pending) ?></td>
                            <td class="text-right">Rp. <?= nominal($cair) ?></td>
                            <td class="text-right">Rp. <?= nominal($tolakBendahara) ?></td>
                            <td class="text-right"><?= number_format($capaian, 2) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-center font-weight-bold">Total</td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalUsulanBaru) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalUsulanVerifikasi) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPersetujuan) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalTolak) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPerbaikan) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPendingPerbaikan) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPending) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalCair) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalTolakBendahara) ?></td>
                        <?php
                        $paguTotal = $is_perubahan ? $alokasiPaguPerubahan : $alokasiPagu;
                        $totalCapaian = $paguTotal > 0 ? ($totalPersetujuan / $paguTotal) * 100 : 0;
                        ?>
                        <td class="text-right font-weight-bold"><?= number_format($totalCapaian, 2) ?>%</td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="12">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <?php elseif ($cetak == 'uraian'): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Uraian</th>
                    <th>Total Pagu</th>
                    <th>Total Realisasi</th>
                    <th>Sisa Anggaran</th>
                    <th>Capaian</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($uraians)): ?>
                    <?php
                    $no = 1;
                    $totalPaguUraian = 0;
                    $totalRealisasiUraian = 0;
                    foreach ($uraians as $uraian):
                        $paguUraian = (float) $uraian->pagu;
                        $realisasiUraian = (float) $uraian->realisasi;
                        $sisaUraian = $paguUraian - $realisasiUraian;
                        $capaianUraian = $paguUraian > 0 ? ($realisasiUraian / $paguUraian) * 100 : 0;
                        $totalPaguUraian += $paguUraian;
                        $totalRealisasiUraian += $realisasiUraian;
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $uraian->kode ?> - <?= $uraian->nama ?></td>
                            <td class="text-right">Rp. <?= nominal($paguUraian) ?></td>
                            <td class="text-right">Rp. <?= nominal($realisasiUraian) ?></td>
                            <td class="text-right">Rp. <?= nominal($sisaUraian) ?></td>
                            <td class="text-right"><?= number_format($capaianUraian, 2) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-center font-weight-bold">Total</td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguUraian) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalRealisasiUraian) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguUraian - $totalRealisasiUraian) ?></td>
                        <td class="text-right font-weight-bold"><?= $totalPaguUraian > 0 ? number_format(($totalRealisasiUraian / $totalPaguUraian) * 100, 2) : '0' ?>%</td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="6">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <?php elseif ($cetak == 'program'): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Program</th>
                    <th>Total Pagu</th>
                    <th>Total Realisasi</th>
                    <th>Sisa Anggaran</th>
                    <th>Capaian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($programs): ?>
                    <?php
                    $no                    = 1;
                    $totalPaguProgram      = 0;
                    $totalRealisasiProgram = 0;
                    foreach ($programs->result() as $program):
                        $paguProgram = $this->target->getAlokasiPaguProgram($program->id, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal ?? 0;

                        if (in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
                            $realisasiProgram = $this->spj->getRealisasiByPartAndProgram(null, $filter_tanggal, $program->id, $tahun_anggaran);
                        } else {
                            $realisasiProgram = $this->spj->getRealisasiByPartAndProgram($part, $filter_tanggal, $program->id, $tahun_anggaran);
                        }

                        $sisaAnggaran           = $paguProgram - $realisasiProgram;
                        $capaian                = $paguProgram > 0 ? ($realisasiProgram / $paguProgram) * 100 : 0;
                        $totalPaguProgram      += $paguProgram;
                        $totalRealisasiProgram += $realisasiProgram;
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $program->nama ?></td>
                            <td class="text-right">Rp. <?= nominal($paguProgram) ?></td>
                            <td class="text-right">Rp. <?= nominal($realisasiProgram) ?></td>
                            <td class="text-right">Rp. <?= nominal($sisaAnggaran) ?></td>
                            <td class="text-right"><?= number_format($capaian, 2) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-center font-weight-bold">Total</td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguProgram) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalRealisasiProgram) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguProgram - $totalRealisasiProgram) ?></td>
                        <td class="text-right font-weight-bold"><?= $totalPaguProgram > 0 ? number_format(($totalRealisasiProgram / $totalPaguProgram) * 100, 2) : '0' ?>%</td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="6">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <?php elseif ($cetak == 'kegiatan'): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kegiatan</th>
                    <th>Total Pagu</th>
                    <th>Total Realisasi</th>
                    <th>Sisa Anggaran</th>
                    <th>Capaian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($kegiatans): ?>
                    <?php
                    $no                         = 1;
                    $totalPaguKegiatan          = 0;
                    $totalRealisasiPaguKegiatan = 0;
                    foreach ($kegiatans->result() as $kegiatan):
                        $paguKegiatan       = $this->target->getAlokasiPaguKegiatan($kegiatan->id, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal ?? 0;
                        $totalPaguKegiatan += $paguKegiatan;

                        if (in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
                            $realisasiKegiatan = $this->spj->getRealisasiByPartAndKegiatan(null, $filter_tanggal, $kegiatan->id, $tahun_anggaran);
                        } else {
                            $realisasiKegiatan = $this->spj->getRealisasiByPartAndKegiatan($part, $filter_tanggal, $kegiatan->id, $tahun_anggaran);
                        }

                        $totalRealisasiPaguKegiatan += $realisasiKegiatan;

                        $sisaAnggaran = $paguKegiatan - $realisasiKegiatan;
                        $capaian      = $paguKegiatan > 0 ? ($realisasiKegiatan / $paguKegiatan) * 100 : 0;
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $kegiatan->nama ?></td>
                            <td class="text-right">Rp. <?= nominal($paguKegiatan) ?></td>
                            <td class="text-right">Rp. <?= nominal($realisasiKegiatan) ?></td>
                            <td class="text-right">Rp. <?= nominal($sisaAnggaran) ?></td>
                            <td class="text-right"><?= number_format($capaian, 2) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-center font-weight-bold">Total</td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguKegiatan) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalRealisasiPaguKegiatan) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguKegiatan - $totalRealisasiPaguKegiatan) ?></td>
                        <td class="text-right font-weight-bold"><?= $totalPaguKegiatan > 0 ? number_format(($totalRealisasiPaguKegiatan / $totalPaguKegiatan) * 100, 2) : '0' ?>%</td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="6">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    <?php elseif ($cetak == 'sub_kegiatan'): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Sub Kegiatan</th>
                    <th>Total Pagu</th>
                    <th>Total Realisasi</th>
                    <th>Sisa Anggaran</th>
                    <th>Capaian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($sub_kegiatans): ?>
                    <?php
                    $no                        = 1;
                    $totalPaguSubKegiatan      = 0;
                    $totalRealisasiSubKegiatan = 0;
                    foreach ($sub_kegiatans->result() as $sub_kegiatan):
                        $paguSubKegiatan       = $this->target->getAlokasiPaguSubKegiatan($sub_kegiatan->id, $is_perubahan, $tahun_anggaran)->row()->total_pagu_awal ?? 0;
                        $totalPaguSubKegiatan += $paguSubKegiatan;

                        if (in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])) {
                            $realisasiSubKegiatan = $this->spj->getRealisasiByPartAndSubKegiatan(null, $filter_tanggal, $sub_kegiatan->id, $tahun_anggaran);
                        } else {
                            $realisasiSubKegiatan = $this->spj->getRealisasiByPartAndSubKegiatan($part, $filter_tanggal, $sub_kegiatan->id, $tahun_anggaran);
                        }

                        $totalRealisasiSubKegiatan += $realisasiSubKegiatan;
                        $capaian = $paguSubKegiatan > 0 ? ($realisasiSubKegiatan / $paguSubKegiatan) * 100 : 0;
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= $sub_kegiatan->nama ?></td>
                            <td class="text-right">Rp. <?= nominal($paguSubKegiatan) ?></td>
                            <td class="text-right">Rp. <?= nominal($realisasiSubKegiatan) ?></td>
                            <td class="text-right">Rp. <?= nominal($paguSubKegiatan - $realisasiSubKegiatan) ?></td>
                            <td class="text-right"><?= number_format($capaian, 2) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-center font-weight-bold">Total</td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguSubKegiatan) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalRealisasiSubKegiatan) ?></td>
                        <td class="text-right font-weight-bold">Rp. <?= nominal($totalPaguSubKegiatan - $totalRealisasiSubKegiatan) ?></td>
                        <td class="text-right font-weight-bold"><?= $totalPaguSubKegiatan > 0 ? number_format(($totalRealisasiSubKegiatan / $totalPaguSubKegiatan) * 100, 2) : '0' ?>%</td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td class="text-center" colspan="6">Data tidak tersedia</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>
