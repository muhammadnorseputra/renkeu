<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Philosopher:wght@600;700&display=swap" rel="stylesheet">
  <link href="<?= base_url('template/assets/Logo DSP Warna/Logo3.png') ?>" rel="icon">
  <link href="<?= base_url('template/assets/Logo DSP Warna/Logo3.png') ?>" rel="apple-touch-icon">
  <link rel="stylesheet" href="<?= base_url('template/login-form-02/fonts/icomoon/icons.css') ?>">
  <link rel="stylesheet" href="<?= base_url('template/login-form-02/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url('template/custom-js/jquery-form-validator/form-validator/theme-default.css') ?>">
  <title><?= $title ?></title>
  <style>
    :root{--navy:#0a2a5e;--navy2:#0f3778;--amber:#F59E0B;--amber2:#d97706;--muted:#6b7280;--line:#e5e7eb;--bg:#f8f9fc}
    *{box-sizing:border-box}
    body{font-family:'Inter',system-ui,-apple-system,sans-serif;background:var(--bg);color:#111827;margin:0}
    a{color:var(--navy)}
    .login-wrap{min-height:100vh;display:flex}
    .login-brand{flex:1.05;background:linear-gradient(135deg,var(--navy) 0%,#0e2f6b 45%,#123d8a 100%);color:#fff;position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between;padding:32px 40px;isolation:isolate;max-width:60vw}
    .login-brand > *{position:relative;z-index:1}
    .login-brand > .orb,.login-brand > .brand-float{position:absolute;z-index:0}
    .login-brand > .brand-float{z-index:1}
    .login-brand::before{content:"";position:absolute;inset:-20%;background:radial-gradient(600px 400px at 20% 20%,rgba(245,158,11,.18),transparent 60%),radial-gradient(500px 500px at 90% 80%,rgba(255,255,255,.08),transparent 60%);animation:glowPan 16s ease-in-out infinite alternate}
    .login-brand::after{content:"";position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.055) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.055) 1px,transparent 1px);background-size:44px 44px;-webkit-mask-image:radial-gradient(75% 75% at 50% 40%,#000 25%,transparent 100%);mask-image:radial-gradient(75% 75% at 50% 40%,#000 25%,transparent 100%);animation:gridPan 26s linear infinite;pointer-events:none}
    @keyframes glowPan{to{transform:translate(-3%,2%) scale(1.06)}}
    @keyframes gridPan{to{background-position:44px 44px,44px 44px}}
    .orb{position:absolute;border-radius:50%;filter:blur(70px);opacity:.55;pointer-events:none;z-index:0}
    .orb-amber{width:340px;height:340px;background:radial-gradient(circle,rgba(245,158,11,.55),transparent 70%);top:-90px;right:-70px;animation:drift1 14s ease-in-out infinite alternate}
    .orb-blue{width:430px;height:430px;background:radial-gradient(circle,rgba(56,130,246,.5),transparent 70%);bottom:-150px;left:-130px;animation:drift2 18s ease-in-out infinite alternate}
    @keyframes drift1{to{transform:translate(-60px,50px) scale(1.15)}}
    @keyframes drift2{to{transform:translate(70px,-50px) scale(1.1)}}
    .brand-top,.brand-hero h1,.brand-hero p,.brand-points li,.brand-foot{opacity:0;animation:riseIn .7s cubic-bezier(.22,.8,.3,1) forwards}
    .brand-top{animation-delay:.05s}
    .brand-hero h1{animation-delay:.15s}
    .brand-hero p{animation-delay:.25s}
    .brand-points li:nth-child(1){animation-delay:.35s}
    .brand-points li:nth-child(2){animation-delay:.45s}
    .brand-points li:nth-child(3){animation-delay:.55s}
    .brand-foot{animation-delay:.65s}
    @keyframes riseIn{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:none}}
    .brand-hero h1 em{background:linear-gradient(100deg,var(--amber) 30%,#ffe3a6 50%,var(--amber) 70%);background-size:200% auto;-webkit-background-clip:text;background-clip:text;color:transparent;animation:shine 5s linear infinite}
    @keyframes shine{to{background-position:200% center}}
    .brand-points li{transition:transform .25s ease,border-color .25s ease,background .25s ease}
    .brand-points li:hover{transform:translateX(6px);border-color:rgba(245,158,11,.45);background:rgba(255,255,255,.12)}
    .brand-float{position:absolute;inset:0;pointer-events:none;z-index:1}
    .float-chip{position:absolute;display:flex;align-items:center;gap:10px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);border-radius:14px;padding:10px 14px;backdrop-filter:blur(10px);box-shadow:0 12px 32px rgba(0,0,0,.25);max-width:200px}
    .float-chip i{width:34px;height:34px;border-radius:10px;display:grid;place-items:center;background:rgba(245,158,11,.25);color:#ffd48a;font-size:16px}
    .float-chip strong{display:block;font-size:15px;line-height:1.1}
    .float-chip span{font-size:11px;color:rgba(255,255,255,.7)}
    .fc-1{top:14%;right:4%;animation:bob 5s ease-in-out infinite alternate}
    .fc-2{bottom:22%;right:10%;animation:bob 6.5s ease-in-out .8s infinite alternate}
    @keyframes bob{from{transform:translateY(-8px) rotate(-1deg)}to{transform:translateY(10px) rotate(1.5deg)}}
    .float-ring{position:absolute;top:44%;right:6%;width:110px;height:110px;border:1.5px dashed rgba(245,158,11,.5);border-radius:50%;animation:spin 24s linear infinite}
    @keyframes spin{to{transform:rotate(360deg)}}
    @media(min-width:992px){
      .brand-hero{padding-right:230px}
      .brand-points{max-width:420px}
      .fc-1{top:24%;right:28px}
      .fc-2{top:46%;right:56px;bottom:auto}
      .float-ring{top:36%;right:44px;width:90px;height:90px}
    }
    @media(prefers-reduced-motion:reduce){
      .login-brand::before,.login-brand::after,.orb,.float-chip,.float-ring,.brand-hero h1 em{animation:none!important}
      .brand-top,.brand-hero h1,.brand-hero p,.brand-points li,.brand-foot{opacity:1;animation:none}
    }
    .brand-top{display:flex;align-items:center;gap:12px}
    .brand-top img{height:42px;width:auto;filter:brightness(0) invert(1)}
    .brand-top span{font-family:'Philosopher',serif;font-weight:700;letter-spacing:.3px;font-size:18px}
    .brand-hero{padding:24px 0}
    .brand-hero h1{font-family:'Philosopher',serif;font-size:38px;line-height:1.1;margin:0 0 14px;font-weight:700}
    .brand-hero h1 em{color:var(--amber);font-style:normal}
    .brand-hero p{color:rgba(255,255,255,.82);font-size:15px;line-height:1.6;max-width:520px;margin:0}
    .brand-points{margin:28px 0 0;padding:0;list-style:none;display:grid;gap:12px;max-width:520px}
    .brand-points li{display:flex;gap:12px;align-items:flex-start;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:14px;padding:12px 14px;backdrop-filter:blur(6px)}
    .brand-points i{width:36px;height:36px;border-radius:10px;display:grid;place-items:center;background:rgba(245,158,11,.18);color:#ffd48a;flex:0 0 36px}
    .brand-points strong{font-size:13px;display:block}
    .brand-points span{font-size:12px;color:rgba(255,255,255,.75)}
    .brand-foot{font-size:12px;color:rgba(255,255,255,.6);display:flex;gap:16px;flex-wrap:wrap}
    .brand-foot a{color:rgba(255,255,255,.85);text-decoration:none;border-bottom:1px dashed rgba(255,255,255,.3)}
    .login-panel{flex:.95;display:flex;align-items:center;justify-content:center;padding:32px 24px;background:var(--bg)}
    .login-card{width:100%;max-width:440px;background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:0 12px 40px rgba(10,42,94,.08);padding:28px}
    .login-card-head{text-align:center;margin-bottom:18px}
    .login-card-head img{height:56px;width:auto;margin-bottom:10px}
    .login-card-head h2{font-size:22px;font-weight:700;color:var(--navy);margin:0}
    .login-card-head p{font-size:13px;color:var(--muted);margin:6px 0 0}
    .form-group{margin-bottom:14px}
    .form-group label{font-size:12px;font-weight:600;letter-spacing:.4px;text-transform:uppercase;color:#374151;margin-bottom:6px;display:block}
    .input-wrap{position:relative}
    .input-wrap > i.bi{position:absolute;left:14px;top:24px;transform:translateY(-50%);color:#9ca3af;font-size:18px;line-height:1;pointer-events:none;z-index:2}
    .form-control{height:48px;border-radius:12px;border:1px solid var(--line);background:#fff;padding-left:48px;font-size:14px;transition:.2s;box-shadow:none}
    .form-control:focus{border-color:var(--navy);box-shadow:0 0 0 4px rgba(10,42,94,.08)}
    select.form-control{padding-left:14px;padding-right:34px;appearance:none;background-image:linear-gradient(45deg,transparent 50%,#6b7280 50%),linear-gradient(135deg,#6b7280 50%,transparent 50%);background-position:calc(100% - 18px) calc(50% - 2px),calc(100% - 12px) calc(50% - 2px);background-size:6px 6px,6px 6px;background-repeat:no-repeat}
    .pwd-wrap .form-control{padding-right:52px}
    .pwd-toggle{position:absolute;right:10px;top:24px;transform:translateY(-50%);width:36px;height:36px;border-radius:10px;border:1px solid var(--line);background:#fff;display:grid;place-items:center;cursor:pointer;color:#6b7280;z-index:3}
    .pwd-toggle:hover{color:var(--navy);border-color:var(--navy)}
    .row-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .check-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:6px 0 16px}
    .check{font-size:13px;color:#374151;display:flex;align-items:center;gap:8px;cursor:pointer}
    .check input{accent-color:var(--navy)}
    .forgot{font-size:13px;color:var(--navy);text-decoration:none;font-weight:500}
    .forgot:hover{color:var(--amber2)}
    .btn-login{width:100%;height:46px;border-radius:12px;border:0;background:linear-gradient(135deg,var(--navy),var(--navy2));color:#fff;font-weight:700;letter-spacing:.3px;box-shadow:0 8px 20px rgba(10,42,94,.22);transition:.2s}
    .btn-login:hover{transform:translateY(-1px);box-shadow:0 12px 28px rgba(10,42,94,.28);color:#fff}
    .btn-login:disabled{opacity:.7;transform:none}
    .divider{height:1px;background:var(--line);margin:16px 0}
    .help{font-size:12px;color:var(--muted);text-align:center}
    .help a{color:var(--navy);font-weight:600;text-decoration:none}
    #message .alert{border-radius:12px;font-size:13px;padding:10px 14px;border:0;display:flex;align-items:center;gap:8px;margin-bottom:12px}
    #message .alert-danger{background:#fef2f2;color:#dc2626;border:1px solid #fecaca}
    #message .alert-success{background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0}
    /* Form validator inline errors */
    .form-control.error{border-color:#dc2626!important;box-shadow:0 0 0 4px rgba(220,38,38,.1)!important}
    .form-control.valid{border-color:#16a34a!important;box-shadow:0 0 0 4px rgba(22,163,74,.1)!important}
    .form-error{color:#dc2626;font-size:12px;margin:4px 0 10px;display:block;font-weight:500;position:relative;padding-left:20px}
    .form-error::before{content:"\f333 ";font-family:"bootstrap-icons" !important;font-size:14px;position:absolute;left:0;top:2px}
    select.form-control.error{border-color:#dc2626!important}
    @media(max-width:991px){
      .login-wrap{flex-direction:column}
      .login-brand{padding:24px 20px}
      .brand-hero h1{font-size:30px}
      .brand-points{display:none}
      .login-panel{padding:20px 16px}
      .row-2{grid-template-columns:1fr}
      .brand-float,.orb{display:none}
    }
  </style>
</head>
<body>
  <div class="login-wrap">
    <div class="login-brand">
      <div class="brand-float" aria-hidden="true">
        <div class="float-chip fc-1"><i class="bi bi-shield-check"></i><div><strong>Verifikasi</strong><span>berjenjang</span></div></div>
        <div class="float-chip fc-2"><i class="bi bi-graph-up-arrow"></i><div><strong>Monitoring</strong><span>real-time</span></div></div>
        <div class="float-ring"></div>
      </div>
      <div class="orb orb-amber" aria-hidden="true"></div>
      <div class="orb orb-blue" aria-hidden="true"></div>
      <div class="brand-top">
        <img src="<?= base_url('template/assets/Logo DSP Warna/Logo1.png') ?>" alt="Logo">
      </div>
      <div class="brand-hero">
        <h1>Kelola anggaran <em>lebih tertib</em> & transparan</h1>
        <p><?= getSetting('APPDescription') ?: 'Sistem monitoring anggaran, realisasi, dan verifikasi SAKIPRA terintegrasi untuk setiap bidang.' ?></p>
        <ul class="brand-points">
          <li><i class="bi bi-shield-check"></i><div><strong>Verifikasi berjenjang</strong><span>Alur entri → verifikasi → arsip terdokumentasi</span></div></li>
          <li><i class="bi bi-graph-up-arrow"></i><div><strong>Monitoring real-time</strong><span>Belanja harian, rekap SPJ & pajak dalam satu dashboard</span></div></li>
          <li><i class="bi bi-file-earmark-lock"></i><div><strong>Aman & teraudit</strong><span>Hak akses per role, jejak aktivitas tercatat</span></div></li>
        </ul>
      </div>
      <div class="brand-foot">
        <span>© <?= date('Y') ?> <?= getSetting('APPName') ?></span>
        <a href="<?= base_url('/') ?>">← Kembali ke Beranda</a>
        <a href="https://wa.me/6282151815132/?text=Halo%20Admin%20Aplikasi%20<?= getSetting('APPName') ?>,%20saya%20mau%20reset%20password." target="_blank">Butuh bantuan?</a>
      </div>
    </div>

    <div class="login-panel">
      <div class="login-card">
        <div class="login-card-head">
          <?php if (getSetting('APPLogo') != '') : ?>
            <img src="<?= base_url('template/assets/Logo DSP Warna/Logo5.png') ?>" alt="Logo">
          <?php endif; ?>
          <h2>Masuk ke Akun</h2>
          <p>Pilih tahun & status anggaran, lalu masuk dengan kredensial Anda</p>
        </div>

        <?php
        $urlRef = isset($_GET['continue']) ? $_GET['continue'] : '';
        if (!$this->session->csrf_token) {
          $this->session->csrf_token = hash('sha1', time());
        }
        ?>
        <div id="message"></div>
        <?= form_open(base_url('login/cek_akun'), ['autocomplete' => 'off', 'id' => 'f_login', 'class' => 'toggle-disabled'], ['token' => $this->session->csrf_token, 'continue' => $urlRef]); ?>

        <div class="row-2">
          <div class="form-group">
            <label for="tahun">Tahun Anggaran</label>
            <select name="tahun" id="tahun" class="form-control">
              <?php
              $year = date('Y');
              for ($i = $year - 1; $i <= $year + 1; $i++) {
                if (isset($detail->tahun)) {
                  $selected = $detail->tahun == $i ? 'selected' : '';
                } else {
                  $selected = $year == $i ? 'selected' : '';
                }
                echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="is_perubahan">Status Anggaran</label>
            <select name="is_perubahan" id="is_perubahan" class="form-control" required>
              <option value="" selected>-- Pilih --</option>
              <option value="0">Murni</option>
              <option value="1">Perubahan</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-wrap">
            <i class="bi bi-person"></i>
            <input type="text" name="username" placeholder="Masukan username" class="form-control" data-sanitize="trim" data-validation="required" id="username" autocomplete="username">
          </div>
        </div>

        <div class="form-group">
          <label for="pwd">Password</label>
          <div class="input-wrap pwd-wrap">
            <i class="bi bi-lock"></i>
            <input type="password" class="form-control password-input" name="pwd" placeholder="Masukan password" autocomplete="current-password" id="pwd" data-sanitize="trim" data-validation="required">
            <button type="button" class="pwd-toggle" aria-label="Show password"><i class="bi bi-eye"></i></button>
          </div>
        </div>

        <div class="check-row">
          <label class="check"><input type="checkbox" checked> Ingat saya</label>
          <a class="forgot" href="https://wa.me/6282151815132/?text=Halo%20Admin%20Aplikasi%20<?= getSetting('APPName') ?>,%20saya%20mau%20reset%20password." target="_blank">Lupa password?</a>
        </div>

        <button type="submit" class="btn-login">Masuk <i class="bi bi-arrow-right ms-1"></i></button>
        <?= form_close(); ?>
        <div class="divider"></div>
        <div class="help">Belum punya akses? Hubungi <a href="https://wa.me/6282151815132" target="_blank">Administrator</a></div>
      </div>
    </div>
  </div>

  <script src="<?= base_url('template/login-form-02/js/jquery-3.3.1.min.js') ?>"></script>
  <script src="<?= base_url('template/login-form-02/js/popper.min.js') ?>"></script>
  <script src="<?= base_url('template/login-form-02/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('template/login-form-02/js/main.js') ?>"></script>
  <script src="<?= base_url('template/custom-js/blockUI/jquery.blockUI.js') ?>"></script>
  <script src="<?= base_url('template/custom-js/jquery-form-validator/form-validator/jquery.form-validator.min.js') ?>"></script>
  <script src="<?= base_url('template/custom-js/route.js') ?>"></script>
  <script src="<?= base_url('template/custom-js/auth.js') ?>"></script>
  <script>
    // enhance toggle to work with new button markup
    $(document).on('click','.pwd-toggle',function(){
      var $inp=$('.password-input');
      var $icon=$(this).find('i');
      if($inp.attr('type')==='password'){ $inp.attr('type','text'); $icon.removeClass('bi-eye').addClass('bi-eye-slash'); }
      else { $inp.attr('type','password'); $icon.removeClass('bi-eye-slash').addClass('bi-eye'); }
    });
  </script>
</body>
</html>
