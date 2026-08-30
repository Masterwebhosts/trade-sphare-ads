<?php
/**
 * Advertisement click tracking handler.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles advertisement click tracking requests.
 */
class TSA_Click_Handler {

	/**
	 * Register click tracking handler.
	 *
	 * @return void
	 */
	public static function init() {

		add_action(
			'template_redirect',
			array( __CLASS__, 'handle' )
		);
	}

	/**
	 * Handle an advertisement click request.
	 *
	 * @return void
	 */
	public static function handle() {

		if ( ! isset( $_GET['tsa_click'] ) ) {
			return;
		}

		if ( 1 !== absint( $_GET['tsa_click'] ) ) {
			return;
		}

		$ad_id = isset( $_GET['tsa_ad_id'] )
			? absint( $_GET['tsa_ad_id'] )
			: 0;

		$zone_id = isset( $_GET['tsa_zone_id'] )
			? absint( $_GET['tsa_zone_id'] )
			: 0;

		if ( ! $ad_id || ! $zone_id ) {
			wp_safe_redirect( home_url( '/' ) );
			exit;
		}

		$ad = TSA_Ads_Table::get( $ad_id );

		if ( ! $ad ) {
			wp_safe_redirect( home_url( '/' ) );
			exit;
		}

		/*
		 * Make sure the advertisement belongs to the requested zone.
		 */
		if ( $zone_id !== absint( $ad->zone_id ) ) {
			wp_safe_redirect( home_url( '/' ) );
			exit;
		}

		/*
		 * Only track active advertisements.
		 */
		if ( 'active' !== $ad->status ) {
			wp_safe_redirect( home_url( '/' ) );
			exit;
		}

		/*
		 * Record the click.
		 */
		TSA_Clicks::record(
			$ad_id,
			$zone_id
		);

		/*
		 * Redirect to the advertiser's target URL.
		 */
		$target_url = ! empty( $ad->target_url )
			? esc_url_raw( $ad->target_url )
			: '';

		if ( $target_url ) {
			wp_redirect( $target_url, 302, 'TradeSphareAds' );
			exit;
		}

		/*
		 * Fallback when the advertisement has no target URL.
		 */
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}
}