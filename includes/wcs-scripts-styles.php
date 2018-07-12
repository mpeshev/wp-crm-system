<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}
//Include scripts and styles
function wpcrm_scripts_styles($hook) {
	global $post_type;
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php' );
	$active_page = isset( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : '';
	// for some reason the global $wpcrm_active_tab was returning NULL here
	$wpcrm_active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'dashboard';

	wp_enqueue_script( 'jquery' );
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

	if( 'client-area-upsell' == $wpcrm_active_tab || 'custom-fields-upsell' == $wpcrm_active_tab || 'importers-upsell' == $wpcrm_active_tab || 'invoicing-upsell' == $wpcrm_active_tab  ){
		wp_register_style( 'wpcrm-system-lightbox', WP_CRM_SYSTEM_PLUGIN_URL . '/css/lightbox.css' );
		wp_enqueue_style( 'wpcrm-system-lightbox' );
	}

	wp_enqueue_script( 'jquery-ui-tooltip' );
	wp_register_style( 'wp_crm_system_tooltips_css', WP_CRM_SYSTEM_PLUGIN_URL . '/css/tooltip.css' );
	wp_enqueue_style( 'wp_crm_system_tooltips_css' );

	wp_register_script( 'wp_crm_system_tooltips_js', WP_CRM_SYSTEM_PLUGIN_URL . '/js/tooltip.js', WP_CRM_SYSTEM_VERSION, false );
	wp_enqueue_script( 'wp_crm_system_tooltips_js' );

	if ( in_array( $post_type, $postTypes ) || 'wpcrm-invoice' == $post_type || 'wpcrm-settings' == $active_page ) {
		wp_register_script( 'wp_crm_system_edit_js', WP_CRM_SYSTEM_PLUGIN_URL . '/js/edit.js', WP_CRM_SYSTEM_VERSION, false );
		wp_enqueue_script( 'wp_crm_system_edit_js' );

		//Searchable Dropdown
		wp_register_script( 'wp_crm_chosen_core_js', WP_CRM_SYSTEM_PLUGIN_URL . '/js/chosen_v1.6.2/chosen.jquery.min.js', array( 'jquery' ), WP_CRM_SYSTEM_VERSION, false );
		wp_enqueue_script( 'wp_crm_chosen_core_js' );

		wp_register_script( 'wp_crm_searchable', WP_CRM_SYSTEM_PLUGIN_URL . '/js/wpCRMSystemSearchable.js', array( 'jquery' ), WP_CRM_SYSTEM_VERSION, false );
		wp_enqueue_script( 'wp_crm_searchable' );

		wp_register_style( 'wp_crm_system_chosen_core_css', WP_CRM_SYSTEM_PLUGIN_URL . '/js/chosen_v1.6.2/chosen.min.css' );
		wp_enqueue_style( 'wp_crm_system_chosen_core_css' );
	}
	if ( 'wpcrm-settings' == $active_page && ( '' == $wpcrm_active_tab || 'dashboard' == $wpcrm_active_tab ) ) {
		wp_register_script( 'wp_crm_system_dashboard_height', WP_CRM_SYSTEM_PLUGIN_URL . '/js/wp-crm-system-dashboard.js', array( 'jquery' ), WP_CRM_SYSTEM_VERSION, false );
		wp_enqueue_script( 'wp_crm_system_dashboard_height' );
	}

	wp_enqueue_script('wpcrm-ajax', WP_CRM_SYSTEM_PLUGIN_URL . '/js/wpcrm-update.js', array('jquery'));
	wp_localize_script('wpcrm-ajax', 'wpcrm_vars', array(
			'wpcrm_nonce' => wp_create_nonce('wpcrm-nonce')
		)
	);
}
add_action( 'admin_enqueue_scripts', 'wpcrm_scripts_styles' );
