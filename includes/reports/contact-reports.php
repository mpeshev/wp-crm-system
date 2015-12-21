<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	global $wpdb;
	include(plugin_dir_path( dirname(dirname(__FILE__ ))) . 'includes/wp-crm-system-vars.php');
	$active_report = isset( $_GET[ 'report' ] ) ? $_GET[ 'report' ] : ''; 
	
	switch ($active_report) {
		case 'organization_contacts':
			$meta_key1 = $prefix . 'contact-attach-to-organization';
			$contact_report = '';
			$contacts = '';
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
						$contacts .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$contacts = '';
				}
				if ($contacts == '') {
					$contact_report .= '';
				} else {
					$contact_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $contacts . '</td></tr>';
				}
				$contacts = '';//reset contacts for next organization
			endwhile;
			if ($contact_report == '') {
				$contact_report = '<tr><td>' . __('No contacts are linked to organizations. Please add or edit a contact and link it to an organization for this report to show.', 'wp-crm-system');
			}
			break;
		
		case 'type_contacts':
			$contact_report = '';
			$report_title = __('Contacts by Type', 'wp-crm-system');
			
			$taxonomies = array('contact-type');
			$args = array('hide_empty'=>0);
			$terms = get_terms($taxonomies, $args);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				foreach ( $terms as $term ) {
					$args = array(
						'post_type'			=>	'wpcrm-contact',
						'posts_per_page'	=> -1,
						'contact-type'			=> $term->slug,
					);
					$posts = get_posts( $args );
					if ($posts) {
						$contact_report .= '<tr><td><strong>' . $term->name . '</strong></td><td>';
						foreach($posts as $post) {
							$contact_report .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
						}
						$contact_report .= '</td></tr>';
					} else {
						$contact_report .= '<tr><td>' . __('No contacts to report.', 'wp-crm-system') . '</td></tr>';
					}
				 }
			}
			if ($contact_report == '') {
				$contact_report = '<tr><td>' . __('No contacts to report.', 'wp-crm-system') . '</td></tr>';
			}
			break;
		case 'task_contacts':
			$meta_key1 = $prefix . 'task-attach-to-contact';
			$contact_report = '';
			$tasks = '';
			$report_title = __('Contacts Associated With Tasks', 'wp-crm-system');
			$args = array( 'posts_per_page'=>-1,'post_type' => 'wpcrm-contact');
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
						$contacts .= '<a href="' . get_edit_post_link($post->ID) . '">' . get_the_title($post->ID) . '</a><br />';
					}
				} else {
					$contacts = '';
				}
				if ($contacts == '') {
					$contact_report .= '';
				} else {
					$contact_report .= '<tr><td><strong>' . $meta_key1_display . '</strong></td><td>' . $contacts . '</td></tr>';
				}
				$contacts = '';//reset tasks for next contact
			endwhile;
			if ($contact_report == '') {
				$contact_report = '<tr><td>' . __('No tasks are linked to contacts. Please add or edit a task and link it to a contact for this report to show.', 'wp-crm-system');
			}
			break;
		default:
			$reports = array('organization_contacts'=>'Contacts by Organization','task_contacts'=>'Contacts Associated With Tasks','type_contacts'=>'Contacts by Type');
			$contact_report = '';
			$report_title = 'Contact Reports';
			foreach ($reports as $key => $value) {
				$contact_report .= '<tr><td><a href="?page=wpcrm-reports&tab=contact&report=' . $key . '">' . $value . '</a></td></tr>';
			}
	}
?>
	<div class="wrap">
		<div>
			<h2><?php echo $report_title; ?></h2>
			<?php if ($active_report == ('' || 'overview')) { ?><a href="?page=wpcrm-reports&tab=contact"><?php _e('Back to Contact Reports', 'wp-crm-system'); ?></a><?php } ?>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<?php echo $contact_report; ?>
				</tbody>
			</table>
		</div>
	</div>