<?php
   /*
   Plugin Name: WP-CRM System
   Plugin URI: https://www.wp-crm.com
   Description: A complete CRM for WordPress
   Version: 1.0.1
   Author: Scott DeLuzio
   Author URI: https://www.wp-crm.com
   Text Domain: wp-crm-system
   */
   
	/*  Copyright 2015  Scott DeLuzio (email : support (at) wp-crm.com)	*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if (!defined('WPCRM_BASE_STORE_URL')){
	define( 'WPCRM_BASE_STORE_URL', 'http://wp-crm.com' );
}
define( 'WPCRM_BASE_PLUGIN_PATH', dirname( __FILE__ ) );

/* Load Text Domain */
add_action('plugins_loaded', 'wp_crm_plugin_init');
function wp_crm_plugin_init() {
	load_plugin_textdomain( 'wp-crm-system', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}
/* Add a metabox on dashboard for WP-CRM System Notices */
add_action('wp_dashboard_setup', 'wp_crm_dashboard_widget');
function wp_crm_dashboard_widget() {
	global $wp_meta_boxes;
	wp_add_dashboard_widget('wp_crm_notifications', 'WP-CRM System', 'wp_crm_dashboard_notifications');
}
function wp_crm_dashboard_notifications() {
	include('wp-crm-system-dashboard.php');
}
	
/* Settings Page */

// Hook for adding admin menu
add_action('admin_menu', 'wpcrm_admin_page');
// action function for above hook
function wpcrm_admin_page() {
    // Add a new menu:
    add_menu_page(__('WP CRM', 'wp-crm-system'), __('WP CRM', 'wp-crm-system'),'manage_options','wpcrm','wpcrm_settings_page', 'dashicons-id');
	add_submenu_page( 'wpcrm', __('Reports', 'wp-crm-system'), __('Reports', 'wp-crm-system'), 'manage_options', 'wpcrm-reports', 'wpcrm_reports_page' );
	add_submenu_page( 'wpcrm', __('Settings', 'wp-crm-system'), __('Settings', 'wp-crm-system'), 'manage_options', 'wpcrm-settings', 'wpcrm_settings_page' );
	add_submenu_page( 'wpcrm', __('Extensions', 'wp-crm-system'), __('Extensions', 'wp-crm-system'), 'manage_options', 'wpcrm-extensions', 'wpcrm_extensions_page' );
	
}
//Display the page content for the plugin settings and reports
function wpcrm_reports_page() {
	include('wp-crm-system-reports.php');
}
function wpcrm_settings_page() {
	include('wp-crm-system-settings.php');
}
function wpcrm_extensions_page() {
	include('wp-crm-extensions.php');
}
//Register Settings
register_activation_hook(__FILE__, 'activate_wpcrm_system_settings');
register_uninstall_hook(__FILE__, 'deactivate_wpcrm_system_settings');
add_action('admin_init', 'register_wpcrm_system_settings');
function activate_wpcrm_system_settings() {
	add_option('wpcrm_system_select_user_role', 'Administrator');
	add_option('wpcrm_system_default_currency', 'USD');
	add_option('wpcrm_system_report_currency_decimals', 0);
	add_option('wpcrm_system_report_currency_decimal_point', '.');
	add_option('wpcrm_system_report_currency_thousand_separator', ',');
	add_option('wpcrm_system_date_format', 'MM dd, yy');
	add_option('wpcrm_system_php_date_format', 'F d, Y');
}
function deactivate_wpcrm_system_settings() {
	delete_option('wpcrm_system_select_user_role');
	delete_option('wpcrm_system_default_currency');
	delete_option('wpcrm_system_report_currency_decimals');
	delete_option('wpcrm_system_report_currency_decimal_point');
	delete_option('wpcrm_system_report_currency_thousand_separator');
	delete_option('wpcrm_system_date_format');
	delete_option('wpcrm_system_php_date_format');
}
function register_wpcrm_system_settings() {
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_select_user_role');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_default_currency');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_report_currency_decimals');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_report_currency_decimal_point');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_report_currency_thousand_separator');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_date_format');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_php_date_format');
}

/**
 * Register post types.
 */
add_action( 'init', 'wpcrm_contacts_init' );
add_action( 'init', 'wpcrm_contact_taxonomy');
add_action( 'init', 'wpcrm_tasks_init' );
add_action( 'init', 'wpcrm_task_taxonomy');
add_action( 'init', 'wpcrm_organizations_init' );
add_action( 'init', 'wpcrm_organization_taxonomy');
add_action( 'init', 'wpcrm_opportunities_init' );
add_action( 'init', 'wpcrm_opportunity_taxonomy');
add_action( 'init', 'wpcrm_projects_init' );
add_action( 'init', 'wpcrm_project_taxonomy');

/* Contacts post type. */
function wpcrm_contacts_init() {
	$labels = array(
		'name'               => __( 'Contacts', 'wp-crm-system' ),
		'singular_name'      => __( 'Contact', 'wp-crm-system' ),
		'menu_name'          => __( 'Contacts', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Contact', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Contact', 'wp-crm-system' ),
		'new_item'           => __( 'New Contact', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Contact', 'wp-crm-system' ),
		'view_item'          => __( 'View Contact', 'wp-crm-system' ),
		'all_items'          => __( 'Contacts', 'wp-crm-system' ),
		'search_items'       => __( 'Search Contacts', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Contact:', 'wp-crm-system' ),
		'not_found'          => __( 'No contacts found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No contacts found in Trash.', 'wp-crm-system' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Contacts', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'wpcrm-contact' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('contact-type'),
		'supports'           => array( 'title', 'thumbnail', 'custom-fields' )
	);

	register_post_type( 'wpcrm-contact', $args );
}
function wpcrm_contact_taxonomy() {
	$labels = array(
		'name'              => __( 'Contact Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Contact Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Contact Type' ),
		'update_item'       => __( 'Update Contact Type' ),
		'add_new_item'      => __( 'Add New Contact Type' ),
		'new_item_name'     => __( 'New Contact Type' ),
		'menu_name'         => __( 'Contact Types' ),
	);
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => false,
	);
	register_taxonomy( 'contact-type', 'wpcrm-contact', $args );
}
/* Tasks post type. */
function wpcrm_tasks_init() {
	$labels = array(
		'name'               => __( 'Tasks', 'wp-crm-system' ),
		'singular_name'      => __( 'Task', 'wp-crm-system' ),
		'menu_name'          => __( 'Tasks', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Task', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Task', 'wp-crm-system' ),
		'new_item'           => __( 'New Task', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Task', 'wp-crm-system' ),
		'view_item'          => __( 'View Task', 'wp-crm-system' ),
		'all_items'          => __( 'Tasks', 'wp-crm-system' ),
		'search_items'       => __( 'Search Tasks', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Task:', 'wp-crm-system' ),
		'not_found'          => __( 'No tasks found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No tasks found in Trash.', 'wp-crm-system' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Tasks', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'wpcrm-task' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('task-type'),
		'supports'           => array( 'title', 'thumbnail', 'custom-fields' )
	);

	register_post_type( 'wpcrm-task', $args );
}
function wpcrm_task_taxonomy() {
	$labels = array(
		'name'              => __( 'Task Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Task Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Task Type' ),
		'update_item'       => __( 'Update Task Type' ),
		'add_new_item'      => __( 'Add New Task Type' ),
		'new_item_name'     => __( 'New Task Type' ),
		'menu_name'         => __( 'Task Types' ),
	);
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_in_menu'			=> 'edit.php?post_type=wpcrm',
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => false,
	);
	register_taxonomy( 'task-type', 'wpcrm-task', $args );
}
/* Organizations post type. */
function wpcrm_organizations_init() {
	$labels = array(
		'name'               => __( 'Organizations', 'wp-crm-system' ),
		'singular_name'      => __( 'Organization', 'wp-crm-system' ),
		'menu_name'          => __( 'Organizations', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Organization', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Organization', 'wp-crm-system' ),
		'new_item'           => __( 'New Organization', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Organization', 'wp-crm-system' ),
		'view_item'          => __( 'View Organization', 'wp-crm-system' ),
		'all_items'          => __( 'Organizations', 'wp-crm-system' ),
		'search_items'       => __( 'Search Organizations', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Organization:', 'wp-crm-system' ),
		'not_found'          => __( 'No organizations found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No organizations found in Trash.', 'wp-crm-system' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Organizations', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'wpcrm-organization' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('organization-type'),
		'supports'           => array( 'title', 'thumbnail', 'custom-fields' )
	);

	register_post_type( 'wpcrm-organization', $args );
}
function wpcrm_organization_taxonomy() {
	$labels = array(
		'name'              => __( 'Organization Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Organization Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Organization Type' ),
		'update_item'       => __( 'Update Organization Type' ),
		'add_new_item'      => __( 'Add New Organization Type' ),
		'new_item_name'     => __( 'New Organization Type' ),
		'menu_name'         => __( 'Organization Types' ),
	);
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => false,
	);
	register_taxonomy( 'organization-type', 'wpcrm-organization', $args );
}
/* Opportunities post type. */
function wpcrm_opportunities_init() {
	$labels = array(
		'name'               => __( 'Opportunities', 'wp-crm-system' ),
		'singular_name'      => __( 'Opportunity', 'wp-crm-system' ),
		'menu_name'          => __( 'Opportunities', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Opportunity', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Opportunity', 'wp-crm-system' ),
		'new_item'           => __( 'New Opportunity', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Opportunity', 'wp-crm-system' ),
		'view_item'          => __( 'View Opportunity', 'wp-crm-system' ),
		'all_items'          => __( 'Opportunities', 'wp-crm-system' ),
		'search_items'       => __( 'Search Opportunities', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Opportunity:', 'wp-crm-system' ),
		'not_found'          => __( 'No opportunities found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No opportunities found in Trash.', 'wp-crm-system' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Opportunities', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'wpcrm-opportunity' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('opportunity-type'),
		'supports'           => array( 'title', 'thumbnail', 'custom-fields' )
	);

	register_post_type( 'wpcrm-opportunity', $args );
}
function wpcrm_opportunity_taxonomy() {
	$labels = array(
		'name'              => __( 'Opportunity Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Opportunity Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Opportunity Type' ),
		'update_item'       => __( 'Update Opportunity Type' ),
		'add_new_item'      => __( 'Add New Opportunity Type' ),
		'new_item_name'     => __( 'New Opportunity Type' ),
		'menu_name'         => __( 'Opportunity Types' ),
	);
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => false,
	);
	register_taxonomy( 'opportunity-type', 'wpcrm-opportunity', $args );
}
/* Projects post type. */
function wpcrm_projects_init() {
	$labels = array(
		'name'               => __( 'Projects', 'wp-crm-system' ),
		'singular_name'      => __( 'Project', 'wp-crm-system' ),
		'menu_name'          => __( 'Projects', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Project', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Project', 'wp-crm-system' ),
		'new_item'           => __( 'New Project', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Project', 'wp-crm-system' ),
		'view_item'          => __( 'View Project', 'wp-crm-system' ),
		'all_items'          => __( 'Projects', 'wp-crm-system' ),
		'search_items'       => __( 'Search Projects', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Project:', 'wp-crm-system' ),
		'not_found'          => __( 'No projects found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No projects found in Trash.', 'wp-crm-system' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Projects', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'wpcrm-project' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('project-type'),
		'supports'           => array( 'title', 'thumbnail', 'custom-fields' )
	);

	register_post_type( 'wpcrm-project', $args );
}
function wpcrm_project_taxonomy() {
	$labels = array(
		'name'              => __( 'Project Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Project Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Project Type' ),
		'update_item'       => __( 'Update Project Type' ),
		'add_new_item'      => __( 'Add New Project Type' ),
		'new_item_name'     => __( 'New Project Type' ),
		'menu_name'         => __( 'Project Types' ),
	);
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => false,
	);
	register_taxonomy( 'project-type', 'wpcrm-project', $args );
}
if ( !class_exists('wpCRMSystemCustomFields') ) {
 
    class wpCRMSystemCustomFields {
        /**
        * @var  string  $prefix  The prefix for storing custom fields in the postmeta table
        */
        var $prefix = '_wpcrm_';
        /**
        * @var  array  $postTypes  An array of public custom post types, plus the standard "post" and "page" - add the custom types you want to include here
        */
        var $postTypes = array( 'wpcrm-contact', 'wpcrm-task', 'wpcrm-organization', 'wpcrm-opportunity', 'wpcrm-project' );
        /**
        * @var  array  $customFields  Defines the custom fields available
        */
        var $customFields = array(
			// Contact Fields
            array(
                'name'          => 'contact-name-prefix',
                'title'         => 'Name Prefix',
                'description'   => '',
                'type'          => 'selectnameprefix',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-first-name',
                'title'         => 'First Name',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
            array(
                'name'          => 'contact-last-name',
                'title'         => 'Last Name',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
				'name'          => 'contact-attach-to-organization',
                'title'         => 'Organization',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectorganization',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-role',
                'title'         => 'Role',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-email',
                'title'         => 'Email',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-phone',
                'title'         => 'Phone',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-mobile-phone',
                'title'         => 'Mobile Phone',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-fax',
                'title'         => 'Fax',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-website',
                'title'         => 'Website',
                'description'   => '',
				'placeholder'	=>	'http://',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-address1',
                'title'         => 'Address 1',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-address2',
                'title'         => 'Address 2',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-city',
                'title'         => 'City',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-state',
                'title'         => 'State/Province',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-postal',
                'title'         => 'Postal Code',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-country',
                'title'         => 'Country',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-gmap',
                'title'         => 'Contact Location',
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'gmap',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'contact-additional',
                'title'         => 'Additional Information',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'wysiwyg',
                'scope'         =>   array( 'wpcrm-contact' ),
                'capability'    => 'manage_options'
            ),
			//Task Fields
			array(
                'name'          => 'task-assignment',
                'title'         => 'Assigned To',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectuser',
                'scope'         =>   array( 'wpcrm-task' ),
                'capability'    => 'manage_options'
            ),
			array(
				'name'          => 'task-attach-to-organization',
                'title'         => 'Attach to organization',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectorganization',
                'scope'         =>   array( 'wpcrm-task' ),
                'capability'    => 'manage_options'
            ),
			array(
				'name'          => 'task-attach-to-contact',
                'title'         => 'Attach to contact',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectcontact',
                'scope'         =>   array( 'wpcrm-task' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'task-due-date',
                'title'         => 'Due Date',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'datepicker',
                'scope'         =>   array( 'wpcrm-task' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'task-start-date',
                'title'         => 'Start Date',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'datepicker',
                'scope'         =>   array( 'wpcrm-task' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'task-progress',
                'title'         => 'Progress',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectprogress',
                'scope'         =>   array( 'wpcrm-task' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'task-priority',
                'title'         => 'Priority',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectpriority',
                'scope'         =>   array( 'wpcrm-task' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'task-status',
                'title'         => 'Status',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectstatus',
                'scope'         =>   array( 'wpcrm-task' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'task-description',
                'title'         => 'Description',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'wysiwyg',
                'scope'         =>   array( 'wpcrm-task' ),
                'capability'    => 'manage_options'
            ),
			//Organization Fields
			array(
                'name'          => 'organization-phone',
                'title'         => 'Phone',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-organization' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'organization-email',
                'title'         => 'Email',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-organization' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'organization-website',
                'title'         => 'Website',
                'description'   => '',
				'placeholder'   => 'http://',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-organization' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'organization-address1',
                'title'         => 'Address 1',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-organization' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'organization-address2',
                'title'         => 'Address 2',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-organization' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'organization-city',
                'title'         => 'City',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-organization' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'organization-state',
                'title'         => 'State/Province',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-organization' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'organization-postal',
                'title'         => 'Postal Code',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-organization' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'organization-country',
                'title'         => 'Country',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'default',
                'scope'         =>   array( 'wpcrm-organization' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'organization-gmap',
                'title'         => 'Organization Location',
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'gmap',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'organization-information',
                'title'         => 'Additional Organization Information',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'wysiwyg',
                'scope'         =>   array( 'wpcrm-organization' ),
                'capability'    => 'manage_options'
            ),
			//Opportunity Fields
			array(
                'name'          => 'opportunity-assigned',
                'title'         => 'Responsible Party',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectuser',
                'scope'         =>   array( 'wpcrm-opportunity' ),
                'capability'    => 'manage_options'
            ),
			array(
				'name'          => 'opportunity-attach-to-organization',
                'title'         => 'Attach to organization',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectorganization',
                'scope'         =>   array( 'wpcrm-opportunity' ),
                'capability'    => 'manage_options'
            ),
			array(
				'name'          => 'opportunity-attach-to-contact',
                'title'         => 'Attach to contact',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectcontact',
                'scope'         =>   array( 'wpcrm-opportunity' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'opportunity-description',
                'title'         => 'Description',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'wysiwyg',
                'scope'         =>   array( 'wpcrm-opportunity' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'opportunity-probability',
                'title'         => 'Probability of Winning',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectprogress',
                'scope'         =>   array( 'wpcrm-opportunity' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'opportunity-closedate',
                'title'         => 'Forecasted Close Date',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'datepicker',
                'scope'         =>   array( 'wpcrm-opportunity' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'opportunity-value',
                'title'         => 'Opportunity Value',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'currency',
                'scope'         =>   array( 'wpcrm-opportunity' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'opportunity-wonlost',
                'title'         => 'Opportunity Won/Lost',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectwonlost',
                'scope'         =>   array( 'wpcrm-opportunity' ),
                'capability'    => 'manage_options'
            ),
			//Project Fields
			array(
                'name'          => 'project-description',
                'title'         => 'Project Description',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'wysiwyg',
                'scope'         =>   array( 'wpcrm-project' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'project-closedate',
                'title'         => 'Project Close Date',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'datepicker',
                'scope'         =>   array( 'wpcrm-project' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'project-progress',
                'title'         => 'Progress',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectprogress',
                'scope'         =>   array( 'wpcrm-project' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'project-status',
                'title'         => 'Project Status',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectstatus',
                'scope'         =>   array( 'wpcrm-project' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'project-value',
                'title'         => 'Project Value',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'currency',
                'scope'         =>   array( 'wpcrm-project' ),
                'capability'    => 'manage_options'
            ),
			array(
                'name'          => 'project-assigned',
                'title'         => 'Responsible Party',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectuser',
                'scope'         =>   array( 'wpcrm-project' ),
                'capability'    => 'manage_options'
            ),
			array(
				'name'          => 'project-attach-to-organization',
                'title'         => 'Attach to organization',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectorganization',
                'scope'         =>   array( 'wpcrm-project' ),
                'capability'    => 'manage_options'
            ),
			array(
				'name'          => 'project-attach-to-contact',
                'title'         => 'Attach to contact',
                'description'   => '',
				'placeholder'   => '',
                'type'          =>   'selectcontact',
                'scope'         =>   array( 'wpcrm-project' ),
                'capability'    => 'manage_options'
            ),
        );
        /**
        * PHP 5 Constructor
        */
        function __construct() {
            add_action( 'admin_menu', array( &$this, 'createCustomFields' ) );
            add_action( 'save_post', array( &$this, 'saveCustomFields' ), 1, 2 );
            // Remove default custom field meta box
            add_action( 'do_meta_boxes', array( &$this, 'removeDefaultCustomFields' ), 10, 3 );
        }
        /**
        * Remove the default Custom Fields meta box
        */
        function removeDefaultCustomFields( $type, $context, $post ) {
            foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
                foreach ( $this->postTypes as $postType ) {
                    remove_meta_box( 'postcustom', $postType, $context );
                }
            }
        }
        /**
        * Create the new Custom Fields meta box
        */
        function createCustomFields() {
            if ( function_exists( 'add_meta_box' ) ) {
                foreach ( $this->postTypes as $postType ) {
                    add_meta_box( 'wpcrm-custom-fields', 'Fields', array( &$this, 'wpcrmCustomFields' ), $postType, 'normal', 'high' );
                }
            }
        }
        /**
        * Display the new Custom Fields meta box
        */
        function wpcrmCustomFields() {
            global $post;
            ?>
            <div class="form-wrap">
                <?php
                wp_nonce_field( 'my-custom-fields', 'my-custom-fields_wpnonce', false, true );
                foreach ( $this->customFields as $customField ) {
                    // Check scope
                    $scope = $customField[ 'scope' ];
                    $output = false;
                    foreach ( $scope as $scopeItem ) {
                        switch ( $scopeItem ) {
                            default: {
                                if ( $post->post_type == $scopeItem )
                                    $output = true;
                                break;
                            }
                        }
                        if ( $output ) break;
                    }
                    // Check capability
                    if ( !current_user_can( $customField['capability'], $post->ID ) )
                        $output = false;
                    // Output if allowed
                    if ( $output ) { ?>
                        <div class="form-field form-required">
                            <?php
                            switch ( $customField[ 'type' ] ) {
								case 'selectcontact':
								case 'selectorganization':
								case 'selectprogress':
								case 'selectwonlost':
								case 'selectpriority':
								case 'selectstatus':
								case 'selectnameprefix':
								case 'selectuser':
								case 'selectemail': {
									// Select
									echo '<label for="' . $this->prefix . $customField[ 'name' ] .'" style="display:inline;"><strong>' . $customField[ 'title' ] . '</strong></label>&nbsp;&nbsp;';
									//Select User
									if ( $customField[ 'type' ] == "selectuser" ) {
										echo'<select name="' . $this->prefix . $customField[ 'name' ] . '">';
										if ( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) == '') { $selected = 'selected'; } else { $selected = ''; }
										echo '<option value="" ' . $selected . '>Not Assigned</option>';
										
										$roles = explode(",", get_option('wpcrm_system_select_user_role'));
										foreach ($roles as $role) {
											$args = array('role' => $role);
											$users = get_users($args);
											if( empty($users) )
											  break;
											foreach( $users as $user ){
												if (get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) == $user->data->user_login) { $selected = 'selected'; } else { $selected = ''; }
												echo '<option value="'.$user->data->user_login.'" ' . $selected . '>'.$user->data->display_name.'</option>';
											}
										}
										echo'</select>';
									} elseif ( $customField[ 'type' ] == "selectorganization" ) {
										//Select Organization
										$orgs = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-organization'));
										if ($orgs) {
											echo'<select name="' . $this->prefix . $customField[ 'name' ] . '">';
											if ( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) == '') { $selected = 'selected'; } else { $selected = ''; }
											echo '<option value="" ' . $selected . '>Not Assigned</option>';
											foreach($orgs as $org) {
												if (get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) == $org->ID) { $selected = 'selected'; } else { $selected = ''; }
												echo '<option value="' . $org->ID . '"' . $selected . '>' . get_the_title($org->ID) . '</option>';
											}
											echo '</select>';
										} else {
											echo '<a href="' . admin_url('edit.php?post_type=wpcrm-organization') . '">';
											_e('Please create an organization first.','wp-crm-system');
											echo '</a>';
										}
									} elseif ( $customField[ 'type' ] == "selectcontact" ) {
										//Select Contact
										$contacts = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-contact'));
										if ($contacts) {
											echo'<select name="' . $this->prefix . $customField[ 'name' ] . '">';
											if ( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) == '') { $selected = 'selected'; } else { $selected = ''; }
											echo '<option value="" ' . $selected . '>' . _e('Not Assigned','wp-crm-system') . '</option>';
											foreach($contacts as $contact) {
												if (get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) == $contact->ID) { $selected = 'selected'; } else { $selected = ''; }
												echo '<option value="' . $contact->ID . '"' . $selected . '>' . get_the_title($contact->ID) . '</option>';
											}
											echo '</select>';
										} else {
											echo '<a href="' . admin_url('edit.php?post_type=wpcrm-contact') . '">';
											_e('Please create a contact first.','wp-crm-system');
											echo '</a>';
										}
									} else {										
										// Select progress
										if ( $customField[ 'type' ] == "selectprogress" ) {
											$args = array('zero'=>0,5=>5,10=>10,15=>15,20=>20,25=>25,30=>30,35=>35,40=>40,45=>45,50=>50,55=>55,60=>60,65=>65,70=>70,75=>75,80=>80,85=>85,90=>90,95=>95,100=>100); 
										}
										//Select Won/Lost
										if ( $customField[ 'type' ] == "selectwonlost" ) {
											$args = array('not-set'=>__('Select an option', 'wp-crm-system'),'won'=>_x('Won','Successful, a winner.','wp-crm-system'),'lost'=>_x('Lost','Unsuccessful, a loser.','wp-crm-system'),'suspended'=>_x('Suspended','Temporarily ended, but may resume again.','wp-crm-system'),'abandoned'=>_x('Abandoned','No longer actively working on.','wp-crm-system'));
										}
										if ( $customField[ 'type' ] == "selectpriority" ) { 
											$args = array(''=>__('Select an option', 'wp-crm-system'),'low'=>_x('Low','Not of great importance','wp-crm-system'),'medium'=>_x('Medium','Average priority','wp-crm-system'),'high'=>_x('High','Greatest importance','wp-crm-system'));
										}
										//Select status
										if ( $customField[ 'type' ] == "selectstatus" ) {
											$args = array('not-started'=>_x('Not Started','Work has not yet begun.','wp-crm-system'),'in-progress'=>_x('In Progress','Work has begun but is not complete.','wp-crm-system'),'complete'=>_x('Complete','All tasks are finished. No further work is needed.','wp-crm-system'),'on-hold'=>_x('On Hold','Work may be in various stages of completion, but has been stopped for one reason or another.','wp-crm-system'));
										}
										//Select prefix
										if ( $customField[ 'type' ] == "selectnameprefix" ) {
											$args = array(''=>__('Select an Option','wp-crm-system'),'mr'=>_x('Mr.','Title for male without a higher professional title.','wp-crm-system'),'mrs'=>_x('Mrs.','Married woman or woman who has been married with no higher professional title.','wp-crm-system'),'miss'=>_x('Miss','An unmarried woman. Also Ms.','wp-crm-system'),'dr'=>_x('Dr.','Doctor','wp-crm-system'),'master'=>_x('Master','Title used for young men.','wp-crm-system'),'rev'=>_x('Rev.','Title of a priest or religous clergy - Reverend ','wp-crm-system'),'fr'=>_x('Fr.','Title of a priest or religous clergy - Father','wp-crm-system'),'atty'=>_x('Atty.','Attorney, or lawyer','wp-crm-system'),'prof'=>_x('Prof.','Professor, as in a teacher at a university.','wp-crm-system'),'hon'=>_x('Hon.','Honorable - often used for elected officials or judges.','wp-crm-system'),'pres'=>_x('Pres.','Term given to the head of an organization or country. As in President of a University or President of the United States','wp-crm-system'),'gov'=>_x('Gov.','Governor, as in the Governor of the State of New York.','wp-crm-system'),'ofc'=>_x('Ofc.','Officer as in a police officer.','wp-crm-system'),'supt'=>_x('Supt.','Superintendent','wp-crm-system'),'rep'=>_x('Rep.','Representative - as in an elected official to the House of Representatives','wp-crm-system'),'sen'=>_x('Sen.','An elected official - Senator.','wp-crm-system'),'amb'=>_x('Amb.','Ambassador - a diplomatic official.','wp-crm-system'));
										}
										//Select Email 
										if ( $customField[ 'type' ] == "selectemail" ) {
											$args = array('work'=>_x('Work','An email associated with an employer','wp-crm-system'),'home'=>_x('Home','An email associated with a family, or personal','wp-crm-system'),'personal'=>_x('Personal','A personal email address, not associated with an employer','wp-crm-system'),'other'=>_x('Other','Not fitting any of the above','wp-crm-system'));
										} ?>
										<select name="<?php echo $this->prefix . $customField[ 'name' ]; ?>">
										<?php foreach ($args as $key => $value) { ?>
											<option value="<?php echo $key; ?>" <?php if (htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) == $key) { echo 'selected'; } ?> ><?php echo $value; if ( $customField[ 'type' ] == "selectprogress" ) { echo '%'; }?></option>
										<?php } ?>
										</select>
										<?php
									}
									break;
								}
								case 'currency': {
									if ( $customField[ 'type' ] == "currency" ) { 
									echo '<label for="' . $this->prefix . $customField[ 'name' ] .'" style="display:inline;"><strong>' . $customField[ 'title' ] . '</strong></label>&nbsp;&nbsp;';?>
										<input style="width:25%;" type="text" name="<?php echo $this->prefix . $customField[ 'name' ]; ?>" id="<?php echo $this->prefix . $customField[ 'name' ]; ?>" value="<?php echo htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ); ?>" placeholder="<?php echo $customField['placeholder']; ?>" />
										<?php echo strtoupper(get_option('wpcrm_system_default_currency')); ?>
										<br />
										<em><?php _e('Only numbers allowed. No thousands separator (commas, spaces, or periods), currency symbols, etc. allowed.', 'wp-crm-system');?></em><?php
									}
									break;
								}
                                case 'textarea':
                                case 'wysiwyg': {
                                    
										echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><strong>' . $customField[ 'title' ] . '</strong></label>';
									if ($customField[ 'type' ] == 'textarea') {
										echo '<textarea name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" columns="30" rows="3">' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '</textarea>';
									}
                                    // WYSIWYG
                                    if ( $customField[ 'type' ] == "wysiwyg" ) { 
										$post = get_post( get_the_ID(), OBJECT, 'edit' );
										$content = get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true );
										$editor_id = $this->prefix . $customField[ 'name' ];
										wp_editor($content, $editor_id);
                                    }
                                    break;
                                }
								case 'checkbox': {
                                    // Checkbox
                                    echo '<label for="' . $this->prefix . $customField[ 'name' ] .'" style="display:inline;"><strong>' . $customField[ 'title' ] . '</strong></label>&nbsp;&nbsp;';
                                    echo '<input type="checkbox" name="' . $this->prefix . $customField['name'] . '" id="' . $this->prefix . $customField['name'] . '" value="yes"';
                                    if ( get_post_meta( $post->ID, $this->prefix . $customField['name'], true ) == "yes" )
                                        echo ' checked="checked"';
                                    echo '" style="width: auto;" />';
                                    break;
                                }
								case 'datepicker': {
									if (!null == (get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ]))) { 
										$date = date(get_option('wpcrm_system_php_date_format'),htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) ); 
									} else { 
										$date = '';
									}
									//Datepicker
									echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><strong>' . $customField[ 'title' ] . '</strong></label>';
                                    echo '<input type="text" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" class="datepicker" value="' . $date . '" placeholder="' . $customField['placeholder'] . '" />'; ?>
									<script type="text/javascript">
									<?php $dateformat = get_option('wpcrm_system_date_format'); echo "var formatOption = '{$dateformat}';"; ?>
										jQuery(document).ready(function() {
											jQuery('.datepicker').datepicker({
												dateFormat : formatOption //allow date format change in settings
											});
										});
									</script>
									<?php
                                    break;
								}
								case 'gmap': {
									function geocode($address){

										// url encode the address
										$address = urlencode($address);
										 
										// google map geocode api url
										$url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
									 
										// get the json response
										$resp_json = file_get_contents($url);
										 
										// decode the json
										$resp = json_decode($resp_json, true);
									 
										// response status will be 'OK', if able to geocode given address 
										if($resp['status']=='OK'){
									 
											// get the important data
											$lati = $resp['results'][0]['geometry']['location']['lat'];
											$longi = $resp['results'][0]['geometry']['location']['lng'];
											$formatted_address = $resp['results'][0]['formatted_address'];
											 
											// verify if data is complete
											if($lati && $longi && $formatted_address){
											 
												// put the data in the array
												$data_arr = array();            
												 
												array_push(
													$data_arr, 
														$lati, 
														$longi, 
														$formatted_address
													);
												 
												return $data_arr;
												 
											}else{
												return false;
											}
											 
										}else{
											return false;
										}
									}
									if ($customField['name'] == 'contact-gmap'){
										$addressString = get_post_meta( $post->ID, $this->prefix . 'contact-address1', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-address2', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-city', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-state', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-postal', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-country', true );
									}
									if ($customField['name'] == 'organization-gmap'){
										$addressString = get_post_meta( $post->ID, $this->prefix . 'organization-address1', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'organization-address2', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'organization-city', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'organization-state', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'organization-postal', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'organization-country', true );
									}
									// get latitude, longitude and formatted address
									$data_arr = geocode($addressString);
								 
									// if able to geocode the address
									if($data_arr){
										 
										$latitude = $data_arr[0];
										$longitude = $data_arr[1];
										$formatted_address = $data_arr[2];
													 
									?>
								 
									<!-- google map will be shown here -->
									<div id="gmap_canvas"><?php _e('Loading map...','wp-crm-system'); ?></div>
									<div id='map-label'><?php _e('Map shows approximate location.','wp-crm-system'); ?></div>
									<!-- JavaScript to show google map included here to retrieve local variables -->
									<script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>
									<script type="text/javascript">
										function init_map() {
											var myOptions = {
												zoom: 14,
												center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
												mapTypeId: google.maps.MapTypeId.ROADMAP
											};
											map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
											marker = new google.maps.Marker({
												map: map,
												position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
											});
											infowindow = new google.maps.InfoWindow({
												content: "<?php echo $formatted_address; ?>"
											});
											google.maps.event.addListener(marker, "click", function () {
												infowindow.open(map, marker);
											});
											infowindow.open(map, marker);
										}
										google.maps.event.addDomListener(window, 'load', init_map);
									</script>
									<?php
									}else{
										_e('No Map Found. Please enter an address or verify the address details are correct.','wp-crm-system');
									}
									break;
								}
                                default: {
                                    // Plain text field
                                    echo '<label for="' . $this->prefix . $customField[ 'name' ] .'"><strong>' . $customField[ 'title' ] . '</strong></label>';
                                    echo '<input type="text" name="' . $this->prefix . $customField[ 'name' ] . '" id="' . $this->prefix . $customField[ 'name' ] . '" value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField[ 'name' ], true ) ) . '" placeholder="' . $customField['placeholder'] . '" />';
                                    break;
                                }
                            }
                            ?>
                            <?php if ( $customField[ 'description' ] ) echo '<p>' . $customField[ 'description' ] . '</p>'; ?>
                        </div>
                    <?php
                    }
                } ?>
            </div>
            <?php
        }
        /**
        * Save the new Custom Fields values
        */
        function saveCustomFields( $post_id, $post ) {
            if ( !isset( $_POST[ 'my-custom-fields_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'my-custom-fields_wpnonce' ], 'my-custom-fields' ) )
                return;
            if ( !current_user_can( 'edit_post', $post_id ) )
                return;
            if ( ! in_array( $post->post_type, $this->postTypes ) )
                return;
            foreach ( $this->customFields as $customField ) {
                if ( current_user_can( $customField['capability'], $post_id ) ) {
                    if ( isset( $_POST[ $this->prefix . $customField['name'] ] ) && trim( $_POST[ $this->prefix . $customField['name'] ] ) ) {
                        $value = $_POST[ $this->prefix . $customField['name'] ];
						if ( $customField['type'] == 'gmap' ) $value = '';
						if ( $customField['type'] == 'datepicker' ) $value = strtotime($value);
                        // Save currency only with numbers. No decimals, commas, currency symbols or other characters.
						if ( $customField['type'] == 'currency' ) $value = preg_replace("/[^0-9]/", "", $value);
                        // Auto-paragraphs for any WYSIWYG
						if ( $customField['type'] == 'wysiwyg' ) $value = wpautop( $value );
                        update_post_meta( $post_id, $this->prefix . $customField[ 'name' ], $value );
                    } else {
                        delete_post_meta( $post_id, $this->prefix . $customField[ 'name' ] );
                    }
                }
            }
        }
 
    } // End Class
 
} // End if class exists statement
 
// Instantiate the class
if ( class_exists('wpCRMSystemCustomFields') ) {
    $wpCRMSystemCustomFields_var = new wpCRMSystemCustomFields();
}

//Include Datepicker and Google Map scripts
function add_datepicker_script() {
	wp_enqueue_script('datepicker');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_register_style('jquery-ui-datepicker', plugins_url('/css/jquery-ui.min.css', __FILE__));
	wp_enqueue_style('jquery-ui-datepicker');
	wp_register_style('gmap-style', plugins_url('/css/gmap.css', __FILE__));
	wp_enqueue_style('gmap-style');
	wp_register_style('wpcrm-style', plugins_url('/css/wp-crm.css', __FILE__));
	wp_enqueue_style('wpcrm-style');
}
add_action( 'admin_enqueue_scripts', 'add_datepicker_script' );