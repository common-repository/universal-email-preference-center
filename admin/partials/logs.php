<div class="container">
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e('Logs', 'universal-email-preference-center'); ?></h1>
    </div>
    <div class="form_wrapper">
        <div class="">
            <form method="get" action="<?php echo esc_url(home_url('/wp-admin/admin.php?page=universal-email-preference-center-logs')); ?>">
                <input type="hidden" name="page" value="universal-email-preference-center-logs">
                <select name="log-file">
                    <?php 
                        if(!empty($logFiles)){
                            foreach ($logFiles as $file) {
                                echo "<option value='" . esc_attr($file) . "' " . esc_attr($file == $openFile ? "selected":"") . ">" . esc_attr($file) . "</option>";
                            } 
                        }
                    ?>
                </select>
                <button class="button-primary" type="submit" name="log-action" value="view"><?php esc_html_e('View', 'universal-email-preference-center'); ?></button>
                <button class="button-primary" type="submit" id="delete" name="log-action" value="delete"><?php esc_html_e('Delete', 'universal-email-preference-center'); ?></button>
                <span class="current_time">
                <?php 
                    echo sprintf(esc_html__('Current Time: %s','universal-email-preference-center'), date('d-M-Y h:i:s A (e)'))
                ?>
                
                </span>
            </form>
        </div>
    </div>
    <div class='logDiv'>
        <?php echo wp_kses_post($logData); ?>
    </div>
</div>