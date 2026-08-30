
/**
 * Trade Sphare Agency Partner Form.
 *
 * @package TradeSphareAds
 */

(function () {
        'use strict';

        /**
         * Initialize the agency partner form.
         */
        function initAgencyForm() {

                var form = document.getElementById(
                        'tsa-agency-partner-form'
                );

                if (!form) {
                        return;
                }

                var messageBox = document.getElementById(
                        'tsa-agency-message'
                );

                var submitButton = form.querySelector(
                        '.tsa-agency-submit'
                );

                var submitText = form.querySelector(
                        '.tsa-agency-submit-text'
                );

                var submitLoading = form.querySelector(
                        '.tsa-agency-submit-loading'
                );

                /**
                 * Get a field value.
                 *
                 * @param {string} name Field name.
                 * @return {string} Field value.
                 */
                function getValue(name) {

                        var field = form.elements[name];

                        if (!field) {
                                return '';
                        }

                        return field.value.trim();
                }

                /**
                 * Show form message.
                 *
                 * @param {string} message Message text.
                 * @param {string} type Message type.
                 */
                function showMessage(message, type) {

                        if (!messageBox) {
                                return;
                        }

                        messageBox.textContent = message;

                        messageBox.className =
                                'tsa-agency-message ' +
                                'tsa-agency-message-' +
                                type;

                        messageBox.hidden = false;
                }

                /**
                 * Hide form message.
                 */
                function hideMessage() {

                        if (!messageBox) {
                                return;
                        }

                        messageBox.textContent = '';
                        messageBox.className = 'tsa-agency-message';
                        messageBox.hidden = true;
                }

                /**
                 * Remove validation errors.
                 */
                function clearErrors() {

                        var fields = form.querySelectorAll(
                                '.tsa-agency-error'
                        );

                        fields.forEach(function (field) {
                                field.classList.remove(
                                        'tsa-agency-error'
                                );
                        });
                }

                /**
                 * Mark field as invalid.
                 *
                 * @param {HTMLElement} field Field element.
                 */
                function markError(field) {

                        if (!field) {
                                return;
                        }

                        field.classList.add(
                                'tsa-agency-error'
                        );
                }

                /**
                 * Validate phone number.
                 *
                 * @param {string} phone Phone number.
                 * @return {boolean} Whether phone is valid.
                 */
                function isValidPhone(phone) {

                        var normalized = phone.replace(
                                /[\s().\-]/g,
                                ''
                        );

                        return /^\+?[0-9]{7,20}$/.test(
                                normalized
                        );
                }

                /**
                 * Validate URL.
                 *
                 * @param {string} value URL.
                 * @return {boolean} Whether URL is valid.
                 */
                function isValidUrl(value) {

                        if (!value) {
                                return true;
                        }

                        try {
                                var url = new URL(value);

                                return (
                                        url.protocol === 'http:' ||
                                        url.protocol === 'https:'
                                );

                        } catch (error) {
                                return false;
                        }
                }

                /**
                 * Normalize phone number.
                 *
                 * @param {string} phone Phone number.
                 * @return {string} Normalized phone.
                 */
                function normalizePhone(phone) {

                        return phone.replace(
                                /[^0-9]/g,
                                ''
                        );
                }

                /**
                 * Replace WhatsApp message variables.
                 *
                 * @param {string} template Message template.
                 * @param {Object} data Form data.
                 * @return {string} Final message.
                 */
                function replaceVariables(template, data) {

                        var message = template || '';

                        Object.keys(data).forEach(function (key) {

                                var variable =
                                        '{' + key + '}';

                                message = message.split(
                                        variable
                                ).join(
                                        data[key] || '-'
                                );
                        });

                        return message;
                }

                /**
                 * Build default WhatsApp message.
                 *
                 * @param {Object} data Form data.
                 * @return {string} WhatsApp message.
                 */
                function buildDefaultMessage(data) {

                        var commission =
                                window.tsaAgency &&
                                tsaAgency.commission
                                        ? tsaAgency.commission
                                        : 15;

                        return [
                                'مرحبًا Trade Sphare،',
                                '',
                                'لدي طلب شراكة جديد.',
                                '',
                                'الاسم: ' + (data.name || '-'),
                                'الهاتف: ' + (data.phone || '-'),
                                'الدولة: ' + (data.country || '-'),
                                'نوع الشريك: ' + (
                                        data.partner_type || '-'
                                ),
                                'الموقع: ' + (
                                        data.website || '-'
                                ),
                                'طريقة جلب المعلنين: ' + (
                                        data.method || '-'
                                ),
                                'العملاء المحتملون شهريًا: ' + (
                                        data.clients || '-'
                                ),
                                'ملاحظات: ' + (
                                        data.notes || '-'
                                ),
                                '',
                                'نسبة العمولة الحالية: ' +
                                        commission +
                                        '%',
                                '',
                                'تم إرسال الطلب من صفحة برنامج الشركاء.'
                        ].join('\n');
                }

                /**
                 * Collect form data.
                 *
                 * @return {Object} Form data.
                 */
                function collectData() {

                        return {
                                name: getValue('name'),
                                phone: getValue('phone'),
                                country: getValue('country'),
                                partner_type: getValue(
                                        'partner_type'
                                ),
                                website: getValue('website'),
                                method: getValue('method'),
                                clients: getValue('clients'),
                                notes: getValue('notes')
                        };
                }

                /**
                 * Validate form.
                 *
                 * @param {Object} data Form data.
                 * @return {boolean} Whether form is valid.
                 */
                function validate(data) {

                        var valid = true;

                        var requiredFields = [
                                'name',
                                'phone',
                                'country',
                                'partner_type',
                                'method'
                        ];

                        requiredFields.forEach(function (fieldName) {

                                var field =
                                        form.elements[fieldName];

                                if (
                                        !data[fieldName] ||
                                        data[fieldName].length === 0
                                ) {
                                        markError(field);
                                        valid = false;
                                }
                        });

                        if (
                                data.phone &&
                                !isValidPhone(data.phone)
                        ) {

                                markError(
                                        form.elements.phone
                                );

                                showMessage(
                                        tsaAgency.i18n.invalidPhone,
                                        'error'
                                );

                                return false;
                        }

                        if (
                                data.website &&
                                !isValidUrl(data.website)
                        ) {

                                markError(
                                        form.elements.website
                                );

                                showMessage(
                                        tsaAgency.i18n.invalidWebsite,
                                        'error'
                                );

                                return false;
                        }

                        if (!valid) {

                                showMessage(
                                        tsaAgency.i18n.required,
                                        'error'
                                );

                                return false;
                        }

                        return true;
                }

                /**
                 * Set loading state.
                 *
                 * @param {boolean} loading Loading state.
                 */
                function setLoading(loading) {

                        if (!submitButton) {
                                return;
                        }

                        submitButton.disabled = loading;

                        if (submitText) {
                                submitText.hidden = loading;
                        }

                        if (submitLoading) {
                                submitLoading.hidden = !loading;
                        }
                }

                /**
                 * Open WhatsApp.
                 *
                 * @param {string} phone WhatsApp phone number.
                 * @param {string} message Message text.
                 */
                function openWhatsApp(phone, message) {

                        var normalizedPhone =
                                normalizePhone(phone);

                        if (!normalizedPhone) {

                                showMessage(
                                        tsaAgency.i18n.whatsappUnavailable,
                                        'error'
                                );

                                return;
                        }

                        var url =
                                'https://wa.me/' +
                                normalizedPhone +
                                '?text=' +
                                encodeURIComponent(message);

                        window.open(
                                url,
                                '_blank',
                                'noopener,noreferrer'
                        );
                }

                /**
                 * Handle form submission.
                 *
                 * @param {SubmitEvent} event Submit event.
                 */
                function handleSubmit(event) {

                        event.preventDefault();

                        clearErrors();
                        hideMessage();

                        var data = collectData();

                        if (!validate(data)) {
                                return;
                        }

                        if (
                                typeof tsaAgency === 'undefined' ||
                                !tsaAgency.whatsapp
                        ) {

                                showMessage(
                                        'رقم واتساب غير متوفر حاليًا. يرجى المحاولة لاحقًا.',
                                        'error'
                                );

                                return;
                        }

                        setLoading(true);

                        var template =
                                tsaAgency.message || '';

                        var finalMessage =
                                template
                                        ? replaceVariables(
                                                template,
                                                data
                                        )
                                        : buildDefaultMessage(
                                                data
                                        );

                        /*
                         * Add commission information when the
                         * administrator configured a commission.
                         */
                        if (
                                tsaAgency.commission &&
                                finalMessage.indexOf(
                                        'نسبة العمولة'
                                ) === -1
                        ) {

                                finalMessage +=
                                        '\n\nنسبة العمولة: ' +
                                        tsaAgency.commission +
                                        '%';
                        }

                        openWhatsApp(
                                tsaAgency.whatsapp,
                                finalMessage
                        );

                        /*
                         * Give the browser a short moment before
                         * restoring the button state.
                         */
                        window.setTimeout(
                                function () {
                                        setLoading(false);
                                },
                                700
                        );
                }

                form.addEventListener(
                        'submit',
                        handleSubmit
                );
        }

        /**
         * Start when DOM is ready.
         */
        if (
                document.readyState === 'loading'
        ) {

                document.addEventListener(
                        'DOMContentLoaded',
                        initAgencyForm
                );

        } else {

                initAgencyForm();
        }

})();

