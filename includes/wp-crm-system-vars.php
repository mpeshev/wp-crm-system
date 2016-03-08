<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 
$prefix = '_wpcrm_';
$postTypes = array( 'wpcrm-contact', 'wpcrm-task', 'wpcrm-organization', 'wpcrm-opportunity', 'wpcrm-project', 'wpcrm-campaign' );
if (!defined('WPCRM_USER_ACCESS')){
	define( 'WPCRM_USER_ACCESS', 'manage_wp_crm' );
}
if (!defined('WPCRM_BASE_STORE_URL')){
	define( 'WPCRM_BASE_STORE_URL', 'http://wp-crm.com' );
}
if (!defined('WPCRM_BASE_PLUGIN_PATH')){
	define( 'WPCRM_BASE_PLUGIN_PATH', dirname(dirname( __FILE__ )) );
}
/**
* Set titles for fields in wpCRMSystemCustomFields
*/
if (!defined('WPCRM_ACTIVE')){
	define( 'WPCRM_ACTIVE', __('Active', 'wp-crm-system') );
}
if (!defined('WPCRM_ACTUAL_COST')){
	define( 'WPCRM_ACTUAL_COST', __('Actual Cost', 'wp-crm-system') );
}
if (!defined('WPCRM_ADDITIONAL')){
	define( 'WPCRM_ADDITIONAL', __('Additional Information', 'wp-crm-system') );
}
if (!defined('WPCRM_ADDRESS_1')){
	define( 'WPCRM_ADDRESS_1', __('Address 1', 'wp-crm-system') );
}
if (!defined('WPCRM_ADDRESS_2')){
	define( 'WPCRM_ADDRESS_2', __('Address 2', 'wp-crm-system') );
}
if (!defined('WPCRM_ASSIGNED')){
	define( 'WPCRM_ASSIGNED', __('Assigned To', 'wp-crm-system') );
}
if (!defined('WPCRM_ATTACH_CAMPAIGN')){
	define( 'WPCRM_ATTACH_CAMPAIGN', __('Attach to Campaign', 'wp-crm-system') );
}
if (!defined('WPCRM_ATTACH_CONTACT')){
	define( 'WPCRM_ATTACH_CONTACT', __('Attach to Contact', 'wp-crm-system') );
}
if (!defined('WPCRM_ATTACH_ORG')){
	define( 'WPCRM_ATTACH_ORG', __('Attach to Organization', 'wp-crm-system') );
}
if (!defined('WPCRM_ATTACH_PROJECT')){
	define( 'WPCRM_ATTACH_PROJECT', __('Attach to Project', 'wp-crm-system') );
}
if (!defined('WPCRM_BUDGETED_COST')){
	define( 'WPCRM_BUDGETED_COST', __('Budgeted Cost', 'wp-crm-system') );
}
if (!defined('WPCRM_CITY')){
	define( 'WPCRM_CITY', __('City', 'wp-crm-system') );
}
if (!defined('WPCRM_CLOSE_DATE')){
	define( 'WPCRM_CLOSE_DATE', __('Close Date', 'wp-crm-system') );
}
if (!defined('WPCRM_COUNTRY')){
	define( 'WPCRM_COUNTRY', __('Country', 'wp-crm-system') );
}
if (!defined('WPCRM_DESCRIPTION')){
	define( 'WPCRM_DESCRIPTION', __('Description', 'wp-crm-system') );
}
if (!defined('WPCRM_DUE')){
	define( 'WPCRM_DUE', __('Due Date', 'wp-crm-system') );
}
if (!defined('WPCRM_EMAIL')){
	define( 'WPCRM_EMAIL', __('Email', 'wp-crm-system') );
}
if (!defined('WPCRM_END')){
	define( 'WPCRM_END', __('End Date', 'wp-crm-system') );
}
if (!defined('WPCRM_FAX')){
	define( 'WPCRM_FAX', __('Fax', 'wp-crm-system') );
}
if (!defined('WPCRM_FIRST_NAME')){
	define( 'WPCRM_FIRST_NAME', __('First Name', 'wp-crm-system') );
}
if (!defined('WPCRM_FORECASTED_CLOSE')){
	define( 'WPCRM_FORECASTED_CLOSE', __('Forecasted Close Date', 'wp-crm-system') );
}
if (!defined('WPCRM_INACTIVE')){
	define( 'WPCRM_INACTIVE', __('Inactive', 'wp-crm-system') );
}
if (!defined('WPCRM_LAST_NAME')){
	define( 'WPCRM_LAST_NAME', __('Last Name', 'wp-crm-system') );
}
if (!defined('WPCRM_LOCATION')){
	define( 'WPCRM_LOCATION', __('Location', 'wp-crm-system') );
}
if (!defined('WPCRM_MOBILE_PHONE')){
	define( 'WPCRM_MOBILE_PHONE', __('Mobile Phone', 'wp-crm-system') );
}
if (!defined('WPCRM_NAME_PREFIX')){
	define( 'WPCRM_NAME_PREFIX', __('Name Prefix', 'wp-crm-system') );
}
if (!defined('WPCRM_OPPORTUNITIES')){
	define( 'WPCRM_OPPORTUNITIES', __('Number of Opportunities Created', 'wp-crm-system') );
}
if (!defined('WPCRM_ORGANIZATION')){
	define( 'WPCRM_ORGANIZATION', __('Organization', 'wp-crm-system') );
}
if (!defined('WPCRM_PHONE')){
	define( 'WPCRM_PHONE', __('Phone', 'wp-crm-system') );
}
if (!defined('WPCRM_POSTAL')){
	define( 'WPCRM_POSTAL', __('Postal Code', 'wp-crm-system') );
}
if (!defined('WPCRM_PRIORITY')){
	define( 'WPCRM_PRIORITY', __('Priority', 'wp-crm-system') );
}
if (!defined('WPCRM_PROBABILITY')){
	define( 'WPCRM_PROBABILITY', __('Probability of Winning', 'wp-crm-system') );
}
if (!defined('WPCRM_PROGRESS')){
	define( 'WPCRM_PROGRESS', __('Progress', 'wp-crm-system') );
}
if (!defined('WPCRM_REACH')){
	define( 'WPCRM_REACH', __('Projected Reach', 'wp-crm-system') );
}
if (!defined('WPCRM_RESPONSES')){
	define( 'WPCRM_RESPONSES', __('Total Responses', 'wp-crm-system') );
}
if (!defined('WPCRM_ROI')){
	define( 'WPCRM_ROI', __('Return on Investment', 'wp-crm-system') );
}
if (!defined('WPCRM_ROLE')){
	define( 'WPCRM_ROLE', __('Role', 'wp-crm-system') );
}
if (!defined('WPCRM_START')){
	define( 'WPCRM_START', __('Start Date', 'wp-crm-system') );
}
if (!defined('WPCRM_STATE')){
	define( 'WPCRM_STATE', __('State/Province', 'wp-crm-system') );
}
if (!defined('WPCRM_STATUS')){
	define( 'WPCRM_STATUS', __('Status', 'wp-crm-system') );
}
if (!defined('WPCRM_VALUE')){
	define( 'WPCRM_VALUE', __('Value', 'wp-crm-system') );
}
if (!defined('WPCRM_VALUE_OPPS')){
	define( 'WPCRM_VALUE_OPPS', __('Value of Opportunities Created', 'wp-crm-system') );
}
if (!defined('WPCRM_VALUE_WON_OPPS')){
	define( 'WPCRM_VALUE_WON_OPPS', __('Value of Opportunities Won', 'wp-crm-system') );
}
if (!defined('WPCRM_WEBSITE')){
	define( 'WPCRM_WEBSITE', __('Website', 'wp-crm-system') );
}
if (!defined('WPCRM_WON_LOST')){
	define( 'WPCRM_WON_LOST', __('Won/Lost', 'wp-crm-system') );
}
?>