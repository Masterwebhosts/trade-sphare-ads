<?php
/**
 * Admin controller.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles the Trade Sphare Ads admin area.
 */
class TSA_Admin {

        /**
         * Register admin hooks.
         *
         * @return void
         */
        public function init() {

                add_action(
                        'admin_menu',
                        array( $this, 'register_menu' )
                );

                add_action(
                        'admin_enqueue_scripts',
                        array( $this, 'enqueue_assets' )
                );
        }

        /**
         * Register plugin admin menu.
         *
         * @return void
         */
        public function register_menu() {

                add_menu_page(
                        __( 'Trade Sphare Ads', 'trade-sphare-ads' ),
                        __( 'Trade Sphare Ads', 'trade-sphare-ads' ),
                        'manage_options',
                        'trade-sphare-ads',
                        array( $this, 'dashboard_page' ),
                        'dashicons-megaphone',
                        30
                );

                add_submenu_page(
                        'trade-sphare-ads',
                        __( 'Dashboard', 'trade-sphare-ads' ),
                        __( 'Dashboard', 'trade-sphare-ads' ),
                        'manage_options',
                        'trade-sphare-ads',
                        array( $this, 'dashboard_page' )
                );

                add_submenu_page(
                        'trade-sphare-ads',
                        __( 'Ad Zones', 'trade-sphare-ads' ),
                        __( 'Ad Zones', 'trade-sphare-ads' ),
                        'manage_options',
                        'tsa-zones',
                        array( $this, 'zones_page' )
                );

                add_submenu_page(
                        'trade-sphare-ads',
                        __( 'Ads', 'trade-sphare-ads' ),
                        __( 'Ads', 'trade-sphare-ads' ),
                        'manage_options',
                        'tsa-ads',
                        array( $this, 'ads_page' )
                );
        }

        /**
         * Load admin CSS and JavaScript.
         *
         * @param string $hook Current admin page hook.
         * @return void
         */
        public function enqueue_assets( $hook ) {

                if (
                        false === strpos( $hook, 'trade-sphare' ) &&
                        false === strpos( $hook, 'tsa-zones' ) &&
                        false === strpos( $hook, 'tsa-ads' )
                ) {
                        return;
                }

                wp_enqueue_style(
                        'tsa-admin',
                        TSA_URL . 'assets/css/admin.css',
                        array(),
                        TSA_VERSION
                );

                wp_enqueue_script(
                        'tsa-admin',
                        TSA_URL . 'assets/js/admin.js',
                        array( 'jquery' ),
                        TSA_VERSION,
                        true
                );
        }

        /**
 * Dashboard page.
 *
 * @return void
 */
public function dashboard_page() {

        if ( ! current_user_can( 'manage_options' ) ) {
                wp_die(
                        esc_html__(
                                'You do not have permission to access this page.',
                                'trade-sphare-ads'
                        )
                );
        }

        /*
         * Dashboard statistics.
         */
        $zones = TSA_Zones_Table::get_all();

        $active_zones = 0;

        foreach ( $zones as $zone ) {
                if ( 'active' === $zone->status ) {
                        $active_zones++;
                }
        }

        /*
         * Load dashboard template.
         */
        include TSA_PATH . 'templates/admin/dashboard.php';
}
        /**
         * Ad zones page.
         *
         * @return void
         */
        public function zones_page() {

                if ( ! current_user_can( 'manage_options' ) ) {
                        wp_die(
                                esc_html__(
                                        'You do not have permission to access this page.',
                                        'trade-sphare-ads'
                                )
                        );
                }

                $action = isset( $_GET['action'] )
                        ? sanitize_key( wp_unslash( $_GET['action'] ) )
                        : '';

                $zones = TSA_Zones::handle_request();

                if (
                        'add' === $action &&
                        ! isset( $_POST['tsa_zone_action'] )
                ) {

                        $zone = null;

                        include TSA_PATH . 'templates/admin/zones/form.php';

                        return;
                }

                if (
                        'edit' === $action &&
                        ! isset( $_POST['tsa_zone_action'] )
                ) {

                        $zone_id = isset( $_GET['zone_id'] )
                                ? absint( $_GET['zone_id'] )
                                : 0;

                        $zone = TSA_Zones_Table::get( $zone_id );

                        if ( ! $zone ) {

                                echo '<div class="wrap">';
                                echo '<div class="notice notice-error">';
                                echo '<p>';

                                echo esc_html__(
                                        'Ad zone not found.',
                                        'trade-sphare-ads'
                                );

                                echo '</p>';
                                echo '</div>';
                                echo '</div>';

                                return;
                        }

                        include TSA_PATH . 'templates/admin/zones/form.php';

                        return;
                }

                include TSA_PATH . 'templates/admin/zones/list.php';
        }

        /**
         * Advertisements page.
         *
         * @return void
         */
        public function ads_page() {

                if ( ! current_user_can( 'manage_options' ) ) {
                        wp_die(
                                esc_html__(
                                        'You do not have permission to access this page.',
                                        'trade-sphare-ads'
                                )
                        );
                }

                $action = isset( $_GET['action'] )
                        ? sanitize_key( wp_unslash( $_GET['action'] ) )
                        : '';

                $ads = TSA_Ads::handle_request();

                if (
                        'add' === $action &&
                        ! isset( $_POST['tsa_ad_action'] )
                ) {

                        $ad = null;

                        include TSA_PATH . 'templates/admin/ads/form.php';

                        return;
                }

                if (
                        'edit' === $action &&
                        ! isset( $_POST['tsa_ad_action'] )
                ) {

                        $ad_id = isset( $_GET['ad_id'] )
                                ? absint( $_GET['ad_id'] )
                                : 0;

                        $ad = TSA_Ads_Table::get( $ad_id );

                        if ( ! $ad ) {

                                echo '<div class="wrap">';
                                echo '<div class="notice notice-error">';
                                echo '<p>';

                                echo esc_html__(
                                        'Advertisement not found.',
                                        'trade-sphare-ads'
                                );

                                echo '</p>';
                                echo '</div>';
                                echo '</div>';

                                return;
                        }

                        include TSA_PATH . 'templates/admin/ads/form.php';

                        return;
                }

                include TSA_PATH . 'templates/admin/ads/list.php';
        }
}