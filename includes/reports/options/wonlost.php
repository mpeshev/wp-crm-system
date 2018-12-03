<?php
/**
 * Displays won/lost dropdown.
 *
 * Lets users select all, won, lost, suspended, abandoned or not set.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="wonlost">Won/Lost</label></p>
<select id="wonlost" name="wp-crm-system-wonlost">
	<option value="all"
	<?php
	if (
		isset( $_POST['wp-crm-system-wonlost'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-wonlost'] ) );
		if ( 'all' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
	<option value="won"
	<?php
	if (
		isset( $_POST['wp-crm-system-wonlost'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-wonlost'] ) );
		if ( 'won' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Won', 'wp-crm-system' ); ?></option>
	<option value="lost"
	<?php
	if (
		isset( $_POST['wp-crm-system-wonlost'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-wonlost'] ) );
		if ( 'lost' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Lost', 'wp-crm-system' ); ?></option>
	<option value="suspended"
	<?php
	if (
		isset( $_POST['wp-crm-system-wonlost'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-wonlost'] ) );
		if ( 'suspended' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Suspended', 'wp-crm-system' ); ?></option>
	<option value="abandoned"
	<?php
	if (
		isset( $_POST['wp-crm-system-wonlost'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-wonlost'] ) );
		if ( 'abandoned' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Abandoned', 'wp-crm-system' ); ?></option>
	<option value=""
	<?php
	if (
		isset( $_POST['wp-crm-system-wonlost'], $_POST['opportunity_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-wonlost'] ) );
		if ( '' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'Not set', 'wp-crm-system' ); ?></option>
</select>
