<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WPCRM_System_Export{
	/**
	 * Our export type. Used for export-type specific filters/actions
	 * @var string
	 * @since 2.1.8
	 */
	public $export_type = 'default';

	/**
	 * Can we export?
	 *
	 * @access public
	 * @since 2.1.8
	 * @return bool Whether we can export or not
	 */
	public function can_export() {
		return (bool) apply_filters( 'wpcrmsystem_export_capability', current_user_can( wpcrm_system_get_required_user_role() ) );
	}

	public function get_cpt_post_ids(){
		$posts = get_posts(
			array(
				'posts_per_page' 	=> -1,
				'post_type'			=> $this->export_type
			)
		);
		$ids = array();
		foreach ( $posts as $post ){
			$ids[] = $post->ID;
		}
		return $ids;
	}

	/**
	 * Set the export headers
	 *
	 * @access public
	 * @since 2.1.8
	 * @return void
	 */
	public function headers() {
		ignore_user_abort( true );

		set_time_limit( 0 );

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=wpcrm-system-export-' . $this->export_type . '-' . date( 'm-d-Y' ) . '.csv' );
		header( "Expires: 0" );
	}

	/**
	 * Set the CSV columns
	 *
	 * @access public
	 * @since 2.1.8
	 * @return array $cols All the columns
	 */
	public function csv_cols() {
		$cols = array(
			'id'   => __( 'ID',   'wp-crm-system' ),
			'date' => __( 'Date', 'wp-crm-system' )
		);
		return $cols;
	}

	/**
	 * Retrieve the CSV columns
	 *
	 * @access public
	 * @since 2.1.8
	 * @return array $cols Array of the columns
	 */
	public function get_csv_cols() {
		$cols = $this->csv_cols();
		return apply_filters( 'wpcrm_system_export_csv_cols_' . $this->export_type, $cols );
	}

	/**
	 * Output the CSV columns
	 *
	 * @access public
	 * @since 2.1.8
	 * @uses WPCRM_System_Export::get_csv_cols()
	 * @return void
	 */
	public function csv_cols_out() {
		$cols = $this->get_csv_cols();
		$i = 1;
		foreach( $cols as $col_id => $column ) {
			echo '"' . addslashes( $column ) . '"';
			echo $i == count( $cols ) ? '' : ',';
			$i++;
		}
		echo "\r\n";
	}

	/**
	 * Get the data being exported
	 *
	 * @access public
	 * @since 2.1.8
	 * @return array $data Data for Export
	 */
	public function get_data() {
		// Just a sample data array
		$data = array(
			0 => array(
				'id'   => '',
				'data' => date( 'F j, Y' )
			),
			1 => array(
				'id'   => '',
				'data' => date( 'F j, Y' )
			)
		);

		$data = apply_filters( 'wpcrm_system_export_get_data', $data );
		$data = apply_filters( 'wpcrm_system_export_get_data_' . $this->export_type, $data );

		return $data;
	}

	/**
	 * Output the CSV rows
	 *
	 * @access public
	 * @since 2.1.8
	 * @return void
	 */
	public function csv_rows_out() {
		$data = $this->get_data();

		$cols = $this->get_csv_cols();

		// Output each row
		foreach ( $data as $row ) {
			$i = 1;
			foreach ( $row as $col_id => $column ) {
				// Make sure the column is valid
				if ( array_key_exists( $col_id, $cols ) ) {
					echo '"' . esc_html( addslashes( $column ) ) . '"';
					echo $i == count( $cols ) ? '' : ',';
					$i++;
				}
			}
			echo "\r\n";
		}
	}

	/**
	 * Perform the export
	 *
	 * @access public
	 * @since 2.1.8
	 * @uses WPCRM_System_Export::can_export()
	 * @uses WPCRM_System_Export::headers()
	 * @uses WPCRM_System_Export::csv_cols_out()
	 * @uses WPCRM_System_Export::csv_rows_out()
	 * @return void
	 */
	public function export() {
		if ( ! $this->can_export() )
			wp_die( __( 'You do not have permission to export data.', 'wp-crm-system' ), __( 'Error', 'wp-crm-system' ), array( 'response' => 403 ) );

		// Set headers
		$this->headers();

		// Output CSV columns (headers)
		$this->csv_cols_out();

		// Output CSV rows
		$this->csv_rows_out();

		exit();
	}

}