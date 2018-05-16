<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_add_privacy_policy_content() {
	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}

	$content = wp_kses_post( apply_filters( 'wpcrm_system_privacy_policy_content', wpautop( __( '
<strong><em>This information should not be copied and pasted into your privacy policy. This is informational and is intended to help you craft a privacy policy message that applies to how your business uses WP-CRM System.</em></strong>

WP-CRM System stores information about your contacts, projects, tasks, campaigns, opportunities, organizations, and certain other information. This information may include, but is not limited to, names, addresses, email addresses, phone numbers, and any other details that you might choose to store.

Handling this data allows WP-CRM System to store your business records on your website (contact information, project progress, etc.) on your behalf.

WP-CRM System does not remove data from your website unless you explicitly delete it. You can delete data by deleting individual fields inside of each record, or by deleting the record entirely. WP-CRM System also hooks into the Export and Erase tools built in to WordPress so that any data associated with a contact can be exported or erased. WP-CRM System provides information on individuals (contacts) and not businesses (organizations) in this manner.

WP-CRM System does not track user behaviors on your website or set cookies.

How data is collected and what is done with the data will depend on your individual set up. Certain add-ons to WP-CRM System will collect information from contact form plugins, while others will send data to third party providers, such as MailChimp, Slack, or others through Zapier. You may also have your own custom integration, which may require additional documentation.
', 'wp-crm-system' ) ) ) );

	wp_add_privacy_policy_content( 'WP-CRM System', $content );
}
add_action( 'admin_init', 'wp_crm_system_add_privacy_policy_content' );