<?php

class Request
{
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    public function api($endpoint)
    {
        $remote_url = esc_attr(get_option($this->plugin_name . '_url'));
        $key = esc_attr(get_option($this->plugin_name . '_key'));
        $center = esc_attr(get_option($this->plugin_name . '_center'));
        $keyname = $version = null;

        switch (strtolower($center)) {
            case 'iterable':
                $version = '/api/';
                $keyname = 'Api-Key';
                break;
            case 'active_campaign':
                $version = '/api/3/';
                $keyname = 'Api-Token';
                break;
        }

        $url = $remote_url . $version . $endpoint;

        if (strpos($endpoint, $remote_url) !== false) {
            $url = $endpoint;
        }

        $args = ['headers' => [$keyname => $key]];

        return array('args' => $args, 'url' => $url);
    }

    public function get($endpoint, $params = array())
    {
        $api = $this->api($endpoint);
        if(empty($api['url'])){
            return;
        }
        $url = $api['url'];

        if (count($params) > 0) {
            $url = $api['url'] . '?' . http_build_query($params);
        }

        $response = wp_remote_get($url, $api['args']);
        $body     = wp_remote_retrieve_body($response);
        return json_decode($body);
    }

    public function post($endpoint, $params = array())
    {
        $api = $this->api($endpoint);
        if(empty($api['url'])){
            return;
        }
        $url = $api['url'];

        $args = array_merge($api['args'], ['body' => json_encode($params)]);
        $response = wp_remote_post($url, $args);
        $body     = wp_remote_retrieve_body($response);

        return json_decode($body);
    }
}
