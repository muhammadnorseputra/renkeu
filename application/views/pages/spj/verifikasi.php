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

          <details class="vrf-acc" open>
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

          <details class="vrf-acc" open>
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
                <label class="d-block mb-1"><b>Status Realisasi</b>
                  <span class="text-danger">*</span>
                </label>
                <div class="vrf-radios" role="radiogroup" aria-label="Status Realisasi">
                  <?php
                  $realisasi = ['LS' => 'Langsung', 'UP' => 'Uang Persediaan', 'GU' => 'Ganti Uang', 'TU' => 'Tambahan Uang'];
                  foreach ($realisasi as $opt => $ket) : ?>
                    <label class="vrf-radio">
                      <input type="radio" name="is_realisasi" value="<?= $opt ?>" required
                             <?= $detail->is_realisasi === $opt ? 'checked' : '' ?> <?= $disabled_tms ?>>
                      <span class="vrf-radio-box">
                        <i class="fa fa-check vrf-tick" aria-hidden="true"></i>
                        <span class="kode"><?= $opt ?></span>
                        <span class="ket"><?= $ket ?></span>
                      </span>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="form-group mb-2">
                <label for="nomor" class="vrf-field-label"><i class="fa fa-hashtag mr-1 text-muted"></i> Nomor Verifikasi <span class="text-danger">*</span></label>
                <div class="vrf-inp-wrap" id="vrfNomorWrap">
                  <i class="fa fa-file-text-o vrf-inp-icon" aria-hidden="true"></i>
                  <input type="text" name="nomor" id="nomor" class="form-control vrf-inp" placeholder="cth: 900/123/BKPSDM/2026" autocomplete="off"
                         value="<?= $detail->nomor_verifikasi ?>" required <?= $disabled_tms ?>>
                  <button type="button" class="vrf-inp-clear" id="vrfNomorClear" tabindex="-1" title="Hapus nomor" aria-label="Hapus nomor"><i class="fa fa-times-circle"></i></button>
                </div>
                <small class="vrf-hint">Nomor  Verifikasi</small>
              </div>
              <div class="form-group mb-0">
                <label for="tanggal" class="vrf-field-label"><i class="fa fa-calendar mr-1 text-muted"></i> Tanggal Verifikasi <span class="text-danger">*</span></label>
                <div class="vrf-date-wrap" id="vrfDateWrap">
                  <i class="fa fa-calendar vrf-date-icon" aria-hidden="true"></i>
                  <input type="text" name="tanggal" id="tanggal" class="form-control vrf-date-input" placeholder="DD-MM-YYYY" autocomplete="off"
                         value="<?= format_tanggal($detail->tanggal_verifikasi) ?>" required <?= $disabled_tms ?>>
                  <button type="button" class="vrf-date-clear" id="vrfDateClear" tabindex="-1" title="Hapus tanggal" aria-label="Hapus tanggal"><i class="fa fa-times-circle"></i></button>
                </div>
                <small class="vrf-hint">Format: hari-bulan-tahun</small>
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
        </div>
      </div>
</div>