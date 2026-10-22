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
      @media (prefers-color-scheme: dark) {
        :root {
          color-scheme: dark;
          --app-bg: #111923;
          --app-surface: #18232F;
          --app-surface-raised: #1D2A37;
          --app-border: #2D3B4B;
          --app-text: #E5EDF5;
          --app-text-muted: #A6B4C3;
          --app-accent: #85BAFF;
          --app-hover: #202E3C;
          --app-active: #23364B;
        }

        body,
        .container.body,
        .right_col {
          background: var(--app-bg) !important;
          color: var(--app-text);
        }

        .left_col,
        .nav_title,
        .top_nav,
        .nav_menu,
        .right_col footer,
        .container.body footer {
          background: var(--app-surface) !important;
          color: var(--app-text);
        }

        .nav-md .container.body .col-md-3.left_col,
        .nav-sm .container.body .col-md-3.left_col,
        .top_nav,
        .nav_menu,
        #is_perubahan,
        .right_col footer,
        .container.body footer {
          border-color: var(--app-border) !important;
        }

        .nav-md .container.body .col-md-3.left_col,
        .nav-sm .container.body .col-md-3.left_col {
          border-right: 1px solid var(--app-border) !important;
        }

        .right_col h1,
        .right_col h2,
        .right_col h3,
        .right_col h4,
        .right_col h5,
        .right_col h6,
        .right_col label,
        .right_col .control-label,
        .top_nav .navbar-right li a,
        .top_nav .navbar-nav > li > a,
        .left_col a,
        .left_col h3,
        .left_col .profile_info h2,
        .left_col .profile_info p,
        .left_col .profile_info span {
          color: var(--app-text) !important;
        }

        .right_col a:not(.btn):not(.page-link):not([class*="text-"]) {
          color: var(--app-accent);
        }

        .right_col a:not(.btn):not(.page-link):not([class*="text-"]):hover,
        .right_col a:not(.btn):not(.page-link):not([class*="text-"]):focus-visible {
          color: #B6D6FF;
        }

        .left_col .site_title img,
        .splash-logo {
          filter: brightness(1.6) contrast(0.95);
        }

        .left_col .side-menu > li > a,
        .nav-sm .left_col .side-menu > li > a {
          border-left: 3px solid transparent;
          border-radius: 0 3px 3px 0;
          transition: background-color .15s ease, color .15s ease;
        }

        .left_col .side-menu > li > a:hover,
        .nav-sm .left_col .side-menu > li > a:hover,
        .left_col .child_menu li:hover,
        .nav-sm .left_col ul.nav.child_menu li:hover {
          background: var(--app-hover) !important;
          color: var(--app-accent) !important;
        }

        .left_col .side-menu > li.active > a,
        .left_col .side-menu > li.current-page > a,
        .nav-sm .left_col .side-menu > li.active-sm > a {
          background: var(--app-active) !important;
          border-left-color: var(--app-accent) !important;
          color: var(--app-accent) !important;
          font-weight: 600;
          box-shadow: none !important;
          text-shadow: none !important;
        }

        .left_col .side-menu > li > a i,
        .nav-sm .left_col .side-menu > li > a i,
        .left_col .child_menu li a,
        .nav-sm .left_col ul.nav.child_menu li a,
        .left_col .menu_section h3,
        .left_col .sidebar-footer a {
          color: var(--app-text-muted) !important;
        }

        .left_col .side-menu > li > a:hover i,
        .left_col .side-menu > li.active > a i,
        .nav-sm .left_col .side-menu > li.active-sm > a i,
        .left_col .child_menu li a:hover,
        .left_col .child_menu li.active a,
        .left_col .child_menu li.current-page a,
        .nav-sm .left_col ul.nav.child_menu li a:hover,
        .nav-sm .left_col ul.nav.child_menu li.active a {
          color: var(--app-accent) !important;
        }

        .left_col .child_menu li.active,
        .nav-sm .left_col ul.nav.child_menu li.active,
        .nav-sm .left_col ul.nav.child_menu {
          background: var(--app-surface) !important;
        }

        .left_col .profile_img,
        .left_col .navbar.nav_title,
        .left_col .sidebar-footer {
          border-color: var(--app-border) !important;
        }

        .left_col .sidebar-footer a:hover,
        .top_nav .dropdown-menu li a:hover {
          background: var(--app-hover) !important;
          color: var(--app-accent) !important;
        }

        .x_panel,
        .modal-content,
        .dropdown-menu,
        .popover,
        .well,
        .panel-default,
        .card,
        .list-group-item {
          background-color: var(--app-surface) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text);
          box-shadow: 0 2px 10px rgba(0, 0, 0, .16);
        }

        .right_col .x_panel {
          background: var(--app-surface) !important;
          border: 1px solid var(--app-border) !important;
          border-radius: 8px;
          color: var(--app-text) !important;
          box-shadow: 0 4px 14px rgba(0, 0, 0, .16);
          transition: background-color .18s ease, border-color .18s ease, box-shadow .18s ease;
        }

        .right_col .x_panel .x_title {
          border-bottom: 1px solid var(--app-border) !important;
        }

        .right_col .x_panel .x_title h2,
        .right_col .x_panel .x_title h2 small,
        .right_col .x_panel .x_content,
        .right_col .x_panel .x_content h4 {
          color: var(--app-text) !important;
        }

        .right_col .x_panel .x_title span,
        .right_col .x_panel .x_title .filter,
        .right_col .x_panel .x_title .collapse-link,
        .right_col .x_panel .x_title .close-link {
          color: var(--app-text-muted) !important;
        }

        .right_col .x_panel .x_title .collapse-link:hover,
        .right_col .x_panel .x_title .close-link:hover {
          color: var(--app-accent) !important;
        }

        .right_col .x_panel .x_content hr,
        .right_col .x_panel .x_content legend {
          border-color: var(--app-border) !important;
        }

        .right_col .tile-stats {
          background: var(--app-surface) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
          box-shadow: 0 3px 12px rgba(0, 0, 0, .14);
          transition: background-color .18s ease, border-color .18s ease, box-shadow .18s ease;
        }

        .right_col .tile-stats .count {
          color: var(--app-text) !important;
        }

        .right_col .tile-stats h3,
        .right_col .tile-stats p {
          color: var(--app-text-muted) !important;
        }

        .right_col .tile-stats .icon,
        .right_col .tile-stats .icon i {
          color: #7C8DA0 !important;
        }

        .right_col .tile-stats:hover {
          border-color: #3A4D61 !important;
          box-shadow: 0 6px 18px rgba(0, 0, 0, .2);
        }

        .right_col .tile-stats:hover .icon i {
          color: var(--app-accent) !important;
        }

        .right_col .tile-stats hr {
          border-color: var(--app-border) !important;
        }

        .right_col .tile-stats > .dash-box-footer {
          background: rgba(133, 186, 255, .08);
          color: var(--app-accent);
        }

        .right_col .tile-stats > .dash-box-footer:hover {
          background: rgba(133, 186, 255, .14);
          color: #B6D6FF;
        }

        .x_title,
        .modal-header,
        .modal-footer,
        .panel-default > .panel-heading,
        .card-header,
        .card-footer,
        .list-group-item {
          border-color: var(--app-border) !important;
        }

        .x_title h2,
        .modal-title,
        .panel-default > .panel-heading,
        .card-header {
          color: var(--app-text) !important;
        }

        .top_nav .dropdown-menu,
        .top_nav .dropdown-menu li a {
          background-color: var(--app-surface) !important;
          color: var(--app-text) !important;
        }

        .right_col .dropdown-menu > li > a,
        .right_col .dropdown-item {
          color: var(--app-text) !important;
        }

        .right_col .dropdown-menu > li > a:hover,
        .right_col .dropdown-item:hover,
        .right_col .dropdown-item:focus {
          background-color: var(--app-hover) !important;
          color: var(--app-accent) !important;
        }

        .top_nav #profile .dropdown-menu.dropdown-usermenu > .dropdown-item {
          transition: background-color .15s ease, color .15s ease;
        }

        .top_nav #profile .dropdown-menu.dropdown-usermenu > .dropdown-item:hover,
        .top_nav #profile .dropdown-menu.dropdown-usermenu > .dropdown-item:focus,
        .top_nav #profile .dropdown-menu.dropdown-usermenu > .dropdown-item:focus-visible {
          background-color: var(--app-hover) !important;
          color: var(--app-accent) !important;
        }

        .top_nav #profile .dropdown-menu.dropdown-usermenu > .dropdown-item:active,
        .top_nav #profile .dropdown-menu.dropdown-usermenu > .dropdown-item.active {
          background-color: var(--app-active) !important;
          color: #B6D6FF !important;
        }

        .top_nav #profile .dropdown-menu.dropdown-usermenu > .dropdown-item:focus-visible {
          outline: 2px solid var(--app-accent);
          outline-offset: -2px;
        }

        .table,
        .table > thead > tr > th,
        .table > tbody > tr > th,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > tbody > tr > td,
        .table > tfoot > tr > td {
          border-color: var(--app-border) !important;
          color: var(--app-text);
        }

        .table-striped > tbody > tr:nth-of-type(odd),
        .table-hover > tbody > tr:hover {
          background-color: var(--app-surface-raised) !important;
        }

        .right_col table thead > tr > th,
        .right_col table thead > tr > td,
        .right_col .dataTables_scrollHead table thead > tr > th,
        .right_col .dt-scroll-head table thead > tr > th,
        .right_col .dtfh-floatingparent table thead > tr > th {
          background-color: #23364B !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .right_col table.dataTable tbody > tr > th,
        .right_col table.dataTable tbody > tr > td,
        .right_col .dataTables_scrollBody table tbody > tr > th,
        .right_col .dt-scroll-body table tbody > tr > th,
        .right_col .dt-scroll-body table tbody > tr > td {
          background-color: var(--app-surface) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .right_col table.dataTable tbody > tr:nth-of-type(odd) > th,
        .right_col table.dataTable tbody > tr:nth-of-type(odd) > td {
          background-color: var(--app-surface-raised) !important;
        }

        .right_col table.dataTable tbody > tr:hover > th,
        .right_col table.dataTable tbody > tr:hover > td {
          background-color: var(--app-hover) !important;
          color: var(--app-text) !important;
        }

        .right_col table.dataTable tbody > tr.selected > th,
        .right_col table.dataTable tbody > tr.selected > td {
          background-color: var(--app-active) !important;
          color: var(--app-text) !important;
        }

        table.jambo_table {
          border-color: var(--app-border) !important;
        }

        table.jambo_table thead {
          background: #23364B !important;
          color: var(--app-text) !important;
        }

        table.jambo_table tbody tr:hover td {
          background-color: var(--app-hover) !important;
          border-color: var(--app-border) !important;
        }

        table.jambo_table tbody tr.selected,
        table.jambo_table tbody tr.selected td {
          background-color: var(--app-active) !important;
          border-color: #3C5C7E !important;
        }

        .right_col table tbody > tr:hover > th,
        .right_col table tbody > tr:hover > td {
          background-color: var(--app-hover) !important;
          color: var(--app-text) !important;
        }

        .right_col table tbody > tr:active > th,
        .right_col table tbody > tr:active > td,
        .right_col table tbody > tr.active > th,
        .right_col table tbody > tr.active > td,
        .right_col table tbody > tr.selected > th,
        .right_col table tbody > tr.selected > td,
        .right_col table.dataTable tbody > tr:active > th,
        .right_col table.dataTable tbody > tr:active > td,
        .right_col table.dataTable tbody > tr.active > th,
        .right_col table.dataTable tbody > tr.active > td,
        .right_col .dataTables_scrollBody table tbody > tr:active > th,
        .right_col .dataTables_scrollBody table tbody > tr:active > td,
        .right_col .dataTables_scrollBody table tbody > tr.active > th,
        .right_col .dataTables_scrollBody table tbody > tr.active > td {
          background-color: var(--app-active) !important;
          border-color: #3C5C7E !important;
          color: var(--app-text) !important;
        }

        .form-control,
        .right_col select,
        .right_col textarea,
        .right_col input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="reset"]):not([type="hidden"]):not([type="file"]):not([type="range"]) {
          background-color: #131E29 !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .form-control::placeholder,
        .right_col input::placeholder,
        .right_col textarea::placeholder {
          color: var(--app-text-muted) !important;
          opacity: .8;
        }

        .form-control:focus,
        .right_col select:focus,
        .right_col textarea:focus,
        .right_col input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="reset"]):not([type="hidden"]):not([type="file"]):not([type="range"]):focus {
          border-color: var(--app-accent) !important;
          box-shadow: 0 0 0 2px rgba(133, 186, 255, .18) !important;
        }

        .form-control:disabled,
        .form-control[readonly],
        .right_col select:disabled,
        .right_col textarea:disabled,
        .right_col input:disabled,
        .right_col input[readonly],
        .right_col textarea[readonly] {
          background-color: var(--app-surface-raised) !important;
          color: var(--app-text-muted) !important;
        }

        .right_col input[type="checkbox"],
        .right_col input[type="radio"] {
          accent-color: #85BAFF;
        }

        .right_col input[type="file"]::file-selector-button {
          margin-right: 10px;
          padding: 6px 10px;
          border: 1px solid var(--app-border);
          border-radius: 4px;
          background: var(--app-surface-raised);
          color: var(--app-text);
          cursor: pointer;
        }

        .right_col input[type="file"]::file-selector-button:hover {
          background: var(--app-hover);
        }

        .input-group-addon,
        .input-group-btn .btn,
        .bootstrap-select > .dropdown-toggle {
          background-color: var(--app-surface-raised) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .bootstrap-select .dropdown-menu > li > a {
          color: var(--app-text) !important;
        }

        .bootstrap-select .dropdown-menu > li > a:hover,
        .bootstrap-select .dropdown-menu > .selected > a {
          background-color: var(--app-hover) !important;
          color: var(--app-accent) !important;
        }

        .btn-default,
        .btn-secondary,
        .pagination > li > a,
        .pagination > li > span,
        .breadcrumb {
          background-color: var(--app-surface-raised) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .btn-primary {
          background-color: #2766A8 !important;
          border-color: #397BBE !important;
          color: #FFFFFF !important;
        }

        .btn-success {
          background-color: #246B50 !important;
          border-color: #328365 !important;
          color: #FFFFFF !important;
        }

        .btn-info {
          background-color: #245F75 !important;
          border-color: #317B93 !important;
          color: #FFFFFF !important;
        }

        .btn-warning {
          background-color: #79551D !important;
          border-color: #98702C !important;
          color: #FFFFFF !important;
        }

        .btn-danger {
          background-color: #873C42 !important;
          border-color: #A44D54 !important;
          color: #FFFFFF !important;
        }

        .btn-light,
        .btn-dark {
          background-color: var(--app-surface-raised) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .btn-outline-primary,
        .btn-outline-info {
          background-color: transparent !important;
          border-color: #85BAFF !important;
          color: #85BAFF !important;
        }

        .btn-outline-success {
          background-color: transparent !important;
          border-color: #72C9A0 !important;
          color: #72C9A0 !important;
        }

        .btn-outline-warning {
          background-color: transparent !important;
          border-color: #E7BF68 !important;
          color: #E7BF68 !important;
        }

        .btn-outline-danger {
          background-color: transparent !important;
          border-color: #EE9298 !important;
          color: #EE9298 !important;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary.active,
        .btn-outline-primary:active,
        .btn-outline-info:hover,
        .btn-outline-info:focus,
        .btn-outline-info.active,
        .btn-outline-info:active {
          background-color: #2766A8 !important;
          color: #FFFFFF !important;
        }

        .btn-outline-success:hover,
        .btn-outline-success:focus,
        .btn-outline-success.active,
        .btn-outline-success:active {
          background-color: #246B50 !important;
          color: #FFFFFF !important;
        }

        .btn-outline-warning:hover,
        .btn-outline-warning:focus,
        .btn-outline-warning.active,
        .btn-outline-warning:active {
          background-color: #79551D !important;
          color: #FFFFFF !important;
        }

        .btn-outline-danger:hover,
        .btn-outline-danger:focus,
        .btn-outline-danger.active,
        .btn-outline-danger:active {
          background-color: #873C42 !important;
          color: #FFFFFF !important;
        }

        .btn-link {
          color: var(--app-accent) !important;
        }

        .btn-default:hover,
        .btn-secondary:hover,
        .btn-default:focus,
        .btn-secondary:focus,
        .btn-default:active,
        .btn-secondary:active,
        .btn-default.active,
        .btn-secondary.active {
          background-color: var(--app-hover) !important;
          border-color: var(--app-accent) !important;
          color: var(--app-text) !important;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-success:hover,
        .btn-success:focus,
        .btn-info:hover,
        .btn-info:focus,
        .btn-warning:hover,
        .btn-warning:focus,
        .btn-danger:hover,
        .btn-danger:focus,
        .btn-primary:active,
        .btn-success:active,
        .btn-info:active,
        .btn-warning:active,
        .btn-danger:active,
        .btn-primary.active,
        .btn-success.active,
        .btn-info.active,
        .btn-warning.active,
        .btn-danger.active {
          filter: brightness(1.12);
        }

        .right_col .btn:active:not(:disabled):not(.disabled) {
          box-shadow: inset 0 2px 4px rgba(0, 0, 0, .2) !important;
          transform: translateY(1px);
        }

        .btn:disabled,
        .btn.disabled,
        fieldset:disabled .btn {
          opacity: .55;
          cursor: not-allowed;
        }

        .pagination > li > a:hover,
        .pagination > li > span:hover,
        .pagination > .active > a,
        .pagination > .active > span {
          background-color: var(--app-active) !important;
          border-color: var(--app-accent) !important;
          color: var(--app-accent) !important;
        }

        .nav-tabs {
          border-bottom-color: var(--app-border) !important;
        }

        .nav-tabs > li > a {
          border-color: transparent !important;
          color: var(--app-text-muted) !important;
          transition: background-color .15s ease, color .15s ease, border-color .15s ease;
        }

        .nav-tabs .nav-link,
        .nav-pills .nav-link {
          color: var(--app-text-muted) !important;
          transition: background-color .15s ease, color .15s ease, border-color .15s ease;
        }

        .nav-tabs > li > a:hover,
        .nav-tabs > li > a:focus {
          background-color: var(--app-hover) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .nav-tabs .nav-link:hover,
        .nav-tabs .nav-link:focus,
        .nav-pills .nav-link:hover,
        .nav-pills .nav-link:focus {
          background-color: var(--app-hover) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .nav-tabs > li.active > a,
        .nav-tabs > li.active > a:hover,
        .nav-tabs > li.active > a:focus {
          background-color: var(--app-surface) !important;
          border-color: var(--app-border) var(--app-border) var(--app-surface) !important;
          color: var(--app-accent) !important;
        }

        .nav-tabs .nav-link.active,
        .nav-tabs .nav-item.show .nav-link {
          background-color: var(--app-surface) !important;
          border-color: var(--app-border) var(--app-border) var(--app-surface) !important;
          color: var(--app-accent) !important;
        }

        .nav-pills > li > a {
          color: var(--app-text-muted) !important;
          transition: background-color .15s ease, color .15s ease;
        }

        .nav-pills > li > a:hover,
        .nav-pills > li > a:focus {
          background-color: var(--app-hover) !important;
          color: var(--app-text) !important;
        }

        .nav-pills > li.active > a,
        .nav-pills > li.active > a:hover,
        .nav-pills > li.active > a:focus {
          background-color: var(--app-active) !important;
          color: var(--app-accent) !important;
        }

        .nav-pills .nav-link.active,
        .nav-pills .show > .nav-link {
          background-color: var(--app-active) !important;
          color: var(--app-accent) !important;
        }

        .tab-content,
        .tab-pane {
          color: var(--app-text);
        }

        .tab-content > .tab-pane {
          background-color: transparent;
        }

        .nav > li.disabled > a,
        .nav > li.disabled > a:hover,
        .nav > li.disabled > a:focus {
          color: #778697 !important;
          cursor: not-allowed;
        }

        .nav-link.disabled {
          color: #778697 !important;
        }

        .panel-primary > .panel-heading,
        .panel-info > .panel-heading,
        .panel-success > .panel-heading,
        .panel-warning > .panel-heading,
        .panel-danger > .panel-heading {
          border-color: var(--app-border) !important;
        }

        .panel-primary > .panel-heading {
          background-color: #193746 !important;
          color: #C2E7F5 !important;
        }

        .panel-info > .panel-heading {
          background-color: #193746 !important;
          color: #C2E7F5 !important;
        }

        .panel-success > .panel-heading {
          background-color: #193A30 !important;
          color: #C0E9D6 !important;
        }

        .panel-warning > .panel-heading {
          background-color: #3B301C !important;
          color: #F1D89E !important;
        }

        .panel-danger > .panel-heading {
          background-color: #3D2429 !important;
          color: #F4C2C5 !important;
        }

        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-search--dropdown .select2-search__field,
        .select2-dropdown,
        .select2-container--default .select2-results__option {
          background-color: var(--app-surface) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered,
        .select2-container--default .select2-selection--single .select2-selection__placeholder,
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display,
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
          color: var(--app-text) !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
          background-color: var(--app-surface-raised) !important;
          border-color: var(--app-border) !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option[aria-selected="true"] {
          background-color: var(--app-active) !important;
          color: var(--app-accent) !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--multiple {
          border-color: var(--app-accent) !important;
          box-shadow: 0 0 0 2px rgba(133, 186, 255, .18);
        }

        .datepicker,
        .datepicker table tr td,
        .datepicker table tr th,
        .daterangepicker,
        .daterangepicker .calendar-table,
        .daterangepicker td,
        .daterangepicker th {
          background-color: var(--app-surface) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .datepicker table tr td.day:hover,
        .datepicker table tr td span:hover,
        .daterangepicker td.available:hover,
        .daterangepicker th.available:hover {
          background-color: var(--app-hover) !important;
          color: var(--app-accent) !important;
        }

        .datepicker table tr td.active,
        .datepicker table tr td.active:hover,
        .daterangepicker td.active,
        .daterangepicker td.active:hover {
          background-color: #2766A8 !important;
          color: #FFFFFF !important;
        }

        .progress {
          background-color: var(--app-surface-raised) !important;
        }

        .alert-info {
          background-color: #193746 !important;
          border-color: #28566A !important;
          color: #C2E7F5 !important;
        }

        .alert-primary {
          background-color: #193746 !important;
          border-color: #28566A !important;
          color: #C2E7F5 !important;
        }

        .alert-success {
          background-color: #193A30 !important;
          border-color: #2C614E !important;
          color: #C0E9D6 !important;
        }

        .alert-warning {
          background-color: #3B301C !important;
          border-color: #695329 !important;
          color: #F1D89E !important;
        }

        .alert-danger {
          background-color: #3D2429 !important;
          border-color: #6D343B !important;
          color: #F4C2C5 !important;
        }

        .alert-secondary,
        .alert-light {
          background-color: var(--app-surface-raised) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .alert-dark {
          background-color: #101820 !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .alert-link {
          color: inherit !important;
          font-weight: 700;
          text-decoration: underline;
          text-underline-offset: 2px;
        }

        .alert-link:hover,
        .alert-link:focus {
          color: var(--app-accent) !important;
        }

        .divider,
        .divider-dashed,
        .right_col .border-right,
        .right_col .border-left,
        .right_col .border-top,
        .right_col .border-bottom {
          border-color: var(--app-border) !important;
        }

        .divider-dashed {
          background-color: var(--app-bg) !important;
        }

        .right_col .accordion .panel,
        .right_col .accordion-item,
        .right_col .accordion-card {
          background-color: var(--app-surface) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .right_col .accordion .panel-heading,
        .right_col .accordion-button {
          background-color: var(--app-surface-raised) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .right_col .accordion .panel-heading:hover,
        .right_col .accordion-button:not(.collapsed) {
          background-color: var(--app-hover) !important;
          color: var(--app-accent) !important;
        }

        .right_col .accordion .panel-body,
        .right_col .accordion-body {
          background-color: var(--app-surface) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .right_col .accordion-button::after {
          filter: invert(.82) sepia(.08) saturate(.35);
        }

        .right_col .dropzone {
          background-color: var(--app-surface) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text-muted) !important;
        }

        .right_col .dropzone:hover,
        .right_col .dropzone.dz-drag-hover {
          background-color: var(--app-hover) !important;
          border-color: var(--app-accent) !important;
        }

        .right_col .dropzone .dz-message,
        .right_col .dropzone .dz-preview,
        .right_col .dropzone .dz-preview .dz-details {
          color: var(--app-text) !important;
        }

        .right_col .dropzone .dz-preview .dz-image {
          background: var(--app-surface-raised) !important;
        }

        .right_col ul.timeline > li {
          border-bottom-color: var(--app-border) !important;
        }

        .right_col .timeline .block {
          border-left-color: var(--app-border) !important;
        }

        .right_col .timeline h2.title,
        .right_col .timeline h2.title a,
        .right_col .timeline .excerpt {
          color: var(--app-text) !important;
        }

        .right_col .timeline h2.title:before {
          background-color: var(--app-surface) !important;
          border-color: #52657A !important;
        }

        .right_col .timeline .byline,
        .right_col .timeline .byline a {
          color: var(--app-text-muted) !important;
        }

        .tooltip-inner,
        .popover-title {
          background-color: var(--app-surface-raised) !important;
          color: var(--app-text) !important;
        }

        .tooltip.top .tooltip-arrow,
        .tooltip.top-left .tooltip-arrow,
        .tooltip.top-right .tooltip-arrow {
          border-top-color: var(--app-surface-raised) !important;
        }

        .tooltip.right .tooltip-arrow {
          border-right-color: var(--app-surface-raised) !important;
        }

        .tooltip.bottom .tooltip-arrow,
        .tooltip.bottom-left .tooltip-arrow,
        .tooltip.bottom-right .tooltip-arrow {
          border-bottom-color: var(--app-surface-raised) !important;
        }

        .tooltip.left .tooltip-arrow {
          border-left-color: var(--app-surface-raised) !important;
        }

        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_paginate,
        .dataTables_wrapper .dataTables_processing,
        .dt-container .dt-info,
        .dt-container .dt-length,
        .dt-container .dt-search,
        .dt-container .dt-paging,
        .dt-container .dt-processing {
          color: var(--app-text-muted) !important;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select,
        .dt-container .dt-search input,
        .dt-container .dt-length select {
          background-color: #131E29 !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .dataTables_wrapper .paginate_button,
        .dataTables_paginate a,
        .dt-container .dt-paging-button,
        button.DTTT_button,
        div.DTTT_button,
        a.DTTT_button {
          background: var(--app-surface-raised) !important;
          border-color: var(--app-border) !important;
          box-shadow: none !important;
          color: var(--app-text) !important;
        }

        .dataTables_wrapper .paginate_button:hover,
        .dataTables_wrapper .paginate_button.current,
        .dataTables_paginate a:hover,
        .dt-container .dt-paging-button:hover,
        .dt-container .dt-paging-button.current {
          background: var(--app-active) !important;
          border-color: var(--app-accent) !important;
          color: var(--app-accent) !important;
        }

        .label-default,
        .badge.badge-dark {
          background-color: var(--app-surface-raised);
          color: var(--app-text) !important;
        }

        .label-primary,
        .badge-primary,
        .label-info,
        .badge-info {
          background-color: #245F75 !important;
          color: #E0F3FA !important;
        }

        .label-success,
        .badge-success {
          background-color: #246B50 !important;
          color: #D9F3E6 !important;
        }

        .label-warning,
        .badge-warning {
          background-color: #79551D !important;
          color: #FFF0C9 !important;
        }

        .label-danger,
        .badge-danger {
          background-color: #873C42 !important;
          color: #FFE2E3 !important;
        }

        .label-light,
        .badge-light {
          background-color: var(--app-surface-raised) !important;
          color: var(--app-text) !important;
        }

        .bg-white,
        .bg-light {
          background-color: var(--app-surface) !important;
        }

        .text-dark {
          color: var(--app-text) !important;
        }

        .bg-dark {
          background-color: var(--app-surface-raised) !important;
        }

        pre,
        code,
        kbd {
          background-color: var(--app-surface-raised);
          border-color: var(--app-border);
          color: var(--app-text);
        }

        .close {
          color: var(--app-text-muted) !important;
          opacity: .85;
          text-shadow: none;
        }

        .close:hover,
        .close:focus {
          color: var(--app-text) !important;
          opacity: 1;
        }

        .uic .uic-content {
          background-color: var(--app-surface) !important;
          color: var(--app-text);
        }

        .uic .uic-title {
          color: var(--app-text) !important;
        }

        .uic .uic-text {
          color: var(--app-text-muted) !important;
        }

        .uic .uic-no {
          background-color: var(--app-surface-raised) !important;
          border-color: var(--app-border) !important;
          color: var(--app-text) !important;
        }

        .uic .uic-no:hover,
        .uic .uic-no:focus {
          background-color: var(--app-hover) !important;
          border-color: var(--app-accent) !important;
          color: var(--app-accent) !important;
        }

        .uic .uic-ico,
        .uic--primary .uic-ico {
          background-color: var(--app-active) !important;
          color: var(--app-accent) !important;
          box-shadow: 0 0 0 8px rgba(133, 186, 255, .08);
        }

        .uic--danger .uic-ico {
          background-color: #3D2429 !important;
          color: #F4C2C5 !important;
        }

        .uic--success .uic-ico {
          background-color: #193A30 !important;
          color: #C0E9D6 !important;
        }

        .uic--warning .uic-ico {
          background-color: #3B301C !important;
          color: #F1D89E !important;
        }

        .uic--info .uic-ico {
          background-color: #193746 !important;
          color: #C2E7F5 !important;
        }

        .app-update-modal__icon {
          background-color: var(--app-active) !important;
          color: var(--app-accent) !important;
        }

        .app-update-modal .modal-content {
          background-color: var(--app-surface) !important;
          color: var(--app-text);
        }

        .app-update-modal__title,
        .app-update-modal__commit-message {
          color: var(--app-text) !important;
        }

        .app-update-modal__description,
        .app-update-modal__commit-label,
        .app-update-modal__note {
          color: var(--app-text-muted) !important;
        }

        .app-update-modal__commit {
          background-color: var(--app-surface-raised) !important;
          border-color: var(--app-border) !important;
        }

        .app-update-modal__error {
          color: #F4C2C5 !important;
        }

        .text-muted,
        .help-block,
        .breadcrumb > li + li:before,
        .right_col footer,
        .container.body footer,
        .right_col footer .pull-right {
          color: var(--app-text-muted) !important;
        }

        .right_col hr,
        .right_col legend {
          border-color: var(--app-border) !important;
        }

        :focus-visible {
          outline: 2px solid var(--app-accent);
          outline-offset: 2px;
        }
      }
      @media (prefers-color-scheme: dark) and (prefers-reduced-motion: reduce) {
        .left_col .side-menu > li > a,
        .right_col .x_panel,
        .right_col .tile-stats,
        .nav-tabs > li > a,
        .nav-tabs .nav-link,
        .nav-pills .nav-link,
        .menu-quick-search__result {
          transition: none !important;
        }
      }
      .left_col .side-menu > li.active,
      .left_col .side-menu > li.current-page,
      .nav-sm .left_col .side-menu > li.active-sm,
      .left_col .child_menu > li.active,
      .left_col .child_menu > li.current-page,
      .nav-sm .left_col ul.nav.child_menu > li.active,
      .nav-sm .left_col ul.nav.child_menu > li.current-page,
      .left_col .side-menu > li.active > a,
      .left_col .side-menu > li.current-page > a,
      .nav-sm .left_col .side-menu > li.active-sm > a,
      .left_col .child_menu > li.active > a,
      .left_col .child_menu > li.current-page > a,
      .nav-sm .left_col ul.nav.child_menu > li.active > a,
      .nav-sm .left_col ul.nav.child_menu > li.current-page > a {
        border-right: 0 !important;
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