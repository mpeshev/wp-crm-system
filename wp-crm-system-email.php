<?php defined( 'ABSPATH' ) OR exit;
$contacts = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-contact'));
$current_user = wp_get_current_user();
wpcrm_send_email();
$to = array();
$subject = '';
$message = '';
$fromemail = $current_user->user_email;
$fromname = $current_user->display_name;
if(isset($_POST['wpcrm_email_send'])) {
	if ('' == $_POST['wpcrm-email-recipients'] || '' == $_POST['wpcrm-email-subject'] || '' == $_POST['wpcrm-email-message'] || '' == $_POST['wpcrm-email-from-name'] || '' == $_POST['wpcrm-email-from-address']) { 
		$recipients = $_POST['wpcrm-email-recipients'];
		foreach ($recipients as $recipient) {
			$to[] = $recipient;
		}
		$subject = $_POST['wpcrm-email-subject'];
		$message = $_POST['wpcrm-email-message'];
		$fromemail = $_POST['wpcrm-email-from-address'];
		$fromname = $_POST['wpcrm-email-from-name'];
	}
}
?>
<div class="wrap">
	<div>
		<h2><?php _e('WP-CRM System Send Email', 'wp-crm-system'); ?></h2>
		<form method="post" action="">
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<tr>
						<td><?php _e('Select Recipients','wp-crm-system'); ?></td>
						<td>
						<?php if($contacts) { ?>
							<select name="wpcrm-email-recipients[]" id="wpcrm-email-recipients" multiple>
								<?php foreach ($contacts as $contact) { 
									setup_postdata( $contact ); 
									$custom = get_post_custom($contact->ID);
									$email = $custom['_wpcrm_contact-email'];
									foreach ( $email as $key => $value ) {
										$emailaddress = $value;
									}
									$name = get_the_title($contact->ID);
									if (is_email($emailaddress)) { ?>
										<option value="<?php echo $emailaddress; ?>" <?php if (in_array($emailaddress,$to)){echo 'selected="selected"';} ?> ><?php echo $name; ?></option>
									<?php }
								} ?>
							</select>
						<?php } else {
							_e('Well this is awkward. It seems like you have no one to email. Why not add some contacts first then come back to try again.','wp-crm-system');
						} ?>
						</td>
					</tr>
					<tr>
						<td><?php _e('Email Subject','wp-crm-system'); ?></td>
						<td>
							<input type="text" name="wpcrm-email-subject" id="wpcrm-email-subject" value="<?php echo $subject; ?>" />
						</td>
					</tr>
					<tr>
						<td><?php _e('Email Message','wp-crm-system'); ?></td>
						<td>
							<textarea name="wpcrm-email-message" id="wpcrm-email-message"><?php echo $message; ?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php _e('From Email Address','wp-crm-system'); ?></td>
						<td>
							<input type="text" name="wpcrm-email-from-address" id="wpcrm-email-from-address" value="<?php echo $fromemail; ?>" />
						</td>
					</tr>
					<tr>
						<td><?php _e('From Name','wp-crm-system'); ?></td>
						<td>
							<input type="text" name="wpcrm-email-from-name" id="wpcrm-email-from-name" value="<?php echo $fromname; ?>"/>
						</td>
					</tr>
					<tr>
						<td><?php _e('Send Email','wp-crm-system'); ?></td>
						<td>
							<input type="submit" class="button-secondary" name="wpcrm_email_send" value="<?php _e('Send Email','wp-crm-system'); ?>"/>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
<?php
function wpcrm_send_email() {
	if (isset($_POST['wpcrm_email_send'])) {
		//All fields are required
		if ('' == $_POST['wpcrm-email-recipients'] || '' == $_POST['wpcrm-email-subject'] || '' == $_POST['wpcrm-email-message'] || '' == $_POST['wpcrm-email-from-name'] || '' == $_POST['wpcrm-email-from-address']) { ?>
			<div class="error"><p><?php _e('All fields are required. Please try again.','wp-crm-system'); ?></p></div><?php 
		} else {
			//Specific data to send in email.
			$recipients = $_POST['wpcrm-email-recipients'];
			$subject = sanitize_text_field($_POST['wpcrm-email-subject']);
			$message = esc_textarea($_POST['wpcrm-email-message']);
			$headers = 'From: "' . sanitize_text_field($_POST['wpcrm-email-from-name']) . '" <' . sanitize_email($_POST['wpcrm-email-from-address']) .'>' . "\r\n";
			$to = array();
			foreach ($recipients as $recipient) {
				$to[] = sanitize_email($recipient);
			}
			
			// Setup wp_mail 
			wp_mail( $to, $subject, $message, $headers );
			
			// Success message ?>
			<div class="updated"><p><?php _e('Email sent successfully.','wp-crm-system'); ?></p></div>
			<?php return;
		}
	} else {
		return;
	}
}
?>