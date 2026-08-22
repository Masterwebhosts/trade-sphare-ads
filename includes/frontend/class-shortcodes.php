<?php
/**
 * Frontend shortcodes.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles advertisement shortcodes.
 */
class TSA_Shortcodes {

	/**
	 * Register shortcodes.
	 *
	 * @return void
	 */
	public static function init() {

		add_shortcode(
			'tsa_ad',
			array( __CLASS__, 'render_ad' )
		);
	}

	/**
	 * Render an advertisement shortcode.
	 *
	 * Usage: [tsa_ad zone="2"]
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function render_ad( $atts ) {

		$atts = shortcode_atts(
			array(
				'zone' => 0,
			),
			$atts,
			'tsa_ad'
		);

		$zone_id = absint( $atts['zone'] );

		if ( ! $zone_id ) {
			return '';
		}

		return TSA_Ad_Placement::render_zone( $zone_id );
	}
}