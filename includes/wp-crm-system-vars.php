<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// Include Support for Depreciated File Structure
if( ! defined( 'WP_CRM_SYSTEM_PLUGIN_DIR' ) ) {
 	define( 'WP_CRM_SYSTEM_PLUGIN_DIR', dirname( dirname( __FILE__ ) ) );
}
include( WP_CRM_SYSTEM_PLUGIN_DIR . '/includes/wcs-vars.php' );
