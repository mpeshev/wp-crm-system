<?php defined( 'ABSPATH' ) OR exit; ?>
<?php
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'overview';
?>
<h2 class="nav-tab-wrapper">
	<a class="nav-tab <?php echo $active_tab == 'overview' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=overview"><?php _e('Overview', 'wp-crm-system') ?></a>
	<a class="nav-tab <?php echo $active_tab == 'project' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=project"><?php _e('Project', 'wp-crm-system') ?></a>
	<a class="nav-tab <?php echo $active_tab == 'task' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=task"><?php _e('Task', 'wp-crm-system') ?></a>
	<a class="nav-tab <?php echo $active_tab == 'opportunity' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=opportunity"><?php _e('Opportunity', 'wp-crm-system') ?></a>
	<a class="nav-tab <?php echo $active_tab == 'organization' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=organization"><?php _e('Organization', 'wp-crm-system') ?></a>
	<a class="nav-tab <?php echo $active_tab == 'contact' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-reports&tab=contact"><?php _e('Contact', 'wp-crm-system') ?></a>
</h2>
<?php
if ($active_tab == 'overview') {
	wpcrm_reports_overview();
}
if ($active_tab == 'project') {
	wpcrm_project_reports();
}
if ($active_tab == 'opportunity') {
	wpcrm_opportunity_reports();
}
if ($active_tab == 'task') {
	wpcrm_task_reports();
}
if ($active_tab == 'organization') {
	wpcrm_organization_reports();
}
if ($active_tab == 'contact') {
	wpcrm_contact_reports();
}
function wpcrm_reports_overview() { 
	global $wpdb;
	include ('/includes/wp-crm-system-vars.php'); ?>
	<div class="wrap">
		<div>
			<h2><?php _e('WP CRM System Reports', 'wp-crm-system'); ?></h2>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<tr>
						<td>
							<strong><?php _e('Value of Opportunities', 'wp-crm-system'); ?></strong>	
						</td>
						<td>
							<?php
							$opp_val = $prefix . 'opportunity-value';
							$organization_value = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s", $opp_val));
							echo strtoupper(get_option('wpcrm_system_default_currency')) . ' ' . number_format(array_sum( $organization_value ),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator'));
							$wpdb->flush(); ?> 
						</td>
					</tr>
					<tr>
						<td>
							<strong><?php _e('Value of Projects', 'wp-crm-system'); ?></strong>	
						</td>
						<td>
							<?php
							$project_val = $prefix . 'project-value';
							$proj_value = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s", $project_val));
							echo strtoupper(get_option('wpcrm_system_default_currency')) . ' ' . number_format(array_sum( $proj_value ),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator'));
							$wpdb->flush(); ?> 
						</td>
					</tr>
					<tr>
						<td>
							<strong><?php _e('Overdue Tasks', 'wp-crm-system'); ?></strong>
						</td>
						<td>
							<?php
							$meta_key1 = $prefix . 'task-status';
							$meta_key1_value = 'complete';
							$meta_key2 = $prefix . 'task-due-date';
							global $post;
							$args = array(
								'post_type'		=>	'wpcrm-task',
								'meta_query'	=> array(
									'relation'		=>	'AND',
									array(
										'key'		=>	$meta_key1,
										'value'		=>	$meta_key1_value,
										'compare'	=>	'!=',
									),
									array(
										'key'		=>	$meta_key2,
										'value'		=>	strtotime('now'),
										'compare'	=>	'<',
									),
								),
							);
							$posts = get_posts($args);
							if ($posts) {
								$i = 0;
								foreach($posts as $post) {
									$i++;
								}
								$overdue_report = 'admin.php?page=wpcrm-reports&tab=task&report=overdue_tasks';
								printf( _n('There is %d overdue task. ', 'There are %d overdue tasks. ', $i, 'wp-crm-system'), $i);
								$link = sprintf( wp_kses(__('<a href="%s">View the overdue task report.</a>', 'wp-crm-system'), array(  'a' => array( 'href' => array() ) ) ), esc_url( $overdue_report ) );
								echo $link;
							} else {
								_e('No tasks are overdue!', 'wp-crm-system');
							}
							?> 
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
<?php }
function wpcrm_project_reports() {
	include ('includes/reports/project-reports.php');
}
function wpcrm_opportunity_reports() {
	include ('includes/reports/opportunity-reports.php');
}
function wpcrm_task_reports() {
	include ('includes/reports/task-reports.php');
}
function wpcrm_organization_reports() { 
	include ('includes/reports/organization-reports.php');
}
function wpcrm_contact_reports() {
	include ('includes/reports/contact-reports.php');
}