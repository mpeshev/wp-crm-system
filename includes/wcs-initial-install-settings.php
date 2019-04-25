<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}
/* Initial install make sure settings are saved */
function wpcrm_system_initial_settings_notice__warning() {
	if ( 'set' != get_option( 'wpcrm_system_settings_initial' ) ) {
		$url = admin_url( 'admin.php?page=wpcrm-settings&tab=settings&subtab=settings' );
		$link = sprintf( wp_kses( __( 'Please visit the <a href="%s">WP-CRM System Dashboard Settings Tab</a> to set your options and complete set up.', 'wp-crm-system' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
	?>
		<div class="notice notice-warning">
			<p><?php echo $link; ?></p>
		</div>
<?php	}
}
add_action( 'admin_notices', 'wpcrm_system_initial_settings_notice__warning' );

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

//Register Settings

function wp_crm_system_activate_settings() {
	global $wpdb;
	global $wpcrm_system_recurring_db_name;
	global $wpcrm_system_db_version;

	add_option( 'wpcrm_system_select_user_role', 'manage_options' );
	add_option( 'wpcrm_system_default_currency', 'USD' );
	add_option( 'wpcrm_system_report_currency_decimals', 0 );
	add_option( 'wpcrm_system_report_currency_decimal_point', '.' );
	add_option( 'wpcrm_system_report_currency_thousand_separator', ',' );
	add_option( 'wpcrm_hide_others_posts','no' );
	add_option( 'wpcrm_system_settings_initial','' );
	add_option( 'wpcrm_system_show_org_address','' );
	add_option( 'wpcrm_system_email_organization_filter', '' );
	add_option( 'wpcrm_system_gmap_api', '' );
	add_option( 'wpcrm_system_gdpr_page_id', '' );
	add_option( '_wpcrm_enable_email_notification', '' );
	add_option( '_wpcrm_enable_html_email', '' );
	add_option( '_wpcrm_email_task_message', '' );
	add_option( '_wpcrm_email_opportunity_message', '' );
	add_option( '_wpcrm_email_project_message', '' );


	$terms = get_terms('contact-type');
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		foreach ( $terms as $term ) {
			add_option($term->slug.'-email-filter','');
		}
	}

	$charset_collate = $wpdb->get_charset_collate();

	// create the database table for recurring entries
	if ( $wpdb->get_var( "show tables like '$wpcrm_system_recurring_db_name'" ) != $wpcrm_system_recurring_db_name ) {
		$sql = "CREATE TABLE " . $wpcrm_system_recurring_db_name . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		project_task tinytext NOT NULL,
		project_task_id tinytext NOT NULL,
		start_date datetime NOT NULL,
		end_date datetime NOT NULL,
		frequency tinytext NOT NULL,
		number_per_frequency tinytext NOT NULL,
		UNIQUE KEY id (id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		@dbDelta( $sql );

		add_option( 'wpcrm_system_db_version', $wpcrm_system_db_version );
	}
	// Creates the schedule for recurring entries
	if ( !wp_next_scheduled( 'wp_crm_system_recurring_entry_processor' ) ){
		wp_schedule_event( time(), 'hourly', 'wp_crm_system_recurring_entry_processor' );
	}
}
function wp_crm_system_deactivate_settings() {
	delete_option( 'wpcrm_system_select_user_role' );
	delete_option( 'wpcrm_system_default_currency' );
	delete_option( 'wpcrm_system_report_currency_decimals' );
	delete_option( 'wpcrm_system_report_currency_decimal_point' );
	delete_option( 'wpcrm_system_report_currency_thousand_separator' );
	delete_option( 'wpcrm_hide_others_posts' );
	delete_option( 'wpcrm_system_settings_initial' );
	delete_option( 'wpcrm_system_show_org_address' );
	delete_option( 'wpcrm_system_date_format' );
	delete_option( 'wpcrm_system_php_date_format' );
	delete_option( 'wpcrm_system_email_organization_filter' );
	delete_option( 'wpcrm_system_gmap_api' );
	delete_option( 'wpcrm_system_gdpr_page_id' );
	delete_option( '_wpcrm_enable_email_notification' );
	delete_option( '_wpcrm_enable_html_email' );
	delete_option( '_wpcrm_email_task_message' );
	delete_option( '_wpcrm_email_opportunity_message' );
	delete_option( '_wpcrm_email_project_message' );

	$terms = get_terms( 'contact-type' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		foreach ( $terms as $term ) {
			delete_option($term->slug.'-email-filter');
		}
	}
	// Clears schedule for recurring entries
	wp_clear_scheduled_hook( 'wp_crm_system_recurring_entry_processor' );
}
add_action( 'admin_init', 'register_wpcrm_system_settings' );
function register_wpcrm_system_settings() {
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_select_user_role');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_default_currency');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_report_currency_decimals');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_report_currency_decimal_point');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_report_currency_thousand_separator');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_hide_others_posts');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_settings_initial');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_show_org_address');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_date_format');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_php_date_format');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_gmap_api');
	register_setting( 'wpcrm_system_settings_main_group', 'wpcrm_system_gdpr_page_id');
	register_setting( 'wpcrm_system_email_group','wpcrm_system_email_organization_filter' );
	register_setting( 'wpcrm-email-notifications', '_wpcrm_enable_email_notification' );
	register_setting( 'wpcrm-email-notifications', '_wpcrm_enable_html_email' );
	register_setting( 'wpcrm-email-notifications', '_wpcrm_email_task_message' );
	register_setting( 'wpcrm-email-notifications', '_wpcrm_email_opportunity_message' );
	register_setting( 'wpcrm-email-notifications', '_wpcrm_email_project_message' );

	$terms = get_terms( 'contact-type' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		foreach ( $terms as $term ) {
			register_setting( 'wpcrm_system_email_group', $term->slug . '-email-filter' );
		}
	}
	add_option( 'wpcrm_system_version', '1.2' );
}

/* Correct the date format if correct format is not being used */
add_action( 'admin_init', 'update_date_formats' );
function update_date_formats() {
	// Use standard WordPress date format set in Settings > General. Convert PHP date format to jQuery format for date pickers.
	$php_format = get_option( 'date_format' );
	$SYMBOLS_MATCHING = array(
		// Day
		'd' => 'dd',
		'D' => 'D',
		'j' => 'd',
		'l' => 'DD',
		'N' => '',
		'S' => '',
		'w' => '',
		'z' => 'o',
		// Week
		'W' => '',
		// Month
		'F' => 'MM',
		'm' => 'mm',
		'M' => 'M',
		'n' => 'm',
		't' => '',
		// Year
		'L' => '',
		'o' => '',
		'Y' => 'yy',
		'y' => 'y',
		// Time
		'a' => '',
		'A' => '',
		'B' => '',
		'g' => '',
		'G' => '',
		'h' => '',
		'H' => '',
		'i' => '',
		's' => '',
		'u' => ''
	);
	$jqueryui_format = "";
	$escaping = false;
	for($i = 0; $i < strlen($php_format); $i++) {
		$char = $php_format[$i];
		if($char === '\\') // PHP date format escaping character
		{
			$i++;
			if($escaping) $jqueryui_format .= $php_format[$i];
			else $jqueryui_format .= '\'' . $php_format[$i];
			$escaping = true;
		}
		else
		{
			if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
			if(isset($SYMBOLS_MATCHING[$char]))
			$jqueryui_format .= $SYMBOLS_MATCHING[$char];
			else
			$jqueryui_format .= $char;
		}
	}

	$js_acceptable = array( 'yy-M-d','M d, y','MM dd, yy','dd.mm.y','dd/mm/y','d MM yy','D d MM yy','DD, MM d, yy','MM d, yy','yy-mm-dd','dd/mm/yy','mm/dd/yy' );
	$php_acceptable = array( 'Y-M-j','M j, y','F d, Y','d.m.y','d/m/y','j F Y','D j F Y','l, F j, Y','F j, Y','Y-m-d','d/m/Y','m/d/Y' );
	if( !in_array( $jqueryui_format, $js_acceptable ) || !in_array( $php_format, $php_acceptable ) ) {
		update_option('wpcrm_system_date_format', 'MM d, yy' );
		update_option('wpcrm_system_php_date_format', 'F j, Y' );
	} else {
		update_option( 'wpcrm_system_date_format', $jqueryui_format );
		update_option( 'wpcrm_system_php_date_format', $php_format );
	}
}
