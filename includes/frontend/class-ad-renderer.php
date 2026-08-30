<?php
/**
 * Advertisement renderer.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders advertisements on the frontend.
 */
class TSA_Ad_Renderer {

	/**
	 * Render an advertisement.
	 *
	 * @param object|null $ad      Advertisement object.
	 * @param int         $zone_id Advertisement zone ID.
	 * @return string
	 */
	public static function render( $ad, $zone_id = 0 ) {

		if ( ! $ad || empty( $ad->id ) ) {
			return '';
		}

		$zone_id = absint( $zone_id );

		ob_start();

		include TSA_PATH . 'templates/frontend/ad.php';

		return ob_get_clean();
	}
}