<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	global $wpdb;
	include(plugin_dir_path( dirname(dirname(__FILE__ ))) . 'includes/wp-crm-system-vars.php');
	$active_report = isset( $_GET[ 'report' ] ) ? $_GET[ 'report' ] : ''; 
	
	switch ($active_report) {
		case 'user_tasks':
			$meta_key1 = $prefix . 'task-assignment';
			$task_report = '';
			$tasks = '';
			$report_title = __('Tasks by User', 'wp-crm-system');
			$users = get_users();
			$wp_crm_users = array();
			foreach( $users as $user ){
				if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
					$wp_crm_users[] = $user;
				}
			}
			foreach( $wp_crm_users as $user) {
				$meta_key1_value = $user->data->user_login;
				$meta_key1_display = $user->data->display_name;
				global $post;
				
				$args = array(
					'post_type'		=>	'wpcrm-task',
					'meta_query'	=> array(
						array(
							'key'		=>	$meta_key1,
							'value'		=>	$meta_key1_value,
							'compare'	=>	'=',
						),
					),
				);
				$posts = get_posts($args);					
				if ($posts) {
					foreach($posts as $post) {
						$tasks .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$tasks = '';
				}
				if ($tasks == '') {
					$task_report .= '';
				} else {
					$task_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $tasks . '</td></tr>';
				}
				$tasks = '';//reset tasks for next user
			}
		if ($task_report == '') {
			$task_report = '<tr><td>' . __('No tasks are linked to users. Please add or edit a task and link it to a user for this report to show.', 'wp-crm-system') . '</td></tr>';
		}
			break;
		case 'organization_tasks':
			$meta_key1 = $prefix . 'task-attach-to-organization';
			$task_report = '';
			$tasks = '';
			$report_title = __('Tasks by Organization', 'wp-crm-system');
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-organization');
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$meta_key1_value = get_the_ID();
				$meta_key1_display = get_the_title();
				$args = array(
					'post_type'		=>	'wpcrm-task',
					'meta_query'	=> array(
						array(
							'key'		=>	$meta_key1,
							'value'		=>	$meta_key1_value,
							'compare'	=>	'=',
						),
					),
				);
				$posts = get_posts($args);					
				if ($posts) {
					foreach($posts as $post) {
						$tasks .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$tasks = '';
				}
				if ($tasks == '') {
					$task_report .= '';
				} else {
					$task_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $tasks . '</td></tr>';
				}
				$tasks = '';//reset tasks for next organization
			endwhile;
			if ($task_report == '') {
				$task_report = '<tr><td>' . __('No tasks are linked to organizations. Please add or edit a task and link it to an organization for this report to show.', 'wp-crm-system');
			}
			break;
		case 'contact_tasks':
			$meta_key1 = $prefix . 'task-attach-to-contact';
			$task_report = '';
			$tasks = '';
			$report_title = __('Tasks by Contact', 'wp-crm-system');
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-contact');
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$meta_key1_value = get_the_ID();
				$meta_key1_display = get_the_title();
				$args = array(
					'post_type'		=>	'wpcrm-task',
					'meta_query'	=> array(
						array(
							'key'		=>	$meta_key1,
							'value'		=>	$meta_key1_value,
							'compare'	=>	'=',
						),
					),
				);
				$posts = get_posts($args);					
				if ($posts) {
					foreach($posts as $post) {
						$tasks .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$tasks = '';
				}
				if ($tasks == '') {
					$task_report .= '';
				} else {
					$task_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $tasks . '</td></tr>';
				}
				$tasks = '';//reset tasks for next contact
			endwhile;
			if ($task_report == '') {
				$task_report = '<tr><td>' . __('No tasks are linked to contacts. Please add or edit a task and link it to a contact for this report to show.', 'wp-crm-system');
			}
			break;
		case 'overdue_tasks':
		case 'upcoming_tasks': {
			$task_report = '';
			if ($active_report == 'overdue_tasks') {
				$report_title = __('Overdue Tasks', 'wp-crm-system');
				$meta_compare = '<';
				$no_tasks = __('No tasks are overdue!', 'wp-crm-system');
			}
			if ($active_report == 'upcoming_tasks') {
				$report_title = __('Upcoming Tasks', 'wp-crm-system');
				$meta_compare = '>';
				$no_tasks = __('No upcoming tasks.', 'wp-crm-system');
			}
			
			$meta_key1 = $prefix . 'task-status';
			$meta_key1_value = 'complete';
			$meta_key2 = $prefix . 'task-due-date';
			global $post;
			$args = array(
				'post_type'		=>	'wpcrm-task',
				'meta_query'	=> array(
					'relation'		=>	'AND',
					array(
						'key'		=>	$meta_key1,
						'value'		=>	$meta_key1_value,
						'compare'	=>	'!=',
					),
					array(
						'key'		=>	$meta_key2,
						'value'		=>	strtotime('now'),
						'compare'	=>	$meta_compare,
					),
				),
			);
			$posts = get_posts($args);
			if ($posts) {
				foreach($posts as $post) {
					$task_report = '<tr><td><a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - Due on ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$meta_key2,true)) . '</td></tr>';
				}
			} else {
				$task_report = '<tr><td>' . $no_tasks . '</td></tr>';
			}
			break;
		}
		case 'pct_complete_tasks':
			$progresses = array(95,90,85,80,75,70,65,60,55,50,45,40,35,30,25,20,15,10,5,0);
			$report_title = __('Tasks by Percentage Complete', 'wp-crm-system');
			$task_report = '';
			$meta_key1 = $prefix . 'task-status';
			$meta_key1_value = 'complete';
			$meta_key2 = $prefix . 'task-progress';
			global $post;
			foreach($progresses as $progress) {
				$args = array(
					'post_type'		=>	'wpcrm-task',
					'meta_query'	=> array(
						'relation'		=>	'AND',
						array(
							'key'		=>	$meta_key1,
							'value'		=>	$meta_key1_value,
							'compare'	=>	'!=',
						),
						array(
							'key'		=>	$meta_key2,
							'value'		=>	$progress,
							'compare'	=>	'=',
						),
					),
				);
				$posts = get_posts($args);
				if ($posts) {
					$task_report .= '<tr><td><strong>' . $progress . '%</strong></td><td>';
					foreach($posts as $post) {
						$task_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - Due on ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'task-due-date',true)) . '<br />';
					}
					$task_report .= '</td></tr>';
				} else {
					$task_report .= '';
				}
			}
			if ($task_report == '') {
				$task_report = '<tr><td>' . __('No tasks to report.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		case 'priority_tasks':
			$priorities = array('high'=>_x('High','Greatest importance','wp-crm-system'),'medium'=>_x('Medium','Average priority','wp-crm-system'),'low'=>_x('Low','Not of great importance','wp-crm-system'));
			$report_title = __('Tasks by Priority', 'wp-crm-system');
			$task_report = '';
			$meta_key1 = $prefix . 'task-status';
			$meta_key1_value = 'complete';
			$meta_key2 = $prefix . 'task-priority';
			global $post;
			foreach($priorities as $key => $value) {
				$args = array(
					'post_type'		=>	'wpcrm-task',
					'meta_query'	=> array(
						'relation'		=>	'AND',
						array(
							'key'		=>	$meta_key1,
							'value'		=>	$meta_key1_value,
							'compare'	=>	'!=',
						),
						array(
							'key'		=>	$meta_key2,
							'value'		=>	$key,
							'compare'	=>	'=',
						),
					),
				);
				$posts = get_posts($args);
				if ($posts) {
					$task_report .= '<tr><td><strong>' . $value . '</strong></td><td>';
					foreach($posts as $post) {
						$task_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - Due on ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'task-due-date',true)) . '<br />';
					}
					$task_report .= '</td></tr>';
				} else {
					$task_report .= '';
				}
			}
			if ($task_report == '') {
				$task_report = '<tr><td>' . __('No tasks to report.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		case 'status_tasks':
			$statuses = array('not-started'=>_x('Not Started','Work has not yet begun.','wp-crm-system'),'in-progress'=>_x('In Progress','Work has begun but is not complete.','wp-crm-system'),'on-hold'=>_x('On Hold','Work may be in various stages of completion, but has been stopped for one reason or another.','wp-crm-system'));
			$report_title = __('Tasks by Status', 'wp-crm-system');
			$task_report = '';
			$meta_key1 = $prefix . 'task-status';
			$meta_key1_value = 'complete';
			$meta_key2 = $prefix . 'task-status';
			global $post;
			foreach($statuses as $key => $value) {
				$args = array(
					'post_type'		=>	'wpcrm-task',
					'meta_query'	=> array(
						'relation'		=>	'AND',
						array(
							'key'		=>	$meta_key1,
							'value'		=>	$meta_key1_value,
							'compare'	=>	'!=',
						),
						array(
							'key'		=>	$meta_key2,
							'value'		=>	$key,
							'compare'	=>	'=',
						),
					),
				);
				$posts = get_posts($args);
				if ($posts) {
					$task_report .= '<tr><td><strong>' . $value . '</strong></td><td>';
					foreach($posts as $post) {
						$task_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - Due on ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'task-due-date',true)) . '<br />';
					}
					$task_report .= '</td></tr>';
				} else {
					$task_report .= '';
				}
			}
			if ($task_report == '') {
				$task_report = '<tr><td>' . __('No tasks to report.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		case 'type_task':
			$task_report = '';
			$report_title = __('Tasks by Type', 'wp-crm-system');
			
			$taxonomies = array('task-type');
			$args = array('hide_empty'=>0);
			$terms = get_terms($taxonomies, $args);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				foreach ( $terms as $term ) {
					$args = array(
						'post_type'			=>	'wpcrm-task',
						'posts_per_page'	=> -1,
						'task-type'			=> $term->slug,
					);
					$posts = get_posts( $args );
					if ($posts) {
						$task_report .= '<tr><td><strong>' . $term->name . '</strong></td><td>';
						foreach($posts as $post) {
							$task_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - Due on ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'task-due-date',true)) . '<br />';
						}
						$task_report .= '</td></tr>';
					} else {
						$task_report .= '<tr><td>' . __('No tasks to report.', 'wp-crm-system') . '</td></tr>';
					}
				 }
			}
			if ($task_report == '') {
				$task_report = '<tr><td>' . __('No tasks to report.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		default:
			$reports = array('user_tasks'=>'Tasks by User','organization_tasks'=>'Tasks by Organization','contact_tasks'=>'Tasks by Contact','overdue_tasks'=>'Overdue Tasks','upcoming_tasks'=>'Upcoming Tasks','pct_complete_tasks'=>'Tasks by Percentage Completion','priority_tasks'=>'Tasks by Priority','status_tasks'=>'Tasks by Status','type_task'=>'Tasks by Type');
			$task_report = '';
			$report_title = 'Task Reports';
			foreach ($reports as $key => $value) {
				$task_report .= '<tr><td><a href="?page=wpcrm-reports&tab=task&report=' . $key . '">' . $value . '</a></td></tr>';
			}
	}
?>
	<div class="wrap">
		<div>
			<h2><?php echo $report_title; ?></h2>
			<?php if ($active_report == ('' || 'overview')) { ?><a href="?page=wpcrm-reports&tab=task"><?php _e('Back to Task Reports', 'wp-crm-system'); ?></a><?php } ?>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<?php echo $task_report; ?>
				</tbody>
			</table>
		</div>
	</div>