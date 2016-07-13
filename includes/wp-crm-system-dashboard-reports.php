<?php defined( 'ABSPATH' ) OR exit;
function wp_crm_user_projects( $report ) {
	global $wpdb;
	include(plugin_dir_path( __FILE__ ) . 'wp-crm-system-vars.php');
	$meta_key1 = $prefix . 'project-assigned';
	$meta_key2 = $prefix . 'project-status';
	$meta_key2_value = 'complete';
	$project_report = '';
	$projects = '';

	$user = wp_get_current_user();
	if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
		$meta_key1_value = $user->user_login;
		$meta_key1_display = $user->display_name;
		global $post;
		$args = array(
			'post_type'		=>	'wpcrm-project',
			'posts_per_page'	=> -1,
			'meta_query'	=> array(
				'relation'		=>	'AND',
				array(
					'key'		=>	$meta_key1,
					'value'		=>	$meta_key1_value,
					'compare'	=>	'=',
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
			if ( 'value' == $report ) {
				$value = array();
				foreach($posts as $post) {
					$value[] = get_post_meta($post->ID,$prefix . 'project-value',true);
				}
				$active_currency = get_option( 'wpcrm_system_default_currency' );
				foreach ( $wpcrm_currencies as $currency => $symbol ){
					if ( $active_currency == $currency ){
						$currency_symbol = $symbol;
					}
				}
				echo ': ' . $currency_symbol . number_format(array_sum( $value ),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator')) . '<span class="dashicons dashicons-editor-help" title="' . __('Total value of all active, incomplete projects assigned to you.') . '"></span>';
			}
			if ( 'list' == $report ) {
				foreach($posts as $post) {
					if (get_post_meta($post->ID,$prefix . 'project-closedate',true)) {
						$close = ' - ' .  date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'project-closedate',true));
					} else {
						$close = '';
					}
					$projects .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a>' . $close . '<br />';
				}
				if ($projects == '') {
					$project_report .= '';
				} else {
					$project_report .= '<li>' . $projects . '</li>';
				}
				if ($project_report == '') {
					$project_report = '<li>' . __('You have no projects assigned', 'wp-crm-system') . '</li>';
				}
				echo '<ul>';
				echo $project_report;
				echo '</ul>';
			}
		} else {
			if ( 'value' == $report ) {
				return;
			}
			if ( 'list' == $report ) {
				_e('You have no projects assigned', 'wp-crm-system');
			}
		}
	} else {
		return;
	}
}
function wp_crm_user_tasks() {
	global $wpdb;
	include(plugin_dir_path( __FILE__ ) . 'wp-crm-system-vars.php');
	$meta_key1 = $prefix . 'task-assignment';
	$meta_key2 = $prefix . 'task-status';
	$meta_key2_value = 'complete';
	$task_report = '';
	$tasks = '';

	$user = wp_get_current_user();
	if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
		$meta_key1_value = $user->user_login;
		$meta_key1_display = $user->display_name;
		global $post;
		$args = array(
			'post_type'		=>	'wpcrm-task',
			'posts_per_page'	=> -1,
			'meta_query'	=> array(
				'relation'		=>	'AND',
				array(
					'key'		=>	$meta_key1,
					'value'		=>	$meta_key1_value,
					'compare'	=>	'=',
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
				if (get_post_meta($post->ID,$prefix . 'task-due-date',true)) {
					$due = ' - ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'task-due-date',true));
				} else {
					$due = '';
				}
				$tasks .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a>' . $due . '<br />';
			}
		} else {
			$tasks = '';
		}
		if ($tasks == '') {
			$task_report .= '';
		} else {
			$task_report .= '<li>' . $tasks . '</li>';
		}
	}

	if ($task_report == '') {
		$task_report = '<li>' . __('You have no tasks assigned', 'wp-crm-system') . '</li>';
	}
	echo '<ul>';
	echo $task_report;
	echo '</ul>';
}
function wp_crm_user_opportunities( $report ) {
	global $wpdb;
	include(plugin_dir_path( __FILE__ ) . 'wp-crm-system-vars.php');
	$meta_key1 = $prefix . 'opportunity-assigned';
	$meta_key2 = $prefix . 'opportunity-wonlost';
	$meta_key2_value = 'not-set';
	$opportunity_report = '';
	$opportunities = '';

	$user = wp_get_current_user();
	if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
		$meta_key1_value = $user->user_login;
		$meta_key1_display = $user->display_name;
		global $post;
		$args = array(
			'post_type'		=>	'wpcrm-opportunity',
			'posts_per_page'	=> -1,
			'meta_query'	=> array(
				'relation'		=>	'AND',
				array(
					'key'		=>	$meta_key1,
					'value'		=>	$meta_key1_value,
					'compare'	=>	'=',
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
			if ( 'value' == $report ) {
				$value = array();
				foreach($posts as $post) {
					$value[] = get_post_meta($post->ID,$prefix . 'opportunity-value',true);
				}
				$active_currency = get_option( 'wpcrm_system_default_currency' );
				foreach ( $wpcrm_currencies as $currency => $symbol ){
					if ( $active_currency == $currency ){
						$currency_symbol = $symbol;
					}
				}
				echo ': ' . $currency_symbol . number_format(array_sum( $value ),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator')) . '<span class="dashicons dashicons-editor-help" title="' . __('Total value of all active, opportunities assigned to you.') . '"></span>';
			}
			if ( 'list' == $report ) {
				foreach($posts as $post) {
					if (get_post_meta($post->ID,$prefix . 'opportunity-closedate',true)){
						$close = ' - ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'opportunity-closedate',true));
					} else {
						$close = '';
					}
					$opportunities .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a>' . $close . '<br />';
				}
				if ($opportunities == '') {
					$opportunity_report .= '';
				} else {
					$opportunity_report .= '<li>' . $opportunities . '</li>';
				}
				if ($opportunity_report == '') {
					$opportunity_report = '<li>' . __('You have no opportunities assigned', 'wp-crm-system') . '</li>';
				}
				echo '<ul>';
				echo $opportunity_report;
				echo '</ul>';
			}
		} else {
			if ( 'value' == $report ) {
				return;
			}
			if ( 'list' == $report ) {
				_e('You have no opportunities assigned', 'wp-crm-system');
			}
		}
	} else {
		return;
	}
}
function wp_crm_user_campaigns() {
	global $wpdb;
	include(plugin_dir_path( __FILE__ ) . 'wp-crm-system-vars.php');
	$meta_key1 = $prefix . 'campaign-assigned';
	$meta_key2 = $prefix . 'campaign-active';
	$meta_key2_value = 'yes';
	$meta_key3 = $prefix . 'campaign-status';
	$meta_key3_value = 'complete';
	$campaign_report = '';
	$campaigns = '';

	$user = wp_get_current_user();
	if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
		$meta_key1_value = $user->user_login;
		$meta_key1_display = $user->display_name;
		global $post;
		$args = array(
			'post_type'		=>	'wpcrm-campaign',
			'posts_per_page'	=> -1,
			'meta_query'	=> array(
				'relation'		=>	'AND',
				array(
					'key'		=>	$meta_key1,
					'value'		=>	$meta_key1_value,
					'compare'	=>	'=',
				),
				array(
					'key'		=>	$meta_key2,
					'value'		=>	$meta_key2_value,
					'compare'	=>	'=',
				),
				array(
					'key'		=>	$meta_key3,
					'value'		=>	$meta_key3_value,
					'compare'	=>	'!=',
				),
			),
		);
		$posts = get_posts($args);
		if ($posts) {
			foreach($posts as $post) {
				if (get_post_meta($post->ID,$prefix . 'campaign-enddate',true)){
					$end = ' - ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'campaign-enddate',true));
				} else {
					$end = '';
				}
				$campaigns .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a>' . $end . '<br />';
			}
		} else {
			$campaigns = '';
		}
		if ($campaigns == '') {
			$campaign_report .= '';
		} else {
			$campaign_report .= '<li>' . $campaigns . '</li>';
		}
	}

	if ($campaign_report == '') {
		$campaign_report = '<li>' . __('You have no campaigns assigned', 'wp-crm-system') . '</li>';
	}
	echo '<ul>';
	echo $campaign_report;
	echo '</ul>';
}
