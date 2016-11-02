<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
//Include scripts and styles
function wpcrm_scripts_styles() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php');
	$active_page = isset( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : '';
	global $wpcrm_active_tab;
	global $post_type;
	wp_enqueue_script( 'datepicker' );
	wp_enqueue_script( 'jquery-ui-datepicker' );

	wp_register_style( 'jquery-ui-datepicker', WP_CRM_SYSTEM_PLUGIN_URL . '/css/jquery-ui.min.css' );
	wp_enqueue_style( 'jquery-ui-datepicker' );

	wp_register_style( 'gmap-style', WP_CRM_SYSTEM_PLUGIN_URL . '/css/gmap.css' );
	wp_enqueue_style( 'gmap-style' );

	wp_register_style( 'wpcrm-style', WP_CRM_SYSTEM_PLUGIN_URL . '/css/wp-crm.css' );
	wp_enqueue_style( 'wpcrm-style' );

	if ( $active_page == 'wpcrm-email' ) {
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'wpcrm-system-accordion', WP_CRM_SYSTEM_PLUGIN_URL . '/js/accordion.js' );
	}

	wp_enqueue_script( 'jquery' );

	if ( $active_page == 'wpcrm-settings' && ( $wpcrm_active_tab == '' || $wpcrm_active_tab =='dashboard' || $wpcrm_active_tab =='mailchimp' ) ) {
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_register_style( 'wp_crm_system_tooltips_css', WP_CRM_SYSTEM_PLUGIN_URL . '/css/tooltip.css' );
		wp_enqueue_style( 'wp_crm_system_tooltips_css' );

		wp_register_script( 'wp_crm_system_tooltips_js', WP_CRM_SYSTEM_PLUGIN_URL . '/js/tooltip.js', 1.0, false );
		wp_enqueue_script( 'wp_crm_system_tooltips_js' );
	}
	if ( in_array( $post_type, $postTypes ) || $post_type == 'wpcrm-invoice' ) {
		wp_register_script( 'wp_crm_system_edit_js', WP_CRM_SYSTEM_PLUGIN_URL . '/js/edit.js', 1.0, false );
		wp_enqueue_script( 'wp_crm_system_edit_js' );

		if ( 'on' == get_option( 'wpcrm_system_searchable_dropdown' ) ) {
		  //Searchable Dropdown
			wp_register_script( 'wp_crm_searchable_core_js', WP_CRM_SYSTEM_PLUGIN_URL . '/js/jquery.searchabledropdown-v1.0.8/sh/shCore.js', array( 'jquery' ), 1.0, false );
		  wp_enqueue_script( 'wp_crm_searchable_core_js' );
		  wp_register_script( 'wp_crm_searchable_brush_js', WP_CRM_SYSTEM_PLUGIN_URL . '/js/jquery.searchabledropdown-v1.0.8/sh/shBrushJScript.js', array( 'jquery' ), 1.0, false );
		  wp_enqueue_script( 'wp_crm_searchable_brush_js' );
		  wp_register_script( 'wp_crm_searchable_dropdown_js', WP_CRM_SYSTEM_PLUGIN_URL . '/js/jquery.searchabledropdown-v1.0.8/jquery.searchabledropdown-1.0.8.min.js', array( 'jquery' ), 1.1, false );
		  wp_enqueue_script( 'wp_crm_searchable_dropdown_js' );
		  wp_register_script( 'wp_crm_searchable', WP_CRM_SYSTEM_PLUGIN_URL . '/js/wpCRMSystemSearchable.js', array( 'jquery' ), 1.0, false );
		  wp_enqueue_script( 'wp_crm_searchable' );
		  wp_register_style( 'wp_crm_system_searchable_core_css', WP_CRM_SYSTEM_PLUGIN_URL . '/js/jquery.searchabledropdown-v1.0.8/sh/shCore.css' );
		  wp_enqueue_style( 'wp_crm_system_searchable_core_css' );
		  wp_register_style( 'wp_crm_system_searchable_default_css', WP_CRM_SYSTEM_PLUGIN_URL . '/js/jquery.searchabledropdown-v1.0.8/sh/shThemeDefault.css' );
		  wp_enqueue_style( 'wp_crm_system_searchable_default_css' );
		}
	}
	if ( $active_page == 'wpcrm-settings' && ( $wpcrm_active_tab == '' || $wpcrm_active_tab =='dashboard' ) ) {
		wp_register_script( 'wp_crm_system_dashboard_height', WP_CRM_SYSTEM_PLUGIN_URL . '/js/wp-crm-system-dashboard.js', array( 'jquery' ), 1.0, false );
		wp_enqueue_script( 'wp_crm_system_dashboard_height' );
	}

  wp_enqueue_script('wpcrm-ajax', WP_CRM_SYSTEM_PLUGIN_DIR . '/js/wpcrm-update.js', array('jquery'));
	wp_localize_script('wpcrm-ajax', 'wpcrm_vars', array(
			'wpcrm_nonce' => wp_create_nonce('wpcrm-nonce')
		)
	);
}
add_action( 'admin_enqueue_scripts', 'wpcrm_scripts_styles' );
