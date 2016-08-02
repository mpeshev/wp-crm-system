<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$prefix = '_wpcrm_';
$postTypes = array( 'wpcrm-contact', 'wpcrm-task', 'wpcrm-organization', 'wpcrm-opportunity', 'wpcrm-project', 'wpcrm-campaign' );
if ( !defined( 'WPCRM_USER_ACCESS' ) ){
	define( 'WPCRM_USER_ACCESS', 'manage_wp_crm' );
}
if ( !defined( 'WPCRM_BASE_STORE_URL' ) ){
	define( 'WPCRM_BASE_STORE_URL', 'http://wp-crm.com' );
}
if ( !defined( 'WPCRM_BASE_PLUGIN_PATH' ) ){
	define( 'WPCRM_BASE_PLUGIN_PATH', dirname(dirname( __FILE__ ) ) );
}
/**
* Set titles for fields in wpCRMSystemCustomFields
*/
if ( !defined( 'WPCRM_ACTIVE' ) ){
	define( 'WPCRM_ACTIVE', __( 'Active', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ACTUAL_COST' ) ){
	define( 'WPCRM_ACTUAL_COST', __( 'Actual Cost', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ADDITIONAL' ) ){
	define( 'WPCRM_ADDITIONAL', __( 'Additional Information', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ADDRESS_1' ) ){
	define( 'WPCRM_ADDRESS_1', __( 'Address 1', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ADDRESS_2' ) ){
	define( 'WPCRM_ADDRESS_2', __( 'Address 2', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ASSIGNED' ) ){
	define( 'WPCRM_ASSIGNED', __( 'Assigned To', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ATTACH_CAMPAIGN' ) ){
	define( 'WPCRM_ATTACH_CAMPAIGN', __( 'Attach to Campaign', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ATTACH_CONTACT' ) ){
	define( 'WPCRM_ATTACH_CONTACT', __( 'Attach to Contact', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ATTACH_ORG' ) ){
	define( 'WPCRM_ATTACH_ORG', __( 'Attach to Organization', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ATTACH_PROJECT' ) ){
	define( 'WPCRM_ATTACH_PROJECT', __( 'Attach to Project', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_BUDGETED_COST' ) ){
	define( 'WPCRM_BUDGETED_COST', __( 'Budgeted Cost', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_CITY' ) ){
	define( 'WPCRM_CITY', __( 'City', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_CLOSE_DATE' ) ){
	define( 'WPCRM_CLOSE_DATE', __( 'Close Date', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_COUNTRY' ) ){
	define( 'WPCRM_COUNTRY', __( 'Country', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_CREATE_CAMPAIGN' ) ){
	define( 'WPCRM_CREATE_CAMPAIGN', __( 'Create New Campaign', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_CREATE_CONTACT' ) ){
	define( 'WPCRM_CREATE_CONTACT', __( 'Create New Contact', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_CREATE_ORGANIZATION' ) ){
	define( 'WPCRM_CREATE_ORGANIZATION', __( 'Create New Organization', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_CREATE_PROJECT' ) ){
	define( 'WPCRM_CREATE_PROJECT', __( 'Create New Project', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_DESCRIPTION' ) ){
	define( 'WPCRM_DESCRIPTION', __( 'Description', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_DROPBOX' ) ){
	define( 'WPCRM_DROPBOX', __( 'Link Files From Dropbox', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_DUE' ) ){
	define( 'WPCRM_DUE', __( 'Due Date', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EMAIL' ) ){
	define( 'WPCRM_EMAIL', __( 'Email', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_END' ) ){
	define( 'WPCRM_END', __( 'End Date', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_CONTACT_USER' ) ){
	define( 'WPCRM_EXTENSION_CONTACT_USER', __( 'Contact From User', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_CUSTOM_FIELDS' ) ){
	define( 'WPCRM_EXTENSION_CUSTOM_FIELDS', __( 'Custom Fields', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DROPBOX' ) ){
	define( 'WPCRM_EXTENSION_DROPBOX', __( 'Dropbox Connect', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_EMAIL_NOTIFICATIONS' ) ){
	define( 'WPCRM_EXTENSION_EMAIL_NOTIFICATIONS', __( 'Email Notifications', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_GRAVITY_FORMS' ) ){
	define( 'WPCRM_EXTENSION_GRAVITY_FORMS', __( 'Gravity Forms Connect', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_IMPORT_CAMPAIGNS' ) ){
	define( 'WPCRM_EXTENSION_IMPORT_CAMPAIGNS', __( 'Import Campaigns', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_IMPORT_CONTACTS' ) ){
	define( 'WPCRM_EXTENSION_IMPORT_CONTACTS', __( 'Import Contacts', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_IMPORT_OPPORTUNITIES' ) ){
	define( 'WPCRM_EXTENSION_IMPORT_OPPORTUNITIES', __( 'Import Opportunities', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_IMPORT_ORGANIZATIONS' ) ){
	define( 'WPCRM_EXTENSION_IMPORT_ORGANIZATIONS', __( 'Import Organizations', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_IMPORT_PROJECTS' ) ){
	define( 'WPCRM_EXTENSION_IMPORT_PROJECTS', __( 'Import Projects', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_IMPORT_TASKS' ) ){
	define( 'WPCRM_EXTENSION_IMPORT_TASKS', __( 'Import Tasks', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_INVOICING' ) ){
	define( 'WPCRM_EXTENSION_INVOICING', __( 'Invoicing', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_MAILCHIMP_SYNC' ) ){
	define( 'WPCRM_EXTENSION_MAILCHIMP_SYNC', __( 'MailChimp Sync', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_NINJA_FORMS' ) ){
	define( 'WPCRM_EXTENSION_NINJA_FORMS', __( 'Ninja Forms Connect', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_SLACK' ) ){
	define( 'WPCRM_EXTENSION_SLACK', __( 'Slack Notifications', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_ZENDESK' ) ){
	define( 'WPCRM_EXTENSION_ZENDESK', __( 'Zendesk Connect', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_CONTACT_USER' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_CONTACT_USER', __( 'Quickly create new contacts in WP-CRM System from existing users on your WordPress site.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_CUSTOM_FIELDS' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_CUSTOM_FIELDS', __( 'Collect custom information for records in WP-CRM System.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_DROPBOX' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_DROPBOX', __( 'Add documents from your Dropbox account to any record in WP-CRM System.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_EMAIL_NOTIFICATIONS' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_EMAIL_NOTIFICATIONS', __( 'Send notifications from WP-CRM System to the assigned user\'s email address.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_GRAVITY_FORMS' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_GRAVITY_FORMS', __( 'Automatically create new records in WP-CRM System from Gravity Form submissions.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_CAMPAIGNS' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_CAMPAIGNS', __( 'Import your campaigns from another CRM with an easy to use CSV importer.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_CONTACTS' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_CONTACTS', __( 'Import your contacts from another CRM with an easy to use CSV importer.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_OPPORTUNITIES' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_OPPORTUNITIES', __( 'Import your opportunities from another CRM with an easy to use CSV importer.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_ORGANIZATIONS' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_ORGANIZATIONS', __( 'Import your organizations from another CRM with an easy to use CSV importer.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_PROJECTS' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_PROJECTS', __( 'Import your projects from another CRM with an easy to use CSV importer.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_TASKS' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_IMPORT_TASKS', __( 'Import your tasks from another CRM with an easy to use CSV importer.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_INVOICING' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_INVOICING', __( 'Send invoices and accept payments directly through WP-CRM System.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_MAILCHIMP_SYNC' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_MAILCHIMP_SYNC', __( 'Add WP-CRM System contacts to your MailChimp list.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_NINJA_FORMS' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_NINJA_FORMS', __( 'Automatically create new contacts from Ninja Form submissions.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_SLACK' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_SLACK', __( 'Send notifications from WP-CRM System to a Slack channel.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_EXTENSION_DESCRIPTION_ZENDESK' ) ){
	define( 'WPCRM_EXTENSION_DESCRIPTION_ZENDESK', __( 'Get up to date Zendesk ticket information from your WP-CRM System contacts.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_FAX' ) ){
	define( 'WPCRM_FAX', __( 'Fax', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_FIRST_NAME' ) ){
	define( 'WPCRM_FIRST_NAME', __( 'First Name', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_FORECASTED_CLOSE' ) ){
	define( 'WPCRM_FORECASTED_CLOSE', __( 'Forecasted Close Date', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_INACTIVE' ) ){
	define( 'WPCRM_INACTIVE', __( 'Inactive', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_LAST_NAME' ) ){
	define( 'WPCRM_LAST_NAME', __( 'Last Name', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_LOCATION' ) ){
	define( 'WPCRM_LOCATION', __( 'Location', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_MOBILE_PHONE' ) ){
	define( 'WPCRM_MOBILE_PHONE', __( 'Mobile Phone', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_NAME_PREFIX' ) ){
	define( 'WPCRM_NAME_PREFIX', __( 'Name Prefix', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_OPPORTUNITIES' ) ){
	define( 'WPCRM_OPPORTUNITIES', __( 'Number of Opportunities Created', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ORGANIZATION' ) ){
	define( 'WPCRM_ORGANIZATION', __( 'Organization', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_PHONE' ) ){
	define( 'WPCRM_PHONE', __( 'Phone', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_POSTAL' ) ){
	define( 'WPCRM_POSTAL', __( 'Postal Code', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_PRIORITY' ) ){
	define( 'WPCRM_PRIORITY', __( 'Priority', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_PROBABILITY' ) ){
	define( 'WPCRM_PROBABILITY', __( 'Probability of Winning', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_PROGRESS' ) ){
	define( 'WPCRM_PROGRESS', __( 'Progress', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_REACH' ) ){
	define( 'WPCRM_REACH', __( 'Projected Reach', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_RESPONSES' ) ){
	define( 'WPCRM_RESPONSES', __( 'Total Responses', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ROI' ) ){
	define( 'WPCRM_ROI', __( 'Return on Investment', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ROLE' ) ){
	define( 'WPCRM_ROLE', __( 'Role', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_SAVE_CHANGES' ) ){
	define( 'WPCRM_SAVE_CHANGES', __( 'Save changes before clicking below.', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_START' ) ){
	define( 'WPCRM_START', __( 'Start Date', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_STATE' ) ){
	define( 'WPCRM_STATE', __( 'State/Province', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_STATUS' ) ){
	define( 'WPCRM_STATUS', __( 'Status', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_VALUE' ) ){
	define( 'WPCRM_VALUE', __( 'Value', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_VALUE_OPPS' ) ){
	define( 'WPCRM_VALUE_OPPS', __( 'Value of Opportunities Created', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_VALUE_WON_OPPS' ) ){
	define( 'WPCRM_VALUE_WON_OPPS', __( 'Value of Opportunities Won', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_WEBSITE' ) ){
	define( 'WPCRM_WEBSITE', __( 'Website', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_WON_LOST' ) ){
	define( 'WPCRM_WON_LOST', __( 'Won/Lost', 'wp-crm-system' ) );
}
if ( !defined( 'WPCRM_ZENDESK_TICKETS' ) ){
	define( 'WPCRM_ZENDESK_TICKETS', __( 'Zendesk Tickets', 'wp-crm-system' ) );
}
$wpcrm_currencies = array('aed'=>'AED','afn'=>'&#1547;','all'=>'&#76;&#101;&#107;','amd'=>'AMD','ang'=>'&#402;','aoa'=>'AOA','ars'=>'&#36;','aud'=>'&#36;','awg'=>'&#402;','azn'=>'&#1084;&#1072;&#1085;','bam'=>'&#75;&#77;','bbd'=>'&#36;','bdt'=>'BDT','bgn'=>'&#1083;&#1074;','bhd'=>'BHD','bif'=>'BIF','bmd'=>'&#36;','bnd'=>'&#36;','bob'=>'&#36;&#98;','brl'=>'&#82;&#36;','bsd'=>'&#36;','btn'=>'BTN','bwp'=>'&#80;','byr'=>'&#112;&#46;','bzd'=>'&#66;&#90;&#36;','cad'=>'&#36;','cdf'=>'CDF','chf'=>'&#67;&#72;&#70;','clp'=>'&#36;','cny'=>'&#165;','cop'=>'&#36;','crc'=>'&#8353;','cuc'=>'CUC','cup'=>'&#8369;','cve'=>'CVE','czk'=>'&#75;&#269;','djf'=>'DJF','dkk'=>'&#107;&#114;','dop'=>'&#82;&#68;&#36;','dzd'=>'DZD','egp'=>'&#163;','ern'=>'ERN','etb'=>'ETB','eur'=>'&#8364;','fjd'=>'&#36;','fkp'=>'&#163;','gbp'=>'&#163;','gel'=>'GEL','ggp'=>'&#163;','ghs'=>'&#162;','gip'=>'&#163;','gmd'=>'GMD','gnf'=>'GNF','gtq'=>'&#81;','gyd'=>'&#36;','hkd'=>'&#36;','hnl'=>'&#76;','hrk'=>'&#107;&#110;','htg'=>'HTG','huf'=>'&#70;&#116;','idr'=>'&#82;&#112;','ils'=>'&#8362;','imp'=>'&#163;','inr'=>'&#8377;','iqd'=>'IQD','irr'=>'&#65020;','isk'=>'&#107;&#114;','jep'=>'&#163;','jmd'=>'&#74;&#36;','jod'=>'JOD','jpy'=>'&#165;','kes'=>'KES','kgs'=>'&#1083;&#1074;','khr'=>'&#6107;','kmf'=>'KMF','kpw'=>'&#8361;','krw'=>'&#8361;','kwd'=>'KWD','kyd'=>'&#36;','kzt'=>'&#1083;&#1074;','lak'=>'&#8365;','lbp'=>'&#163;','lkr'=>'&#8360;','lrd'=>'&#36;','lsl'=>'LSL','lyd'=>'LYD','mad'=>'MAD','mdl'=>'MDL','mga'=>'MGA','mkd'=>'&#1076;&#1077;&#1085;','mmk'=>'MMK','mnt'=>'&#8366;','mop'=>'MOP','mro'=>'MRO','mur'=>'&#8360;','mvr'=>'MVR','mwk'=>'MWK','mxn'=>'&#36;','myr'=>'&#82;&#77;','mzn'=>'&#77;&#84;','nad'=>'&#36;','ngn'=>'&#8358;','nio'=>'&#67;&#36;','nok'=>'&#107;&#114;','npr'=>'&#8360;','nzd'=>'&#36;','omr'=>'&#65020;','pab'=>'&#66;&#47;&#46;','pen'=>'&#83;&#47;&#46;','pgk'=>'PGK','php'=>'&#8369;','pkr'=>'&#8360;','pln'=>'&#122;&#322;','prb'=>'PRB','pyg'=>'&#71;&#115;','qar'=>'&#65020;','ron'=>'&#108;&#101;&#105;','rsd'=>'&#1044;&#1080;&#1085;&#46;','rub'=>'&#1088;&#1091;&#1073;','rwf'=>'RWF','sar'=>'&#65020;','sbd'=>'&#36;','scr'=>'&#8360;','sdg'=>'SDG','sek'=>'&#107;&#114;','sgd'=>'&#36;','shp'=>'&#163;','sll'=>'SLL','sos'=>'&#83;','srd'=>'&#36;','ssp'=>'SSP','std'=>'STD','syp'=>'&#163;','szl'=>'SZL','thb'=>'&#3647;','tjs'=>'TJS','tmt'=>'TMT','tnd'=>'TND','top'=>'TOP','try'=>'&#8378;','ttd'=>'&#84;&#84;&#36;','twd'=>'&#78;&#84;&#36;','tzs'=>'TZS','uah'=>'&#8372;','ugx'=>'UGX','usd'=>'&#36;','uyu'=>'&#36;&#85;','uzs'=>'&#1083;&#1074;','vef'=>'&#66;&#115;','vnd'=>'&#8363;','vuv'=>'VUV','wst'=>'WST','xaf'=>'XAF','xcd'=>'&#36;','xof'=>'XOF','xpf'=>'XPF','yer'=>'&#65020;','zar'=>'&#82;','zmw'=>'ZMW');
?>
