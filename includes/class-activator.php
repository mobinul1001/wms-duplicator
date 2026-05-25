<?php

    if (!defined('ABSPATH')) {
        exit;
    }

class WMS_Duplicator_Activator {

    public static function activate() {

        flush_rewrite_rules();
    }
}