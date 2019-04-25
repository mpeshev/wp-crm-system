<?php
if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Send Email notifications for Opportunities
function wp_crm_system_notify_email_opportunities( $ID, $post ){
	include ( WP_PLUGIN_DIR.'/wp-crm-system/includes/wp-crm-system-vars.php' );
	$enable_email = '';
	$opportunityassigned = '';
	$opportunitymessage = '';
	if(get_option( $prefix . 'enable_email_notification' ) == 'yes' ) {
		$enable_email = get_option( $prefix . 'enable_email_notification' );
	}
	if( '' != trim(get_option( $prefix . 'email_opportunity_message' ) ) ) {
		$opportunitymessage = get_option( $prefix . 'email_opportunity_message' );
	}
	if( isset( $_POST[$prefix . 'opportunity-assigned'] ) && $_POST[$prefix . 'opportunity-assigned'] != '' ) {
		$opportunityassigned = $_POST[$prefix . 'opportunity-assigned'];
	}
    if( '' == $enable_email || '' == $opportunityassigned || '' == $opportunitymessage ) {
        return;
    }

	//Make sure post meta is available and updated before we use it
	if( isset( $_POST[$prefix . 'opportunity-assigned'] ) && $_POST[$prefix . 'opportunity-assigned'] != '' ) {
		update_post_meta( $ID, $prefix . 'opportunity-assigned', $_POST[$prefix . 'opportunity-assigned'] );
	}
	if( isset( $_POST[$prefix . 'opportunity-attach-to-organization'] ) && $_POST[$prefix . 'opportunity-attach-to-organization'] != '' ) {
		update_post_meta( $ID, $prefix . 'opportunity-attach-to-organization', $_POST[$prefix . 'opportunity-attach-to-organization'] );
	}
	if( isset( $_POST[$prefix . 'opportunity-attach-to-contact'] ) && $_POST[$prefix . 'opportunity-attach-to-contact'] != '' ) {
		update_post_meta( $ID, $prefix . 'opportunity-attach-to-contact', $_POST[$prefix . 'opportunity-attach-to-contact'] );
	}
	if( isset( $_POST[$prefix . 'opportunity-probability'] ) && $_POST[$prefix . 'opportunity-probability'] != '' ) {
		update_post_meta( $ID, $prefix . 'opportunity-probability', $_POST[$prefix . 'opportunity-probability'] );
	}
	if( isset( $_POST[$prefix . 'opportunity-closedate'] ) && $_POST[$prefix . 'opportunity-closedate'] != '' ) {
		update_post_meta( $ID, $prefix . 'opportunity-closedate', $_POST[$prefix . 'opportunity-closedate'] );
	}
	if( isset( $_POST[$prefix . 'opportunity-value'] ) && $_POST[$prefix . 'opportunity-value'] != '' ) {
		update_post_meta( $ID, $prefix . 'opportunity-value', $_POST[$prefix . 'opportunity-value'] );
	}
	if( isset( $_POST[$prefix . 'opportunity-wonlost'] ) && $_POST[$prefix . 'opportunity-wonlost'] != '' ) {
		update_post_meta( $ID, $prefix . 'opportunity-wonlost', $_POST[$prefix . 'opportunity-wonlost'] );
	}


	//Specific data to send in email.
	$title	= $post->post_title;
	$edit	= get_edit_post_link( $ID, '' );

	$user	= get_post_meta( $ID, $prefix . 'opportunity-assigned', true );
	if( $user != '' ){
		$assignedUsername	= get_user_by( 'login', $user );
		$assigned			= $assignedUsername->display_name;
		$email				= $assignedUsername->user_email;
		$name				= $assignedUsername->first_name . ' ' . $assignedUsername->last_name;
	} else {
		$assigned	= __( 'Not assigned', 'wp-crm-system' );
		$email		= __( 'Not assigned', 'wp-crm-system' );
		$name		= __( 'Not assigned', 'wp-crm-system' );
	}
	$org	= get_post_meta( $ID, $prefix . 'opportunity-attach-to-organization', true );
	if( $org == '' ) {
		$org = __( 'Not set', 'wp-crm-system' );
	} else {
		$org = get_the_title( $org);
	}
	$contact = get_post_meta( $ID, $prefix . 'opportunity-attach-to-contact', true );
	if( $contact == '' ) {
		$contact = __( 'Not set', 'wp-crm-system' );
	} else {
		$contact = get_the_title( $contact);
	}
	$probability = get_post_meta( $ID, $prefix . 'opportunity-probability', true );
	if( $probability == '' ) {
		$probability = __( 'Not set', 'wp-crm-system' );
	} else {
		$probability = $probability.'%';
	}
	$close = get_post_meta( $ID, $prefix . 'opportunity-closedate', true );
	if( $close != '' ) {
		$closedate = $close;
	} else {
		$closedate = __( 'No close date set', 'wp-crm-system' );
	}
	$value = get_post_meta( $ID, $prefix . 'opportunity-value', true );
	if( $value == '' ) {
		$value = __( 'Not set', 'wp-crm-system' );
	} else {
		$currency = get_option( 'wpcrm_system_default_currency' );
		$value = strtoupper( $currency ) . ' ' . number_format( $value, get_option( 'wpcrm_system_report_currency_decimals' ), get_option( 'wpcrm_system_report_currency_decimal_point' ), get_option( 'wpcrm_system_report_currency_thousand_separator' ) );
	}
	$status = get_post_meta( $ID, $prefix . 'opportunity-wonlost', true );
	$statusargs = array( 'not-set'=>__( 'Select an option', 'wp-crm-system' ), 'won'=>_x( 'Won', 'Successful, a winner.', 'wp-crm-system' ), 'lost'=>_x( 'Lost', 'Unsuccessful, a loser.', 'wp-crm-system' ), 'suspended'=>_x( 'Suspended', 'Temporarily ended, but may resume again.', 'wp-crm-system' ), 'abandoned'=>_x( 'Abandoned', 'No longer actively working on.', 'wp-crm-system' ) );
	if( isset( $statusargs[$status] ) ) {
		$sendstatus = $statusargs[$status];
	}

	$vars = array(
		'{title}' 			=> $title,
		'{url}'				=> $edit,
		'{titlelink}'		=> "<a href='" . $edit . "'>" . $title . "</a>",
		'{assigned}'		=> $assigned,
		'{organization}'	=> $org,
		'{contact}'			=> $contact,
		'{close}'			=> $closedate,
		'{probability}'		=> $probability,
		'{value}'			=> $value,
		'{status}'			=> $sendstatus,
	);

	// Setup wp_mail
	$to			= sprintf( '%s <%s>', $name, $email );
	$subject	= $title . ' Update';
    $message	= strtr( $opportunitymessage, $vars );
	if( get_option( $prefix . 'enable_html_email' ) == 'yes' ) {
		$headers = 'Content-type: text/html';
	} else {
		$headers = '';
	}
	$send_email	= array(
		'send'		=> true,
		'vars'		=> $vars,
		'to'		=> $to,
		'subject'	=> $subject,
		'message'	=> $message,
		'headers'	=> $headers,
		'id'		=> $ID,
		'post'		=> $post,
	);
	$send_email = apply_filters( 'wp_crm_system_email_notifications_opportunity', $send_email );
	if( $send_email['send'] ){
		wp_mail( $send_email['to'], $send_email['subject'], $send_email['message'], $send_email['headers'] );
	}
	return;
}
add_action( 'publish_wpcrm-opportunity', 'wp_crm_system_notify_email_opportunities', 10, 2 );