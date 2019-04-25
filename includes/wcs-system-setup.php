<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}
function wpcrm_system_system_info_tab() {
	//Get current dashboard tab name
	global $wpcrm_active_tab; ?>
	<a class="nav-tab <?php echo $wpcrm_active_tab == 'settings' ? 'nav-tab-active' : ''; ?>" href="?page=wpcrm-settings&tab=settings"><?php _e('Settings', 'wp-crm-system') ?></a>
<?php }
add_action( 'wpcrm_system_settings_tab', 'wpcrm_system_system_info_tab', 2 );

function wpcrm_system_system_info_subtab() {
	global $wpcrm_active_tab, $wpcrm_active_subtab;
	if (isset( $wpcrm_active_tab ) && 'settings' == $wpcrm_active_tab ) { ?>
		<li>
			<a class="<?php echo in_array($wpcrm_active_subtab, array( '', 'settings' ) ) ? 'current' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=wpcrm-settings&tab=settings&subtab=settings' ); ?>"><?php _e( 'Settings', 'wp-crm-system' ); ?> </a>
		</li>
		<li>
			|
			<a class="<?php echo $wpcrm_active_subtab == 'system-info' ? 'current' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=wpcrm-settings&tab=settings&subtab=system-info' ); ?>"><?php _e( 'System Info', 'wp-crm-system' ); ?> </a>
		</li>
		<?php if ( has_action( 'wpcrm_system_license_key_field' ) ) { ?>
		<li>
			|
			<a class="<?php echo $wpcrm_active_subtab == 'licenses' ? 'current' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=wpcrm-settings&tab=settings&subtab=licenses' ); ?>"><?php _e( 'Licenses', 'wp-crm-system' ); ?> </a>
		</li>
		<?php }
	}
}

add_action( 'wpcrm_system_settings_subtab', 'wpcrm_system_system_info_subtab' );

/**
 * Create the main settings page
 */
add_action( 'wpcrm_system_settings_content', 'wpcrm_system_main_settings' );

function wpcrm_system_main_settings() {
	global $wpcrm_active_tab, $wpcrm_active_subtab;
	if( 'settings' == $wpcrm_active_tab && ( '' == $wpcrm_active_subtab || 'settings' == $wpcrm_active_subtab ) ){
		//Only show to administrators
		if ( current_user_can( 'manage_options' ) )  { ?>
			<div class="wrap">
			<h2><?php _e('WP-CRM System Settings', 'wp-crm-system'); ?></h2>
				<form id="wpcrm_settings" name="wpcrm_settings" method='post' action='options.php'>
					<?php wp_nonce_field( 'update-options' ); ?>
					<?php settings_fields( 'wpcrm_system_settings_main_group' ); ?>
					<table>
						<tbody>
							<tr>
								<td>
									<strong><?php _e('Access Level', 'wp-crm-system'); ?></strong><span class="wpcrm-system-help-tip dashicons dashicons-editor-help" title="<?php _e('Roles are listed in order of seniority (Administrator is highest, Subscriber is lowest). All roles higher than, and including the role you select will have access to WP-CRM System.', 'wp-crm-system'); ?>"></span>
								</td>
								<td>
									<?php
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
								<td>
									<strong><?php _e('Default Currency', 'wp-crm-system'); ?></strong><span class="wpcrm-system-help-tip dashicons dashicons-editor-help" title="<?php _e('Currency to be used when assigning values to projects or opportunities.', 'wp-crm-system'); ?>"></span>
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
								<td>
									<strong><?php _e('Currency Format', 'wp-crm-system'); ?></strong><span class="wpcrm-system-help-tip dashicons dashicons-editor-help" title="<?php _e( 'Set your preferred currency and numeral settings for reports.', 'wp-crm-system'); ?>"></span>
								</td>
								<td></td>
							</tr>
							<tr>
								<td>
									<strong><?php _e('Thousands separator', 'wp-crm-system'); ?></strong>
								</td>
								<td>
									<input type="text" name="wpcrm_system_report_currency_thousand_separator" size="5" value="<?php echo get_option('wpcrm_system_report_currency_thousand_separator'); ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<strong><?php _e('Decimal point', 'wp-crm-system'); ?></strong>
								</td>
								<td>
									<input type="text" name="wpcrm_system_report_currency_decimal_point" size="5" value="<?php echo get_option('wpcrm_system_report_currency_decimal_point'); ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<strong><?php _e('Number of decimals', 'wp-crm-system'); ?></strong>
								</td>
								<td>
									<input type="text" name="wpcrm_system_report_currency_decimals" size="5" value="<?php echo get_option('wpcrm_system_report_currency_decimals'); ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<strong><?php _e( 'Show Org. Address', 'wp-crm-system' ); ?></strong><span class="wpcrm-system-help-tip dashicons dashicons-editor-help" title="<?php _e( 'If you have multiple organizations with the same name and possibly different locations, it would be difficult to distinguish which is which in the various dropdown menus. Check this box to add the Address 1 field for each organization to the dropdown menu.', 'wp-crm-system' ); ?>"></span>
								</td>
								<td>
									<input type="checkbox" value="true" name="wpcrm_system_show_org_address" <?php checked( get_option( 'wpcrm_system_show_org_address' ), 'true' ); ?> />
								</td>
							</tr>
							<tr>
								<td>
									<strong><?php _e( 'Hide Other User Content', 'wp-crm-system' ); ?></strong><span class="wpcrm-system-help-tip dashicons dashicons-editor-help" title="<?php _e( 'This option will hide any entry in WP-CRM System that the current user did not create. Administrators will still have full access.', 'wp-crm-system' ); ?>"></span>
								</td>
								<td>
									<input type="checkbox" value="yes" name="wpcrm_hide_others_posts" <?php if( 'yes' == get_option( 'wpcrm_hide_others_posts' ) ) echo 'checked'; ?> />
								</td>
							</tr>
							<tr>
								<td>
									<strong><?php _e( 'Google Maps API Key', 'wp-crm-system' ); ?></strong><span class="wpcrm-system-help-tip dashicons dashicons-editor-help" title="<?php _e( 'Enter a valid Google Maps API key in order to correctly display the map view in Contacts and Organizations.', 'wp-crm-system' ); ?>"></span>
								</td>
								<td>
									<input type="text" value="<?php echo get_option( 'wpcrm_system_gmap_api' ); ?>" name="wpcrm_system_gmap_api" size="10" />
								</td>
							</tr>
							<?php
							$gdpr_page = get_option( 'wpcrm_system_gdpr_page_id' );
							if ( $gdpr_page && is_numeric( $gdpr_page ) ){ ?>
							<tr>
								<td>
									<strong><?php _e( 'GDPR Page', 'wp-crm-system' ); ?></strong> <?php _e( 'This setting will be depreciated. Use WordPress built in Privacy Tools.', 'wp-crm-system' ); ?><span class="wpcrm-system-help-tip dashicons dashicons-editor-help" title="<?php _e( 'Select the page that has the [wpcrm_system_gdpr] shortcode. If you do not have contacts who are located in the European Union, you do not need to select a page here.', 'wp-crm-system' ); ?>"></span>
								</td>
								<td>
									<?php wp_dropdown_pages( array(
										'show_option_none'	=> __( 'Select a GDPR Page', 'wp-crm-system' ),
										'name'				=> 'wpcrm_system_gdpr_page_id',
										'selected'			=> get_option( 'wpcrm_system_gdpr_page_id' )
										) ); ?>
								</td>
							</tr>
							<?php } ?>
							<tr>
								<td><input type="hidden" name="wpcrm_system_settings_initial" value="set" /><input type="hidden" name="wpcrm_system_settings_update" value="update" /><?php submit_button(); ?></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
	<?php }
	}
}

/**
 * Create the license key section
 *
 */
add_action( 'wpcrm_system_settings_content', 'wpcrm_system_license_keys' );

function wpcrm_system_license_keys() {
	global $wpcrm_active_tab, $wpcrm_active_subtab;
	// Provides a way to activate license keys only if an add-on plugin is installed.
	if ( 'settings' == $wpcrm_active_tab && 'licenses' == $wpcrm_active_subtab ) { ?>
		<div class="wrap">
			<h2><?php _e('Premium Plugin Licenses','wp-crm-system'); ?></h2>
			<form method="post" action="options.php">
			<?php settings_fields('wpcrm_license_group'); ?>
				<table class="form-table">
					<tbody>
					<?php do_action( 'wpcrm_system_license_key_field' ); ?>
					</tbody>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php }
}

/**
 * Create the system info report for support tickets
 *
 */
add_action( 'wpcrm_system_settings_content', 'wpcrm_system_info' );

function wpcrm_system_info() {
	global $wpdb, $wpcrm_active_tab, $wpcrm_active_subtab;
	if( 'settings' == $wpcrm_active_tab && 'system-info' == $wpcrm_active_subtab ){
		if ( ! class_exists( 'Browser' ) )
			require_once WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/Browser.php';

		$browser = new Browser();
		if ( get_bloginfo( 'version' ) < '3.4' ) {
			$theme_data = get_theme_data( get_stylesheet_directory() . '/style.css' );
			$theme      = $theme_data['Name'] . ' ' . $theme_data['Version'];
		} else {
			$theme_data = wp_get_theme();
			$theme      = $theme_data->Name . ' ' . $theme_data->Version;
		}

		// Try to identify the hosting provider
		$host = false;
		if( defined( 'WPE_APIKEY' ) ) {
			$host = 'WP Engine';
		} elseif( defined( 'PAGELYBIN' ) ) {
			$host = 'Pagely';
		}
	?>
		<div class="wrap">
			<h2><?php _e( 'System Information', 'wp-crm-system' ); ?></h2><br/>
			<textarea class="wpcrm-system-help-tip" readonly="readonly" onclick="this.focus();this.select()" id="system-info-textarea" name="wp-crm-system-sysinfo" title="<?php _e( 'To copy the system info, click inside the box then press Ctrl + C (PC) or Cmd + C (Mac).', 'wp-crm-system' ); ?>">
### Begin System Info ###

## Please include this information when posting support requests ##

<?php do_action( 'wpcrm_system_info_before' ); ?>

Multisite:                <?php echo is_multisite() ? 'Yes' . "\n" : 'No' . "\n" ?>

SITE_URL:                 <?php echo site_url() . "\n"; ?>
HOME_URL:                 <?php echo home_url() . "\n"; ?>

WP-CRM SYSTEM SETTINGS:

Access Level:             <?php echo get_option( 'wpcrm_system_select_user_role' ) . "\n"; ?>
Default Currency:         <?php echo get_option( 'wpcrm_system_default_currency' ) . "\n"; ?>
Thousands Separator:      <?php echo get_option( 'wpcrm_system_report_currency_thousand_separator' ) . "\n"; ?>
Decimal Point:            <?php echo get_option( 'wpcrm_system_report_currency_decimal_point' ) . "\n"; ?>
Number of Decimals:       <?php echo get_option( 'wpcrm_system_report_currency_decimals' ) . "\n"; ?>
Show Org. Address:        <?php echo '' != get_option( 'wpcrm_system_show_org_address' ) ? "Yes" : "No"; ?><?php echo "\n"; ?>
Hide Other User Content:  <?php echo '' != get_option( 'wpcrm_hide_others_posts' ) ? "Yes" : "No"; ?><?php echo "\n"; ?>
Google Maps API:          <?php echo '' != get_option( 'wpcrm_system_gmap_api' ) ? "Yes" : "No"; ?><?php echo "\n"; ?>

Initial Settings:         <?php echo get_option( 'wpcrm_system_settings_initial' ) . "\n"; ?>
Email Org Filter:         <?php echo '' != get_option( 'wpcrm_system_email_organization_filter' ) ? get_option( 'wpcrm_system_email_organization_filter' ) : "Not Set"; ?> <?php echo "\n"; ?>
JS Date Format:           <?php echo get_option( 'wpcrm_system_date_format' ) . "\n"; ?>
PHP Date Format:          <?php echo get_option( 'wpcrm_system_php_date_format' ) . "\n"; ?>
WordPress Date Format:    <?php echo get_option( 'date_format' ) . "\n"; ?>

WP-CRM System Version:    <?php echo WP_CRM_SYSTEM_VERSION . "\n"; ?>
WordPress Version:        <?php echo get_bloginfo( 'version' ) . "\n"; ?>
Permalink Structure:      <?php echo get_option( 'permalink_structure' ) . "\n"; ?>
Active Theme:             <?php echo $theme . "\n"; ?>
Can Users Register:       <?php echo '1' == get_option( 'users_can_register' ) ? 'Yes' : 'No'; ?> <?php echo "\n"; ?>
<?php if( $host ) : ?>
Host:                     <?php echo $host . "\n"; ?>
<?php endif; ?>

Registered Post Stati:    <?php echo implode( ', ', get_post_stati() ) . "\n\n"; ?>

<?php echo $browser; ?>

PHP Version:              <?php echo '7.2' > PHP_VERSION ? PHP_VERSION . " Upgrade PHP to at least 7.2 Recommended!" : PHP_VERSION; ?> <?php echo "\n"; ?>
MySQL Version:            <?php $link = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD ); echo mysqli_get_server_info( $link ) . "\n"; ?>
Web Server Info:          <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?>

WordPress Memory Limit:   <?php echo WP_MEMORY_LIMIT; ?><?php echo "\n"; ?>
PHP Safe Mode:            <?php echo ini_get( 'safe_mode' ) ? "Yes" : "No\n"; ?>
PHP Memory Limit:         <?php echo ini_get( 'memory_limit' ) . "\n"; ?>
PHP Upload Max Size:      <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
PHP Post Max Size:        <?php echo ini_get( 'post_max_size' ) . "\n"; ?>
PHP Upload Max Filesize:  <?php echo ini_get( 'upload_max_filesize' ) . "\n"; ?>
PHP Time Limit:           <?php echo ini_get( 'max_execution_time' ) . "\n"; ?>
PHP Max Input Vars:       <?php echo ini_get( 'max_input_vars' ) . "\n"; ?>
PHP Arg Separator:        <?php echo ini_get( 'arg_separator.output' ) . "\n"; ?>
PHP Allow URL File Open:  <?php echo ini_get( 'allow_url_fopen' ) ? "Yes" : "No\n"; ?>
PHP mail() function:      <?php echo function_exists( 'mail' ) ? "Yes" : "No\n"; ?>

WP_DEBUG:                 <?php echo defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' . "\n" : 'Disabled' . "\n" : 'Not set' . "\n" ?>

WP Table Prefix:          <?php echo "Length: ". strlen( $wpdb->prefix ); echo " Status:"; if ( strlen( $wpdb->prefix )>16 ) {echo " ERROR: Too Long";} else {echo " Acceptable";} echo "\n"; ?>

Show On Front:            <?php echo get_option( 'show_on_front' ) . "\n" ?>
Page On Front:            <?php $id = get_option( 'page_on_front' ); echo get_the_title( $id ) . ' (#' . $id . ')' . "\n" ?>
Page For Posts:           <?php $id = get_option( 'page_for_posts' ); echo get_the_title( $id ) . ' (#' . $id . ')' . "\n" ?>

<?php
$request['cmd'] = '_notify-validate';

$params = array(
	'sslverify'		=> false,
	'timeout'		=> 60,
	'user-agent'	=> 'WP-CRM SYSTEM/' . WP_CRM_SYSTEM_VERSION,
	'body'			=> $request
);

$response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );

if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
	$WP_REMOTE_POST =  'wp_remote_post() works' . "\n";
} else {
	$WP_REMOTE_POST =  'wp_remote_post() does not work' . "\n";
}
?>
WP Remote Post:           <?php echo $WP_REMOTE_POST; ?>

Session:                  <?php echo isset( $_SESSION ) ? 'Enabled' : 'Disabled'; ?><?php echo "\n"; ?>
Session Name:             <?php echo esc_html( ini_get( 'session.name' ) ); ?><?php echo "\n"; ?>
Cookie Path:              <?php echo esc_html( ini_get( 'session.cookie_path' ) ); ?><?php echo "\n"; ?>
Save Path:                <?php echo esc_html( ini_get( 'session.save_path' ) ); ?><?php echo "\n"; ?>
Use Cookies:              <?php echo ini_get( 'session.use_cookies' ) ? 'On' : 'Off'; ?><?php echo "\n"; ?>
Use Only Cookies:         <?php echo ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off'; ?><?php echo "\n"; ?>

DISPLAY ERRORS:           <?php echo ( ini_get( 'display_errors' ) ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A'; ?><?php echo "\n"; ?>
FSOCKOPEN:                <?php echo ( function_exists( 'fsockopen' ) ) ? 'Your server supports fsockopen.' : 'Your server does not support fsockopen.'; ?><?php echo "\n"; ?>
cURL:                     <?php echo ( function_exists( 'curl_init' ) ) ? 'Your server supports cURL.' : 'Your server does not support cURL.'; ?><?php echo "\n"; ?>
SOAP Client:              <?php echo ( class_exists( 'SoapClient' ) ) ? 'Your server has the SOAP Client enabled.' : 'Your server does not have the SOAP Client enabled.'; ?><?php echo "\n"; ?>
SUHOSIN:                  <?php echo ( extension_loaded( 'suhosin' ) ) ? 'Your server has SUHOSIN installed.' : 'Your server does not have SUHOSIN installed.'; ?><?php echo "\n"; ?>

ACTIVE PLUGINS:

<?php
$plugins = get_plugins();
$active_plugins = get_option( 'active_plugins', array() );

foreach ( $plugins as $plugin_path => $plugin ) {
	// If the plugin isn't active, don't show it.
	if ( ! in_array( $plugin_path, $active_plugins ) )
		continue;

	echo $plugin['Name'] . ': ' . $plugin['Version'] ."\n";
}

if ( is_multisite() ) :
?>

NETWORK ACTIVE PLUGINS:

<?php
$plugins = wp_get_active_network_plugins();
$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

foreach ( $plugins as $plugin_path ) {
	$plugin_base = plugin_basename( $plugin_path );

	// If the plugin isn't active, don't show it.
	if ( ! array_key_exists( $plugin_base, $active_plugins ) )
		continue;

	$plugin = get_plugin_data( $plugin_path );

	echo $plugin['Name'] . ' :' . $plugin['Version'] ."\n";
}

endif;

do_action( 'wpcrm_system_info_after' );
?>
### End System Info ###</textarea>
			</div>
		</div>
	<?php
	}
}