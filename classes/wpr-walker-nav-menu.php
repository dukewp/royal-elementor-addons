<?php
namespace WprAddons\Classes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Walker class
 */

class Wpr_Walker_Nav_Menu extends \Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? ' wpr-mega-menu-test' : '';
        var_dump($depth > 0);
        var_dump($submenu);
        $output .= "\n$indent<ul class=\"drodown-menu$submenu depth_$depth\">\n";
    }

    // function start_el() {

    // }
}