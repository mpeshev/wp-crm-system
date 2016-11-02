<?php defined( 'ABSPATH' ) OR exit; ?>
<?php
global $wpcrm_reports_active_tab;
$wpcrm_reports_active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'overview';
add_action( 'wpcrm_system_report_tab', 'wpcrm_system_report_overview_tab', 1 );
add_action( 'wpcrm_system_report_tab', 'wpcrm_system_report_project_tab', 2 );
add_action( 'wpcrm_system_report_tab', 'wpcrm_system_report_task_tab', 3 );
add_action( 'wpcrm_system_report_tab', 'wpcrm_system_report_opportunity_tab', 4 );
add_action( 'wpcrm_system_report_tab', 'wpcrm_system_report_organization_tab', 5 );
add_action( 'wpcrm_system_report_tab', 'wpcrm_system_report_contact_tab', 6 );
add_action( 'wpcrm_system_report_tab', 'wpcrm_system_report_campaign_tab', 7 );
function wpcrm_system_report_overview_tab() {
	global $wpcrm_reports_active_tab; ?>
	<a class="nav-tab <?php echo $wpcrm_reports_active_tab == 'overview' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=overview"><?php _e('Overview', 'wp-crm-system') ?></a>
<?php
}
function wpcrm_system_report_project_tab() {
	global $wpcrm_reports_active_tab; ?>
	<a class="nav-tab <?php echo $wpcrm_reports_active_tab == 'project' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=project"><?php _e('Project', 'wp-crm-system') ?></a>
<?php
}
function wpcrm_system_report_task_tab() {
	global $wpcrm_reports_active_tab; ?>
	<a class="nav-tab <?php echo $wpcrm_reports_active_tab == 'task' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=task"><?php _e('Task', 'wp-crm-system') ?></a>
<?php
}
function wpcrm_system_report_opportunity_tab() {
	global $wpcrm_reports_active_tab; ?>
	<a class="nav-tab <?php echo $wpcrm_reports_active_tab == 'opportunity' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=opportunity"><?php _e('Opportunity', 'wp-crm-system') ?></a>
<?php
}
function wpcrm_system_report_organization_tab() {
	global $wpcrm_reports_active_tab; ?>
	<a class="nav-tab <?php echo $wpcrm_reports_active_tab == 'organization' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=organization"><?php _e('Organization', 'wp-crm-system') ?></a>
<?php
}
function wpcrm_system_report_contact_tab() {
	global $wpcrm_reports_active_tab; ?>
	<a class="nav-tab <?php echo $wpcrm_reports_active_tab == 'contact' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=contact"><?php _e('Contact', 'wp-crm-system') ?></a>
<?php
}
function wpcrm_system_report_campaign_tab() {
	global $wpcrm_reports_active_tab; ?>
	<a class="nav-tab <?php echo $wpcrm_reports_active_tab == 'campaign' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=campaign"><?php _e('Campaign', 'wp-crm-system') ?></a>
<?php
}

add_action( 'wpcrm_system_report_content', 'wpcrm_system_report_overview_content' );
add_action( 'wpcrm_system_report_content', 'wpcrm_system_report_project_content' );
add_action( 'wpcrm_system_report_content', 'wpcrm_system_report_task_content' );
add_action( 'wpcrm_system_report_content', 'wpcrm_system_report_opportunity_content' );
add_action( 'wpcrm_system_report_content', 'wpcrm_system_report_organization_content' );
add_action( 'wpcrm_system_report_content', 'wpcrm_system_report_contact_content' );
add_action( 'wpcrm_system_report_content', 'wpcrm_system_report_campaign_content' );
function wpcrm_system_report_overview_content() {
	global $wpcrm_reports_active_tab;
	if ($wpcrm_reports_active_tab == 'overview') {
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/reports/overview-reports.php' ); ?>
		<div class="wrap">
			<div>
				<h2><?php _e('WP CRM System Reports', 'wp-crm-system'); ?></h2>
				<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
					<tbody>
						<?php do_action( 'wpcrm_system_overview_reports' ); ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php }
}
function wpcrm_system_report_project_content() {
	global $wpcrm_reports_active_tab;
	if ($wpcrm_reports_active_tab == 'project') {
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/reports/project-reports.php' );
	}
}
function wpcrm_system_report_opportunity_content() {
	global $wpcrm_reports_active_tab;
	if ($wpcrm_reports_active_tab == 'opportunity') {
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/reports/opportunity-reports.php' );
	}
}
function wpcrm_system_report_task_content() {
	global $wpcrm_reports_active_tab;
	if ($wpcrm_reports_active_tab == 'task') {
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/reports/task-reports.php' );
	}
}
function wpcrm_system_report_organization_content() {
	global $wpcrm_reports_active_tab;
	if ($wpcrm_reports_active_tab == 'organization') {
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/reports/organization-reports.php' );
	}
}
function wpcrm_system_report_contact_content() {
	global $wpcrm_reports_active_tab;
	if ($wpcrm_reports_active_tab == 'contact') {
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/reports/contact-reports.php' );
	}
}
function wpcrm_system_report_campaign_content() {
	global $wpcrm_reports_active_tab;
	if ($wpcrm_reports_active_tab == 'campaign') {
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/reports/campaign-reports.php' );
	}
}
?>
<h2 class="nav-tab-wrapper">
	<?php do_action( 'wpcrm_system_report_tab' ); ?>
</h2>
<?php
do_action( 'wpcrm_system_report_content' );
