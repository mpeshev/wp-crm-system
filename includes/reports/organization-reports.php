<?php
/**
 * Displays options for reporting on organizations.
 *
 * Lets users generate dynamic reports based on input.
 *
 * @since 1.0.0
 * @package wp-crm-system
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;
require WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php';

?>
<div class="wrap">
	<div>
		<h2><?php esc_attr_e( 'Organization Reports', 'wp-crm-system' ); ?></h2>
		<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
			<tbody>
				<?php wp_crm_system_show_organization_form(); ?>
				<?php
				if ( ! empty( $_POST ) && check_admin_referer( 'check_organization_report_nonce', 'organization_report_nonce' ) ) {
					wp_crm_system_process_organization_form();
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<?php
/**
 * Shows the organization reporting form.
 *
 * Lets the user select various options to report on organizations dynamically.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */
function wp_crm_system_show_organization_form() {
	?>
	<form method="post">
		<?php wp_nonce_field( 'check_organization_report_nonce', 'organization_report_nonce' ); ?>
		<div class="wp-crm-first wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/city.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/state.php'; ?></div>
		<div class="wp-crm-one-fourth"><?php require plugin_dir_path( __FILE__ ) . '/options/country.php'; ?></div>
		<div class="wp-crm-first wp-crm-one-half"><br /><input type="submit" name="submit" value="Submit" class="button button-primary"><br /><br /></div>
	</form>
	<?php
}

/**
 * Processes the organization reporting form.
 *
 * Handles the processing of the organization reporting form and shows output.
 *
 * @since 2.5.4
 * @package wp-crm-system
 */
function wp_crm_system_process_organization_form() {

	$prefix = '_wpcrm_';

	if ( isset( $_POST['submit'], $_POST['organization_report_nonce'] )
	&& wp_verify_nonce( sanitize_key( $_POST['organization_report_nonce'] ), 'check_organization_report_nonce' ) ) {

		foreach ( $_POST as $param_name => $param_val ) {
			if ( 'wp-crm-system-city' === $param_name ) {
				$city    = esc_html( $param_val );
				$cit_arr = '';
				if ( 'all' === $city ) {

				} elseif ( '' === $city ) {
					$cit_arr = array(
						'key'     => $prefix . 'organization-city',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					);
				} else {
					$cit_arr = array(
						'key'     => $prefix . 'organization-city',
						'value'   => $city,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-state' === $param_name ) {
				$state   = esc_html( $param_val );
				$sta_arr = '';
				if ( 'all' === $state ) {

				} elseif ( '' === $state ) {
					$sta_arr = array(
						'key'     => $prefix . 'organization-state',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					);
				} else {
					$sta_arr = array(
						'key'     => $prefix . 'organization-state',
						'value'   => $state,
						'compare' => '=',
					);
				}
			} elseif ( 'wp-crm-system-country' === $param_name ) {
				$country = esc_html( $param_val );
				$cou_arr = '';
				if ( 'all' === $country ) {

				} elseif ( '' === $country ) {
					$cou_arr = array(
						'key'     => $prefix . 'organization-country',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					);
				} else {
					$cou_arr = array(
						'key'     => $prefix . 'organization-country',
						'value'   => $country,
						'compare' => '=',
					);
				}
			}
		}

		$organization_report = '';

		$args = array(
			'post_type'      => 'wpcrm-organization',
			'posts_per_page' => -1,
			'meta_query'     => array(
				'relation' => 'AND',
				$cit_arr,
				$sta_arr,
				$cou_arr,
			),
		);

		$wpcposts = get_posts( $args );

		if ( $wpcposts ) {
			$organization_report .= '<tr><th><strong>' . esc_attr_x( 'Organization', 'wp-crm-system' ) . '</strong></th>';
			$organization_report .= '<th><strong>' . esc_attr_x( 'City', 'wp-crm-system' ) . '</strong></th>';
			$organization_report .= '<th><strong>' . esc_attr_x( 'State', 'wp-crm-system' ) . '</strong></th>';
			$organization_report .= '<th><strong>' . esc_attr_x( 'Country', 'wp-crm-system' ) . '</strong></th>';
			foreach ( $wpcposts as $wpcpost ) {

				$organization_report .= '<tr><td>';

				$organization_report .= '<a href="' . esc_url( get_edit_post_link( $wpcpost->ID ) ) . '">' . esc_html( get_the_title( $wpcpost->ID ) ) . '</a>';

				$city_output          = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'organization-city', true ) );
				$organization_report .= '</td><td>' . $city_output;

				$state_output         = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'organization-state', true ) );
				$organization_report .= '</td><td>' . $state_output;

				$country_output       = esc_html( get_post_meta( $wpcpost->ID, $prefix . 'organization-country', true ) );
				$organization_report .= '</td><td>' . $country_output;

				$organization_report .= '</td></tr>';
			}
		} else {
			$organization_report .= '<tr><th><strong>Organization</strong></th><tr><td>' . esc_attr_x( 'No organizations to report.', 'wp-crm-system' ) . '</td></tr>';
		}

		print $organization_report;
	}
}
