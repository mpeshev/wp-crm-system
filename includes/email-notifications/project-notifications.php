<?php
if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Send Email notifications for Projects
add_action( 'publish_wpcrm-project', 'wp_crm_system_email_notifications_projects', 10, 2 );

function wp_crm_system_email_notifications_projects( $ID, $post ){
	include ( WP_PLUGIN_DIR.'/wp-crm-system/includes/wp-crm-system-vars.php' );
	$enable_email		= '';
	$projectassigned	= '';
	$projectmessage		= '';
	if( get_option( $prefix . 'enable_email_notification' ) == 'yes' ) {
		$enable_email   = get_option( $prefix . 'enable_email_notification' );
	}
	if( '' != trim( get_option( $prefix . 'email_project_message' ) ) ) {
		$projectmessage = get_option( $prefix . 'email_project_message' );
	}
	if( isset( $_POST[$prefix . 'project-assigned'] ) && $_POST[$prefix . 'project-assigned'] != '' ) {
		$projectassigned = $_POST[$prefix . 'project-assigned'];
	}
    if( '' == $enable_email || '' == $projectassigned || '' == $projectmessage ){
        return;
    }

	//Make sure post meta is updated before we use it
	if( isset( $_POST[$prefix . 'project-assigned'] ) && $_POST[$prefix . 'project-assigned'] != '' ) {
		update_post_meta( $ID, $prefix . 'project-assigned', $_POST[$prefix . 'project-assigned'] );
	}
	if( isset( $_POST[$prefix . 'project-attach-to-organization'] ) && $_POST[$prefix . 'project-attach-to-organization'] != '' ) {
		update_post_meta( $ID, $prefix . 'project-attach-to-organization', $_POST[$prefix . 'project-attach-to-organization'] );
	}
	if( isset( $_POST[$prefix . 'project-attach-to-contact'] ) && $_POST[$prefix . 'project-attach-to-contact'] != '' ) {
		update_post_meta( $ID, $prefix . 'project-attach-to-contact', $_POST[$prefix . 'project-attach-to-contact'] );
	}
	if( isset( $_POST[$prefix . 'project-progress'] ) && $_POST[$prefix . 'project-progress'] != '' ) {
		update_post_meta( $ID, $prefix . 'project-progress', $_POST[$prefix . 'project-progress'] );
	}
	if( isset( $_POST[$prefix . 'project-closedate'] ) && $_POST[$prefix . 'project-closedate'] != '' ) {
		update_post_meta( $ID, $prefix . 'project-closedate', $_POST[$prefix . 'project-closedate'] );
	}
	if( isset( $_POST[$prefix . 'project-value'] ) && $_POST[$prefix . 'project-value'] != '' ) {
		update_post_meta( $ID, $prefix . 'project-value', $_POST[$prefix . 'project-value'] );
	}
	if( isset( $_POST[$prefix . 'project-status'] ) && $_POST[$prefix . 'project-status'] != '' ) {
		update_post_meta( $ID, $prefix . 'project-status', $_POST[$prefix . 'project-status'] );
	}


	//Specific data to send in email.
	$title	= $post->post_title;
	$edit	= get_edit_post_link( $ID, '' );

	// User specific information
	$user	= get_post_meta( $ID, $prefix . 'project-assigned', true );
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

	// Organization specific information
	$org = get_post_meta( $ID, $prefix . 'project-attach-to-organization', true );
	if( $org == '' ) {
		$org = __( 'Not set', 'wp-crm-system' );
	} else {
		$org = get_the_title( $org );
	}

	// Contact specific information
	$contact = get_post_meta( $ID, $prefix . 'project-attach-to-contact', true );
	if( $contact == '' ) {
		$contact = __( 'Not set', 'wp-crm-system' );
	} else {
		$contact = get_the_title( $contact );
	}

	$progress = get_post_meta( $ID, $prefix . 'project-progress', true ) . '%';

	$close = get_post_meta( $ID, $prefix . 'project-closedate', true );
	if( $close != '' ) {
		$closedate = $close;
	} else {
		$closedate = __( 'No close date set', 'wp-crm-system' );
	}

	$value = get_post_meta( $ID, $prefix . 'project-value', true );
	if( $value == '' ) {
		$value = __( 'Not set', 'wp-crm-system' );
	} else {
		$currency = get_option( 'wpcrm_system_default_currency' );
		$value = strtoupper( $currency ) . ' ' . number_format( $value,get_option( 'wpcrm_system_report_currency_decimals' ),get_option( 'wpcrm_system_report_currency_decimal_point' ),get_option( 'wpcrm_system_report_currency_thousand_separator' ) );
	}

	$status = get_post_meta( $ID, $prefix . 'project-status', true );
	$statusargs = array(
		'not-started'	=> _x( 'Not Started', 'Work has not yet begun.', 'wp-crm-system' ),
		'in-progress'	=> _x( 'In Progress', 'Work has begun but is not complete.', 'wp-crm-system' ),
		'complete'		=> _x( 'Complete', 'All tasks are finished. No further work is needed.', 'wp-crm-system' ),
		'on-hold'		=> _x( 'On Hold', 'Work may be in various stages of completion, but has been stopped for one reason or another.', 'wp-crm-system' )
	);
	if( isset( $statusargs[$status] ) ) {
		$sendstatus = $statusargs[$status];
	} else {
		$sendstatus = __( 'Not set', 'wp-crm-system' );
	}

	$vars = array(
		'{title}' 			=> $title,
		'{url}'				=> $edit,
		'{titlelink}'		=> "<a href='" . $edit . "'>" . $title . "</a>",
		'{assigned}'		=> $assigned,
		'{organization}'	=> $org,
		'{contact}'			=> $contact,
		'{close}'			=> $closedate,
		'{progress}'		=> $progress,
		'{value}'			=> $value,
		'{status}'			=> $sendstatus,
	);

    // Setup wp_mail
	$to			= sprintf( '%s <%s>', $name, $email );

	$subject	= $title . ' Update';

    $message	= strtr( $projectmessage, $vars );
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
	$send_email = apply_filters( 'wp_crm_system_email_notifications_project', $send_email );
	if( $send_email['send'] ){
		wp_mail( $send_email['to'], $send_email['subject'], $send_email['message'], $send_email['headers'] );
	}
	return;
}