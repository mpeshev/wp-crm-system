<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}

add_action( 'admin_init', 'wpcrm_system_fields');
function wpcrm_system_fields() {
	$fields = array();
	if( has_filter( 'wpcrm_system_fields' ) ) {
		$defaultFields = apply_filters( 'wpcrm_system_fields', $fields );
	}
	return $defaultFields;
}

add_action( 'admin_menu', 'createWPCRMSystemFields' );
/**
* Create the new meta boxes
*/
function createWPCRMSystemFields() {
	$postTypes		= array( 'wpcrm-contact', 'wpcrm-task', 'wpcrm-organization', 'wpcrm-opportunity', 'wpcrm-project', 'wpcrm-campaign' );
	$gmapTypes		= array( 'wpcrm-contact', 'wpcrm-organization' );
	// $recurringTypes	= array( 'wpcrm-project', 'wpcrm-task' );

	if ( function_exists( 'add_meta_box' ) ) {
		foreach ( $postTypes as $postType ) {
			add_meta_box( 'wpcrm-default-fields', __( 'Fields', 'wp-crm-system' ), 'wpcrmDefaultFields', $postType, 'normal', 'high' );
		}

		if ( '' != get_option( 'wpcrm_system_gmap_api' ) ) {
			foreach ( $gmapTypes as $gmapType ) {
				add_meta_box( 'wpcrm-gmap', __( 'Map', 'wp-crm-system' ), 'wpcrmGmap', $gmapType, 'side', 'low' );
			}
			add_action( 'admin_enqueue_scripts', 'wcs_gmap_load_js' );
		}
		add_meta_box( 'wpcrm-opportunity-options', __( 'WP-CRM System Options', 'wp-crm-system' ), 'wpcrmOpportunityOptions', 'wpcrm-opportunity', 'side', 'low' );
		add_meta_box( 'wpcrm-project-tasks', __( 'Tasks', 'wp-crm-system' ), 'wpcrmListTasksinProjects', 'wpcrm-project', 'side', 'low' );
		add_meta_box( 'wpcrm-organization-projects', __( 'Projects', 'wp-crm-system' ), 'wpcrmListProjectsinOrganizations', 'wpcrm-organization', 'side', 'low' );
		add_meta_box( 'wpcrm-organization-tasks', __( 'Tasks', 'wp-crm-system' ), 'wpcrmListTasksinOrganizations', 'wpcrm-organization', 'side', 'low' );
		add_meta_box( 'wpcrm-organization-opportunities', __( 'Opportunities', 'wp-crm-system' ), 'wpcrmListOpportunitiesinOrganizations', 'wpcrm-organization', 'side', 'low' );
		add_meta_box( 'wpcrm-organization-contacts', __( 'Contacts', 'wp-crm-system' ), 'wpcrmListContactsinOrg', 'wpcrm-organization', 'side', 'low' );
		add_meta_box( 'wpcrm-contacts-opportunities', __( 'Opportunities', 'wp-crm-system' ), 'wpcrmListOpportunitiesinContact', 'wpcrm-contact', 'side', 'low' );
		add_meta_box( 'wpcrm-contacts-projects', __( 'Projects', 'wp-crm-system' ), 'wpcrmListProjectsinContact', 'wpcrm-contact', 'side', 'low' );
		add_meta_box( 'wpcrm-contacts-tasks', __( 'Tasks', 'wp-crm-system' ), 'wpcrmListTasksinContact', 'wpcrm-contact', 'side', 'low' );
		if ( get_option( 'wpcrm_system_gdpr_page_id' ) ){
			add_meta_box( 'wpcrm-contacts-gdpr', __( 'GDPR', 'wp-crm-system' ), 'wpcrm_system_GDPR_metabox', 'wpcrm-contact', 'side', 'high' );
		}
		add_meta_box( 'wpcrm_custom_meta', __( 'Contact Emails', 'wp-crm-system' ), 'wpcrm_system_display_email',  'wpcrm-contact', 'normal', 'low' );
		// Add support for custom meta boxes
		do_action( 'wpcrm_system_custom_meta_boxes' );
	}
}
add_action( 'save_post', 'saveWPCRMSystemFields', 1, 2 );
/**
* Save the new Custom Fields values
*/
function saveWPCRMSystemFields( $post_id, $post ) {
	$postTypes = array( 'wpcrm-contact', 'wpcrm-task', 'wpcrm-organization', 'wpcrm-opportunity', 'wpcrm-project', 'wpcrm-campaign' );
	$defaultFields = wpcrm_system_fields();
	if ( !isset( $_POST[ 'wpcrm-fields_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'wpcrm-fields_wpnonce' ], 'wpcrm-fields' ) )
		return;
	if ( !current_user_can( 'edit_post', $post_id ) )
		return;
	if ( ! in_array( $post->post_type, $postTypes ) )
		return;

	foreach ( $defaultFields as $defaultField ) {
		if ( current_user_can( $defaultField['capability'], $post_id ) ) {
			if ( isset( $_POST[ '_wpcrm_' . $defaultField['name'] ] ) && trim( $_POST[ '_wpcrm_' . $defaultField['name'] ] ) != '' ) {
				//Get field's value
				$value = $_POST[ '_wpcrm_' . $defaultField['name'] ];
				$safevalue = '';
				$contactTitle = array();
				/** Validate and sanitize input **/
				switch ( $defaultField[ 'type' ] ) {
					case 'selectcontact': {
						$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-contact'));
						$posts = array('do not show');
						foreach ($allowed as $post) {
							$posts[] = $post->ID;
						}
						if ($posts) {
							if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
						}
						break;
					}
					case 'selectproject': {
						$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-project'));
						$posts = array();
						foreach ($allowed as $post) {
							$posts[] = $post->ID;
						}
						if ($posts) {
							if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
						}
						break;
					}
					case 'selectorganization': {
						$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-organization'));
						$posts = array('do not show');
						foreach ($allowed as $post) {
							$posts[] = $post->ID;
						}
						if ($posts) {
							if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
						}
						break;
					}
					case 'selectcampaign': {
						$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-campaign'));
						$posts = array('do not show');
						foreach ($allowed as $post) {
							$posts[] = $post->ID;
						}
						if ($posts) {
							if (in_array($value,$posts)){$safevalue = $value;}else{$safevalue = '';}
						}
						break;
					}
					case 'selectprogress': {
						$allowed = array('zero',5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100);
						if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = 'zero';}
						break;
					}
					case 'selectwonlost': {
						$allowed = array('not-set','won','lost','suspended','abandoned');
						if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = 'not-set';}
						break;
					}
					case 'selectpriority': {
						$allowed = array('','low','medium','high');
						if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = '';}
						break;
					}
					case 'selectstatus': {
						$allowed = array('not-started','in-progress','complete','on-hold');
						if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = 'not-started';}
						break;
					}
					case 'selectnameprefix': {
						$allowed = array('','mr','mrs','miss','ms','dr','master','coach','rev','fr','atty','prof','hon','pres','gov','ofc','supt','rep','sen','amb');
						if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = '';}
						break;
					}
					case 'selectuser': {
						$users = get_users();
						$wp_crm_users = array();
						foreach( $users as $user ){
							if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
								$wp_crm_users[] = $user->data->user_login;
							}
						}
						if(in_array($value,$wp_crm_users)){$safevalue = $value;}else{$safevalue = '';}
						break;
					}
					case 'dropbox': {
						// Save data
						$safevalue = $value;
						break;
					}
					case 'gmap': {
						// Google maps needs no input to be saved.
						$safevalue = '';
						break;
					}
					case 'datepicker': {
						// Datepicker fields should be strtotime()
						$safevalue = strtotime($value);
						break;
					}
					case 'currency':
					case 'number': {
						// Save currency only with numbers.
						$safevalue = preg_replace("/[^0-9]/", "", $value);
						break;
					}
					case 'wysiwyg': {
						// Auto-paragraphs for any WYSIWYG. Sanitize content for allowed HTML
						$safevalue = wp_kses_post( wpautop( $value ) );
						break;
					}
					case 'textarea': {
						//Sanitize content for allowed textarea content.
						$safevalue = esc_textarea( $value );
						break;
					}
					case 'url': {
						//Sanitize URLs
						$safevalue = esc_url_raw( $value );
						break;
					}
					case 'email': {
						// Sanitize email field and make sure value is actually an email
						$email = sanitize_email( $value );
						if ( is_email($email)) {$safevalue = $email;} else {$safevalue = '';}
						break;
					}
					case 'checkbox': {
						//Option will either be yes or blank
						$allowed = array('','yes');
						if(in_array($value,$allowed)){$safevalue = $value;}else{$safevalue = '';}
						break;
					}
					case 'addcontact': {
						$new_title = sanitize_text_field( $_POST[ '_wpcrm_' . $defaultField['name'] ] );
						$new_slug = preg_replace("/[^A-Za-z0-9]/",'',strtolower($new_title));
						global $post;
						$currentid = $post->ID;
						if( null == get_page_by_title( $new_title, OBJECT, 'wpcrm-contact' ) ) {
							global $wpdb;
							$author_id = get_current_user_id();
							$wpdb->insert($wpdb->posts, array(
								'post_content'  => '',
								'post_title'    => $new_title,
								'post_status'   => 'publish',
								'post_date'     => date('Y-m-d H:i:s'),
								'post_date_gmt' => gmdate('Y-m-d H:i:s'),
								'post_author'   => $author_id,
								'post_name'     => $new_slug,
								'post_type'     => 'wpcrm-contact'
							));
							$safevalue = $wpdb->insert_id;
							update_post_meta( $currentid, substr( '_wpcrm_' . $defaultField[ 'name' ], 0, -4), $safevalue );
							$wpdb->flush();
						}
						break;
					}
					case 'addorganization': {
						$new_org_title = sanitize_text_field( $_POST[ '_wpcrm_' . $defaultField['name'] ] );
						$new_org_slug = preg_replace("/[^A-Za-z0-9]/",'',strtolower($new_org_title));
						global $post;
						$currentid = $post->ID;
						if( null == get_page_by_title( $new_org_title, OBJECT, 'wpcrm-organization' ) ) {
							global $wpdb;
							$author_id = get_current_user_id();
							$wpdb->insert($wpdb->posts, array(
								'post_content'  => '',
								'post_title'    => $new_org_title,
								'post_status'   => 'publish',
								'post_date'     => date('Y-m-d H:i:s'),
								'post_date_gmt' => gmdate('Y-m-d H:i:s'),
								'post_author'   => $author_id,
								'post_name'     => $new_org_slug,
								'post_type'     => 'wpcrm-organization'
							));
							$safevalue = $wpdb->insert_id;
							update_post_meta( $currentid, substr( '_wpcrm_' . $defaultField[ 'name' ], 0, -4), $safevalue );
							$wpdb->flush();
						}
						break;
					}
					case 'addproject': {
						$new_title = sanitize_text_field( $_POST[ '_wpcrm_' . $defaultField['name'] ] );
						$new_slug = preg_replace("/[^A-Za-z0-9]/",'',strtolower($new_title));
						global $post;
						$currentid = $post->ID;
						if( null == get_page_by_title( $new_title, OBJECT, 'wpcrm-project' ) ) {
							global $wpdb;
							$author_id = get_current_user_id();
							$wpdb->insert($wpdb->posts, array(
								'post_content'  => '',
								'post_title'    => $new_title,
								'post_status'   => 'publish',
								'post_date'     => date('Y-m-d H:i:s'),
								'post_date_gmt' => gmdate('Y-m-d H:i:s'),
								'post_author'   => $author_id,
								'post_name'     => $new_slug,
								'post_type'     => 'wpcrm-project'
							));
							$safevalue = $wpdb->insert_id;
							update_post_meta( $currentid, substr( '_wpcrm_' . $defaultField[ 'name' ], 0, -4), $safevalue );
							$wpdb->flush();
						}
						break;
					}
					case 'addcampaign': {
						$new_title = sanitize_text_field( $_POST[ '_wpcrm_' . $defaultField['name'] ] );
						$new_slug = preg_replace("/[^A-Za-z0-9]/",'',strtolower($new_title));
						global $post;
						$currentid = $post->ID;
						if( null == get_page_by_title( $new_title, OBJECT, 'wpcrm-campaign' ) ) {
							global $wpdb;
							$author_id = get_current_user_id();
							$wpdb->insert($wpdb->posts, array(
								'post_content'  => '',
								'post_title'    => $new_title,
								'post_status'   => 'publish',
								'post_date'     => date('Y-m-d H:i:s'),
								'post_date_gmt' => gmdate('Y-m-d H:i:s'),
								'post_author'   => $author_id,
								'post_name'     => $new_slug,
								'post_type'     => 'wpcrm-campaign'
							));
							$safevalue = $wpdb->insert_id;
							update_post_meta( $currentid, substr( '_wpcrm_' . $defaultField[ 'name' ], 0, -4), $safevalue );
							$wpdb->flush();
						}
						break;
					}
					default: {
						// Sanitize text field
						$safevalue = sanitize_text_field( $value );
						if ( 'contact-first-name' == $defaultField['name'] ) {
							$contactFirst = $safevalue;
						}
						if ( 'contact-last-name' == $defaultField['name'] ) {
							$contactLast = $safevalue;
						}
						if ( ! empty( $contactFirst ) && ! empty( $contactLast ) ) {
							$contactTitle = $contactFirst . ' ' . $contactLast;
						}
						break;
					}
				}
				update_post_meta( $post_id, '_wpcrm_' . $defaultField[ 'name' ], $safevalue );
			} else {
				delete_post_meta( $post_id, '_wpcrm_' . $defaultField[ 'name' ] );
			}
		}
	}
}
add_action( 'save_post', 'saveContactTitle', 2, 1 );
/**
* Save the contact's title
*/
function saveContactTitle( $post_id ) {
	global $post;
	//Suggested $post_id != $post->ID to provide compatibility with 3rd party plugins.
	if ( empty( $post ) || $post_id != $post->ID ) {
		$post = get_post($post_id);
	}

	if ( $post_id == null || empty($_POST) ){
		return;
	}

	if ( !isset( $_POST['post_type'] ) || 'wpcrm-contact' != $_POST['post_type'] || 'wpcrm-contact' != $post->post_type ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		$post_id = wp_is_post_revision( $post_id );
	}

	if ( isset( $_POST['_wpcrm_' . 'contact-first-name'] ) && $_POST['_wpcrm_' . 'contact-first-name'] != '' && isset( $_POST['_wpcrm_' . 'contact-last-name'] ) && $_POST['_wpcrm_' . 'contact-last-name'] != '' ) {
		global $wpdb;
		$first = $_POST['_wpcrm_' . 'contact-first-name'];
		$last = $_POST['_wpcrm_' . 'contact-last-name'];
		$title = $first . ' ' . $last;
		$where = array( 'ID' => $post_id );
		$wpdb->update( $wpdb->posts, array( 'post_title' => $title ), $where );
	}
}

add_action( 'do_meta_boxes', 'removeDefaultFields', 10, 3 );
/**
* Remove the default Fields meta box
*/
function removeDefaultFields( $type, $context, $post ) {
	$postTypes = array( 'wpcrm-contact', 'wpcrm-task', 'wpcrm-organization', 'wpcrm-opportunity', 'wpcrm-project', 'wpcrm-campaign' );
	foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
		foreach ( $postTypes as $postType ) {
			remove_meta_box( 'postcustom', $postType, $context );
		}
	}
}
/**
* Display the Google Maps meta box
*/
function wpcrm_system_geocode($address){

	// url encode the address
	$address = urlencode($address);
	$key = get_option( 'wpcrm_system_gmap_api' );

	// google map geocode api url
	$url = "http://maps.google.com/maps/api/geocode/json?address={$address}";

	// get the json response
	$resp_json = file_get_contents($url);

	// decode the json
	$resp = json_decode($resp_json, true);

	// response status will be 'OK', if able to geocode given address
	if($resp['status']=='OK'){

		// get the important data
		$lati = $resp['results'][0]['geometry']['location']['lat'];
		$longi = $resp['results'][0]['geometry']['location']['lng'];
		$formatted_address = $resp['results'][0]['formatted_address'];

		// verify if data is complete
		if($lati && $longi && $formatted_address){
		// put the data in the array
		$data_arr = array();
		array_push(
			$data_arr,
			$lati,
			$longi,
			$formatted_address
		);
		return $data_arr;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
function wcs_gmap_load_js() {
	global $post;
	$active_page = isset( $_GET[ 'action' ] ) ? $_GET[ 'action' ] : '';
	if( !empty( $post ) ) {
		if (( 'wpcrm-contact' == $post->post_type ) || ( 'wpcrm-organization' == $post->post_type ) ){
			if ( 'edit' == $active_page ) {
				$key = get_option( 'wpcrm_system_gmap_api' );
				wp_enqueue_script( 'wcs_gmap_api_js', '//maps.google.com/maps/api/js?key=' . $key, null, 1.0, true );
				$screen = get_current_screen();
				if (get_post_type() == 'wpcrm-contact'){
					$addressString = get_post_meta( $post->ID, '_wpcrm_contact-address1', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_contact-address2', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_contact-city', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_contact-state', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_contact-postal', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_contact-country', true );
				}
				if (get_post_type() == 'wpcrm-organization'){
					$addressString = get_post_meta( $post->ID, '_wpcrm_organization-address1', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_organization-address2', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_organization-city', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_organization-state', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_organization-postal', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_organization-country', true );
				}
				$data_arr = false;
				if ( $addressString ){
					// get latitude, longitude and formatted address
					$data_arr = wpcrm_system_geocode( $addressString );
				}

				// if able to geocode the address
				if( $data_arr ){
					$latitude = $data_arr[0];
					$longitude = $data_arr[1];
					$formatted_address = $data_arr[2];
					wp_enqueue_script( 'wcs_gmap_js_handle', WP_CRM_SYSTEM_URL . '/js/show-gmap.js', null, 1.0, true );
					wp_localize_script( 'wcs_gmap_js_handle', 'wcs_gmap_vars',
						array(
							'latitude'  => $latitude,
							'longitude' => $longitude,
							'address'   => $formatted_address
						)
					);
				}
			}
		}
	}
}

function wpcrmGmap() {
	$key = get_option( 'wpcrm_system_gmap_api' );
	if ( '' == $key ){
		return false;
	}
	global $post;
	echo '<div class="form-field form-required">';

	if (get_post_type() == 'wpcrm-contact'){
		$addressString = get_post_meta( $post->ID, '_wpcrm_contact-address1', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_contact-address2', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_contact-city', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_contact-state', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_contact-postal', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_contact-country', true );
	}
	if (get_post_type() == 'wpcrm-organization'){
		$addressString = get_post_meta( $post->ID, '_wpcrm_organization-address1', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_organization-address2', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_organization-city', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_organization-state', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_organization-postal', true ) . ' ' . get_post_meta( $post->ID, '_wpcrm_organization-country', true );
	}
	$data_arr = false;
	if ( $addressString ){
		// get latitude, longitude and formatted address
		$data_arr = wpcrm_system_geocode( $addressString );
	}

	// if able to geocode the address
	if( $data_arr != false ){
		?>

		<!-- google map will be shown here -->
		<div id="gmap_canvas"><?php _e('Loading map...','wp-crm-system'); ?></div>
		<div id='map-label'><?php _e('Map shows approximate location.','wp-crm-system'); ?></div>

		<?php
	} else {
		_e('No Map Found. Please enter an address or verify the address details are correct.','wp-crm-system');
	} ?>
</div>
<?php
}
/**
* Display the additional options meta box
*/
function wpcrmOpportunityOptions() {
	global $post;
	$screen = get_current_screen();
	$author_id = get_current_user_id();
	$title = get_the_title();
	$slug = preg_replace("/[^A-Za-z0-9]/",'',strtolower($title));

	$projectFromOpportunity = "//" . sanitize_text_field( $_SERVER['HTTP_HOST'] ) . sanitize_text_field( $_SERVER['REQUEST_URI'] ) . '&wpcrm-system-action=new-project-from-opportunity';
	echo __( 'Save changes before clicking below.', 'wp-crm-system' );
	echo '<ul>';
	echo '<li><a class="button" href="' . esc_url( $projectFromOpportunity ) . '">' . __( 'Create Project From Opportunity', 'wp-crm-system' ) . '</a></li>';
	echo '</ul>';

	if ( isset( $_GET['wpcrm-system-action'] ) ) {
		$action = $_GET['wpcrm-system-action'];
		/* Do wpcrm-system-action */
		if ( $action == 'new-project-from-opportunity' ) {
			if( null == get_page_by_title( $title, OBJECT, 'wpcrm-project' ) ) {
				$org = get_post_meta( $post->ID, '_wpcrm_' . 'opportunity-attach-to-organization', true );
				$contact = get_post_meta( $post->ID, '_wpcrm_' . 'opportunity-attach-to-contact', true );
				$assigned = get_post_meta( $post->ID, '_wpcrm_'. 'opportunity-assigned', true );
				$close = get_post_meta( $post->ID, '_wpcrm_' . 'opportunity-closedate', true );
				$value = get_post_meta( $post->ID, '_wpcrm_' . 'opportunity-value', true );
				$description = get_post_meta( $post->ID, '_wpcrm_' . 'opportunity-description', true );
				$post_id = wp_insert_post(
				array(
					'comment_status'  =>  'closed',
					'ping_status'   =>  'closed',
					'post_author'   =>  $author_id,
					'post_name'     =>  $slug,
					'post_title'    =>  $title,
					'post_status'   =>  'publish',
					'post_type'     =>  'wpcrm-project'
				)
			);
			add_post_meta($post_id, '_wpcrm_' .'project-attach-to-organization',$org,true);
			add_post_meta($post_id, '_wpcrm_' .'project-attach-to-contact',$contact,true);
			add_post_meta($post_id, '_wpcrm_' .'project-assigned',$contact,true);
			if($close != '') {
				add_post_meta($post_id, '_wpcrm_' .'project-closedate',$close,true);
			}
			add_post_meta($post_id, '_wpcrm_' .'project-value',$value,true);
			add_post_meta($post_id, '_wpcrm_' .'project-description',$description,true);
			echo '<div class="updated"><p>'.__('New Project Added!','wp-crm-system-zendesk').'</p></div>';
			} else {
				echo '<div class="error"><p>'.__('Project not added. A project with this name already exists: ','wp-crm-system-zendesk').$title.'</p></div>';
			}
		}
	}
}
function wpcrmListTasksinProjects() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php' );
	//List Tasks
	$meta_key = '_wpcrm_task-attach-to-project';
	$meta_value = get_the_ID();
	$tasks = get_posts( array(
		'posts_per_page'  => -1,
		'post_type'     => 'wpcrm-task',
		'meta_query'    => array(
			array(
				'key'   => $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$task_report = '';
	if ($tasks == '') {
		$task_report = '';
	} else {
		foreach( $tasks as $task ) {
			$task_report .= '<li><a href="' . get_edit_post_link($task) . '">' . get_the_title($task) . '</a></li>';
		}
	}
	if ($task_report != '') {
		echo '<ul>' . $task_report . '</ul>';
	} else {
		_e('No tasks assigned to this project.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListProjectsinContact() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php');
	//List Projects
	$meta_key = '_wpcrm_project-attach-to-contact';
	$meta_value = get_the_ID();
	$projects = get_posts( array(
		'posts_per_page'  => -1,
		'post_type'     => 'wpcrm-project',
		'meta_query'    => array(
			array(
				'key'   => $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$project_report = '';
	if ($projects == '') {
		$project_report = '';
	} else {
		foreach( $projects as $project ) {
			$project_report .= '<li><a href="' . get_edit_post_link($project) . '">' . get_the_title($project) . '</a></li>';
		}
	}
	if ($project_report != '') {
		echo '<ul>' . $project_report . '</ul>';
	} else {
		_e('No projects assigned to this contact.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListTasksinContact() {
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php');
	//List Tasks
	$meta_key = '_wpcrm_task-attach-to-contact';
	$meta_value = get_the_ID();
	$tasks = get_posts( array(
		'posts_per_page'  => -1,
		'post_type'     => 'wpcrm-task',
		'meta_query'    => array(
			array(
				'key'   => $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$task_report = '';
	if ($tasks == '') {
		$task_report = '';
	} else {
		foreach( $tasks as $task ) {
			$task_report .= '<li><a href="' . get_edit_post_link($task) . '">' . get_the_title($task) . '</a></li>';
		}
	}
	if ($task_report != '') {
		echo '<ul>' . $task_report . '</ul>';
	} else {
		_e('No tasks assigned to this contact.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrm_system_GDPR_metabox(){
	global $post;
	$secret		= get_post_meta( $post->ID, '_wpcrm_system_gdpr_secret', true );
	$show_url	= true;
	if ( $secret == ( '' || false ) ){
		$show_url = false;
		$secret = wpcrm_system_random_string();
	}
	wp_nonce_field( 'wpcrm-system-gdpr-secret', 'wpcrm_system_gdpr_secret_wpnonce', false, true );
	?>
	<label for="_wpcrm_system_gdpr_secret"><?php _e( 'Secret key for GDPR link', 'wp-crm-system' ); ?></label>
	<input type="text" id="_wpcrm_system_gdpr_secret" name="_wpcrm_system_gdpr_secret" value="<?php echo $secret; ?>" />
	<?php
	if ( $show_url ){
		echo wpcrm_system_gdpr_page( $post->ID, $secret );?>
	<?php
	} else {
		echo wpcrm_system_gdpr_page( $post->ID, '' );
	}
}

add_action( 'save_post', 'wpcrm_system_save_GDPR_secret' );
function wpcrm_system_save_GDPR_secret( $post_id ){
	if ( !isset( $_POST['wpcrm_system_gdpr_secret_wpnonce'] ) ){
		return $post_id;
	}
	if ( !wp_verify_nonce( $_POST['wpcrm_system_gdpr_secret_wpnonce'] , 'wpcrm-system-gdpr-secret' ) ){
		return $post_id;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return $post_id;
	}
	if ( 'wpcrm-contact' == $_POST['post_type'] ){
		if( isset( $_POST[ '_wpcrm_system_gdpr_secret' ] ) && trim( $_POST['_wpcrm_system_gdpr_secret'] != '' ) ){
			$value		= $_POST[ '_wpcrm_system_gdpr_secret' ];
			$safevalue	= sanitize_text_field( $value );
			update_post_meta( $post_id, '_wpcrm_system_gdpr_secret', $safevalue );
		} else {
			delete_post_meta( $post_id, '_wpcrm_system_gdpr_secret' );
		}
	}
}

function wpcrmListOpportunitiesinContact() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php');
	//List Opportunities
	$meta_key = '_wpcrm_opportunity-attach-to-contact';
	$meta_value = get_the_ID();
	$opportunities = get_posts( array(
		'posts_per_page'  => -1,
		'post_type'     => 'wpcrm-opportunity',
		'meta_query'    => array(
			array(
				'key'   => $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$opportunity_report = '';
	if ($opportunities == '') {
		$opportunity_report = '';
	} else {
		foreach( $opportunities as $opportunity ) {
			$opportunity_report .= '<li><a href="' . get_edit_post_link($opportunity) . '">' . get_the_title($opportunity) . '</a></li>';
		}
	}
	if ($opportunity_report != '') {
		echo '<ul>' . $opportunity_report . '</ul>';
	} else {
		_e('No opportunities assigned to this contact.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListProjectsinOrganizations() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php');
	//List Projects
	$meta_key = '_wpcrm_project-attach-to-organization';
	$meta_value = get_the_ID();
	$projects = get_posts( array(
		'posts_per_page'  => -1,
		'post_type'     => 'wpcrm-project',
		'meta_query'    => array(
			array(
				'key'   => $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$project_report = '';
	if ($projects == '') {
		$project_report = '';
	} else {
		foreach( $projects as $project ) {
			$project_report .= '<li><a href="' . get_edit_post_link($project) . '">' . get_the_title($project) . '</a></li>';
		}
	}
	if ($project_report != '') {
		echo '<ul>' . $project_report . '</ul>';
	} else {
		_e('No projects assigned to this organization.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListTasksinOrganizations() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php');
	//List Tasks
	$meta_key = '_wpcrm_task-attach-to-organization';
	$meta_value = get_the_ID();
	$tasks = get_posts( array(
		'posts_per_page'  => -1,
		'post_type'     => 'wpcrm-task',
		'meta_query'    => array(
			array(
				'key'   => $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$task_report = '';
	if ($tasks == '') {
		$task_report = '';
	} else {
		foreach( $tasks as $task ) {
			$task_report .= '<li><a href="' . get_edit_post_link($task) . '">' . get_the_title($task) . '</a></li>';
		}
	}
	if ($task_report != '') {
		echo '<ul>' . $task_report . '</ul>';
	} else {
		_e('No tasks assigned to this organization.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListOpportunitiesinOrganizations() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php');
	//List Opportunities
	$meta_key = '_wpcrm_opportunity-attach-to-organization';
	$meta_value = get_the_ID();
	$opportunitys = get_posts( array(
		'posts_per_page'  => -1,
		'post_type'     => 'wpcrm-opportunity',
		'meta_query'    => array(
			array(
				'key'   => $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$opportunity_report = '';
	if ($opportunitys == '') {
		$opportunity_report = '';
	} else {
		foreach( $opportunitys as $opportunity ) {
			$opportunity_report .= '<li><a href="' . get_edit_post_link($opportunity) . '">' . get_the_title($opportunity) . '</a></li>';
		}
	}
	if ($opportunity_report != '') {
		echo '<ul>' . $opportunity_report . '</ul>';
	} else {
		_e('No opportunities assigned to this organization.','wp-crm-system');
	}
	wp_reset_postdata();
}
function wpcrmListContactsinOrg() {
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php');
	//List Contacts
	$meta_key = '_wpcrm_contact-attach-to-organization';
	$meta_value = get_the_ID();
	$contacts = get_posts( array(
		'posts_per_page'  => -1,
		'post_type'     => 'wpcrm-contact',
		'meta_query'    => array(
			array(
				'key'   => $meta_key,
				'value' => $meta_value,
			)
		)
	));
	$contact_report = '';
	if ($contacts == '') {
		$contact_report = '';
	} else {
		foreach( $contacts as $contact ) {
			$contact_report .= '<li><a href="' . get_edit_post_link($contact) . '">' . get_the_title($contact) . '</a></li>';
		}
	}
	if ($contact_report != '') {
		echo '<ul>' . $contact_report . '</ul>';
	} else {
		_e('No contacts assigned to this organization.','wp-crm-system');
	}
	wp_reset_postdata();
}
/**
* Display the main fields meta box
*/
function wpcrmDefaultFields() {
global $post;
$defaultFields = wpcrm_system_fields();
?>
<div class="form-wrap">
	<?php
	wp_nonce_field( 'wpcrm-fields', 'wpcrm-fields_wpnonce', false, true );
	foreach ( $defaultFields as $defaultField ) {
		// Check scope
		$scope = $defaultField[ 'scope' ];
		$output = false;
		foreach ( $scope as $scopeItem ) {
			switch ( $scopeItem ) {
				default: {
					if ( $post->post_type == $scopeItem )
					$output = true;
					break;
				}
			}
			if ( $output ) break;
		}
		// Check capability
		if ( !current_user_can( $defaultField['capability'], $post->ID ) )
		$output = false;
		// Output if allowed
		if ( $output ) { ?>
			<?php
			switch ( $defaultField[ 'type' ] ) {
				case 'addproject': {
					$projectmeta = get_post_meta( $post->ID, substr( '_wpcrm_' . $defaultField[ 'name' ], 0, -4), true );
					if ( $projectmeta == '' ) {
						$before = $defaultField[ 'before' ];
						$after = $defaultField[ 'after' ];
						echo $before;
						echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
						echo '<label for="' . '_wpcrm_' . $defaultField['name'] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
						echo '<input type="text" name="' . '_wpcrm_' . $defaultField['name'] . '" id="' . '_wpcrm_' . $defaultField['name'] . '" value="" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
						echo '</div>';
						echo $after;
					}
					break;
				}
				case 'addorganization': {
					$orgmeta = get_post_meta( $post->ID, substr( '_wpcrm_' . $defaultField[ 'name' ], 0, -4), true );
					if ( $orgmeta == '' ) {
						$before = $defaultField[ 'before' ];
						$after = $defaultField[ 'after' ];
						echo $before;
						echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
						echo '<label for="' . '_wpcrm_' . $defaultField['name'] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
						echo '<input type="text" name="' . '_wpcrm_' . $defaultField['name'] . '" id="' . '_wpcrm_' . $defaultField['name'] . '" value="" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
						echo '</div>';
						echo $after;
					}
					break;
				}
				case 'addcontact': {
					$contactmeta = get_post_meta( $post->ID, substr( '_wpcrm_' . $defaultField[ 'name' ], 0, -4), true );
					if ( $contactmeta == '' ) {
						$before = $defaultField[ 'before' ];
						$after = $defaultField[ 'after' ];
						echo $before;
						echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
						echo '<label for="' . '_wpcrm_' . $defaultField['name'] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
						echo '<input type="text" name="' . '_wpcrm_' . $defaultField['name'] . '" id="' . '_wpcrm_' . $defaultField['name'] . '" value="" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
						echo '</div>';
						echo $after;
					}
					break;
				}
				case 'addcampaign': {
					$campaignmeta = get_post_meta( $post->ID, substr( '_wpcrm_' . $defaultField[ 'name' ], 0, -4), true );
					if ( $campaignmeta == '' ) {
						$before = $defaultField[ 'before' ];
						$after = $defaultField[ 'after' ];
						echo $before;
						echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
						echo '<label for="' . '_wpcrm_' . $defaultField['name'] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
						echo '<input type="text" name="' . '_wpcrm_' . $defaultField['name'] . '" id="' . '_wpcrm_' . $defaultField['name'] . '" value="" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
						echo '</div>';
						echo $after;
					}
					break;
				}

				case 'selectuser':
				case 'selectcampaign':
				case 'selectorganization':
				case 'selectcontact':
				case 'selectproject':
				case 'selectprogress':
				case 'selectwonlost':
				case 'selectpriority':
				case 'selectstatus':
				case 'selectnameprefix': {
					// Select
					$selection = get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true );
					$editshow = in_array( $defaultField[ 'type' ], array( 'selectprogress', 'selectwonlost', 'selectpriority', 'selectstatus', 'selectnameprefix' ) ) ? 'onmouseenter=showEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '")' : '';
					$before = $defaultField[ 'before' ];
					$after = $defaultField[ 'after' ];
					echo $before;
					echo '<div ' . $editshow . ' class="form-field form-required ' . $defaultField[ 'style' ] . '">';
					//Select User
					if ( $defaultField[ 'type' ] == "selectuser" ) {
						$users = get_users();
						$wp_crm_users = array();
						foreach( $users as $user ){
							if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
								$wp_crm_users[] = $user;
							}
						}
						foreach( $wp_crm_users as $user) {
							if ($selection == $user->data->user_login) { $display_name = $user->data->display_name; }
							if (!$selection || '' == $selection) { $display_name = __('Not Assigned', 'wp-crm-system'); }
						}
						if ( isset( $selection ) ){
							echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . ': </strong>';
							if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
								echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
							}
							echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline">' . $display_name . '</span></label>';
						}
						echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
							echo '<option value="" ' . selected( $selection, '' ) . '>Not Assigned</option>';
							foreach( $wp_crm_users as $user) {
								echo '<option value="' . $user->data->user_login . '" ' . selected( $selection, $user->data->user_login ) . '>'. $user->data->display_name .'</option>';
							}
						echo'</select>';
					} elseif ( $defaultField[ 'type' ] == "selectcampaign" ) {
						//Select Campaign
						$campaigns = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-campaign'));
						if ($campaigns) {
							if ( isset( $selection ) ){
								if ( 'do not show' != $selection ){
									echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . ': </strong>';
									if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									if ( get_post_status( $selection ) != 'trash' ){
										echo '<a id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline;" href="' . get_edit_post_link($selection) . '">' . get_the_title($selection) . '</a></label>';
									}
								}
							}

							echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
							echo '<option value="" ' . selected( $selection, '' ) . '>Not Assigned</option>';
							echo '<option value="do not show" ' . selected( $selection, 'do not show' ) . '>Not Applicable</option>';
							foreach($campaigns as $campaign) {
								echo '<option value="' . $campaign->ID . '"' . selected( $selection, $campaign->ID ) . '>' . get_the_title($campaign->ID) . '</option>';
							}
							echo '</select>';
						} else {
							echo '<a href="' . admin_url('edit.php?post_type=wpcrm-campaign') . '">';
							_e('Please create a campaign first.','wp-crm-system');
							echo '</a>';
						}
					} elseif ( $defaultField[ 'type' ] == "selectorganization" ) {
						//Select Organization
						$orgs = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-organization'));
						if ($orgs) {
							if ( isset( $selection ) ){
								if ( 'do not show' != $selection ){
									echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . ': </strong>';
									if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									if ( get_post_status( $selection ) != 'trash' ){
										echo '<a id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline;" href="' . get_edit_post_link($selection) . '">' . get_the_title($selection) . '</a></label>';
									}
								}
							}
								echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" style="display:none;" class="wp-crm-system-searchable" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
								echo '<option value="" ' . selected( $selection, '' ) . '>Not Assigned</option>';
								echo '<option value="do not show" ' . selected( $selection, 'do not show' ) . '>Not Applicable</option>';
								foreach( $orgs as $org ) {
									$orgaddress = ( true == get_option( 'wpcrm_system_show_org_address' ) && '' != get_post_meta( $org->ID, '_wpcrm_organization-address1', true ) ) ? ' [' . esc_html( get_post_meta( $org->ID, '_wpcrm_organization-address1', true ) ) . ']' : '';
									echo '<option value="' . $org->ID . '"' . selected( $selection, $org->ID ) . '>' . esc_html( get_the_title( $org->ID ) ) . $orgaddress . '</option>';
								}
								echo '</select>';
							} else {
							echo '<a href="' . admin_url( 'edit.php?post_type=wpcrm-organization' ) . '">';
							_e('Please create an organization first.','wp-crm-system');
							echo '</a>';
						}
					} elseif ( $defaultField[ 'type' ] == "selectcontact" ) {
						//Select Contact
						$contacts = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-contact'));
						if ($contacts) {
							if ( isset( $selection ) ){
								if ( 'do not show' != $selection ){
									echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . ': </strong>';
									if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									if ( get_post_status( $selection ) != 'trash' ){
										echo '<a id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline;" href="' . get_edit_post_link($selection) . '">' . get_the_title($selection) . '</a></label>';
									}
								}
							}
							echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
							echo '<option value="" ' . selected( $selection, '' ) . '>Not Assigned</option>';
							echo '<option value="do not show" ' . selected( $selection, 'do not show' ) . '>Not Applicable</option>';
							foreach($contacts as $contact) {
								echo '<option value="' . $contact->ID . '"' . selected( $selection, $contact->ID ) . '>' . get_the_title($contact->ID) . '</option>';
							}
							echo '</select>';
						} else {
							echo '<a href="' . admin_url('edit.php?post_type=wpcrm-contact') . '">';
							_e('Please create a contact first.','wp-crm-system');
							echo '</a>';
						}
					} elseif ( $defaultField[ 'type' ] == "selectproject" ) {
						//Select Project
						$projects = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-project'));
						if ($projects) {
							if ( isset( $selection ) ){
								if ( 'do not show' != $selection ){
									echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . ': </strong>';
									if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									if ( get_post_status( $selection ) != 'trash' ){
										echo '<a id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline;" href="' . get_edit_post_link($selection) . '">' . get_the_title($selection) . '</a></label>';
									}
								}
							}

							echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" style="display:none;" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
							echo '<option value="" ' . selected( $selection, '' ) . '>Not Assigned</option>';
							foreach($projects as $project) {
								echo '<option value="' . $project->ID . '"' . selected( $selection, $project->ID ) . '>' . get_the_title($project->ID) . '</option>';
							}
							echo '</select>';
						} else {
							echo '<a href="' . admin_url('edit.php?post_type=wpcrm-project') . '">';
							_e('Please create a project first.','wp-crm-system');
							echo '</a>';
						}
					} else {
						// Select progress
						if ( $defaultField[ 'type' ] == "selectprogress" ) {
							$args = array('zero'=>0,5=>5,10=>10,15=>15,20=>20,25=>25,30=>30,35=>35,40=>40,45=>45,50=>50,55=>55,60=>60,65=>65,70=>70,75=>75,80=>80,85=>85,90=>90,95=>95,100=>100);
							$wpcrm_after = '%';
						}
						//Select Won/Lost
						if ( $defaultField[ 'type' ] == "selectwonlost" ) {
							$args = array('not-set'=>__('Select an option', 'wp-crm-system'),'won'=>_x('Won','Successful, a winner.','wp-crm-system'),'lost'=>_x('Lost','Unsuccessful, a loser.','wp-crm-system'),'suspended'=>_x('Suspended','Temporarily ended, but may resume again.','wp-crm-system'),'abandoned'=>_x('Abandoned','No longer actively working on.','wp-crm-system'));
							$wpcrm_after = '';
						}
						if ( $defaultField[ 'type' ] == "selectpriority" ) {
							$args = array(''=>__('Select an option', 'wp-crm-system'),'low'=>_x('Low','Not of great importance','wp-crm-system'),'medium'=>_x('Medium','Average priority','wp-crm-system'),'high'=>_x('High','Greatest importance','wp-crm-system'));
							$wpcrm_after = '';
						}
						//Select status
						if ( $defaultField[ 'type' ] == "selectstatus" ) {
							$args = array('not-started'=>_x('Not Started','Work has not yet begun.','wp-crm-system'),'in-progress'=>_x('In Progress','Work has begun but is not complete.','wp-crm-system'),'complete'=>_x('Complete','All tasks are finished. No further work is needed.','wp-crm-system'),'on-hold'=>_x('On Hold','Work may be in various stages of completion, but has been stopped for one reason or another.','wp-crm-system'));
							$wpcrm_after = '';
						}
						//Select prefix
						if ( $defaultField[ 'type' ] == "selectnameprefix" ) {
							$args = array(''=>__('Select an Option','wp-crm-system'),'mr'=>_x('Mr.','Title for male without a higher professional title.','wp-crm-system'),'mrs'=>_x('Mrs.','Married woman or woman who has been married with no higher professional title.','wp-crm-system'),'miss'=>_x('Miss','An unmarried woman. Also Ms.','wp-crm-system'),'ms'=>_x('Ms.','An unmarried woman. Also Miss.','wp-crm-system'),'dr'=>_x('Dr.','Doctor','wp-crm-system'),'master'=>_x('Master','Title used for young men.','wp-crm-system'),'coach'=>_x('Coach','Title used for the person in charge of a sports team','wp-crm-system'),'rev'=>_x('Rev.','Title of a priest or religious clergy - Reverend ','wp-crm-system'),'fr'=>_x('Fr.','Title of a priest or religious clergy - Father','wp-crm-system'),'atty'=>_x('Atty.','Attorney, or lawyer','wp-crm-system'),'prof'=>_x('Prof.','Professor, as in a teacher at a university.','wp-crm-system'),'hon'=>_x('Hon.','Honorable - often used for elected officials or judges.','wp-crm-system'),'pres'=>_x('Pres.','Term given to the head of an organization or country. As in President of a University or President of the United States','wp-crm-system'),'gov'=>_x('Gov.','Governor, as in the Governor of the State of New York.','wp-crm-system'),'ofc'=>_x('Ofc.','Officer as in a police officer.','wp-crm-system'),'supt'=>_x('Supt.','Superintendent','wp-crm-system'),'rep'=>_x('Rep.','Representative - as in an elected official to the House of Representatives','wp-crm-system'),'sen'=>_x('Sen.','An elected official - Senator.','wp-crm-system'),'amb'=>_x('Amb.','Ambassador - a diplomatic official.','wp-crm-system'));
							if ( has_filter( 'wpcrmsystem_name_prefix' ) ){
								$args = apply_filters( 'wpcrmsystem_name_prefix', $args );
							}
							$wpcrm_after = '';
						}
						?>
						<label for="<?php echo '_wpcrm_' . $defaultField[ 'name' ]; ?>" style="display:<?php if ( isset( $selection ) && '' != $selection ) { echo 'none'; } else { echo 'inline'; }; ?>" id="<?php echo '_wpcrm_' . $defaultField[ 'name' ]; ?>-label"><strong><?php _e($defaultField[ 'title' ],'wp-crm-system'); ?></strong></label><?php if ( !isset( $selection ) || '' == $selection ) { echo '<br />'; } ?>
						<select id="<?php echo '_wpcrm_' . $defaultField[ 'name' ] . '-input"'; if ( isset( $selection ) && '' != $selection ) { echo ' style="display:none;"'; } ?> name="<?php echo '_wpcrm_' . $defaultField[ 'name' ]; ?>">
							<?php
							$display=''; //in case the foreach loop is not entered.
							foreach ($args as $key => $value) { ?>
								<option value="<?php echo $key; ?>" <?php if (esc_html( $selection ) == $key) { echo 'selected'; $display = $value; } ?> ><?php echo $value; if ( $defaultField[ 'type' ] == "selectprogress" ) { echo '%'; }?></option>
								<?php } ?>
							</select>
							<?php if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] && isset ( $selection ) && '' != $selection ){
								echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
							} ?>
							<span id="<?php echo '_wpcrm_' . $defaultField[ 'name' ] . '-text" '; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:inline;"'; } else { echo 'style="display:none;"'; } echo '>' . $display . $wpcrm_after . '</span>'; ?>
							<span id="<?php echo '_wpcrm_' . $defaultField[ 'name' ] . '-edit"'; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")'; } ?> ></span>
								<?php
							}
							echo '</div>';
							echo $after;
							break;
						}
						case 'currency': {
							$amount = esc_html( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) );
							$currency_symbol = wpcrm_system_display_currency_symbol( trim( get_option( 'wpcrm_system_default_currency' ) ) );
								$before = $defaultField[ 'before' ];
								$after = $defaultField[ 'after' ];
								echo $before;
								echo '<div onmouseenter=showEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
								if ( isset( $amount ) && '' != $amount ) {
									echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline">' . $currency_symbol . $amount . '</span>';
									echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $amount . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
									echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
								} else {
									echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
									echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '" value="' . $amount . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
									echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
									echo '<br />';
									echo '<em>' . __('Only numbers allowed. No thousands separator (commas, spaces, or periods), currency symbols, etc. allowed.', 'wp-crm-system') . '</em>';
								} ?>
								<span id="<?php echo '_wpcrm_' . $defaultField[ 'name' ] . '-comment'; ?>" style="display:none;"><br />
								<em><?php _e('Only numbers allowed. No thousands separator (commas, spaces, or periods), currency symbols, etc. allowed.', 'wp-crm-system');?></em></span><?php
								echo '</div>';
								echo $after;
							break;
						}
						case 'textarea':
						case 'wysiwyg': {
							echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
							if ($defaultField[ 'type' ] == 'textarea') {
								echo '<textarea name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '" columns="30" rows="3">' . esc_textarea( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) ) . '</textarea>';
							}
							// WYSIWYG
							if ( $defaultField[ 'type' ] == "wysiwyg" ) {
								$post = get_post( get_the_ID(), OBJECT, 'edit' );
								$content = get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true );
								$editor_id = '_wpcrm_' . $defaultField[ 'name' ];
								$settings = array( 'drag_drop_upload' => true );
								wp_editor($content, $editor_id, $settings);
							}
							echo '</div>';
							break;
						}
						case 'checkbox': {
							// Checkbox
							echo '<div class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
							echo '<input type="checkbox" name="' . '_wpcrm_' . $defaultField['name'] . '" id="' . '_wpcrm_' . $defaultField['name'] . '" value="yes"';
							if ( get_post_meta( $post->ID, '_wpcrm_' . $defaultField['name'], true ) == "yes" )
							echo ' checked="checked"';
							echo '" style="width: auto;" />';
							echo '</div>';
							break;
						}
						case 'datepicker': {
							$timestamp = absint( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) );
							// Should only legitimately be 0 if the date is supposed to be January 1, 1970. No other dates will be affected.
							// Any other 0 value indicates no value was saved, and therefore shouldn't be output as a date.
							if ( isset( $timestamp ) && is_numeric( $timestamp ) && 0 != $timestamp ) {
								$date = date( get_option( 'wpcrm_system_php_date_format' ), $timestamp );
							} else {
								$date = '';
							}
							$before	= $defaultField[ 'before' ];
							$after	= $defaultField[ 'after' ];
							//Datepicker
							?>
								<script type="text/javascript">
									<?php
									$dateformat = get_option('wpcrm_system_date_format');
									echo "var formatOption = '".$dateformat."';";
									?>
									jQuery(document).ready(function() {
										jQuery('.datepicker').datepicker({
											dateFormat : formatOption //allow date format change in settings
										});
									});
								</script>
								<?php
							echo $before;
							echo '<div onmouseenter=showEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							if ( isset( $date ) && '' != $date ) {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
									echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
								}
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline">' . $date . '</span>';
								echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" style="display:none;" autocomplete="off" class="datepicker" value="' . $date . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons" onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
							} else {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '" autocomplete="off" class="datepicker" value="' . $date . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
							}
							echo '</div>';
							echo $after;
							break;
						}
						case 'dropbox': {
							if(is_plugin_active('wp-crm-system-dropbox/wp-crm-system-dropbox.php')) {
								$field = '_wpcrm_' . $defaultField[ 'name' ];
								$title = $defaultField[ 'title' ];
								wp_crm_dropbox_content($field,$title);
							} else {
								echo '';
							}
							break;
						}
						case 'zendesk': {
							if(is_plugin_active('wp-crm-system-zendesk/wp-crm-system-zendesk.php')) {
								if ((get_option('_wpcrm_zendesk_api_key') && get_option('_wpcrm_zendesk_user') && get_option('_wpcrm_zendesk_subdomain')) != '') {
									// Set display fields
									$field = '_wpcrm_' . $defaultField[ 'name' ];
									$title = $defaultField[ 'title' ];
									$contact = $post->ID;
									wp_crm_zendesk_content($field,$title,$contact);
								}
							}
							break;
						}
						case 'email': {
							// Plain text field with email validation
							$email = esc_html( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) );
							$before = $defaultField[ 'before' ];
							$after = $defaultField[ 'after' ];
							echo $before;
							echo '<div onmouseenter=showEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							if ( isset( $email ) && '' != $email ) {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
									echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
								}
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline"><a href="mailto:' . $email . '">' . $email . '</a></span>';
								echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $email . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
							} else {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '" value="' . $email . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
							}
							echo '</div>';
							echo $after;
							break;
						}
						case 'url': {
							// Plain text field with url validation
							$urllink = esc_url( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) );
							$before = $defaultField[ 'before' ];
							$after = $defaultField[ 'after' ];
							echo $before;
							echo '<div onmouseenter=showEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';

							if ( isset( $urllink ) && '' != $urllink ) {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
									echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
								}
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline"><a href="' . $urllink . '">' . $urllink . '</a></span>';
								echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $urllink . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
							} else {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '" value="' . esc_url( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) ) . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
							}
							echo '</div>';
							echo $after;
							break;
						}
						case 'number': {
							// Plain text field
							$textinput = esc_html( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) );
							$before = $defaultField[ 'before' ];
							$after = $defaultField[ 'after' ];
							echo $before;
							echo '<div onmouseenter=showEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							if ( isset( $textinput ) && '' != $textinput ) {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
									echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
								}
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline">' . $textinput . '</span>';
								echo '<input type="number" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $textinput . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
							} else {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
								echo '<input class="' . $defaultField[ 'name' ] . '" type="number" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '" value="' . esc_html( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) ) . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
							}
							echo '</div>';
							echo $after;
							break;
						}
						case 'phone': {
							// Plain text field with clickable link to initiate call on mobile devices.
							$textinput = esc_html( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) );
							$before = $defaultField[ 'before' ];
							$after = $defaultField[ 'after' ];
							echo $before;
							echo '<div onmouseenter=showEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							if ( isset( $textinput ) && '' != $textinput ) {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
									echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
								}
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline"><a href="tel:' . $textinput . '">' . $textinput . '</a></span>';
								echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $textinput . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
							} else {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
								echo '<input class="' . $defaultField[ 'name' ] . '" type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '" value="' . esc_html( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) ) . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
							}
							echo '</div>';
							echo $after;
							break;
						}
						default: {
							// Plain text field
							$textinput = esc_html( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) );
							$before = $defaultField[ 'before' ];
							$after = $defaultField[ 'after' ];
							echo $before;
							echo '<div onmouseenter=showEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							if ( isset( $textinput ) && '' != $textinput ) {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
									echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
								}
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline">' . $textinput . '</span>';
								echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $textinput . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
							} else {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
								echo '<input class="' . $defaultField[ 'name' ] . '" type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '" value="' . esc_html( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) ) . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
							}
							echo '</div>';
							echo $after;
							break;
						}
					}
					?>
					<?php if ( $defaultField[ 'description' ] ) echo '<p>' . $defaultField[ 'description' ] . '</p>'; ?>
					<?php
				}
			} ?>
		</div>
		<?php
	}
// Add content to the new meta box
function wpcrm_system_display_email() {
	global $post;
	$contact_emails = get_post_meta( $post->ID, '_wpcrm_system_email', false );
	if ( $contact_emails ){ ?>
		<div class="contact_email_list">
		<?php
		foreach ( $contact_emails as $email ) {
			$sent = date( get_option( 'wpcrm_system_php_date_format' ),esc_html( $email[2] ) ); ?>
			<div class="email_info">
			<?php
				echo __( 'Sent: ', 'wp-crm-system' ) . $sent . __( ' From: ', 'wp-crm-system' ) . $email[0] . ' ' . $email[1] . '<br />';
				echo __( 'Subject: ', 'wp-crm-system' ) . $email[3] . '<br />';
				echo __( 'Message: ', 'wp-crm-system' ) . '<br />' . $email[4];
				echo '<hr />';
			?>
			</div>
			<?php
		} ?>
		</div>
		<?php
	}
}