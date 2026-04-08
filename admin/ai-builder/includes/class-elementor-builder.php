<?php
namespace WprAddons\Admin\AiBuilder\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_Elementor_Builder {

	private $sections_dir;

	private static $text_keys = [
		'title', 'editor', 'title_text', 'description_text', 'text',
		'prefix_text', 'animated_text', 'heading', 'tab_title', 'tab_content',
		'item_title', 'item_description', 'btn_text', 'button_text',
		'highlighted_text', 'suffix_text',
		'front_title', 'front_description', 'back_title', 'back_description',
		'front_btn_text', 'back_btn_text',
	];

	public function __construct() {
		$this->sections_dir = dirname( __DIR__ ) . '/sections/';
	}

	/**
	 * Build original page — same sections, no modifications
	 */
	public function build_original( $ai_response ) {
		$elementor_data = [];

		foreach ( $ai_response['sections'] as $section_pick ) {
			$section_json = $this->load_section( $section_pick['id'] );
			if ( ! $section_json ) {
				continue;
			}

			$section_json['content'] = $this->regenerate_ids( $section_json['content'] );

			foreach ( $section_json['content'] as $element ) {
				$elementor_data[] = $element;
			}
		}

		return $elementor_data;
	}

	public function build( $ai_response ) {
		$elementor_data = [];
		$color_scheme = $ai_response['color_scheme'] ?? [];

		foreach ( $ai_response['sections'] as $section_pick ) {
			$section_json = $this->load_section( $section_pick['id'] );
			if ( ! $section_json ) {
				continue;
			}

			// Apply text replacements (ordered array)
			if ( ! empty( $section_pick['texts'] ) && is_array( $section_pick['texts'] ) ) {
				$index = 0;
				$section_json['content'] = $this->replace_texts( $section_json['content'], $section_pick['texts'], $index );
			}

			// Strip images
			$section_json['content'] = $this->strip_images( $section_json['content'], $color_scheme );

			// Apply color scheme — only to chromatic (non-gray) colors
			$section_json['content'] = $this->apply_colors( $section_json['content'], $color_scheme );

			// Regenerate element IDs
			$section_json['content'] = $this->regenerate_ids( $section_json['content'] );

			foreach ( $section_json['content'] as $element ) {
				$elementor_data[] = $element;
			}
		}

		return $elementor_data;
	}

	private function load_section( $section_id ) {
		$parts = explode( '/', $section_id );
		if ( count( $parts ) !== 2 ) {
			return null;
		}

		$file = $this->sections_dir . $parts[0] . '/' . $parts[1] . '.json';
		if ( ! file_exists( $file ) ) {
			return null;
		}

		$data = json_decode( file_get_contents( $file ), true );
		if ( ! is_array( $data ) || empty( $data['content'] ) ) {
			return null;
		}

		return $data;
	}

	// ─── TEXT REPLACEMENT ────────────────────────────────────────────

	/**
	 * Replace text fields in order using an indexed array from AI
	 */
	private function replace_texts( $elements, $texts, &$index ) {
		if ( ! is_array( $elements ) ) {
			return $elements;
		}

		foreach ( $elements as &$element ) {
			if ( isset( $element['settings'] ) ) {
				// Direct text fields
				foreach ( self::$text_keys as $key ) {
					if ( $index >= count( $texts ) ) {
						break 2;
					}
					if ( isset( $element['settings'][ $key ] ) && is_string( $element['settings'][ $key ] ) ) {
						$original = $element['settings'][ $key ];
						$clean = strip_tags( $original );
						if ( strlen( $clean ) > 2 ) {
							$new_text = $texts[ $index ];

							// Preserve HTML structure from original
							if ( $original !== $clean && strpos( $original, '<' ) !== false ) {
								$element['settings'][ $key ] = $this->replace_preserving_html( $original, $new_text );
							} else {
								$element['settings'][ $key ] = $new_text;
							}
							$index++;
						}
					}
				}

				// Repeater fields (accordion items, icon boxes, etc.)
				foreach ( $element['settings'] as $setting_key => &$setting_val ) {
					if ( is_array( $setting_val ) && ! empty( $setting_val ) && isset( $setting_val[0] ) && is_array( $setting_val[0] ) ) {
						foreach ( $setting_val as &$repeater_item ) {
							if ( $index >= count( $texts ) ) {
								break 3;
							}
							foreach ( self::$text_keys as $key ) {
								if ( $index >= count( $texts ) ) {
									break 4;
								}
								if ( isset( $repeater_item[ $key ] ) && is_string( $repeater_item[ $key ] ) ) {
									$original = $repeater_item[ $key ];
									$clean = strip_tags( $original );
									if ( strlen( $clean ) > 2 ) {
										$new_text = $texts[ $index ];

										if ( mb_strlen( $clean ) < 50 && mb_strlen( $new_text ) > mb_strlen( $clean ) * 1.3 ) {
											$index++;
											continue;
										}

										if ( $original !== $clean && strpos( $original, '<' ) !== false ) {
											$repeater_item[ $key ] = $this->replace_preserving_html( $original, $new_text );
										} else {
											$repeater_item[ $key ] = $new_text;
										}
										$index++;
									}
								}
							}
						}
					}
				}
			}

			if ( isset( $element['elements'] ) ) {
				$element['elements'] = $this->replace_texts( $element['elements'], $texts, $index );
			}
		}

		return $elements;
	}

	/**
	 * Replace text content while preserving the HTML wrapper from original.
	 * Simple approach: detect if original was wrapped in <p> tags, keep that wrapper.
	 */
	private function replace_preserving_html( $original, $new_text ) {
		$new_text = esc_html( $new_text );

		// If original was wrapped in <p>...</p>, wrap new text the same way
		if ( preg_match( '/^\s*<p>/', $original ) ) {
			return '<p>' . $new_text . '</p>';
		}

		// For any other HTML, just return the escaped text
		return $new_text;
	}

	// ─── IMAGE STRIPPING ─────────────────────────────────────────────

	private function strip_images( $elements, $color_scheme ) {
		if ( ! is_array( $elements ) ) {
			return $elements;
		}

		$primary = $color_scheme['primary'] ?? '#2c3e50';

		foreach ( $elements as &$element ) {
			if ( isset( $element['settings'] ) ) {
				$settings = &$element['settings'];

				if ( isset( $settings['background_image'] ) && ! empty( $settings['background_image']['url'] ) ) {
					unset( $settings['background_image'] );
					$settings['background_background'] = 'classic';
					$settings['background_color'] = $primary;
				}

				if ( isset( $settings['background_video_link'] ) ) {
					unset( $settings['background_video_link'] );
					$settings['background_background'] = 'classic';
					$settings['background_color'] = $primary;
				}

				if ( isset( $settings['bg_image'] ) ) {
					unset( $settings['bg_image'] );
				}
				if ( isset( $settings['hover_parallax'] ) ) {
					unset( $settings['hover_parallax'] );
				}
				if ( isset( $settings['background_slideshow_gallery'] ) ) {
					unset( $settings['background_slideshow_gallery'] );
				}

				if ( isset( $settings['image'] ) && is_array( $settings['image'] ) && ! empty( $settings['image']['url'] ) ) {
					$settings['image'] = [ 'url' => '', 'id' => '', 'alt' => '', 'source' => 'library' ];
				}

				if ( isset( $settings['background_overlay_image'] ) && ! empty( $settings['background_overlay_image']['url'] ) ) {
					unset( $settings['background_overlay_image'] );
				}

				foreach ( $settings as $setting_key => &$setting_val ) {
					if ( is_array( $setting_val ) && ! empty( $setting_val ) && isset( $setting_val[0] ) && is_array( $setting_val[0] ) ) {
						foreach ( $setting_val as &$repeater_item ) {
							if ( isset( $repeater_item['image'] ) && is_array( $repeater_item['image'] ) && ! empty( $repeater_item['image']['url'] ) ) {
								$repeater_item['image'] = [ 'url' => '', 'id' => '', 'alt' => '', 'source' => 'library' ];
							}
						}
					}
				}
			}

			if ( isset( $element['elements'] ) ) {
				$element['elements'] = $this->strip_images( $element['elements'], $color_scheme );
			}
		}

		return $elements;
	}

	// ─── COLOR APPLICATION (CHROMATIC ONLY) ──────────────────────────

	/**
	 * Apply color scheme — only replace chromatic (non-gray) existing colors.
	 * Neutral colors (black, white, grays) are left untouched.
	 */
	private function apply_colors( $elements, $color_scheme ) {
		if ( ! is_array( $elements ) || empty( $color_scheme ) ) {
			return $elements;
		}

		$primary    = $color_scheme['primary'] ?? '#2c3e50';
		$secondary  = $color_scheme['secondary'] ?? '#64748b';
		$accent     = $color_scheme['accent'] ?? '#e74c3c';
		$background = $color_scheme['background'] ?? '#ffffff';
		$text       = $color_scheme['text'] ?? '#334155';
		$text_light = $color_scheme['text_light'] ?? '#94a3b8';

		$primary_tint = $this->lighten_color( $primary, 0.85 );

		// Colors that should always be applied even to neutral existing values
		// NOTE: $background is NOT here — section backgrounds should keep their original dark/light tone
		$always_replace = [ $primary_tint, '#ffffff' ];

		$color_map = [
			// Icon backgrounds — light tint
			'/icon.*bg/' => $primary_tint,
			'/icon_box_color/' => $primary_tint,
			'/icon_box_active/' => $primary,
			'/feature_list_icon_wrapper_bg/' => $primary_tint,

			// Icon foreground
			'/icon_color/' => $primary,
			'/icon_primary/' => $primary,
			'/icon_secondary/' => $primary,
			'/front_icon/' => $primary,
			'/icon_active_color/' => $primary,

			// Button text — always white
			'/button.*text_color/' => '#ffffff',
			'/btn.*text_color/' => '#ffffff',
			'/^button_color$/' => '#ffffff',
			'/^btn_color/' => '#ffffff',
			'/front_btn_color/' => '#ffffff',
			'/back_btn_color/' => '#ffffff',

			// Primary: titles, headings, button backgrounds, active states
			'/title_color/' => $primary,
			'/heading_color/' => $primary,
			'/^primary/' => $primary,
			'/button_bg/' => $primary,
			'/btn_bg_color/' => $primary,
			'/subscribe_btn_bg/' => $primary,
			'/active_color/' => $primary,
			'/active_bg/' => $primary,
			'/pointer_color/' => $primary,
			'/slider_dots_active/' => $primary,
			'/slider_nav_hover/' => $primary,
			'/feature_list_line/' => $primary,

			// Secondary: borders, separators, dividers
			'/border_color/' => $secondary,
			'/separator/' => $secondary,
			'/divider/' => $secondary,
			'/shape_divider/' => $secondary,
			'/^secondary/' => $secondary,
			'/_hover_bg/' => $secondary,
			'/hover_bg_color/' => $secondary,

			// Accent: badges, highlights, flip card backs
			'/badge/' => $accent,
			'/sale/' => $accent,
			'/highlight/' => $accent,
			'/back_bg_color/' => $accent,
			'/toggle_btn_item_count/' => $accent,

			// Text
			'/description_color/' => $text,
			'/content_color/' => $text,
			'/^text_color$/' => $text,
			'/^color$/' => $text,
			'/excerpt_color/' => $text,
			'/menu_item_color$/' => $text,
			'/sub_menu_color$/' => $text,
			'/^tab_color$/' => $text,
			'/comments_color$/' => $text,

			// Text light
			'/placeholder/' => $text_light,
			'/date_color/' => $text_light,
			'/labels_color/' => $text_light,
			'/meta_color/' => $text_light,

			// Backgrounds — NOT replaced. Sections keep their original dark/light backgrounds.
			// Only front_bg_color (flip card fronts) gets a neutral white.
			'/front_bg_color/' => '#ffffff',
		];

		foreach ( $elements as &$element ) {
			if ( isset( $element['settings'] ) ) {
				foreach ( $element['settings'] as $key => &$value ) {
					if ( is_string( $value ) && preg_match( '/^#[0-9a-fA-F]{3,8}$/', $value ) ) {
						foreach ( $color_map as $pattern => $new_color ) {
							if ( preg_match( $pattern, $key ) ) {
								// Only replace if existing color is chromatic OR target is in always-replace list
								if ( $this->is_chromatic_color( $value ) || in_array( $new_color, $always_replace, true ) ) {
									$value = $new_color;
								}
								break;
							}
						}
					}

					// Repeater arrays
					if ( is_array( $value ) && ! empty( $value ) && isset( $value[0] ) && is_array( $value[0] ) ) {
						foreach ( $value as &$repeater_item ) {
							foreach ( $repeater_item as $rkey => &$rval ) {
								if ( is_string( $rval ) && preg_match( '/^#[0-9a-fA-F]{3,8}$/', $rval ) ) {
									foreach ( $color_map as $pattern => $new_color ) {
										if ( preg_match( $pattern, $rkey ) ) {
											if ( $this->is_chromatic_color( $rval ) || in_array( $new_color, $always_replace, true ) ) {
												$rval = $new_color;
											}
											break;
										}
									}
								}
							}
						}
					}
				}
			}

			if ( isset( $element['elements'] ) ) {
				$element['elements'] = $this->apply_colors( $element['elements'], $color_scheme );
			}
		}

		return $elements;
	}

	// ─── HELPERS ─────────────────────────────────────────────────────

	/**
	 * Check if a color is chromatic (has actual hue) vs neutral (grayscale).
	 * Neutral = R, G, B values are close together (black, white, grays).
	 */
	private function is_chromatic_color( $hex, $threshold = 30 ) {
		$hex = ltrim( $hex, '#' );

		if ( strlen( $hex ) === 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
		// 8-char hex (with alpha) — take first 6
		if ( strlen( $hex ) === 8 ) {
			$hex = substr( $hex, 0, 6 );
		}
		if ( strlen( $hex ) !== 6 ) {
			return false;
		}

		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		return ( max( $r, $g, $b ) - min( $r, $g, $b ) ) > $threshold;
	}

	/**
	 * Lighten a hex color by mixing with white
	 */
	private function lighten_color( $hex, $amount = 0.8 ) {
		$hex = ltrim( $hex, '#' );
		if ( strlen( $hex ) === 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		$r = round( $r + ( 255 - $r ) * $amount );
		$g = round( $g + ( 255 - $g ) * $amount );
		$b = round( $b + ( 255 - $b ) * $amount );

		return sprintf( '#%02x%02x%02x', $r, $g, $b );
	}

	private function regenerate_ids( $elements ) {
		if ( ! is_array( $elements ) ) {
			return $elements;
		}

		foreach ( $elements as &$element ) {
			$element['id'] = substr( md5( wp_generate_uuid4() ), 0, 8 );

			if ( isset( $element['elements'] ) ) {
				$element['elements'] = $this->regenerate_ids( $element['elements'] );
			}
		}

		return $elements;
	}
}
