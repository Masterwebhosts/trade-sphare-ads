<?php
/**
 * Advertisement template.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $ad ) || ! is_object( $ad ) ) {
        return;
}

$ad_id      = isset( $ad->id ) ? absint( $ad->id ) : 0;
$zone_id    = isset( $zone_id ) ? absint( $zone_id ) : 0;
$title      = isset( $ad->title ) ? $ad->title : '';
$content    = isset( $ad->content ) ? $ad->content : '';
$image_url  = isset( $ad->image_url ) ? $ad->image_url : '';
$target_url = isset( $ad->target_url ) ? $ad->target_url : '';

/*
 * Build the click tracking URL.
 *
 * The tracking endpoint records the click and then
 * redirects the visitor to the original target URL.
 */
$click_url = '';

if ( $target_url && $ad_id && $zone_id ) {

        $click_url = add_query_arg(
                array(
                        'tsa_click'  => 1,
                        'tsa_ad_id'  => $ad_id,
                        'tsa_zone_id' => $zone_id,
                ),
                home_url( '/' )
        );
}
?>

<div
        class="tsa-ad"
        data-ad-id="<?php echo esc_attr( $ad_id ); ?>"
        data-zone-id="<?php echo esc_attr( $zone_id ); ?>"
>

        <?php if ( $click_url ) : ?>

                <a
                        class="tsa-ad-link"
                        href="<?php echo esc_url( $click_url ); ?>"
                        target="_blank"
                        rel="nofollow sponsored noopener"
                >

        <?php endif; ?>

                <?php if ( $image_url ) : ?>

                        <div class="tsa-ad-image">
                                <img
                                        src="<?php echo esc_url( $image_url ); ?>"
                                        alt="<?php echo esc_attr( $title ); ?>"
                                        loading="lazy"
                                >
                        </div>

                <?php endif; ?>

                <?php if ( $title ) : ?>

                        <h3 class="tsa-ad-title">
                                <?php echo esc_html( $title ); ?>
                        </h3>

                <?php endif; ?>

                <?php if ( $content ) : ?>

                        <div class="tsa-ad-content">
                                <?php echo wp_kses_post( $content ); ?>
                        </div>

                <?php endif; ?>

        <?php if ( $click_url ) : ?>

                </a>

        <?php endif; ?>

</div>