<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://draglabs.com
 * @since             1.0.0
 * @package           Universal_Email_Preference_Center
 *
 * @wordpress-plugin
 * Plugin Name:       Universal email preference center
 * Plugin URI:        https://wpressapi.com/universal-email-preference-center
 * Description:       Base Program allows users to update their email preferences on ActiveCampaign and Iterable.
 * Version:           1.3.0
 * Author:            David Strom & Manish Kumar
 * Author URI:        https://draglabs.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       universal-email-preference-center
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('UNIVERSAL_EMAIL_PREFERENCE_CENTER_VERSION', '1.3.0');

if (!defined("UEPC_DIR_PATH")) {
    define("UEPC_DIR_PATH", dirname(__FILE__));
}

if (!defined("UEPC_PLUGIN_NAME")) {
    define("UEPC_PLUGIN_NAME", "universal-email-preference-center");
}

/**
 * The code that runs during plugin activation.
 */
register_activation_hook(__FILE__, function () {
    // Get all the active plugins.
    $active_plugins = get_option('active_plugins');

    // Loop through all active plugins.
    foreach ($active_plugins as $key => $active_plugin) {
        // If another plugin with the same codebase is found, deactivate it and activate the current plugin.
        if (strpos($active_plugin, basename(__FILE__)) !== false && $active_plugin !== plugin_basename(__FILE__)) {
            deactivate_plugins($active_plugin);
            activate_plugin(plugin_basename(__FILE__));
            break;
        }
    }
});

if (function_exists('uepc_freemius')) {
    uepc_freemius()->set_basename(false, __FILE__);
} else {
    // Create a helper function for easy SDK access.
    function uepc_freemius()
    {
        global $uepc_freemius;

        if (!isset($uepc_freemius)) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/includes/freemius/start.php';

            $uepc_freemius = fs_dynamic_init(
                array(
                    'id' => '11199',
                    'slug' => 'universal-email-preference-center',
                    'type' => 'plugin',
                    'public_key' => 'pk_cb4708bb111950a5298e4c0e8f5a4',
                    'is_premium' => false,
                    'has_addons' => true,
                    'has_paid_plans' => false,
                    'menu' => array(
                        'slug' => 'universal-email-preference-center',
                        'support' => false,
                    ),
                )
            );
        }

        return $uepc_freemius;
    }

    // Init Freemius.
    uepc_freemius();
    // Signal that SDK was initiated.
    do_action('uepc_freemius_loaded');
}

// Run Plugin.
add_action('plugins_loaded', function () {
    uepc_freemius();
    // freemius custom icon.
    uepc_freemius()->add_filter('plugin_icon', function () {
        return dirname(__FILE__) . '/admin/img/icon.png';
    });

    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path(__FILE__) . 'includes/class-universal-email-preference-center.php';

    $plugin = new Universal_email_preference_center();
    $plugin->run();

    function uepc_freemius_custom_connect_message_on_update(
        $message,
        $user_first_name,
        $plugin_title,
        $user_login,
        $site_link,
        $freemius_link
    ) {
        return sprintf(
            __('Hey %1$s') . ',<br>' .
            __('Please help us improve %2$s! If you opt-in, some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'universal-email-preference-center'),
            $user_first_name,
            '<b>' . $plugin_title . '</b>',
            '<b>' . $user_login . '</b>',
            $site_link,
            $freemius_link
        );
    }

    uepc_freemius()->add_filter('connect_message_on_update', 'uepc_freemius_custom_connect_message_on_update', 10, 6);
});

/*
 * added loging code to enable better debugging.
 * log types are
 * error | notice | success
 */
if (!function_exists('uepc_log')) {
    function uepc_log($centerType, $msg = 'something went wrong', $type = 'error')
    {
        $centerType = esc_attr(str_replace('_', ' ', $centerType));
        $pluginlog = plugin_dir_path(__FILE__) . 'logs/' . $type . '.log';
        if (!file_exists(plugin_dir_path(__FILE__) . 'logs/')) {
            mkdir(plugin_dir_path(__FILE__) . 'logs/', 0777, true);
        }
        $logDate = date('d-M-Y h:i:s A (e)');
        if ($type == 'error') {
            $message = "[ $logDate ] : " . ucwords($type . " : " . $centerType . " : ") . strtolower($msg) . "<br> File:" . debug_backtrace()[0]['file'] . " | Line->" . debug_backtrace()[0]['line'] . PHP_EOL;
        } else {
            $message = "[ $logDate ] : " . ucwords($type . " : " . $centerType . " : ") . strtolower($msg) . PHP_EOL;
        }

        error_log(__($message), 3, __($pluginlog));
    }
}
