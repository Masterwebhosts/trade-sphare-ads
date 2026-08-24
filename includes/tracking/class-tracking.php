<?php
/**
 * Advertisement tracking manager.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles advertisement statistics tracking.
 */
class TSA_Tracking {

        /**
         * Record an advertisement impression.
         *
         * @param int $ad_id   Advertisement ID.
         * @param int $zone_id Ad zone ID.
         * @return bool
         */
        public static function record_impression( $ad_id, $zone_id ) {

                return self::increment_stat(
                        $ad_id,
                        $zone_id,
                        'impressions'
                );
        }

        /**
         * Record an advertisement click.
         *
         * @param int $ad_id   Advertisement ID.
         * @param int $zone_id Ad zone ID.
         * @return bool
         */
        public static function record_click( $ad_id, $zone_id ) {

                return self::increment_stat(
                        $ad_id,
                        $zone_id,
                        'clicks'
                );
        }

        /**
         * Increment a daily statistic.
         *
         * @param int    $ad_id   Advertisement ID.
         * @param int    $zone_id Ad zone ID.
         * @param string $column  Statistic column.
         * @return bool
         */
        private static function increment_stat(
                $ad_id,
                $zone_id,
                $column
        ) {
                global $wpdb;

                $ad_id   = absint( $ad_id );
                $zone_id = absint( $zone_id );

                if ( ! $ad_id || ! $zone_id ) {
                        return false;
                }

                $allowed_columns = array(
                        'impressions',
                        'clicks',
                );

                if ( ! in_array( $column, $allowed_columns, true ) ) {
                        return false;
                }

                $table_name = TSA_Stats_Table::table_name();
                $stat_date  = current_time( 'Y-m-d' );
                $now        = current_time( 'mysql' );

                $sql = $wpdb->prepare(
                        "INSERT INTO {$table_name}
                        (
                                stat_date,
                                ad_id,
                                zone_id,
                                impressions,
                                clicks,
                                created_at,
                                updated_at
                        )
                        VALUES
                        (
                                %s,
                                %d,
                                %d,
                                %d,
                                %d,
                                %s,
                                %s
                        )
                        ON DUPLICATE KEY UPDATE
                                {$column} = {$column} + 1,
                                updated_at = VALUES(updated_at)",
                        $stat_date,
                        $ad_id,
                        $zone_id,
                        'impressions' === $column ? 1 : 0,
                        'clicks' === $column ? 1 : 0,
                        $now,
                        $now
                );

                return false !== $wpdb->query( $sql );
        }
}