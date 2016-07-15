<?php
/*
Plugin Name: WP-CRM System
Plugin URI: https://www.wp-crm.com
Description: A complete CRM for WordPress
Version: 1.2.1
Author: Scott DeLuzio
Author URI: https://www.wp-crm.com
Text Domain: wp-crm-system
*/

/*
 * Copyright 2016  Scott DeLuzio (email : support (at) wp-crm.com)
 * "Fax" Icon made by Freepik [www.freepik.com] from www.flaticon.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');

/* Load Text Domain */
add_action('plugins_loaded', 'wp_crm_plugin_init');
function wp_crm_plugin_init() {
	load_plugin_textdomain( 'wp-crm-system', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}

/* Settings Page */

// Hook for adding admin menu
add_action('admin_menu', 'wpcrm_admin_page');
// action function for above hook
function wpcrm_admin_page() {
	// Add a new menu:
	add_menu_page(__('WP-CRM System', 'wp-crm-system'), __('WP-CRM System', 'wp-crm-system'),WPCRM_USER_ACCESS,'wpcrm','wpcrm_settings_page', 'dashicons-id');
	add_submenu_page( 'wpcrm', __('Dashboard', 'wp-crm-system'), __('Dashboard', 'wp-crm-system'), 'manage_options', 'wpcrm-settings', 'wpcrm_settings_page' );
	add_submenu_page( 'wpcrm', __('Email', 'wp-crm-system'), __('Email', 'wp-crm-system'), WPCRM_USER_ACCESS, 'wpcrm-email', 'wpcrm_email_page' );
	add_submenu_page( 'wpcrm', __('Reports', 'wp-crm-system'), __('Reports', 'wp-crm-system'), WPCRM_USER_ACCESS, 'wpcrm-reports', 'wpcrm_reports_page' );
	add_submenu_page( 'wpcrm', __('Extensions', 'wp-crm-system'), __('Extensions', 'wp-crm-system'), WPCRM_USER_ACCESS, 'wpcrm-extensions', 'wpcrm_extensions_page' );
}
// Set order of submenu pages
add_filter( 'custom_menu_order', 'wpcrm_system_custom_menu_order' );
function wpcrm_system_custom_menu_order( $menu_ord ) {
	global $submenu;
	$arr = array();
	if ( defined( 'WPCRM_INVOICING' ) ) {
		$arr[] = $submenu['wpcrm'][7]; //Settings
		$arr[] = $submenu['wpcrm'][3]; //Organizations
		$arr[] = $submenu['wpcrm'][1]; //Contacts
		$arr[] = $submenu['wpcrm'][4]; //Opportunities
		$arr[] = $submenu['wpcrm'][5]; //Projects
		$arr[] = $submenu['wpcrm'][2]; //Tasks
		$arr[] = $submenu['wpcrm'][6]; //Campaigns
		$arr[] = $submenu['wpcrm'][0]; //Invoices
		$arr[] = $submenu['wpcrm'][8]; //Email
		$arr[] = $submenu['wpcrm'][9]; //Reports
		$arr[] = $submenu['wpcrm'][10]; //Extensions
		$submenu['wpcrm'] = $arr;
	} else {
		$arr[] = $submenu['wpcrm'][6]; //Settings
		$arr[] = $submenu['wpcrm'][2]; //Organizations
		$arr[] = $submenu['wpcrm'][0]; //Contacts
		$arr[] = $submenu['wpcrm'][3]; //Opportunities
		$arr[] = $submenu['wpcrm'][4]; //Projects
		$arr[] = $submenu['wpcrm'][1]; //Tasks
		$arr[] = $submenu['wpcrm'][5]; //Campaigns
		$arr[] = $submenu['wpcrm'][7]; //Email
		$arr[] = $submenu['wpcrm'][8]; //Reports
		$arr[] = $submenu['wpcrm'][9]; //Extensions
		$submenu['wpcrm'] = $arr;
	}
	return $menu_ord;
}
//Include scripts and styles
function wpcrm_scripts_styles() {
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	$active_page = isset( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : '';
	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : '';
	global $post_type;
	wp_enqueue_script('datepicker');
	wp_enqueue_script('jquery-ui-datepicker');

	wp_register_style('jquery-ui-datepicker', plugins_url('/css/jquery-ui.min.css', __FILE__));
	wp_enqueue_style('jquery-ui-datepicker');

	wp_register_style('gmap-style', plugins_url('/css/gmap.css', __FILE__));
	wp_enqueue_style('gmap-style');

	wp_register_style('wpcrm-style', plugins_url('/css/wp-crm.css', __FILE__));
	wp_enqueue_style('wpcrm-style');

	if ( $active_page == 'wpcrm-email' ) {
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('wpcrm-system-accordion',	plugins_url( '/js/accordion.js',__FILE__) );
	}
	wp_enqueue_script( 'jquery' );

	if ( $active_page == 'wpcrm-settings' && ( $active_tab == '' || $active_tab =='dashboard' ) ) {
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_register_style('wp_crm_system_tooltips_css', plugins_url('/css/tooltip.css', __FILE__));
		wp_enqueue_style('wp_crm_system_tooltips_css');

		wp_register_script('wp_crm_system_tooltips_js', plugins_url('/js/tooltip.js', __FILE__), 1.0, false);
		wp_enqueue_script('wp_crm_system_tooltips_js');
	}
	if ( in_array( $post_type, $postTypes ) || $post_type == 'wpcrm-invoice'){
		wp_register_script('wp_crm_system_edit_js', plugins_url('/js/edit.js', __FILE__), 1.0, false);
		wp_enqueue_script('wp_crm_system_edit_js');
	}
}
add_action( 'admin_enqueue_scripts', 'wpcrm_scripts_styles' );

//* Add TinyMCE Editor and Media Upload to WP-CRM System Comments - To Do
/*add_filter( 'wp_editor_settings', 'comment_editor_visual', 10, 2 );
function comment_editor_visual( $settings, $editor_id ){
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	$screen = get_current_screen();
	if ( in_array( $screen->post_type, $postTypes ) ) {
		$settings['quicktags'] = true;
		$settings['tinymce'] = true;
		$settings['media_buttons'] = true;
		$settings['drag_drop_upload'] = true;
	}
	return $settings;
}*/

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


function wpcrm_admin_notice__update() {
	if ('1.2' == get_option('wpcrm_updated_last_run_version')) {
		return;
	}
	$args = array( 'post_type' => 'wpcrm-contact', 'posts_per_page' => -1 );
	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
		$contact_id = get_the_ID();
		$first = get_post_meta($contact_id,'_wpcrm_contact-first-name',true);
		$last = get_post_meta($contact_id,'_wpcrm_contact-last-name',true);
		$title = get_the_title($contact_id);
		$update_required = 'no';
		if ( empty($first) && empty($last) && '' != $title ){
			$update_required = 'yes';
			break;
		}
	endwhile;
	wp_reset_postdata();
	if ('no' == $update_required) {
		update_option('wpcrm_updated_last_run_version', '1.2');
	} elseif ('yes' == $update_required) {
		$nagclass = 'notice notice-warning';
		$wpcrm_version = get_option( 'wpcrm_system_version' );
		$nagmessage = __( 'WP-CRM System: Your database needs a quick update. Please back up your database and run the update now. <form id="wpcrm-updater" action="" method="POST"><input type="submit" id="wpcrm-update-submit" name="wpcrm-update" class="button-primary" value="Update" /></form>', 'wp-crm-system' );
		$successclass = 'notice notice-success';
		$successmessage = __( 'WP-CRM System Update Successful!', 'wp-crm-system' );

		printf( '<div style="display:none;" id="wpcrm_update_status" class="%1$s"><p>%2$s</p></div>', $successclass, $successmessage );
		printf( '<div id="wpcrm_update_nag" class="%1$s"><p>%2$s</p></div>', $nagclass, $nagmessage );
	}
}
add_action( 'admin_notices', 'wpcrm_admin_notice__update' );

function wpcrm_update_scripts(){
	wp_enqueue_script('wpcrm-ajax', plugin_dir_url(__FILE__) . 'js/wpcrm-update.js', array('jquery'));
	wp_localize_script('wpcrm-ajax', 'wpcrm_vars', array(
			'wpcrm_nonce' => wp_create_nonce('wpcrm-nonce')
		)
	);
}
add_action('admin_enqueue_scripts', 'wpcrm_update_scripts');

function update_wpcrm_contact_names() {
	// If the post title is set but the first/last name fields are not set we'll set one of them so the data is visible and not lost with the layout change.
	if( !isset( $_POST['wpcrm_nonce'] ) || !wp_verify_nonce($_POST['wpcrm_nonce'], 'wpcrm-nonce') ) {
		die('Permissions check failed, please try again.');
	}
	$args = array( 'post_type' => 'wpcrm-contact', 'posts_per_page' => -1 );
	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
		$contact_id = get_the_ID();
		$first = get_post_meta($contact_id,'_wpcrm_contact-first-name',true);
		$last = get_post_meta($contact_id,'_wpcrm_contact-last-name',true);
		$title = get_the_title($contact_id);
		if ( empty($first) && empty($last) && '' != $title ){
			global $wpdb;
			$wpdb->insert( $wpdb->postmeta, array('post_id' => $contact_id, 'meta_key' => '_wpcrm_contact-first-name', 'meta_value' => $title ) );
		}
	endwhile;
	wp_reset_postdata();
	update_option('wpcrm_updated_last_run_version', '1.2');
	die();
}
add_action('wp_ajax_wpcrm_update_contacts', 'update_wpcrm_contact_names');
function activate_wpcrm_system_settings() {
	// Use standard WordPress date format set in Settings > General. Convert PHP date format to jQuery format for date pickers.
	$php_format = get_option( 'date_format' );
	$SYMBOLS_MATCHING = array(
		// Day
		'd' => 'dd',
		'D' => 'D',
		'j' => 'd',
		'l' => 'DD',
		'N' => '',
		'S' => '',
		'w' => '',
		'z' => 'o',
		// Week
		'W' => '',
		// Month
		'F' => 'MM',
		'm' => 'mm',
		'M' => 'M',
		'n' => 'm',
		't' => '',
		// Year
		'L' => '',
		'o' => '',
		'Y' => 'yy',
		'y' => 'y',
		// Time
		'a' => '',
		'A' => '',
		'B' => '',
		'g' => '',
		'G' => '',
		'h' => '',
		'H' => '',
		'i' => '',
		's' => '',
		'u' => ''
	);
	$jqueryui_format = "";
	$escaping = false;
	for($i = 0; $i < strlen($php_format); $i++) {
		$char = $php_format[$i];
		if($char === '\\') // PHP date format escaping character
		{
			$i++;
			if($escaping) $jqueryui_format .= $php_format[$i];
			else $jqueryui_format .= '\'' . $php_format[$i];
			$escaping = true;
		}
		else
		{
			if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
			if(isset($SYMBOLS_MATCHING[$char]))
			$jqueryui_format .= $SYMBOLS_MATCHING[$char];
			else
			$jqueryui_format .= $char;
		}
	}

	add_option('wpcrm_system_select_user_role', 'manage_options');
	add_option('wpcrm_system_default_currency', 'USD');
	add_option('wpcrm_system_report_currency_decimals', 0);
	add_option('wpcrm_system_report_currency_decimal_point', '.');
	add_option('wpcrm_system_report_currency_thousand_separator', ',');
	add_option('wpcrm_system_date_format', $jqueryui_format);
	add_option('wpcrm_system_php_date_format', $php_format);
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
	add_option( "wpcrm_system_version", "1.2" );
}
/* Correct the date format if correct format is not being used */
add_action('admin_init', 'update_date_formats');
function update_date_formats() {
	$js_date_format = get_option('wpcrm_system_date_format');
	$php_date_format = get_option('wpcrm_system_php_date_format');
	if (trim($js_date_format) == '' || trim($php_date_format) == '') {
		$js_acceptable = array('yy-M-d','M d, y','MM dd, yy','dd.mm.y','dd/mm/y','d MM yy','D d MM yy','DD, MM d, yy');
		$php_acceptable = array('Y-M-j','M j, y','F d, Y','d.m.y','d/m/y','j F Y','D j F Y','l, F j, Y');
		if(!in_array($js_date_format,$js_acceptable) || !in_array($php_date_format,$php_acceptable)) {
			update_option('wpcrm_system_date_format','MM dd, yy');
			update_option('wpcrm_system_php_date_format','F d, Y');
		} else {
			update_option('wpcrm_system_date_format',$js_date_format);
			update_option('wpcrm_system_php_date_format',$php_date_format);
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
		'supports'           => array( 'author', 'thumbnail', 'custom-fields', 'comments' )
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
		'supports'           => array( 'title', 'author', 'thumbnail', 'custom-fields', 'comments' )
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
		'supports'           => array( 'title', 'author', 'thumbnail', 'custom-fields', 'comments' )
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
		'supports'           => array( 'title', 'author', 'thumbnail', 'custom-fields', 'comments' )
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
		'supports'           => array( 'title', 'author', 'thumbnail', 'custom-fields', 'comments' )
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
		'supports'           => array( 'title', 'author', 'thumbnail', 'custom-fields', 'comments' )
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
		* @var  string  $wpcrm_currencies  Currencies and their related symbols
		*/
		var $wpcrm_currencies = array('aed'=>'AED','afn'=>'&#1547;','all'=>'&#76;&#101;&#107;','amd'=>'AMD','ang'=>'&#402;','aoa'=>'AOA','ars'=>'&#36;','aud'=>'&#36;','awg'=>'&#402;','azn'=>'&#1084;&#1072;&#1085;','bam'=>'&#75;&#77;','bbd'=>'&#36;','bdt'=>'BDT','bgn'=>'&#1083;&#1074;','bhd'=>'BHD','bif'=>'BIF','bmd'=>'&#36;','bnd'=>'&#36;','bob'=>'&#36;&#98;','brl'=>'&#82;&#36;','bsd'=>'&#36;','btn'=>'BTN','bwp'=>'&#80;','byr'=>'&#112;&#46;','bzd'=>'&#66;&#90;&#36;','cad'=>'&#36;','cdf'=>'CDF','chf'=>'&#67;&#72;&#70;','clp'=>'&#36;','cny'=>'&#165;','cop'=>'&#36;','crc'=>'&#8353;','cuc'=>'CUC','cup'=>'&#8369;','cve'=>'CVE','czk'=>'&#75;&#269;','djf'=>'DJF','dkk'=>'&#107;&#114;','dop'=>'&#82;&#68;&#36;','dzd'=>'DZD','egp'=>'&#163;','ern'=>'ERN','etb'=>'ETB','eur'=>'&#8364;','fjd'=>'&#36;','fkp'=>'&#163;','gbp'=>'&#163;','gel'=>'GEL','ggp'=>'&#163;','ghs'=>'&#162;','gip'=>'&#163;','gmd'=>'GMD','gnf'=>'GNF','gtq'=>'&#81;','gyd'=>'&#36;','hkd'=>'&#36;','hnl'=>'&#76;','hrk'=>'&#107;&#110;','htg'=>'HTG','huf'=>'&#70;&#116;','idr'=>'&#82;&#112;','ils'=>'&#8362;','imp'=>'&#163;','inr'=>'&#8377;','iqd'=>'IQD','irr'=>'&#65020;','isk'=>'&#107;&#114;','jep'=>'&#163;','jmd'=>'&#74;&#36;','jod'=>'JOD','jpy'=>'&#165;','kes'=>'KES','kgs'=>'&#1083;&#1074;','khr'=>'&#6107;','kmf'=>'KMF','kpw'=>'&#8361;','krw'=>'&#8361;','kwd'=>'KWD','kyd'=>'&#36;','kzt'=>'&#1083;&#1074;','lak'=>'&#8365;','lbp'=>'&#163;','lkr'=>'&#8360;','lrd'=>'&#36;','lsl'=>'LSL','lyd'=>'LYD','mad'=>'MAD','mdl'=>'MDL','mga'=>'MGA','mkd'=>'&#1076;&#1077;&#1085;','mmk'=>'MMK','mnt'=>'&#8366;','mop'=>'MOP','mro'=>'MRO','mur'=>'&#8360;','mvr'=>'MVR','mwk'=>'MWK','mxn'=>'&#36;','myr'=>'&#82;&#77;','mzn'=>'&#77;&#84;','nad'=>'&#36;','ngn'=>'&#8358;','nio'=>'&#67;&#36;','nok'=>'&#107;&#114;','npr'=>'&#8360;','nzd'=>'&#36;','omr'=>'&#65020;','pab'=>'&#66;&#47;&#46;','pen'=>'&#83;&#47;&#46;','pgk'=>'PGK','php'=>'&#8369;','pkr'=>'&#8360;','pln'=>'&#122;&#322;','prb'=>'PRB','pyg'=>'&#71;&#115;','qar'=>'&#65020;','ron'=>'&#108;&#101;&#105;','rsd'=>'&#1044;&#1080;&#1085;&#46;','rub'=>'&#1088;&#1091;&#1073;','rwf'=>'RWF','sar'=>'&#65020;','sbd'=>'&#36;','scr'=>'&#8360;','sdg'=>'SDG','sek'=>'&#107;&#114;','sgd'=>'&#36;','shp'=>'&#163;','sll'=>'SLL','sos'=>'&#83;','srd'=>'&#36;','ssp'=>'SSP','std'=>'STD','syp'=>'&#163;','szl'=>'SZL','thb'=>'&#3647;','tjs'=>'TJS','tmt'=>'TMT','tnd'=>'TND','top'=>'TOP','try'=>'&#8378;','ttd'=>'&#84;&#84;&#36;','twd'=>'&#78;&#84;&#36;','tzs'=>'TZS','uah'=>'&#8372;','ugx'=>'UGX','usd'=>'&#36;','uyu'=>'&#36;&#85;','uzs'=>'&#1083;&#1074;','vef'=>'&#66;&#115;','vnd'=>'&#8363;','vuv'=>'VUV','wst'=>'WST','xaf'=>'XAF','xcd'=>'&#36;','xof'=>'XOF','xpf'=>'XPF','yer'=>'&#65020;','zar'=>'&#82;','zmw'=>'ZMW');
		/**
		* @var  array  $postTypes  An array of public custom post types
		*/
		var $postTypes = array( 'wpcrm-contact', 'wpcrm-task', 'wpcrm-organization', 'wpcrm-opportunity', 'wpcrm-project', 'wpcrm-campaign' );
		/**
		* @var  array  $gmapTypes  An array of custom post types to include Google Maps
		*/
		var $gmapTypes = array( 'wpcrm-contact', 'wpcrm-organization' );
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
				'style'					=> 'wp-crm-first',
				'before'				=> '<div class="wp-crm-first wp-crm-one-half"><div class="wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> 'dashicons dashicons-businessman wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-first-name',
				'title'         => WPCRM_FIRST_NAME,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> '',
				'before'				=> '<div class="wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-last-name',
				'title'         => WPCRM_LAST_NAME,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> '',
				'before'				=> '<div class="wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-attach-to-organization',
				'title'         => WPCRM_ORGANIZATION,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'selectorganization',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> '',
				'before'				=> '<div class="wp-crm-first wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> 'dashicons dashicons-building wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-role',
				'title'         => WPCRM_ROLE,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> '',
				'before'				=> '<div class="wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-attach-to-organization-new',
				'title'         => WPCRM_CREATE_ORGANIZATION,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'addorganization',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> 'wp-crm-first',
				'before'				=> '<div class="wp-crm-first wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-website',
				'title'         => WPCRM_WEBSITE,
				'description'   => '',
				'placeholder'		=> 'http://',
				'type'          => 'url',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> '',
				'before'				=> '<div class="wp-crm-first wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> 'dashicons dashicons-admin-links wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-phone',
				'title'         => WPCRM_PHONE,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> '',
				'before'				=> '<div class="wp-crm-first wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> 'dashicons dashicons-phone wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-mobile-phone',
				'title'         => WPCRM_MOBILE_PHONE,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> '',
				'before'				=> '<div class="wp-crm-inline">',
				'after'					=> '</div></div>',
				'icon'					=> 'dashicons dashicons-smartphone wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-address1',
				'title'         => WPCRM_ADDRESS_1,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style' 				=> 'wp-crm-first',
				'before'				=> '<div class="wp-crm-one-half"><div class="wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> 'dashicons dashicons-location wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-address2',
				'title'         => WPCRM_ADDRESS_2,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style' 				=> '',
				'before'				=> '<div class="wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-city',
				'title'         => WPCRM_CITY,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> '',
				'before'				=> '<div class="wp-crm-first wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-state',
				'title'         => WPCRM_STATE,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> '',
				'before'				=> '<div class="wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-postal',
				'title'         => WPCRM_POSTAL,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> '',
				'before'				=> '<div class="wp-crm-inline">',
				'after'					=> '</div>',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-country',
				'title'         => WPCRM_COUNTRY,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> 'wp-crm-first',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-email',
				'title'         => WPCRM_EMAIL,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'email',
				'scope'         => array( 'wpcrm-contact' ),
				'style' 				=> 'wp-crm-first',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> 'dashicons dashicons-email wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-fax',
				'title'         => WPCRM_FAX,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
				'scope'         => array( 'wpcrm-contact' ),
				'style' 				=> 'wp-crm-first',
				'before'				=> '',
				'after'					=> '</div>',
				'icon'					=> 'wpcrm-dashicons-fax',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-additional',
				'title'         => WPCRM_ADDITIONAL,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'wysiwyg',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> 'wp-crm-first',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-dropbox',
				'title'         => WPCRM_DROPBOX,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'dropbox',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> 'wp-crm-first',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'contact-zendesk',
				'title'         => WPCRM_ZENDESK_TICKETS,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'zendesk',
				'scope'         => array( 'wpcrm-contact' ),
				'style'					=> 'wp-crm-first',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			//Task Fields
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
			//Organization Fields
			array(
				'name'          => 'organization-phone',
				'title'         => WPCRM_PHONE,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'default',
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
			//Opportunity Fields
			array(
				'name'          => 'opportunity-attach-to-organization',
				'title'         => WPCRM_ATTACH_ORG,
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
				'title'         => WPCRM_ATTACH_CONTACT,
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
				'title'         => WPCRM_ATTACH_CAMPAIGN,
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
				'title'         => WPCRM_ASSIGNED,
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
				'title'         => WPCRM_CREATE_ORGANIZATION,
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
				'title'         => WPCRM_CREATE_CONTACT,
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
				'title'         => WPCRM_CREATE_CAMPAIGN,
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
				'title'         => WPCRM_PROBABILITY,
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
				'title'         => WPCRM_FORECASTED_CLOSE,
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
				'title'         => WPCRM_VALUE,
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
				'title'         => WPCRM_WON_LOST,
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
				'title'         => WPCRM_DESCRIPTION,
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
				'title'         => WPCRM_DROPBOX,
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
			//Project Fields
			array(
				'name'          => 'project-value',
				'title'         => WPCRM_VALUE,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'currency',
				'scope'         => array( 'wpcrm-project' ),
				'style'					=> 'wp-crm-first wp-crm-one-fourth',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> 'dashicons dashicons-chart-line wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'project-closedate',
				'title'         => WPCRM_CLOSE_DATE,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'datepicker',
				'scope'         => array( 'wpcrm-project' ),
				'style'					=> 'wp-crm-one-fourth',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> 'dashicons dashicons-calendar-alt wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'project-status',
				'title'         => WPCRM_STATUS,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'selectstatus',
				'scope'         => array( 'wpcrm-project' ),
				'style'					=> 'wp-crm-one-fourth',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> 'dashicons dashicons-admin-tools wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'project-progress',
				'title'         => WPCRM_PROGRESS,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'selectprogress',
				'scope'         => array( 'wpcrm-project' ),
				'style'					=> 'wp-crm-one-fourth',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> 'dashicons dashicons-forms wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'project-attach-to-organization',
				'title'         => WPCRM_ATTACH_ORG,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'selectorganization',
				'scope'         => array( 'wpcrm-project' ),
				'style'					=> 'wp-crm-first wp-crm-one-third',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> 'dashicons dashicons-building wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'project-attach-to-contact',
				'title'         => WPCRM_ATTACH_CONTACT,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'selectcontact',
				'scope'         => array( 'wpcrm-project' ),
				'style'					=> 'wp-crm-one-third',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> 'dashicons dashicons-id wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'project-assigned',
				'title'         => WPCRM_ASSIGNED,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'selectuser',
				'scope'         => array( 'wpcrm-project' ),
				'style'					=> 'wp-crm-one-third',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> 'dashicons dashicons-businessman wpcrm-dashicons',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'project-attach-to-organization-new',
				'title'         => WPCRM_CREATE_ORGANIZATION,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'addorganization',
				'scope'         => array( 'wpcrm-project' ),
				'style'					=> 'wp-crm-first wp-crm-one-third',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'project-attach-to-contact-new',
				'title'         => WPCRM_CREATE_CONTACT,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'addcontact',
				'scope'         => array( 'wpcrm-project' ),
				'style'					=> 'wp-crm-one-third',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'project-description',
				'title'         => WPCRM_DESCRIPTION,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'wysiwyg',
				'scope'         => array( 'wpcrm-project' ),
				'style'					=> 'wp-crm-first',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			array(
				'name'          => 'project-dropbox',
				'title'         => WPCRM_DROPBOX,
				'description'   => '',
				'placeholder'   => '',
				'type'          => 'dropbox',
				'scope'         => array( 'wpcrm-project' ),
				'style'					=> 'wp-crm-first',
				'before'				=> '',
				'after'					=> '',
				'icon'					=> '',
				'capability'    => WPCRM_USER_ACCESS
			),
			//Campaign fields
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
		/**
		* PHP 5 Constructor
		*/
		function __construct() {
			add_action( 'admin_menu', array( &$this, 'createWPCRMSystemFields' ) );
			add_action( 'save_post', array( &$this, 'saveWPCRMSystemFields' ), 1, 2 );
			add_action( 'save_post', array( &$this, 'saveContactTitle' ), 2, 1 );
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
					add_meta_box( 'wpcrm-default-fields', __( 'Fields', 'wp-crm-system' ), array( &$this, 'wpcrmDefaultFields' ), $postType, 'normal', 'high' );
				}
				foreach ( $this->gmapTypes as $gmapType ) {
					add_meta_box( 'wpcrm-gmap', __( 'Map', 'wp-crm-system' ), array( &$this, 'wpcrmGmap' ), $gmapType, 'side', 'low' );
				}
				add_meta_box( 'wpcrm-opportunity-options', __( 'WP-CRM System Options', 'wp-crm-system' ), array( &$this, 'wpcrmOpportunityOptions' ), 'wpcrm-opportunity', 'side', 'low' );
				add_meta_box( 'wpcrm-project-tasks', __( 'Tasks', 'wp-crm-system' ), array( &$this, 'wpcrmListTasksinProjects' ), 'wpcrm-project', 'side', 'low' );
				add_meta_box( 'wpcrm-organization-projects', __( 'Projects', 'wp-crm-system' ), array( &$this, 'wpcrmListProjectsinOrganizations' ), 'wpcrm-organization', 'side', 'low' );
				add_meta_box( 'wpcrm-organization-tasks', __( 'Tasks', 'wp-crm-system' ), array( &$this, 'wpcrmListTasksinOrganizations' ), 'wpcrm-organization', 'side', 'low' );
				add_meta_box( 'wpcrm-organization-opportunities', __( 'Opportunities', 'wp-crm-system' ), array( &$this, 'wpcrmListOpportunitiesinOrganizations' ), 'wpcrm-organization', 'side', 'low' );
				add_meta_box( 'wpcrm-organization-contacts', __( 'Contacts', 'wp-crm-system' ), array( &$this, 'wpcrmListContactsinOrg' ), 'wpcrm-organization', 'side', 'low' );
				add_meta_box( 'wpcrm-contacts-opportunities', __( 'Opportunities', 'wp-crm-system' ), array( &$this, 'wpcrmListOpportunitiesinContact' ), 'wpcrm-contact', 'side', 'low' );
				add_meta_box( 'wpcrm-contacts-projects', __( 'Projects', 'wp-crm-system' ), array( &$this, 'wpcrmListProjectsinContact' ), 'wpcrm-contact', 'side', 'low' );
				add_meta_box( 'wpcrm-contacts-tasks', __( 'Tasks', 'wp-crm-system' ), array( &$this, 'wpcrmListTasksinContact' ), 'wpcrm-contact', 'side', 'low' );
			}
		}

		/**
		* Display the Google Maps meta box
		*/
		function wpcrmGmap() {
			global $post;
			echo '<div class="form-field form-required">';
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
		if (get_post_type() == 'wpcrm-contact'){
			$addressString = get_post_meta( $post->ID, $this->prefix . 'contact-address1', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-address2', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-city', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-state', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-postal', true ) . ' ' . get_post_meta( $post->ID, $this->prefix . 'contact-country', true );
		}
		if (get_post_type() == 'wpcrm-organization'){
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
			<script type="text/javascript" src="//maps.google.com/maps/api/js"></script>
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
		} ?>
	</div>
	<?php }
	/**
	* Display the additional options meta box
	*/
	function wpcrmOpportunityOptions() {
		global $post;
		$screen = get_current_screen();
		$author_id = get_current_user_id();
		$title = get_the_title();
		$slug = preg_replace("/[^A-Za-z0-9]/",'',strtolower($title));

		$projectFromOpportunity = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&wpcrm-system-action=new-project-from-opportunity';
		echo WPCRM_SAVE_CHANGES;
		echo '<ul>';
		echo '<li><a href="' . $projectFromOpportunity . '">' . __( 'Create Project From Opportunity', 'wp-crm-system' ) . '</a></li>';
		echo '</ul>';

		if ( isset( $_GET['wpcrm-system-action'] ) ) {
			$action = $_GET['wpcrm-system-action'];
			/* Do wpcrm-system-action */
			if ( $action == 'new-project-from-opportunity' ) {
				if( null == get_page_by_title( $title, OBJECT, 'wpcrm-project' ) ) {
					$org = get_post_meta( $post->ID, $this->prefix . 'opportunity-attach-to-organization', true );
					$contact = get_post_meta( $post->ID, $this->prefix . 'opportunity-attach-to-contact', true );
					$assigned = get_post_meta( $post->ID, $this->prefix. 'opportunity-assigned', true );
					$close = get_post_meta( $post->ID, $this->prefix . 'opportunity-closedate', true );
					$value = get_post_meta( $post->ID, $this->prefix . 'opportunity-value', true );
					$description = get_post_meta( $post->ID, $this->prefix . 'opportunity-description', true );
					$post_id = wp_insert_post(
					array(
						'comment_status'	=>	'closed',
						'ping_status'		=>	'closed',
						'post_author'		=>	$author_id,
						'post_name'			=>	$slug,
						'post_title'		=>	$title,
						'post_status'		=>	'publish',
						'post_type'			=>	'wpcrm-project'
					)
				);
				add_post_meta($post_id, $this->prefix .'project-attach-to-organization',$org,true);
				add_post_meta($post_id, $this->prefix .'project-attach-to-contact',$contact,true);
				add_post_meta($post_id, $this->prefix .'project-assigned',$contact,true);
				if($close != '') {
					add_post_meta($post_id, $this->prefix .'project-closedate',$close,true);
				}
				add_post_meta($post_id, $this->prefix .'project-value',$value,true);
				add_post_meta($post_id, $this->prefix .'project-description',$description,true);
				echo '<div class="updated"><p>'.__('New Project Added!','wp-crm-system-zendesk').'</p></div>';
			} else {
				echo '<div class="error"><p>'.__('Project not added. A project with this name already exists: ','wp-crm-system-zendesk').$title.'</p></div>';
			}
		}
	}
}
function wpcrmListTasksinProjects() {
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	//List Tasks
	$meta_key = $prefix . 'task-attach-to-project';
	$meta_value = get_the_ID();
	$tasks = get_posts( array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'wpcrm-task',
		'meta_query' 		=> array(
			array(
				'key' 	=> $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$task_report = '';
	if ($tasks == '') {
		$task_report = '';
	} else {
		foreach( $tasks as $task ) {
			$task_report .= '<li><a href="' . get_edit_post_link($task) . '">' . get_the_title($task) . '</a></li>';
		}
	}
	if ($task_report != '') {
		echo '<ul>' . $task_report . '</ul>';
	} else {
		_e('No tasks assigned to this project.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListProjectsinContact() {
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	//List Projects
	$meta_key = $prefix . 'project-attach-to-contact';
	$meta_value = get_the_ID();
	$projects = get_posts( array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'wpcrm-project',
		'meta_query' 		=> array(
			array(
				'key' 	=> $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$project_report = '';
	if ($projects == '') {
		$project_report = '';
	} else {
		foreach( $projects as $project ) {
			$project_report .= '<li><a href="' . get_edit_post_link($project) . '">' . get_the_title($project) . '</a></li>';
		}
	}
	if ($project_report != '') {
		echo '<ul>' . $project_report . '</ul>';
	} else {
		_e('No projects assigned to this contact.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListTasksinContact() {
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	//List Tasks
	$meta_key = $prefix . 'task-attach-to-contact';
	$meta_value = get_the_ID();
	$tasks = get_posts( array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'wpcrm-task',
		'meta_query' 		=> array(
			array(
				'key' 	=> $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$task_report = '';
	if ($tasks == '') {
		$task_report = '';
	} else {
		foreach( $tasks as $task ) {
			$task_report .= '<li><a href="' . get_edit_post_link($task) . '">' . get_the_title($task) . '</a></li>';
		}
	}
	if ($task_report != '') {
		echo '<ul>' . $task_report . '</ul>';
	} else {
		_e('No tasks assigned to this contact.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListOpportunitiesinContact() {
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	//List Opportunities
	$meta_key = $prefix . 'opportunity-attach-to-contact';
	$meta_value = get_the_ID();
	$opportunities = get_posts( array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'wpcrm-opportunity',
		'meta_query' 		=> array(
			array(
				'key' 	=> $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$opportunity_report = '';
	if ($opportunities == '') {
		$opportunity_report = '';
	} else {
		foreach( $opportunities as $opportunity ) {
			$opportunity_report .= '<li><a href="' . get_edit_post_link($opportunity) . '">' . get_the_title($opportunity) . '</a></li>';
		}
	}
	if ($opportunity_report != '') {
		echo '<ul>' . $opportunity_report . '</ul>';
	} else {
		_e('No opportunities assigned to this contact.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListProjectsinOrganizations() {
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	//List Projects
	$meta_key = $prefix . 'project-attach-to-organization';
	$meta_value = get_the_ID();
	$projects = get_posts( array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'wpcrm-project',
		'meta_query' 		=> array(
			array(
				'key' 	=> $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$project_report = '';
	if ($projects == '') {
		$project_report = '';
	} else {
		foreach( $projects as $project ) {
			$project_report .= '<li><a href="' . get_edit_post_link($project) . '">' . get_the_title($project) . '</a></li>';
		}
	}
	if ($project_report != '') {
		echo '<ul>' . $project_report . '</ul>';
	} else {
		_e('No projects assigned to this organization.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListTasksinOrganizations() {
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	//List Tasks
	$meta_key = $prefix . 'task-attach-to-organization';
	$meta_value = get_the_ID();
	$tasks = get_posts( array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'wpcrm-task',
		'meta_query' 		=> array(
			array(
				'key' 	=> $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$task_report = '';
	if ($tasks == '') {
		$task_report = '';
	} else {
		foreach( $tasks as $task ) {
			$task_report .= '<li><a href="' . get_edit_post_link($task) . '">' . get_the_title($task) . '</a></li>';
		}
	}
	if ($task_report != '') {
		echo '<ul>' . $task_report . '</ul>';
	} else {
		_e('No tasks assigned to this organization.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListOpportunitiesinOrganizations() {
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	//List Opportunities
	$meta_key = $prefix . 'opportunity-attach-to-organization';
	$meta_value = get_the_ID();
	$opportunitys = get_posts( array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'wpcrm-opportunity',
		'meta_query' 		=> array(
			array(
				'key' 	=> $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$opportunity_report = '';
	if ($opportunitys == '') {
		$opportunity_report = '';
	} else {
		foreach( $opportunitys as $opportunity ) {
			$opportunity_report .= '<li><a href="' . get_edit_post_link($opportunity) . '">' . get_the_title($opportunity) . '</a></li>';
		}
	}
	if ($opportunity_report != '') {
		echo '<ul>' . $opportunity_report . '</ul>';
	} else {
		_e('No opportunities assigned to this organization.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListContactsinOrg() {
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	//List Contacts
	$meta_key = $prefix . 'contact-attach-to-organization';
	$meta_value = get_the_ID();
	$contacts = get_posts( array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'wpcrm-contact',
		'meta_query' 		=> array(
			array(
				'key' 	=> $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$contact_report = '';
	if ($contacts == '') {
		$contact_report = '';
	} else {
		foreach( $contacts as $contact ) {
			$contact_report .= '<li><a href="' . get_edit_post_link($contact) . '">' . get_the_title($contact) . '</a></li>';
		}
	}
	if ($contact_report != '') {
		echo '<ul>' . $contact_report . '</ul>';
	} else {
		_e('No contacts assigned to this organization.','wp-crm-system');
	}
	wp_reset_postdata();
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
				<?php
				switch ( $defaultField[ 'type' ] ) {
					case 'addproject': {
						$projectmeta = get_post_meta( $post->ID, substr( $this->prefix . $defaultField[ 'name' ], 0, -4), true );
						if ( $projectmeta == '' ) {
							$before = $defaultField[ 'before' ];
							$after = $defaultField[ 'after' ];
							echo $before;
							echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							echo '<label for="' . $this->prefix . $defaultField['name'] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
							echo '<input type="text" name="' . $this->prefix . $defaultField['name'] . '" id="' . $this->prefix . $defaultField['name'] . '" value="" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
							echo '</div>';
							echo $after;
						}
						break;
					}
					case 'addorganization': {
						$orgmeta = get_post_meta( $post->ID, substr( $this->prefix . $defaultField[ 'name' ], 0, -4), true );
						if ( $orgmeta == '' ) {
							$before = $defaultField[ 'before' ];
							$after = $defaultField[ 'after' ];
							echo $before;
							echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							echo '<label for="' . $this->prefix . $defaultField['name'] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
							echo '<input type="text" name="' . $this->prefix . $defaultField['name'] . '" id="' . $this->prefix . $defaultField['name'] . '" value="" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
							echo '</div>';
							echo $after;
						}
						break;
					}
					case 'addcontact': {
						$contactmeta = get_post_meta( $post->ID, substr( $this->prefix . $defaultField[ 'name' ], 0, -4), true );
						if ( $contactmeta == '' ) {
							$before = $defaultField[ 'before' ];
							$after = $defaultField[ 'after' ];
							echo $before;
							echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							echo '<label for="' . $this->prefix . $defaultField['name'] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
							echo '<input type="text" name="' . $this->prefix . $defaultField['name'] . '" id="' . $this->prefix . $defaultField['name'] . '" value="" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
							echo '</div>';
							echo $after;
						}
						break;
					}
					case 'addcampaign': {
						$campaignmeta = get_post_meta( $post->ID, substr( $this->prefix . $defaultField[ 'name' ], 0, -4), true );
						if ( $campaignmeta == '' ) {
							$before = $defaultField[ 'before' ];
							$after = $defaultField[ 'after' ];
							echo $before;
							echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							echo '<label for="' . $this->prefix . $defaultField['name'] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
							echo '<input type="text" name="' . $this->prefix . $defaultField['name'] . '" id="' . $this->prefix . $defaultField['name'] . '" value="" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
							echo '</div>';
							echo $after;
						}
						break;
					}

					case 'selectuser':
					case 'selectcampaign':
					case 'selectorganization':
					case 'selectcontact':
					case 'selectproject':
					case 'selectprogress':
					case 'selectwonlost':
					case 'selectpriority':
					case 'selectstatus':
					case 'selectnameprefix': {
						// Select
						$selection = get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true );
						$before = $defaultField[ 'before' ];
						$after = $defaultField[ 'after' ];
						echo $before;
						echo '<div onmouseenter=showEdit("' . $this->prefix . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . $this->prefix . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
						//Select User
						if ( $defaultField[ 'type' ] == "selectuser" ) {
							if ( isset( $selection ) && '' != $selection ){
								if ( '' != $this->prefix . $defaultField[ 'icon' ] ){
									echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
								}
								echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:none;" id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								echo '<select id="' . $this->prefix . $defaultField[ 'name' ] . '-input" style="display:none;" name="' . $this->prefix . $defaultField[ 'name' ] . '">';
								if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="" ' . $selected . '>Not Assigned</option>';
								$users = get_users();
								$wp_crm_users = array();
								foreach( $users as $user ){
									if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
										$wp_crm_users[] = $user;
									}
								}
								foreach( $wp_crm_users as $user) {
									if ($selection == $user->data->user_login) { $selected = 'selected'; $display_name = $user->data->display_name; } else { $selected = ''; }
									echo '<option value="'.$user->data->user_login.'" ' . $selected . '>'.$display_name.'</option>';
								}
								echo'</select>';
								echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-text" style="display:inline">' . $display_name . '</span>';
								echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")></span>';
							} else {
								echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
								echo '<select id="' . $this->prefix . $defaultField[ 'name' ] . '-input" name="' . $this->prefix . $defaultField[ 'name' ] . '">';
								if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="" selected>Not Assigned</option>';
								$users = get_users();
								$wp_crm_users = array();
								foreach( $users as $user ){
									if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
										$wp_crm_users[] = $user;
									}
								}
								foreach( $wp_crm_users as $user) {
									$display_name = $user->data->display_name;
									echo '<option value="'.$user->data->user_login.'" ' . $selected . '>'.$display_name.'</option>';
								}
								echo'</select>';
								echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit"></span>';
							}
						} elseif ( $defaultField[ 'type' ] == "selectcampaign" ) {
							//Select Campaign
							$campaigns = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-campaign'));
							if ($campaigns) {
								if ( isset( $selection ) && '' != $selection ){
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:none;" id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									echo '<select id="' . $this->prefix . $defaultField[ 'name' ] . '-input" style="display:none;" name="' . $this->prefix . $defaultField[ 'name' ] . '">';
									if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="" ' . $selected . '>Not Assigned</option>';
									if ( $selection == 'do not show') { $selected = 'selected'; $linkcampaign = ' '; } else { $selected = ''; }
									echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
									foreach($campaigns as $campaign) {
										if ($selection == $campaign->ID) { $selected = 'selected'; $linkcampaign = $campaign->ID; } else { $selected = ''; }
										echo '<option value="' . $campaign->ID . '"' . $selected . '>' . get_the_title($campaign->ID) . '</option>';
									}
									echo '</select>';
									if (isset($linkcampaign)) {
										if ( '' != $this->prefix . $defaultField[ 'icon' ] ){
											echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
										}
										echo '<a id="' . $this->prefix . $defaultField[ 'name' ] . '-text" '; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:inline;"'; } else { echo 'style="display:none;"'; } echo 'href="' . get_edit_post_link($linkcampaign) . '">' . get_the_title($linkcampaign) . '</a>';
										echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")></span>';
									}
								} else {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
									echo '<select id="' . $this->prefix . $defaultField[ 'name' ] . '-input" name="' . $this->prefix . $defaultField[ 'name' ] . '">';
									if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="" ' . $selected . '>Not Assigned</option>';
									if ( $selection == 'do not show') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
									foreach($campaigns as $campaign) {
										if ($selection == $campaign->ID) { $selected = 'selected'; $linkcampaign = $campaign->ID; } else { $selected = ''; }
										echo '<option value="' . $campaign->ID . '"' . $selected . '>' . get_the_title($campaign->ID) . '</option>';
									}
									echo '</select>';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit"></span>';
								}
							} else {
								echo '<a href="' . admin_url('edit.php?post_type=wpcrm-campaign') . '">';
								_e('Please create a campaign first.','wp-crm-system');
								echo '</a>';
							}
						} elseif ( $defaultField[ 'type' ] == "selectorganization" ) {
							//Select Organization
							$orgs = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-organization'));
							if ($orgs) {
								if ( isset( $selection ) && '' != $selection ){
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:none;" id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									echo '<select id="' . $this->prefix . $defaultField[ 'name' ] . '-input" style="display:none;" name="' . $this->prefix . $defaultField[ 'name' ] . '">';
									if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="" ' . $selected . '>Not Assigned</option>';
									if ( $selection == 'do not show') { $selected = 'selected'; $linkorg = ' '; } else { $selected = ''; }
									echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
									foreach($orgs as $org) {
										if ($selection == $org->ID) { $selected = 'selected'; $linkorg = $org->ID;} else { $selected = ''; }
										echo '<option value="' . $org->ID . '"' . $selected . '>' . get_the_title($org->ID) . '</option>';
									}
									echo '</select>';
									if (isset($linkorg)) {
										if ( '' != $this->prefix . $defaultField[ 'icon' ] ){
											echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
										}
										echo '<a id="' . $this->prefix . $defaultField[ 'name' ] . '-text" '; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:inline;"'; } else { echo 'style="display:none;"'; } echo 'href="' . get_edit_post_link($linkorg) . '">' . get_the_title($linkorg) . '</a>';
										echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")></span>';
									}
								} else {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
									echo '<select id="' . $this->prefix . $defaultField[ 'name' ] . '-input" name="' . $this->prefix . $defaultField[ 'name' ] . '">';
									if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="" ' . $selected . '>Not Assigned</option>';
									if ( $selection == 'do not show') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
									foreach($orgs as $org) {
										if ($selection == $org->ID) { $selected = 'selected'; $linkorg = $org->ID;} else { $selected = ''; }
										echo '<option value="' . $org->ID . '"' . $selected . '>' . get_the_title($org->ID) . '</option>';
									}
									echo '</select>';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit"></span>';
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
								if ( isset( $selection ) && '' != $selection ){
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:none;" id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									echo '<select id="' . $this->prefix . $defaultField[ 'name' ] . '-input" style="display:none;" name="' . $this->prefix . $defaultField[ 'name' ] . '">';
									if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="" ' . $selected . '>Not Assigned</option>';
									if ( $selection == 'do not show') { $selected = 'selected'; $linkcontact = ' '; } else { $selected = ''; }
									echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
									foreach($contacts as $contact) {
										if ($selection == $contact->ID) { $selected = 'selected'; $linkcontact = $contact->ID; } else { $selected = ''; }
										echo '<option value="' . $contact->ID . '"' . $selected . '>' . get_the_title($contact->ID) . '</option>';
									}
									echo '</select>';
									if (isset($linkcontact)) {
										if ( '' != $this->prefix . $defaultField[ 'icon' ] ){
											echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
										}
										echo '<a id="' . $this->prefix . $defaultField[ 'name' ] . '-text" '; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:inline;"'; } else { echo 'style="display:none;"'; } echo 'href="' . get_edit_post_link($linkcontact) . '">' . get_the_title($linkcontact) . '</a>';
										echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")></span>';
									}
								} else {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
									echo '<select id="' . $this->prefix . $defaultField[ 'name' ] . '-input" name="' . $this->prefix . $defaultField[ 'name' ] . '">';
									if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="" ' . $selected . '>Not Assigned</option>';
									if ( $selection == 'do not show') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
									foreach($contacts as $contact) {
										if ($selection == $contact->ID) { $selected = 'selected'; $linkcontact = $contact->ID; } else { $selected = ''; }
										echo '<option value="' . $contact->ID . '"' . $selected . '>' . get_the_title($contact->ID) . '</option>';
									}
									echo '</select>';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit"></span>';
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
								if ( isset( $selection ) && '' != $selection ){
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:none;" id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									echo '<select id="' . $this->prefix . $defaultField[ 'name' ] . '-input" style="display:none;" name="' . $this->prefix . $defaultField[ 'name' ] . '">';
									if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="" ' . $selected . '>Not Assigned</option>';
									foreach($projects as $project) {
										if ($selection == $project->ID) { $selected = 'selected'; $linkproject = $project->ID;} else { $selected = ''; }
										echo '<option value="' . $project->ID . '"' . $selected . '>' . get_the_title($project->ID) . '</option>';
									}
									echo '</select>';
									if (isset($linkproject)) {
										if ( '' != $this->prefix . $defaultField[ 'icon' ] ){
											echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
										}
										echo '<a id="' . $this->prefix . $defaultField[ 'name' ] . '-text" '; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:inline;"'; } else { echo 'style="display:none;"'; } echo 'href="' . get_edit_post_link($linkproject) . '">' . get_the_title($linkproject) . '</a>';
										echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")></span>';
									}
								} else {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
									echo '<select id="' . $this->prefix . $defaultField[ 'name' ] . '-input" name="' . $this->prefix . $defaultField[ 'name' ] . '">';
									if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="" ' . $selected . '>Not Assigned</option>';
									if ( $selection == 'do not show') { $selected = 'selected'; } else { $selected = ''; }
									echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
									foreach($campaigns as $campaign) {
										if ($selection == $campaign->ID) { $selected = 'selected'; $linkcampaign = $campaign->ID; } else { $selected = ''; }
										echo '<option value="' . $campaign->ID . '"' . $selected . '>' . get_the_title($campaign->ID) . '</option>';
									}
									echo '</select>';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit"></span>';
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
								$wpcrm_after = '%';
							}
							//Select Won/Lost
							if ( $defaultField[ 'type' ] == "selectwonlost" ) {
								$args = array('not-set'=>__('Select an option', 'wp-crm-system'),'won'=>_x('Won','Successful, a winner.','wp-crm-system'),'lost'=>_x('Lost','Unsuccessful, a loser.','wp-crm-system'),'suspended'=>_x('Suspended','Temporarily ended, but may resume again.','wp-crm-system'),'abandoned'=>_x('Abandoned','No longer actively working on.','wp-crm-system'));
								$wpcrm_after = '';
							}
							if ( $defaultField[ 'type' ] == "selectpriority" ) {
								$args = array(''=>__('Select an option', 'wp-crm-system'),'low'=>_x('Low','Not of great importance','wp-crm-system'),'medium'=>_x('Medium','Average priority','wp-crm-system'),'high'=>_x('High','Greatest importance','wp-crm-system'));
								$wpcrm_after = '';
							}
							//Select status
							if ( $defaultField[ 'type' ] == "selectstatus" ) {
								$args = array('not-started'=>_x('Not Started','Work has not yet begun.','wp-crm-system'),'in-progress'=>_x('In Progress','Work has begun but is not complete.','wp-crm-system'),'complete'=>_x('Complete','All tasks are finished. No further work is needed.','wp-crm-system'),'on-hold'=>_x('On Hold','Work may be in various stages of completion, but has been stopped for one reason or another.','wp-crm-system'));
								$wpcrm_after = '';
							}
							//Select prefix
							if ( $defaultField[ 'type' ] == "selectnameprefix" ) {
								$args = array(''=>__('Select an Option','wp-crm-system'),'mr'=>_x('Mr.','Title for male without a higher professional title.','wp-crm-system'),'mrs'=>_x('Mrs.','Married woman or woman who has been married with no higher professional title.','wp-crm-system'),'miss'=>_x('Miss','An unmarried woman. Also Ms.','wp-crm-system'),'ms'=>_x('Ms.','An unmarried woman. Also Miss.','wp-crm-system'),'dr'=>_x('Dr.','Doctor','wp-crm-system'),'master'=>_x('Master','Title used for young men.','wp-crm-system'),'coach'=>_x('Coach','Title used for the person in charge of a sports team','wp-crm-system'),'rev'=>_x('Rev.','Title of a priest or religous clergy - Reverend ','wp-crm-system'),'fr'=>_x('Fr.','Title of a priest or religous clergy - Father','wp-crm-system'),'atty'=>_x('Atty.','Attorney, or lawyer','wp-crm-system'),'prof'=>_x('Prof.','Professor, as in a teacher at a university.','wp-crm-system'),'hon'=>_x('Hon.','Honorable - often used for elected officials or judges.','wp-crm-system'),'pres'=>_x('Pres.','Term given to the head of an organization or country. As in President of a University or President of the United States','wp-crm-system'),'gov'=>_x('Gov.','Governor, as in the Governor of the State of New York.','wp-crm-system'),'ofc'=>_x('Ofc.','Officer as in a police officer.','wp-crm-system'),'supt'=>_x('Supt.','Superintendent','wp-crm-system'),'rep'=>_x('Rep.','Representative - as in an elected official to the House of Representatives','wp-crm-system'),'sen'=>_x('Sen.','An elected official - Senator.','wp-crm-system'),'amb'=>_x('Amb.','Ambassador - a diplomatic official.','wp-crm-system'));
								$wpcrm_after = '';
							}
							 ?>
							<label for=<?php echo '"' . $this->prefix . $defaultField[ 'name' ] . '"'; ?> style="display:<?php if ( isset( $selection ) && '' != $selection ) { echo 'none'; } else { echo 'inline'; }; ?>" id="<?php echo $this->prefix . $defaultField[ 'name' ]; ?>-label"><strong><?php _e($defaultField[ 'title' ],'wp-crm-system'); ?></strong></label><?php if ( !isset( $selection ) || '' == $selection ) { echo '<br />'; } ?>
							<select id=<?php echo '"' . $this->prefix . $defaultField[ 'name' ] . '-input"'; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:none;"'; } ?> name="<?php echo $this->prefix . $defaultField[ 'name' ]; ?>">
								<?php foreach ($args as $key => $value) { ?>
									<option value="<?php echo $key; ?>" <?php if (esc_html( $selection ) == $key) { echo 'selected'; $display = $value; } ?> ><?php echo $value; if ( $defaultField[ 'type' ] == "selectprogress" ) { echo '%'; }?></option>
									<?php } ?>
								</select>
								<?php if ( '' != $this->prefix . $defaultField[ 'icon' ] && isset ( $selection ) && '' != $selection ){
									echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
								} ?>
								<span id="<?php echo $this->prefix . $defaultField[ 'name' ] . '-text" '; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:inline;"'; } else { echo 'style="display:none;"'; } echo '>' . $display . $wpcrm_after . '</span>'; ?>
								<span id="<?php echo $this->prefix . $defaultField[ 'name' ] . '-edit"'; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")'; } ?> ></span>
									<?php
								}
								echo '</div>';
								echo $after;
								break;
							}
							case 'currency': {
								$amount = esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) );
								if ( $defaultField[ 'type' ] == "currency" ) {
									$active_currency = get_option( 'wpcrm_system_default_currency' );
									foreach ( $this->wpcrm_currencies as $currency => $symbol ){
										if ( $active_currency == $currency ){
											$currency_symbol = $symbol;
										}
									}
									$before = $defaultField[ 'before' ];
									$after = $defaultField[ 'after' ];
									echo $before;
									echo '<div onmouseenter=showEdit("' . $this->prefix . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . $this->prefix . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
									if ( isset( $amount ) && '' != $amount ) {
										echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:none;" id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
										if ( '' != $this->prefix . $defaultField[ 'icon' ] ){
											echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
										}
										echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-text" style="display:inline">' . $currency_symbol . $amount . '</span>';
										echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $amount . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
										echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")></span>';
									} else {
										echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
										echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" value="' . $amount . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
										echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit"></span>';
										echo '<br />';
										echo '<em>' . __('Only numbers allowed. No thousands separator (commas, spaces, or periods), currency symbols, etc. allowed.', 'wp-crm-system') . '</em>';
									} ?>
									<span id="<?php echo $this->prefix . $defaultField[ 'name' ] . '-comment'; ?>" style="display:none;"><br />
									<em><?php _e('Only numbers allowed. No thousands separator (commas, spaces, or periods), currency symbols, etc. allowed.', 'wp-crm-system');?></em></span><?php
									echo '</div>';
									echo $after;
								}
								break;
							}
							case 'textarea':
							case 'wysiwyg': {
								echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
								echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								if ($defaultField[ 'type' ] == 'textarea') {
									echo '<textarea name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" columns="30" rows="3">' . esc_textarea( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) . '</textarea>';
								}
								// WYSIWYG
								if ( $defaultField[ 'type' ] == "wysiwyg" ) {
									$post = get_post( get_the_ID(), OBJECT, 'edit' );
									$content = get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true );
									$editor_id = $this->prefix . $defaultField[ 'name' ];
									$settings = array( 'drag_drop_upload' => true );
									wp_editor($content, $editor_id, $settings);
								}
								echo '</div>';
								break;
							}
							case 'checkbox': {
								// Checkbox
								echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
								echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								echo '<input type="checkbox" name="' . $this->prefix . $defaultField['name'] . '" id="' . $this->prefix . $defaultField['name'] . '" value="yes"';
								if ( get_post_meta( $post->ID, $this->prefix . $defaultField['name'], true ) == "yes" )
								echo ' checked="checked"';
								echo '" style="width: auto;" />';
								echo '</div>';
								break;
							}
							case 'datepicker': {
								if (!null == (get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ]))) {
									$date = date(get_option('wpcrm_system_php_date_format'),esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) );
								} else {
									$date = '';
								}
								$before = $defaultField[ 'before' ];
								$after = $defaultField[ 'after' ];
								//Datepicker
								echo $before;
								echo '<div onmouseenter=showEdit("' . $this->prefix . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . $this->prefix . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
								if ( isset( $date ) && '' != $date ) {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:none;" id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									if ( '' != $this->prefix . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-text" style="display:inline">' . $date . '</span>';
									echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $date . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")></span>';
								} else {
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
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit"></span>';
								}
								echo '</div>';
								echo $after;
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
							case 'email': {
								// Plain text field with email validation
								$email = esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) );
								$before = $defaultField[ 'before' ];
								$after = $defaultField[ 'after' ];
								echo $before;
								echo '<div onmouseenter=showEdit("' . $this->prefix . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . $this->prefix . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
								if ( isset( $email ) && '' != $email ) {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:none;" id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									if ( '' != $this->prefix . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-text" style="display:inline"><a href="mailto:' . $email . '">' . $email . '</a></span>';
									echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $email . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")></span>';
								} else {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" value="' . $email . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit"></span>';
								}
								echo '</div>';
								echo $after;
								break;
							}
							case 'url': {
								// Plain text field with url validation
								$urllink = esc_url( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) );
								$before = $defaultField[ 'before' ];
								$after = $defaultField[ 'after' ];
								echo $before;
								echo '<div onmouseenter=showEdit("' . $this->prefix . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . $this->prefix . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';

								if ( isset( $urllink ) && '' != $urllink ) {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:none;" id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									if ( '' != $this->prefix . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-text" style="display:inline"><a href="' . $urllink . '">' . $urllink . '</a></span>';
									echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $urllink . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")></span>';
								} else {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" value="' . esc_url( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit"></span>';
								}
								echo '</div>';
								echo $after;
								break;
							}
							case 'number': {
								// Plain text field
								$textinput = esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) );
								$before = $defaultField[ 'before' ];
								$after = $defaultField[ 'after' ];
								echo $before;
								echo '<div onmouseenter=showEdit("' . $this->prefix . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . $this->prefix . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
								if ( isset( $textinput ) && '' != $textinput ) {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:none;" id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									if ( '' != $this->prefix . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-text" style="display:inline">' . $textinput . '</span>';
									echo '<input type="number" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $textinput . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")></span>';
								} else {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
									echo '<input class="' . $defaultField[ 'name' ] . '" type="number" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" value="' . esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit"></span>';
								}
								echo '</div>';
								echo $after;
								break;
							}
							default: {
								// Plain text field
								$textinput = esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) );
								$before = $defaultField[ 'before' ];
								$after = $defaultField[ 'after' ];
								echo $before;
								echo '<div onmouseenter=showEdit("' . $this->prefix . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . $this->prefix . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
								if ( isset( $textinput ) && '' != $textinput ) {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:none;" id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									if ( '' != $this->prefix . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-text" style="display:inline">' . $textinput . '</span>';
									echo '<input type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $textinput . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . $this->prefix . $defaultField[ 'name' ] . '")></span>';
								} else {
									echo '<label for="' . $this->prefix . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . $this->prefix . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
									echo '<input class="' . $defaultField[ 'name' ] . '" type="text" name="' . $this->prefix . $defaultField[ 'name' ] . '" id="' . $this->prefix . $defaultField[ 'name' ] . '" value="' . esc_html( get_post_meta( $post->ID, $this->prefix . $defaultField[ 'name' ], true ) ) . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
									echo '<span id="' . $this->prefix . $defaultField[ 'name' ] . '-edit"></span>';
								}
								echo '</div>';
								echo $after;
								break;
							}
						}
						?>
						<?php if ( $defaultField[ 'description' ] ) echo '<p>' . $defaultField[ 'description' ] . '</p>'; ?>
						<?php
					}
				} ?>
			</div>
			<?php
		}
		/**
		* Save the contact's title
		*/
		function saveContactTitle( $post_id ) {
			if ( $post_id == null || empty($_POST) ){
				return;
			}

			if ( !isset( $_POST['post_type'] ) || $_POST['post_type']!='wpcrm-contact' ) {
				return;
			}

			if ( wp_is_post_revision( $post_id ) ) {
				$post_id = wp_is_post_revision( $post_id );
			}

			global $post;
			if ( empty( $post ) ) {
				$post = get_post($post_id);
			}

			if ( isset( $_POST[$this->prefix . 'contact-first-name'] ) && $_POST[$this->prefix . 'contact-first-name'] != '' && isset( $_POST[$this->prefix . 'contact-last-name'] ) && $_POST[$this->prefix . 'contact-last-name'] != '' ) {
				global $wpdb;
				$first = $_POST[$this->prefix . 'contact-first-name'];
				$last = $_POST[$this->prefix . 'contact-last-name'];
				$title = $first . ' ' . $last;
				$where = array( 'ID' => $post_id );
				$wpdb->update( $wpdb->posts, array( 'post_title' => $title ), $where );
			}
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
					if ( isset( $_POST[ $this->prefix . $defaultField['name'] ] ) && trim( $_POST[ $this->prefix . $defaultField['name'] ] ) != '' ) {
						//Get field's value
						$value = $_POST[ $this->prefix . $defaultField['name'] ];
						$safevalue = '';
						$contactTitle = array();
						/** Validate and sanitize input **/
						switch ( $defaultField[ 'type' ] ) {
							case 'selectcontact': {
								$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-contact'));
								$posts = array('do not show');
								foreach ($allowed as $post) {
									$posts[] = $post->ID;
								}
								if ($posts) {
									if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
								}
								break;
							}
							case 'selectproject': {
								$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-project'));
								$posts = array();
								foreach ($allowed as $post) {
									$posts[] = $post->ID;
								}
								if ($posts) {
									if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
								}
								break;
							}
							case 'selectorganization': {
								$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-organization'));
								$posts = array('do not show');
								foreach ($allowed as $post) {
									$posts[] = $post->ID;
								}
								if ($posts) {
									if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
								}
								break;
							}
							case 'selectcampaign': {
								$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-campaign'));
								$posts = array('do not show');
								foreach ($allowed as $post) {
									$posts[] = $post->ID;
								}
								if ($posts) {
									if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
								}
								break;
							}
							case 'selectprogress': {
								$allowed = array('zero',5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100);
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = 'zero';}
								break;
							}
							case 'selectwonlost': {
								$allowed = array('not-set','won','lost','suspended','abandoned');
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = 'not-set';}
								break;
							}
							case 'selectpriority': {
								$allowed = array('','low','medium','high');
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = '';}
								break;
							}
							case 'selectstatus': {
								$allowed = array('not-started','in-progress','complete','on-hold');
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = 'not-started';}
								break;
							}
							case 'selectnameprefix': {
								$allowed = array('','mr','mrs','miss','ms','dr','master','coach','rev','fr','atty','prof','hon','pres','gov','ofc','supt','rep','sen','amb');
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = '';}
								break;
							}
							case 'selectuser': {
								$users = get_users();
								$wp_crm_users = array();
								foreach( $users as $user ){
									if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
										$wp_crm_users[] = $user->data->user_login;
									}
								}
								if(in_array($value,$wp_crm_users)){$safevalue = $value;}else{$safevalue = '';}
								break;
							}
							case 'dropbox': {
								// Save data
								$safevalue = $value;
								break;
							}
							case 'gmap': {
								// Google maps needs no input to be saved.
								$safevalue = '';
								break;
							}
							case 'datepicker': {
								// Datepicker fields should be strtotime()
								$safevalue = strtotime($value);
								break;
							}
							case 'currency':
							case 'number': {
								// Save currency only with numbers.
								$safevalue = preg_replace("/[^0-9]/", "", $value);
								break;
							}
							case 'wysiwyg': {
								// Auto-paragraphs for any WYSIWYG. Sanitize content for allowed HTML
								$safevalue = wp_kses_post( wpautop( $value ) );
								break;
							}
							case 'textarea': {
								//Sanitize content for allowed textarea content.
								$safevalue = esc_textarea( $value );
								break;
							}
							case 'url': {
								//Sanitize URLs
								$safevalue = esc_url_raw( $value );
								break;
							}
							case 'email': {
								// Sanitize email field and make sure value is actually an email
								$email = sanitize_email( $value );
								if ( is_email($email)) {$safevalue = $email;} else {$safevalue = '';}
								break;
							}
							case 'checkbox': {
								//Option will either be yes or blank
								$allowed = array('','yes');
								if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = '';}
								break;
							}
							case 'addcontact': {
								$new_title = sanitize_text_field( $_POST[ $this->prefix . $defaultField['name'] ] );
								$new_slug = preg_replace("/[^A-Za-z0-9]/",'',strtolower($new_title));
								global $post;
								$currentid = $post->ID;
								if( null == get_page_by_title( $new_title, OBJECT, 'wpcrm-contact' ) ) {
									global $wpdb;
									$author_id = get_current_user_id();
									$wpdb->insert($wpdb->posts, array(
										'post_content'	=> '',
										'post_title'		=> $new_title,
										'post_status'		=> 'publish',
										'post_date'			=> date('Y-m-d H:i:s'),
										'post_date_gmt'	=> gmdate('Y-m-d H:i:s'),
										'post_author'		=> $author_id,
										'post_name'			=> $new_slug,
										'post_type'			=> 'wpcrm-contact'
									));
									$safevalue = $wpdb->insert_id;
									update_post_meta( $currentid, substr( $this->prefix . $defaultField[ 'name' ], 0, -4), $safevalue );
									$wpdb->flush();
								}
								break;
							}
							case 'addorganization': {
								$new_org_title = sanitize_text_field( $_POST[ $this->prefix . $defaultField['name'] ] );
								$new_org_slug = preg_replace("/[^A-Za-z0-9]/",'',strtolower($new_org_title));
								global $post;
								$currentid = $post->ID;
								if( null == get_page_by_title( $new_org_title, OBJECT, 'wpcrm-organization' ) ) {
									global $wpdb;
									$author_id = get_current_user_id();
									$wpdb->insert($wpdb->posts, array(
										'post_content'	=> '',
										'post_title'		=> $new_org_title,
										'post_status'		=> 'publish',
										'post_date'			=> date('Y-m-d H:i:s'),
										'post_date_gmt'	=> gmdate('Y-m-d H:i:s'),
										'post_author'		=> $author_id,
										'post_name'			=> $new_org_slug,
										'post_type'			=> 'wpcrm-organization'
									));
									$safevalue = $wpdb->insert_id;
									update_post_meta( $currentid, substr( $this->prefix . $defaultField[ 'name' ], 0, -4), $safevalue );
									$wpdb->flush();
								}
								break;
							}
							case 'addproject': {
								$new_title = sanitize_text_field( $_POST[ $this->prefix . $defaultField['name'] ] );
								$new_slug = preg_replace("/[^A-Za-z0-9]/",'',strtolower($new_title));
								global $post;
								$currentid = $post->ID;
								if( null == get_page_by_title( $new_title, OBJECT, 'wpcrm-project' ) ) {
									global $wpdb;
									$author_id = get_current_user_id();
									$wpdb->insert($wpdb->posts, array(
										'post_content'	=> '',
										'post_title'		=> $new_title,
										'post_status'		=> 'publish',
										'post_date'			=> date('Y-m-d H:i:s'),
										'post_date_gmt'	=> gmdate('Y-m-d H:i:s'),
										'post_author'		=> $author_id,
										'post_name'			=> $new_slug,
										'post_type'			=> 'wpcrm-project'
									));
									$safevalue = $wpdb->insert_id;
									update_post_meta( $currentid, substr( $this->prefix . $defaultField[ 'name' ], 0, -4), $safevalue );
									$wpdb->flush();
								}
								break;
							}
							case 'addcampaign': {
								$new_title = sanitize_text_field( $_POST[ $this->prefix . $defaultField['name'] ] );
								$new_slug = preg_replace("/[^A-Za-z0-9]/",'',strtolower($new_title));
								global $post;
								$currentid = $post->ID;
								if( null == get_page_by_title( $new_title, OBJECT, 'wpcrm-campaign' ) ) {
									global $wpdb;
									$author_id = get_current_user_id();
									$wpdb->insert($wpdb->posts, array(
										'post_content'	=> '',
										'post_title'		=> $new_title,
										'post_status'		=> 'publish',
										'post_date'			=> date('Y-m-d H:i:s'),
										'post_date_gmt'	=> gmdate('Y-m-d H:i:s'),
										'post_author'		=> $author_id,
										'post_name'			=> $new_slug,
										'post_type'			=> 'wpcrm-campaign'
									));
									$safevalue = $wpdb->insert_id;
									update_post_meta( $currentid, substr( $this->prefix . $defaultField[ 'name' ], 0, -4), $safevalue );
									$wpdb->flush();
								}
								break;
							}
							case 'default': {
								// Sanitize text field
								$safevalue = sanitize_text_field( $value );
								if ( 'contact-first-name' == $defaultField['name'] ) {
									$contactFirst = $safevalue;
								}
								if ( 'contact-last-name' == $defaultField['name'] ) {
									$contactLast = $safevalue;
								}
								if ( ! empty( $contactFirst ) && ! empty( $contactLast ) ) {
									$contactTitle = $contactFirst . ' ' . $contactLast;
								}
								break;
							}
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
