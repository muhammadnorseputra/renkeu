<div class="row">
    <div class="col-md-12">
    <?php if($this->session->flashdata('pesan') <> '' ): ?>
        <div class="alert alert-<?= $this->session->flashdata('pesan_type') ?> alert-dismissible fade show" role="alert">
            <span class="alert-icon"><i class="ni ni-bell-55"></i></span>
            <span class="alert-text"><?= $this->session->flashdata('pesan') ?></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>
<div class="row">
<div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Set Privilages <small>(<?= ucwords($user->nama) ?>::<?= $user->username ?>)</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <?php 
					$cek_priv = $this->users->cek_privilages($uid); 
					$priv_default = !empty($cek_priv->priv_default) && $cek_priv->priv_default  == 'Y' ? 'checked' : '';
					$priv_users = !empty($cek_priv->priv_users) && $cek_priv->priv_users  == 'Y' ? 'checked' : '';
					$priv_settings = !empty($cek_priv->priv_settings) && $cek_priv->priv_settings  == 'Y' ? 'checked' : '';
					$priv_notify = !empty($cek_priv->priv_notify) && $cek_priv->priv_notify  == 'Y' ? 'checked' : '';
					$priv_programs = !empty($cek_priv->priv_programs) && $cek_priv->priv_programs  == 'Y' ? 'checked' : '';
				?>
                  <?= form_open(base_url('app/users/privilages_update'), ['id' => 'f_privilage'], ['f_type' => 'privilage', 'uid' => encrypt_url($uid)]); ?>
                    <table class="table table-bordered table-condensed">
                      <thead class="text-center">
                        <tr>
                          <th>#</th>
                          <th>Priv Default</th>
                          <th>Priv Users</th>
                          <th>Priv Settings</th>
                          <th>Priv Notify</th>
                        </tr>
                      </thead>
                      <tbody class="text-center">
                        <tr>
                        <th scope="row">Pilih</th>
                        <td>
                            <input type="checkbox" name="priv_default" value="Y" class="js-switch" <?= $priv_default ?> />
                        </td>
                        <td>
                            <input type="checkbox" name="priv_users" value="Y" class="js-switch" <?= $priv_users ?> />
                        </td>
                        <td>
                            <input type="checkbox" name="priv_settings" value="Y" class="js-switch" <?= $priv_settings ?> />
                        </td>
                        <td>
                            <input type="checkbox" name="priv_notify" value="Y" class="js-switch" <?= $priv_notify ?> />
                        </td>
                        </tr>
                      </tbody>
                      <thead class="text-center">
                        <tr>
                          <th>#</th>
                          <th>Priv Programs</th>
                        </tr>
                      </thead>
                      <tbody class="text-center">
                      <tr>
                          <th scope="row">Pilih</th>
                          <td>
                          <input type="checkbox" name="priv_programs" value="Y" class="js-switch" <?= $priv_programs ?> />
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="divider-dashed"></div>
                    <button type="submit" role="button" class="btn btn-success rounded-0">Perbaharui</button>
                    <button type="button" role="button" onclick="window.location.href='<?= base_url('app/users') ?>'" class="btn btn-danger rounded-0">Batal</button>
                <?= form_close(); ?>
                  </div>
                </div>
              </div>
</div>