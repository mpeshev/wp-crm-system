<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_import_tasks_process(){
	if( isset( $_FILES[ 'import_tasks' ] ) && isset( $_POST[ 'wpcrm_system_import_tasks_nonce' ] ) ){
		if( wp_verify_nonce( $_POST[ 'wpcrm_system_import_tasks_nonce' ], 'wpcrm-system-import-tasks-nonce' ) ){
			$errors			= array();
			$file_name 		= $_FILES['import_tasks']['name'];
			$file_size 		= $_FILES['import_tasks']['size'];
			$file_tmp 		= $_FILES['import_tasks']['tmp_name'];
			$file_type 		= $_FILES['import_tasks']['type'];
			$count_skipped 	= 0;
			$count_added 	= 0;
			$csv_types 		= array(
				'text/csv',
				'text/plain',
				'application/csv',
				'text/comma-separated-values',
				'application/excel',
				'application/vnd.ms-excel',
				'application/vnd.msexcel',
				'text/anytext',
				'application/octet-stream',
				'application/txt',
			);

			if( !in_array( $file_type, $csv_types ) ){
				$errors[] = __( 'File not allowed, please use a CSV file.', 'wp-crm-system' );
			}
			if( $file_size > wp_crm_system_return_bytes( ini_get( 'upload_max_filesize' ) ) ){
				$errors[] = __( 'File size must be less than', 'wp-crm-system' ) . ini_get( 'upload_max_filesize' );
			}
			if( empty ( $errors ) == true ){

				$handle 	= fopen( $file_tmp, 'r' );
				$i 			= 0;
				$author_id 	= get_current_user_id();
				// List all possible users in array.
				global $wpdb;
				$users = $wpdb->get_col( "SELECT user_login FROM $wpdb->users" );
				$wpdb->flush();
				$org_val = 'wpcrm-organization';
				// List all current organizations in array.
				$orgs = get_posts( array( 'posts_per_page' => -1, 'post_type' => 'wpcrm-organization' ) );
				$orgIDs = array();
				foreach ( $orgs as $post) {
					$orgIDs[] = $post->ID;
				}
				// List all contacts in array.
				$contacts = get_posts( array( 'posts_per_page' => -1, 'post_type' => 'wpcrm-contact' ) );
				$contactIDs = array();
					foreach ( $contacts as $post ) {
						$contactIDs[] = $post->ID;
					}

				// List all progress options in array.
				$progress = array( 0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100 );
				// List all priority options in array.
				$priority = array( 'low', 'medium', 'high' );
				// List all statuses in array.
				$status = array( 'not-started', 'in-progress', 'complete', 'on-hold' );
				while( ( $fileop = fgetcsv( $handle, 5000, "," ) ) !== false ) {
					// get fields from uploaded CSV
					$taskName = sanitize_text_field( $fileop[0] );
					if ( in_array( $fileop[1], $users ) ){
						$taskAssigned = $fileop[1];
					} else {
						$taskAssigned = '';
					}
					if ( in_array( $fileop[2], $orgIDs ) ){
						$taskOrg = $fileop[2];
					} else {
						$taskOrg = '';
					}
					if ( in_array( $fileop[3], $contactIDs ) ) {
						$taskContact = $fileop[3];
					} else {
						$taskContact = '';
					}
					$taskDue = strtotime( $fileop[4] );
					$taskStart = strtotime( $fileop[5] );
					if ( in_array( preg_replace( "/[^0-9]/", "", $fileop[6] ), $progress ) ) {
						$taskProgress = preg_replace( "/[^0-9]/", "", $fileop[6] );
					} else {
						$taskProgress = 'zero';
					}
					if ( in_array( $fileop[7], $priority ) ) {
						$taskPriority = $fileop[7];
					} else {
						$taskPriority = '';
					}
					if ( in_array( $fileop[8], $status ) ) {
						$taskStatus = $fileop[8];
					} else {
						$taskStatus = 'not-started';
					}
					$taskDescription = wp_kses_post( wpautop( $fileop[9] ) );
					$taskCategories = sanitize_text_field( $fileop[10] );
					$taskCategories = explode( ', ', $taskCategories );
					$categories = array();
					foreach( $taskCategories as $category ) {
						$categories[] = $category;
					}
					$taskCategories = array_unique( $categories );

					//Get custom fields if there are any
					if( defined( 'WPCRM_CUSTOM_FIELDS' ) && function_exists( 'wpcrm_system_sanitize_imported_fields' ) ){
						$field_count = get_option( '_wpcrm_system_custom_field_count' );
						if( $field_count ){
							$custom_fields = array();
							for( $field = 1; $field <= $field_count; $field++ ){
								$import_id = 10 + $field;
								if( $fileop[$import_id] ){
									// Make sure we want this field to be imported.
									$field_type = get_option( '_wpcrm_custom_field_type_' . $field );
									$can_import = wpcrm_system_sanitize_imported_fields( $field, 'wpcrm-task', $field_type, $fileop[$import_id] );
									if( $can_import ){
										$custom_fields[$field] = $fileop[$import_id];
									}
								}
							}
						}
					}

					// set some fields for new task
					$post_id	= -1;
					$slug 		= preg_replace( "/[^A-Za-z0-9]/", '', strtolower( $taskName ) );
					$title 		= $taskName;

					if( $i > 0) {
						// If the page doesn't already exist, then create it
						if( null == get_page_by_title( $title, OBJECT, 'wpcrm-task' ) ) {
							$post_id = wp_insert_post(
								array(
									'comment_status'	=>	'closed',
									'ping_status'		=>	'closed',
									'post_author'		=>	$author_id,
									'post_name'			=>	$slug,
									'post_title'		=>	$title,
									'post_status'		=>	'publish',
									'post_type'			=>	'wpcrm-task'
								)
							);
							//Add user's information to tasks fields.
							if( $taskAssigned != '' ) {
								add_post_meta( $post_id, '_wpcrm_task-assignment', $taskAssigned, true );
							}
							if( $taskOrg != '' ) {
								add_post_meta( $post_id, '_wpcrm_task-attach-to-organization', $taskOrg, true );
							}
							if( $taskContact != '' ) {
								add_post_meta( $post_id, '_wpcrm_task-attach-to-contact', $taskContact, true );
							}
							if( $taskDue != '' ) {
								add_post_meta( $post_id, '_wpcrm_task-due-date', $taskDue, true );
							}
							if( $taskStart != '' ) {
								add_post_meta( $post_id, '_wpcrm_task-start-date', $taskStart, true );
							}
							if( $taskProgress != '' ) {
								add_post_meta( $post_id, '_wpcrm_task-progress', $taskProgress, true );
							}
							if( $taskPriority != '' ) {
								add_post_meta( $post_id, '_wpcrm_task-priority', $taskPriority, true );
							}
							if( $taskStatus != '' ) {
								add_post_meta( $post_id, '_wpcrm_task-status', $taskStatus, true );
							}
							if( $taskDescription != '' ) {
								add_post_meta( $post_id, '_wpcrm_task-description', $taskDescription, true );
							}
							if( $taskCategories != '' ) {
								$taskTypes = wp_set_object_terms( $post_id, $taskCategories, 'task-type' );
								if ( is_wp_error( $taskTypes ) ) {
									$error[] = __( 'There was an error with the categories and they could not be set.', 'wp-crm-system' );
								}
							}
							if( isset( $custom_fields ) && is_array( $custom_fields ) ){
								foreach( $custom_fields as $id => $value ){
									add_post_meta( $post_id, '_wpcrm_custom_field_id_' . $id, $value, true );
								}
							}

						// Otherwise, we'll stop
						} else {
							// Arbitrarily use -2 to indicate that the page with the title already exists
							$post_id = -2;
						} //end if

						if( $post_id ) {
							if( $post_id < 0 ) {
								$count_skipped++;
							} else {
								$count_added++;
							}
						}
					}
					$i++;
				}
				fclose( $handle );
				?>
				<div id="message" class="updated">
					<p><strong><?php _e( 'Tasks uploaded. ', 'wp-crm-system' ); echo $count_added; _e( ' added. ', 'wp-crm-system' ); echo $count_skipped; _e( ' skipped.', 'wp-crm-system' ); ?> </strong></p>
				</div>
			<?php } else { ?>
			<div id="message" class="error">
				<?php
				foreach( $errors as $error ){
					echo $error;
				} ?>
			</div>
			<?php }
		}
	}
}
add_action( 'admin_init', 'wp_crm_system_import_tasks_process' );