<?php
/**
 * Agency partner application form.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/*
 * Expected variables:
 *
 * $settings
 */

if ( ! isset( $settings ) || ! is_array( $settings ) ) {
	return;
}

$commission = isset( $settings['commission'] )
	? (float) $settings['commission']
	: 15;

$currency = isset( $settings['currency'] )
	? sanitize_text_field( $settings['currency'] )
	: 'USD';

$whatsapp = isset( $settings['whatsapp'] )
	? preg_replace( '/[^0-9]/', '', $settings['whatsapp'] )
	: '';

$enabled = ! empty( $settings['enabled'] );
?>

<section class="tsa-agency-section" id="tsa-agency-form">


<div class="tsa-agency-container">

	<div class="tsa-agency-header">

		<div class="tsa-agency-badge">
			<?php
			esc_html_e(
				'Trade Sphare Partners',
				'trade-sphare-ads'
			);
			?>
		</div>

		<h2>
			<?php
			esc_html_e(
				'انضم كشريك في Trade Sphare',
				'trade-sphare-ads'
			);
			?>
		</h2>

		<p>
			<?php
			esc_html_e(
				'إذا كانت لديك علاقات أو جمهور أو قدرة على الوصول إلى أصحاب الأعمال، يمكنك التعاون معنا لجلب طلبات إعلانية والحصول على عمولة عن الإعلانات المدفوعة.',
				'trade-sphare-ads'
			);
			?>
		</p>

	</div>

	<?php if ( ! $enabled ) : ?>

		<div class="tsa-agency-disabled">

			<h3>
				<?php
				esc_html_e(
					'برنامج الشركاء غير متاح حاليًا',
					'trade-sphare-ads'
				);
				?>
			</h3>

			<p>
				<?php
				esc_html_e(
					'نعتذر، استقبال طلبات الشركاء متوقف حاليًا. يرجى المحاولة لاحقًا.',
					'trade-sphare-ads'
				);
				?>
			</p>

		</div>

	<?php else : ?>

		<div class="tsa-agency-layout">

			<!-- Instructions -->

			<div class="tsa-agency-info">

				<div class="tsa-agency-commission">

					<strong>
						<?php echo esc_html( $commission ); ?>%
					</strong>

					<div>
						<?php
						esc_html_e(
							'عمولة الشريك',
							'trade-sphare-ads'
						);
						?>
					</div>

				</div>

				<h3>
					<?php
					esc_html_e(
						'كيف يعمل البرنامج؟',
						'trade-sphare-ads'
					);
					?>
				</h3>

				<ol class="tsa-agency-steps">

					<li>

						<div class="tsa-agency-step-content">

							<strong>
								<?php
								esc_html_e(
									'تعرّف على نشاط تجاري محتمل',
									'trade-sphare-ads'
								);
								?>
							</strong>

							<p>
								<?php
								esc_html_e(
									'يمكن أن يكون متجرًا أو شركة أو صاحب موقع أو مدونة أو أي نشاط تجاري يحتاج إلى الإعلان.',
									'trade-sphare-ads'
								);
								?>
							</p>

						</div>

					</li>

					<li>

						<div class="tsa-agency-step-content">

							<strong>
								<?php
								esc_html_e(
									'قدّم طلب الإعلان',
									'trade-sphare-ads'
								);
								?>
							</strong>

							<p>
								<?php
								esc_html_e(
									'أرسل لنا بيانات النشاط التجاري واحتياجه الإعلاني من خلال نموذج الشراكة.',
									'trade-sphare-ads'
								);
								?>
							</p>

						</div>

					</li>

					<li>

						<div class="tsa-agency-step-content">

							<strong>
								<?php
								esc_html_e(
									'نتولى التواصل مع المعلن',
									'trade-sphare-ads'
								);
								?>
							</strong>

							<p>
								<?php
								esc_html_e(
									'نتولى التواصل مع المعلن ومتابعة طلبه حتى إتمام الاتفاق والدفع.',
									'trade-sphare-ads'
								);
								?>
							</p>

						</div>

					</li>

					<li>

						<div class="tsa-agency-step-content">

							<strong>
								<?php
								esc_html_e(
									'تحصل على عمولتك',
									'trade-sphare-ads'
								);
								?>
							</strong>

							<p>
								<?php
								esc_html_e(
									'تستحق العمولة بعد إتمام المعلن للدفع وفق شروط برنامج الشراكة.',
									'trade-sphare-ads'
								);
								?>
							</p>

						</div>

					</li>

				</ol>

				<div class="tsa-agency-note">

					<strong>
						<?php
						esc_html_e(
							'متى تستحق العمولة؟',
							'trade-sphare-ads'
						);
						?>
					</strong>

					<p>
						<?php
						printf(
							esc_html__(
								'تُحتسب عمولة الشريك بنسبة %s%% من قيمة الإعلان المؤهل، وتستحق بعد إتمام المعلن للدفع.',
								'trade-sphare-ads'
							),
							esc_html( $commission )
						);
						?>
					</p>

				</div>

			</div>

			<!-- Application Form -->

			<div class="tsa-agency-form-wrapper">

				<h3>
					<?php
					esc_html_e(
						'أرسل طلب الشراكة',
						'trade-sphare-ads'
					);
					?>
				</h3>

				<p class="tsa-agency-form-intro">
					<?php
					esc_html_e(
						'املأ البيانات التالية وسنراجع طلبك ونتواصل معك عبر واتساب.',
						'trade-sphare-ads'
					);
					?>
				</p>

				<form
					class="tsa-agency-form"
					id="tsa-agency-partner-form"
					novalidate
				>

					<div class="tsa-agency-field">

						<label for="tsa-agency-name">
							<?php
							esc_html_e(
								'الاسم الكامل',
								'trade-sphare-ads'
							);
							?>

							<span aria-hidden="true">*</span>
						</label>

						<input
							type="text"
							id="tsa-agency-name"
							name="name"
							required
							autocomplete="name"
							maxlength="100"
						>

					</div>

					<div class="tsa-agency-field">

						<label for="tsa-agency-phone">
							<?php
							esc_html_e(
								'رقم الهاتف / واتساب',
								'trade-sphare-ads'
							);
							?>

							<span aria-hidden="true">*</span>
						</label>

						<input
							type="tel"
							id="tsa-agency-phone"
							name="phone"
							required
							autocomplete="tel"
							maxlength="30"
						>

					</div>

					<div class="tsa-agency-field">

						<label for="tsa-agency-country">
							<?php
							esc_html_e(
								'الدولة',
								'trade-sphare-ads'
							);
							?>

							<span aria-hidden="true">*</span>
						</label>

						<input
							type="text"
							id="tsa-agency-country"
							name="country"
							required
							autocomplete="country-name"
							maxlength="100"
						>

					</div>

					<div class="tsa-agency-field">

						<label for="tsa-agency-partner-type">
							<?php
							esc_html_e(
								'نوع الشريك',
								'trade-sphare-ads'
							);
							?>

							<span aria-hidden="true">*</span>
						</label>

						<select
							id="tsa-agency-partner-type"
							name="partner_type"
							required
						>

							<option value="">
								<?php
								esc_html_e(
									'اختر نوع الشراكة',
									'trade-sphare-ads'
								);
								?>
							</option>

							<option value="business_owner">
								<?php
								esc_html_e(
									'صاحب محل أو نشاط تجاري',
									'trade-sphare-ads'
								);
								?>
							</option>

							<option value="website_owner">
								<?php
								esc_html_e(
									'صاحب موقع أو مدونة',
									'trade-sphare-ads'
								);
								?>
							</option>

							<option value="social_media">
								<?php
								esc_html_e(
									'صاحب صفحة أو جمهور على وسائل التواصل',
									'trade-sphare-ads'
								);
								?>
							</option>

							<option value="remote_agent">
								<?php
								esc_html_e(
									'مسوّق أو شخص يعمل عن بعد',
									'trade-sphare-ads'
								);
								?>
							</option>

							<option value="other">
								<?php
								esc_html_e(
									'أخرى',
									'trade-sphare-ads'
								);
								?>
							</option>

						</select>

					</div>

					<div class="tsa-agency-field">

						<label for="tsa-agency-website">
							<?php
							esc_html_e(
								'الموقع أو الصفحة',
								'trade-sphare-ads'
							);
							?>
						</label>

						<input
							type="url"
							id="tsa-agency-website"
							name="website"
							autocomplete="url"
							maxlength="255"
							placeholder="https://"
						>

					</div>

					<div class="tsa-agency-field">

						<label for="tsa-agency-method">
							<?php
							esc_html_e(
								'كيف يمكنك جلب المعلنين؟',
								'trade-sphare-ads'
							);
							?>

							<span aria-hidden="true">*</span>
						</label>

						<textarea
							id="tsa-agency-method"
							name="method"
							rows="4"
							required
							maxlength="1000"
						></textarea>

					</div>

					<div class="tsa-agency-field">

						<label for="tsa-agency-clients">
							<?php
							esc_html_e(
								'عدد العملاء المحتملين شهريًا',
								'trade-sphare-ads'
							);
							?>
						</label>

						<select
							id="tsa-agency-clients"
							name="clients"
						>

							<option value="">
								<?php
								esc_html_e(
									'اختر تقديرًا',
									'trade-sphare-ads'
								);
								?>
							</option>

							<option value="1-5">1 - 5</option>
							<option value="6-10">6 - 10</option>
							<option value="11-25">11 - 25</option>
							<option value="26+">26+</option>

						</select>

					</div>

					<div class="tsa-agency-field">

						<label for="tsa-agency-notes">
							<?php
							esc_html_e(
								'ملاحظات إضافية',
								'trade-sphare-ads'
							);
							?>
						</label>

						<textarea
							id="tsa-agency-notes"
							name="notes"
							rows="4"
							maxlength="1500"
						></textarea>

					</div>

					<div
						class="tsa-agency-message"
						id="tsa-agency-message"
						role="alert"
						aria-live="polite"
						hidden
					></div>

					<button
						type="submit"
						class="tsa-agency-submit"
					>

						<span class="tsa-agency-submit-text">
							<?php
							esc_html_e(
								'إرسال طلب الشراكة عبر واتساب',
								'trade-sphare-ads'
							);
							?>
						</span>

						<span
							class="tsa-agency-submit-loading"
							hidden
						>
							<?php
							esc_html_e(
								'جاري تجهيز الطلب...',
								'trade-sphare-ads'
							);
							?>
						</span>

					</button>

					<p class="tsa-agency-privacy">

						<?php
						esc_html_e(
							'بإرسال الطلب، أنت توافق على شروط الاستخدام وسياسة الخصوصية الخاصة بـ Trade Sphare.',
							'trade-sphare-ads'
						);
						?>

					</p>

				</form>

			</div>

		</div>

	<?php endif; ?>

</div>


</section>
