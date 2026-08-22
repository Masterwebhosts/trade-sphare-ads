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
	 * @param object|null $ad Advertisement object.
	 * @return string
	 */
	public static function render( $ad ) {

		if ( ! $ad || empty( $ad->id ) ) {
			return '';
		}

		ob_start();

		include TSA_PATH . 'templates/frontend/ad.php';

		return ob_get_clean();
	}
}