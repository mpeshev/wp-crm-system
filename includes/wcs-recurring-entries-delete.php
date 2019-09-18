<?php
/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}
?>
<div class="wrap">
	<h2><?php _e( 'Delete Entry', 'wp-crm-system' ); ?> - <a href="admin.php?page=wpcrm-settings&tab=recurring&subtab=recurring-entries" class="button-secondary"><?php _e( 'Cancel - Go Back', 'wp-crm-system' ); ?></a></h2>
	<form method="post" action="" class="wp_crm_system_recurring_entries_form">
		<?php $entry = $wpdb->get_row("SELECT * FROM " . $wpcrm_system_recurring_db_name . " WHERE id='" . $_GET['entry_id'] . "';"); ?>
		<p><?php _e( 'Recurring entry deletion is permanent and cannot be undone. No further recurring entries will be created for this entry.', 'wp-crm-system' ); ?></p>
		<p><?php _e( 'Are you sure you wish to delete yes this entry?', 'wp-crm-system' ); ?></p>
		<p>
			<input type="hidden" name="wp_crm_system_recurring_entry_nonce" value="<?php echo wp_create_nonce('wp-crm-system-recurring-entry-nonce'); ?>"/>
			<input type="hidden" name="delete_entry" value="<?php echo $entry->id; ?>"/>
			<input type="submit" class="button-primary" value="<?php _e( 'Yes, Delete Entry', 'wp-crm-system' ); ?>" />
		</p>
	</form>
</div>