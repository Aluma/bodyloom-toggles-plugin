<?php
namespace Bodyloom\DynamicToggles;

use Bodyloom\DynamicToggles\Widgets\Toggles;
use Bodyloom\DynamicToggles\Provider_Factory;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Main Plugin Class
 */
class Plugin
{

    /**
     * Instance
     *
     * @var Plugin The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return Plugin An instance of the class.
     */
    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     */
    private function includes()
    {
        // require_once BODYLOOM_TOGGLES_PATH . 'includes/widgets/class-toggles-widget.php';
    }

    /**
     * Initialize hooks
     */
    private function init_hooks()
    {
        // Register Elementor Widget
        add_action('elementor/widgets/register', [$this, 'register_widgets']);

        // Register Shortcode
        add_shortcode('bodyloom_toggles', [$this, 'render_shortcode']);

        // Enqueue Scripts and Styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Register Elementor Widgets
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register_widgets($widgets_manager)
    {
        require_once BODYLOOM_TOGGLES_PATH . 'includes/widgets/class-toggles-widget.php';
        $widgets_manager->register(new Widgets\Toggles());
    }

    /**
     * Enqueue Scripts and Styles
     */
    public function enqueue_scripts()
    {
        wp_register_style(
            'bodyloom-toggles',
            BODYLOOM_TOGGLES_URL . 'assets/css/bodyloom-toggles.css',
            [],
            BODYLOOM_TOGGLES_VERSION
        );

        wp_register_script(
            'bodyloom-toggles',
            BODYLOOM_TOGGLES_URL . 'assets/js/bodyloom-toggles.js',
            ['jquery'],
            BODYLOOM_TOGGLES_VERSION,
            true
        );
    }

    /**
     * Render Shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function render_shortcode($atts)
    {
        $atts = shortcode_atts([
            'id' => '', // Post ID to pull ACF data from (optional)
            'repeater' => '', // ACF Repeater field name
            'title_field' => '', // Sub-field for title
            'content_field' => '', // Sub-field for content
            'type' => 'toggles', // toggles or accordion
            'title_tag' => 'div', // HTML tag for title
            'faq_schema' => 'no', // yes or no
        ], $atts, 'bodyloom_toggles');

        // Enqueue assets
        wp_enqueue_style('bodyloom-toggles');
        wp_enqueue_script('bodyloom-toggles');

        if (empty($atts['repeater']) || empty($atts['title_field']) || empty($atts['content_field'])) {
            return '';
        }

        $post_id = !empty($atts['id']) ? intval($atts['id']) : get_the_ID();
        $toggles = $this->get_dynamic_data($post_id, $atts['repeater'], $atts['title_field'], $atts['content_field']);

        if (empty($toggles)) {
            return '';
        }

        // Prepare view data
        $view_data = [
            'id' => 'sc-' . uniqid(),
            'settings' => [
                'type' => $atts['type'],
                'title_html_tag' => $atts['title_tag'],
                'faq_schema' => $atts['faq_schema'],
                'style' => $atts['style'] ?? 'default',
                'default_toggle' => (isset($atts['open_first']) && 'yes' === $atts['open_first']) ? 1 : 0,
            ],
            'toggles' => $toggles,
        ];

        ob_start();
        include BODYLOOM_TOGGLES_PATH . 'templates/toggles-view.php';
        return ob_get_clean();
    }

    /**
     * Get Dynamic Data
     *
     * @param int $post_id Post ID.
     * @param string $repeater_name Repeater name.
     * @param string $title_field Title field name.
     * @param string $content_field Content field name.
     * @return array Toggles data.
     */
    public function get_dynamic_data($post_id, $repeater_name, $title_field, $content_field)
    {
        $provider = Provider_Factory::get_provider();

        if (!$provider) {
            return [];
        }

        return $provider->get_repeater_data($post_id, $repeater_name, $title_field, $content_field);
    }
}
