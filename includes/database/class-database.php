<?php
/**
 * Database manager for Trade Sphare Ads.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles database installation and upgrades.
 */
class TSA_Database {

        /**
         * Current database schema version.
         */
        const DB_VERSION = '1.3.0';

        /**
         * Database schema option name.
         */
        const DB_VERSION_OPTION = 'tsa_db_version';

        /**
         * Run database installation or upgrade.
         *
         * @return void
         */
        public static function install() {

                $current_version = get_option(
                        self::DB_VERSION_OPTION,
                        '0.0.0'
                );

                /*
                 * Fresh installation.
                 */
                if ( '0.0.0' === $current_version ) {

                        self::create_tables();

                        update_option(
                                self::DB_VERSION_OPTION,
                                self::DB_VERSION,
                                false
                        );

                        return;
                }

                /*
                 * Upgrade from 1.1.0 to 1.2.0.
                 */
                if (
                        version_compare(
                                $current_version,
                                '1.2.0',
                                '<'
                        )
                ) {
                        self::upgrade_to_1_2_0();

                        update_option(
                                self::DB_VERSION_OPTION,
                                '1.2.0',
                                false
                        );

                        $current_version = '1.2.0';
                }

                /*
                 * Upgrade to 1.3.0.
                 *
                 * Adds advertisement statistics storage.
                 */
                if (
                        version_compare(
                                $current_version,
                                '1.3.0',
                                '<'
                        )
                ) {
                        self::upgrade_to_1_3_0();

                        update_option(
                                self::DB_VERSION_OPTION,
                                '1.3.0',
                                false
                        );
                }

                /*
                 * Safety check.
                 *
                 * The database version may already be 1.3.0 while the
                 * statistics table is missing. This can happen if table
                 * creation failed during a previous installation or upgrade.
                 */
                if ( ! self::stats_table_exists() ) {
                        TSA_Stats_Table::create_table();
                }
        }

        /**
         * Check whether the statistics table exists.
         *
         * @return bool
         */
        private static function stats_table_exists() {

                global $wpdb;

                $table_name = TSA_Stats_Table::table_name();

                return $wpdb->get_var(
                        $wpdb->prepare(
                                'SHOW TABLES LIKE %s',
                                $table_name
                        )
                ) === $table_name;
        }

        /**
         * Create all plugin database tables.
         *
         * @return void
         */
        private static function create_tables() {

                TSA_Zones_Table::create_table();
                TSA_Ads_Table::create_table();
                TSA_Stats_Table::create_table();
        }

        /**
         * Upgrade database schema to 1.2.0.
         *
         * Adds automatic advertisement display support.
         *
         * @return void
         */
        private static function upgrade_to_1_2_0() {

                global $wpdb;

                $table_name = TSA_Zones_Table::table_name();

                $column_exists = $wpdb->get_var(
                        $wpdb->prepare(
                                "SHOW COLUMNS FROM {$table_name} LIKE %s",
                                'automatic_display'
                        )
                );

                if ( ! $column_exists ) {

                        $wpdb->query(
                                "ALTER TABLE {$table_name}
                                ADD COLUMN automatic_display tinyint(1) unsigned NOT NULL DEFAULT 1
                                AFTER status"
                        );

                        $wpdb->query(
                                "ALTER TABLE {$table_name}
                                ADD KEY automatic_display (automatic_display)"
                        );
                }
        }

        /**
         * Upgrade database schema to 1.3.0.
         *
         * Adds advertisement statistics storage.
         *
         * @return void
         */
        private static function upgrade_to_1_3_0() {

                TSA_Stats_Table::create_table();
        }
}