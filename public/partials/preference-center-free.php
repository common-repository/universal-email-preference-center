<?php if (!is_null($email)) : ?>
    <div class="uepc-welcome-text">
        <h3><?php echo sprintf(esc_html__('Welcome %s , you are subscribed to the following lists.','universal-email-preference-center'), str_replace(' ', '+', sanitize_text_field($email))); ?> </h3>
    </div>
<?php endif; ?>
<?php if (count($lists) > 0) : $channelName = ''; ?>
    <?php foreach ($lists as $list) :
        $enable_input_checkbox = in_array($list['id'], $subscribed_lists);

        if(!$enable_input_checkbox) {
            if(isset($contact['unsubscribedMessageTypeIds']) && !in_array($list['id'], $contact['unsubscribedMessageTypeIds'])) {
                $enable_input_checkbox = (bool)esc_attr($list['default']);
            }
        }

        $list_style = '';
        $channel_id = isset($list['channel']) ? esc_attr($list['channel']['id']) : '';

        if (isset($list['required']) && (bool)$list['required']) {
            $list_style = 'display: none;';
        }

        if (isset($list['channel']['name']) && $list['channel']['name'] != $channelName) {
            $channel_style = '';
            $checked = checked(!in_array($channel_id, $unsubscribed_channels), 1, 0);

            if (isset($list['channel']['required']) && (bool)$list['channel']['required']) {
                $channel_style = 'display: none;';
                $checked = "checked='checked'";
            }

            $channelName = isset($list['channel']['name']) ? esc_attr($list['channel']['name']) :''; ?>

            <div class="channelNameWrapper">
                <div>
                    <h4>
                        <label class="uepc-channel-name">
                            <?php isset($list['channel']['updated_name']) && !empty($list['channel']['updated_name']) ? esc_attr_e($list['channel']['updated_name']) : esc_attr_e($list['channel']['name']); ?>
                        </label>
                    </h4>
                </div>
                <div style="<?php esc_attr_e($channel_style); ?>">
                    Subscribed
                    <input type="checkbox" class="uepc_channel_checkbox" id="channel_<?php esc_attr_e($channel_id); ?>" name="subscribed_channel_ids[]" value="<?php esc_attr_e($channel_id); ?>" style="transform: scale(1.5);margin-left: 12px;" <?php esc_attr_e($checked); ?>>
                </div>
            </div>
            <?php
        } ?>
        <div class="uepc-list">
            <?php if ($enable_input_checkbox) : ?>
                <label class="uepc-list-title"><input type="checkbox" name="subscribed_list_ids[]" value="<?php esc_attr_e($list['id']); ?>" checked="checked" class="uepc_list_item channel_<?php esc_attr_e($channel_id); ?>" style="<?php esc_attr_e($list_style); ?>"> <?php esc_attr_e($list['name']); ?></label>
                <span class="uepc-action-text"><?php esc_html_e('To un-subscribe to this list uncheck this box and click the submit button at bottom of page.', 'universal-email-preference-center'); ?></span>
            <?php else : ?>
                <label class="uepc-list-title"><input type="checkbox" name="subscribed_list_ids[]" value="<?php esc_html_e($list['id']); ?>" class="uepc_list_item channel_<?php esc_attr_e($channel_id); ?>" style="<?php esc_attr_e($list_style); ?>"> <?php esc_attr_e($list['name']); ?></label>
                <span class="uepc-action-text"><?php esc_html_e('To subscribe to this list check this box and click the submit button at bottom of page.', 'universal-email-preference-center'); ?></span>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

<?php endif; ?>