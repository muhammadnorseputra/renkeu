    <!-- Bootstrap -->
   <script src="<?= base_url('template/backend/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- FastClick -->
    <script src="<?= base_url('template/backend/vendors/fastclick/lib/fastclick.js') ?>"></script>
    <!-- NProgress -->
    <script src="<?= base_url('template/backend/vendors/nprogress/nprogress.js') ?>"></script>
    <!-- jQuery custom content scroller -->
    <script src="<?= base_url('template/backend/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') ?>"></script>
    <!-- Js Notify -->
    <script src="<?= base_url('template/custom-js/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>

    <!-- Custom Theme Scripts -->
    <script src="<?= base_url('template/backend/build/js/custom.js') ?>"></script>
    <script src="<?= base_url('template/custom-js/admin.js') ?>"></script>

    <?php
          if(isset($autoload_js)) {
            foreach ($autoload_js as $script):
                  echo tagscript($script);
            endforeach;
          }
    ?>
  </body>
</html>