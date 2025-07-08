<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?= getSetting('APPName') ?> - <?= getSetting('APPDescription') ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= base_url('template/assets/logo.png') ?>" rel="icon">
  <link href="<?= base_url('template/assets/logo.png') ?>" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Roboto:100,300,400,500,700|Philosopher:400,400i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= base_url('template/landingpage/assets/vendor/aos/aos.css') ?>" rel="stylesheet">
  <link href="<?= base_url('template/landingpage/assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('template/landingpage/assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?= base_url('template/landingpage/assets/css/style.css') ?>" rel="stylesheet">

  <!-- =======================================================
  * Template Name: eStartup
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/estartup-bootstrap-landing-page-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

      <div id="logo">
        <!-- <h1 data-aos="fade-in"><?= getSetting('APPName') ?></h1> -->
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.html"><img src="<?= base_url('template/landingpage/assets/img/logo.png') ?>" alt="" title="" /></a>-->
      </div>

      <!-- <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto <?= isActive('/') ?>" href="<?= base_url('/') ?>">Home</a></li>
          <li><a class="nav-link scrollto <?= isActive('about') ?>" href="<?= base_url('frontend/about') ?>">About</a></li>
          <li><a class="nav-link scrollto <?= isActive('featured') ?>" href="<?= base_url('frontend/featured') ?>">Features</a></li>
          <li><a class="nav-link scrollto <?= isActive('screenshot') ?>" href="<?= base_url('frontend/screenshot') ?>">Screenshots</a></li>
          <li><a class="nav-link scrollto <?= isActive('team') ?>" href="<?= base_url('frontend/team') ?>">Team</a></li>
          <li><a class="nav-link scrollto <?= isActive('contact') ?>" href="<?= base_url('frontend/contact') ?>">Contact</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>.navbar -->

    </div>
  </header><!-- End Header -->

  <?php $this->load->view($content); ?>
  <!-- Vendor JS Files -->
  <script src="<?= base_url('template/landingpage/assets/vendor/aos/aos.js') ?>"></script>
  <script src="<?= base_url('template/landingpage/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

  <!-- Template Main JS File -->
  <script src="<?= base_url('template/landingpage/assets/js/main.js') ?>"></script>
  <script>
    AOS.init({
      duration: 1000,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    })
  </script>
</body>

</html>