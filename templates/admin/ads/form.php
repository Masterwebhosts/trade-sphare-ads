<?php
/**
 * Advertisement form.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

$ad = isset( $ad ) && is_object( $ad ) ? $ad : null;

$is_edit = ! empty( $ad );

$action = $is_edit ? 'update' : 'create';

$zone_id = $is_edit ? absint( $ad->zone_id ) : 0;
$name    = $is_edit ? $ad->name : '';
$title   = $is_edit ? $ad->title : '';

$content = $is_edit ? $ad->content : '';

$image_url  = $is_edit ? $ad->image_url : '';
$target_url = $is_edit ? $ad->target_url : '';

$status = $is_edit ? $ad->status : 'active';

$sort_order = $is_edit ? intval( $ad->sort_order ) : 0;

$zones = TSA_Zones_Table::get_all(
	array(
		'limit'  => 100,
		'offset' => 0,
	)
);
?>

<div class="wrap">

	<h1>
		<?php
		echo esc_html(
			$is_edit
				? __( 'Edit Advertisement', 'trade-sphare-ads' )
				: __( 'Add New Advertisement', 'trade-sphare-ads' )
		);
		?>
	</h1>

	<form method="post">

		<input
			type="hidden"
			name="tsa_ad_action"
			value="<?php echo esc_attr( $action ); ?>"
		>

		<?php if ( $is_edit ) : ?>

			<input
				type="hidden"
				name="ad_id"
				value="<?php echo esc_attr( $ad->id ); ?>"
			>

		<?php endif; ?>

		<?php wp_nonce_field( 'tsa_ad_action', 'tsa_ad_nonce' ); ?>

		<table class="form-table">

			<tr>

				<th>
					<label for="tsa-ad-zone">
						<?php esc_html_e( 'Ad Zone', 'trade-sphare-ads' ); ?>
					</label>
				</th>

				<td>

					<select
						id="tsa-ad-zone"
						name="zone_id"
						required
					>

						<option value="">
							<?php esc_html_e( 'Select a zone', 'trade-sphare-ads' ); ?>
						</option>

						<?php foreach ( $zones as $zone ) : ?>

							<option
								value="<?php echo esc_attr( $zone->id ); ?>"
								<?php selected( $zone_id, $zone->id ); ?>
							>
								<?php
								echo esc_html(
									$zone->name . ' (' .
									$zone->width . ' × ' .
									$zone->height . ' px)'
								);
								?>
							</option>

						<?php endforeach; ?>

					</select>

				</td>

			</tr>

			<tr>

				<th>
					<label for="tsa-ad-name">
						<?php esc_html_e( 'Ad Name', 'trade-sphare-ads' ); ?>
					</label>
				</th>

				<td>

					<input
						type="text"
						id="tsa-ad-name"
						name="name"
						value="<?php echo esc_attr( $name ); ?>"
						class="regular-text"
						required
					>

					<p class="description">
						<?php esc_html_e( 'Internal name used to identify the advertisement.', 'trade-sphare-ads' ); ?>
					</p>

				</td>

			</tr>

			<tr>

				<th>
					<label for="tsa-ad-title">
						<?php esc_html_e( 'Title', 'trade-sphare-ads' ); ?>
					</label>
				</th>

				<td>

					<input
						type="text"
						id="tsa-ad-title"
						name="title"
						value="<?php echo esc_attr( $title ); ?>"
						class="regular-text"
					>

				</td>

			</tr>

			<tr>

				<th>
					<label for="tsa-ad-content">
						<?php esc_html_e( 'Content', 'trade-sphare-ads' ); ?>
					</label>
				</th>

				<td>

					<?php
					wp_editor(
						$content,
						'tsa_ad_content',
						array(
							'textarea_name' => 'content',
							'textarea_rows' => 8,
							'media_buttons' => true,
						)
					);
					?>

				</td>

			</tr>

			<tr>

				<th>
					<label for="tsa-ad-image">
						<?php esc_html_e( 'Image URL', 'trade-sphare-ads' ); ?>
					</label>
				</th>

				<td>

					<input
						type="url"
						id="tsa-ad-image"
						name="image_url"
						value="<?php echo esc_attr( $image_url ); ?>"
						class="regular-text"
					>

					<p class="description">
						<?php esc_html_e( 'Optional image URL for the advertisement.', 'trade-sphare-ads' ); ?>
					</p>

				</td>

			</tr>

			<tr>

				<th>
					<label for="tsa-ad-target">
						<?php esc_html_e( 'Target URL', 'trade-sphare-ads' ); ?>
					</label>
				</th>

				<td>

					<input
						type="url"
						id="tsa-ad-target"
						name="target_url"
						value="<?php echo esc_attr( $target_url ); ?>"
						class="regular-text"
					>

					<p class="description">
						<?php esc_html_e( 'URL opened when the advertisement is clicked.', 'trade-sphare-ads' ); ?>
					</p>

				</td>

			</tr>

			<tr>

				<th>
					<label for="tsa-ad-status">
						<?php esc_html_e( 'Status', 'trade-sphare-ads' ); ?>
					</label>
				</th>

				<td>

					<select
						id="tsa-ad-status"
						name="status"
					>

						<option
							value="active"
							<?php selected( $status, 'active' ); ?>
						>
							<?php esc_html_e( 'Active', 'trade-sphare-ads' ); ?>
						</option>

						<option
							value="inactive"
							<?php selected( $status, 'inactive' ); ?>
						>
							<?php esc_html_e( 'Inactive', 'trade-sphare-ads' ); ?>
						</option>

					</select>

				</td>

			</tr>

			<tr>

				<th>
					<label for="tsa-ad-sort">
						<?php esc_html_e( 'Sort Order', 'trade-sphare-ads' ); ?>
					</label>
				</th>

				<td>

					<input
						type="number"
						id="tsa-ad-sort"
						name="sort_order"
						value="<?php echo esc_attr( $sort_order ); ?>"
						class="small-text"
					>

					<p class="description">
						<?php esc_html_e( 'Lower numbers are displayed first.', 'trade-sphare-ads' ); ?>
					</p>

				</td>

			</tr>

		</table>

		<?php
		submit_button(
			$is_edit
				? __( 'Update Advertisement', 'trade-sphare-ads' )
				: __( 'Create Advertisement', 'trade-sphare-ads' )
		);
		?>

	</form>

</div>