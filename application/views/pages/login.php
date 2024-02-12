<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

  <!-- Favicons -->
  <link href="<?= base_url('template/assets/logo.png') ?>" rel="icon">
  <link href="<?= base_url('template/assets/logo.png') ?>" rel="apple-touch-icon">
  
  <link rel="stylesheet" href="<?= base_url('template/login-form-02/fonts/icomoon/icons.css') ?>">

  <link rel="stylesheet" href="<?= base_url('template/login-form-02/css/owl.carousel.min.css') ?>">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?= base_url('template/login-form-02/css/bootstrap.min.css') ?>">

  <!-- Style -->
  <link rel="stylesheet" href="<?= base_url('template/login-form-02/css/style.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?= base_url('template/custom-js/jquery-form-validator/form-validator/theme-default.css') ?>">

  <title><?= $title ?></title>
  <style>
    .toggle-password {
    cursor: pointer;
}
  </style>
</head>

<body>


  <div class="d-lg-flex half">
    <div class="bg order-1 order-md-2" style="background-image: url('<?= base_url('template/assets/1-Tahulah-Pian-Tugu-Paringin_RVN.webp') ?>'); background-position:center; background-size: cover; background-repeat: no-repeat;"></div>
    <div class="contents order-2 order-md-1">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7">
            <!-- App Logo -->
            <?php if (getSetting('APPLogo') != '') : ?>
              <center><img style="margin:30px;" src="<?= base_url('template/assets/logo.png') ?>" width="50" alt="Logo Application"></center>
            <?php endif; ?>
            <?php if (getSetting('APPName') != '') : ?>
              <h3 class="text-center">Log In - <strong><?= getSetting('APPName') ?></strong></h3>
            <?php endif; ?>
            <p class="mb-4 text-center"><?= getSetting('APPDescription') ?></p>
            <?php
            $urlRef = isset($_GET['continue']) ? $_GET['continue'] : '';
            if (!$this->session->csrf_token) {
              $this->session->csrf_token = hash('sha1', time());
            }
            ?>
            <?= form_open(base_url('login/cek_akun'), ['autocomplete' => 'off', 'id' => 'f_login', 'class' => 'toggle-disabled'], ['token' => $this->session->csrf_token, 'continue' => $urlRef]); ?>
            <div class="form-group first">
              <label for="username">Username</label>
              <input type="text" name="username" placeholder="Masukan Username" class="form-control" data-sanitize="trim" data-validation="required" id="username">
            </div>
            <div class="form-group last mb-3">
              <label for="pwd">Password</label>
              <div class="input-group">
                <input type="password" class="form-control password-input" name="pwd" placeholder="Masukan Password" autocomplete="off" id="pwd" data-sanitize="trim" data-validation="required">
                <div class="input-group-append">
                  <span class="input-group-text shadow-sm bg-white border-0 ml-1 rounded" data-toggle="tooltip" title="Show/Hide Password"><i class="toggle-password icon-eye"></i></span>
                </div>
              </div>
            </div>

            <div class="d-flex mb-5 align-items-center">
              <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                <input type="checkbox" checked="checked" />
                <div class="control__indicator"></div>
              </label>
              <span class="ml-auto"><a href="https://wa.me/6282151815132/?text=Halo%20Admin%20Aplikasi%20<?= getSetting('APPName') ?>,%20saya%20mau%20reset%20password." target="_blank" class="forgot-pass">Forgot Password</a></span>
            </div>

            <button type="submit" role="button" class="btn btn-block btn-success">Masuk</button>

            <?= form_close(); ?>
          </div>
        </div>
      </div>
    </div>


  </div>

  <script src="<?= base_url('template/login-form-02/js/jquery-3.3.1.min.js') ?>"></script>
  <script src="<?= base_url('template/login-form-02/js/popper.min.js') ?>"></script>
  <script src="<?= base_url('template/login-form-02/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('template/login-form-02/js/main.js') ?>"></script>
  <!-- Custom-JS -->
  <script src="<?= base_url('template/custom-js/blockUI/jquery.blockUI.js') ?>"></script>
  <script src="<?= base_url('template/custom-js/jquery-form-validator/form-validator/jquery.form-validator.min.js') ?>"></script>
  <script src="<?= base_url('template/custom-js/route.js') ?>"></script>
  <script src="<?= base_url('template/custom-js/auth.js') ?>"></script>
</body>

</html>