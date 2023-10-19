<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?= base_url('template/assets/logo.png') ?>">

    <link rel="stylesheet" type="text/css" href="<?= base_url('template/login/fonts/icomoon/style.css') ?>">

    <link rel="stylesheet" type="text/css" href="<?= base_url('template/login/css/owl.carousel.min.css') ?>">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('template/login/css/bootstrap.min.css') ?>">
    
    <!-- Style -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('template/login/css/style.css') ?>">

    <title><?= $title ?></title>
  </head>
  <body>
  

  
  <div class="content">
    <div class="container">
      <div class="row">
        <!-- <div class="col-md-6">
          <img src="<?= base_url('template/login/images/undraw_remotely_2j6y.svg') ?>" alt="Image" class="img-fluid">
        </div> -->
        <div class="col-md-8 offset-md-2 contents">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="mb-4 text-center">
              <img style="margin:15px auto;" src="<?= base_url('template/assets/logo.png') ?>" width="50" alt="Logo Application"> 
              <h3>Log In - emonev</h3>
              <p class="mb-4 text-primary">Penyelesaian laporan anggaran kantor lebih mudah dan fleksibel terhadap penyesuaian realisasi anggaran saat ini.</p>
            </div>
            <?php  
							$urlRef = isset($_GET['continue']) ? $_GET['continue'] : ''; 
							if(!$this->session->csrf_token) {
								$this->session->csrf_token = hash('sha1', time());
							}
						?>
            <?= form_open(base_url('login/cek_akun'), ['autocomplete' => 'off', 'id' => 'f_login', 'class' => 'toggle-disabled'], ['token' => $this->session->csrf_token, 'continue' => $urlRef]); ?>
              <div class="form-group first">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" data-sanitize="trim" data-validation="required" id="username">

              </div>
              <div class="form-group last mb-4">
                <label for="pwd">Password</label>
                <input type="password" name="pwd" autocomplete="off" id="pwd" class="form-control" data-sanitize="trim" data-validation="required">
                
              </div>
              
              <div class="d-flex mb-5 align-items-center">
                <span class="ml-auto"><a href="https://wa.me/6282151815132/?text=Halo%20Admin%20Aplikasi%20e-monev%2C%20saya%20mau%20reset%20password." target="_blank" class="forgot-pass">Lupa Password ?</a></span> 
              </div>

              <button type="submit" role="button" class="btn btn-block btn-primary">Masuk</button>
              <?= form_close(); ?>
              <p class="text-center mt-4" style="font-size: small">
                &copy; <?= date('Y') ?> Badan Kepegawaian dan Pengembangan Sumber Daya Manusia <br/> App Version. 1.0
              </p>
            </div>
          </div>
          
        </div>
        
      </div>
    </div>
  </div>

  <!-- Library -->
  <script src="<?= base_url('template/login/js/jquery-3.3.1.min.js') ?>"></script>
  <script src="<?= base_url('template/login/js/popper.min.js') ?>"></script>
  <script src="<?= base_url('template/login/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('template/login/js/main.js') ?>"></script>
  <!-- Custom-JS -->
  <script src="<?= base_url('template/custom-js/blockUI/jquery.blockUI.js') ?>"></script>
	<script src="<?= base_url('template/custom-js/jquery-form-validator/form-validator/jquery.form-validator.min.js') ?>"></script>
  <script src="<?= base_url('template/custom-js/route.js') ?>"></script>
  <script src="<?= base_url('template/custom-js/auth.js') ?>"></script>
			
  </body>
</html>