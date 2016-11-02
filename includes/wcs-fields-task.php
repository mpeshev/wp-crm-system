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
      'title'         => WPCRM_ATTACH_ORG,
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
      'title'         => WPCRM_CREATE_ORGANIZATION,
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
      'title'         => WPCRM_ATTACH_CONTACT,
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
      'title'         => WPCRM_CREATE_CONTACT,
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
      'title'         => WPCRM_ATTACH_PROJECT,
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
      'title'         => WPCRM_CREATE_PROJECT,
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
      'title'         => WPCRM_ASSIGNED,
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
      'title'         => WPCRM_START,
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
      'title'         => WPCRM_DUE,
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
      'title'         => WPCRM_PROGRESS,
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
      'title'         => WPCRM_PRIORITY,
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
      'title'         => WPCRM_STATUS,
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
      'title'         => WPCRM_DESCRIPTION,
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
      'title'         => WPCRM_DROPBOX,
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
  return $fields;
}
