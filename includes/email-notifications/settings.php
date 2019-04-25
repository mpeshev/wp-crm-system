<?php
if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include ( WP_PLUGIN_DIR.'/wp-crm-system/includes/wp-crm-system-vars.php' );

$email_fields = array(
	array(
		'id'	=>	'enable_email_notification',
		'name'	=>	__( 'Check to Enable Email Notifications', 'wp-crm-system' ),
		'input'	=>	'checkbox',
		'more'	=>	__( 'Uncheck if you want to temporarily disable email notifications, but do not want to lose your settings below. This is useful if you are importing a large number of tasks, opportunities, or projects and do not want to overwhelm your inboxes.', 'wp-crm-system' ),
	),
	array(
		'id'	=>	'enable_html_email',
		'name'	=>	__( 'Check to Enable HTML Email', 'wp-crm-system' ),
		'input'	=>	'checkbox',
		'more'	=>	__( 'This will allow basic HTML to be included in your email. If you are familiar with HTML, you can use this option to use some markup such as <code>&lt;strong&gt;</code>, <code>&lt;ul&gt;</code>, <code>&lt;em&gt;</code>, <code>&lt;p&gt;</code>, etc. to markup your messages.', 'wp-crm-system' ),
	),
	array(
		'id'	=>	'email_task_message',
		'name'	=>	__( 'Task Notification Message', 'wp-crm-system' ),
		'input'	=>	'textarea',
		'more'	=>	__( 'No task notifications will be sent if this field is not complete. You can use the placeholder codes below.', 'wp-crm-system' ) . '<br /><strong>{title}</strong> ' . __( 'The title of this task.', 'wp-crm-system' ) . '<br /><strong>{url}</strong> ' . __( 'The link to this task.', 'wp-crm-system' ) . '<br /><strong>{titlelink}</strong> ' . __( 'The title of this task linked to the task. (Will only work with HTML emails enabled).', 'wp-crm-system' ) . '<br /><strong>{assigned}</strong> ' . __( 'The name of the individual this task is assigned to.', 'wp-crm-system' ) . '<br /><strong>{organization}</strong> ' . __( 'The name of the organization this task is associated with.', 'wp-crm-system' ) . '<br /><strong>{contact}</strong> ' . __( 'The name of the contact this task is associated with.', 'wp-crm-system' ) . '<br /><strong>{due}</strong> ' . __( 'The due date of this task.', 'wp-crm-system' ) . '<br /><strong>{start}</strong> ' . __( 'The start date of this task.', 'wp-crm-system' ) . '<br /><strong>{progress}</strong> ' . __( 'The percent completion progress of this task.', 'wp-crm-system' ) . '<br /><strong>{priority}</strong> ' . __( 'The priority level of this task.', 'wp-crm-system' ) . '<br /><strong>{status}</strong> ' . __( 'The status of this task (complete, in-progress, etc.)', 'wp-crm-system' ),
	),
	array(
		'id'	=>	'email_opportunity_message',
		'name'	=>	__( 'Opportunity Notification Message', 'wp-crm-system' ),
		'input'	=>	'textarea',
		'more'	=>	__( 'No opportunity notifications will be sent if this field is not complete. You can use the placeholder codes below.', 'wp-crm-system' ) . '<br /><strong>{title}</strong> ' . __( 'The title of this opportunity.', 'wp-crm-system' ) . '<br /><strong>{url}</strong> ' . __( 'The link to this opportunity.', 'wp-crm-system' ) . '<br /><strong>{titlelink}</strong> ' . __( 'The title of this opportunity linked to the opportunity. (Will only work with HTML emails enabled).', 'wp-crm-system' ) . '<br /><strong>{assigned}</strong> ' . __( 'The name of the individual this opportunity is assigned to.', 'wp-crm-system' ) . '<br /><strong>{organization}</strong> ' . __( 'The name of the organization this opportunity is associated with.', 'wp-crm-system' ) . '<br /><strong>{contact}</strong> ' . __( 'The name of the contact this opportunity is associated with.', 'wp-crm-system' ) . '<br /><strong>{close}</strong> ' . __( 'The close date of this opportunity.', 'wp-crm-system' ) . '<br /><strong>{probability}</strong> ' . __( 'The probability of winning this opportunity.', 'wp-crm-system' ) . '<br /><strong>{value}</strong> ' . __( 'The value assigned to this opportunity. Will be formatted as three character currency (i.e. USD), then the value formatted as indicated in WP-CRM System settings.', 'wp-crm-system' ) . '<br /><strong>{status}</strong> ' . __( 'The won/lost status of this opportunity.', 'wp-crm-system' ),
	),
	array(
		'id'	=>	'email_project_message',
		'name'	=>	__( 'Project Notification Message', 'wp-crm-system' ),
		'input'	=>	'textarea',
		'more'	=>	__( 'No project notifications will be sent if this field is not complete. You can use the placeholder codes below.', 'wp-crm-system' ) . '<br /><strong>{title}</strong> ' . __( 'The title of this project.', 'wp-crm-system' ) . '<br /><strong>{url}</strong> ' . __( 'The link to this project.', 'wp-crm-system' ) . '<br /><strong>{titlelink}</strong> ' . __( 'The title of this project linked to the project. (Will only work with HTML emails enabled).', 'wp-crm-system' ) . '<br /><strong>{assigned}</strong> ' . __( 'The name of the individual this project is assigned to.', 'wp-crm-system' ) . '<br /><strong>{organization}</strong> ' . __( 'The name of the organization this project is associated with.', 'wp-crm-system' ) . '<br /><strong>{contact}</strong> ' . __( 'The name of the contact this project is associated with.', 'wp-crm-system' ) . '<br /><strong>{close}</strong> ' . __( 'The close date of this project.', 'wp-crm-system' ) . '<br /><strong>{progress}</strong> ' . __( 'The percent completion progress of this project.', 'wp-crm-system' ) . '<br /><strong>{value}</strong> ' . __( 'The value assigned to this project. Will be formatted as three character currency (i.e. USD), then the value formatted as indicated in WP-CRM System settings.', 'wp-crm-system' ) . '<br /><strong>{status}</strong> ' . __( 'The status of this project (complete, in-progress, etc.).', 'wp-crm-system' ),
	),
);
?>

<div class="wrap">
	<div>
		<h2><?php _e( 'Email Notifications for WP-CRM System', 'wp-crm-system' ); ?></h2>
		<form method="post" action="options.php">
			<?php wp_nonce_field( 'wpcrm-email-notifications' ); ?>
			<?php settings_fields( 'wpcrm-email-notifications' ); ?>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<?php foreach ( $email_fields as $email_field ) { ?>
					<tr>
						<td>
							<h2><?php echo $email_field['name']; ?></h2>
							<?php echo $email_field['more']; ?>
						</td>
						<td>
						<?php if ( $email_field['input'] == 'textarea' ) { ?>
							<textarea name="<?php echo $prefix.$email_field['id']; ?>" cols="40" rows="9"><?php echo get_option( $prefix.$email_field['id'] ); ?></textarea>
						<?php } else { ?>
							<input type="<?php echo $email_field['input']; ?>" name="<?php echo $prefix.$email_field['id']; ?>" value="<?php if ( $email_field['input'] == 'text' ) { echo get_option( $prefix.$email_field['id'] ); } else { echo 'yes'; } ?>" <?php if ( $email_field['input'] == 'checkbox' && get_option( $prefix.$email_field['id'] ) == 'yes' ) { echo 'checked'; } ?> />
						<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<tr><td><input type="hidden" name="action" value="update" /><?php submit_button(); ?></td></tr>
				</tbody>
			</table>
		</form>
	</div>
</div>