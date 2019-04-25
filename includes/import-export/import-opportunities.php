<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_import_opportunities_process(){
	if( isset( $_FILES[ 'import_opportunities' ] ) && isset( $_POST[ 'wpcrm_system_import_opportunities_nonce' ] ) ){
		if( wp_verify_nonce( $_POST[ 'wpcrm_system_import_opportunities_nonce' ], 'wpcrm-system-import-opportunities-nonce' ) ){
			$errors			= array();
			$file_name 		= $_FILES[ 'import_opportunities' ][ 'name' ];
			$file_size 		= $_FILES[ 'import_opportunities' ][ 'size' ];
			$file_tmp 		= $_FILES[ 'import_opportunities' ][ 'tmp_name' ];
			$file_type 		= $_FILES[ 'import_opportunities' ][ 'type' ];
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

				$handle 	= fopen( $file_tmp, 'r' );
				$i 			= 0;
				$author_id 	= get_current_user_id();
				global $wpdb;
				//List all users
				$users = $wpdb->get_col( "SELECT user_login FROM $wpdb->users" );
				$wpdb->flush();
				// List all current organizations in array.
				$orgs 	= get_posts( array( 'posts_per_page'=>-1, 'post_type' => 'wpcrm-organization' ) );
				$orgIDs = array();
					foreach ( $orgs as $post) {
						$orgIDs[] = $post->ID;
					}
				// List all contacts in array.
				$contacts 	= get_posts(array( 'posts_per_page'=>-1, 'post_type' => 'wpcrm-contact' ) );
				$contactIDs = array();
					foreach ( $contacts as $post) {
						$contactIDs[] = $post->ID;
					}
				// List all campaigns in array.
				$campaigns 		= get_posts(array( 'posts_per_page'=>-1, 'post_type' => 'wpcrm-campaign' ) );
				$campaignIDs 	= array();
					foreach ( $campaigns as $post) {
						$campaignIDs[] = $post->ID;
					}
				// List all won/lost statuses in array.
				$wonLost 		= array( 'won', 'lost', 'suspended', 'abandoned' );
				// List all probability options in array.
				$probability 	= array( 0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100 );
				while( ( $fileop = fgetcsv( $handle, 5000, "," ) ) !== false ) {
					// get fields from uploaded CSV
					$oppName = sanitize_text_field( $fileop[0] );
					if ( in_array( $fileop[1], $users ) ){
						$oppAssigned = $fileop[1];
					} else {
						$oppAssigned = '';
					}
					if ( in_array( $fileop[2], $orgIDs ) ){
						$oppOrg = $fileop[2];
					} else {
						$oppOrg = '';
					}
					if ( in_array( $fileop[3], $contactIDs ) ) {
						$oppContact = $fileop[3];
					} else {
						$oppContact = '';
					}
					if ( in_array( $fileop[4], $campaignIDs ) ) {
						$oppCampaign = $fileop[4];
					} else {
						$oppCampaign = '';
					}
					$oppDescription = wp_kses_post( wpautop( $fileop[5] ) );
					if ( in_array( preg_replace( "/[^0-9]/", "", $fileop[6] ), $probability ) ) {
						$oppProbability = preg_replace("/[^0-9]/", "", $fileop[6] );
					} else {
						$oppProbability = 'zero';
					}
					$oppClose = strtotime( $fileop[7] );
					$oppValue = preg_replace("/[^0-9]/", "", $fileop[8] );
					if ( in_array( strtolower( $fileop[9] ), $wonLost ) ) {
						$oppWonLost = strtolower( $fileop[9] );
					} else {
						$oppWonLost = '';
					}
					$opportunityCategories = sanitize_text_field( $fileop[10] );
					$opportunityCategories = explode( ', ', $opportunityCategories );
					$categories = array();
					foreach( $opportunityCategories as $category ) {
						$categories[] = $category;
					}
					$opportunityCategories = array_unique( $categories );

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
									$can_import = wpcrm_system_sanitize_imported_fields( $field, 'wpcrm-opportunity', $field_type, $fileop[$import_id] );
									if( $can_import ){
										$custom_fields[$field] = $fileop[$import_id];
									}
								}
							}
						}
					}

					// set some fields for new opportunity
					$post_id	= -1;
					$slug 		= preg_replace( "/[^A-Za-z0-9]/", '', strtolower( $oppName ) );
					$title 		= $oppName;

					if( $i > 0 ) {
						// If the page doesn't already exist, then create it
						if( null == get_page_by_title( $title, OBJECT, 'wpcrm-opportunity' ) ) {
							$post_id = wp_insert_post(
								array(
									'comment_status'	=>	'closed',
									'ping_status'		=>	'closed',
									'post_author'		=>	$author_id,
									'post_name'			=>	$slug,
									'post_title'		=>	$title,
									'post_status'		=>	'publish',
									'post_type'			=>	'wpcrm-opportunity'
								)
							);
							//Add user's information to opportunities fields.
							if( $oppAssigned != '' ) {
								add_post_meta( $post_id, '_wpcrm_opportunity-assigned', $oppAssigned, true );
							}
							if( $oppOrg != '' ) {
								add_post_meta( $post_id, '_wpcrm_opportunity-attach-to-organization', $oppOrg, true );
							}
							if( $oppContact != '' ) {
								add_post_meta( $post_id, '_wpcrm_opportunity-attach-to-contact', $oppContact, true );
							}
							if( $oppCampaign != '' ) {
								add_post_meta( $post_id, '_wpcrm_opportunity-attach-to-campaign', $oppCampaign, true );
							}
							if( $oppDescription != '' ) {
								add_post_meta( $post_id, '_wpcrm_opportunity-description', $oppDescription, true );
							}
							if( $oppProbability != '' ) {
								add_post_meta( $post_id, '_wpcrm_opportunity-probability', $oppProbability, true );
							}
							if( $oppClose != '' ) {
								add_post_meta( $post_id, '_wpcrm_opportunity-closedate', $oppClose, true );
							}
							if( $oppValue != '' ) {
								add_post_meta( $post_id, '_wpcrm_opportunity-value', $oppValue, true );
							}
							if( $oppWonLost != '' ) {
								add_post_meta( $post_id, '_wpcrm_opportunity-wonlost', $oppWonLost, true );
							}
							if( $opportunityCategories != '' ) {
								$opportunityTypes = wp_set_object_terms( $post_id, $opportunityCategories, 'opportunity-type' );
								if ( is_wp_error( $opportunityTypes ) ) {
									$error[] = _e( 'There was an error with the categories and they could not be set.', 'wp-crm-system' );
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
					<p><strong><?php _e( 'Opportunities uploaded. ', 'wp-crm-system' ); echo $count_added; _e( ' added. ', 'wp-crm-system' ); echo $count_skipped; _e( ' skipped.', 'wp-crm-system' ); ?> </strong></p>
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
add_action( 'admin_init', 'wp_crm_system_import_opportunities_process' );