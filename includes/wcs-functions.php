<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !function_exists( 'wpcrm_system_display_name_prefix' ) ){
	function wpcrm_system_display_name_prefix( $prefix ){
		$wpcrm_prefixes = array(''=>__('Select an Option','wp-crm-system'),'mr'=>_x('Mr.','Title for male without a higher professional title.','wp-crm-system'),'mrs'=>_x('Mrs.','Married woman or woman who has been married with no higher professional title.','wp-crm-system'),'miss'=>_x('Miss','An unmarried woman. Also Ms.','wp-crm-system'),'ms'=>_x('Ms.','An unmarried woman. Also Miss.','wp-crm-system'),'dr'=>_x('Dr.','Doctor','wp-crm-system'),'master'=>_x('Master','Title used for young men.','wp-crm-system'),'coach'=>_x('Coach','Title used for the person in charge of a sports team','wp-crm-system'),'rev'=>_x('Rev.','Title of a priest or religous clergy - Reverend ','wp-crm-system'),'fr'=>_x('Fr.','Title of a priest or religous clergy - Father','wp-crm-system'),'atty'=>_x('Atty.','Attorney, or lawyer','wp-crm-system'),'prof'=>_x('Prof.','Professor, as in a teacher at a university.','wp-crm-system'),'hon'=>_x('Hon.','Honorable - often used for elected officials or judges.','wp-crm-system'),'pres'=>_x('Pres.','Term given to the head of an organization or country. As in President of a University or President of the United States','wp-crm-system'),'gov'=>_x('Gov.','Governor, as in the Governor of the State of New York.','wp-crm-system'),'ofc'=>_x('Ofc.','Officer as in a police officer.','wp-crm-system'),'supt'=>_x('Supt.','Superintendent','wp-crm-system'),'rep'=>_x('Rep.','Representative - as in an elected official to the House of Representatives','wp-crm-system'),'sen'=>_x('Sen.','An elected official - Senator.','wp-crm-system'),'amb'=>_x('Amb.','Ambassador - a diplomatic official.','wp-crm-system'));
		if ( has_filter( 'wpcrmsystem_name_prefix' ) ){
			$wpcrm_prefixes = apply_filters( 'wpcrmsystem_name_prefix', $wpcrm_prefixes );
		}
		if ( array_key_exists( $prefix, $wpcrm_prefixes ) ){
			return $wpcrm_prefixes[$prefix];
		} else {
			return;
		}
	}
}

if ( !function_exists( 'wpcrm_system_display_currency_symbol' ) ){
	function wpcrm_system_display_currency_symbol( $currency ){
		$wpcrm_currencies = array('aed'=>'AED','afn'=>'&#1547;','all'=>'&#76;&#101;&#107;','amd'=>'AMD','ang'=>'&#402;','aoa'=>'AOA','ars'=>'&#36;','aud'=>'&#36;','awg'=>'&#402;','azn'=>'&#1084;&#1072;&#1085;','bam'=>'&#75;&#77;','bbd'=>'&#36;','bdt'=>'BDT','bgn'=>'&#1083;&#1074;','bhd'=>'BHD','bif'=>'BIF','bmd'=>'&#36;','bnd'=>'&#36;','bob'=>'&#36;&#98;','brl'=>'&#82;&#36;','bsd'=>'&#36;','btn'=>'BTN','bwp'=>'&#80;','byr'=>'&#112;&#46;','bzd'=>'&#66;&#90;&#36;','cad'=>'&#36;','cdf'=>'CDF','chf'=>'&#67;&#72;&#70;','clp'=>'&#36;','cny'=>'&#165;','cop'=>'&#36;','crc'=>'&#8353;','cuc'=>'CUC','cup'=>'&#8369;','cve'=>'CVE','czk'=>'&#75;&#269;','djf'=>'DJF','dkk'=>'&#107;&#114;','dop'=>'&#82;&#68;&#36;','dzd'=>'DZD','egp'=>'&#163;','ern'=>'ERN','etb'=>'ETB','eur'=>'&#8364;','fjd'=>'&#36;','fkp'=>'&#163;','gbp'=>'&#163;','gel'=>'GEL','ggp'=>'&#163;','ghs'=>'&#162;','gip'=>'&#163;','gmd'=>'GMD','gnf'=>'GNF','gtq'=>'&#81;','gyd'=>'&#36;','hkd'=>'&#36;','hnl'=>'&#76;','hrk'=>'&#107;&#110;','htg'=>'HTG','huf'=>'&#70;&#116;','idr'=>'&#82;&#112;','ils'=>'&#8362;','imp'=>'&#163;','inr'=>'&#8377;','iqd'=>'IQD','irr'=>'&#65020;','isk'=>'&#107;&#114;','jep'=>'&#163;','jmd'=>'&#74;&#36;','jod'=>'JOD','jpy'=>'&#165;','kes'=>'KES','kgs'=>'&#1083;&#1074;','khr'=>'&#6107;','kmf'=>'KMF','kpw'=>'&#8361;','krw'=>'&#8361;','kwd'=>'KWD','kyd'=>'&#36;','kzt'=>'&#1083;&#1074;','lak'=>'&#8365;','lbp'=>'&#163;','lkr'=>'&#8360;','lrd'=>'&#36;','lsl'=>'LSL','lyd'=>'LYD','mad'=>'MAD','mdl'=>'MDL','mga'=>'MGA','mkd'=>'&#1076;&#1077;&#1085;','mmk'=>'MMK','mnt'=>'&#8366;','mop'=>'MOP','mro'=>'MRO','mur'=>'&#8360;','mvr'=>'MVR','mwk'=>'MWK','mxn'=>'&#36;','myr'=>'&#82;&#77;','mzn'=>'&#77;&#84;','nad'=>'&#36;','ngn'=>'&#8358;','nio'=>'&#67;&#36;','nok'=>'&#107;&#114;','npr'=>'&#8360;','nzd'=>'&#36;','omr'=>'&#65020;','pab'=>'&#66;&#47;&#46;','pen'=>'&#83;&#47;&#46;','pgk'=>'PGK','php'=>'&#8369;','pkr'=>'&#8360;','pln'=>'&#122;&#322;','prb'=>'PRB','pyg'=>'&#71;&#115;','qar'=>'&#65020;','ron'=>'&#108;&#101;&#105;','rsd'=>'&#1044;&#1080;&#1085;&#46;','rub'=>'&#1088;&#1091;&#1073;','rwf'=>'RWF','sar'=>'&#65020;','sbd'=>'&#36;','scr'=>'&#8360;','sdg'=>'SDG','sek'=>'&#107;&#114;','sgd'=>'&#36;','shp'=>'&#163;','sll'=>'SLL','sos'=>'&#83;','srd'=>'&#36;','ssp'=>'SSP','std'=>'STD','syp'=>'&#163;','szl'=>'SZL','thb'=>'&#3647;','tjs'=>'TJS','tmt'=>'TMT','tnd'=>'TND','top'=>'TOP','try'=>'&#8378;','ttd'=>'&#84;&#84;&#36;','twd'=>'&#78;&#84;&#36;','tzs'=>'TZS','uah'=>'&#8372;','ugx'=>'UGX','usd'=>'&#36;','uyu'=>'&#36;&#85;','uzs'=>'&#1083;&#1074;','vef'=>'&#66;&#115;','vnd'=>'&#8363;','vuv'=>'VUV','wst'=>'WST','xaf'=>'XAF','xcd'=>'&#36;','xof'=>'XOF','xpf'=>'XPF','yer'=>'&#65020;','zar'=>'&#82;','zmw'=>'ZMW');
		if ( array_key_exists( $currency, $wpcrm_currencies ) ){
			return $wpcrm_currencies[$currency];
		} else {
			return;
		}
	}
}

if ( !function_exists( 'wpcrm_system_display_status' ) ){
	function wpcrm_system_display_status( $status ){
		$wpcrm_status = array('not-started'=>_x('Not Started','Work has not yet begun.','wp-crm-system'),'in-progress'=>_x('In Progress','Work has begun but is not complete.','wp-crm-system'),'complete'=>_x('Complete','All tasks are finished. No further work is needed.','wp-crm-system'),'on-hold'=>_x('On Hold','Work may be in various stages of completion, but has been stopped for one reason or another.','wp-crm-system'));
		if ( array_key_exists( $status, $wpcrm_status ) ){
			return $wpcrm_status[$status];
		} else {
			return;
		}
	}
}

if ( !function_exists( 'wpcrm_system_display_progress' ) ){
	function wpcrm_system_display_progress( $progress ){
		$wpcrm_progress = array('zero'=>0,5=>5,10=>10,15=>15,20=>20,25=>25,30=>30,35=>35,40=>40,45=>45,50=>50,55=>55,60=>60,65=>65,70=>70,75=>75,80=>80,85=>85,90=>90,95=>95,100=>100);
		if ( array_key_exists( $progress, $wpcrm_progress ) ){
			return $wpcrm_progress[$progress] . '%';
		} else {
			return;
		}
	}
}

if ( !function_exists( 'wpcrm_system_display_status' ) ){
	function wpcrm_system_display_status( $status ){
		$wpcrm_status = array('not-started'=>_x('Not Started','Work has not yet begun.','wp-crm-system'),'in-progress'=>_x('In Progress','Work has begun but is not complete.','wp-crm-system'),'complete'=>_x('Complete','All tasks are finished. No further work is needed.','wp-crm-system'),'on-hold'=>_x('On Hold','Work may be in various stages of completion, but has been stopped for one reason or another.','wp-crm-system'));
		if ( array_key_exists( $status, $wpcrm_status ) ){
			return $wpcrm_status[$status];
		} else {
			return;
		}
	}
}

if ( !function_exists( 'wpcrm_system_display_priority' ) ){
	function wpcrm_system_display_priority( $priority ){
		$wpcrm_priority = array(''=>__('Not Set', 'wp-crm-system'),'low'=>_x('Low','Not of great importance','wp-crm-system'),'medium'=>_x('Medium','Average priority','wp-crm-system'),'high'=>_x('High','Greatest importance','wp-crm-system'));
		if ( array_key_exists( $status, $wpcrm_priority ) ){
			return $wpcrm_priority[$priority];
		} else {
			return;
		}
	}
}

if ( !function_exists( 'wpcrm_system_display_wonlost' ) ){
	function wpcrm_system_display_wonlost( $wonlost ){
		$wpcrm_wonlost = array('not-set'=>__('Not Set', 'wp-crm-system'),'won'=>_x('Won','Successful, a winner.','wp-crm-system'),'lost'=>_x('Lost','Unsuccessful, a loser.','wp-crm-system'),'suspended'=>_x('Suspended','Temporarily ended, but may resume again.','wp-crm-system'),'abandoned'=>_x('Abandoned','No longer actively working on.','wp-crm-system'));
		if ( array_key_exists( $wonlost, $wpcrm_wonlost ) ){
			return $wpcrm_wonlost[$wonlost];
		} else {
			return;
		}
	}
}

if ( !function_exists( 'wpcrm_system_display_calendar' ) ){
	function wpcrm_system_display_calendar( $types, $month, $year ){
		if ( 'all' == $types ){
			$post_types = array( 'wpcrm-campaign', 'wpcrm-opportunity', 'wpcrm-project', 'wpcrm-task' );
		} else {
			$post_types = array( $types );
		}
		if ( '1' == $month ){
			$prev_month = '12';
			$prev_year 	= $year - 1;
		} else {
			$prev_month = $month - 1;
			$prev_year 	= $year;
		}
		if ( '12' == $month ){
			$next_month = '1';
			$next_year 	= $year + 1;
		} else {
			$next_month = $month + 1;
			$next_year 	= $year;
		}

		/* draw table */
		$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

		/* table headings */
		$calendar .= '<tr class="calendar-row">
			<td class="calendar-day-head"><a href="?page=wpcrm-settings&wpcrm-cal-month=' . $prev_month . '&wpcrm-cal-year=' . $prev_year . '">' . __( '<< Previous Month', 'wp-crm-system' ) . '</td>
			<td class="calendar-day-head" colspan="5">' . date( "F Y", mktime( 0,0,0,$month,1,$year ) ) . '</td>
			<td class="calendar-day-head"><a href="?page=wpcrm-settings&wpcrm-cal-month=' . $next_month . '&wpcrm-cal-year=' . $next_year . '">' . __( 'Next Month >>', 'wp-crm-system' ) . '</td>
		</tr>';
		$headings = array( __( 'Sunday', 'wp-crm-system' ),__( 'Monday', 'wp-crm-system' ),__( 'Tuesday', 'wp-crm-system' ),__( 'Wednesday', 'wp-crm-system' ),__( 'Thursday', 'wp-crm-system' ),__( 'Friday', 'wp-crm-system' ),__( 'Saturday', 'wp-crm-system' ) );
		$calendar .= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

		/* days and weeks vars now ... */
		$running_day = date('w',mktime(0,0,0,$month,1,$year));
		$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = array();

		/* row for week one */
		$calendar.= '<tr class="calendar-row">';

		/* print "blank" days until the first of the current week */
		for($x = 0; $x < $running_day; $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
			$days_in_this_week++;
		endfor;

		/* keep going with days.... */
		for($list_day = 1; $list_day <= $days_in_month; $list_day++):
			$calendar.= '<td class="calendar-day">';
				/* add in the day number */
				$calendar.= '<div class="day-number">'.$list_day.'</div><ul>';

				/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/

				$args = array(
					'post_type'		=>	$post_types,
					'meta_query'	=>	array(
						array(
							'value'	=>	strtotime( $month . '/' . $list_day . '/' . $year )
						)
					)
				);
				$event_query = new WP_Query( $args );
				if( $event_query->have_posts() ) {
				  while( $event_query->have_posts() ) {
				    $event_query->the_post();
				    $this_type = get_post_type();
				    switch ( $this_type ) {
						case 'wpcrm-campaign':
							$icon = '<span class="dashicons dashicons-megaphone wpcrm-dashicons"></span>';
							break;
						case 'wpcrm-opportunity':
							$icon = '<span class="dashicons dashicons-phone wpcrm-dashicons"></span>';
							break;
						case 'wpcrm-project':
							$icon = '<span class="dashicons dashicons-clipboard wpcrm-dashicons"></span>';
							break;
						case 'wpcrm-task':
							$icon = '<span class="dashicons dashicons-yes wpcrm-dashicons"></span>';
							break;
						default:
							$icon = '';
							break;
				    }
				    $calendar .= '<li>' . $icon . '<a href="' . get_edit_post_link() . '">' . get_the_title() . '</a></li>';
				    // Do your work...
				  } // end while
				} else {
				// end if
					$calendar.= str_repeat('<li>&nbsp;</li>',2);
				}
				wp_reset_postdata();
				
			$calendar.= '</td>';
			if($running_day == 6):
				$calendar.= '</tr>';
				if(($day_counter+1) != $days_in_month):
					$calendar.= '<tr class="calendar-row">';
				endif;
				$running_day = -1;
				$days_in_this_week = 0;
			endif;
			$days_in_this_week++; $running_day++; $day_counter++;
		endfor;

		/* finish the rest of the days in the week */
		if($days_in_this_week < 8):
			for($x = 1; $x <= (8 - $days_in_this_week); $x++):
				$calendar.= '<td class="calendar-day-np"> </td>';
			endfor;
		endif;

		/* final row */
		$calendar.= '</tr>';

		/* end the table */
		$calendar.= '</table>';
		
		/* all done, return result */
		return $calendar;
	}
}