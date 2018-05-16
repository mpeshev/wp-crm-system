<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_gdpr_opportunity_eraser( $email_address, $page = 1 ) {

	$page			= (int) $page;

	$number			= (int) apply_filters( 'wpcrm_system_gdpr_erase_number_filter', 500 ); // Limit us to avoid timing out

	$items_removed	= false;

	$items_retained	= false;

	$anonymize		= (bool) apply_filters( 'wpcrm_system_gdpr_anonymize_opportunity', false );

	if ( false === $anonymize ){
		/* We can't include pagination in this request since we are deleting records.
		 * Deleted records will move undeleted records up in the queue, so records 1-500 on the 2nd iteration would have been
		 * records 501-1000 on the 1st iteration if we paginate. Therefore, ignoring $page parameter.
		 */
		$ids			= wpcrm_system_get_records_by_contact_email_address( $email_address, 'opportunity', $number );

		$ids			= apply_filters( 'wpcrm_system_gdpr_opportunity_eraser_ids', $ids );


		foreach ( (array) $ids as $id ) {
			$deleted = wp_delete_post( $id, true );

			if ( ! empty( $deleted ) ) {
				$items_removed = true;
			}

		}

	} else {
		/* Here we are retaining records, so we can use the $page variable.
		 * Records are not deleted, so records 1-500 on the 1st iteration are still records 1-500 on the 2nd iteration.
		 */
		$ids	= wpcrm_system_get_records_by_contact_email_address( $email_address, 'opportunity', $number, $page );

		$ids	= apply_filters( 'wpcrm_system_gdpr_opportunity_eraser_ids', $ids );

		foreach ( (array) $ids as $id ) {

			update_post_meta( $id, '_wpcrm_opportunity-attach-to-contact', '' );

			$items_removed	= false;

			$item_retained	= true;

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
function register_wp_crm_system_gdpr_opportunity_eraser( $erasers ) {
	$erasers['wp-crm-system-opportunity-eraser'] = array(
		'eraser_friendly_name' => __( 'WP-CRM System Opportunity Eraser', 'wp-crm-system' ),
		'callback'             => 'wp_crm_system_gdpr_opportunity_eraser',
	);
	return $erasers;
}

add_filter( 'wp_privacy_personal_data_erasers',	'register_wp_crm_system_gdpr_opportunity_eraser', 10 );