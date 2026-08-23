<?php
/**
 * Trade Sphare Ads dashboard.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/*
 * Get dashboard data.
 */
$zones = TSA_Zones_Table::get_all(
	array(
		'orderby' => 'sort_order',
		'order'   => 'ASC',
		'limit'   => 100,
	)
);

$ads = TSA_Ads_Table::get_all(
	array(
		'orderby' => 'sort_order',
		'order'   => 'ASC',
		'limit'   => 100,
	)
);

/*
 * Calculate statistics.
 */
$total_zones  = count( $zones );
$active_zones = 0;

foreach ( $zones as $zone ) {
	if ( 'active' === $zone->status ) {
		$active_zones++;
	}
}

$total_ads  = count( $ads );
$active_ads = 0;

foreach ( $ads as $ad ) {
	if ( 'active' === $ad->status ) {
		$active_ads++;
	}
}

/*
 * Display a maximum number of items on the dashboard.
 */
$dashboard_zones = array_slice( $zones, 0, 5 );
$dashboard_ads   = array_slice( $ads, 0, 5 );

/*
 * Build zone lookup for advertisements.
 */
$zone_lookup = array();

foreach ( $zones as $zone ) {
	$zone_lookup[ (int) $zone->id ] = $zone;
}

/*
 * Location labels.
 */
$location_labels = array(
	'sidebar' => __( 'Sidebar', 'trade-sphare-ads' ),
	'top'     => __( 'Top of Article', 'trade-sphare-ads' ),
	'middle'  => __( 'Middle of Article', 'trade-sphare-ads' ),
	'bottom'  => __( 'Bottom of Article', 'trade-sphare-ads' ),
);

/*
 * Location descriptions.
 */
$location_descriptions = array(
	'sidebar' => __( 'Displayed automatically in the sidebar.', 'trade-sphare-ads' ),
	'top'     => __( 'Displayed automatically before the article content.', 'trade-sphare-ads' ),
	'middle'  => __( 'Displayed automatically in the middle of the article.', 'trade-sphare-ads' ),
	'bottom'  => __( 'Displayed automatically after the article content.', 'trade-sphare-ads' ),
);
?>

<div class="wrap tsa-admin-wrap">

	<h1>
		<?php esc_html_e( 'Trade Sphare Ads', 'trade-sphare-ads' ); ?>
	</h1>

	<p class="tsa-dashboard-description">
		<?php
		esc_html_e(
			'Manage your advertising zones and advertisements from one place.',
			'trade-sphare-ads'
		);
		?>
	</p>

	<!-- Statistics -->

	<div class="tsa-dashboard-stats">

		<div class="tsa-dashboard-stat">
			<div class="tsa-dashboard-stat-number">
				<?php echo esc_html( $total_zones ); ?>
			</div>

			<div class="tsa-dashboard-stat-label">
				<?php esc_html_e( 'Ad Zones', 'trade-sphare-ads' ); ?>
			</div>
		</div>

		<div class="tsa-dashboard-stat">
			<div class="tsa-dashboard-stat-number">
				<?php echo esc_html( $active_zones ); ?>
			</div>

			<div class="tsa-dashboard-stat-label">
				<?php esc_html_e( 'Active Zones', 'trade-sphare-ads' ); ?>
			</div>
		</div>

		<div class="tsa-dashboard-stat">
			<div class="tsa-dashboard-stat-number">
				<?php echo esc_html( $total_ads ); ?>
			</div>

			<div class="tsa-dashboard-stat-label">
				<?php esc_html_e( 'Advertisements', 'trade-sphare-ads' ); ?>
			</div>
		</div>

		<div class="tsa-dashboard-stat">
			<div class="tsa-dashboard-stat-number">
				<?php echo esc_html( $active_ads ); ?>
			</div>

			<div class="tsa-dashboard-stat-label">
				<?php esc_html_e( 'Active Ads', 'trade-sphare-ads' ); ?>
			</div>
		</div>

	</div>

	<!-- Quick Actions -->

	<div class="tsa-dashboard-card">

		<h2>
			<?php esc_html_e( 'Quick Actions', 'trade-sphare-ads' ); ?>
		</h2>

		<div class="tsa-dashboard-actions">

			<a
				class="button button-primary"
				href="<?php echo esc_url( admin_url( 'admin.php?page=tsa-zones&action=add' ) ); ?>"
			>
				<?php esc_html_e( 'Add New Zone', 'trade-sphare-ads' ); ?>
			</a>

			<a
				class="button button-primary"
				href="<?php echo esc_url( admin_url( 'admin.php?page=tsa-ads&action=add' ) ); ?>"
			>
				<?php esc_html_e( 'Add New Ad', 'trade-sphare-ads' ); ?>
			</a>

			<a
				class="button"
				href="<?php echo esc_url( admin_url( 'admin.php?page=tsa-zones' ) ); ?>"
			>
				<?php esc_html_e( 'Manage Zones', 'trade-sphare-ads' ); ?>
			</a>

			<a
				class="button"
				href="<?php echo esc_url( admin_url( 'admin.php?page=tsa-ads' ) ); ?>"
			>
				<?php esc_html_e( 'Manage Ads', 'trade-sphare-ads' ); ?>
			</a>

		</div>

	</div>

	<!-- Ad Zones -->

	<div class="tsa-dashboard-card">

		<div class="tsa-dashboard-card-header">

			<h2>
				<?php esc_html_e( 'Ad Zones', 'trade-sphare-ads' ); ?>
			</h2>

			<a
				href="<?php echo esc_url( admin_url( 'admin.php?page=tsa-zones' ) ); ?>"
			>
				<?php esc_html_e( 'View All', 'trade-sphare-ads' ); ?>
			</a>

		</div>

		<?php if ( empty( $dashboard_zones ) ) : ?>

			<p>
				<?php
				esc_html_e(
					'No advertising zones have been created yet.',
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
							<?php esc_html_e( 'Location', 'trade-sphare-ads' ); ?>
						</th>

						<th>
							<?php esc_html_e( 'Size', 'trade-sphare-ads' ); ?>
						</th>

						<th>
							<?php esc_html_e( 'Status', 'trade-sphare-ads' ); ?>
						</th>

						<th>
							<?php esc_html_e( 'Automatic Display', 'trade-sphare-ads' ); ?>
						</th>

						<th>
							<?php esc_html_e( 'Shortcode', 'trade-sphare-ads' ); ?>
						</th>

					</tr>

				</thead>

				<tbody>

					<?php foreach ( $dashboard_zones as $zone ) : ?>

						<?php
						$zone_location = isset( $location_labels[ $zone->location ] )
							? $location_labels[ $zone->location ]
							: $zone->location;

						$zone_description = isset( $location_descriptions[ $zone->location ] )
							? $location_descriptions[ $zone->location ]
							: __( 'Displayed according to the selected zone location.', 'trade-sphare-ads' );

						$shortcode = '[tsa_ad zone="' . absint( $zone->id ) . '"]';
						?>

						<tr>

							<td>
								<strong>
									<?php echo esc_html( $zone->name ); ?>
								</strong>
							</td>

							<td>

								<strong>
									<?php echo esc_html( $zone_location ); ?>
								</strong>

								<div class="description">
									<?php echo esc_html( $zone_description ); ?>
								</div>

							</td>

							<td>
								<?php
								echo esc_html(
									$zone->width . ' × ' . $zone->height . ' px'
								);
								?>
							</td>

							<td>

								<?php if ( 'active' === $zone->status ) : ?>

									<span class="tsa-status tsa-status-active">
										<?php esc_html_e( 'Active', 'trade-sphare-ads' ); ?>
									</span>

								<?php else : ?>

									<span class="tsa-status tsa-status-inactive">
										<?php esc_html_e( 'Inactive', 'trade-sphare-ads' ); ?>
									</span>

								<?php endif; ?>

							</td>

							<td>

								<?php if ( ! empty( $zone->automatic_display ) ) : ?>

									<span class="tsa-display-method tsa-display-automatic">
										<?php esc_html_e( 'Automatic', 'trade-sphare-ads' ); ?>
									</span>

									<div class="description">
										<?php echo esc_html( $zone_description ); ?>
									</div>

								<?php else : ?>

									<span class="tsa-display-method tsa-display-shortcode">
										<?php esc_html_e( 'Shortcode Only', 'trade-sphare-ads' ); ?>
									</span>

									<div class="description">
										<?php
										esc_html_e(
											'Automatic display is disabled for this zone.',
											'trade-sphare-ads'
										);
										?>
									</div>

								<?php endif; ?>

							</td>

							<td>

								<code>
									<?php echo esc_html( $shortcode ); ?>
								</code>

							</td>

						</tr>

					<?php endforeach; ?>

				</tbody>

			</table>

		<?php endif; ?>

	</div>

	<!-- Advertisements -->

	<div class="tsa-dashboard-card">

		<div class="tsa-dashboard-card-header">

			<h2>
				<?php esc_html_e( 'Advertisements', 'trade-sphare-ads' ); ?>
			</h2>

			<a
				href="<?php echo esc_url( admin_url( 'admin.php?page=tsa-ads' ) ); ?>"
			>
				<?php esc_html_e( 'View All', 'trade-sphare-ads' ); ?>
			</a>

		</div>

		<?php if ( empty( $dashboard_ads ) ) : ?>

			<p>
				<?php
				esc_html_e(
					'No advertisements have been created yet.',
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
							<?php esc_html_e( 'Zone', 'trade-sphare-ads' ); ?>
						</th>

						<th>
							<?php esc_html_e( 'Status', 'trade-sphare-ads' ); ?>
						</th>

						<th>
							<?php esc_html_e( 'Sort Order', 'trade-sphare-ads' ); ?>
						</th>

					</tr>

				</thead>

				<tbody>

					<?php foreach ( $dashboard_ads as $ad ) : ?>

						<tr>

							<td>

								<strong>
									<?php echo esc_html( $ad->name ); ?>
								</strong>

								<?php if ( ! empty( $ad->title ) ) : ?>

									<div class="description">
										<?php echo esc_html( $ad->title ); ?>
									</div>

								<?php endif; ?>

							</td>

							<td>

								<?php
								$ad_zone_id = isset( $ad->zone_id )
									? absint( $ad->zone_id )
									: 0;

								if (
									$ad_zone_id &&
									isset( $zone_lookup[ $ad_zone_id ] )
								) {

									echo esc_html(
										$zone_lookup[ $ad_zone_id ]->name
									);

								} else {

									esc_html_e(
										'Unassigned',
										'trade-sphare-ads'
									);
								}
								?>

							</td>

							<td>

								<?php if ( 'active' === $ad->status ) : ?>

									<span class="tsa-status tsa-status-active">
										<?php esc_html_e( 'Active', 'trade-sphare-ads' ); ?>
									</span>

								<?php else : ?>

									<span class="tsa-status tsa-status-inactive">
										<?php esc_html_e( 'Inactive', 'trade-sphare-ads' ); ?>
									</span>

								<?php endif; ?>

							</td>

							<td>
								<?php echo esc_html( $ad->sort_order ); ?>
							</td>

						</tr>

					<?php endforeach; ?>

				</tbody>

			</table>

		<?php endif; ?>

	</div>

	<!-- Display Methods -->

	<div class="tsa-dashboard-card">

		<h2>
			<?php esc_html_e( 'Display Methods', 'trade-sphare-ads' ); ?>
		</h2>

		<div class="tsa-dashboard-methods">

			<div class="tsa-dashboard-method">

				<h3>
					<?php esc_html_e( 'Automatic Display', 'trade-sphare-ads' ); ?>
				</h3>

				<p>
					<?php
					esc_html_e(
						'Enable Automatic Display in a zone to show its active advertisements automatically according to the selected location.',
						'trade-sphare-ads'
					);
					?>
				</p>

				<ul>
					<li>
						<strong>
							<?php esc_html_e( 'Sidebar:', 'trade-sphare-ads' ); ?>
						</strong>
						<?php esc_html_e( 'Displayed in the configured sidebar widget.', 'trade-sphare-ads' ); ?>
					</li>

					<li>
						<strong>
							<?php esc_html_e( 'Top:', 'trade-sphare-ads' ); ?>
						</strong>
						<?php esc_html_e( 'Displayed before the article content.', 'trade-sphare-ads' ); ?>
					</li>

					<li>
						<strong>
							<?php esc_html_e( 'Middle:', 'trade-sphare-ads' ); ?>
						</strong>
						<?php esc_html_e( 'Displayed in the middle of the article.', 'trade-sphare-ads' ); ?>
					</li>

					<li>
						<strong>
							<?php esc_html_e( 'Bottom:', 'trade-sphare-ads' ); ?>
						</strong>
						<?php esc_html_e( 'Displayed after the article content.', 'trade-sphare-ads' ); ?>
					</li>
				</ul>

			</div>

			<div class="tsa-dashboard-method">

				<h3>
					<?php esc_html_e( 'Shortcode', 'trade-sphare-ads' ); ?>
				</h3>

				<p>
					<?php
					esc_html_e(
						'Display a specific advertising zone manually anywhere shortcodes are supported.',
						'trade-sphare-ads'
					);
					?>
				</p>

				<code>
					[tsa_ad zone="2"]
				</code>

			</div>

		</div>

	</div>

	<!-- Upgrade to PRO -->

	<div class="tsa-dashboard-card tsa-dashboard-pro-card">

		<h2>
			<?php esc_html_e( 'Upgrade to PRO', 'trade-sphare-ads' ); ?>
		</h2>

		<p>
			<?php
			esc_html_e(
				'Unlock advanced advertising features and powerful campaign management tools.',
				'trade-sphare-ads'
			);
			?>
		</p>

		<a
			class="button button-primary"
			href="<?php echo esc_url( admin_url( 'admin.php?page=tsa-upgrade' ) ); ?>"
		>
			<?php esc_html_e( 'Upgrade to PRO', 'trade-sphare-ads' ); ?>
		</a>

	</div>

</div>