    <!-- Bootstrap -->
    <script src="<?php echo asset_url('template/backend/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- CountUp.js -->
    <script src="https://cdn.jsdelivr.net/npm/countup.js@2.10.1/dist/countUp.umd.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo asset_url('template/backend/vendors/fastclick/lib/fastclick.js') ?>"></script>
    <!-- NProgress -->
    <script src="<?php echo asset_url('template/backend/vendors/nprogress/nprogress.js') ?>"></script>
    <!-- jQuery custom content scroller -->
    <script
        src="<?php echo asset_url('template/backend/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') ?>">
    </script>
    <!-- Js Notify -->
    <script src="<?php echo asset_url('template/custom-js/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>
    <!-- Bootstrap Progressbar -->
    <script src="<?php echo asset_url('template/backend/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') ?>">
    </script>
    <!-- Custom Theme Scripts -->
    <script src="<?php echo asset_url('template/backend/build/js/custom.js') ?>"></script>
    <script src="<?php echo asset_url('template/custom-js/ui-confirm.js') ?>"></script>
    <script src="<?php echo asset_url('template/custom-js/admin.js') ?>"></script>

    <?php
        if (isset($autoload_js)) {
            foreach ($autoload_js as $script):
                echo tagscript($script);
            endforeach;
        }
    ?>
    </body>

    </html>