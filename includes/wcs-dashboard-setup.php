<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-dashboard-address-book.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-dashboard-project-list.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-dashboard-task-list.php' );
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-dashboard-opportunity-list.php' );
// Add Default Settings
function wpcrm_system_default_setting_tab() {
	global $wpcrm_active_tab; ?>
	<a class="nav-tab <?php echo $wpcrm_active_tab == 'dashboard' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=dashboard"><?php _e('Dashboard', 'wp-crm-system') ?></a>
<?php }
add_action( 'wpcrm_system_settings_tab', 'wpcrm_system_default_setting_tab', 1 );



function wpcrm_dashboard_settings_content() {
	global $wpcrm_active_tab;
	if ($wpcrm_active_tab == 'dashboard') { ?>
		<h2><?php _e( 'WP-CRM System Dashboard', 'wp-crm-system' ); ?></h2>
		<!-- Add New... Box -->
		<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
		<?php
			$posttypes = array(
				'' 						=>	__( 'Add New...', 'wp-crm-system' ),
				'wpcrm-campaign' 		=>	__( 'Campaign', 'wp-crm-system' ),
				'wpcrm-contact' 		=>	__( 'Contact', 'wp-crm-system' ),
				'wpcrm-invoice' 		=>	__( 'Invoice', 'wp-crm-system' ),
				'wpcrm-opportunity'		=>	__( 'Opportunity', 'wp-crm-system' ),
				'wpcrm-organization'	=>	__( 'Organization', 'wp-crm-system' ),
				'wpcrm-project' 		=>	__( 'Project', 'wp-crm-system' ),
				'wpcrm-task' 			=>	__( 'Task', 'wp-crm-system' ),
			);
			foreach ( $posttypes as $key => $value ){
				if ( 'wpcrm-invoice' == $key && defined( 'WPCRM_INVOICING' ) ) { ?>
					<option value="post-new.php?post_type=<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php } else { ?>
					<option value="post-new.php?post_type=<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php }
			} ?>
		</select>
		<!-- Jump to Category Box -->
		<select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
		<?php
			$categories = array(
				'' 					=> __( 'Go to Category...', 'wp-crm-system' ),
				'organization-type'	=> __( 'Organization Categories','wp-crm-system' ),
				'contact-type'		=> __( 'Contact Categories','wp-crm-system' ),
				'opportunity-type'	=> __( 'Opportunity Categories','wp-crm-system' ),
				'project-type'		=> __( 'Project Categories','wp-crm-system' ),
				'task-type'			=> __( 'Task Categories','wp-crm-system' ),
				'campaign-type'		=> __( 'Campaign Categories','wp-crm-system' )
			);
			$categories = apply_filters( 'wpcrm_system_dashboard_categories_menu', $categories );
			foreach ($categories as $key => $value) {
				$post_type = 'wpcrm-' . str_replace('-type', '', $key);
				?>
				<option value="edit-tags.php?taxonomy=<?php echo $key; ?>&amp;post_type=<?php echo $post_type; ?>"><?php echo $value; ?></option>
			<?php } ?>
		</select>
		<?php
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-dashboard-reports.php' );
		include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-dashboard.php' );
	}
}
add_action( 'wpcrm_system_settings_content', 'wpcrm_dashboard_settings_content' );