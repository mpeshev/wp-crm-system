<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
add_filter( 'wpcrm_system_fields', 'wpcrm_system_campaign_fields', 10, 1 );
function wpcrm_system_campaign_fields( $fields ) {
  $campaignFields = array(
    array(
      'name'          => 'campaign-active',
      'title'         => WPCRM_ACTIVE,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'checkbox',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-first wp-crm-one-third',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-clock wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-assigned',
      'title'         => WPCRM_ASSIGNED,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectuser',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-one-third',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-businessman wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-status',
      'title'         => WPCRM_STATUS,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectstatus',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-one-third',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-admin-tools wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-startdate',
      'title'         => WPCRM_START,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'datepicker',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-first wp-crm-one-half',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-calendar-alt wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-enddate',
      'title'         => WPCRM_END,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'datepicker',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-one-half',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-calendar-alt wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-projectedreach',
      'title'         => WPCRM_REACH,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'number',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-first wp-crm-one-half',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-groups wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-responses',
      'title'         => WPCRM_RESPONSES,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'number',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-one-half',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-chart-bar wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-budgetcost',
      'title'         => WPCRM_BUDGETED_COST,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'currency',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-first wp-crm-one-half',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-chart-area wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-actualcost',
      'title'         => WPCRM_ACTUAL_COST,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'currency',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-one-half',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-chart-line wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-attach-to-organization',
      'title'         => WPCRM_ATTACH_ORG,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectorganization',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-first wp-crm-one-half',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-building wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-attach-to-contact',
      'title'         => WPCRM_ATTACH_CONTACT,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectcontact',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-one-half',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-id wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-attach-to-organization-new',
      'title'         => WPCRM_CREATE_ORGANIZATION,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'addorganization',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-first wp-crm-one-half',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-attach-to-contact-new',
      'title'         => WPCRM_CREATE_CONTACT,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'addcontact',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-one-half',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-description',
      'title'         => WPCRM_ADDITIONAL,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'wysiwyg',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'campaign-dropbox',
      'title'         => WPCRM_DROPBOX,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'dropbox',
      'scope'         => array( 'wpcrm-campaign' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
  );
  $fields = array_merge( $campaignFields, $fields );
  return $fields;
}
