=== Member Approval ===
Contributors: bruce-caldwell
Tags: members, deactivation, approval, activate, plugin, s2member, membership, emails, notifications
Requires at least: 3.2
Tested up to: 3.7.1
Stable tag: 131109
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=6ZPC8JXLBNAMU&lc=US&item_name=Member%20Approval&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted

Gives you the capability to create a very simple User Approval process with new signups on your site.

== Description ==

Now create customized notification emails for your Users that require approval on signup!

Member Approval is designed to make it super simple to get started with member approval, with the simplest method possible: preventing login.

Deactivate and activate/approve members directly from the User Edit menu, and sort your Users list by Approved to Not Approved members out-of-box.

Simple-to-use and effective. Let me  know if you have any issues with it. :-)

P.S.: This works best if you edit your New User Emails with a plugin such as s2Member, which this plugin was originally intended to be used with. :-)

== Installation ==

You can install Member Approval by downloading it through the WordPress Repository, or by uploading it to your Plugins directory via FTP.

After it's installed, activate it through your Plugins list.

== Frequently Asked Questions ==

= Will this plugin work with other login forms/widgets that I have? =

Yep. Member Approval hooks directly into the WordPress core login authentication filter, so any time someone is trying to log in a User they should be given an error if the User is not Approved.

= I'm using a plugin that tracks how many time a User fails to log in correctly, will this cause issues with it? =

It shouldn't. I made sure to keep processes that run on the `wp_login_failed` hook from firing when the User is being kept from logging in because they're not activated.

= Can I edit the message that's shown to Users when they're blocked from logging in? =

Yep. There's an option for that in the Settings page for the plugin.

= Is the plugin translatable? =

Currently the only part of the plugin that displays text on the frontend of your site is the error that shows up when the User is blocked from logging in, which is fully customizable. However, the backend of the plugin is not translatable yet, I'll get to that soon. :-)

= Is there a Github Repo for this? =

Yep. Check it out here: https://github.com/BruceCaldwell/Member-Approval

I create new branches for versions as they're released, and keep the Master branch for any changes I do before I'm ready to upgrade the plugin.

= TODO list? =

Here's my current TODO list:

* I want to make it possible to approve/deactivate Users via the Users list
* Make the entire backend and frontend fully translatable
* Create randomly-generated links to approve members directly from the notification email that comes to the admin of the site
* Improve the email system

== Screenshots ==

1. Screenshot of the backend of the plugin
2. Screenshot of an example error that you might show a User that's not allowed to log in yet.
3. Screenshot of the Method of Approval
4. Screenshot of the checkbox for deactivating a User
5. Screenshot of the Users column for Approval

== Changelog ==

= 131109 =
* Bug Fix: The plugin now works with plugins that updates Users' Roles after they are created, using the `user_register` hook.

= 131014 =
* Users that were already singed-in when deactivated will now either be silently logged out if an error message is set up in the Dashboard. Otherwise, they'll be sent to the redirection URL set up in the WordPress Admin Panel.

= 131009 =
* Users that are already logged-in when deactivated will be logged-out the next time they load a page
* Users that are logged-out by Member Approval are sent to wp_login_url() with a redirect_to variable set to the page they were on previously.

= 130719 =
* More documentation changes
* Added `ABSPATH` check to `/includes/column.php`
* Fixed some typos in commenting
* New tags
* Added donation link
* Edits to the readme.txt file to fit with the new features
* Minor formatting changes
* Fix on replacement code `%%fullname%%`

= 130717 =
* Many filters and actions in the source code for advanced customization through PHP
* Various formatting changes in files
* Documentation changes
* Fixed an issue that would cause a warning in WP_DEBUG mode with non-AJAX logins
* Fixed an issue with redirects and sites running on HTTPS
* PHP includes now just reside in `/includes/` instead of `/includes/php/`

= 130704 =
* Several Documentation fixes

* The plugin now attempts to catch AJAX login requests, and will return a WP_Error instead of redirect if caught, even if the plugin is set to redirect. This should help prevent problems with AJAX widgets.
* The plugin now supports up to WordPress 3.6-alpha

= 130627 =
* Emails now send from noreply@yoursite.com rather than from "WordPress"

* There is now a column in the Users list that will tell you if a User is Approved or not. Sortable.

= 130626 =
* Administrator no longer will show up in the Applicable Roles section of the settings
* Activation/Approval process changed to use a Checkbox rather than dropdown

* You can now deactivate Users through the User Edit panel in the Dashboard
* New Section of the Settings for Email Configuration for Notifications
* Notification Emails
* You are now able to have a User redirect when they attempt to log in while disabled. See the settings page for info

= 130625 =
* First release of the plugin. Let me know if there are any issues with it!

== Upgrade Notice ==

= 131109 =
* Maintenance Release, Upgrade Immediately.

= 131014 =
* Maintenance Release, Upgrade Immediately.

= 131009 =
* Fix: Users that are already logged-in when deactivated will be logged-out the next time they load a page
* Maintenance Release, Upgrade Immediately.

= 130719 =
* Maintenance Release, Upgrade Immediately.

= 130717 =
* Maintenance Release + bug fixes, Upgrade Immediately.

= 130704 =
* Maintenance Release, Upgrade Immediately.

= 130627 =
* Maintenance Release + new feature. Upgrade Immediately.

= 130626 =
* New Features + bug fix! Upgrade Immediately.

= 130625 =
* First Release