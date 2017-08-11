<?php

if(!defined('ABSPATH'))
	exit('Don\'t access source files directly!');

/**
 * Class member_approval_restriction
 *
 * Used for Restricting login access
 */
class member_approval_restriction {
	/**
	 * @param $user NULL|WP_User|WP_Error The current User Object WordPress is working with
	 *
	 * @return NULL|WP_User|WP_Error If the User is restricted from logging in, we'll return a WP_Error. Otherwise, it'll return whatever WordPress passed to us.
	 */
	public static function maybe_prevent_login($user) {
		// Lets make sure that this is an attempt we need to work with
		if(!is_a($user, 'WP_User') // Make sure we're looking at a WP_User and not an Error
		   || $user->has_cap('manage_options') // Don't want to prevent Admins/Super Admins from logging in
		   || apply_filters('member_approval_disable_login_prevent', FALSE) /* To allow the process to be cancelled through code */
		) {

			return $user; // Then we'll just return what we have.
		}

		$opts = get_site_option('member-approval-opts', FALSE);

		do_action('member_approval_before_maybe_prevent_login', get_defined_vars());
		apply_filters('member_approval_maybe_prevent_login_vars', $opts, $user);

		if(!empty($opts) && $opts['on-off'] === 'off')
			return $user; // We're not running right now.

		$user_needs_approval = apply_filters('member_approval_member_requires_approval', get_user_meta($user->ID, 'requires_approval', TRUE), $user); // If this meta key is set then we can't allow them to log in.

		if($user_needs_approval) {
			remove_all_actions('wp_login_failed'); // We don't want other plugins to record this as a failed login attempt. It only failed because the User isn't activated yet.

			if($opts['restriction'] === 'error' || empty($opts['restriction']) /* For anyone that had 130625 installed */ || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'))
				return new WP_Error('require_approval', apply_filters('member_approval_error_message', $opts['message']) /* You can change the error message this way */); // We'll use WP_Error to keep the User from logging in. This way they should also see what went wrong.

			elseif($opts['restriction'] === 'redirect' && !empty($opts['redirect'])) {
				if(strpos(strtolower($opts['redirect']), 'http://') === 0 || strpos(strtolower($opts['redirect']), 'https://') === 0 /* For HTTPS connections */) {
					wp_redirect($opts['redirect']);
					exit();
				}
				else {
					wp_redirect(site_url($opts['redirect']));
					exit();
				}
			}
		}

		return $user;
	}

	/**
	 * @param $user_id int The User's ID, as passed from user_register
	 * @param $force bool If set to TRUE, will force the User to be disabled unless they are an Administrator
	 *
	 * Used to add the meta key requires_approval to Users when they register, or when an Administrator deactivates an account
	 */
	public static function maybe_disable_user($user_id, $force = FALSE) {
		$user = new WP_User($user_id);

		if($user->has_cap('administrator'))
			return;

		if($force) {
			update_user_meta($user_id, 'requires_approval', TRUE); // If this meta key is set then we can't allow them to log in.
			return;
		}

		$opts = get_site_option('member-approval-opts', FALSE);

		if($opts['on-off'] === 'off')
			return; // We're not running right now

		$applicable_roles = $opts['applicable-roles'];

		foreach($user->roles as $role) {
			if($applicable_roles[$role]) { // Checking to see if User was created in a Role that requires Approval
				update_user_meta($user_id, 'requires_approval', TRUE); // If this meta key is set then we can't allow them to log in.
				member_approval_notifications::new_user($user);
			}
		}
	}

	/**
	 * This function will check to see if a logged-in User needs to be approved, and log them out if they do require approval.
	 *
	 * @Since 131009
	 */
	public static function maybe_logout_user() {
		global $current_user;
		get_currentuserinfo();

		if($current_user->has_cap('administrator') || !is_user_logged_in())
			return;

		$opts = get_site_option('member-approval-opts', FALSE);

		if(get_user_meta($current_user->ID, 'requires_approval')) {
			wp_logout();

			if($opts['restriction'] === 'redirect' && !empty($opts['redirect'])) {
				if(strpos(strtolower($opts['redirect']), 'http://') === 0 || strpos(strtolower($opts['redirect']), 'https://') === 0 /* For HTTPS connections */) {
					wp_redirect($opts['redirect']);
					exit();
				}
				else {
					wp_redirect(site_url($opts['redirect']));
					exit();
				}
			}
			// Otherwise we'll just let this go through silently.
			return; // return for uniformity.
		}
	}
}