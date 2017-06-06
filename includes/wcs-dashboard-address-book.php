<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_ajax_address_book() {
	if( !isset( $_POST['address_book_nonce'] ) || !wp_verify_nonce($_POST['address_book_nonce'], 'address-book-nonce') )
		die('Permissions check failed');
	
	if( !isset( $_POST['contact_id'] ) )
		die('No contact data sent');

	$contact_id		= absint( $_POST['contact_id'] );
	$contact_meta	= array(
		'name-prefix',
		'first-name',
		'last-name',
		'attach-to-organization',
		'role',
		'website',
		'phone',
		'mobile-phone',
		'address1',
		'address2',
		'city',
		'state',
		'postal',
		'country',
		'email',
		'fax'
	);
	$contact_info = array();
	foreach ( $contact_meta as $meta ){
		$contact_info[] = esc_html( get_post_meta( $contact_id, '_wpcrm_contact-' . $meta, true ) );
	}
	$thumbnail = get_the_post_thumbnail( $contact_id, array( 96, 96 ), array( 'class' => 'alignright wpcrm-circle' ) );
	$contact_info[] = !empty( $thumbnail ) ?  $thumbnail : get_avatar( $contact_info['14'], array( 96, 96 ), 'mysteryman', '', array( 'class' => 'alignright wpcrm-circle' ) );

	$name = '' != esc_url( $contact_info['5'] ) ? '<a href="' . esc_url( $contact_info['5'] ) . '">' . wpcrm_system_display_name_prefix( $contact_info['0'] ) . ' ' . $contact_info['1'] . ' ' . $contact_info['2'] . '</a>' : wpcrm_system_display_name_prefix( $contact_info['0'] ) . ' ' . $contact_info['1'] . ' ' . $contact_info['2'];
	$company = '' != trim( $contact_info['3'] ) ? '<a href="' . get_edit_post_link( $contact_info['3'] ) . '">' . get_the_title( $contact_info['3'] ) . '</a> ' . $contact_info['4'] : '';
	$address = $contact_info['8'] . ' ' . $contact_info['9'] . ' ' . $contact_info['10'] . ' ' . $contact_info['11'] . ' ' . $contact_info['12'] . ' ' . $contact_info['13'];
	
	if ( '' != trim( $thumbnail ) )
		echo '<a href="' . get_edit_post_link( $contact_id ) . '" class="wpcrm-system-help-tip" title="' . __( 'Click to view contact', 'wp-crm-system' ) . '">' . $thumbnail . '</a>';

	if ( '' != trim( $name ) ){ ?>
		<span class="wpcrm-system-help-tip dashicons dashicons-id wpcrm-dashicons" title="<?php _e( 'Contact Name', 'wp-crm-system' ); ?>"></span>
		<?php echo trim( $name ) . '<br />';
	}
	if ( '' != trim( $contact_info['14'] ) ){ ?>
		<span class="wpcrm-system-help-tip dashicons dashicons-email wpcrm-dashicons" title="<?php _e( 'Contact Email', 'wp-crm-system' ); ?>"></span>
		<?php echo '<a href="mailto:' . $contact_info['14'] . '">' . $contact_info['14'] . '</a><br />';
	}
	if ( '' != trim( $company ) ){ ?>
		<span class="wpcrm-system-help-tip dashicons dashicons-building wpcrm-dashicons" title="<?php _e( 'Contact Company - Click to view company', 'wp-crm-system' ); ?>"></span>
		<?php echo $company . '<br />';
	}
	if ( '' != trim( $contact_info['6'] ) ){ ?>
		<span class="wpcrm-system-help-tip dashicons dashicons-phone wpcrm-dashicons" title="<?php _e( 'Contact Phone', 'wp-crm-system' ); ?>"></span>
		<?php echo '<a href="tel:' . $contact_info['6'] . '">' . $contact_info['6'] . '</a><br />';
	}
	if ( '' != trim( $contact_info['7'] ) ){ ?>
		<span class="wpcrm-system-help-tip dashicons dashicons-smartphone wpcrm-dashicons" title="<?php _e( 'Contact Mobile', 'wp-crm-system' ); ?>"></span>
		<?php echo '<a href="tel:' . $contact_info['7'] . '">' . $contact_info['7'] . '</a><br />';
	}
	if ( '' != trim( $contact_info['15'] ) ){ ?>
		<span class="wpcrm-system-help-tip wpcrm-dashicons-fax" title="<?php _e( 'Contact Fax', 'wp-crm-system' ); ?>"></span>
		<?php echo '<a href="tel:' . $contact_info['15'] . '">' . $contact_info['15'] . '</a><br />';
	}
	if ( '' != trim( $address ) ){ ?>
		<span class="wpcrm-system-help-tip dashicons dashicons-location wpcrm-dashicons" title="<?php _e( 'Contact Address', 'wp-crm-system' ); ?>"></span>
		<?php echo $address;
	}
	wp_die();
}
add_action('wp_ajax_address_book_response', 'wp_crm_system_ajax_address_book');

function wpcrm_system_dashboard_contacts_js($hook){
	wp_enqueue_script( 'wp_crm_system_dashboard_address_book', WP_CRM_SYSTEM_PLUGIN_URL . '/js/dashboard-address-book.js', array( 'jquery' ), WP_CRM_SYSTEM_VERSION, false );
	wp_localize_script('wp_crm_system_dashboard_address_book', 'address_book_vars', array(
			'address_book_nonce'	=> wp_create_nonce('address-book-nonce'),
		)
	);
}
add_action('admin_enqueue_scripts', 'wpcrm_system_dashboard_contacts_js');