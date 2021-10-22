<?php
namespace WprAddons\Admin\Templates;

use WprAddons\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPR_Templates_Actions setup
 *
 * @since 1.0
 */
class WPR_Templates_Data {
	public static function get_available_blocks() {
		return [
			'grid' => [
				'v1' => ['type' => 'iframe', 'url' => 'grid/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'grid/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'grid/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'grid/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'grid/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'grid/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'grid/v7/'],
				'v8-pro' => ['type' => 'iframe', 'url' => 'grid/v8/'],
				'v9-pro' => ['type' => 'iframe', 'url' => 'grid/v9/'],
				'v10-pro' => ['type' => 'iframe', 'url' => 'grid/v10/'],
			],
			'woo-grid' => [
				'v1' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v2/'],
				'v3-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'woocommerce-grid/v7/'],
			],
			'media-grid' => [
				'v1' => ['type' => 'iframe', 'url' => 'image-grid/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'image-grid/v2/'],
			],
			'magazine-grid' => [
				'v1' => ['type' => 'iframe', 'url' => 'magazine-grid/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'magazine-grid/v2/'],
				// 'v3' => ['type' => 'iframe', 'url' => 'magazine-grid/v3/', 'sub' => 'carousel'], <-- Keep as example
			],
			'advanced-slider' => [
				'v1' => ['type' => 'iframe', 'url' => 'advanced-slider/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'advanced-slider/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'advanced-slider/v3/'],
			],
			'testimonial' => [
				'v1' => ['type' => 'iframe', 'url' => 'testimonial-slider/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'testimonial-slider/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'testimonial-slider/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'testimonial-slider/v4/'],
			],
			'nav-menu' => [
				'v1' => ['type' => 'iframe', 'url' => 'nav-menu/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'nav-menu/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'nav-menu/v3/'],
			],
			'onepage-nav' => [
				'v1' => ['type' => 'iframe', 'url' => 'one-page-navigation/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'one-page-navigation/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'one-page-navigation/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'one-page-navigation/v4/'],
			],
			'pricing-table' => [
				'v1' => ['type' => 'iframe', 'url' => 'pricing-table/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'pricing-table/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'pricing-table/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'pricing-table/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'pricing-table/v5/'],
			],
			'content-toggle' => [
				'v1' => ['type' => 'iframe', 'url' => 'content-toggle/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'content-toggle/v2/'],
			],
			'countdown' => [
				'v1' => ['type' => 'iframe', 'url' => 'countdown/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'countdown/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'countdown/v3/'],
			],
			'progress-bar' => [
				'v1' => ['type' => 'iframe', 'url' => 'progress-bar/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'progress-bar/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'progress-bar/v3/'],
			],
			'tabs' => [
				'v1' => ['type' => 'iframe', 'url' => 'tabs/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'tabs/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'tabs/v3/'],
			],
			'advanced-text' => [
				'v1' => ['type' => 'iframe', 'url' => 'advanced-text/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'advanced-text/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'advanced-text/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'advanced-text/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'advanced-text/v5/'],
				'v6' => ['type' => 'iframe', 'url' => 'advanced-text/v6/'],
				'v7-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v7/'],
				'v8-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v8/'],
				'v9-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v9/'],
				'v10-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v10/'],
				'v11-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v11/'],
				'v12-pro' => ['type' => 'iframe', 'url' => 'advanced-text/v12/'],
			],
			'flip-box' => [
				'v1' => ['type' => 'iframe', 'url' => 'flip-box/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'flip-box/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'flip-box/v3/'],
			],
			'promo-box' => [
				'v1' => ['type' => 'iframe', 'url' => 'promo-box/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'promo-box/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'promo-box/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'promo-box/v4/'],
				'v5-pro' => ['type' => 'iframe', 'url' => 'promo-box/v5/'],
				'v6-pro' => ['type' => 'iframe', 'url' => 'promo-box/v6/'],
			],
			'before-after' => [
				'v1' => ['type' => 'iframe', 'url' => 'before-and-after/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'before-and-after/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'before-and-after/v3/'],
			],
			'image-hotspots' => [
				'v1' => ['type' => 'iframe', 'url' => 'image-hotspot/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'image-hotspot/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'image-hotspot/v3/'],
			],
			'forms' => [],
			'mailchimp' => [
				'v1' => ['type' => 'iframe', 'url' => 'mailchimp/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'mailchimp/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'mailchimp/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'mailchimp/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'mailchimp/v5/'],
			],
			'content-ticker' => [
				'v1' => ['type' => 'iframe', 'url' => 'content-ticker/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'content-ticker/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'content-ticker/v3/'],
				'v4-pro' => ['type' => 'iframe', 'url' => 'content-ticker/v4/'],
			],
			'button' => [
				'v1' => ['type' => 'iframe', 'url' => 'button/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'button/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'button/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'button/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'button/v5/'],
			],
			'dual-button' => [
				'v1' => ['type' => 'iframe', 'url' => 'dual-button/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'dual-button/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'dual-button/v3/'],
			],
			'team-member' => [
				'v1' => ['type' => 'iframe', 'url' => 'team-member/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'team-member/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'team-member/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'team-member/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'team-member/v5/']
			],
			'google-maps' => [
				'v1' => ['type' => 'iframe', 'url' => 'google-map/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'google-map/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'google-map/v3/'],
				'v4' => ['type' => 'iframe', 'url' => 'google-map/v4/'],
				'v5' => ['type' => 'iframe', 'url' => 'google-map/v5/'],
			],
			'price-list' => [
				'v1' => ['type' => 'iframe', 'url' => 'price-list/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'price-list/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'price-list/v3/'],
			],
			'business-hours' => [
				'v1' => ['type' => 'iframe', 'url' => 'business-hours/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'business-hours/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'business-hours/v3/'],
			],
			'sharing-buttons' => [
				'v1' => ['type' => 'iframe', 'url' => 'sharing-button/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'sharing-button/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'sharing-button/v3/'],
			],
			'logo' => [],
			'search' => [
				'v1' => ['type' => 'iframe', 'url' => 'search/v1/'],
				'v2' => ['type' => 'iframe', 'url' => 'search/v2/'],
				'v3' => ['type' => 'iframe', 'url' => 'search/v3/'],
			],
			'phone-call' => [],
			'back-to-top' => [],
		];
	}


	public static function get_available_popups() {
		return [
			// 'contact' => [
			// 	'v1' => ['type' => 'iframe', 'url' => 'search/v1/'],
			// 	'v2' => ['type' => 'iframe', 'url' => 'search/v2/'],
			// 	'v3' => ['type' => 'iframe', 'url' => 'search/v3/'],
			// ],
			'cookie' => [
				'v1' => ['type' => 'iframe', 'url' => 'search/v1/'],
			],
			'countdown' => [
				'v1' => ['type' => 'iframe', 'url' => 'search/v1/'],
			],
			// 'discount' => [
			// 	'v1' => ['type' => 'iframe', 'url' => 'search/v1/'],
			// 	'v2' => ['type' => 'iframe', 'url' => 'search/v2/'],
			// 	'v3' => ['type' => 'iframe', 'url' => 'search/v3/'],
			// ],
			// 'gdpr' => [
			// 	'v1' => ['type' => 'iframe', 'url' => 'search/v1/'],
			// 	'v2' => ['type' => 'iframe', 'url' => 'search/v2/'],
			// 	'v3' => ['type' => 'iframe', 'url' => 'search/v3/'],
			// ],
			// 'subscribe' => [
			// 	'v1' => ['type' => 'iframe', 'url' => 'search/v1/'],
			// 	'v2' => ['type' => 'iframe', 'url' => 'search/v2/'],
			// 	'v3' => ['type' => 'iframe', 'url' => 'search/v3/'],
			// ],
			// 'yesno' => [
			// 	'v1' => ['type' => 'iframe', 'url' => 'search/v1/'],
			// 	'v2' => ['type' => 'iframe', 'url' => 'search/v2/'],
			// 	'v3' => ['type' => 'iframe', 'url' => 'search/v3/'],
			// ],
		];
	}
}