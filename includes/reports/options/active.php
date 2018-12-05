<?php
/**
 * Displays active dropdown.
 *
 * Lets users select all projects, active projects or inactive projects.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="active">Active</label></p>
<select id="active" name="wp-crm-system-active">
	<option value="all"
	<?php
	if (
		isset( $_POST['wp-crm-system-active'], $_POST['campaign_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-active'] ) );
		if ( 'all' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
	<option value="yes"
	<?php
	if (
		isset( $_POST['wp-crm-system-active'], $_POST['campaign_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-active'] ) );
		if ( 'yes' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Yes', 'wp-crm-system' ); ?></option>
	<option value=""
	<?php
	if (
		isset( $_POST['wp-crm-system-active'], $_POST['campaign_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-active'] ) );
		if ( '' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'No', 'wp-crm-system' ); ?></option>
</select>
