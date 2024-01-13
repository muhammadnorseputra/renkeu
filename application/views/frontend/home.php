  <!-- ======= Hero Section ======= -->
  <section id="hero">
    <div class="hero-container">
      <h1 data-aos="fade-down">Welcome to <?= getSetting('APPName') ?></h1>
      <h2 data-aos="fade-up" data-aos-delay="250"><?= getSetting('APPDescription') ?></h2>
      <img src="<?= base_url('template/landingpage/assets/img/hero-img.png') ?>" data-aos="zoom-in" data-aos-delay="400" alt="Hero Imgs">
      <a href="<?= base_url('login') ?>" class="btn-get-started scrollto" data-aos="fade-up" data-aos-delay="1500">Log In Aplikasi</a>
      <!-- <div class="btns">
        <a href="#"><i class="fa fa-apple fa-3x"></i> App Store</a>
        <a href="#"><i class="fa fa-play fa-3x"></i> Google Play</a>
        <a href="#"><i class="fa fa-windows fa-3x"></i> windows</a>
      </div> -->
    </div>
  </section><!-- End Hero Section -->