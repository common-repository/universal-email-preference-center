<?php

if ($valid) :
    require_once apply_filters('uepc_admin_lists_layout', []);
else:
    echo wp_kses_post($app_config_error);
endif;
