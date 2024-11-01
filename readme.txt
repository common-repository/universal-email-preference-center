=== Universal email preference center ===
Contributors: roofboard, Manish Kumar, freemius
Donate link: wpressapi.com
Tags: activecampaign, active campaign, email preference center, iterable, universal email preference center
Tested up to: 6.2
Requires at least: 3.8
Stable tag: 1.3.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow users to manage their subscriptions to your ActiveCampaign or Iterable lists through an integrated email preference center on Wordpress.

== Description ==

ActiveCampaign and Iterable do not have a native email preference center to allow your customers to manage their subscriptions to various lists. I developed this plugin as a fork of the Universal Email Preference Center to add new capabilities and make it compatible with Iterable and other platforms.

### The FREE version of this plugin gives you the following features:

- Easy access to all of your ActiveCampaign and Iterable lists.
- Ability for users to look up their subscriptions.
- Ability for users to subscribe and unsubscribe from all of your ActiveCampaign lists.
- Easy integration via WordPress shortcode.

= Usage =
Integration is easy, just use the shortcode `[universal-email-preference-center]` on any page, post or widget area to integrate your email preference center.


### The PREMIUM version of this plugin includes the following additional features:

- Select which ActiveCampaign or Iterable lists to show in email preference center.
- The ability to change the name of your ActiveCampaign or Iterable list for presentational purposes. *(i.e., if your list is called Our Customers in ActiveCampaign, you could change that to Mailing List for the frontend)
- Assign a description for each of your ActiveCampaign or Iterable lists to describe your different email lists.
- Control various template elements through use of simple shortcode flags.
- Able to have logged in users automatically pull their subscribed lists.
- Ability to implement tamper protection to send an email to user to confirm their email address before updating email preferences.



== Installation ==

After installing the plugin, be sure to navigate to the Settings->Universal email preference center page and input your ActiveCampaign or Iterable Url and API Key. You can find your API url and key by visiting https://help.activecampaign.com/hc/en-us/articles/207317590-Getting-started-with-the-API. Your Iterable URL will be https://api.iterable.com.

== Frequently Asked Questions ==

**Question:** I have several lists, but I only want to show a couple of them. Can I hide various lists?  
**Answer:** Yes, but only with the Premium version of this plugin. With the Premium version, you can toggle on and off each of the lists you want to show in the email preference center.

**Question:** Can I style the email preference center to match my theme?  
**Answer:** Yes, I purposely did not include any styles with this plugin to make it utilize your theme's built-in styles. If you navigate to the settings page, at the bottom, I have included some basic styles that you could add to your additional CSS section of Appearance->Customize.

**Question:** I really wish this plugin did X. Do you offer customizations?  
**Answer:** Yes, please reach out and let me know what feature you wish this plugin had. I might add it to the roadmap if I think it would benefit everyone, or we could discuss the possibility of custom development for your specific case. Please add a feature request at https://wpressapi.com.

== Screenshots ==

1. Settings Screen
4. Preference Center Email Lookup
5. Preference Center Lists

== Changelog ==

= 1.2.4 =
* bug-fix
*testing ci merge up capabilities
*modified ci to use  the runner server*runner

= 1.2.3 =
* bug-fix
*updated copy

= 1.2.2 =
* bug-fix
*ci

= 1.2.1 =
* bug-fix
*ci

= 1.2.0 =
* feature
*ci

= 1.1.0 =
* feature
* bug fix * status pre release
* Resolved issue preventing upload to freemius due to uninstall problem.
* feature *
* Implemented token validation functionality.
* Incorporated  added functionality to display original class and list name in the event of any modifications.
* Fixed the issue related ActiveCampagian user name update issue.

= 1.2.2 =
* bug-fix
* Fixed the issue related ActiveCampagian user name update issue.

= 1.0.1 =
* feature
* Fix the validate token function. I've made some changes to the 'validate token' function. Previously, the function sanitized the key while validating, but not while creating. I have  updated the function so that it sanitizes the key both when creating and validating.
* I have fixed an issue with the tamper protection feature. Enabling the feature should now activate the tamper protection functionality as expected.
* I have made some changes to the security vulnerability functions.

= 1.0.0 =
* version
*major refactor

== Upgrade Notice ==

= 1.2.4 =
* bug-fix
*testing ci merge up capabilities
*modified ci to use  the runner server*runner
