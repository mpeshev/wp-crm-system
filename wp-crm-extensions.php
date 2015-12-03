<?php defined( 'ABSPATH' ) OR exit; ?>
<?php 
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'overview';
?>
<style>
.wpcrm-extension{ 
	float: left;
    margin: 0 15px 15px 0;
    background: #fff;
    border: 1px solid #ccc;
    width: 300px;
    padding: 14px;
    height: 315px;
    position: relative;
}
</style>
<h2 class="nav-tab-wrapper">
	<a class="nav-tab <?php echo $active_tab == 'overview' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-extensions&tab=overview"><?php _e('Overview', 'wp-crm-system') ?></a>
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
				'title'			=>	'Contact From User',
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'no',
				'notifications'	=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/contact-from-user/',
				'img'			=>	'/contact-from-user-300x145.png',
				'desc'			=>	'Quickly create new contacts in WP-CRM from existing users on your WordPress site.',
				'class'			=>	'WPCRM_CONTACT_FROM_USER',
			),
			array(
				'title'			=>	'Gravity Forms Connect',
				'overview'		=>	'yes',
				'contact-forms'	=>	'yes',
				'import'		=>	'no',
				'notifications'	=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/gravity-forms-connect/',
				'img'			=>	'/gravity-forms-connect-300x145.png',
				'desc'			=>	'Automatically create new contacts from Gravity Form submissions.',
				'class'			=>	'WPCRM_GRAVITY_FORMS_CONNECT',
			),
			array(
				'title'			=>	'Ninja Form Connect',
				'overview'		=>	'yes',
				'contact-forms'	=>	'yes',
				'import'		=>	'no',
				'notifications'	=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/ninja-form-connect/',
				'img'			=>	'/ninja-forms-connect-300x145.png',
				'desc'			=>	'Automatically create new contacts from Ninja Form submissions.',
				'class'			=>	'WPCRM_NINJA_FORMS_CONNECT',
			),
			array(
				'title'			=>	'Import Contacts',
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/import-contacts/',
				'img'			=>	'/import-contacts-300x145.png',
				'desc'			=>	'Import your contacts from another CRM with an easy to use CSV importer.',
				'class'			=>	'WPCRM_IMPORT_CONTACTS',
			),
			array(
				'title'			=>	'Import Opportunities',
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/import-opportunities/',
				'img'			=>	'/import-opportunities-300x145.png',
				'desc'			=>	'Import your opportunities from another CRM with an easy to use CSV importer.',
				'class'			=>	'WPCRM_IMPORT_OPPORTUNITIES',
			),
			array(
				'title'			=>	'Import Organizations',
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/import-organizations/',
				'img'			=>	'/import-organizations-300x145.png',
				'desc'			=>	'Import your organizations from another CRM with an easy to use CSV importer.',
				'class'			=>	'WPCRM_IMPORT_ORGANIZATIONS',
			),
			array(
				'title'			=>	'Import Projects',
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/import-projects/',
				'img'			=>	'/import-projects-300x145.png',
				'desc'			=>	'Import your projects from another CRM with an easy to use CSV importer.',
				'class'			=>	'WPCRM_IMPORT_PROJECTS',
			),
			array(
				'title'			=>	'Import Tasks',
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'yes',
				'notifications'	=>	'no',
				'url'			=>	'https://www.wp-crm.com/downloads/import-tasks/',
				'img'			=>	'/import-tasks-300x145.png',
				'desc'			=>	'Import your tasks from another CRM with an easy to use CSV importer.',
				'class'			=>	'WPCRM_IMPORT_TASKS',
			),
			array(
				'title'			=>	'Slack Notifications',
				'overview'		=>	'yes',
				'contact-forms'	=>	'no',
				'import'		=>	'no',
				'notifications'	=>	'yes',
				'url'			=>	'https://www.wp-crm.com/downloads/slack-notifications/',
				'img'			=>	'/slack-notifications-300x145.png',
				'desc'			=>	'Send notifications from WP-CRM to a Slack channel.',
				'class'			=>	'WPCRM_SLACK_NOTIFICATIONS',
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
	} // End Class
} // End if class exists statement
// Instantiate the class
if ( class_exists('wpCRMSystemExtensions') ) {
    $wpCRMSystemExtensions_var = new wpCRMSystemExtensions();
	if ($active_tab == 'overview') {
		$wpCRMSystemExtensions_var->wpcrm_extensions_overview();
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