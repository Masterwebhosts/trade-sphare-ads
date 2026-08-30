<?php
/**
 * Agency partner frontend form.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles the Trade Sphare agency partner frontend.
 */
class TSA_Agency_Form {

	/**
	 * Initialize agency form.
	 *
	 * @return void
	 */
	public static function init() {

		add_shortcode(
			'tsa_agency_form',
			array( __CLASS__, 'render' )
		);

		add_action(
			'wp_enqueue_scripts',
			array( __CLASS__, 'register_assets' )
		);
	}

	/**
	 * Register frontend assets.
	 *
	 * @return void
	 */
	public static function register_assets() {

		wp_register_style(
			'tsa-agency-form',
			TSA_URL . 'assets/css/agency-form.css',
			array(),
			TSA_VERSION
		);

		wp_register_script(
			'tsa-agency-form',
			TSA_URL . 'assets/js/agency-form.js',
			array(),
			TSA_VERSION,
			true
		);
	}

	/**
	 * Render agency partner form.
	 *
	 * @return string
	 */
	public static function render() {

		$settings = get_option(
			'tsa_agency_settings',
			array()
		);

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		$defaults = array(
			'enabled'          => 1,
			'whatsapp'         => '',
			'email'            => '',
			'commission'       => 15,
			'currency'         => 'USD',
			'whatsapp_message' => '',
		);

		$settings = wp_parse_args(
			$settings,
			$defaults
		);

		/*
		 * Do not render anything when the partner program is disabled.
		 * The template itself also handles the disabled state.
		 */
		wp_enqueue_style( 'tsa-agency-form' );
		wp_enqueue_script( 'tsa-agency-form' );

		/*
		 * Pass settings to JavaScript.
		 */
		wp_localize_script(
			'tsa-agency-form',
			'tsaAgency',
			array(
				'whatsapp'   => preg_replace(
					'/[^0-9]/',
					'',
					$settings['whatsapp']
				),
				'message'    => $settings['whatsapp_message'],
				'commission' => (float) $settings['commission'],
				'currency'   => sanitize_text_field(
					$settings['currency']
				),
				'i18n'       => array(
					'required' => __(
						'يرجى تعبئة الحقول المطلوبة.',
						'trade-sphare-ads'
					),
					'invalidPhone' => __(
						'يرجى إدخال رقم هاتف صحيح.',
						'trade-sphare-ads'
					),
					'invalidWebsite' => __(
						'يرجى إدخال رابط صحيح.',
						'trade-sphare-ads'
					),
					'whatsappUnavailable' => __(
						'رقم واتساب غير متوفر حاليًا. يرجى المحاولة لاحقًا.',
						'trade-sphare-ads'
					),
				),
			)
		);

		/*
		 * Load frontend template.
		 */
		ob_start();

		include TSA_PATH . 'templates/frontend/agency/form.php';

		return ob_get_clean();
	}
}