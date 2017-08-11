<?php

if(!defined('ABSPATH'))
	exit('Don\'t access source files directly!');

/**
 * Class member_approval_admin
 *
 * Admin-side "stuff"
 */
class member_approval_admin {
	/**
	 * Sets up the Options Page on the `admin_menu` hook
	 */
	public static function setup() {
		add_options_page('Member Approval Settings', 'Member Approval', 'manage_options', 'member-approval', array('member_approval_admin', 'page'));
	}

	/**
	 * Controls Options Page content
	 */
	public static function page() {
		if(!current_user_can('manage_options'))
			wp_die(__('You do not have sufficient permissions to access this page.'));

		if(isset($_POST['submit'])) {
			$opts = stripslashes_deep($_POST['gsettings']);

			foreach($opts as $key => $value) {
				if(is_string($value))
					$opts[$key] = trim($value);
			}

			update_option('member-approval-opts', $opts);
		}

		else
			$opts = get_site_option('member-approval-opts', FALSE);

		$roles = get_editable_roles();

		$defaults                     = array();
		$defaults['on-off']           = 'off';
		$defaults['applicable-roles'] = array('subscriber' => 'Subscriber');
		$defaults['message']          = 'Your account is awaiting approval. Please try again later.';
		$defaults['restriction']      = 'error';
		$defaults['redirect']         = '';

		$defaults['send-new-user-emails']      = 'no';
		$defaults['new-user-email']['subject'] = 'Your account is currently under review';
		$defaults['new-user-email']['body']    = 'Hello %%firstname%%, '."\n\n".
		                                         'Your account is currently under review. We\'ll let you know when we\'re done reviewing it.'."\n".
		                                         'Once your account is activated, you can log in here:'."\n\n".
		                                         site_url('/wp-login.php');

		$defaults['send-approval-email']       = 'no';
		$defaults['approval-email']['subject'] = 'Your account was approved!';
		$defaults['approval-email']['body']    = 'Hello %%firstname%%, '."\n\n".
		                                         'Your account was approved! You can log in here:'."\n\n".
		                                         site_url('/wp-login.php');

		if(!$opts) {
			add_site_option('member-approval-opts', $defaults, '', FALSE);

			$opts = $defaults;
		}

		$opts = array_merge($defaults, $opts); // For Users of older versions of the plugin
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2>Member Approval</h2>
			<div style="clear: both;"></div>
			<div id="member-approval-general-settings" class="member-approval-main-section">
				<form method="post" action="">
					<h3>General Settings</h3>
					<table class="form-table">
						<tbody>

						<tr>
							<th scope="row">
								<label for="gsettings[on-off]">New User Approval</label>
							</th>
							<td>
								<select name="gsettings[on-off]" id="approval-on-off">
									<option <?php if($opts['on-off'] === 'on') {
										echo 'selected="selected"';
									} ?> value="on">ON
									</option>
									<option <?php if($opts['on-off'] === 'off') {
										echo 'selected="selected"';
									} ?> value="off">OFF
									</option>
								</select>
								<p class="description">Make sure you have your settings done before turning this on.</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="gsettings[applicable-roles]">Applicable Roles</label>
							</th>
							<td>
								<?php
								foreach($roles as $slug => $data) {
									if($slug !== 'administrator' && $slug !== 'super-administrator') { // Administrators can't be blocked from logging in
										echo '<input type="checkbox" name="gsettings[applicable-roles]['.$slug.']" ';

										if(isset($opts['applicable-roles'][$slug]))
											echo 'checked="checked"';

										echo ' value="'.$slug.'"/> '.$data['name'].'<br />';
									}
								}
								?>
								<p class="description">Select all Roles that, when Users register at that Role, should require the User to be Approved to log in.</p>
								<p class="description">NOTE: If you don't select any Roles, Member Approval won't do anything.</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="gsettings[message]">Restriction Style</label>
							</th>
							<td>
								<input type="radio" name="gsettings[restriction]" value="error" <?php if($opts['restriction'] === 'error') {
									echo 'checked="checked"';
								} ?> id="gsettings-error" /> <label for="gsettings-error">Login Error Message</label>
								<p style="padding-left: 10px;">
									<input type="text" <?php echo 'value="'.$opts['message'].'"'; ?> name="gsettings[message]" class="regular-text ltr" autocomplete="off" /><br />
									<span class="description">This is the message that will show up for the User when they attempt to log in while not Approved.</span>
								</p>

								<input type="radio" name="gsettings[restriction]" value="redirect" <?php if($opts['restriction'] === 'redirect') {
									echo 'checked="checked"';
								} ?> id="gsettings-redirect" /> <label for="gsettings-redirect">Redirect</label>
								<p style="padding-left: 10px;">
									<input type="text" <?php echo 'value="'.$opts['redirect'].'"'; ?> name="gsettings[redirect]" class="regular-text ltr" autocomplete="off" /><br />
									<span class="description">Put the URL that you'd like to redirect to here. Relative URLs, like <code>/info/</code> work here, too.</span>
								</p>
							</td>
						</tr>

						</tbody>
					</table>

					<h3>Email Configuration</h3>
					These emails are extra emails that can be sent along with the default WordPress New User Emails to give extra information on the approval process. Totally optional.
					<table class="form-table">
						<tbody>
						<tr>
							<th scope="row">
								Send Additional New User Emails
							</th>
							<td>
								<select name="gsettings[send-new-user-emails]">
									<option value="no">NO</option>
									<option <?php if($opts['send-new-user-emails'] === 'yes') {
										echo 'selected="selected"';
									} ?> value="yes">YES
									</option>
								</select>
								<p class="description">If you turn these on, you can have Member Approval send information to both you and the registered User about the Approval process as they are disabled when they register.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								Registration Email Subject
							</th>
							<td>
								<input type="text" name="gsettings[new-user-email][subject]" value="<?php echo $opts['new-user-email']['subject']; ?>" class="regular-text ltr" />
								<p class="description">The Subject of the Email that's sent.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								Registration Email Message
							</th>
							<td>
								<textarea name="gsettings[new-user-email][body]" class="large-text code" rows="10" cols="50"><?php echo $opts['new-user-email']['body']; ?></textarea>
								<p class="description">You can use the <code>%%firstname%%</code>, <code>%%lastname%%</code>, and <code>%%fullname%%</code> Replacement Codes here to personalize this email a bit.</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								Send an Approval Email
							</th>
							<td>
								<select name="gsettings[send-approval-email]">
									<option value="no">NO</option>
									<option <?php if($opts['send-approval-email'] === 'yes') {
										echo 'selected="selected"';
									} ?> value="yes">YES
									</option>
								</select>
								<p class="description">If you turn this on, the User will be notified with they are Approved via the Edit User panel in the Dashboard.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								Registration Email Subject
							</th>
							<td>
								<input type="text" name="gsettings[approval-email][subject]" value="<?php echo $opts['approval-email']['subject']; ?>" class="regular-text ltr" />
								<p class="description">The Subject of the Email that's sent.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								Approval Email Message
							</th>
							<td>
								<textarea name="gsettings[approval-email][body]" class="large-text code" rows="10" cols="50"><?php echo $opts['approval-email']['body']; ?></textarea>
								<p class="description">You can use the <code>%%firstname%%</code>, <code>%%lastname%%</code>, and <code>%%fullname%%</code> Replacement Codes here to personalize this email a bit.</p>
							</td>
						</tr>
						</tbody>
					</table>
					<p class="submit">
						<input type="submit" name="submit" id="submit" class="button button-primary" value="Save All Changes">
					</p>
				</form>
			</div>
		</div>
	<?php
	}

	/**
	 * Adds the Settings link to the admin menu
	 *
	 * @param $links string
	 *
	 * @return string
	 */
	public static function settings_link($links) {
		$settings_link = '<a href="options-general.php?page=member-approval">Settings</a>';
		array_unshift($links, $settings_link);
		return $links;
	}
}
