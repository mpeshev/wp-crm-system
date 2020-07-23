<?php
/* Prevent direct access to the plugin */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, you are not allowed to access this page directly.' );
}
add_filter( 'manage_edit-wpcrm-campaign_columns', 'wpcrm_system_campaign_columns' );

function wpcrm_system_campaign_columns( $columns ) {

	$columns = array(
		'cb'        => '<input type="checkbox" />',
		'title'     => __( 'Campaign', 'wp-crm-system' ),
		'active'    => __( 'Active', 'wp-crm-system' ),
		'status'    => __( 'Status', 'wp-crm-system' ),
		'start'     => __( 'Start Date', 'wp-crm-system' ),
		'end'       => __( 'End Date', 'wp-crm-system' ),
		'reach'     => __( 'Projected Reach', 'wp-crm-system' ),
		'responses' => __( 'Total Responses', 'wp-crm-system' ),
		'budget'    => __( 'Budget Cost', 'wp-crm-system' ),
		'actual'    => __( 'Actual Cost', 'wp-crm-system' ),
		'date'      => __( 'Date', 'wp-crm-system' ),
		'category'  => __( 'Category', 'wp-crm-system' ),
	);

	return $columns;
}

add_action( 'manage_wpcrm-campaign_posts_custom_column', 'wprcm_system_campaign_columns_content', 10, 2 );

function wprcm_system_campaign_columns_content( $column, $post_id ) {
	global $post;
	$wpcrm_currencies = array(
		'aed' => 'AED',
		'afn' => '&#1547;',
		'all' => '&#76;&#101;&#107;',
		'amd' => 'AMD',
		'ang' => '&#402;',
		'aoa' => 'AOA',
		'ars' => '&#36;',
		'aud' => '&#36;',
		'awg' => '&#402;',
		'azn' => '&#1084;&#1072;&#1085;',
		'bam' => '&#75;&#77;',
		'bbd' => '&#36;',
		'bdt' => 'BDT',
		'bgn' => '&#1083;&#1074;',
		'bhd' => 'BHD',
		'bif' => 'BIF',
		'bmd' => '&#36;',
		'bnd' => '&#36;',
		'bob' => '&#36;&#98;',
		'brl' => '&#82;&#36;',
		'bsd' => '&#36;',
		'btn' => 'BTN',
		'bwp' => '&#80;',
		'byr' => '&#112;&#46;',
		'bzd' => '&#66;&#90;&#36;',
		'cad' => '&#36;',
		'cdf' => 'CDF',
		'chf' => '&#67;&#72;&#70;',
		'clp' => '&#36;',
		'cny' => '&#165;',
		'cop' => '&#36;',
		'crc' => '&#8353;',
		'cuc' => 'CUC',
		'cup' => '&#8369;',
		'cve' => 'CVE',
		'czk' => '&#75;&#269;',
		'djf' => 'DJF',
		'dkk' => '&#107;&#114;',
		'dop' => '&#82;&#68;&#36;',
		'dzd' => 'DZD',
		'egp' => '&#163;',
		'ern' => 'ERN',
		'etb' => 'ETB',
		'eur' => '&#8364;',
		'fjd' => '&#36;',
		'fkp' => '&#163;',
		'gbp' => '&#163;',
		'gel' => 'GEL',
		'ggp' => '&#163;',
		'ghs' => '&#162;',
		'gip' => '&#163;',
		'gmd' => 'GMD',
		'gnf' => 'GNF',
		'gtq' => '&#81;',
		'gyd' => '&#36;',
		'hkd' => '&#36;',
		'hnl' => '&#76;',
		'hrk' => '&#107;&#110;',
		'htg' => 'HTG',
		'huf' => '&#70;&#116;',
		'idr' => '&#82;&#112;',
		'ils' => '&#8362;',
		'imp' => '&#163;',
		'inr' => '&#8377;',
		'iqd' => 'IQD',
		'irr' => '&#65020;',
		'isk' => '&#107;&#114;',
		'jep' => '&#163;',
		'jmd' => '&#74;&#36;',
		'jod' => 'JOD',
		'jpy' => '&#165;',
		'kes' => 'KES',
		'kgs' => '&#1083;&#1074;',
		'khr' => '&#6107;',
		'kmf' => 'KMF',
		'kpw' => '&#8361;',
		'krw' => '&#8361;',
		'kwd' => 'KWD',
		'kyd' => '&#36;',
		'kzt' => '&#1083;&#1074;',
		'lak' => '&#8365;',
		'lbp' => '&#163;',
		'lkr' => '&#8360;',
		'lrd' => '&#36;',
		'lsl' => 'LSL',
		'lyd' => 'LYD',
		'mad' => 'MAD',
		'mdl' => 'MDL',
		'mga' => 'MGA',
		'mkd' => '&#1076;&#1077;&#1085;',
		'mmk' => 'MMK',
		'mnt' => '&#8366;',
		'mop' => 'MOP',
		'mro' => 'MRO',
		'mur' => '&#8360;',
		'mvr' => 'MVR',
		'mwk' => 'MWK',
		'mxn' => '&#36;',
		'myr' => '&#82;&#77;',
		'mzn' => '&#77;&#84;',
		'nad' => '&#36;',
		'ngn' => '&#8358;',
		'nio' => '&#67;&#36;',
		'nok' => '&#107;&#114;',
		'npr' => '&#8360;',
		'nzd' => '&#36;',
		'omr' => '&#65020;',
		'pab' => '&#66;&#47;&#46;',
		'pen' => '&#83;&#47;&#46;',
		'pgk' => 'PGK',
		'php' => '&#8369;',
		'pkr' => '&#8360;',
		'pln' => '&#122;&#322;',
		'prb' => 'PRB',
		'pyg' => '&#71;&#115;',
		'qar' => '&#65020;',
		'ron' => '&#108;&#101;&#105;',
		'rsd' => '&#1044;&#1080;&#1085;&#46;',
		'rub' => '&#1088;&#1091;&#1073;',
		'rwf' => 'RWF',
		'sar' => '&#65020;',
		'sbd' => '&#36;',
		'scr' => '&#8360;',
		'sdg' => 'SDG',
		'sek' => '&#107;&#114;',
		'sgd' => '&#36;',
		'shp' => '&#163;',
		'sll' => 'SLL',
		'sos' => '&#83;',
		'srd' => '&#36;',
		'ssp' => 'SSP',
		'std' => 'STD',
		'syp' => '&#163;',
		'szl' => 'SZL',
		'thb' => '&#3647;',
		'tjs' => 'TJS',
		'tmt' => 'TMT',
		'tnd' => 'TND',
		'top' => 'TOP',
		'try' => '&#8378;',
		'ttd' => '&#84;&#84;&#36;',
		'twd' => '&#78;&#84;&#36;',
		'tzs' => 'TZS',
		'uah' => '&#8372;',
		'ugx' => 'UGX',
		'usd' => '&#36;',
		'uyu' => '&#36;&#85;',
		'uzs' => '&#1083;&#1074;',
		'vef' => '&#66;&#115;',
		'vnd' => '&#8363;',
		'vuv' => 'VUV',
		'wst' => 'WST',
		'xaf' => 'XAF',
		'xcd' => '&#36;',
		'xof' => 'XOF',
		'xpf' => 'XPF',
		'yer' => '&#65020;',
		'zar' => '&#82;',
		'zmw' => 'ZMW',
	);
	$active_currency  = get_option( 'wpcrm_system_default_currency' );
	$currency_symbol  = '';
	foreach ( $wpcrm_currencies as $currency => $symbol ) {
		if ( $active_currency == $currency ) {
			$currency_symbol = $symbol;
		}
	}

	switch ( $column ) {

		/* If displaying the 'budget' column. */
		case 'active':
			/* Get the post meta. */
			$value = get_post_meta( $post_id, '_wpcrm_campaign-active', true );

			/* If no duration is found, output a default message. */
			if ( empty( $value ) ) {
				_e( 'No', 'wp-crm-system' );
			}

			/* If there is a start date, display it in the set date format. */
			elseif ( 'yes' == $value ) {
				_e( 'Yes', 'wp-crm-system' );
			} else {
				_e( 'No', 'wp-crm-system' );
			}

			break;
		/* If displaying the 'budget' column. */
		case 'budget':
			/* Get the post meta. */
			$value = get_post_meta( $post_id, '_wpcrm_campaign-budgetcost', true );

			/* If no duration is found, output a default message. */
			if ( empty( $value ) ) {
				echo __( 'Not Set', 'wp-crm-system' );
			}

			/* If there is a start date, display it in the set date format. */
			else {
				echo $currency_symbol . esc_html( $value );
			}

			break;
		/* If displaying the 'actual' column. */
		case 'actual':
			/* Get the post meta. */
			$value = get_post_meta( $post_id, '_wpcrm_campaign-actualcost', true );

			/* If no duration is found, output a default message. */
			if ( empty( $value ) ) {
				echo __( 'Not Set', 'wp-crm-system' );
			}

			/* If there is a start date, display it in the set date format. */
			else {
				echo $currency_symbol . esc_html( $value );
			}

			break;
		/* If displaying the 'start date' column. */
		case 'start':
			/* Get the post meta. */
			$date = get_post_meta( $post_id, '_wpcrm_campaign-startdate', true );

			/* If no duration is found, output a default message. */
			if ( empty( $date ) ) {
				echo __( 'Not Set', 'wp-crm-system' );
			}

			/* If there is a start date, display it in the set date format. */
			else {
				echo date( get_option( 'wpcrm_system_php_date_format' ), esc_html( $date ) );
			}

			break;
		/* If displaying the 'end date' column. */
		case 'end':
			/* Get the post meta. */
			$date = get_post_meta( $post_id, '_wpcrm_campaign-enddate', true );

			/* If no duration is found, output a default message. */
			if ( empty( $date ) ) {
				echo __( 'Not Set', 'wp-crm-system' );
			}

			/* If there is a start date, display it in the set date format. */
			else {
				echo date( get_option( 'wpcrm_system_php_date_format' ), esc_html( $date ) );
			}

			break;
		/* If displaying the 'reach' column. */
		case 'reach':
			/* Get the post meta. */
			$reach = get_post_meta( $post_id, '_wpcrm_campaign-projectedreach', true );

			/* If no duration is found, output a default message. */
			if ( empty( $reach ) ) {
				echo __( 'Not Set', 'wp-crm-system' );
			}

			/* If there is a reach, display it. */
			else {
				echo esc_html( $reach );
			}

			break;
		/* If displaying the 'responses' column. */
		case 'responses':
			/* Get the post meta. */
			$responses = get_post_meta( $post_id, '_wpcrm_campaign-responses', true );

			/* If no duration is found, output a default message. */
			if ( empty( $responses ) ) {
				echo __( 'Not Set', 'wp-crm-system' );
			}

			/* If there are responses, display it. */
			else {
				echo esc_html( $responses );
			}

			break;
		/* If displaying the 'status' column. */
		case 'status':
			/* Get the post meta. */
			$status = get_post_meta( $post_id, '_wpcrm_campaign-status', true );

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
			$categories = get_the_terms( $post_id, 'campaign-type' );
			if ( ! empty( $categories ) ) {
				sort( $categories );
				foreach ( $categories as $category ) {
					echo '<a href="' . esc_url( admin_url( 'edit.php?campaign-type=' . $category->slug . '&post_type="wpcrm-campaign"', 'admin' ) ) . '">' . esc_html( $category->name ) . '</a><br />';
				}
			}
			break;
		/* Just break out of the switch statement for everything else. */
		default:
			break;
	}
}

add_filter( 'manage_edit-wpcrm-campaign_sortable_columns', 'wpcrm_system_campaign_sortable_columns' );

function wpcrm_system_campaign_sortable_columns( $columns ) {

	$columns['active']    = 'active';
	$columns['status']    = 'status';
	$columns['start']     = 'start';
	$columns['end']       = 'end';
	$columns['reach']     = 'reach';
	$columns['responses'] = 'responses';
	$columns['budget']    = 'budget';
	$columns['actual']    = 'actual';

	return $columns;
}

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'wpcrm_system_campaign_edit_load' );

function wpcrm_system_campaign_edit_load() {
	add_filter( 'request', 'wpcrm_system_sort_campaign_columns' );
}

/* Sorts the campaign. */
function wpcrm_system_sort_campaign_columns( $vars ) {

	/* Check if we're viewing the 'wpcrm-task' post type. */
	if ( isset( $vars['post_type'] ) && 'wpcrm-campaign' == $vars['post_type'] ) {

		/* Check if 'orderby' is set. */
		if ( isset( $vars['orderby'] ) ) {
			switch ( $vars['orderby'] ) {
				case 'active':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_campaign-active',
							'orderby'  => 'meta_value',
						)
					);
					break;
				case 'status':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_campaign-status',
							'orderby'  => 'meta_value',
						)
					);
					break;
				case 'start':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_campaign-startdate',
							'orderby'  => 'meta_value_num',
						)
					);
					break;
				case 'end':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_campaign-enddate',
							'orderby'  => 'meta_value_num',
						)
					);
					break;
				case 'reach':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_campaign-reach',
							'orderby'  => 'meta_value_num',
						)
					);
					break;
				case 'respnoses':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_campaign-respnoses',
							'orderby'  => 'meta_value_num',
						)
					);
					break;
				case 'budget':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_campaign-budgetcost',
							'orderby'  => 'meta_value_num',
						)
					);
					break;
				case 'actual':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_campaign-actualcost',
							'orderby'  => 'meta_value_num',
						)
					);
					break;
				case 'status':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_campaign-status',
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
