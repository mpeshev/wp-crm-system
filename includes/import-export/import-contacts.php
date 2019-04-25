<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_import_contacts_process(){
	if( isset( $_FILES[ 'import_contacts' ] ) && isset( $_POST[ 'wpcrm_system_import_contacts_nonce' ] ) ){
		if( wp_verify_nonce( $_POST[ 'wpcrm_system_import_contacts_nonce' ], 'wpcrm-system-import-contacts-nonce' ) ){
			$errors			= array();
			$file_name		= $_FILES[ 'import_contacts' ][ 'name' ];
			$file_size		= $_FILES[ 'import_contacts' ][ 'size' ];
			$file_tmp		= $_FILES[ 'import_contacts' ][ 'tmp_name' ];
			$file_type		= $_FILES[ 'import_contacts' ][ 'type' ];
			$count_skipped	= 0;
			$count_added	= 0;
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
				$errors[] = __( 'File size must be less than', 'wp-crm-system' ) . ini_get( 'upload_max_filesize' ) . ' current file size is ' . $file_size;
			}
			if( empty( $errors ) == true ){
				$handle 	= fopen( $file_tmp, 'r' );
				$i 			= 0;
				$author_id 	= get_current_user_id();
				// List all prefix options in array.
				$prefix = array('mr', 'mrs', 'miss', 'ms', 'dr', 'master', 'coach', 'rev', 'fr', 'atty', 'prof', 'hon', 'pres', 'gov', 'ofc', 'supt', 'rep', 'sen', 'amb' );
				// List all current organizations in array.
				$orgs 	= get_posts( array( 'posts_per_page' => -1, 'post_type' => 'wpcrm-organization' ) );
				$posts 	= array();
				foreach ( $orgs as $post ) {
					$posts[] = $post->ID;
				}
				while( ( $fileop = fgetcsv( $handle, 5000, "," ) ) !== false ) {
					// get fields from uploaded CSV
					if ( in_array( $fileop[0], $prefix ) ) {
						$contactPrefix = $fileop[0];
					} else {
						$contactPrefix = '';
					}
					$contactFirst 	= sanitize_text_field( $fileop[1] );
					$contactLast 	= sanitize_text_field( $fileop[2] );
					if ( in_array( $fileop[3], $posts ) ){
						$contactOrg = $fileop[3];
					} else {
						$contactOrg = '';
					}
					$contactRole		= sanitize_text_field( $fileop[4] );
					$contactStreet1		= sanitize_text_field( $fileop[5] );
					$contactStreet2		= sanitize_text_field( $fileop[6] );
					$contactCity		= sanitize_text_field( $fileop[7] );
					$contactState		= sanitize_text_field( $fileop[8] );
					$contactZip			= sanitize_text_field( $fileop[9] );
					$contactCountry		= sanitize_text_field( $fileop[10] );
					$contactPhone		= sanitize_text_field( $fileop[11] );
					$contactFax			= sanitize_text_field( $fileop[12] );
					$contactMobile		= sanitize_text_field( $fileop[13] );
					$contactEmail		= sanitize_email( $fileop[14] );
					$contactURL			= esc_url_raw( $fileop[15] );
					$contactInfo		= wp_kses_post( wpautop( $fileop[16] ) );
					$contactCategories	= sanitize_text_field( $fileop[17] );
					$contactCategories	= explode( ', ', $contactCategories );
					$categories = array();
					foreach( $contactCategories as $category ) {
						$categories[] = $category;
					}
					$contactCategories = array_unique( $categories );

					//Get custom fields if there are any
					if( defined( 'WPCRM_CUSTOM_FIELDS' ) && function_exists( 'wpcrm_system_sanitize_imported_fields' ) ){
						$field_count = get_option( '_wpcrm_system_custom_field_count' );
						if( $field_count ){
							$custom_fields = array();
							for( $field = 1; $field <= $field_count; $field++ ){
								$import_id = 17 + $field;
								if( $fileop[$import_id] ){
									// Make sure we want this field to be imported.
									$field_type = get_option( '_wpcrm_custom_field_type_' . $field );
									$can_import = wpcrm_system_sanitize_imported_fields( $field, 'wpcrm-contact', $field_type, $fileop[$import_id] );
									if( $can_import ){
										$custom_fields[$field] = $fileop[$import_id];
									}
								}
							}
						}
					}

					// set some fields for new contact
					$post_id 	= -1;
					$slug 		= preg_replace( "/[^A-Za-z0-9]/", '', strtolower( $contactFirst ) ) . '-' . preg_replace( "/[^A-Za-z0-9]/", '', strtolower( $contactLast ) );
					$title 		= $contactFirst . ' ' . $contactLast;

					if( $i > 0 ) {
						// If the page doesn't already exist, then create it
						if( wpcrm_system_import_contacts_duplicate( $title, $fileop ) ) {
							$post_id = wp_insert_post(
								array(
									'comment_status'	=>	'closed',
									'ping_status'		=>	'closed',
									'post_author'		=>	$author_id,
									'post_name'			=>	$slug,
									'post_title'		=>	$title,
									'post_status'		=>	'publish',
									'post_type'			=>	'wpcrm-contact'
								)
							);
							//Add user's information to contact fields.
							if ( $contactPrefix != '' ) {
								add_post_meta( $post_id, '_wpcrm_contact-name-prefix', $contactPrefix, true );
							}
							if ( $contactFirst != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-first-name', $contactFirst, true );
							}
							if ( $contactLast != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-last-name', $contactLast, true );
							}
							if ( $contactOrg != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-attach-to-organization', $contactOrg, true );
							}
							if ( $contactRole != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-role', $contactRole, true );
							}
							if ( is_email( $contactEmail ) ) {
								add_post_meta( $post_id,'_wpcrm_contact-email', $contactEmail, true );
							}
							if ( $contactStreet1 != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-address1', $contactStreet1, true );
							}
							if ( $contactStreet2 != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-address2', $contactStreet2, true );
							}
							if ( $contactCity != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-city', $contactCity, true );
							}
							if ( $contactState != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-state', $contactState, true );
							}
							if ( $contactZip != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-postal', $contactZip, true );
							}
							if ( $contactCountry != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-country', $contactCountry, true );
							}
							if ( $contactPhone != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-phone', $contactPhone, true );
							}
							if ( $contactMobile != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-mobile-phone', $contactMobile, true );
							}
							if ( $contactFax != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-fax', $contactFax, true );
							}
							if ( $contactURL != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-website', $contactURL, true );
							}
							if ( $contactInfo != '' ) {
								add_post_meta( $post_id,'_wpcrm_contact-additional', $contactInfo, true );
							}
							if( $contactCategories != '' ) {
								$contactTypes = wp_set_object_terms( $post_id, $contactCategories, 'contact-type' );
								if ( is_wp_error( $contactTypes ) ) {
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
					<p><strong><?php _e( 'Contacts uploaded. ', 'wp-crm-system' ); echo $count_added; _e( ' added. ', 'wp-crm-system' ); echo $count_skipped; _e( ' skipped.', 'wp-crm-system' ); ?> </strong></p>
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
add_action( 'admin_init', 'wp_crm_system_import_contacts_process' );

function wpcrm_system_import_contacts_duplicate( $title, $fields ){
	$create_contact = false;
	if ( null == get_page_by_title( $title, OBJECT, 'wpcrm-contact' ) ){
		$create_contact = true;
	}
	//Contact will be created if returns true.
	//Contact will not be created if returns false.
	return apply_filters( 'wp_crm_system_import_contacts_duplicate', $create_contact, $title, $fields );
}