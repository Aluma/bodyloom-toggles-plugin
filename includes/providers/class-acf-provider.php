<?php
namespace Bodyloom\DynamicToggles\Providers;

use Bodyloom\DynamicToggles\Interfaces\Field_Provider;

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Provider implements Field_Provider
{

    public function is_active()
    {
        return function_exists('get_field');
    }

    public function get_repeater_data($post_id, $field_name, $title_field, $content_field)
    {
        $data = [];

        if (!$this->is_active()) {
            return $data;
        }

        // Handle nested repeaters (parent/child)
        if (strpos($field_name, '/') !== false) {
            list($parent, $child) = explode('/', $field_name);
            $parent_data = get_field($parent, $post_id);

            if ($parent_data && isset($parent_data[$child]) && is_array($parent_data[$child])) {
                foreach ($parent_data[$child] as $row) {
                    $data[] = [
                        'toggle_title' => $row[$title_field] ?? '',
                        'toggle_content' => $row[$content_field] ?? '',
                        'toggle_custom_id' => '',
                    ];
                }
            }
        } else {
            // Standard repeater
            if (have_rows($field_name, $post_id)) {
                while (have_rows($field_name, $post_id)) {
                    the_row();
                    $data[] = [
                        'toggle_title' => get_sub_field($title_field),
                        'toggle_content' => get_sub_field($content_field),
                        'toggle_custom_id' => '',
                    ];
                }
            }
        }

        return $data;
    }
}
