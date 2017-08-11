<?php
/*
Plugin Name: Member Approval
Description: Allows you to create an approval process for new Users on your site by blocking the ability to log in when a User registers until approved.
Version: 131109
Author: Bruce Caldwell
Author URI: http://profiles.wordpress.org/bruce-caldwell
License: GPLv3
*/

/*
Copyright (C)  2013  Bruce Caldwell

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if(!defined('ABSPATH'))
	exit('Don\'t access source files directly!');

/**
 * Root directory for includes
 */
define('MEMBER_APPROVAL_ROOT', dirname(__FILE__));

/**
 * @param $c string Class's name
 *
 * Autoloader
 */
function member_approval_autoload($c) {
	if(strpos($c, 'member_approval_') === 0) { // If the class name starts with `member_approval_`
		$file = str_replace(array('member_approval_', '_'), array('', '-'), $c).'.php';
		/** @noinspection PhpIncludeInspection */
		require_once(MEMBER_APPROVAL_ROOT.'/includes/'.$file);
	}
}

require_once(MEMBER_APPROVAL_ROOT.'/includes/hooks.php'); // Hooks file