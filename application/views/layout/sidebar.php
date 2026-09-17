<div class="left_col scroll-view" id="tour_navbar">
    <div class="navbar nav_title" style="border-bottom: 1px solid #666; padding-left: 13px;">
        <a href="<?php echo base_url('/') ?>" class="site_title">
            <?php if (getSetting('APPLogo') != ''): ?>
            <img src="<?php echo base_url('template/assets/Logo DSP Monokrom/Logo4.png') ?>" width="180"
                alt="Logo Application">
            <?php endif; ?>
        </a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
        <div class="profile_pic">
            <img src="<?php echo base_url('template/assets/picture_akun/' . $this->session->userdata('pic')) ?>"
                alt="<?php echo $this->session->userdata('user_name'); ?>" class="img-circle profile_img">
        </div>
        <div class="profile_info">
            <!-- <span>Welcome,</span> -->
            <h2><?php echo ucwords($this->session->userdata('nama')); ?></h2>
            <p>(<?php echo strtolower($this->session->userdata('user_name')); ?>)</p>
        </div>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">

            <h3>NAVIGASI</h3>
            <ul class="nav side-menu">
                <li><a href="<?php echo base_url('app/dashboard') ?>" title="Dashboard"><i
                            class="fa fa-home"></i> Dashboard</a></li>
                <?php if (privilages('priv_programs')): ?>
                <li><a href="<?php echo $this->session->userdata('is_valid_profile') ? base_url('app/programs') : '#' ?>"
                        title="Program & Kegiatan"
                        style="cursor: <?php echo $this->session->userdata('is_valid_profile') === "0" ? 'not-allowed' : 'allowed' ?>"><i
                            class="fa fa-database"></i>Rincian Anggaran </a></li>
                <?php endif; ?>
                <?php if (privilages('priv_spj')): ?>
                <li
                    style="cursor: <?php echo $this->session->userdata('is_valid_profile') === "0" ? 'not-allowed' : 'allowed' ?>">
                    <a>
                        <i class="fa fa-dollar"></i> SPJ <span class="fa fa-chevron-down"></span>
                    </a>
                    <ul class="nav child_menu">
                        <li><a href="<?php echo $this->session->userdata('is_valid_profile') ? base_url('app/spj') : '#' ?>"
                                title="SPJ (Surat Pertanggung Jawaban)"
                                style="cursor: <?php echo $this->session->userdata('is_valid_profile') === "0" ? 'not-allowed' : 'allowed' ?>">
                                Inbox</a></li>

                        <li><a href="<?php echo $this->session->userdata('is_valid_profile') ? base_url('app/spj/rekap_perjadin') : '#' ?>"
                                title="Rekapitulasi Perjalanan Dinas"
                                style="cursor: <?php echo $this->session->userdata('is_valid_profile') === "0" ? 'not-allowed' : 'allowed' ?>">
                                Rekapitulasi Perjadin</a></li>

                        <li><a href="<?php echo $this->session->userdata('is_valid_profile') ? base_url('app/spj/rekap_pajak') : '#' ?>"
                                title="Rekapitulasi Pajak"
                                style="cursor: <?php echo $this->session->userdata('is_valid_profile') === "0" ? 'not-allowed' : 'allowed' ?>">
                                Rekapitulasi Pajak</a></li>
                    </ul>
                </li>

                <li><a href="<?php echo $this->session->userdata('is_valid_profile') ? base_url('app/spj/monitor') : '#' ?>"
                        title="Monitoring Belanja"
                        style="cursor: <?php echo $this->session->userdata('is_valid_profile') === "0" ? 'not-allowed' : 'allowed' ?>"><i
                            class="fa fa-line-chart"></i> Monitoring Belanja</a></li>
                <?php endif; ?>
                <?php if (privilages('priv_bukujaga')): ?>
                <li
                    style="cursor: <?php echo $this->session->userdata('is_valid_profile') === "0" ? 'not-allowed' : 'allowed' ?>">
                    <a>
                        <i class="fa fa-archive"></i> Buku Jaga <span class="fa fa-chevron-down"></span>
                    </a>
                    <ul class="nav child_menu">
                        <li><a href="<?php echo $this->session->userdata('is_valid_profile') ? base_url('app/bukujaga') : '#' ?>"
                                title="Buku Jaga Belanja Kegiatan"
                                style="cursor: <?php echo $this->session->userdata('is_valid_profile') === "0" ? 'not-allowed' : 'allowed' ?>">Belanja
                                Kegiatan</a></li>

                        <li><a href="<?php echo $this->session->userdata('is_valid_profile') ? base_url('app/bukujaga/rincian') : '#' ?>"
                                title="Rincian Belanja"
                                style="cursor: <?php echo $this->session->userdata('is_valid_profile') === "0" ? 'not-allowed' : 'allowed' ?>">Rincian
                                Belanja</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if (privilages('priv_anggarankinerja') || privilages('priv_verify_kinerja')): ?>
                <li
                    style="cursor: <?php echo $this->session->userdata('is_valid_profile') === "0" ? 'not-allowed' : 'allowed' ?>">
                    <a>
                        <i class="fa fa-dashboard"></i> Kinerja <span class="fa fa-chevron-down"></span>
                    </a>
                    <?php if ($this->session->userdata('is_valid_profile') === "1"): ?>
                    <ul class="nav child_menu">
                        <?php if (isAuthorizedRole(['ADMIN', 'SUPER_ADMIN', 'USER', 'SUPER_USER'])): ?>

                        <li><a href="<?php echo base_url('app/verifikator-kinerja') ?>"
                                title="Verifikasi Hasil Kinerja">Verifikasi Hasil Kinerja</a></li>
                        <li><a href="<?php echo base_url('app/pegawai') ?>"
                                title="Mapping Pegawai">Mapping Pegawai</a></li>
                        <li><a href="<?php echo base_url('app/dokuments/pengelolaan_resiko') ?>"
                                title="Target">Dokumen Pengelolaan Resiko</a></li>
                        <li><a href="#" title="Target"
                                style="cursor: not-allowed; pointer-events: none; opacity: 0.3;">Dokumen Kinerja Non
                                PK <i class="fa fa-lock"></i></a></li>
                        <li><a href="#" title="Indikator"
                                style="cursor: not-allowed; pointer-events: none; opacity: 0.3;">Indikator <i
                                    class="fa fa-lock"></i></a></li>

                        <li><a href="#" title="Target"
                                style="cursor: not-allowed; pointer-events: none; opacity: 0.3;">Target <i
                                    class="fa fa-lock"></i></a>
                        </li>
                        <?php endif; ?>
                        <li><a href="#" title="Realisasi Indikator"
                                style="cursor: not-allowed; pointer-events: none; opacity: 0.3;">Realisasi <i
                                    class="fa fa-lock"></i></a></li>
                        <?php if (isAuthorizedRole(['ADMIN', 'SUPER_ADMIN', 'SUPER_USER'])): ?>
                        <li><a href="#" title="Capaian Indikator"
                                style="cursor: not-allowed; pointer-events: none; opacity: 0.3;">Capaian <i
                                    class="fa fa-lock"></i></a></li>
                        <li><a href="#" title="Target Laporan"
                                style="cursor: not-allowed; pointer-events: none; opacity: 0.3;">Laporan Tahunan <i
                                    class="fa fa-lock"></i></a>
                        </li>
                        <?php endif?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php if (privilages('priv_users') || privilages('priv_notify')): ?>
        <div class="menu_section">
            <h3>MASTER DATA</h3>
            <ul class="nav side-menu">
                <!-- <li>
                    <a>
                        <i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span>
                    </a>
                    <ul class="nav child_menu">
                        <li><a href="#level1_1">Level One</a><li>
                        <li>
                            <a>Level Two<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="level2.html">Level Two</a></li>
                                <li><a href="#level2_1">Level Two_1</a></li>
                                <li><a href="#level2_2">Level Two_2</a></li>
                            </ul>
                        </li>
                        <li><a href="#level1_2">Level Tree</a>
                        </li>
                    </ul>
                </li> -->
                <?php if (privilages('priv_users')): ?>
                <li><a href="<?php echo base_url('app/users') ?>"><i class="fa fa-users"></i> Users</a></li>
                <?php endif; ?>
                <?php if (privilages('priv_notify')): ?>
                <!-- <li><a href="<?php echo base_url('app/whatsapp') ?>"><i class="fa fa-envelope"></i> Whatsapp Notify</a></li> -->
                <li><a href="<?php echo base_url('app/messages') ?>"><i class="fa fa-envelope"></i> Web Notify</a></li>
                <li><a href="<?php echo base_url('app/settings') ?>"><i class="fa fa-cogs"></i> Settings</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
        <a href="<?php echo base_url('/app/settings') ?>" data-toggle="tooltip" data-placement="top" title="Settings">
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
        </a>
        <a id="btnFullScreen" href="#" data-toggle="tooltip" data-placement="top" title="FullScreen">
            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
        </a>
        <a href="<?php echo base_url("/login/lockScreenAction?continue=" . urlencode(curPageURL())) ?>"
            data-toggle="tooltip" data-placement="top" title="Lock">
            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="Logout"
            href="<?php echo base_url('login/removeSession') ?>">
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a>
    </div>
    <!-- /menu footer buttons -->
</div>