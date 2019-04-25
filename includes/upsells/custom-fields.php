<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
} ?>
<h2><?php _e( 'Custom Fields', 'wp-crm-system' ); ?></h2>

<div class="wp-crm-one-third wp-crm-first">
	<p><strong><?php _e( 'The Custom Fields add-on is included in our Personal package.', 'wp-crm-system' ); ?></strong></p>
	<p><?php _e( 'About the Custom Fields add-on:', 'wp-crm-system' ); ?></p>
	<ul class="upsell-features">
		<li><?php _e( 'Create fields that are specific to your business needs.', 'wp-crm-system' ); ?></li>
		<li><?php _e( '13 types of fields including: textbox, text area, file uploader, WYSIWYG text editor, checkboxes, multiselect field, email, url, number, datepicker, select/dropdown menu, as well as repeatable textbox, textarea, file uploader, and datepicker fields.', 'wp-crm-system' ); ?></li>
		<li><?php _e( 'Custom fields can be imported while using one of our Import, Zapier Connect, Ninja Forms Connect, or Gravity Forms Connect add-ons.', 'wp-crm-system' ); ?></li>
	</ul>
	<p><?php _e( 'Store nearly any type of information with Custom Fields. Here are a few examples of what you can do with Custom Fields:', 'wp-crm-system' ); ?></p>
	<ul class="upsell-features">
		<li><?php _e( 'Upload project files so that they are easily accessible right from the project edit screen. All uploads are stored in a secure location on your server so only the people who should access the files can access them.', 'wp-crm-system' ); ?></li>
		<li><?php _e( 'Store links to social media profiles, to easily see what your contacts are posting about online.', 'wp-crm-system' ); ?></li>
		<li><?php _e( 'Record notes from each call with a client. Everything is in one place and is easily accessible in the future.', 'wp-crm-system' ); ?></li>
		<li><?php _e( 'Save important dates and never forget to send a birthday or anniversary card again!', 'wp-crm-system' ); ?></li>
	</ul>
	<p>
		<a href="https://www.wp-crm.com/downloads/custom-fields/?utm_campaign=upgrade-wp-crm-system&utm_source=upgrade-custom-fields-tab" class="button-primary">
			<?php _e( 'Upgrade WP-CRM System with Custom Fields today!', 'wp-crm-system' ); ?>
		</a>
		<span class="dashicons dashicons-external wpcrm-dashicons"></span>
	</p>
</div>
<div class="wp-crm-two-thirds">
	<div><strong><?php _e( 'Screenshots', 'wp-crm-system' ); ?></strong> (<?php _e( 'click to enlarge', 'wp-crm-system' ); ?>)</div>
	<div class="screenshot-group">
		<a class="lightbox" href="#custom-field-settings">
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/custom-field-settings.png"/>
		</a>
		<div class="screenshot-text"><?php _e( 'Custom Fields Settings', 'wp-crm-system' ); ?></div>
	</div>
	<div class="screenshot-group">
		<a class="lightbox" href="#custom-fields-in-record">
			<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/custom-fields-in-record.png"/>
		</a>
		<div class="screenshot-text"><?php _e( 'Displaying custom fields in Contact record', 'wp-crm-system' ); ?></div>
	</div>
</div>

<div class="lightbox-target" id="custom-field-settings">
	<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/custom-field-settings.png"/>
	<a class="lightbox-close" href="#"></a>
</div>
<div class="lightbox-target" id="custom-fields-in-record">
	<img src="<?php echo WP_CRM_SYSTEM_PLUGIN_URL; ?>/includes/upsells/screenshots/custom-fields-in-record.png"/>
	<a class="lightbox-close" href="#"></a>
</div>