<?php
/**
 * Displays priority dropdown.
 *
 * Lets users select all priorities or an individual priority (low, medium, high or none).
 *
 * @since 2.5.4
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="priority">Priority</label></p>
<select id="priority" name="wp-crm-system-priority">
	<option value="all"
	<?php
	if (
		isset( $_POST['wp-crm-system-priority'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-priority'] ) );
		if ( 'all' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
	<option value="low"
	<?php
	if (
		isset( $_POST['wp-crm-system-priority'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-priority'] ) );
		if ( 'low' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Low', 'wp-crm-system' ); ?></option>
	<option value="medium"
	<?php
	if (
		isset( $_POST['wp-crm-system-priority'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-priority'] ) );
		if ( 'medium' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Medium', 'wp-crm-system' ); ?></option>
	<option value="high"
	<?php
	if (
		isset( $_POST['wp-crm-system-priority'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-priority'] ) );
		if ( 'high' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'High', 'wp-crm-system' ); ?></option>
	<option value=""
	<?php
	if (
		isset( $_POST['wp-crm-system-priority'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-priority'] ) );
		if ( '' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'None', 'wp-crm-system' ); ?></option>
</select>
