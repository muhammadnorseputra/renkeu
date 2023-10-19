<div class="container body">
      <?php $this->load->view('notify/notif_global'); ?>
      <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
          <?php include_once("sidebar.php") ?>
        </div>

        <!-- top navigation -->
        <div class="top_nav <?= getSetting('TopBarFix') ?>">
        <?php  
            $akun = $this->users->profile_id($this->session->userdata('user_id'))->row();
            // var_dump($is_block);
            if(($akun->is_restricted === 'Y')):
                $this->load->view('notify/notif_dibatasi');
            endif;
        ?>
            <div class="nav_menu">
                <div class="nav toggle">
                  <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
                <nav class="nav navbar-nav">
                <ul class="navbar-right">
                  <li class="nav-item dropdown open" style="padding-left: 15px;">
                    <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                      <img src="<?= base_url('template/assets/picture_akun/'.$this->session->userdata('pic')) ?>" alt="<?= $this->session->userdata('user_name'); ?>"><?= $this->session->userdata('nama'); ?>
                    </a>
                    <div class="dropdown-menu dropdown-usermenu pull-right" style="margin-top: 15px" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item"  href="<?= base_url('/app/account') ?>"> Profile</a>
                        <a class="dropdown-item"  href="javascript:;">
                          <span class="badge bg-red pull-right">50%</span>
                          <span>Settings</span>
                        </a>
                      <a class="dropdown-item"  href="javascript:;">Help</a>
                      <a class="dropdown-item"  href="<?= base_url("/login/lockScreenAction?continue=".urlencode(curPageURL())) ?>"><i class="fa fa-sign-out pull-right"></i> Lock Screen</a>
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
                      if($notify_count > 0):
                      ?>
                      <?php
                      foreach($notify_all as $notif):
                      $isi = limitText($notif->message)
                      ?>
                      <li class="nav-item">
                        <a class="dropdown-item" href="<?= base_url('app/inbox?mail='.$notif->id) ?>">
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
                </ul>
              </nav>
            </div>
          </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <?php 
            if($akun->is_block === 'Y'):
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
          &copy; <?= date('Y') ?> <?= getSetting('copyright') ?> - <?= getSetting('version_app') ?>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>