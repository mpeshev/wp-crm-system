<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}
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
<div class="wrap">
	<h2><?php _e( 'Edit Recurring Entry', 'wp-crm-system' ); ?> - <a href="admin.php?page=wpcrm-settings&tab=recurring&subtab=recurring-entries" class="button-secondary"><?php _e( 'Cancel - Go Back', 'wp-crm-system' ); ?></a></h2>

	<form method="post" action="" class="wp_crm_system_recurring_entry_form">
		<?php
		$entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpcrm_system_recurring_db_name . " WHERE id='%d';", $_GET['entry_id'] ) );
		$start_date	= strtotime( $entry->start_date );
		$end_date	= strtotime( $entry->end_date );
		if ( isset( $start_date ) && is_numeric( $start_date ) && 0 != $start_date ) {
			$start_date = date( get_option( 'wpcrm_system_php_date_format' ), $start_date );
		} else {
			$start_date = '';
		}
		if ( isset( $end_date ) && is_numeric( $end_date ) && 0 != $end_date ) {
			$end_date = date( get_option( 'wpcrm_system_php_date_format' ), $end_date );
		} else {
			$end_date = '';
		}
		?>
		<p>
			<label class="description" for="project_task_id"><?php _e( 'Select the project or task to be recurring', 'wp-crm-system' ); ?></label>
			<?php echo wp_crm_system_get_post_type_list( $entry->project_task_id, array( 'wpcrm-project', 'wpcrm-task' ), 'project_task_id' ); ?><br/>
		</p>
		<p>
			<label class="description" for="start_date"><?php _e( 'Select the date this entry should start recurring', 'wp-crm-system' ); ?></label>
			<input type="text" name="start_date" id="start_date" class="datepicker" value="<?php echo $start_date; ?>" />
		</p>
		<p>
			<label class="description" for="end_date"><?php _e( 'Select the date this entry should stop recurring', 'wp-crm-system' ); ?></label>
			<input type="text" name="end_date" id="end_date" class="datepicker" value="<?php echo $end_date; ?>" />
		</p>
		<p>
			<label class="description" for="frequency"><?php _e( 'Repeat every', 'wp-crm-system' ); ?></label>
			<select name="number">
			<?php
			$max_number = apply_filters( 'wp_crm_system_max_recurring_entry', 365 );
			for( $a = 1; $a <= $max_number; $a++ ){ ?>
				<option value="<?php echo $a; ?>" <?php selected( $a, $entry->number_per_frequency ); ?>><?php echo $a; ?></option>
			<?php } ?>
			</select>
			<select name="frequency">
				<option value="day" <?php selected( 'day', $entry->frequency ); ?>><?php _e( 'Day(s)', 'wp-crm-system' ); ?></option>
				<option value="week" <?php selected( 'week', $entry->frequency ); ?>><?php _e( 'Week(s)', 'wp-crm-system' ); ?></option>
				<option value="month" <?php selected( 'month', $entry->frequency ); ?>><?php _e( 'Month(s)', 'wp-crm-system' ); ?></option>
				<option value="year" <?php selected( 'year', $entry->frequency ); ?>><?php _e( 'Year(s)', 'wp-crm-system' ); ?></option>
			</select>
		</p>
		<p>
			<hr/><br/>
			<input type="hidden" name="edit_entry" value="<?php echo $entry->id; ?>"/>
			<input type="hidden" name="wp_crm_system_recurring_entry_nonce" value="<?php echo wp_create_nonce('wp-crm-system-recurring-entry-nonce'); ?>"/>
			<input type="submit" class="button-primary" value="<?php _e( 'Save', 'wp-crm-system' ); ?>" />
		</p>
	</form>

</div>