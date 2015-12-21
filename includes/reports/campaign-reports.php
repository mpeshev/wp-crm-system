<?php
	global $wpdb;
	include(plugin_dir_path( dirname(dirname(__FILE__ ))) . 'includes/wp-crm-system-vars.php');
	$active_report = isset( $_GET[ 'report' ] ) ? $_GET[ 'report' ] : ''; 
	$active_campaign = isset( $_GET['campaign'] ) ? $_GET['campaign'] : '';
	
	switch ($active_report) {
		case 'all_campaigns':
			$campaign_report = '';
			$report_title = '';
			$no_campaigns = __('No campaigns to display.', 'wp-crm-system');
				
			$meta_key1 = $prefix . 'campaign-status';
			$meta_key1_values = array('not-started'=>__('Not Started','wp-crm-system'),'in-progress'=>__('In Progress','wp-crm-system'),'complete'=>__('Complete','wp-crm-system'),'on-hold'=>__('On Hold','wp-crm-system'));
			$assigned = $prefix . 'campaign-assigned';
			$active = $prefix . 'campaign-active';
			$status = $prefix . 'campaign-status';
			$start = $prefix . 'campaign-startdate';
			$end = $prefix . 'campaign-enddate';
			$responses = $prefix . 'campaign-responses';
			$reach = $prefix . 'campaign-projectedreach';
			$budgetcost = $prefix . 'campaign-budgetcost';
			$actualcost = $prefix . 'campaign-actualcost';
			global $post;
			if ($active_campaign == '') {
				$report_title = __('Campaigns by Status', 'wp-crm-system');
				$campaign_report .= '<tr><td><strong>' . WPCRM_STATUS . '</strong></td><td><strong>' . __('Campaign','wp-crm-system') . ' - ' . WPCRM_END . '</strong></td></tr>';
				foreach($meta_key1_values as $key=>$value) {
					$args = array(
						'post_type'		=>	'wpcrm-campaign',
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
						$campaign_report .= '<tr><td><strong>' . $value . '</strong></td><td>';
						foreach($posts as $post) {
							$campaign_report .= '<a href="?page=wpcrm-reports&tab=campaign&report=all_campaigns&campaign='.$post->ID.'">' . get_the_title($post->ID) . '</a> - ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$end,true)) . '<br />';
						}
						$campaign_report .= '</td></tr>';
					} else {
						$campaign_report .= '<tr><td><strong>' . $value . '</strong></td><td>' . $no_campaigns . '</td></tr>';
					}
				}
			} else {
				$report_title .= get_the_title($active_campaign).' <a href="'.get_edit_post_link($active_campaign).'">'.__('Edit this campaign','wp-crm-system').'</a>';
				if (get_post_meta($active_campaign,$active,true) == 'yes') { 
					$is_active = WPCRM_ACTIVE; 
				} else { 
					$is_active = WPCRM_INACTIVE;
				}
				$campaign_status = get_post_meta($active_campaign,$status,true);
				$statuses = array(__('Not Started','wp-crm-system')=>'not-started',__('In Progress','wp-crm-system')=>'in-progress',__('Complete','wp-crm-system')=>'complete',__('On Hold','wp-crm-system')=>'on-hold');
				$display_status = array_search($campaign_status,$statuses);
				if (get_post_meta($active_campaign,$assigned,true) && get_post_meta($active_campaign,$assigned,true) != '') { 
					$user = get_user_by('login',get_post_meta($active_campaign,$assigned,true)); 
					$is_assigned = $user->display_name;
				} else { 
					$is_assigned = __('Not Assigned','wp-crm-system');
				}
				$attach_campaign = $prefix . 'opportunity-attach-to-campaign';
				$search_opportunities = "SELECT count(DISTINCT pm.post_id)
					FROM $wpdb->postmeta pm
					JOIN $wpdb->posts p ON (p.ID = pm.post_id)
					WHERE pm.meta_key = '$attach_campaign'
					AND pm.meta_value = '$active_campaign'
					AND p.post_type = 'wpcrm-opportunity'
					AND p.post_status = 'publish'
					";
				$count_opportunities = $wpdb->get_var($search_opportunities);
				$opp_val = $prefix . 'opportunity-value';
				$search_opp_values = $wpdb->get_col($wpdb->prepare("SELECT a.meta_value FROM $wpdb->postmeta a INNER JOIN $wpdb->postmeta b ON a.post_id = b.post_id AND b.meta_key = %s AND b.meta_value = %s WHERE a.meta_key = %s", $attach_campaign,$active_campaign,$opp_val));
				$value_opportunities = strtoupper(get_option('wpcrm_system_default_currency')) . ' ' . number_format(array_sum( $search_opp_values ),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator'));
				$wpdb->flush();
				$won = 'won';
				$won_key = $prefix . 'opportunity-wonlost';
				$search_won_opps = $wpdb->get_col($wpdb->prepare("SELECT a.meta_value 
				FROM $wpdb->postmeta a 
				INNER JOIN $wpdb->postmeta b 
					ON a.post_id = b.post_id 
					AND b.meta_key = %s 
					AND b.meta_value = %s 
				INNER JOIN $wpdb->postmeta c
					ON a.post_id = c.post_id
					AND c.meta_key = %s 
					AND c.meta_value = %s 
				WHERE a.meta_key = %s", $attach_campaign,$active_campaign,$won_key,$won,$opp_val));
				$value_won_opps = strtoupper(get_option('wpcrm_system_default_currency')) . ' ' . number_format(array_sum( $search_won_opps ),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator'));
				$roi = number_format(((array_sum( $search_won_opps )-get_post_meta($active_campaign,$actualcost,true))/get_post_meta($active_campaign,$actualcost,true)*100),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator'));
				
				$campaign_report .= '<tr><th>'.WPCRM_ASSIGNED.'</th><th>'.WPCRM_ACTIVE.'</th><th>'.WPCRM_STATUS.'</th><th>'.WPCRM_START.'</th><th>'.WPCRM_END.'</th><th>'.WPCRM_REACH.'</th><th>'.WPCRM_RESPONSES.'</th><th>'.WPCRM_OPPORTUNITIES.'</th><th>'.WPCRM_VALUE_OPPS.'</th><th>'.WPCRM_VALUE_WON_OPPS.'</th><th>'.WPCRM_ROI.'</th><th>'.WPCRM_BUDGETED_COST.'</th><th>'.WPCRM_ACTUAL_COST.'</th></tr>';
				$campaign_report .= '<tr><td>'.$is_assigned.'</td><td>'.$is_active.'</td><td>'.$display_status.'</td><td>'.date(get_option('wpcrm_system_php_date_format'),get_post_meta($active_campaign,$start,true)).'</td><td>'.date(get_option('wpcrm_system_php_date_format'),get_post_meta($active_campaign,$end,true)).'</td><td>'.number_format(get_post_meta($active_campaign,$reach,true),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator')).'</td><td>'.number_format(get_post_meta($active_campaign,$responses,true),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator')).'</td><td>'.$count_opportunities.'</td><td>'.$value_opportunities.'</td><td>'.$value_won_opps.'</td><td>'.$roi.'%</td><td>'.strtoupper(get_option('wpcrm_system_default_currency')) . ' ' . number_format(get_post_meta($active_campaign,$budgetcost,true),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator')).'</td><td>'.strtoupper(get_option('wpcrm_system_default_currency')) . ' ' . number_format(get_post_meta($active_campaign,$actualcost,true),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator')).'</td></tr>';
			}
		break;
		case 'user_campaigns':
			$meta_key1 = $prefix . 'campaign-assigned';
			$campaign_report = '';
			$campaigns = '';
			$report_title = __('Campaigns by User', 'wp-crm-system');
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
					'post_type'		=>	'wpcrm-campaign',
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
						$campaigns .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$campaigns = '';
				}
				if ($campaigns == '') {
					$campaign_report .= '';
				} else {
					$campaign_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $campaigns . '</td></tr>';
				}
				$campaigns = '';//reset campaigns for next user
			}
		if ($campaign_report == '') {
			$campaign_report = '<tr><td>' . __('No campaigns are linked to users. Please add or edit a campaign and link it to a user for this report to show.', 'wp-crm-system') . '</td></tr>';
		}
			break;
		case 'type_campaign':
			$campaign_report = '';
			$report_title = __('Campaigns by Type', 'wp-crm-system');
			
			$taxonomies = array('campaign-type');
			$args = array('hide_empty'=>0);
			$terms = get_terms($taxonomies, $args);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				foreach ( $terms as $term ) {
					$args = array(
						'post_type'			=>	'wpcrm-campaign',
						'posts_per_page'	=> -1,
						'opportunity-type'	=> $term->slug,
					);
					$posts = get_posts( $args );
					if ($posts) {
						$campaign_report .= '<tr><td><strong>' . $term->name . '</strong></td><td>';
						foreach($posts as $post) {
							$campaign_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a> - Forecasted Close Date  ' . date(get_option('wpcrm_system_php_date_format'),get_post_meta($post->ID,$prefix . 'opportunity-closedate',true)) . '<br />';
						}
						$campaign_report .= '</td></tr>';
					} else {
						$campaign_report .= '<tr><td>' . __('No campaigns to report.', 'wp-crm-system') . '</td></tr>';
					}
				 }
			}
			if ($campaign_report == '') {
				$campaign_report = '<tr><td>' . __('No campaigns to report.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		default:
			$reports = array('all_campaigns'=>'All Campaigns','user_campaigns'=>'Campaigns by User','type_campaign'=>'Campaigns by Type');
			$campaign_report = '';
			$report_title = 'Campaign Reports';
			foreach ($reports as $key => $value) {
				$campaign_report .= '<tr><td><a href="?page=wpcrm-reports&tab=campaign&report=' . $key . '">' . $value . '</a></td></tr>';
			}
	}
?>
	<div class="wrap">
		<div>
			<h2><?php echo $report_title; ?></h2>
			<?php if ($active_report == ('' || 'overview')) { ?><a href="?page=wpcrm-reports&tab=campaign"><?php _e('Back to Campaign Reports', 'wp-crm-system'); ?></a><?php } ?>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<?php echo $campaign_report; ?>
				</tbody>
			</table>
		</div>
	</div>