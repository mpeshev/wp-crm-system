<?php
/*
Plugin Name: WP-CRM System
Plugin URI: https://www.wp-crm.com
Description: A complete CRM for WordPress
Version: 3.0.2
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

/* Load Text Domain */
add_action('plugins_loaded', 'wp_crm_plugin_init');
function wp_crm_plugin_init() {
	load_plugin_textdomain( 'wp-crm-system', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}

global $wpdb, $wpcrm_system_recurring_db_name, $wpcrm_system_db_version;
$wpcrm_system_recurring_db_name	= $wpdb->prefix . 'wpcrm_system_recurring_entries';
$wpcrm_system_db_version		= 1.0;
/*
 * Includes for WP-CRM System
 */
if ( ! defined( 'WP_CRM_SYSTEM' ) ) {
  define( 'WP_CRM_SYSTEM', __FILE__ );
}
if ( ! defined( 'WP_CRM_SYSTEM_VERSION' ) ) {
  define( 'WP_CRM_SYSTEM_VERSION', '3.0.2' );
}
if( ! defined( 'WP_CRM_SYSTEM_URL' ) ) {
	define( 'WP_CRM_SYSTEM_URL', plugins_url( '', __FILE__ ) );
}
if( ! defined( 'WP_CRM_SYSTEM_PLUGIN_DIR' ) ) {
 	define( 'WP_CRM_SYSTEM_PLUGIN_DIR', dirname( __FILE__ ) );
}
if( ! defined( 'WP_CRM_SYSTEM_PLUGIN_DIR_PATH' ) ) {
 	define( 'WP_CRM_SYSTEM_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if( ! defined( 'WP_CRM_SYSTEM_PLUGIN_URL' ) ) {
	define( 'WP_CRM_SYSTEM_PLUGIN_URL', plugins_url( '', __FILE__ ) );
}
/* Run Updates if Needed */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-updates.php' );
/* Include system variables */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php' );
/* Welcome screen */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-welcome-screen.php' );
/* Include system functions */
include_once( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-functions.php' );
/* Include GDPR Functionality */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/privacy/gdpr-export-campaign.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/privacy/gdpr-export-contact.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/privacy/gdpr-export-opportunity.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/privacy/gdpr-export-project.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/privacy/gdpr-export-task.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/privacy/gdpr-eraser-campaign.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/privacy/gdpr-eraser-contact.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/privacy/gdpr-eraser-opportunity.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/privacy/gdpr-eraser-project.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/privacy/gdpr-eraser-task.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/privacy/gdpr-privacy-policy-content.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/legacy/gdpr-shortcode.php' );
/* Include Create Class */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/class-wpcrmsystemcreate.php' );
/* Initial Install Settings Setup */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-initial-install-settings.php' );
/* Menu Links */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-admin-pages.php' );
/* Register Custom Post Types */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-post-types.php' );
/* Modify Search Query for Contacts */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-search-filters.php' );
/* Setup Dashboard Content */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-dashboard-setup.php' );
/* Show Upsells */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/upsells/wcs-upsell-tabs.php' );
/* Display System Setup */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-system-setup.php' );
/* Display Recurring Entries Settings */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-recurring-entries.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-recurring-entries-process.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-recurring-entries-create.php' );
/* Restrict non-admins from viewing others records */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-restrict-others.php' );
/* Include default fields */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-fields.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-fields-campaign.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-fields-contact.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-fields-opportunity.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-fields-organization.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-fields-project.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-fields-task.php' );
/* Enqueue Scripts and Styles */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-scripts-styles.php' );
/* Include custom meta columns */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-meta-columns-campaign.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-meta-columns-contact.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-meta-columns-opportunity.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-meta-columns-organization.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-meta-columns-project.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-meta-columns-task.php' );
/* Include Create Contact From User */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/contact-from-user.php' );
/* Include Email Notifications */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/email-notifications/admin-settings.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/email-notifications/opportunity-notifications.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/email-notifications/project-notifications.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/email-notifications/task-notifications.php' );
/* Include Import / Export */
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/settings-page.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/class-export.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/export-campaigns.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/import-campaigns.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/export-contacts.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/import-contacts.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/export-opportunities.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/import-opportunities.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/export-organizations.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/import-organizations.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/export-projects.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/import-projects.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/export-tasks.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/import-export/import-tasks.php' );



register_activation_hook( __FILE__, 'wp_crm_system_activate_settings' );
register_uninstall_hook( __FILE__, 'wp_crm_system_deactivate_settings' );

//* Add TinyMCE Editor and Media Upload to WP-CRM System Comments - To Do
/*add_filter( 'wp_editor_settings', 'comment_editor_visual', 10, 2 );
function comment_editor_visual( $settings, $editor_id ){
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php' );
	$screen = get_current_screen();
	if ( in_array( $screen->post_type, $postTypes ) ) {
		$settings['quicktags'] = true;
		$settings['tinymce'] = true;
		$settings['media_buttons'] = true;
		$settings['drag_drop_upload'] = true;
	}
	return $settings;
}*/