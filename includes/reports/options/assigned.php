<?php
/**
 * Displays assigned dropdown.
 *
 * Lets users select all assigned or an individual user.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="assigned"><?php esc_attr_e( 'Assigned', 'wp-crm-system' ); ?></label></p>
<?php
$user = wp_get_current_user();
if ( $user->has_cap( get_option( 'wpcrm_system_select_user_role' ) ) ) {

	$users = get_users( array( 'fields' => array( 'user_login' ) ) );
	if ( $users ) {
		?>
		<select id="assigned" name="wp-crm-system-assigned">
			<option value="all"
			<?php
			if (
				( isset( $_POST['wp-crm-system-assigned'], $_POST['task_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-assigned'], $_POST['project_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-assigned'], $_POST['opportunity_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-assigned'], $_POST['campaign_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
			) {
				$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-assigned'] ) );
				if ( 'all' === $val ) {
					echo 'selected="selected"';
				}
			}
			?>
			><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
			<?php
			foreach ( $users as $user ) {
				$opt_out = '';
				$opt_out = '<option value="' . esc_attr( $user->user_login ) . '"';
				if (
					( isset( $_POST['wp-crm-system-assigned'], $_POST['task_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
					( isset( $_POST['wp-crm-system-assigned'], $_POST['project_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
					( isset( $_POST['wp-crm-system-assigned'], $_POST['opportunity_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) ) ||
					( isset( $_POST['wp-crm-system-assigned'], $_POST['campaign_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
				) {
					$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-assigned'] ) );
					if ( esc_attr( $user->user_login ) === $val ) {
						$opt_out .= 'selected="selected"';
					}
				};
				$opt_out .= '>' . esc_attr( $user->user_login ) . '</option>';
				echo $opt_out;
			}
			?>
		</select>
		<?php
	} else {
		esc_attr_e( 'No users to display', 'wp-crm-system' );
	}
}
