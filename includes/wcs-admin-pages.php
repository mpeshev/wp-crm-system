<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
		die( "Sorry, you are not allowed to access this page directly." );
}
// Hook for adding admin menu
add_action('admin_menu', 'wp_crm_system_admin_page');
// action function for above hook
function wp_crm_system_admin_page() {
	if( 'set' == get_option( 'wpcrm_system_settings_initial' ) ) {
		$page_role = WPCRM_USER_ACCESS;
	} else {
		$page_role = 'manage_options';
	}
	$menu_name = apply_filters( 'wpcrm_system_menu_name', __('WP-CRM System', 'wp-crm-system') );
	$menu_icon = apply_filters( 'wpcrm_system_menu_icon', 'dashicons-id' );
	// Add a new menu:
	add_menu_page( $menu_name, $menu_name, $page_role, 'wpcrm', 'wp_crm_system_settings_page', $menu_icon );
	add_submenu_page( 'wpcrm', __( 'Dashboard', 'wp-crm-system' ), __( 'Dashboard', 'wp-crm-system' ), $page_role, 'wpcrm-settings', 'wp_crm_system_settings_page' );
	add_submenu_page( 'wpcrm', __( 'Email', 'wp-crm-system' ), __( 'Email', 'wp-crm-system' ), $page_role, 'wpcrm-email', 'wp_crm_system_email_page' );
	add_submenu_page( 'wpcrm', __( 'Reports', 'wp-crm-system' ), __( 'Reports', 'wp-crm-system' ), $page_role, 'wpcrm-reports', 'wp_crm_system_reports_page' );
	add_submenu_page( 'wpcrm', __( 'Extensions', 'wp-crm-system' ), __( 'Extensions', 'wp-crm-system' ), $page_role, 'wpcrm-extensions', 'wp_crm_system_extensions_page' );
}

//Display the page content for the plugin settings and reports
function wp_crm_system_settings_page() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-settings.php');
}
function wp_crm_system_email_page() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-email.php' );
}
function wp_crm_system_reports_page() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/reports/wcs-reports.php' );
}
function wp_crm_system_extensions_page() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-extensions.php' );
}

// Set order of submenu pages
add_filter( 'custom_menu_order', 'wpcrm_system_custom_menu_order' );
function wpcrm_system_custom_menu_order( $menu_ord ) {
	global $submenu;
	$arr = array();
	if ( array_key_exists( 'wpcrm', $submenu ) && 'set' == get_option( 'wpcrm_system_settings_initial' ) ) {
		if ( defined( 'WPCRM_INVOICING' ) ) {
			$arr[] = $submenu['wpcrm'][7];	//Dashboard
			$arr[] = $submenu['wpcrm'][3];	//Organizations
			$arr[] = $submenu['wpcrm'][1];	//Contacts
			$arr[] = $submenu['wpcrm'][4];	//Opportunities
			$arr[] = $submenu['wpcrm'][5];	//Projects
			$arr[] = $submenu['wpcrm'][2];	//Tasks
			$arr[] = $submenu['wpcrm'][6];	//Campaigns
			$arr[] = $submenu['wpcrm'][0];	//Invoices
			$arr[] = $submenu['wpcrm'][8];	//Email
			$arr[] = $submenu['wpcrm'][9];	//Reports
			$arr[] = $submenu['wpcrm'][10];	//Extensions
			$submenu['wpcrm'] = $arr;
		} else {
			$arr[] = $submenu['wpcrm'][6];	//Dashboard
			$arr[] = $submenu['wpcrm'][2];	//Organizations
			$arr[] = $submenu['wpcrm'][0];	//Contacts
			$arr[] = $submenu['wpcrm'][3];	//Opportunities
			$arr[] = $submenu['wpcrm'][4];	//Projects
			$arr[] = $submenu['wpcrm'][1];	//Tasks
			$arr[] = $submenu['wpcrm'][5];	//Campaigns
			$arr[] = $submenu['wpcrm'][7];	//Email
			$arr[] = $submenu['wpcrm'][8];	//Reports
			$arr[] = $submenu['wpcrm'][9];	//Extensions
			$submenu['wpcrm'] = $arr;
		}
	}
	return $menu_ord;
}
