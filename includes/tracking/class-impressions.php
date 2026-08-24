<?php
/**
 * Advertisement impression tracking.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles advertisement impression tracking.
 */
class TSA_Impressions {

        /**
         * Record an advertisement impression.
         *
         * @param int $ad_id   Advertisement ID.
         * @param int $zone_id Ad zone ID.
         * @return bool
         */
        public static function record( $ad_id, $zone_id ) {

                return TSA_Tracking::record_impression(
                        $ad_id,
                        $zone_id
                );
        }
}