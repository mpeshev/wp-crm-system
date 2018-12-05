<?php
/**
 * Displays value dropdown.
 *
 * Lets users select all values or an individual grouping (< 1000, 1K-5K, 5K-10K, over 10K).
 *
 * @since 2.5.4
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="value">Value</label></p>
<select id="value" name="wp-crm-system-value">
	<option value="all"
	<?php
	if (
		( isset( $_POST['wp-crm-system-value'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-value'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-value'] ) );
		if ( 'all' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
	<option value="1000"
	<?php
	if (
		( isset( $_POST['wp-crm-system-value'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-value'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-value'] ) );
		if ( '1000' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Less than 1000', 'wp-crm-system' ); ?></option>
	<option value="5000"
	<?php
	if (
		( isset( $_POST['wp-crm-system-value'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-value'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-value'] ) );
		if ( '5000' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( '1000 - 5000', 'wp-crm-system' ); ?></option>
	<option value="10000"
	<?php
	if (
		( isset( $_POST['wp-crm-system-value'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-value'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-value'] ) );
		if ( '10000' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( '5000 - 10000', 'wp-crm-system' ); ?></option>
	<option value="10001"
	<?php
	if (
		( isset( $_POST['wp-crm-system-value'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-value'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-value'] ) );
		if ( '10001' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'More than 10000', 'wp-crm-system' ); ?></option>
</select>
