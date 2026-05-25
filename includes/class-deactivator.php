<?php

    if (!defined('ABSPATH')) {
        exit;
    }

class WMS_Duplicator_Deactivator {

    public static function deactivate() {

        flush_rewrite_rules();
    }
}