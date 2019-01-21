<?php
/**
 * Displays options for reporting on projects.
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
		<h2><?php esc_attr_e( 'Project Reports', 'wp-crm-system' ); ?></h2>
		<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
			<tbody>
				<?php wp_crm_system_show_project_form(); ?>
				<?php
				if ( ! empty( $_POST ) && check_admin_referer( 'check_project_report_nonce', 'project_report_nonce' ) ) {
					wp_crm_system_process_project_form();
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<?php
/**
 * Shows the project reporting form.
 *
 * Lets the user select various options to report on projects dynamically.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */
function wp_crm_system_show_project_form() {
	?>
	<form method="post">
		<?php wp_nonce_field( 'check_project_report_nonce', 'project_report_nonce' ); ?>
		<div class="wp-crm-first wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/close.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/progress.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/status.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/value.php'; ?></div>
		<div class="wp-crm-first wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/organization.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/contact.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/assigned.php'; ?></div>
		<div class="wp-crm-first wp-crm-one-half"><br /><input type="submit" name="submit" value="Submit" class="button button-primary"><br /><br /></div>
	</form>
	<?php
}

/**
 * Processes the project reporting form.
 *
 * Handles the processing of the project reporting form and shows output.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */
function wp_crm_system_process_project_form() {

	$prefix = '_wpcrm_';

	if ( isset( $_POST['submit'], $_POST['project_report_nonce'] )
	&& wp_verify_nonce( sanitize_key( $_POST['project_report_nonce'] ), 'check_project_report_nonce' ) ) {

		foreach ( $_POST as $param_name => $param_val ) {
			if ( 'wp-crm-system-close' === $param_name ) {
				$close   = esc_html( $param_val );
				$clo_arr = '';
				$now     = strtotime( 'now' );
				if ( 'upcoming' === $close ) {
					$clo_arr = array(
						'key'     => $prefix . 'project-closedate',
						'value'   => $now,
						'compare' => '>',
					);
				} elseif ( 'overdue' === $close ) {
					$clo_arr = array(
						'key'     => $prefix . 'project-closedate',
						'value'   => $now,
						'compare' => '<',
					);
				}
			} elseif ( 'wp-crm-system-progress' === $param_name ) {
				$progress = esc_html( $param_val );
				$pro_arr  = '';
				if ( 'zero' === $progress ) {
					$pro_arr = array(
						'key'     => $prefix . 'project-progress',
						'compare' => 'NOT EXISTS',
					);
				} elseif ( 'all' !== $progress ) {
					$pro_arr = array(
						'key'     => $prefix . 'project-progress',
						'value'   => $progress,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-status' === $param_name ) {
				$status      = esc_html( $param_val );
				$status_sign = '=';
				if ( 'all' === $status ) {
					$status_sign = '!=';
				}
			} elseif ( 'wp-crm-system-value' === $param_name ) {
				$value   = esc_html( $param_val );
				$val_arr = '';
				if ( '1000' === $value ) {
					$val_arr = array(
						'key'     => $prefix . 'project-value',
						'value'   => $value,
						'type'    => 'numeric',
						'compare' => '<',
					);
				} elseif ( '5000' === $value ) {
					$val_arr = array(
						'key'     => $prefix . 'project-value',
						'value'   => array( '1000', $value ),
						'type'    => 'numeric',
						'compare' => 'BETWEEN',
					);
				} elseif ( '10000' === $value ) {
					$val_arr = array(
						'key'     => $prefix . 'project-value',
						'value'   => array( '5000', $value ),
						'type'    => 'numeric',
						'compare' => 'BETWEEN',
					);
				} elseif ( '10001' === $value ) {
					$val_arr = array(
						'key'     => $prefix . 'project-value',
						'value'   => $value,
						'type'    => 'numeric',
						'compare' => '>',
					);
				}
			} elseif ( 'wp-crm-system-organization' === $param_name ) {
				$organization = esc_html( $param_val );
				$org_arr      = '';
				if ( 'all' !== $organization ) {
					$org_arr = array(
						'key'     => $prefix . 'project-attach-to-organization',
						'value'   => $organization,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-contact' === $param_name ) {
				$contact = esc_html( $param_val );
				$con_arr = '';
				if ( 'all' !== $contact ) {
					$con_arr = array(
						'key'     => $prefix . 'project-attach-to-contact',
						'value'   => $contact,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-assigned' === $param_name ) {
				$assigned = esc_html( $param_val );
				$asg_arr  = '';
				if ( 'all' !== $assigned ) {
					$asg_arr = array(
						'key'     => $prefix . 'project-assigned',
						'value'   => $assigned,
						'compare' => '=',
					);
				}
			}
		}

		$project_report = '';

		$args = array(
			'post_type'      => 'wpcrm-project',
			'posts_per_page' => -1,
			'meta_query'     => array(
				'relation' => 'AND',
				$clo_arr,
				$pro_arr,
				array(
					'key'     => $prefix . 'project-status',
					'value'   => $status,
					'compare' => $status_sign,
				),
				$val_arr,
				$org_arr,
				$con_arr,
				$asg_arr,
			),
		);

		$wpcposts = get_posts( $args );

		if ( $wpcposts ) {
			$project_report .= '<tr><th><strong>' . esc_attr_x( 'Project', 'wp-crm-system' ) . '</strong></th>';
			$project_report .= '<th><strong>' . esc_attr_x( 'Close', 'wp-crm-system' ) . '</strong></th>';
			$project_report .= '<th><strong>' . esc_attr_x( 'Progress', 'wp-crm-system' ) . '</strong></th>';
			$project_report .= '<th><strong>' . esc_attr_x( 'Status', 'wp-crm-system' ) . '</strong></th>';
			$project_report .= '<th><strong>' . esc_attr_x( 'Value', 'wp-crm-system' ) . '</strong></th>';
			$project_report .= '<th><strong>' . esc_attr_x( 'Organization', 'wp-crm-system' ) . '</strong></th>';
			$project_report .= '<th><strong>' . esc_attr_x( 'Contact', 'wp-crm-system' ) . '</strong></th>';
			$project_report .= '<th><strong>' . esc_attr_x( 'Assigned', 'wp-crm-system' ) . '</strong></th></tr>';
			foreach ( $wpcposts as $wpcpost ) {

				$project_report .= '<tr><td>';

				$project_report .= '<a href="' . esc_url( get_edit_post_link( $wpcpost->ID ) ) . '">' . esc_html( get_the_title( $wpcpost->ID ) ) . '</a>';

				$close_output = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'project-closedate', true ) );
				if ( '' !== $close_output ) {
					$close_output = date( esc_html( get_option( 'wpcrm_system_php_date_format' ) ), $close_output );
				} else {
					$close_output = 'Not set';
				}
				$project_report .= '</td><td>' . $close_output;

				$progress_output = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'project-progress', true ) );
				if ( '' === $progress_output ) {
					$progress_output = '0';
				}
				$project_report .= '</td><td>' . $progress_output . '%';

				$status_output = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'project-status', true ) );
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
				$project_report .= '</td><td>' . $status_output;

				$value_output = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'project-value', true ) );
				if ( '' === $value_output ) {
					$value_output = 'Not Set';
				} else {
					$value_output = wpcrm_system_display_currency_symbol( get_option( 'wpcrm_system_default_currency' ) ) . ' ' . number_format( $value_output, get_option( 'wpcrm_system_report_currency_decimals' ), get_option( 'wpcrm_system_report_currency_decimal_point' ), get_option( 'wpcrm_system_report_currency_thousand_separator' ) );
				}
				$project_report .= '</td><td>' . $value_output;

				$org                 = '';
				$organization_output = '';
				$org                 = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'project-attach-to-organization', true ) );
				if ( '' === $org ) {
					$organization_output = '';
				} else {
					$organization_output .= '<a href="' . esc_url( get_edit_post_link( $org ) ) . '">' . esc_html( get_the_title( $org ) ) . '</a>';
				}
				$project_report .= '</td><td>' . $organization_output;

				$con            = '';
				$contact_output = '';
				$con            = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'project-attach-to-contact', true ) );
				if ( '' === $con ) {
					$contact_output = '';
				} else {
					$contact_output .= '<a href="' . esc_url( get_edit_post_link( $con ) ) . '">' . esc_html( get_the_title( $con ) ) . '</a>';
				}
				$project_report .= '</td><td>' . $contact_output;

				$asg               = '';
				$assignment_output = '';
				$asg               = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'project-assigned', true ) );
				if ( '' === $asg ) {
					$assignment_output = '';
				} else {
					$assignment_output .= $asg;
				}
				$project_report .= '</td><td>' . $assignment_output;

				$project_report .= '</td></tr>';
			}
		} else {
			$project_report .= '<tr><th><strong>Project</strong></th><tr><td>' . esc_attr_x( 'No projects to report.', 'wp-crm-system' ) . '</td></tr>';
		}

		print $project_report;
	}
}
