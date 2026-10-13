<?php
/**
 * PromptThis parent block render template.
 *
 * @package ai-prompt-this
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template-scope variables; file is included in WP_Block render scope.

// Strip the saved wrapper from $content — it includes the block's innerHTML
// wrapper (<div> for old blocks, <ul> for new blocks).
$inner_blocks_html = preg_replace(
	'#^\s*<(?:div|ul)\b[^>]*>(.*)</(?:div|ul)>\s*$#s',
	'$1',
	trim( $content )
);

// Resolve block gap from editor settings.
$has_block_gap   = isset( $attributes['style']['spacing'] ) && array_key_exists( 'blockGap', $attributes['style']['spacing'] );
$raw_block_gap   = $attributes['style']['spacing']['blockGap'] ?? '';
$block_gap_style = '';

if ( $has_block_gap ) {
	if ( $raw_block_gap && str_starts_with( $raw_block_gap, 'var:preset|spacing|' ) ) {
		$slug            = substr( $raw_block_gap, strlen( 'var:preset|spacing|' ) );
		$block_gap_style = '--wp--style--block-gap:var(--wp--preset--spacing--' . $slug . ')';
	} elseif ( $raw_block_gap ) {
		$block_gap_style = '--wp--style--block-gap:' . $raw_block_gap;
	} else {
		$block_gap_style = '--wp--style--block-gap:0';
	}
}

$wrapper_attributes = get_block_wrapper_attributes(
	$block_gap_style ? array( 'style' => $block_gap_style ) : array()
);

?>

<ul <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php echo $inner_blocks_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</ul>
