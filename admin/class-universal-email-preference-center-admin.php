<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://draglabs.com
 * @since      1.0.0
 *
 * @package    Universal_Email_Preference_Center
 * @subpackage Universal_Email_Preference_Center/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Universal_Email_Preference_Center
 * @subpackage Universal_Email_Preference_Center/admin
 * @author     David Strom & Manish Kumar <developermanishhub@gmail.com>
 */
class Universal_Email_Preference_Center_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    private $center;
    private $center_class;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->center = esc_attr(get_option($this->plugin_name . '_center'));
        if (!empty($this->center)) {
            if ($this->center == "Iterable") {
                $this->center_class = new Iterable_center($this->plugin_name, $this->version);
            } else {
                $this->center_class = new $this->center($this->plugin_name, $this->version);
            }
        }
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Universal_Email_Preference_Center_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Universal_Email_Preference_Center_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style('wp-jquery-ui-dialog');

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/universal-email-preference-center-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Universal_Email_Preference_Center_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Universal_Email_Preference_Center_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script('jquery-ui-dialog');

        wp_enqueue_script(
            $this->plugin_name . '_run_prettify',
            plugin_dir_url(__FILE__) . 'js/run_prettify.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/universal-email-preference-center-admin.js', array( 'jquery' ), $this->version, false);
    }

    public static function email_centers()
    {
        return [
            "Active_campaign" => [
                "name"  => "Active Campaign",
                "route" => "/api/3/",
            ],
            "Iterable" => [
                "name"  => "Iterable",
                "route" => "/api/",
            ],
        ];
    }

    public function options_init()
    {
        register_setting($this->plugin_name . '_api', $this->plugin_name . '_center');
        register_setting($this->plugin_name . '_api', $this->plugin_name . '_key');
        register_setting($this->plugin_name . '_api', $this->plugin_name . '_url');
        register_setting($this->plugin_name . '_api', $this->plugin_name . '_api_setting_update_status');
        register_setting($this->plugin_name . '_lists', $this->plugin_name . '_list_data');
    }

    public static function tabs()
    {
        return [
            [
                'name' => 'API Settings',
                'key' => 'settings',
                'order' => 0
            ],
            [
                'name' => 'Lists',
                'key' => 'lists',
                'order' => 1
            ],
            [
                'name' => 'Options',
                'key' => 'options',
                'order' => 2
            ],
            [
                'name' => 'Appearance',
                'key' => 'appearance',
                'order' => 3
            ],
            [
                'name' => 'Custom Styles',
                'key' => 'style',
                'order' => 4
            ]
        ];
    }

    public static function tabs_layout($tab,$valid)
    {
        global $uepc_freemius;

        $tabs = self::tabs();

        if (Universal_Email_Preference_Center::has_credentials()) {
            $tabs[0]['order'] = 5;
        }

        usort($tabs, fn ($a, $b) => $a['order'] <=> $b['order']);
        $html = '';
        foreach ($tabs as $item) {
            $list = true;
            if (in_array($item['key'], ['options', 'appearance'])) {
                if (function_exists("uepcp_fs") && uepcp_fs()->is__premium_only() && uepcp_fs()->can_use_premium_code()) {
                } else {
                    $list = false;
                    
                    $link1 = add_query_arg( array(
                        'page' => UEPC_PLUGIN_NAME,
                        'tab' => esc_attr($item['key']),
                    ), admin_url('admin.php') );
                    $active_class = ($tab == $item['key'] ? 'nav-tab-active' : '');
                    $html.= sprintf("<a href='%s' class='nav-tab %s'>%s<span class='dashicons dashicons-star-filled'></span></a>", esc_url($link1), esc_attr($active_class), esc_attr($item['name']) );
                }
            }
            if ($item['key'] == 'lists') {
                $list_data = get_option(UEPC_PLUGIN_NAME . '_list_data');
                $setting_status = get_option(UEPC_PLUGIN_NAME . '_api_setting_update_status');
                if (!$list_data || $setting_status) {
                    $list = false;
                    $link1 =  add_query_arg( array(
                        'page' => UEPC_PLUGIN_NAME,
                        'tab' => esc_attr($item['key']),
                    ), admin_url('admin.php') );
                    $active_class = ($tab == $item['key'] ? 'nav-tab-active' : '');
                    $html.= sprintf("<a href='%s' class='nav-tab %s'><span class='tooltip' data-tooltip='Settings updated Please click the Save List Data button below as per your selection'>%s <span class='dashicons dashicons-info' style='color: red;' ></span></span></a>", esc_url($link1), esc_attr($active_class),  esc_attr($item['name']));

                }
            }
            if ($list) {
                $link1 =  add_query_arg( array(
                    'page' => UEPC_PLUGIN_NAME,
                    'tab' => esc_attr($item['key']),
                ), admin_url('admin.php') );
                $active_class = ($tab == $item['key'] ? 'nav-tab-active' : '');
                if ("settings" == $item['key']) {
                    $active_class .= $valid ? ' settings-valid-true':' settings-valid-false';
                }
                $html.= sprintf("<a href='%s' class='nav-tab %s'>%s</a>", esc_url($link1), esc_attr($active_class),  esc_attr($item['name']));
            }
        }

        echo wp_kses_post($html);
    }

    public function uepc_tab_content_layout($tab)
    {
        return plugin_dir_path(__FILE__) . 'partials/tabs/' . $tab . '.php';
    }

    public function add_plugin_options()
    {
        add_menu_page(
            'Settings Admin',
            'Universal email preference center',
            'manage_options',
            'universal-email-preference-center',
            array( $this, 'create_options_page' ),
            'dashicons-networking',
            70
        );

        add_submenu_page(
            'universal-email-preference-center',
            'Logs',
            'Logs',
            'manage_options',
            'universal-email-preference-center-logs',
            array(
                $this,
                'create_logs_page',
            )
        );
    }

    public function create_options_page()
    {
        //Get the active tab from the $_GET param
        $default = 'settings';

        if (Universal_Email_Preference_Center::has_credentials()) {
            $default = 'lists';
        }

        $tab = isset($_GET['tab']) && !empty($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default;

        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        if (isset($_REQUEST['settings-updated']) && !empty($_REQUEST['settings-updated'])) {
            echo  '<div class="updated"><p>' . esc_attr_x('Your settings were saved!', 'universal-email-preference-center') . '</p></div>';
        }

        if (isset($_POST['updated']) && $_POST['updated'] === 'true' && wp_verify_nonce($_POST['save_list_data_nonce'], 'save_list_data')) {
            $this->handle_settings_form();
        }

        if (Universal_Email_Preference_Center::has_credentials()) {
            $valid = $this->center_class->validate();
        } else {
            $valid = false;
        }

        if ($valid) {
            $list_data = get_option($this->plugin_name . '_list_data');
            echo  '<div class="updated"><p>' . esc_attr_x('App connected with Email Preference Center.', 'universal-email-preference-center') . '</p></div>';
            if (!$list_data) {
                echo  '<div class="notice notice-error"><p>' . wp_kses_post('You have not saved your list data. Please select which lists you would like to enable and click the <strong>"Save List Data"</strong> button below.') . '</p></div>';
            }
            switch ($tab) {
                case 'lists':
                    $lists = $this->center_class->get_lists();
                    break;

                case 'appearance':
                case 'options':
                    $tamper_protection_email_subject = get_option($this->plugin_name . '_tamper_protection_email_subject', _x('Manage Your %%BLOG_NAME%% Email Subscription', 'universal-email-preference-center'));
                    $tamper_protection_email_content = get_option($this->plugin_name . '_tamper_protection_email_content', _x("Someone has requested to update your email subscription to %%BLOG_NAME%%.  In order to do so you need to click the link below. \n\n\n %%LINK%% ", 'universal-email-preference-center'));
                    $tamper_protection_emailing = get_option($this->plugin_name . '_tamper_protection_emailing', esc_attr_x('Please check your email, it contains a link to confirm you have access to manage your subscription.', 'universal-email-preference-center'));
                    $heading_available_lists = get_option($this->plugin_name . '_heading_available_lists', esc_attr_x('Available Lists', 'universal-email-preference-center'));
                    $heading_welcome = get_option($this->plugin_name . '_heading_welcome', _x('Welcome %%EMAIL%%, you are subscribed to the following lists.', 'universal-email-preference-center'));
                    $heading_retrieve = get_option($this->plugin_name . '_heading_retrieve', esc_attr_x('Retrieve Email Preferences', 'universal-email-preference-center'));
                    $placeholder_email = get_option($this->plugin_name . '_placeholder_email', esc_attr_x('Enter your email...', 'universal-email-preference-center'));
                    $button_save_preferences = get_option($this->plugin_name . '_button_save_preferences', esc_attr_x('Save Preferences', 'Button text if email has NOT been retrieved', 'universal-email-preference-center'));
                    $button_subscribe = get_option($this->plugin_name . '_button_subscribe', esc_attr_x('Subscribe', 'Button text if email HAS been retrieved.', 'universal-email-preference-center'));
                    $button_retrieve = get_option($this->plugin_name . '_button_retrieve', esc_attr_x('Retrieve Email Preferences', 'Button text if email HAS NOT been retrieved.', 'universal-email-preference-center'));
                    $string_success_text = get_option($this->plugin_name . '_string_success_text', esc_attr_x('Your email preferences have been saved.', 'Success string when subscribing to list', 'universal-email-preference-center'));
                    $string_error_text = get_option($this->plugin_name . '_string_error_text', esc_attr_x('There was an error processing your request.  Try again.', 'Error string when subscribing to list', 'universal-email-preference-center'));
                    $string_saving_text = get_option($this->plugin_name . '_string_saving_text', esc_attr_x('Please wait...', 'Text when save preferences button is pressed', 'universal-email-preference-center'));
                    $name_position = get_option($this->plugin_name . '_name_position', esc_attr_x('bottom', 'universal-email-preference-center'));
                    $enable_name = filter_var(get_option($this->plugin_name . '_enable_name'), FILTER_VALIDATE_BOOLEAN);
                    break;
            }
        } else {
            $setting_link = admin_url('admin.php?page='.UEPC_PLUGIN_NAME.'&tab=settings');
            ?>
           <div class="notice notice-error"><p><?php  esc_html_e('Your API Credentials are not valid.', 'universal-email-preference-center'); ?> </p></div>
             <?php $app_config_error = sprintf('<h3>Please <a href="%s">click here</a> to configure your app and try again.</h3>', esc_url( $setting_link)); ?>
            <?php
        }

        require_once plugin_dir_path(__FILE__) . 'partials/universal-email-preference-center-admin-display.php';
    }


    function uep_log_redirect( $url ) {
        ?>
            <script type="text/javascript">
            window.location = "<?php echo esc_url_raw($url); ?>";
            </script>
        <?php
    }
    

    /*
    *
    * LOGS TEMPLATE FUNCTION.
    */
    public function create_logs_page()
    {
        $openFile = '';
        // get log action from $_REQUEST method.
        $logAction = isset($_REQUEST['log-action']) ? sanitize_text_field($_REQUEST['log-action']) : "";
        if ($logAction == 'view') {
            // get log file from $_REQUEST method.
            $openFile = isset($_REQUEST['log-file']) ? sanitize_text_field($_REQUEST['log-file']) :'';
        } elseif ($logAction == 'delete') {
            // get log file from $_REQUEST method.
            $openFile = isset($_REQUEST['log-file']) ? sanitize_text_field($_REQUEST['log-file']) :'';
            if (file_exists(dirname(plugin_dir_path(__FILE__)) . '/logs/' .$openFile)) {
                unlink(dirname(plugin_dir_path(__FILE__)) . '/logs/' . $openFile);
            }
            $this->uep_log_redirect(admin_url('/admin.php?page=universal-email-preference-center-logs'));
            return;
        }

        $logFiles = [];

        if (is_dir(dirname(plugin_dir_path(__FILE__)) . '/logs') && $handle = opendir(dirname(plugin_dir_path(__FILE__)) . '/logs')) {
            $ignore = ['cgi-bin', '.', '..', '._'];
            while (false !== ($entry = readdir($handle))) {
                if (!in_array($entry, $ignore) and substr($entry, 0, 1) != '.') {
                    $logFiles[] = $entry;
                }
            }
            closedir($handle);
        }

        if ($openFile == '') {
            $openFile = isset($logFiles[count($logFiles) - 1]) ? $logFiles[count($logFiles) - 1] : "";
        }

        $logFile = dirname(plugin_dir_path(__FILE__)) . '/logs/' . $openFile;
        $logData = '';
        if (file_exists($logFile) && !empty($openFile)) {
            $file = file($logFile);
            $file = array_reverse($file);
            foreach ($file as $f) {
                $logData .= "<button class='accordion'>" . $this->limit_text($f, 8) . "</button><div class='panel'><p>$f</p></div>";
            }
        } else {
            $logData .= "<div class='notice notice-warning is-dismissible'>
					<p>Log's File Is Not Exist </p>
				</div>";
        }

        require_once plugin_dir_path(__FILE__) . 'partials/logs.php';
    }

    /**
     * Truncate string as per limit.
     */
    public function limit_text($text, $limit)
    {
        $pos = stripos($text, '<br>');

        if ($pos > -1) {
            $text = substr($text, 0, $pos) . "...";
        } else {
            if (str_word_count($text, 0) > $limit) {
                $words = str_word_count($text, 2);
                $pos = array_keys($words);
                $text = substr($text, 0, $pos[$limit]) . '...';
            }
        }

        return $text;
    }

    public function uepc_admin_lists_layout()
    {
        return $this->center_class->admin_lists_layout();
    }

    public function admin_style_layout()
    {
        return plugin_dir_path(__FILE__) . 'partials/settings-styles.php';
    }

    public function handle_settings_form()
    {
        $data = apply_filters('uepc_process_settings_form', []);
        $this->save_settings_data($data);
    }

    public function process_settings_form()
    {
        // Process admin from via $_POST method after save button.
        $data = array();
        foreach ($_POST['list_id'] as $list_id) {
            $data[$list_id] = array(
                'id' => (int) $list_id,
                'enabled' => true,
                'default' => isset($_POST['default']) ? (bool) in_array($list_id, (array) $_POST['default']) : "",
                'required' => isset($_POST['required']) ? (bool) in_array($list_id, (array) $_POST['required']) : "",
                'name' => isset($_POST['name'][$list_id]) ? sanitize_text_field($_POST['name'][$list_id]) : "",
                'description' => "",
                'channel' => [
                    'id' => !empty($_POST['channelId'][$list_id]) ? sanitize_text_field($_POST['channelId'][$list_id]) : "",
                    'name' => isset($_POST['channelName']) ? sanitize_text_field($_POST['channelName'][$list_id]) : "",
                    'updated_name' => isset($_POST['channelName']) && isset($_POST['channelNameUpdate'][$_POST['channelName'][$list_id]]['name']) ? sanitize_text_field($_POST['channelNameUpdate'][$_POST['channelName'][$list_id]]['name']) : "",
                    'enabled' => filter_var(isset($_POST['channelName']) && isset($_POST['channelNameUpdate'][$_POST['channelName'][$list_id]]['enabled']) ? sanitize_text_field($_POST['channelNameUpdate'][$_POST['channelName'][$list_id]]['enabled']) : "", FILTER_VALIDATE_BOOLEAN),
                    'required' => filter_var(isset($_POST['channelName']) && isset($_POST['channelNameUpdate'][$_POST['channelName'][$list_id]]['required']) ? sanitize_text_field($_POST['channelNameUpdate'][$_POST['channelName'][$list_id]]['required']) : "", FILTER_VALIDATE_BOOLEAN)
                ],
            );
        }
        return $data;
    }

    public function save_settings_data($data)
    {
        error_log(print_r($data, true));
        update_option($this->plugin_name . '_api_setting_update_status', false);
        if ($data != get_option($this->plugin_name . '_list_data')) {
            if (update_option($this->plugin_name . '_list_data', $data, false)) {
                ?>
                <div class="updated"><p><?php esc_html_e('Your fields were saved!', 'universal-email-preference-center'); ?> </p></div>
                <?php
            } else {
                uepc_log($this->center, "There was an issue saving your list data.<br>".json_encode($data), 'error');
                ?>
                <div class="error"><p><?php esc_html_e('ERROR: There was an issue saving your list data.', 'universal-email-preference-center'); ?></p></div>
                <?php 
            }
        } else {
            ?>
            <div class="updated"><p><?php  esc_html_e('Your fields were saved!', 'universal-email-preference-center');?></p></div> 
            <?php 
        }
    }
}
