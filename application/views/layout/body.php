<?php
$menuSearchCatalog = [];
$menuSearchConfigPath = APPPATH . 'config/menu_search.json';

if (is_readable($menuSearchConfigPath)) {
  $menuSearchJson = file_get_contents($menuSearchConfigPath);
  $menuSearchConfig = $menuSearchJson !== false ? json_decode($menuSearchJson, true) : null;

  if (is_array($menuSearchConfig)) {
    $menuSearchPrivilegeCache = [];
    $menuSearchRole = $this->session->userdata('role');
    $menuSearchProfileValid = $this->session->userdata('is_valid_profile') === '1';

    foreach ($menuSearchConfig as $menuSearchItem) {
      if (
        !is_array($menuSearchItem) ||
        !isset($menuSearchItem['label']) || !is_string($menuSearchItem['label']) || $menuSearchItem['label'] === '' ||
        !isset($menuSearchItem['url']) || !is_string($menuSearchItem['url']) || $menuSearchItem['url'] === '' ||
        !isset($menuSearchItem['iconClass']) || !is_string($menuSearchItem['iconClass']) || $menuSearchItem['iconClass'] === '' ||
        !isset($menuSearchItem['anyPrivileges']) || !is_array($menuSearchItem['anyPrivileges']) ||
        !isset($menuSearchItem['roles']) || !is_array($menuSearchItem['roles']) ||
        !isset($menuSearchItem['requiresValidProfile']) || !is_bool($menuSearchItem['requiresValidProfile']) ||
        (isset($menuSearchItem['parentLabel']) && !is_string($menuSearchItem['parentLabel']))
      ) {
        log_message('error', 'Invalid menu search item in ' . $menuSearchConfigPath);
        continue;
      }

      $requiredPrivileges = $menuSearchItem['anyPrivileges'];
      $validRequirements = true;
      foreach (array_merge($requiredPrivileges, $menuSearchItem['roles']) as $requirement) {
        if (!is_string($requirement) || $requirement === '') {
          $validRequirements = false;
          break;
        }
      }
      if (!$validRequirements) {
        log_message('error', 'Invalid privilege or role requirement in ' . $menuSearchConfigPath);
        continue;
      }

      $hasPrivilege = empty($requiredPrivileges);

      foreach ($requiredPrivileges as $privilege) {
        if (!array_key_exists($privilege, $menuSearchPrivilegeCache)) {
          $menuSearchPrivilegeCache[$privilege] = privilages($privilege);
        }

        if ($menuSearchPrivilegeCache[$privilege]) {
          $hasPrivilege = true;
          break;
        }
      }

      $allowedRoles = $menuSearchItem['roles'];
      $hasRole = empty($allowedRoles) || in_array($menuSearchRole, $allowedRoles, true);
      $requiresValidProfile = !empty($menuSearchItem['requiresValidProfile']);

      if (!$hasPrivilege || !$hasRole || ($requiresValidProfile && !$menuSearchProfileValid)) {
        continue;
      }

      $menuSearchCatalog[] = [
        'label' => $menuSearchItem['label'],
        'parentLabel' => isset($menuSearchItem['parentLabel']) ? $menuSearchItem['parentLabel'] : '',
        'url' => base_url($menuSearchItem['url']),
        'iconClass' => $menuSearchItem['iconClass']
      ];
    }
  } else {
    log_message('error', 'Unable to decode menu search catalog: ' . $menuSearchConfigPath);
  }
} else {
  log_message('error', 'Menu search catalog is missing or unreadable: ' . $menuSearchConfigPath);
}

$menuSearchCatalogJson = json_encode(
  $menuSearchCatalog,
  JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
);
if ($menuSearchCatalogJson === false) {
  log_message('error', 'Unable to encode authorized menu search catalog.');
  $menuSearchCatalogJson = '[]';
}
?>
<div class="container body">

  <div class="header sticky-top">
    <?php $this->load->view('notify/notif_global'); ?>
  </div>

  <div id="app-update-modal" class="modal fade app-update-modal" tabindex="-1" role="dialog" aria-labelledby="app-update-title" aria-describedby="app-update-description" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="app-update-modal__accent"></div>
        <div class="modal-body text-center p-4 p-md-5">
          <div class="app-update-modal__icon" aria-hidden="true">
            <i class="fa fa-refresh"></i>
          </div>
          <h4 id="app-update-title" class="app-update-modal__title">Pembaruan aplikasi tersedia</h4>
          <p id="app-update-description" class="app-update-modal__description">
            Terdapat pembaruan baru tersedia. Silahkan update aplikasi untuk melanjutkan.
          </p>
          <div class="app-update-modal__commit">
            <div class="app-update-modal__commit-label">Commit terbaru</div>
            <div id="app-update-message" class="app-update-modal__commit-message"></div>
            <span id="app-update-sha" class="badge badge-dark app-update-modal__sha"></span>
          </div>
          <div id="app-update-error" class="app-update-modal__error" role="alert" aria-live="polite"></div>
          <button id="btn-app-update" type="button" class="btn btn-primary btn-lg btn-block app-update-modal__button">
            <i class="fa fa-refresh mr-2" aria-hidden="true"></i>Update Sekarang
          </button>
          <p class="app-update-modal__note mb-0">Modal ini akan tetap terbuka sampai aplikasi diperbarui.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="main_container">
    <div class="col-md-3 left_col <?= getSetting('SideBarFix') ?>">
      <?php include_once("sidebar.php") ?>
    </div>

    <!-- top navigation -->
    <div class="top_nav <?= getSetting('TopBarFix') ?>">
      <?php
      $akun = $this->users->profile_id($this->session->userdata('user_id'))->row();
      // var_dump($is_block);
      if (($akun->is_restricted === 'Y')):
        $this->load->view('notify/notif_dibatasi');
      endif;
      ?>
      <div class="nav_menu">
        <div class="nav toggle">
          <a id="menu_toggle" class="hamburger-icon" aria-label="Toggle sidebar"><i class="fa fa-bars" aria-hidden="true"></i></a>
        </div>

        <div class="menu-quick-search">
          <script>window.menuSearchCatalog = <?= $menuSearchCatalogJson ?>;</script>
          <label class="sr-only" for="menu-quick-search-input">Cari menu</label>
          <div class="menu-quick-search__field">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input id="menu-quick-search-input" type="search" placeholder="Cari menu / submenu..." autocomplete="off"
              aria-autocomplete="list" aria-controls="menu-quick-search-results" aria-expanded="false">
            <span class="menu-quick-search__shortcut" aria-hidden="true">
              <i class="fa fa-keyboard-o"></i>
              <kbd>Ctrl</kbd><kbd>K</kbd>
            </span>
          </div>
          <div id="menu-quick-search-results" class="menu-quick-search__results" role="listbox" hidden></div>
        </div>

        <nav class="nav navbar-nav">
          <ul class="navbar-right">

            <li class="nav-item dropdown open" style="padding-left: 15px;" id="profile">
              <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                <!-- <img src="<?= base_url('template/assets/picture_akun/' . $this->session->userdata('pic')) ?>" alt="<?= $this->session->userdata('user_name'); ?>"><?= $this->session->userdata('nama'); ?> -->
                <?= $this->session->userdata('nama'); ?>
              </a>
              <div class="dropdown-menu dropdown-usermenu pull-right" style="margin-top: 15px" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="<?= base_url('/app/account') ?>"><i class="fa fa-user pull-right"></i> Profile</a>
                <?php if ($this->session->userdata('role') === 'SUPER_ADMIN'): ?>
                  <a class="dropdown-item" href="<?= base_url('/app/settings') ?>">
                    <!-- <span class="badge bg-red pull-right">50%</span> -->
                    <span>Settings</span>
                  </a>
                <?php endif; ?>
                <a class="dropdown-item" href="<?= base_url("/app/changelog") ?>"><i class="fa fa-help pull-right"></i> Changelog</a>
                <a class="dropdown-item" href="<?= base_url("/login/lockScreenAction?continue=" . urlencode(curPageURL())) ?>"><i class="fa fa-lock pull-right"></i> Lockscreen</a>
                <a class="dropdown-item" href="<?= base_url("/login/removeSession") ?>"><i class="fa fa-sign-out pull-right"></i> Logout</a>
              </div>
            </li>
            <li role="presentation" class="nav-item dropdown open" style="padding-top: 5px">
              <?php
              $notify_private = $this->notify->getNotify('t_notify', ['mode' => 'PRIVATE', 'is_aktif' => 'Y', 'to' => decrypt_url($this->session->userdata('user_id'))]);
              $notify_private_all = $this->notify->getNotify('t_notify', ['mode' => 'PRIVATE_ALL', 'is_aktif' => 'Y']);
              $notify_all = array_merge($notify_private->result(), $notify_private_all->result());
              sort($notify_all);
              $notify_count = count($notify_all);
              ?>
              <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-envelope-o"></i>
                <span class="badge bg-green" style="margin-top: 5px"><?= isset($notify_count) ? $notify_count : '0';  ?></span>
              </a>
              <ul class="dropdown-menu list-unstyled msg_list" role="menu" style="margin-top: 15px; overflow-y:auto; max-height:800px" aria-labelledby="navbarDropdown1">
                <?php
                if ($notify_count > 0):
                ?>
                  <?php
                  foreach ($notify_all as $notif):
                    $isi = limitText($notif->message)
                  ?>
                    <li class="nav-item">
                      <a class="dropdown-item" href="<?= base_url('app/inbox?mail=' . $notif->id) ?>">
                        <span class="image"><img src="<?= base_url('template/assets/notify_message.png') ?>" style="width: 50px" alt="Notify" /></span>
                        <span>
                          <span class="text-info"><?= TanggalIndo($notif->created_at) ?></span>
                          <span class="badge badge-warning"><?= jamServer($notif->created_at) ?></span>
                        </span>
                        <span class="message">
                          <?= $isi ?>
                        </span>
                      </a>
                    </li>
                  <?php endforeach; ?>
                  <li class="nav-item">
                    <div class="text-center">
                      <a class="dropdown-item" href="<?= base_url('app/inbox') ?>">
                        <strong>Lihat Semua Pesan</strong>
                        <i class="fa fa-angle-right"></i>
                      </a>
                    </div>
                  </li>
                <?php else: ?>
                  <li class="nav-item">
                    <div class="text-center">
                      <a class="dropdown-item">
                        <strong>No Message</strong>
                      </a>
                    </div>
                  </li>
                <?php endif; ?>
              </ul>
            </li>

            <li style="margin-right: 15px; border-right: 1px solid #000; padding-right: 10px" id="is_perubahan">
              <h5 style="margin:0; padding: 0"><?= ($this->session->userdata('is_perubahan') === '1') ? '<span class="badge badge-info blink-fade">Perubahan</span>' : '<span class="badge badge-success blink-fade">Murni</span>'; ?></h5>
            </li>
            <li style=" padding-right: 10px" id="tahun_anggaran">
              <h5 style="margin:0; padding: 0">TA. <?= $this->session->userdata('tahun_anggaran'); ?></h5>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <!-- /top navigation -->

    <!-- page content -->
    <div class="right_col" role="main">
      <?php
      if ($akun->is_block === 'Y'):
        $this->load->view('notify/notif_block');
        return false;
      endif;
      ?>
      <?php include_once("content.php") ?>
    </div>
    <!-- /page content -->

    <!-- footer content -->
    <footer>
      <div class="pull-right">
        &copy; <?= date('Y') ?> <?= getSetting('copyright') ?> <?= getSetting('version_app') ?>
      </div>
      <div class="clearfix"></div>
    </footer>
    <!-- /footer content -->
  </div>
</div>