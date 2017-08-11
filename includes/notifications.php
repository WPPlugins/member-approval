<?php

if(!defined('ABSPATH'))
	exit('Don\'t access source files directly!');

/**
 * Class member_approval_notifications
 */
class member_approval_notifications {
	/**
	 * @param $user WP_User WP_User object for the User
	 */
	public static function new_user($user) {
		$opts = get_site_option('member-approval-opts', FALSE);

		do_action('member_approval_before_new_user_email_sent', get_defined_vars());
		apply_filters('member_approval_new_user_email_vars', $opts, $user);

		$header = 'From: '.get_bloginfo('name').' <noreply@'.site_url().'>'."\r\n";

		if($opts['send-new-user-emails'] === 'yes') {
			$opts['new-user-email']['body'] = str_replace(array('%%firstname%%', '%%lastname%%', '%%fullname%%'), array($user->first_name, $user->last_name, $user->first_name.' '.$user->last_name), $opts['new-user-email']['body']);

			wp_mail($user->user_email, $opts['new-user-email']['subject'], $opts['new-user-email']['body'], $header); // New User's Email
			wp_mail(get_bloginfo('admin_email'), 'A New User needs to be Approved!', 'A new User on your WordPress site needs to be Approved!'."\n\n".'You can do that here: '."\n".site_url('/wp-admin/user-edit.php?user_id='.$user->ID), $header);
		}
	}

	/**
	 * @param $user_id int The User's ID
	 */
	public static function approved($user_id) {
		$opts = get_site_option('member-approval-opts', FALSE);

		$user = new WP_User($user_id);

		do_action('member_approval_before_approved_email_sent', get_defined_vars());
		apply_filters('member_approval_approval_email_vars', $opts, $user);

		$header = 'From: '.get_bloginfo('name').' <noreply@'.site_url().'>'."\r\n";

		if($opts['send-approval-email'] === 'yes') {
			$opts['new-user-email']['body'] = str_replace(array('%%firstname%%', '%%lastname%%', '%%fullname%%'), array($user->first_name, $user->last_name, $user->first_name.' '.$user->last_name), $opts['new-user-email']['body']);

			wp_mail($user->user_email, $opts['approval-email']['subject'], $opts['approval-email']['body'], $header); // Approval Notice
		}
	}
}