<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_import_organizations_process(){
	if( isset( $_FILES[ 'import_organizations' ] ) && isset( $_POST[ 'wpcrm_system_import_organizations_nonce' ] ) ){
		if( wp_verify_nonce( $_POST[ 'wpcrm_system_import_organizations_nonce' ], 'wpcrm-system-import-organizations-nonce' ) ){
			$errors			= array();
			$file_name 		= $_FILES['import_organizations']['name'];
			$file_size 		= $_FILES['import_organizations']['size'];
			$file_tmp 		= $_FILES['import_organizations']['tmp_name'];
			$file_type 		= $_FILES['import_organizations']['type'];
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
				$errors[]= __( 'File size must be less than', 'wp-crm-system' ) . ini_get( 'upload_max_filesize' );
			}
			if(empty( $errors)==true){

				$handle 	= fopen( $file_tmp, 'r' );
				$i 			= 0;
				$author_id 	= get_current_user_id();

				while( ( $fileop = fgetcsv( $handle, 5000, ",") ) !== false) {
					// get fields from uploaded CSV
					$orgName 		= sanitize_text_field( $fileop[0]);
					$orgPhone 		= sanitize_text_field( $fileop[1]);
					$orgEmail 		= sanitize_email( $fileop[2]);
					$orgURL 		= esc_url_raw( $fileop[3]);
					$orgStreet1 	= sanitize_text_field( $fileop[4]);
					$orgStreet2 	= sanitize_text_field( $fileop[5]);
					$orgCity 		= sanitize_text_field( $fileop[6]);
					$orgState 		= sanitize_text_field( $fileop[7]);
					$orgZip 		= sanitize_text_field( $fileop[8]);
					$orgCountry 	= sanitize_text_field( $fileop[9]);
					$orgInfo 		= wp_kses_post( wpautop( $fileop[10] ) );
					$orgCategories 	= sanitize_text_field( $fileop[11]);
					$orgCategories 	= explode( ', ', $orgCategories);
					$categories 	= array();
					foreach( $orgCategories as $category) {
						$categories[] = $category;
					}
					$orgCategories = array_unique( $categories);

					//Get custom fields if there are any
					if( defined( 'WPCRM_CUSTOM_FIELDS' ) && function_exists( 'wpcrm_system_sanitize_imported_fields' ) ){
						$field_count = get_option( '_wpcrm_system_custom_field_count' );
						if( $field_count ){
							$custom_fields = array();
							for( $field = 1; $field <= $field_count; $field++ ){
								$import_id = 11 + $field;
								if( $fileop[$import_id] ){
									// Make sure we want this field to be imported.
									$field_type = get_option( '_wpcrm_custom_field_type_' . $field );
									$can_import = wpcrm_system_sanitize_imported_fields( $field, 'wpcrm-organization', $field_type, $fileop[$import_id] );
									if( $can_import ){
										$custom_fields[$field] = $fileop[$import_id];
									}
								}
							}
						}
					}

					// set some fields for new organization
					$post_id 	= -1;
					$slug 		= preg_replace( "/[^A-Za-z0-9]/", '', strtolower( $orgName ) );
					$title 		= $orgName;

					if( $i > 0) {
						// If the page doesn't already exist, then create it
						if( null == get_page_by_title( $title, OBJECT, 'wpcrm-organization' ) ) {
							$post_id = wp_insert_post(
								array(
									'comment_status'	=>	'closed',
									'ping_status'		=>	'closed',
									'post_author'		=>	$author_id,
									'post_name'			=>	$slug,
									'post_title'		=>	$title,
									'post_status'		=>	'publish',
									'post_type'			=>	'wpcrm-organization'
								)
							);
							//Add user's information to organizations fields.
							if( $orgPhone != '' ) {
								add_post_meta( $post_id, '_wpcrm_organization-phone', $orgPhone, true );
							}
							if( $orgEmail != '' ) {
								add_post_meta( $post_id, '_wpcrm_organization-email', $orgEmail, true );
							}
							if( $orgURL != '' ){
								add_post_meta( $post_id, '_wpcrm_organization-website', $orgURL, true );
							}
							if( $orgStreet1 != '' ){
								add_post_meta( $post_id, '_wpcrm_organization-address1', $orgStreet1, true );
							}
							if( $orgStreet2 != '' ){
								add_post_meta( $post_id, '_wpcrm_organization-address2', $orgStreet2, true );
							}
							if( $orgCity != '' ){
								add_post_meta( $post_id, '_wpcrm_organization-city', $orgCity, true );
							}
							if( $orgState != '' ){
								add_post_meta( $post_id, '_wpcrm_organization-state', $orgState, true );
							}
							if( $orgZip != '' ){
								add_post_meta( $post_id, '_wpcrm_organization-postal', $orgZip, true );
							}
							if( $orgCountry != '' ){
								add_post_meta( $post_id, '_wpcrm_organization-country', $orgCountry, true );
							}
							if( $orgInfo != '' ){
								add_post_meta( $post_id, '_wpcrm_organization-information', $orgInfo, true );
							}
							if( $orgCategories != '' ) {
								$orgTypes = wp_set_object_terms( $post_id, $orgCategories, 'organization-type' );
								if ( is_wp_error( $orgTypes ) ) {
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

						if( $post_id) {
							if( $post_id < 0) {
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
					<p><strong><?php _e( 'Organizations uploaded. ', 'wp-crm-system' ); echo $count_added; _e( ' added. ', 'wp-crm-system' ); echo $count_skipped; _e( ' skipped.', 'wp-crm-system' ); ?> </strong></p>
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
add_action( 'admin_init', 'wp_crm_system_import_organizations_process' );