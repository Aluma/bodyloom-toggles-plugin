<?php
/**
 * Toggles View Template
 *
 * @var array $view_data
 */

if (!defined('ABSPATH')) {
	exit;
}

if (empty($bodyloom_view_data['toggles'])) {
	return;
}

$bodyloom_id = $bodyloom_view_data['id'];
$bodyloom_settings = $bodyloom_view_data['settings'];
$bodyloom_toggles_items = $bodyloom_view_data['toggles'];
$bodyloom_type = $bodyloom_settings['type'] ?? 'toggles';
$bodyloom_allowed_tags = ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'span', 'p'];
$bodyloom_title_tag = (in_array($bodyloom_settings['title_html_tag'] ?? '', $bodyloom_allowed_tags)) ? $bodyloom_settings['title_html_tag'] : 'div';
$bodyloom_style = $bodyloom_settings['style'] ?? 'default';
?>
<div class="bodyloom-toggles-wrapper bodyloom-toggles-type-<?php echo esc_attr($bodyloom_type); ?> bodyloom-style-<?php echo esc_attr($bodyloom_style); ?>"
	id="bodyloom-toggles-<?php echo esc_attr($bodyloom_id); ?>" data-type="<?php echo esc_attr($bodyloom_type); ?>">
	<?php foreach ($bodyloom_toggles_items as $bodyloom_index => $bodyloom_item):
		$bodyloom_tab_count = $bodyloom_index + 1;
		$bodyloom_custom_id_val = '';
		if (!empty($bodyloom_item['toggle_custom_id'])) {
			$bodyloom_custom_id_val = str_replace('#', '', $bodyloom_item['toggle_custom_id']);
		}

		$bodyloom_is_active = false;
		if (!empty($bodyloom_settings['default_toggle']) && $bodyloom_tab_count == $bodyloom_settings['default_toggle']) {
			$bodyloom_is_active = true;
		}

		$bodyloom_active_class = $bodyloom_is_active ? 'active' : '';
		?>
		<div class="bodyloom-toggles-item <?php echo esc_attr($bodyloom_active_class); ?>" <?php if (!empty($bodyloom_custom_id_val))
			   echo 'id="' . esc_attr($bodyloom_custom_id_val) . '"'; ?>>
			<<?php echo esc_html($bodyloom_title_tag); ?> class="bodyloom-toggles-title"
				data-tab="<?php echo esc_attr($bodyloom_tab_count); ?>"
				role="button" tabindex="0">
				<span class="bodyloom-toggles-title-text"><?php echo esc_html($bodyloom_item['toggle_title']); ?></span>
				<span class="bodyloom-toggles-icon">
					<?php
					// Render Elementor Icons if available and set
					if (class_exists('\Elementor\Icons_Manager') && !empty($bodyloom_settings['trigger_icon']['value'])):
						?>
						<span class="bodyloom-icon-closed">
							<?php \Elementor\Icons_Manager::render_icon($bodyloom_settings['trigger_icon'], ['aria-hidden' => 'true']); ?>
						</span>
						<?php if (!empty($bodyloom_settings['trigger_active_icon']['value'])): ?>
							<span class="bodyloom-icon-opened">
								<?php \Elementor\Icons_Manager::render_icon($bodyloom_settings['trigger_active_icon'], ['aria-hidden' => 'true']); ?>
							</span>
						<?php else: ?>
							<span class="bodyloom-icon-opened">
								<?php \Elementor\Icons_Manager::render_icon($bodyloom_settings['trigger_icon'], ['aria-hidden' => 'true']); ?>
							</span>
						<?php endif; ?>
					<?php endif; ?>
				</span>
			</<?php echo esc_html($bodyloom_title_tag); ?>>
			<div class="bodyloom-toggles-content" data-tab="<?php echo esc_attr($bodyloom_tab_count); ?>"
				style="<?php echo $bodyloom_is_active ? 'display: block;' : 'display: none;'; ?>">
				<div class="bodyloom-toggles-content-inner">
					<?php echo do_shortcode($bodyloom_item['toggle_content']); ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<?php
// FAQ Schema
if (!empty($bodyloom_settings['faq_schema']) && 'yes' === $bodyloom_settings['faq_schema']) {
	$bodyloom_schema = [
		'@context' => 'https://schema.org',
		'@type' => 'FAQPage',
		'mainEntity' => [],
	];

	foreach ($bodyloom_toggles_items as $bodyloom_item) {
		$bodyloom_schema['mainEntity'][] = [
			'@type' => 'Question',
			'name' => wp_strip_all_tags($bodyloom_item['toggle_title']),
			'acceptedAnswer' => [
				'@type' => 'Answer',
				'text' => wp_strip_all_tags($bodyloom_item['toggle_content']),
			],
		];
	}
	?>
	<script type="application/ld+json"><?php echo wp_json_encode($bodyloom_schema); ?></script>
	<?php
}
?>