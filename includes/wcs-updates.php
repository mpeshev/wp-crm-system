<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wpcrm_admin_notice__update() {
	if ('1.2' == get_option('wpcrm_updated_last_run_version')) {
		return;
	}
	$update_required = 'no';
	$args = array( 'post_type' => 'wpcrm-contact', 'posts_per_page' => -1 );
	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
		$contact_id = get_the_ID();
		$first = get_post_meta($contact_id,'_wpcrm_contact-first-name',true);
		$last = get_post_meta($contact_id,'_wpcrm_contact-last-name',true);
		$title = get_the_title($contact_id);
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
		$nagmessage = __( 'WP-CRM System: Your database needs a quick update. Please back up your database and run the update now.', 'wp-crm-system' ) . '<form id="wpcrm-updater" action="" method="POST"><input type="submit" id="wpcrm-update-submit" name="wpcrm-update" class="button-primary" value="Update" /></form>';
		$successclass = 'notice notice-success';
		$successmessage = __( 'WP-CRM System Update Successful!', 'wp-crm-system' );

		printf( '<div style="display:none;" id="wpcrm_update_status" class="%1$s"><p>%2$s</p></div>', $successclass, $successmessage );
		printf( '<div id="wpcrm_update_nag" class="%1$s"><p>%2$s</p></div>', $nagclass, $nagmessage );
	}
}
add_action( 'admin_notices', 'wpcrm_admin_notice__update' );

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

/**
 * Version 3.0 of WP-CRM System included functionality that was previously
 * only available in several premium plugins. These plugins are no longer
 * necessary and may actually cause conflicts if they are loaded.
 * Deactivating these plugins will prevent errors from occurring.
 */
function wp_crm_system_remove_merged_premium_addons(){
	$plugins	= array(
		'wp-crm-system-contact-user/wp-crm-system-contact-user.php',
		'wp-crm-system-email-notifications/wp-crm-system-email-notifications.php',
		'wp-crm-system-import-campaigns/wp-crm-system-import-campaigns.php',
		'wp-crm-system-import-contacts/wp-crm-system-import-contacts.php',
		'wp-crm-system-import-opportunities/wp-crm-system-import-opportunities.php',
		'wp-crm-system-import-organizations/wp-crm-system-import-organizations.php',
		'wp-crm-system-import-projects/wp-crm-system-import-projects.php',
		'wp-crm-system-import-tasks/wp-crm-system-import-tasks.php',
	);
	deactivate_plugins( $plugins );
}
add_action( 'admin_init', 'wp_crm_system_remove_merged_premium_addons', 1 );