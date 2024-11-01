<div id="postbox-container-1" class="postbox-container">
    <div class="postbox">
        <h2 class="hndle">
            <?php esc_html_e('Usage & Shortcodes', 'universal-email-preference-center'); ?>
        </h2>

        <div class="inside">
            <p>
                <?php esc_html_e('Usage is simple.  Copy and paste the shortcode below to any page or post on your site and the email preference center will appear.', 'universal-email-preference-center'); ?>
            </p>
            <p><input type="text" value="[universal-email-preference-center]" class="code" style="width:100%"></p>

            <?php if (!function_exists("uepcp_fs") || (function_exists("uepcp_fs") &&uepcp_fs()->is_not_paying())): ?>
            <div class="hilite">
                <h3> <?php esc_html_e('Upgrade to Premium Version', 'universal-email-preference-center'); ?></h3>
                <?php if (uepc_freemius()->has_addons()): ?>
                <a href="<?php echo esc_url(uepc_freemius()->_get_admin_page_url('addons')) ?>" class="button button-secondary">Add Premium</a>
                <?php endif ?>
                <hr>
                <p>
                    <strong><?php esc_html_e('The following features are only available in the premium version.', 'universal-email-preference-center'); ?></strong>
                </p>
            </div>
            <?php endif; ?>
            <h3>
                <?php esc_html_e('Shortcode Options', 'universal-email-preference-center'); ?>
            </h3>
            <p>
                <?php esc_html_e('The following are attributes that you can place inside the shortcode for additional functionality.', 'universal-email-preference-center'); ?>
            </p>
            <ul class="uepc-shortcode-options">
                <li><code><?php esc_html_e('show_action_text', 'universal-email-preference-center'); ?></code>
                    <ul>
                        <li>
                            <span class="description">
                                <?php esc_html_e('Default is True. If false, this will hide all action text.  I.E. "To subscribe to this list check this box and click the submit button at bottom of page."', 'universal-email-preference-center'); ?>
                            </span>
                        </li>
                        <li>
                            <?php esc_html_e('Example:', 'universal-email-preference-center'); ?><br>
                            <input type="text" value="[universal-email-preference-center show_action_text=false]" class="code">
                        </li>
                    </ul>
                </li>

                <li><code><?php esc_html_e('show_list_descriptions', 'universal-email-preference-center'); ?></code>
                    <ul>
                        <li>
                            <span class="description">
                                <?php esc_html_e('Default is True. If false, this will hide all list descriptions', 'universal-email-preference-center'); ?>
                            </span>
                        </li>
                        <li>
                            <?php esc_html_e('Example:', 'universal-email-preference-center'); ?><br>
                            <input type="text" value="[universal-email-preference-center show_list_descriptions=false]" class="code">
                        </li>
                    </ul>
                </li>
                <li><code><?php esc_html_e('show_welcome_text', 'universal-email-preference-center'); ?></code>
                    <ul>
                        <li>
                            <span class="description">
                                <?php esc_html_e('Default is True. If false, this will hide the "Welcome {email}, you are subscribed to the following lists!" text.', 'universal-email-preference-center'); ?>
                            </span>
                        </li>
                        <li>
                            <?php esc_html_e('Example:', 'universal-email-preference-center'); ?><br>
                            <input type="text" value="[universal-email-preference-center show_welcome_text=false]" class="code">
                        </li>
                    </ul>
                </li>
                <li><code><?php esc_html_e('list_ids', 'universal-email-preference-center'); ?></code>
                    <ul>
                        <li>
                            <span class="description">
                                <?php esc_html_e('Optional:  Add in the list ids separated by commas that you want the specific shortcode to display.', 'universal-email-preference-center'); ?>
                            </span>
                        </li>
                        <li>
                            <?php esc_html_e('Example:', 'universal-email-preference-center'); ?><br>
                            <input type="text" value="[universal-email-preference-center list_ids=2,12,32]" class="code">
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
    <div class="postbox">
        <h2 class="hndle">
            <?php esc_html_e('Have Questions? Request a Feature?', 'universal-email-preference-center'); ?>
        </h2>

        <div class="inside">
            <p>
                <?php esc_html_e('If you have any questions or want to suggest a feature request please reach out to me at wpressapi.com.  If you really dig this plugin consider leaving me a review!', 'universal-email-preference-center'); ?>
            </p>
            <h3>
                <?php esc_html_e('Additional Help', 'universal-email-preference-center'); ?>
            </h3>
            <ul>
                <li><a href="https://wpressapi.com/plugins/universal-email-preference-center/#usage" target="_blank">
                        <?php esc_html_e('Usage', 'universal-email-preference-center'); ?>
                    </a></li>
                <li><a href="https://wpressapi.com/plugins/universal-email-preference-center/#faq" target="_blank">
                        <?php esc_html_e('FAQ', 'universal-email-preference-center'); ?>
                    </a></li>
                <li><a href="https://wpressapi.com/feature-requests/" target="_blank">
                        <?php esc_html_e('Feature Requests', 'universal-email-preference-center'); ?>
                    </a></li>
                <li><a href="https://wpressapi.com/documentation/category/universal-email-preference-center/"
                        target="_blank">
                        <?php esc_html_e('Documentation', 'universal-email-preference-center'); ?>
                    </a></li>
            </ul>
        </div>

    </div>
</div>