<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://draglabs.com
 * @since      1.0.0
 *
 * @package    Universal_Email_Preference_Center
 * @subpackage Universal_Email_Preference_Center/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div class="uepc-settings-wrap">

        <h1><?php esc_html_e('Universal email preference center settings', 'universal-email-preference-center'); ?></h1>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <!-- Here are our tabs -->
                    <nav class="nav-tab-wrapper">
                        <?php Universal_Email_Preference_Center_Admin::tabs_layout($tab,$valid); ?>
                    </nav>

                    <div class="tab-content">
                        <?php
                        $file = apply_filters('uepc_tab_content_layout', $tab);
                            if (file_exists($file)) {
                                require_once $file;
                            } else {
                                esc_html_e("You are trying to access wrong path.");
                                echo html_entity_decode(esc_html("<br>"));
                                echo esc_url("$file");
                            }
                            ?>
                    </div>
                </div>
                <?php require_once plugin_dir_path(__FILE__) . 'sidebar.php'; ?>
            </div>
        </div>
    </div>
</div>