<div class="row">
    <div class="col-md-6 col-sm-12 ">
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
            <?= form_open(base_url('app/users/update_profile_aksi'), ['id' => 'f_profile', 'class' => 'form-horizontal form-label-left', 'data-parsley-validate' => ''], ['uid' => $uid]); ?>
                    <div class="row form-group">
                        <div class="col-md-3 col-sm-3">
                            <img src="<?= base_url('template/assets/picture_akun/'.$user->pic.'?time='.date('H:i:s')) ?>" alt="<?= $this->session->userdata('user_name'); ?>" width="100" class="img-circle">
                        </div>
                        <div class="col-md-8 col-sm-12">
                        <span class="help-block">*) File ukuran gambar maksimal 1 MB / 1000 KB</span>
                        <span class="help-block">*) File Format gambar JPG/JPEG/PNG</span>
                        <span class="help-block">*) Apabila gambar tidak diganti, abaikan inputan ini</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="customFile">Ganti Photo
                        </label>
                            <input type="file" name="file" class="form-control" id="customFile">
                    </div>
                    <div class="row form-group">
                        <label for="input-nip">NIP/NIK <span class="text-danger">*</span>
                        </label>
                        <input required data-parsley-trigger="focusout" data-parsley-type="number" type="text" name="nip" id="input-nip" class="form-control" value="<?= $user->nip ?>">
                    </div>
                    <div class="row form-group">
                        <label for="input-nama">Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input required data-parsley-trigger="focusout" type="text" name="nama" id="input-nama" class="form-control" value="<?= $user->nama ?>">
                    </div>
                    <div class="row form-group">
                        <label for="pilih-part">Badan / Bidang / Bagian <span class="text-danger">*</span></label>
                        <select class="form-control" name="part" id="pilih-part" required data-parsley-trigger="change">
                            <option value="">Pilih Part</option>
                            <?php  
                                foreach($parts->result() as $part):
                            ?>
                            <option value="<?= $part->id ?>" <?= ($part->id === $user->fid_part) ? 'selected' : ''; ?>><?= $part->nama ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row form-group">
                        <label for="pilih-role">Role <span class="text-danger">*</span></label>
                        <?php  
                            $is_role = $user->role;
                        ?>
                        <select class="form-control" name="role" id="pilih-role" required data-parsley-trigger="change">
                            <option value="">Pilih Level</option>
                            <option value="SUPER_ADMIN" <?= ($is_role == 'SUPER_ADMIN') ? 'selected' : ''; ?>>SUPER ADMIN</option>
                            <option value="SUPER_USER" <?= ($is_role == 'SUPER_USER') ? 'selected' : ''; ?>>SUPER USER</option>
                            <option value="ADMIN" <?= ($is_role == 'ADMIN') ? 'selected' : ''; ?>>ADMIN</option>
                            <option value="USER" <?= ($is_role == 'USER') ? 'selected' : ''; ?>>USER</option>
                            <option value="VERIFICATOR" <?= ($is_role == 'VERIFICATOR') ? 'selected' : ''; ?>>VERIFICATOR</option>
                        </select>
                    </div>
                    <div class="row form-group">
                        <label for="input-nohp">No. Handphone <span class="text-danger">*</span>
                        </label>
                        <input required data-parsley-trigger="focusout" data-parsley-pattern-message="Nomor handphone tidak valid !" pattern="^(\+62|62|0)8[1-9][0-9]{6,9}$" type="text" name="nohp" id="input-nohp" class="form-control" value="<?= $user->nohp ?>">
                    </div>
                    <div class="row form-group">
                        <label for="jobdesk">Job Deskripsi</label>
                            <textarea name="jobdesk" id="jobdesk" class="form-control" cols="30" rows="5"><?= $user->jobdesk ?></textarea>
                    </div>
                    <div class="divider-dashed"></div>
                    <div class="row form-group">
                            <button type="submit" role="button" class="btn btn-success rounded-0">Perbaharui</button>
                            <button type="button" role="button" onclick="window.history.back(-1)" class="btn btn-danger rounded-0">Batal</button>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
	$(function() {
		$form = $("#f_profile");
		$form.submit(function(e){
          e.preventDefault();
          var $this = $(this); 
          var $url = $this.attr('action');
          if($form.parsley().isValid()) {
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
                message: res.pesan, 
                timeout: 3000,
                onBlock: function(){ 
                    if(res.valid === true) {
                        window.location.href = `${res.redirectTo}`
                    }
                 }  
            });
               // console.log(res);
             }
          });
        }
      });
	})
</script>