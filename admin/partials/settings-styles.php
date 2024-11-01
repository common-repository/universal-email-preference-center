<div class="inside">
    <p><?php esc_html_e('If you look at the source when viewing your preference center, you will see there are various classes attached to elements to help you style.', 'universal-email-preference-center'); ?></p>
    <p><strong><?php esc_html_e('This plugin specifically does NOT include any styling as to make it easier to integrate with various themes.', 'universal-email-preference-center'); ?></strong></p>
    <p><?php esc_html_e('Below is some basic styles which you can add to your Additional CSS section in Appearance->Customize to give you a basic idea of how you can style your preference center.', 'universal-email-preference-center'); ?></p>
    <pre class="prettyprint">

    .universal-email-preference-center {
        border:2px solid #eee;
        padding:20px;
        margin-bottom:20px;
    }

    .uepc-email {
        height: 25px;
    }

    .uepc-welcome-text h3 {
        color: green;
    }

    .uepc-list {
        border-bottom: 2px dashed #ccc;
        padding-bottom:10px;
        margin-bottom:20px;
    }

    .uepc-list:last-child {
        border-bottom: none;
    }

    .uepc-list-title {
        color:red;
    }

    .uepc-action-text {
        display:block;
        font-weight:normal;
        font-size:13px;
        font-style: italic;
    }

    .uepc-success {
        color: green;
    }

    .uepc-error {
        color: red;
    }

</pre>
</div>