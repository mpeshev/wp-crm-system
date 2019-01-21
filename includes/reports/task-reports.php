<?php
/**
 * Displays options for reporting on tasks.
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
		<h2><?php esc_attr_e( 'Task Reports', 'wp-crm-system' ); ?></h2>
		<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
			<tbody>
				<?php wp_crm_system_show_task_form(); ?>
				<?php
				if ( ! empty( $_POST ) && check_admin_referer( 'check_task_report_nonce', 'task_report_nonce' ) ) {
					wp_crm_system_process_task_form();
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<?php
/**
 * Shows the task reporting form.
 *
 * Lets the user select various options to report on tasks dynamically.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */
function wp_crm_system_show_task_form() {
	?>
	<form method="post">
		<?php wp_nonce_field( 'check_task_report_nonce', 'task_report_nonce' ); ?>
		<div class="wp-crm-first wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/due.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/progress.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/priority.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/status.php'; ?></div>
		<div class="wp-crm-first wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/organization.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/contact.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/project.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/assigned.php'; ?></div>
		<div class="wp-crm-first wp-crm-one-half"><br /><input type="submit" name="submit" value="Submit" class="button button-primary"><br /><br /></div>
	</form>
	<?php
}

/**
 * Processes the task reporting form.
 *
 * Handles the processing of the task reporting form and shows output.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */
function wp_crm_system_process_task_form() {

	$prefix = '_wpcrm_';

	if ( isset( $_POST['submit'], $_POST['task_report_nonce'] )
	&& wp_verify_nonce( sanitize_key( $_POST['task_report_nonce'] ), 'check_task_report_nonce' ) ) {

		foreach ( $_POST as $param_name => $param_val ) {
			if ( 'wp-crm-system-due' === $param_name ) {
				$due     = esc_html( $param_val );
				$due_arr = '';
				$now     = strtotime( 'now' );
				if ( 'upcoming' === $due ) {
					$due_arr = array(
						'key'     => $prefix . 'task-due-date',
						'value'   => $now,
						'compare' => '>',
					);
				} elseif ( 'overdue' === $due ) {
					$due_arr = array(
						'key'     => $prefix . 'task-due-date',
						'value'   => $now,
						'compare' => '<',
					);
				}
			} elseif ( 'wp-crm-system-progress' === $param_name ) {
				$progress      = esc_html( $param_val );
				$progress_sign = '=';
				if ( 'all' === $progress ) {
					$progress_sign = '!=';
				}
			} elseif ( 'wp-crm-system-priority' === $param_name ) {
				$priority     = esc_html( $param_val );
				$priority_arr = '';
				if ( 'all' === $priority ) {

				} elseif ( '' === $priority ) {
					$priority_arr = array(
						'key'     => $prefix . 'task-priority',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					);
				} else {
					$priority_arr = array(
						'key'     => $prefix . 'task-priority',
						'value'   => $priority,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-status' === $param_name ) {
				$status      = esc_html( $param_val );
				$status_sign = '=';
				if ( 'all' === $status ) {
					$status_sign = '!=';
				}
			} elseif ( 'wp-crm-system-organization' === $param_name ) {
				$organization = esc_html( $param_val );
				$org_arr      = '';
				if ( 'all' !== $organization ) {
					$org_arr = array(
						'key'     => $prefix . 'task-attach-to-organization',
						'value'   => $organization,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-contact' === $param_name ) {
				$contact = esc_html( $param_val );
				$con_arr = '';
				if ( 'all' !== $contact ) {
					$con_arr = array(
						'key'     => $prefix . 'task-attach-to-contact',
						'value'   => $contact,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-project' === $param_name ) {
				$project = esc_html( $param_val );
				$pro_arr = '';
				if ( 'all' !== $project ) {
					$pro_arr = array(
						'key'     => $prefix . 'task-attach-to-project',
						'value'   => $project,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-assigned' === $param_name ) {
				$assigned = esc_html( $param_val );
				$asg_arr  = '';
				if ( 'all' !== $assigned ) {
					$asg_arr = array(
						'key'     => $prefix . 'task-assignment',
						'value'   => $assigned,
						'compare' => '=',
					);
				}
			}
		}

		$task_report = '';

		$args = array(
			'post_type'      => 'wpcrm-task',
			'posts_per_page' => -1,
			'meta_query'     => array(
				'relation' => 'AND',
				$due_arr,
				array(
					'key'     => $prefix . 'task-progress',
					'value'   => $progress,
					'compare' => $progress_sign,
				),
				$priority_arr,
				array(
					'key'     => $prefix . 'task-status',
					'value'   => $status,
					'compare' => $status_sign,
				),
				$org_arr,
				$con_arr,
				$pro_arr,
				$asg_arr,
			),
		);

		$wpcposts = get_posts( $args );

		if ( $wpcposts ) {
			$task_report .= '<tr><th><strong>' . esc_attr_x( 'Task', 'wp-crm-system' ) . '</strong></th>';
			$task_report .= '<th><strong>' . esc_attr_x( 'Due', 'wp-crm-system' ) . '</strong></th>';
			$task_report .= '<th><strong>' . esc_attr_x( 'Progress', 'wp-crm-system' ) . '</strong></th>';
			$task_report .= '<th><strong>' . esc_attr_x( 'Priority', 'wp-crm-system' ) . '</strong></th>';
			$task_report .= '<th><strong>' . esc_attr_x( 'Status', 'wp-crm-system' ) . '</strong></th>';
			$task_report .= '<th><strong>' . esc_attr_x( 'Organization', 'wp-crm-system' ) . '</strong></th>';
			$task_report .= '<th><strong>' . esc_attr_x( 'Contact', 'wp-crm-system' ) . '</strong></th>';
			$task_report .= '<th><strong>' . esc_attr_x( 'Project', 'wp-crm-system' ) . '</strong></th>';
			$task_report .= '<th><strong>' . esc_attr_x( 'Assigned', 'wp-crm-system' ) . '</strong></th></tr>';
			foreach ( $wpcposts as $wpcpost ) {

				$task_report .= '<tr><td>';

				$task_report .= '<a href="' . esc_url( get_edit_post_link( $wpcpost->ID ) ) . '">' . esc_html( get_the_title( $wpcpost->ID ) ) . '</a>';

				$due_output = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'task-due-date', true ) );
				if ( '' !== $due_output ) {
					$due_output = date( esc_html( get_option( 'wpcrm_system_php_date_format' ) ), $due_output );
				} else {
					$due_output = 'Not set';
				}
				$task_report .= '</td><td>' . $due_output;

				$progress_output = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'task-progress', true ) );
				if ( 'zero' === $progress_output ) {
					$progress_output = '0';
				}
				$task_report .= '</td><td>' . $progress_output . '%';

				$priority_output = ucfirst( esc_html( get_post_meta( $wpcpost->ID, $prefix . 'task-priority', true ) ) );
				if ( '' === $priority_output ) {
					$priority_output = 'None';
				}
				$task_report .= '</td><td>' . $priority_output;

				$status_output = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'task-status', true ) );
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
				$task_report .= '</td><td>' . $status_output;

				$org                 = '';
				$organization_output = '';
				$org                 = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'task-attach-to-organization', true ) );
				if ( '' === $org ) {
					$organization_output = '';
				} else {
					$organization_output .= '<a href="' . esc_url( get_edit_post_link( $org ) ) . '">' . esc_html( get_the_title( $org ) ) . '</a>';
				}
				$task_report .= '</td><td>' . $organization_output;

				$con            = '';
				$contact_output = '';
				$con            = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'task-attach-to-contact', true ) );
				if ( '' === $con ) {
					$contact_output = '';
				} else {
					$contact_output .= '<a href="' . esc_url( get_edit_post_link( $con ) ) . '">' . esc_html( get_the_title( $con ) ) . '</a>';
				}
				$task_report .= '</td><td>' . $contact_output;

				$pro            = '';
				$project_output = '';
				$pro            = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'task-attach-to-project', true ) );
				if ( '' === $pro ) {
					$project_output = '';
				} else {
					$project_output .= '<a href="' . esc_url( get_edit_post_link( $pro ) ) . '">' . esc_html( get_the_title( $pro ) ) . '</a>';
				}
				$task_report .= '</td><td>' . $project_output;

				$asg               = '';
				$assignment_output = '';
				$asg               = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'task-assignment', true ) );
				if ( '' === $asg ) {
					$assignment_output = '';
				} else {
					$assignment_output .= $asg;
				}
				$task_report .= '</td><td>' . $assignment_output;

				$task_report .= '</td></tr>';
			}
		} else {
			$task_report .= '<tr><th><strong>Task</strong></th><tr><td>' . esc_attr_x( 'No tasks to report.', 'wp-crm-system' ) . '</td></tr>';
		}

		print $task_report;
	}
}
