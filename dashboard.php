<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
include('includes/wp-crm-system-dashboard-reports.php');
?>
<div class="wp-crm-dashboard-boxes wp-crm-first">
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
<div class="wp-crm-dashboard-boxes">
	<h2><?php _e('Extensions', 'wp-crm-system'); ?></h2>
	<?php
	$plugin_base = 'wp-crm-system-';
	$plugins_active = '';
	$active_count = 0;

	$plugins = array(
		'import-organizations'	=> 'wpcrm_import_organizations_license_status',
		'import-contacts'		=> 'wpcrm_import_contacts_license_status',
		'import-opportunities'	=> 'wpcrm_import_opportunities_license_status',
		'import-tasks'			=> 'wpcrm_import_tasks_license_status',
		'import-projects'		=> 'wpcrm_import_projects_license_status',
		'import-campaigns'		=> 'wpcrm_import_campaigns_license_status',
		'custom-fields'			=> 'wpcrm_custom_fields_license_status',
		'contact-user'			=> 'wpcrm_contact_from_user_license_status',
		'gravity-form-connect'	=> 'wpcrm_gravity_forms_connect_license_status',
		'invoicing'				=> 'wpcrm_invoicing_license_status',
		'ninja-form-connect'	=> 'wpcrm_ninja_forms_connect_license_status',
		'slack-notifications'	=> 'wpcrm_slack_notifications_license_status',
		'email-notifications'	=> 'wpcrm_email_notifications_license_status',
		'dropbox'				=> 'wpcrm_dropbox_license_status',
		'zendesk'				=> 'wpcrm_zendesk_license_status'
	);

	foreach( $plugins as $plugin => $status) {
		if( is_plugin_active( $plugin_base . $plugin.'/'.$plugin_base . $plugin.'.php' ) ) {
			$plugins_active = 'yes';
			$active_count++;
			$plugin_nicename = ucwords( str_replace( '-', ' ', $plugin ) );
			$plugin_status = get_option( $status );
			if ( $plugin_status !== false && $plugin_status == 'valid' ) {
				$plugin_display = '<span style="color:green;">' . __( 'Active', 'wp-crm-system' ) . '</span>';
			} else {
				$plugin_display = '<span style="color:red;">' . __( 'Inactive', 'wp-crm-system' ) . '</span>';
			}
			echo '<div class="wp-crm-one-half wp-crm-first">' . $plugin_nicename . '</div><div class="wp-crm-one-half">' . $plugin_display . '</div>';
		}
	} ?>
	<div class="wp-crm-first">
	<?php if ( '' == $plugins_active ) {
		$url = admin_url( 'admin.php?page=wpcrm-extensions' );
		$link = sprintf( wp_kses( __( 'Take a look at our <a href="%s">extensions</a> to see how you can get more out of WP-CRM System.', 'wp-crm-system' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
		echo $link;
	} else {
		$license_url = admin_url( 'admin.php?page=wpcrm-settings&tab=licenses' );
		echo '<a href="' . $license_url . '">';
		printf( esc_html( _n( 'Manage license here', 'Manage licenses here', $active_count, 'wp-crm-system' ) ), $active_count );
		echo '</a>';
	}
	?>
	</div>
</div>

<div class="wp-crm-dashboard-boxes">
	<h2><?php _e( 'Categories', 'wp-crm-system' ); ?></h2>
	<?php
	$categories = array('organization-type'=>'Organization Categories','contact-type'=>'Contact Categories','opportunity-type'=>'Opportunity Categories','project-type'=>'Project Categories','task-type'=>'Task Categories','campaign-type'=>'Campaign Categories');?>

	<ul>

	<?php foreach ($categories as $key => $value) { ?>
		<li><a href="edit-tags.php?taxonomy=<?php echo $key; ?>"><?php echo $value; ?></a></li>
	<?php } ?>
	<?php if (defined('WPCRM_INVOICING')) { ?>
		<li><a href="edit-tags.php?taxonomy=invoice-type"><?php _e('Invoice Categories','wp-crm-system'); ?></a></li>
	<?php } ?>
	</ul>
</div>

<div class="wp-crm-dashboard-boxes">
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
						$roles = array(
							'manage_options'	=>	__('Administrator', 'wp-crm-system'),
							'edit_pages'		=>	__('Editor', 'wp-crm-system'),
							'publish_posts'		=>	__('Author', 'wp-crm-system'),
							'edit_posts'		=>	__('Contributor', 'wp-crm-system'),
							'read'				=>	__('Subscriber', 'wp-crm-system'),
						);?>
						<select name="wpcrm_system_select_user_role"> <?php
						  foreach ($roles as $role=>$name){
							if (get_option('wpcrm_system_select_user_role') == $role) { $selected = 'selected'; } else { $selected = ''; } ?>
							<option value="<?php echo $role; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
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
						<option value="<?php echo $key; ?>" <?php if (get_option('wpcrm_system_default_currency') == $key) { echo 'selected'; } ?>><?php echo $value; ?></option>
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
				<?php wpcrm_dropbox_settings_content(); ?>
				<tr>
					<td colspan="2"><input type="hidden" name="action" value="update" /><?php submit_button(); ?></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<div class="wp-crm-dashboard-boxes wp-crm-first">
	<h2><?php _e('Your Projects', 'wp-crm-system') . wp_crm_user_projects( 'value' ); ?></h2>
	<?php wp_crm_user_projects( 'list' ); ?>
</div>
<div class="wp-crm-dashboard-boxes">
	<h2><?php _e('Your Tasks','wp-crm-system'); ?></h2>
	<?php wp_crm_user_tasks(); ?>
</div>
<div class="wp-crm-dashboard-boxes">
	<h2><?php _e('Your Opportunities','wp-crm-system') . wp_crm_user_opportunities( 'value' ); ?></h2>
	<?php wp_crm_user_opportunities( 'list' ); ?>
</div>
<div class="wp-crm-dashboard-boxes">
	<h2><?php _e('Your Campaigns','wp-crm-system'); ?></h2>
	<?php wp_crm_user_campaigns(); ?>
</div>
