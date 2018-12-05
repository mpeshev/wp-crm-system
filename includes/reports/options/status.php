<?php
/**
 * Displays status dropdown.
 *
 * Lets users select all statuses or an individual status (not started, in progress, complete, on hold).
 *
 * @since 2.5.4
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="status">Status</label></p>
<select id="status" name="wp-crm-system-status">
	<option value="all"
	<?php
	if (
		( isset( $_POST['wp-crm-system-status'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-status'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-status'], $_POST['campaign_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-status'] ) );
		if ( 'all' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
	<option value="not-started"
	<?php
	if (
		( isset( $_POST['wp-crm-system-status'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-status'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-status'], $_POST['campaign_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-status'] ) );
		if ( 'not-started' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Not Started', 'wp-crm-system' ); ?></option>
	<option value="in-progress"
	<?php
	if (
		( isset( $_POST['wp-crm-system-status'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-status'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-status'], $_POST['campaign_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-status'] ) );
		if ( 'in-progress' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'In Progress', 'wp-crm-system' ); ?></option>
	<option value="complete"
	<?php
	if (
		( isset( $_POST['wp-crm-system-status'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-status'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-status'], $_POST['campaign_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-status'] ) );
		if ( 'complete' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Complete', 'wp-crm-system' ); ?></option>
	<option value="on-hold"
	<?php
	if (
		( isset( $_POST['wp-crm-system-status'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-status'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-status'], $_POST['campaign_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-status'] ) );
		if ( 'on-hold' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'On Hold', 'wp-crm-system' ); ?></option>
</select>
