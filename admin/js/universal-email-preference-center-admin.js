(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

    $(function () {
        var $options_tab = $('#uepc-settings-options');
        var $options_container = $('#uepc-options-container');

        var $appearance_tab = $('#uepc-settings-appearance');
        var $appearance_container = $('#uepc-appearance-container');

        $options_tab.click(function (e) {
            e.preventDefault();
            $options_tab.addClass('nav-tab-active');
            $options_container.show();
            $appearance_container.hide();
            $appearance_tab.removeClass('nav-tab-active');
        });

        $appearance_tab.click(function (e) {
            e.preventDefault();
            $appearance_tab.addClass('nav-tab-active');
            $appearance_container.show();
            $options_container.hide();
            $options_tab.removeClass('nav-tab-active');
        });

        change_api_reference_link($('input[name="email_preference_center_centre_type"]:checked').val());

        $('input[name="email_preference_center_centre_type"]').change(function (e) {
           change_api_reference_link(this.value)
        });

        $("#check-all-list").prop('checked', $('.email-preference-list-checkbox:checked').length);

        $("#check-all-value-default").prop('checked', $('.email-preference-value-default-checkbox:checked').length);

        $("#check-all-value-reverse").prop('checked', $('.email-preference-value-reverse-checkbox:checked').length);
        
        $("#check-all-value-required").prop('checked', $('.email-preference-value-required-checkbox:checked').length);

        $(".email-preference-list-checkbox").change(function(){
            if( validate_premium(this, 'checkbox', !this.checked) ){
                $("#check-all-list").prop('checked', $('.email-preference-list-checkbox:checked').length);
            }
        });

        $(".email-preference-value-default-checkbox").change(function(){
            $("#check-all-value-default").prop('checked', $('.email-preference-value-default-checkbox:checked').length);
        });

        $(".email-preference-value-reverse-checkbox").change(function(){
            if( validate_premium(this, 'checkbox', false) ){
                $("#check-all-value-reverse").prop('checked', $('.email-preference-value-reverse-checkbox:checked').length);
            }
        });

        $(".email-preference-value-required-checkbox").change(function(){
                $("#check-all-value-required").prop('checked', $('.email-preference-value-required-checkbox:checked').length);
        });

        $("#check-all-list").change(function(){
            if( validate_premium(this, 'checkbox', true) ){
                $('.email-preference-list-checkbox').prop('checked', this.checked);
            }
        });

        $("#check-all-value-reverse").change(function(){
            if( validate_premium(this, 'checkbox', false) ){
                $('.email-preference-value-reverse-checkbox').prop('checked', this.checked);
            }
        });

        $("#check-all-value-default").change(function(){
            $('.email-preference-value-default-checkbox').prop('checked', this.checked);
        });

        $("#check-all-value-required").change(function(){
            $('.email-preference-value-required-checkbox').prop('checked', this.checked);
        });

        $(".uepc-sort-order-input").change(function(){
            validate_premium(this, 'number', 0)
        });

        $(".uepc-description-input").keyup(function(){
            validate_premium(this, 'textarea', "")
        });

        $("#uepc-scroll").click(function() {
            $('html, body').animate({
                scrollTop: $("#postbox-container-1").offset().top
            }, 2000);
        });

        // LOGS JS
        var acc = $(".accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }

        $('#delete').click(function (e) {
            var val = confirm("Please confirm to delete log file.");
            if (val === false) {
                return false;
            }
        });

        if($(".logDiv").length){
            $(".logDiv").scrollTop($(".logDiv")[0].scrollHeight);
        }
        
        if($('#upec-premium-js-validation').length < 1){
            $('#uepc-modal').dialog({
                title: 'Premium Add-On Required',
                dialogClass: 'wp-dialog',
                autoOpen: false,
                draggable: false,
                width: 'auto',
                modal: true,
                resizable: false,
                closeOnEscape: true,
                position: {
                    my: "center",
                    at: "center",
                    of: window
                },
                open: function () {
                    // close dialog by clicking the overlay behind it
                    $('.ui-widget-overlay').bind('click', function(){
                        $('#uepc-modal').dialog('close');
                    })
                },
                create: function () {
                    // style fix for WordPress admin
                    $('.ui-dialog-titlebar-close').addClass('ui-button');
                },
            });
        }

        function validate_premium(input, type, value) {
            if($('#upec-premium-js-validation').length < 1){
                $('#uepc-modal').dialog('open');
                switch (type) {
                    case "checkbox":
                        $(input).prop('checked', value);
                        break;
                    case "number":
                    case "textarea":
                        $(input).val(value);
                        break;
                }
                return false;
            }

            return true;
        }

        function change_api_reference_link(type){
            let link = '';
            switch (type) {
                case 'active_campaign':
                    link = 'https://help.activecampaign.com/hc/en-us/articles/207317590-Getting-started-with-the-API';
                    $("#api-create-help-text").show();
                    break;
                case 'iterable':
                    link = 'https://support.iterable.com/hc/en-us/articles/360043464871-API-Keys-';
                    $("#api-create-help-text").show();
                    break;
                default:
                    $("#api-create-help-text").hide();
                    break;
            }
            $("#api-create-help-link").attr('href', link);
        }
    });

})( jQuery );
