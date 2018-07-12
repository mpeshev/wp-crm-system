<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
} ?>
<h2><?php _e( 'Import Your Data', 'wp-crm-system' ); ?></h2>

<div class="wp-crm-one-third wp-crm-first">
	<p><strong><?php _e( 'The Import add-ons are included in our Personal package.', 'wp-crm-system' ); ?></strong></p>
	<p><?php _e( 'About the Import add-ons:', 'wp-crm-system' ); ?></p>
	<ul class="upsell-features">
		<li><?php _e( 'Import or Export data from Contacts, Organizations, Projects, Tasks, Opportunities, or Campaigns.', 'wp-crm-system' ); ?></li>
		<li><?php _e( 'Import from an existing CRM or spreadsheet with an easy to complete CSV import template..', 'wp-crm-system' ); ?></li>
		<li><?php _e( 'Custom fields can be imported the Custom Fields add-on.', 'wp-crm-system' ); ?></li>
	</ul>
	<p>
		<a href="https://www.wp-crm.com/checkout/?edd_action=add_to_cart&download_id=1304&edd_options[price_id]=1&utm_campaign=upgrade-personal-button&utm_source=upgrade-importers-tab" class="button-primary">
			<?php _e( 'Upgrade to Personal to import and export your CRM data today!', 'wp-crm-system' ); ?>
		</a>
		<span class="dashicons dashicons-external wpcrm-dashicons"></span>
	</p>
	<p>
		<?php _e( 'Custom Fields plus import/export extensions for <strong>one site</strong>', 'wp-crm-system' ); ?>
	</p>
	<p>
		<em><?php _e( '($49 billed annually - cancel any time)', 'wp-crm-system' ); ?></em>
	</p>
</div>
<div class="wp-crm-two-thirds">
	<div><strong><?php _e( 'Screenshots', 'wp-crm-system' ); ?></strong> (<?php _e( 'click to enlarge', 'wp-crm-system' ); ?>)</div>
	<div class="screenshot-group">
		<a class="lightbox" href="#import-csv-example">
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/import-csv-example.png"/>
		</a>
		<div class="screenshot-text"><?php _e( 'Easily import records from a CSV file', 'wp-crm-system' ); ?></div>
	</div>
	<div class="screenshot-group">
		<a class="lightbox" href="#import-settings">
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/import-settings.png"/>
		</a>
		<div class="screenshot-text"><?php _e( 'Import or export all types of records', 'wp-crm-system' ); ?></div>
	</div>
</div>

<div class="lightbox-target" id="import-csv-example">
	<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/import-csv-example.png"/>
	<a class="lightbox-close" href="#"></a>
</div>
<div class="lightbox-target" id="import-settings">
	<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/import-settings.png"/>
	<a class="lightbox-close" href="#"></a>
</div>