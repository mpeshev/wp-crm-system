<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function wp_crm_system_ajax_opportunity_list() {
	if( !isset( $_POST['opportunity_list_nonce'] ) || !wp_verify_nonce($_POST['opportunity_list_nonce'], 'opportunity-list-nonce') )
		die('Permissions check failed');

	if( !isset( $_POST['opportunity_id'] ) )
		die('No opportunity data sent');

	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php' );

	$opportunity_id	= absint( $_POST['opportunity_id'] );
	$opportunity_meta	= array(
		'attach-to-organization',
		'attach-to-contact',
		'attach-to-campaign',
		'assigned',
		'probability',
		'close-date',
		'value',
		'wonlost'
	);
	$opportunity_info = array();
	foreach ( $opportunity_meta as $meta ){
		$opportunity_info[] = esc_html( get_post_meta( $opportunity_id, '_wpcrm_opportunity-' . $meta, true ) );
	} 
	$title 			= get_the_title( $opportunity_id );
	$company 		= '' != trim( $opportunity_info['0'] ) ? '<a href="' . get_edit_post_link( $opportunity_info['0'] ) . '">' . get_the_title( $opportunity_info['0'] ) . '</a>' : '';
	$contact 		= '' != trim( $opportunity_info['1'] ) ? '<a href="' . get_edit_post_link( $opportunity_info['1'] ) . '">' . get_the_title( $opportunity_info['1'] ) . '</a>' : '';
	$campaign 		= '' != trim( $opportunity_info['2'] ) ? '<a href="' . get_edit_post_link( $opportunity_info['2'] ) . '">' . get_the_title( $opportunity_info['2'] ) . '</a>' : '';
	$user 			= get_user_by( 'login', $opportunity_info['3'] );
	$user_display = '';
	if ( !empty( $user ) ){
		$user_display = $user->display_name;
	}
	$probability	= '' != trim( $opportunity_info['4'] ) ? wpcrm_system_display_progress( $opportunity_info['4'] ) : '';
	$closedate 		= date( get_option( 'wpcrm_system_php_date_format' ), $opportunity_info['5'] );
	$value 			= '' != $opportunity_info['6'] ? wpcrm_system_display_currency_symbol( get_option( 'wpcrm_system_default_currency' ) ) . $opportunity_info['6'] : '';
	$wonlost 		= '' != trim( $opportunity_info['7'] ) ? wpcrm_system_display_wonlost( $opportunity_info['7'] ) : '';
	?>
	<table>
	<?php
		if ( '' != trim( $title ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-phone wpcrm-dashicons" title="<?php _e( 'Opportunity Name', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<a href="<?php echo get_edit_post_link( $opportunity_id ); ?>"><?php echo trim( $title ); ?></a>
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
		if ( '' != trim( $campaign ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-megaphone wpcrm-dashicons" title="<?php _e( 'Related Campaign', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo $campaign; ?>
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
		<?php }
		if ( '' != trim( $probability ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-forms wpcrm-dashicons" title="<?php _e( 'Probability of Winning', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo $probability; ?>
			</td>
		</tr>
		<?php }
		if ( '' != trim( $closedate ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-calendar-alt wpcrm-dashicons" title="<?php _e( 'Close Date', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo $closedate; ?>
			</td>
		</tr>
		<?php }
		if ( '' != trim( $value ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-chart-line wpcrm-dashicons" title="<?php _e( 'Value of Opportunity', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo $value; ?>
			</td>
		</tr>
		<?php }
		if ( '' != trim( $wonlost ) ){ ?>
		<tr>
			<td>
				<span class="wpcrm-system-help-tip dashicons dashicons-yes wpcrm-dashicons" title="<?php _e( 'Won/Lost', 'wp-crm-system' ); ?>"></span>
			</td>
			<td>
				<?php echo trim( $wonlost ); ?>
			</td>
		</tr>
		<?php } ?>
	</table>
	<?php	
	wp_die();
}
add_action('wp_ajax_opportunity_list_response', 'wp_crm_system_ajax_opportunity_list');

function wpcrm_system_dashboard_opportunity_js($hook){
	wp_enqueue_script( 'wp_crm_system_dashboard_opportunity_list', WP_CRM_SYSTEM_PLUGIN_URL . '/js/dashboard-opportunity-list.js', array( 'jquery' ), WP_CRM_SYSTEM_VERSION, false );
	wp_localize_script('wp_crm_system_dashboard_opportunity_list', 'opportunity_list_vars', array(
			'opportunity_list_nonce'	=> wp_create_nonce('opportunity-list-nonce'),
		)
	);
}
add_action('admin_enqueue_scripts', 'wpcrm_system_dashboard_opportunity_js');