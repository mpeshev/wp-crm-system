<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
add_filter( 'wpcrm_system_fields', 'wpcrm_system_task_fields', 10, 1 );
function wpcrm_system_task_fields( $fields ) {
  $taskFields = array(
    array(
      'name'          => 'task-attach-to-organization',
      'title'         => __( 'Attach to Organization', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectorganization',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '<div class="wp-crm-first wp-crm-one-half"><div class="wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> 'dashicons dashicons-building wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-attach-to-organization-new',
      'title'         => __( 'Create New Organization', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'addorganization',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-attach-to-contact',
      'title'         => __( 'Attach to Contact', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectcontact',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-first wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> 'dashicons dashicons-id wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-attach-to-contact-new',
      'title'         => __( 'Create New Contact', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'addcontact',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-attach-to-project',
      'title'         => __( 'Attach to Project', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectproject',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-first wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> 'dashicons dashicons-clipboard wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-attach-to-project-new',
      'title'         => __( 'Create New Project', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'addproject',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-assignment',
      'title'         => __( 'Assigned To', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectuser',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-first wp-crm-inline">',
      'after'					=> '</div></div>',
      'icon'					=> 'dashicons dashicons-businessman wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-start-date',
      'title'         => __( 'Start Date', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'datepicker',
      'scope'         => array( 'wpcrm-task' ),
      'style' 				=> 'wp-crm-first',
      'before'				=> '<div class="wp-crm-one-half"><div class="wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> 'dashicons dashicons-calendar-alt wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-due-date',
      'title'         => __( 'Due Date', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'datepicker',
      'scope'         => array( 'wpcrm-task' ),
      'style' 				=> '',
      'before'				=> '<div class="wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> 'dashicons dashicons-calendar-alt wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-progress',
      'title'         => __( 'Progress', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectprogress',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '<div class="wp-crm-first wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> 'dashicons dashicons-forms wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-priority',
      'title'         => __( 'Priority', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectpriority',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> 'dashicons dashicons-warning wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-status',
      'title'         => __( 'Status', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectstatus',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-inline">',
      'after'					=> '</div></div>',
      'icon'					=> 'dashicons dashicons-admin-tools wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-description',
      'title'         => __( 'Description', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'wysiwyg',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'task-dropbox',
      'title'         => __( 'Link Files From Dropbox', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'dropbox',
      'scope'         => array( 'wpcrm-task' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
  );
  $fields = array_merge( $taskFields, $fields );
  $fields = apply_filters( 'wpcrm_system_task_fields', $fields );
  return $fields;
}
