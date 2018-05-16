<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wpcrm_system_welcome_screen_page(){
	add_dashboard_page( 'Welcome', 'Welcome', 'read', 'wpcrm-system-plugin-welcome', 'wpcrm_system_display_welcome_page' );
}
add_action( 'admin_menu', 'wpcrm_system_welcome_screen_page' );

function wpcrm_system_display_welcome_page(){
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/welcome-page-template.php' );
}

function wpcrm_system_remove_welcome_page_menu_item(){
	remove_submenu_page( 'index.php', 'wpcrm-system-plugin-welcome' );
}
add_action( 'admin_head', 'wpcrm_system_remove_welcome_page_menu_item' );

function wpcrm_system_welcome_screen_activate(){
	set_transient( 'wpcrm_system_welcome_screen_activation_redirect', true, 30 );
}
register_activation_hook( WP_CRM_SYSTEM, 'wpcrm_system_welcome_screen_activate' ); // check that this file is correct

function wpcrm_system_welcome_page_redirect(){
	if( ! get_transient( 'wpcrm_system_welcome_screen_activation_redirect' ) ){
		return;
	}

	delete_transient( 'wpcrm_system_welcome_screen_activation_redirect' );

	if( is_network_admin() || isset( $_GET['activate-multi'] ) ){
		return;
	}

	wp_safe_redirect( admin_url( 'index.php?page=wpcrm-system-plugin-welcome' ) );
	die();
}
add_action( 'admin_init', 'wpcrm_system_welcome_page_redirect' );