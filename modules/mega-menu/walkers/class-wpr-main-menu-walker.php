<?php

class Wpr_Main_Menu_Walker extends \Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'mega' === $this->get_item_type() ) {
			return;
		}

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu', 'wpr-sub-menu' );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul $class_names>{$n}";
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'mega' === $this->get_item_type() ) {
			return;
		}


		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$this->set_item_type( $item->ID, $depth );
		
		if ( 'mega' === $this->get_item_type() && 0 < $depth ) {
			return;
		}

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$item_li_class = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$item_li_class = $item_li_class ? ' class="' . esc_attr( $item_li_class ) . '"' : '';
		

		$output .= '<li'. $id . $item_li_class .'>';

			$item_output = $args->before;
				$item_a_class = 'wpr-menu-item wpr-pointer-item';
				$submenu_icon = '';
				// Parent Item
				if ( in_array( 'menu-item-has-children', $item->classes ) ) {
					if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
						$item_a_class .= ' wpr-active-menu-item';
					}

					// Add Sub Menu Icon
					if ( $depth > 0 ) {
						$submenu_icon .='<i class="wpr-sub-icon fas wpr-sub-icon-rotate" aria-hidden="true"></i>';
					} else {
						$submenu_icon .='<i class="wpr-sub-icon fas" aria-hidden="true"></i>';
					}
				}

				// Sub Menu Classes
				if ( $depth > 0 ) {
					$item_a_class = 'wpr-sub-menu-item';

					if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current-menu-ancestor', $item->classes ) ) {
						$item_a_class .= ' wpr-active-menu-item';
					}
				}
				
				// Anchor Output
				$item_output .= '<a href="'. esc_url($item->url) .'" class="'. esc_attr($item_a_class) .'">';
					$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
					$item_output .= $submenu_icon;
				$item_output .= '</a>';
			$item_output .= $args->after;
		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( 'mega' === $this->get_item_type() && 0 < $depth ) {
			return;
		}
		
		if ( $depth === 0 ) {
			if ( $this->has_mega_menu($item->ID) ) {
				$elementor = \Elementor\Plugin::instance();
				$mega_id = get_post_meta( $item->ID, 'wpr-mega-menu-item', true);
				$content = $elementor->frontend->get_builder_content_for_display($mega_id);
				$output .= '<div class="wpr-sub-mega-menu">'. $content .'</div>';
			}

			$output .= "</li>\n";
		}
	}

	public function set_item_type( $item_id = 0, $depth = 0 ) {
		if ( 0 < $depth ) {
			return;
		}

		$item_type = 'simple';

		if ( $this->has_mega_menu( $item_id ) ) {
			$item_type = 'mega';
		}

		wp_cache_set( 'item-type', $item_type, 'wpr-mega-menu' );

	}

	public function get_item_type() {
		return wp_cache_get( 'item-type', 'wpr-mega-menu' );
	}

	public function has_mega_menu( $item_id ) {
		$mega_settings = get_post_meta( $item_id, 'wpr-mega-menu-settings', true);

		return isset($mega_settings['wpr_mm_enable']) && 'true' === $mega_settings['wpr_mm_enable'] ? true : false;
	}
}