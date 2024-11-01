<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://draglabs.com
 * @since      1.0.0
 *
 * @package    Universal_Email_Preference_Center
 */

// If uninstall not called from WordPress, then exit.
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// unregister delete options.
$options = [
    '_center',
    '_key',
    '_url',
    '_list_data',
    // Css Add on keys
    '_css_enable',
    '_css_code',
    // Options Settings
    '_auto_load_logged_in_users',
    '_tamper_protection',
    '_tamper_protection_email_subject',
    '_tamper_protection_email_content',
    '_tamper_protection_emailing',
    // Appearance
    '_iterable_policy',
    '_enable_switch_layout',
    '_enable_name',
    '_name_position',
    '_heading_available_lists',
    '_heading_welcome',
    '_heading_retrieve',
    '_button_save_preferences',
    '_button_subscribe',
    '_button_retrieve',
    '_placeholder_email',
    '_string_success_text',
    '_string_error_text',
    '_string_saving_text'
];

foreach ($options as $option) {
    delete_option('universal-email-preference-center' . $option);
}