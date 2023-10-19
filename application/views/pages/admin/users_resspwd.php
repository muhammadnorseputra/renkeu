<div class="row">
	<div class="col-12 col-md-6 col-lg-4 col-xl-6">
		<div class="card">
			<h4 class="card-header">Reset Password</h4>
			<div class="card-body">
				<?= form_open(base_url('app/users/resspwd_aksi'), ['id' => 'f_new_pwd','class' => 'form-horizontal', 'autocomplete' => 'off'], ['uid' => $uid]); ?>
					<div class="form-group">
						<label for="username">Username</label>
				    	<input type="text" name="user_name" class="form-control" id="username" value="<?= $user->username ?>" readonly>
					</div>
					<div class="form-group">
						<label for="password">New Password</label>
				    	<input name="newpwd" type="password" class="form-control" id="password">
					</div>
					<div class="form-group">
						<label for="re-password">Re-type Password</label>
				    	<input name="newpwd_confirm" type="password" class="form-control" id="re-password">
					</div>
					<div class="form-group">
						<label class="form-check-label">
							<span toggle="#password,#re-password" class="fa fa-fw fa-eye-slash toggle-password mr-2"></span>
							<small class="text_pw">Lihat Password</small>
						</label>
					</div>					
					<div class="p-4 mb-3 border border-warning rounded shadow-sm">
						<label for="re-user">Silahkan masukan username anda untuk mereset password user. Hal ini untuk memastikan bahwa anda mempuyai wewenang untuk merubah privacy akun pengguna</label>
					    <input name="username_confirm" type="text" id="re-user" class="form-control form-control-alternative" placeholder="Username Anda ...">
					</div>

					<button class="btn btn-primary rounded-0" type="submit" disabled>Simpan</button>
					<button type="button" class="btn btn-danger rounded-0" onclick="window.location.href='<?= base_url('app/users') ?>'">Batal</button>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
</div>
<script>
	$(function(){
		var $form = $("#f_new_pwd");
		$form.submit(function(event) {
			event.preventDefault();
			var $this = $(this);
			$.post($this.attr('action'), $this.serialize(), response, 'json');
		});

		function response(res) {
			alert(res.pesan);
            NProgress.start();
			if(res.valid === true) {
				window.location.href = res.redirectUrl;
                NProgress.done();
			}
			// console.log(res);
		}

		// Cek Confirm
		var $confirm_input = $form.find("input[name='username_confirm']");
		var $confirm_btn = $form.find("button[type='submit']");
		$confirm_input.on("keyup", function() {
			if($(this).val().length > 3) {
				$confirm_btn.prop('disabled', false);
				return false;
			}
			$confirm_btn.prop('disabled', true); 
		});
		// Toggle show pwd
		$(".toggle-password").click(function() {
		      $(this).toggleClass("fa-eye fa-eye-slash");
		      var input = $($(this).attr("toggle"));
		      var textPw = $("small.text_pw");
		      if (input.attr("type") == "password") {
		          input.attr("type", "text");
		          textPw.text('Tutup Password');
		      } else {
		          input.attr("type", "password");
		          textPw.text('Lihat Password');
		      }
		  });
	});
</script>