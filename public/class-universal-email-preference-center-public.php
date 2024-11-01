<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://draglabs.com
 * @since      1.0.0
 *
 * @package    Universal_Email_Preference_Center
 * @subpackage Universal_Email_Preference_Center/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Universal_Email_Preference_Center
 * @subpackage Universal_Email_Preference_Center/public
 * @author     David Strom & Manish Kumar <developermanishhub@gmail.com>
 */
class Universal_Email_Preference_Center_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = !empty($plugin_name) ? $plugin_name :'';
        $this->version = !empty($version) ? $version :'' ;

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
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/universal-email-preference-center-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/universal-email-preference-center-public.js', array( 'jquery' ), $this->version, false);

        $string_success_text = get_option($this->plugin_name . '_string_success_text', esc_attr_x('Your email preferences have been saved.', 'Success string when subscribing to list', 'universal-email-preference-center'));
        $string_error_text = get_option($this->plugin_name . '_string_error_text', esc_attr_x('There was an error processing your request.  Try again.', 'Error string when subscribing to list', 'universal-email-preference-center'));
        $string_saving_text = get_option($this->plugin_name . '_string_saving_text', esc_attr_x('Please wait...', 'Text when save preferences button is pressed', 'universal-email-preference-center'));
        $button_save_preferences = get_option($this->plugin_name . '_button_save_preferences', esc_attr_x('Save Preferences', 'Button text if email has NOT been retrieved', 'universal-email-preference-center'));
        $tamper_protection = filter_var(get_option($this->plugin_name . '_tamper_protection'), FILTER_VALIDATE_BOOLEAN);
        $loader_img_src = plugin_dir_url(__FILE__).'img/loading.gif';
        wp_localize_script($this->plugin_name, 'uepc_ajax', [
            'ajax_url'                 => admin_url('admin-ajax.php'),
            'update_nonce'             => wp_create_nonce('update_nonce'),
            'success_text'             => $string_success_text,
            'error_text'               => $string_error_text,
            'saving_text'              => $string_saving_text,
            'save_text'                => $button_save_preferences,
            'enable_tamper_protection' => $tamper_protection,
            'loading'				   => '<img src="'. esc_url($loader_img_src).'" alt="Loading" width="40">'
        ]);
    }

    public function register_shortcodes()
    {
        add_shortcode('universal-email-preference-center', [$this, 'preference_center_shortcode']);
    }

    public static function getEmailFromGet()
    {
        if (isset($_GET['email']) && !empty($_GET['email']) && sanitize_email($_GET['email'])) {
            $handle_plus = str_replace(' ', '+', sanitize_email($_GET['email']));
            return $handle_plus;
        }

        return null;
    }

    public function preference_center_shortcode($atts)
    {
        ob_start();
        $args = shortcode_atts(array(
            'show_action_text'       => false,
            'show_list_descriptions' => true,
            'show_welcome_text'      => true,
            'list_ids'               => null,
        ), $atts, 'universal-email-preference-center');
        $show_action_text = filter_var($args['show_action_text'], FILTER_VALIDATE_BOOLEAN);
        $show_list_descriptions = filter_var($args['show_list_descriptions'], FILTER_VALIDATE_BOOLEAN);
        $show_welcome_text = filter_var($args['show_welcome_text'], FILTER_VALIDATE_BOOLEAN);
        $sending_email = false;
        $use_wordpress_user = false;
        $button_save_preferences = get_option($this->plugin_name . '_button_save_preferences', esc_attr_x('Save Preferences', 'Button text if email has NOT been retrieved', 'universal-email-preference-center'));
        $button_subscribe = get_option($this->plugin_name . '_button_subscribe', esc_attr_x('Subscribe', 'Button text if email HAS been retrieved.', 'universal-email-preference-center'));

        $email = $this->getEmailFromGet();

        if (Universal_Email_Preference_Center::has_credentials()) {
            $valid = $this->center_class->validate();
        } else {
            $valid = false;
            $allowed_html = array(
                'h5' => array(
                    'class'=>array()
                ),
            );
            $message = "<h5 class='uepc-error'> ". esc_attr("The connection failed, or the plugin isn't set up yet.") . "</h5>";
            echo wp_kses($message,$allowed_html);
        
            return ob_get_clean();
        }

        $validation_error = 'Your API Credentials are not valid.';

        $current_user = wp_get_current_user();
        if (empty($email) && isset($use_wordpress_user) && is_user_logged_in()) {
            $email = str_replace(' ', '+', sanitize_email($current_user->user_email));
        }

        $connection = $this->center_class->validate();
        if ($connection) {
            $contact = $this->center_class->get_contact($email);
            $lists = $this->center_class->get_saved_lists();
            $has_credentials = Universal_email_preference_center::has_credentials();
            $unsubscribed_channels = isset($contact['unsubscribedChannelIds']) && !empty($contact['unsubscribedChannelIds']) ? $contact['unsubscribedChannelIds'] : [];
            $subscribed_lists = [];
            $channel_ids = [];
            foreach ($lists as $list) {
                if (isset($list['channel']['id'])) {
                    $channel_ids[] = sanitize_text_field($list['channel']['id']);
                }
            }
            $channel_ids = array_unique($channel_ids);
            if (!is_null($email) && isset($contact['lists']) && !empty($contact['lists'])) {
                $subscribed_lists = $contact['lists'];
            }

            require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/universal-email-preference-center-public-display.php';
        } else {
            uepc_log($this->center, "The connection failed, or the plugin isn't set up yet.", 'notice');
            $allowed_html = array(
                'h5' => array(
                    'class'=>array()
                ),
            );
            $message = "<h5 class='uepc-error'> ". esc_attr("The connection failed, or the plugin isn't set up yet.") . "</h5>";
            echo wp_kses($message,$allowed_html);
        }
        return ob_get_clean();
    }

    public function ajax_update_preferences()
    {
        if (check_ajax_referer('update_nonce', 'security')) {
            $current_user = wp_get_current_user();
            $account_validation = filter_var(get_option(UEPC_PLUGIN_NAME . '_account_validation'), FILTER_VALIDATE_BOOLEAN);
            $tamper_protection = filter_var(get_option(UEPC_PLUGIN_NAME . '_tamper_protection'), FILTER_VALIDATE_BOOLEAN);

            // GET all frontend submitted data using $_POST method.
            parse_str($_POST['data'], $data);

            $email = str_replace(' ', '+', sanitize_email($data['email']));

            if (function_exists("uepcp_fs") && uepcp_fs()->is__premium_only() && uepcp_fs()->can_use_premium_code() && $tamper_protection && $current_user->user_email !== $email) {
                $validate = $this->validate_token($data);
                if (!$validate["status"]) {
                    wp_send_json([
                        "status" => false,
                        "data" => [],
                        "message" => esc_attr($validate["message"])
                    ], 200);
                }
            }
            
            // Validate Token
            if (function_exists("uepcp_fs") && uepcp_fs()->is__premium_only() && uepcp_fs()->can_use_premium_code() && $account_validation) {
                $token = $data['prefCtrId'];
                if (empty($token)) {
                    wp_send_json([
                        "status" => false,
                        "data" => [],
                        "message" => esc_attr("The token is required for submitting this request.")
                    ], 200);
                }
                $validate = $this->validate_jwt_token($token,$email);
                if(!$validate){
                    wp_send_json([
                        "status" => false,
                        "data" => [],
                        "message" => esc_attr("The token provided is invalid. Please try again with a new token.")
                    ], 200);
                }
            }

            $contact = $this->center_class->update_preferences($email, $data);
            wp_send_json([
                "status" => true,
                "data" => esc_attr(urlencode(serialize($contact["lists"]))),
                "message" => ""
            ], 200);
        }

        die();
    }

    public function validate_jwt_token($token,$email)
    {
        $secretKey = "IterableJWTToken";
        list($headerEncoded, $payloadEncoded, $signatureEncoded) = explode('.', $token);
        $header = json_decode(base64_decode($headerEncoded), true);
        $payload = json_decode(base64_decode($payloadEncoded), true);
        $signature = hash_hmac('sha256', $headerEncoded.'.'.$payloadEncoded, $secretKey, true);
        $signatureEncoded = base64_encode($signature);
        $isValid = ($signatureEncoded === $signatureEncoded);

        if(isset($payload['exp']) && $payload['exp'] < time()) {
            $isValid = false; // Token has expired
        }

        if(isset($payload['iat']) && $payload['iat'] > time()) {
            $isValid = false; // Token is invalid because it was issued in the future
        }
        
        if ($payload['email'] != $email) {
            $isValid = false;
        }

        return $isValid;
    }

    public function validate_token($data)
    {
        global $wpdb;
        $table = $wpdb->prefix . "uepc_nonces";
        // GET nonce data using $_POST.
        $nonce = sanitize_key($data["nonce"]);

        // GET User IP using $_SERVER method.
        $ip_address = sanitize_text_field($_SERVER["REMOTE_ADDR"]);
        $secret_key = "uepc-update-preferences-premium";
        if (empty($nonce)) {
            return [
                "status" => false,
                "message" => esc_html__("Token required, Please create token and try again.", "universal-email-preference-center")
            ];
        }
        if (wp_verify_nonce($nonce, hash_hmac('md5', $ip_address, $secret_key))) {
            // Check if the nonce exists in the database
            $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE nonce = %s", $nonce));
            if (!empty($result)) {
                $wpdb->delete($table, array( 'id' => $result->id ));
                return [
                    "status" => true,
                    "message" => esc_html__("Token validation success.", 'universal-email-preference-center')
                ];
            }
        }

        return [
            "status" => false,
            "message" => esc_html__("Token expired, Please create new token and try again.", 'universal-email-preference-center')
        ];
    }

    public function uepc_frontend_layout()
    {
        return plugin_dir_path(dirname(__FILE__)) . 'public/partials/preference-center-free.php';
    }
}
