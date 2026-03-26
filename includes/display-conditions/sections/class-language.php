<?php

use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPR_DC_Section_Language extends WPR_DC_Section_Base {

	public function get_id() {
		return 'wpr_dc_language';
	}

	public function get_label() {
		return esc_html__( 'Language', 'wpr-addons' );
	}

	public function get_order() {
		return 110;
	}

	public function is_available() {
		return $this->has_wpml() || $this->has_polylang() || $this->has_translatepress() || $this->has_weglot();
	}

	public function register_controls( $element ) {
		$element->add_control(
			'wpr_dc_languages',
			[
				'label'       => esc_html__( 'Languages', 'wpr-addons' ),
				'description' => esc_html__( 'Select languages to match.', 'wpr-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'default'     => [],
				'options'     => $this->get_language_options(),
				'render_type' => 'none',
			]
		);
	}

	public function evaluate( $settings ) {
		$languages = ! empty( $settings['wpr_dc_languages'] ) ? $settings['wpr_dc_languages'] : [];

		if ( empty( $languages ) ) {
			return null;
		}

		$current = $this->get_current_language();

		if ( '' === $current ) {
			return null;
		}

		return in_array( $current, $languages, true );
	}

	private function get_current_language() {
		if ( $this->has_wpml() ) {
			return apply_filters( 'wpml_current_language', '' );
		}

		if ( $this->has_polylang() && function_exists( 'pll_current_language' ) ) {
			return pll_current_language( 'slug' );
		}

		if ( $this->has_translatepress() ) {
			$locale = get_locale();
			return $locale ? substr( $locale, 0, 2 ) : '';
		}

		if ( $this->has_weglot() && function_exists( 'weglot_get_current_language' ) ) {
			return weglot_get_current_language();
		}

		return '';
	}

	private function get_language_options() {
		if ( $this->has_wpml() ) {
			return $this->get_wpml_languages();
		}

		if ( $this->has_polylang() ) {
			return $this->get_polylang_languages();
		}

		if ( $this->has_translatepress() ) {
			return $this->get_translatepress_languages();
		}

		if ( $this->has_weglot() ) {
			return $this->get_weglot_languages();
		}

		return [];
	}

	private function get_wpml_languages() {
		$options   = [];
		$languages = apply_filters( 'wpml_active_languages', null, [ 'skip_missing' => 0 ] );

		if ( is_array( $languages ) ) {
			foreach ( $languages as $lang ) {
				$options[ $lang['code'] ] = $lang['translated_name'];
			}
		}

		return $options;
	}

	private function get_polylang_languages() {
		$options = [];

		if ( function_exists( 'pll_languages_list' ) ) {
			$languages = pll_languages_list( [ 'fields' => [] ] );
			foreach ( $languages as $lang ) {
				$options[ $lang->slug ] = $lang->name;
			}
		}

		return $options;
	}

	private function get_translatepress_languages() {
		$options = [];

		if ( class_exists( 'TRP_Translate_Press' ) ) {
			$trp        = \TRP_Translate_Press::get_trp_instance();
			$settings   = $trp->get_component( 'settings' );
			$trp_settings = $settings->get_settings();

			if ( ! empty( $trp_settings['publish-languages'] ) ) {
				foreach ( $trp_settings['publish-languages'] as $lang_code ) {
					$options[ substr( $lang_code, 0, 2 ) ] = $lang_code;
				}
			}
		}

		return $options;
	}

	private function get_weglot_languages() {
		$options = [];

		if ( function_exists( 'weglot_get_languages_configured' ) ) {
			$languages = weglot_get_languages_configured();
			foreach ( $languages as $lang ) {
				$code = is_object( $lang ) ? $lang->getInternalCode() : (string) $lang;
				$options[ $code ] = strtoupper( $code );
			}
		}

		return $options;
	}

	private function has_wpml() {
		return defined( 'ICL_SITEPRESS_VERSION' );
	}

	private function has_polylang() {
		return defined( 'POLYLANG_VERSION' );
	}

	private function has_translatepress() {
		return class_exists( 'TRP_Translate_Press' );
	}

	private function has_weglot() {
		return defined( 'WEGLOT_VERSION' );
	}
}
