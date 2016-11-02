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
  $postTypes = array( 'wpcrm-contact', 'wpcrm-task', 'wpcrm-organization', 'wpcrm-opportunity', 'wpcrm-project', 'wpcrm-campaign' );
  $gmapTypes = array( 'wpcrm-contact', 'wpcrm-organization' );
  if ( function_exists( 'add_meta_box' ) ) {
    foreach ( $postTypes as $postType ) {
      add_meta_box( 'wpcrm-default-fields', __( 'Fields', 'wp-crm-system' ), 'wpcrmDefaultFields', $postType, 'normal', 'high' );
    }
    if ( '' != get_option( 'wpcrm_system_gmap_api' ) ) {
      foreach ( $gmapTypes as $gmapType ) {
        add_meta_box( 'wpcrm-gmap', __( 'Map', 'wp-crm-system' ), 'wpcrmGmap', $gmapType, 'side', 'low' );
      }
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
                'post_content'	=> '',
                'post_title'		=> $new_title,
                'post_status'		=> 'publish',
                'post_date'			=> date('Y-m-d H:i:s'),
                'post_date_gmt'	=> gmdate('Y-m-d H:i:s'),
                'post_author'		=> $author_id,
                'post_name'			=> $new_slug,
                'post_type'			=> 'wpcrm-contact'
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
                'post_content'	=> '',
                'post_title'		=> $new_org_title,
                'post_status'		=> 'publish',
                'post_date'			=> date('Y-m-d H:i:s'),
                'post_date_gmt'	=> gmdate('Y-m-d H:i:s'),
                'post_author'		=> $author_id,
                'post_name'			=> $new_org_slug,
                'post_type'			=> 'wpcrm-organization'
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
                'post_content'	=> '',
                'post_title'		=> $new_title,
                'post_status'		=> 'publish',
                'post_date'			=> date('Y-m-d H:i:s'),
                'post_date_gmt'	=> gmdate('Y-m-d H:i:s'),
                'post_author'		=> $author_id,
                'post_name'			=> $new_slug,
                'post_type'			=> 'wpcrm-project'
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
                'post_content'	=> '',
                'post_title'		=> $new_title,
                'post_status'		=> 'publish',
                'post_date'			=> date('Y-m-d H:i:s'),
                'post_date_gmt'	=> gmdate('Y-m-d H:i:s'),
                'post_author'		=> $author_id,
                'post_name'			=> $new_slug,
                'post_type'			=> 'wpcrm-campaign'
              ));
              $safevalue = $wpdb->insert_id;
              update_post_meta( $currentid, substr( '_wpcrm_' . $defaultField[ 'name' ], 0, -4), $safevalue );
              $wpdb->flush();
            }
            break;
          }
          case 'default': {
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
function geocode($address){

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
			// get latitude, longitude and formatted address
			$data_arr = geocode( $addressString );

			// if able to geocode the address
			if( $data_arr ){
				$latitude = $data_arr[0];
				$longitude = $data_arr[1];
				$formatted_address = $data_arr[2];
		  }

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
add_action( 'admin_enqueue_scripts', 'wcs_gmap_load_js' );

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
	// get latitude, longitude and formatted address
	$data_arr = geocode( $addressString );

	// if able to geocode the address
	if( $data_arr ){
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

	$projectFromOpportunity = "//" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&wpcrm-system-action=new-project-from-opportunity';
	echo WPCRM_SAVE_CHANGES;
	echo '<ul>';
	echo '<li><a class="button" href="' . $projectFromOpportunity . '">' . __( 'Create Project From Opportunity', 'wp-crm-system' ) . '</a></li>';
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
					'comment_status'	=>	'closed',
					'ping_status'		=>	'closed',
					'post_author'		=>	$author_id,
					'post_name'			=>	$slug,
					'post_title'		=>	$title,
					'post_status'		=>	'publish',
					'post_type'			=>	'wpcrm-project'
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
  	'posts_per_page'	=> -1,
  	'post_type'			=> 'wpcrm-task',
  	'meta_query' 		=> array(
  		array(
  			'key' 	=> $meta_key,
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
  	'posts_per_page'	=> -1,
  	'post_type'			=> 'wpcrm-project',
  	'meta_query' 		=> array(
  		array(
  			'key' 	=> $meta_key,
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
  	'posts_per_page'	=> -1,
  	'post_type'			=> 'wpcrm-task',
  	'meta_query' 		=> array(
  		array(
  			'key' 	=> $meta_key,
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
function wpcrmListOpportunitiesinContact() {
  include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php');
  //List Opportunities
  $meta_key = '_wpcrm_opportunity-attach-to-contact';
  $meta_value = get_the_ID();
  $opportunities = get_posts( array(
  	'posts_per_page'	=> -1,
  	'post_type'			=> 'wpcrm-opportunity',
  	'meta_query' 		=> array(
  		array(
  			'key' 	=> $meta_key,
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
  	'posts_per_page'	=> -1,
  	'post_type'			=> 'wpcrm-project',
  	'meta_query' 		=> array(
  		array(
  			'key' 	=> $meta_key,
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
  	'posts_per_page'	=> -1,
  	'post_type'			=> 'wpcrm-task',
  	'meta_query' 		=> array(
  		array(
  			'key' 	=> $meta_key,
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
  	'posts_per_page'	=> -1,
  	'post_type'			=> 'wpcrm-opportunity',
  	'meta_query' 		=> array(
  		array(
  			'key' 	=> $meta_key,
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
  	'posts_per_page'	=> -1,
  	'post_type'			=> 'wpcrm-contact',
  	'meta_query' 		=> array(
  		array(
  			'key' 	=> $meta_key,
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
					$before = $defaultField[ 'before' ];
					$after = $defaultField[ 'after' ];
					echo $before;
					echo '<div onmouseenter=showEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
					//Select User
					if ( $defaultField[ 'type' ] == "selectuser" ) {
						if ( isset( $selection ) && '' != $selection ){
							if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
								echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
							}
							echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
							echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" style="display:none;" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
							if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
							echo '<option value="" ' . $selected . '>Not Assigned</option>';
							$users = get_users();
							$wp_crm_users = array();
							foreach( $users as $user ){
								if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
									$wp_crm_users[] = $user;
								}
							}
							foreach( $wp_crm_users as $user) {
								if ($selection == $user->data->user_login) { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="' . $user->data->user_login . '" ' . $selected . '>'. $user->data->display_name .'</option>';
								if ($selection == $user->data->user_login) { $display_name = $user->data->display_name; }
								if (!$selection || '' == $selection) { $display_name = __('Not Assigned', 'wp-crm-system'); }
							}
							echo'</select>';
							echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline">' . $display_name . '</span>';
							echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
						} else {
							echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
							echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
							echo '<option value="" selected>Not Assigned</option>';
							$users = get_users();
							$wp_crm_users = array();
							foreach( $users as $user ){
								if($user->has_cap(get_option('wpcrm_system_select_user_role'))){
									$wp_crm_users[] = $user;
								}
							}
							foreach( $wp_crm_users as $user) {
								$display_name = $user->data->display_name;
								echo '<option value="'.$user->data->user_login.'">'.$display_name.'</option>';
							}
							echo'</select>';
							echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
						}
					} elseif ( $defaultField[ 'type' ] == "selectcampaign" ) {
						//Select Campaign
						$campaigns = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-campaign'));
						if ($campaigns) {
							if ( isset( $selection ) && '' != $selection ){
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" style="display:none;" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
								if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="" ' . $selected . '>Not Assigned</option>';
								if ( $selection == 'do not show') { $selected = 'selected'; $linkcampaign = ' '; } else { $selected = ''; }
								echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
								foreach($campaigns as $campaign) {
									if ($selection == $campaign->ID) { $selected = 'selected'; $linkcampaign = $campaign->ID; } else { $selected = ''; }
									echo '<option value="' . $campaign->ID . '"' . $selected . '>' . get_the_title($campaign->ID) . '</option>';
								}
								echo '</select>';
								if (isset($linkcampaign)) {
									if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									echo '<a id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" '; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:inline;"'; } else { echo 'style="display:none;"'; } echo 'href="' . get_edit_post_link($linkcampaign) . '">' . get_the_title($linkcampaign) . '</a>';
									echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
								}
							} else {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
								echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
								if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="" ' . $selected . '>Not Assigned</option>';
								if ( $selection == 'do not show') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
								foreach($campaigns as $campaign) {
									if ($selection == $campaign->ID) { $selected = 'selected'; $linkcampaign = $campaign->ID; } else { $selected = ''; }
									echo '<option value="' . $campaign->ID . '"' . $selected . '>' . get_the_title($campaign->ID) . '</option>';
								}
								echo '</select>';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
							}
						} else {
							echo '<a href="' . admin_url('edit.php?post_type=wpcrm-campaign') . '">';
							_e('Please create a campaign first.','wp-crm-system');
							echo '</a>';
						}
					} elseif ( $defaultField[ 'type' ] == "selectorganization" ) {
						//Select Organization
						$orgs = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-organization'));
						if ($orgs) {
							if ( isset( $selection ) && '' != $selection ){
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" style="display:none;" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
								if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="" ' . $selected . '>Not Assigned</option>';
								if ( $selection == 'do not show') { $selected = 'selected'; $linkorg = ' '; } else { $selected = ''; }
								echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
								foreach($orgs as $org) {
									if ($selection == $org->ID) { $selected = 'selected'; $linkorg = $org->ID;} else { $selected = ''; }
									echo '<option value="' . $org->ID . '"' . $selected . '>' . get_the_title($org->ID) . '</option>';
								}
								echo '</select>';
								if (isset($linkorg)) {
									if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									echo '<a id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" '; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:inline;"'; } else { echo 'style="display:none;"'; } echo 'href="' . get_edit_post_link($linkorg) . '">' . get_the_title($linkorg) . '</a>';
									echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
								}
							} else {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
								echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
								if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="" ' . $selected . '>Not Assigned</option>';
								if ( $selection == 'do not show') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
								foreach($orgs as $org) {
									if ($selection == $org->ID) { $selected = 'selected'; $linkorg = $org->ID;} else { $selected = ''; }
									echo '<option value="' . $org->ID . '"' . $selected . '>' . get_the_title($org->ID) . '</option>';
								}
								echo '</select>';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
							}
						} else {
							echo '<a href="' . admin_url('edit.php?post_type=wpcrm-organization') . '">';
							_e('Please create an organization first.','wp-crm-system');
							echo '</a>';
						}
					} elseif ( $defaultField[ 'type' ] == "selectcontact" ) {
						//Select Contact
						$contacts = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-contact'));
						if ($contacts) {
							if ( isset( $selection ) && '' != $selection ){
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" style="display:none;" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
								if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="" ' . $selected . '>Not Assigned</option>';
								if ( $selection == 'do not show') { $selected = 'selected'; $linkcontact = ' '; } else { $selected = ''; }
								echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
								foreach($contacts as $contact) {
									if ($selection == $contact->ID) { $selected = 'selected'; $linkcontact = $contact->ID; } else { $selected = ''; }
									echo '<option value="' . $contact->ID . '"' . $selected . '>' . get_the_title($contact->ID) . '</option>';
								}
								echo '</select>';
								if (isset($linkcontact)) {
									if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									echo '<a id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" '; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:inline;"'; } else { echo 'style="display:none;"'; } echo 'href="' . get_edit_post_link($linkcontact) . '">' . get_the_title($linkcontact) . '</a>';
									echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
								}
							} else {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
								echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
								if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="" ' . $selected . '>Not Assigned</option>';
								if ( $selection == 'do not show') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
								foreach($contacts as $contact) {
									if ($selection == $contact->ID) { $selected = 'selected'; $linkcontact = $contact->ID; } else { $selected = ''; }
									echo '<option value="' . $contact->ID . '"' . $selected . '>' . get_the_title($contact->ID) . '</option>';
								}
								echo '</select>';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
							}
						} else {
							echo '<a href="' . admin_url('edit.php?post_type=wpcrm-contact') . '">';
							_e('Please create a contact first.','wp-crm-system');
							echo '</a>';
						}
					} elseif ( $defaultField[ 'type' ] == "selectproject" ) {
						//Select Project
						$projects = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-project'));
						if ($projects) {
							if ( isset( $selection ) && '' != $selection ){
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" style="display:none;" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
								if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="" ' . $selected . '>Not Assigned</option>';
								foreach($projects as $project) {
									if ($selection == $project->ID) { $selected = 'selected'; $linkproject = $project->ID;} else { $selected = ''; }
									echo '<option value="' . $project->ID . '"' . $selected . '>' . get_the_title($project->ID) . '</option>';
								}
								echo '</select>';
								if (isset($linkproject)) {
									if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
										echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
									}
									echo '<a id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" '; if ( isset( $selection ) && '' != $selection ) { echo 'style="display:inline;"'; } else { echo 'style="display:none;"'; } echo 'href="' . get_edit_post_link($linkproject) . '">' . get_the_title($linkproject) . '</a>';
									echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
								}
							} else {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:inline;"  id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label><br />';
								echo '<select id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" class="wp-crm-system-searchable" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '">';
								if ( $selection == '') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="" ' . $selected . '>Not Assigned</option>';
								if ( $selection == 'do not show') { $selected = 'selected'; } else { $selected = ''; }
								echo '<option value="do not show" ' . $selected . '>Not Applicable</option>';
								foreach($projects as $project) {
									if ($selection == $project->ID) { $selected = 'selected'; $linkproject = $project->ID; } else { $selected = ''; }
									echo '<option value="' . $project->ID . '"' . $selected . '>' . get_the_title($project->ID) . '</option>';
								}
								echo '</select>';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit"></span>';
							}
						} else {
							echo '<a href="' . admin_url('edit.php?post_type=wpcrm-contact') . '">';
							_e('Please create a contact first.','wp-crm-system');
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
							$args = array(''=>__('Select an Option','wp-crm-system'),'mr'=>_x('Mr.','Title for male without a higher professional title.','wp-crm-system'),'mrs'=>_x('Mrs.','Married woman or woman who has been married with no higher professional title.','wp-crm-system'),'miss'=>_x('Miss','An unmarried woman. Also Ms.','wp-crm-system'),'ms'=>_x('Ms.','An unmarried woman. Also Miss.','wp-crm-system'),'dr'=>_x('Dr.','Doctor','wp-crm-system'),'master'=>_x('Master','Title used for young men.','wp-crm-system'),'coach'=>_x('Coach','Title used for the person in charge of a sports team','wp-crm-system'),'rev'=>_x('Rev.','Title of a priest or religous clergy - Reverend ','wp-crm-system'),'fr'=>_x('Fr.','Title of a priest or religous clergy - Father','wp-crm-system'),'atty'=>_x('Atty.','Attorney, or lawyer','wp-crm-system'),'prof'=>_x('Prof.','Professor, as in a teacher at a university.','wp-crm-system'),'hon'=>_x('Hon.','Honorable - often used for elected officials or judges.','wp-crm-system'),'pres'=>_x('Pres.','Term given to the head of an organization or country. As in President of a University or President of the United States','wp-crm-system'),'gov'=>_x('Gov.','Governor, as in the Governor of the State of New York.','wp-crm-system'),'ofc'=>_x('Ofc.','Officer as in a police officer.','wp-crm-system'),'supt'=>_x('Supt.','Superintendent','wp-crm-system'),'rep'=>_x('Rep.','Representative - as in an elected official to the House of Representatives','wp-crm-system'),'sen'=>_x('Sen.','An elected official - Senator.','wp-crm-system'),'amb'=>_x('Amb.','Ambassador - a diplomatic official.','wp-crm-system'));
							$wpcrm_after = '';
						}
						?>
						<label for="<?php echo '_wpcrm_' . $defaultField[ 'name' ]; ?>" style="display:<?php if ( isset( $selection ) && '' != $selection ) { echo 'none'; } else { echo 'inline'; }; ?>" id="<?php echo '_wpcrm_' . $defaultField[ 'name' ]; ?>-label"><strong><?php _e($defaultField[ 'title' ],'wp-crm-system'); ?></strong></label><?php if ( !isset( $selection ) || '' == $selection ) { echo '<br />'; } ?>
						<select id="<?php echo '_wpcrm_' . $defaultField[ 'name' ] . '-input"'; if ( isset( $selection ) && '' != $selection ) { echo ' style="display:none;"'; } ?> name="<?php echo '_wpcrm_' . $defaultField[ 'name' ]; ?>">
							<?php foreach ($args as $key => $value) { ?>
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
              $wpcrm_currencies = array('aed'=>'AED','afn'=>'&#1547;','all'=>'&#76;&#101;&#107;','amd'=>'AMD','ang'=>'&#402;','aoa'=>'AOA','ars'=>'&#36;','aud'=>'&#36;','awg'=>'&#402;','azn'=>'&#1084;&#1072;&#1085;','bam'=>'&#75;&#77;','bbd'=>'&#36;','bdt'=>'BDT','bgn'=>'&#1083;&#1074;','bhd'=>'BHD','bif'=>'BIF','bmd'=>'&#36;','bnd'=>'&#36;','bob'=>'&#36;&#98;','brl'=>'&#82;&#36;','bsd'=>'&#36;','btn'=>'BTN','bwp'=>'&#80;','byr'=>'&#112;&#46;','bzd'=>'&#66;&#90;&#36;','cad'=>'&#36;','cdf'=>'CDF','chf'=>'&#67;&#72;&#70;','clp'=>'&#36;','cny'=>'&#165;','cop'=>'&#36;','crc'=>'&#8353;','cuc'=>'CUC','cup'=>'&#8369;','cve'=>'CVE','czk'=>'&#75;&#269;','djf'=>'DJF','dkk'=>'&#107;&#114;','dop'=>'&#82;&#68;&#36;','dzd'=>'DZD','egp'=>'&#163;','ern'=>'ERN','etb'=>'ETB','eur'=>'&#8364;','fjd'=>'&#36;','fkp'=>'&#163;','gbp'=>'&#163;','gel'=>'GEL','ggp'=>'&#163;','ghs'=>'&#162;','gip'=>'&#163;','gmd'=>'GMD','gnf'=>'GNF','gtq'=>'&#81;','gyd'=>'&#36;','hkd'=>'&#36;','hnl'=>'&#76;','hrk'=>'&#107;&#110;','htg'=>'HTG','huf'=>'&#70;&#116;','idr'=>'&#82;&#112;','ils'=>'&#8362;','imp'=>'&#163;','inr'=>'&#8377;','iqd'=>'IQD','irr'=>'&#65020;','isk'=>'&#107;&#114;','jep'=>'&#163;','jmd'=>'&#74;&#36;','jod'=>'JOD','jpy'=>'&#165;','kes'=>'KES','kgs'=>'&#1083;&#1074;','khr'=>'&#6107;','kmf'=>'KMF','kpw'=>'&#8361;','krw'=>'&#8361;','kwd'=>'KWD','kyd'=>'&#36;','kzt'=>'&#1083;&#1074;','lak'=>'&#8365;','lbp'=>'&#163;','lkr'=>'&#8360;','lrd'=>'&#36;','lsl'=>'LSL','lyd'=>'LYD','mad'=>'MAD','mdl'=>'MDL','mga'=>'MGA','mkd'=>'&#1076;&#1077;&#1085;','mmk'=>'MMK','mnt'=>'&#8366;','mop'=>'MOP','mro'=>'MRO','mur'=>'&#8360;','mvr'=>'MVR','mwk'=>'MWK','mxn'=>'&#36;','myr'=>'&#82;&#77;','mzn'=>'&#77;&#84;','nad'=>'&#36;','ngn'=>'&#8358;','nio'=>'&#67;&#36;','nok'=>'&#107;&#114;','npr'=>'&#8360;','nzd'=>'&#36;','omr'=>'&#65020;','pab'=>'&#66;&#47;&#46;','pen'=>'&#83;&#47;&#46;','pgk'=>'PGK','php'=>'&#8369;','pkr'=>'&#8360;','pln'=>'&#122;&#322;','prb'=>'PRB','pyg'=>'&#71;&#115;','qar'=>'&#65020;','ron'=>'&#108;&#101;&#105;','rsd'=>'&#1044;&#1080;&#1085;&#46;','rub'=>'&#1088;&#1091;&#1073;','rwf'=>'RWF','sar'=>'&#65020;','sbd'=>'&#36;','scr'=>'&#8360;','sdg'=>'SDG','sek'=>'&#107;&#114;','sgd'=>'&#36;','shp'=>'&#163;','sll'=>'SLL','sos'=>'&#83;','srd'=>'&#36;','ssp'=>'SSP','std'=>'STD','syp'=>'&#163;','szl'=>'SZL','thb'=>'&#3647;','tjs'=>'TJS','tmt'=>'TMT','tnd'=>'TND','top'=>'TOP','try'=>'&#8378;','ttd'=>'&#84;&#84;&#36;','twd'=>'&#78;&#84;&#36;','tzs'=>'TZS','uah'=>'&#8372;','ugx'=>'UGX','usd'=>'&#36;','uyu'=>'&#36;&#85;','uzs'=>'&#1083;&#1074;','vef'=>'&#66;&#115;','vnd'=>'&#8363;','vuv'=>'VUV','wst'=>'WST','xaf'=>'XAF','xcd'=>'&#36;','xof'=>'XOF','xpf'=>'XPF','yer'=>'&#65020;','zar'=>'&#82;','zmw'=>'ZMW');
							if ( $defaultField[ 'type' ] == "currency" ) {
								$active_currency = get_option( 'wpcrm_system_default_currency' );
								foreach ( $wpcrm_currencies as $currency => $symbol ){
									if ( $active_currency == $currency ){
										$currency_symbol = $symbol;
									}
								}
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
							}
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
							if (!null == (get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ]))) {
								$date = date(get_option('wpcrm_system_php_date_format'),esc_html( get_post_meta( $post->ID, '_wpcrm_' . $defaultField[ 'name' ], true ) ) );
							} else {
								$date = '';
							}
							$before = $defaultField[ 'before' ];
							$after = $defaultField[ 'after' ];
							//Datepicker
							echo $before;
							echo '<div onmouseenter=showEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") onmouseleave=hideEdit("' . '_wpcrm_' . $defaultField[ 'name' ] . '") class="form-field form-required ' . $defaultField[ 'style' ] . '">';
							if ( isset( $date ) && '' != $date ) {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'" style="display:none;" id="' . '_wpcrm_' . $defaultField[ 'name' ] .'-label"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								if ( '' != '_wpcrm_' . $defaultField[ 'icon' ] ){
									echo '<div class="' .  $defaultField[ 'icon' ] . '" class="wp-crm-inline"></div>';
								}
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-text" style="display:inline">' . $date . '</span>';
								echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-input" style="display:none;" value="' . $date . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />';
								echo '<span id="' . '_wpcrm_' . $defaultField[ 'name' ] . '-edit" style="display:none;" class="dashicons dashicons-edit wpcrm-dashicons"  onclick=editField("' . '_wpcrm_' . $defaultField[ 'name' ] . '")></span>';
							} else {
								echo '<label for="' . '_wpcrm_' . $defaultField[ 'name' ] .'"><strong>' . __($defaultField[ 'title' ],'wp-crm-system') . '</strong></label>';
								echo '<input type="text" name="' . '_wpcrm_' . $defaultField[ 'name' ] . '" id="' . '_wpcrm_' . $defaultField[ 'name' ] . '" class="datepicker" value="' . $date . '" placeholder="' . __($defaultField['placeholder'],'wp-crm-system') . '" />'; ?>
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
