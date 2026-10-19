<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo isset($title) ? $title : 'Welcome to Emonev App' ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('template/assets/picture_akun/'.$this->session->userdata('pic')) ?>">

    <!-- Bootstrap -->
    <link href="<?php echo asset_url('template/backend/vendors/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo asset_url('template/backend/vendors/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo asset_url('template/backend/vendors/nprogress/nprogress.css') ?>" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo asset_url('template/backend/build/css/admin.css') ?>" rel="stylesheet">
    <link href="<?php echo asset_url('template/backend/build/css/custom.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css"/>
    <!-- uiConfirm / uiAlert modal -->
    <link href="<?php echo asset_url('template/custom-css/ui-confirm.css') ?>" rel="stylesheet">
    <link href="<?php echo asset_url('template/custom-css/app-update.css') ?>" rel="stylesheet">

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
      @media (prefers-color-scheme: dark) {
        #splash-screen {
          background: rgba(0, 0, 0, 0.6);
        }
        .splash-spinner {
          border-color: rgba(255, 255, 255, 0.15);
          border-top-color: #1a73e8;
        }
        /* Logo abu-abu muda → brighten agar terlihat di atas backdrop gelap */
        .splash-logo,
        .left_col .site_title img {
          filter: brightness(1.6) contrast(0.95);
        }
      }
      @media (prefers-color-scheme: light) {
        :root {
          /* Palette */
          --sb-bg: #F7F7F7;
          --sb-border: #E2E8F0;
          --sb-text: #1A2B3C;
          --sb-text-muted: #6B7B8C;
          --sb-text-sub: #4A5568;
          --sb-accent: #1A73E8;
          --sb-hover: #EFF4FA;
          --sb-active: #E1EAF7;
        }

        .left_col,
        .nav_title {
          background: var(--sb-bg) !important;
        }
        /* Body & area konten memakai warna sama dengan content */
        body,
        .container.body,
        .right_col {
          background: #F7F7F7 !important;
          color: #1A2B3C;
        }
        /* Footer mengikuti warna konten */
        .right_col footer,
        .container.body footer {
          background: #F7F7F7 !important;
          color: #6B7B8C;
          border-top: 1px solid var(--sb-border);
        }
        .right_col footer .pull-right {
          color: #6B7B8C;
        }
        .left_col .site_title img {
          filter: brightness(0.35) saturate(0) contrast(1.8);
        }
        .nav-md .container.body .col-md-3.left_col,
        .nav-sm .container.body .col-md-3.left_col {
          border-right: 1px solid var(--sb-border);
        }
        .left_col a,
        .left_col h3,
        .left_col .profile_info h2,
        .left_col .profile_info p,
        .left_col .profile_info span {
          color: var(--sb-text) !important;
        }

        /* Menu utama: hover lembut, active tegas dengan aksen kiri */
        .left_col .side-menu > li > a,
        .nav-sm .left_col .side-menu > li > a {
          border-left: 3px solid transparent;
          border-radius: 0 3px 3px 0;
          transition: background-color .15s ease, color .15s ease;
        }
        .left_col .side-menu > li > a:hover,
        .nav-sm .left_col .side-menu > li > a:hover {
          background: var(--sb-hover) !important;
          color: var(--sb-accent) !important;
        }
        .left_col .side-menu > li.active > a,
        .left_col .side-menu > li.current-page > a,
        .nav-sm .left_col .side-menu > li.active-sm > a {
          background: var(--sb-active) !important;
          border-left-color: var(--sb-accent) !important;
          color: var(--sb-accent) !important;
          font-weight: 600;
          box-shadow: none !important;
          text-shadow: none !important;
        }
        .left_col .side-menu > li > a i,
        .nav-sm .left_col .side-menu > li > a i {
          color: var(--sb-text-muted) !important;
        }
        .left_col .side-menu > li > a:hover i,
        .left_col .side-menu > li.active > a i,
        .nav-sm .left_col .side-menu > li > a:hover i,
        .nav-sm .left_col .side-menu > li.active-sm > a i {
          color: var(--sb-accent) !important;
        }
        .left_col .side-menu > li.active,
        .left_col .side-menu > li.current-page,
        .nav-sm .left_col .side-menu > li.active-sm {
          border-right-color: transparent !important;
        }

        /* Submenu */
        .left_col .child_menu li:hover,
        .nav-sm .left_col ul.nav.child_menu li:hover,
        .left_col .child_menu li.active,
        .nav-sm .left_col ul.nav.child_menu li.active {
          background-color: rgba(26, 115, 232, 0.07) !important;
        }
        .left_col .child_menu li a,
        .nav-sm .left_col ul.nav.child_menu li a {
          color: var(--sb-text-sub) !important;
        }
        .left_col .child_menu li a:hover,
        .left_col .child_menu li.active a,
        .left_col .child_menu li.current-page a,
        .nav-sm .left_col ul.nav.child_menu li a:hover,
        .nav-sm .left_col ul.nav.child_menu li.active a {
          background: transparent !important;
          color: var(--sb-accent) !important;
          font-weight: 600;
        }
        .nav-sm .left_col ul.nav.child_menu {
          background: var(--sb-bg) !important;
          border-right: 1px solid var(--sb-border);
          box-shadow: 2px 0 8px rgba(26, 43, 60, .08);
        }
        .nav-md ul.child_menu li:before {
          background: var(--sb-text-muted) !important;
        }
        .nav-md ul.child_menu li:after {
          border-left-color: var(--sb-border) !important;
        }

        /* Profile & section label */
        .left_col .profile_img {
          border-color: var(--sb-border) !important;
        }
        .left_col .navbar.nav_title {
          border-bottom-color: var(--sb-border) !important;
        }
        .left_col .menu_section h3 {
          color: var(--sb-text-muted) !important;
          text-shadow: none !important;
          letter-spacing: .8px;
        }

        /* Footer */
        .left_col .sidebar-footer {
          background: var(--sb-bg) !important;
          border-top: none !important;
          border-right: 1px solid var(--sb-border) !important;
        }
        .left_col .sidebar-footer a {
          color: var(--sb-text-muted) !important;
          background: transparent !important;
          transition: color .15s ease, background-color .15s ease;
        }
        .left_col .sidebar-footer a:hover {
          color: var(--sb-accent) !important;
          background: var(--sb-hover) !important;
        }

      }
      /* Top bar light mode — bersih dan profesional */
      @media (prefers-color-scheme: light) {
        .top_nav,
        .nav_menu {
          background: #F7F7F7 !important;
          border-bottom-color: var(--sb-border) !important;
        }
        .top_nav .navbar-right li a,
        .top_nav .navbar-nav > li > a {
          color: var(--sb-text) !important;
        }
        .top_nav .dropdown-menu {
          background: #FFFFFF !important;
          border-color: var(--sb-border) !important;
        }
        .top_nav .dropdown-menu li a {
          color: var(--sb-text) !important;
        }
        .top_nav .dropdown-menu li a:hover {
          background: var(--sb-hover) !important;
          color: var(--sb-accent) !important;
        }
      }
      .splash-logo {
        width: 120px;
        height: 120px;
        object-fit: contain;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      }
      .splash-spinner {
        width: 180px;
        height: 180px;
        border: 8px solid rgba(0, 0, 0, 0.12);
        border-top-color: #1a73e8;
        border-radius: 50%;
        animation: splash-rotate 0.8s linear infinite;
        position: relative;
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
          if (isset($autoload_css)) {
              foreach ($autoload_css as $css):
                  echo link_tag(asset_url($css));
              endforeach;
          }
      ?>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
    <script src="<?php echo asset_url('template/backend/vendors/jquery/dist/jquery.min.js') ?>"></script>
    <script src="<?php echo asset_url('template/custom-js/route.js') ?>"></script>
  </head>

  <body class="<?php echo getSetting('FooterFix') ?>">
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
      <img class="splash-logo" src="<?php echo base_url('template/assets/Logo DSP Monokrom/Logo3.png') ?>" alt="Logo">
      <div class="splash-spinner"></div>
    </div>