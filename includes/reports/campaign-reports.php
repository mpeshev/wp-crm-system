<?php
/**
 * Displays options for reporting on campaigns.
 *
 * Lets users generate dynamic reports based on input.
 *
 * @since 1.0.0
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
require WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php';

?>
<div class="wrap">
	<div>
		<h2><?php esc_attr_e( 'Campaign Reports', 'wp-crm-system' ); ?></h2>
		<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
			<tbody>
				<?php wp_crm_system_show_campaign_form(); ?>
				<?php
				if ( ! empty( $_POST ) && check_admin_referer( 'check_campaign_report_nonce', 'campaign_report_nonce' ) ) {
					wp_crm_system_process_campaign_form();
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<?php
/**
 * Shows the campaign reporting form.
 *
 * Lets the user select various options to report on campaigns dynamically.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */
function wp_crm_system_show_campaign_form() {
	?>
	<form method="post">
		<?php wp_nonce_field( 'check_campaign_report_nonce', 'campaign_report_nonce' ); ?>
		<div class="wp-crm-first wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/due.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/status.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/active.php'; ?></div>
		<div class="wp-crm-first wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/organization.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/contact.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/assigned.php'; ?></div>
		<div class="wp-crm-first wp-crm-one-half"><br /><input type="submit" name="submit" value="Submit" class="button button-primary"><br /><br /></div>
	</form>
	<?php
}

/**
 * Processes the campaign reporting form.
 *
 * Handles the processing of the campaign reporting form and shows output.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */
function wp_crm_system_process_campaign_form() {

	$prefix = '_wpcrm_';

	if ( isset( $_POST['submit'], $_POST['campaign_report_nonce'] )
	&& wp_verify_nonce( sanitize_key( $_POST['campaign_report_nonce'] ), 'check_campaign_report_nonce' ) ) {

		foreach ( $_POST as $param_name => $param_val ) {
			if ( 'wp-crm-system-due' === $param_name ) {
				$due     = esc_html( $param_val );
				$due_arr = '';
				$now     = strtotime( 'now' );
				if ( 'upcoming' === $due ) {
					$due_arr = array(
						'key'     => $prefix . 'campaign-enddate',
						'value'   => $now,
						'compare' => '>',
					);
				} elseif ( 'overdue' === $due ) {
					$due_arr = array(
						'key'     => $prefix . 'campaign-enddate',
						'value'   => $now,
						'compare' => '<',
					);
				}
			} elseif ( 'wp-crm-system-status' === $param_name ) {
				$status      = esc_html( $param_val );
				$status_sign = '=';
				if ( 'all' === $status ) {
					$status_sign = '!=';
				}
			} elseif ( 'wp-crm-system-active' === $param_name ) {
				$active  = esc_html( $param_val );
				$act_arr = '';
				if ( 'all' === $active ) {

				} elseif ( '' === $active ) {
					$act_arr = array(
						'key'     => $prefix . 'campaign-active',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					);
				} else {
					$act_arr = array(
						'key'     => $prefix . 'campaign-active',
						'value'   => $active,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-organization' === $param_name ) {
				$organization = esc_html( $param_val );
				$org_arr      = '';
				if ( 'all' !== $organization ) {
					$org_arr = array(
						'key'     => $prefix . 'campaign-attach-to-organization',
						'value'   => $organization,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-contact' === $param_name ) {
				$contact = esc_html( $param_val );
				$con_arr = '';
				if ( 'all' !== $contact ) {
					$con_arr = array(
						'key'     => $prefix . 'campaign-attach-to-contact',
						'value'   => $contact,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-assigned' === $param_name ) {
				$assigned = esc_html( $param_val );
				$asg_arr  = '';
				if ( 'all' !== $assigned ) {
					$asg_arr = array(
						'key'     => $prefix . 'campaign-assigned',
						'value'   => $assigned,
						'compare' => '=',
					);
				}
			}
		}

		$campaign_report = '';

		$args = array(
			'post_type'      => 'wpcrm-campaign',
			'posts_per_page' => -1,
			'meta_query'     => array(
				'relation' => 'AND',
				$due_arr,
				array(
					'key'     => $prefix . 'campaign-status',
					'value'   => $status,
					'compare' => $status_sign,
				),
				$act_arr,
				$org_arr,
				$con_arr,
				$asg_arr,
			),
		);

		$wpcposts = get_posts( $args );

		if ( $wpcposts ) {
			$campaign_report .= '<tr><th><strong>' . esc_attr_x( 'Campaign', 'wp-crm-system' ) . '</strong></th>';
			$campaign_report .= '<th><strong>' . esc_attr_x( 'Due', 'wp-crm-system' ) . '</strong></th>';
			$campaign_report .= '<th><strong>' . esc_attr_x( 'Status', 'wp-crm-system' ) . '</strong></th>';
			$campaign_report .= '<th><strong>' . esc_attr_x( 'Active', 'wp-crm-system' ) . '</strong></th>';
			$campaign_report .= '<th><strong>' . esc_attr_x( 'Organization', 'wp-crm-system' ) . '</strong></th>';
			$campaign_report .= '<th><strong>' . esc_attr_x( 'Contact', 'wp-crm-system' ) . '</strong></th>';
			$campaign_report .= '<th><strong>' . esc_attr_x( 'Assigned', 'wp-crm-system' ) . '</strong></th></tr>';
			foreach ( $wpcposts as $wpcpost ) {

				$campaign_report .= '<tr><td>';

				$campaign_report .= '<a href="' . esc_url( get_edit_post_link( $wpcpost->ID ) ) . '">' . esc_html( get_the_title( $wpcpost->ID ) ) . '</a>';

				$due_output = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'campaign-enddate', true ) );
				if ( '' !== $due_output ) {
					$due_output = date( esc_html( get_option( 'wpcrm_system_php_date_format' ) ), $due_output );
				} else {
					$due_output = 'Not set';
				}
				$campaign_report .= '</td><td>' . $due_output;

				$status_output = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'campaign-status', true ) );
				switch ( $status_output ) {
					case 'not-started':
						$status_output = 'Not Started';
						break;
					case 'in-progress':
						$status_output = 'In Progress';
						break;
					case 'complete':
						$status_output = 'Complete';
						break;
					case 'on-hold':
						$status_output = 'On Hold';
						break;
				}
				$campaign_report .= '</td><td>' . $status_output;

				$active_output = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'campaign-active', true ) );
				if ( '' === $active_output ) {
					$active_output = 'No';
				} else {
					$active_output = ucfirst( $active_output );
				}
				$campaign_report .= '</td><td>' . $active_output;

				$org                 = '';
				$organization_output = '';
				$org                 = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'campaign-attach-to-organization', true ) );
				if ( '' === $org ) {
					$organization_output = '';
				} else {
					$organization_output .= '<a href="' . esc_url( get_edit_post_link( $org ) ) . '">' . esc_html( get_the_title( $org ) ) . '</a>';
				}
				$campaign_report .= '</td><td>' . $organization_output;

				$con            = '';
				$contact_output = '';
				$con            = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'campaign-attach-to-contact', true ) );
				if ( '' === $con ) {
					$contact_output = '';
				} else {
					$contact_output .= '<a href="' . esc_url( get_edit_post_link( $con ) ) . '">' . esc_html( get_the_title( $con ) ) . '</a>';
				}
				$campaign_report .= '</td><td>' . $contact_output;

				$asg               = '';
				$assignment_output = '';
				$asg               = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'campaign-assigned', true ) );
				if ( '' === $asg ) {
					$assignment_output = '';
				} else {
					$assignment_output .= $asg;
				}
				$campaign_report .= '</td><td>' . $assignment_output;

				$campaign_report .= '</td></tr>';
			}
		} else {
			$campaign_report .= '<tr><th><strong>Campaign</strong></th><tr><td>' . esc_attr_x( 'No campaigns to report.', 'wp-crm-system' ) . '</td></tr>';
		}

		print $campaign_report;
	}
}
