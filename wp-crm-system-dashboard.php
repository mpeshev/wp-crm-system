<?php defined( 'ABSPATH' ) OR exit;
function wp_crm_user_projects() {
	global $wpdb;
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	$meta_key1 = $prefix . 'project-assigned';
	$meta_key2 = $prefix . 'project-status';
	$meta_key2_value = 'complete';
	$project_report = '';
	$projects = '';
	$wpcrm_user = '';
	$user = wp_get_current_user();
	$roles = explode(",", get_option('wpcrm_system_select_user_role'));
	foreach ($roles as $role){
		if( in_array(strtolower($role), $user->roles) ) {
			$meta_key1_value = $user->user_login;
			$meta_key1_display = $user->display_name;
			global $post;
			$args = array(
				'post_type'		=>	'wpcrm-project',
				'posts_per_page'	=> 5,
				'meta_query'	=> array(
					'relation'		=>	'AND',
					array(
						'key'				=>	$meta_key1,
						'value'				=>	$meta_key1_value,
						'compare'			=>	'=',
					),
					array(
						'key'		=>	$meta_key2,
						'value'		=>	$meta_key2_value,
						'compare'	=>	'!=',
					),
				),
			);
			$posts = get_posts($args);					
			if ($posts) {
				foreach($posts as $post) {
					$projects .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'project-closedate',true)) . '<br />';
				}
			} else {
				$projects = '';
			}
			if ($projects == '') {
				$project_report .= '';
			} else {
				$project_report .= '<li>' . $projects . '</li>';
			}
		break;
		}
	}
		
	if ($project_report == '') {
		$project_report = '<li>' . __('You have no projects assigned', 'wp-crm-system') . '</li>';
	}
	echo $project_report;
}
function wp_crm_user_tasks() {
	global $wpdb;
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	$meta_key1 = $prefix . 'task-assignment';
	$meta_key2 = $prefix . 'task-status';
	$meta_key2_value = 'complete';
	$task_report = '';
	$tasks = '';
	$wpcrm_user = '';
	$user = wp_get_current_user();
	$roles = explode(",", get_option('wpcrm_system_select_user_role'));
	foreach ($roles as $role){
		if( in_array(strtolower($role), $user->roles) ) {
			$meta_key1_value = $user->user_login;
			$meta_key1_display = $user->display_name;
			global $post;
			$args = array(
				'post_type'		=>	'wpcrm-task',
				'posts_per_page'	=> 5,
				'meta_query'	=> array(
					'relation'		=>	'AND',
					array(
						'key'				=>	$meta_key1,
						'value'				=>	$meta_key1_value,
						'compare'			=>	'=',
					),
					array(
						'key'		=>	$meta_key2,
						'value'		=>	$meta_key2_value,
						'compare'	=>	'!=',
					),
				),
			);
			$posts = get_posts($args);					
			if ($posts) {
				foreach($posts as $post) {
					$tasks .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'task-due-date',true)) . '<br />';
				}
			} else {
				$tasks = '';
			}
			if ($tasks == '') {
				$task_report .= '';
			} else {
				$task_report .= '<li>' . $tasks . '</li>';
			}
		break;
		}
	}
		
	if ($task_report == '') {
		$task_report = '<li>' . __('You have no tasks assigned', 'wp-crm-system') . '</li>';
	}
	echo $task_report;
}
function wp_crm_user_opportunities() {
	global $wpdb;
	include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
	$meta_key1 = $prefix . 'opportunity-assigned';
	$meta_key2 = $prefix . 'opportunity-wonlost';
	$meta_key2_value = 'not-set';
	$opportunity_report = '';
	$opportunities = '';
	$wpcrm_user = '';
	$user = wp_get_current_user();
	$roles = explode(",", get_option('wpcrm_system_select_user_role'));
	foreach ($roles as $role){
		if( in_array(strtolower($role), $user->roles) ) {
			$meta_key1_value = $user->user_login;
			$meta_key1_display = $user->display_name;
			global $post;
			$args = array(
				'post_type'		=>	'wpcrm-opportunity',
				'posts_per_page'	=> 5,
				'meta_query'	=> array(
					'relation'		=>	'AND',
					array(
						'key'				=>	$meta_key1,
						'value'				=>	$meta_key1_value,
						'compare'			=>	'=',
					),
					array(
						'key'		=>	$meta_key2,
						'value'		=>	$meta_key2_value,
						'compare'	=>	'=',
					),
				),
			);
			$posts = get_posts($args);					
			if ($posts) {
				foreach($posts as $post) {
					$opportunities .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'opportunity-closedate',true)) . '<br />';
				}
			} else {
				$opportunities = '';
			}
			if ($opportunities == '') {
				$opportunity_report .= '';
			} else {
				$opportunity_report .= '<li>' . $opportunities . '</li>';
			}
		break;
		}
	}
		
	if ($opportunity_report == '') {
		$opportunity_report = '<li>' . __('You have no opportunities assigned', 'wp-crm-system') . '</li>';
	}
	echo $opportunity_report;
}
?>
<div class="wp-crm-widget">
<h3><?php _e('Your Projects','wp-crm-system'); ?></h3>
<ul>
<li><strong><?php _e('Project Name - Due Date','wp-crm-system'); ?></strong></li>
<?php wp_crm_user_projects(); ?>
</ul>
</div>
<div class="wp-crm-widget">
<h3><?php _e('Your Tasks','wp-crm-system'); ?></h3>
<ul>
<li><strong><?php _e('Task Name - Due Date','wp-crm-system'); ?></strong></li>
<?php wp_crm_user_tasks(); ?>
</div>
<div class="wp-crm-widget">
<h3><?php _e('Your Opportunities','wp-crm-system'); ?></h3>
<ul>
<li><strong><?php _e('Opportunity Name - Forecasted Close Date','wp-crm-system'); ?></strong></li>
<?php wp_crm_user_opportunities(); ?>
</ul>
</div>
<div class="wp-crm-widget">
<a href="<?php echo admin_url('admin.php?page=wpcrm-reports'); ?>"><?php _e('View Reports','wp-crm-system'); ?></a> | <a href="<?php echo admin_url('admin.php?page=wpcrm-extensions'); ?>"><?php _e('Browse Extensions','wp-crm-system'); ?></a>
</div>