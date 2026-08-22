<?php
/**
 * Handles advertisement placement.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Selects and renders advertisements for display.
 */
class TSA_Ad_Placement {

	/**
	 * Get active advertisements for a zone.
	 *
	 * @param int $zone_id Ad zone ID.
	 * @return array
	 */
	public static function get_ads_for_zone( $zone_id ) {

		$zone_id = absint( $zone_id );

		if ( ! $zone_id ) {
			return array();
		}

		return TSA_Ads_Table::get_all(
			array(
				'zone_id' => $zone_id,
				'status'  => 'active',
				'orderby' => 'sort_order',
				'order'   => 'ASC',
				'limit'   => 100,
			)
		);
	}

	/**
	 * Get the first active advertisement for a zone.
	 *
	 * @param int $zone_id Ad zone ID.
	 * @return object|null
	 */
	public static function get_ad_for_zone( $zone_id ) {

        $zone_id = absint( $zone_id );

        if ( ! $zone_id ) {
                return null;
        }

        $ads = self::get_ads_for_zone( $zone_id );

        if ( empty( $ads ) ) {
                return null;
        }

        /*
         * Get current rotation index for this zone.
         */
        $option_name = 'tsa_rotation_index_' . $zone_id;

        $rotation_index = absint(
                get_option( $option_name, 0 )
        );

        /*
         * Select advertisement using rotation index.
         */
        $ad_index = $rotation_index % count( $ads );

        $ad = $ads[ $ad_index ];

        /*
         * Move rotation to the next advertisement.
         */
        update_option(
                $option_name,
                $rotation_index + 1,
                false
        );

        return $ad;
}

	/**
	 * Render an advertisement for a zone.
	 *
	 * @param int $zone_id Ad zone ID.
	 * @return string
	 */
	public static function render_zone( $zone_id ) {

		$ad = self::get_ad_for_zone( $zone_id );

		if ( ! $ad ) {
			return '';
		}

		return TSA_Ad_Renderer::render( $ad );
	}
}