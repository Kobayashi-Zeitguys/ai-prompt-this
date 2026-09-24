<?php
/**
 * Plugin Name:       AI PromptThis
 * Plugin URI:        https://kobayashi.ca
 * Description:       Deep-link buttons to send page content to AI platforms (ChatGPT, Claude, Gemini, Grok, Perplexity).
 * Version:           1.0.0
 * Requires at least: 6.6
 * Requires PHP:      8.1
 * Author:            Hector Jarquin, Tomas Llobet-Arany
 * Author URI:        https://kobayashi.ca
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ai-prompt-this
 * Domain Path:       /languages
 *
 * @package ai-prompt-this
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register blocks and load JS translations.
 */
function ai_prompt_this_init() {
	register_block_type_from_metadata( __DIR__ . '/build/prompt-this' );
	register_block_type_from_metadata( __DIR__ . '/build/prompt-this-item' );

	wp_set_script_translations( 'zp-ai-prompt-this-editor-script', 'ai-prompt-this', __DIR__ . '/languages' );
	wp_set_script_translations( 'zp-ai-prompt-this-item-editor-script', 'ai-prompt-this', __DIR__ . '/languages' );
}
add_action( 'init', 'ai_prompt_this_init' );

/**
 * Ensure the plugin's blocks appear in the inserter even when a theme
 * restricts the allowed block types.
 *
 * Runs at the latest possible priority because some themes (e.g. Zeitpress)
 * rebuild the whitelist from the block registry in their own
 * `allowed_block_types_all` filter — ignoring the incoming value and dropping
 * any `zp/*` block not in their hardcoded list. Appending here re-adds them.
 *
 * @param array|bool $allowed_blocks Allowed block types (true/false = unrestricted/restricted, array = whitelist).
 * @param object     $context        Editor context.
 * @return array|bool
 */
function ai_prompt_this_allowed_block_types( $allowed_blocks, $context ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- required by WP filter signature
	if ( ! is_array( $allowed_blocks ) ) {
		return $allowed_blocks;
	}
	return array_merge(
		$allowed_blocks,
		array( 'zp/ai-prompt-this', 'zp/ai-prompt-this-item' )
	);
}
add_filter( 'allowed_block_types_all', 'ai_prompt_this_allowed_block_types', PHP_INT_MAX, 2 );
