<div class="row">
    <div class="col-md-12">
        <div id="galat"></div>
    </div>
</div>
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="title font-weight-bold">New User</h4>
            </div>
            <div class="card-body">
                <?= form_open_multipart(base_url('app/users/insert'), ['id' => 'f_users', 'class' => 'form-horizontal']); ?>
                <div class="row">
                    <div class="col-lg-6 align-self-center mb-3">
                        <label for="img_pic" class="form-control-label">Upload Photo <span class="text-danger">*</span></label>
                        <div class="form-group d-flex align-items-center">
                            <div class="custom-file">
                                <input type="file" name="photo" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <span class="help-block">*) File Format gambar JPG/JPEG/PNG</span>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                        <label class="form-control-label" for="input-nama">Nama Panggilan <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="input-nama" class="form-control">
                        </div>
                    </div>
                    </div>
                <div class="row bg-light py-4 mb-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                        <label class="form-control-label" for="input-username">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="input-username" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                        <label class="form-control-label" for="input-pwd">Password <span class="text-danger">*</span></label>
                        <input type="password" name="pwd" id="input-pwd" class="form-control">
                        </div>
                    </div>
                </div>
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