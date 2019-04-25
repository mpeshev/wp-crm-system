<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
add_filter( 'wpcrm_system_fields', 'wpcrm_system_opportunity_fields', 10, 1 );
function wpcrm_system_opportunity_fields( $fields ) {
  $opportunityFields = array(
    array(
      'name'          => 'opportunity-attach-to-organization',
      'title'         => __( 'Attach to Organization', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectorganization',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-first wp-crm-one-fourth',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-building wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-attach-to-contact',
      'title'         => __( 'Attach to Contact', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectcontact',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-one-fourth',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-id wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-attach-to-campaign',
      'title'         => __( 'Attach to Campaign', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectcampaign',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-one-fourth',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-megaphone wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-assigned',
      'title'         => __( 'Assigned To', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectuser',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-one-fourth',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-businessman wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-attach-to-organization-new',
      'title'         => __( 'Create New Organization', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'addorganization',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-first wp-crm-one-fourth',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-attach-to-contact-new',
      'title'         => __( 'Create New Contact', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'addcontact',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-one-fourth',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-attach-to-campaign-new',
      'title'         => __( 'Create New Campaign', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'addcampaign',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-one-fourth',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-probability',
      'title'         => __( 'Probability of Winning', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectprogress',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-first wp-crm-one-fourth',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-forms wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-closedate',
      'title'         => __( 'Forecasted Close Date', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'datepicker',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-one-fourth',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-calendar-alt wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-value',
      'title'         => __( 'Value', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'currency',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-one-fourth',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-chart-line wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-wonlost',
      'title'         => __( 'Won/Lost', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'selectwonlost',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-one-fourth',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-yes wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-description',
      'title'         => __( 'Description', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'wysiwyg',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'opportunity-dropbox',
      'title'         => __( 'Link Files From Dropbox', 'wp-crm-system' ),
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'dropbox',
      'scope'         => array( 'wpcrm-opportunity' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
  );
  $fields = array_merge( $opportunityFields, $fields );
  $fields = apply_filters( 'wpcrm_system_opportunity_fields', $fields );
  return $fields;
}
