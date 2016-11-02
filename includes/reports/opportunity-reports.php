<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	global $wpdb;
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php');
	$active_report = isset( $_GET[ 'report' ] ) ? $_GET[ 'report' ] : '';

	switch ($active_report) {
		case 'user_opportunities':
			$meta_key1 = $prefix . 'opportunity-assigned';
			$opportunity_report = '';
			$opportunities = '';
			$report_title = __('Opportunities by User', 'wp-crm-system');
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
					'post_type'		=>	'wpcrm-opportunity',
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
						$opportunities .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$opportunities = '';
				}
				if ($opportunities == '') {
					$opportunity_report .= '';
				} else {
					$opportunity_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $opportunities . '</td></tr>';
				}
				$opportunities = '';//reset opportunities for next user
			}
			if ($opportunity_report == '') {
				$opportunity_report = '<tr><td>' . __('No opportunities are linked to users. Please add or edit a opportunity and link it to a user for this report to show.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		case 'organization_opportunities':
			$meta_key1 = $prefix . 'opportunity-attach-to-organization';
			$opportunity_report = '';
			$opportunities = '';
			$report_title = __('Opportunities by Organization', 'wp-crm-system');
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-organization');
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$meta_key1_value = get_the_ID();
				$meta_key1_display = get_the_title();
				$args = array(
					'post_type'		=>	'wpcrm-opportunity',
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
						$opportunities .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$opportunities = '';
				}
				if ($opportunities == '') {
					$opportunity_report .= '';
				} else {
					$opportunity_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $opportunities . '</td></tr>';
				}
				$opportunities = '';//reset opportunities for next organization
			endwhile;
			if ($opportunity_report == '') {
				$opportunity_report = '<tr><td>' . __('No opportunities are linked to organizations. Please add or edit a opportunity and link it to an organization for this report to show.', 'wp-crm-system');
			}
			break;
		case 'contact_opportunities':
			$meta_key1 = $prefix . 'opportunity-attach-to-contact';
			$opportunity_report = '';
			$opportunities = '';
			$report_title = __('Projects by Contact', 'wp-crm-system');
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-contact');
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$meta_key1_value = get_the_ID();
				$meta_key1_display = get_the_title();
				$args = array(
					'post_type'		=>	'wpcrm-opportunity',
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
						$opportunities .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$opportunities = '';
				}
				if ($opportunities == '') {
					$opportunity_report .= '';
				} else {
					$opportunity_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $opportunities . '</td></tr>';
				}
				$opportunities = '';//reset opportunities for next contact
			endwhile;
			if ($opportunity_report == '') {
				$opportunity_report = '<tr><td>' . __('No opportunities are linked to contacts. Please add or edit a opportunity and link it to a contact for this report to show.', 'wp-crm-system');
			}
			break;
		case 'status_opportunities':
			$opportunity_report = '';
			$report_title = __('Opportunities by Status', 'wp-crm-system');
			$no_opportunities = __('No opportunities to display.', 'wp-crm-system');

			$meta_key1 = $prefix . 'opportunity-wonlost';
			$meta_key1_values = array('won'=>__('Won','wp-crm-system'),'lost'=>__('Lost','wp-crm-system'),'suspended'=>__('Suspended','wp-crm-system'),'abandoned'=>__('Abandoned','wp-crm-system'),'not-set'=>__('Not Set','wp-crm-system'));
			$meta_key2 = $prefix . 'opportunity-closedate';
			global $post;
			$opportunity_report .= '<tr><td><strong>' . WPCRM_STATUS . '</strong></td><td><strong>' . __('Opportunity','wp-crm-system') . ' - ' . WPCRM_FORECASTED_CLOSE .'</strong></td></tr>';
			foreach($meta_key1_values as $key=>$value) {
				$args = array(
					'post_type'		=>	'wpcrm-opportunity',
					'meta_query'	=> array(
						array(
							'key'		=>	$meta_key1,
							'value'		=>	$key,
							'compare'	=>	'=',
						),
					),
				);
				$posts = get_posts($args);
				if ($posts) {
					$opportunity_report .= '<tr><td><strong>' . $value . '</strong></td><td>';
					foreach($posts as $post) {
						$opportunity_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - '. date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$meta_key2,true)) . '<br />';
					}
					$opportunity_report .= '</td></tr>';
				} else {
					$opportunity_report .= '<tr><td><strong>' . $value . '</strong></td><td>' . $no_opportunities . '</td></tr>';
				}
			}
			break;
		case 'type_opportunity':
			$opportunity_report = '';
			$report_title = __('Opportunities by Type', 'wp-crm-system');

			$taxonomies = array('opportunity-type');
			$args = array('hide_empty'=>0);
			$terms = get_terms($taxonomies, $args);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				foreach ( $terms as $term ) {
					$args = array(
						'post_type'			=>	'wpcrm-opportunity',
						'posts_per_page'	=> -1,
						'opportunity-type'	=> $term->slug,
					);
					$posts = get_posts( $args );
					if ($posts) {
						$opportunity_report .= '<tr><td><strong>' . $term->name . '</strong></td><td>';
						foreach($posts as $post) {
							$opportunity_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - Forecasted Close Date  ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'opportunity-closedate',true)) . '<br />';
						}
						$opportunity_report .= '</td></tr>';
					} else {
						$opportunity_report .= '<tr><td>' . __('No opportunities to report.', 'wp-crm-system') . '</td></tr>';
					}
				 }
			}
			if ($opportunity_report == '') {
				$opportunity_report = '<tr><td>' . __('No opportunities to report.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		default:
			$reports = array('user_opportunities'=>'Opportunities by User','organization_opportunities'=>'Opportunities by Organization','contact_opportunities'=>'Opportunities by Contact','status_opportunities'=>'Opportunities by Status','type_opportunity'=>'Opportunities by Type');
			$opportunity_report = '';
			$report_title = 'Opportunity Reports';
			foreach ($reports as $key => $value) {
				$opportunity_report .= '<tr><td><a href="?page=wpcrm-reports&tab=opportunity&report=' . $key . '">' . $value . '</a></td></tr>';
			}
	}
?>
	<div class="wrap">
		<div>
			<h2><?php echo $report_title; ?></h2>
			<?php if ($active_report == ('' || 'overview')) { ?><a href="?page=wpcrm-reports&tab=opportunity"><?php _e('Back to Opportunity Reports', 'wp-crm-system'); ?></a><?php } ?>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<?php echo $opportunity_report; ?>
				</tbody>
			</table>
		</div>
	</div>
