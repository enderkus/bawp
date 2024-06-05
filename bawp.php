<?php
/**
 * Plugin Name: BAWP
 * Description: A WordPress plugin to provide API endpoints for retrieving WordPress and plugin information.
 * Version: 1.0.0
 * Author: Ender KUS
 * Text Domain: bawp
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('BAWP_VERSION', '1.0.0');
define('BAWP_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Include the core plugin class files
require_once BAWP_PLUGIN_DIR . 'includes/class-bawp-api.php';
require_once BAWP_PLUGIN_DIR . 'includes/class-bawp-admin.php';

// Initialize the plugin
function run_bawp() {
    $bawp_api = new BAWP_API();
    $bawp_admin = new BAWP_Admin();
}
run_bawp();
