<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_gdpr_contact_eraser( $email_address, $page = 1 ) {

	$page			= (int) $page; // For possible future use

	$number			= (int) apply_filters( 'wpcrm_system_gdpr_erase_number_filter', 500 ); // Limit us to avoid timing out

	$items_removed	= false;

	$items_retained	= false;

	$ids			= wpcrm_system_get_contact_ids_by_email_address( $email_address, $number );

	$ids			= apply_filters( 'wpcrm_system_gdpr_contact_eraser_ids', $ids );


	foreach ( (array) $ids as $id ) {
		$deleted = wp_delete_post( $id, true );

		if ( ! empty( $deleted ) ) {
			$items_removed = true;
		}

	}

	// Tell core if we have more to work on still
	$done = count( $ids ) < $number;

	return array(
		'items_removed'		=> $items_removed,
		'items_retained'	=> $items_retained,
		'messages'			=> array(), // no messages in this example
		'done'				=> $done,
	);
}
/*

The next thing the plugin needs to do is to register the callback by
filtering the eraser array using the `wp_privacy_personal_data_erasers`
filter.

When registering you provide a friendly name for the eraser (to aid in
debugging - this friendly name is not shown to anyone at this time)
and the callback, e.g.

*/
function register_wp_crm_system_gdpr_contact_eraser( $erasers ) {
	$erasers['wp-crm-system-contact-eraser'] = array(
		'eraser_friendly_name' => __( 'WP-CRM System Contact Eraser', 'wp-crm-system' ),
		'callback'             => 'wp_crm_system_gdpr_contact_eraser',
	);
	return $erasers;
}

add_filter( 'wp_privacy_personal_data_erasers',	'register_wp_crm_system_gdpr_contact_eraser', 999 );