<?php
/* Prevent direct access to the plugin */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, you are not allowed to access this page directly.' );
}
add_action( 'load-edit.php', 'wp_crm_system_recurring_task_notice' );
function wp_crm_system_recurring_task_notice() {

	$screen = get_current_screen();

	if ( 'edit-wpcrm-task' === $screen->id ) {
		add_action(
			'all_admin_notices',
			function() {
				$url  = admin_url( 'admin.php?page=wpcrm-settings&tab=recurring' );
				$link = sprintf(
					wp_kses(
						__( 'Need a task to repeat after a period of time? Try the <a href="%s">Recurring Entries</a> setting.', 'wp-crm-system' ),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					),
					esc_url( $url )
				);
				echo '<div class="notice notice-success"><p>' . $link . '</p></div>';
			}
		);

	}
}
add_filter( 'manage_edit-wpcrm-task_columns', 'wpcrm_system_task_columns' );

function wpcrm_system_task_columns( $columns ) {

	$columns = array(
		'cb'       => '<input type="checkbox" />',
		'title'    => __( 'Task', 'wp-crm-system' ),
		'start'    => __( 'Start Date', 'wp-crm-system' ),
		'due'      => __( 'Due Date', 'wp-crm-system' ),
		'progress' => __( 'Progress', 'wp-crm-system' ),
		'priority' => __( 'Priority', 'wp-crm-system' ),
		'status'   => __( 'Status', 'wp-crm-system' ),
		'date'     => __( 'Date', 'wp-crm-system' ),
		'category' => __( 'Category', 'wp-crm-system' ),
	);

	return $columns;
}

add_action( 'manage_wpcrm-task_posts_custom_column', 'wprcm_system_task_columns_content', 10, 2 );

function wprcm_system_task_columns_content( $column, $post_id ) {
	global $post;

	switch ( $column ) {

		/* If displaying the 'start date' column. */
		case 'start':
			/* Get the post meta. */
			$start = get_post_meta( $post_id, '_wpcrm_task-start-date', true );

			/* If no duration is found, output a default message. */
			if ( empty( $start ) ) {
				echo __( 'Not Set', 'wp-crm-system' );
			}

			/* If there is a start date, display it in the set date format. */
			else {
				echo date( get_option( 'wpcrm_system_php_date_format' ), esc_html( $start ) );
			}

			break;
		/* If displaying the 'due date' column. */
		case 'due':
			/* Get the post meta. */
			$due = get_post_meta( $post_id, '_wpcrm_task-due-date', true );

			/* If no duration is found, output a default message. */
			if ( empty( $due ) ) {
				echo __( 'Not Set', 'wp-crm-system' );
			}

			/* If there is a due date, display it in the set date format. */
			else {
				echo date( get_option( 'wpcrm_system_php_date_format' ), esc_html( $due ) );
			}

			break;
		/* If displaying the 'progress' column. */
		case 'progress':
			/* Get the post meta. */
			$progress = get_post_meta( $post_id, '_wpcrm_task-progress', true );

			/* If no duration is found, output a default message. */
			if ( empty( $progress ) ) {
				echo __( 'Not Set', 'wp-crm-system' );
			}

			/* If there is a progress, append '%' to the text string. */
			else {
				echo esc_html( $progress ) . '%';
			}

			break;
		/* If displaying the 'priority' column. */
		case 'priority':
			/* Get the post meta. */
			$priority = get_post_meta( $post_id, '_wpcrm_task-priority', true );

			$priorities = array(
				''       => __( 'Not Set', 'wp-crm-system' ),
				'low'    => __( 'Low', 'wp-crm-system' ),
				'medium' => __( 'Medium', 'wp-crm-system' ),
				'high'   => __( 'High', 'wp-crm-system' ),
			);
			/* If no duration is found, output a default message. */
			if ( empty( $priority ) ) {
				echo __( 'Not Set', 'wp-crm-system' );
			}

			/* If there is a priority, display it. */
			elseif ( array_key_exists( $priority, $priorities ) ) {
				echo esc_html( $priorities[ $priority ] );
			}

			break;
		/* If displaying the 'status' column. */
		case 'status':
			/* Get the post meta. */
			$status = get_post_meta( $post_id, '_wpcrm_task-status', true );

			$statuses = array(
				'not-started' => __( 'Not Started', 'wp-crm-system' ),
				'in-progress' => __( 'In Progress', 'wp-crm-system' ),
				'complete'    => __( 'Complete', 'wp-crm-system' ),
				'on-hold'     => __( 'On Hold', 'wp-crm-system' ),
			);

			/* If no duration is found, output a default message. */
			if ( empty( $status ) ) {
				echo __( 'Not Set', 'wp-crm-system' );
			}

			/* If there is a status, display it. */
			elseif ( array_key_exists( $status, $statuses ) ) {
				echo esc_html( $statuses[ $status ] );
			}

			break;
		/* If displaying the 'category' column */
		case 'category':
			$categories = get_the_terms( $post_id, 'task-type' );
			if ( ! empty( $categories ) ) {
				sort( $categories );
				foreach ( $categories as $category ) {
					echo '<a href="' . esc_url( admin_url( 'edit.php?task-type=' . $category->slug . '&post_type="wpcrm-task"', 'admin' ) ) . '">' . esc_html( $category->name ) . '</a><br />';
				}
			}
			break;
		/* Just break out of the switch statement for everything else. */
		default:
			break;
	}
}

add_filter( 'manage_edit-wpcrm-task_sortable_columns', 'wpcrm_system_task_sortable_columns' );

function wpcrm_system_task_sortable_columns( $columns ) {

	$columns['start']    = 'start';
	$columns['due']      = 'due';
	$columns['progress'] = 'progress';
	$columns['priority'] = 'priority';
	$columns['status']   = 'status';

	return $columns;
}

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'wpcrm_system_task_edit_load' );

function wpcrm_system_task_edit_load() {
	add_filter( 'request', 'wpcrm_system_sort_task_columns' );
}

/* Sorts the tasks. */
function wpcrm_system_sort_task_columns( $vars ) {

	/* Check if we're viewing the 'wpcrm-task' post type. */
	if ( isset( $vars['post_type'] ) && 'wpcrm-task' == $vars['post_type'] ) {

		/* Check if 'orderby' is set. */
		if ( isset( $vars['orderby'] ) ) {
			switch ( $vars['orderby'] ) {
				case 'start':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_task-start-date',
							'orderby'  => 'meta_value_num',
						)
					);
					break;
				case 'due':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_task-due-date',
							'orderby'  => 'meta_value_num',
						)
					);
					break;
				case 'progress':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_task-progress',
							'orderby'  => 'meta_value_num',
						)
					);
					break;
				case 'priority':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_task-priority',
							'orderby'  => 'meta_value',
						)
					);
					break;
				case 'status':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_task-status',
							'orderby'  => 'meta_value',
						)
					);
					break;
				default:
					break;
			}
		}
	}
	return $vars;
}

/**
 * Add custom action link for quick status update to "Complete"
 *
 * @since   3.1.7
 */
add_filter( 'post_row_actions', 'wpcrm_system_task_row_actions', 10, 2 );
function wpcrm_system_task_row_actions( $actions, $post ) {

	if ( 'wpcrm-task' === $post->post_type ) {
		$url = admin_url( 'post.php?post=' . $post->ID );

		// Keep the Trash on new var for now
		$trash = $actions['trash'];

		// Remove the Trash and re-add on the last child
		unset( $actions['trash'] );

		// Include a nonce in this link
		$complete_link = wp_nonce_url( add_query_arg( array( 'action' => 'quick-complete' ), $url ), 'qc_nonce' );

		// Add the new action link
		$actions = array_merge(
			$actions,
			array(
				'complete' => sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( $complete_link ),
					__( 'Complete', 'wp-crm-system' )
				),
			)
		);

		// Re-insert thrash link preserved from the default $actions.
		$actions['trash'] = $trash;
	}

	return $actions;
}

/**
 * Action callback for the Quick Complete
 *
 * @since   1.3.7
 */
add_action( 'admin_init', 'wpcrm_system_task_quick_complete' );
function wpcrm_system_task_quick_complete() {
	if ( isset( $_REQUEST['post'] ) && isset( $_REQUEST['action'] ) ) {
		if ( 'quick-complete' === $_REQUEST['action'] ) {
			$id = sanitize_text_field( $_REQUEST['post'] );
			if ( 'wpcrm-task' === get_post_type( $id ) ) {

				// Update status to complete
				update_post_meta( $id, '_wpcrm_task-status', 'complete' );

				// Always return to task list table
				wp_safe_redirect( admin_url( 'edit.php?post_type=wpcrm-task' ) );
				exit;
			}
		}
	}
}

/**
 * Add new filter option for task statuses
 *
 * This can be found on top of the Task's table list
 *
 * @since   1.3.7
 */
add_action( 'restrict_manage_posts', 'wpcrm_system_task_filtering' );
function wpcrm_system_task_filtering( $post_type ) {
	if ( 'wpcrm-task' !== $post_type ) {
		return;
	}

	/**
	 * For status filter
	 *
	 * We don't have filtered sttus(from WP Hooks) so the values are static
	 */
	$statuses = array(
		'default'     => __( 'All statuses', 'wp-crm-system' ),
		'not-started' => __( 'Not Started', 'wp-crm-system' ),
		'in-progress' => __( 'In Progress', 'wp-crm-system' ),
		'complete'    => __( 'Complete', 'wp-crm-system' ),
		'on-hold'     => __( 'On Hold', 'wp-crm-system' ),
	);

	/**
	 * Get the selected status, if any
	 *
	 * Default will be "default"/ All statuses
	 */
	$selected_status = isset( $_GET['task-status-filter'] ) ? sanitize_text_field( $_GET['task-status-filter'] ) : 'default';

	// Print status dropdown
	$options = '';
	foreach ( $statuses as $key => $value ) {
		$options .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $selected_status, $key, false ), $value );
	}
	printf( '<select name="task-status-filter">%s</select>', $options );
}

/**
 * Hijack the pre get posts
 *
 * So that we can pull the posts based on given status
 *
 * @since   1.3.7
 */
add_action( 'pre_get_posts', 'wpcrm_system_task_pre_get_posts' );
function wpcrm_system_task_pre_get_posts( $query ) {

	// We required post type and status to proceed
	// Failure on both should stops the execution
	if ( ! isset( $_GET['post_type'] ) && ! isset( $_GET['task-status-filter'] ) ) {
		return $query;
	}

	// Set defaults and make sure we sanitize 'em
	$post_type  = sanitize_text_field( $_GET['post_type'] );
	$status     = sanitize_text_field( $_GET['task-status-filter'] );
	$meta_query = array();

	// If status = "default" / All statuses, nothing to do here
	if ( 'default' === $status ) {
		return $query;
	}

	// If this request is not from Task post type, nothing to do here
	if ( 'wpcrm-task' !== $post_type ) {
		return $query;
	}

	// If status is empty, illegal request! Nothing to do here
	if ( empty( $status ) ) {
		return $query;
	}

	if ( $query->is_main_query() ) {
		$meta_query[] = array(
			'key'   => '_wpcrm_task-status',
			'value' => $status,
		);
	}

	if ( ! empty( $meta_query ) ) {
		$query->set( 'meta_query', $meta_query );
	}
}
