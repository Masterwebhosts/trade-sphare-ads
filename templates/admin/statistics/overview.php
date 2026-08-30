<?php
/**
 * Advertisement statistics overview.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

$ctr_display = number_format_i18n( $ctr, 2 );
?>

<div class="wrap tsa-admin-wrap">

        <h1>
                <?php esc_html_e( 'Statistics', 'trade-sphare-ads' ); ?>
        </h1>

        <p class="tsa-dashboard-description">
                <?php
                esc_html_e(
                        'Monitor advertisement impressions, clicks, and click-through rate.',
                        'trade-sphare-ads'
                );
                ?>
        </p>

        <!-- Overview -->

        <div class="tsa-dashboard-stats">

                <div class="tsa-dashboard-stat">

                        <div class="tsa-dashboard-stat-number">
                                <?php echo esc_html( number_format_i18n( $total_impressions ) ); ?>
                        </div>

                        <div class="tsa-dashboard-stat-label">
                                <?php esc_html_e( 'Impressions', 'trade-sphare-ads' ); ?>
                        </div>

                </div>

                <div class="tsa-dashboard-stat">

                        <div class="tsa-dashboard-stat-number">
                                <?php echo esc_html( number_format_i18n( $total_clicks ) ); ?>
                        </div>

                        <div class="tsa-dashboard-stat-label">
                                <?php esc_html_e( 'Clicks', 'trade-sphare-ads' ); ?>
                        </div>

                </div>

                <div class="tsa-dashboard-stat">

                        <div class="tsa-dashboard-stat-number">
                                <?php echo esc_html( $ctr_display ); ?>%
                        </div>

                        <div class="tsa-dashboard-stat-label">
                                <?php esc_html_e( 'CTR', 'trade-sphare-ads' ); ?>
                        </div>

                </div>

        </div>

        <!-- Advertisement statistics -->

        <div class="tsa-dashboard-card">

                <div class="tsa-dashboard-card-header">

                        <h2>
                                <?php esc_html_e( 'Statistics by Advertisement', 'trade-sphare-ads' ); ?>
                        </h2>

                </div>

                <?php if ( empty( $ad_statistics ) ) : ?>

                        <p>
                                <?php
                                esc_html_e(
                                        'No advertisement statistics are available yet.',
                                        'trade-sphare-ads'
                                );
                                ?>
                        </p>

                <?php else : ?>

                        <table class="widefat striped">

                                <thead>

                                        <tr>

                                                <th>
                                                        <?php esc_html_e( 'Advertisement', 'trade-sphare-ads' ); ?>
                                                </th>

                                                <th>
                                                        <?php esc_html_e( 'Impressions', 'trade-sphare-ads' ); ?>
                                                </th>

                                                <th>
                                                        <?php esc_html_e( 'Clicks', 'trade-sphare-ads' ); ?>
                                                </th>

                                                <th>
                                                        <?php esc_html_e( 'CTR', 'trade-sphare-ads' ); ?>
                                                </th>

                                        </tr>

                                </thead>

                                <tbody>

                                        <?php foreach ( $ad_statistics as $stat ) : ?>

                                                <?php
                                                $ad_id = absint( $stat->ad_id );
                                                $impressions = (int) $stat->impressions;
                                                $clicks = (int) $stat->clicks;

                                                $ad_ctr = 0;

                                                if ( $impressions > 0 ) {
                                                        $ad_ctr = ( $clicks / $impressions ) * 100;
                                                }

                                                $ad = TSA_Ads_Table::get( $ad_id );
                                                ?>

                                                <tr>

                                                        <td>

                                                                <?php if ( $ad ) : ?>

                                                                        <strong>
                                                                                <?php echo esc_html( $ad->name ); ?>
                                                                        </strong>

                                                                <?php else : ?>

                                                                        <?php
                                                                        printf(
                                                                                esc_html__( 'Ad #%d', 'trade-sphare-ads' ),
                                                                                $ad_id
                                                                        );
                                                                        ?>

                                                                <?php endif; ?>

                                                        </td>

                                                        <td>
                                                                <?php echo esc_html( number_format_i18n( $impressions ) ); ?>
                                                        </td>

                                                        <td>
                                                                <?php echo esc_html( number_format_i18n( $clicks ) ); ?>
                                                        </td>

                                                        <td>
                                                                <?php echo esc_html( number_format_i18n( $ad_ctr, 2 ) ); ?>%
                                                        </td>

                                                </tr>

                                        <?php endforeach; ?>

                                </tbody>

                        </table>

                <?php endif; ?>

        </div>

        <!-- Zone statistics -->

        <div class="tsa-dashboard-card">

                <div class="tsa-dashboard-card-header">

                        <h2>
                                <?php esc_html_e( 'Statistics by Zone', 'trade-sphare-ads' ); ?>
                        </h2>

                </div>

                <?php if ( empty( $zone_statistics ) ) : ?>

                        <p>
                                <?php
                                esc_html_e(
                                        'No zone statistics are available yet.',
                                        'trade-sphare-ads'
                                );
                                ?>
                        </p>

                <?php else : ?>

                        <table class="widefat striped">

                                <thead>

                                        <tr>

                                                <th>
                                                        <?php esc_html_e( 'Zone', 'trade-sphare-ads' ); ?>
                                                </th>

                                                <th>
                                                        <?php esc_html_e( 'Impressions', 'trade-sphare-ads' ); ?>
                                                </th>

                                                <th>
                                                        <?php esc_html_e( 'Clicks', 'trade-sphare-ads' ); ?>
                                                </th>

                                                <th>
                                                        <?php esc_html_e( 'CTR', 'trade-sphare-ads' ); ?>
                                                </th>

                                        </tr>

                                </thead>

                                <tbody>

                                        <?php foreach ( $zone_statistics as $stat ) : ?>

                                                <?php
                                                $zone_id = absint( $stat->zone_id );
                                                $impressions = (int) $stat->impressions;
                                                $clicks = (int) $stat->clicks;

                                                $zone_ctr = 0;

                                                if ( $impressions > 0 ) {
                                                        $zone_ctr = ( $clicks / $impressions ) * 100;
                                                }

                                                $zone = TSA_Zones_Table::get( $zone_id );
                                                ?>

                                                <tr>

                                                        <td>

                                                                <?php if ( $zone ) : ?>

                                                                        <strong>
                                                                                <?php echo esc_html( $zone->name ); ?>
                                                                        </strong>

                                                                <?php else : ?>

                                                                        <?php
                                                                        printf(
                                                                                esc_html__( 'Zone #%d', 'trade-sphare-ads' ),
                                                                                $zone_id
                                                                        );
                                                                        ?>

                                                                <?php endif; ?>

                                                        </td>

                                                        <td>
                                                                <?php echo esc_html( number_format_i18n( $impressions ) ); ?>
                                                        </td>

                                                        <td>
                                                                <?php echo esc_html( number_format_i18n( $clicks ) ); ?>
                                                        </td>

                                                        <td>
                                                                <?php echo esc_html( number_format_i18n( $zone_ctr, 2 ) ); ?>%
                                                        </td>

                                                </tr>

                                        <?php endforeach; ?>

                                </tbody>

                        </table>

                <?php endif; ?>

        </div>

        <!-- Daily statistics -->

        <div class="tsa-dashboard-card">

                <div class="tsa-dashboard-card-header">

                        <h2>
                                <?php esc_html_e( 'Daily Statistics', 'trade-sphare-ads' ); ?>
                        </h2>

                        <span class="description">
                                <?php esc_html_e( 'Last 30 days with recorded activity.', 'trade-sphare-ads' ); ?>
                        </span>

                </div>

                <?php if ( empty( $daily_statistics ) ) : ?>

                        <p>
                                <?php
                                esc_html_e(
                                        'No daily statistics are available yet.',
                                        'trade-sphare-ads'
                                );
                                ?>
                        </p>

                <?php else : ?>

                        <table class="widefat striped">

                                <thead>

                                        <tr>

                                                <th>
                                                        <?php esc_html_e( 'Date', 'trade-sphare-ads' ); ?>
                                                </th>

                                                <th>
                                                        <?php esc_html_e( 'Impressions', 'trade-sphare-ads' ); ?>
                                                </th>

                                                <th>
                                                        <?php esc_html_e( 'Clicks', 'trade-sphare-ads' ); ?>
                                                </th>

                                                <th>
                                                        <?php esc_html_e( 'CTR', 'trade-sphare-ads' ); ?>
                                                </th>

                                        </tr>

                                </thead>

                                <tbody>

                                        <?php foreach ( $daily_statistics as $stat ) : ?>

                                                <?php
                                                $impressions = (int) $stat->impressions;
                                                $clicks = (int) $stat->clicks;

                                                $daily_ctr = 0;

                                                if ( $impressions > 0 ) {
                                                        $daily_ctr = ( $clicks / $impressions ) * 100;
                                                }
                                                ?>

                                                <tr>

                                                        <td>
                                                                <?php echo esc_html( $stat->stat_date ); ?>
                                                        </td>

                                                        <td>
                                                                <?php echo esc_html( number_format_i18n( $impressions ) ); ?>
                                                        </td>

                                                        <td>
                                                                <?php echo esc_html( number_format_i18n( $clicks ) ); ?>
                                                        </td>

                                                        <td>
                                                                <?php echo esc_html( number_format_i18n( $daily_ctr, 2 ) ); ?>%
                                                        </td>

                                                </tr>

                                        <?php endforeach; ?>

                                </tbody>

                        </table>

                <?php endif; ?>

        </div>

</div>