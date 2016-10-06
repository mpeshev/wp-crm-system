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
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_create_new_box', 1 );
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_extensions_box', 2 );
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_categories_box', 3 );
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_settings_box', 4 );
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_projects_box', 5 );
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_tasks_box', 6 );
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_opportunities_box', 7 );
add_action( 'wpcrm_system_custom_dashboard_boxes', 'wpcrm_system_dashboard_campaigns_box', 8 );

function wpcrm_system_dashboard_create_new_box() { ?>
	<div class="wpcrm-dashboard">
		<h2><?php _e('Create New...', 'wp-crm-system'); ?></h2>
		<ul>
			<li class="wp-crm-first wp-crm-one-half"><span class="dashicons dashicons-building"></span> <a href="<?php echo admin_url( 'post-new.php?post_type=wpcrm-organization' ); ?>"><?php _e('Organization', 'wp-crm-system'); ?></a></li>
			<li class="wp-crm-one-half"><span class="dashicons dashicons-id"></span> <a href="<?php echo admin_url( 'post-new.php?post_type=wpcrm-contact' ); ?>"><?php _e('Contact', 'wp-crm-system'); ?></a></li>
			<li class="wp-crm-first wp-crm-one-half"><span class="dashicons dashicons-phone"> </span><a href="<?php echo admin_url( 'post-new.php?post_type=wpcrm-opportunity' ); ?>"><?php _e('Opportunity', 'wp-crm-system'); ?></a></li>
			<li class="wp-crm-one-half"><span class="dashicons dashicons-clipboard"></span> <a href="<?php echo admin_url( 'post-new.php?post_type=wpcrm-project' ); ?>"><?php _e('Project', 'wp-crm-system'); ?></a></li>
			<li class="wp-crm-first wp-crm-one-half"><span class="dashicons dashicons-yes"></span> <a href="<?php echo admin_url( 'post-new.php?post_type=wpcrm-task' ); ?>"><?php _e('Task', 'wp-crm-system'); ?></a></li>
			<li class="wp-crm-one-half"><span class="dashicons dashicons-megaphone"></span> <a href="<?php echo admin_url( 'post-new.php?post_type=wpcrm-campaign' ); ?>"><?php _e('Campaign', 'wp-crm-system'); ?></a></li>
			<?php if ( defined('WPCRM_INVOICING') ) { ?>
				<li class="wp-crm-first wp-crm-one-half"><span class="dashicons dashicons-chart-line"></span> <a href="<?php echo admin_url( 'post-new.php?post_type=wpcrm-invoice' ); ?>"><?php _e('Invoice', 'wp-crm-system'); ?></a></li>
			<?php } ?>
		</ul>
	</div>
<?php }
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
function wpcrm_system_dashboard_categories_box() { ?>
	<div class="wpcrm-dashboard">
		<h2><?php _e( 'Categories', 'wp-crm-system' ); ?></h2>
		<?php
		$categories = array('organization-type'=>__('Organization Categories','wp-crm-system'),'contact-type'=>__('Contact Categories','wp-crm-system'),'opportunity-type'=>__('Opportunity Categories','wp-crm-system'),'project-type'=>__('Project Categories','wp-crm-system'),'task-type'=>__('Task Categories','wp-crm-system'),'campaign-type'=>__('Campaign Categories','wp-crm-system'));?>

		<ul>

		<?php foreach ($categories as $key => $value) { ?>
			<li><a href="edit-tags.php?taxonomy=<?php echo $key; ?>"><?php echo $value; ?></a></li>
		<?php } ?>
		<?php if (defined('WPCRM_INVOICING')) { ?>
			<li><a href="edit-tags.php?taxonomy=invoice-type"><?php _e('Invoice Categories','wp-crm-system'); ?></a></li>
		<?php } ?>
		</ul>
	</div>
<?php }
function wpcrm_system_dashboard_settings_box() {
	//Only show to administrators
	if ( current_user_can( 'activate_plugins' ) )  { ?>
		<div class="wpcrm-dashboard">
		<h2><?php _e('WP-CRM System Settings', 'wp-crm-system'); ?></h2>
			<form id="wpcrm_settings" name="wpcrm_settings" method='post' action='options.php'>
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( 'wpcrm_system_settings_main_group' ); ?>
				<table>
					<tbody>
						<tr>
							<td colspan="2">
								<strong><?php _e('Access Level', 'wp-crm-system'); ?></strong><span class="dashicons dashicons-editor-help" title="<?php _e('Roles are listed in order of seniority (Administrator is highest, Subscriber is lowest). All roles higher than, and including the role you select will have access to WP-CRM System.', 'wp-crm-system'); ?>"></span>
							</td>
							<td>
								<?php
								add_filter( 'wpcrm_system_user_role_options', 'wpcrm_system_select_user_roles', 10 );
								function wpcrm_system_select_user_roles( $array ){
									$array = array(
										'manage_options'	=>	__('Administrator', 'wp-crm-system'),
										'edit_pages'		=>	__('Editor', 'wp-crm-system'),
										'publish_posts'		=>	__('Author', 'wp-crm-system'),
										'edit_posts'		=>	__('Contributor', 'wp-crm-system'),
										'read'				=>	__('Subscriber', 'wp-crm-system')
									);
									return $array;
								}

								$wpcrm_system_settings_roles = apply_filters( 'wpcrm_system_user_role_options', array() );
								?>
								<select name="wpcrm_system_select_user_role"> <?php
									foreach ($wpcrm_system_settings_roles as $role=>$name){
									if (get_option('wpcrm_system_select_user_role') == $role) { $selected = 'selected'; } else { $selected = ''; } ?>
									<option value="<?php echo $role; ?>" <?php echo $selected; ?> ><?php echo $name; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<strong><?php _e('Default Currency', 'wp-crm-system'); ?></strong><span class="dashicons dashicons-editor-help" title="<?php _e('Currency to be used when assigning values to projects or opportunities.', 'wp-crm-system'); ?>"></span>
							</td>
							<td>
							<select name="wpcrm_system_default_currency">
							<?php $args = array('aed'=>'AED','afn'=>'AFN','all'=>'ALL','amd'=>'AMD','ang'=>'ANG','aoa'=>'AOA','ars'=>'ARS','aud'=>'AUD','awg'=>'AWG','azn'=>'AZN','bam'=>'BAM','bbd'=>'BBD','bdt'=>'BDT','bgn'=>'BGN','bhd'=>'BHD','bif'=>'BIF','bmd'=>'BMD','bnd'=>'BND','bob'=>'BOB','brl'=>'BRL','bsd'=>'BSD','btn'=>'BTN','bwp'=>'BWP','byr'=>'BYR','bzd'=>'BZD','cad'=>'CAD','cdf'=>'CDF','chf'=>'CHF','clp'=>'CLP','cny'=>'CNY','cop'=>'COP','crc'=>'CRC','cuc'=>'CUC','cup'=>'CUP','cve'=>'CVE','czk'=>'CZK','djf'=>'DJF','dkk'=>'DKK','dop'=>'DOP','dzd'=>'DZD','egp'=>'EGP','ern'=>'ERN','etb'=>'ETB','eur'=>'EUR','fjd'=>'FJD','fkp'=>'FKP','gbp'=>'GBP','gel'=>'GEL','ggp'=>'GGP','ghs'=>'GHS','gip'=>'GIP','gmd'=>'GMD','gnf'=>'GNF','gtq'=>'GTQ','gyd'=>'GYD','hkd'=>'HKD','hnl'=>'HNL','hrk'=>'HRK','htg'=>'HTG','huf'=>'HUF','idr'=>'IDR','ils'=>'ILS','imp'=>'IMP','inr'=>'INR','iqd'=>'IQD','irr'=>'IRR','isk'=>'ISK','jep'=>'JEP','jmd'=>'JMD','jod'=>'JOD','jpy'=>'JPY','kes'=>'KES','kgs'=>'KGS','khr'=>'KHR','kmf'=>'KMF','kpw'=>'KPW','krw'=>'KRW','kwd'=>'KWD','kyd'=>'KYD','kzt'=>'KZT','lak'=>'LAK','lbp'=>'LBP','lkr'=>'LKR','lrd'=>'LRD','lsl'=>'LSL','lyd'=>'LYD','mad'=>'MAD','mdl'=>'MDL','mga'=>'MGA','mkd'=>'MKD','mmk'=>'MMK','mnt'=>'MNT','mop'=>'MOP','mro'=>'MRO','mur'=>'MUR','mvr'=>'MVR','mwk'=>'MWK','mxn'=>'MXN','myr'=>'MYR','mzn'=>'MZN','nad'=>'NAD','ngn'=>'NGN','nio'=>'NIO','nok'=>'NOK','npr'=>'NPR','nzd'=>'NZD','omr'=>'OMR','pab'=>'PAB','pen'=>'PEN','pgk'=>'PGK','php'=>'PHP','pkr'=>'PKR','pln'=>'PLN','prb'=>'PRB','pyg'=>'PYG','qar'=>'QAR','ron'=>'RON','rsd'=>'RSD','rub'=>'RUB','rwf'=>'RWF','sar'=>'SAR','sbd'=>'SBD','scr'=>'SCR','sdg'=>'SDG','sek'=>'SEK','sgd'=>'SGD','shp'=>'SHP','sll'=>'SLL','sos'=>'SOS','srd'=>'SRD','ssp'=>'SSP','std'=>'STD','syp'=>'SYP','szl'=>'SZL','thb'=>'THB','tjs'=>'TJS','tmt'=>'TMT','tnd'=>'TND','top'=>'TOP','try'=>'TRY','ttd'=>'TTD','twd'=>'TWD','tzs'=>'TZS','uah'=>'UAH','ugx'=>'UGX','usd'=>'USD','uyu'=>'UYU','uzs'=>'UZS','vef'=>'VEF','vnd'=>'VND','vuv'=>'VUV','wst'=>'WST','xaf'=>'XAF','xcd'=>'XCD','xof'=>'XOF','xpf'=>'XPF','yer'=>'YER','zar'=>'ZAR','zmw'=>'ZMW');
							foreach ($args as $key => $value) { ?>
								<option value="<?php echo $key; ?>" <?php if (get_option('wpcrm_system_default_currency') == $key) { echo 'selected'; } ?> ><?php echo $value; ?></option>
							<?php } ?>
							</select>
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<strong><?php _e('Currency Format', 'wp-crm-system'); ?></strong><span class="dashicons dashicons-editor-help" title="<?php _e( 'Set your preferred currency and numeral settings for reports.', 'wp-crm-system'); ?>"></span>
							</td>
						</tr>
						<tr>
							<td>
								<?php _e('Thousands separator', 'wp-crm-system'); ?>
							</td>
							<td>
								<?php _e('Decimal point', 'wp-crm-system'); ?>
							</td>
							<td>
								<?php _e('Number of decimals', 'wp-crm-system'); ?>
							</td>
						</tr>
						<tr>
							<td>
								<input type="text" name="wpcrm_system_report_currency_thousand_separator" size="5" value="<?php echo get_option('wpcrm_system_report_currency_thousand_separator'); ?>" />
							</td>
							<td>
								<input type="text" name="wpcrm_system_report_currency_decimal_point" size="5" value="<?php echo get_option('wpcrm_system_report_currency_decimal_point'); ?>" />
							</td>
							<td>
								<input type="text" name="wpcrm_system_report_currency_decimals" size="5" value="<?php echo get_option('wpcrm_system_report_currency_decimals'); ?>" />
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<strong><?php _e( 'Searchable Menus', 'wp-crm-system' ); ?></strong><span class="dashicons dashicons-editor-help" title="<?php _e( 'If you have a large number of records to be displayed in a drop down menu, this option will allow you to filter results by typing instead of scrolling through the whole list.', 'wp-crm-system' ); ?>"></span>
							</td>
							<td>
								<input type="checkbox" value="on" name="wpcrm_system_searchable_dropdown" <?php if( 'on' == get_option( 'wpcrm_system_searchable_dropdown' ) ) echo 'checked'; ?> />
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<strong><?php _e( 'Hide Other User Content', 'wp-crm-system' ); ?></strong><span class="dashicons dashicons-editor-help" title="<?php _e( 'This option will hide any entry in WP-CRM System that the current user did not create. Administrators will still have full access.', 'wp-crm-system' ); ?>"></span>
							</td>
							<td>
								<input type="checkbox" value="yes" name="wpcrm_hide_others_posts" <?php if( 'yes' == get_option( 'wpcrm_hide_others_posts' ) ) echo 'checked'; ?> />
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<strong><?php _e( 'Google Maps API Key', 'wp-crm-system' ); ?></strong><span class="dashicons dashicons-editor-help" title="<?php _e( 'Enter a valid Google Maps API key in order to correctly display the map view in Contacts and Organizations.', 'wp-crm-system' ); ?>"></span>
							</td>
							<td>
								<input type="text" value="<?php echo get_option( 'wpcrm_system_gmap_api' ); ?>" name="wpcrm_system_gmap_api" size="10" />
							</td>
						</tr>
						<tr>
							<td colspan="3"><input type="hidden" name="wpcrm_system_settings_initial" value="set" /><input type="hidden" name="wpcrm_system_settings_update" value="update" /><?php submit_button(); ?></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	<?php }
}
function wpcrm_system_dashboard_projects_box() { ?>
	<div class="wpcrm-dashboard">
		<h2><?php _e('Your Projects', 'wp-crm-system') . wp_crm_user_projects( 'value' ); ?></h2>
		<?php wp_crm_user_projects( 'list' ); ?>
	</div>
<?php }
function wpcrm_system_dashboard_tasks_box() { ?>
	<div class="wpcrm-dashboard">
		<h2><?php _e('Your Tasks','wp-crm-system'); ?></h2>
		<?php wp_crm_user_tasks(); ?>
	</div>
<?php }
function wpcrm_system_dashboard_opportunities_box() { ?>
	<div class="wpcrm-dashboard">
		<h2><?php _e('Your Opportunities','wp-crm-system') . wp_crm_user_opportunities( 'value' ); ?></h2>
		<?php wp_crm_user_opportunities( 'list' ); ?>
	</div>
<?php }
function wpcrm_system_dashboard_campaigns_box() { ?>
	<div class="wpcrm-dashboard">
		<h2><?php _e('Your Campaigns','wp-crm-system'); ?></h2>
		<?php wp_crm_user_campaigns(); ?>
	</div>
<?php }
