<?php defined( 'ABSPATH' ) OR exit;
if( isset($_GET['settings-updated']) ) { ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Settings saved.', 'wp-crm-system') ?></strong></p>
    </div>
<?php }
global $wpcrm_active_tab;
$wpcrm_active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'dashboard';
?>
<h2 class="nav-tab-wrapper">
  <?php
  do_action( 'wpcrm_system_settings_tab' );
  if ( has_action( 'wpcrm_system_import_field' ) ) { ?>
  	<a class="nav-tab <?php echo $wpcrm_active_tab == 'import' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=import"><?php _e('Import', 'wp-crm-system') ?></a>
  <?php }
  if ( has_action( 'wpcrm_system_license_key_field' ) ) { ?>
  	<a class="nav-tab <?php echo $wpcrm_active_tab == 'licenses' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=licenses"><?php _e('Licenses', 'wp-crm-system') ?></a>
  <?php } ?>
</h2>
<?php
do_action( 'wpcrm_system_settings_content' );

if ($wpcrm_active_tab == 'import') {
	wpcrm_import_settings_content();
}
if ($wpcrm_active_tab == 'licenses') {
	wpcrm_license_keys();
}

// Load paid plugin settings if plugins are installed and active.
function wpcrm_import_settings_content() {
	include(plugin_dir_path( __FILE__ ) . 'includes/return_bytes.php');
  do_action( 'wpcrm_system_import_field' );
}
function wpcrm_license_keys() {
	// Provides a way to activate license keys only if an add-on plugin is installed.?>
	<div class="wrap">
		<h2><?php _e('Premium Plugin Licenses','wp-crm-system'); ?></h2>
		<form method="post" action="options.php">
		<?php settings_fields('wpcrm_license_group'); ?>
			<table class="form-table">
				<tbody>
          <?php do_action( 'wpcrm_system_license_key_field' ); ?>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
<?php }
