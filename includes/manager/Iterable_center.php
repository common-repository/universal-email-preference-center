<?php

class Iterable_center
{
    private $plugin_name;
    private $version;
    private $request;
    private $center;
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->request = new Request($this->plugin_name, $this->version);

        $this->center = esc_attr(get_option($this->plugin_name . '_center'));
    }

    public function validate()
    {
        $api = $this->request->api('campaigns/metrics');
        $url = sanitize_text_field( $api['url'] );

        $response = wp_remote_get($url, $api['args']);
        if (is_wp_error($response)) {
            return false;
        }

        if ($response['response']['code'] === 200) {
            return true;
        }

        return false;
    }

    public function get_lists()
    {
        $return = [];
        $lists  = $this->request->get("messageTypes", array('limit' => '100'));
        $channels = $this->request->get('channels');
        if (!empty($channels)) {
            $channels = $channels->channels;
        }

        foreach ($lists->messageTypes as $list) {
            if (is_object($list)) {
                $key = array_search($list->channelId, array_column($channels, 'id'));
                $channelName = $channels[$key]->name;
                $return[$channelName][$list->id] = (array)$list;
            }
        }

        return $return;
    }

    public function get_saved_lists()
    {
        $lists = get_option($this->plugin_name . '_list_data');
        $lists = (!$lists ? array() : $lists);
        return $lists;
    }

    public function admin_lists_layout()
    {
        return plugin_dir_path(__FILE__) . 'layouts/iterable-lists.php';
    }

    public function get_contact($email)
    {
        $email = str_replace(' ', '+', sanitize_email($email));
        $return = [];
        $contact = $this->request->get("users/$email");

        if (empty((array)$contact)) {
            global $current_user;
            wp_get_current_user();

            $contact = $this->create_contact($email);

            if ($contact->code == "Success") {
                $return = [
                    'first_name' => sanitize_text_field($current_user->first_name),
                    'last_name'  => sanitize_text_field($current_user->last_name),
                    'email'      => str_replace(' ', '+', sanitize_email($email))
                ];

                $return['lists'] = [];
                $return['unsubscribedChannelIds'] = [];
                $return['unsubscribedMessageTypeIds'] = [];
            }
        } else {
            if (!is_null($contact)) {
                $return = [
                    'id' => isset($contact->user->userId) ? sanitize_text_field($contact->user->userId) : "",
                    'first_name' => isset($contact->user->dataFields->firstName) ? sanitize_text_field($contact->user->dataFields->firstName) : "",
                    'last_name'  => isset($contact->user->dataFields->lastName) ? sanitize_text_field($contact->user->dataFields->lastName) : "",
                    'email'      => str_replace(' ', '+', sanitize_email($contact->user->email)) ?? ""
                ];

                $return['lists'] = $contact->user->dataFields->subscribedMessageTypeIds ?? [];
                $return['unsubscribedChannelIds'] = $contact->user->dataFields->unsubscribedChannelIds ?? [];
                $return['unsubscribedMessageTypeIds'] = $contact->user->dataFields->unsubscribedMessageTypeIds ?? [];
            }
        }

        uepc_log($this->center, "Contact API Responce.<br>". json_encode($contact), 'notice');

        return $return;
    }

    public function create_contact($email, $first_name = "", $last_name = "")
    {
        $contact = $this->request->post('users/update', [
            "email" => $email,
            "dataFields" => [
                "firstName" => $first_name,
                "lastName" => $last_name
            ],
            "preferUserId" => true,
            "mergeNestedObjects" => true
        ]);

        return $contact;
    }

    public function filter_iterable_list_reverse_enabled_ids($submitted_ids)
    {
        $ids['subscribed'] = [];
        $ids['unsubscribed'] = [];
        $lists = $this->get_saved_lists();
        foreach ($lists as $id => $item) {
            if (in_array($id, $submitted_ids) && !$item['reverse'] || !in_array($id, $submitted_ids) && $item['reverse']) {
                $ids['subscribed'][] = $id;
            } else {
                $ids['unsubscribed'][] = $id;
            }
        }
        return $ids;
    }

    public function update_preferences($email, $data)
    {
        $current_subscribed_list_ids = unserialize(urldecode($data['current_subscribed_list_ids']));
        $all_channel_ids = unserialize(urldecode($data['all_channel_ids']));
        if (!isset($data['subscribed_list_ids'])) {
            $data['subscribed_list_ids'] = array();
        }
        $submitted_list_ids = isset($data['subscribed_list_ids']) && !empty($data['subscribed_list_ids']) ? $data['subscribed_list_ids'] : [];
        $submitted_channel_ids = isset($data['subscribed_channel_ids']) && !empty($data['subscribed_channel_ids']) ? $data['subscribed_channel_ids'] : [];

        $subscribed_channel_ids = array_values(array_diff($all_channel_ids, $submitted_channel_ids));

        $contact = $this->get_contact($email);

        if (has_action('uepc_update_user_details_premium')) {
            do_action('uepc_update_user_details_premium', $contact, $data);
        }

        $lists = [];

        /**
         * Create array of our current subscribed lists and statuses
         */
        foreach ($current_subscribed_list_ids as $current_subscribed_list_id) {
            $status                               = in_array($current_subscribed_list_id, $submitted_list_ids) ? 1 : 2;  //1 = subscribed 2 = un-subscribed
            $lists[$current_subscribed_list_id] = $status;
        }

        /**
         * Loop through our submitted list IDs and mark them down as subscribed.
         */

        foreach ($submitted_list_ids as $submitted_list_id) {
            $lists[$submitted_list_id] = 1;
        }

        $submitted_list_ids_filter = array_map(function ($item) {
            return (int)$item;
        }, $submitted_list_ids);

        $subscribed_channel_ids_filter = array_map(function ($item) {
            return (int)$item;
        }, $subscribed_channel_ids);

        $filter_ids = $this->filter_iterable_list_reverse_enabled_ids($submitted_list_ids_filter);

        $update_data = [
            "email" => $contact['email'],
            "userId" => $contact['id'],
            "subscribedMessageTypeIds" => $filter_ids['subscribed'],
            "unsubscribedMessageTypeIds" => $filter_ids['unsubscribed'],
            "unsubscribedChannelIds" => $subscribed_channel_ids_filter
        ];

        if (isset($data['templateId']) && !empty($data['templateId'])) {
            $update_data['templateId'] = (int)$data['templateId'];
        }

        if (isset($data['campaignId']) && !empty($data['campaignId'])) {
            $update_data['campaignId'] = (int)$data['campaignId'];
        }

        $sync = $this->update_subscription($update_data);

        if ($sync->code == 'Success') {
            uepc_log($this->center, "User list update successfully.<br>".json_encode($sync), 'success');
        } else {
            uepc_log($this->center, "Error updating list status.<br>".json_encode($sync), 'error');
            wp_send_json('Error updating list status.', 500);
        }

        //We re grab the contact info from AC to include newly subscribed lists.
        return $this->get_contact($email);
    }

    public function update_subscription($data)
    {
        return $this->request->post('users/updateSubscriptions', $data);
    }
}
