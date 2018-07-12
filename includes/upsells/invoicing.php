<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
} ?>
<h2><?php _e( 'Invoicing', 'wp-crm-system' ); ?></h2>

<div class="wp-crm-one-third wp-crm-first">
	<p><strong><?php _e( 'The Invoicing add-on is included in our Professional package.', 'wp-crm-system' ); ?></strong></p>
	<p><?php _e( 'About the Invoicing add-on:', 'wp-crm-system' ); ?></p>
	<ul class="upsell-features">
		<li><?php _e( 'Easily bill clients for products and services.', 'wp-crm-system' ); ?></li>
		<li><?php _e( 'Accept payments by check or credit card via Stripe.', 'wp-crm-system' ); ?></li>
		<li><?php _e( 'Send invoices by email to your clients so they know when an invoice is avaialble.', 'wp-crm-system' ); ?></li>
	</ul>
	<p>
		<a href="https://www.wp-crm.com/checkout/?edd_action=add_to_cart&download_id=1306&edd_options[price_id]=1&utm_campaign=upgrade-professional-button&utm_source=upgrade-client-area-tab" class="button-primary">
			<?php _e( 'Upgrade to Professional with Invoicing today!', 'wp-crm-system' ); ?>
		</a>
		<span class="dashicons dashicons-external wpcrm-dashicons"></span>
	</p>
	<p>
		<?php _e( 'Custom Fields, import/export extensions, invoice clients, and connect to 3rd party apps for <strong>unlimited sites</strong>', 'wp-crm-system' ); ?>	<em><?php _e( '($199 billed annually - cancel any time)', 'wp-crm-system' ); ?></em>
	</p>
</div>
<div class="wp-crm-two-thirds">
	<div><strong><?php _e( 'Screenshots', 'wp-crm-system' ); ?></strong> (<?php _e( 'click to enlarge', 'wp-crm-system' ); ?>)</div>
	<div class="screenshot-group">
		<a class="lightbox" href="#invoice-example">
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/invoice-example.png"/>
		</a>
		<div class="screenshot-text"><?php _e( 'Have customers view invoices directly from your website.', 'wp-crm-system' ); ?></div>
	</div>
	<div class="screenshot-group">
		<a class="lightbox" href="#invoice-pay-by-credit-card">
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/invoice-pay-by-credit-card.png"/>
		</a>
		<div class="screenshot-text"><?php _e( 'Accept credit card payments on your website through Stripe', 'wp-crm-system' ); ?></div>
	</div>
</div>

<div class="lightbox-target" id="invoice-example">
	<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/invoice-example.png"/>
	<a class="lightbox-close" href="#"></a>
</div>
<div class="lightbox-target" id="invoice-pay-by-credit-card">
	<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/invoice-pay-by-credit-card.png"/>
	<a class="lightbox-close" href="#"></a>
</div>