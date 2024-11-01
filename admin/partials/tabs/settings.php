<div class="inside">
    <form action="options.php" method="post">
        <?php settings_fields(UEPC_PLUGIN_NAME . '_api'); ?>
        <input type="hidden" name="<?php esc_attr_e(UEPC_PLUGIN_NAME,'universal-email-preference-center'); ?>_api_setting_update_status" value=true>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="<?php esc_attr_e(UEPC_PLUGIN_NAME,'universal-email-preference-center'); ?>_center"><?php esc_html_e('Select Email Center', 'universal-email-preference-center'); ?></label>
                    </th>
                    <td class="forminp">
                        <?php
                        $email_centers = Universal_Email_Preference_Center_Admin::email_centers();
                        if(!empty($email_centers)){

                            foreach ($email_centers as $key => $email_center) { ?>
                                <input type="radio" id="<?php esc_attr_e(UEPC_PLUGIN_NAME,'universal-email-preference-center'); ?>_center"
                                    name="<?php esc_attr_e(UEPC_PLUGIN_NAME,'universal-email-preference-center'); ?>_center" value="<?php esc_attr_e($key); ?>"
                                    required <?php esc_attr_e(get_option(UEPC_PLUGIN_NAME . '_center') == $key ? "checked" : ""); ?>> <?php esc_attr_e($email_center['name']); ?> </br>
                            <?php } 
                    
                        } ?>
                        <a href="mailto:test@example.com?subject=Request Integration!">Request Integration</a>
                        <p class="description">
                            <?php esc_html_e('This is selection for your Universal email preference center', 'universal-email-preference-center'); ?>
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="<?php esc_attr_e(UEPC_PLUGIN_NAME . '_url'); ?>"><?php esc_html_e('Email Center URL', 'universal-email-preference-center'); ?></label>
                    </th>
                    <td class="forminp">
                        <input id="<?php esc_attr_e(UEPC_PLUGIN_NAME . '_url'); ?>"
                            name="<?php esc_attr_e(UEPC_PLUGIN_NAME . '_url'); ?>" type="url"
                            value="<?php echo esc_url(get_option(UEPC_PLUGIN_NAME . '_url')); ?>" autocomplete="off"
                            autocorrect="off" autocapitalize="off" spellcheck="false" class="large-text"
                            required="required">
                        <p class="description">
                            <?php esc_html_e('This is the URL to your Universal email preference center installation', 'universal-email-preference-center'); ?>
                        </p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" class="titledesc">
                        <label for="<?php esc_attr_e(UEPC_PLUGIN_NAME . '_key'); ?>"><?php esc_html_e('Email Center API Key', 'universal-email-preference-center'); ?></label>
                    </th>
                    <td class="forminp">
                        <input id="<?php esc_attr_e(UEPC_PLUGIN_NAME . '_key'); ?>"
                            name="<?php esc_attr_e(UEPC_PLUGIN_NAME . '_key'); ?>" type="text"
                            value="<?php esc_attr_e(get_option(UEPC_PLUGIN_NAME . '_key')); ?>" autocomplete="off"
                            autocorrect="off" autocapitalize="off" spellcheck="false" class="large-text"
                            required="required">
                        <p class="description" id="api-create-help-text">
                            <?php echo  sprintf('If you do not know your API Key or need help finding it, %s for Universal email preference center article.', '<a href="" target="_blank" id="api-create-help-link">click here</a>'); ?>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button('Save API Settings'); ?>
    </form>
</div>