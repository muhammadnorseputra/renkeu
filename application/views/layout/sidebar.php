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
            <span>Welcome,</span>
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
                <li><a href="<?= base_url('app/dashboard') ?>"><i class="fa fa-home"></i> Beranda</a></li>
                <?php if(privilages('priv_programs')): ?>
                    <li><a href="<?= base_url('app/programs') ?>"><i class="fa fa-database"></i> Program & Kegiatan</a></li>
                    <li><a href="<?= base_url('app/arsip') ?>"><i class="fa fa-archive"></i> Arsip</a></li>
                    <?php endif; ?>
                <li>
                    <a>
                        <i class="fa fa-money"></i> Anggaran <span class="fa fa-chevron-down"></span>
                    </a>
                    <ul class="nav child_menu">
                        <li><a href="#level1_1">Target</a><li>
                        <li><a href="#level1_1">Kinerja</a><li>
                        <li><a href="<?= base_url('app/spj') ?>">SPJ</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <?php if($this->session->userdata('role') === 'SUPER_ADMIN'): ?>
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