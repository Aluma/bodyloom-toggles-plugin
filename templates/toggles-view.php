<?php
/**
 * Toggles View Template
 *
 * @var array $view_data
 */

if (!defined('ABSPATH')) {
	exit;
}

if (empty($view_data['toggles'])) {
	return;
}

$id = $view_data['id'];
$settings = $view_data['settings'];
$toggles = $view_data['toggles'];
$type = $settings['type'] ?? 'toggles';
$allowed_tags = ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'span', 'p'];
$title_tag = (in_array($settings['title_html_tag'] ?? '', $allowed_tags)) ? $settings['title_html_tag'] : 'div';
$style = $settings['style'] ?? 'default';
?>
<div class="bodyloom-toggles-wrapper bodyloom-toggles-type-<?php echo esc_attr($type); ?> bodyloom-style-<?php echo esc_attr($style); ?>"
	id="bodyloom-toggles-<?php echo esc_attr($id); ?>" data-type="<?php echo esc_attr($type); ?>">
	<?php foreach ($toggles as $index => $item):
		$tab_count = $index + 1;
		$custom_id = '';
		if (!empty($item['toggle_custom_id'])) {
			$custom_id = 'id="' . esc_attr(str_replace('#', '', $item['toggle_custom_id'])) . '"';
		}

		$is_active = false;
		if (!empty($settings['default_toggle']) && $tab_count == $settings['default_toggle']) {
			$is_active = true;
		}

		$active_class = $is_active ? 'active' : '';
		$content_style = $is_active ? 'style="display: block;"' : 'style="display: none;"';
		?>
		<div class="bodyloom-toggles-item <?php echo esc_attr($active_class); ?>" <?php echo $custom_id; ?>>
			<<?php echo $title_tag; ?> class="bodyloom-toggles-title" data-tab="<?php echo esc_attr($tab_count); ?>"
				role="button" tabindex="0">
				<span class="bodyloom-toggles-title-text"><?php echo esc_html($item['toggle_title']); ?></span>
				<span class="bodyloom-toggles-icon">
					<?php
					// Render Elementor Icons if available and set
					if (class_exists('\Elementor\Icons_Manager') && !empty($settings['trigger_icon']['value'])):
						?>
						<span class="bodyloom-icon-closed">
							<?php \Elementor\Icons_Manager::render_icon($settings['trigger_icon'], ['aria-hidden' => 'true']); ?>
						</span>
						<?php if (!empty($settings['trigger_active_icon']['value'])): ?>
							<span class="bodyloom-icon-opened">
								<?php \Elementor\Icons_Manager::render_icon($settings['trigger_active_icon'], ['aria-hidden' => 'true']); ?>
							</span>
						<?php else: ?>
							<span class="bodyloom-icon-opened">
								<?php \Elementor\Icons_Manager::render_icon($settings['trigger_icon'], ['aria-hidden' => 'true']); ?>
							</span>
						<?php endif; ?>
					<?php endif; ?>
				</span>
			</<?php echo $title_tag; ?>>
			<div class="bodyloom-toggles-content" data-tab="<?php echo esc_attr($tab_count); ?>" <?php echo $content_style; ?>>
				<div class="bodyloom-toggles-content-inner">
					<?php echo do_shortcode($item['toggle_content']); ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<?php
// FAQ Schema
if (!empty($settings['faq_schema']) && 'yes' === $settings['faq_schema']) {
	$schema = [
		'@context' => 'https://schema.org',
		'@type' => 'FAQPage',
		'mainEntity' => [],
	];

	foreach ($toggles as $item) {
		$schema['mainEntity'][] = [
			'@type' => 'Question',
			'name' => wp_strip_all_tags($item['toggle_title']),
			'acceptedAnswer' => [
				'@type' => 'Answer',
				'text' => wp_strip_all_tags($item['toggle_content']),
			],
		];
	}
	?>
	<script type="application/ld+json"><?php echo wp_json_encode($schema); ?></script>
	<?php
}
?>