=== AI PromptThis ===
Contributors: kobayashizeitguys
Tags: ai, chatgpt, claude, gemini, prompt
Requires at least: 6.6
Tested up to: 7.1
Stable tag: 1.0.0
Requires PHP: 8.1
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Deep-link buttons that send page content to AI platforms (ChatGPT, Claude, Gemini, Grok, Perplexity).

== Description ==

AI PromptThis adds a set of AI platform buttons to your content. Each button deep-links to a platform with a pre-filled prompt plus the current page URL, so visitors can generate an AI response about the page in one click.

Supported AI platforms:

* ChatGPT
* Claude
* Gemini
* Grok
* Perplexity

Features:

* Parent-child block structure (a container plus individual platform items, similar to Social Links)
* Per-item AI platform selector
* Customizable prompt text (set once for the whole block, or override per item)
* Custom label per platform button
* Icon-only mode (hides labels visually while keeping them accessible to screen readers)
* Open-in-new-tab toggle
* Deep links with pre-loaded prompts

== Source Code ==

The source code and build tooling are developed in the open at https://github.com/Kobayashi-Zeitguys/ai-prompt-this. To build from source, run `npm install` followed by `npm run build`.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/ai-prompt-this` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Add the "AI PromptThis" block in the block editor, then choose a platform for each item.

== Frequently Asked Questions ==

= Which AI platforms are supported? =

ChatGPT, Claude, Gemini, Grok, and Perplexity.

= Can I customize the prompt? =

Yes. Set a prompt at the block level, or override it per platform item. The default is "Summarize this article".

= Can I hide the labels and show only icons? =

Yes. Enable the "Icon only" toggle to hide labels visually while keeping them available to screen readers.

= Does this plugin send any data to third-party services? =

No data is sent until a visitor clicks a button. When clicked, the button opens the platform and passes the page URL plus the prompt as query parameters:

* ChatGPT: https://chat.openai.com/?q=
* Claude: https://claude.ai/new?q=
* Gemini: https://www.google.com/search?udm=50&q=
* Grok: https://x.com/i/grok?text=
* Perplexity: https://www.perplexity.ai/search/new?q=

= Do the AI platforms automatically generate responses? =

Some platforms generate responses automatically on arrival; others require the visitor to submit the prompt manually. This behavior is controlled by each platform, not by this plugin.

== Changelog ==

= 1.0.0 =
* Initial standalone release.
* Parent block: AI PromptThis (container)
* Child block: AI PromptThis Item (individual AI platform)
* Support for ChatGPT, Claude, Gemini, Grok, and Perplexity
* Customizable prompts, labels, icon color, and icon-only mode
