<?php
/**
* Plugin Name: Bodyloom Dynamic Toggles
Plugin URI: https://github.com/aluma/bodyloom-dynamic-toggles
Description: Create beautiful, accessible toggles and accordions from repeater fields. Works with ACF Pro, Meta Box, or
Pods.
Version: 1.0.0
Author: Jimmy Thanki
Author URI: https://bodyloom.com
License: GPLv2 or later
Text Domain: bodyloom-dynamic-toggles
*/

if (!defined('ABSPATH')) {
    exit;
}

define('BODYLOOM_TOGGLES_VERSION', '1.0.0');
define('BODYLOOM_TOGGLES_PATH', plugin_dir_path(__FILE__));
define('BODYLOOM_TOGGLES_URL', plugin_dir_url(__FILE__));

// Include Interfaces
require_once BODYLOOM_TOGGLES_PATH . 'includes/interfaces/class-field-provider.php';

// Include Providers
require_once BODYLOOM_TOGGLES_PATH . 'includes/providers/class-acf-provider.php';
require_once BODYLOOM_TOGGLES_PATH . 'includes/providers/class-metabox-provider.php';
require_once BODYLOOM_TOGGLES_PATH . 'includes/providers/class-pods-provider.php';

// Include Factory
require_once BODYLOOM_TOGGLES_PATH . 'includes/class-provider-factory.php';

// Include Main Class
require_once BODYLOOM_TOGGLES_PATH . 'includes/class-plugin.php';

// Initialize Plugin
function bodyloom_dynamic_toggles_init()
{
    \Bodyloom\DynamicToggles\Plugin::get_instance();
}
add_action('plugins_loaded', 'bodyloom_dynamic_toggles_init');