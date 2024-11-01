<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://draglabs.com
 * @since      1.0.0
 *
 * @package    Universal_Email_Preference_Center
 * @subpackage Universal_Email_Preference_Center/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Universal_Email_Preference_Center
 * @subpackage Universal_Email_Preference_Center/includes
 * @author     David Strom & Manish Kumar <developermanishhub@gmail.com>
 */
class Universal_Email_Preference_Center
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Universal_Email_Preference_Center_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('UNIVERSAL_EMAIL_PREFERENCE_CENTER_VERSION')) {
            $this->version = UNIVERSAL_EMAIL_PREFERENCE_CENTER_VERSION;
        } else {
            $this->version = '1.3.0';
        }
        $this->plugin_name = UEPC_PLUGIN_NAME;

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Universal_Email_Preference_Center_Loader. Orchestrates the hooks of the plugin.
     * - Universal_Email_Preference_Center_i18n. Defines internationalization functionality.
     * - Universal_Email_Preference_Center_Admin. Defines all hooks for the admin area.
     * - Universal_Email_Preference_Center_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-universal-email-preference-center-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-universal-email-preference-center-i18n.php';

        require plugin_dir_path(dirname(__FILE__)) . 'includes/request.php';

        require plugin_dir_path(dirname(__FILE__)) . 'includes/manager/Iterable_center.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/manager/Active_campaign.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-universal-email-preference-center-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-universal-email-preference-center-public.php';

        $this->loader = new Universal_Email_Preference_Center_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Universal_Email_Preference_Center_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Universal_Email_Preference_Center_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Universal_Email_Preference_Center_Admin($this->get_plugin_name(), $this->get_version());

        // Add Plugin menu
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_options');
        $this->loader->add_action('admin_menu', $plugin_admin, 'options_init');

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        $this->loader->add_action('uepc_tab_content_layout', $plugin_admin, 'uepc_tab_content_layout', 10);

        $this->loader->add_action('uepc_process_settings_form', $plugin_admin, 'process_settings_form', 10);

        $this->loader->add_action('uepc_admin_lists_layout', $plugin_admin, 'uepc_admin_lists_layout', 10);
        $this->loader->add_action('uepc_admin_style_layout', $plugin_admin, 'admin_style_layout', 10);

        $this->loader->add_action('get_uepc_pages', $this, 'get_pages');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Universal_Email_Preference_Center_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        $this->loader->add_action('wp_ajax_update_preferences', $plugin_public, 'ajax_update_preferences');
        $this->loader->add_action('wp_ajax_nopriv_update_preferences', $plugin_public, 'ajax_update_preferences');
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
        $this->loader->add_action('uepc_frontend_layout', $plugin_public, 'uepc_frontend_layout', 10);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Universal_Email_Preference_Center_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    public static function has_credentials()
    {
        $creds = get_option(UEPC_PLUGIN_NAME . '_center');
        $creds = get_option(UEPC_PLUGIN_NAME . '_key');
        $creds = get_option(UEPC_PLUGIN_NAME . '_url');

        if (!$creds) {
            return false;
        } else {
            return true;
        }
    }

    public static function dialog_html()
    {
        ob_start(); ?>
            <?php global $current_user; ?>
            <div id="uepc-modal" class="hidden" style="max-width:800px">
                <h3>Hi <?php esc_attr_e($current_user->user_login); ?>,</h3>
                <p>The option you are trying to use it's premium version feature.
                </p>
                <p>Please install premium add-on to use this feature.</p>
                <p><a target="_blank" href="<?php echo esc_url(uepc_freemius()->_get_admin_page_url('addons')) ?>"><< Click me to add premium >></a></p>
                <p><strong>Thanks</strong></p>
            </div>
        <?php

        echo ob_get_clean();
    }

    public function get_pages()
    {
        global $wp, $wpdb;

        $pages = $wpdb->get_results("SELECT ID, post_title, guid FROM ".$wpdb->posts." WHERE post_content LIKE '%[universal-email-preference-center%' AND post_status = 'publish'");

        if ($pages) {
            foreach ($pages as $item) {
                echo wp_kses_post('<a target="_blank" href= "'.get_permalink($item->ID).'">'.$item->post_title.'</a><br/>');
            }
        } else {
            echo wp_kses_post('<p><span id="uepc-scroll" style="cursor: pointer;color: red;">CLICK ME!</span> to use shortcode.</p>');
        }
    }

    public static function iterable_class_search($search_key, $search, $array)
    {
        if (!empty($array)) {
            foreach ($array as $value) {
                if (isset($value['channel']) && $value['channel'][$search_key] == $search) {
                    return $value['channel'];
                }
            }
        }
    }
}
