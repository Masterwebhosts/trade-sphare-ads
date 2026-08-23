<?php
/**
 * Automatic article advertisement placement.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles automatic advertisement placement inside articles.
 */
class TSA_Article {

        /**
         * Register article hooks.
         *
         * @return void
         */
        public static function init() {

                add_filter(
                        'the_content',
                        array( __CLASS__, 'inject_ads' ),
                        20
                );
        }

        /**
         * Inject automatic advertisements into article content.
         *
         * @param string $content Post content.
         * @return string
         */
        public static function inject_ads( $content ) {

                if ( is_admin() || is_feed() || is_preview() ) {
                        return $content;
                }

                if ( ! is_singular( 'post' ) || empty( $content ) ) {
                        return $content;
                }

                $top_zones = self::get_zones_by_location( 'top' );

                if ( ! empty( $top_zones ) ) {
                        $content = self::prepend_zones(
                                $content,
                                $top_zones
                        );
                }

                $middle_zones = self::get_zones_by_location( 'middle' );

                if ( ! empty( $middle_zones ) ) {
                        $content = self::insert_middle_zones(
                                $content,
                                $middle_zones
                        );
                }

                $bottom_zones = self::get_zones_by_location( 'bottom' );

                if ( ! empty( $bottom_zones ) ) {
                        $content = self::append_zones(
                                $content,
                                $bottom_zones
                        );
                }

                return $content;
        }

        /**
         * Get active automatic zones for a location.
         *
         * @param string $location Zone location.
         * @return array
         */
        private static function get_zones_by_location( $location ) {

                return TSA_Zones_Table::get_all(
                        array(
                                'status'   => 'active',
                                'location' => $location,
                                'orderby'  => 'sort_order',
                                'order'    => 'ASC',
                                'limit'    => 100,
                        )
                );
        }

        /**
         * Render zones with automatic display enabled.
         *
         * @param array $zones Zones.
         * @return string
         */
        private static function render_zones( $zones ) {

                $output = '';

                foreach ( $zones as $zone ) {

                        if (
                                ! isset( $zone->automatic_display ) ||
                                1 !== (int) $zone->automatic_display
                        ) {
                                continue;
                        }

                        $zone_id = absint( $zone->id );

                        if ( ! $zone_id ) {
                                continue;
                        }

                        $rendered = TSA_Ad_Placement::render_zone( $zone_id );

                        if ( ! empty( $rendered ) ) {
                                $output .= $rendered;
                        }
                }

                return $output;
        }

        /**
         * Add zones before article content.
         *
         * @param string $content Article content.
         * @param array  $zones Zones.
         * @return string
         */
        private static function prepend_zones( $content, $zones ) {

                $ads = self::render_zones( $zones );

                if ( empty( $ads ) ) {
                        return $content;
                }

                return $ads . $content;
        }

        /**
         * Add zones after article content.
         *
         * @param string $content Article content.
         * @param array  $zones Zones.
         * @return string
         */
        private static function append_zones( $content, $zones ) {

                $ads = self::render_zones( $zones );

                if ( empty( $ads ) ) {
                        return $content;
                }

                return $content . $ads;
        }

        /**
         * Insert zones into the middle of the article.
         *
         * @param string $content Article content.
         * @param array  $zones Zones.
         * @return string
         */
        private static function insert_middle_zones( $content, $zones ) {

                $ads = self::render_zones( $zones );

                if ( empty( $ads ) ) {
                        return $content;
                }

                $paragraphs = preg_split(
                        '/(<\/p>)/i',
                        $content,
                        -1,
                        PREG_SPLIT_DELIM_CAPTURE
                );

                if ( ! is_array( $paragraphs ) || count( $paragraphs ) < 3 ) {
                        return $content . $ads;
                }

                $paragraph_count = 0;

                foreach ( $paragraphs as $part ) {

                        if ( preg_match( '/<\/p>/i', $part ) ) {
                                $paragraph_count++;
                        }
                }

                if ( $paragraph_count < 2 ) {
                        return $content . $ads;
                }

                $target = max(
                        1,
                        (int) ceil( $paragraph_count / 2 )
                );

                $current = 0;

                foreach ( $paragraphs as $index => $part ) {

                        if ( preg_match( '/<\/p>/i', $part ) ) {

                                $current++;

                                if ( $current === $target ) {

                                        array_splice(
                                                $paragraphs,
                                                $index + 1,
                                                0,
                                                array( $ads )
                                        );

                                        break;
                                }
                        }
                }

                return implode( '', $paragraphs );
        }
}