<?php
/**
 * Main plugin class.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Trade Sphare Ads plugin class.
 */
class TSA_Plugin {

	/**
	 * Run the plugin.
	 *
	 * @return void
	 */
	public function run() {

		/*
 * Load database classes.
 */
require_once TSA_PATH . 'includes/database/class-database.php';
require_once TSA_PATH . 'includes/database/class-zones-table.php';
require_once TSA_PATH . 'includes/database/class-ads-table.php';

/*
 * Load admin classes.
 */
if ( is_admin() ) {

        require_once TSA_PATH . 'includes/admin/class-zones.php';
        require_once TSA_PATH . 'includes/admin/class-ads.php';
        require_once TSA_PATH . 'includes/admin/class-admin.php';

        $admin = new TSA_Admin();
        $admin->init();
}
		/*
		 * Load translations.
		 */
		add_action(
			'plugins_loaded',
			array( $this, 'load_textdomain' )
		);

		/*
		 * Admin development notice.
		 */
		add_action(
			'admin_notices',
			array( $this, 'admin_notice' )
		);
	}

	/**
	 * Load plugin translations.
	 *
	 * @return void
	 */
	public function load_textdomain() {

		load_plugin_textdomain(
			'trade-sphare-ads',
			false,
			dirname( TSA_BASENAME ) . '/languages'
		);
	}

	/**
	 * Display development notice.
	 *
	 * @return void
	 */
	public function admin_notice() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		echo '<div class="notice notice-success is-dismissible">';
		echo '<p>';
		echo esc_html__(
			'Trade Sphare Ads is active and ready for development.',
			'trade-sphare-ads'
		);
		echo '</p>';
		echo '</div>';
	}
}