<div class="page-title">
    <div class="title_left">
        <h3>User Profile</h3>
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Informasi User</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
            <?= form_open(base_url('app/account/update_profile_basic'), ['id' => 'f_profile'], ['uid' => $this->session->userdata('user_id')]); ?>
                    <div class="item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="customFile">&nbsp;</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <img src="<?= base_url('template/assets/picture_akun/'.$this->session->userdata('pic')) ?>" alt="<?= $this->session->userdata('user_name'); ?>" width="100" class="img-circle">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="customFile">Ganti Photo
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="file" name="file" class="form-control" id="customFile">
                            <span class="help-block">*) Apabila gambar tidak diganti, abaikan inputan ini</span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" name="nama" id="input-nama" class="form-control" value="<?= $this->session->userdata('nama') ?>">
                        </div>
                    </div>
                    <!-- <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="jobdesk">Role
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                        <input type="text" id="input-nama" class="form-control" value="<?= strtoupper($detail->role) ?>" disabled>
                        </div>
                    </div> -->
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="jobdesk">Job Deskripsi
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea name="jobdesk" id="jobdesk" class="form-control" cols="30" rows="5" disabled><?= $detail->jobdesk ?></textarea>
                        </div>
                    </div>
                    <div class="divider-dashed"></div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <button type="submit" role="button" class="btn btn-success">Perbaharui</button>
                        </div>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Informasi Akun</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?= form_open(base_url('app/account/update_profile_pwd'), ['id' => 'f_pwd', 'autocomplete' => 'off'], ['token' => $this->session->csrf_token, 'uid' => $this->session->userdata('user_id')]); ?>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="input-username">Username
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="input-username" class="form-control" placeholder="Username" value="<?= $this->session->userdata('user_name') ?>" disabled>
                            <span class="help-block">*) Username tidak dapat diganti, kecuali laporkan ke admin</span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="input-old-pwd">Old Password <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="password" autocomplete="off" name="old_pwd" id="input-old-pwd" class="form-control" placeholder="Masukan Password Lama">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="input-new-pwd">New Password <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                        <input type="password" autocomplete="off" name="new_pwd" id="input-new-pwd" class="form-control" placeholder="Masukan Password Baru">
                        </div>
                    </div>
                    <div class="divider-dashed"></div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <button type="submit" role="button" class="btn btn-success">Reset Password</button>
                        </div>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
      var $form_profile = $("#f_profile"),
          $form_pwd = $("#f_pwd");

      $form_profile.submit(function(e){
          e.preventDefault();
          var $this = $(this); 
          var $url = $this.attr('action');
           $.ajax({
             url: $url,
             type:"post",
             data:new FormData(this),
             dataType: 'json',
             processData:false,
             contentType:false,
             cache:false,
             async:false,
             success: function(res) {
                $.blockUI({ 
                    fadeIn: 200, 
                    timeout: 3000, 
                    message: res.pesan,
                    onUnblock: function() { 
                        if(res.valid === true) {
                            window.location.href = `${_uri}/login/removeSession?continue=${encodeURI(res.redirectTo)}`
                        }
                    }
                });
             }
          });
      });

      $form_pwd.submit(function(e) {
        e.preventDefault();
        var $this = $(this),
        $url = $this.attr('action'),
        $data = $this.serialize();
        $.post($url,$data, response, 'json');
      })

      function response(res) {
        $.blockUI({ 
            fadeIn: 200, 
            timeout: 2000, 
            message: res.pesan,
            onUnblock: function() { 
                if(res.valid === true) 
                {
                    window.location.href = `${_uri}/login/lockScreenAction?continue=${encodeURI(res.redirectTo)}`
                }
            }
        });
      }

    });
</script>