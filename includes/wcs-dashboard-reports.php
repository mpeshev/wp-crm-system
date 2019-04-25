<?php defined( 'ABSPATH' ) OR exit;

add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_calendar', 1 );
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_contacts_box', 2 );
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_projects_box', 3 );
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_tasks_box', 4 );
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_opportunities_box', 5 );

function wpcrm_system_dashboard_calendar(){ ?>
	<div class="wpcrm-dashboard-calendar">
		<?php
			if ( isset( $_GET ) && isset( $_GET['wpcrm-cal-month'] ) && isset( $_GET['wpcrm-cal-year'] ) ){
				$month 	= absint( $_GET['wpcrm-cal-month'] );
				$year 	= absint( $_GET['wpcrm-cal-year'] );
			} else {
				$month 	= date( 'n' );
				$year 	= date( 'Y' );
			}
			echo wpcrm_system_display_calendar( 'all', $month, $year );
		?>
	</div>
	<?php
}


function wpcrm_system_dashboard_contacts_box(){ ?>
	<div class="wpcrm-dashboard">
		<h3><?php _e( 'Address Book', 'wp-crm-system' ); ?></h3>
		<?php
		$user = wp_get_current_user();
		if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
			$args = array(
				'post_type'			=>	'wpcrm-contact',
				'posts_per_page'	=> -1
			);
			$posts = get_posts($args);
			if ($posts) { ?>
				<form id="address-book-form" action="" method="POST">
					<select id="dashboard-address-book" class="wp-crm-system-searchable" name="address_book_entry">
						<option value=""><?php _e( 'Select a Contact', 'wp-crm-system' ); ?></option>
						<?php
						foreach($posts as $post) {
							echo '<option value="' . $post->ID . '">' . get_the_title( $post->ID ) . '</option>';
						} ?>
					</select>
					<img src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting" id="address_book_loading" style="display:none;"/>
				</form>
				<div id="address_book_results"></div>
				<?php
			} else {
				_e( 'No contacts to display', 'wp-crm-system' );
			}
		} ?>
	</div>
	<?php
}
function wpcrm_system_dashboard_projects_box() { ?>
	<div class="wpcrm-dashboard">
		<h3><?php _e( 'Projects', 'wp-crm-system' ); ?></h3>
		<?php
		$user = wp_get_current_user();
		if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
			$args = array(
				'post_type'			=>	'wpcrm-project',
				'posts_per_page'	=>	-1
			);
			$posts = get_posts($args);
			if ($posts) { ?>
				<form id="project-list-form" action="" method="POST">
					<select id="dashboard-project-list" class="wp-crm-system-searchable" name="project_list_entry">
						<option value=""><?php _e( 'Select a Project', 'wp-crm-system' ); ?></option>
						<?php
						foreach($posts as $post) {
							echo '<option value="' . $post->ID . '">' . get_the_title( $post->ID ) . '</option>';
						} ?>
					</select>
					<img src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting" id="project_list_loading" style="display:none;"/>
				</form>
				<div id="project_list_results"></div>
				<?php
			} else {
				_e( 'No projects to display', 'wp-crm-system' );
			}
		} ?>
	</div>
	<?php
}

function wpcrm_system_dashboard_tasks_box() { ?>
	<div class="wpcrm-dashboard">
		<h3><?php _e( 'Tasks', 'wp-crm-system' ); ?></h3>
		<?php
		$user = wp_get_current_user();
		if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
			$args = array(
				'post_type'			=>	'wpcrm-task',
				'posts_per_page'	=>	-1
			);
			$posts = get_posts($args);
			if ($posts) { ?>
				<form id="task-list-form" action="" method="POST">
					<select id="dashboard-task-list" class="wp-crm-system-searchable" name="task_list_entry">
						<option value=""><?php _e( 'Select a Task', 'wp-crm-system' ); ?></option>
						<?php
						foreach($posts as $post) {
							echo '<option value="' . $post->ID . '">' . get_the_title( $post->ID ) . '</option>';
						} ?>
					</select>
					<img src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting" id="task_list_loading" style="display:none;"/>
				</form>
				<div id="task_list_results"></div>
				<?php
			} else {
				_e( 'No tasks to display', 'wp-crm-system' );
			}
		} ?>
	</div>
	<?php
}

function wpcrm_system_dashboard_opportunities_box() { ?>
	<div class="wpcrm-dashboard">
		<h3><?php _e( 'Opportunities', 'wp-crm-system' ); ?></h3>
		<?php
		$user = wp_get_current_user();
		if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
			$args = array(
				'post_type'			=>	'wpcrm-opportunity',
				'posts_per_page'	=>	-1
			);
			$posts = get_posts($args);
			if ($posts) { ?>
				<form id="opportunity-list-form" action="" method="POST">
					<select id="dashboard-opportunity-list" class="wp-crm-system-searchable" name="opportunity_list_entry">
						<option value=""><?php _e( 'Select an Opportunity', 'wp-crm-system' ); ?></option>
						<?php
						foreach($posts as $post) {
							echo '<option value="' . $post->ID . '">' . get_the_title( $post->ID ) . '</option>';
						} ?>
					</select>
					<img src="<?php echo admin_url('/images/wpspin_light.gif'); ?>" class="waiting" id="opportunity_list_loading" style="display:none;"/>
				</form>
				<div id="opportunity_list_results"></div>
				<?php
			} else {
				_e( 'No opportunities to display', 'wp-crm-system' );
			}
		} ?>
	</div>
	<?php
}

function wpcrm_system_show_extensions() {
	global $plugins_active;
	$plugins_active = '';
	$plugin_base = 'wp-crm-system-';
	$active_count = 0;

	$plugins = array();
	$list = '';

	if(has_filter('wpcrm_system_dashboard_extensions')) {
		$plugins = apply_filters('wpcrm_system_dashboard_extensions', $plugins);
		ksort( $plugins, SORT_STRING );
	}

	foreach($plugins as $plugin => $status) :
		if( is_plugin_active( $plugin_base . $plugin.'/'.$plugin_base . $plugin.'.php' ) ) {
			$plugins_active = 'yes';
			$active_count++;
			$plugin_nicename = ucwords( str_replace( '-', ' ', $plugin ) );
			$plugin_status = get_option( $status );
			if ( $plugin_status !== false && $plugin_status == 'valid' ) {
				$plugin_display = '<span style="color:green;" id="' . $plugin . '">' . __( 'Active', 'wp-crm-system' ) . '</span>';
			} else {
				$plugin_display = '<span style="color:red;">' . __( 'Inactive', 'wp-crm-system' ) . '</span>';
			}
			$list .= '<div class="wp-crm-one-half wp-crm-first">' . $plugin_nicename . '</div><div class="wp-crm-one-half">' . $plugin_display . '</div>';
		}
	endforeach;

	return $list;
}

function wpcrm_system_dashboard_extensions_box() { ?>
	<div class="wpcrm-dashboard">
		<h2><?php _e('Extensions', 'wp-crm-system'); ?></h2>
		<?php
			echo wpcrm_system_show_extensions();
		?>
		<div class="wp-crm-first">
		<?php
			$url = admin_url( 'admin.php?page=wpcrm-extensions' );
			$link = sprintf( wp_kses( __( 'Take a look at our <a href="%s">extensions</a> to see how you can get more out of WP-CRM System.', 'wp-crm-system' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
			echo '<hr /><strong>' . $link . '</strong>';
		?>
		</div>
	</div>
<?php }