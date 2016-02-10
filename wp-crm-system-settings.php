<?php defined( 'ABSPATH' ) OR exit; ?>
<?php 
function load_jquery() {
    wp_enqueue_script( 'jquery' );
}
add_action( 'admin_enqueue_scripts', 'load_jquery' );
?>
<?php if( isset($_GET['settings-updated']) ) { ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Settings saved.', 'wp-crm-system') ?></strong></p>
    </div>
<?php }
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
?>
<h2 class="nav-tab-wrapper">
	<a class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=general"><?php _e('General', 'wp-crm-system') ?></a>
	<a class="nav-tab <?php echo $active_tab == 'categories' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=categories"><?php _e('Categories', 'wp-crm-system') ?></a>
	<?php if (defined('WPCRM_IMPORT_CONTACTS') || defined('WPCRM_IMPORT_OPPORTUNITIES') || defined('WPCRM_IMPORT_ORGANIZATIONS') || defined('WPCRM_IMPORT_TASKS') || defined('WPCRM_IMPORT_PROJECTS') || defined('WPCRM_IMPORT_CAMPAIGNS')) { ?>
	<a class="nav-tab <?php echo $active_tab == 'import' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=import"><?php _e('Import', 'wp-crm-system') ?></a>
	<?php } 
	if (defined('WPCRM_NINJA_FORMS_CONNECT')) { ?>
	<a class="nav-tab <?php echo $active_tab == 'ninja-connect' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=ninja-connect"><?php _e('Ninja Forms', 'wp-crm-system') ?></a>
	<?php } 
	if (defined('WPCRM_GRAVITY_FORMS_CONNECT')) { ?>
	<a class="nav-tab <?php echo $active_tab == 'gravity-connect' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=gravity-connect"><?php _e('Gravity Forms', 'wp-crm-system') ?></a>
	<?php } 
	if (defined('WPCRM_SLACK_NOTIFICATIONS')) { ?>
	<a class="nav-tab <?php echo $active_tab == 'slack-notifications' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=slack-notifications"><?php _e('Slack', 'wp-crm-system') ?></a>
	<?php } 
	if (defined('WPCRM_EMAIL_NOTIFICATIONS')) { ?>
	<a class="nav-tab <?php echo $active_tab == 'email-notifications' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=email-notifications"><?php _e('Email', 'wp-crm-system') ?></a>
	<?php } 
	if (defined('WPCRM_ZENDESK')) { ?>
	<a class="nav-tab <?php echo $active_tab == 'zendesk' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=zendesk"><?php _e('Zendesk', 'wp-crm-system') ?></a>
	<?php } 
	if (defined('WPCRM_CONTACT_FROM_USER') || defined('WPCRM_DROPBOX_CONNECT') || defined('WPCRM_EMAIL_NOTIFICATIONS') || defined('WPCRM_GRAVITY_FORMS_CONNECT') || defined('WPCRM_IMPORT_CAMPAIGNS') || defined('WPCRM_IMPORT_CONTACTS') || defined('WPCRM_IMPORT_OPPORTUNITIES') || defined('WPCRM_IMPORT_ORGANIZATIONS') || defined('WPCRM_IMPORT_PROJECTS') || defined('WPCRM_IMPORT_TASKS') || defined('WPCRM_NINJA_FORMS_CONNECT') || defined('WPCRM_SLACK_NOTIFICATIONS') || defined('WPCRM_ZENDESK')) { ?>
	<a class="nav-tab <?php echo $active_tab == 'licenses' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=licenses"><?php _e('Licenses', 'wp-crm-system') ?></a>
	<?php } ?>
</h2>
<?php 
if ($active_tab == 'general') {
	wpcrm_general_settings_content();
}
if ($active_tab == 'categories') {
	wpcrm_categories_settings_content();
}
if ($active_tab == 'import') {
	wpcrm_import_settings_content();
}
if ($active_tab == 'ninja-connect') {
	wpcrm_nf_settings_content();
}
if ($active_tab == 'gravity-connect') {
	wpcrm_gf_settings_content();
}
if ($active_tab == 'slack-notifications') {
	wpcrm_sn_settings_content();
}
if ($active_tab == 'email-notifications') {
	wpcrm_email_settings_content();
}
if ($active_tab == 'zendesk') {
	wpcrm_zendesk_settings_content();
}
if ($active_tab == 'licenses') {
	wpcrm_license_keys();
}
function wpcrm_general_settings_content() { ?>
	<div class="wrap">
		<div>
			<h2><?php _e('WP CRM System Settings', 'wp-crm-system'); ?></h2>
			
			
			<form id="wpcrm_settings" name="wpcrm_settings" method='post' action='options.php'>
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( 'wpcrm_system_settings_main_group' ); ?>
				<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
					<tbody>
						<tr>
							<td><input type="hidden" name="action" value="update" /><?php submit_button(); ?></td>
							<td></td>
						</tr>
						<tr>
							<td>
								<strong><?php _e('Select the user role that should have access to WP-CRM System.', 'wp-crm-system'); ?></strong><br />
								<?php _e('Roles are listed in order of seniority (Administrator is highest, Subscriber is lowest). All roles higher than, and including the role you select will have access to WP-CRM System.', 'wp-crm-system'); ?><br />
								<em><?php _e('Example: Selecting Author will allow access to Author, Editor, and Administrator roles.', 'wp-crm-system'); ?></em>
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
							<td>
								<strong><?php _e('Select the default currency to be used when assigning values to projects or opportunities.', 'wp-crm-system'); ?></strong>
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
							<td>
								<strong><?php _e('Currency format for reports.', 'wp-crm-system'); ?></strong>
							</td>
							<td>
								<input type="text" name="wpcrm_system_report_currency_decimals" size="5" value="<?php echo get_option('wpcrm_system_report_currency_decimals'); ?>" /> <?php _e('Number of decimals', 'wp-crm-system'); ?><br />
								<input type="text" name="wpcrm_system_report_currency_decimal_point" size="5" value="<?php echo get_option('wpcrm_system_report_currency_decimal_point'); ?>" /> <?php _e('Decimal point', 'wp-crm-system'); ?><br />
								<input type="text" name="wpcrm_system_report_currency_thousand_separator" size="5" value="<?php echo get_option('wpcrm_system_report_currency_thousand_separator'); ?>" /> <?php _e('Thousands separator', 'wp-crm-system'); ?><br />
							</td>
						</tr>
						<tr>
							<td>
								<strong><?php _e('Select the date format to be used in reports.', 'wp-crm-system'); ?></strong><br />
								<?php _e('Dates in the dropdown menu are today as displayed in the different available formats.', 'wp-crm-system'); ?>
							</td>
							<td>
							<?php $formats = array(
									'yy-M-d'=>'Y-M-j',
									'M d, y'=>'M j, y',
									'MM dd, yy'=>'F d, Y',
									'dd.mm.y'=>'d.m.y',
									'dd/mm/y'=>'d/m/y',
									'd MM yy'=>'j F Y',
									'D d MM yy'=>'D j F Y',
									'DD, MM d, yy'=>'l, F j, Y',
								);?>
								<select name="wpcrm_system_date_format" id="select_date_format">
									<?php foreach($formats as $key => $value) { ?>
										<option value="<?php echo $key; ?>" <?php if (get_option('wpcrm_system_date_format') == $key) {echo 'selected'; } ?> id="<?php echo $value; ?>"><?php echo date($value); ?></option>
									<?php } ?>
								</select>
								<input type="hidden" id="wpcrm_system_php_date_format" name="wpcrm_system_php_date_format" value="<?php echo get_option('wpcrm_system_php_date_format'); ?>" />
								<script>
									jQuery(document).ready(function () {
										jQuery("#select_date_format").on('change',function() {
											var php_id = jQuery(this).find('option:selected').attr("id");
											jQuery('#wpcrm_system_php_date_format').val(php_id);
										});
										
									});
									</script>
							</td>
						</tr>
						<?php wpcrm_dropbox_settings_content(); ?>
						<tr>
							<td><input type="hidden" name="action" value="update" /><?php submit_button(); ?></td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>
<?php }
function wpcrm_categories_settings_content() {
	$categories = array('contact-type'=>'Contact Categories','task-type'=>'Task Categories','organization-type'=>'Organization Categories','opportunity-type'=>'Opportunity Categories','project-type'=>'Project Categories','campaign-type'=>'Campaign Categories');?>
	<div class="wrap">
		<div>
			<h2><?php _e('WP CRM System Categories', 'wp-crm-system'); ?></h2>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<?php foreach ($categories as $key => $value) { ?>
					<tr>
						<td><a href="edit-tags.php?taxonomy=<?php echo $key; ?>"><?php echo $value; ?></a></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
<?php }
// Load paid plugin settings if plugins are installed and active.
function wpcrm_import_settings_content() {
	include(plugin_dir_path( __FILE__ ) . 'includes/return_bytes.php');
	$plugin_base = 'wp-crm-system-';
	$import_types = array($plugin_base.'import-organizations',$plugin_base.'import-contacts',$plugin_base.'import-opportunities',$plugin_base.'import-tasks',$plugin_base.'import-projects',$plugin_base.'import-campaigns');
	foreach($import_types as $import_type) {
		if(is_plugin_active($import_type.'/'.$import_type.'.php')) {
			include(WP_PLUGIN_DIR .'/'.$import_type.'/import.php');
		}
	}
}
function wpcrm_nf_settings_content() {
	$plugin = 'wp-crm-system-ninja-form-connect';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/import.php');
	}
}
function wpcrm_gf_settings_content() {
	$plugin = 'wp-crm-system-gravity-form-connect';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/import.php');
	}
}
function wpcrm_sn_settings_content() {
	$plugin = 'wp-crm-system-slack-notifications';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/settings.php');
	}
}
function wpcrm_email_settings_content() {
	$plugin = 'wp-crm-system-email-notifications';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/settings.php');
	}
}
function wpcrm_zendesk_settings_content() {
	$plugin = 'wp-crm-system-zendesk';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/settings.php');
	}
}
function wpcrm_dropbox_settings_content() {
	$plugin = 'wp-crm-system-dropbox';
	if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
		include(WP_PLUGIN_DIR .'/'.$plugin.'/settings.php');
	}
}
function wpcrm_license_keys() {
	// Provides a way to activate license keys only if an add-on plugin is installed.
	$plugin_base = 'wp-crm-system-';
	$plugins = array($plugin_base.'import-organizations',$plugin_base.'import-contacts',$plugin_base.'import-opportunities',$plugin_base.'import-tasks',$plugin_base.'import-projects',$plugin_base.'import-campaigns',$plugin_base.'contact-user',$plugin_base.'gravity-form-connect',$plugin_base.'ninja-form-connect',$plugin_base.'slack-notifications',$plugin_base.'email-notifications',$plugin_base.'dropbox',$plugin_base.'zendesk'); ?>
	<div class="wrap">
		<h2><?php _e('Premium Plugin Licenses','wp-crm-system'); ?></h2>
		<form method="post" action="options.php">
		<?php settings_fields('wpcrm_license_group'); ?>
			<table class="form-table">
				<tbody>
					<?php foreach($plugins as $plugin) {
						if(is_plugin_active($plugin.'/'.$plugin.'.php')) {
							include(WP_PLUGIN_DIR .'/'.$plugin.'/license.php');
						}
					} ?>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
<?php }