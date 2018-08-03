<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}
add_action( 'wp_crm_system_recurring_entry_processor', 'wp_crm_system_create_recurring_entry' );
function wp_crm_system_create_recurring_entry(){
	global $wpdb, $wpcrm_system_recurring_db_name;
	$entries	= $wpdb->get_results("SELECT * FROM " . $wpcrm_system_recurring_db_name . ";");
	foreach ( $entries as $entry ){
		$id	= $entry->project_task_id;
		/* Compare the start/end times to now to see if we need to worry about creating an entry. */
		$start_date	= strtotime( $entry->start_date );
		$end_date	= strtotime( $entry->end_date );
		$now		= time();
		if ( $start_date <= $now && $end_date >= $now ){

			$transient	= get_transient( 'wp_crm_system_recurring_entry_' . $entry->id );

			if ( !$transient ){
				/* Transient is not set so we need to create a new project or task entry and set a new transient */
				$create = new WPCRM_System_Create;
				switch ( $entry->project_task ) {
					case 'wpcrm-project':
						$fields = array(
							'title'			=> apply_filters( 'wp_crm_system_recurring_project_title', get_the_title( $id ) . ' - ' . date( get_option( 'wpcrm_system_php_date_format' ) ), $id ),
							'value'			=> apply_filters( 'wp_crm_system_recurring_project_value', get_post_meta( $id, '_wpcrm_project-value', true ), $id ),
							'close_date'	=> apply_filters( 'wp_crm_system_recurring_project_close_date', date( get_option( 'wpcrm_system_php_date_format' ), get_post_meta( $id, '_wpcrm_project-closedate', true ) ), $id ),
							'status'		=> apply_filters( 'wp_crm_system_recurring_project_status', get_post_meta( $id, '_wpcrm_project-status', true ), $id ),
							'progress'		=> apply_filters( 'wp_crm_system_recurring_project_progress', get_post_meta( $id, '_wpcrm_project-progress', true ), $id ),
							'org'			=> apply_filters( 'wp_crm_system_recurring_project_org', get_the_title( get_post_meta( $id, '_wpcrm_project-attach-to-organization', true ) ), $id ),
							'contact'		=> apply_filters( 'wp_crm_system_recurring_project_contact', get_the_title( get_post_meta( $id, '_wpcrm_project-attach-to-contact', true ) ), $id ),
							'assigned'		=> apply_filters( 'wp_crm_system_recurring_project_assigned', get_post_meta( $id, '_wpcrm_project-assigned', true ), $id ),
							'additional'	=> apply_filters( 'wp_crm_system_recurring_project_additional', get_post_meta( $id, '_wpcrm_project-description', true ), $id ),
						);
						//Get custom fields if there are any
						$custom_fields = array();
						if( defined( 'WPCRM_CUSTOM_FIELDS' ) && function_exists( 'wpcrm_system_sanitize_imported_fields' ) ){
							$field_count = get_option( '_wpcrm_system_custom_field_count' );
							if( $field_count ){
								$custom_fields = array();
								for( $field = 1; $field <= $field_count; $field++ ){
									// Make sure we want this field to be imported.
									$field_type = get_option( '_wpcrm_custom_field_type_' . $field );
									$can_import = wpcrm_system_sanitize_imported_fields( $field, 'wpcrm-project', $field_type, get_post_meta( $id, '_wpcrm_custom_field_id_' . $field, true ) );
									if( $can_import ){
										$field_name = get_option( '_wpcrm_custom_field_name_' . $field );
										$custom_fields[$field_name] = $can_import;
									}
								}
							}
						}
						$categories	= apply_filters( 'wp_crm_system_recurring_project_categories', get_the_terms( $id, 'project-type' ), $id );
						$cats		= array();
						foreach ( $categories as $category ){
							$cats[] = $category->name;
						}
						$categories	= implode( ',', $cats );
						$status		= apply_filters( 'wp_crm_system_recurring_project_post_status', get_post_status( $id ), $id );
						$author		= apply_filters( 'wp_crm_system_recurring_project_post_author', get_post_field( 'post_author', $id ), $id );
						$update		= apply_filters( 'wp_crm_system_recurring_project_update', false, $id );

						$post_id = $create->projects( $fields, $custom_fields, $categories, $status, $author, $update );
						break;

					case 'wpcrm-task':
						$fields = array(
							'title'			=> apply_filters( 'wp_crm_system_recurring_task_title', get_the_title( $id ) . ' - ' . date( get_option( 'wpcrm_system_php_date_format' ) ), $id ),
							'org'			=> apply_filters( 'wp_crm_system_recurring_task_org', get_the_title( get_post_meta( $id, '_wpcrm_task-attach-to-organization', true ) ), $id ),
							'contact'		=> apply_filters( 'wp_crm_system_recurring_task_contact', get_the_title( get_post_meta( $id, '_wpcrm_task-attach-to-contact', true ) ), $id ),
							'project'		=> apply_filters( 'wp_crm_system_recurring_task_project', get_the_title( get_post_meta( $id, '_wpcrm_task-attach-to-project', true ) ), $id ),
							'assigned'		=> apply_filters( 'wp_crm_system_recurring_task_assigned', get_post_meta( $id, '_wpcrm_task-assignment', true ), $id ),
							'start_date'	=> apply_filters( 'wp_crm_system_recurring_task_start_date', date( get_option( 'wpcrm_system_php_date_format' ), get_post_meta( $id, '_wpcrm_task-start-date', true ) ), $id ),
							'due_date'		=> apply_filters( 'wp_crm_system_recurring_task_due_date', date( get_option( 'wpcrm_system_php_date_format' ), get_post_meta( $id, '_wpcrm_task-due-date', true ) ), $id ),
							'progress'		=> apply_filters( 'wp_crm_system_recurring_task_progress', get_post_meta( $id, '_wpcrm_task-progress', true ), $id ),
							'priority'		=> apply_filters( 'wp_crm_system_recurring_task_priority', get_post_meta( $id, '_wpcrm_task-priority', true ), $id ),
							'status'		=> apply_filters( 'wp_crm_system_recurring_task_status', get_post_meta( $id, '_wpcrm_task-status', true ), $id ),
							'additional'	=> apply_filters( 'wp_crm_system_recurring_task_additional', get_post_meta( $id, '_wpcrm_task-description', true ), $id ),
						);
						//Get custom fields if there are any
						$custom_fields = array();
						if( defined( 'WPCRM_CUSTOM_FIELDS' ) && function_exists( 'wpcrm_system_sanitize_imported_fields' ) ){
							$field_count = get_option( '_wpcrm_system_custom_field_count' );
							if( $field_count ){
								$custom_fields = array();
								for( $field = 1; $field <= $field_count; $field++ ){
									// Make sure we want this field to be imported.
									$field_type = get_option( '_wpcrm_custom_field_type_' . $field );
									switch ( $field_type ) {
										case 'repeater-text':
										case 'repeater-textarea':
										case 'repeater-date':
											$custom_field_value	= get_post_meta( $id, '_wpcrm_custom_field_id_' . $field );
											break;

										default:
											$custom_field_value	= get_post_meta( $id, '_wpcrm_custom_field_id_' . $field, true );
											break;
									}
									$can_import = wpcrm_system_sanitize_imported_fields( $field, 'wpcrm-task', $field_type, $custom_field_value );
									if( $can_import ){
										$field_name = get_option( '_wpcrm_custom_field_name_' . $field );
										$custom_fields[$field_name] = $can_import;
									}
								}
							}
						}
						$categories	= apply_filters( 'wp_crm_system_recurring_task_categories', get_the_terms( $id, 'task-type' ), $id );
						$cats		= array();
						foreach ( $categories as $category ){
							$cats[] = $category->name;
						}
						$categories	= implode( ',', $cats );
						$status		= apply_filters( 'wp_crm_system_recurring_task_post_status', get_post_status( $id ), $id );
						$author		= apply_filters( 'wp_crm_system_recurring_task_post_author', get_post_field( 'post_author', $id ), $id );
						$update		= apply_filters( 'wp_crm_system_recurring_task_update', false, $id );

						$post_id	= $create->tasks( $fields, $custom_fields, $categories, $status, $author, $update );
						break;

					default:
						$post_id = false;
						break;
				}
				if ( $post_id ){
					switch ( $entry->frequency ) {
						case 'day':
							$expiration = $entry->number_per_frequency * DAY_IN_SECONDS;
							break;
						case 'week':
							$expiration = $entry->number_per_frequency * WEEK_IN_SECONDS;
							break;
						case 'month':
							$expiration = $entry->number_per_frequency * MONTH_IN_SECONDS;
							break;
						default:
							//year
							$expiration = $entry->number_per_frequency * YEAR_IN_SECONDS;
							break;
					}

					set_transient( 'wp_crm_system_recurring_entry_' . $entry->id, $post_id, $expiration );

				}

			}

		}

	}

}