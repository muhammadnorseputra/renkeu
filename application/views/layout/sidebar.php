<div class="left_col scroll-view">
    <div class="navbar nav_title" style="border-bottom: 1px solid #666; padding-left: 13px;">
        <a href="<?= base_url('/') ?>" class="site_title">
        <?php if(getSetting('APPLogo') != ''): ?>
            <img src="<?= base_url('template/assets/logo.png') ?>" width="25" alt="Logo Application"> 
        <?php endif; ?>
        <?php if(getSetting('APPName') != ''): ?>
            <span><?= getSetting('APPName') ?> - App</span>
        <?php endif; ?>
        </a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
        <div class="profile_pic">
            <img src="<?= base_url('template/assets/picture_akun/'.$this->session->userdata('pic')) ?>" alt="<?= $this->session->userdata('user_name'); ?>" class="img-circle profile_img">
        </div>
        <div class="profile_info">
            <!-- <span>Welcome,</span> -->
            <h2><?= ucwords($this->session->userdata('nama')); ?></h2>
            <p>(<?= strtolower($this->session->userdata('user_name')); ?>)</p>
        </div>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
            <h3>NAVIGASI</h3>
            <ul class="nav side-menu">
                <li><a href="<?= base_url('app/dashboard') ?>" class="loadContent" title="Dashboard"><i class="fa fa-home"></i> Beranda</a></li>
                <?php if(privilages('priv_programs')): ?>
                    <li><a href="<?= base_url('app/programs') ?>" class="loadContent" title="Program & Kegiatan"><i class="fa fa-database"></i> Program & Kegiatan</a></li>
                <?php endif; ?>
                <?php if(privilages('priv_spj')): ?>
                    <li><a href="<?= base_url('app/spj') ?>" class="loadContent"  title="SPJ (Surat Pertanggung Jawaban)"><i class="fa fa-dollar"></i> SPJ</a></li>
                <?php endif; ?>
                <?php if(privilages('priv_bukujaga')): ?>
                    <li><a href="<?= base_url('app/bukujaga') ?>" class="loadContent"  title="Buku Jaga Kegiatan"><i class="fa fa-book"></i> Buku Jaga</a></li>
                <?php endif; ?>
                <?php if(privilages('priv_anggarankinerja')): ?>
                <li>
                    <a>
                        <i class="fa fa-money"></i> Anggaran & Kinerja <span class="fa fa-chevron-down"></span>
                    </a>
                    <ul class="nav child_menu">
                        <li><a href="<?= base_url('app/target') ?>" class="loadContent"  title="Target Indikator">Target</a></li>
                        <li><a href="<?= base_url('app/realisasi') ?>" class="loadContent" title="Realisasi Indikator">Realisasi</a></li>
                        <li><a href="<?= base_url('app/capaian') ?>" class="loadContent"  title="Capaian Indikator">Capaian</a></li>
                        <li><a href="<?= base_url('app/capaian/laporan') ?>" class="loadContent"  title="Target Laporan">Laporan Tahunan</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php if(privilages('priv_users') || privilages('priv_notify')): ?>
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
                <?php if(privilages('priv_users')): ?>
                <li><a href="<?= base_url('app/users') ?>"><i class="fa fa-users"></i> Users</a></li>
                <?php endif; ?>
                <?php if(privilages('priv_notify')): ?>
                <li><a href="<?= base_url('app/messages') ?>"><i class="fa fa-envelope"></i> Notify</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>

    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
        <a href="<?= base_url('/app/settings') ?>" data-toggle="tooltip" data-placement="top" title="Settings">
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
        </a>
        <a id="btnFullScreen" href="#" data-toggle="tooltip" data-placement="top" title="FullScreen">
            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
        </a>
        <a href="<?= base_url("/login/lockScreenAction?continue=".urlencode(curPageURL())) ?>" data-toggle="tooltip" data-placement="top" title="Lock">
            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?= base_url('login/removeSession') ?>">
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a>
    </div>
    <!-- /menu footer buttons -->
</div>