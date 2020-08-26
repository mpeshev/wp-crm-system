<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_action( 'admin_init', 'wp_crm_system_process_settings_import' );
function wp_crm_system_process_settings_import() {
	if( isset( $_GET['message'] ) && 'import-successful' == $_GET['message'] ){
		echo '<div id="message" class="updated"><p><strong>'. __( 'Import Successful!', 'wp-crm-system' ) . '</strong></p></div>';
	}
	if( empty( $_POST['wp_crm_system_action'] ) || 'import_settings' != $_POST['wp_crm_system_action'] )
		return;
	if( ! wp_verify_nonce( $_POST['wp_crm_system_import_nonce'], 'wp_crm_system_import_nonce' ) )
		return;
	if( ! current_user_can( 'manage_options' ) )
		return;
	$extension = end( explode( '.', $_FILES['import_file']['name'] ) );
	if( $extension != 'json' ) {
		wp_die( __( 'Please upload a valid .json file' ) );
	}
	$import_file = $_FILES['import_file']['tmp_name'];
	if( empty( $import_file ) ) {
		wp_die( __( 'Please upload a file to import' ) );
	}
	// Retrieve the settings from the file and convert the json object to an array.
	$settings = (array) json_decode( file_get_contents( $import_file ) );
var_dump( $settings );
	foreach( $settings as $option_name => $option_value ){
		update_option( $option_name, maybe_unserialize( $option_value ) );
	}
	wp_safe_redirect( admin_url( 'admin.php?page=wpcrm-settings&tab=settings&subtab=settings&message=import-successful' ) ); exit;
}
add_action( 'wpcrm_system_settings_content', 'wp_crm_system_import_settings_field' );
function wp_crm_system_import_settings_field(){
	global $wpcrm_active_tab, $wpcrm_active_subtab;
	if ( 'settings' == $wpcrm_active_tab && ( 'settings' == $wpcrm_active_subtab || '' == $wpcrm_active_subtab ) ) {?>
	<div class="postbox">
		<div class="inside">
			<header>
				<h3><span><?php _e( 'Import Settings' ); ?></span></h3>
			</header>
			<p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'wp-crm-system' ); ?></p>
			<form method="post" enctype="multipart/form-data">
				<p>
					<input type="file" name="import_file"/>
				</p>
				<p>
					<input type="hidden" name="wp_crm_system_action" value="import_settings" />
					<?php wp_nonce_field( 'wp_crm_system_import_nonce', 'wp_crm_system_import_nonce' ); ?>
					<?php submit_button( __( 'Import', 'wp-crm-system' ), 'secondary', 'submit', false ); ?>
				</p>
			</form>
		</div><!-- .inside -->
	</div><!-- .postbox -->
	<?php }
}