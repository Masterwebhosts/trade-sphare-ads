<?php
/**
 * Plugin Name: Trade Sphare Ads
 * Plugin URI: https://tradesphare.com/
 * Description: A professional advertising management system for WordPress. Create ad zones, manage advertisements, campaigns, advertisers, and performance statistics.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: Trade Sphare
 * Author URI: https://tradesphare.com/
 * Text Domain: trade-sphare-ads
 * Domain Path: /languages
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Plugin version.
 */
define( 'TSA_VERSION', '1.0.0' );

/**
 * Plugin file.
 */
define( 'TSA_FILE', __FILE__ );

/**
 * Plugin directory path.
 */
define( 'TSA_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'TSA_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin basename.
 */
define( 'TSA_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Load required core files.
 */
require_once TSA_PATH . 'includes/class-activator.php';
require_once TSA_PATH . 'includes/class-deactivator.php';
require_once TSA_PATH . 'includes/class-plugin.php';

/**
 * Run plugin activation tasks.
 *
 * @return void
 */
function tsa_activate() {
    TSA_Activator::activate();
}

register_activation_hook( TSA_FILE, 'tsa_activate' );

/**
 * Run plugin deactivation tasks.
 *
 * @return void
 */
function tsa_deactivate() {
    TSA_Deactivator::deactivate();
}

register_deactivation_hook( TSA_FILE, 'tsa_deactivate' );

/**
 * Initialize the plugin.
 *
 * @return void
 */
function tsa_run_plugin() {
    $plugin = new TSA_Plugin();
    $plugin->run();
}

tsa_run_plugin();