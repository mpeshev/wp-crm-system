<?php
// Accessed at wp-admin/index.php?page=wpcrm-system-plugin-welcome
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div style="background-color:#f1f1f1;">
	<div class="wrap about-wrap" style="float:left;">
		<div class="changelog">
			<h1><?php _e( 'About WP-CRM System', 'wp-crm-system' ); ?></h1>
		</div>
	</div>
	<a href="https://www.wp-crm.com/?utm_campaign=welcome-screen-logo&utm_source=welcome-page-upgrade">
		<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL . '/includes/images/new-logo-transparent.png'; ?>" alt="WP-CRM System logo" />
	</a>
</div>
<div class="wrap about-wrap">
	<div class="changelog">
		<h2 class="about-headline-callout"><?php _e( 'Close more sales and improve customer relationships.', 'wp-crm-system' ); ?></h2>
		<div class="about-text wp-crm-welcome-content">
			<?php _e( 'Thanks for using WP-CRM System. Here are a few ways WP-CRM System can help your business to get you started.', 'wp-crm-system' ); ?>
		</div>

		<div class="about-text wp-crm-welcome-content">
			<h3 class="about-headline-callout"><?php _e( 'You own everything', 'wp-crm-system' ); ?></h3>
			<p><?php _e( 'Since all of your customer\'s data is stored on your WordPress site, you will never have to pay expensive monthly fees to have access to it. You will have access to your data for as long as you own your website - the way it should be.', 'wp-crm-system' ); ?></p>
		</div>
		<div class="about-text wp-crm-welcome-content">
			<h3 class="about-headline-callout"><?php _e( 'Reporting On What Matters Most To You', 'wp-crm-system' ); ?></h3>
			<p><?php _e( 'Easily view the status of projects for a certain contact or organization. Get a high level overview of upcoming tasks, and much more!', 'wp-crm-system' ); ?></p>
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL . '/includes/images/welcome-screen-reports.png'; ?>" alt="Task Report Tab" width="1050" height="449" />
		</div>
		<div class="about-text wp-crm-welcome-content">
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL . '/includes/images/address-book-demo.gif'; ?>" alt="Address Book Demo" width="352" height="368" style="float:right;max-width:352px;" />
			<h3 class="about-headline-callout"><?php _e( 'Contact Information At Your Fingertips', 'wp-crm-system' ); ?></h3>
			<p>
				<?php _e( 'Quickly find the information you need for each of your contacts at a glance right from WP-CRM System\'s main dashboard.', 'wp-crm-system' ); ?>
			</p>
			<p>
				<?php _e( 'You will find even more detailed information in each contact\'s record. Click the contact\'s name or photo from the results to view their complete information.', 'wp-crm-system' ); ?>
			</p>
		</div>
		<hr class="wp-crm-first" />
		<div class="about-text wp-crm-welcome-content">
			<h2 class="about-headline-callout"><?php _e( 'Upgrade to make the most of WP-CRM System', 'wp-crm-system' ); ?></h2>
			<h3><?php _e( 'Solutions For Every Business', 'wp-crm-system' ); ?></h3>
				<p><?php _e( 'Our Custom Fields extension can help make it easier to keep tabs on the information that is important to you.', 'wp-crm-system' ); ?></p>
				<p><?php _e( 'Pair that with our Zapier Connect extension and you will be able to send data to or from WP-CRM System to over 1,000 different apps. Automation like this can save you and your team time by not having to copy and paste data from one system to another. Instantly send a "welcome" email to contacts when they are tagged as a new customer. Or, send handwritten thank you notes after the completion of a successful project without having to pick up a pen!', 'wp-crm-system' ); ?></p>
			<h3><?php _e( 'Bill Clients And Provide Them With A Secure Online Portal', 'wp-crm-system' ); ?></h3>
				<p><?php _e( 'Easily bill clients for a maintenance plan or other services with our Client Area extension, or invoice them directly for one-off services with our Invoicing extension.', 'wp-crm-system' ); ?></p>
				<p><a href="https://www.wp-crm.com/downloads?utm_campaign=upgrade&utm_source=welcome-page-upgrade" class="button-primary"><?php _e( 'View All Extensions', 'wp-crm-system' ); ?></a><span class="dashicons dashicons-external wpcrm-dashicons"></span></p>
		</div>

		<div class="about-text wp-crm-welcome-content">
			<h2><?php _e( 'Get Started', 'wp-crm-system' ); ?></h2>
		</div>
		<div class="about-text wp-crm-welcome-content wp-crm-first wp-crm-one-half">
			<h3><?php _e( 'Create a new...', 'wp-crm-system' ); ?></h3>
			<ul class="wp-crm-welcome-create-links">
				<li><a href="post-new.php?post_type=wpcrm-contact"><span class="dashicons dashicons-id wpcrm-dashicons"></span><?php _e( 'Contact', 'wp-crm-system' ); ?></a></li>
				<li><a href="post-new.php?post_type=wpcrm-organization"><span class="dashicons dashicons-building wpcrm-dashicons"></span><?php _e( 'Organization', 'wp-crm-system' ); ?></a></li>
				<li><a href="post-new.php?post_type=wpcrm-project"><span class="dashicons dashicons-clipboard wpcrm-dashicons"></span><?php _e( 'Project', 'wp-crm-system' ); ?></a></li>
				<li><a href="post-new.php?post_type=wpcrm-task"><span class="dashicons dashicons-yes wpcrm-dashicons"></span><?php _e( 'Task', 'wp-crm-system' ); ?></a></li>
				<li><a href="post-new.php?post_type=wpcrm-opportunity"><span class="dashicons dashicons-phone wpcrm-dashicons"></span><?php _e( 'Opportunity', 'wp-crm-system' ); ?></a></li>
				<li><a href="post-new.php?post_type=wpcrm-campaign"><span class="dashicons dashicons-megaphone wpcrm-dashicons"></span><?php _e( 'Campaign', 'wp-crm-system' ); ?></a></li>
			</ul>
		</div>
		<div class="about-text wp-crm-welcome-content wp-crm-one-half">
			<h3><?php _e( 'View...', 'wp-crm-system' ); ?></h3>
			<ul class="wp-crm-welcome-create-links">
				<li><a href="admin.php?page=wpcrm-settings"><?php _e( 'Your Dashboard', 'wp-crm-system' ); ?></a></li>
				<li><a href="admin.php?page=wpcrm-reports"><?php _e( 'Reports', 'wp-crm-system' ); ?></a></li>
				<li><a href="admin.php?page=wpcrm-settings&tab=import"><?php _e( 'Import From Your Old CRM', 'wp-crm-system' ); ?></a></li>
				<li><a href="https://www.wp-crm.com/document/"><?php _e( 'Support Documentation', 'wp-crm-system' ); ?><span class="dashicons dashicons-external wpcrm-dashicons"></span></a></li>
			</ul>
		</div>
	</div>
</div>