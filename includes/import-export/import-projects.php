<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_import_projects_process(){
	if( isset( $_FILES[ 'import_projects' ] ) && isset( $_POST[ 'wpcrm_system_import_projects_nonce' ] ) ){
		if( wp_verify_nonce( $_POST[ 'wpcrm_system_import_projects_nonce' ], 'wpcrm-system-import-projects-nonce' ) ){
			$errors			= array();
			$file_name 		= $_FILES['import_projects']['name'];
			$file_size 		= $_FILES['import_projects']['size'];
			$file_tmp 		= $_FILES['import_projects']['tmp_name'];
			$file_type 		= $_FILES['import_projects']['type'];
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
			if( empty( $errors ) == true ){

				$handle		= fopen( $file_tmp, 'r' );
				$i 			= 0;
				$author_id	= get_current_user_id();
				// List all progress options in array.
				$progress = array( 0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100 );
				// List all statuses in array.
				$status = array( 'not-started', 'in-progress', 'complete', 'on-hold' );
				// List all possible users in array.
				global $wpdb;
				$users = $wpdb->get_col("SELECT user_login FROM $wpdb->users");
				$wpdb->flush();
				// List all current organizations in array.
				$orgs 	= get_posts(array( 'posts_per_page'=>-1, 'post_type' => 'wpcrm-organization' ) );
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

				while( ( $fileop = fgetcsv( $handle, 5000, ",") ) !== false) {
					// get fields from uploaded CSV
					$projectName = sanitize_text_field( $fileop[0] );
					$projectDescription = wp_kses_post( wpautop( $fileop[1] ) );
					$projectClose = strtotime( $fileop[2] );
					if ( in_array( preg_replace( "/[^0-9]/", "", $fileop[3] ), $progress ) ) {
						$projectProgress = preg_replace( "/[^0-9]/", "", $fileop[3] );
					} else {
						$projectProgress = 'zero';
					}
					if ( in_array( $fileop[4], $status ) ) {
						$projectStatus = $fileop[4];
					} else {
						$projectStatus = 'not-started';
					}
					$projectValue = preg_replace( "/[^0-9]/", "", $fileop[5] );
					if ( in_array( $fileop[6], $users ) ){
						$projectAssigned = $fileop[6];
					} else {
						$projectAssigned = '';
					}
					if ( in_array( $fileop[7], $orgIDs ) ){
						$projectOrg = $fileop[7];
					} else {
						$projectOrg = '';
					}
					if ( in_array( $fileop[8], $contactIDs ) ) {
						$projectContact = $fileop[8];
					} else {
						$projectContact = '';
					}
					$projectCategories 	= sanitize_text_field( $fileop[9] );
					$projectCategories 	= explode( ', ', $projectCategories );
					$categories 		= array();
					foreach( $projectCategories as $category ) {
						$categories[] = $category;
					}
					$projectCategories = array_unique( $categories );

					//Get custom fields if there are any
					if( defined( 'WPCRM_CUSTOM_FIELDS' ) && function_exists( 'wpcrm_system_sanitize_imported_fields' ) ){
						$field_count = get_option( '_wpcrm_system_custom_field_count' );
						if( $field_count ){
							$custom_fields = array();
							for( $field = 1; $field <= $field_count; $field++ ){
								$import_id = 9 + $field;
								if( $fileop[$import_id] ){
									// Make sure we want this field to be imported.
									$field_type = get_option( '_wpcrm_custom_field_type_' . $field );
									$can_import = wpcrm_system_sanitize_imported_fields( $field, 'wpcrm-project', $field_type, $fileop[$import_id] );
									if( $can_import ){
										$custom_fields[$field] = $fileop[$import_id];
									}
								}
							}
						}
					}

					// set some fields for new project
					$post_id 	= -1;
					$slug 		= preg_replace("/[^A-Za-z0-9]/", '',strtolower( $projectName) );
					$title 		= $projectName;

					if( $i > 0 ) {
						// If the page doesn't already exist, then create it
						if( null == get_page_by_title( $title, OBJECT, 'wpcrm-project' ) ) {
							$post_id = wp_insert_post(
								array(
									'comment_status'	=>	'closed',
									'ping_status'		=>	'closed',
									'post_author'		=>	$author_id,
									'post_name'			=>	$slug,
									'post_title'		=>	$title,
									'post_status'		=>	'publish',
									'post_type'			=>	'wpcrm-project'
								)
							);
							//Add user's information to projects fields.
							if( $projectAssigned != '' ) {
								add_post_meta( $post_id, '_wpcrm_project-assigned', $projectAssigned, true );
							}
							if( $projectOrg != '' ) {
								add_post_meta( $post_id, '_wpcrm_project-attach-to-organization', $projectOrg, true );
							}
							if( $projectContact != '' ) {
								add_post_meta( $post_id, '_wpcrm_project-attach-to-contact', $projectContact, true );
							}
							if( $projectClose != '' ) {
								add_post_meta( $post_id, '_wpcrm_project-closedate', $projectClose, true );
							}
							if( $projectProgress != '' ) {
								add_post_meta( $post_id, '_wpcrm_project-progress', $projectProgress, true );
							}
							if( $projectValue != '' ) {
								add_post_meta( $post_id, '_wpcrm_project-value', $projectValue, true );
							}
							if( $projectStatus != '' ) {
								add_post_meta( $post_id, '_wpcrm_project-status', $projectStatus, true );
							}
							if( $projectDescription != '' ) {
								add_post_meta( $post_id, '_wpcrm_project-description', $projectDescription, true );
							}
							if( $projectCategories != '' ) {
								$projectTypes = wp_set_object_terms( $post_id, $projectCategories, 'project-type' );
								if ( is_wp_error( $projectTypes ) ) {
									$error[] = __( 'There was an error with the categories and they could not be set.', 'wp-crm-system-import-projects' );
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
					<p><strong><?php _e( 'Projects uploaded. ', 'wp-crm-system' ); echo $count_added; _e( ' added. ', 'wp-crm-system' ); echo $count_skipped; _e( ' skipped.', 'wp-crm-system' ); ?> </strong></p>
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
add_action( 'admin_init', 'wp_crm_system_import_projects_process' );