<?php
/**
 * Ad zones admin controller.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles ad zone administration.
 */
class TSA_Zones {

	/**
	 * Handle zone admin requests.
	 *
	 * @return array
	 */
	public static function handle_request() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return array();
		}

		$message = '';
		$error   = '';

		if ( isset( $_POST['tsa_zone_action'] ) ) {

			$nonce = isset( $_POST['tsa_zone_nonce'] )
				? sanitize_text_field( wp_unslash( $_POST['tsa_zone_nonce'] ) )
				: '';

			if ( ! wp_verify_nonce( $nonce, 'tsa_zone_action' ) ) {
				$error = __( 'Security verification failed.', 'trade-sphare-ads' );
			} else {

				$action = sanitize_key(
					wp_unslash( $_POST['tsa_zone_action'] )
				);

				if ( 'create' === $action ) {

					$result = TSA_Zones_Table::insert(
						self::get_post_data()
					);

					if ( is_wp_error( $result ) ) {
						$error = $result->get_error_message();
					} else {
						$message = __( 'Ad zone created successfully.', 'trade-sphare-ads' );
					}
				}

				if ( 'update' === $action ) {

					$id = isset( $_POST['zone_id'] )
						? absint( $_POST['zone_id'] )
						: 0;

					$result = TSA_Zones_Table::update(
						$id,
						self::get_post_data()
					);

					if ( is_wp_error( $result ) ) {
						$error = $result->get_error_message();
					} else {
						$message = __( 'Ad zone updated successfully.', 'trade-sphare-ads' );
					}
				}
			}
		}

		if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] ) {

			$nonce = isset( $_GET['_wpnonce'] )
				? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) )
				: '';

			$id = isset( $_GET['zone_id'] )
				? absint( $_GET['zone_id'] )
				: 0;

			if (
				$id &&
				wp_verify_nonce( $nonce, 'tsa_delete_zone_' . $id )
			) {

				$result = TSA_Zones_Table::delete( $id );

				if ( is_wp_error( $result ) ) {
					$error = $result->get_error_message();
				} else {
					$message = __( 'Ad zone deleted successfully.', 'trade-sphare-ads' );
				}
			}
		}

		return array(
			'zones'   => TSA_Zones_Table::get_all(),
			'message' => $message,
			'error'   => $error,
		);
	}

	/**
	 * Get submitted zone data.
	 *
	 * @return array
	 */
	private static function get_post_data() {

		return array(
			'name'         => isset( $_POST['name'] )
				? sanitize_text_field( wp_unslash( $_POST['name'] ) )
				: '',

			'slug'         => isset( $_POST['slug'] )
				? sanitize_title( wp_unslash( $_POST['slug'] ) )
				: '',

			'location'     => isset( $_POST['location'] )
				? sanitize_key( wp_unslash( $_POST['location'] ) )
				: 'sidebar',

			'width'        => isset( $_POST['width'] )
				? absint( $_POST['width'] )
				: 300,

			'height'       => isset( $_POST['height'] )
				? absint( $_POST['height'] )
				: 250,

			'pricing_type' => isset( $_POST['pricing_type'] )
				? sanitize_key( wp_unslash( $_POST['pricing_type'] ) )
				: 'monthly',

			'price'        => isset( $_POST['price'] )
				? (float) wp_unslash( $_POST['price'] )
				: 0,

			'currency'     => isset( $_POST['currency'] )
				? strtoupper(
					sanitize_text_field(
						wp_unslash( $_POST['currency'] )
					)
				)
				: 'USD',

			'status'       => isset( $_POST['status'] )
				? sanitize_key( wp_unslash( $_POST['status'] ) )
				: 'active',

				'automatic_display' => isset( $_POST['automatic_display'] )
                ? absint( $_POST['automatic_display'] )
                : 1,

			'sort_order'   => isset( $_POST['sort_order'] )
				? intval( $_POST['sort_order'] )
				: 0,
		);
	}
}