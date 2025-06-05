<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('user_register', 'onsefy_validate_user_registration', 20, 1);

function onsefy_validate_user_registration($user_id)
{

    $options = get_option('onsefy_settings');

    // Check if settings exist
    if (empty($options['bearer_token']) || empty($options['service_id']) || empty($options['plan_type'])) {
        // Missing config, allow registration
        return;
    }

    $bearer_token = sanitize_text_field($options['bearer_token']);
    $service_id = sanitize_text_field($options['service_id']);
    $plan_type = sanitize_text_field($options['plan_type']);
    $risk_threshold = floatval($options['risk_threshold'] ?? 1.00);

    $api_base_uri = $plan_type === 'paid' ? 'https://api.onsefy.com' : 'https://free-api.onsefy.com';

    $user_info = get_userdata($user_id);
    if (!$user_info) {
       return;
    }

    // Prepare data
    $data = [
        //'phone'      => '', // Not available by default, leave empty or extend user meta
        'email'      => $user_info->user_email,
        'ip'         => onsefy_get_user_ip(),
        'name'       => $user_info->display_name ?: $user_info->user_login,
        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '',
    ];

    $args = [
        'body'        => wp_json_encode($data),
        'headers'     => [
            'Authorization' => 'Bearer ' . $bearer_token,
            'X-Service-Id'  => $service_id,
            'Content-Type'  => 'application/json',
        ],
        'timeout'     => 10,
        'data_format' => 'body',
    ];

    $response = wp_remote_post("{$api_base_uri}/v1/validate/user", $args);


    if (is_wp_error($response)) {
        // API failed, allow registration but log error
        error_log('OnSefy API request failed: ' . $response->get_error_message());
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);
    $response_data = json_decode($response_body, true);

    if ($response_code !== 200 || !isset($response_data['summary']['risk_score'])) {
        // Invalid response, allow registration but log
        error_log('OnSefy API invalid response: ' . $response_body);
        return;
    }

    $risk_score = floatval($response_data['summary']['risk_score']);

    if ($risk_score > $risk_threshold) {
        // Block user by deleting immediately
        require_once ABSPATH . 'wp-admin/includes/user.php';
        wp_delete_user($user_id);

        // Set transient for admin notice
        set_transient('onsefy_blocked_user_' . get_current_user_id(), true, 30);
        if (!get_option('onsefy_generic_notice')) {
            update_option('onsefy_generic_notice', 'active');
        }

        // Stop registration by redirecting with error
        wp_die(
            __('Your registration was blocked due to suspicious activity detected.', 'onsefy-fraud-blocker'),
            __('Registration Blocked', 'onsefy-fraud-blocker'),
            ['back_link' => true]
        );
    }
}

function onsefy_get_user_ip() {
    $ip = '';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // X-Forwarded-For can contain multiple IPs (comma-separated)
        $ip_parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ip_parts[0]);
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    // Handle IPv6 localhost and empty case
    if (empty($ip) || $ip === '::1') {
        $ip = '127.0.0.1';
    }

    return sanitize_text_field($ip);
}

