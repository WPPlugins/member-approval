<?php

if(!defined('ABSPATH'))
	exit('Don\'t access source files directly!');

/**
 * Class member_approval_user_edits
 *
 * For the ability to Approve members through the Edit User panel.
 */
class member_approval_user_edits {
	/**
	 * @param $user WP_User A WP_User Object passed from WordPress
	 */
	public static function add_field($user) {
		global $current_user;
		if(!current_user_can('edit_user', $user->ID) || $current_user->ID === $user->ID) // Make sure this User is allowed to be doing this
			return;

		$user_needs_approval = get_user_meta($user->ID, 'requires_approval', TRUE);

		if($user_needs_approval) {

			?>
			<table class="form-table">
				<tr>
					<th>This User is disabled.<br /><strong>Activate User now?</strong></th>
					<td>
						<input type="checkbox" name="member_approval_approved" id="member_approval_approved" value="yes" /> <label for="member_approval_approved">Yes, Approve/Activate this User</label><br />
						<span class="description">Leave this checkbox empty to keep the User from logging in.</span>
					</td>
				</tr>
			</table>
		<?php
		}
		elseif(!$user->has_cap('administrator')) { // Make sure this isn't an admin
			?>
			<table class="form-table">
				<tr>
					<th>This User is currently activated.<br /><strong>Do you want to deactivate them?</strong></th>
					<td>
						<input type="checkbox" name="member_approval_deactivate" id="member_approval_deactivate" value="yes" /> <label for="member_approval_deactivate">Yes, deactivate this User</label><br />
						<span class="description">If you check this, this User will not be able to log in again until you reactivate them.</span>
					</td>
				</tr>
			</table>
		<?php
		}
	}

	/**
	 * @param $user_id int The User's ID, as passed from the `edit_user_profile_update` action hook
	 */
	public static function save_field($user_id) {
		global $current_user;
		if(!current_user_can('edit_user', $user_id) || $current_user->ID === $user_id) // Make sure this User is allowed to be doing this
			return;

		if(isset($_POST['member_approval_approved']) && get_user_meta($user_id, 'requires_approval', TRUE)) {
			delete_user_meta($user_id, 'requires_approval');
			member_approval_notifications::approved($user_id);
		}

		elseif(isset($_POST['member_approval_deactivate']) && !get_user_meta($user_id, 'requires_approval', TRUE))
			member_approval_restriction::maybe_disable_user($user_id, TRUE); // Deactivate this User
	}
}