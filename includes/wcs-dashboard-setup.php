<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// Add Default Settings
if(!function_exists('wpcrm_system_default_setting_tab')){
	function wpcrm_system_default_setting_tab() {
		global $wpcrm_active_tab; ?>
		<a class="nav-tab <?php echo $wpcrm_active_tab == 'dashboard' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=dashboard"><?php _e('Dashboard', 'wp-crm-system') ?></a>
	<?php }
}
add_action( 'wpcrm_system_settings_tab', 'wpcrm_system_default_setting_tab', 1 );

function wpcrm_dashboard_settings_content() {
	global $wpcrm_active_tab;
	if ($wpcrm_active_tab == 'dashboard') {
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-dashboard-reports.php' );
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-dashboard.php' );
	}
}
add_action( 'wpcrm_system_settings_content', 'wpcrm_dashboard_settings_content' );

// Add Categories tab
function wpcrm_system_categories_tab() {
	global $wpcrm_active_tab; ?>
	<a class="nav-tab <?php echo $wpcrm_active_tab == 'categories' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=categories"><?php _e('Categories', 'wp-crm-system') ?></a>
<?php }
add_action( 'wpcrm_system_settings_tab', 'wpcrm_system_categories_tab' );
// Add Categories Content
function wpcrm_categories_settings_content() {
  global $wpcrm_active_tab;
  if ($wpcrm_active_tab == 'categories') {
  $categories = array('contact-type'=>__('Contact Categories','wp-crm-system'),'task-type'=>__('Task Categories','wp-crm-system'),'organization-type'=>__('Organization Categories','wp-crm-system'),'opportunity-type'=>__('Opportunity Categories','wp-crm-system'),'project-type'=>__('Project Categories','wp-crm-system'),'campaign-type'=>__('Campaign Categories','wp-crm-system'));?>
	<div class="wrap">
		<div>
			<h2><?php _e('WP CRM System Categories', 'wp-crm-system'); ?></h2>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<?php foreach ($categories as $key => $value) { ?>
					<tr>
						<td><a href="edit-tags.php?taxonomy=<?php echo $key; ?>"><?php echo $value; ?></a></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
  <?php }
}
add_action( 'wpcrm_system_settings_content', 'wpcrm_categories_settings_content' );
