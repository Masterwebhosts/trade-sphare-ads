<?php
/**
 * Sidebar advertisement integration.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles sidebar advertisement widget.
 */
class TSA_Sidebar extends WP_Widget {

	/**
	 * Register sidebar widget.
	 *
	 * @return void
	 */
	public static function init() {

        add_action(
                'widgets_init',
                array( __CLASS__, 'register_widget' )
        );

        add_action(
                'dynamic_sidebar',
                array( __CLASS__, 'render_automatic_ads' ),
                20
        );
}

	/**
	 * Register widget.
	 *
	 * @return void
	 */
	public static function register_widget() {

		register_widget( __CLASS__ );
	}

	/**
	 * Widget constructor.
	 */
	public function __construct() {

		parent::__construct(
			'tsa_ads_widget',
			__( 'Trade Sphare Ads', 'trade-sphare-ads' ),
			array(
				'description' => __(
					'Display a Trade Sphare Ads advertisement zone.',
					'trade-sphare-ads'
				),
			)
		);
	}

	/**
	 * Output widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Widget instance.
	 * @return void
	 */
	public function widget( $args, $instance ) {

		$zone_id = isset( $instance['zone_id'] )
			? absint( $instance['zone_id'] )
			: 0;

		if ( ! $zone_id ) {
			return;
		}

		$output = TSA_Ad_Placement::render_zone( $zone_id );

		if ( ! $output ) {
			return;
		}

		echo $args['before_widget'];
		echo $output;
		echo $args['after_widget'];
	}

	/**
	 * Widget settings form.
	 *
	 * @param array $instance Widget instance.
	 * @return void
	 */
	public function form( $instance ) {

		$zone_id = isset( $instance['zone_id'] )
			? absint( $instance['zone_id'] )
			: 0;

		$zones = TSA_Zones_Table::get_all();
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'zone_id' ) ); ?>">
				<?php esc_html_e( 'Ad Zone', 'trade-sphare-ads' ); ?>
			</label>

			<select
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'zone_id' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'zone_id' ) ); ?>"
			>
				<option value="0">
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
		</p>

		<?php
	}

	/**
	 * Save widget settings.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Previous settings.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		return array(
			'zone_id' => isset( $new_instance['zone_id'] )
				? absint( $new_instance['zone_id'] )
				: 0,
		);
	}

	/**
 * Render automatic sidebar advertisements.
 *
 * @return void
 */
public static function render_automatic_ads() {

        $zones = TSA_Zones_Table::get_all(
                array(
                        'status'   => 'active',
                        'location' => 'sidebar',
                        'orderby'  => 'sort_order',
                        'order'    => 'ASC',
                        'limit'    => 100,
                )
        );

        if ( empty( $zones ) ) {
                return;
        }

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

                $output = TSA_Ad_Placement::render_zone( $zone_id );

                if ( empty( $output ) ) {
                        continue;
                }

                echo '<div class="tsa-automatic-sidebar-ad tsa-zone-' .
                        esc_attr( $zone_id ) .
                        '">';

                echo $output;

                echo '</div>';
        }
}
}