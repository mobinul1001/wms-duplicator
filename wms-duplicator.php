<?php
/*
Plugin Name: WMS Duplicator
Plugin URI: https://websitemaintenanceservices.org/
Description: Duplicate posts, pages, Elementor templates, and custom post types with one click.
Version: 1.0.0
Author: Md. Mobinul Hoque
Author URI: https://websitemaintenanceservices.org
License: GPL2
Text Domain: wms-duplicator
*/

if (!defined('ABSPATH')) {
    exit;
}

/*
----------------------------------------
DEFINE CONSTANTS
----------------------------------------
*/

define('WMS_DUPLICATOR_VERSION', '1.0.0');

define('WMS_DUPLICATOR_PATH', plugin_dir_path(__FILE__));

define('WMS_DUPLICATOR_URL', plugin_dir_url(__FILE__));

/*
----------------------------------------
ACTIVATION
----------------------------------------
*/

function activate_wms_duplicator() {

    require_once plugin_dir_path(__FILE__) . 'includes/class-activator.php';

    WMS_Duplicator_Activator::activate();
}

register_activation_hook(__FILE__, 'activate_wms_duplicator');

/*
----------------------------------------
DEACTIVATION
----------------------------------------
*/

function deactivate_wms_duplicator() {

    require_once plugin_dir_path(__FILE__) . 'includes/class-deactivator.php';

    WMS_Duplicator_Deactivator::deactivate();
}

register_deactivation_hook(__FILE__, 'deactivate_wms_duplicator');

/*
----------------------------------------
CORE CLASS
----------------------------------------
*/

require plugin_dir_path(__FILE__) . 'includes/class-wms-duplicator.php';

function run_wms_duplicator() {

    $plugin = new WMS_Duplicator();

    $plugin->run();
}

run_wms_duplicator();