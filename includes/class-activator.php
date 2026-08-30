<?php
/**
 * Plugin activation handler.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles plugin activation.
 */
class TSA_Activator {

        /**
         * Run activation tasks.
         *
         * @return void
         */
        public static function activate() {

                /*
                 * Load database table classes.
                 */
                require_once TSA_PATH . 'includes/database/class-zones-table.php';
                require_once TSA_PATH . 'includes/database/class-ads-table.php';
                require_once TSA_PATH . 'includes/database/class-stats-table.php';

                /*
                 * Load database manager.
                 */
                require_once TSA_PATH . 'includes/database/class-database.php';

                /*
                 * Install or upgrade database tables.
                 */
                TSA_Database::install();

                /*
                 * Store plugin version.
                 */
                update_option(
                        'tsa_version',
                        TSA_VERSION,
                        false
                );
        }
}
