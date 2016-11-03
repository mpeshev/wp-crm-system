=== WP-CRM System ===
Contributors: scott.deluzio
Tags:  WordPress CRM, wp crm, CRM, project management, marketing, customer management
Requires at least: 3.3.0
Tested up to: 4.6.1
Stable tag: 2.0.12
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP-CRM System is a fully featured CRM designed to work exclusively with WordPress.

== Description ==
WP-CRM System is a fully featured CRM that allows you to use the WordPress interface you are already familiar with to manage your customers, projects, and tasks with ease.

Why spend hundreds or thousands of dollars on a CRM that charges based on the number of records you have in your database, or the number of users that you have accessing your database? WP-CRM System allows you to have an unlimited number of records and lets you give everyone in your organization access to the data they need to drive business.

**More than a CRM**

WP-CRM System allows your team to manage projects, assign individual tasks, track marketing campaigns, and helps convert your opportunities into successes!

**Try the Demo!**

Not sure if WP-CRM System is right for you? [Try out the demo](http://demo.wp-crm.com) to see how it works.

**Premium Extensions Available**

There are a number of inexpensive extensions that will allow you to do more with WP-CRM System.

* [Client Area](https://www.wp-crm.com/downloads/client-area/) - Create a portal for clients to see the status of their projects, tasks, campaigns, and invoices.
* [Less Accounting](https://www.wp-crm.com/downloads/less-accounting/) - Connect to Less Accounting to manage invoices, and client records.
* [MailChimp Sync](https://www.wp-crm.com/downloads/mailchimp-sync/) - Subscribe contacts to your MailChimp list easily.
* [Invoicing](https://www.wp-crm.com/downloads/invoicing/) - Send invoices to customers and accept payments by credit card through Stripe.
* [Custom Fields](https://www.wp-crm.com/downloads/custom-fields/) - Add extra fields to WP-CRM System. Keep track of an unlimited number of extra data for all of your records.
* [Dropbox Connect](https://www.wp-crm.com/downloads/dropbox-connect/) - Attach files from your Dropbox account to any record in WP-CRM System.
* [Slack Notifications](https://www.wp-crm.com/downloads/slack-notifications/) - Alert your team through a Slack channel whenever a project, task, or opportunity is updated.
* [Email Notifications](https://www.wp-crm.com/downloads/email-notifications/) - Alert assigned users via email whenever a project, task, or opportunity is updated.
* [Ninja Forms](https://www.wp-crm.com/downloads/ninja-form-connect/) - Automatically create new contacts in WP-CRM System whenever a visitor fills out a contact form on your site (requires Ninja Forms).
* [Gravity Forms](https://www.wp-crm.com/downloads/gravity-forms-connect/) - Automatically create new records in WP-CRM System whenever a visitor fills out a contact form on your site (requires Gravity Forms).
* [Import Records](https://www.wp-crm.com/downloads/import-bundle/) - If you have records from another CRM or already in a spreadsheet, you can import them easily into WP-CRM System. This extension also allows you to export your records to a CSV file for use elsewhere.
* [Create Contacts from Site Users](https://www.wp-crm.com/downloads/contact-from-user/) - If you have a large user base on your site already, you can quickly create new contacts in WP-CRM System from your existing users.
* [Zendesk Connect](https://www.wp-crm.com/downloads/zendesk-connect/) - View tickets submitted to Zendesk by your WP-CRM System contacts. Quickly create a WP-CRM System task, project, or opportunity from the information provided in the ticket.

**Need Help?**

Documentation for WP-CRM System and extensions can be [found on our website](https://www.wp-crm.com/document).

Get support from the WordPress [support page](http://wordpress.org/support/plugin/wp-crm-system) for this plugin, or on our [support forum](https://www.wp-crm.com/support).

**Languages**

WP-CRM System is written in American English, and has been translated into the following languages:

* Russian - WP-CRM System Core Plugin by Maxim Glazunov
* German - WP-CRM System Core Plugin and all premium extensions by Michael Pekic
* Polish - WP-CRM System Core Plugin by Pawel Michalski

If you would like to submit a translation, please submit it [through our website](https://www.wp-crm.com/contact).

== Installation ==
1. Download archive and unzip in wp-content/plugins or install via Plugins - Add New.
2. Activate the plugin through the Plugins menu in WordPress.

== Frequently Asked Questions ==
= What makes WP-CRM System different from other CRM's? =
Many other CRM's are hosted on the CRM company's servers, which requires you to maintain an active subscription in order to access YOUR data. WP-CRM System gives you control over your data by keeping it all contained on your website. You own it all and no one can ever keep you from it - just like it should be. There are no expensive contracts required in order for you to access your customer's information.

= What if I am coming from another CRM, can I import my data? =
Absolutely. There are inexpensive extensions that allow you to import as little or as much information as you need to into WP-CRM System.

= Is my data secure? =
Your data is only accessible through the WordPress dashboard. None of it will be accessible through the public facing portion of your website. This allows you to provide access to as few or as many people as you need to.

== Changelog ==
= 2.0.12 =
* Fix: Script incorrectly looked up contact and organization address information when not viewing a contact or organization's record.
= 2.0.11 =
* Added: Filter for default fields allows developers to modify or add fields as needed.
* Improved file structure for easier edits and maintainability of the plugin.
* Fix: Error that showed if no email address existed for a contact on the Email page.
= 2.0.10 =
* Tweak: Removed depreciated jQuery code used in Searchable Menus setting.
* New: Included Google Maps API key setting to allow for continued use of Google Maps in Contact and Organization records.
= 2.0.9 =
* Fix: Updated date settings function, which was not saving dates correctly.
= 2.0.8 =
* New: Added ability to assign campaigns to contacts and organizations.
* Update: Added information to new extensions.
= 2.0.7 =
* Tweak: Update save contact name/title function to enable compatibility with certain 3rd party plugins.
= 2.0.6 =
* New: Hook added to allow extensions listed on WP-CRM System dashboard through the extension plugin, rather than hardcode in WP-CRM System. Developers can add_filter('wpcrm_system_dashboard_extensions') to be included here.
= 2.0.5 =
* Fix: New installs of the plugin were not displaying the WP-CRM System dashboard correctly.
= 2.0.4 =
* Fix: Added MailChimp option to dashboard extension list.
* Fix: WP-CRM System dashboard boxes were displaying at inconsistent heights making the page layout break. JS fix to ensure consistent heights.
* New: WP-CRM System dashboard is visible to anyone who has access to WP-CRM System. Previously only administrators had access. WP-CRM System settings box is only visible to administrators.
* New: Added option to restrict users to view their own records only. This only impacts users that are not administrators. Administrators can still view/edit/delete all records.
= 2.0.3 =
* Added option for certain searchable select/option menus. Specifically, select menus for choosing a user, campaign, organization, contact, or project were added.
= 2.0.2 =
* Bug fix: Categories tab was removed from dashboard page.
= 2.0.0 =
* Added hooks to for developers to extend and customize WP-CRM System.
* Hooks include ability to add or remove user roles, add/remove/move WP-CRM System's dashboard boxes, add custom fields and meta boxes, add custom reports, create custom plugins.
* Updated WP-CRM System dashboard layout to address issues with viewing the dashboard on smaller screens.
* **This version is required for all 2.0.0+ versions of add-on plugins.**
= 1.2.7 =
* Bug fix: New tasks were not able to get assigned to a project correctly. This update addresses that bug.
= 1.2.6 =
* Minor bug fix
= 1.2.5 =
* Added support for MailChimp extension.
= 1.2.4 =
* Removed Gravity Forms connect settings page in WP-CRM System Dashboard as new update does not require a settings page.
* Minor bug fix to user display in records.
= 1.2.3 =
* Added button to the "Create Project from Opportunity" link.
* Fixed bug in user display when no user was selected.
= 1.2.2 =
* Corrected wording on email page.
= 1.2.1 =
* Performance improvements.
* Updated language translatable file.
= 1.2.0 =
* Improved Contacts by removing a redundancy in the title and first/last name fields, which should be the same. Now title field is removed, and the title is automatically generated by the First and Last Name fields.
* Improved structure of the WP-CRM System Menu to a more logical flow.
* Added new dashboard screen to give quick access to relevant information.
* Reformatted edit screens to allow for easier readability of your information.
* Enabled default WordPress commenting on records so you and your team can have a conversation from within WP-CRM System.
* New feature allows you to quickly create new records from within other records. For example, create a new organization from within a contact's edit page.
* Various bug fixes and performance improvements.
= 1.1.11 =
* Corrected a bug that prevented Google Maps from loading in Contacts and Organizations when viewed over a secure HTTPS connection.
= 1.1.10 =
* Corrected a bug that caused no contacts to be loaded on the Email page if at least one contact category was not present.
= 1.1.9 =
* Updated German translation.
* Added support for Invoicing extension.
* Update to extension page.
* Minor performance improvements.
= 1.1.8 =
* Added German translation thanks to Michael Pekic.
= 1.1.7 =
* Included additional name prefix options.
= 1.1.6 =
* Minor update to fix included images.
= 1.1.5 =
* Fixed minor URL issue on extensions.
= 1.1.4 =
* Added support for custom fields extension.
* Minor updates to UI in administration areas.
= 1.1.3 =
* Minor fix for accordion categories on email filtering.
= 1.1.2 =
* Added filtering by organization and category for email recipients.
= 1.1.1 =
* Added new reports - list all tasks assigned to a project, and list all projects assigned to an organization.
* Added ability to assign tasks to projects.
= 1.1 =
* New Feature: Send emails to contacts directly from WP-CRM System.
* Minor update: Added category edit page for Campaigns.
= 1.0.10 =
* Added support for Zendesk extension.
= 1.0.9 =
* Resolved minor issue with date formatting.
= 1.0.8 =
* Added Russian translation thanks to Maxim Glazunov.
* Support for Dropbox extension added.
= 1.0.7 =
* Fixed issue with some strings not displaying translations
= 1.0.6 =
* New Feature: Track marketing campaigns. Find out how well your marketing campaigns are performing. Track opportunities created from each campaign and keep tabs on your campaign's ROI.
= 1.0.5 =
* Dashboard report fix.
= 1.0.4 =
* Modified how users are provided access to WP-CRM System. Only Administrator level users (with manage_options capability) can access WP-CRM System settings. Administrators can allow users in other roles access to add, edit, or delete records, as well as view reports.
* Hide dashboard widget if current user is not in a role with access to WP-CRM System information.
* Added translatable text in missing areas.
= 1.0.3 =
* Fixed error message on reports.
= 1.0.2 =
* Fixed error message on certain reports and dashboard notice.
= 1.0.1 =
* Fixed minor issue with Google maps in records with addresses.
* Added dashboard notifications for any projects, tasks, or opportunities the current logged in user is assigned to.
* Fixed a minor CSS issue.
= 1.0 =
* Initial Release

== Upgrade Notice ==
= 2.0.12 =
* Fix: Script incorrectly looked up contact and organization address information when not viewing a contact or organization's record.