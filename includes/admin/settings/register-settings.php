<?php
/**
 * Register Settings
 *
 * @package     RCP
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2015, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.5.0
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * Retrieve settings tabs
 *
 * @since 2.5.0
 * @return array $tabs
 */
function rcp_get_settings_tabs() {
	$settings = rcp_get_registered_settings();

	$tabs             = array();
	$tabs['general']  = __( 'General', 'rcp' );
	$tabs['gateways'] = __( 'Payment Gateways', 'rcp' );
	$tabs['emails']   = __( 'Emails', 'rcp' );
	$tabs['invoices'] = __( 'Invoices', 'rcp' );

	if( ! empty( $settings['extensions'] ) ) {
		$tabs['extensions'] = __( 'Extensions', 'rcp' );
	}

	$tabs['licenses'] = __( 'Licenses', 'rcp' );
	$tabs['misc']      = __( 'Misc', 'rcp' );

	return apply_filters( 'rcp_settings_tabs', $tabs );
}


/**
 * Retrieve the array of plugin settings
 *
 * @since 2.5.0
 * @return array
 */
function rcp_get_registered_settings() {
	/**
	 * 'Whitelisted' RCP settings, filters are provided for each settings
	 * section to allow extensions and other plugins to add their own settings
	 */
	$rcp_settings = array(
		/** General Settings */
		'general' => apply_filters( 'rcp_settings_general',
			array(
				'registration_page' => array(
					'id' => 'registration_page',
					'name' => __( 'Registration Page', 'rcp' ),
					'desc' => __( 'Choose the page that has the [register_form] short code.', 'rcp' ),
					'type' => 'select',
					'options' => rcp_get_pages()
				),
				'redirect' => array(
					'id' => 'redirect',
					'name' => __( 'Success Page', 'rcp' ),
					'desc' => __( 'This is the page users are redirected to after a successful registration.', 'rcp' ),
					'type' => 'select',
					'options' => rcp_get_pages()
				),
				'account_page' => array(
					'id' => 'account_page',
					'name' => __( 'Account Page', 'rcp' ),
					'desc' => __( 'This page displays the account and membership information for members. Contains [subscription_details] shortcode.', 'rcp' ),
					'type' => 'select',
					'options' => rcp_get_pages()
				),
				'edit_profile' => array(
					'id' => 'edit_profile',
					'name' => __( 'Edit Profile Page', 'rcp' ),
					'desc' => __( 'This page displays a profile edit form for logged-in members. Contains [rcp_profile_editor] shortcode.', 'rcp' ),
					'type' => 'select',
					'options' => rcp_get_pages()
				),
				'update_card' => array(
					'id' => 'update_card',
					'name' => __( 'Update Billing Card Page', 'rcp' ),
					'desc' => __( 'This page displays a profile edit form for logged-in members. Contains [rcp_update_card] shortcode.', 'rcp' ),
					'type' => 'select',
					'options' => rcp_get_pages()
				),
				'auto_renew' => array(
					'id' => 'auto_renew',
					'name' => __( 'Auto Renew', 'rcp' ),
					'desc' => __( 'Select the auto renew behavior you would like subscription levels to have.', 'rcp' ),
					'type' => 'select',
					'options' => array(
						'1' => __( 'Always auto renew', 'rcp' ),
						'2' => __( 'Never auto renew', 'rcp' ),
						'3' => __( 'Let customer choose whether to auto renew', 'rcp' )
					)
				),
				'free_message' => array(
					'id' => 'free_message',
					'name' => __( 'Free Content Message', 'rcp' ),
					'desc' => __( 'This is the message shown to users that do not have privilege to view free, user only content.', 'rcp' ),
					'type' => 'rich_editor',
					'teeny' => true
				),
				'paid_message' => array(
					'id' => 'paid_message',
					'name' => __( 'Premium Content Message', 'rcp' ),
					'desc' => __( 'This is the message shown to users that do not have privilege to view premium content.', 'rcp' ),
					'type' => 'rich_editor',
					'teeny' => true
				)
			)
		),
		/** Payment Gateways Settings */
		'gateways' => apply_filters('rcp_settings_gateways',
			array(
				'currency' => array(
					'id' => 'currency',
					'name' => __( 'Currency', 'rcp' ),
					'desc' => __( 'Choose your currency.', 'rcp' ),
					'type' => 'select',
					'options' => rcp_get_currencies()
				),
				'currency_position' => array(
					'id' => 'currency_position',
					'name' => __( 'Currency Position', 'rcp' ),
					'desc' => __( 'Show the currency sign before or after the price?', 'rcp' ),
					'type' => 'select',
					'options' => array(
						'before' => __( 'Before - $10', 'rcp' ),
						'after' => __( 'After - 10$', 'rcp' )
					)
				),
				'gateways' => array(
					'id' => 'gateways',
					'name' => __( 'Enabled Gateways', 'rcp' ),
					'desc' => '',
					'type' => 'gateways',
					'options' => rcp_get_payment_gateways()
				),
				'sandbox' => array(
					'id' => 'sandbox',
					'name' => __( 'Sandbox Mode', 'rcp' ),
					'desc' => __( 'Use Restrict Content Pro in Sandbox mode. This allows you to test the plugin with test accounts from your payment processor.', 'rcp' ),
					'type' => 'checkbox'
				),
				'stripe_header' => array(
					'id' => 'stripe_header',
					'name' => __( 'Stripe Settings', 'rcp' ),
					'desc' => '',
					'type' => 'header'
				),
				'stripe_test_secret' => array(
					'id' => 'stripe_test_secret',
					'name' => __( 'Test Secret Key', 'rcp' ),
					'desc' => sprintf( __( 'Enter your test secret key. Your API keys can be obtained from your %s.'), '<a href="https://dashboard.stripe.com/account/apikeys" target="_blank">' . __( 'Stripe account settings', 'rcp' ) . '</a>' ),
					'type' => 'text'
				),
				'stripe_test_publishable' => array(
					'id' => 'stripe_test_publishable',
					'name' => __( 'Test Publishable Key', 'rcp' ),
					'desc' => __( 'Enter your test publishable key.', 'rcp' ),
					'type' => 'text'
				),
				'stripe_live_secret' => array(
					'id' => 'stripe_live_secret',
					'name' => __( 'Live Secret Key', 'rcp' ),
					'desc' => __( 'Enter your live secret key.', 'rcp' ),
					'type' => 'text'
				),
				'stripe_live_publishable' => array(
					'id' => 'stripe_live_publishable',
					'name' => __( 'Live Publishable Key', 'rcp' ),
					'desc' => __( 'Enter your live publishable key.', 'rcp' ),
					'type' => 'text'
				),
				'stripe_note' => array(
					'id' => 'stripe_note',
					'name' => __( 'Note', 'rcp' ),
					'desc' => sprintf( __( 'In order for subscription payments made through Stripe to be tracked, you must enter the following URL to your %s under Account Settings:', 'rcp' ), '<a href="https://dashboard.stripe.com/account/webhooks" target="_blank">' . __( 'Stripe Webhooks', 'rcp' ) . '</a>' ) . '<br /><strong>' . esc_url( add_query_arg( 'listener', 'stripe', home_url() ) ) . '</strong>',
					'type' => 'descriptive_text'
				),
				'twocheckout_header' => array(
					'id' => 'twocheckout_header',
					'name' => __( '2Checkout Settings', 'rcp' ),
					'desc' => '',
					'type' => 'header'
				),
				'twocheckout_secret_word' => array(
					'id' => 'twocheckout_secret_word',
					'name' => __( 'Secret Word', 'rcp' ),
					'desc' => sprintf( __( 'Enter your secret word. This can be obtained from the %s.', 'rcp' ), '<a href="https://sandbox.2checkout.com/sandbox/acct/detail_company_info" target="_blank">' . __( '2Checkout Sandbox', 'rcp' ) . '</a>' ),
					'type' => 'text'
				),
				'twocheckout_test_private' => array(
					'id' => 'twocheckout_test_private',
					'name' => __( 'Test Private Key', 'rcp' ),
					'desc' => sprintf( __( 'Enter your test private key. Your test API keys can be obtained from the %s.', 'rcp' ), '<a href="https://sandbox.2checkout.com/sandbox/api" target="_blank">' . __( '2Checkout Sandbox', 'rcp' ) . '</a>' ),
					'type' => 'text'
				),
				'twocheckout_test_publishable' => array(
					'id' => 'twocheckout_test_publishable',
					'name' => __( 'Test Publishable Key', 'rcp' ),
					'desc' => __( 'Enter your test publishable key.', 'rcp' ),
					'type' => 'text'
				),
				'twocheckout_test_seller_id' => array(
					'id' => 'twocheckout_test_seller_id',
					'name' => __( 'Test Seller ID', 'rcp' ),
					'desc' => sprintf( __( 'Enter your live Seller ID. %s.', 'rcp' ), '<a href="http://help.2checkout.com/articles/FAQ/Where-is-my-Seller-ID" target="_blank">' . __( 'Where is my Seller ID?', 'rcp' ) . '</a>' ),
					'type' => 'text'
				),
				'twocheckout_live_private' => array(
					'id' => 'twocheckout_live_private',
					'name' => __( 'Live Private Key', 'rcp' ),
					'desc' => sprintf( __( 'Enter your live private key. Your live API keys can be obtained from the %s.', 'rcp' ), '<a href="https://pci.trustwave.com/2checkout" target="_blank">' . __( '2Checkout PCI Program', 'rcp' ) . '</a>' ),
					'type' => 'text'
				),
				'twocheckout_live_publishable' => array(
					'id' => 'twocheckout_live_publishable',
					'name' => __( 'Live Publishable Key', 'rcp' ),
					'desc' => __( 'Enter your live publishable key.', 'rcp' ),
					'type' => 'text'
				),
				'twocheckout_live_seller_id' => array(
					'id' => 'twocheckout_live_seller_id',
					'name' => __( 'Live Seller ID', 'rcp' ),
					'desc' => sprintf( __( 'Enter your live Seller ID. %s.', 'rcp' ), '<a href="http://help.2checkout.com/articles/FAQ/Where-is-my-Seller-ID" target="_blank">' . __( 'Where is my Seller ID?', 'rcp' ) . '</a>' ),
					'type' => 'text'
				)
			)
		),
		/** Emails Settings */
		'emails' => apply_filters('rcp_settings_emails',
			array(
				'general_email_header' => array(
					'id' => 'general_email_header',
					'name' => __( 'General Settings', 'rcp' ),
					'desc' => '',
					'type' => 'header'
				),
				'from_name' => array(
					'id' => 'from_name',
					'name' => __( 'From Name', 'rcp' ),
					'desc' => __( 'The name that emails come from. This is usually the name of your business.', 'rcp' ),
					'type' => 'text',
					'std' => get_bloginfo( 'name' )
				),
				'from_email' => array(
					'id' => 'from_email',
					'name' => __( 'From Email', 'rcp' ),
					'desc' => __( 'The email address that emails are sent from.', 'rcp' ),
					'type' => 'text',
					'std' => get_bloginfo( 'admin_email' )
				),
				'email_template_tags' => array(
					'id' => 'email_template_tags',
					'name' => __( 'Available Template Tags', 'rcp' ),
					'desc' => rcp_display_email_tags(),
					'type' => 'descriptive_text'
				),
				'active_email_header' => array(
					'id' => 'active_email_header',
					'name' => __( 'Active Subscription Email', 'rcp' ),
					'desc' => '',
					'type' => 'header'
				),
				'disable_active_email' => array(
					'id' => 'disable_active_email',
					'name' => __( 'Disabled', 'rcp' ),
					'desc' => __( 'Check this to disable the email sent out when a member becomes active.', 'rcp' ),
					'type' => 'checkbox'
				),
				'active_subject' => array(
					'id' => 'active_subject',
					'name' => __( 'Subject', 'rcp' ),
					'desc' => __( 'The subject line for the email sent to users when their subscription becomes active.', 'rcp' ),
					'type' => 'text'
				),
				'active_email' => array(
					'id' => 'active_email',
					'name' => __( 'Email Body', 'rcp' ),
					'desc' => __( 'This is the email message that is sent to users when their subscription becomes active.', 'rcp' ),
					'type' => 'textarea'
				),
				'cancelled_email_header' => array(
					'id' => 'cancelled_email_header',
					'name' => __( 'Cancelled Subscription Email', 'rcp' ),
					'desc' => '',
					'type' => 'header'
				),
				'disable_cancelled_email' => array(
					'id' => 'disable_cancelled_email',
					'name' => __( 'Disabled', 'rcp' ),
					'desc' => __( 'Check this to disable the email sent out when a member is cancelled.', 'rcp' ),
					'type' => 'checkbox'
				),
				'cancelled_subject' => array(
					'id' => 'cancelled_subject',
					'name' => __( 'Subject', 'rcp' ),
					'desc' => __( 'The subject line for the email sent to users when their subscription is cancelled.', 'rcp' ),
					'type' => 'text'
				),
				'cancelled_email' => array(
					'id' => 'cancelled_email',
					'name' => __( 'Email Body', 'rcp' ),
					'desc' => __( 'This is the email message that is sent to users when their subscription is cancelled.', 'rcp' ),
					'type' => 'textarea'
				),
				'expired_email_header' => array(
					'id' => 'expired_email_header',
					'name' => __( 'Expired Subscription Email', 'rcp' ),
					'desc' => '',
					'type' => 'header'
				),
				'disable_expired_email' => array(
					'id' => 'disable_expired_email',
					'name' => __( 'Disabled', 'rcp' ),
					'desc' => __( 'Check this to disable the email sent out when a member expires.', 'rcp' ),
					'type' => 'checkbox'
				),
				'expired_subject' => array(
					'id' => 'expired_subject',
					'name' => __( 'Subject', 'rcp' ),
					'desc' => __( 'The subject line for the email sent to users when their subscription is expired.', 'rcp' ),
					'type' => 'text'
				),
				'expired_email' => array(
					'id' => 'expired_email',
					'name' => __( 'Email Body', 'rcp' ),
					'desc' => __( 'This is the email message that is sent to users when their subscription is expired.', 'rcp' ),
					'type' => 'textarea'
				),
				'renewal_email_header' => array(
					'id' => 'renewal_email_header',
					'name' => __( 'Expiring Soon Email', 'rcp' ),
					'desc' => '',
					'type' => 'header'
				),
				'renewal_subject' => array(
					'id' => 'renewal_subject',
					'name' => __( 'Subject', 'rcp' ),
					'desc' => __( 'The subject line for the email sent to users before their subscription expires.', 'rcp' ),
					'type' => 'text'
				),
				'renewal_notice_email' => array(
					'id' => 'renewal_notice_email',
					'name' => __( 'Email Body', 'rcp' ),
					'desc' => __( 'This is the email message that is sent to users before their subscription expires to encourage them to renew.', 'rcp' ),
					'type' => 'textarea'
				),
				'renewal_reminder_period' => array(
					'id' => 'renewal_reminder_period',
					'name' => __( 'Reminder Period', 'rcp' ),
					'desc' => __( 'When should the renewal reminder be sent?', 'rcp' ),
					'type' => 'select',
					'options' => rcp_get_renewal_reminder_periods()
				),
				'free_email_header' => array(
					'id' => 'free_email_header',
					'name' => __( 'Free Subscription Email', 'rcp' ),
					'desc' => '',
					'type' => 'header'
				),
				'disable_free_email' => array(
					'id' => 'disable_free_email',
					'name' => __( 'Disabled', 'rcp' ),
					'desc' => __( 'Check this to disable the email sent out when a free member registers.', 'rcp' ),
					'type' => 'checkbox'
				),
				'free_subject' => array(
					'id' => 'free_subject',
					'name' => __( 'Subject', 'rcp' ),
					'desc' => __( 'The subject line for the email sent to users when they sign up for a free membership.', 'rcp' ),
					'type' => 'text'
				),
				'free_email' => array(
					'id' => 'free_email',
					'name' => __( 'Email Body', 'rcp' ),
					'desc' => __( 'This is the email message that is sent to users when they sign up for a free account.', 'rcp' ),
					'type' => 'textarea'
				),
				'trial_email_header' => array(
					'id' => 'trial_email_header',
					'name' => __( 'Trial Subscription Email', 'rcp' ),
					'desc' => '',
					'type' => 'header'
				),
				'disable_trial_email' => array(
					'id' => 'disable_trial_email',
					'name' => __( 'Disabled', 'rcp' ),
					'desc' => __( 'Check this to disable the email sent out when a member signs up with a trial.', 'rcp' ),
					'type' => 'checkbox'
				),
				'trial_subject' => array(
					'id' => 'trial_subject',
					'name' => __( 'Subject', 'rcp' ),
					'desc' => __( 'The subject line for the email sent to users when they sign up for a free trial.', 'rcp' ),
					'type' => 'text'
				),
				'trial_email' => array(
					'id' => 'trial_email',
					'name' => __( 'Email Body', 'rcp' ),
					'desc' => __( 'This is the email message that is sent to users when they sign up for a free trial.', 'rcp' ),
					'type' => 'textarea'
				),
				'payment_received_email_header' => array(
					'id' => 'payment_received_email_header',
					'name' => __( 'Payment Received Email', 'rcp' ),
					'desc' => '',
					'type' => 'header'
				),
				'disable_payment_received_email' => array(
					'id' => 'disable_payment_received_email',
					'name' => __( 'Disabled', 'rcp' ),
					'desc' => __( 'Check this to disable the email sent out when a payment is received.', 'rcp' ),
					'type' => 'checkbox'
				),
				'payment_received_subject' => array(
					'id' => 'payment_received_subject',
					'name' => __( 'Subject', 'rcp' ),
					'desc' => __( 'The subject line for the email sent to users upon a successful payment being received.', 'rcp' ),
					'type' => 'text'
				),
				'payment_received_email' => array(
					'id' => 'payment_received_email',
					'name' => __( 'Email Body', 'rcp' ),
					'desc' => __( 'This is the email message that is sent to users after a payment has been received from them.', 'rcp' ),
					'type' => 'textarea'
				),
				'new_user_notices_header' => array(
					'id' => 'new_user_notices_header',
					'name' => __( 'New User Notifications', 'rcp' ),
					'desc' => '',
					'type' => 'header'
				),
				'disable_new_user_notices' => array(
					'id' => 'disable_new_user_notices',
					'name' => __( 'Disabled', 'rcp' ),
					'desc' => __( 'Check this option if you do NOT want to receive emails when new users signup.', 'rcp' ),
					'type' => 'checkbox'
				)
			)
		),
		/** Invoice Settings */
		'invoices' => apply_filters('rcp_settings_invoices',
			array(
				'invoice_logo' => array(
					'id' => 'invoice_logo',
					'name' => __( 'Choose Logo', 'rcp' ),
					'desc' => __( 'Upload a logo to display on the invoices.', 'rcp' ),
					'type' => 'upload'
				),
				'invoice_company' => array(
					'id' => 'invoice_company',
					'name' => __( 'Company Name', 'rcp' ),
					'desc' => __( 'Enter the company name that will be shown on the invoice. This is only displayed if no logo image is uploaded above.', 'rcp' ),
					'type' => 'text'
				),
				'invoice_name' => array(
					'id' => 'invoice_name',
					'name' => __( 'Name', 'rcp' ),
					'desc' => __( 'Enter the personal name that will be shown on the invoice.', 'rcp' ),
					'type' => 'text'
				),
				'invoice_address' => array(
					'id' => 'invoice_address',
					'name' => __( 'Address Line 1', 'rcp' ),
					'desc' => __( 'Enter the first address line that will appear on the invoice.', 'rcp' ),
					'type' => 'text'
				),
				'invoice_address_2' => array(
					'id' => 'invoice_address_2',
					'name' => __( 'Address Line 2', 'rcp' ),
					'desc' => __( 'Enter the second address line that will appear on the invoice.', 'rcp' ),
					'type' => 'text'
				),
				'invoice_city_state_zip' => array(
					'id' => 'invoice_city_state_zip',
					'name' => __( 'City, State, and Zip', 'rcp' ),
					'desc' => __( 'Enter the city, state and zip/postal code that will appear on the invoice.', 'rcp' ),
					'type' => 'text'
				),
				'invoice_email' => array(
					'id' => 'invoice_email',
					'name' => __( 'Email', 'rcp' ),
					'desc' => __( 'Enter the email address that will appear on the invoice.', 'rcp' ),
					'type' => 'text'
				),
				'invoice_header' => array(
					'id' => 'invoice_header',
					'name' => __( 'Header Text','rcp' ),
					'desc' => __( 'Enter the message you would like to be shown on the header of the invoice.', 'rcp' ),
					'type' => 'text'
				),
				'invoice_notes' => array(
					'id' => 'invoice_notes',
					'name' => __( 'Notes', 'rcp' ),
					'desc' => __( 'Enter additional notes you would like displayed below the invoice totals.', 'rcp' ),
					'type' => 'textarea'
				),
				'invoice_footer' => array(
					'id' => 'invoice_footer',
					'name' => __( 'Footer Text', 'rcp' ),
					'desc' => __( 'Enter the message you would like to be shown on the footer of the invoice.', 'rcp' ),
					'type' => 'text'
				),
				'invoice_enable_char_support' => array(
					'id' => 'invoice_enable_char_support',
					'name' => __( 'Characters not displaying correctly?', 'rcp' ),
					'desc' => __( 'Check to enable the Free Sans/Free Serif font replacing Open Sans/Helvetica/Times. Only do this if you have characters which do not display correctly (e.g. Greek characters)', 'rcp' ),
					'type' => 'checkbox'
				)
			)
		),
		/** Extension Settings */
		'extensions' => apply_filters('rcp_settings_extensions',
			array()
		),
		'licenses' => apply_filters('rcp_settings_licenses',
			array(
				'license_key' => array(
					'id' => 'license_key',
					'name' => __( 'Restrict Content Pro', 'rcp' ),
					'desc' => sprintf( __( 'Enter license key for Restrict Content Pro. This is required for automatic updates and %s.', 'rcp' ), '<a href="http://pippinsplugins.com/plugin-support">' . __( 'support', 'rcp' ) . '</a>' ),
					'type' => 'license_key'
				)
			)
		),
		/** Misc Settings */
		'misc' => apply_filters('rcp_settings_misc',
			array(
				'hide_premium' => array(
					'id' => 'hide_premium',
					'name' => __( 'Hide Premium Posts', 'rcp' ),
					'desc' => __( 'Check this to hide all premium posts from queries when user is not logged in. Note, this will only hide posts that have the "Paid Only?" checkbox checked.', 'rcp' ),
					'type' => 'checkbox'
				),
				'redirect_from_premium' => array(
					'id' => 'redirect_from_premium',
					'name' => __( 'Redirect Page', 'rcp' ),
					'desc' => __( 'This is the page non-subscribed users are redirected to when attempting to access a premium post or page.', 'rcp' ),
					'type' => 'select',
					'options' => rcp_get_pages()
				),
				'hijack_login_url' => array(
					'id' => 'hijack_login_url',
					'name' => __( 'Redirect Default Login URL', 'rcp' ),
					'desc' => __( 'Check this to force the default login URL to redirect to the page specified below.', 'rcp' ),
					'type' => 'checkbox'
				),
				'login_redirect' => array(
					'id' => 'login_redirect',
					'name' => __( 'Login Page', 'rcp' ),
					'desc' => __( 'This is the page the default login URL redirects to, if the option above is checked. This should be the page that contains the [login_form] short code.', 'rcp' ),
					'type' => 'select',
					'options' => rcp_get_pages()
				),
				'no_login_sharing' => array(
					'id' => 'no_login_sharing',
					'name' => __( 'Prevent Account Sharing', 'rcp' ),
					'desc' => __( 'Check this if you\'d like to prevent multiple users from logging into the same account simultaneously.', 'rcp' ),
					'type' => 'checkbox'
				),
				'email_ipn_reports' => array(
					'id' => 'email_ipn_reports',
					'name' => __( 'Email IPN Reports', 'rcp' ),
					'desc' => __( 'Check this to send an email each time an IPN request is made with PayPal. The email will contain a list of all data sent. This is useful for debugging in the case that something is not working with the PayPal integration.', 'rcp' ),
					'type' => 'checkbox'
				),
				'disable_css' => array(
					'id' => 'disable_css',
					'name' => __( 'Disable Form CSS', 'rcp' ),
					'desc' => __( 'Check this to disable all included form styling.', 'rcp' ),
					'type' => 'checkbox'
				),
				'enable_recaptcha' => array(
					'id' => 'enable_recaptcha',
					'name' => __( 'Enable reCaptcha', 'rcp' ),
					'desc' => __( 'Check this to enable reCaptcha on the registration form.', 'rcp' ),
					'type' => 'checkbox'
				),
				'recaptcha_public_key' => array(
					'id' => 'recaptcha_public_key',
					'name' => __( 'reCaptcha Site Key', 'rcp' ),
					'desc' => sprintf( __( 'This your own personal reCaptcha Site key. Go to %s then click on your domain (or add a new one) to find your site key.', 'rcp' ), '<a href="https://www.google.com/recaptcha/">' . __( 'your account', 'rcp' ) . '</a>' ),
					'type' => 'text'
				),
				'recaptcha_private_key' => array(
					'id' => 'recaptcha_private_key',
					'name' => __( 'reCaptcha Secret Key', 'rcp' ),
					'desc' => __( 'This your own personal reCaptcha Secret key.', 'rcp' ),
					'type' => 'text'
				)
			)
		)
	);

	return apply_filters( 'rcp_registered_settings', $rcp_settings );
}


/**
 * Add PayPal settings
 *
 * @since 2.5.0
 * @param array $settings The current gateway settings
 * @return array $settings The updated gateway settings
 */
function rcp_paypal_pro_settings( $settings ) {
	$paypal_settings = array(
		'paypal_header' => array(
			'id' => 'paypal_header',
			'name' => __( 'PayPal Settings', 'rcp' ),
			'desc' => '',
			'type' => 'header'
		),
		'paypal_email' => array(
			'id' => 'paypal_email',
			'name' => __( 'PayPal Address', 'rcp' ),
			'desc' => __( 'Enter your PayPal email address.', 'rcp' ),
			'type' => 'text'
		),
		'paypal_api_note' => array(
			'id' => 'paypal_api_note',
			'name' => __( 'PayPal API Credentials', 'rcp' ),
			'desc' => sprintf( __( 'The PayPal API credentials are required in order to use PayPal Express, PayPal Pro, and to support advanced subscription cancellation options in PayPal Standard. Test API credentials can be obtained at %s.', 'rcp' ), '<a href="http://docs.pippinsplugins.com/article/826-setting-up-paypal-sandbox-accounts" target="_blank">developer.paypal.com</a>' ),
			'type' => 'descriptive_text'
		)
	);

	if( ! function_exists( 'rcp_register_paypal_pro_express_gateway' ) ) {
		$new_settings = array(
			'test_paypal_api_username' => array(
				'id' => 'test_paypal_api_username',
				'name' => __( 'Test API Username', 'rcp' ),
				'desc' => __( 'Enter your test API username.', 'rcp' ),
				'type' => 'text'
			),
			'test_paypal_api_password' => array(
				'id' => 'test_paypal_api_password',
				'name' => __( 'Test API Password', 'rcp' ),
				'desc' => __( 'Enter your test API password.', 'rcp' ),
				'type' => 'text'
			),
			'test_paypal_api_signature' => array(
				'id' => 'test_paypal_api_signature',
				'name' => __( 'Test API Signature', 'rcp' ),
				'desc' => __( 'Enter your test API signature.', 'rcp' ),
				'type' => 'text'
			),
			'live_paypal_api_username' => array(
				'id' => 'live_paypal_api_username',
				'name' => __( 'Live API Username', 'rcp' ),
				'desc' => __( 'Enter your live API username.', 'rcp' ),
				'type' => 'text'
			),
			'live_paypal_api_password' => array(
				'id' => 'live_paypal_api_password',
				'name' => __( 'Live API Password', 'rcp' ),
				'desc' => __( 'Enter your live API password.', 'rcp' ),
				'type' => 'text'
			),
			'live_paypal_api_signature' => array(
				'id' => 'live_paypal_api_signature',
				'name' => __( 'Live API Signature', 'rcp' ),
				'desc' => __( 'Enter your live API signature.', 'rcp' ),
				'type' => 'text'
			)
		);

		$paypal_settings = array_merge( $paypal_settings, $new_settings );
	}

	$new_settings = array(
		'paypal_page_style' => array(
			'id' => 'paypal_page_style',
			'name' => __( 'PayPal Page Style', 'rcp' ),
			'desc' => __( 'Enter the PayPal page style name you wish to use, or leave blank for default.', 'rcp' ),
			'type' => 'text'
		),
		'disable_curl' => array(
			'id' => 'disable_curl',
			'name' => __( 'Disable cURL', 'rcp' ),
			'desc' => __( 'Only check this option if your host does not allow cURL.', 'rcp' ),
			'type' => 'checkbox'
		),
		'disable_ipn_verify' => array(
			'id' => 'disable_ipn_verify',
			'name' => __( 'Disable IPN Verification', 'rcp' ),
			'desc' => __( 'Only check this option if your members statuses are not getting changed to "active".', 'rcp' ),
			'type' => 'checkbox'
		)
	);

	return array_merge( $settings, $paypal_settings, $new_settings );
}
add_filter( 'rcp_settings_gateways', 'rcp_paypal_pro_settings' );


/**
 * Get an option
 *
 * Looks to see if the specified setting exists, returns default if not
 *
 * @since 2.5.0
 * @return mixed
 */
function rcp_get_option( $key = '', $default = false ) {
	global $rcp_options;

	$value = ! empty( $rcp_options[ $key ] ) ? $rcp_options[ $key ] : $default;
	$value = apply_filters( 'rcp_get_option', $value, $key, $default );

	return apply_filters( 'rcp_get_option_' . $key, $value, $key, $default );
}


/**
 * Update an option
 *
 * Updates an rcp setting value in both the db and the global variable.
 * Warning: Passing in an empty, false or null string value will remove
 *          the key from the rcp_options array.
 *
 * @since 2.5.0
 * @param string $key The Key to update
 * @param string|bool|int $value The value to set the key to
 * @return boolean True if updated, false if not.
 */
function rcp_update_option( $key = '', $value = false ) {
	// If no key, exit
	if ( empty( $key ) ){
		return false;
	}

	if ( empty( $value ) ) {
		$remove_option = rcp_delete_option( $key );
		return $remove_option;
	}

	// First let's grab the current settings
	$options = get_option( 'rcp_settings' );

	// Let's let devs alter that value coming in
	$value = apply_filters( 'rcp_update_option', $value, $key );

	// Next let's try to update the value
	$options[ $key ] = $value;
	$did_update = update_option( 'rcp_settings', $options );

	// If it updated, let's update the global variable
	if ( $did_update ){
		global $rcp_options;
		$rcp_options[ $key ] = $value;
	}

	return $did_update;
}


/**
 * Remove an option
 *
 * Removes an rcp setting value in both the db and the global variable.
 *
 * @since 2.5.0
 * @param string $key The Key to delete
 * @return boolean True if updated, false if not.
 */
function rcp_delete_option( $key = '' ) {
	// If no key, exit
	if ( empty( $key ) ){
		return false;
	}

	// First let's grab the current settings
	$options = get_option( 'rcp_settings' );

	// Next let's try to update the value
	if( isset( $options[ $key ] ) ) {
		unset( $options[ $key ] );
	}

	$did_update = update_option( 'rcp_settings', $options );

	// If it updated, let's update the global variable
	if ( $did_update ){
		global $rcp_options;
		$rcp_options = $options;
	}

	return $did_update;
}


/**
 * Get Settings
 *
 * Retrieves all plugin settings
 *
 * @since 2.5.0
 * @return array RCP settings
 */
function rcp_get_settings() {
	$settings = get_option( 'rcp_settings' );

	if( empty( $settings ) ) {
		update_option( 'rcp_settings', array() );
	}

	return apply_filters( 'rcp_get_settings', $settings );
}


/**
 * Add all settings sections and fields
 *
 * @since 2.5.0
 * @return void
 */
function rcp_register_settings() {
	if ( false == get_option( 'rcp_settings' ) ) {
		add_option( 'rcp_settings' );
	}

	foreach( rcp_get_registered_settings() as $tab => $settings ) {

		add_settings_section(
			'rcp_settings_' . $tab,
			__return_null(),
			'__return_false',
			'rcp_settings_' . $tab
		);

		foreach ( $settings as $option ) {
			$name = isset( $option['name'] ) ? $option['name'] : '';

			add_settings_field(
				'rcp_settings[' . $option['id'] . ']',
				$name,
				function_exists( 'rcp_' . $option['type'] . '_callback' ) ? 'rcp_' . $option['type'] . '_callback' : 'rcp_missing_callback',
				'rcp_settings_' . $tab,
				'rcp_settings_' . $tab,
				array(
					'section'     => $tab,
					'id'          => isset( $option['id'] )          ? $option['id']          : null,
					'desc'        => ! empty( $option['desc'] )      ? $option['desc']        : '',
					'name'        => isset( $option['name'] )        ? $option['name']        : null,
					'size'        => isset( $option['size'] )        ? $option['size']        : null,
					'options'     => isset( $option['options'] )     ? $option['options']     : '',
					'std'         => isset( $option['std'] )         ? $option['std']         : '',
					'min'         => isset( $option['min'] )         ? $option['min']         : null,
					'max'         => isset( $option['max'] )         ? $option['max']         : null,
					'step'        => isset( $option['step'] )        ? $option['step']        : null,
					'chosen'      => isset( $option['chosen'] )      ? $option['chosen']      : null,
					'placeholder' => isset( $option['placeholder'] ) ? $option['placeholder'] : null,
					'multiple'    => isset( $option['multiple'] )    ? $option['multiple']    : null,
					'allow_blank' => isset( $option['allow_blank'] ) ? $option['allow_blank'] : true,
					'readonly'    => isset( $option['readonly'] )    ? $option['readonly']    : false,
					'buttons'     => isset( $option['buttons'] )     ? $option['buttons']     : null,
					'wpautop'     => isset( $option['wpautop'] )     ? $option['wpautop']     : null,
					'teeny'       => isset( $option['teeny'] )       ? $option['teeny']       : null
				)
			);
		}
	}

	// Creates our settings in the options table
	register_setting( 'rcp_settings', 'rcp_settings', 'rcp_settings_sanitize' );
}
add_action('admin_init', 'rcp_register_settings');


/**
 * Settings Sanitization
 *
 * Adds a settings error (for the updated message)
 * At some point this will validate input
 *
 * @since 2.5.0
 *
 * @param array $input The value inputted in the field
 * @return string $input Sanitizied value
 */
function rcp_settings_sanitize( $input = array() ) {
	global $rcp_options;

	if ( empty( $_POST['_wp_http_referer'] ) ) {
		return $input;
	}

	parse_str( $_POST['_wp_http_referer'], $referrer );

	$settings = rcp_get_registered_settings();
	$tab      = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';

	$input = $input ? $input : array();
	$input = apply_filters( 'rcp_settings_' . $tab . '_sanitize', $input );

	// Loop through each setting being saved and pass it through a sanitization filter
	foreach ( $input as $key => $value ) {

		// Get the setting type (checkbox, select, etc)
		$type = isset( $settings[$tab][$key]['type'] ) ? $settings[$tab][$key]['type'] : false;

		if ( $type ) {
			// Field type specific filter
			$input[$key] = apply_filters( 'rcp_settings_sanitize_' . $type, $value, $key );
		}

		// General filter
		$input[$key] = apply_filters( 'rcp_settings_sanitize', $input[$key], $key );
	}

	// Loop through the whitelist and unset any that are empty for the tab being saved
	if ( ! empty( $settings[$tab] ) ) {
		foreach ( $settings[$tab] as $key => $value ) {

			// settings used to have numeric keys, now they have keys that match the option ID. This ensures both methods work
			if ( is_numeric( $key ) ) {
				$key = $value['id'];
			}

			if ( empty( $input[$key] ) ) {
				unset( $rcp_options[$key] );
			}
		}
	}

	// Merge our new settings with the existing
	$output = array_merge( $rcp_options, $input );

	add_settings_error( 'rcp-notices', '', __( 'Settings updated.', 'rcp' ), 'updated' );

	return $output;
}


/**
 * Misc Settings Sanitization
 *
 * @since 2.5.0
 * @param array $input The value inputted in the field
 * @return string $input Sanitizied value
 */
function rcp_settings_sanitize_misc( $input ) {
	// Make sure the [login_form] short code is on the redirect page. Users get locked out if it is not
	if( isset( $input['hijack_login_url'] ) ) {

		$page_id = absint( $input['login_redirect'] );
		$page    = get_post( $page_id );

		if( ! $page || 'page' != $page->post_type ) {
			unset( $input['hijack_login_url'] );
		}

		if(
			// Check for various login form short codes
			false === strpos( $page->post_content, '[login_form' ) &&
			false === strpos( $page->post_content, '[edd_login' ) &&
			false === strpos( $page->post_content, '[subscription_details' ) &&
			false === strpos( $page->post_content, '[login' )
		) {
			unset( $input['hijack_login_url'] );
		}

	}

	return $input;
}
add_filter( 'rcp_settings_misc_sanitize', 'rcp_settings_sanitize_misc' );


/**
 * License Settings Sanitization
 *
 * @since 2.5.0
 * @param array $input The value inputted in the field
 * @return string $input Sanitizied value
 */
function rcp_settings_sanitize_licenses( $input ) {
	if( empty( $input['license_key'] ) ) {
		delete_option( 'rcp_license_status' );
	}

	if( ! empty( $_POST['license_key_deactivate'] ) ) {
		rcp_deactivate_license();
	} elseif( ! empty( $input['license_key'] ) ) {
		rcp_activate_license();
	}

	return $input;
}
add_filter( 'rcp_settings_licenses_sanitize', 'rcp_settings_sanitize_licenses' );


/**
 * Sanitize text fields
 *
 * @since 2.5.0
 * @param array $input The field value
 * @return string $input Sanitizied value
 */
function rcp_sanitize_text_field( $input ) {
	return trim( $input );
}
add_filter( 'rcp_settings_sanitize_text', 'rcp_sanitize_text_field' );


/**
 * Header Callback
 *
 * Renders the header.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function rcp_header_callback( $args ) {
	echo '<hr/>';
}


/**
 * Checkbox Callback
 *
 * Renders checkboxes.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @return void
 */
function rcp_checkbox_callback( $args ) {
	global $rcp_options;

	$name = 'name="rcp_settings[' . $args['id'] . ']"';
	$checked = isset( $rcp_options[ $args['id'] ] ) ? checked( 1, $rcp_options[ $args['id'] ], false ) : '';
	$html = '<input type="checkbox" id="rcp_settings[' . $args['id'] . ']"' . $name . ' value="1" ' . $checked . '/>';
	$html .= '<label for="rcp_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


/**
 * Retrieve a list of all published pages
 *
 * On large sites this can be expensive, so only load if on the settings page or $force is set to true
 *
 * @since 1.9.5
 * @param bool $force Force the pages to be loaded even if not on settings
 * @return array $pages_options An array of the pages
 */
function rcp_get_pages( $force = false ) {

	$pages_options = array( '' => '' ); // Blank option

	if( ( ! isset( $_GET['page'] ) || 'rcp-settings' != $_GET['page'] ) && ! $force ) {
		return $pages_options;
	}

	$pages = get_pages();
	if ( $pages ) {
		foreach ( $pages as $page ) {
			$pages_options[ $page->ID ] = $page->post_title;
		}
	}

	return $pages_options;
}


/**
 * Color picker Callback
 *
 * Renders color picker fields.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @return void
 */
function rcp_color_callback( $args ) {
	global $rcp_options;

	if ( isset( $rcp_options[ $args['id'] ] ) ) {
		$value = $rcp_options[ $args['id'] ];
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	$default = isset( $args['std'] ) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="text" class="rcp-color-picker" id="rcp_settings[' . $args['id'] . ']" name="rcp_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '" data-default-color="' . esc_attr( $default ) . '" />';
	$html .= '<label for="rcp_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


/**
 * Descriptive text callback.
 *
 * Renders descriptive text onto the settings field.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function rcp_descriptive_text_callback( $args ) {
	echo wp_kses_post( $args['desc'] );
}


/**
 * Gateways Callback
 *
 * Renders gateways fields.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the EDD Options
 * @return void
 */
function rcp_gateways_callback( $args ) {
	global $rcp_options;

	foreach ( $args['options'] as $key => $option ) :
		if ( isset( $rcp_options['gateways'][ $key ] ) )
			$enabled = '1';
		else
			$enabled = null;

		echo '<input name="rcp_settings[' . $args['id'] . '][' . $key . ']"" id="rcp_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="1" ' . checked('1', $enabled, false) . '/>&nbsp;';
		echo '<label for="rcp_settings[' . $args['id'] . '][' . $key . ']">' . $option['admin_label'] . '</label><br/>';
	endforeach;
}


/**
 * Multicheck Callback
 *
 * Renders multiple checkboxes.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @return void
 */
function rcp_multicheck_callback( $args ) {
	global $rcp_options;

	if ( ! empty( $args['options'] ) ) {
		foreach( $args['options'] as $key => $option ):
			if( isset( $rcp_options[$args['id']][$key] ) ) { $enabled = $option; } else { $enabled = NULL; }
			echo '<input name="rcp_settings[' . $args['id'] . '][' . $key . ']" id="rcp_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked($option, $enabled, false) . '/>&nbsp;';
			echo '<label for="rcp_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
		endforeach;
		echo '<p class="description">' . $args['desc'] . '</p>';
	}
}


/**
 * Number Callback
 *
 * Renders number fields.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @return void
 */
function rcp_number_callback( $args ) {
	global $rcp_options;

	if ( isset( $rcp_options[ $args['id'] ] ) ) {
		$value = $rcp_options[ $args['id'] ];
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	$name = 'name="rcp_settings[' . $args['id'] . ']"';
	$max  = isset( $args['max'] ) ? $args['max'] : 999999;
	$min  = isset( $args['min'] ) ? $args['min'] : 0;
	$step = isset( $args['step'] ) ? $args['step'] : 1;
	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

	$html = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $size . '-text" id="rcp_settings[' . $args['id'] . ']" ' . $name . ' value="' . esc_attr( stripslashes( $value ) ) . '"/>';
	$html .= '<label for="rcp_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


/**
 * Password Callback
 *
 * Renders password fields.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @return void
 */
function rcp_password_callback( $args ) {
	global $rcp_options;

	if ( isset( $rcp_options[ $args['id'] ] ) ) {
		$value = $rcp_options[ $args['id'] ];
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="password" class="' . $size . '-text" id="rcp_settings[' . $args['id'] . ']" name="rcp_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<label for="rcp_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


/**
 * Radio Callback
 *
 * Renders radio boxes.
 *
 * @since 1.3.3
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @return void
 */
function rcp_radio_callback( $args ) {
	global $rcp_options;

	if( ! empty( $args['options'] ) ) {
		foreach ( $args['options'] as $key => $option ) :
			$checked = false;

			if ( isset( $rcp_options[ $args['id'] ] ) && $rcp_options[ $args['id'] ] == $key )
				$checked = true;
			elseif( isset( $args['std'] ) && $args['std'] == $key && ! isset( $rcp_options[ $args['id'] ] ) )
				$checked = true;

			echo '<input name="rcp_settings[' . $args['id'] . ']"" id="rcp_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked(true, $checked, false) . '/>&nbsp;';
			echo '<label for="rcp_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
		endforeach;
	}

	echo '<p class="description">' . $args['desc'] . '</p>';
}


/**
 * Rich Editor Callback
 *
 * Renders rich editor fields.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @global $wp_version WordPress Version
 */
function rcp_rich_editor_callback( $args ) {
	global $rcp_options, $wp_version;

	if ( isset( $rcp_options[ $args['id'] ] ) ) {
		$value = $rcp_options[ $args['id'] ];

		if( empty( $args['allow_blank'] ) && empty( $value ) ) {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	$rows    = isset( $args['size'] )    ? $args['size']    : 20;
	$wpautop = isset( $args['wpautop'] ) ? $args['wpautop'] : true;
	$buttons = isset( $args['buttons'] ) ? $args['buttons'] : true;
	$teeny   = isset( $args['teeny'] )   ? $args['teeny']   : false;

	if ( $wp_version >= 3.3 && function_exists( 'wp_editor' ) ) {
		ob_start();
		wp_editor( stripslashes( $value ), 'rcp_settings_' . $args['id'], array( 'textarea_name' => 'rcp_settings[' . $args['id'] . ']', 'textarea_rows' => $rows, 'wpautop' => $wpautop, 'media_buttons' => $buttons, 'teeny' => $teeny ) );
		$html = ob_get_clean();
	} else {
		$html = '<textarea class="large-text" rows="10" id="rcp_settings[' . $args['id'] . ']" name="rcp_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
	}

	$html .= '<br/><label for="rcp_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


/**
 * Select Callback
 *
 * Renders select fields.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @return void
 */
function rcp_select_callback($args) {
	global $rcp_options;

	if ( isset( $rcp_options[ $args['id'] ] ) ) {
		$value = $rcp_options[ $args['id'] ];
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	if ( isset( $args['placeholder'] ) ) {
		$placeholder = $args['placeholder'];
	} else {
		$placeholder = '';
	}

	if ( isset( $args['chosen'] ) ) {
		$chosen = 'class="rcp-chosen"';
	} else {
		$chosen = '';
	}

	if( isset( $args['multiple'] ) && $args['multiple'] === true ) {
		$html = '<select id="rcp_settings[' . $args['id'] . ']" name="rcp_settings[' . $args['id'] . '][]" ' . $chosen . 'data-placeholder="' . $placeholder . '" multiple="multiple" />';
	} else {
		$html = '<select id="rcp_settings[' . $args['id'] . ']" name="rcp_settings[' . $args['id'] . ']" ' . $chosen . 'data-placeholder="' . $placeholder . '" />';
	}

	foreach ( $args['options'] as $option => $name ) {
		if( isset( $args['multiple'] ) && $args['multiple'] === true ) {
			if( is_array( $value ) ) {
				$selected = ( in_array( $option, $value ) ? 'selected="selected"' : '' );
			} else {
				$selected = '';
			}
		} else {
			if( is_string( $value ) ) {
				$selected = selected( $option, $value, false );
			} else {
				$selected = '';
			}
		}

		$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
	}

	$html .= '</select>&nbsp;';
	$html .= '<label for="rcp_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


/**
 * Text Callback
 *
 * Renders text fields.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @return void
 */
function rcp_text_callback( $args ) {
	global $rcp_options;

	if ( isset( $rcp_options[ $args['id'] ] ) ) {
		$value = $rcp_options[ $args['id'] ];
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	$name = 'name="rcp_settings[' . $args['id'] . ']"';
	$readonly = $args['readonly'] === true ? ' readonly="readonly"' : '';
	$size     = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

	$html     = '<input type="text" class="' . $size . '-text" id="rcp_settings[' . $args['id'] . ']"' . $name . ' value="' . esc_attr( stripslashes( $value ) ) . '"' . $readonly . '/>';
	$html    .= '<label for="rcp_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


/**
 * Textarea Callback
 *
 * Renders textarea fields.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @return void
 */
function rcp_textarea_callback( $args ) {
	global $rcp_options;

	if ( isset( $rcp_options[ $args['id'] ] ) ) {
		$value = $rcp_options[ $args['id'] ];
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	$html = '<textarea class="large-text" cols="50" rows="5" id="rcp_settings[' . $args['id'] . ']" name="rcp_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
	$html .= '<label for="rcp_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


/**
 * Upload Callback
 *
 * Renders upload fields.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @return void
 */
function rcp_upload_callback( $args ) {
	global $rcp_options;

	if ( isset( $rcp_options[ $args['id'] ] ) ) {
		$value = $rcp_options[$args['id']];
	} else {
		$value = isset($args['std']) ? $args['std'] : '';
	}

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="text" class="' . $size . '-text" id="rcp_settings[' . $args['id'] . ']" name="rcp_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
	$html .= '<span>&nbsp;<input type="button" class="rcp_settings_upload_button button-secondary" value="' . __( 'Upload File', 'rcp' ) . '"/></span>';
	$html .= '<label for="rcp_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


/**
 * Missing Callback
 *
 * If a function is missing for settings callbacks alert the user.
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function rcp_missing_callback($args) {
	printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'rcp' ), $args['id'] );
}


/**
 * Hook Callback
 *
 * Adds a do_action() hook in place of the field
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function rcp_hook_callback( $args ) {
	do_action( 'rcp_' . $args['id'], $args );
}


/**
 * Registers the license field callback for Software Licensing
 *
 * @since 2.5.0
 * @param array $args Arguments passed by the setting
 * @global $rcp_options Array of all the RCP Options
 * @return void
 */
if ( ! function_exists( 'rcp_license_key_callback' ) ) {
	function rcp_license_key_callback( $args ) {
		global $rcp_options;

		if ( isset( $rcp_options[ $args['id'] ] ) ) {
			$value = $rcp_options[ $args['id'] ];
		} else {
			$value = isset( $args['std'] ) ? $args['std'] : '';
		}

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html = '<input type="text" class="' . $size . '-text" id="rcp_settings[' . $args['id'] . ']" name="rcp_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';

		if ( 'valid' == rcp_check_license() ) {
			$html .= '<input type="submit" class="button-secondary" name="' . $args['id'] . '_deactivate" value="' . __( 'Deactivate License',  'rcp' ) . '"/>';
		}
		$html .= '<label for="rcp_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

		wp_nonce_field( $args['id'] . '-nonce', $args['id'] . '-nonce' );

		echo $html;
	}
}