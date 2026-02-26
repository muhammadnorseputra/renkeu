  <!-- ======= Hero Section ======= -->
  <section id="hero">
    <div class="hero-container">
      <h1 data-aos="fade-down">Welcome</h1>
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
  <!-- ======= About Us Section ======= -->
  <section id="about-us" class="about-us padd-section">
    <div class="container" data-aos="fade-up">
      <div class="row justify-content-center">

        <div class="col-md-5 col-lg-3">
          <img src="https://images.unsplash.com/photo-1763550662603-78aa2f2033bf?q=80&w=1014&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="About" data-aos="zoom-in" data-aos-delay="100">
        </div>

        <div class="col-md-7 col-lg-5">
          <div class="about-content" data-aos="fade-left" data-aos-delay="100">

            <h2><span>Digta<sup>+</sup></span>Team </h2>
            <p>Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat
            </p>

            <ul class="list-unstyled">
              <li><i class="vi bi-chevron-right"></i>Creative Design</li>
              <li><i class="vi bi-chevron-right"></i>Retina Ready</li>
              <li><i class="vi bi-chevron-right"></i>Easy to Use</li>
              <li><i class="vi bi-chevron-right"></i>Unlimited Features</li>
              <li><i class="vi bi-chevron-right"></i>Unlimited Features</li>
            </ul>

          </div>
        </div>

      </div>
    </div>
  </section><!-- End About Us Section -->

  <!-- ======= Logo Section ======= -->
    <section id="design-logo" class="padd-section text-center">

      <div class="container" data-aos="fade-up">
        <div class="section-title text-center">
          <h2>Desain Logo</h2>
          <p class="separator">Desain Logo yang telah dibuat oleh tim</p>
        </div>

        <div class="screens-slider swiper">
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><img src="<?= base_url('template/assets/desain-logo.jpeg') ?>" class="img-fluid" alt="" width="700"></div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>

    </section><!-- End Screenshots Section -->