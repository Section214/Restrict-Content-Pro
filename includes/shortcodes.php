<?php

/*******************************************
* Restrict Content Short Codes
*******************************************/

// shortcode or restricting content to registered users and or user roles
function rcp_restrict_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'userlevel' => 'none',
		'message' => '',
		'paid' => false,
		'level' => 0,
		'subscription' => 'all'
	), $atts ) );

	global $rcp_options, $user_ID;

	if(strlen(trim($message)) > 0 ) {
		$teaser = $message;
	} elseif($paid) {
		$teaser = $rcp_options['paid_message'];
	} else {
		$teaser = $rcp_options['free_message'];
	}
		
	if($paid == true) {
		
		$has_access = false;
		if(rcp_is_active($user_ID) && rcp_user_has_access($user_ID, $level)) {
			$has_access = true;
			if($subscription != 'all') {
				if(rcp_get_subscription_id($user_ID) != $subscription) {
					$has_access = false;
				}
			}
		}

		if ($userlevel == 'admin' && current_user_can('switch_themes') && $has_access)
		{
			return do_shortcode(wpautop($content));
		}
		if ($userlevel == 'editor' && current_user_can('moderate_comments') && $has_access)
		{
			return do_shortcode(wpautop($content));
		}
		if ($userlevel == 'author' && current_user_can('upload_files') && $has_access)
		{
			return do_shortcode(wpautop($content));
		}
		if ($userlevel == 'contributor' && current_user_can('edit_posts') && $has_access)
		{
		 	return do_shortcode(wpautop($content));
		}
		if ($userlevel == 'subscriber' && current_user_can('read') && $has_access)
		{
		 	return do_shortcode(wpautop($content));
		}
		if ($userlevel == 'none' && is_user_logged_in() && $has_access)
		{
		 	return do_shortcode(wpautop($content));
		}
		else {
			return '<div class="rcp_restricted rcp_paid_only">' . rcp_format_teaser($teaser) . '</div>';
		}
		
	} else {
	
		$has_access = false;
		if(rcp_user_has_access($user_ID, $level)) {
			$has_access = true;
			if($subscription != 'all') {
				if(rcp_get_subscription_id($user_ID) != $subscription) {
					$has_access = false;
				}
			}
		}
	
		if ($userlevel == 'admin' && current_user_can('switch_themes') && $has_access) {
			return do_shortcode(wpautop($content));
		} elseif ($userlevel == 'editor' && current_user_can('moderate_comments') && $has_access) {
			return do_shortcode(wpautop($content));
		} elseif ($userlevel == 'author' && current_user_can('upload_files') && $has_access) {
			return do_shortcode(wpautop($content));
		} elseif ($userlevel == 'contributor' && current_user_can('edit_posts') && $has_access) {
		 	return do_shortcode(wpautop($content));
		} elseif ($userlevel == 'subscriber' && current_user_can('read') && $has_access) {
		 	return do_shortcode(wpautop($content));
		} elseif ($userlevel == 'none' && is_user_logged_in() && $has_access) {
		 	return do_shortcode(wpautop($content));
		} else {
			return '<div class="rcp_restricted">' . wpautop($teaser) . '</div>';
		}
	}
}
add_shortcode('restrict', 'rcp_restrict_shortcode');

// shows content only to active, paid users
function rcp_is_paid_user_shortcode($atts, $content = null ) {
	
	global $user_ID;
	
	if(rcp_is_active($user_ID)) {
		return do_shortcode($content);
	}
}
add_shortcode('is_paid', 'rcp_is_paid_user_shortcode');

// shows content only to logged-in free users, and can hide from paid
function rcp_is_free_user_shortcode($atts, $content = null ) {
	extract( shortcode_atts( array(
		'hide_from_paid' => true
	), $atts ) );
	
	global $user_ID;
	
	if($hide_from_paid) {
		if(!rcp_is_active($user_ID) && is_user_logged_in()) {
			return do_shortcode($content);
		}
	} elseif(is_user_logged_in()) {
		return do_shortcode($content);
	}
}
add_shortcode('is_free', 'rcp_is_free_user_shortcode');

function rcp_not_logged_in($atts, $content = null ) {
	if(!is_user_logged_in()) {
		return do_shortcode($content);
	}
}
add_shortcode('not_logged_in', 'rcp_not_logged_in');

// allows content to be shown to only users that don't have an active subscription
function rcp_is_not_paid($atts, $content = null ) {
	global $user_ID;
	if(rcp_is_active($user_ID))
		return;
	else 
		return do_shortcode($content);
		
}
add_shortcode('is_not_paid', 'rcp_is_not_paid');

// shows the display name of the currently logged-in user
function rcp_user_name($atts, $content = null ) {
	global $user_ID;
	if(is_user_logged_in()) {
		return get_userdata($user_ID)->display_name;
	}
		
}
add_shortcode('user_name', 'rcp_user_name');

// user registration login form
function rcp_registration_form($atts, $content = null ) {
	extract( shortcode_atts( array(
		'registered_message' => __('You are already registered and have an active subscription.', 'rcp')
	), $atts ) );
	
	global $user_ID;
	
	// only show the registration form to non-logged-in members
	if(!rcp_is_active($user_ID)) {
	
		global $rcp_options, $rcp_load_css, $rcp_load_scripts;
		
		// set this to true so the CSS and JS scripts are loaded
		$rcp_load_css = true;
		$rcp_load_scripts = true;
		
		// check to make sure user registration is enabled
		$registration_enabled = get_option('users_can_register');
	
		// only show the registration form if allowed
		if($registration_enabled) {
			$output = rcp_registration_form_fields();
		} else {
			$output = __('User registration is not enabled.', 'rcp');
		}
	} else {
		$output = $registered_message;
	}
	return $output;
}
add_shortcode('register_form', 'rcp_registration_form');

// user login form
function rcp_login_form($atts, $content = null ) {

	global $post;

	if (is_singular()) :
		$current_page = get_permalink($post->ID);
	else :
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") $pageURL .= "s";
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		else $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		$current_page = $pageURL;
	endif;

	extract( shortcode_atts( array(
		'redirect' => $current_page,
		'class' => 'rcp_form'
	), $atts ) );
	
	$output = '';
		
	global $rcp_load_css;
	
	// set this to true so the CSS is loaded
	$rcp_load_css = true;
	
	$output = rcp_login_form_fields(array('redirect' => $redirect, 'class' => $class));

	return $output;
}
add_shortcode('login_form', 'rcp_login_form');

// password reset form
function rcp_reset_password_form() {
	if(is_user_logged_in()) {
		
		global $rcp_options, $rcp_load_css, $rcp_load_scripts;
		// set this to true so the CSS is loaded
		$rcp_load_css = true;
		if(isset($rcp_options['front_end_validate']) && $rcp_options['front_end_validate']) {
			$rcp_load_scripts = true;
		}
		
		// get the password reset form fields
		$output = rcp_change_password_form();
		
		return $output;
	}
}
add_shortcode('password_form', 'rcp_reset_password_form');

// displays a list of premium posts
function rcp_list_paid_posts() {
	$paid_posts = rcp_get_paid_posts();
	$list = '';
	if($paid_posts) {
		$list .= '<ul class="rcp_paid_posts">';
		foreach($paid_posts as $post_id) {
			$list .= '<li><a href="' . get_permalink($post_id) . '">' . get_the_title($post_id) . '</a></li>';
		}
		$list .= '</ul>';
	}
	return $list;
}
add_shortcode('paid_posts', 'rcp_list_paid_posts');

// displays the current user's subscription details
function rcp_user_subscription_details($atts, $content = null ) {
	extract( shortcode_atts( array(
		'option' => ''
	), $atts ) );
	
	global $user_ID, $rcp_options;
	
	if(is_user_logged_in())	{
		$details = '<ul id="rcp_subscription_details">';
			$details .= '<li><span class="rcp_subscription_name">' . __('Subscription Level', 'rcp') . '</span><span class="rcp_sub_details_separator">:&nbsp;</span><span class="rcp_sub_details_current_level">' . rcp_get_subscription($user_ID) . '</span></li>';
			if( rcp_get_expiration_date($user_ID) ) {
				$details .= '<li><span class="rcp_sub_details_exp">' . __('Expiration Date', 'rcp') . '</span><span class="rcp_sub_details_separator">:&nbsp;</span><span class="rcp_sub_details_exp_date">' . rcp_get_expiration_date($user_ID) . '</span></li>';
			}			
			$details .= '<li><span class="rcp_sub_details_recurring">' . __('Recurring', 'rcp') . '</span><span class="rcp_sub_details_separator">:&nbsp;</span><span class="rcp_sub_is_recurring">';
			$details .= rcp_is_recurring($user_ID) ? __('yes', 'rcp') : __('no', 'rcp') . '</span></li>';
			$details .= '<li><span class="rcp_sub_details_status">' . __('Current Status', 'rcp') . '</span><span class="rcp_sub_details_separator">:&nbsp;</span><span class="rcp_sub_details_current_status">' . rcp_print_status($user_ID) . '</span></li>';
			if(rcp_is_expired($user_ID) || rcp_get_status($user_ID) == 'cancelled') {
				$details .= '<li><a href="' . get_permalink($rcp_options['registration_page']) . '" title="' . __('Renew your subscription', 'rcp') . '" class="rcp_sub_details_renew">' . __('Renew your subscription', 'rcp') . '</a></li>';
			} elseif(!rcp_is_active($user_ID)) {
				$details .= '<li><a href="' . get_permalink($rcp_options['registration_page']) . '" title="' . __('Upgrade your subscription', 'rcp') . '" class="rcp_sub_details_renew">' . __('Upgrade your subscription', 'rcp') . '</a></li>';
			}
			$details = apply_filters('rcp_subscription_details_list', $details);
		$details .= '</ul>';
		$details .= '<div class="rcp-payment-history">';
			$details .= '<h3 class="payment_history_header">' . __('Your Payment History', 'rcp') . '</h3>';
			$details .= rcp_print_user_payments($user_ID);
		$details .= '</div>';
		$details = apply_filters('rcp_subscription_details', $details);
	} else {
		$details = '<p>' . __('You must be logged in to view your subscription details', 'rcp') . '</p>';
	}
	return $details;
}
add_shortcode('subscription_details', 'rcp_user_subscription_details');

add_filter('widget_text', 'do_shortcode');