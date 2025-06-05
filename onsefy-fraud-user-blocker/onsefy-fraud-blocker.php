<?php
/*
Plugin Name: OnSefy Fraud User Blocker
Plugin URI: https://onsefy.com/
Description: Block fake signups usingAI Powered OnSefy fraud detection API.
Version: 1.0.0
Author: OnSefy
Author URI: https://onsefy.com/
Text Domain: onsefy-fraud-user-blocker
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ONSEFY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ONSEFY_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once ONSEFY_PLUGIN_DIR . 'includes/admin-settings.php';
require_once ONSEFY_PLUGIN_DIR . 'includes/admin-notices.php';
require_once ONSEFY_PLUGIN_DIR . 'includes/registration-validation.php';
require_once ONSEFY_PLUGIN_DIR . 'includes/registration-fields.php';
// Load plugin textdomain for translations
function onsefy_load_textdomain()
{
    load_plugin_textdomain('onsefy-fraud-blocker', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'onsefy_load_textdomain');

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'onsefy_add_settings_link');

function onsefy_add_settings_link($links)
{
    $settings_link = '<a href="options-general.php?page=onsefy_fraud_blocker">' . __('Settings', 'onsefy-fraud-user-blocker') . '</a>';
    $links[] = $settings_link;
    return $links;
}

