<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}
add_filter( 'wpcrm_system_fields', 'wpcrm_system_campaign_fields', 10, 1 );
function wpcrm_system_campaign_fields( $fields ) {
	$campaignFields = array(
		array(
			'name'			=> 'campaign-active',
			'title'			=> __( 'Active', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'checkbox',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-first wp-crm-one-third',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> 'dashicons dashicons-clock wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-assigned',
			'title'			=> __( 'Assigned To', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'selectuser',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-one-third',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> 'dashicons dashicons-businessman wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-status',
			'title'			=> __( 'Status', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'selectstatus',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-one-third',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> 'dashicons dashicons-admin-tools wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-startdate',
			'title'			=> __( 'Start Date', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'datepicker',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-first wp-crm-one-half',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> 'dashicons dashicons-calendar-alt wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-enddate',
			'title'			=> __( 'End Date', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'datepicker',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-one-half',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> 'dashicons dashicons-calendar-alt wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-projectedreach',
			'title'			=> __( 'Projected Reach', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'number',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-first wp-crm-one-half',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> 'dashicons dashicons-groups wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-responses',
			'title'			=> __( 'Total Responses', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'number',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-one-half',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> 'dashicons dashicons-chart-bar wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-budgetcost',
			'title'			=> __( 'Budgeted Cost', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'currency',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-first wp-crm-one-half',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> 'dashicons dashicons-chart-area wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-actualcost',
			'title'			=> __( 'Actual Cost', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'currency',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-one-half',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> 'dashicons dashicons-chart-line wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-attach-to-organization',
			'title'			=> __( 'Attach to Organization', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'selectorganization',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-first wp-crm-one-half',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> 'dashicons dashicons-building wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-attach-to-contact',
			'title'			=> __( 'Attach to Contact', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'selectcontact',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-one-half',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> 'dashicons dashicons-id wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-attach-to-organization-new',
			'title'			=> __( 'Create New Organization', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'addorganization',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-first wp-crm-one-half',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> '',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-attach-to-contact-new',
			'title'			=> __( 'Create New Contact', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'addcontact',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-one-half',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> '',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-description',
			'title'			=> __( 'Additional Information', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'wysiwyg',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-first',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> '',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'campaign-dropbox',
			'title'			=> __( 'Link Files From Dropbox', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'dropbox',
			'scope'			=> array( 'wpcrm-campaign' ),
			'style'					=> 'wp-crm-first',
			'before'				=> '',
			'after'					=> '',
			'icon'					=> '',
			'capability'	=> WPCRM_USER_ACCESS
		),
	);
	$fields = array_merge( $campaignFields, $fields );
	$fields = apply_filters( 'wpcrm_system_campaign_fields', $fields );
	return $fields;
}
