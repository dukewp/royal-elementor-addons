<?php
namespace WprAddons\Admin\AiBuilder\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_Prompt_Builder {

	/**
	 * Fixed section definitions with human-written field descriptions.
	 * Each field tells the AI exactly what to write and how long.
	 */
	private static $fixed_sections = [
		'hero-section/1' => [
			'name' => 'Hero Section',
			'fields' => [
				[ 'desc' => 'Prefix text before animated words (e.g. "Realize Your", "Welcome to")', 'max' => 12 ],
				[ 'desc' => 'Two animated rotating phrases separated by a newline. Each phrase 8-15 chars. Example: "Full Potential!\nBest Dreams!" — write TWO phrases joined by \n', 'max' => 30, 'special' => 'animated_newline' ],
				[ 'desc' => 'Highlighted/branded keyword (not displayed prominently, can be business name)', 'max' => 13 ],
				[ 'desc' => 'Short paragraph describing the business (1-2 sentences)', 'max' => 80 ],
				[ 'desc' => 'Primary CTA button text', 'max' => 12 ],
				[ 'desc' => 'Secondary CTA button text', 'max' => 7 ],
			],
		],
		'services/4' => [
			'name' => 'Services Section',
			'fields' => [
				[ 'desc' => 'Section label (e.g. "Service", "What We Do")', 'max' => 10 ],
				[ 'desc' => 'Section main heading', 'max' => 20 ],
				[ 'desc' => 'Section description paragraph (1-2 sentences about your services)', 'max' => 130 ],
				[ 'desc' => 'Service item 1 name', 'max' => 14 ],
				[ 'desc' => 'Service item 2 name', 'max' => 14 ],
				[ 'desc' => 'Service item 3 name', 'max' => 14 ],
				[ 'desc' => 'Service item 4 name', 'max' => 12 ],
				[ 'desc' => 'Service item 5 name', 'max' => 14 ],
				[ 'desc' => 'Service item 6 name', 'max' => 12 ],
			],
		],
		'faq/3' => [
			'name' => 'FAQ Section',
			'fields' => [
				[ 'desc' => 'Section label (e.g. "FAQ")', 'max' => 5 ],
				[ 'desc' => 'Section intro paragraph', 'max' => 140 ],
				[ 'desc' => 'FAQ question 1', 'max' => 30 ],
				[ 'desc' => 'FAQ answer 1 (detailed, helpful answer)', 'max' => 340 ],
				[ 'desc' => 'FAQ question 2', 'max' => 30 ],
				[ 'desc' => 'FAQ answer 2 (detailed, helpful answer)', 'max' => 220 ],
				[ 'desc' => 'FAQ question 3', 'max' => 52 ],
				[ 'desc' => 'FAQ answer 3 (detailed, helpful answer)', 'max' => 220 ],
				[ 'desc' => 'FAQ question 4', 'max' => 45 ],
				[ 'desc' => 'FAQ answer 4 (detailed, helpful answer)', 'max' => 410 ],
				[ 'desc' => 'FAQ question 5', 'max' => 43 ],
				[ 'desc' => 'FAQ answer 5 (detailed, helpful answer)', 'max' => 410 ],
				[ 'desc' => 'FAQ question 6', 'max' => 35 ],
				[ 'desc' => 'FAQ answer 6 (detailed, helpful answer)', 'max' => 420 ],
			],
		],
		'footer/11' => [
			'name' => 'Footer',
			'fields' => [
				[ 'desc' => 'Copyright line (e.g. "© 2024 Business Name. All rights reserved.")', 'max' => 45 ],
				[ 'desc' => 'Back to top button text', 'max' => 13 ],
			],
		],
	];

	public function __construct( $catalog = [] ) {
		// catalog not used in fixed mode
	}

	public function get_fixed_section_ids() {
		return array_keys( self::$fixed_sections );
	}

	public function build_system_prompt() {
		return 'You are an expert website copywriter. You write text content for website sections.

You will be given a list of sections, each with numbered text fields. For each field you see:
- A description of what kind of text to write
- A maximum character count

RULES:
1. Return EXACTLY the number of texts requested for each section
2. Each text MUST be within the max character limit. Aim for 90-100% of the max. NEVER exceed it.
3. For fields marked "animated_newline": write exactly 2 short phrases joined by \n (a literal newline character)
4. Write text relevant to the business described by the user
5. For FAQ answers: write genuinely helpful, detailed answers
6. For buttons: use short 1-3 word CTAs

RESPOND WITH ONLY a raw JSON object. Start with { end with }.

{
  "page_title": "Page title for the business",
  "sections": [
    {
      "id": "section/id",
      "texts": ["text for field 1", "text for field 2", "..."]
    }
  ],
  "color_scheme": {
    "primary": "#hex (brand color for headings, buttons, icons)",
    "secondary": "#hex (borders, accents, hover states)",
    "accent": "#hex (highlights, badges, special elements)"
  }
}';
	}

	public function build_user_prompt( $args ) {
		$prompt = $args['prompt'] ?? '';
		$page_type = $args['page_type'] ?? 'landing';
		$business_name = $args['business_name'] ?? '';
		$style = $args['style'] ?? 'modern';
		$color_preference = $args['color_preference'] ?? '';

		$user_prompt = "WRITE TEXT FOR A WEBSITE:\n\n";

		if ( ! empty( $business_name ) ) {
			$user_prompt .= "Business: {$business_name}\n";
		}

		$user_prompt .= "Type: {$page_type}\n";

		if ( ! empty( $color_preference ) ) {
			$user_prompt .= "Colors: {$color_preference}\n";
		}

		$user_prompt .= "Description: {$prompt}\n\n";
		$user_prompt .= "SECTIONS TO FILL:\n";

		foreach ( self::$fixed_sections as $id => $section ) {
			$user_prompt .= "\n--- {$section['name']} (id: {$id}) ---\n";
			foreach ( $section['fields'] as $i => $field ) {
				$min = max( 3, (int) round( $field['max'] * 0.85 ) );
				$user_prompt .= "  " . ( $i + 1 ) . ". {$field['desc']} ({$min}-{$field['max']} chars)\n";
			}
		}

		return $user_prompt;
	}

	// Keep for future expansion back to dynamic catalog
	public function build_catalog_summary( $args ) {
		return '';
	}
}
