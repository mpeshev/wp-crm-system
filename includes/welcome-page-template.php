<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div style="background-color:#f1f1f1;text-align:center;"><a href="https://www.wp-crm.com/?utm_campaign=welcome-screen-logo&utm_source=welcome-page-upgrade"><img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL . '/includes/images/new-logo-transparent.png'; ?>" alt="WP-CRM System logo" /></a></div>
<h1><?php _e( 'Welcome to WP-CRM System', 'wp-crm-system' ); ?></h1>

<div>
	<?php _e( 'Thanks for using WP-CRM System. There are a lot of things you can do to help streamline your business. Here are a few to help get you started.' ); ?>
</div>

<div class="wp-crm-two-thirds wp-crm-first wp-crm-welcome-content">
	<h2><?php _e( 'Upgrade to make the most of WP-CRM System', 'wp-crm-system' ); ?></h2>
	<h3><?php _e( 'Custom Fields, Import/Export Records', 'wp-crm-system' ); ?></h3>
		<p><?php _e( 'Switching from another CRM, or have some custom data you want to track? Our Custom Fields and Import/Export extensions can help make it easier to transition from your old CRM.', 'wp-crm-system' ); ?></p>
		<p><a href="https://www.wp-crm.com/checkout?edd_action=add_to_cart&download_id=8993&utm_campaign=upgrade-plus-button&utm_source=welcome-page-upgrade" class="button-primary"><?php _e( 'Plus - $99', 'wp-crm-system' ); ?></a><span class="dashicons dashicons-external wpcrm-dashicons"></span><br /><?php _e( 'Custom Fields plus import/export extensions for <strong>one site</strong> <em>(billed annually - cancel any time)</em>', 'wp-crm-system' ); ?></p>
	<h3><?php _e( 'Bill Clients, Connect With Other WordPress Plugins & 3rd Party Apps', 'wp-crm-system' ); ?></h3>
		<p><?php _e( 'Easily bill clients for a maintenance plan or other services with our Client Area extension, and connect to popular WordPress plugins like Gravity Forms, Ninja Forms, WooCommerce, or Easy Digital Downloads. Let our Zapier Connect extension do the heavy lifting and send or receive CRM data to or from over 1,000 third party apps!', 'wp-crm-system' ); ?></p>
		<p><a href="https://www.wp-crm.com/checkout?edd_action=add_to_cart&download_id=8995&utm_campaign=upgrade-professional-button&utm_source=welcome-page-upgrade" class="button-primary"><?php _e( 'Professional - $249', 'wp-crm-system' ); ?></a><span class="dashicons dashicons-external wpcrm-dashicons"></span><br /><?php _e( 'Custom Fields, import/export extensions, bill clients, and connect to 3rd party apps for <strong>unlimited sites</strong> <em>(billed annually - cancel any time)</em>', 'wp-crm-system' ); ?></p>
</div>

<div class="wp-crm-one-third wp-crm-welcome-content">
	<h2><?php _e( 'Get Started', 'wp-crm-system' ); ?></h2>
	<h3><?php _e( 'Create a new...', 'wp-crm-system' ); ?></h3>
	<ul class="wp-crm-welcome-create-links">
		<li><a href="post-new.php?post_type=wpcrm-contact"><span class="dashicons dashicons-id wpcrm-dashicons"></span><?php _e( 'Contact', 'wp-crm-system' ); ?></a></li>
		<li><a href="post-new.php?post_type=wpcrm-organization"><span class="dashicons dashicons-building wpcrm-dashicons"></span><?php _e( 'Organization', 'wp-crm-system' ); ?></a></li>
		<li><a href="post-new.php?post_type=wpcrm-project"><span class="dashicons dashicons-clipboard wpcrm-dashicons"></span><?php _e( 'Project', 'wp-crm-system' ); ?></a></li>
		<li><a href="post-new.php?post_type=wpcrm-task"><span class="dashicons dashicons-yes wpcrm-dashicons"></span><?php _e( 'Task', 'wp-crm-system' ); ?></a></li>
		<li><a href="post-new.php?post_type=wpcrm-opportunity"><span class="dashicons dashicons-phone wpcrm-dashicons"></span><?php _e( 'Opportunity', 'wp-crm-system' ); ?></a></li>
		<li><a href="post-new.php?post_type=wpcrm-campaign"><span class="dashicons dashicons-megaphone wpcrm-dashicons"></span><?php _e( 'Campaign', 'wp-crm-system' ); ?></a></li>
	</ul>
	<h3><?php _e( 'View...', 'wp-crm-system' ); ?></h3>
	<ul class="wp-crm-welcome-create-links">
		<li><a href="admin.php?page=wpcrm-settings"><?php _e( 'Your Dashboard', 'wp-crm-system' ); ?></a></li>
		<li><a href="admin.php?page=wpcrm-reports"><?php _e( 'Reports', 'wp-crm-system' ); ?></a></li>
		<li><a href="https://www.wp-crm.com/document/"><?php _e( 'Support Documentation', 'wp-crm-system' ); ?><span class="dashicons dashicons-external wpcrm-dashicons"></span></a></li>
	</ul>
</div>