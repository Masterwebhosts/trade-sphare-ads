<?php
/**
 * Ad Zones list.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

$data = isset( $zones ) && is_array( $zones ) ? $zones : array();

$zone_list = isset( $data['zones'] ) ? $data['zones'] : array();
$message   = isset( $data['message'] ) ? $data['message'] : '';
$error     = isset( $data['error'] ) ? $data['error'] : '';
?>

<div class="wrap">

	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'Ad Zones', 'trade-sphare-ads' ); ?>
	</h1>

	<a
		href="<?php echo esc_url( admin_url( 'admin.php?page=tsa-zones&action=add' ) ); ?>"
		class="page-title-action"
	>
		<?php esc_html_e( 'Add New Zone', 'trade-sphare-ads' ); ?>
	</a>

	<hr class="wp-header-end">

	<?php if ( $message ) : ?>

		<div class="notice notice-success is-dismissible">
			<p><?php echo esc_html( $message ); ?></p>
		</div>

	<?php endif; ?>

	<?php if ( $error ) : ?>

		<div class="notice notice-error">
			<p><?php echo esc_html( $error ); ?></p>
		</div>

	<?php endif; ?>

	<table class="wp-list-table widefat fixed striped">

		<thead>
			<tr>
				<th>
					<?php esc_html_e( 'Name', 'trade-sphare-ads' ); ?>
				</th>

				<th>
					<?php esc_html_e( 'Location', 'trade-sphare-ads' ); ?>
				</th>

				<th>
					<?php esc_html_e( 'Size', 'trade-sphare-ads' ); ?>
				</th>

				<th>
					<?php esc_html_e( 'Pricing', 'trade-sphare-ads' ); ?>
				</th>

				<th>
					<?php esc_html_e( 'Status', 'trade-sphare-ads' ); ?>
				</th>

				<th>
					<?php esc_html_e( 'Actions', 'trade-sphare-ads' ); ?>
				</th>
			</tr>
		</thead>

		<tbody>

			<?php if ( empty( $zone_list ) ) : ?>

				<tr>
					<td colspan="6">
						<?php esc_html_e( 'No ad zones found.', 'trade-sphare-ads' ); ?>
					</td>
				</tr>

			<?php else : ?>

				<?php foreach ( $zone_list as $zone ) : ?>

					<?php
					$zone_id = absint( $zone->id );

					$edit_url = admin_url(
						'admin.php?page=tsa-zones&action=edit&zone_id=' . $zone_id
					);

					$delete_url = wp_nonce_url(
						admin_url(
							'admin.php?page=tsa-zones&action=delete&zone_id=' . $zone_id
						),
						'tsa_delete_zone_' . $zone_id
					);
					?>

					<tr>

						<td>
							<strong>
								<?php echo esc_html( $zone->name ); ?>
							</strong>
						</td>

						<td>
							<?php echo esc_html( ucfirst( $zone->location ) ); ?>
						</td>

						<td>
							<?php
							echo esc_html(
								$zone->width . ' × ' . $zone->height . ' px'
							);
							?>
						</td>

						<td>
							<?php
							echo esc_html(
								number_format_i18n(
									(float) $zone->price,
									2
								) . ' ' . $zone->currency
							);
							?>
						</td>

						<td>
							<?php echo esc_html( ucfirst( $zone->status ) ); ?>
						</td>

						<td>

							<a href="<?php echo esc_url( $edit_url ); ?>">
								<?php esc_html_e( 'Edit', 'trade-sphare-ads' ); ?>
							</a>

							|

							<a
								href="<?php echo esc_url( $delete_url ); ?>"
								style="color:#b32d2e;"
								onclick="return confirm('<?php echo esc_js( __( 'Are you sure you want to delete this ad zone?', 'trade-sphare-ads' ) ); ?>');"
							>
								<?php esc_html_e( 'Delete', 'trade-sphare-ads' ); ?>
							</a>

						</td>

					</tr>

				<?php endforeach; ?>

			<?php endif; ?>

		</tbody>

	</table>

</div>