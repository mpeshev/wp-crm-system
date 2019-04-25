<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}
function wpcrm_system_system_recurring_tab() {
	//Get current dashboard tab name
	global $wpcrm_active_tab; ?>
	<a class="nav-tab <?php echo $wpcrm_active_tab == 'recurring' ? 'current' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=wpcrm-settings&tab=recurring' ); ?>"><?php _e( 'Recurring', 'wp-crm-system' ); ?> </a>
<?php }
add_action( 'wpcrm_system_settings_tab', 'wpcrm_system_system_recurring_tab', 3 );

add_action( 'wpcrm_system_settings_content', 'wpcrm_system_recurring_entries' );
function wpcrm_system_recurring_entries(){
	global $wpdb, $wpcrm_active_tab, $wpcrm_system_recurring_db_name;

	if ( 'recurring' == $wpcrm_active_tab && !isset( $_GET['action'] ) ) { ?>
		<div class="wrap">
			<h2><?php _e( 'Recurring Projects and Tasks', 'wp-crm-system' ); ?></h2>

			<p>
				<a class="button-primary" href="admin.php?page=wpcrm-settings&tab=settings&subtab=recurring-entries&action=add_new"><?php _e( 'Add New Recurring Entry', 'wp-crm-system' ); ?></a>
			</p>

			<table class="wp-list-table widefat fixed posts" id="wp_crm_system_recurring_entries_table">
				<thead>
					<tr>
						<th style="width: 40px;"><?php _e( 'ID', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'Project/Task', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'Project/Task Name', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'Start Date', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'End Date', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'Repeats Every', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'Edit', 'wp-crm-system' ); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th style="width: 40px;"><?php _e( 'ID', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'Project/Task', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'Project/Task Name', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'Start Date', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'End Date', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'Repeats Every', 'wp-crm-system' ); ?></th>
						<th><?php _e( 'Edit', 'wp-crm-system' ); ?></th>
					</tr>
				</tfoot>
				<tbody>
				<?php
				$entries = $wpdb->get_results("SELECT * FROM " . $wpcrm_system_recurring_db_name . " ORDER BY id;");
				if( $entries ) :
					foreach( $entries as $key => $entry ) {
						switch ( $entry->project_task ) {
							case 'wpcrm-project':
								$type = __( 'Project', 'wp-crm-system' );
								break;

							case 'wpcrm-task':
								$type = __( 'Task', 'wp-crm-system' );
								break;

							default:
								$type = '';
								break;
						}
						// Account for singular/plural variations of day/week/month/year in the table
						$frequency = array(
							'day'	=> __( 'Day', 'wp-crm-system' ),
							'week'	=> __( 'Week', 'wp-crm-system'),
							'month'	=> __( 'Month', 'wp-crm-system'),
							'year'	=> __( 'Year', 'wp-crm-system'),
						);
						if( $entry->number_per_frequency > 1 ){
							$frequency = array(
								'day'	=> __( 'Days', 'wp-crm-system' ),
								'week'	=> __( 'Weeks', 'wp-crm-system'),
								'month'	=> __( 'Months', 'wp-crm-system'),
								'year'	=> __( 'Years', 'wp-crm-system'),
							);
						}
						$start_date	= strtotime( $entry->start_date );
						$end_date	= strtotime( $entry->end_date );
						/* Should only legitimately be 0 if the date is supposed to be January 1, 1970. No other dates will be affected.
						 * Any other 0 value indicates no value was saved, and therefore shouldn't be output as a date.
						 */
						if ( isset( $start_date ) && is_numeric( $start_date ) && 0 != $start_date ) {
							$start_date = date( get_option( 'wpcrm_system_php_date_format' ), $start_date );
						} else {
							$start_date = __( 'Not set', 'wp-crm-system' );
						}
						if ( isset( $end_date ) && is_numeric( $end_date ) && 0 != $end_date ) {
							$end_date = date( get_option( 'wpcrm_system_php_date_format' ), $end_date );
						} else {
							$end_date = __( 'Not set', 'wp-crm-system' );
						}
						?>
						<tr>
							<td><?php echo $entry->id; ?></td>
							<td><?php echo $type; ?></td>
							<td><?php echo get_the_title( $entry->project_task_id ); ?></td>
							<td><?php echo $start_date; ?></td>
							<td><?php echo $end_date; ?></td>
							<td><?php echo $entry->number_per_frequency . ' ' . $frequency[$entry->frequency]; ?></td>
							<td>
								<a class="button-secondary" href="admin.php?page=wpcrm-settings&tab=settings&subtab=recurring-entries&action=edit&entry_id=<?php echo $entry->id; ?>"><?php _e( 'Edit', 'wp-crm-system' ); ?></a>
								<a class="button-secondary" href="admin.php?page=wpcrm-settings&tab=settings&subtab=recurring-entries&action=delete&entry_id=<?php echo $entry->id; ?>"><?php _e( 'Delete', 'wp-crm-system' ); ?></a>
							</td>
						</tr>
					<?php }
				else : ?>
					<tr>
						<td colspan=6><?php _e( 'You have not created any recurring entries yet.', 'wp-crm-system' ); ?>
					</tr>
				<?php endif;?>
				</tbody>
			</table>

			<p class="submit">
				<a class="button-primary" href="admin.php?page=wpcrm-settings&tab=settings&subtab=recurring-entries&action=add_new"><?php _e( 'Add New Recurring Entry', 'wp-crm-system' ); ?></a>
			</p>
		</div><!--end wrap-->
	<?php }
	if ( 'recurring' == $wpcrm_active_tab && isset( $_GET['action'] ) && 'add_new' == $_GET['action'] ) {
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-recurring-entries-add-new.php' );
	}
	if ( 'recurring' == $wpcrm_active_tab && isset( $_GET['action'] ) && 'edit' == $_GET['action'] ) {
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-recurring-entries-edit.php' );
	}
	if ( 'recurring' == $wpcrm_active_tab && isset( $_GET['action'] ) && 'delete' == $_GET['action'] ) {
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-recurring-entries-delete.php' );
	}
}