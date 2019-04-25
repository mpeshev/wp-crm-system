<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
		die( "Sorry, you are not allowed to access this page directly." );
}
add_filter( 'wpcrm_system_fields', 'wpcrm_system_project_fields', 10, 1 );
function wpcrm_system_project_fields( $fields ) {
	$projectFields = array(
		array(
			'name'			=> 'project-value',
			'title'			=> __( 'Value', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'currency',
			'scope'			=> array( 'wpcrm-project' ),
			'style'			=> 'wp-crm-first wp-crm-one-fourth',
			'before'		=> '',
			'after'			=> '',
			'icon'			=> 'dashicons dashicons-chart-line wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'project-closedate',
			'title'			=> __( 'Close Date', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'datepicker',
			'scope'			=> array( 'wpcrm-project' ),
			'style'			=> 'wp-crm-one-fourth',
			'before'		=> '',
			'after'			=> '',
			'icon'			=> 'dashicons dashicons-calendar-alt wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'project-status',
			'title'			=> __( 'Status', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'selectstatus',
			'scope'			=> array( 'wpcrm-project' ),
			'style'			=> 'wp-crm-one-fourth',
			'before'		=> '',
			'after'			=> '',
			'icon'			=> 'dashicons dashicons-admin-tools wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'project-progress',
			'title'			=> __( 'Progress', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'selectprogress',
			'scope'			=> array( 'wpcrm-project' ),
			'style'			=> 'wp-crm-one-fourth',
			'before'		=> '',
			'after'			=> '',
			'icon'			=> 'dashicons dashicons-forms wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'project-attach-to-organization',
			'title'			=> __( 'Attach to Organization', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'selectorganization',
			'scope'			=> array( 'wpcrm-project' ),
			'style'			=> 'wp-crm-first wp-crm-one-third',
			'before'		=> '',
			'after'			=> '',
			'icon'			=> 'dashicons dashicons-building wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'project-attach-to-contact',
			'title'			=> __( 'Attach to Contact', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'selectcontact',
			'scope'			=> array( 'wpcrm-project' ),
			'style'			=> 'wp-crm-one-third',
			'before'		=> '',
			'after'			=> '',
			'icon'			=> 'dashicons dashicons-id wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'project-assigned',
			'title'			=> __( 'Assigned To', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'selectuser',
			'scope'			=> array( 'wpcrm-project' ),
			'style'			=> 'wp-crm-one-third',
			'before'		=> '',
			'after'			=> '',
			'icon'			=> 'dashicons dashicons-businessman wpcrm-dashicons',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'project-attach-to-organization-new',
			'title'			=> __( 'Create New Organization', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'addorganization',
			'scope'			=> array( 'wpcrm-project' ),
			'style'			=> 'wp-crm-first wp-crm-one-third',
			'before'		=> '',
			'after'			=> '',
			'icon'			=> '',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'project-attach-to-contact-new',
			'title'			=> __( 'Create New Contact', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'addcontact',
			'scope'			=> array( 'wpcrm-project' ),
			'style'			=> 'wp-crm-one-third',
			'before'		=> '',
			'after'			=> '',
			'icon'			=> '',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'project-description',
			'title'			=> __( 'Description', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'wysiwyg',
			'scope'			=> array( 'wpcrm-project' ),
			'style'			=> 'wp-crm-first',
			'before'		=> '',
			'after'			=> '',
			'icon'			=> '',
			'capability'	=> WPCRM_USER_ACCESS
		),
		array(
			'name'			=> 'project-dropbox',
			'title'			=> __( 'Link Files From Dropbox', 'wp-crm-system' ),
			'description'	=> '',
			'placeholder'	=> '',
			'type'			=> 'dropbox',
			'scope'			=> array( 'wpcrm-project' ),
			'style'			=> 'wp-crm-first',
			'before'		=> '',
			'after'			=> '',
			'icon'			=> '',
			'capability'	=> WPCRM_USER_ACCESS
		),
	);
	$fields = array_merge( $projectFields, $fields );
	$fields = apply_filters( 'wpcrm_system_project_fields', $fields );
	return $fields;
}
