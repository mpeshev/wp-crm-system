<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<p><label for="project"><?php esc_attr_e( 'Project', 'wp-crm-system' ); ?></label></p>
<?php
$user = wp_get_current_user();
if ( $user->has_cap( get_option( 'wpcrm_system_select_user_role' ) ) ) {
	$args     = array(
		'post_type'      => 'wpcrm-project',
		'posts_per_page' => -1,
	);
	$wpcposts = get_posts( $args );
	if ( $wpcposts ) {
		?>
		<select id="project" name="wp-crm-system-project">
			<option value="all"><?php esc_attr_e( 'All', 'wp-crm-system' ); ?></option>
			<?php
			foreach ( $wpcposts as $wpcpost ) {
				echo '<option value="' . esc_attr( $wpcpost->ID ) . '">' . get_the_title( esc_attr( $wpcpost->ID ) ) . '</option>';
			}
			?>
		</select>
		<?php
	} else {
		esc_attr_e( 'No projects to display', 'wp-crm-system' );
	}
}
