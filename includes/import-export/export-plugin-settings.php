<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_action( 'admin_init', 'wp_crm_system_export_plugin_settings' );
function wp_crm_system_export_plugin_settings(){
	if( empty( $_POST['wp_crm_system_action'] ) || 'export_settings' != $_POST['wp_crm_system_action'] )
		return;

	if( !wp_verify_nonce( $_POST['wp_crm_system_export_nonce'] , 'wp_crm_system_export_nonce' ) )
		return;

	if( ! current_user_can( 'manage_options' ) )
		return;

	global $wpdb;
	// Certain options had an inconsistent naming convention, so we need to run two queries to get all of the options.
	$wpcrm_options = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE '%wpcrm%'" );
	$wp_crm_options = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE '%wp-crm%'" );

	$export = array();
	foreach ( $wpcrm_options as $option ) {
		$export[$option->option_name] = $option->option_value;
	}
	// Unset $option so that it doesn't affect the next loop.
	unset($option);
	foreach ( $wp_crm_options as $option ) {
		$export[$option->option_name] = $option->option_value;
	}

	ignore_user_abort( true );
	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=wp-crm-system-settings-export-' . date( 'm-d-Y' ) . '.json' );
	header( "Expires: 0" );

	echo json_encode( $export );
	exit;
}

add_action( 'wpcrm_system_settings_content', 'wp_crm_system_export_settings_button' );
function wp_crm_system_export_settings_button(){
	global $wpcrm_active_tab, $wpcrm_active_subtab;
	if ( 'settings' == $wpcrm_active_tab && ( 'settings' == $wpcrm_active_subtab || '' == $wpcrm_active_subtab ) ) {?>
	<div class="postbox">
		<h3><span><?php _e( 'Export Settings' ); ?></span></h3>
		<div class="inside">
			<p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'wp-crm-system' ); ?></p>
			<p><?php _e( 'Please note that this will include all settings. If applicable, this includes settings from add-on extension plugins including license keys, etc.', 'wp-crm-system' ); ?></p>
			<form method="post">
				<p><input type="hidden" name="wp_crm_system_action" value="export_settings" /></p>
				<p>
					<?php wp_nonce_field( 'wp_crm_system_export_nonce', 'wp_crm_system_export_nonce' ); ?>
					<?php submit_button( __( 'Export', 'wp-crm-system' ), 'secondary', 'submit', false ); ?>
				</p>
			</form>
		</div><!-- .inside -->
	</div><!-- .postbox -->
	<?php }
}