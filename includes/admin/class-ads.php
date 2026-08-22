<?php
/**
 * Ads admin controller.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles advertisement administration.
 */
class TSA_Ads {

	/**
	 * Handle advertisement admin requests.
	 *
	 * @return array
	 */
	public static function handle_request() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return array(
				'ads'     => array(),
				'message' => '',
				'error'   => '',
			);
		}

		$message = '';
		$error   = '';

		/*
		 * Create or update advertisement.
		 */
		if ( isset( $_POST['tsa_ad_action'] ) ) {

			$nonce = isset( $_POST['tsa_ad_nonce'] )
				? sanitize_text_field( wp_unslash( $_POST['tsa_ad_nonce'] ) )
				: '';

			if ( ! wp_verify_nonce( $nonce, 'tsa_ad_action' ) ) {

				$error = __( 'Security verification failed.', 'trade-sphare-ads' );

			} else {

				$action = sanitize_key(
					wp_unslash( $_POST['tsa_ad_action'] )
				);

				$data = self::get_post_data();

				if ( 'create' === $action ) {

					$result = TSA_Ads_Table::insert( $data );

					if ( is_wp_error( $result ) ) {
						$error = $result->get_error_message();
					} else {
						$message = __( 'Advertisement created successfully.', 'trade-sphare-ads' );
					}
				}

				if ( 'update' === $action ) {

					$id = isset( $_POST['ad_id'] )
						? absint( $_POST['ad_id'] )
						: 0;

					$result = TSA_Ads_Table::update( $id, $data );

					if ( is_wp_error( $result ) ) {
						$error = $result->get_error_message();
					} else {
						$message = __( 'Advertisement updated successfully.', 'trade-sphare-ads' );
					}
				}
			}
		}

		/*
		 * Delete advertisement.
		 */
		if (
			isset( $_GET['action'] ) &&
			'delete' === sanitize_key( wp_unslash( $_GET['action'] ) )
		) {

			$id = isset( $_GET['ad_id'] )
				? absint( $_GET['ad_id'] )
				: 0;

			$nonce = isset( $_GET['_wpnonce'] )
				? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) )
				: '';

			if (
				$id &&
				wp_verify_nonce( $nonce, 'tsa_delete_ad_' . $id )
			) {

				$result = TSA_Ads_Table::delete( $id );

				if ( is_wp_error( $result ) ) {
					$error = $result->get_error_message();
				} else {
					$message = __( 'Advertisement deleted successfully.', 'trade-sphare-ads' );
				}
			}
		}

		return array(
			'ads'     => TSA_Ads_Table::get_all(),
			'message' => $message,
			'error'   => $error,
		);
	}

	/**
	 * Get submitted advertisement data.
	 *
	 * @return array
	 */
	private static function get_post_data() {

		return array(
			'zone_id' => isset( $_POST['zone_id'] )
				? absint( $_POST['zone_id'] )
				: 0,

			'name' => isset( $_POST['name'] )
				? sanitize_text_field( wp_unslash( $_POST['name'] ) )
				: '',

			'title' => isset( $_POST['title'] )
				? sanitize_text_field( wp_unslash( $_POST['title'] ) )
				: '',

			'content' => isset( $_POST['content'] )
				? wp_kses_post( wp_unslash( $_POST['content'] ) )
				: '',

			'image_url' => isset( $_POST['image_url'] )
				? esc_url_raw( wp_unslash( $_POST['image_url'] ) )
				: '',

			'target_url' => isset( $_POST['target_url'] )
				? esc_url_raw( wp_unslash( $_POST['target_url'] ) )
				: '',

			'status' => isset( $_POST['status'] )
				? sanitize_key( wp_unslash( $_POST['status'] ) )
				: 'active',

			'sort_order' => isset( $_POST['sort_order'] )
				? intval( $_POST['sort_order'] )
				: 0,
		);
	}
}