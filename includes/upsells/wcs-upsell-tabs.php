<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}
add_action( 'wpcrm_system_settings_tab', 'wpcrm_system_client_area_upsell_tab', 3 );
function wpcrm_system_client_area_upsell_tab(){
	global $wpcrm_active_tab;
	if( !defined( 'WPCRM_CLIENT_AREA' ) ){ ?>
		<a class="nav-tab <?php echo $wpcrm_active_tab == 'client-area-upsell' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=client-area-upsell"><?php _e( 'Client Area', 'wp-crm-system' ); ?></a>
	<?php }
}
add_action( 'wpcrm_system_settings_tab', 'wpcrm_system_custom_fields_upsell_tab', 4 );
function wpcrm_system_custom_fields_upsell_tab(){
	global $wpcrm_active_tab;
	if( !defined( 'WPCRM_CUSTOM_FIELDS' ) ){ ?>
		<a class="nav-tab <?php echo $wpcrm_active_tab == 'custom-fields-upsell' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=custom-fields-upsell"><?php _e( 'Custom Fields', 'wp-crm-system' ); ?></a>
	<?php }
}
add_action( 'wpcrm_system_settings_tab', 'wpcrm_system_invoicing_upsell_tab', 6 );
function wpcrm_system_invoicing_upsell_tab(){
	global $wpcrm_active_tab;
	if( !defined( 'WPCRM_INVOICING' ) ){ ?>
		<a class="nav-tab <?php echo $wpcrm_active_tab == 'invoicing-upsell' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=invoicing-upsell"><?php _e( 'Invoicing', 'wp-crm-system' ); ?></a>
	<?php }
}
add_action( 'wpcrm_system_settings_content', 'wpcrm_system_upsell_content' );
function wpcrm_system_upsell_content(){
	global $wpcrm_active_tab;

	switch ( $wpcrm_active_tab ) {
		case 'client-area-upsell':
			include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/upsells/client-area.php' );
			break;

		case 'custom-fields-upsell':
			include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/upsells/custom-fields.php' );
			break;

		case 'invoicing-upsell':
			include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/upsells/invoicing.php' );
			break;

		default:
			// otherwise do nothing
			break;
	}

}