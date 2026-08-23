<?php
/**
 * Statistics database table for Trade Sphare Ads.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles advertisement statistics storage.
 */
class TSA_Stats_Table {

	/**
	 * Get the full database table name.
	 *
	 * @return string
	 */
	public static function table_name() {
		global $wpdb;

		return $wpdb->prefix . 'tsa_stats';
	}

	/**
	 * Create the statistics table.
	 *
	 * Statistics are aggregated by advertisement, zone, and calendar day.
	 *
	 * @return void
	 */
	public static function create_table() {
		global $wpdb;

		$table_name      = self::table_name();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			stat_date date NOT NULL,
			ad_id bigint(20) unsigned NOT NULL,
			zone_id bigint(20) unsigned NOT NULL,
			impressions bigint(20) unsigned NOT NULL DEFAULT 0,
			clicks bigint(20) unsigned NOT NULL DEFAULT 0,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY ad_zone_date (ad_id,zone_id,stat_date),
			KEY stat_date (stat_date),
			KEY zone_id (zone_id),
			KEY ad_id (ad_id)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( $sql );
	}
}