<?php defined( 'ABSPATH' ) OR exit;
if( isset( $_GET['settings-updated'] ) ) { ?>
	<div id="message" class="updated">
		<p><strong><?php _e( 'Settings saved.', 'wp-crm-system' ); ?></strong></p>
	</div>
<?php }
global $wpcrm_active_tab;
$wpcrm_active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'dashboard';

global $wpcrm_active_subtab;
$wpcrm_active_subtab = isset( $_GET[ 'subtab' ] ) ? $_GET[ 'subtab' ] : '';

?>
<h2 class="nav-tab-wrapper">
	<?php
	do_action( 'wpcrm_system_settings_tab' );
	if ( has_action( 'wpcrm_system_import_field' ) ) { ?>
		<a class="nav-tab <?php echo $wpcrm_active_tab == 'import' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=import"><?php _e( 'Import', 'wp-crm-system' ) ?></a>
	<?php } ?>
</h2>
<ul class="subsubsub">
<?php
	do_action( 'wpcrm_system_settings_subtab' );
?>
</ul><br />
<?php
do_action( 'wpcrm_system_settings_content' );

if ( $wpcrm_active_tab == 'import' ) {
	do_action( 'wpcrm_system_import_field' );
}