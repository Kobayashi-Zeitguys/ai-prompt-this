<?php
/**
 * PromptThis item block render template.
 *
 * @package ai-prompt-this
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template-scope variables; file is included in WP_Block render scope.

// Extract attributes with defaults.
$platform        = $attributes['platform'] ?? array(
	'key'      => 'chatgpt',
	'name'     => 'ChatGPT',
	'baseUrl'  => 'https://chat.openai.com/?q=',
	'svg'      => '',
	'svgSolid' => '',
);
$custom_label    = $attributes['customLabel'] ?? '';
$open_in_new_tab = $block->context['zp/ai-prompt-this/openInNewTab'] ?? true;

// Get prompt text: use child's own if set, otherwise fall back to parent context.
$prompt_text = ! empty( $attributes['promptText'] )
	? $attributes['promptText']
	: ( $block->context['zp/ai-prompt-this/promptText'] ?? '' );

// Get current post URL.
$current_url = '';
if ( is_singular() ) {
	$current_url = get_permalink();
} else {
	$current_url = home_url( add_query_arg( null, null ) );
}

// Get icon color: use child's own if set, otherwise fall back to parent context.
$icon_color = ! empty( $attributes['iconColor'] )
	? $attributes['iconColor']
	: ( $block->context['zp/ai-prompt-this/iconColor'] ?? '' );

// Extract platform data.
$platform_key  = $platform['key'];
$platform_name = $platform['name'];
$base_url      = $platform['baseUrl'];

// Pick SVG: solid (currentColor) if icon color is set, otherwise brand-coloured.
$platform_svg = ( ! empty( $icon_color ) && ! empty( $platform['svgSolid'] ) )
	? $platform['svgSolid']
	: ( $platform['svg'] ?? '' );

// Build the deep link URL.
$deep_link = $base_url . rawurlencode( $prompt_text . ' ' . $current_url );

// Get platform label.
$label     = ! empty( $custom_label ) ? $custom_label : $platform_name;
$icon_only = $block->context['zp/ai-prompt-this/iconOnly'] ?? false;

$link_classes = 'platform-' . esc_attr( $platform_key );

// Build block wrapper attributes for the <li>.
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => $link_classes,
	)
);

?>

<li <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<a class="<?php echo esc_attr( $link_classes ); ?>" href="<?php echo esc_url( $deep_link ); ?>"<?php echo $open_in_new_tab ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
		<span class="prompt-this-icon" aria-hidden="true"<?php echo ! empty( $icon_color ) ? ' style="color:' . esc_attr( $icon_color ) . ';"' : ''; ?>>
			<?php echo $platform_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</span>
		<span class="<?php echo $icon_only ? 'screen-reader-text' : 'prompt-this-label'; ?>"><?php echo esc_html( $label ); ?></span>
	</a>
</li>
