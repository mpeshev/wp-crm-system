<?php
/**
 * Displays organization dropdown.
 *
 * Lets users select all organizations or an individual organization.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="organization"><?php esc_attr_e( 'Organization', 'wp-crm-system' ); ?></label></p>
<?php
$user = wp_get_current_user();
if ( $user->has_cap( get_option( 'wpcrm_system_select_user_role' ) ) ) {
	$args     = array(
		'post_type'      => 'wpcrm-organization',
		'posts_per_page' => -1,
		'order'          => 'ASC',
		'orderby'        => 'title',
	);
	$wpcposts = get_posts( $args );
	if ( $wpcposts ) {
		?>
		<select id="organization" name="wp-crm-system-organization">
			<option value="all"
			<?php
			if (
				( isset( $_POST['wp-crm-system-organization'], $_POST['task_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-organization'], $_POST['project_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-organization'], $_POST['opportunity_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-organization'], $_POST['contact_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['contact_report_nonce'] ), 'check_contact_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-organization'], $_POST['campaign_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
			) {
				$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-organization'] ) );
				if ( 'all' === $val ) {
					echo 'selected="selected"';
				}
			}
			?>
			><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
			<?php
			foreach ( $wpcposts as $wpcpost ) {
				$opt_out = '';
				$opt_out = '<option value="' . esc_attr( $wpcpost->ID ) . '"';
				if (
					( isset( $_POST['wp-crm-system-organization'], $_POST['task_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
					( isset( $_POST['wp-crm-system-organization'], $_POST['project_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
					( isset( $_POST['wp-crm-system-organization'], $_POST['opportunity_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) ) ||
					( isset( $_POST['wp-crm-system-organization'], $_POST['contact_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['contact_report_nonce'] ), 'check_contact_report_nonce' ) ) ||
					( isset( $_POST['wp-crm-system-organization'], $_POST['campaign_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
				) {
					$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-organization'] ) );
					if ( esc_attr( $wpcpost->ID ) === $val ) {
						$opt_out .= 'selected="selected"';
					}
				};
				$opt_out .= '>' . get_the_title( esc_attr( $wpcpost->ID ) ) . '</option>';
				echo $opt_out;
			}
			?>
		</select>
		<?php
	} else {
		esc_attr_e( 'No organizations to display', 'wp-crm-system' );
	}
}
