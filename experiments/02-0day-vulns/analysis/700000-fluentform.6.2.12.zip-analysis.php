<?php
/***
*
*Found actions: 16
*Found functions:14
*Extracted functions:13
*Total parameter names extracted: 3
*Overview: {'routeAjaxEndpoints': {'fluentform_user_payment_endpoints'}, 'handleBulkAction': {'fluentform-do_entry_bulk_actions_payment'}, 'importForms': {'fluentform-migrator-import-forms'}, 'getFilters': {'fluentform_get_all_payments_entries_filters'}, 'fluentform_dashboard_access': {'fluentform_select_group_ajax_data'}, 'buildForm': {'fluentform_ai_create_form'}, 'getFormsByKey': {'fluentform-migrator-get-forms-by-key'}, 'getPayments': {'fluentform_get_payments'}, 'getMigratorData': {'fluentform-migrator-get-migrator-data'}, 'confirmScaPayment': {'fluentform_sca_inline_confirm_payment', 'nopriv_fluentform_sca_inline_confirm_payment'}, 'fetchInterestGroups': {'fluentform_mailchimp_interest_groups'}, 'importEntries': {'fluentform-migrator-import-entries'}, 'confirmScaSetupIntentsPayment': {'nopriv_fluentform_sca_inline_confirm_payment_setup_intents', 'fluentform_sca_inline_confirm_payment_setup_intents'}, 'handleAjaxEndpoints': {'fluentform_handle_payment_ajax_endpoint'}}
*
***/

/** Function routeAjaxEndpoints() called by wp_ajax hooks: {'fluentform_user_payment_endpoints'} **/
/** No params detected :-/ **/


/** Function handleBulkAction() called by wp_ajax hooks: {'fluentform-do_entry_bulk_actions_payment'} **/
/** No params detected :-/ **/


/** Function importForms() called by wp_ajax hooks: {'fluentform-migrator-import-forms'} **/
/** No params detected :-/ **/


/** Function getFilters() called by wp_ajax hooks: {'fluentform_get_all_payments_entries_filters'} **/
/** No params detected :-/ **/


/** Function fluentform_dashboard_access() called by wp_ajax hooks: {'fluentform_select_group_ajax_data'} **/
/** No function found :-/ **/


/** Function buildForm() called by wp_ajax hooks: {'fluentform_ai_create_form'} **/
/** No params detected :-/ **/


/** Function getFormsByKey() called by wp_ajax hooks: {'fluentform-migrator-get-forms-by-key'} **/
/** No params detected :-/ **/


/** Function getPayments() called by wp_ajax hooks: {'fluentform_get_payments'} **/
/** No params detected :-/ **/


/** Function getMigratorData() called by wp_ajax hooks: {'fluentform-migrator-get-migrator-data'} **/
/** No params detected :-/ **/


/** Function confirmScaPayment() called by wp_ajax hooks: {'fluentform_sca_inline_confirm_payment', 'nopriv_fluentform_sca_inline_confirm_payment'} **/
/** Parameters found in function confirmScaPayment(): {"request": ["submission_id", "payment_method", "payment_intent_id"]} **/
function confirmScaPayment()
    {
        $submissionId = isset($_REQUEST['submission_id']) ? (int) $_REQUEST['submission_id'] : 0;
        $paymentMethod = isset($_REQUEST['payment_method']) ? sanitize_text_field(wp_unslash($_REQUEST['payment_method'])) : '';
        $paymentIntentId = isset($_REQUEST['payment_intent_id']) ? sanitize_text_field(wp_unslash($_REQUEST['payment_intent_id'])) : '';

        $this->setSubmissionId($submissionId);
        $submission = $this->getSubmission();
        $this->form = $this->getForm();

        $transaction = $this->getLastTransaction($submissionId);

        $validation = $this->validateScaRequest($submissionId, $paymentIntentId, $submission, $transaction);

        if (is_wp_error($validation)) {
            wp_send_json([
                'errors' => $validation->get_error_message(),
            ], 423);
        }

        // Use submission's form_id rather than trusting $_REQUEST
        $formId = $submission->form_id;

        $confirmation = SCA::confirmPayment($paymentIntentId, [
            'payment_method' => $paymentMethod,
        ], $formId);

        if (is_wp_error($confirmation)) {
            $message = 'Payment has been failed. ' . $confirmation->get_error_message();
            $this->handlePaymentChargeError($message, $submission, $transaction, $confirmation, 'payment_error');
        }

        if ($confirmation->status == 'succeeded') {
            $charge = $confirmation->charges->data[0];

            $confirmedCurrency = strtolower((string) $confirmation->currency);
            $transactionCurrency = strtolower((string) $transaction->currency);
            if (!$confirmedCurrency || $confirmedCurrency !== $transactionCurrency) {
                $logData = [
                    'parent_source_id' => $submission->form_id,
                    'source_type'      => 'submission_item',
                    'source_id'        => $submission->id,
                    'component'        => 'Payment',
                    'status'           => 'error',
                    'title'            => __('Stripe Currency Mismatch', 'fluentform'),
                    'description'      => sprintf(
                        // translators: %1$s is the expected currency, %2$s is the confirmed currency
                        __('Expected %1$s but Stripe confirmed %2$s. Payment rejected.', 'fluentform'),
                        strtoupper($transactionCurrency),
                        strtoupper($confirmedCurrency)
                    ),
                ];
                do_action('fluentform/log_data', $logData);

                wp_send_json([
                    'errors' => __('Payment currency verification failed.', 'fluentform'),
                ], 423);
            }

            // Verify the confirmed amount matches the transaction amount.
            // Normalize for zero-decimal currencies: FluentForm stores amounts x100 internally,
            // but Stripe returns amounts in the currency's smallest unit (e.g. yen for JPY).
            $confirmedAmount = (int) $confirmation->amount;
            if (PaymentHelper::isZeroDecimal($transaction->currency)) {
                $confirmedAmount = $confirmedAmount * 100;
            }
            if ($transaction->payment_total && $confirmedAmount != intval($transaction->payment_total)) {
                $logData = [
                    'parent_source_id' => $submission->form_id,
                    'source_type'      => 'submission_item',
                    'source_id'        => $submission->id,
                    'component'        => 'Payment',
                    'status'           => 'error',
                    'title'            => __('Stripe Amount Mismatch', 'fluentform'),
                    'description'      => sprintf(
                        // translators: %1$d is the expected amount, %2$d is the confirmed amount
                        __('Expected %1$d but Stripe confirmed %2$d. Payment rejected.', 'fluentform'),
                        intval($transaction->payment_total),
                        intval($confirmation->amount)
                    ),
                ];
                do_action('fluentform/log_data', $logData);

                wp_send_json([
                    'errors' => __('Payment amount verification failed.', 'fluentform'),
                ], 423);
            }

            $this->handlePaymentSuccess($charge, $transaction, $submission);
        } else {
            $this->handlePaymentChargeError('We could not verify your payment. Please try again', $submission, $transaction, $confirmation, 'payment_error');
        }
    }


/** Function fetchInterestGroups() called by wp_ajax hooks: {'fluentform_mailchimp_interest_groups'} **/
/** No params detected :-/ **/


/** Function importEntries() called by wp_ajax hooks: {'fluentform-migrator-import-entries'} **/
/** No params detected :-/ **/


/** Function confirmScaSetupIntentsPayment() called by wp_ajax hooks: {'nopriv_fluentform_sca_inline_confirm_payment_setup_intents', 'fluentform_sca_inline_confirm_payment_setup_intents'} **/
/** Parameters found in function confirmScaSetupIntentsPayment(): {"request": ["submission_id", "payment_intent_id"]} **/
function confirmScaSetupIntentsPayment()
    {
        $submissionId = isset($_REQUEST['submission_id']) ? intval($_REQUEST['submission_id']) : 0;
        $intentId = isset($_REQUEST['payment_intent_id']) ? sanitize_text_field(wp_unslash($_REQUEST['payment_intent_id'])) : '';

        $this->setSubmissionId($submissionId);
        $this->form = $this->getForm();

        $submission = $this->getSubmission();
        $transaction = $this->getLastTransaction($submissionId);

        // Validate the request
        $validation = $this->validateScaRequest($submissionId, $intentId, $submission, $transaction);

        if (is_wp_error($validation)) {
            wp_send_json([
                'errors' => $validation->get_error_message(),
            ], 423);
        }

        // Use submission's form_id rather than trusting $_REQUEST
        $formId = $submission->form_id;

        // Let's retrieve the intent
        $intent = SCA::retrievePaymentIntent($intentId, [
            'expand' => [
                'invoice.payment_intent',
            ],
        ], $formId);

        if (is_wp_error($intent)) {
            $this->handlePaymentChargeError($intent->get_error_message(), $submission, false, false, 'payment_intent');
        }

        $invoice = $intent->invoice;

        $this->handlePaidSubscriptionInvoice($invoice, $submission);
    }


/** Function handleAjaxEndpoints() called by wp_ajax hooks: {'fluentform_handle_payment_ajax_endpoint'} **/
/** Parameters found in function handleAjaxEndpoints(): {"request": ["route"]} **/
function handleAjaxEndpoints()
    {
        // phpcs:disable WordPress.Security.NonceVerification.Recommended -- Nonce verified by Acl::verify()
        $route = isset($_REQUEST['route']) ? sanitize_text_field(wp_unslash($_REQUEST['route'])) : '';
        $paymentMutationRoutes = [
            'update_transaction',
            'cancel_subscription'
        ];
        $formSettingRoutes = [
            'get_form_settings',
            'save_form_settings'
        ];

        if (in_array($route, $paymentMutationRoutes, true)) {
            Acl::verify('fluentform_manage_payments', $this->resolveRouteFormId($route));
        } elseif (in_array($route, $formSettingRoutes, true)) {
            Acl::verify('fluentform_forms_manager', $this->resolveRouteFormId($route));
        } else {
            Acl::verify('fluentform_settings_manager');
        }
        // phpcs:enable WordPress.Security.NonceVerification.Recommended -- End of AJAX endpoint nonce verification by Acl::verify()

        (new AjaxEndpoints())->handleEndpoint($route);
    }


