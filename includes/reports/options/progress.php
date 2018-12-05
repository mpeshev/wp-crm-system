<?php
/**
 * Displays progress dropdown.
 *
 * Lets users select all or an individual progress (0-100).
 *
 * @since 2.5.4
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="progress">Progress</label></p>
<select id="progress" name="wp-crm-system-progress">
	<option value="all"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( 'all' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
	<option value="zero"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( 'zero' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>0%</option>
	<option value="5"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '5' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>5%</option>
	<option value="10"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '10' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>10%</option>
	<option value="15"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '15' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>15%</option>
	<option value="20"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '20' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>20%</option>
	<option value="25"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '25' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>25%</option>
	<option value="30"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '30' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>30%</option>
	<option value="35"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '35' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>35%</option>
	<option value="40"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '40' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>40%</option>
	<option value="45"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '45' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>45%</option>
	<option value="50"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '50' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>50%</option>
	<option value="55"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '55' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>55%</option>
	<option value="60"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '60' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>60%</option>
	<option value="65"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '65' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>65%</option>
	<option value="70"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '70' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>70%</option>
	<option value="75"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '75' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>75%</option>
	<option value="80"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '80' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>80%</option>
	<option value="85"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '85' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>85%</option>
	<option value="90"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '90' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>90%</option>
	<option value="95"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '95' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>95%</option>
	<option value="100"
	<?php
	if (
		( isset( $_POST['wp-crm-system-progress'], $_POST['task_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
		( isset( $_POST['wp-crm-system-progress'], $_POST['project_report_nonce'] )
		&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) )
	) {
		$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-progress'] ) );
		if ( '100' === $val ) {
			echo 'selected="selected"';
		}
	}
	?>
	>100%</option>
</select>
