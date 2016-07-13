<?php defined( 'ABSPATH' ) OR exit;
if( isset($_GET['settings-updated']) ) { ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Settings saved.', 'wp-crm-system') ?></strong></p>
    </div>
<?php }
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'dashboard';
?>
<h2 class="nav-tab-wrapper">
	<a class="nav-tab <?php echo $active_tab == 'dashboard' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=dashboard"><?php _e('Dashboard', 'wp-crm-system') ?></a>
	<?php if (defined('WPCRM_CUSTOM_FIELDS')) { ?>
		<a class="nav-tab <?php echo $active_tab == 'custom' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=custom"><?php _e('Custom Fields', 'wp-crm-system') ?></a>
	<?php }
	if (defined('WPCRM_IMPORT_CONTACTS') || defined('WPCRM_IMPORT_OPPORTUNITIES') || defined('WPCRM_IMPORT_ORGANIZATIONS') || defined('WPCRM_IMPORT_TASKS') || defined('WPCRM_IMPORT_PROJECTS') || defined('WPCRM_IMPORT_CAMPAIGNS')) { ?>
		<a class="nav-tab <?php echo $active_tab == 'import' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=import"><?php _e('Import', 'wp-crm-system') ?></a>
	<?php }
	if (defined('WPCRM_NINJA_FORMS_CONNECT')) { ?>
		<a class="nav-tab <?php echo $active_tab == 'ninja-connect' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=ninja-connect"><?php _e('Ninja Forms', 'wp-crm-system') ?></a>
	<?php }
	if (defined('WPCRM_GRAVITY_FORMS_CONNECT')) { ?>
		<a class="nav-tab <?php echo $active_tab == 'gravity-connect' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=gravity-connect"><?php _e('Gravity Forms', 'wp-crm-system') ?></a>
	<?php }
	if (defined('WPCRM_SLACK_NOTIFICATIONS')) { ?>
		<a class="nav-tab <?php echo $active_tab == 'slack-notifications' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=slack-notifications"><?php _e('Slack', 'wp-crm-system') ?></a>
	<?php }
	if (defined('WPCRM_EMAIL_NOTIFICATIONS')) { ?>
		<a class="nav-tab <?php echo $active_tab == 'email-notifications' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=email-notifications"><?php _e('Email', 'wp-crm-system') ?></a>
	<?php }
	if (defined('WPCRM_INVOICING')) { ?>
		<a class="nav-tab <?php echo $active_tab == 'invoicing' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=invoicing"><?php _e('Invoicing', 'wp-crm-system') ?></a>
	<?php }
	if (defined('WPCRM_ZENDESK')) { ?>
		<a class="nav-tab <?php echo $active_tab == 'zendesk' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=zendesk"><?php _e('Zendesk', 'wp-crm-system') ?></a>
	<?php }
	if (defined('WPCRM_CONTACT_FROM_USER') || defined('WPCRM_CUSTOM_FIELDS') || defined('WPCRM_DROPBOX_CONNECT') || defined('WPCRM_EMAIL_NOTIFICATIONS') || defined('WPCRM_GRAVITY_FORMS_CONNECT') || defined('WPCRM_INVOICING') || defined('WPCRM_IMPORT_CAMPAIGNS') || defined('WPCRM_IMPORT_CONTACTS') || defined('WPCRM_IMPORT_OPPORTUNITIES') || defined('WPCRM_IMPORT_ORGANIZATIONS') || defined('WPCRM_IMPORT_PROJECTS') || defined('WPCRM_IMPORT_TASKS') || defined('WPCRM_NINJA_FORMS_CONNECT') || defined('WPCRM_SLACK_NOTIFICATIONS') || defined('WPCRM_ZENDESK')) { ?>
		<a class="nav-tab <?php echo $active_tab == 'licenses' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=licenses"><?php _e('Licenses', 'wp-crm-system') ?></a>
	<?php } ?>
</h2>
<?php
if ($active_tab == 'dashboard') {
	wpcrm_dashboard_settings_content();
}
if ($active_tab == 'general') {
	wpcrm_general_settings_content();
}
if ($active_tab == 'custom') {
	wpcrm_custom_fields_content();
}
if ($active_tab == 'import') {
	wpcrm_import_settings_content();
}
if ($active_tab == 'ninja-connect') {
	wpcrm_nf_settings_content();
}
if ($active_tab == 'gravity-connect') {
	wpcrm_gf_settings_content();
}
if ($active_tab == 'slack-notifications') {
	wpcrm_sn_settings_content();
}
if ($active_tab == 'email-notifications') {
	wpcrm_email_settings_content();
}
if ($active_tab == 'invoicing') {
	wpcrm_invoicing_settings_content();
}
if ($active_tab == 'zendesk') {
	wpcrm_zendesk_settings_content();
}
if ($active_tab == 'licenses') {
	wpcrm_license_keys();
}
function wpcrm_dashboard_settings_content() {
	include(WP_PLUGIN_DIR .'/wp-crm-system/dashboard.php');

}

function wpcrm_custom_fields_content() {
	$plugin = 'wp-crm-system-custom-fields';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/settings.php');
	} else {
		return;
	}
}
// Load paid plugin settings if plugins are installed and active.
function wpcrm_import_settings_content() {
	include(plugin_dir_path( __FILE__ ) . 'includes/return_bytes.php');
	$plugin_base = 'wp-crm-system-';
	$import_types = array($plugin_base.'import-organizations',$plugin_base.'import-contacts',$plugin_base.'import-opportunities',$plugin_base.'import-tasks',$plugin_base.'import-projects',$plugin_base.'import-campaigns');
	foreach($import_types as $import_type) {
		if(is_plugin_active($import_type.'/'.$import_type.'.php')) {
			include(WP_PLUGIN_DIR .'/'.$import_type.'/import.php');
		}
	}
}
function wpcrm_nf_settings_content() {
	$plugin = 'wp-crm-system-ninja-form-connect';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/import.php');
	} else {
		return;
	}
}
function wpcrm_gf_settings_content() {
	$plugin = 'wp-crm-system-gravity-form-connect';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/import.php');
	} else {
		return;
	}
}
function wpcrm_sn_settings_content() {
	$plugin = 'wp-crm-system-slack-notifications';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/settings.php');
	} else {
		return;
	}
}
function wpcrm_email_settings_content() {
	$plugin = 'wp-crm-system-email-notifications';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/settings.php');
	} else {
		return;
	}
}
function wpcrm_invoicing_settings_content() {
	$plugin = 'wp-crm-system-invoicing';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/settings.php');
	} else {
		return;
	}
}
function wpcrm_zendesk_settings_content() {
	$plugin = 'wp-crm-system-zendesk';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/settings.php');
	} else {
		return;
	}
}
function wpcrm_dropbox_settings_content() {
	$plugin = 'wp-crm-system-dropbox';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/settings.php');
	} else {
		return;
	}
}
function wpcrm_license_keys() {
	// Provides a way to activate license keys only if an add-on plugin is installed.
	$plugin_base = 'wp-crm-system-';
	$plugins = array($plugin_base.'import-organizations',$plugin_base.'import-contacts',$plugin_base.'import-opportunities',$plugin_base.'import-tasks',$plugin_base.'import-projects',$plugin_base.'import-campaigns',$plugin_base.'contact-user',$plugin_base.'custom-fields',$plugin_base.'gravity-form-connect',$plugin_base.'invoicing',$plugin_base.'ninja-form-connect',$plugin_base.'slack-notifications',$plugin_base.'email-notifications',$plugin_base.'dropbox',$plugin_base.'zendesk'); ?>
	<div class="wrap">
		<h2><?php _e('Premium Plugin Licenses','wp-crm-system'); ?></h2>
		<form method="post" action="options.php">
		<?php settings_fields('wpcrm_license_group'); ?>
			<table class="form-table">
				<tbody>
					<?php foreach($plugins as $plugin) {
						if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
							include(WP_PLUGIN_DIR .'/'.$plugin.'/license.php');
						}
					} ?>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
<?php }
