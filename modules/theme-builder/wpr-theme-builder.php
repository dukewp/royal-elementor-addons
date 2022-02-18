<?php

use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Theme_Builder extends Elementor\Core\Base\Document {
	
	public function get_name() {
		return 'wpr-theme-builder';
	}

	public static function get_type() {
		return 'wpr-theme-builder';
	}

	public static function get_title() {
		return esc_html__( 'WPR Theme Builder', 'wpr-addons' );
	}

	protected function _register_controls() {
		// Get Available Post Types
		$post_types = Utilities::get_custom_types_of( 'post', false );

		// Get Available Taxonomies
		$post_taxonomies = Utilities::get_custom_types_of( 'tax', false );

		$this->start_controls_section(
			'preview_settings',
			[
				'label' => esc_html__( 'Preview Settings', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$default_archives = [
			'archive/post' => esc_html__( 'Posts Archive', 'wpr-addons' ),
			'archive/products' => esc_html__( 'Products Archive', 'wpr-addons' ),
			'archive/author' => esc_html__( 'Author Archive', 'wpr-addons' ),
			'archive/search' => esc_html__( 'Search Results', 'wpr-addons' ),
		];

		$taxonomy_archives = $post_taxonomies;
		$taxonomy_archives['category'] = esc_html__( 'Post Category', 'wpr-addons' );
		$taxonomy_archives['post_tag'] = esc_html__( 'Post Tag', 'wpr-addons' );

		$this->add_control(
			'preview_source',
			[
				'label' => esc_html__( 'Preview Source', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => $this->get_default_post_type(),
				'groups' => [
					'archive' => [
						'label' => __( 'Archives', 'wpr-addons' ),
						'options' => $default_archives + $taxonomy_archives,
					],
					'single' => [
						'label' => __( 'Singular', 'wpr-addons' ),
						'options' => $post_types
					],
				],
			]
		);

		$this->add_control(
			'preview_archive_author',
			[
				'label' => esc_html__( 'Select Author', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'options' => Utilities::get_users(),
				'separator' => 'before',
				'condition' => [
					'preview_source' => 'archive/author'
				]
			]
		);

		$this->add_control(
			'preview_archive_search',
			[
				'label' => esc_html__( 'Search Keyword', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'condition' => [
					'preview_source' => 'archive/search',
				]
			]
		);

		// Posts
		foreach ( $post_types as $slug => $title ) {
			$latest_post = get_posts('post_type='. $slug .'&numberposts=1');

			$this->add_control(
				'preview_single_'. $slug,
				[
					'label' => 'Select '. $title,
					'type' => Controls_Manager::SELECT2,
					'default' => !empty($latest_post) ? $latest_post[0]->ID : '',
					'label_block' => true,
					'options' => Utilities::get_posts_by_post_type( $slug ),
					'separator' => 'before',
					'condition' => [
						'preview_source' => $slug,
					],
				]
			);
		}

		// Taxonomies
		foreach ( $post_taxonomies as $slug => $title ) {
			if ( 'category' === $slug || 'post_tag' === $slug ) {
				$title = 'Post '. $title;
			}

			$this->add_control(
				'preview_archive_'. $slug,
				[
					'label' => 'Select '. $title,
					'type' => Controls_Manager::SELECT2,
					'label_block' => true,
					'options' => Utilities::get_terms_by_taxonomy( $slug ),
					'separator' => 'before',
					'condition' => [
						'preview_source' => $slug,
					],
				]
			);
		}

		$this->add_control(
			'submit_preview_changes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-update-preview editor-wpr-preview-update"><span>Update changes to Preview</span><button class="elementor-button elementor-button-success">Apply</button>',
                'separator' => 'after'
            ]
		);

		$this->end_controls_section();

		// Default Document Settings
		parent::_register_controls();
	}

	public function get_default_post_type() {
		$slug = get_post_meta( get_the_ID(), '_wpr_template_type', true  );

		if ( 0 === strpos( $slug, 'single' ) ) {
			return 'post';
		} else {
			return 'archive/post';
		}
	}

	public function get_tax_query_args( $tax, $terms ) {
		$terms = empty($terms) ? [ 'all' ] : $terms;

		$args = [
			'tax_query' => [
				[
					'taxonomy' => $tax,
					'terms' => $terms,
					'field' => 'id',
				],
			],
		];

		return $args;
	}

	public function get_document_query_args() {
		$settings = $this->get_settings();
		$source = $settings['preview_source'];
		$args = false;

		// Default Archives
		switch ( $source ) {
			case 'archive/post':
				$args = [ 'post_type' => 'post' ];
				break;

			case 'archive/author':
				$args = [ 'author' => $settings['preview_archive_author'] ];
				break;

			case 'archive/search':
				$args = [ 's' => $settings['preview_archive_search'] ];
				break;
		}

		// Taxonomy Archives
		foreach ( Utilities::get_custom_types_of( 'tax', false ) as $slug => $title ) {
			if ( $slug === $source ) {
				$args = $this->get_tax_query_args( $slug, $settings[ 'preview_archive_'. $slug ] );
			}
		}

		// Singular Posts
		foreach ( Utilities::get_custom_types_of( 'post', false ) as $slug => $title ) {
			if ( $slug === $source ) {
				// Get Post
				$post = get_posts( [
					'post_type' => $source,
					'numberposts' => 1,
					'orderby' => 'date',
					'order' => 'DESC',
					'suppress_filters' => false,
				] );

				$args = [ 'post_type' => $source ];

				$post_id = $settings[ 'preview_single_'. $slug ];

				if ( ! empty( $post ) && '' === $post_id ) {
					$args['p'] = $post[0]->ID;
				} else {
					$args['p'] = $post_id;
				}
			}
		}

		// Default
		if ( false === $args ) {
			// Get Post
			$post = get_posts( [
				'post_type' => 'post',
				'numberposts' => 1,
				'orderby' => 'date',
				'order' => 'DESC',
				'suppress_filters' => false,
			] );

			$args = [ 'post_type' => 'post' ];

			// Last Post for Single Pages
			if ( ! empty( $post ) ) {
				$args['p'] = $post[0]->ID;
			}
		}

		return $args;
	}

	public function switch_to_preview_query() {
		if ( 'wpr_templates' === get_post_type( get_the_ID() ) ) {
		$document = Elementor\Plugin::instance()->documents->get_doc_or_auto_save( get_the_ID() );

			Elementor\Plugin::instance()->db->switch_to_query( $document->get_document_query_args() );
		}
	}

	public function get_content( $with_css = false ) {
		$this->switch_to_preview_query();

		$content = parent::get_content( $with_css );

		Elementor\Plugin::instance()->db->restore_current_query();

		return $content;
	}

	public function print_content() {
		$plugin = Plugin::elementor();

		if ( $plugin->preview->is_preview_mode( $this->get_main_id() ) ) {
			echo $plugin->preview->builder_wrapper( '' );
		} else {
			echo $this->get_content();
		}
	}

	public static function get_preview_as_default() {
		return '';
	}

	public static function get_preview_as_options() {
		return [];
	}
	
	public function get_elements_raw_data( $data = null, $with_html_content = false ) {

		$this->switch_to_preview_query();

		$editor_data = parent::get_elements_raw_data( $data, $with_html_content );

		Elementor\Plugin::instance()->db->restore_current_query();

		return $editor_data;
	}

	public function render_element( $data ) {

		$this->switch_to_preview_query();

		$render_html = parent::render_element( $data );

		Elementor\Plugin::instance()->db->restore_current_query();

		return $render_html;
	}
}