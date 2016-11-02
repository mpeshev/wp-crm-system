<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	global $wpdb;
	include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php');
	$active_report = isset( $_GET[ 'report' ] ) ? $_GET[ 'report' ] : '';

	switch ($active_report) {
		case 'contacts_organization':
			$meta_key1 = $prefix . 'contact-attach-to-organization';
			$organization_report = '';
			$organizations = '';
			$report_title = __('Contacts by Organization', 'wp-crm-system');
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-organization');
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
				);
				$posts = get_posts($args);
				if ($posts) {
					foreach($posts as $post) {
						$organizations .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$organizations = '';
				}
				if ($organizations == '') {
					$organization_report .= '';
				} else {
					$organization_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $organizations . '</td></tr>';
				}
				$organizations = '';//reset contacts for next organization
			endwhile;
			if ($organization_report == '') {
				$organization_report = '<tr><td>' . __('No contacts are linked to organizations. Please add or edit a contact and link it to an organization for this report to show.', 'wp-crm-system');
			}
			break;

		case 'type_orgnaizations':
			$organization_report = '';
			$report_title = __('Organizations by Type', 'wp-crm-system');

			$taxonomies = array('organization-type');
			$args = array('hide_empty'=>0);
			$terms = get_terms($taxonomies, $args);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				foreach ( $terms as $term ) {
					$args = array(
						'post_type'			=>	'wpcrm-organization',
						'posts_per_page'	=> -1,
						'organization-type'			=> $term->slug,
					);
					$posts = get_posts( $args );
					if ($posts) {
						$organization_report .= '<tr><td><strong>' . $term->name . '</strong></td><td>';
						foreach($posts as $post) {
							$organization_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
						}
						$organization_report .= '</td></tr>';
					} else {
						$organization_report .= '<tr><td>' . __('No organizations to report.', 'wp-crm-system') . '</td></tr>';
					}
				 }
			}
			if ($organization_report == '') {
				$organization_report = '<tr><td>' . __('No organizations to report.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		case 'task_organizations':
			$meta_key1 = $prefix . 'task-attach-to-organization';
			$organization_report = '';
			$tasks = '';
			$report_title = __('Organizations Associated With Tasks', 'wp-crm-system');
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-organization');
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$meta_key1_value = get_the_ID();
				$meta_key1_display = get_the_title();
				$args = array(
					'post_type'		=>	'wpcrm-task',
					'meta_query'	=> array(
						array(
							'key'		=>	$meta_key1,
							'value'		=>	$meta_key1_value,
							'compare'	=>	'=',
						),
					),
				);
				$posts = get_posts($args);
				if ($posts) {
					foreach($posts as $post) {
						$organizations .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$organizations = '';
				}
				if ($organizations == '') {
					$organization_report .= '';
				} else {
					$organization_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $organizations . '</td></tr>';
				}
				$organizations = '';//reset tasks for next organization
			endwhile;
			if ($organization_report == '') {
				$organization_report = '<tr><td>' . __('No tasks are linked to an organization. Please add or edit a task and link it to a organization for this report to show.', 'wp-crm-system');
			}
			break;
		case 'project_organizations':
			$meta_key1 = $prefix . 'project-attach-to-organization';
			$organization_report = '';
			$tasks = '';
			$report_title = __('Projects Attached to Organizations', 'wp-crm-system');
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-organization');
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) : $loop->the_post();
				$meta_key1_value = get_the_ID();
				$meta_key1_display = get_the_title();
				$args = array(
					'post_type'		=>	'wpcrm-project',
					'meta_query'	=> array(
						array(
							'key'		=>	$meta_key1,
							'value'		=>	$meta_key1_value,
							'compare'	=>	'=',
						),
					),
				);
				$posts = get_posts($args);
				if ($posts) {
					foreach($posts as $post) {
						$organizations .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$organizations = '';
				}
				if ($organizations == '') {
					$organization_report .= '';
				} else {
					$organization_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $organizations . '</td></tr>';
				}
				$organizations = '';//reset projects for next organization
			endwhile;
			if ($organization_report == '') {
				$organization_report = '<tr><td>' . __('No projects are linked to an organization. Please add or edit a project and link it to a organization for this report to show.', 'wp-crm-system');
			}
			break;
		default:
			$reports = array('contacts_organization'=>'Contacts by Organization','task_organizations'=>'Organizations Associated With Tasks','type_orgnaizations'=>'Organizations by Type','project_organizations'=>'Projects Attached to Organizations');
			$organization_report = '';
			$report_title = 'Organization Reports';
			foreach ($reports as $key => $value) {
				$organization_report .= '<tr><td><a href="?page=wpcrm-reports&tab=organization&report=' . $key . '">' . $value . '</a></td></tr>';
			}
	}
?>
	<div class="wrap">
		<div>
			<h2><?php echo $report_title; ?></h2>
			<?php if ($active_report == ('' || 'overview')) { ?><a href="?page=wpcrm-reports&tab=organization"><?php _e('Back to Organization Reports', 'wp-crm-system'); ?></a><?php } ?>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<?php echo $organization_report; ?>
				</tbody>
			</table>
		</div>
	</div>
