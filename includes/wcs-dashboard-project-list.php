<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_ajax_project_list() {
	if( !isset( $_POST['project_list_nonce'] ) || !wp_verify_nonce($_POST['project_list_nonce'], 'project-list-nonce') )
		die('Permissions check failed');

	if( !isset( $_POST['project_id'] ) )
		die('No project data sent');

	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php' );

	$project_id		= absint( $_POST['project_id'] );
	$project_meta	= array(
		'value',
		'closedate',
		'status',
		'progress',
		'attach-to-organization',
		'attach-to-contact',
		'assigned'
	);
	$project_info = array();
	foreach ( $project_meta as $meta ){
		$project_info[] = esc_html( get_post_meta( $project_id, '_wpcrm_project-' . $meta, true ) );
	} 
	$title = get_the_title( $project_id );
	$value = '' != $project_info['0'] ? wpcrm_system_display_currency_symbol( get_option( 'wpcrm_system_default_currency' ) ) . $project_info['0'] : '';
	$date = date( get_option( 'wpcrm_system_php_date_format' ), $project_info['1'] );
	$company = '' != trim( $project_info['4'] ) ? '<a href="' . get_edit_post_link( $project_info['4'] ) . '">' . get_the_title( $project_info['4'] ) . '</a>' : '';
	$contact = '' != trim( $project_info['5'] ) ? '<a href="' . get_edit_post_link( $project_info['5'] ) . '">' . get_the_title( $project_info['5'] ) . '</a>' : '';
	$user = get_user_by( 'login', $project_info['6'] );
	$user_display = '';
	if ( !empty( $user ) ){
		$user_display = $user->display_name;
	}
	?>
	<table>
	<?php
		if ( '' != trim( $title ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-clipboard wpcrm-dashicons" title="<?php _e( 'Project Name', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<a href="<?php echo get_edit_post_link( $project_id ); ?>"><?php echo trim( $title ); ?></a>
			</td>
		</tr>
		<?php }
		if ( '' != trim( $value ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-chart-line wpcrm-dashicons" title="<?php _e( 'Project Value', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo $value; ?>
			</td>
		</tr>
		<?php }
		if ( '' != trim( $date ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-calendar-alt wpcrm-dashicons" title="<?php _e( 'Close Date', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo $date; ?>
			</td>
		</tr>
		<?php }
		if ( '' != trim( $project_info['2'] ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-admin-tools wpcrm-dashicons" title="<?php _e( 'Project Status', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo wpcrm_system_display_status( trim( $project_info['2'] ) ); ?>
			</td>
		</tr>
		<?php }
		if ( '' != trim( $project_info['3'] ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-forms wpcrm-dashicons" title="<?php _e( 'Progress', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo wpcrm_system_display_progress( trim( $project_info['3'] ) ); ?>
			</td>
		</tr>
		<?php }
		if ( '' != trim( $company ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-building wpcrm-dashicons" title="<?php _e( 'Company', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo $company; ?>
			</td>
		</tr>
		<?php }
		if ( '' != trim( $contact ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-id wpcrm-dashicons" title="<?php _e( 'Contact Name', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo $contact; ?>
			</td>
		</tr>
		<?php } 
		if ( '' != trim( $user_display ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-businessman wpcrm-dashicons" title="<?php _e( 'Assigned to User', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo $user_display; ?>
			</td>
		</tr>
		<?php } ?>
	</table>
	<?php	
	wp_die();
}
add_action('wp_ajax_project_list_response', 'wp_crm_system_ajax_project_list');

function wpcrm_system_dashboard_projects_js($hook){
	wp_enqueue_script( 'wp_crm_system_dashboard_project_list', WP_CRM_SYSTEM_PLUGIN_URL . '/js/dashboard-project-list.js', array( 'jquery' ), WP_CRM_SYSTEM_VERSION, false );
	wp_localize_script('wp_crm_system_dashboard_project_list', 'project_list_vars', array(
			'project_list_nonce'	=> wp_create_nonce('project-list-nonce'),
		)
	);
}
add_action('admin_enqueue_scripts', 'wpcrm_system_dashboard_projects_js');