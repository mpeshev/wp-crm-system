<?php
/**
 * Displays due dropdown.
 *
 * Lets users select all, upcoming or overdue.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="close">Due</label></p>
<select id="close" name="wp-crm-system-due">
	<option value="all"
	<?php
	if (
		( isset( $_POST['wp-crm-system-due'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-due'], $_POST['campaign_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-due'] ) );
		if ( 'all' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
	<option value="upcoming"
	<?php
	if (
		( isset( $_POST['wp-crm-system-due'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-due'], $_POST['campaign_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-due'] ) );
		if ( 'upcoming' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Upcoming', 'wp-crm-system' ); ?></option>
	<option value="overdue"
	<?php
	if (
		( isset( $_POST['wp-crm-system-due'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-due'], $_POST['campaign_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-due'] ) );
		if ( 'overdue' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Overdue', 'wp-crm-system' ); ?></option>
</select>
