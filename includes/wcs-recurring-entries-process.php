<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}
function wp_crm_system_process_recurring_entries() {

	global $wpdb;
	global $wpcrm_system_recurring_db_name;

	$wp_crm_system_post = ( !empty( $_POST ) ) ? true : false;
	if( $wp_crm_system_post ) // if data is being sent
	{

		if( ! current_user_can( wpcrm_system_get_required_user_role() ) )
			return;

		// process the "add new entry" function
		if( isset( $_POST['add_new_entry'] ) && $_POST['add_new_entry'] == 1 && wp_verify_nonce( $_POST['wp_crm_system_recurring_entry_nonce'], 'wp-crm-system-recurring-entry-nonce' ) ) {
			if( !isset( $_POST['project_task_id'] ) ){
				$page_id = '';
			} else {
				$page_id = $_POST['project_task_id'];
			}
			$start_date	= wp_crm_system_process_datetime( $_POST['start_date'] );
			$end_date	= wp_crm_system_process_datetime( $_POST['end_date'] );
			$new_entry = $wpdb->insert( $wpcrm_system_recurring_db_name,
				array(
					'project_task'			=> get_post_type( $page_id ),
					'project_task_id'		=> $page_id,
					'start_date'			=> $start_date,
					'end_date'				=> $end_date,
					'frequency'				=> $_POST['frequency'],
					'number_per_frequency'	=> $_POST['number']
					)
				);
				if( $new_entry ) {
					$url = 'admin.php?page=wpcrm-settings&tab=settings&subtab=recurring-entries&entry-added=success';
				} else {
					$url = 'admin.php?page=wpcrm-settings&tab=settings&subtab=recurring-entries&entry-added=failed';
				}
				wp_redirect( $url ); exit;
			}

			// process updates to an entry
			if( isset( $_POST['edit_entry'] ) && wp_verify_nonce( $_POST['wp_crm_system_recurring_entry_nonce'], 'wp-crm-system-recurring-entry-nonce' ) ) {
				if( !isset( $_POST['project_task_id'] ) ){
					$page_id = '';
				} else {
					$page_id = $_POST['project_task_id'];
				}
				$start_date	= wp_crm_system_process_datetime( $_POST['start_date'] );
				$end_date	= wp_crm_system_process_datetime( $_POST['end_date'] );
				$edit_entry = $wpdb->update( $wpcrm_system_recurring_db_name,
					array(
						'project_task'			=> get_post_type( $page_id ),
						'project_task_id'		=> $page_id,
						'start_date'			=> $start_date,
						'end_date'				=> $end_date,
						'frequency'				=> $_POST['frequency'],
						'number_per_frequency'	=> $_POST['number']
					),
					array(
						'id' => $_POST['edit_entry']
					)
				);
			if( false !== $edit_entry ) {
				$url = 'admin.php?page=wpcrm-settings&tab=settings&subtab=recurring-entries&entry-edited=success';
			} else {
				$url = 'admin.php?page=wpcrm-settings&tab=settings&subtab=recurring-entries&entry-edited=failed';
			}
			wp_redirect( $url ); exit;
		}

		// process entry deletions
		if( isset( $_POST['delete_entry'] ) && wp_verify_nonce( $_POST['wp_crm_system_recurring_entry_nonce'], 'wp-crm-system-recurring-entry-nonce' ) ) {
			$delete_entry = $wpdb->query( $wpdb->prepare( "DELETE FROM " . $wpcrm_system_recurring_db_name . " WHERE id = '%d';", absint( $_POST['delete_entry'] ) ) );
			if( $delete_entry ) {
				$url = 'admin.php?page=wpcrm-settings&tab=settings&subtab=recurring-entries&entry-deleted=success';
			} else {
				$url = 'admin.php?page=wpcrm-settings&tab=settings&subtab=recurring-entries&entry-deleted=failed';
			}
			wp_redirect( $url ); exit;
		}
	}
}
add_action( 'admin_init', 'wp_crm_system_process_recurring_entries' );