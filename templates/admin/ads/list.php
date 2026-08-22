<?php
/**
 * Ads list.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

$data = isset( $ads ) && is_array( $ads ) ? $ads : array();

$ad_list = isset( $data['ads'] ) ? $data['ads'] : array();
$message = isset( $data['message'] ) ? $data['message'] : '';
$error   = isset( $data['error'] ) ? $data['error'] : '';
?>

<div class="wrap">

	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'Advertisements', 'trade-sphare-ads' ); ?>
	</h1>

	<a
		href="<?php echo esc_url( admin_url( 'admin.php?page=tsa-ads&action=add' ) ); ?>"
		class="page-title-action"
	>
		<?php esc_html_e( 'Add New Ad', 'trade-sphare-ads' ); ?>
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
					<?php esc_html_e( 'Title', 'trade-sphare-ads' ); ?>
				</th>

				<th>
					<?php esc_html_e( 'Zone', 'trade-sphare-ads' ); ?>
				</th>

				<th>
					<?php esc_html_e( 'Status', 'trade-sphare-ads' ); ?>
				</th>

				<th>
					<?php esc_html_e( 'Order', 'trade-sphare-ads' ); ?>
				</th>

				<th>
					<?php esc_html_e( 'Actions', 'trade-sphare-ads' ); ?>
				</th>
			</tr>
		</thead>

		<tbody>

			<?php if ( empty( $ad_list ) ) : ?>

				<tr>
					<td colspan="6">
						<?php esc_html_e( 'No advertisements found.', 'trade-sphare-ads' ); ?>
					</td>
				</tr>

			<?php else : ?>

				<?php foreach ( $ad_list as $ad ) : ?>

					<?php
					$zone = TSA_Zones_Table::get( $ad->zone_id );
					?>

					<tr>

						<td>
							<strong>
								<?php echo esc_html( $ad->name ); ?>
							</strong>
						</td>

						<td>
							<?php echo esc_html( $ad->title ); ?>
						</td>

						<td>
							<?php
							echo esc_html(
								$zone ? $zone->name : __( 'Unknown zone', 'trade-sphare-ads' )
							);
							?>
						</td>

						<td>
							<?php echo esc_html( ucfirst( $ad->status ) ); ?>
						</td>

						<td>
							<?php echo esc_html( $ad->sort_order ); ?>
						</td>

						<td>

							<a
								href="<?php echo esc_url(
									admin_url(
										'admin.php?page=tsa-ads&action=edit&ad_id=' . absint( $ad->id )
									)
								); ?>"
							>
								<?php esc_html_e( 'Edit', 'trade-sphare-ads' ); ?>
							</a>

							|
							
							<?php
							$delete_url = wp_nonce_url(
								admin_url(
									'admin.php?page=tsa-ads&action=delete&ad_id=' . absint( $ad->id )
								),
								'tsa_delete_ad_' . absint( $ad->id )
							);
							?>

							<a
								href="<?php echo esc_url( $delete_url ); ?>"
								onclick="return confirm('<?php echo esc_js( __( 'Are you sure you want to delete this advertisement?', 'trade-sphare-ads' ) ); ?>');"
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