<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WPCRM_System_Create{

	public static function contacts( $fields = array(), $custom_fields = '', $categories = '', $status = 'publish', $author = '', $update = false ){

		$type		= 'wpcrm-contact';
		$tax		= 'contact-type';

		$email_key	= apply_filters( 'wpcrm_system_create_contacts_email_key', false );
		$get_status	= apply_filters( 'wpcrm_system_create_contacts_email_key_status', 'publish' );

		$first		= sanitize_text_field( $fields['first_name'] );
		$last		= sanitize_text_field( $fields['last_name'] );
		$email		= sanitize_email( $fields['email'] );
		$title		= $first . ' ' . $last;

		if ( false == $email_key ){
			$name = get_page_by_title( $title, OBJECT, $type );
		} else {
			$pages = get_posts(
				array(
					'post_type'		=> $type,
					'meta_key'		=> '_wpcrm_contact-email',
					'meta_value'	=> $email,
					'post_status'	=> $get_status
				)
			);
			$name = ( is_array( $pages ) && !empty( $pages ) ) ? $pages[0]: null;
		}

		$categories	= self::format_categories( $categories, $tax );

		$default_fields = array(
			'_wpcrm_contact-name-prefix'			=> self::get_prefix( $fields['prefix'] ),
			'_wpcrm_contact-first-name'				=> sanitize_text_field( $fields['first_name'] ),
			'_wpcrm_contact-last-name'				=> sanitize_text_field( $fields['last_name'] ),
			'_wpcrm_contact-attach-to-organization'	=> self::get_organization( $fields['org'] ),
			'_wpcrm_contact-role'					=> sanitize_text_field( $fields['role'] ),
			'_wpcrm_contact-website'				=> esc_url_raw( $fields['url'] ),
			'_wpcrm_contact-email'					=> sanitize_email( $fields['email'] ),
			'_wpcrm_contact-phone'					=> sanitize_text_field( $fields['phone'] ),
			'_wpcrm_contact-mobile-phone'			=> sanitize_text_field( $fields['mobile'] ),
			'_wpcrm_contact-fax'					=> sanitize_text_field( $fields['fax'] ),
			'_wpcrm_contact-address1'				=> sanitize_text_field( $fields['address_1'] ),
			'_wpcrm_contact-address2'				=> sanitize_text_field( $fields['address_2'] ),
			'_wpcrm_contact-city'					=> sanitize_text_field( $fields['city'] ),
			'_wpcrm_contact-state'					=> sanitize_text_field( $fields['state'] ),
			'_wpcrm_contact-postal'					=> sanitize_text_field( $fields['postal'] ),
			'_wpcrm_contact-country'				=> sanitize_text_field( $fields['country'] ),
			'_wpcrm_contact-additional'				=> wp_kses_post( wpautop( $fields['additional'] ) )
		);

		$custom_fields	= self::get_custom_fields( $type, $custom_fields );

		$fields			= array_merge( $default_fields, $custom_fields );

		if ( null == $name && in_array( $update, array( false, 'create', 'update-create' ) ) ){
			// Contact does not exist and we're supposed to create one.
			$post_id = self::create_new( $title, $status, $type, $author );
			self::post_fields( $post_id, $fields, 'new' );
		}

		if ( is_object( $name ) && in_array( $update, array( true, 'update', 'update-create' ) ) ){
			//Contact exists, and we're supposed to update an existing contact.
			$post_id = $name->ID;
			self::post_fields( $post_id, $fields, 'update' );
		}

		if ( is_array( $categories ) && isset( $post_id ) ){
			wp_set_post_terms( $post_id, $categories, $tax,  true );
		}

		if( isset( $post_id ) ){
			return $post_id;
		}
	}

	public static function organizations( $fields = array(), $custom_fields = '', $categories = '', $status = 'publish', $author = '', $update = false ){

		$type		= 'wpcrm-organization';
		$tax		= 'organization-type';

		$title		= sanitize_text_field( $fields['title'] );

		$name		= get_page_by_title( $title, OBJECT, $type );

		$categories	= self::format_categories( $categories, $tax );

		$default_fields = array(
			'_wpcrm_organization-phone'				=> sanitize_text_field( $fields['phone'] ),
			'_wpcrm_organization-email'				=> sanitize_email( $fields['email'] ),
			'_wpcrm_organization-website'			=> esc_url_raw( $fields['url'] ),
			'_wpcrm_organization-address1'			=> sanitize_text_field( $fields['address_1'] ),
			'_wpcrm_organization-address2'			=> sanitize_text_field( $fields['address_2'] ),
			'_wpcrm_organization-city'				=> sanitize_text_field( $fields['city'] ),
			'_wpcrm_organization-state'				=> sanitize_text_field( $fields['state'] ),
			'_wpcrm_organization-postal'			=> sanitize_text_field( $fields['postal'] ),
			'_wpcrm_organization-country'			=> sanitize_text_field( $fields['country'] ),
			'_wpcrm_organization-information'		=> wp_kses_post( wpautop( $fields['additional'] ) )
		);

		$custom_fields	= self::get_custom_fields( $type, $custom_fields );

		$fields			= array_merge( $default_fields, $custom_fields );

		if ( null == $name && in_array( $update, array( false, 'create', 'update-create' ) ) ){
			// Organization does not exist and we're supposed to create a new organization.
			$post_id = self::create_new( $title, $status, $type, $author );
			self::post_fields( $post_id, $fields, 'new' );
		}

		if ( is_object( $name ) && in_array( $update, array( true, 'update', 'update-create' ) ) ){
			//Organization exists, and we're supposed to update an existing organization
			$post_id = $name->ID;
			self::post_fields( $post_id, $fields, 'update' );
		}

		if ( is_array( $categories ) && isset( $post_id ) ){
			wp_set_post_terms( $post_id, $categories, $tax,  true );
		}

		if( isset( $post_id ) ){
			return $post_id;
		}

	}

	public static function projects( $fields = array(), $custom_fields = '', $categories = '', $status = 'publish', $author = '', $update = false ){

		$type		= 'wpcrm-project';
		$tax		= 'project-type';

		$title		= sanitize_text_field( $fields['title'] );

		$name		= get_page_by_title( $title, OBJECT, $type );

		$categories	= self::format_categories( $categories, $tax );

		$default_fields = array(
			'_wpcrm_project-value'						=> number_format( $fields['value'], 0, '', '' ),
			'_wpcrm_project-closedate'					=> strtotime( $fields['close_date'] ),
			'_wpcrm_project-status'						=> self::get_status( $fields['status'] ),
			'_wpcrm_project-progress'					=> self::get_progress( $fields['progress'] ),
			'_wpcrm_project-attach-to-organization'		=> self::get_organization( $fields['org'] ),
			'_wpcrm_project-attach-to-contact'			=> self::get_contact( $fields['contact'] ),
			'_wpcrm_project-assigned'					=> self::get_assigned( $fields['assigned'] ),
			'_wpcrm_project-description'				=> wp_kses_post( wpautop( $fields['additional'] ) )
		);

		$custom_fields	= self::get_custom_fields( $type, $custom_fields );

		$fields			= array_merge( $default_fields, $custom_fields );

		if ( null == $name && in_array( $update, array( false, 'create', 'update-create' ) ) ){
			// Project does not exist and we're supposed to create a new project so we can create it.
			$post_id = self::create_new( $title, $status, $type, $author );
			self::post_fields( $post_id, $fields, 'new' );
		}

		if ( is_object( $name ) && in_array( $update, array( true, 'update', 'update-create' ) ) ){
			//Project exists, and we're supposed to update an existing project
			$post_id = $name->ID;
			self::post_fields( $post_id, $fields, 'update' );
		}

		if ( is_array( $categories ) && isset( $post_id ) ){
			wp_set_post_terms( $post_id, $categories, $tax,  true );
		}

		if( isset( $post_id ) ){
			return $post_id;
		}

	}

	public static function tasks( $fields = array(), $custom_fields = '', $categories = '', $status = 'publish', $author = '', $update = false ){

		$type		= 'wpcrm-task';
		$tax		= 'task-type';

		$title		= sanitize_text_field( $fields['title'] );

		$name		= get_page_by_title( $title, OBJECT, $type );

		$categories	= self::format_categories( $categories, $tax );

		$default_fields = array(
			'_wpcrm_task-attach-to-organization'	=> self::get_organization( $fields['org'] ),
			'_wpcrm_task-attach-to-contact'			=> self::get_contact( $fields['contact'] ),
			'_wpcrm_task-attach-to-project'			=> self::get_project( $fields['project'] ),
			'_wpcrm_task-assignment'				=> self::get_assigned( $fields['assigned'] ),
			'_wpcrm_task-start-date'				=> strtotime( $fields['start_date'] ),
			'_wpcrm_task-due-date'					=> strtotime( $fields['due_date'] ),
			'_wpcrm_task-progress'					=> self::get_progress( $fields['progress'] ),
			'_wpcrm_task-priority'					=> self::get_priority( $fields['priority'] ),
			'_wpcrm_task-status'					=> self::get_status( $fields['status'] ),
			'_wpcrm_task-description'				=> wp_kses_post( wpautop( $fields['additional'] ) ),
		);

		$custom_fields	= self::get_custom_fields( $type, $custom_fields );

		$fields			= array_merge( $default_fields, $custom_fields );

		if ( null == $name && in_array( $update, array( false, 'create', 'update-create' ) ) ){
			// Task does not exist and we're supposed to create a new task so we can create it.
			$post_id = self::create_new( $title, $status, $type, $author );
			self::post_fields( $post_id, $fields, 'new' );
		}

		if ( is_object( $name ) && in_array( $update, array( true, 'update', 'update-create' ) ) ){
			//Task exists, and we're supposed to update an existing task
			$post_id = $name->ID;
			self::post_fields( $post_id, $fields, 'update' );
		}

		if ( is_array( $categories ) && isset( $post_id ) ){
			wp_set_post_terms( $post_id, $categories, $tax,  true );
		}

		if( isset( $post_id ) ){
			return $post_id;
		}

	}

	public static function opportunities( $fields = array(), $custom_fields = '', $categories = '', $status = 'publish', $author = '', $update = false ){

		$type		= 'wpcrm-opportunity';
		$tax		= 'opportunity-type';

		$title		= sanitize_text_field( $fields['title'] );

		$name		= get_page_by_title( $title, OBJECT, $type );

		$categories	= self::format_categories( $categories, $tax );

		$default_fields = array(
			'_wpcrm_opportunity-attach-to-organization'	=> self::get_organization( $fields['org'] ),
			'_wpcrm_opportunity-attach-to-contact'		=> self::get_contact( $fields['contact'] ),
			'_wpcrm_opportunity-attach-to-campaign'		=> self::get_campaign( $fields['campaign'] ),
			'_wpcrm_opportunity-assigned'				=> self::get_assigned( $fields['assigned'] ),
			'_wpcrm_opportunity-probability'			=> self::get_progress( $fields['probability'] ), //progress and probability are both skip count by 5 between 0-100
			'_wpcrm_opportunity-closedate'				=> strtotime( $fields['close_date'] ),
			'_wpcrm_opportunity-value'					=> number_format( $fields['value'], 0, '', '' ),
			'_wpcrm_opportunity-wonlost'				=> self::get_wonlost( $fields['won_lost'] ),
			'_wpcrm_opportunity-description'			=> wp_kses_post( wpautop( $fields['additional'] ) ),
		);

		$custom_fields	= self::get_custom_fields( $type, $custom_fields );

		$fields			= array_merge( $default_fields, $custom_fields );

		if ( null == $name && in_array( $update, array( false, 'create', 'update-create' ) ) ){
			// Opportunity does not exist and we're supposed to create a new opportunity so we can create it.
			$post_id = self::create_new( $title, $status, $type, $author );
			self::post_fields( $post_id, $fields, 'new' );
		}

		if ( is_object( $name ) && in_array( $update, array( true, 'update', 'update-create' ) ) ){
			//Opportunity exists, and we're supposed to update an existing opportunity
			$post_id = $name->ID;
			self::post_fields( $post_id, $fields, 'update' );
		}

		if ( is_array( $categories ) && isset( $post_id ) ){
			wp_set_post_terms( $post_id, $categories, $tax,  true );
		}

		if( isset( $post_id ) ){
			return $post_id;
		}

	}

	public static function campaigns( $fields = array(), $custom_fields = '', $categories = '', $status = 'publish', $author = '', $update = false ){

		$type		= 'wpcrm-campaign';
		$tax		= 'campaign-type';

		$title		= sanitize_text_field( $fields['title'] );

		$name		= get_page_by_title( $title, OBJECT, $type );

		$categories	= self::format_categories( $categories, $tax );

		$default_fields = array(
			'_wpcrm_campaign-active'					=> self::get_checkbox( $fields['active'] ),
			'_wpcrm_campaign-assigned'					=> self::get_assigned( $fields['assigned'] ),
			'_wpcrm_campaign-status'					=> self::get_status( $fields['status'] ),
			'_wpcrm_campaign-startdate'					=> strtotime( $fields['start_date'] ),
			'_wpcrm_campaign-enddate'					=> strtotime( $fields['end_date'] ),
			'_wpcrm_campaign-projectedreach'			=> number_format( $fields['reach'], 0, '', '' ),
			'_wpcrm_campaign-responses'					=> number_format( $fields['responses'], 0, '', '' ),
			'_wpcrm_campaign-budgetcost'				=> number_format( $fields['budget'], 0, '', '' ),
			'_wpcrm_campaign-actualcost'				=> number_format( $fields['actual'], 0, '', '' ),
			'_wpcrm_campaign-attach-to-organization'	=> self::get_organization( $fields['org'] ),
			'_wpcrm_campaign-attach-to-contact'			=> self::get_contact( $fields['contact'] ),
			'_wpcrm_campaign-description'				=> wp_kses_post( wpautop( $fields['additional'] ) ),
		);

		$custom_fields	= self::get_custom_fields( $type, $custom_fields );

		$fields			= array_merge( $default_fields, $custom_fields );

		if ( null == $name && in_array( $update, array( false, 'create', 'update-create' ) ) ){
			// Campaign does not exist and we're supposed to create a new campaign so we can create it.
			$post_id = self::create_new( $title, $status, $type, $author );
			self::post_fields( $post_id, $fields, 'new' );
		}

		if ( is_object( $name ) && in_array( $update, array( true, 'update', 'update-create' ) ) ){
			//Campaign exists, and we're supposed to update an existing campaign
			$post_id = $name->ID;
			self::post_fields( $post_id, $fields, 'update' );
		}

		if ( is_array( $categories ) && isset( $post_id ) ){
			wp_set_post_terms( $post_id, $categories, $tax,  true );
		}

		if( isset( $post_id ) ){
			return $post_id;
		}

	}

	public static function create_new( $title, $status, $type, $author = '' ){

		$slug		= preg_replace( "/[^A-Za-z0-9]/", '', strtolower( $title ) );
		$post_id	= wp_insert_post(
			array(
				'post_name'		=> wp_strip_all_tags( $slug ),
				'post_title'	=> wp_strip_all_tags( $title ),
				'post_status'	=> $status,
				'post_type'		=> $type,
				'post_author'	=> $author
			)
		);
		return $post_id;
	}

	public static function post_fields( $post_id, $fields, $type = 'new' ){
		switch ( $type ) {
			case 'update':
				foreach ( $fields as $key => $value ) {
					update_post_meta( $post_id, $key, $value );
				}
				if ( 'wpcrm-contact' == get_post_type( $post_id ) ){
					$post_info = array(
						'ID'			=> $post_id,
						'post_title'	=> $fields['_wpcrm_contact-first-name'] . ' ' . $fields['_wpcrm_contact-last-name']
					);
					wp_update_post( $post_info );
				}
				break;

			case 'new':
			default:
				foreach ( $fields as $key => $value ) {
					add_post_meta( $post_id, $key, $value, true );
				}
				break;
		}
	}

	public static function format_categories( $categories, $type ){
		$categories	= explode( ',', $categories );

		$ids		= array();
		foreach ( $categories  as $category ) {
			$id		= get_term_by( 'name', $category, $type );
			if ( $id ){
				$ids[]	= $id->term_id;
			}
		}
		return $ids;
	}

	public static function get_custom_fields( $post_type, $custom_fields ){
		$output = array();
		// Get all custom fields
		if ( defined( 'WPCRM_CUSTOM_FIELDS' ) ){
			$field_count		= get_option( '_wpcrm_system_custom_field_count' );
			$custom_field_ids	= array();
			if ( $field_count ){
				for( $field = 1; $field <= $field_count; $field++ ) {
					$delete = get_option( '_wpcrm_custom_field_delete_' . $field );
					$scope 	= get_option( '_wpcrm_custom_field_scope_' . $field );
					if ( $post_type == $scope && 'yes' != $delete ){
						$custom_field_ids[] = $field;
					}
				}
			}
		}

		if( isset( $custom_field_ids ) ){
			foreach ( $custom_field_ids as $key => $value) {
				$input	= false;
				$type 	= get_option( '_wpcrm_custom_field_type_' . $value );
				$name	= get_option( '_wpcrm_custom_field_name_' . $value );
				if ( isset( $custom_fields[$name] ) ){
					$input 	= $custom_fields[$name];
				}

				if ( $input ){
					$separator = apply_filters( 'wpcrm_system_custom_field_separator', '|' );
					switch ( $type ) {
						case 'repeater-text':
						case 'repeater-file':
							$existing	= get_post_meta( $post_id, '_wpcrm_custom_field_id_' . $value );
							$incoming	= explode( $separator, $input );
							$new		= array();
							if( array() != $existing ){

								$incoming	= array_merge( $incoming, $existing[0] );

							}
							$count	= count( $incoming );
							for( $i = 0; $i < $count; $i++ ){
								if( $incoming[$i] != '' ){
									$new[$i] = sanitize_text_field( $incoming[$i] );
								}
							}
							$safevalue = $new;
							break;
						case 'repeater-textarea':
							$existing	= get_post_meta( $post_id, '_wpcrm_custom_field_id_' . $value );
							$incoming	= explode( $separator, $input );
							$new		= array();
							if( array() != $existing ){

								$incoming	= array_merge( $incoming, $existing[0] );

							}
							$count	= count( $incoming );
							for( $i = 0; $i < $count; $i++ ){
								if( $incoming[$i] != '' ){
									$new[$i] = esc_textarea( $incoming[$i] );
								}
							}
							$safevalue = $new;
							break;
						case 'repeater-date':
							$existing	= get_post_meta( $post_id, '_wpcrm_custom_field_id_' . $value );
							$incoming	= explode( $separator, $input );
							$new		= array();
							if( array() != $existing ){

								$incoming	= array_merge( $incoming, $existing[0] );

							}
							$count	= count( $incoming );
							for( $i = 0; $i < $count; $i++ ){
								if( $incoming[$i] != '' ){
									// If the value is numeric, it already existed as a timestamp.
									if ( is_numeric( $incoming[$i] ) ){
										$new[$i] = $incoming[$i];
									} else {
										$new[$i] = strtotime( $incoming[$i] );
									}
								}
							}
							$safevalue = $new;
							break;
						case 'select':
							$select_options = get_option( '_wpcrm_custom_field_options_' . $value );
							$options 		= explode( ',', $select_options );
							$safevalue		= in_array( $input, $options ) ? $input : '';
							break;
						case 'textarea':
							$safevalue 		= sanitize_textarea_field( $input );
							break;
						case 'wysiwyg':
							$safevalue 		= wp_filter_post_kses( $input );
							break;
						case 'email':
							$email 			= sanitize_email( $input );
							$safevalue 		= is_email( $email ) ? $email : '';
							break;
						case 'url':
							$safevalue 		= esc_url( $input );
							break;
						case 'number':
							$safevalue 		= is_numeric( $input ) ? $input : '';
							break;
						case 'datepicker':
							$safevalue 		= strtotime( $input );
							break;
						default:
							$safevalue 		= sanitize_text_field( $input );
							break;
					}
					$output['_wpcrm_custom_field_id_' . $value]	= $safevalue;
				}
				$input										= false; // reset for next field
			}
		}
		return $output;
	}

	public static function get_checkbox( $check ){
		switch ( $check ) {
			case 'yes':
				$value = 'yes';
				break;

			default:
				$value = 'no';
				break;
		}
		return $value;
	}

	public static function get_prefix( $prefix ){
		$prefix		= sanitize_text_field( $prefix );
		$prefixes	= array(
			'mr'		=>	'mr',
			'mrs'		=>	'mrs',
			'miss'		=>	'miss',
			'ms'		=>	'ms',
			'dr'		=>	'dr',
			'master'	=>	'master',
			'coach'		=>	'coach',
			'rev'		=>	'rev',
			'fr'		=>	'fr',
			'atty'		=>	'atty',
			'prof'		=>	'prof',
			'hon'		=>	'hon',
			'pres'		=>	'pres',
			'gov'		=>	'gov',
			'ofc'		=>	'ofc',
			'supt'		=>	'supt',
			'rep'		=>	'rep',
			'sen'		=>	'sen',
			'amb'		=>	'amb',
		);

		$prefixes	= apply_filters( 'wpcrm_system_create_prefixes', $prefixes );

		if( isset( $prefixes[strtolower( $prefix )] ) ){
			return $prefixes[strtolower( $prefix )];
		} else {
			return '';
		}
	}

	public static function get_status( $status ){
		$status		= strtolower( sanitize_text_field( $status ) );
		$statuses	= array(
			'not-started'	=>	'not-started',
			'not started'	=>	'not-started',
			'in-progress'	=>	'in-progress',
			'in progress'	=>	'in-progress',
			'complete'		=>	'complete',
			'finished'		=>	'complete',
			'on-hold'		=>	'on-hold',
			'on hold'		=>	'on-hold',
		);

		$statuses	= apply_filters( 'wpcrm_system_create_statuses', $statuses );

		if( isset( $statuses[$status] ) ){
			return $statuses[$status];
		} else {
			return 'not-started';
		}
	}

	public static function get_progress( $progress ){
		$progress	= sanitize_text_field( $progress );
		$progresses	= array(
			'zero'	=> 'zero',
			'0'		=> 'zero',
			'0%'	=> 'zero',
			'5'		=> 5,
			'5%'	=> 5,
			'10'	=> 10,
			'10%'	=> 10,
			'15'	=> 15,
			'15%'	=> 15,
			'20'	=> 20,
			'20%'	=> 20,
			'25'	=> 25,
			'25%'	=> 25,
			'30'	=> 30,
			'30%'	=> 30,
			'35'	=> 35,
			'35%'	=> 35,
			'40'	=> 40,
			'40%'	=> 40,
			'45'	=> 45,
			'45%'	=> 45,
			'50'	=> 50,
			'50%'	=> 50,
			'55'	=> 55,
			'55%'	=> 55,
			'60'	=> 60,
			'60%'	=> 60,
			'65'	=> 65,
			'65%'	=> 65,
			'70'	=> 70,
			'70%'	=> 70,
			'75'	=> 75,
			'75%'	=> 75,
			'80'	=> 80,
			'80%'	=> 80,
			'85'	=> 85,
			'85%'	=> 85,
			'90'	=> 90,
			'90%'	=> 90,
			'95'	=> 95,
			'95%'	=> 95,
			'100'	=> 100,
			'100%'	=> 100,
		);

		$progresses	= apply_filters( 'wpcrm_system_create_progresses', $progresses );

		if( isset( $progresses[strtolower( $progress )] ) ){
			return $progresses[strtolower( $progress )];
		} else {
			return 'zero';
		}
	}

	public static function get_wonlost( $wonlost ){
		$wonlost	= sanitize_text_field( $wonlost );
		$statuses	= array(
			'not-set'	=>	'not-set',
			'not set'	=>	'not-set',
			'won'		=>	'won',
			'lost'		=>	'lost',
			'suspended'	=>	'suspended',
			'abandoned'	=>	'abandoned',
		);

		$statuses	= apply_filters( 'wpcrm_system_create_wonlost', $statuses );

		if( isset( $statuses[strtolower( $wonlost )] ) ){
			return $statuses[strtolower( $wonlost )];
		} else {
			return 'not-set';
		}
	}

	public static function get_priority( $priority ){
		$priority	= sanitize_text_field( $priority );
		$priorities	= array(
			'low'		=>	'low',
			'medium'	=>	'medium',
			'high'		=>	'high',
		);

		$priorities	= apply_filters( 'wpcrm_system_create_priorities', $priorities );

		if( isset( $priorities[strtolower( $priority )] ) ){
			return $priorities[strtolower( $priority )];
		} else {
			return '';
		}
	}

	public static function get_assigned( $assigned ){
		$assigned	=  sanitize_text_field( $assigned );

		if ( is_email( $assigned ) ){

			$user = get_user_by( 'email', $assigned );

			if ( ! $user ){
				return '';
			} else {
				return $user->user_login;
			}
		} else {
			global $wpdb;
			$user = $wpdb->get_row( $wpdb->prepare( "
				SELECT *
				FROM $wpdb->users
				WHERE `display_name` = %s", $assigned
			) );

			if ( ! $user ){
				return '';
			} else {
				return $user->user_login;
			}
		}

	}

	public static function get_contact( $title ){
		if ( ! $title || '' == $title ){
			return;
		}
		$get_contact	= get_page_by_title( sanitize_text_field( $title ), OBJECT, 'wpcrm-contact' );

		if ( is_object( $get_contact ) ){
			return $get_contact->ID;
		} else {
			//Contact doesn't exist.
			$id = self::create_new( $title, 'publish', 'wpcrm-contact' );
			//Need to add the contact's name
			add_post_meta( $id, '_wpcrm_contact-first-name', $contact, true );
			return $id;
		}
	}

	public static function get_organization( $title ){
		$get_org	= get_page_by_title( sanitize_text_field( $title ), OBJECT, 'wpcrm-organization' );

		if ( is_object( $get_org ) ){
			return $get_org->ID;
		} else {
			//Organization doesn't exist.
			$id = self::create_new( $title, 'publish', 'wpcrm-organization' );
			return $id;
		}
	}

	public static function get_campaign( $title ){
		$get_campaign	= get_page_by_title( sanitize_text_field( $title ), OBJECT, 'wpcrm-campaign' );

		if ( is_object( $get_campaign ) ){
			return $get_campaign->ID;
		} else {
			//Campaign doesn't exist.
			$id = self::create_new( $title, 'publish', 'wpcrm-campaign' );
			return $id;
		}

	}

	public static function get_project( $title ){
		$get_project	= get_page_by_title( sanitize_text_field( $title ), OBJECT, 'wpcrm-project' );

		if ( $get_project ){
			return $get_project->ID;
		} else {
			//Project doesn't exist.
			$id = self::create_new( $title, 'publish', 'wpcrm-project' );
			return $id;
		}

	}

}