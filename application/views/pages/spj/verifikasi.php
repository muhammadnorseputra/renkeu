<?php
$userusul      = $this->users->profile_username($detail->entri_by)->row();
$userverfikasi = $detail->verify_by ? $this->users->profile_username($detail->verify_by)->row() : null;
$role          = $this->session->userdata('role');
$is_verifikator = $role === 'VERIFICATOR' && privilages('priv_verifikasi');
$list_penerima = $this->spj->getListPenerimaManfaat($detail->token)->result();
$disabled_tms  = $is_verifikator ? '' : 'disabled';

$status_meta = [
    'ENTRI'            => ['secondary', 'Entri'],
    'VERIFIKASI'       => ['info', 'Verifikasi'],
    'VERIFIKASI_ADMIN' => ['primary', 'Verifikasi Admin'],
    'BTL'              => ['dark', 'Batal'],
    'TMS'              => ['danger', 'TMS'],
    'SELESAI'          => ['success', 'Selesai'],
    'SELESAI_TMS'      => ['danger', 'Selesai (TMS)'],
    'SELESAI_BTL'      => ['dark', 'Selesai (Batal)'],
];
$status_color = isset($status_meta[$detail->is_status]) ? $status_meta[$detail->is_status][0] : 'secondary';
$status_label = isset($status_meta[$detail->is_status]) ? $status_meta[$detail->is_status][1] : $detail->is_status;

// strip: kode rekening dari ref_uraians (aman null)
$koderek = '';
if (! empty($detail->fid_uraian)) {
    $ru = $this->db->get_where('ref_uraians', ['id' => $detail->fid_uraian])->row();
    if ($ru && ! empty($ru->kode)) $koderek = $ru->kode;
}
if ($koderek === '') {
    $tmp = trim((string) $detail->koderek);
    $koderek = trim((string) strtok($tmp, '-')) ?: $tmp;
}

$program = [
    'Program'      => [$detail->kode_program, $detail->nama_program],
    'Kegiatan'     => [$detail->kode_kegiatan, $detail->nama_kegiatan],
    'Sub Kegiatan' => [$detail->kode_sub_kegiatan, $detail->nama_sub_kegiatan],
];

// --- preview berkas inline (lazy): Google Drive / PDF / gambar langsung di-preview,
// tipe lain fallback tombol buka tab baru.
$berkas      = trim((string) $detail->berkas_link);
$preview_url = '';
if ($berkas !== '') {
    $ext = strtolower(pathinfo((string) parse_url($berkas, PHP_URL_PATH), PATHINFO_EXTENSION));
    if (preg_match('~drive\.google\.com/(?:file/d/|open\?id=|uc\?.*?id=)([\w-]+)~i', $berkas, $m)) {
        $preview_url = 'https://drive.google.com/file/d/' . $m[1] . '/preview'; // iframe-safe
    } elseif (in_array($ext, ['pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'], true)) {
        $preview_url = $berkas;
    }
}
?>

<style>
  .vrf-kode {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-size: 11px; letter-spacing: .3px; color: #667085;
  }
  .vrf-wrap {
    background: #fff; border: 1px solid #e4e7ec; border-radius: 4px;
    padding: 16px 10px; box-shadow: 0 1px 2px rgba(16,24,40,.04);
  }
  /* --- header halaman --- */
  .vrf-head {
    display: flex; align-items: center; flex-wrap: wrap; gap: 10px;
    background: #fff; border: 1px solid #e4e7ec; border-radius: 4px;
    padding: 10px 14px; margin-bottom: 10px; box-shadow: 0 1px 2px rgba(16,24,40,.04);
  }
  .vrf-back {
    color: #1d3a6e; border: 1px solid #1d3a6e; background: #f8fafc;
    font-size: 12.5px; font-weight: 600; padding: 5px 12px; border-radius: 3px;
    transition: all .15s;
  }
  .vrf-back:hover, .vrf-back:focus { background: #1d3a6e; color: #fff; text-decoration: none; }
  .vrf-head-title h3 { font-size: 15px; font-weight: 700; color: #101828; margin: 0; line-height: 1.25; }
  .vrf-head-title h3 .fa { color: #2f5fa8; }
  .vrf-head-title .vrf-head-sub { font-size: 11.5px; color: #667085; }
  .vrf-head-meta { margin-left: auto; }
  /* --- strip ringkas, bukan header besar --- */
  .vrf-strip {
    display: flex; flex-wrap: wrap; align-items: center; gap: 14px;
    background: linear-gradient(135deg, #1d3a6e 0%, #2f5fa8 100%);
    color: #fff; padding: 12px 18px; border-radius: 4px;
  }
  .vrf-strip .lbl {
    font-size: 10px; text-transform: uppercase; letter-spacing: .7px;
    color: rgba(255,255,255,.7); display: block;
  }
  .vrf-strip .val { font-size: 13.5px; font-weight: 600; }
  .vrf-strip .angka { font-size: 20px; font-weight: 700; letter-spacing: -.3px; line-height: 1.1; }
  .vrf-strip .sep { width: 1px; height: 30px; background: rgba(255,255,255,.22); }
  /* rekening: kontras tinggi + tombol copy */
  .vrf-strip .rek {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-size: 14px; font-weight: 700; letter-spacing: .5px;
    color: #fff; text-shadow: 0 1px 2px rgba(0,0,0,.35);
  }
  .vrf-strip .rek-copy {
    background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.35);
    color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 2px;
    margin-left: 6px; vertical-align: middle; transition: background .15s;
  }
  .vrf-strip .rek-copy:hover { background: rgba(255,255,255,.32); }

  /* --- seksi collapse --- */
  .vrf-acc { border: 1px solid var(--vrf-line, #e4e7ec); border-radius: 3px; margin-bottom: 6px; }
  .vrf-acc > summary {
    list-style: none; cursor: pointer; padding: 8px 12px; font-size: 12.5px; font-weight: 600;
    color: #344054; background: #f8fafc; display: flex; align-items: center; gap: 8px;
  }
  .vrf-acc > summary::-webkit-details-marker { display: none; }
  .vrf-acc > summary .fa-chevron-down { margin-left: auto; font-size: 11px; color: #98a2b3; transition: transform .15s; }
  .vrf-acc[open] > summary .fa-chevron-down { transform: rotate(180deg); }
  .vrf-acc[open] > summary { border-bottom: 1px solid var(--vrf-line, #e4e7ec); }
  .vrf-acc .acc-body { padding: 10px 12px; font-size: 12.5px; line-height: 1.65; }
  .vrf-acc .rowline { display: flex; gap: 10px; align-items: flex-start; padding: 6px 0; border-bottom: 1px dashed #e4e7ec; }
  .vrf-acc .rl-label { flex: 0 0 110px; min-width: 0; }
  .vrf-acc .rl-body { flex: 1 1 auto; min-width: 0; display: flex; flex-direction: column; gap: 2px; }
  .vrf-acc .rl-kode { line-height: 1.3; }
  .vrf-copy-kode {
    background: #f2f4f7; border: 1px solid #e4e7ec; color: #667085;
    font-size: 10px; padding: 1px 5px; border-radius: 2px;
    vertical-align: middle; transition: all .15s;
  }
  .vrf-copy-kode:hover { background: #1d3a6e; border-color: #1d3a6e; color: #fff; }
  .vrf-acc .rl-val { overflow-wrap: anywhere; word-break: break-word; line-height: 1.4; }

  /* --- dock aksi --- */
  .vrf-dock {
    position: sticky; bottom: 0; z-index: 20;
    background: #fff; border-top: 1px solid #e4e7ec;
    margin: 16px -16px -16px; padding: 10px 16px; box-shadow: 0 -2px 10px rgba(16,24,40,.06);
    border-radius: 0 0 4px 4px;
  }
  /* --- tombol aksi dock --- */
  .vrf-acts { gap: 8px; }
  .vrf-acts .vrf-act {
    flex: 1 1 0; min-width: 0; padding: 7px 0;
    font-size: 12.5px; font-weight: 600; border-radius: 3px;
    transition: all .15s;
  }
  .vrf-acts .vrf-act .fa { font-size: 12px; }
  .vrf-top-acts { display: flex; justify-content: space-between; gap: 8px; margin-bottom: 8px; }
  .vrf-top-acts .btn { min-width: 120px; font-size: 12.5px; font-weight: 600; border-radius: 3px; padding: 7px 16px; }
  .vrf-proses { display: block; width: 100%; padding: 8px 0; font-size: 12.5px; font-weight: 600; border-radius: 3px; }
  /* --- loading --- */
  .vrf-busy {
    position: absolute; inset: 0; z-index: 30;
    display: none; align-items: center; justify-content: center; flex-direction: column; gap: 10px;
    background: rgba(255,255,255,.82); border-radius: 4px; font-size: 12.5px; color: #344054;
  }
  .vrf-busy.on { display: flex; }
  .vrf-busy .fa { font-size: 26px; color: #1d3a6e; }
  .vrf-frame-loading {
    position: absolute; inset: 0; z-index: 5; display: none;
    align-items: center; justify-content: center; gap: 10px; flex-direction: column;
    background: #f4f6f9; font-size: 12.5px; color: #667085;
  }
  .vrf-frame-loading.on { display: flex; }
  .vrf-frame-loading .fa { font-size: 24px; color: #2f5fa8; }
  .btn.is-busy { pointer-events: none; opacity: .7; }
  /* --- tabs aksi: segmented, konsisten --- */
  .vrf-tabs { display: flex; background: #f2f4f7; border: 1px solid #e4e7ec; border-radius: 3px; padding: 3px; gap: 3px; margin-bottom: 8px; }
  .vrf-tabs .nav-item { flex: 1 1 0; min-width: 0; }
  .vrf-tabs .nav-link {
    display: block; width: 100%; text-align: center; padding: 7px 4px; font-size: 12.5px; font-weight: 600;
    color: #667085; border: 0; border-radius: 2px; background: transparent; transition: all .15s;
  }
  .vrf-tabs .nav-link:hover { color: #1d3a6e; background: rgba(255,255,255,.6); }
  .vrf-tabs .nav-link.active { color: #fff; background: #1d3a6e; box-shadow: 0 1px 3px rgba(29,58,110,.3); }
  .vrf-tabs .nav-link .tab-hint { display: block; font-size: 9.5px; font-weight: 400; opacity: .65; letter-spacing: .4px; }

  .vrf-form .form-control, .vrf-form .input-group-addon { border-radius: 0; font-size: 13px; }
  .vrf-form label { font-size: 11.5px; color: #475467; margin-bottom: 3px; }
  .vrf-list { max-height: 130px; overflow-y: auto; }
  @media (max-width: 991px) { .vrf-dock { position: static; } }
  @media (max-width: 576px) {
    .vrf-acc .rowline { flex-wrap: wrap; gap: 4px; }
    .vrf-acc .rl-label { flex: 0 0 100%; }
    .vrf-acc .rl-body { flex: 0 0 100%; }
  }
</style>

<div class="vrf-head">
  <div class="d-flex align-items-center">
    <a href="<?= base_url('app/spj?tab=%23verifikasi') ?>" class="vrf-back mr-3">
      <i class="fa fa-arrow-left mr-1"></i> Kembali
    </a>
    <div class="vrf-head-title">
      <h3><i class="fa fa-check-circle mr-2"></i>Verifikasi Usul SPJ</h3>
      <span class="vrf-head-sub">Periksa dokumen, tentukan MS / TMS / Perbaikan</span>
    </div>
  </div>
  <div class="vrf-head-meta">
    <span class="badge badge-light border text-muted p-1" style="font-size:11px">
      <i class="fa fa-user mr-1"></i><?= strtoupper($role) ?>
    </span>
  </div>
</div>

<div class="vrf-wrap" style="position:relative">
      <div class="vrf-busy" id="vrfBusy">
        <i class="fa fa-spinner fa-spin"></i>
        <span id="vrfBusyText">Memproses…</span>
      </div>

          <?php if ($is_verifikator && ! empty($detail->catatan)) : ?>
            <div class="alert alert-warning rounded-0 border text-dark py-2 px-3"
                 style="font-size:12px" role="alert">
              <i class="fa fa-exclamation-triangle mr-2 text-danger"></i>
              <b>Catatan <?= $detail->catatan_by === 'ADMIN' ? 'Admin' : 'Verifikator' ?>:</b>
              <?= $detail->catatan ?>
            </div>
          <?php endif; ?>
      <div class="vrf-strip mb-3">
        <div>
          <span class="lbl">Status</span>
          <span class="badge p-1 badge-<?= $status_color ?>"><i class="fa fa-circle mr-1" style="font-size:6px"></i><?= $status_label ?></span>
        </div>
        <div class="sep"></div>
        <div>
          <span class="lbl">Nilai</span>
          <span class="angka" id="vrfNilai" style="cursor: pointer" data-show="1"
                title="Tampilkan / sembunyikan nilai">Rp. <?= nominal($detail->jumlah) ?></span>
          <button type="button" id="vrfNilaiToggle" class="btn btn-xs rek-copy"
                  title="Tampilkan / sembunyikan nilai" aria-pressed="true">
            <i class="fa fa-eye"></i>
          </button>
        </div>
        <div class="sep"></div>
        <div>
          <span class="lbl">SPJ</span>
          <span class="val"><?= $detail->periode ?> / <?= $detail->tahun ?></span>
        </div>
        <div>
          <span class="lbl">Rekening</span>
          <span class="rek" id="vrfRek"><?= $koderek ?></span>
          <button type="button" id="vrfCopyRek" class="btn btn-xs rek-copy"
                  title="Salin kode rekening">
            <i class="fa fa-copy"></i>
          </button>
        </div>
        <div class="ml-auto d-flex">
          <button type="button" id="vrfBerkas" class="btn btn-sm btn-light rounded-0 mr-1" style="font-size:12px">
            <i class="fa fa-eye mr-1"></i> Preview
          </button>
          <a href="<?= $berkas ?>" target="_blank" class="btn btn-sm btn-light rounded-0" style="font-size:12px">
            <i class="fa fa-link mr-1"></i> Berkas
          </a>
        </div>
      </div>

      <!-- panel preview berkas, tampil di bawah strip -->
      <div id="vrfPreview" class="d-none rounded border mb-3" style="overflow:hidden; position:relative">
        <div class="vrf-frame-loading" id="vrfFrameLoading">
          <i class="fa fa-spinner fa-spin"></i><span>Memuat berkas…</span>
        </div>
        <?php if ($preview_url !== '') : ?>
          <div class="text-uppercase text-muted text-center border-bottom py-1" style="font-size:10px; letter-spacing:.7px">
            Preview Berkas
          </div>
          <iframe id="vrfPreviewFrame" data-src="<?= $preview_url ?>" src="about:blank"
                  style="width:100%; height:65vh; border:0; display:block; background:#f4f6f9"></iframe>
        <?php else : ?>
          <div class="text-center text-muted p-4" style="font-size:12.5px">
            <i class="fa fa-file-o mr-2"></i>Preview tidak tersedia untuk tipe berkas ini.
            Gunakan tombol <b>Berkas</b> untuk membuka di tab baru.
          </div>
        <?php endif; ?>
      </div>

      <div class="row">
        <!-- KIRI : data usul ( collapsible) -->
        <div class="col-lg-7 pr-lg-3">
          <details class="vrf-acc" open>
            <summary><i class="fa fa-sitemap text-muted"></i> Program / Kegiatan</summary>
            <div class="acc-body">
              <?php foreach ($program as $label => $row) : ?>
                <div class="rowline">
                  <div class="rl-label text-muted"><?= $label ?></div>
                  <div class="rl-body">
                    <div class="rl-kode vrf-kode">
                      <?= $row[0] ?>
                      <button type="button" class="btn btn-xs vrf-copy-kode"
                              data-kode="<?= htmlspecialchars($row[0], ENT_QUOTES) ?>"
                              title="Salin kode <?= strtolower($label) ?>">
                        <i class="fa fa-copy"></i>
                      </button>
                    </div>
                    <div class="rl-val text-uppercase font-weight-bold"><?= $row[1] ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="rowline">
                <div class="rl-label text-muted">Bidang / Bagian</div>
                <div class="rl-body">
                  <div class="rl-val"><?= $detail->nama_part ?></div>
                </div>
              </div>
              <div class="rowline">
                <div class="rl-label text-muted">Keterangan</div>
                <div class="rl-body">
                  <div class="rl-val"><?= $detail->uraian ?></div>
                </div>
              </div>
            </div>
          </details>

          <details class="vrf-acc">
            <summary>
              <i class="fa fa-users text-muted"></i> Pihak Penerima Manfaat
              <span class="badge badge-secondary ml-1"><?= count($list_penerima) ?></span>
            </summary>
            <div class="acc-body">
              <?php if (count($list_penerima) > 0) : ?>
                <div class="vrf-list">
                  <table class="table table-sm table-striped mb-0">
                    <tbody>
                      <?php foreach ($list_penerima as $idx => $penerima) : ?>
                        <tr>
                          <td class="text-center text-muted" style="width:30px"><?= $idx + 1 ?>.</td>
                          <td><?= ! empty($penerima->organisasi) ? $penerima->organisasi : '-' ?></td>
                          <td class="text-muted" style="width:35%"><?= ! empty($penerima->perorangan) ? $penerima->perorangan : '-' ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php else : ?>
                <div class="text-center text-muted">Data penerima manfaat tidak ada</div>
              <?php endif; ?>
            </div>
          </details>

          <details class="vrf-acc">
            <summary><i class="fa fa-history text-muted"></i> Riwayat</summary>
            <div class="acc-body">
              <b>Dientri</b> oleh <?= $userusul->nama ?>
              <span class="vrf-kode">(<?= strtoupper($detail->entri_by) ?>)</span> ·
              <?= longdate_indo(substr($detail->entri_at, 0, 10)) ?> <?= substr($detail->entri_at, 10, 6) ?> WITA
              <?php if ($userverfikasi) : ?>
                <br>
                <b>Diverifikasi</b> oleh <?= $userverfikasi->nama ?>
                <span class="vrf-kode">(<?= strtoupper($detail->verify_by) ?>)</span> ·
                <?= longdate_indo(substr($detail->verify_at, 0, 10)) ?> <?= substr($detail->verify_at, 10, 6) ?> WITA
              <?php endif; ?>
            </div>
          </details>
        </div>

        <!-- KANAN : aksi -->
        <div class="col-lg-5 pl-lg-3">
          <ul class="nav vrf-tabs" id="vrfTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="ms-tab" data-toggle="tab" href="#ms" role="tab" aria-selected="true">
                MS <span class="tab-hint">MEMENUHI SYARAT</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tms-tab" data-toggle="tab" href="#tms" role="tab" aria-selected="false">
                TMS <span class="tab-hint">TIDAK SESUAI</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="perbaikan-tab" data-toggle="tab" href="#perbaikan" role="tab" aria-selected="false">
                Perbaikan <span class="tab-hint">KEMBALI</span>
              </a>
            </li>
          </ul>

          <div class="tab-content border rounded p-3">
            <!-- MS -->
            <div class="tab-pane active show" id="ms" role="tabpanel" aria-labelledby="ms-tab">
              <?=
              form_open(base_url('app/spj/verifikasi_proses'), [
                'id'     => 'formVerifikasi',
                'class'  => 'vrf-form',
                'data-parsley-validate' => '',
                'data-parsley-errors-messages-disabled' => '',
              ], ['status' => 'MS', 'token' => $detail->token]);
              ?>
              <div class="form-group mb-2">
                <label for="is_realisasi"><b>Status Realisasi</b></label>
                <select name="is_realisasi" id="is_realisasi" class="form-control" required <?= $disabled_tms ?>>
                  <option value="">-- Pilih --</option>
                  <option value="LS" <?= $detail->is_realisasi === 'LS' ? 'selected' : '' ?>>LS</option>
                  <option value="UP" <?= $detail->is_realisasi === 'UP' ? 'selected' : '' ?>>UP</option>
                  <option value="GU" <?= $detail->is_realisasi === 'GU' ? 'selected' : '' ?>>GU</option>
                  <option value="TU" <?= $detail->is_realisasi === 'TU' ? 'selected' : '' ?>>TU</option>
                </select>
              </div>
              <div class="form-group mb-2">
                <label for="nomor"><b>Nomor Verifikasi</b></label>
                <input type="text" name="nomor" id="nomor" class="form-control" placeholder="Nomor BKU" value="<?= $detail->nomor_verifikasi ?>" required <?= $disabled_tms ?>>
              </div>
              <div class="form-group mb-0">
                <label for="tanggal"><b>Tanggal Verifikasi</b></label>
                <div class="input-group">
                  <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                  <input type="text" name="tanggal" id="tanggal" class="form-control date"
                         value="<?= format_tanggal($detail->tanggal_verifikasi) ?>" required <?= $disabled_tms ?>>
                </div>
              </div>
              <?= form_close() ?>
            </div>

            <!-- TMS -->
            <div class="tab-pane" id="tms" role="tabpanel" aria-labelledby="tms-tab">
              <?=
              form_open(base_url('app/spj/verifikasi_proses'), [
                'id'     => 'formVerifikasi',
                'class'  => 'vrf-form',
                'data-parsley-validate' => '',
                'data-parsley-errors-messages-disabled' => '',
              ], ['status' => 'TMS', 'token' => $detail->token]);
              ?>
              <div class="form-group mb-0">
                <label for="catatan_tms"><b>Alasan Tidak Memenuhi Syarat</b></label>
                <textarea name="catatan" id="catatan_tms" rows="4" class="form-control"
                          placeholder="Tuliskan alasan TMS…" <?= $disabled_tms ?>><?= $detail->is_status === 'TMS' ? $detail->catatan : '' ?></textarea>
              </div>
              <?= form_close() ?>
            </div>

            <!-- Perbaikan -->
            <div class="tab-pane" id="perbaikan" role="tabpanel" aria-labelledby="perbaikan-tab">
              <?=
              form_open(base_url('app/spj/verifikasi_proses'), [
                'id'     => 'formVerifikasi',
                'class'  => 'vrf-form',
                'data-parsley-validate' => '',
                'data-parsley-errors-messages-disabled' => '',
              ], ['status' => 'UBAH_STATUS', 'token' => $detail->token]);
              ?>
              <div class="form-group mb-2">
                <label for="status"><b>Kembalikan ke Status</b></label>
                <select name="status" id="status" class="form-control" required>
                  <option value="">-- Pilih Status --</option>
                  <?php if ($is_verifikator) : ?>
                    <option value="ENTRI" <?= $detail->is_status === 'ENTRI' ? 'selected' : '' ?>>ENTRI</option>
                  <?php endif; ?>
                  <?php if (in_array($role, ['SUPER_ADMIN', 'ADMIN']) && $detail->is_status === 'VERIFIKASI_ADMIN') : ?>
                    <option value="VERIFIKASI" <?= $detail->is_status === 'VERIFIKASI' ? 'selected' : '' ?>>VERIFIKASI</option>
                  <?php endif; ?>
                </select>
              </div>
              <div class="form-group mb-0">
                <label for="catatan_perbaikan"><b>Keterangan Perbaikan</b></label>
                <textarea name="catatan" id="catatan_perbaikan" rows="4" class="form-control"
                          placeholder="Masukan keterangan perbaikan…" required></textarea>
              </div>
              <?= form_close() ?>
            </div>
          </div>

          <!-- dock aksi, selalu terlihat -->
          <div class="vrf-dock">
            <button type="button" id="vrfProses" class="btn btn-primary vrf-proses">
              <i class="fa fa-save mr-1"></i> Proses
            </button>
            <div class="vrf-top-acts">
              <a href="<?= base_url('app/spj?tab=%23verifikasi') ?>"
                 class="btn btn-light border">
                <i class="fa fa-times mr-1"></i> Batal
              </a>
              <?php if (($role === 'SUPER_ADMIN' || $role === 'ADMIN' || $role === 'VERIFICATOR') && privilages('priv_approve') && $detail->is_status === 'VERIFIKASI_ADMIN') : ?>
                <button type="button" onclick="Selesai('<?= $detail->token ?>')"
                        class="btn btn-success">
                  <i class="fa fa-check-circle mr-1"></i> Selesaikan
                </button>
              <?php endif; ?>
            </div>
          </div>
          <script>
            // busy: overlay penuh + teks
            function vrfBusy(on, text) {
              $("#vrfBusyText").text(text || "Memproses…");
              $("#vrfBusy").toggleClass("on", !!on);
            }

            // dock submit form pada tab yang sedang aktif (id form sama di 3 tab).
            // validasi Parsley + confirm + efek loading dipegang spj_verifikasi.js,
            // supaya batal di modal konfirmasi tidak menyisakan overlay loading.
            $("#vrfProses").on("click", function () {
              $(".tab-pane.active form").submit();
            });

            // copy kode rekening
            $("#vrfCopyRek").on("click", function () {
              var $btn = $(this), txt = $("#vrfRek").text();
              var done = function () {
                $btn.find("i").removeClass("fa-copy").addClass("fa-check");
                setTimeout(function () { $btn.find("i").removeClass("fa-check").addClass("fa-copy"); }, 1200);
              };
              if (navigator.clipboard) {
                navigator.clipboard.writeText(txt).then(done);
              } else {
                var $t = $("<textarea>").val(txt).appendTo("body").select();
                document.execCommand("copy"); $t.remove(); done();
              }
            });

            // show/hide nilai
            $("#vrfNilaiToggle").on("click", function () {
              var $btn = $(this), $n = $("#vrfNilai"), $i = $btn.find("i"),
                  show = !$n.data("show");
              $n.data("show", show).css("filter", show ? "none" : "blur(2.5px)");
              $i.attr("class", show ? "fa fa-eye" : "fa fa-eye-slash");
              $btn.attr("aria-pressed", show ? "true" : "false");
              $n.attr("title", show ? "Sembunyikan nilai" : "Tampilkan nilai");
            });
            $("#vrfNilai").on("click", function () { $("#vrfNilaiToggle").trigger("click"); });

            // copy kode program/kegiatan/sub kegiatan
            $(document).on("click", ".vrf-copy-kode", function () {
              var $btn = $(this), txt = $btn.data("kode") + "", done = function () {
                $btn.find("i").removeClass("fa-copy").addClass("fa-check");
                setTimeout(function () { $btn.find("i").removeClass("fa-check").addClass("fa-copy"); }, 1200);
              };
              if (navigator.clipboard) {
                navigator.clipboard.writeText(txt).then(done);
              } else {
                var $t = $("<textarea>").val(txt).appendTo("body").select();
                document.execCommand("copy"); $t.remove(); done();
              }
            });

            // toggle preview berkas: muat iframe hanya saat dibuka (hemat loading)
            $("#vrfBerkas").on("click", function () {
              var $p = $("#vrfPreview"), $f = $p.find("#vrfPreviewFrame");
              $p.toggleClass("d-none");
              if ($p.hasClass("d-none")) return;
              if ($f.length && $f.attr("src") === "about:blank") {
                $("#vrfFrameLoading").addClass("on");
                vrfBusy(true, "Memuat berkas…");
                $f.one("load", function () {
                  $("#vrfFrameLoading").removeClass("on");
                  vrfBusy(false);
                }).attr("src", $f.data("src"));
                // jaring pengaman: iframe dari GDrive/office kadang tidak fire load
                setTimeout(function () {
                  $("#vrfFrameLoading").removeClass("on");
                  vrfBusy(false);
                }, 8000);
              } else {
                vrfBusy(true, "Memuat berkas…");
                $f.attr("src", $f.attr("src")); // force reload
                $("#vrfFrameLoading").addClass("on");
                setTimeout(function () {
                  $("#vrfFrameLoading").removeClass("on");
                  vrfBusy(false);
                }, 3000);
              }
            });
          </script>
        </div>
      </div>
</div>