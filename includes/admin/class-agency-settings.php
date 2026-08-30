<?php
/**
 * Agency partners settings.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles Trade Sphare Ads agency partner settings.
 */
class TSA_Agency_Settings {

	/**
	 * Option name.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'tsa_agency_settings';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {

		add_action(
			'admin_init',
			array( $this, 'register_settings' )
		);
	}

	/**
	 * Register agency settings.
	 *
	 * @return void
	 */
	public function register_settings() {

		register_setting(
			'tsa_agency_settings_group',
			self::OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => $this->get_defaults(),
			)
		);

		add_settings_section(
			'tsa_agency_general',
			__( 'Agency Partner Program', 'trade-sphare-ads' ),
			array( $this, 'render_section_description' ),
			'tsa-agency-settings'
		);

		add_settings_field(
			'enabled',
			__( 'Program Status', 'trade-sphare-ads' ),
			array( $this, 'render_enabled_field' ),
			'tsa-agency-settings',
			'tsa_agency_general'
		);

		add_settings_field(
			'whatsapp',
			__( 'WhatsApp Number', 'trade-sphare-ads' ),
			array( $this, 'render_whatsapp_field' ),
			'tsa-agency-settings',
			'tsa_agency_general'
		);

		add_settings_field(
			'email',
			__( 'Partner Email', 'trade-sphare-ads' ),
			array( $this, 'render_email_field' ),
			'tsa-agency-settings',
			'tsa_agency_general'
		);

		add_settings_field(
			'commission',
			__( 'Commission Rate', 'trade-sphare-ads' ),
			array( $this, 'render_commission_field' ),
			'tsa-agency-settings',
			'tsa_agency_general'
		);

		add_settings_field(
			'currency',
			__( 'Currency', 'trade-sphare-ads' ),
			array( $this, 'render_currency_field' ),
			'tsa-agency-settings',
			'tsa_agency_general'
		);

		add_settings_field(
			'whatsapp_message',
			__( 'WhatsApp Message', 'trade-sphare-ads' ),
			array( $this, 'render_whatsapp_message_field' ),
			'tsa-agency-settings',
			'tsa_agency_general'
		);

		add_settings_field(
			'shortcode',
			__( 'Agency Form Shortcode', 'trade-sphare-ads' ),
			array( $this, 'render_shortcode_field' ),
			'tsa-agency-settings',
			'tsa_agency_general'
		);
	}

	/**
	 * Get default settings.
	 *
	 * @return array
	 */
	public function get_defaults() {

		return array(
			'enabled'          => 1,
			'whatsapp'         => '96179168899',
			'email'            => 'partners@tradesphare.com',
			'commission'       => 15,
			'currency'         => 'USD',
			'whatsapp_message' => "طلب انضمام إلى برنامج شركاء Trade Sphare\n\n"
				. "الاسم: {name}\n"
				. "رقم الهاتف / واتساب: {phone}\n"
				. "الدولة: {country}\n"
				. "نوع الشريك: {partner_type}\n"
				. "الموقع / الصفحة: {website}\n"
				. "طريقة جلب المعلنين: {method}\n"
				. "عدد العملاء المحتملين: {clients}\n"
				. "ملاحظات: {notes}\n\n"
				. "المصدر: صفحة برنامج شركاء Trade Sphare",
		);
	}

	/**
	 * Get settings.
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = get_option(
			self::OPTION_NAME,
			array()
		);

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		return wp_parse_args(
			$settings,
			$this->get_defaults()
		);
	}

	/**
	 * Sanitize settings.
	 *
	 * @param array $input Settings input.
	 * @return array
	 */
	public function sanitize_settings( $input ) {

		if ( ! is_array( $input ) ) {
			return $this->get_defaults();
		}

		return array(
			'enabled' => ! empty( $input['enabled'] ) ? 1 : 0,

			'whatsapp' => isset( $input['whatsapp'] )
				? preg_replace(
					'/[^0-9]/',
					'',
					$input['whatsapp']
				)
				: '',

			'email' => isset( $input['email'] )
				? sanitize_email( $input['email'] )
				: '',

			'commission' => isset( $input['commission'] )
				? max(
					0,
					min(
						100,
						(float) $input['commission']
					)
				)
				: 15,

			'currency' => isset( $input['currency'] )
				? sanitize_text_field( $input['currency'] )
				: 'USD',

			'whatsapp_message' => isset( $input['whatsapp_message'] )
				? sanitize_textarea_field(
					$input['whatsapp_message']
				)
				: '',
		);
	}

	/**
	 * Render section description.
	 *
	 * @return void
	 */
	public function render_section_description() {

		echo '<p>';
		echo esc_html__(
			'Configure the Trade Sphare partner program and WhatsApp contact details.',
			'trade-sphare-ads'
		);
		echo '</p>';
	}

	/**
	 * Render enabled field.
	 *
	 * @return void
	 */
	public function render_enabled_field() {

		$settings = $this->get_settings();
		?>

		<label>
			<input
				type="checkbox"
				name="<?php echo esc_attr( self::OPTION_NAME ); ?>[enabled]"
				value="1"
				<?php checked( 1, $settings['enabled'] ); ?>
			>

			<?php
			esc_html_e(
				'Enable the partner program',
				'trade-sphare-ads'
			);
			?>
		</label>

		<?php
	}

	/**
	 * Render WhatsApp field.
	 *
	 * @return void
	 */
	public function render_whatsapp_field() {

		$settings = $this->get_settings();
		?>

		<input
			type="text"
			class="regular-text"
			name="<?php echo esc_attr( self::OPTION_NAME ); ?>[whatsapp]"
			value="<?php echo esc_attr( $settings['whatsapp'] ); ?>"
			placeholder="96179168899"
		>

		<p class="description">
			<?php
			esc_html_e(
				'Use the international format without + or spaces.',
				'trade-sphare-ads'
			);
			?>
		</p>

		<?php
	}

	/**
	 * Render email field.
	 *
	 * @return void
	 */
	public function render_email_field() {

		$settings = $this->get_settings();
		?>

		<input
			type="email"
			class="regular-text"
			name="<?php echo esc_attr( self::OPTION_NAME ); ?>[email]"
			value="<?php echo esc_attr( $settings['email'] ); ?>"
		>

		<?php
	}

	/**
	 * Render commission field.
	 *
	 * @return void
	 */
	public function render_commission_field() {

		$settings = $this->get_settings();
		?>

		<input
			type="number"
			min="0"
			max="100"
			step="0.01"
			class="small-text"
			name="<?php echo esc_attr( self::OPTION_NAME ); ?>[commission]"
			value="<?php echo esc_attr( $settings['commission'] ); ?>"
		>

		<span>%</span>

		<p class="description">
			<?php
			esc_html_e(
				'Commission earned by the partner after the advertiser completes payment.',
				'trade-sphare-ads'
			);
			?>
		</p>

		<?php
	}

	/**
	 * Render currency field.
	 *
	 * @return void
	 */
	public function render_currency_field() {

		$settings = $this->get_settings();
		?>

		<input
			type="text"
			class="small-text"
			name="<?php echo esc_attr( self::OPTION_NAME ); ?>[currency]"
			value="<?php echo esc_attr( $settings['currency'] ); ?>"
			maxlength="10"
		>

		<?php
	}

	/**
	 * Render WhatsApp message field.
	 *
	 * @return void
	 */
	public function render_whatsapp_message_field() {

		$settings = $this->get_settings();
		?>

		<textarea
			name="<?php echo esc_attr( self::OPTION_NAME ); ?>[whatsapp_message]"
			rows="12"
			class="large-text"
		><?php echo esc_textarea( $settings['whatsapp_message'] ); ?></textarea>

		<p class="description">
			<?php
			esc_html_e(
				'Available variables: {name}, {phone}, {country}, {partner_type}, {website}, {method}, {clients}, {notes}.',
				'trade-sphare-ads'
			);
			?>
		</p>

		<?php
	}

	/**
	 * Render shortcode field.
	 *
	 * @return void
	 */
	public function render_shortcode_field() {
		?>

		<div class="tsa-agency-shortcode-box">

			<input
				type="text"
				class="regular-text code"
				value="[tsa_agency_form]"
				readonly
				id="tsa-agency-shortcode"
			>

			<button
				type="button"
				class="button"
				id="tsa-copy-agency-shortcode"
			>
				<?php
				esc_html_e(
					'Copy Shortcode',
					'trade-sphare-ads'
				);
				?>
			</button>

			<p class="description">
				<?php
				esc_html_e(
					'Copy this shortcode and place it inside any WordPress page where you want to display the agency partner form.',
					'trade-sphare-ads'
				);
				?>
			</p>

		</div>

		<script>
		document.addEventListener(
			'DOMContentLoaded',
			function () {

				const button = document.getElementById(
					'tsa-copy-agency-shortcode'
				);

				const input = document.getElementById(
					'tsa-agency-shortcode'
				);

				if ( ! button || ! input ) {
					return;
				}

				button.addEventListener(
					'click',
					function () {

						if (
							navigator.clipboard &&
							window.isSecureContext
						) {
							navigator.clipboard.writeText(
								input.value
							);
						} else {
							input.select();

							document.execCommand(
								'copy'
							);
						}

						const originalText =
							button.textContent;

						button.textContent =
							'<?php echo esc_js( __( 'Copied!', 'trade-sphare-ads' ) ); ?>';

						setTimeout(
							function () {
								button.textContent =
									originalText;
							},
							1500
						);
					}
				);
			}
		);
		</script>

		<?php
	}

	/**
	 * Render settings page.
	 *
	 * @return void
	 */
	public function render_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__(
					'You do not have permission to access this page.',
					'trade-sphare-ads'
				)
			);
		}
		?>

		<div class="wrap">

			<h1>
				<?php
				esc_html_e(
					'Agency Partners',
					'trade-sphare-ads'
				);
				?>
			</h1>

			<form
				method="post"
				action="options.php"
			>

				<?php
				settings_fields(
					'tsa_agency_settings_group'
				);

				do_settings_sections(
					'tsa-agency-settings'
				);

				submit_button();
				?>

			</form>

		</div>

		<?php
	}
}