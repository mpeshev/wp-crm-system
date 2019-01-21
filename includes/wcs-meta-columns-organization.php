<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
add_filter( 'manage_edit-wpcrm-organization_columns', 'wpcrm_system_organization_columns' ) ;

function wpcrm_system_organization_columns( $columns ) {

	$columns = array(
		'cb'		=> '<input type="checkbox" />',
		'title'		=> __( 'Organization', 'wp-crm-system' ),
		'phone'		=> __( 'Phone', 'wp-crm-system' ),
		'email'		=> __( 'Email', 'wp-crm-system' ),
		'address'	=> __( 'Address', 'wp-crm-system' ),
		'contacts'	=> __( 'Contacts', 'wp-crm-system' ),
		'date'		=> __( 'Date', 'wp-crm-system' ),
		'category'	=> __( 'Category', 'wp-crm-system' )
	);

	return $columns;
}

add_action( 'manage_wpcrm-organization_posts_custom_column', 'wprcm_system_organization_columns_content', 10, 2 );

function wprcm_system_organization_columns_content( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'phone' column. */
		case 'phone' :

			/* Get the post meta. */
			$number = esc_html( get_post_meta( $post_id, '_wpcrm_organization-phone', true ) );

			/* If no duration is found, output a default message. */
			if ( empty( $number ) )
				echo __( 'Not Set', 'wp-crm-system' );

			/* If there is a phone number, display it with clickable link. */
			else
				echo '<a href="tel:' . $number . '">' . $number . '</a>';

			break;
		/* If displaying the 'email' column. */
		case 'email' :

			/* Get the post meta. */
			$email = get_post_meta( $post_id, '_wpcrm_organization-email', true );

			/* If no duration is found, output a default message. */
			if ( empty( $email ) )
				echo __( 'Not Set', 'wp-crm-system' );

			/* If there is a email, display it. */
			else
				$email = esc_html( $email );
				echo '<a href="mailto:' . $email . '">' . $email . '</a>';

			break;
		/* If displaying the 'address' column. */
		case 'address' :

			/* Get the post meta. */
			$address1 = get_post_meta( $post_id, '_wpcrm_organization-address1', true );
			$address2 = get_post_meta( $post_id, '_wpcrm_organization-address2', true );
			$city = get_post_meta( $post_id, '_wpcrm_organization-city', true );
			$state = get_post_meta( $post_id, '_wpcrm_organization-state', true );
			$postal = get_post_meta( $post_id, '_wpcrm_organization-postal', true );

			$address1 = !empty( $address1 ) ? $address1 : '';
			$address2 = !empty( $address2 ) ? ' ' . $address2 : '';
			$city = !empty( $city ) ? $city . ', ' : '';
			$state = !empty( $state ) ? $state . ' ' : '';
			$postal = !empty( $postal ) ? $postal : '';


			/* If no duration is found, output a default message. */
			if ( empty( $address1 ) && empty( $address2 ) && empty( $city ) && empty( $state ) && empty( $postal ) )
				echo __( 'Not Set', 'wp-crm-system' );

			/* If there is an address field set, display it. */
			else
				echo esc_html( $address1 ) . esc_html( $address2 ) . '<br />';
				echo esc_html( $city ) . esc_html( $state ) . esc_html( $postal );

			break;
		case 'contacts' :

			/* Get the post meta. */
			echo wpcrmListContactsinOrg();

			break;
		/* If displaying the 'category' column */
		case 'category':
			$categories = get_the_terms( $post_id, 'organization-type' );
			if ( !empty ( $categories ) ){
				sort( $categories );
				foreach ( $categories as $category ){
					echo '<a href="' . esc_url( admin_url( 'edit.php?organization-type=' . $category->slug . '&post_type="wpcrm-organization"', 'admin' ) ) . '">' . esc_html( $category->name ) . '</a><br />';
				}
			}
			break;
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

add_filter( 'manage_edit-wpcrm-organization_sortable_columns', 'wpcrm_system_organization_sortable_columns' );

function wpcrm_system_organization_sortable_columns( $columns ) {

	$columns['org']		= 'org';
	$columns['phone']	= 'phone';
	$columns['email']	= 'email';

	return $columns;
}

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'wpcrm_system_organization_edit_load' );

function wpcrm_system_organization_edit_load() {
	add_filter( 'request', 'wpcrm_system_sort_organization_columns' );
}

/* Sorts the organization. */
function wpcrm_system_sort_organization_columns( $vars ) {

	/* Check if we're viewing the 'wpcrm-task' post type. */
	if ( isset( $vars['post_type'] ) && 'wpcrm-organization' == $vars['post_type'] ) {

		/* Check if 'orderby' is set. */
		if ( isset( $vars['orderby'] ) ) {
			switch ( $vars['orderby'] ){
				case 'org':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_organization-attach-to-organization',
							'orderby' => 'meta_value'
						)
					);
					break;
				case 'phone':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_organization-phone',
							'orderby' => 'meta_value'
						)
					);
					break;
				case 'email':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_organization-email',
							'orderby' => 'meta_value'
						)
					);
					break;
				default:
					break;
			}
		}
	}
	return $vars;
}