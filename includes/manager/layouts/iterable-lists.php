<?php function create_row($ele, $list_data, $list, $channelName = null)
{
    $list_id = $list['id']; ?>
    <tr>
        <td>
            <span class="tooltip" data-tooltip="Enabled/Disable List">
                <input class="email-preference-list-checkbox" type="checkbox" name="enabled[]" value="<?php esc_attr_e($list_id); ?>" checked>
            </span>
            <input type="hidden" name="list_id[]" value="<?php esc_attr_e($list_id); ?>">
        </td>
        <td>
            <?php
                $default_checked = isset($list_data[$list_id]['default']) ? esc_attr($list_data[$list_id]['default']) : false;
            ?>
            <span class="tooltip" data-tooltip="Default Value Enabled/Disable">
                <input class="email-preference-value-default-checkbox" type="checkbox" name="default[]" value="<?php esc_attr_e($list_id); ?>" <?php checked($default_checked, 1); ?>>
            </span>
        </td>
        <td>
            <span class="tooltip" data-tooltip="Reverse Value Enabled/Disable">
                <input class="email-preference-value-reverse-checkbox" type="checkbox" name="reverse[]" value="<?php esc_attr_e($list_id); ?>">
            </span>
        </td>
        <td>
            <?php
                $checked = isset($list_data[$list_id]['required']) ? esc_attr($list_data[$list_id]['required']) : false;
            ?>
            <span class="tooltip" data-tooltip="Required Enabled/Disable">
                <input class="email-preference-value-required-checkbox" type="checkbox" name="required[]" value="<?php esc_attr_e($list_id); ?>" <?php checked($checked, 1); ?>>
            </span>
        </td>
        <td>
            <input type="number" name="sort_order[<?php esc_attr_e($list_id); ?>]"
                value="<?php echo isset($list_data[$list_id]['sort_order']) ? esc_attr($list_data[$list_id]['sort_order']) : 0; ?>" min="0" class="small-text uepc-sort-order-input" style="width:100%;">
        </td>
        <td>
            <input type="text" name="name[<?php esc_attr_e($list_id); ?>]"
                value="<?php echo empty($list_data[$list_id]['name']) ? esc_attr($list['name']) : esc_attr($list_data[$list_id]['name']); ?>"
                class="regular-text" style="width:100%;">
            <span class="description">
                <?php !empty($list_data[$list_id]['name']) && $list['name'] != $list_data[$list_id]['name'] ? esc_attr_e('Orignal list name is "'.esc_attr($list['name']).'"') : esc_attr_e('You can change the name of this list', 'universal-email-preference-center'); ; ?>
            </span>
        </td>
        <input type="hidden" name="subscriptionPolicy[<?php esc_attr_e($list_id); ?>]"
            value="<?php esc_attr_e($list['subscriptionPolicy']); ?>">
        <td style="font-weight: 600;"><span style="background: yellow;">
                <?php esc_attr_e($list['subscriptionPolicy']); ?>
            </span></td>
        <td>
            <textarea name="description[<?php esc_attr_e($list_id); ?>]" rows="2" class="large-text uepc-description-input"
                style="width:100%;"><?php echo isset($list_data[$list_id]['description']) ? esc_textarea($list_data[$list_id]['description']) : ""; ?></textarea>
        </td>
        <td>
            <?php if ($channelName): ?>
                <input type="hidden" name="channelName[<?php esc_attr_e($list_id); ?>]" value="<?php esc_attr_e($channelName) ?>">
                <input type="hidden" name="channelId[<?php esc_attr_e($list_id); ?>]" value="<?php esc_attr_e($list['channelId']) ?>">
            <?php endif; ?>
            <code><?php esc_attr_e($list_id); ?></code>
        </td>
    </tr>
<?php } ?>
<div class="inside">
    <p>
        <?php esc_attr_e('Each of your Email Lists are outputted below.  You could then toggle if this list is included in email preference center on the front-end of site.  You could also input a description for each list which would be shown to the visitor.', 'universal-email-preference-center'); ?>
    </p>
    <form method="post">
        <table class="wp-list-table widefat fixed striped posts uepc">
            <thead>
                <tr>
                    <th width="1%">
                        <span class="tooltip" data-tooltip="Enabled/Disable All Lists">
                            <input type="checkbox" id="check-all-list" style="margin: auto;">
                        </span>
                    </th>
                    <th width="1%">
                        <span class="tooltip" data-tooltip="Default Enabled/Disable All Lists">
                            <input type="checkbox" id="check-all-value-default" style="margin: auto;">
                        </span>
                    </th>
                    <th width="1%">
                        <span class="tooltip" data-tooltip="Reverse All Lists Value">
                            <input type="checkbox" id="check-all-value-reverse" style="margin: auto;">
                        </span>
                    </th>
                    <th width="1%">
                        <span class="tooltip" data-tooltip="Required All Lists">
                            <input type="checkbox" id="check-all-value-required" style="margin: auto;">
                        </span>
                    </th>
                    <th width="2%">Order</th>
                    <th width="14%">List Name</th>
                    <th width="3%">Policy</th>
                    <th width="13%">Description</th>
                    <th width="3%">List ID</th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php $channelNameStore = '' ?>
                <?php foreach ($lists as $channelName => $list):
                    if ($channelNameStore != $channelName) {
                        $channelNameStore = $channelName;
                        $class_data = Universal_Email_Preference_Center::iterable_class_search('name', $channelName, $list_data);
                        $channelId = isset($list[array_key_first($list)]['channelId']) ? esc_attr($list[array_key_first($list)]['channelId']) : ''; ?>
                        <tr>
                            <td>
                                <span class="tooltip" data-tooltip="Enabled/Disable List">
                                    <input class="email-preference-list-checkbox channel-checkbox" value=true type="checkbox" name="channelNameUpdate[<?php esc_attr_e($channelName); ?>][enabled]" style="margin: 1em 0;" checked>
                                </span>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <span class="tooltip" data-tooltip="Required Enabled/Disable List">
                                    <input class="email-preference-value-required-checkbox" value=true type="checkbox" name="channelNameUpdate[<?php esc_attr_e($channelName); ?>][required]" <?php checked(isset($class_data['required']) ? (bool) $class_data['required'] : "", 1, 1); ?> style="margin: 1em 0;">
                                </span>
                            </td>
                            <td></td>
                            <td><h3>
                                <input name="channelNameUpdate[<?php esc_attr_e($channelName); ?>][name]" value="<?php (isset($class_data['updated_name']) && !empty($class_data['updated_name'])) ? esc_attr_e($class_data['updated_name']) : esc_attr_e($channelName); ?>" class="regular-text" style="padding: 0 8px;line-height: 2;min-height: 30px;width: 100%">
                                <span class='description'><?php echo (isset($class_data['updated_name']) && $class_data['updated_name'] != $class_data['name']) ? 'Orignal channel name is "'.esc_attr($channelName).'"' : "You can edit the name of this Channel"; ?></span>
                            </h3></td>
                            <td></td><td></td><td><code><?php esc_attr_e($channelId); ?></code></td>
                        </tr>
                        <?php
                    }

                    foreach ($list as $key => $item) {
                        create_row($this, $list_data, $item, $channelName);
                    }
                endforeach; ?>
            </tbody>
        </table>
        <input type="hidden" name="updated" value="true" />
        <?php wp_nonce_field('save_list_data', 'save_list_data_nonce'); ?>
        <div style="display: flex;justify-content: space-between;">
            <?php submit_button('Save List Data'); ?>
            <div>
                <h3 style="margin: 6px 0px;">Pages Where Shortcode Used</h3>
                <?php
                    if (has_action('get_uepc_pages')) {
                        do_action('get_uepc_pages');
                    }
?>
            </div>
        </div>
    </form>
</div>
<?php Universal_Email_Preference_Center::dialog_html(); ?>