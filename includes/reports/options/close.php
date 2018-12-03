<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="close">Close</label></p>
<select id="close" name="wp-crm-system-close">
	<option value="all"
	<?php
	if (
		( isset( $_POST['wp-crm-system-close'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-close'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) )

	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-close'] ) );
		if ( 'all' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
	<option value="upcoming"
	<?php
	if (
		( isset( $_POST['wp-crm-system-close'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-close'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-close'] ) );
		if ( 'upcoming' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Upcoming', 'wp-crm-system' ); ?></option>
	<option value="overdue"
	<?php
	if (
		( isset( $_POST['wp-crm-system-close'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-close'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-close'] ) );
		if ( 'overdue' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Overdue', 'wp-crm-system' ); ?></option>
</select>
