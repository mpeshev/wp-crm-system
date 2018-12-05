<?php
/**
 * Displays country dropdown.
 *
 * Lets users select all countries or an individual country.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="country"><?php esc_attr_e( 'Country', 'wp-crm-system' ); ?></label></p>
<?php
$user = wp_get_current_user();
if ( $user->has_cap( get_option( 'wpcrm_system_select_user_role' ) ) ) {

	global $wpdb;
	$results = $wpdb->get_results( "SELECT DISTINCT pm.meta_value FROM {$wpdb->prefix}postmeta pm INNER JOIN {$wpdb->prefix}posts p ON p.ID = pm.post_id WHERE pm.meta_key LIKE '%country%' AND p.post_status = 'publish' ORDER BY meta_value", OBJECT );

	if ( $results ) {
		?>
		<select id="country" name="wp-crm-system-country">
			<option value="all"
			<?php
			if (
				( isset( $_POST['wp-crm-system-country'], $_POST['organization_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['organization_report_nonce'] ), 'check_organization_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-country'], $_POST['contact_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['contact_report_nonce'] ), 'check_contact_report_nonce' ) )
			) {
				$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-country'] ) );
				if ( 'all' === $val ) {
					echo 'selected="selected"';
				}
			}
			?>
			><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
			<option value=""
			<?php
			if (
				( isset( $_POST['wp-crm-system-country'], $_POST['organization_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['organization_report_nonce'] ), 'check_organization_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-country'], $_POST['contact_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['contact_report_nonce'] ), 'check_contact_report_nonce' ) )
			) {
				$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-country'] ) );
				if ( '' === $val ) {
					echo 'selected="selected"';
				}
			}
			?>
			><?php esc_attr_e( 'Not set', 'wp-crm-system' ); ?></option>
			<?php
			foreach ( $results as $result ) {
				$opt_out = '';
				$opt_out = '<option value="' . esc_attr( $result->meta_value ) . '"';
				if (
					( isset( $_POST['wp-crm-system-country'], $_POST['organization_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['organization_report_nonce'] ), 'check_organization_report_nonce' ) ) ||
					( isset( $_POST['wp-crm-system-country'], $_POST['contact_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['contact_report_nonce'] ), 'check_contact_report_nonce' ) )
				) {
					$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-country'] ) );
					if ( esc_attr( $result->meta_value ) === $val ) {
						$opt_out .= 'selected="selected"';
					}
				};
				$opt_out .= '>' . esc_attr( $result->meta_value ) . '</option>';
				echo $opt_out;
			}
			?>
		</select>
		<?php
	} else {
		esc_attr_e( 'No countries to display', 'wp-crm-system' );
	}
}
