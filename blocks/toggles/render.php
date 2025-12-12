<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Render callback for the Dynamic Toggles block.
 *
 * @param array $attributes Block attributes.
 * @return string Block output.
 */

if (empty($attributes['repeater_field']) || empty($attributes['title_field']) || empty($attributes['content_field'])) {
    return '<div class="bodyloom-toggles-placeholder">' . esc_html__('Please configure the Dynamic Toggles block.', 'bodyloom-dynamic-toggles') . '</div>';
}

$plugin = \Bodyloom\DynamicToggles\Plugin::get_instance();
$post_id = get_the_ID();
$toggles = $plugin->get_dynamic_data($post_id, $attributes['repeater_field'], $attributes['title_field'], $attributes['content_field']);

if (empty($toggles)) {
    return '<div class="bodyloom-toggles-empty">' . esc_html__('No data found for the specified repeater field.', 'bodyloom-dynamic-toggles') . '</div>';
}

// Prepare view data
$view_data = [
    'id' => 'block-' . uniqid(),
    'settings' => [
        'type' => $attributes['type'],
        'title_html_tag' => 'div', // Default for block
        'faq_schema' => $attributes['faq_schema'] ? 'yes' : 'no',
        'style' => $attributes['style'],
        'default_toggle' => $attributes['open_first'] ? 1 : 0,
    ],
    'toggles' => $toggles,
];

// Enqueue assets
wp_enqueue_style('bodyloom-toggles');
wp_enqueue_script('bodyloom-toggles');

ob_start();
include BODYLOOM_TOGGLES_PATH . 'templates/toggles-view.php';
return ob_get_clean();
