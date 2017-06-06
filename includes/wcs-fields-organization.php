<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
add_filter( 'wpcrm_system_fields', 'wpcrm_system_organization_fields', 10, 1 );
function wpcrm_system_organization_fields( $fields ) {
  $organizationFields = array(
    array(
      'name'          => 'organization-phone',
      'title'         => WPCRM_PHONE,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'phone',
      'scope'         => array( 'wpcrm-organization' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '<div class="wp-crm-first wp-crm-one-half">',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-phone wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'organization-email',
      'title'         => WPCRM_EMAIL,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'email',
      'scope'         => array( 'wpcrm-organization' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> 'dashicons dashicons-email wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'organization-website',
      'title'         => WPCRM_WEBSITE,
      'description'   => '',
      'placeholder'   => 'http://',
      'type'          => 'url',
      'scope'         => array( 'wpcrm-organization' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '',
      'after'					=> '</div>',
      'icon'					=> 'dashicons dashicons-admin-links wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'organization-address1',
      'title'         => WPCRM_ADDRESS_1,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'default',
      'scope'         => array( 'wpcrm-organization' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '<div class="wp-crm-one-half"><div class="wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> 'dashicons dashicons-location wpcrm-dashicons',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'organization-address2',
      'title'         => WPCRM_ADDRESS_2,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'default',
      'scope'         => array( 'wpcrm-organization' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'organization-city',
      'title'         => WPCRM_CITY,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'default',
      'scope'         => array( 'wpcrm-organization' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-first wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'organization-state',
      'title'         => WPCRM_STATE,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'default',
      'scope'         => array( 'wpcrm-organization' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'organization-postal',
      'title'         => WPCRM_POSTAL,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'default',
      'scope'         => array( 'wpcrm-organization' ),
      'style'					=> '',
      'before'				=> '<div class="wp-crm-inline">',
      'after'					=> '</div>',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'organization-country',
      'title'         => WPCRM_COUNTRY,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'default',
      'scope'         => array( 'wpcrm-organization' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '',
      'after'					=> '</div>',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'organization-information',
      'title'         => WPCRM_ADDITIONAL,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'wysiwyg',
      'scope'         => array( 'wpcrm-organization' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
    array(
      'name'          => 'organization-dropbox',
      'title'         => WPCRM_DROPBOX,
      'description'   => '',
      'placeholder'   => '',
      'type'          => 'dropbox',
      'scope'         => array( 'wpcrm-organization' ),
      'style'					=> 'wp-crm-first',
      'before'				=> '',
      'after'					=> '',
      'icon'					=> '',
      'capability'    => WPCRM_USER_ACCESS
    ),
  );
  $fields = array_merge( $organizationFields, $fields );
  return $fields;
}
