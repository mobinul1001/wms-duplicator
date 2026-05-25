<?php

    if (!defined('ABSPATH')) {
        exit;
    }

class WMS_Duplicator_Public {

    public function run() {

        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));

        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_styles() {

        wp_enqueue_style(
            'wms-duplicator-public',
            WMS_DUPLICATOR_URL . 'public/css/public.css',
            array(),
            WMS_DUPLICATOR_VERSION
        );
    }

    public function enqueue_scripts() {

        wp_enqueue_script(
            'wms-duplicator-public',
            WMS_DUPLICATOR_URL . 'public/js/public.js',
            array('jquery'),
            WMS_DUPLICATOR_VERSION,
            true
        );
    }
}