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