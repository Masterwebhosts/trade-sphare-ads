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

$ad_id       = isset( $ad->id ) ? absint( $ad->id ) : 0;
$title       = isset( $ad->title ) ? $ad->title : '';
$content     = isset( $ad->content ) ? $ad->content : '';
$image_url   = isset( $ad->image_url ) ? $ad->image_url : '';
$target_url  = isset( $ad->target_url ) ? $ad->target_url : '';
?>

<div
	class="tsa-ad"
	data-ad-id="<?php echo esc_attr( $ad_id ); ?>"
>

	<?php if ( $target_url ) : ?>

		<a
			class="tsa-ad-link"
			href="<?php echo esc_url( $target_url ); ?>"
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

	<?php if ( $target_url ) : ?>

		</a>

	<?php endif; ?>

</div>