<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_gdpr_contact_exporter( $email_address, $page = 1 ) {
	global $post;
	$page		= (int) $page;
	$number		= (int) apply_filters( 'wp_crm_system_gdpr_export_number_filter', 500 ); // Limit us to avoid timing out

	$ids = wpcrm_system_get_contact_ids_by_email_address( $email_address, $number, $page );

	$export_items = array();
	if( $ids ){
		foreach ( (array) $ids as $id ){
			$item_id		= 'wp-crm-system-contact-{$id}';
			$group_id		= 'wp-crm-system-contacts';
			$group_label	= __( 'CRM Contact', 'wp-crm-system' );

			$photo = '';
			/* Get the post meta. */
			$thumbnail = get_the_post_thumbnail_url( $id, array( 96, 96 ) );

			/* If there is a photo, display it. */
			if ( !empty( $thumbnail ) ){
				$photo = $thumbnail;
			} else {
				$photo = __( 'No photo available.', 'wp-crm-system' );
			}

			$category_obj	= get_the_terms( $id, 'contact-type' );
			$categories		= '';
			foreach( $category_obj as $category ){
				if ( is_object( $category ) ){
					$categories .= $category->name . ', ';
				} else {
					$categories .= '';
				}
			}
			$categories = rtrim( $categories, ', ' );


			$comments_obj	= get_comments( array( 'post_id' => $id ) );
			$comments		= '';
			foreach ( $comments_obj as $comment ) {
				if ( is_object( $comment ) ){
					$comments .= $comment->comment_date . ': ' . $comment->comment_content . ', ';
				}
			}
			$comments = rtrim( $comments, ', ' );

			$data = array(
				array(
					'name'	=> __( 'Contact ID', 'wp-crm-system' ),
					'value'	=> $id,
				),
				array(
					'name'	=> __( 'Photo', 'wp-crm-system' ),
					'value'	=> $photo,
				),
				array(
					'name'	=> __( 'Contact Name', 'wp-crm-system' ),
					'value'	=> get_the_title( $id ),
				),
				array(
					'name'	=> __( 'Prefix', 'wp-crm-system' ),
					'value'	=> wpcrm_system_display_name_prefix( get_post_meta( $id, '_wpcrm_contact-name-prefix', true ) ),
				),
				array(
					'name'	=> __( 'First Name', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-first-name', true ),
				),
				array(
					'name'	=> __( 'Last Name', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-last-name', true ),
				),
				array(
					'name'	=> __( 'Organization', 'wp-crm-system' ),
					'value'	=> get_the_title( get_post_meta( $id, '_wpcrm_contact-attach-to-organization', true ) ),
				),
				array(
					'name'	=> __( 'Role', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-role', true ),
				),
				array(
					'name'	=> __( 'Address 1', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-address1', true ),
				),
				array(
					'name'	=> __( 'Address 2', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-address2', true ),
				),
				array(
					'name'	=> __( 'City', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-city', true ),
				),
				array(
					'name'	=> __( 'State', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-state', true ),
				),
				array(
					'name'	=> __( 'Postal Code', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-postal', true ),
				),
				array(
					'name'	=> __( 'Country', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-country', true ),
				),
				array(
					'name'	=> __( 'Phone', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-phone', true ),
				),
				array(
					'name'	=> __( 'Fax', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-fax', true ),
				),
				array(
					'name'	=> __( 'Mobile', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-mobile-phone', true ),
				),
				array(
					'name'	=> __( 'Email Address', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-email', true ),
				),
				array(
					'name'	=> __( 'Website URL', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-website', true ),
				),
				array(
					'name'	=> __( 'Information', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_contact-additional', true ),
				),
				array(
					'name'	=> __( 'Category', 'wp-crm-system' ),
					'value'	=> $categories,
				),
				array(
					'name'	=> __( 'Comments', 'wp-crm-system' ),
					'value'	=> $comments,
				),
			);
			/* Custom Fields */
			if( defined( 'WPCRM_CUSTOM_FIELDS' ) ){
				$field_count 	= get_option( '_wpcrm_system_custom_field_count' );
				if( $field_count ){
					for( $field = 1; $field <= $field_count; $field++ ){
						// Make sure we want this field to be exported.
						$field_scope 	= get_option( '_wpcrm_custom_field_scope_' . $field );
						$field_type		= get_option( '_wpcrm_custom_field_type_' . $field );
						$can_export 	= $field_scope == 'wpcrm-contact' ? true : false;
						if( $can_export ){
							$value 	= get_post_meta( $id, '_wpcrm_custom_field_id_' . $field, true );

							switch ( $field_type ) {
								case 'datepicker':
									$export = date( get_option( 'wpcrm_system_php_date_format' ), $value );
									break;
								case 'repeater-date':
									if ( is_array( $value ) ){
										foreach ( $value as $key => $v ){
											$values[$key] = date( get_option( 'wpcrm_system_php_date_format' ), $v );
										}
										if ( isset( $values ) ){
											$export = implode( ',', $values );
										} else {
											$export = '';
										}
									} else {
										$export = '';
									}
									break;
								case 'repeater-text':
								case 'repeater-textarea':
									if ( is_array( $value ) ){
										$export = implode( ',', $value );
									} else {
										$export = '';
									}
									break;
								default:
									$export = $value;
									break;
							}
							$custom_data = array(
								array(
									'name'	=> get_option( '_wpcrm_custom_field_name_' . $field ),
									'value'	=> $export
								)
							);
							$data = array_merge( $data, $custom_data );
						}
					}
				}
			}
			$export_items[] = array(
				'group_id'		=> $group_id,
				'group_label'	=> $group_label,
				'item_id'		=> $item_id,
				'data'			=> $data,
			);
		}
	}
	// Tell core if we have more contacts to work on still
	// This is not likely to happen as there will probably not be more than one contact with the same email address.
	// If there are, it won't be likely that there are over 500 ($number) of them.
	$done = count( $ids ) < $number;

	return array(
		'data' => $export_items,
		'done' => $done,
	);
}

function register_wp_crm_system_gdpr_contact_exporter( $exporters ) {
	$exporters['wp-crm-system-contact-exporter'] = array(
		'exporter_friendly_name'	=> __( 'WP-CRM System', 'wp-crm-system' ),
		'callback'					=> 'wp_crm_system_gdpr_contact_exporter',
	);
	return $exporters;
}

add_filter(	'wp_privacy_personal_data_exporters', 'register_wp_crm_system_gdpr_contact_exporter', 10 );