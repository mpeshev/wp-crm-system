<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_action( 'plugins_loaded', 'wp_crm_system_export_opportunities_process' );
function wp_crm_system_export_opportunities_process(){
	if ( isset( $_POST[ 'wpcrm_system_export_opportunities_nonce' ] ) ) {
		if( wp_verify_nonce( $_POST[ 'wpcrm_system_export_opportunities_nonce' ], 'wpcrm-system-export-opportunities-nonce' ) ) {
			require_once WP_CRM_SYSTEM_PLUGIN_DIR_PATH . '/includes/class-export.php';

			$export = new WPCRM_System_Export_Opportunities();

			$export->export();
		}
	}
}
class WPCRM_System_Export_Opportunities extends WPCRM_System_Export{
	/**
	 * Our export type. Used for export-type specific filters/actions
	 * @var string
	 * @since 3.0
	 */
	public $export_type = 'wpcrm-opportunity';

	/**
	 * Set the CSV columns
	 *
	 * @access public
	 * @since 3.0
	 * @return array $cols All the columns
	 */
	public function csv_cols() {

		$cols = array(
			'opportunity_name'	=> __( 'Opportunity Name', 'wp-crm-system-import-contacts' ),
			'assigned' 			=> __( 'Assigned to', 'wp-crm-system-import-contacts' ),
			'organization'		=> __( 'Organization Name', 'wp-crm-system-import-contacts' ),
			'organization_id'	=> __( 'Organization ID', 'wp-crm-system-import-contacts' ),
			'contact'			=> __( 'Contact Name', 'wp-crm-system-import-contacts' ),
			'contact_id'		=> __( 'Contact ID', 'wp-crm-system-import-contacts' ),
			'campaign'			=> __( 'Campaign', 'wp-crm-system-import-contacts' ),
			'campaign_id'		=> __( 'Campaign ID', 'wp-crm-system-import-contacts' ),
			'description'		=> __( 'Description', 'wp-crm-system-import-contacts' ),
			'probability'		=> __( 'Probability', 'wp-crm-system-import-contacts' ),
			'closedate'			=> __( 'Close Date', 'wp-crm-system-import-contacts' ),
			'value'				=> __( 'Value', 'wp-crm-system-import-contacts' ),
			'wonlost'			=> __( 'Won/Lost', 'wp-crm-system-import-contacts' )
		);

		if( defined( 'WPCRM_CUSTOM_FIELDS' ) ){
			$field_count = get_option( '_wpcrm_system_custom_field_count' );
			if( $field_count ){
				$custom_fields = array();
				for( $field = 1; $field <= $field_count; $field++ ){
					// Make sure we want this field to be imported.
					$field_scope = get_option( '_wpcrm_custom_field_scope_' . $field );
					$can_export = $field_scope == $this->export_type ? true : false;
					if( $can_export ){
						$custom_fields[] = get_option( '_wpcrm_custom_field_name_' . $field );
					}
				}
				$cols = array_merge( $cols, $custom_fields );
			}
		}

		$cols = apply_filters( 'wpcrm_system_export_cols_' . $this->export_type, $cols );

		return $cols;
	}

	/**
	 * Get the Export Data
	 *
	 * @access public
	 * @since 3.0
	 * @return array $data The data for the CSV file
	 */
	public function get_data() {
		$get_ids = $this->get_cpt_post_ids();
		foreach ( $get_ids as $id ){
			$data[$id] = array(
				'opportunity_name' 	=> get_the_title( $id ),
				'assigned' 			=> get_post_meta( $id, '_wpcrm_opportunity-assigned', true ),
				'organization'		=> get_the_title( get_post_meta( $id, '_wpcrm_opportunity-attach-to-organization', true ) ),
				'organization_id'	=> get_post_meta( $id, '_wpcrm_opportunity-attach-to-organization', true ),
				'contact'			=> get_the_title( get_post_meta( $id, '_wpcrm_opportunity-attach-to-contact', true ) ),
				'contact_id'		=> get_post_meta( $id, '_wpcrm_opportunity-attach-to-contact', true ),
				'campaign'			=> get_the_title( get_post_meta( $id, '_wpcrm_opportunity-attach-to-campaign', true ) ),
				'campaign_id'		=> get_post_meta( $id, '_wpcrm_opportunity-attach-to-campaign', true ),
				'description'		=> esc_html( get_post_meta( $id, '_wpcrm_opportunity-description', true ) ),
				'probability'		=> get_post_meta( $id, '_wpcrm_opportunity-probability', true ),
				'closedate'			=> date( get_option( 'wpcrm_system_php_date_format' ), get_post_meta( $id, '_wpcrm_opportunity-closedate', true ) ),
				'value'				=> get_post_meta( $id, '_wpcrm_opportunity-value', true ),
				'wonlost'			=> get_post_meta( $id, '_wpcrm_opportunity-wonlost', true )
			);
			if( defined( 'WPCRM_CUSTOM_FIELDS' ) ){
				$field_count 	= get_option( '_wpcrm_system_custom_field_count' );
				if( $field_count ){
					for( $field = 1; $field <= $field_count; $field++ ){
						// Make sure we want this field to be imported.
						$field_scope 	= get_option( '_wpcrm_custom_field_scope_' . $field );
						$field_type		= get_option( '_wpcrm_custom_field_type_' . $field );
						$can_export 	= $field_scope == $this->export_type ? true : false;
						if( $can_export ){
							$value 	= get_post_meta( $id, '_wpcrm_custom_field_id_' . $field, true );
							switch ( $field_type ) {
								case 'datepicker':
									$export = date( get_option( 'wpcrm_system_php_date_format' ), $value );
									break;
								case 'repeater-date':
									if ( is_array( $value ) ){
										foreach ( $value as $key => $v ){
											$values[$key] = date( get_option( 'wpcrm_system_php_date_format' ), $v );
										}
										$export = implode( ',', $values );
									} else {
										$export = '';
									}
									break;
								case 'repeater-file':
								case 'repeater-text':
								case 'repeater-textarea':
									if ( is_array( $value ) ){
										$export = implode( ',', $value );
									} else {
										$export = '';
									}
									break;
								default:
									$export = $value;
									break;
							}
							$data[$id][] = $export;
						}
					}
				}
			}
		}

		$data = apply_filters( 'wpcrm_system_export_get_data', $data );
		$data = apply_filters( 'wpcrm_system_export_get_data_' . $this->export_type, $data );

		return $data;
	}

}