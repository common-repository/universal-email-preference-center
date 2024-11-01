<?php function create_row($ele, $list_data, $list, $channelName = null)
{ ?>
    <tr>
        <td>
            <?php $list_id = $list['id']; ?>
            <input class="email-preference-list-checkbox" type="checkbox" name="enabled[]" value="<?php esc_attr_e($list_id); ?>" checked>
            <input type="hidden" name="list_id[]" value="<?php esc_attr_e($list_id); ?>">
        </td>
        <td>
            <?php
                $checked = isset($list_data[$list_id]['required']) ? esc_attr($list_data[$list_id]['required']) : false;
            ?>
            <input class="email-preference-value-required-checkbox" type="checkbox" name="required[]" value="<?php esc_attr_e($list_id); ?>" <?php checked($checked, 1); ?>>
        </td>
        <td>
            <input type="number" name="sort_order[<?php esc_attr_e($list_id); ?>]"
                value="<?php echo isset($list_data[$list_id]['sort_order']) ? esc_attr($list_data[$list_id]['sort_order']) : 0; ?>"
                min="0" class="small-text uepc-sort-order-input" style="width:100%;">
        </td>
        <td>
            <input type="text" name="name[<?php esc_attr_e($list_id); ?>]"
                value="<?php echo empty($list_data[$list_id]['name']) ? esc_attr($list['name']) : esc_attr($list_data[$list_id]['name']); ?>"
                class="regular-text" style="width:100%;">
            <span class="description">
                <?php esc_attr_e('You can change the name of this list', 'universal-email-preference-center'); ?>
            </span>
        </td>
        <td>
            <textarea name="description[<?php esc_attr_e($list_id); ?>]" rows="2" class="large-text uepc-description-input"
                style="width:100%;"><?php echo isset($list_data[$list_id]['description']) ? esc_textarea($list_data[$list_id]['description']) : ""; ?></textarea>
        </td>
        <td>
            <?php if ($channelName): ?>
                <input type="hidden" name="channelName[<?php esc_attr_e($list_id); ?>]" value="<?php esc_attr_e($channelName); ?>">
                <input type="hidden" name="channelId[<?php esc_attr_e($list_id); ?>]" value="<?php esc_attr_e($list['channelId']); ?>">
            <?php endif; ?>
            <code><?php esc_attr_e($list_id); ?></code>
        </td>
    </tr>
<?php } ?>
<div class="inside">
    <p>
        <?php esc_attr_e('Each of your Email Lists are outputted below.  You could then toggle if this list is included in email preference center on the front-end of site. You could also input a description for each list which would be shown to the visitor.', 'universal-email-preference-center'); ?>
    </p>
    <form method="post">
        <table class="wp-list-table widefat fixed striped posts">
            <thead>
                <tr>
                    <th width="12%">
                        <span>
                            <input type="checkbox" id="check-all-list" style="margin: auto;"> Enabled?
                        </span>
                    </th>
                    <th width="12%">
                        <span>
                            <input type="checkbox" id="check-all-value-required" style="margin: auto;">
                            Required
                        </span>
                    </th>
                    <th width="6%">Order</th>
                    <th width="25%">List Name</th>
                    <th width="23%">Description</th>
                    <th width="8%">List ID</th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php
            $channelNameStore = '';
foreach ($lists as $channelName => $list) {
    create_row($this, $list_data, $list);
};
?>
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