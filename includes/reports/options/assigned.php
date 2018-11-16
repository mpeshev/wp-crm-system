<?php
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
			<option value="all"><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
			<?php
			foreach ( $users as $user ) {
				echo '<option value="' . esc_attr( $user->user_login ) . '">' . esc_attr( $user->user_login ) . '</option>';
			}
			?>
		</select>
		<?php
	} else {
		esc_attr_e( 'No users to display', 'wp-crm-system' );
	}
}
