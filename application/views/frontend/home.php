  <!-- ======= Hero Section ======= -->
  <section id="hero">
    <div class="hero-overlay"></div>
    <div class="hero-glow"></div>
    <div class="container hero-container">
      <div class="hero-grid">
        <!-- Left: content -->
        <div class="hero-content">
          <div class="hero-badge" data-aos="fade-down" data-aos-delay="100">
            <i class="bi bi-buildings"></i> Sistem Informasi Manajemen Kinerja + Keuangan
          </div>

          <h1 data-aos="fade-up" data-aos-delay="200">
            <?= getSetting('APPName') ?>+
          </h1>

          <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="300">
            Digitalisasi penyusunan laporan capaian kinerja & realisasi keuangan — 
            mulai dari verifikasi SAKIPRA hingga monitoring belanja harian.
          </p>

          <div class="hero-actions" data-aos="fade-up" data-aos-delay="400">
            <a href="<?= base_url('login') ?>" class="btn-hero-primary">
              <i class="bi bi-box-arrow-in-right"></i> Masuk Aplikasi
            </a>
            <a href="#features" class="btn-hero-ghost">
              Lihat Fitur <i class="bi bi-arrow-down-short"></i>
            </a>
          </div>
        </div>

        <!-- Right: image -->
        <div class="hero-visual" data-aos="fade-left" data-aos-delay="300">
          <div class="hero-image-frame">
            <img src="<?= base_url('template/assets/ChatGPT Image Feb 26, 2026, 03_47_16 PM.png') ?>" alt="Dashboard Preview" class="hero-image">
          </div>
          <div class="hero-float-card card-peg">
            <i class="bi bi-people-fill"></i>
            <div>
              <strong><?= $jml_pegawai ?></strong>
              <small>Pegawai</small>
            </div>
          </div>
          <div class="hero-float-card card-bid">
            <i class="bi bi-diagram-3-fill"></i>
            <div>
              <strong><?= $jml_bidang ?></strong>
              <small>Bidang / Bagian</small>
            </div>
          </div>
          <div class="hero-float-card card-ta">
            <i class="bi bi-calendar4-week"></i>
            <div>
              <strong><?= $tahun_anggaran ?></strong>
              <small>Tahun Anggaran</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section><!-- End Hero Section -->
  <!-- ======= About Us Section ======= -->
  <section id="about-us" class="about-us padd-section">
    <div class="container" data-aos="fade-up">
      <div class="row justify-content-center">

        <div class="col-md-5 col-lg-3">
          <img src="https://images.unsplash.com/photo-1763550662603-78aa2f2033bf?q=80&w=1014&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="About" data-aos="zoom-in" data-aos-delay="100">
        </div>

        <div class="col-md-7 col-lg-5">
          <div class="about-content" data-aos="fade-left" data-aos-delay="100">

            <h2><span>Digta<sup>+</sup></span>Team</h2>
            <p><?= getSetting('APPDescription') ?></p>

            <ul class="list-unstyled">
              <li><i class="vi bi-chevron-right"></i>Dashboard Real-time</li>
              <li><i class="vi bi-chevron-right"></i>Verifikasi Kinerja SAKIPRA</li>
              <li><i class="vi bi-chevron-right"></i>Realisasi Keuangan & Target</li>
              <li><i class="vi bi-chevron-right"></i>Export Laporan Otomatis</li>
            </ul>

          </div>
        </div>

      </div>
    </div>
  </section><!-- End About Us Section -->

  <!-- ======= Features Section ======= -->
  <section id="features" class="padd-section">
    <div class="container" data-aos="fade-up">
      <div class="section-title text-center">
        <h2>Fitur Unggulan</h2>
        <p class="separator">Modul utama untuk mendukung pengelolaan kinerja & keuangan</p>
      </div>

      <div class="row">
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
          <div class="feature-block">
            <div class="feature-icon">
              <i class="bi bi-speedometer2"></i>
            </div>
            <h4>Dashboard Real-time</h4>
            <p>Monitoring capaian kinerja & realisasi keuangan dalam satu tampilan</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
          <div class="feature-block">
            <div class="feature-icon">
              <i class="bi bi-check2-circle"></i>
            </div>
            <h4>Verifikasi Kinerja SAKIPRA</h4>
            <p>Validasi triwulan otomatis dengan checklist 6 indikator per periode</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
          <div class="feature-block">
            <div class="feature-icon">
              <i class="bi bi-file-earmark-text"></i>
            </div>
            <h4>Rekap SPJ & Pajak</h4>
            <p>Upload & kelola dokumen perjadin, pajak pusat & daerah terpusat</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
          <div class="feature-block">
            <div class="feature-icon">
              <i class="bi bi-shield-check"></i>
            </div>
            <h4>Pengelolaan Resiko</h4>
            <p>Dokumentasi rencana & hasil mitigasi resiko terstruktur</p>
          </div>
        </div>
      </div>
    </div>
  </section><!-- End Features Section -->

  <!-- ======= Transparansi Anggaran Section ======= -->
  <section id="anggaran" class="padd-section anggaran-section">
    <div class="container" data-aos="fade-up">
      <div class="section-title text-center">
        <h2>Transparansi Anggaran <?= $tahun_anggaran ?></h2>
        <p class="separator">Pagu dan realisasi belanja per kelompok, diperbarui otomatis dari data SPJ</p>
      </div>

      <div class="anggaran-summary">
        <div class="anggaran-stat">
          <span class="anggaran-stat-label"><i class="bi bi-wallet2"></i> Pagu Murni</span>
          <strong class="anggaran-stat-value">Rp. <?= nominal($total_pagu_murni) ?></strong>
        </div>
        <div class="anggaran-stat">
          <span class="anggaran-stat-label"><i class="bi bi-arrow-repeat"></i> Pagu Perubahan</span>
          <strong class="anggaran-stat-value">Rp. <?= nominal($total_pagu_perubahan) ?></strong>
        </div>
        <div class="anggaran-stat">
          <span class="anggaran-stat-label"><i class="bi bi-graph-up-arrow"></i> Total Realisasi</span>
          <strong class="anggaran-stat-value">Rp. <?= nominal($total_realisasi) ?></strong>
        </div>
        <div class="anggaran-stat anggaran-stat-accent">
          <span class="anggaran-stat-label"><i class="bi bi-percent"></i> Capaian</span>
          <strong class="anggaran-stat-value"><?= $persentase_capaian ?>%</strong>
        </div>
      </div>

      <!-- Tabs info pagu / realisasi / capaian -->
      <ul class="nav nav-tabs anggaran-tabs" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" id="tab-pagu-btn" data-bs-toggle="tab" data-bs-target="#tab-pagu" type="button" role="tab"><i class="bi bi-wallet2 me-1"></i>Pagu</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="tab-realisasi-btn" data-bs-toggle="tab" data-bs-target="#tab-realisasi" type="button" role="tab"><i class="bi bi-graph-up-arrow me-1"></i>Realisasi</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="tab-capaian-btn" data-bs-toggle="tab" data-bs-target="#tab-capaian" type="button" role="tab"><i class="bi bi-percent me-1"></i>Capaian</button></li>
      </ul>
      <div class="tab-content anggaran-tab-content">
        <div class="tab-pane fade show active" id="tab-pagu" role="tabpanel">
          <div class="anggaran-card">
            <h4 class="anggaran-card-title">Pagu Murni &amp; Perubahan per Kelompok Belanja</h4>
            <div class="anggaran-chart-wrap">
              <canvas id="chartPaguKelompok"></canvas>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="tab-realisasi" role="tabpanel">
          <div class="anggaran-card">
            <h4 class="anggaran-card-title">Realisasi Belanja per Kelompok Belanja</h4>
            <div class="anggaran-chart-wrap">
              <canvas id="chartRealisasiKelompok"></canvas>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="tab-capaian" role="tabpanel">
          <div class="anggaran-card">
            <h4 class="anggaran-card-title">Capaian Anggaran per Kelompok Belanja</h4>
            <div class="anggaran-chart-wrap">
              <canvas id="chartCapaianKelompok"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Chart Belanja Harian -->
      <div class="anggaran-card mt-4">
        <h4 class="anggaran-card-title">Belanja Harian <?= $tahun_anggaran ?></h4>
        <div class="anggaran-chart-wrap">
          <canvas id="chartBelanjaHarian" height="130" data-chart='<?= $chart_harian ?>'></canvas>
        </div>
      </div>

      <?php $pagu_chart_data = json_decode($pagu_chart, true); ?>
      <div class="anggaran-table-wrap">
        <table class="table anggaran-table">
          <thead>
            <tr>
              <th>Kelompok Belanja</th>
              <th class="text-end">Pagu Murni</th>
              <th class="text-end">Pagu Perubahan</th>
              <th class="text-end">Total Pagu</th>
              <th class="text-end">Realisasi</th>
              <th class="text-end">%</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pagu_chart_data['labels'] as $i => $label):
              $pagu_murni = $pagu_chart_data['pagu_murni'][$i];
              $pagu_perubahan = $pagu_chart_data['pagu_perubahan'][$i];
              $real = $pagu_chart_data['realisasi'][$i];
              $pagu_aktif = $pagu_perubahan > 0 ? $pagu_perubahan : $pagu_murni;
              $pct = $pagu_aktif > 0 ? round(($real / $pagu_aktif) * 100, 1) : 0;
            ?>
              <tr>
                <td><?= $label ?></td>
                <td class="text-end">Rp. <?= nominal($pagu_murni) ?></td>
                <td class="text-end">Rp. <?= nominal($pagu_perubahan) ?></td>
                <td class="text-end fw-semibold">Rp. <?= nominal($pagu_murni + $pagu_perubahan) ?></td>
                <td class="text-end">Rp. <?= nominal($real) ?></td>
                <td class="text-end"><span class="anggaran-badge <?= $pct >= 80 ? 'badge-success' : ($pct >= 50 ? 'badge-warning' : 'badge-danger') ?>"><?= $pct ?>%</span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section><!-- End Transparansi Anggaran Section -->

  <script id="pagu-chart-data" type="application/json"><?= $pagu_chart ?></script>

  <!-- ======= FAQ Section ======= -->
  <section id="faq" class="padd-section">
    <div class="container" data-aos="fade-up">
      <div class="section-title text-center">
        <h2>Pertanyaan Umum</h2>
        <p class="separator">Jawaban singkat untuk pertanyaan yang sering diajukan</p>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Apa itu Digta Sunanpraja+?
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Digta Sunanpraja+ adalah sistem informasi manajemen kinerja & keuangan terintegrasi untuk mendukung penyusunan laporan capaian kinerja (SAKIP) dan realisasi keuangan (SPJ) secara digital, efisien, dan terstandarisasi.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Modul apa saja yang tersedia?
                </button>
              </h2>
              <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Modul utama: <strong>Dashboard Real-time</strong> (monitoring kinerja & keuangan), <strong>Verifikasi Kinerja SAKIPRA</strong> (validasi triwulan 6 indikator), <strong>Rekap SPJ & Pajak</strong> (perjadin, pajak pusat & daerah), <strong>Pengelolaan Resiko</strong> (rencana & hasil mitigasi), <strong>Monitoring Belanja</strong> (harian/bulanan), <strong>Buku Jaga</strong> (belanja kegiatan & rincian), serta <strong>Rincian Anggaran</strong> (program & kegiatan).
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Siapa yang bisa menggunakan aplikasi ini?
                </button>
              </h2>
              <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Pegawai perangkat daerah yang memiliki akun login (role: ADMIN, SUPER_ADMIN, USER, SUPER_USER, VERIFIKATOR). Akses modul dibatasi oleh hak akses (privileges) dan validitas profil.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                  Bagaimana cara verifikasi kinerja SAKIPRA?
                </button>
              </h2>
              <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Verifikator memilih pegawai → pilih triwulan (TW1–TW4) → ceklis 6 indikator per periode. Sistem otomatis menghitung skor (maks 6 per TW, total 24). Status "Lengkap" jika total ≥ 24. Data terkunci per tahun anggaran aktif di session login.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                  Format file apa yang didukung untuk upload dokumen?
                </button>
              </h2>
              <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  <strong>PDF, XLS, XLSX</strong> maksimal 2 MB per file. Kategori: Rekap Perjadin, Rekap Pajak (Pusat/Daerah), Pengelolaan Resiko (Rencana/Hasil). Upload via drag-drop dengan progress bar real-time.
                </div>
              </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="headingSix">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                  Apakah data real-time dan bisa diekspor?
                </button>
              </h2>
              <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                  Ya. Dashboard menampilkan realisasi keuangan & capaian kinerja real-time. Semua tabel mendukung ekspor Excel/PDF/Print via DataTables. Chart belanja harian interaktif dengan tooltip total rupiah.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section><!-- End FAQ Section -->

  <!-- ======= Team Section ======= -->
  <section id="team" class="padd-section">
    <div class="container" data-aos="fade-up">
      <div class="section-title text-center">
        <h2>Tim Kami</h2>
        <p class="separator">Pengembang & pengelola sistem Digta Sunanpraja+</p>
      </div>

      <div class="team-slider swiper">
        <div class="swiper-wrapper">
          <?php
          $role_desc = [
            'SUPER_ADMIN' => 'Administrator Sistem',
            'ADMIN'       => 'Operator',
            'SUPER_USER'  => 'Super User',
            'VERIFICATOR' => 'Verifikator',
            'USER'        => 'Pengguna',
            'ARSIP_USER'  => 'Pengelola Arsip',
            'BENDAHARA'   => 'Bendahara',
          ];
          foreach ($team as $member): ?>
          <div class="swiper-slide">
            <div class="team-card">
              <div class="team-image">
                <img src="<?= base_url('template/assets/picture_akun/' . $member->pic) ?>" alt="<?= $member->nama ?>" class="img-fluid" loading="lazy">
                <div class="team-overlay">
                  <span class="team-role-badge"><?= $role_desc[$member->role] ?? $member->role ?></span>
                </div>
              </div>
              <div class="team-info">
                <h4><?= $member->nama ?></h4>
                <p class="team-bidang"><?= $member->bidang ?? '-' ?><?= !empty($member->singkatan) ? ' (' . $member->singkatan . ')' : '' ?></p>
                <p class="team-role-desc"><?= $role_desc[$member->role] ?? $member->role ?></p>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>
  </section><!-- End Team Section -->

  <!-- ======= Logo Section ======= -->
    <section id="design-logo" class="padd-section text-center">

      <div class="container" data-aos="fade-up">
        <div class="section-title text-center">
          <h2>Desain Logo</h2>
          <p class="separator">Desain Logo yang telah dibuat oleh tim</p>
        </div>

        <div class="logo-showcase">
          <a href="<?= base_url('template/assets/desain-logo.jpeg') ?>" class="glightbox" data-gallery="logo-gallery" data-title="Desain Logo Digta Sunanpraja+">
            <img src="<?= base_url('template/assets/desain-logo.jpeg') ?>" class="img-fluid" alt="Desain Logo Digta Sunanpraja+" loading="lazy">
            <span class="logo-zoom"><i class="bi bi-zoom-in"></i></span>
          </a>
        </div>
      </div>

    </section><!-- End Logo Section -->