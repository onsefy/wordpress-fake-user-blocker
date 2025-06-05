<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_notices', 'onsefy_generic_block_notice');

function onsefy_generic_block_notice()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $notice_shown = get_option('onsefy_generic_notice');

    if ($notice_shown !== 'dismissed') {
        ?>
        <div class="notice notice-warning is-dismissible onsefy-block-notice">
            <p><strong>OnSefy:</strong> <?php _e('Some users are blocked during registrations. Please check the OnSefy application for more details.','onsefy-fraud-blocker'); ?></p>
        </div>
        <script>
            (function($){
                $(document).on('click', '.onsefy-block-notice .notice-dismiss', function(){
                    $.post(ajaxurl, {
                        action: 'onsefy_dismiss_generic_notice'
                    });
                });
            })(jQuery);
        </script>
        <?php
    }
}

add_action('wp_ajax_onsefy_dismiss_generic_notice', 'onsefy_dismiss_generic_notice');

function onsefy_dismiss_generic_notice()
{
    if (current_user_can('manage_options')) {
        update_option('onsefy_generic_notice', 'dismissed');
        wp_send_json_success();
    } else {
        wp_send_json_error('Unauthorized');
    }
}
