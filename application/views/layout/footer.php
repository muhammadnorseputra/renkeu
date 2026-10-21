    <!-- Bootstrap -->
    <script src="<?php echo asset_url('template/backend/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- CountUp.js -->
    <script src="https://cdn.jsdelivr.net/npm/countup.js@2.10.1/dist/countUp.umd.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo asset_url('template/backend/vendors/fastclick/lib/fastclick.js') ?>"></script>
    <!-- NProgress -->
    <script src="<?php echo asset_url('template/backend/vendors/nprogress/nprogress.js') ?>"></script>
    <!-- Js Notify -->
    <script src="<?php echo asset_url('template/custom-js/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>
    <!-- Bootstrap Progressbar -->
    <script src="<?php echo asset_url('template/backend/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') ?>">
    </script>
    <!-- Custom Theme Scripts -->
    <script src="<?php echo asset_url('template/backend/build/js/custom.js') ?>"></script>
    <script src="<?php echo asset_url('template/custom-js/ui-confirm.js') ?>"></script>
    <script src="<?php echo asset_url('template/custom-js/chart-export.js') ?>"></script>
    <script src="<?php echo asset_url('template/custom-js/admin.js') ?>"></script>
    <script src="<?php echo asset_url('template/custom-js/app-update.js') ?>"></script>
    <!-- Search menu backdrop CSS -->
    <link href="<?php echo asset_url('template/custom-css/search-menu.css') ?>" rel="stylesheet">

    <?php
        if (isset($autoload_js)) {
            foreach ($autoload_js as $script):
                echo tagscript($script);
            endforeach;
        }
    ?>
    </body>

    </html>