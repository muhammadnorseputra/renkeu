<div class="row">
  <div class="col-md-12">
    <?php if ($this->session->flashdata('pesan') <> '') : ?>
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
        $priv_verifikasi = !empty($cek_priv->priv_verifikasi) && $cek_priv->priv_verifikasi  == 'Y' ? 'checked' : '';
        $priv_approve = !empty($cek_priv->priv_approve) && $cek_priv->priv_approve  == 'Y' ? 'checked' : '';
        $priv_spj = !empty($cek_priv->priv_spj) && $cek_priv->priv_spj  == 'Y' ? 'checked' : '';
        $priv_riwayat_spj = !empty($cek_priv->priv_riwayat_spj) && $cek_priv->priv_riwayat_spj  == 'Y' ? 'checked' : '';
        $priv_bukujaga = !empty($cek_priv->priv_bukujaga) && $cek_priv->priv_bukujaga  == 'Y' ? 'checked' : '';
        $priv_anggarankinerja = !empty($cek_priv->priv_anggarankinerja) && $cek_priv->priv_anggarankinerja  == 'Y' ? 'checked' : '';
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
              <th>Priv Approve</th>
              <th>Priv Verifikasi</th>
              <th>Priv Riwayat SPJ</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <th scope="row">Pilih</th>
              <td>
                <input type="checkbox" name="priv_programs" value="Y" class="js-switch" <?= $priv_programs ?> />
              </td>
              <td>
                <input type="checkbox" name="priv_approve" value="Y" class="js-switch" <?= $priv_approve ?> />
              </td>
              <td>
                <input type="checkbox" name="priv_verifikasi" value="Y" class="js-switch" <?= $priv_verifikasi ?> />
              </td>
              <td>
                <input type="checkbox" name="priv_riwayat_spj" value="Y" class="js-switch" <?= $priv_riwayat_spj ?> />
              </td>
            </tr>
          </tbody>
          <thead class="text-center">
            <tr>
              <th>#</th>
              <th>Priv SPJ</th>
              <th>Priv Buku Jaga</th>
              <th>Priv Anggaran & Kinerja</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <th scope="row">Pilih</th>
              <td>
                <input type="checkbox" name="priv_spj" value="Y" class="js-switch" <?= $priv_spj ?> />
              </td>
              <td>
                <input type="checkbox" name="priv_bukujaga" value="Y" class="js-switch" <?= $priv_bukujaga ?> />
              </td>
              <td>
                <input type="checkbox" name="priv_anggarankinerja" value="Y" class="js-switch" <?= $priv_anggarankinerja ?> />
              </td>
            </tr>
          </tbody>
        </table>
        <div class="divider-dashed"></div>
        <button type="submit" role="button" class="btn btn-success rounded-0">Perbaharui</button>
        <button type="button" role="button" onclick="window.location.href='<?= base_url('app/users') ?>'" class="btn btn-danger rounded-0">Kembali</button>
        <?= form_close(); ?>
      </div>
    </div>
  </div>
</div>