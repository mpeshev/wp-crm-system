<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}

add_shortcode( 'wpcrm_system_gdpr', 'wpcrm_system_gdpr_check' );
function wpcrm_system_gdpr_check( $atts ){
	if( !$_GET || !$_GET['contact_id'] || !$_GET['secret'] )
		return __( 'Error: Incorrect URL given. Please request a valid URL.', 'wp-crm-system' ); // Possibly add an error message of some sort here.

	$id	= $_GET['contact_id'];
	if ( 'wpcrm-contact' != get_post_type( $id ) )
		return __( 'Error: Incorrect contact URL given. Please request a valid contact URL.'); // Possibly add an error message indicating that the contact ID is not a valid WP-CRM System contact ID.

	$secret			= $_GET['secret'];
	$contact_secret	= get_post_meta( $id, '_wpcrm_system_gdpr_secret', true );

	if( $secret != $contact_secret )
		return __( 'Error: URL is incorrect. Please request a valid URL.'); // Possibly add an error message indicating that the secret is incorrect. Might not be a good idea as it leaves open the opportunity to guess the secret.

	/*
	 * All checks passed, output the contact's data.
	 */

	$atts = shortcode_atts(
		array(
			'allow_export' => true,
			'allow_delete' => true
		), $atts, 'wpcrm_system_gdpr'
	);

	return wpcrm_system_gdpr_data( $id, $atts['allow_export'], $atts['allow_delete'] );
}

function wpcrm_system_gdpr_data( $id, $allow_export, $allow_delete ){
	$post_status = get_post_status( $id );
	if ( 'gdpr_deletion' == $post_status ){
		$deletion = __( 'Your data is marked for deletion. We will review your request and remove it from this site as soon as possible.', 'wp-crm-system' );
		$deletion = apply_filters( 'wpcrm_system_gdpr_deletion_message', $deletion );
	}
	$org_id = get_post_meta( $id, '_wpcrm_contact-attach-to-organization', true );
	switch ( $org_id ) {
		case '':
			$organization = '';
			break;

		default:
			$organization = esc_html( get_the_title( $org_id ) );
			break;
	}

	$data = array(
		'prefix' 			=> esc_html( get_post_meta( $id, '_wpcrm_contact-name-prefix', true ) ),
		'first_name'		=> esc_html( get_post_meta( $id, '_wpcrm_contact-first-name', true ) ),
		'last_name'			=> esc_html( get_post_meta( $id, '_wpcrm_contact-last-name', true ) ),
		'organization'		=> $organization,
		'role'				=> esc_html( get_post_meta( $id, '_wpcrm_contact-role', true ) ),
		'street_1'			=> esc_html( get_post_meta( $id, '_wpcrm_contact-address1', true ) ),
		'street_2'			=> esc_html( get_post_meta( $id, '_wpcrm_contact-address2', true ) ),
		'city'				=> esc_html( get_post_meta( $id, '_wpcrm_contact-city', true ) ),
		'state'				=> esc_html( get_post_meta( $id, '_wpcrm_contact-state', true ) ),
		'postal_code'		=> esc_html( get_post_meta( $id, '_wpcrm_contact-postal', true ) ),
		'country'			=> esc_html( get_post_meta( $id, '_wpcrm_contact-country', true ) ),
		'phone'				=> esc_html( get_post_meta( $id, '_wpcrm_contact-phone', true ) ),
		'fax'				=> esc_html( get_post_meta( $id, '_wpcrm_contact-fax', true ) ),
		'mobile'			=> esc_html( get_post_meta( $id, '_wpcrm_contact-mobile-phone', true ) ),
		'email'				=> esc_html( get_post_meta( $id, '_wpcrm_contact-email', true ) ),
		'url'				=> esc_url( get_post_meta( $id, '_wpcrm_contact-website', true ) ),
		'information'		=> esc_html( get_post_meta( $id, '_wpcrm_contact-additional', true ) ),
		'categories'		=> esc_html( get_the_terms( $id, 'contact-type' ) ),
		'comments'			=> esc_textarea( get_comments( array( 'post_id' => $id ) ) )
	);

	$data = apply_filters( 'wpcrm_system_gdpr_contact_fields', $data );

	if( defined( 'WPCRM_CUSTOM_FIELDS' ) ){
		$field_count = get_option( '_wpcrm_system_custom_field_count' );
		if( $field_count ){
			$custom_fields = array();
			for( $field = 1; $field <= $field_count; $field++ ){
				// Make sure we want this field to be displayed.
				$field_scope	= get_option( '_wpcrm_custom_field_scope_' . $field );
				$field_type		= get_option( '_wpcrm_custom_field_type_' . $field );
				$field_name		= get_option( '_wpcrm_custom_field_name_' . $field );
				$can_show		= $field_scope == 'wpcrm-contact' ? true : false;
				if( $can_show ){
					$value				= get_post_meta( $id, '_wpcrm_custom_field_id_' . $field, true );
					switch ( $field_type ) {
						case 'datepicker':
							$value = date( get_option( 'wpcrm_system_php_date_format' ), $value );
							break;
						case 'repeater-date':
							foreach ( $value as $key => $v ){
								$value[$key] = date( get_option( 'wpcrm_system_php_date_format' ), $v );
							}
							break;
						default:
							$value = $value;
							break;
					}
					$custom_fields[$field_name]	= $value;
				}
			}
			$custom_fields = apply_filters( 'wpcrm_system_gdpr_contact_custom_fields', $custom_fields );
		}
	}

	ob_start();
	?>

		<table class="wpcrm_system_gdpr_data_table">
			<?php
			$photo = '';
			/* Get the post meta. */
			$thumbnail = get_the_post_thumbnail( $id, array( 96, 96 ) );

			/* If no duration is found, output a default message. */
			if ( ( array_key_exists( 'email', $data ) && '' == $data['email'] ) && empty( $thumbnail ) ){
				unset( $photo );
			} else {
				/* If there is a photo, display it. */
				if ( !empty( $thumbnail ) ){
					$photo = $thumbnail;
				} else {
					$photo = get_avatar( $data['email'] );
					$photo .= '<div id="wpcrm_system_gdpr_gravatar_note">' . __( 'Note: this is the avatar set on Gravatar.com, and is not stored on this site.', 'wp-crm-system' ) . '</div>';
				}
			}
			if ( isset( $photo ) ): ?>
			<tr id="contact_photo">
				<th class="wpcrm_system_gdpr_title">
					<?php _e( 'Photo', 'wp-crm-system' ); ?>
				</th>
				<td class="wpcrm_system_gdpr_data">
					<?php echo $photo; ?>
				</td>
			</tr>
			<?php endif;
			/* Contact Name */
			if (
				( array_key_exists( 'first_name', $data )	&& '' != $data['first_name'] )||
				( array_key_exists( 'last_name', $data )	&& '' != $data['last_name'] )
			): ?>
			<tr id="contact_name">
				<th class="wpcrm_system_gdpr_title">
					<?php _e( 'Name', 'wp-crm-system' ); ?>
				</th>
				<td class="wpcrm_system_gdpr_data">
					<?php
					if ( array_key_exists( 'prefix', $data ) && '' != $data['prefix']){
						 echo '<span id="wpcrm_system_gdpr_table_name_prefix">' . wpcrm_system_display_name_prefix( $data['prefix'] ) . '</span> ';
					}
					if ( array_key_exists( 'first_name', $data ) && '' != $data['first_name'] ){
						echo '<span id="wpcrm_system_gdpr_table_first_name">' . $data['first_name'] . '</span> ';
					}
					if ( array_key_exists( 'last_name', $data ) && '' != $data['last_name'] ){
						echo '<span id="wpcrm_system_gdpr_table_last_name">' . $data['last_name'] . '</span>';
					}
					?>
				</td>
			</tr>
			<?php endif;
			/* Contact Categories */
			if ( array_key_exists( 'categories', $data ) ): ?>
			<tr id="contact_categories">
				<th class="wpcrm_system_gdpr_title">
					<?php _e( 'Category', 'wp-crm-system' ); ?>
				</th>
				<td class="wpcrm_system_gdpr_data">
					<?php $count_categories = count( $data['categories'] );
					$a = 1;
					foreach ( $data['categories'] as $category ){
						if( is_object( $category ) ){
							$comma = ( $a < $count_categories ) ? ', ' : '';
							$cat_id = str_replace( ' ', '_', strtolower( $category->name ) ); ?>
							<span id="wpcrm_system_gdpr_table_category_<?php echo $cat_id; ?>"><?php echo $category->name . $comma; ?></span>
						<?php }
						$a++;
					} ?>
				</td>
			</tr>
			<?php endif;

			/* Contact Organization */
			if (
				( array_key_exists( 'organization', $data )	&& '' != $data['organization'] ) ||
				( array_key_exists( 'role', $data )			&& '' != $data['role'] )
			 ): ?>
			<tr id="contact_name">
				<th class="wpcrm_system_gdpr_title">
					<?php _e( 'Organization', 'wp-crm-system' ); ?>
				</th>
				<td class="wpcrm_system_gdpr_data">
					<?php
					if ( array_key_exists( 'organization', $data ) && '' != $data['organization'] ){
						echo '<span id="wpcrm_system_gdpr_table_organization">' . $data['organization'] . '</span> ';
					}
					if ( array_key_exists( 'role', $data ) && '' != $data['role'] ){
						echo '<span id="wpcrm_system_gdpr_table_role">' . $data['role'] . '</span>';
					}
					?>
				</td>
			</tr>
			<?php endif;

			/* Contact Address */
			if (
				( array_key_exists( 'street_1', $data )		&& '' != $data['street_1'] )	||
				( array_key_exists( 'street_2', $data )		&& '' != $data['street_2'] )	||
				( array_key_exists( 'city', $data )			&& '' != $data['city'] )		||
				( array_key_exists( 'state', $data )		&& '' != $data['state'] )		||
				( array_key_exists( 'postal_code', $data )	&& '' != $data['postal_code'] )	||
				( array_key_exists( 'country', $data ) 		&& '' != $data['country'] )
			): ?>
			<tr id="contact_address">
				<th class="wpcrm_system_gdpr_title">
					<?php _e( 'Address', 'wp-crm-system' ); ?>
				</th>
				<td class="wpcrm_system_gdpr_data">
					<?php
					if ( array_key_exists( 'street_1', $data ) && '' != $data['street_1'] ){
						echo '<div id="wpcrm_system_gdpr_table_street_1">' . $data['street_1'] . '</div>';
					}
					if ( array_key_exists( 'street_2', $data ) && '' != $data['street_2'] ){
						echo '<div id="wpcrm_system_gdpr_table_street_2">' . $data['street_2'] . '</div>';
					}
					if ( array_key_exists( 'city', $data ) && '' != $data['city'] ){
						echo '<div id="wpcrm_system_gdpr_table_city">' . $data['city'] . '</div>';
					}
					if ( array_key_exists( 'state', $data ) && '' != $data['state'] ){
						echo '<div id="wpcrm_system_gdpr_table_state">' . $data['state'] . '</div>';
					}
					if ( array_key_exists( 'postal_code', $data ) && '' != $data['postal_code'] ){
						echo '<div id="wpcrm_system_gdpr_table_postal_code">' . $data['postal_code'] . '</div>';
					}
					if ( array_key_exists( 'country', $data ) && '' != $data['country'] ){
						echo '<div id="wpcrm_system_gdpr_table_country">' . $data['country'] . '</div>';
					}
					?>
				</td>
			</tr>
			<?php endif;

			/* Contact's Contact Information */
			if (
				( array_key_exists( 'phone', $data )	&& '' != $data['phone'] )	||
				( array_key_exists( 'fax', $data )		&& '' != $data['fax'] )		||
				( array_key_exists( 'mobile', $data )	&& '' != $data['mobile'] )	||
				( array_key_exists( 'email', $data )	&& '' != $data['email'] )	||
				( array_key_exists( 'url', $data )		&& '' != $data['phone'] )
			): ?>
			<tr id="contact_contact_information">
				<th class="wpcrm_system_gdpr_title">
					<?php _e( 'Contact Information', 'wp-crm-system' ); ?>
				</th>
				<td class="wpcrm_system_gdpr_data">
					<?php
					if ( array_key_exists( 'phone', $data ) && '' != $data['phone'] ){
						echo '<div id="wpcrm_system_gdpr_table_phone">' . __( 'Phone: ', 'wp-crm-system' ) . $data['phone'] . '</div>';
					}
					if ( array_key_exists( 'mobile', $data ) && '' != $data['mobile'] ){
						echo '<div id="wpcrm_system_gdpr_table_mobile">' . __( 'Mobile: ', 'wp-crm-system' ) . $data['mobile'] . '</div>';
					}
					if ( array_key_exists( 'fax', $data ) && '' != $data['fax'] ){
						echo '<div id="wpcrm_system_gdpr_table_fax">' . __( 'Fax: ', 'wp-crm-system' ) . $data['fax'] . '</div>';
					}
					if ( array_key_exists( 'email', $data ) && '' != $data['email'] ){
						echo '<div id="wpcrm_system_gdpr_table_email">' . __( 'Email: ', 'wp-crm-system' ) . $data['email'] . '</div>';
					}
					if ( array_key_exists( 'url', $data ) && '' != $data['url'] ){
						echo '<div id="wpcrm_system_gdpr_table_url">' . __( 'Website: ', 'wp-crm-system' ) . $data['url'] . '</div>';
					}
					?>
				</td>
			</tr>
			<?php endif;

			/* Contact's Contact Information */
			if ( array_key_exists( 'information', $data ) && '' != $data['information'] ): ?>
			<tr id="contact_additional">
				<th class="wpcrm_system_gdpr_title">
					<?php _e( 'Additional Information', 'wp-crm-system' ); ?>
				</th>
				<td class="wpcrm_system_gdpr_data">
					<?php
					if ( array_key_exists( 'information', $data ) ){
						echo '<div id="wpcrm_system_gdpr_table_information">' . $data['information'] . '</div>';
					}
					?>
				</td>
			</tr>
			<?php endif;
			/* Comments */
			if ( array_key_exists( 'comments', $data ) && !empty( $data['comments'] ) ): ?>
			<tr id="contact_comments">
				<th class="wpcrm_system_gdpr_title">
					<?php _e( 'Comments', 'wp-crm-system' ); ?>
				</th>
				<td class="wpcrm_system_gdpr_data">
					<?php foreach ( $data['comments'] as $comment ){ ?>
						<div class="wpcrm_system_gdpr_comment" id="wpcrm_system_gdpr_comment_<?php echo $comment->ID; ?>">
							<div class="wpcrm_system_gdpr_comment_content">
								<?php echo $comment->comment_content; ?>
							</div>
							<div class="wpcrm_system_gdpr_comment_date">
								<?php echo $comment->comment_date; ?>
							</div>
						</div>
					<?php } ?>
				</td>
			</tr>
			<?php endif;
			/* Custom Fields */
			if ( $custom_fields && is_array( $custom_fields ) ):
				foreach ( $custom_fields as $field_name => $value ){
					$field_id = str_replace( ' ', '_', strtolower( $field_name ) ); ?>
					<tr id="<?php echo 'wpcrm_system_gdpr_' . $field_id; ?>">
						<th class="wpcrm_system_gdpr_title">
							<?php echo $field_name; ?>
						</th>
						<td class="wpcrm_system_gdpr_data">
							<?php if ( is_array( $value ) ){
								foreach ( $value as $v ){
									echo '<div class="wpcrm_system_gdpr_table_' . $field_id . '">' . esc_html( $v ) . '</div>';
								}
							} else {
								echo '<div class="wpcrm_system_gdpr_table_' . $field_id . '">' . esc_html( $value ) . '</div>';
							} ?>
						</td>
					</tr>
				<?php
				}
			endif;
			if ( 'true' == $allow_export ){
			?>
			<tr id="contact_export">
				<th class="wpcrm_system_gdpr_title">
					<?php _e( 'Export this data to a CSV file', 'wp-crm-system' ); ?>
				</th>
				<td class="wpcrm_system_gdpr_data">
					<form id="wpcrm_system_gdpr_export_contact" name="wpcrm_system_gdpr_export_contact" method="post" action="">
						<input type="hidden" name="wpcrm_system_gdpr_export_contact_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-gdpr-export-contact-nonce' ); ?>" />
						<input type="submit" name="wpcrm_system_gdpr_export_contact" value="<?php _e( 'Export', 'wp-crm-system-import-contacts' ); ?>" />
					</form>
				</td>
			</tr>
			<?php }
			if ( 'true' == $allow_delete ){

			?>
			<tr id="contact_change">
				<th class="wpcrm_system_gdpr_title">
					<?php _e( 'Request Removal of Your Data', 'wp-crm-system' ); ?>
				</th>
				<td class="wpcrm_system_gdpr_data">
					<?php if( !isset( $deletion ) ){ ?>
					<form id="wpcrm_system_gdpr_remove_data" name="wpcrm_system_gdpr_remove_data" method="post" action="">
						<input type="hidden" name="wpcrm_system_gdpr_delete_contact_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-gdpr-delete-contact-nonce' ); ?>" />
						<input type="hidden" name="wpcrm_system_gdpr_contact_id" value="<?php echo $id; ?>" />
						<input type="submit" name="wpcrm_system_gdpr_delete_contact" value="<?php _e( 'Delete My Data', 'wp-crm-system-import-contacts' ); ?>" />
					</form>
					<?php } else {
					_e( 'Your data has been marked for deletion. It will be removed from this site as soon as possible.', 'wp-crm-system' );
					} ?>
				</td>
			<?php } ?>
		</table>
	<?php
	$output = ob_get_clean();
	return $output;
}

add_action( 'init', 'wp_crm_system_gdpr_export_contacts' );
function wp_crm_system_gdpr_export_contacts(){
	if ( isset( $_POST[ 'wpcrm_system_gdpr_export_contact_nonce' ] ) ) {
		if( wp_verify_nonce( $_POST[ 'wpcrm_system_gdpr_export_contact_nonce' ], 'wpcrm-system-gdpr-export-contact-nonce' ) ) {
			require_once WP_CRM_SYSTEM_PLUGIN_DIR_PATH . '/includes/class-export.php';
			require_once WP_CRM_SYSTEM_PLUGIN_DIR_PATH . '/includes/legacy/gdpr-export-contact.php';

			$export = new WPCRM_System_GDPR_Export_Contact();

			$export->export();
		}
	}
}

add_action( 'init', 'wp_crm_system_gdpr_delete_contacts' );
function wp_crm_system_gdpr_delete_contacts(){
	$message = '';
	if ( isset( $_POST[ 'wpcrm_system_gdpr_delete_contact_nonce' ] ) ) {
		if( wp_verify_nonce( $_POST[ 'wpcrm_system_gdpr_delete_contact_nonce' ], 'wpcrm-system-gdpr-delete-contact-nonce' ) ) {
			if ( $_GET['contact_id'] != $_POST['wpcrm_system_gdpr_contact_id'] || 0 == intval( $_POST['wpcrm_system_gdpr_contact_id'] ) ){
				$message = __( 'Invalid request. Please try again.', 'wp-crm-system' );
			} else {
				$contact_delete = array(
					'ID'			=> sanitize_text_field( trim( $_POST['wpcrm_system_gdpr_contact_id'] ) ),
					'post_status'	=> 'gdpr_deletion'
				);

				$post_id = wp_update_post( $contact_delete );

				if ( is_wp_error( $post_id ) ){
					$errors = $post_id->get_error_messages();
					foreach ( $errors as $error ){
						$message .= $error . '<br />';
					}
				} else {
					$message = __( 'Your data was successfully marked for deletion.', 'wp-crm-system' );
				}
				/* Send email to site admin notifying of delete request */
				$to				= apply_filters( 'wpcrm_system_gdpr_delete_request_email', get_option( 'admin_email' ) );
				$subject		= apply_filters( 'wpcrm_system_gdpr_delete_request_subject', __( 'GDPR Delete Request', 'wp-crm-system' ) );

				$contact_name	= get_the_title( $contact_delete['ID'] );
				$edit_link		= admin_url( 'post.php?post=' . $contact_delete['ID'] . '&action=edit' );


				$message		= sprintf(
					wp_kses(
						__( 'A contact, %s, has requested that their information be deleted from WP-CRM System. Their record has been marked for deletion, but will not be deleted automatically. This enables you to determine whether or not you are required to retain their data, or delete it from any other system your company uses, such as a mailing list. You can review their record in WP-CRM System by copying and pasting the following link into your browser: %s', 'wp-crm-system' ),
						array(  'a' => array( 'href' => array() ) )
					),
					$contact_name, esc_url( $edit_link )
				);

				$message		= apply_filters( 'wpcrm_system_gdpr_delete_request_message', $message, $contact_name, $edit_link );

				$headers		= apply_filters( 'wpcrm_system_gdpr_delete_request_headers', '' );

				$attachments	= apply_filters( 'wpcrm_system_gdpr_delete_request_attachments', '' );

				wp_mail( $to, $subject, $message, $headers, $attachments );
				/* possibly add support for Slack/Zapier add-on to send notification elsewhere */
			}
		}
	}
	return $message;
}

add_action( 'post_submitbox_misc_actions', 'wpcrm_system_gdpr_contact_marked_for_deletion' );

function wpcrm_system_gdpr_contact_marked_for_deletion( $post ){
	if( 'gdpr_deletion' == $post->post_status && 'wpcrm-contact' == $post->post_type ){ ?>
		<div class="misc-pub-section" style="background-color:#ff0000;color:#ffffff;">
			<?php _e( 'This contact has requested that their data be deleted. Please review this information and delete their data as soon as possible.', 'wp-crm-system' ); ?>
		</div>
	<?php }
}

add_filter( 'the_content', 'wpcrm_system_auto_filter_gdpr_shortcode' );
function wpcrm_system_auto_filter_gdpr_shortcode( $content ) {
	$gdpr_page_id = get_option( 'wpcrm_system_gdpr_page_id', true );
	if ( ! $gdpr_page_id )
		return $content;
	// Make sure we're not on the admin side, only in the main query in the loop and on the single page.
	if ( is_main_query() && in_the_loop() && is_page( $gdpr_page_id ) && ! is_admin() ) {
		if ( !has_shortcode( $content, 'wpcrm_system_gdpr' ) ){
			$content .= ' [wpcrm_system_gdpr]';
		}
	}
	return $content;
}