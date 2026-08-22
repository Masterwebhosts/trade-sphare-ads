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
	const DB_VERSION = '1.1.0';

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
		$current_version = get_option( self::DB_VERSION_OPTION, '0.0.0' );

		if ( version_compare( $current_version, self::DB_VERSION, '>=' ) ) {
			return;
		}

		self::create_tables();

		update_option(
			self::DB_VERSION_OPTION,
			self::DB_VERSION,
			false
		);
	}

	/**
	 * Create all plugin database tables.
	 *
	 * @return void
	 */
	private static function create_tables() {

        TSA_Zones_Table::create_table();
        TSA_Ads_Table::create_table();
}
}