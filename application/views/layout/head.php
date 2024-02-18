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

  <body class="<?= getSetting('toggleNavbar') ?> <?= getSetting('FooterFix') ?>">