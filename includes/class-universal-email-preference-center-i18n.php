<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://draglabs.com
 * @since      1.0.0
 *
 * @package    Universal_Email_Preference_Center
 * @subpackage Universal_Email_Preference_Center/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Universal_Email_Preference_Center
 * @subpackage Universal_Email_Preference_Center/includes
 * @author     David Strom & Manish Kumar <developermanishhub@gmail.com>
 */
class Universal_Email_Preference_Center_i18n
{
    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            'universal-email-preference-center',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
