<?php

    if (!defined('ABSPATH')) {
        exit;
    }

class WMS_Duplicator {

    public function run() {

        if (is_admin()) {

            require_once WMS_DUPLICATOR_PATH . 'admin/class-wms-duplicator-admin.php';

            $admin = new WMS_Duplicator_Admin();

            $admin->run();
        }

        require_once WMS_DUPLICATOR_PATH . 'public/class-wms-duplicator-public.php';

        $public = new WMS_Duplicator_Public();

        $public->run();
    }
}