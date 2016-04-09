<?php defined( 'ABSPATH' ) OR exit;
include(plugin_dir_path( __FILE__ ) . 'includes/wp-crm-system-vars.php');
$current_user = wp_get_current_user();
wpcrm_send_email();
$to = array();
$subject = '';
$message = '';
$filterOrg = '';
$filter_cats = '';
$check = '';
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

$terms = get_terms('contact-type');
$checked[] = '';
if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
	$term_id = array();
	foreach ( $terms as $term ) {
		if (get_option($term->slug.'-email-filter') == 'yes') {
			$checked[$term->slug.'-email-filter'] = 'yes';
			$filter_cats = 'yes';
			$term_id[] = $term->slug;
		} else {
			$checked[$term->slug.'-email-filter'] = 'no';
		}
	}
}
?>
<div class="wrap">
	<div>
		<h2><?php _e('WP-CRM System Send Email', 'wp-crm-system'); ?></h2>
		<?php  // Get Organizations to Filter
			$organizations = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-organization');
			$loop = new WP_Query( $organizations );?>
				<form id="wpcrm_email_settings" name="wpcrm_email_settings" method="post" action="options.php">
					<?php wp_nonce_field( 'update-options' ); ?>
					<?php settings_fields( 'wpcrm_system_email_group' ); ?>
					<?php if($organizations) { ?>
						<p><strong><?php _e('Filter Contacts by Organization', 'wp-crm-system'); ?></strong></p>
						<div>
						<select name="wpcrm_system_email_organization_filter" id="wpcrm_system_email_organization_filter">
							<option value=""><?php _e('Do not filter by organization','wp-crm-system'); ?></option>
							<?php while ( $loop->have_posts() ) : $loop->the_post();
								$orgID = get_the_ID();
								$title = get_the_title();
								if(get_option('wpcrm_system_email_organization_filter') == $orgID) {$selected = ' selected'; $filterOrg = $orgID;} else {$selected = '';} ?>
								<option value="<?php echo $orgID; ?>"<?php echo $selected; ?> ><?php echo $title; ?></option>
							<?php
							endwhile;
							?>
						</select>
						</div><br />
					<?php }
					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
					<div id="wp-crm-system-accordion">
						<h3><?php _e('Filter Contacts by Categories', 'wp-crm-system'); ?></h3>
						<div>
						<ul>
							<?php foreach ( $terms as $term ) {
								if ($checked[$term->slug.'-email-filter'] == 'yes') { $check = 'checked'; } else { $check = ''; }
								echo '<li><input type="checkbox" name="'. $term->slug . '-email-filter" id="'. $term->slug . '-email-filter" value="yes" ' . $check . ' />' . $term->name . '</li>';
							} ?>
						</ul>
						</div>
					</div>
					<?php } ?>
					<input type="hidden" name="action" value="update" /><?php submit_button(__('Filter Recipients','wp-crm-system')); ?>
				</form>
		<form method="post" action="">
			<table style="border-collapse: collapse;width:100%;">
				<tbody>
					<tr>
						<td><?php _e('Select Recipients','wp-crm-system'); ?></td>
						<td>
						<?php 
						if($filterOrg != '') {
							if($filter_cats != 'yes') {
								foreach ( $terms as $term ) {
									$term_id[] = $term->slug;
								}
							}
							$meta_key1 = $prefix . 'contact-attach-to-organization';
							$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-organization','p'=>$filterOrg);
							$loop = new WP_Query( $args );
							while ( $loop->have_posts() ) : $loop->the_post();
								$meta_key1_value = get_the_ID();
								$meta_key1_display = get_the_title();
								$args = array(
									'post_type'		=>	'wpcrm-contact',
									'meta_query'	=> array(
										array(
											'key'		=>	$meta_key1,
											'value'		=>	$meta_key1_value,
											'compare'	=>	'=',
										),
									),
									'tax_query'		=> array(
											array(
												'taxonomy'	=> 'contact-type',
												'field'		=> 'slug',
												'terms'		=> $term_id,
											),
										),
								);
								$posts = get_posts($args);
								if ($posts) { ?>
									<select class="wp-crm-email" name="wpcrm-email-recipients[]" id="wpcrm-email-recipients" multiple>
								<?php 
									foreach($posts as $org) {
										setup_postdata( $org ); 
										$custom = get_post_custom($org->ID);
										$email = $custom['_wpcrm_contact-email'];
										foreach ( $email as $key => $value ) {
											$emailaddress = $value;
										}
										$name = get_the_title($org->ID);
										if (is_email($emailaddress)) { ?>
											<option value="<?php echo $emailaddress; ?>" <?php if (in_array($emailaddress,$to)){echo 'selected="selected"';} ?> ><?php echo $name; ?></option>
										<?php }
									} ?>
									</select>
								<?php } else {
									_e('Well this is awkward. It seems like you have no one to email. Why not add some contacts first then come back to try again.','wp-crm-system');
								}
							endwhile; ?>
							
						<?php } else {
							$term_id = array();
							if($filter_cats != 'yes') {
								foreach ( $terms as $term ) {
									$term_id[] = $term->slug;
								}
							}
							$args = array(
								'posts_per_page'	=> -1,
								'post_type'			=> 'wpcrm-contact',
								'tax_query'			=> array(
									array(
										'taxonomy'	=> 'contact-type',
										'field'		=> 'slug',
										'terms'		=> $term_id,
									),
								),
							);
							$contacts = get_posts($args);
							if($contacts) { ?>
								<select class="wp-crm-email" name="wpcrm-email-recipients[]" id="wpcrm-email-recipients" multiple>
									<?php foreach ($contacts as $contact) { 
										setup_postdata( $contact ); 
										$custom = get_post_custom($contact->ID);
										$email = $custom['_wpcrm_contact-email'];
										foreach ( $email as $key => $value ) {
											$emailaddress = $value;
										}
										$name = get_the_title($contact->ID);
										// Make sure the contact has an email address so we're not talking to ourselves.
										if (is_email($emailaddress)) { ?>
											<option value="<?php echo $emailaddress; ?>" <?php if (in_array($emailaddress,$to)){echo 'selected="selected"';} ?> ><?php echo $name; ?></option>
										<?php }
									} ?>
								</select>
							<?php } else {
								_e('Well this is awkward. It seems like you have no one to email. Why not add some contacts first then come back to try again.','wp-crm-system');
							}
						} ?>
						</td>
					</tr>
					<tr>
						<td><?php _e('Email Subject','wp-crm-system'); ?></td>
						<td>
							<input class="wp-crm-email" type="text" name="wpcrm-email-subject" id="wpcrm-email-subject" value="<?php echo $subject; ?>" />
						</td>
					</tr>
					<tr>
						<td><?php _e('Email Message','wp-crm-system'); ?></td>
						<td>
							<textarea class="wp-crm-email" name="wpcrm-email-message" id="wpcrm-email-message"><?php echo $message; ?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php _e('From Email Address','wp-crm-system'); ?></td>
						<td>
							<input class="wp-crm-email" type="text" name="wpcrm-email-from-address" id="wpcrm-email-from-address" value="<?php echo $fromemail; ?>" />
						</td>
					</tr>
					<tr>
						<td><?php _e('From Name','wp-crm-system'); ?></td>
						<td>
							<input class="wp-crm-email" type="text" name="wpcrm-email-from-name" id="wpcrm-email-from-name" value="<?php echo $fromname; ?>"/>
						</td>
					</tr>
					<tr>
						<td><?php _e('Send Email','wp-crm-system'); ?></td>
						<td>
							<input class="wp-crm-email" type="submit" class="button-secondary" name="wpcrm_email_send" value="<?php _e('Send Email','wp-crm-system'); ?>"/>
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