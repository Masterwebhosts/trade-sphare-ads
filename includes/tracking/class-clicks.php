<?php
/**
 * Advertisement click tracking.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles advertisement click tracking.
 */
class TSA_Clicks {

        /**
         * Record an advertisement click.
         *
         * @param int $ad_id   Advertisement ID.
         * @param int $zone_id Ad zone ID.
         * @return bool
         */
        public static function record( $ad_id, $zone_id ) {

                return TSA_Tracking::record_click(
                        $ad_id,
                        $zone_id
                );
        }
}