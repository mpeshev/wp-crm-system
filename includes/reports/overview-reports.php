<?php defined( 'ABSPATH' ) OR exit;

function wpcrm_system_opportunity_value_overview_report() {
  global $wpdb;
  include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php' ); ?>
  <tr>
    <td>
      <strong><?php _e('Value of Opportunities', 'wp-crm-system'); ?></strong>
    </td>
    <td>
      <?php
      $opp_val = $prefix . 'opportunity-value';
      $organization_value = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s", $opp_val));
      echo wpcrm_system_display_currency_symbol(get_option('wpcrm_system_default_currency')) . ' ' . number_format(array_sum( $organization_value ),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator'));
      $wpdb->flush(); ?>
    </td>
  </tr>
<?php }
add_action( 'wpcrm_system_overview_reports', 'wpcrm_system_opportunity_value_overview_report', 1 );

function wpcrm_system_projects_value_overview_report() {
  global $wpdb;
  include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php' ); ?>
  <tr>
    <td>
      <strong><?php _e('Value of Projects', 'wp-crm-system'); ?></strong>
    </td>
    <td>
      <?php
      $project_val = $prefix . 'project-value';
      $proj_value = $wpdb->get_col($wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = %s", $project_val));
      echo wpcrm_system_display_currency_symbol(get_option('wpcrm_system_default_currency')) . ' ' . number_format(array_sum( $proj_value ),get_option('wpcrm_system_report_currency_decimals'),get_option('wpcrm_system_report_currency_decimal_point'),get_option('wpcrm_system_report_currency_thousand_separator'));
      $wpdb->flush(); ?>
    </td>
  </tr>
<?php }
add_action( 'wpcrm_system_overview_reports', 'wpcrm_system_projects_value_overview_report', 2 );

function wpcrm_system_overdue_tasks_overview_report() {
  global $wpdb;
  include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php' ); ?>
  <tr>
    <td>
      <strong><?php _e('Overdue Tasks', 'wp-crm-system'); ?></strong>
    </td>
    <td>
      <?php
      $meta_key1 = $prefix . 'task-status';
      $meta_key1_value = 'complete';
      $meta_key2 = $prefix . 'task-due-date';
      global $post;
      $args = array(
        'post_type'		=>	'wpcrm-task',
        'meta_query'	=> array(
          'relation'		=>	'AND',
          array(
            'key'		=>	$meta_key1,
            'value'		=>	$meta_key1_value,
            'compare'	=>	'!=',
          ),
          array(
            'key'		=>	$meta_key2,
            'value'		=>	strtotime('now'),
            'compare'	=>	'<',
          ),
        ),
      );
      $posts = get_posts($args);
      if ($posts) {
        $i = 0;
        foreach($posts as $post) {
          $i++;
        }
        $overdue_report = 'admin.php?page=wpcrm-reports&tab=task&report=overdue_tasks';
        printf( _n('There is %d overdue task. ', 'There are %d overdue tasks. ', $i, 'wp-crm-system'), $i);
      } else {
        _e('No tasks are overdue!', 'wp-crm-system');
      }
      ?>
    </td>
  </tr>
<?php }
add_action( 'wpcrm_system_overview_reports', 'wpcrm_system_overdue_tasks_overview_report', 3 );
