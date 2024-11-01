(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

        var $btn = $('.uepc-save-button'),
            $form = $('.universal-email-preference-center-form'),
            $status = $('.universal-email-preference-status-text'),
            $currentUrl = window.location.href,
            $currentSubscribedLists = $('#current_subscribed_list_ids');

        $form.submit(function (e) {
            e.preventDefault();
            $status.html(uepc_ajax.loading).show();
            $btn.val(uepc_ajax.saving_text).prop("disabled", true);

            $.ajax({
                url: uepc_ajax.ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'update_preferences',
                    data: $form.serialize(),
                    security: uepc_ajax.update_nonce
                }
            })
                .done(function (res) {
                    if (res?.status) {
                        $status.removeClass('uepc-error').addClass('uepc-success').html(uepc_ajax.success_text);
                        $btn.val(uepc_ajax.save_text).prop("disabled", false);
                        $currentSubscribedLists.val(res?.data);

                        if(!uepc_ajax.enable_tamper_protection && !$currentUrl.includes("?email=")){
                            window.location = location.href.replace(location.search, '') + '?email='+$('.uepc-email').val();
                        }
                    }else{
                        $status.addClass('uepc-error').html(res?.message)
                    }

                })
                .fail(function (result) {
                    $status.removeClass('uepc-success').addClass('uepc-error').html(uepc_ajax.error_text);
                    $btn.val(uepc_ajax.save_text).prop("disabled", false);
                });
        });

        $('#upec-get-token-btn').click( function () {
            $("#tamper-protection-alert").html();
            $(this).val("Please Wait...");
            $.ajax({
                url: uepc_ajax.ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'uepc_get_token',
                    security: uepc_ajax.update_nonce,
                    data: {
                        email: $('input[name=email]').val(),
                        url: window.location.href
                    }
                }
            })
            .done(function (data) {
                if (data.status) {
                    $("#tamper-protection-alert").html(data?.message);
                }else{
                    $("#tamper-protection-alert").html(data?.message);
                }
                $("#upec-get-token-btn").val("Get Token");
            })
            .fail(function (xhr, status, error) {
                $("#tamper-protection-alert").html(`<div class="uepc-alert uepc-status-text" style="color:red;">Something went wrong...</div>`);
                $("#upec-get-token-btn").val("Get Token");
            });
        });
        
        $('.uepc_channel_checkbox').click(function(){
            $(`.${this.id}:visible`).prop('checked', this.checked);
            if ( !this.checked && $(`.${this.id}:hidden`).length) {
                alert("You can't, able to unsubscribe from the channel because the channel has compulsory message types. But other message types are disabled now.");
                $(this).prop('checked', true)
            }
        });

        $('.uepc_list_item').click(function(){
            $(`#${this.classList[1]}:visible`).prop('checked', $(`.${this.classList[1]}:checked`).length);
        });
    });

})( jQuery );
