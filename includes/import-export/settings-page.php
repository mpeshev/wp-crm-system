<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_action( 'wpcrm_system_import_field', 'wp_crm_system_import_export_fields' );
function wp_crm_system_import_export_fields(){ ?>
	<div class="wrap">
		<div>
			<h2><?php _e( 'Import Campaigns From CSV', 'wp-crm-system' ); ?></h2>

			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<form id="wpcrm_import_campaigns" name="wpcrm_import_campaigns" method="post" action="" enctype="multipart/form-data">
						<tr>
							<td>
								<input type="file" name="import_campaigns" />
								<input type="hidden" name="wpcrm_system_import_campaigns_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-import-campaigns-nonce' ); ?>" />
								<input type="submit"/>
							</td>
							<td>
								<?php $url = 'https://www.wp-crm.com/wp-content/uploads/2016/03/campaigns.csv';
								$link = sprintf( wp_kses( __( 'Important: Please make sure your CSV file is in the <a href="%s">correct format</a>. If it is out of order, your fields will not be imported correctly. ', 'wp-crm-system' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
								echo $link; ?>
							</td>
						</tr>
					</form>
					<form id="wpcrm_export_campaigns" name="wpcrm_export_campaigns" method="post" action="">
						<tr>
							<td>
								<input type="hidden" name="wpcrm_system_export_campaigns_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-export-campaigns-nonce' ); ?>" />
								<input type="submit" name="export_campaigns" value="<?php _e( 'Export Campaigns', 'wp-crm-system' ); ?>" />
							</td>
							<td></td>
						</tr>
					</form>
				</tbody>
			</table>
		</div>
	</div>
	<div class="wrap">
		<div>
			<h2><?php _e( 'Import Contacts From CSV', 'wp-crm-system' ); ?></h2>

			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<form id="wpcrm_import_contacts" name="wpcrm_import_contacts" method="post" action="" enctype="multipart/form-data">
						<tr>
							<td>
								<input type="file" name="import_contacts" />
								<input type="hidden" name="wpcrm_system_import_contacts_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-import-contacts-nonce' ); ?>" />
								<input type="submit" name="wpcrm_system_import_contacts" />
							</td>
							<td>
								<?php $url = 'https://www.wp-crm.com/wp-content/uploads/2016/03/contacts.csv';
								$link = sprintf( wp_kses( __( 'Important: Please make sure your CSV file is in the <a href="%s">correct format</a>. If it is out of order, your fields will not be imported correctly. ', 'wp-crm-system' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
								echo $link; ?>
							</td>
						</tr>
					</form>
					<form id="wpcrm_export_contacts" name="wpcrm_export_contacts" method="post" action="">
						<tr>
							<td>
								<input type="hidden" name="wpcrm_system_export_contacts_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-export-contacts-nonce' ); ?>" />
								<input type="submit" name="wpcrm_system_export_contacts" value="<?php _e( 'Export Contacts', 'wp-crm-system' ); ?>" />
							</td>
							<td></td>
						</tr>
					</form>
				</tbody>
			</table>
		</div>
	</div>
	<div class="wrap">
		<div>
			<h2><?php _e( 'Import Opportunities From CSV', 'wp-crm-system-import-opportunities' ); ?></h2>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<form id="wpcrm_import_opportunities" name="wpcrm_import_opportunities" method="post" action="" enctype="multipart/form-data">
						<tr>
							<td>
								<input type="file" name="import_opportunities" />
								<input type="hidden" name="wpcrm_system_import_opportunities_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-import-opportunities-nonce' ); ?>" />
								<input type="submit"/>
							</td>
							<td>
								<?php $url = 'https://www.wp-crm.com/wp-content/uploads/2016/03/opportunities.csv';
								$link = sprintf( wp_kses( __( 'Important: Please make sure your CSV file is in the <a href="%s">correct format</a>. If it is out of order, your fields will not be imported correctly. ', 'wp-crm-system' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
								echo $link; ?>
							</td>
						</tr>
					</form>
					<form id="wpcrm_export_opportunities" name="wpcrm_export_opportunities" method="post" action="">
						<tr>
							<td>
							<input type="hidden" name="wpcrm_system_export_opportunities_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-export-opportunities-nonce' ); ?>" />
							<input type="submit" name="export_opportunities" value="<?php _e( 'Export Opportunities', 'wp-crm-system' ); ?>" /></td>
							<td></td>
						</tr>
					</form>
				</tbody>
			</table>
		</div>
	</div>
	<div class="wrap">
		<div>
			<h2><?php _e( 'Import Organizations From CSV', 'wp-crm-system' ); ?></h2>

			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<form id="wpcrm_import_organizations" name="wpcrm_import_organizations" method="post" action="" enctype="multipart/form-data">
						<tr>
							<td>
								<input type="file" name="import_organizations" />
								<input type="hidden" name="wpcrm_system_import_organizations_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-import-organizations-nonce' ); ?>" />
								<input type="submit"/>
							</td>
							<td>
								<?php $url = 'https://www.wp-crm.com/wp-content/uploads/2016/03/organizations.csv';
								$link = sprintf( wp_kses( __( 'Important: Please make sure your CSV file is in the <a href="%s">correct format</a>. If it is out of order, your fields will not be imported correctly. ', 'wp-crm-system' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
								echo $link; ?>
							</td>
						</tr>
					</form>
					<form id="wpcrm_export_organizations" name="wpcrm_export_organizations" method="post" action="">
						<tr>
							<td>
								<input type="hidden" name="wpcrm_system_export_organizations_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-export-organizations-nonce' ); ?>" />
								<input type="submit" name="export_organizations" value="<?php _e( 'Export Organizations', 'wp-crm-system' ); ?>" />
							</td>
							<td></td>
						</tr>
					</form>
				</tbody>
			</table>
		</div>
	</div>
	<div class="wrap">
		<div>
			<h2><?php _e( 'Import Projects From CSV', 'wp-crm-system' ); ?></h2>

			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<form id="wpcrm_import_projects" name="wpcrm_import_projects" method="post" action="" enctype="multipart/form-data">
						<tr>
							<td>
								<input type="file" name="import_projects" />
								<input type="hidden" name="wpcrm_system_import_projects_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-import-projects-nonce' ); ?>" />
								<input type="submit"/>
							</td>
							<td>
								<?php $url = 'https://www.wp-crm.com/wp-content/uploads/2016/03/project.csv';
								$link = sprintf( wp_kses( __( 'Important: Please make sure your CSV file is in the <a href="%s">correct format</a>. If it is out of order, your fields will not be imported correctly. ', 'wp-crm-system' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
								echo $link; ?>
							</td>
						</tr>
					</form>
					<form id="wpcrm_export_projects" name="wpcrm_export_projects" method="post" action="">
						<tr>
							<td>
								<input type="hidden" name="wpcrm_system_export_projects_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-export-projects-nonce' ); ?>" />
								<input type="submit" name="export_projects" value="<?php _e( 'Export Projects', 'wp-crm-system' ); ?>" />
							</td>
							<td></td>
						</tr>
					</form>
					</tbody>
				</table>
			</form>
		</div>
	</div>
	<div class="wrap">
		<div>
			<h2><?php _e( 'Import Tasks From CSV', 'wp-crm-system' ); ?></h2>
			<table class="wp-list-table widefat fixed posts" style="border-collapse: collapse;">
				<tbody>
					<form id="wpcrm_import_tasks" name="wpcrm_import_tasks" method="post" action="" enctype="multipart/form-data">
						<tr>
							<td>
								<input type="file" name="import_tasks" />
								<input type="hidden" name="wpcrm_system_import_tasks_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-import-tasks-nonce' ); ?>" />
								<input type="submit" />
							</td>
							<td>
								<?php $url = 'https://www.wp-crm.com/wp-content/uploads/2016/03/tasks.csv';
								$link = sprintf( wp_kses( __( 'Important: Please make sure your CSV file is in the <a href="%s">correct format</a>. If it is out of order, your fields will not be imported correctly. ', 'wp-crm-system' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
								echo $link; ?>
							</td>
						</tr>
					</form>
					<form id="wpcrm_export_tasks" name="wpcrm_export_tasks" method="post" action="">
						<tr>
							<td>
								<input type="hidden" name="wpcrm_system_export_tasks_nonce" value="<?php echo wp_create_nonce( 'wpcrm-system-export-tasks-nonce' ); ?>" />
								<input type="submit" name="export_tasks" value="<?php _e( 'Export Tasks', 'wp-crm-system' ); ?>" />
							</td>
							<td></td>
						</tr>
					</form>
				</tbody>
			</table>
		</div>
	</div>
<?php }