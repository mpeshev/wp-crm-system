<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_import_campaigns_process(){
	if( isset( $_FILES[ 'import_campaigns' ] ) && isset( $_POST[ 'wpcrm_system_import_campaigns_nonce' ] ) ){
		if( wp_verify_nonce( $_POST[ 'wpcrm_system_import_campaigns_nonce' ], 'wpcrm-system-import-campaigns-nonce' ) ){
			$errors			= array();
			$file_name 		= $_FILES[ 'import_campaigns' ][ 'name' ];
			$file_size 		= $_FILES[ 'import_campaigns' ][ 'size' ];
			$file_tmp 		= $_FILES[ 'import_campaigns' ][ 'tmp_name' ];
			$file_type 		= $_FILES[ 'import_campaigns' ][ 'type' ];
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

				// List all statuses in array.
				$status = array( 'not-started', 'in-progress', 'complete', 'on-hold' );
				// List all possible users in array.
				global $wpdb;
				$users = $wpdb->get_col( "SELECT user_login FROM $wpdb->users" );
				$wpdb->flush();

				while( ( $fileop = fgetcsv( $handle, 5000, ",") ) !== false ) {
					// get fields from uploaded CSV
					$campaignName 		= $fileop[0];
					$campaignAssigned 	= $fileop[1];
					$campaignActive 	= $fileop[2];
					if ( in_array( $fileop[3], $status ) ) {
						$campaignStatus = $fileop[3];
					} else {
						$campaignStatus = 'not-started';
					}
					$campaignStart 			= strtotime( $fileop[4] );
					$campaignEnd 			= strtotime( $fileop[5] );
					$campaignReach 			= preg_replace( "/[^0-9]/", "", $fileop[6] );
					$campaignResponses 		= preg_replace( "/[^0-9]/", "", $fileop[7] );
					$campaignBudget 		= preg_replace( "/[^0-9]/", "", $fileop[8] );
					$campaignActual			= preg_replace( "/[^0-9]/", "", $fileop[9] );
					$campaignDescription	= wp_kses_post( wpautop( $fileop[10] ) );
					$campaignCategories 	= sanitize_text_field( $fileop[11] );
					$campaignCategories 	= explode( ', ', $campaignCategories );
					$categories 			= array();
					foreach( $campaignCategories as $category ) {
						$categories[] = $category;
					}
					$campaignCategories 	= array_unique( $categories );

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
									$can_import = wpcrm_system_sanitize_imported_fields( $field, 'wpcrm-campaign', $field_type, $fileop[$import_id] );
									if( $can_import ){
										$custom_fields[$field] = $fileop[$import_id];
									}
								}
							}
						}
					}

					// set some fields for new campaign
					$post_id 	= -1;
					$slug 		= preg_replace( "/[^A-Za-z0-9]/", '', strtolower( $campaignName ) );
					$title 		= $campaignName;

					if( $i > 0 ) {
						// If the page doesn't already exist, then create it
						if( null == get_page_by_title( $title, OBJECT, 'wpcrm-campaign' ) ) {
							$post_id = wp_insert_post(
								array(
									'comment_status'	=>	'closed',
									'ping_status'		=>	'closed',
									'post_author'		=>	$author_id,
									'post_name'			=>	$slug,
									'post_title'		=>	$title,
									'post_status'		=>	'publish',
									'post_type'			=>	'wpcrm-campaign'
								)
							);
							//Add user's information to campaigns fields.
							if( $campaignAssigned != '' ) {
								add_post_meta( $post_id, '_wpcrm_campaign-assigned', $campaignAssigned, true);
							}
							if( $campaignActive == 'yes' ) {
								add_post_meta( $post_id, '_wpcrm_campaign-active', $campaignActive, true );
							}
							if( $campaignStatus != '' ) {
								$acceptable = array('not-started', 'in-progress', 'complete', 'on-hold' );
								if ( in_array( $campaignStatus, $acceptable ) ){
									add_post_meta( $post_id, '_wpcrm_campaign-status', $campaignStatus, true );
								} else {
									add_post_meta( $post_id, '_wpcrm_campaign-status', 'not-started', true );
								}
							}
							if( $campaignStart != '' ) {
								add_post_meta( $post_id, '_wpcrm_campaign-startdate', $campaignStart, true );
							}
							if( $campaignEnd != '' ) {
								add_post_meta( $post_id, '_wpcrm_campaign-enddate', $campaignEnd, true );
							}
							if( $campaignReach != '' ) {
								add_post_meta( $post_id, '_wpcrm_campaign-projectedreach', $campaignReach, true );
							}
							if( $campaignResponses != '' ) {
								add_post_meta( $post_id, '_wpcrm_campaign-responses', $campaignResponses, true );
							}
							if( $campaignBudget != '' ) {
								add_post_meta( $post_id, '_wpcrm_campaign-budgetcost', $campaignBudget, true );
							}
							if( $campaignActual != '' ) {
								add_post_meta( $post_id, '_wpcrm_campaign-actualcost', $campaignActual, true );
							}
							if( $campaignDescription != '' ) {
								add_post_meta( $post_id, '_wpcrm_campaign-description', $campaignDescription, true );
							}
							if( $campaignCategories != '' ) {
								$campaignTypes = wp_set_object_terms( $post_id, $campaignCategories, 'campaign-type' );
								if ( is_wp_error( $campaignTypes ) ) {
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
					<p><strong><?php _e( 'Campaigns uploaded. ', 'wp-crm-system' ); echo $count_added; _e( ' added. ', 'wp-crm-system' ); echo $count_skipped; _e( ' skipped.', 'wp-crm-system' ); ?> </strong></p>
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
add_action( 'admin_init', 'wp_crm_system_import_campaigns_process' );