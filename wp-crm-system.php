<?php
   /*
   Plugin Name: WP-CRM System
   Plugin URI: https://www.wp-crm.com
   Description: A complete CRM for WordPress
   Version: 1.1.8
   Author: Scott DeLuzio
   Author URI: https://www.wp-crm.com
   Text Domain: wp-crm-system
   */
   
	/*  Copyright 2016  Scott DeLuzio (email : support (at) wp-crm.com)	*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');

/* Load Text Domain */
add_action('plugins_loaded', 'wp_crm_plugin_init');
function wp_crm_plugin_init() {
	load_plugin_textdomain( 'wp-crm-system', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}
/* Add a metabox on dashboard for WP-CRM System Notices */
add_action('wp_dashboard_setup', 'wp_crm_dashboard_widget');
function wp_crm_dashboard_widget() {
	if(current_user_can(WPCRM_USER_ACCESS)) {
		global $wp_meta_boxes;
		wp_add_dashboard_widget('wp_crm_notifications', 'WP-CRM System', 'wp_crm_dashboard_notifications');
	}
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
    add_menu_page(__('WP CRM System', 'wp-crm-system'), __('WP CRM System', 'wp-crm-system'),WPCRM_USER_ACCESS,'wpcrm','wpcrm_settings_page', 'dashicons-id');
	add_submenu_page( 'wpcrm', __('Email', 'wp-crm-system'), __('Email', 'wp-crm-system'), WPCRM_USER_ACCESS, 'wpcrm-email', 'wpcrm_email_page' );
	add_submenu_page( 'wpcrm', __('Reports', 'wp-crm-system'), __('Reports', 'wp-crm-system'), WPCRM_USER_ACCESS, 'wpcrm-reports', 'wpcrm_reports_page' );
	add_submenu_page( 'wpcrm', __('Settings', 'wp-crm-system'), __('Settings', 'wp-crm-system'), 'manage_options', 'wpcrm-settings', 'wpcrm_settings_page' );
	add_submenu_page( 'wpcrm', __('Extensions', 'wp-crm-system'), __('Extensions', 'wp-crm-system'), WPCRM_USER_ACCESS, 'wpcrm-extensions', 'wpcrm_extensions_page' );
}
//Include scripts and styles
function wpcrm_scripts_styles() {
	$active_tab = isset( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : '';
	wp_enqueue_script('datepicker');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_register_style('jquery-ui-datepicker', plugins_url('/css/jquery-ui.min.css', __FILE__));
	wp_enqueue_style('jquery-ui-datepicker');
	wp_register_style('gmap-style', plugins_url('/css/gmap.css', __FILE__));
	wp_enqueue_style('gmap-style');
	wp_register_style('wpcrm-style', plugins_url('/css/wp-crm.css', __FILE__));
	wp_enqueue_style('wpcrm-style');
	if ( $active_tab == 'wpcrm-email' ) {
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('wpcrm-system-accordion',	plugins_url( '/js/accordion.js',__FILE__) );
	}
	wp_enqueue_script( 'jquery' );
}
add_action( 'admin_enqueue_scripts', 'wpcrm_scripts_styles' );
//Display the page content for the plugin settings and reports
function wpcrm_email_page() {
	include('wp-crm-system-email.php');
}
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
	add_option('wpcrm_system_select_user_role', 'manage_options');
	add_option('wpcrm_system_default_currency', 'USD');
	add_option('wpcrm_system_report_currency_decimals', 0);
	add_option('wpcrm_system_report_currency_decimal_point', '.');
	add_option('wpcrm_system_report_currency_thousand_separator', ',');
	add_option('wpcrm_system_date_format', 'MM dd, yy');
	add_option('wpcrm_system_php_date_format', 'F d, Y');
	add_option('wpcrm_system_email_organization_filter', '');
	$plugin = 'wp-crm-system-dropbox';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		add_option('wpcrm_dropbox_app_key', '');
	}
	
	$terms = get_terms('contact-type');
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		foreach ( $terms as $term ) {
			add_option($term->slug.'-email-filter','');
		}
	}
}
function deactivate_wpcrm_system_settings() {
	delete_option('wpcrm_system_select_user_role');
	delete_option('wpcrm_system_default_currency');
	delete_option('wpcrm_system_report_currency_decimals');
	delete_option('wpcrm_system_report_currency_decimal_point');
	delete_option('wpcrm_system_report_currency_thousand_separator');
	delete_option('wpcrm_system_date_format');
	delete_option('wpcrm_system_php_date_format');
	delete_option('wpcrm_system_email_organization_filter');
	$plugin = 'wp-crm-system-dropbox';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		delete_option('wpcrm_dropbox_app_key');
	}
	
	$terms = get_terms('contact-type');
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		foreach ( $terms as $term ) {
			delete_option($term->slug.'-email-filter');
		}
	}
}
function register_wpcrm_system_settings() {
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_select_user_role');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_default_currency');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_report_currency_decimals');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_report_currency_decimal_point');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_report_currency_thousand_separator');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_date_format');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_php_date_format');
	register_setting( 'wpcrm_system_email_group','wpcrm_system_email_organization_filter' );
	$plugin = 'wp-crm-system-dropbox';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_dropbox_app_key');
	}
	
	$terms = get_terms('contact-type');
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		foreach ( $terms as $term ) {
			register_setting('wpcrm_system_email_group',$term->slug.'-email-filter');
		}
	}
}
/* Correct the date format if correct format is not being used */
add_action('admin_init', 'update_date_formats');
function update_date_formats() {
	$js_date_format = get_option('wpcrm_system_date_format');
	$php_date_format = get_option('wpcrm_system_php_date_format');
	if (!$js_date_format == false || !$php_date_format == false) {
		$js_acceptable = array('yy-M-d','M d, y','MM dd, yy','dd.mm.y','dd/mm/y','d MM yy','D d MM yy','DD, MM d, yy');
		$php_acceptable = array('Y-M-j','M j, y','F d, Y','d.m.y','d/m/y','j F Y','D j F Y','l, F j, Y');
		if(!in_array($js_date_format,$js_acceptable) || !in_array($php_date_format,$php_acceptable)) {
			update_option('wpcrm_system_date_format','MM dd, yy');
			update_option('wpcrm_system_php_date_format','F d, Y');
		}
	}
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
add_action( 'init', 'wpcrm_campaign_init' );
add_action( 'init', 'wpcrm_campaign_taxonomy');

/**
 * Adjust capabilities as necessary
 */
add_action('admin_init','wpcrm_add_role_caps',999);
function wpcrm_add_role_caps() {
	$post_types = array('wpcrm-contact','wpcrm-task','wpcrm-organization','wpcrm-opportunity','wpcrm-project','wpcrm-campaign');
    
	foreach($post_types as $post_type) {
		// Add the roles you'd like to administer contacts
		$roles = array('subscriber','contributor','author','editor','administrator');

		// Loop through each role and assign capabilities
		foreach($roles as $the_role) { 
			$role = get_role($the_role);
		// Need to check if the role has get_option('wpcrm_system_select_user_role'); capability then add_cap if it does.
			if($role->has_cap(get_option('wpcrm_system_select_user_role'))) {
				$role->add_cap( 'edit_'.$post_type);
				$role->add_cap( 'read_'.$post_type);
				$role->add_cap( 'delete_'.$post_type);
				$role->add_cap( 'edit_'.$post_type.'s');
				$role->add_cap( 'edit_others_'.$post_type.'s');
				$role->add_cap( 'publish_'.$post_type.'s');
				$role->add_cap( 'read_private_'.$post_type.'s');
				$role->add_cap( 'read_'.$post_type);
				$role->add_cap( 'delete_'.$post_type.'s');
				$role->add_cap( 'delete_private_'.$post_type.'s');
				$role->add_cap( 'delete_published_'.$post_type.'s');
				$role->add_cap( 'delete_others_'.$post_type.'s');
				$role->add_cap( 'edit_private_'.$post_type.'s');
				$role->add_cap( 'edit_published_'.$post_type.'s');
				$role->add_cap( 'create_'.$post_type.'s');
				$role->add_cap( 'manage_wp_crm');
			} else {
			// Remove the capabilities if the role isn't supposed to edit the CPT. Allows for admin to change to a higher role if too much access was previously given.
				$role->remove_cap( 'edit_'.$post_type);
				$role->remove_cap( 'read_'.$post_type);
				$role->remove_cap( 'delete_'.$post_type);
				$role->remove_cap( 'edit_'.$post_type.'s');
				$role->remove_cap( 'edit_others_'.$post_type.'s');
				$role->remove_cap( 'publish_'.$post_type.'s');
				$role->remove_cap( 'read_private_'.$post_type.'s');
				$role->remove_cap( 'read_'.$post_type);
				$role->remove_cap( 'delete_'.$post_type.'s');
				$role->remove_cap( 'delete_private_'.$post_type.'s');
				$role->remove_cap( 'delete_published_'.$post_type.'s');
				$role->remove_cap( 'delete_others_'.$post_type.'s');
				$role->remove_cap( 'edit_private_'.$post_type.'s');
				$role->remove_cap( 'edit_published_'.$post_type.'s');
				$role->remove_cap( 'create_'.$post_type.'s');
				$role->remove_cap( 'manage_wp_crm');
			}
		}
	}
}

/* Contacts post type. */
function wpcrm_contacts_init() {
	$post_type = 'wpcrm-contact';
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
			'rewrite'            => array( 'slug' => $post_type ),
			'capabilities' => array(
				'edit_post' => 'edit_'.$post_type,
				'read_post' => 'read_'.$post_type,
				'delete_post' => 'delete_'.$post_type,
				'edit_posts' => 'edit_'.$post_type.'s',
				'edit_others_posts' => 'edit_others_'.$post_type.'s',
				'publish_posts' => 'publish_'.$post_type.'s',
				'read_private_posts' => 'read_private_'.$post_type.'s',
				'read' => 'read_'.$post_type,
				'delete_posts' => 'delete_'.$post_type.'s',
				'delete_private_posts' => 'delete_private_'.$post_type.'s',
				'delete_published_posts' => 'delete_published_'.$post_type.'s',
				'delete_others_posts' => 'delete_others_'.$post_type.'s',
				'edit_private_posts' => 'edit_private_'.$post_type.'s',
				'edit_published_posts' => 'edit_published_'.$post_type.'s',
				'create_posts' => 'create_'.$post_type.'s',
			),
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'taxonomies'		 => array('contact-type'),
			'supports'           => array( 'title', 'thumbnail', 'custom-fields' )
	);

	register_post_type( $post_type, $args );
}
function wpcrm_contact_taxonomy() {
	$labels = array(
		'name'              => __( 'Contact Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Contact Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Contact Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Contact Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Contact Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Contact Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Contact Types', 'wp-crm-system' ),
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
	$post_type = 'wpcrm-task';
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
		'rewrite'            => array( 'slug' => $post_type ),
		'capabilities' => array(
			'edit_post' => 'edit_'.$post_type,
			'read_post' => 'read_'.$post_type,
			'delete_post' => 'delete_'.$post_type,
			'edit_posts' => 'edit_'.$post_type.'s',
			'edit_others_posts' => 'edit_others_'.$post_type.'s',
			'publish_posts' => 'publish_'.$post_type.'s',
			'read_private_posts' => 'read_private_'.$post_type.'s',
			'read' => 'read_'.$post_type,
			'delete_posts' => 'delete_'.$post_type.'s',
			'delete_private_posts' => 'delete_private_'.$post_type.'s',
			'delete_published_posts' => 'delete_published_'.$post_type.'s',
			'delete_others_posts' => 'delete_others_'.$post_type.'s',
			'edit_private_posts' => 'edit_private_'.$post_type.'s',
			'edit_published_posts' => 'edit_published_'.$post_type.'s',
			'create_posts' => 'create_'.$post_type.'s',
		),
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('task-type'),
		'supports'           => array( 'title', 'thumbnail', 'custom-fields' )
	);

	register_post_type( $post_type, $args );
}
function wpcrm_task_taxonomy() {
	$labels = array(
		'name'              => __( 'Task Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Task Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Task Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Task Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Task Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Task Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Task Types', 'wp-crm-system' ),
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
	register_taxonomy( 'task-type', 'wpcrm-task', $args );
}
/* Organizations post type. */
function wpcrm_organizations_init() {
	$post_type = 'wpcrm-organization';
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
		'rewrite'            => array( 'slug' => $post_type ),
		'capabilities' => array(
			'edit_post' => 'edit_'.$post_type,
			'read_post' => 'read_'.$post_type,
			'delete_post' => 'delete_'.$post_type,
			'edit_posts' => 'edit_'.$post_type.'s',
			'edit_others_posts' => 'edit_others_'.$post_type.'s',
			'publish_posts' => 'publish_'.$post_type.'s',
			'read_private_posts' => 'read_private_'.$post_type.'s',
			'read' => 'read_'.$post_type,
			'delete_posts' => 'delete_'.$post_type.'s',
			'delete_private_posts' => 'delete_private_'.$post_type.'s',
			'delete_published_posts' => 'delete_published_'.$post_type.'s',
			'delete_others_posts' => 'delete_others_'.$post_type.'s',
			'edit_private_posts' => 'edit_private_'.$post_type.'s',
			'edit_published_posts' => 'edit_published_'.$post_type.'s',
			'create_posts' => 'create_'.$post_type.'s',
		),
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('organization-type'),
		'supports'           => array( 'title', 'thumbnail', 'custom-fields' )
	);

	register_post_type( $post_type, $args );
}
function wpcrm_organization_taxonomy() {
	$labels = array(
		'name'              => __( 'Organization Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Organization Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Organization Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Organization Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Organization Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Organization Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Organization Types', 'wp-crm-system' ),
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
	$post_type = 'wpcrm-opportunity';
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
		'rewrite'            => array( 'slug' => $post_type ),
		'capabilities' => array(
			'edit_post' => 'edit_'.$post_type,
			'read_post' => 'read_'.$post_type,
			'delete_post' => 'delete_'.$post_type,
			'edit_posts' => 'edit_'.$post_type.'s',
			'edit_others_posts' => 'edit_others_'.$post_type.'s',
			'publish_posts' => 'publish_'.$post_type.'s',
			'read_private_posts' => 'read_private_'.$post_type.'s',
			'read' => 'read_'.$post_type,
			'delete_posts' => 'delete_'.$post_type.'s',
			'delete_private_posts' => 'delete_private_'.$post_type.'s',
			'delete_published_posts' => 'delete_published_'.$post_type.'s',
			'delete_others_posts' => 'delete_others_'.$post_type.'s',
			'edit_private_posts' => 'edit_private_'.$post_type.'s',
			'edit_published_posts' => 'edit_published_'.$post_type.'s',
			'create_posts' => 'create_'.$post_type.'s',
		),
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('opportunity-type'),
		'supports'           => array( 'title', 'thumbnail', 'custom-fields' )
	);

	register_post_type( $post_type, $args );
}
function wpcrm_opportunity_taxonomy() {
	$labels = array(
		'name'              => __( 'Opportunity Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Opportunity Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Opportunity Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Opportunity Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Opportunity Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Opportunity Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Opportunity Types', 'wp-crm-system' ),
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
	$post_type = 'wpcrm-project';
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
		'rewrite'            => array( 'slug' => $post_type ),
		'capabilities' => array(
			'edit_post' => 'edit_'.$post_type,
			'read_post' => 'read_'.$post_type,
			'delete_post' => 'delete_'.$post_type,
			'edit_posts' => 'edit_'.$post_type.'s',
			'edit_others_posts' => 'edit_others_'.$post_type.'s',
			'publish_posts' => 'publish_'.$post_type.'s',
			'read_private_posts' => 'read_private_'.$post_type.'s',
			'read' => 'read_'.$post_type,
			'delete_posts' => 'delete_'.$post_type.'s',
			'delete_private_posts' => 'delete_private_'.$post_type.'s',
			'delete_published_posts' => 'delete_published_'.$post_type.'s',
			'delete_others_posts' => 'delete_others_'.$post_type.'s',
			'edit_private_posts' => 'edit_private_'.$post_type.'s',
			'edit_published_posts' => 'edit_published_'.$post_type.'s',
			'create_posts' => 'create_'.$post_type.'s',
		),
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('project-type'),
		'supports'           => array( 'title', 'thumbnail', 'custom-fields' )
	);

	register_post_type( $post_type, $args );
}
function wpcrm_project_taxonomy() {
	$labels = array(
		'name'              => __( 'Project Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Project Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Project Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Project Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Project Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Project Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Project Types', 'wp-crm-system' ),
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
/* Campaign post type. */
function wpcrm_campaign_init() {
	$post_type = 'wpcrm-campaign';
	$labels = array(
		'name'               => __( 'Campaigns', 'wp-crm-system' ),
		'singular_name'      => __( 'Campaign', 'wp-crm-system' ),
		'menu_name'          => __( 'Campaigns', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Campaign', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Campaign', 'wp-crm-system' ),
		'new_item'           => __( 'New Campaign', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Campaign', 'wp-crm-system' ),
		'view_item'          => __( 'View Campaign', 'wp-crm-system' ),
		'all_items'          => __( 'Campaigns', 'wp-crm-system' ),
		'search_items'       => __( 'Search Campaigns', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Campaign:', 'wp-crm-system' ),
		'not_found'          => __( 'No campaigns found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No campaigns found in Trash.', 'wp-crm-system' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Campaigns', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $post_type ),
		'capabilities' => array(
			'edit_post' => 'edit_'.$post_type,
			'read_post' => 'read_'.$post_type,
			'delete_post' => 'delete_'.$post_type,
			'edit_posts' => 'edit_'.$post_type.'s',
			'edit_others_posts' => 'edit_others_'.$post_type.'s',
			'publish_posts' => 'publish_'.$post_type.'s',
			'read_private_posts' => 'read_private_'.$post_type.'s',
			'read' => 'read_'.$post_type,
			'delete_posts' => 'delete_'.$post_type.'s',
			'delete_private_posts' => 'delete_private_'.$post_type.'s',
			'delete_published_posts' => 'delete_published_'.$post_type.'s',
			'delete_others_posts' => 'delete_others_'.$post_type.'s',
			'edit_private_posts' => 'edit_private_'.$post_type.'s',
			'edit_published_posts' => 'edit_published_'.$post_type.'s',
			'create_posts' => 'create_'.$post_type.'s',
		),
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('campaign-type'),
		'supports'           => array( 'title', 'thumbnail', 'custom-fields' )
	);

	register_post_type( $post_type, $args );
}
function wpcrm_campaign_taxonomy() {
	$labels = array(
		'name'              => __( 'Campaign Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Campaign Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Campaign Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Campaign Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Campaign Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Campaign Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Campaign Types', 'wp-crm-system' ),
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
	register_taxonomy( 'campaign-type', 'wpcrm-campaign', $args );
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
        var $postTypes = array( 'wpcrm-contact', 'wpcrm-task', 'wpcrm-organization', 'wpcrm-opportunity', 'wpcrm-project', 'wpcrm-campaign' );
        /**
        * @var  array  $defaultFields  Defines the custom fields available
        */
        var $defaultFields = array(
			// Contact Fields
            array(
                'name'          => 'contact-name-prefix',
                'title'         => WPCRM_NAME_PREFIX,
                'description'   => '',
                'type'          => 'selectnameprefix',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-first-name',
                'title'         => WPCRM_FIRST_NAME,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
            array(
                'name'          => 'contact-last-name',
                'title'         => WPCRM_LAST_NAME,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
				'name'          => 'contact-attach-to-organization',
                'title'         => WPCRM_ORGANIZATION,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectorganization',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-role',
                'title'         => WPCRM_ROLE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-email',
                'title'         => WPCRM_EMAIL,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'email',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-phone',
                'title'         => WPCRM_PHONE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-mobile-phone',
                'title'         => WPCRM_MOBILE_PHONE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-fax',
                'title'         => WPCRM_FAX,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-website',
                'title'         => WPCRM_WEBSITE,
                'description'   => '',
				'placeholder'	=> 'http://',
                'type'          => 'url',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-address1',
                'title'         => WPCRM_ADDRESS_1,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-address2',
                'title'         => WPCRM_ADDRESS_2,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-city',
                'title'         => WPCRM_CITY,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-state',
                'title'         => WPCRM_STATE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-postal',
                'title'         => WPCRM_POSTAL,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-country',
                'title'         => WPCRM_COUNTRY,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-gmap',
                'title'         => WPCRM_LOCATION,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'gmap',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-additional',
                'title'         => WPCRM_ADDITIONAL,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'wysiwyg',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-dropbox',
                'title'         => 'Link Files From Dropbox',
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'dropbox',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'contact-zendesk',
                'title'         => 'Zendesk Tickets',
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'zendesk',
                'scope'         => array( 'wpcrm-contact' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			//Task Fields
			array(
                'name'          => 'task-assignment',
                'title'         => WPCRM_ASSIGNED,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectuser',
                'scope'         => array( 'wpcrm-task' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
				'name'          => 'task-attach-to-organization',
                'title'         => WPCRM_ATTACH_ORG,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectorganization',
                'scope'         => array( 'wpcrm-task' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
				'name'          => 'task-attach-to-contact',
                'title'         => WPCRM_ATTACH_CONTACT,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectcontact',
                'scope'         => array( 'wpcrm-task' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
				'name'          => 'task-attach-to-project',
                'title'         => WPCRM_ATTACH_PROJECT,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectproject',
                'scope'         => array( 'wpcrm-task' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'task-due-date',
                'title'         => WPCRM_DUE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'datepicker',
                'scope'         => array( 'wpcrm-task' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'task-start-date',
                'title'         => WPCRM_START,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'datepicker',
                'scope'         => array( 'wpcrm-task' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'task-progress',
                'title'         => WPCRM_PROGRESS,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectprogress',
                'scope'         => array( 'wpcrm-task' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'task-priority',
                'title'         => WPCRM_PRIORITY,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectpriority',
                'scope'         => array( 'wpcrm-task' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'task-status',
                'title'         => WPCRM_STATUS,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectstatus',
                'scope'         => array( 'wpcrm-task' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'task-description',
                'title'         => WPCRM_DESCRIPTION,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'wysiwyg',
                'scope'         => array( 'wpcrm-task' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'task-dropbox',
                'title'         => 'Link Files From Dropbox',
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'dropbox',
                'scope'         => array( 'wpcrm-task' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			//Organization Fields
			array(
                'name'          => 'organization-phone',
                'title'         => WPCRM_PHONE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'organization-email',
                'title'         => WPCRM_EMAIL,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'email',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'organization-website',
                'title'         => WPCRM_WEBSITE,
                'description'   => '',
				'placeholder'   => 'http://',
                'type'          => 'url',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'organization-address1',
                'title'         => WPCRM_ADDRESS_1,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'organization-address2',
                'title'         => WPCRM_ADDRESS_2,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'organization-city',
                'title'         => WPCRM_CITY,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'organization-state',
                'title'         => WPCRM_STATE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'organization-postal',
                'title'         => WPCRM_POSTAL,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'organization-country',
                'title'         => WPCRM_COUNTRY,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'default',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'organization-gmap',
                'title'         => WPCRM_LOCATION,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'gmap',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'organization-information',
                'title'         => WPCRM_ADDITIONAL,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'wysiwyg',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'organization-dropbox',
                'title'         => 'Link Files From Dropbox',
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'dropbox',
                'scope'         => array( 'wpcrm-organization' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			//Opportunity Fields
			array(
                'name'          => 'opportunity-assigned',
                'title'         => WPCRM_ASSIGNED,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectuser',
                'scope'         => array( 'wpcrm-opportunity' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
				'name'          => 'opportunity-attach-to-organization',
                'title'         => WPCRM_ATTACH_ORG,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectorganization',
                'scope'         => array( 'wpcrm-opportunity' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
				'name'          => 'opportunity-attach-to-contact',
                'title'         => WPCRM_ATTACH_CONTACT,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectcontact',
                'scope'         => array( 'wpcrm-opportunity' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
				'name'          => 'opportunity-attach-to-campaign',
                'title'         => WPCRM_ATTACH_CAMPAIGN,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectcampaign',
                'scope'         => array( 'wpcrm-opportunity' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'opportunity-description',
                'title'         => WPCRM_DESCRIPTION,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'wysiwyg',
                'scope'         => array( 'wpcrm-opportunity' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'opportunity-probability',
                'title'         => WPCRM_PROBABILITY,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectprogress',
                'scope'         => array( 'wpcrm-opportunity' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'opportunity-closedate',
                'title'         => WPCRM_FORECASTED_CLOSE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'datepicker',
                'scope'         => array( 'wpcrm-opportunity' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'opportunity-value',
                'title'         => WPCRM_VALUE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'currency',
                'scope'         => array( 'wpcrm-opportunity' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'opportunity-wonlost',
                'title'         => WPCRM_WON_LOST,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectwonlost',
                'scope'         => array( 'wpcrm-opportunity' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'opportunity-dropbox',
                'title'         => 'Link Files From Dropbox',
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'dropbox',
                'scope'         => array( 'wpcrm-opportunity' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			//Project Fields
			array(
                'name'          => 'project-description',
                'title'         => WPCRM_DESCRIPTION,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'wysiwyg',
                'scope'         => array( 'wpcrm-project' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'project-closedate',
                'title'         => WPCRM_CLOSE_DATE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'datepicker',
                'scope'         => array( 'wpcrm-project' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'project-progress',
                'title'         => WPCRM_PROGRESS,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectprogress',
                'scope'         => array( 'wpcrm-project' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'project-status',
                'title'         => WPCRM_STATUS,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectstatus',
                'scope'         => array( 'wpcrm-project' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'project-value',
                'title'         => WPCRM_VALUE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'currency',
                'scope'         => array( 'wpcrm-project' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'project-assigned',
                'title'         => WPCRM_ASSIGNED,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectuser',
                'scope'         => array( 'wpcrm-project' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
				'name'          => 'project-attach-to-organization',
                'title'         => WPCRM_ATTACH_ORG,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectorganization',
                'scope'         => array( 'wpcrm-project' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
				'name'          => 'project-attach-to-contact',
                'title'         => WPCRM_ATTACH_CONTACT,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectcontact',
                'scope'         => array( 'wpcrm-project' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'project-dropbox',
                'title'         => 'Link Files From Dropbox',
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'dropbox',
                'scope'         => array( 'wpcrm-project' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			//Campaign fields
			array(
                'name'          => 'campaign-assigned',
                'title'         => WPCRM_ASSIGNED,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectuser',
                'scope'         => array( 'wpcrm-campaign' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'campaign-active',
                'title'         => WPCRM_ACTIVE,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'checkbox',
                'scope'         => array( 'wpcrm-campaign' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'campaign-status',
                'title'         => WPCRM_STATUS,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'selectstatus',
                'scope'         => array( 'wpcrm-campaign' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'campaign-startdate',
                'title'         => WPCRM_START,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'datepicker',
                'scope'         => array( 'wpcrm-campaign' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'campaign-enddate',
                'title'         => WPCRM_END,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'datepicker',
                'scope'         => array( 'wpcrm-campaign' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'campaign-projectedreach',
                'title'         => WPCRM_REACH,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'number',
                'scope'         => array( 'wpcrm-campaign' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'campaign-responses',
                'title'         => WPCRM_RESPONSES,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'number',
                'scope'         => array( 'wpcrm-campaign' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'campaign-budgetcost',
                'title'         => WPCRM_BUDGETED_COST,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'currency',
                'scope'         => array( 'wpcrm-campaign' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'campaign-actualcost',
                'title'         => WPCRM_ACTUAL_COST,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'currency',
                'scope'         => array( 'wpcrm-campaign' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'campaign-description',
                'title'         => WPCRM_ADDITIONAL,
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'wysiwyg',
                'scope'         => array( 'wpcrm-campaign' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
			array(
                'name'          => 'campaign-dropbox',
                'title'         => 'Link Files From Dropbox',
                'description'   => '',
				'placeholder'   => '',
                'type'          => 'dropbox',
                'scope'         => array( 'wpcrm-campaign' ),
                'capability'    => WPCRM_USER_ACCESS
            ),
        );
        /**
        * PHP 5 Constructor
        */
        function __construct() {
            add_action( 'admin_menu', array( &$this, 'createWPCRMSystemFields' ) );
            add_action( 'save_post', array( &$this, 'saveWPCRMSystemFields' ), 1, 2 );
            // Remove default custom field meta box
            add_action( 'do_meta_boxes', array( &$this, 'removeDefaultFields' ), 10, 3 );
        }
        /**
        * Remove the default Fields meta box
        */
        function removeDefaultFields( $type, $context, $post ) {
            foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
                foreach ( $this->postTypes as $postType ) {
                    remove_meta_box( 'postcustom', $postType, $context );
                }
            }
        }
        /**
        * Create the new meta boxes
        */
        function createWPCRMSystemFields() {
            if ( function_exists( 'add_meta_box' ) ) {
				foreach ( $this->postTypes as $postType ) {
                    add_meta_box( 'wpcrm-default-fields', 'Fields', array( &$this, 'wpcrmDefaultFields' ), $postType, 'normal', 'high' );
                }
            }
        }
		
		/**
        * Display the main fields meta box
        */
        function wpcrmListTasks() {
		include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
		global $post;
			//List Tasks
			$meta_key1 = $prefix . 'task-attach-to-project';
			$meta_key1_value = get_the_ID();
			$tasks = '';
			$task_report = '';
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-task');
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$args = array(
					'post_type'		=>	'wpcrm-task',
					'meta_query'	=>	array(
						array(
							'key'		=>	$meta_key1,
							'value'		=>	$meta_key1_value,
							'compare'	=>	'=',
						),
					),
				);
			endwhile;
			$posts = get_posts($args);					
			if ($posts) {
				foreach($posts as $post) {
					$tasks .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a>';
				}
			} else {
				$tasks = '';
			}
			if ($tasks == '') {
				$task_report .= '';
			} else {
				$task_report .= '<li>'.$tasks.'</li>';
			}
			if ($task_report != '') {
				echo '<ul>' . $task_report . '</ul>';
			} else {
				_e('No tasks assigned to this project','wp-crm-system');
			} 
			wp_reset_query();
		}
        /**
        * Display the main fields meta box
        */
        function wpcrmDefaultFields() {
            global $post;
            ?>
            <div class="form-wrap">
                <?php
                wp_nonce_field( 'wpcrm-fields', 'wpcrm-fields_wpnonce', false, true );
                foreach ( $this->defaultFields as $defaultField ) {
                    // Check scope
                    $scope = $defaultField[ 'scope' ];
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
                    if ( !current_user_can( $defaultField['capability'], $post->ID ) )
                        $output = false;
                    // Output if allowed
                    if ( $output ) { ?>
                        <div class="form-field form-required">
                            <?php
                            switch ( $defaultField[ 'type' ] ) {
								case 'selectcontact':
								case 'selectproject':
								case 'selectcampaign':
								case 'selectorganization':
								case 'selectprogress':
								case 'selectwonlost':
								case 'selectpriority':
								case 'selectstatus':
								case 'selectnameprefix':
								case 'selectuser': {
									// Select
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>&nbsp;&nbsp;';
									//Select User
									if ( $defaultField[ 'type' ] == "selectuser" ) {
										echo'<select name="' . $this->prefix . $defaultField[ 'name' ] . '">';
										if ( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) == '') { $selected = 'selected'; } else { $selected = ''; }
										echo '<option value="" ' . $selected . '>Not Assigned</option>';
											$users = get_users();
											$wp_crm_users = array();
											foreach( $users as $user ){
												if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
													$wp_crm_users[] = $user;
												}
											}
											foreach( $wp_crm_users as $user) {
												if (get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) == $user->data->user_login) { $selected = 'selected'; } else { $selected = ''; }
												echo '<option value="'.$user->data->user_login.'" ' . $selected . '>'.$user->data->display_name.'</option>';
											}
										echo'</select>';
									} elseif ( $defaultField[ 'type' ] == "selectcampaign" ) {
										//Select Campaign
										$campaigns = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-campaign'));
										if ($campaigns) {
											echo'<select name="' . $this->prefix . $defaultField[ 'name' ] . '">';
											if ( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) == '') { $selected = 'selected'; } else { $selected = ''; }
											echo '<option value="" ' . $selected . '>Not Assigned</option>';
											foreach($campaigns as $campaign) {
												if (get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) == $campaign->ID) { $selected = 'selected'; $linkcampaign = $campaign->ID; } else { $selected = ''; }
												echo '<option value="' . $campaign->ID . '"' . $selected . '>' . get_the_title($campaign->ID) . '</option>';
											}
											echo '</select>';
											if (isset($linkcampaign)) {
												echo '<br /><a href="' . get_edit_post_link($linkcampaign) . '">' . __('Go to ','wp-crm-system') . get_the_title($linkcampaign) . '</a>';
											}
										} else {
											echo '<a href="' . admin_url('edit.php?post_type=wpcrm-campaign') . '">';
											_e('Please create an campaign first.','wp-crm-system');
											echo '</a>';
										}
									} elseif ( $defaultField[ 'type' ] == "selectorganization" ) {
										//Select Organization
										$orgs = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-organization'));
										if ($orgs) {
											echo'<select name="' . $this->prefix . $defaultField[ 'name' ] . '">';
											if ( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) == '') { $selected = 'selected'; } else { $selected = ''; }
											echo '<option value="" ' . $selected . '>' . __('Not Assigned','wp-crm-system') . '</option>';
											foreach($orgs as $org) {
												if (get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) == $org->ID) { $selected = 'selected'; $linkorg = $org->ID;} else { $selected = ''; }
												echo '<option value="' . $org->ID . '"' . $selected . '>' . get_the_title($org->ID) . '</option>';
											}
											echo '</select>';
											if (isset($linkorg)) {
												echo '<br /><a href="' . get_edit_post_link($linkorg) . '">' . __('Go to ','wp-crm-system') . get_the_title($linkorg) . '</a>';
											}
										} else {
											echo '<a href="' . admin_url('edit.php?post_type=wpcrm-organization') . '">';
											_e('Please create an organization first.','wp-crm-system');
											echo '</a>';
										}
									} elseif ( $defaultField[ 'type' ] == "selectcontact" ) {
										//Select Contact
										$contacts = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-contact'));
										if ($contacts) {
											echo'<select name="' . $this->prefix . $defaultField[ 'name' ] . '">';
											if ( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) == '') { $selected = 'selected'; } else { $selected = ''; }
											echo '<option value="" ' . $selected . '>' . __('Not Assigned','wp-crm-system') . '</option>';
											foreach($contacts as $contact) {
												if (get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) == $contact->ID) { $selected = 'selected'; $linkcontact = $contact->ID; } else { $selected = ''; }
												echo '<option value="' . $contact->ID . '"' . $selected . '>' . get_the_title($contact->ID) . '</option>';
											}
											echo '</select>';
											if (isset($linkcontact)) {
												echo '<br /><a href="' . get_edit_post_link($linkcontact) . '">' . __('Go to ','wp-crm-system') . get_the_title($linkcontact) . '</a>';
											}
										} else {
											echo '<a href="' . admin_url('edit.php?post_type=wpcrm-contact') . '">';
											_e('Please create a contact first.','wp-crm-system');
											echo '</a>';
										}
									} elseif ( $defaultField[ 'type' ] == "selectproject" ) {
										//Select Project
										$projects = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-project'));
										if ($projects) {
											echo'<select name="' . $this->prefix . $defaultField[ 'name' ] . '">';
											if ( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) == '') { $selected = 'selected'; } else { $selected = ''; }
											echo '<option value="" ' . $selected . '>' . __('Not Assigned','wp-crm-system') . '</option>';
											foreach($projects as $project) {
												if (get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) == $project->ID) { $selected = 'selected'; $linkproject = $project->ID;} else { $selected = ''; }
												echo '<option value="' . $project->ID . '"' . $selected . '>' . get_the_title($project->ID) . '</option>';
											}
											echo '</select>';
											if (isset($linkproject)) {
												echo '<br /><a href="' . get_edit_post_link($linkproject) . '">' . __('Go to ','wp-crm-system') . get_the_title($linkproject) . '</a>';
											}
										} else {
											echo '<a href="' . admin_url('edit.php?post_type=wpcrm-contact') . '">';
											_e('Please create a contact first.','wp-crm-system');
											echo '</a>';
										}
									} else {										
										// Select progress
										if ( $defaultField[ 'type' ] == "selectprogress" ) {
											$args = array('zero'=>0,5=>5,10=>10,15=>15,20=>20,25=>25,30=>30,35=>35,40=>40,45=>45,50=>50,55=>55,60=>60,65=>65,70=>70,75=>75,80=>80,85=>85,90=>90,95=>95,100=>100); 
										}
										//Select Won/Lost
										if ( $defaultField[ 'type' ] == "selectwonlost" ) {
											$args = array('not-set'=>__('Select an option', 'wp-crm-system'),'won'=>_x('Won','Successful, a winner.','wp-crm-system'),'lost'=>_x('Lost','Unsuccessful, a loser.','wp-crm-system'),'suspended'=>_x('Suspended','Temporarily ended, but may resume again.','wp-crm-system'),'abandoned'=>_x('Abandoned','No longer actively working on.','wp-crm-system'));
										}
										if ( $defaultField[ 'type' ] == "selectpriority" ) { 
											$args = array(''=>__('Select an option', 'wp-crm-system'),'low'=>_x('Low','Not of great importance','wp-crm-system'),'medium'=>_x('Medium','Average priority','wp-crm-system'),'high'=>_x('High','Greatest importance','wp-crm-system'));
										}
										//Select status
										if ( $defaultField[ 'type' ] == "selectstatus" ) {
											$args = array('not-started'=>_x('Not Started','Work has not yet begun.','wp-crm-system'),'in-progress'=>_x('In Progress','Work has begun but is not complete.','wp-crm-system'),'complete'=>_x('Complete','All tasks are finished. No further work is needed.','wp-crm-system'),'on-hold'=>_x('On Hold','Work may be in various stages of completion, but has been stopped for one reason or another.','wp-crm-system'));
										}
										//Select prefix
										if ( $defaultField[ 'type' ] == "selectnameprefix" ) {
											$args = array(''=>__('Select an Option','wp-crm-system'),'mr'=>_x('Mr.','Title for male without a higher professional title.','wp-crm-system'),'mrs'=>_x('Mrs.','Married woman or woman who has been married with no higher professional title.','wp-crm-system'),'miss'=>_x('Miss','An unmarried woman. Also Ms.','wp-crm-system'),'ms'=>_x('Ms.','An unmarried woman. Also Miss.','wp-crm-system'),'dr'=>_x('Dr.','Doctor','wp-crm-system'),'master'=>_x('Master','Title used for young men.','wp-crm-system'),'coach'=>_x('Coach','Title used for the person in charge of a sports team','wp-crm-system'),'rev'=>_x('Rev.','Title of a priest or religous clergy - Reverend ','wp-crm-system'),'fr'=>_x('Fr.','Title of a priest or religous clergy - Father','wp-crm-system'),'atty'=>_x('Atty.','Attorney, or lawyer','wp-crm-system'),'prof'=>_x('Prof.','Professor, as in a teacher at a university.','wp-crm-system'),'hon'=>_x('Hon.','Honorable - often used for elected officials or judges.','wp-crm-system'),'pres'=>_x('Pres.','Term given to the head of an organization or country. As in President of a University or President of the United States','wp-crm-system'),'gov'=>_x('Gov.','Governor, as in the Governor of the State of New York.','wp-crm-system'),'ofc'=>_x('Ofc.','Officer as in a police officer.','wp-crm-system'),'supt'=>_x('Supt.','Superintendent','wp-crm-system'),'rep'=>_x('Rep.','Representative - as in an elected official to the House of Representatives','wp-crm-system'),'sen'=>_x('Sen.','An elected official - Senator.','wp-crm-system'),'amb'=>_x('Amb.','Ambassador - a diplomatic official.','wp-crm-system'));
										} ?>
										<select name="<?php echo $this->prefix . $defaultField[ 'name' ]; ?>">
										<?php foreach ($args as $key => $value) { ?>
											<option value="<?php echo $key; ?>" <?php if (esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) == $key) { echo 'selected'; } ?> ><?php echo $value; if ( $defaultField[ 'type' ] == "selectprogress" ) { echo '%'; }?></option>
										<?php } ?>
										</select>
										<?php
									}
									break;
								}
								case 'currency': {
									if ( $defaultField[ 'type' ] == "currency" ) { 
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>&nbsp;&nbsp;';?>
										<input style="width:25%;" type="text" name="<?php echo $this->prefix . $defaultField[ 'name' ]; ?>" id="<?php echo $this->prefix . $defaultField[ 'name' ]; ?>" value="<?php echo esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ); ?>" placeholder="<?php _e($defaultField['placeholder'],'wp-crm-system'); ?>" />
										<?php echo strtoupper(get_option('wpcrm_system_default_currency')); ?>
										<br />
										<em><?php _e('Only numbers allowed. No thousands separator (commas, spaces, or periods), currency symbols, etc. allowed.', 'wp-crm-system');?></em><?php
									}
									break;
								}
                                case 'textarea':
                                case 'wysiwyg': {
                                    
										echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									if ($defaultField[ 'type' ] == 'textarea') {
										echo '<textarea name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" columns="30" rows="3">' . esc_textarea( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) . '</textarea>';
									}
                                    // WYSIWYG
                                    if ( $defaultField[ 'type' ] == "wysiwyg" ) { 
										$post = get_post( get_the_ID(), OBJECT, 'edit' );
										$content = get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true );
										$editor_id = $this->prefix . $defaultField[ 'name' ];
										wp_editor($content, $editor_id);
                                    }
                                    break;
                                }
								case 'checkbox': {
                                    // Checkbox
                                    echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>&nbsp;&nbsp;';
                                    echo '<input type="checkbox" name="' . $this->prefix . $defaultField['name'] . '" id="' . $this->prefix . $defaultField['name'] . '" value="yes"';
                                    if ( get_post_meta( $post->ID, $this->prefix . $defaultField['name'], true ) == "yes" )
                                        echo ' checked="checked"';
                                    echo '" style="width: auto;" />';
                                    break;
                                }
								case 'datepicker': {
									if (!null == (get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ]))) { 
										$date = date(get_option('wpcrm_system_php_date_format'),esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) ); 
									} else { 
										$date = '';
									}
									//Datepicker
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
                                    echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" class="datepicker" value="' . $date . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />'; ?>
									<script type="text/javascript">
									<?php 
										$dateformat = get_option('wpcrm_system_date_format'); 
										echo "var formatOption = '".$dateformat."';";
									?>
										jQuery(document).ready(function() {
											jQuery('.datepicker').datepicker({
												dateFormat : formatOption //allow date format change in settings
											});
										});
									</script>
									<?php
                                    break;
								}								
								case 'dropbox': {
									if(is_plugin_active('wp-crm-system-dropbox/wp-crm-system-dropbox.php')) {
										$field = $this->prefix . $defaultField[ 'name' ];
										$title = $defaultField[ 'title' ];
										wp_crm_dropbox_content($field,$title);
									} else {
										echo '';
									}
									break;
								}
								case 'zendesk': {
									if(is_plugin_active('wp-crm-system-zendesk/wp-crm-system-zendesk.php')) {
										if ((get_option('_wpcrm_zendesk_api_key') && get_option('_wpcrm_zendesk_user') && get_option('_wpcrm_zendesk_subdomain')) != '') {
											// Set display fields
											$field = $this->prefix . $defaultField[ 'name' ];
											$title = $defaultField[ 'title' ];
											$contact = $post->ID;
											wp_crm_zendesk_content($field,$title,$contact);
										}
									}
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
									if ($defaultField['name'] == 'contact-gmap'){
										$addressString = get_post_meta( $post->ID, $this->prefix . 'contact-address1', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-address2', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-city', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-state', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-postal', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-country', true );
									}
									if ($defaultField['name'] == 'organization-gmap'){
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
								case 'email': {
									// Plain text field with email validation
                                    echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
                                    echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" value="' . esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
                                    break;
                                }
								case 'url': {
									// Plain text field with url validation
                                    echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
                                    echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" value="' . esc_url( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
                                    break;
                                }
								case 'number': {
									// Plain text field with number validation
                                    echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
                                    echo '<input type="number" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" value="' . esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
                                    break;
                                }
                                default: {
                                    // Plain text field
                                    echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
                                    echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" value="' . esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
                                    break;
                                }
                            }
                            ?>
                            <?php if ( $defaultField[ 'description' ] ) echo '<p>' . $defaultField[ 'description' ] . '</p>'; ?>
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
        function saveWPCRMSystemFields( $post_id, $post ) {
            if ( !isset( $_POST[ 'wpcrm-fields_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'wpcrm-fields_wpnonce' ], 'wpcrm-fields' ) )
                return;
            if ( !current_user_can( 'edit_post', $post_id ) )
                return;
            if ( ! in_array( $post->post_type, $this->postTypes ) )
                return;
            foreach ( $this->defaultFields as $defaultField ) {
                if ( current_user_can( $defaultField['capability'], $post_id ) ) {
                    if ( isset( $_POST[ $this->prefix . $defaultField['name'] ] ) && trim( $_POST[ $this->prefix . $defaultField['name'] ] ) ) {
						//Get field's value
                        $value = $_POST[ $this->prefix . $defaultField['name'] ];
						$safevalue = '';
						/** Validate and sanitize input **/
							if ( $defaultField['type'] == 'selectcontact' ) {
								$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-contact'));
								$posts = array();
								foreach ($allowed as $post) {
									$posts[] = $post->ID;
								}
								if ($posts) {
									if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
								}
							}
							if ( $defaultField['type'] == 'selectproject' ) {
								$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-project'));
								$posts = array();
								foreach ($allowed as $post) {
									$posts[] = $post->ID;
								}
								if ($posts) {
									if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
								}
							}
							if ( $defaultField['type'] == 'selectorganization' ) {
								$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-organization'));
								$posts = array();
								foreach ($allowed as $post) {
									$posts[] = $post->ID;
								}
								if ($posts) {
									if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
								}
							}
							if ( $defaultField['type'] == 'selectcampaign' ) {
								$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-campaign'));
								$posts = array();
								foreach ($allowed as $post) {
									$posts[] = $post->ID;
								}
								if ($posts) {
									if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
								}
							}
							if ( $defaultField['type'] == 'selectprogress' ) {
								$allowed = array('zero',5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100);
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = 'zero';}
							}
							if ( $defaultField['type'] == 'selectwonlost' ) {
								$allowed = array('not-set','won','lost','suspended','abandoned');
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = 'not-set';}
							}
							if ( $defaultField['type'] == 'selectpriority' ) {
								$allowed = array('','low','medium','high');
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = '';}
							}
							if ( $defaultField['type'] == 'selectstatus' ) {
								$allowed = array('not-started','in-progress','complete','on-hold');
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = 'not-started';}
							}
							if ( $defaultField['type'] == 'selectnameprefix' ) {
								$allowed = array('','mr','mrs','miss','ms','dr','master','coach','rev','fr','atty','prof','hon','pres','gov','ofc','supt','rep','sen','amb');
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = '';}
							}
							if ( $defaultField['type'] == 'selectuser' ) {
								$users = get_users();
								$wp_crm_users = array();
								foreach( $users as $user ){
									if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
										$wp_crm_users[] = $user->data->user_login;
									}
								}
								if(in_array($value,$wp_crm_users)){$safevalue = $value;}else{$safevalue = '';}
							}
							if ( $defaultField['type'] == 'dropbox' ) {
								// Save data
								$safevalue = $value;
							}
							if ( $defaultField['type'] == 'gmap' ) {
								// Google maps needs no input to be saved.
								$safevalue = '';
							}
							if ( $defaultField['type'] == 'datepicker' ) {
								// Datepicker fields should be strtotime()
								$safevalue = strtotime($value);
							}
							if ( $defaultField['type'] == 'currency' || $defaultField['type'] == 'number' ) {
								// Save currency only with numbers.
								$safevalue = preg_replace("/[^0-9]/", "", $value);
							}
							if ( $defaultField['type'] == 'wysiwyg' ) {
								// Auto-paragraphs for any WYSIWYG. Sanitize content for allowed HTML
								$safevalue = wp_kses_post( wpautop( $value ) );
							}
							if ( $defaultField['type'] == 'textarea' ) {
								//Sanitize content for allowed textarea content.
								$safevalue = esc_textarea( $value );
							}
							if ( $defaultField['type'] == 'url' ) {
								//Sanitize URLs
								$safevalue = esc_url_raw( $value );
							}
							if ( $defaultField['type'] == 'email' ) {
								// Sanitize email field and make sure value is actually an email
								$email = sanitize_email( $value );
								if ( is_email($email)) {$safevalue = $email;} else {$safevalue = '';}
							}
							if ( $defaultField['type'] == 'checkbox' ) {
								//Option will either be yes or blank
								$allowed = array('','yes');
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = '';}
							}
							if ( $defaultField['type'] == 'default' ) {
								// Sanitize text field
								$safevalue = sanitize_text_field( $value );
							}
						update_post_meta( $post_id, $this->prefix . $defaultField[ 'name' ], $safevalue );
                    } else {
                        delete_post_meta( $post_id, $this->prefix . $defaultField[ 'name' ] );
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