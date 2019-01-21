<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}

function wpcrm_system_gdpr_marked_deletion_post_status(){
	register_post_status( 'gdpr_deletion', array(
		'label'						=> _x( 'Marked For Deletion', 'wp-crm-system' ),
		'public'					=> false,
		'private'					=> true,
		'internal'					=> true,
		'exclude_from_search'		=> true,
		'show_in_admin_all_list'	=> true,
		'show_in_admin_status_list'	=> true,
		'label_count'				=> _n_noop( 'Marked for Deletion <span class="count">(%s)</span>', 'Marked for Deletion <span class="count">(%s)</span>' ),
	) );
}
add_action( 'init', 'wpcrm_system_gdpr_marked_deletion_post_status' );

add_filter( 'manage_edit-wpcrm-contact_columns', 'wpcrm_system_contact_columns' ) ;

function wpcrm_system_contact_columns( $columns ) {

	$columns = array(
		'cb'		=> '<input type="checkbox" />',
		'title'		=> __( 'Name', 'wp-crm-system' ),
		'photo'		=> __( 'Photo', 'wp-crm-system' ),
		'org'		=> __( 'Organization', 'wp-crm-system' ),
		'phone'		=> __( 'Phone', 'wp-crm-system' ),
		'mobile'	=> __( 'Mobile Phone', 'wp-crm-system' ),
		'email'		=> __( 'Email', 'wp-crm-system' ),
		'address'	=> __( 'Address', 'wp-crm-system' ),
		'date'		=> __( 'Date', 'wp-crm-system' ),
		'category'	=> __( 'Category', 'wp-crm-system' )
	);

	return $columns;
}

add_action( 'manage_wpcrm-contact_posts_custom_column', 'wprcm_system_contact_columns_content', 10, 2 );

function wprcm_system_contact_columns_content( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'photo' column. */
		case 'photo' :

			/* Get the post meta. */
			$email = get_post_meta( $post_id, '_wpcrm_contact-email', true );
			$thumbnail = get_the_post_thumbnail( $post_id, array( 96, 96 ) );

			/* If no duration is found, output a default message. */
			if ( empty( $email ) && empty( $thumbnail ) )
				echo __( 'No Photo Set', 'wp-crm-system' );

			/* If there is a photo, display it. */
			else
				if ( !empty( $thumbnail ) ){
					echo $thumbnail;
				} else {
					echo get_avatar( $email );
				}

			break;
		/* If displaying the 'org' column. */
		case 'org' :

			/* Get the post meta. */
			$post = get_post_meta( $post_id, '_wpcrm_contact-attach-to-organization', true );

			/* If no duration is found, output a default message. */
			if ( empty( $post ) )
				echo __( 'Not Set', 'wp-crm-system' );

			/* If there is an organization, display it with a link to the org. */
			else
				echo '<a href="' . get_edit_post_link( $post ) . '">' . get_the_title( $post ) . '</a>';

			break;
		/* If displaying the 'phone' column. */
		case 'phone' :

			/* Get the post meta. */
			$number = get_post_meta( $post_id, '_wpcrm_contact-phone', true );

			/* If no duration is found, output a default message. */
			if ( empty( $number ) )
				echo __( 'Not Set', 'wp-crm-system' );

			/* If there is a phone number, display it with clickable link. */
			else
				$number = esc_html( $number );
				echo '<a href="tel:' . $number . '">' . $number . '</a>';

			break;
		/* If displaying the 'mobile' column. */
		case 'mobile' :

			/* Get the post meta. */
			$number = esc_html( get_post_meta( $post_id, '_wpcrm_contact-mobile-phone', true ) );

			/* If no duration is found, output a default message. */
			if ( empty( $number ) )
				echo __( 'Not Set', 'wp-crm-system' );

			/* If there is a mobile phone number, display it with clickable link. */
			else
				echo '<a href="tel:' . $number . '">' . $number . '</a>';

			break;
		/* If displaying the 'email' column. */
		case 'email' :

			/* Get the post meta. */
			$email = esc_html( get_post_meta( $post_id, '_wpcrm_contact-email', true ) );

			/* If no duration is found, output a default message. */
			if ( empty( $email ) )
				echo __( 'Not Set', 'wp-crm-system' );

			/* If there is a email, display it. */
			else
				echo '<a href="mailto:' . $email . '">' . $email . '</a>';

			break;
		/* If displaying the 'address' column. */
		case 'address' :

			/* Get the post meta. */
			$address1	= esc_html( get_post_meta( $post_id, '_wpcrm_contact-address1', true ) );
			$address2	= esc_html( get_post_meta( $post_id, '_wpcrm_contact-address2', true ) );
			$city		= esc_html( get_post_meta( $post_id, '_wpcrm_contact-city', true ) );
			$state		= esc_html( get_post_meta( $post_id, '_wpcrm_contact-state', true ) );
			$postal		= esc_html( get_post_meta( $post_id, '_wpcrm_contact-postal', true ) );

			$address1	= !empty( $address1 ) ? $address1 : '';
			$address2	= !empty( $address2 ) ? ' ' . $address2 : '';
			$city		= !empty( $city ) ? $city . ', ' : '';
			$state		= !empty( $state ) ? $state . ' ' : '';
			$postal		= !empty( $postal ) ? $postal : '';

			/* If no duration is found, output a default message. */
			if ( empty( $address1 ) && empty( $address2 ) && empty( $city ) && empty( $state ) && empty( $postal ) )
				echo __( 'Not Set', 'wp-crm-system' );

			/* If there is an address field set, display it. */
			else
				echo esc_html( $address1 ) . esc_html( $address2 ) . '<br />';
				echo esc_html( $city ) . esc_html( $state ) . esc_html( $postal );

			break;
		/* If displaying the 'category' column */
		case 'category':
			$categories = get_the_terms( $post_id, 'contact-type' );
			if ( !empty ( $categories ) ){
				sort( $categories );
				foreach ( $categories as $category ){
					echo '<a href="' . esc_url( admin_url( 'edit.php?contact-type=' . $category->slug . '&post_type="wpcrm-contact"', 'admin' ) ) . '">' . esc_html( $category->name ) . '</a><br />';
				}
			}
			break;
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

add_filter( 'manage_edit-wpcrm-contact_sortable_columns', 'wpcrm_system_contact_sortable_columns' );

function wpcrm_system_contact_sortable_columns( $columns ) {

	$columns['org']		= 'org';
	$columns['phone']	= 'phone';
	$columns['mobile']	= 'mobile';
	$columns['email']	= 'email';

	return $columns;
}

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'wpcrm_system_contact_edit_load' );

function wpcrm_system_contact_edit_load() {
	add_filter( 'request', 'wpcrm_system_sort_contact_columns' );
}

/* Sorts the contact. */
function wpcrm_system_sort_contact_columns( $vars ) {

	/* Check if we're viewing the 'wpcrm-task' post type. */
	if ( isset( $vars['post_type'] ) && 'wpcrm-contact' == $vars['post_type'] ) {

		/* Check if 'orderby' is set. */
		if ( isset( $vars['orderby'] ) ) {
			switch ( $vars['orderby'] ){
				case 'org':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_contact-attach-to-organization',
							'orderby' => 'meta_value'
						)
					);
					break;
				case 'phone':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_contact-phone',
							'orderby' => 'meta_value'
						)
					);
					break;
				case 'mobile':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_contact-mobile-phone',
							'orderby' => 'meta_value'
						)
					);
					break;
				case 'email':
					/* Merge the query vars with our custom variables. */
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => '_wpcrm_contact-email',
							'orderby' => 'meta_value'
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

/* Start filter contacts by organization */
add_action( 'restrict_manage_posts', 'wpcrm_system_contact_filter_by_organization' );

function wpcrm_system_contact_filter_by_organization(){
	global $pagenow;
    $type = '';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }
    if ( 'wpcrm-contact' == $type && is_admin() && $pagenow=='edit.php' ) {
		global $wpdb;
		$custom_post_type = 'wpcrm-organization';
		$allowed = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s", $custom_post_type ), ARRAY_A );
		//$allowed = get_posts(array('posts_per_page'=>-1,'post_type' => 'wpcrm-organization'));
		$values = array();
		foreach ($allowed as $index => $post) {
			$values[] = $post['ID'];
		}
		if ($values) {
		?>
			<select name="wpcrm_system_filter_contact_by_org">
				<option value=""><?php _e('Filter By Organization', 'wp-crm-system'); ?></option>
				<?php
				$current_v = isset($_GET['wpcrm_system_filter_contact_by_org'])? $_GET['wpcrm_system_filter_contact_by_org']:'';
				foreach ($values as $value) {
					printf (
						'<option value="%s"%s>%s</option>',
						$value,
						$value == $current_v? ' selected="selected"':'',
						get_the_title( $value )
					);
				}
				?>
			</select>
		<?php
		}
    }
}

add_filter( 'parse_query', 'wpcrm_system_contacts_orgs_filter' );

function wpcrm_system_contacts_orgs_filter( $query ){
    global $pagenow;
    $type = '';
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }
    if ( 'wpcrm-contact' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['wpcrm_system_filter_contact_by_org']) && $_GET['wpcrm_system_filter_contact_by_org'] != '') {
        $query->query_vars['meta_key'] = '_wpcrm_contact-attach-to-organization';
        $query->query_vars['meta_value'] = $_GET['wpcrm_system_filter_contact_by_org'];
    }
}
/* End filter contacts by organization */