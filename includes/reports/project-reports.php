<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	global $wpdb;
	include(plugin_dir_path( dirname(dirname(__FILE__ ))) . 'includes/wp-crm-system-vars.php');
	$active_report = isset( $_GET[ 'report' ] ) ? $_GET[ 'report' ] : ''; 
	
	switch ($active_report) {
		case 'user_projects':
			$meta_key1 = $prefix . 'project-assigned';
			$project_report = '';
			$projects = '';
			$report_title = __('Projects by User', 'wp-crm-system');
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
					'post_type'		=>	'wpcrm-project',
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
						$projects .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$projects = '';
				}
				if ($projects == '') {
					$project_report .= '';
				} else {
					$project_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $projects . '</td></tr>';
				}
				$projects = '';//reset projects for next user
			}
			if ($project_report == '') {
				$project_report = '<tr><td>' . __('No projects are linked to users. Please add or edit a project and link it to a user for this report to show.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		case 'organization_projects':
			$meta_key1 = $prefix . 'project-attach-to-organization';
			$project_report = '';
			$projects = '';
			$report_title = __('Projects by Organization', 'wp-crm-system');
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-organization');
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$meta_key1_value = get_the_ID();
				$meta_key1_display = get_the_title();
				$args = array(
					'post_type'		=>	'wpcrm-project',
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
						$projects .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$projects = '';
				}
				if ($projects == '') {
					$project_report .= '';
				} else {
					$project_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $projects . '</td></tr>';
				}
				$projects = '';//reset projects for next organization
			endwhile;
			if ($project_report == '') {
				$project_report = '<tr><td>' . __('No projects are linked to organizations. Please add or edit a project and link it to an organization for this report to show.', 'wp-crm-system');
			}
			break;
		case 'contact_projects':
			$meta_key1 = $prefix . 'project-attach-to-contact';
			$project_report = '';
			$projects = '';
			$report_title = __('Projects by Contact', 'wp-crm-system');
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-contact');
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$meta_key1_value = get_the_ID();
				$meta_key1_display = get_the_title();
				$args = array(
					'post_type'		=>	'wpcrm-project',
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
						$projects .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$projects = '';
				}
				if ($projects == '') {
					$project_report .= '';
				} else {
					$project_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $projects . '</td></tr>';
				}
				$projects = '';//reset projects for next contact
			endwhile;
			if ($project_report == '') {
				$project_report = '<tr><td>' . __('No projects are linked to contacts. Please add or edit a project and link it to a contact for this report to show.', 'wp-crm-system');
			}
			break;
		case 'overdue_projects':
		case 'upcoming_projects': {
			$project_report = '';
			if ($active_report == 'overdue_projects') {
				$report_title = __('Overdue Projects', 'wp-crm-system');
				$meta_compare = '<';
				$no_projects = __('No projects are overdue!', 'wp-crm-system');
			}
			if ($active_report == 'upcoming_projects') {
				$report_title = __('Upcoming Projects', 'wp-crm-system');
				$meta_compare = '>';
				$no_projects = __('No upcoming projects.', 'wp-crm-system');
			}
			
			$meta_key1 = $prefix . 'project-status';
			$meta_key1_value = 'complete';
			$meta_key2 = $prefix . 'project-closedate';
			global $post;
			$args = array(
				'post_type'		=>	'wpcrm-project',
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
					$project_report = '<tr><td><a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - Projected close date ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$meta_key2,true)) . '</td></tr>';
				}
			} else {
				$project_report = '<tr><td>' . $no_projects . '</td></tr>';
			}
			break;
		}
		case 'pct_complete_projects':
			$progresses = array(95,90,85,80,75,70,65,60,55,50,45,40,35,30,25,20,15,10,5,0);
			$report_title = __('Projects by Percentage Complete', 'wp-crm-system');
			$project_report = '';
			$meta_key1 = $prefix . 'project-status';
			$meta_key1_value = 'complete';
			$meta_key2 = $prefix . 'project-progress';
			global $post;
			foreach($progresses as $progress) {
				$args = array(
					'post_type'		=>	'wpcrm-project',
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
					$project_report .= '<tr><td><strong>' . $progress . '%</strong></td><td>';
					foreach($posts as $post) {
						$project_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - Projected close date ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'project-closedate',true)) . '<br />';
					}
					$project_report .= '</td></tr>';
				} else {
					$project_report .= '';
				}
			}
			if ($project_report == '') {
				$project_report = '<tr><td>' . __('No projects to report.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		case 'status_projects':
			$statuses = array('not-started'=>_x('Not Started','Work has not yet begun.','wp-crm-system'),'in-progress'=>_x('In Progress','Work has begun but is not complete.','wp-crm-system'),'on-hold'=>_x('On Hold','Work may be in various stages of completion, but has been stopped for one reason or another.','wp-crm-system'));
			$report_title = __('Projects by Status', 'wp-crm-system');
			$project_report = '';
			$meta_key1 = $prefix . 'project-status';
			$meta_key1_value = 'complete';
			$meta_key2 = $prefix . 'project-status';
			global $post;
			foreach($statuses as $key => $value) {
				$args = array(
					'post_type'		=>	'wpcrm-project',
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
					$project_report .= '<tr><td><strong>' . $value . '</strong></td><td>';
					foreach($posts as $post) {
						$project_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - Projected close date ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'project-closedate',true)) . '<br />';
					}
					$project_report .= '</td></tr>';
				} else {
					$project_report .= '';
				}
			}
			if ($project_report == '') {
				$project_report = '<tr><td>' . __('No projects to report.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		case 'type_project':
			$project_report = '';
			$report_title = __('Projects by Type', 'wp-crm-system');
			
			$taxonomies = array('project-type');
			$args = array('hide_empty'=>0);
			$terms = get_terms($taxonomies, $args);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				foreach ( $terms as $term ) {
					$args = array(
						'post_type'			=>	'wpcrm-project',
						'posts_per_page'	=> -1,
						'project-type'			=> $term->slug,
					);
					$posts = get_posts( $args );
					if ($posts) {
						$project_report .= '<tr><td><strong>' . $term->name . '</strong></td><td>';
						foreach($posts as $post) {
							$project_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - Projected close date ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'project-closedate',true)) . '<br />';
						}
						$project_report .= '</td></tr>';
					} else {
						$project_report .= '<tr><td>' . __('No projects to report.', 'wp-crm-system') . '</td></tr>';
					}
				 }
			}
			if ($project_report == '') {
				$project_report = '<tr><td>' . __('No projects to report.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		case 'listtasks':
			$meta_key1 = $prefix . 'task-attach-to-project';
			$project_report = '';
			$projects = '';
			$report_title = __('Tasks assigned to Projects', 'wp-crm-system');
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-project');
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
						$projects .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$projects = '';
				}
				if ($projects == '') {
					$project_report .= '';
				} else {
					$project_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $projects . '</td></tr>';
				}
				$projects = '';//reset projects for next contact
			endwhile;
			if ($project_report == '') {
				$project_report = '<tr><td>' . __('No tasks are linked to projects. Please add or edit a task and link it to a project for this report to show.', 'wp-crm-system');
			}
			break;
		default:
			$reports = array('user_projects'=>'Projects by User','organization_projects'=>'Projects by Organization','contact_projects'=>'Projects by Contact','overdue_projects'=>'Overdue Projects','upcoming_projects'=>'Upcoming Projects','pct_complete_projects'=>'Projects by Percentage Completion','status_projects'=>'Projects by Status','type_project'=>'Projects by Type','listtasks'=>'Tasks per Project');
			$project_report = '';
			$report_title = 'Project Reports';
			foreach ($reports as $key => $value) {
				$project_report .= '<tr><td><a href="?page=wpcrm-reports&tab=project&report=' . $key . '">' . $value . '</a></td></tr>';
			}
	}
?>
	<div class="wrap">
		<div>
			<h2><?php echo $report_title; ?></h2>
			<?php if ($active_report == ('' || 'overview')) { ?><a href="?page=wpcrm-reports&tab=project"><?php _e('Back to Project Reports', 'wp-crm-system'); ?></a><?php } ?>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<?php echo $project_report; ?>
				</tbody>
			</table>
		</div>
	</div>