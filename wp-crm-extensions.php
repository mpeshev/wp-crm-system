<?php defined( 'ABSPATH' ) OR exit;
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'overview';
?>
<h2 class="nav-tab-wrapper">
	<a class="nav-tab <?php echo $active_tab == 'overview' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-extensions&tab=overview"><?php _e('Overview', 'wp-crm-system') ?></a>
	<a class="nav-tab <?php echo $active_tab == 'documents' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-extensions&tab=documents"><?php _e('Documents', 'wp-crm-system') ?></a>
	<a class="nav-tab <?php echo $active_tab == 'contact-forms' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-extensions&tab=contact-forms"><?php _e('Contact Forms', 'wp-crm-system') ?></a>
	<a class="nav-tab <?php echo $active_tab == 'import' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-extensions&tab=import"><?php _e('Importers', 'wp-crm-system') ?></a>
	<a class="nav-tab <?php echo $active_tab == 'notifications' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-extensions&tab=notifications"><?php _e('Notifications', 'wp-crm-system') ?></a>
</h2>
<?php
if ( !class_exists('wpCRMSystemExtensions') ) {
    class wpCRMSystemExtensions {

		/**
        * @var  array  $extensionFields Defines the extension fields available
        */
		var $extensionFields = array(
			array(
				'title'			=>	WPCRM_EXTENSION_MAILCHIMP_SYNC,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'no',
				'notifications'	=>	'yes',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/mailchimp-sync/',
				'img'			=>	'/mailchimp-sync-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_MAILCHIMP_SYNC,
				'class'			=>	'WPCRM_MAILCHIMP_SYNC',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_INVOICING,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'no',
				'notifications'	=>	'no',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/invoicing/',
				'img'			=>	'/invoicing-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_INVOICING,
				'class'			=>	'WPCRM_INVOICING',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_CUSTOM_FIELDS,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/custom-fields/',
				'img'			=>	'/custom-fields-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_CUSTOM_FIELDS,
				'class'			=>	'WPCRM_CUSTOM_FIELDS',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_ZENDESK,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'no',
				'notifications'	=>	'yes',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/zendesk-connect/',
				'img'			=>	'/zendesk-connect-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_ZENDESK,
				'class'			=>	'WPCRM_ZENDESK',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_DROPBOX,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'no',
				'notifications'	=>	'no',
				'documents'		=>	'yes',
				'url'			=>	'https://www.wp-crm.com/downloads/dropbox-connect/',
				'img'			=>	'/dropbox-connect-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_DROPBOX,
				'class'			=>	'WPCRM_DROPBOX_CONNECT',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_SLACK,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'no',
				'notifications'	=>	'yes',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/slack-notifications/',
				'img'			=>	'/slack-notifications-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_SLACK,
				'class'			=>	'WPCRM_SLACK_NOTIFICATIONS',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_CONTACT_USER,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'no',
				'notifications'	=>	'no',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/contact-from-user/',
				'img'			=>	'/contact-from-user-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_CONTACT_USER,
				'class'			=>	'WPCRM_CONTACT_FROM_USER',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_GRAVITY_FORMS,
				'overview'		=>	'yes',
				'contact-forms'	=>	'yes',
				'import'		=>	'no',
				'notifications'	=>	'no',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/gravity-forms-connect/',
				'img'			=>	'/gravity-forms-connect-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_GRAVITY_FORMS,
				'class'			=>	'WPCRM_GRAVITY_FORMS_CONNECT',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_NINJA_FORMS,
				'overview'		=>	'yes',
				'contact-forms'	=>	'yes',
				'import'		=>	'no',
				'notifications'	=>	'no',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/ninja-form-connect/',
				'img'			=>	'/ninja-forms-connect-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_NINJA_FORMS,
				'class'			=>	'WPCRM_NINJA_FORMS_CONNECT',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_IMPORT_CONTACTS,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/import-contacts/',
				'img'			=>	'/import-contacts-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_IMPORT_CONTACTS,
				'class'			=>	'WPCRM_IMPORT_CONTACTS',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_IMPORT_OPPORTUNITIES,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/import-opportunities/',
				'img'			=>	'/import-opportunities-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_IMPORT_OPPORTUNITIES,
				'class'			=>	'WPCRM_IMPORT_OPPORTUNITIES',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_IMPORT_ORGANIZATIONS,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/import-organizations/',
				'img'			=>	'/import-organizations-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_IMPORT_ORGANIZATIONS,
				'class'			=>	'WPCRM_IMPORT_ORGANIZATIONS',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_IMPORT_PROJECTS,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/import-projects/',
				'img'			=>	'/import-projects-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_IMPORT_PROJECTS,
				'class'			=>	'WPCRM_IMPORT_PROJECTS',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_IMPORT_CAMPAIGNS,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/import-campaigns/',
				'img'			=>	'/import-campaigns-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_IMPORT_CAMPAIGNS,
				'class'			=>	'WPCRM_IMPORT_CAMPAIGNS',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_IMPORT_TASKS,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/import-tasks/',
				'img'			=>	'/import-tasks-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_IMPORT_TASKS,
				'class'			=>	'WPCRM_IMPORT_TASKS',
			),
			array(
				'title'			=>	WPCRM_EXTENSION_EMAIL_NOTIFICATIONS,
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'no',
				'notifications'	=>	'yes',
				'documents'		=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/email-notifications/',
				'img'			=>	'/email-notifications-300x145.png',
				'desc'			=>	WPCRM_EXTENSION_DESCRIPTION_EMAIL_NOTIFICATIONS,
				'class'			=>	'WPCRM_EMAIL_NOTIFICATIONS',
			),
		);
		function wpcrm_extensions_overview() {  ?>
			<div class="wrap">
				<div>
					<h2><?php _e('WP CRM System Extensions', 'wp-crm-system'); ?></h2>
					<p><?php _e('These extensions add features to your WP-CRM System', 'wp-crm-system'); ?></p>
					<?php foreach($this->extensionFields as $extensionField) {
						if ($extensionField['overview'] == 'yes') { ?>
							<div class="wpcrm-extension">
								<h3 class="wpcrm-extension-title"><?php echo $extensionField['title']; ?></h3>
								<a href="<?php echo $extensionField['url']; ?>"><img width="300px" height="145px" src="<?php echo plugins_url('includes/images',__FILE__) . $extensionField['img']; ?>" alt="<?php echo $extensionField['title']; ?>" /></a>
								<p><?php echo $extensionField['desc']; ?></p>
								<?php if(defined($extensionField['class'])) { ?>
									<a href="" class="button-secondary disabled"><?php _e('Extension Installed','wp-crm-system'); ?></a>
								<?php } else { ?>
									<a href="<?php echo $extensionField['url']; ?>" class="button-secondary"><?php _e('Get This Extension','wp-crm-system'); ?></a>
								<?php } ?>
							</div>
					<?php }
					} ?>
				</div>
			</div>
		<?php }
		function wpcrm_extensions_contact() {  ?>
			<div class="wrap">
				<div>
					<h2><?php _e('WP CRM System Extensions', 'wp-crm-system'); ?></h2>
					<p><?php _e('These extensions add features to your WP-CRM System', 'wp-crm-system'); ?></p>
					<?php foreach($this->extensionFields as $extensionField) {
						if ($extensionField['contact-forms'] == 'yes') { ?>
							<div class="wpcrm-extension">
								<h3 class="wpcrm-extension-title"><?php echo $extensionField['title']; ?></h3>
								<a href="<?php echo $extensionField['url']; ?>"><img width="300px" height="145px" src="<?php echo plugins_url('includes/images',__FILE__) .$extensionField['img']; ?>" alt="<?php echo $extensionField['title']; ?>" /></a>
								<p><?php echo $extensionField['desc']; ?></p>
								<?php if(defined($extensionField['class'])) { ?>
									<a href="" class="button-secondary disabled"><?php _e('Extension Installed','wp-crm-system'); ?></a>
								<?php } else { ?>
									<a href="<?php echo $extensionField['url']; ?>" class="button-secondary"><?php _e('Get This Extension','wp-crm-system'); ?></a>
								<?php } ?>
							</div>
					<?php }
					} ?>
				</div>
			</div>
		<?php }
		function wpcrm_extensions_import() {  ?>
			<div class="wrap">
				<div>
					<h2><?php _e('WP CRM System Extensions', 'wp-crm-system'); ?></h2>
					<p><?php _e('These extensions add features to your WP-CRM System', 'wp-crm-system'); ?></p>
					<?php foreach($this->extensionFields as $extensionField) {
						if ($extensionField['import'] == 'yes') { ?>
							<div class="wpcrm-extension">
								<h3 class="wpcrm-extension-title"><?php echo $extensionField['title']; ?></h3>
								<a href="<?php echo $extensionField['url']; ?>"><img width="300px" height="145px" src="<?php echo plugins_url('includes/images',__FILE__) .$extensionField['img']; ?>" alt="<?php echo $extensionField['title']; ?>" /></a>
								<p><?php echo $extensionField['desc']; ?></p>
								<?php if(defined($extensionField['class'])) { ?>
									<a href="" class="button-secondary disabled"><?php _e('Extension Installed','wp-crm-system'); ?></a>
								<?php } else { ?>
									<a href="<?php echo $extensionField['url']; ?>" class="button-secondary"><?php _e('Get This Extension','wp-crm-system'); ?></a>
								<?php } ?>
							</div>
					<?php }
					} ?>
				</div>
			</div>
		<?php }
		function wpcrm_extensions_notifications() {  ?>
			<div class="wrap">
				<div>
					<h2><?php _e('WP CRM System Extensions', 'wp-crm-system'); ?></h2>
					<p><?php _e('These extensions add features to your WP-CRM System', 'wp-crm-system'); ?></p>
					<?php foreach($this->extensionFields as $extensionField) {
						if ($extensionField['notifications'] == 'yes') { ?>
							<div class="wpcrm-extension">
								<h3 class="wpcrm-extension-title"><?php echo $extensionField['title']; ?></h3>
								<a href="<?php echo $extensionField['url']; ?>"><img width="300px" height="145px" src="<?php echo plugins_url('includes/images',__FILE__) .$extensionField['img']; ?>" alt="<?php echo $extensionField['title']; ?>" /></a>
								<p><?php echo $extensionField['desc']; ?></p>
								<?php if(defined($extensionField['class'])) { ?>
									<a href="" class="button-secondary disabled"><?php _e('Extension Installed','wp-crm-system'); ?></a>
								<?php } else { ?>
									<a href="<?php echo $extensionField['url']; ?>" class="button-secondary"><?php _e('Get This Extension','wp-crm-system'); ?></a>
								<?php } ?>
							</div>
					<?php }
					} ?>
				</div>
			</div>
		<?php }
		function wpcrm_extensions_documents() {  ?>
			<div class="wrap">
				<div>
					<h2><?php _e('WP CRM System Extensions', 'wp-crm-system'); ?></h2>
					<p><?php _e('These extensions add features to your WP-CRM System', 'wp-crm-system'); ?></p>
					<?php foreach($this->extensionFields as $extensionField) {
						if ($extensionField['documents'] == 'yes') { ?>
							<div class="wpcrm-extension">
								<h3 class="wpcrm-extension-title"><?php echo $extensionField['title']; ?></h3>
								<a href="<?php echo $extensionField['url']; ?>"><img width="300px" height="145px" src="<?php echo plugins_url('includes/images',__FILE__) .$extensionField['img']; ?>" alt="<?php echo $extensionField['title']; ?>" /></a>
								<p><?php echo $extensionField['desc']; ?></p>
								<?php if(defined($extensionField['class'])) { ?>
									<a href="" class="button-secondary disabled"><?php _e('Extension Installed','wp-crm-system'); ?></a>
								<?php } else { ?>
									<a href="<?php echo $extensionField['url']; ?>" class="button-secondary"><?php _e('Get This Extension','wp-crm-system'); ?></a>
								<?php } ?>
							</div>
					<?php }
					} ?>
				</div>
			</div>
		<?php }
	} // End Class
} // End if class exists statement
// Instantiate the class
if ( class_exists('wpCRMSystemExtensions') ) {
    $wpCRMSystemExtensions_var = new wpCRMSystemExtensions();
	if ($active_tab == 'overview') {
		$wpCRMSystemExtensions_var->wpcrm_extensions_overview();
	}
	if ($active_tab == 'documents') {
		$wpCRMSystemExtensions_var->wpcrm_extensions_documents();
	}
	if ($active_tab == 'contact-forms') {
		$wpCRMSystemExtensions_var->wpcrm_extensions_contact();
	}
	if ($active_tab == 'import') {
		$wpCRMSystemExtensions_var->wpcrm_extensions_import();
	}
	if ($active_tab == 'notifications') {
		$wpCRMSystemExtensions_var->wpcrm_extensions_notifications();
	}
}
