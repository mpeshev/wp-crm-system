<?php
/**
 * Displays contact dropdown.
 *
 * Lets users select all contacts or an individual contact.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="contact"><?php esc_attr_e( 'Contact', 'wp-crm-system' ); ?></label></p>
<?php
$user = wp_get_current_user();
if ( $user->has_cap( get_option( 'wpcrm_system_select_user_role' ) ) ) {
	$args     = array(
		'post_type'      => 'wpcrm-contact',
		'posts_per_page' => -1,
		'order'          => 'ASC',
		'orderby'        => 'title',
	);
	$wpcposts = get_posts( $args );
	if ( $wpcposts ) {
		?>
		<select id="contact" name="wp-crm-system-contact">
			<option value="all"
			<?php
			if (
				( isset( $_POST['wp-crm-system-contact'], $_POST['task_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-contact'], $_POST['project_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-contact'], $_POST['opportunity_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) ) ||
				( isset( $_POST['wp-crm-system-contact'], $_POST['campaign_report_nonce'] )
				&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
			) {
				$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-contact'] ) );
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
					( isset( $_POST['wp-crm-system-contact'], $_POST['task_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) ||
					( isset( $_POST['wp-crm-system-contact'], $_POST['project_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) ||
					( isset( $_POST['wp-crm-system-contact'], $_POST['opportunity_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['opportunity_report_nonce'] ), 'check_opportunity_report_nonce' ) ) ||
					( isset( $_POST['wp-crm-system-contact'], $_POST['campaign_report_nonce'] )
					&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) )
				) {
					$val = sanitize_text_field( wp_unslash( $_POST['wp-crm-system-contact'] ) );
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
		esc_attr_e( 'No contacts to display', 'wp-crm-system' );
	}
}
