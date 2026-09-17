<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= isset($title) ? $title : 'Welcome to Emonev App' ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('template/assets/picture_akun/'.$this->session->userdata('pic')) ?>">

    <!-- Bootstrap -->
    <link href="<?= base_url('template/backend/vendors/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?= base_url('template/backend/vendors/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?= base_url('template/backend/vendors/nprogress/nprogress.css') ?>" rel="stylesheet">
    <!-- jQuery custom content scroller -->
    <link href="<?= base_url('template/backend/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css"') ?>" rel="stylesheet"/>

    <!-- Custom Theme Style -->
    <link href="<?= base_url('template/backend/build/css/admin.css') ?>" rel="stylesheet">
    <link href="<?= base_url('template/backend/build/css/custom.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css"/>

    <!-- Splash screen loading style -->
    <style>
      #splash-screen {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        transition: opacity 0.35s ease, visibility 0.35s ease;
      }
      #splash-screen.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
      }
      .splash-spinner {
        width: 56px;
        height: 56px;
        border: 5px solid rgba(0, 0, 0, 0.12);
        border-top-color: #1a73e8;
        border-radius: 50%;
        animation: splash-rotate 0.8s linear infinite;
      }
      @keyframes splash-rotate {
        to { transform: rotate(360deg); }
      }
      /* Sidebar selalu di atas konten, tetapi di bawah splash screen (z-index 99999) */
      .nav-md .container.body .col-md-3.left_col,
      .nav-sm .container.body .col-md-3.left_col {
        z-index: 9999;
      }
      /* Top bar: background blur, support semua browser modern */
      .top_nav .nav_menu {
        background: rgba(237, 237, 237, 0.75);
        -webkit-backdrop-filter: blur(10px);
        backdrop-filter: blur(10px);
      }
      @supports not ((backdrop-filter: blur(10px)) or (-webkit-backdrop-filter: blur(10px))) {
        .top_nav .nav_menu {
          background: rgba(237, 237, 237, 0.95);
        }
      }
    </style>
    <script>
      // Splash screen: tampil saat navigasi browser dimulai, hilang saat halaman selesai dimuat
      window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
          // BFCache: halaman di-restore, sembunyikan langsung
          document.getElementById('splash-screen').classList.add('hidden');
        }
      });
      window.addEventListener('load', function () {
        document.getElementById('splash-screen').classList.add('hidden');
      });
      // Tampilkan splash segera saat menu/submenu sidebar diklik
      document.addEventListener('click', function (e) {
        var link = e.target.closest('#sidebar-menu a[href]');
        if (link && link.getAttribute('href') !== '#') {
          document.getElementById('splash-screen').classList.remove('hidden');
        }
      });
    </script>

    <!-- Link Tags Dinamic-->
	  <?php
    if(isset($autoload_css)) {
      foreach ($autoload_css as $css) :
          echo link_tag($css);
      endforeach;
    }
    ?>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
    <script src="<?= base_url('template/backend/vendors/jquery/dist/jquery.min.js') ?>"></script>
    <script src="<?= base_url('template/custom-js/route.js') ?>"></script>
  </head>

  <body class="<?= getSetting('FooterFix') ?>">
    <script>
      // Restore state sidebar (expand/collapse) dari localStorage sebelum layout dirender.
      // Default: nav-md (expand).
      (function () {
        var saved = localStorage.getItem('sidebar_state') || 'nav-md';
        document.body.className = document.body.className
          .replace(/\bnav-(sm|md)\b/g, '')
          .trim() + ' ' + saved;
      })();
    </script>
    <!-- Splash screen: tampil sebelum konten dimuat -->
    <div id="splash-screen">
      <div class="splash-spinner"></div>
    </div>