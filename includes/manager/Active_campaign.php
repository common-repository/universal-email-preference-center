<?php

class Active_campaign
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
        $api = $this->request->api("");
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
        $lists  = $this->request->get("lists", array('limit' => '100'));

        if (!empty($lists)) {
            foreach ($lists->lists as $list) {
                if (is_object($list)) {
                    $return[$list->id] = array(
                        'id' => $list->id,
                        'name' => $list->name
                    );
                }
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
        return plugin_dir_path(__FILE__) . 'layouts/active-campagin-lists.php';
    }

    public function get_contact($email)
    {
        $email = str_replace(' ', '+', sanitize_email($email));
        $return = [];
        $contact = $this->request->get('contacts', array('email' => $email));
        if (empty($contact->contacts)) {
            $contact = $this->create_contact(sanitize_email($email));
            $return = [
                'id'         => sanitize_text_field($contact->id),
                'first_name' => sanitize_text_field($contact->firstName),
                'last_name'  => sanitize_text_field($contact->lastName),
                'email'      => str_replace(' ', '+', sanitize_email($contact->email))
            ];

            $list_url = $contact->links->contactLists;
        } else {
            $return = [
                'id'         => sanitize_text_field($contact->contacts[0]->id),
                'first_name' => sanitize_text_field($contact->contacts[0]->firstName),
                'last_name'  => sanitize_text_field($contact->contacts[0]->lastName),
                'email'      => str_replace(' ', '+', sanitize_email($contact->contacts[0]->email))
            ];

            $list_url = esc_url($contact->contacts[0]->links->contactLists);
        }

        $get_lists = $this->request->get($list_url);

        $lists = [];

        if (!is_null($get_lists)) {
            foreach ($get_lists->contactLists as $list) {
                if ($list->status === '1') {
                    $lists[] = $list->list;
                }
            }
        }

        $return['lists'] = $lists;

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

    public function update_preferences($email, $data)
    {
        $current_subscribed_list_ids = unserialize(urldecode($data['current_subscribed_list_ids']));
        if (!isset($data['subscribed_list_ids'])) {
            $data['subscribed_list_ids'] = array();
        }
        $submitted_list_ids = $data['subscribed_list_ids'];

        $contact = $this->get_contact($email);

        if (has_action('uepc_update_user_details_premium')) {
            do_action('uepc_update_user_details_premium', $contact, $data);
        }

        $lists = [];
        /**
         * Create array of our current subscribed lists and statuses
         */
        foreach ($current_subscribed_list_ids as $current_subscribed_list_id) {
            $status = (in_array($current_subscribed_list_id, $submitted_list_ids) ? 1 : 2);
            //1 = subscribed 2 = un-subscribed
            $lists[$current_subscribed_list_id] = $status;
        }
        /**
         * Loop through our submitted list IDs and mark them down as subscribed.
         */
        foreach ($submitted_list_ids as $submitted_list_id) {
            $lists[$submitted_list_id] = 1;
        }

        foreach ($lists as $id => $status) {
            $sync = $this->update_subscription([
                'list'    => $id,
                'contact' => $contact['id'],
                'status'  => $status,
            ]);
        }

        if (isset($sync->errors)) {
            uepc_log($this->center, "Error updating list status.<br>".json_encode($sync), 'error');
            wp_send_json('Error updating list status.', 500);
        } else {
            uepc_log($this->center, "User list update successfully.<br>".json_encode($sync), 'success');
        }
        //We re grab the contact info from AC to include newly subscribed lists.
        return $this->get_contact($email);
    }

    public function update_subscription($data)
    {
        return $this->request->post('contactLists', ['contactList' => $data]);
    }
}
