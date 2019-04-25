<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * A function used to programmatically create a contact from a user account in WordPress. The slug, author ID, and title
 * are defined within the context of the function.
 *
 * @return -1 if the post was never created, -2 if a post with the same title exists, or the ID of the post if successful.
 */
function wp_crm_system_programmatically_create_contact() {
	// Make sure add contact link was clicked.
	if( !isset( $_GET['action'] ) || 'wp_crm_system_add_contact_user' != $_GET['action'] ){
		return;
	}
	if( !isset( $_GET['user_to_contact_nonce'] ) ){
		return;
	}

	$nonce = $_GET['user_to_contact_nonce'];
	if( !wp_verify_nonce( $nonce, 'user_to_contact_nonce' ) ){
		return;
	}
	if( isset( $_GET['user'] ) ) {
		$userID = sanitize_text_field( $_GET['user'] );
	} else {
		$userID = 0;
	}

	$user_info = get_userdata( $userID );

	// Initialize the page ID to -1. This indicates no action has been taken.
	$post_id	= -1;
	$title		= false;
	if( $user_info ){
		// Setup the author, slug, title, and contact information.
		$author_id 		= get_current_user_id();
		$userFirstName 	= $user_info->first_name;
		$userLastName 	= $user_info->last_name;
		$userEmail 		= $user_info->user_email;
		$userURL 		= $user_info->user_url;
		$slug 			= preg_replace( "/[^A-Za-z0-9]/",'', strtolower( $userFirstName ) ) . '-' . preg_replace( "/[^A-Za-z0-9]/",'', strtolower( $userLastName ) );
		$title 			= $userFirstName . ' ' . $userLastName;
		$userAddress1 	= $user_info->billing_address_1;
		$userAddress2 	= $user_info->billing_address_2;
		$userCity 		= $user_info->billing_city;
		$userState 		= $user_info->billing_state;
		$userZip 		= $user_info->billing_postcode;
		$userPhone 		= $user_info->billing_phone;
	}

	// If the page doesn't already exist, then create it
	if( null == get_page_by_title( $title, OBJECT, 'wpcrm-contact' ) ) {

		// Set the post ID so that we know the post was created successfully
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
		add_post_meta( $post_id, '_wpcrm_contact-first-name', $userFirstName, true );
		add_post_meta( $post_id, '_wpcrm_contact-last-name', $userLastName, true );
		add_post_meta( $post_id, '_wpcrm_contact-email', $userEmail, true );
		add_post_meta( $post_id, '_wpcrm_contact-website', $userURL, true );
		add_post_meta( $post_id, '_wpcrm_contact-address1', $userAddress1, true );
		add_post_meta( $post_id, '_wpcrm_contact-address2', $userAddress2, true );
		add_post_meta( $post_id, '_wpcrm_contact-city', $userCity, true );
		add_post_meta( $post_id, '_wpcrm_contact-state', $userState, true );
		add_post_meta( $post_id, '_wpcrm_contact-postal', $userZip, true );
		add_post_meta( $post_id, '_wpcrm_contact-phone', $userPhone, true );
	// Otherwise, we'll stop
	} else {

		// Arbitrarily use -2 to indicate that the page with the title already exists
		$post_id = -2;

	} // end if
	switch ( $post_id ) {
		case -1: ?>
			<div id="message" class="error">
				<p><strong><?php _e( 'The contact was not created. An error has occurred.', 'wp-crm-system' ); ?></strong></p>
			</div>
			<?php
			break;

		case -2: ?>
			<div id="message" class="error">
				<p><strong><?php _e( 'The contact was not created. A contact with the following name appears to already exist:', 'wp-crm-system' ); ?> <?php echo $title; ?></strong></p>
			</div>
			<?php
			break;

		default: ?>
			<div id="message" class="updated">
				<p><strong><?php _e( 'New contact created:', 'wp-crm-system' ); ?> <a href="<?php echo get_edit_post_link( $post_id ); ?>"><?php echo $title; ?></a></strong></p>
			</div>
			<?php
			break;
	}
} // end wpcrm_system_programmatically_create_contact
add_filter( 'after_setup_theme', 'wp_crm_system_programmatically_create_contact' );

function wp_crm_system_create_contact_action_links( $actions, $user_object ) {
	$add_user = esc_url(
		add_query_arg( array(
			'action'				=> 'wp_crm_system_add_contact_user',
			'user'					=> $user_object->ID,
			'user_to_contact_nonce'	=> wp_create_nonce( 'user_to_contact_nonce' ),
		), admin_url( 'users.php' ) )
	);
	$actions['add_contact'] = "<a class='wp_crm_system_add_contacts' href='" . $add_user . "'>" . __( 'Add as WP-CRM System Contact', 'wp-crm-system' ) . "</a>";
	return $actions;
}
add_filter( 'user_row_actions', 'wp_crm_system_create_contact_action_links', 10, 2 );