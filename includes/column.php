<?php

if(!defined('ABSPATH'))
	exit('Don\'t access source files directly!');

/**
 * Class member_approval_column
 */
class member_approval_column {
	/**
	 * @param $col array
	 *
	 * @return array
	 */
	public static function add_column($col) {
		$col['member_approval_approved'] = 'Approval Status';
		return $col;
	}

	// add_filter('manage_users_columns', array('member_approval_column', 'add_column'));

	/**
	 * @param $columns array
	 *
	 * @return array
	 */
	public static function make_sortable($columns) {
		$columns['member_approval_approved'] = 'member_approval_approved';
		return $columns;
	}

	// add_filter('manage_users_sortable_columns', array('member_approval_column', 'make_sortable'));

	/**
	 * @param $val mixed
	 * @param $col_name string
	 * @param $id int User ID
	 *
	 * @return string The data for the User's column
	 */
	public static function give_value($val, $col_name, $id) {
		if($col_name === 'member_approval_approved') {
			$user_needs_approval = get_user_meta($id, 'requires_approval', TRUE);

			if($user_needs_approval)
				return 'Not Approved';
			else
				return 'Approved';
		}

		else return $val;
	}

	// add_filter('manage_users_custom_column', array('member_approval_column', 'give_value'), 10, 3);

	/**
	 * @param $query object WordPress query object to filter
	 */
	public static function sort_query($query) {
		global $wpdb; // Get WordPress database class / for prefix
		$vars = $query->query_vars;
		/**/
		if($vars['orderby'] === 'member_approval_approved') // Check to make sure we're ordering Approval Status
		{
			$query->query_from .= " LEFT JOIN ".$wpdb->prefix."usermeta m ON (".$wpdb->prefix."users.ID = m.user_id  AND m.meta_key = 'requires_approval')";
			$query->query_orderby = "ORDER BY m.meta_value ".$vars['order']; // order by these values by ASC or DESC order, depending on the query
		}
	}
	// add_action('pre_user_query', array('member_approval_column', 'sort_query'));
}