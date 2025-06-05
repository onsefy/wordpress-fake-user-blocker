<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'onsefy_add_admin_menu');
add_action('admin_init', 'onsefy_settings_init');

function onsefy_add_admin_menu()
{
    add_options_page(
        __('OnSefy Fraud Blocker Settings', 'onsefy-fraud-blocker'),
        __('OnSefy Fraud Blocker', 'onsefy-fraud-blocker'),
        'manage_options',
        'onsefy_fraud_blocker',
        'onsefy_options_page'
    );
}

function onsefy_settings_init()
{
    register_setting('onsefy_settings_group', 'onsefy_settings');

    add_settings_section(
        'onsefy_settings_section',
        __('OnSefy API Configuration', 'onsefy-fraud-blocker'),
        null,
        'onsefy_fraud_blocker'
    );

    add_settings_field(
        'onsefy_bearer_token',
        __('API Key', 'onsefy-fraud-blocker'),
        'onsefy_bearer_token_render',
        'onsefy_fraud_blocker',
        'onsefy_settings_section'
    );

    add_settings_field(
        'onsefy_service_id',
        __('Service ID', 'onsefy-fraud-blocker'),
        'onsefy_service_id_render',
        'onsefy_fraud_blocker',
        'onsefy_settings_section'
    );

    add_settings_field(
        'onsefy_plan_type',
        __('Plan Type', 'onsefy-fraud-blocker'),
        'onsefy_plan_type_render',
        'onsefy_fraud_blocker',
        'onsefy_settings_section'
    );

    add_settings_field(
        'onsefy_risk_threshold',
        __('Risk Score Threshold', 'onsefy-fraud-blocker'),
        'onsefy_risk_threshold_render',
        'onsefy_fraud_blocker',
        'onsefy_settings_section'
    );
}

function onsefy_bearer_token_render()
{
    $options = get_option('onsefy_settings');
    ?>
    <input type="text" name="onsefy_settings[bearer_token]" value="<?php echo esc_attr($options['bearer_token'] ?? ''); ?>" size="50">
    <p class="description">
        <?php _e('Enter your OnSefy API Key. Go to the ', 'onsefy-fraud-blocker'); ?>
        <a href="https://account.onsefy.com/application-connect?id=manage-api-keys" target="_blank" rel="noopener noreferrer">
            <?php _e('API Keys', 'onsefy-fraud-blocker'); ?>
        </a>
        <?php _e(' page in OnSefy, where you can create a new API Key or use an existing one. Copy the key and paste it here.', 'onsefy-fraud-blocker'); ?>
    </p>
    <?php
}


function onsefy_service_id_render()
{
    $options = get_option('onsefy_settings');
    ?>
    <input type='text' name='onsefy_settings[service_id]' value='<?php echo esc_attr($options['service_id'] ?? ''); ?>' size="50">
    <p class="description"><?php _e('Your OnSefy Service ID.', 'onsefy-fraud-blocker'); ?></p>
    <?php
}

function onsefy_plan_type_render()
{
    $options = get_option('onsefy_settings');
    $plan_type = $options['plan_type'] ?? 'free';
    ?>
    <select name="onsefy_settings[plan_type]">
        <option value="free" <?php selected($plan_type, 'free'); ?>><?php _e('Free', 'onsefy'); ?></option>
        <option value="paid" <?php selected($plan_type, 'paid'); ?>><?php _e('Paid', 'onsefy'); ?></option>
    </select>
    <p class="description"><?php _e('Choose your OnSefy plan type.', 'onsefy'); ?></p>
    <?php
}

function onsefy_risk_threshold_render()
{
    $options = get_option('onsefy_settings');
    $threshold = $options['risk_threshold'] ?? '1.00';
    ?>
    <input type="number" step="0.01" min="0" max="10" name="onsefy_settings[risk_threshold]" value="<?php echo esc_attr($threshold); ?>">
    <p class="description"><?php _e('Block registrations with risk score above this threshold.', 'onsefy'); ?></p>
    <?php
}

function onsefy_options_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('OnSefy Fraud Blocker Settings', 'onsefy'); ?></h1>
        <form action='options.php' method='post'>
            <?php
            settings_fields('onsefy_settings_group');
            do_settings_sections('onsefy_fraud_blocker');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Enqueue admin CSS for styling
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('onsefy-admin-css', plugin_dir_url(__FILE__) . '../assets/admin.css', [], '1.0.0');
});
