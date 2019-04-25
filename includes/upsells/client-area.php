<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
} ?>
<h2><?php _e( 'Client Area', 'wp-crm-system' ); ?></h2>

<div class="wp-crm-one-third wp-crm-first">
	<p><?php _e( 'About the Client Area add-on:', 'wp-crm-system' ); ?></p>
	<ul class="upsell-features">
		<li><?php _e( 'Easily accept credit card payments from clients for maintenance plans, retainers, or one-off payments via Stripe', 'wp-crm-system' ); ?></li>
		<li><?php _e( 'Let your customers view the status of their Projects, Tasks, Campaigns, and Invoices (Requires WP-CRM System Invoicing).', 'wp-crm-system' ); ?></li>
		<li><?php _e( 'Password protected area that only your clients can view.', 'wp-crm-system' ); ?></li>
	</ul>
	<p>
		<a href="https://www.wp-crm.com/downloads/client-area/?utm_campaign=upgrade-wp-crm-system&utm_source=upgrade-client-area-tab" class="button-primary">
			<?php _e( 'Upgrade to WP-CRM System with Client Area today!', 'wp-crm-system' ); ?>
		</a>
		<span class="dashicons dashicons-external wpcrm-dashicons"></span>
	</p>
</div>
<div class="wp-crm-two-thirds">
	<div><strong><?php _e( 'Screenshots', 'wp-crm-system' ); ?></strong> (<?php _e( 'click to enlarge', 'wp-crm-system' ); ?>)</div>
	<div class="screenshot-group">
		<a class="lightbox" href="#shortcodes">
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/client-area-shortcodes.png"/>
		</a>
		<div class="screenshot-text"><?php _e( 'Shortcodes used in Client Area', 'wp-crm-system' ); ?></div>
	</div>
	<div class="screenshot-group">
		<a class="lightbox" href="#create-plan">
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/create-plan.png"/>
		</a>
		<div class="screenshot-text"><?php _e( 'Admin create a billing plan through Stripe', 'wp-crm-system' ); ?></div>
	</div>
	<div class="screenshot-group">
		<a class="lightbox" href="#payment-form">
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/payment-form.png"/>
		</a>
		<div class="screenshot-text"><?php _e( 'Client side payment form', 'wp-crm-system' ); ?></div>
	</div>
	<div class="screenshot-group">
		<a class="lightbox" href="#project-task-list">
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/project-task-list.png"/>
		</a>
		<div class="screenshot-text"><?php _e( 'Client view of projects and tasks', 'wp-crm-system' ); ?></div>
	</div>
</div>

<div class="lightbox-target" id="shortcodes">
	<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/client-area-shortcodes.png"/>
	<a class="lightbox-close" href="#"></a>
</div>
<div class="lightbox-target" id="create-plan">
	<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/create-plan.png"/>
	<a class="lightbox-close" href="#"></a>
</div>
<div class="lightbox-target" id="payment-form">
	<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/payment-form.png"/>
	<a class="lightbox-close" href="#"></a>
</div>
<div class="lightbox-target" id="project-task-list">
	<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/project-task-list.png"/>
	<a class="lightbox-close" href="#"></a>
</div>