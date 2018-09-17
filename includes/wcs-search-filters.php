<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Modify search query for contacts
 */
add_filter( 'posts_join', 'wp_crm_system_contacts_search_join' );
function wp_crm_system_contacts_search_join( $join ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-contact".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-contact' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	}
	return $join;
}

add_filter( 'posts_where', 'wp_crm_system_contacts_search_where' );
function wp_crm_system_contacts_search_where( $where ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-contact".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-contact' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$where = preg_replace(
			"/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
	}
	return $where;
}

add_filter( 'posts_distinct', 'wp_crm_system_contacts_search_distinct' );
function wp_crm_system_contacts_search_distinct( $where ){
	global $pagenow, $wpdb;

	if ( is_admin() && $pagenow == 'edit.php'
		&& isset( $_GET['post_type'] ) && 'wpcrm-contact' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
	return "DISTINCT";

	}
	return $where;
}
/**
 * Modify search query for organizations
 */
add_filter( 'posts_join', 'wp_crm_system_organizations_search_join' );
function wp_crm_system_organizations_search_join( $join ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-organization".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-organization' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	}
	return $join;
}

add_filter( 'posts_where', 'wp_crm_system_organizations_search_where' );
function wp_crm_system_organizations_search_where( $where ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-organization".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-organization' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$where = preg_replace(
			"/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
	}
	return $where;
}

add_filter( 'posts_distinct', 'wp_crm_system_organizations_search_distinct' );
function wp_crm_system_organizations_search_distinct( $where ){
	global $pagenow, $wpdb;

	if ( is_admin() && $pagenow == 'edit.php'
		&& isset( $_GET['post_type'] ) && 'wpcrm-organization' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
	return "DISTINCT";

	}
	return $where;
}
/**
 * Modify search query for projects
 */
add_filter( 'posts_join', 'wp_crm_system_projects_search_join' );
function wp_crm_system_projects_search_join( $join ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-project".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-project' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	}
	return $join;
}

add_filter( 'posts_where', 'wp_crm_system_projects_search_where' );
function wp_crm_system_projects_search_where( $where ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-project".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-project' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$where = preg_replace(
			"/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
	}
	return $where;
}

add_filter( 'posts_distinct', 'wp_crm_system_projects_search_distinct' );
function wp_crm_system_projects_search_distinct( $where ){
	global $pagenow, $wpdb;

	if ( is_admin() && $pagenow == 'edit.php'
		&& isset( $_GET['post_type'] ) && 'wpcrm-project' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
	return "DISTINCT";

	}
	return $where;
}
/**
 * Modify search query for tasks
 */
add_filter( 'posts_join', 'wp_crm_system_tasks_search_join' );
function wp_crm_system_tasks_search_join( $join ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-task".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-task' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	}
	return $join;
}

add_filter( 'posts_where', 'wp_crm_system_tasks_search_where' );
function wp_crm_system_tasks_search_where( $where ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-task".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-task' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$where = preg_replace(
			"/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
	}
	return $where;
}

add_filter( 'posts_distinct', 'wp_crm_system_tasks_search_distinct' );
function wp_crm_system_tasks_search_distinct( $where ){
	global $pagenow, $wpdb;

	if ( is_admin() && $pagenow == 'edit.php'
		&& isset( $_GET['post_type'] ) && 'wpcrm-task' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
	return "DISTINCT";

	}
	return $where;
}
/**
 * Modify search query for campaigns
 */
add_filter( 'posts_join', 'wp_crm_system_campaigns_search_join' );
function wp_crm_system_campaigns_search_join( $join ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-campaign".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-campaign' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	}
	return $join;
}

add_filter( 'posts_where', 'wp_crm_system_campaigns_search_where' );
function wp_crm_system_campaigns_search_where( $where ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-campaign".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-campaign' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$where = preg_replace(
			"/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
	}
	return $where;
}

add_filter( 'posts_distinct', 'wp_crm_system_campaigns_search_distinct' );
function wp_crm_system_campaigns_search_distinct( $where ){
	global $pagenow, $wpdb;

	if ( is_admin() && $pagenow == 'edit.php'
		&& isset( $_GET['post_type'] ) && 'wpcrm-campaign' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
	return "DISTINCT";

	}
	return $where;
}
/**
 * Modify search query for opportunities
 */
add_filter( 'posts_join', 'wp_crm_system_opportunities_search_join' );
function wp_crm_system_opportunities_search_join( $join ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-opportunity".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-opportunity' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	}
	return $join;
}

add_filter( 'posts_where', 'wp_crm_system_opportunities_search_where' );
function wp_crm_system_opportunities_search_where( $where ) {
	global $pagenow, $wpdb;

	// I want the filter only when performing a search on edit page of Custom Post Type named "wpcrm-opportunity".
	if ( is_admin() && 'edit.php' == $pagenow
		&& isset( $_GET['post_type'] ) && 'wpcrm-opportunity' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
		$where = preg_replace(
			"/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
	}
	return $where;
}

add_filter( 'posts_distinct', 'wp_crm_system_opportunities_search_distinct' );
function wp_crm_system_opportunities_search_distinct( $where ){
	global $pagenow, $wpdb;

	if ( is_admin() && $pagenow == 'edit.php'
		&& isset( $_GET['post_type'] ) && 'wpcrm-opportunity' == $_GET['post_type']
		&& isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
	return "DISTINCT";

	}
	return $where;
}