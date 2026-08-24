<?php
/**
 * Advertisement statistics administration.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles advertisement statistics in the admin area.
 */
class TSA_Statistics {

	/**
	 * Register statistics hooks.
	 *
	 * @return void
	 */
	public function init() {

		add_action(
			'admin_menu',
			array( $this, 'register_menu' )
		);
	}

	/**
	 * Register statistics menu.
	 *
	 * @return void
	 */
	public function register_menu() {

		add_submenu_page(
			'trade-sphare-ads',
			__( 'Statistics', 'trade-sphare-ads' ),
			__( 'Statistics', 'trade-sphare-ads' ),
			'manage_options',
			'tsa-statistics',
			array( $this, 'statistics_page' )
		);
	}

	/**
	 * Display statistics page.
	 *
	 * @return void
	 */
	public function statistics_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__(
					'You do not have permission to access this page.',
					'trade-sphare-ads'
				)
			);
		}

		global $wpdb;

		$table_name = TSA_Stats_Table::table_name();

		/*
		 * Get totals.
		 */
		$totals = $wpdb->get_row(
			"SELECT
				COALESCE(SUM(impressions), 0) AS impressions,
				COALESCE(SUM(clicks), 0) AS clicks
			FROM {$table_name}"
		);

		$total_impressions = isset( $totals->impressions )
			? (int) $totals->impressions
			: 0;

		$total_clicks = isset( $totals->clicks )
			? (int) $totals->clicks
			: 0;

		/*
		 * Calculate CTR.
		 */
		$ctr = 0;

		if ( $total_impressions > 0 ) {
			$ctr = ( $total_clicks / $total_impressions ) * 100;
		}

		/*
		 * Statistics grouped by advertisement.
		 */
		$ad_statistics = $wpdb->get_results(
			"SELECT
				ad_id,
				SUM(impressions) AS impressions,
				SUM(clicks) AS clicks
			FROM {$table_name}
			GROUP BY ad_id
			ORDER BY impressions DESC"
		);

		/*
		 * Statistics grouped by zone.
		 */
		$zone_statistics = $wpdb->get_results(
			"SELECT
				zone_id,
				SUM(impressions) AS impressions,
				SUM(clicks) AS clicks
			FROM {$table_name}
			GROUP BY zone_id
			ORDER BY impressions DESC"
		);

		/*
		 * Daily statistics.
		 */
		$daily_statistics = $wpdb->get_results(
			"SELECT
				stat_date,
				SUM(impressions) AS impressions,
				SUM(clicks) AS clicks
			FROM {$table_name}
			GROUP BY stat_date
			ORDER BY stat_date DESC
			LIMIT 30"
		);

		include TSA_PATH . 'templates/admin/statistics/overview.php';
	}
}