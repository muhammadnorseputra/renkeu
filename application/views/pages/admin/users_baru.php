<div class="row">
    <div class="col-md-12">
        <div id="galat"></div>
    </div>
</div>
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
            <?= form_open_multipart(base_url('app/users/insert'), ['id' => 'f_users', 'class' => 'form-horizontal']); ?>
                
                <label for="img_pic" class="form-control-label">Upload Photo <span class="text-danger">*</span></label>
                <div class="form-group d-flex align-items-center">
                    <div class="custom-file">
                        <input type="file" name="photo" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <span class="help-block">File Format gambar JPG/JPEG/PNG</span>
            
                <div class="form-group">
                    <label class="form-control-label" for="input-nama">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="input-nama" class="form-control">
                </div>
                
                <div class="form-group">
                    <label class="form-control-label" for="input-nip">NIP/NIK <span class="text-danger">*</span></label>
                    <input type="text" name="nip" id="input-nip" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="input-nohp">No. Handphone <span class="text-danger">*</span></label>
                    <input type="text" name="nohp" id="input-nohp" class="form-control">
                    <span class="help-block"> Masukan nomor hp hanya angka, contoh: 08215181****</span>
                </div>

                <div class="divider-dashed"></div>
                <div class="form-group my-4">
                    <label class="form-control-label" for="input-username">Username<span class="text-danger">*</span></label>
                    <input type="text" name="username" id="input-username" class="form-control">
                </div>
                <div class="form-group mb-4">
                    <label class="form-control-label" for="input-pwd">Password <span class="text-danger">*</span></label>
                    <input type="password" name="pwd" id="input-pwd" class="form-control">
                </div>
                <div class="divider-dashed"></div>

                <div class="row">
                    <div class="col-lg-6">
                        <label class="form-control-label" for="pilih-role">Pilih Role <span class="text-danger">*</span></label>
                        <select class="custom-select" name="role" id="pilih-role">
                            <option selected value="">Pilih Level</option>
                            <option value="SUPER_ADMIN">SUPER ADMIN</option>
                            <option value="SUPER_USER">SUPER USER</option>
                            <option value="ADMIN">ADMIN</option>
                            <option value="USER">USER</option>
                            <option value="VERIFICATOR">VERIFICATOR</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="row form-group">
                            <label for="jobdesk">Job Deskripsi</label>
                                <textarea name="jobdesk" id="jobdesk" class="form-control" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="divider-dashed"></div>
                <button type="submit" class="btn btn-info rounded-0">Simpan</button>
                <button type="button" class="btn btn-danger rounded-0" onclick="window.location.href='<?= base_url('app/users') ?>'">Batal</button>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
	$(function() {
		$form = $("#f_users");
		$container_galat = $("#galat");
		$form.submit(function(e){
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
               if(res.valid == true) {
               	alert(res.pesan);
                window.location.href = `${res.redirectTo}`
               	return false;
               }
               $container_galat.html(`<div class="alert alert-warning" role="alert">
								    <strong>Galat!</strong> ${res.pesan}
								</div>`);
             }
          });
      });
	})
</script>