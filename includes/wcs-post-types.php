<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}
/**
* Register post types and taxonomies.
* Taxonomies used to be added in init as the post types were.
* This caused a problem when exporting as the taxonomy was not created yet and would return an invalid taxonomy error.
* 'plugins_loaded' loads earlier than init, and allows the export to fire without issue.
*/
add_action( 'init', 'wpcrm_contacts_init' );
add_action( 'plugins_loaded', 'wpcrm_contact_taxonomy');
add_action( 'init', 'wpcrm_tasks_init' );
add_action( 'plugins_loaded', 'wpcrm_task_taxonomy');
add_action( 'init', 'wpcrm_organizations_init' );
add_action( 'plugins_loaded', 'wpcrm_organization_taxonomy');
add_action( 'init', 'wpcrm_opportunities_init' );
add_action( 'plugins_loaded', 'wpcrm_opportunity_taxonomy');
add_action( 'init', 'wpcrm_projects_init' );
add_action( 'plugins_loaded', 'wpcrm_project_taxonomy');
add_action( 'init', 'wpcrm_campaign_init' );
add_action( 'plugins_loaded', 'wpcrm_campaign_taxonomy');

/**
* Adjust capabilities as necessary
*/
add_action( 'admin_init', 'wpcrm_add_role_caps', 999 );
function wpcrm_add_role_caps() {
	if( isset( $_POST[ 'wpcrm_system_settings_update' ] ) ) {
		$post_types = array( 'wpcrm-contact','wpcrm-task','wpcrm-organization','wpcrm-opportunity','wpcrm-project','wpcrm-campaign' );

		$roles = array(
			'subscriber',
			'contributor',
			'author',
			'editor',
			'administrator'
		);

		$wpcrm_system_roles = apply_filters( 'wpcrm_system_default_user_roles', $roles );
		foreach($post_types as $post_type) {
			// Loop through each role and assign capabilities
			foreach($wpcrm_system_roles as $the_role) {
				$role = get_role($the_role);
				// Need to check if the role has get_option('wpcrm_system_select_user_role'); capability then add_cap if it does.
				if( $role->has_cap( get_option( 'wpcrm_system_select_user_role' ) ) ) {
					$role->add_cap( 'edit_'.$post_type );
					$role->add_cap( 'read_'.$post_type );
					$role->add_cap( 'delete_'.$post_type );
					$role->add_cap( 'edit_'.$post_type.'s' );
					$role->add_cap( 'edit_others_'.$post_type.'s' );
					$role->add_cap( 'publish_'.$post_type.'s' );
					$role->add_cap( 'read_private_'.$post_type.'s' );
					$role->add_cap( 'read_'.$post_type );
					$role->add_cap( 'delete_'.$post_type.'s' );
					$role->add_cap( 'delete_private_'.$post_type.'s' );
					$role->add_cap( 'delete_published_'.$post_type.'s' );
					$role->add_cap( 'delete_others_'.$post_type.'s' );
					$role->add_cap( 'edit_private_'.$post_type.'s' );
					$role->add_cap( 'edit_published_'.$post_type.'s' );
					$role->add_cap( 'create_'.$post_type.'s' );
					$role->add_cap( 'manage_wp_crm' );
				} else {
					// Remove the capabilities if the role isn't supposed to edit the CPT. Allows for admin to change to a higher role if too much access was previously given.
					$role->remove_cap( 'edit_'.$post_type );
					$role->remove_cap( 'read_'.$post_type );
					$role->remove_cap( 'delete_'.$post_type );
					$role->remove_cap( 'edit_'.$post_type.'s' );
					$role->remove_cap( 'edit_others_'.$post_type.'s' );
					$role->remove_cap( 'publish_'.$post_type.'s' );
					$role->remove_cap( 'read_private_'.$post_type.'s' );
					$role->remove_cap( 'read_'.$post_type );
					$role->remove_cap( 'delete_'.$post_type.'s' );
					$role->remove_cap( 'delete_private_'.$post_type.'s' );
					$role->remove_cap( 'delete_published_'.$post_type.'s' );
					$role->remove_cap( 'delete_others_'.$post_type.'s' );
					$role->remove_cap( 'edit_private_'.$post_type.'s' );
					$role->remove_cap( 'edit_published_'.$post_type.'s' );
					$role->remove_cap( 'create_'.$post_type.'s' );
					$role->remove_cap( 'manage_wp_crm' );
				}
			}
		}
	}
}

/* Contacts post type. */
function wpcrm_contacts_init() {
	$post_type = 'wpcrm-contact';
	$labels = array(
		'name'               => __( 'Contacts', 'wp-crm-system' ),
		'singular_name'      => __( 'Contact', 'wp-crm-system' ),
		'menu_name'          => __( 'Contacts', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Contact', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Contact', 'wp-crm-system' ),
		'new_item'           => __( 'New Contact', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Contact', 'wp-crm-system' ),
		'view_item'          => __( 'View Contact', 'wp-crm-system' ),
		'all_items'          => __( 'Contacts', 'wp-crm-system' ),
		'search_items'       => __( 'Search Contacts', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Contact:', 'wp-crm-system' ),
		'not_found'          => __( 'No contacts found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No contacts found in Trash.', 'wp-crm-system' )
	);
	$labels = apply_filters( 'wpcrm_system_post_type_labels_' . $post_type, $labels );
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Contacts', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $post_type ),
		'capabilities' => array(
			'edit_post' => 'edit_'.$post_type,
			'read_post' => 'read_'.$post_type,
			'delete_post' => 'delete_'.$post_type,
			'edit_posts' => 'edit_'.$post_type.'s',
			'edit_others_posts' => 'edit_others_'.$post_type.'s',
			'publish_posts' => 'publish_'.$post_type.'s',
			'read_private_posts' => 'read_private_'.$post_type.'s',
			'read' => 'read_'.$post_type,
			'delete_posts' => 'delete_'.$post_type.'s',
			'delete_private_posts' => 'delete_private_'.$post_type.'s',
			'delete_published_posts' => 'delete_published_'.$post_type.'s',
			'delete_others_posts' => 'delete_others_'.$post_type.'s',
			'edit_private_posts' => 'edit_private_'.$post_type.'s',
			'edit_published_posts' => 'edit_published_'.$post_type.'s',
			'create_posts' => 'create_'.$post_type.'s',
		),
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array( 'contact-type' ),
		'supports'           => array( 'author', 'thumbnail', 'custom-fields', 'comments' )
	);
	$args = apply_filters( 'wpcrm_system_post_type_args_' . $post_type, $args );
	register_post_type( $post_type, $args );
}
function wpcrm_contact_taxonomy() {
	$post_type = 'wpcrm-contact';
	$labels = array(
		'name'              => __( 'Contact Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Contact Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Contact Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Contact Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Contact Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Contact Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Contact Types', 'wp-crm-system' ),
	);
	$labels = apply_filters( 'wpcrm_system_taxonomy_labels_' . $post_type, $labels );
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => false,
	);
	$args = apply_filters( 'wpcrm_system_taxonomy_args_' . $post_type, $args );
	register_taxonomy( 'contact-type', $post_type, $args );
}
/* Tasks post type. */
function wpcrm_tasks_init() {
	$post_type = 'wpcrm-task';
	$labels = array(
		'name'               => __( 'Tasks', 'wp-crm-system' ),
		'singular_name'      => __( 'Task', 'wp-crm-system' ),
		'menu_name'          => __( 'Tasks', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Task', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Task', 'wp-crm-system' ),
		'new_item'           => __( 'New Task', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Task', 'wp-crm-system' ),
		'view_item'          => __( 'View Task', 'wp-crm-system' ),
		'all_items'          => __( 'Tasks', 'wp-crm-system' ),
		'search_items'       => __( 'Search Tasks', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Task:', 'wp-crm-system' ),
		'not_found'          => __( 'No tasks found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No tasks found in Trash.', 'wp-crm-system' )
	);
	$labels = apply_filters( 'wpcrm_system_post_type_labels_' . $post_type, $labels );
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Tasks', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $post_type ),
		'capabilities' => array(
			'edit_post' => 'edit_'.$post_type,
			'read_post' => 'read_'.$post_type,
			'delete_post' => 'delete_'.$post_type,
			'edit_posts' => 'edit_'.$post_type.'s',
			'edit_others_posts' => 'edit_others_'.$post_type.'s',
			'publish_posts' => 'publish_'.$post_type.'s',
			'read_private_posts' => 'read_private_'.$post_type.'s',
			'read' => 'read_'.$post_type,
			'delete_posts' => 'delete_'.$post_type.'s',
			'delete_private_posts' => 'delete_private_'.$post_type.'s',
			'delete_published_posts' => 'delete_published_'.$post_type.'s',
			'delete_others_posts' => 'delete_others_'.$post_type.'s',
			'edit_private_posts' => 'edit_private_'.$post_type.'s',
			'edit_published_posts' => 'edit_published_'.$post_type.'s',
			'create_posts' => 'create_'.$post_type.'s',
		),
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('task-type'),
		'supports'           => array( 'title', 'author', 'thumbnail', 'custom-fields', 'comments' )
	);
	$args = apply_filters( 'wpcrm_system_post_type_args_' . $post_type, $args );
	register_post_type( $post_type, $args );
}
function wpcrm_task_taxonomy() {
	$post_type = 'wpcrm-task';
	$labels = array(
		'name'              => __( 'Task Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Task Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Task Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Task Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Task Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Task Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Task Types', 'wp-crm-system' ),
	);
	$labels = apply_filters( 'wpcrm_system_taxonomy_labels_' . $post_type, $labels );
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => false,
	);
	$args = apply_filters( 'wpcrm_system_taxonomy_args_' . $post_type, $args );
	register_taxonomy( 'task-type', $post_type, $args );
}
/* Organizations post type. */
function wpcrm_organizations_init() {
	$post_type = 'wpcrm-organization';
	$labels = array(
		'name'               => __( 'Organizations', 'wp-crm-system' ),
		'singular_name'      => __( 'Organization', 'wp-crm-system' ),
		'menu_name'          => __( 'Organizations', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Organization', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Organization', 'wp-crm-system' ),
		'new_item'           => __( 'New Organization', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Organization', 'wp-crm-system' ),
		'view_item'          => __( 'View Organization', 'wp-crm-system' ),
		'all_items'          => __( 'Organizations', 'wp-crm-system' ),
		'search_items'       => __( 'Search Organizations', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Organization:', 'wp-crm-system' ),
		'not_found'          => __( 'No organizations found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No organizations found in Trash.', 'wp-crm-system' )
	);
	$labels = apply_filters( 'wpcrm_system_post_type_labels_' . $post_type, $labels );
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Organizations', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $post_type ),
		'capabilities' => array(
			'edit_post' => 'edit_'.$post_type,
			'read_post' => 'read_'.$post_type,
			'delete_post' => 'delete_'.$post_type,
			'edit_posts' => 'edit_'.$post_type.'s',
			'edit_others_posts' => 'edit_others_'.$post_type.'s',
			'publish_posts' => 'publish_'.$post_type.'s',
			'read_private_posts' => 'read_private_'.$post_type.'s',
			'read' => 'read_'.$post_type,
			'delete_posts' => 'delete_'.$post_type.'s',
			'delete_private_posts' => 'delete_private_'.$post_type.'s',
			'delete_published_posts' => 'delete_published_'.$post_type.'s',
			'delete_others_posts' => 'delete_others_'.$post_type.'s',
			'edit_private_posts' => 'edit_private_'.$post_type.'s',
			'edit_published_posts' => 'edit_published_'.$post_type.'s',
			'create_posts' => 'create_'.$post_type.'s',
		),
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('organization-type'),
		'supports'           => array( 'title', 'author', 'thumbnail', 'custom-fields', 'comments' )
	);
	$args = apply_filters( 'wpcrm_system_post_type_args_' . $post_type, $args );
	register_post_type( $post_type, $args );
}
function wpcrm_organization_taxonomy() {
	$post_type = 'wpcrm-organization';
	$labels = array(
		'name'              => __( 'Organization Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Organization Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Organization Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Organization Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Organization Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Organization Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Organization Types', 'wp-crm-system' ),
	);
	$labels = apply_filters( 'wpcrm_system_taxonomy_labels_' . $post_type, $labels );
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => false,
	);
	$args = apply_filters( 'wpcrm_system_taxonomy_args_' . $post_type, $args );
	register_taxonomy( 'organization-type', $post_type, $args );
}
/* Opportunities post type. */
function wpcrm_opportunities_init() {
	$post_type = 'wpcrm-opportunity';
	$labels = array(
		'name'               => __( 'Opportunities', 'wp-crm-system' ),
		'singular_name'      => __( 'Opportunity', 'wp-crm-system' ),
		'menu_name'          => __( 'Opportunities', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Opportunity', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Opportunity', 'wp-crm-system' ),
		'new_item'           => __( 'New Opportunity', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Opportunity', 'wp-crm-system' ),
		'view_item'          => __( 'View Opportunity', 'wp-crm-system' ),
		'all_items'          => __( 'Opportunities', 'wp-crm-system' ),
		'search_items'       => __( 'Search Opportunities', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Opportunity:', 'wp-crm-system' ),
		'not_found'          => __( 'No opportunities found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No opportunities found in Trash.', 'wp-crm-system' )
	);
	$labels = apply_filters( 'wpcrm_system_post_type_labels_' . $post_type, $labels );
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Opportunities', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $post_type ),
		'capabilities' => array(
			'edit_post' => 'edit_'.$post_type,
			'read_post' => 'read_'.$post_type,
			'delete_post' => 'delete_'.$post_type,
			'edit_posts' => 'edit_'.$post_type.'s',
			'edit_others_posts' => 'edit_others_'.$post_type.'s',
			'publish_posts' => 'publish_'.$post_type.'s',
			'read_private_posts' => 'read_private_'.$post_type.'s',
			'read' => 'read_'.$post_type,
			'delete_posts' => 'delete_'.$post_type.'s',
			'delete_private_posts' => 'delete_private_'.$post_type.'s',
			'delete_published_posts' => 'delete_published_'.$post_type.'s',
			'delete_others_posts' => 'delete_others_'.$post_type.'s',
			'edit_private_posts' => 'edit_private_'.$post_type.'s',
			'edit_published_posts' => 'edit_published_'.$post_type.'s',
			'create_posts' => 'create_'.$post_type.'s',
		),
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('opportunity-type'),
		'supports'           => array( 'title', 'author', 'thumbnail', 'custom-fields', 'comments' )
	);
	$args = apply_filters( 'wpcrm_system_post_type_args_' . $post_type, $args );
	register_post_type( $post_type, $args );
}
function wpcrm_opportunity_taxonomy() {
	$post_type = 'wpcrm-opportunity';
	$labels = array(
		'name'              => __( 'Opportunity Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Opportunity Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Opportunity Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Opportunity Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Opportunity Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Opportunity Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Opportunity Types', 'wp-crm-system' ),
	);
	$labels = apply_filters( 'wpcrm_system_taxonomy_labels_' . $post_type, $labels );
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => false,
	);
	$args = apply_filters( 'wpcrm_system_taxonomy_args_' . $post_type, $args );
	register_taxonomy( 'opportunity-type', $post_type, $args );
}
/* Projects post type. */
function wpcrm_projects_init() {
	$post_type = 'wpcrm-project';
	$labels = array(
		'name'               => __( 'Projects', 'wp-crm-system' ),
		'singular_name'      => __( 'Project', 'wp-crm-system' ),
		'menu_name'          => __( 'Projects', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Project', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Project', 'wp-crm-system' ),
		'new_item'           => __( 'New Project', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Project', 'wp-crm-system' ),
		'view_item'          => __( 'View Project', 'wp-crm-system' ),
		'all_items'          => __( 'Projects', 'wp-crm-system' ),
		'search_items'       => __( 'Search Projects', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Project:', 'wp-crm-system' ),
		'not_found'          => __( 'No projects found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No projects found in Trash.', 'wp-crm-system' )
	);
	$labels = apply_filters( 'wpcrm_system_post_type_labels_' . $post_type, $labels );
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Projects', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $post_type ),
		'capabilities' => array(
			'edit_post' => 'edit_'.$post_type,
			'read_post' => 'read_'.$post_type,
			'delete_post' => 'delete_'.$post_type,
			'edit_posts' => 'edit_'.$post_type.'s',
			'edit_others_posts' => 'edit_others_'.$post_type.'s',
			'publish_posts' => 'publish_'.$post_type.'s',
			'read_private_posts' => 'read_private_'.$post_type.'s',
			'read' => 'read_'.$post_type,
			'delete_posts' => 'delete_'.$post_type.'s',
			'delete_private_posts' => 'delete_private_'.$post_type.'s',
			'delete_published_posts' => 'delete_published_'.$post_type.'s',
			'delete_others_posts' => 'delete_others_'.$post_type.'s',
			'edit_private_posts' => 'edit_private_'.$post_type.'s',
			'edit_published_posts' => 'edit_published_'.$post_type.'s',
			'create_posts' => 'create_'.$post_type.'s',
		),
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('project-type'),
		'supports'           => array( 'title', 'author', 'thumbnail', 'custom-fields', 'comments' )
	);
	$args = apply_filters( 'wpcrm_system_post_type_args_' . $post_type, $args );
	register_post_type( $post_type, $args );
}
function wpcrm_project_taxonomy() {
	$post_type = 'wpcrm-project';
	$labels = array(
		'name'              => __( 'Project Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Project Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Project Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Project Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Project Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Project Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Project Types', 'wp-crm-system' ),
	);
	$labels = apply_filters( 'wpcrm_system_taxonomy_labels_' . $post_type, $labels );
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => false,
	);
	$args = apply_filters( 'wpcrm_system_taxonomy_args_' . $post_type, $args );
	register_taxonomy( 'project-type', $post_type, $args );
}
/* Campaign post type. */
function wpcrm_campaign_init() {
	$post_type = 'wpcrm-campaign';
	$labels = array(
		'name'               => __( 'Campaigns', 'wp-crm-system' ),
		'singular_name'      => __( 'Campaign', 'wp-crm-system' ),
		'menu_name'          => __( 'Campaigns', 'wp-crm-system' ),
		'name_admin_bar'     => __( 'Campaign', 'wp-crm-system' ),
		'add_new'            => __( 'Add New', 'wp-crm-system' ),
		'add_new_item'       => __( 'Add New Campaign', 'wp-crm-system' ),
		'new_item'           => __( 'New Campaign', 'wp-crm-system' ),
		'edit_item'          => __( 'Edit Campaign', 'wp-crm-system' ),
		'view_item'          => __( 'View Campaign', 'wp-crm-system' ),
		'all_items'          => __( 'Campaigns', 'wp-crm-system' ),
		'search_items'       => __( 'Search Campaigns', 'wp-crm-system' ),
		'parent_item_colon'  => __( 'Parent Campaign:', 'wp-crm-system' ),
		'not_found'          => __( 'No campaigns found.', 'wp-crm-system' ),
		'not_found_in_trash' => __( 'No campaigns found in Trash.', 'wp-crm-system' )
	);
	$labels = apply_filters( 'wpcrm_system_post_type_labels_' . $post_type, $labels );
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Campaigns', 'wp-crm-system' ),
		'public'             => false,
		'exclude_from_search'=> true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => 'wpcrm',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => $post_type ),
		'capabilities' => array(
			'edit_post' => 'edit_'.$post_type,
			'read_post' => 'read_'.$post_type,
			'delete_post' => 'delete_'.$post_type,
			'edit_posts' => 'edit_'.$post_type.'s',
			'edit_others_posts' => 'edit_others_'.$post_type.'s',
			'publish_posts' => 'publish_'.$post_type.'s',
			'read_private_posts' => 'read_private_'.$post_type.'s',
			'read' => 'read_'.$post_type,
			'delete_posts' => 'delete_'.$post_type.'s',
			'delete_private_posts' => 'delete_private_'.$post_type.'s',
			'delete_published_posts' => 'delete_published_'.$post_type.'s',
			'delete_others_posts' => 'delete_others_'.$post_type.'s',
			'edit_private_posts' => 'edit_private_'.$post_type.'s',
			'edit_published_posts' => 'edit_published_'.$post_type.'s',
			'create_posts' => 'create_'.$post_type.'s',
		),
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'taxonomies'		 => array('campaign-type'),
		'supports'           => array( 'title', 'author', 'thumbnail', 'custom-fields', 'comments' )
	);
	$args = apply_filters( 'wpcrm_system_post_type_args_' . $post_type, $args );
	register_post_type( $post_type, $args );
}
function wpcrm_campaign_taxonomy() {
	$post_type = 'wpcrm-campaign';
	$labels = array(
		'name'              => __( 'Campaign Types', 'wp-crm-system' ),
		'singular_name'		=> __( 'Campaign Type', 'wp-crm-system' ),
		'edit_item'         => __( 'Edit Campaign Type', 'wp-crm-system' ),
		'update_item'       => __( 'Update Campaign Type', 'wp-crm-system' ),
		'add_new_item'      => __( 'Add New Campaign Type', 'wp-crm-system' ),
		'new_item_name'     => __( 'New Campaign Type', 'wp-crm-system' ),
		'menu_name'         => __( 'Campaign Types', 'wp-crm-system' ),
	);
	$labels = apply_filters( 'wpcrm_system_taxonomy_labels_' . $post_type, $labels );
	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => false,
	);
	$args = apply_filters( 'wpcrm_system_taxonomy_args_' . $post_type, $args );
	register_taxonomy( 'campaign-type', $post_type, $args );
}
