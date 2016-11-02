<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
/* Functions to restrict users from managing or viewing other user's WP-CRM System Records
 * Code adapted from Manage/View Your Posts Only plugin by fearnowrath https://wordpress.org/plugins/manageview-your-posts-only/
 */
function wpcrm_system_parse_query_useronly( $wp_query ) {
	if ( 'yes' == get_option( 'wpcrm_hide_others_posts' ) ) {
		$post_types = array( 'wpcrm-contact','wpcrm-task','wpcrm-organization','wpcrm-opportunity','wpcrm-project','wpcrm-campaign','wpcrm-invoice' );
		foreach ( $post_types as $post_type ) {
			if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/edit.php?post_type=' . $post_type ) !== false ) {
		    if ( !current_user_can( 'activate_plugins' ) )  {
						add_action( 'views_edit-' . $post_type, 'wpcrm_system_remove_some_post_views' );
						global $current_user;
						$wp_query->set( 'author', $current_user->ID );
		    }
		  }
		}
	}
}
add_filter('parse_query', 'wpcrm_system_parse_query_useronly' );
/**
 * Remove All, Published and Trashed posts views.
 *
 * Requires WP 3.1+.
 * @param array $views
 * @return array
 */
function wpcrm_system_remove_some_post_views( $views ) {
	unset($views['all']);
	unset($views['publish']);
	unset($views['trash']);
	unset($views['draft']);
	unset($views['pending']);
	return $views;
}
