<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_gdpr_campaign_exporter( $email_address, $page = 1 ) {
	global $post;
	$page		= (int) $page;
	$number		= (int) apply_filters( 'wp_crm_system_gdpr_export_number_filter', 500 ); // Limit us to avoid timing out

	$ids = wpcrm_system_get_records_by_contact_email_address( $email_address, 'campaign', $number, $page );

	$export_items = array();
	if( $ids ){
		foreach ( (array) $ids as $id ){
			$item_id		= 'wp-crm-system-campaign-{$id}';
			$group_id		= 'wp-crm-system-campaigns';
			$group_label	= __( 'CRM Campaigns', 'wp-crm-system' );


			$category_obj	= get_the_terms( $id, 'campaign-type' );
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
					'name'	=> __( 'Campaign ID', 'wp-crm-system' ),
					'value'	=> $id,
				),
				array(
					'name'	=> __( 'Campaign Name', 'wp-crm-system' ),
					'value'	=> get_the_title( $id ),
				),
				array(
					'name'	=> __( 'Active?', 'wp-crm-system' ),
					'value'	=> 'yes' == get_post_meta( $id, '_wpcrm_campaign-active', true ) ? __( 'Yes', 'wp-crm-system' ) : __( 'No', 'wp-crm-system' ),
				),
				array(
					'name'	=> __( 'Assigned To', 'wp-crm-system' ),
					'value'	=> wpcrm_system_get_crm_nicename_by_id( get_post_meta( $id, '_wpcrm_campaign-assigned', true ) ),
				),
				array(
					'name'	=> __( 'Status', 'wp-crm-system' ),
					'value'	=> wpcrm_system_display_status( get_post_meta( $id, '_wpcrm_campaign-status', true ) ),
				),
				array(
					'name'	=> __( 'Start Date', 'wp-crm-system' ),
					'value'	=> date( get_option( 'wpcrm_system_php_date_format' ), get_post_meta( $id, '_wpcrm_campaign-startdate', true ) ),
				),
				array(
					'name'	=> __( 'End Date', 'wp-crm-system' ),
					'value'	=> date( get_option( 'wpcrm_system_php_date_format' ), get_post_meta( $id, '_wpcrm_campaign-enddate', true ) ),
				),
				array(
					'name'	=> __( 'Projected Reach', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_campaign-projectedreach', true ),
				),
				array(
					'name'	=> __( 'Responses', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_campaign-responses', true ),
				),
				array(
					'name'	=> __( 'Budget Cost', 'wp-crm-system' ),
					'value'	=> wpcrm_system_display_currency_symbol( trim( get_option( 'wpcrm_system_default_currency' ) ) ) . get_post_meta( $id, '_wpcrm_campaign-budgetcost', true ),
				),
				array(
					'name'	=> __( 'Actual Cost', 'wp-crm-system' ),
					'value'	=> wpcrm_system_display_currency_symbol( trim( get_option( 'wpcrm_system_default_currency' ) ) ) . get_post_meta( $id, '_wpcrm_campaign-actualcost', true ),
				),
				array(
					'name'	=> __( 'Organization', 'wp-crm-system' ),
					'value'	=> get_the_title( get_post_meta( $id, '_wpcrm_campaign-attach-to-organization', true ) ),
				),
				array(
					'name'	=> __( 'Description', 'wp-crm-system' ),
					'value'	=> get_post_meta( $id, '_wpcrm_campaign-description', true ),
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
						$can_export 	= $field_scope == 'wpcrm-campaign' ? true : false;
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
	// Tell core if we have more campaigns to work on still
	$done = count( $ids ) < $number;

	return array(
		'data' => $export_items,
		'done' => $done,
	);
}

function register_wp_crm_system_gdpr_campaign_exporter( $exporters ) {
	$exporters['wp-crm-system-campaign-exporter'] = array(
		'exporter_friendly_name'	=> __( 'WP-CRM System Campaigns', 'wp-crm-system' ),
		'callback'					=> 'wp_crm_system_gdpr_campaign_exporter',
	);
	return $exporters;
}

add_filter(	'wp_privacy_personal_data_exporters', 'register_wp_crm_system_gdpr_campaign_exporter', 10 );