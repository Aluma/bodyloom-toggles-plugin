<?php
namespace Bodyloom\DynamicToggles\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;
use Elementor\Utils;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Toggles extends Widget_Base
{

    public function get_name()
    {
        return 'bodyloom-toggles';
    }

    public function get_title()
    {
        return esc_html__('Bodyloom Toggles', 'bodyloom-dynamic-toggles');
    }

    public function get_icon()
    {
        return 'eicon-toggle';
    }

    public function get_categories()
    {
        return ['general'];
    }

    public function get_script_depends()
    {
        return ['bodyloom-toggles'];
    }

    public function get_style_depends()
    {
        return ['bodyloom-toggles'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Toggles', 'bodyloom-dynamic-toggles'),
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => esc_html__('Type', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::SELECT,
                'default' => 'toggles',
                'options' => [
                    'toggles' => esc_html__('Toggles', 'bodyloom-dynamic-toggles'),
                    'accordion' => esc_html__('Accordion', 'bodyloom-dynamic-toggles'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__('Style', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default (Arrow)', 'bodyloom-dynamic-toggles'),
                    'plus-minus' => esc_html__('Plus/Minus', 'bodyloom-dynamic-toggles'),
                    'chevron' => esc_html__('Chevron', 'bodyloom-dynamic-toggles'),
                ],
            ]
        );

        $this->add_control(
            'data_source',
            [
                'label' => esc_html__('Data Source', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::SELECT,
                'default' => 'static',
                'options' => [
                    'static' => esc_html__('Static', 'bodyloom-dynamic-toggles'),
                    'acf_repeater' => esc_html__('ACF Repeater', 'bodyloom-dynamic-toggles'),
                ],
            ]
        );

        // Static Repeater Controls
        $repeater = new Repeater();

        $repeater->add_control(
            'toggle_title',
            [
                'label' => esc_html__('Title', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Toggle Title', 'bodyloom-dynamic-toggles'),
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'toggle_content',
            [
                'label' => esc_html__('Content', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Toggle Content', 'bodyloom-dynamic-toggles'),
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'toggle_custom_id',
            [
                'label' => esc_html__('Custom ID', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'my-toggle-id',
                'description' => esc_html__('Add a custom ID for deep linking (e.g., #my-toggle-id).', 'bodyloom-dynamic-toggles'),
            ]
        );

        $this->add_control(
            'toggles',
            [
                'label' => esc_html__('Items', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'toggle_title' => esc_html__('Item #1', 'bodyloom-dynamic-toggles'),
                        'toggle_content' => esc_html__('Lorem ipsum dolor sit amet.', 'bodyloom-dynamic-toggles'),
                    ],
                    [
                        'toggle_title' => esc_html__('Item #2', 'bodyloom-dynamic-toggles'),
                        'toggle_content' => esc_html__('Lorem ipsum dolor sit amet.', 'bodyloom-dynamic-toggles'),
                    ],
                ],
                'title_field' => '{{{ toggle_title }}}',
                'condition' => [
                    'data_source' => 'static',
                ],
            ]
        );

        // ACF Controls
        $this->add_control(
            'acf_repeater_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__('Please ensure ACF Pro is active.', 'bodyloom-dynamic-toggles'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'condition' => [
                    'data_source' => 'acf_repeater',
                ],
            ]
        );

        $this->add_control(
            'acf_repeater_field_name',
            [
                'label' => esc_html__('ACF Repeater Name', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('For nested repeaters: parent_group/repeater_name', 'bodyloom-dynamic-toggles'),
                'condition' => ['data_source' => 'acf_repeater'],
            ]
        );

        $this->add_control(
            'acf_title_field',
            [
                'label' => esc_html__('Title Sub-Field', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::TEXT,
                'condition' => ['data_source' => 'acf_repeater'],
            ]
        );

        $this->add_control(
            'acf_content_field',
            [
                'label' => esc_html__('Content Sub-Field', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::TEXT,
                'condition' => ['data_source' => 'acf_repeater'],
            ]
        );

        $this->add_control(
            'title_html_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                ],
                'default' => 'div',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'faq_schema',
            [
                'label' => esc_html__('FAQ Schema', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'default_toggle',
            [
                'label' => esc_html__('Active Toggle', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 0,
                'description' => esc_html__('Enter the number of the item to be active by default (e.g. 1). Set to 0 to keep all closed.', 'bodyloom-dynamic-toggles'),
            ]
        );

        $this->add_control(
            'trigger_icon',
            [
                'label' => esc_html__('Icon', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'trigger_active_icon',
            [
                'label' => esc_html__('Active Icon', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-minus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'trigger_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Item
        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('Item', 'bodyloom-dynamic-toggles'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bodyloom-toggles-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_space_between',
            [
                'label' => esc_html__('Space Between', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bodyloom-toggles-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .bodyloom-toggles-item',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bodyloom-toggles-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .bodyloom-toggles-item',
            ]
        );

        $this->end_controls_section();

        // Style Section - Title
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'bodyloom-dynamic-toggles'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bodyloom-toggles-title',
            ]
        );

        $this->start_controls_tabs('title_colors_tabs');

        $this->start_controls_tab(
            'title_colors_normal',
            ['label' => esc_html__('Normal', 'bodyloom-dynamic-toggles')]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bodyloom-toggles-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_color',
            [
                'label' => esc_html__('Background', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bodyloom-toggles-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_colors_active',
            ['label' => esc_html__('Active', 'bodyloom-dynamic-toggles')]
        );

        $this->add_control(
            'title_color_active',
            [
                'label' => esc_html__('Color', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bodyloom-toggles-item.active .bodyloom-toggles-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_color_active',
            [
                'label' => esc_html__('Background', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bodyloom-toggles-item.active .bodyloom-toggles-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bodyloom-toggles-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // Style Section - Content
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'bodyloom-dynamic-toggles'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .bodyloom-toggles-content',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bodyloom-toggles-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'bodyloom-dynamic-toggles'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bodyloom-toggles-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $toggles = [];

        if ('acf_repeater' === $settings['data_source']) {
            $toggles = $this->get_dynamic_toggles($settings);
        } else {
            $toggles = $settings['toggles'];
        }

        if (empty($toggles)) {
            return;
        }

        // Prepare data for the view
        $view_data = [
            'id' => $this->get_id(),
            'settings' => $settings,
            'toggles' => $toggles,
        ];

        // Include the template
        include BODYLOOM_TOGGLES_PATH . 'templates/toggles-view.php';
    }

    protected function get_dynamic_toggles($settings)
    {
        $repeater_name = $settings['acf_repeater_field_name'];
        $title_field = $settings['acf_title_field'];
        $content_field = $settings['acf_content_field'];

        if (empty($repeater_name) || empty($title_field) || empty($content_field)) {
            return [];
        }

        $post_id = get_the_ID();

        $provider = \Bodyloom\DynamicToggles\Provider_Factory::get_provider();

        if (!$provider) {
            return [];
        }

        return $provider->get_repeater_data($post_id, $repeater_name, $title_field, $content_field);
    }
}
