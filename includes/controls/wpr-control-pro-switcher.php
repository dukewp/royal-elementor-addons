<?php
namespace WprAddons\Includes\Controls;

use Elementor\Base_Data_Control;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class WPR_Control_Pro_Switcher extends Base_Data_Control {
	private static $_arrow_icons;

	public function get_type() {
		return 'wpr-arrow-icons';
	}

	public static function get_arrow_icons() {
		if ( is_null( self::$_arrow_icons ) ) {
			self::$_arrow_icons = [
				'Font Awesome' => [
					'fas fa-angle' => esc_html__( 'Angle', 'wpr-addons' ),
					'fas fa-angle-double' => esc_html__( 'Angle Double', 'wpr-addons' ),
					'fas fa-arrow' => esc_html__( 'Arrow', 'wpr-addons' ),
					'fas fa-arrow-alt-circle' => esc_html__( 'Arrow Circle', 'wpr-addons' ),
					'far fa-arrow-alt-circle' => esc_html__( 'Arrow Circle Alt', 'wpr-addons' ),
					'fas fa-long-arrow-alt' => esc_html__( 'Long Arrow', 'wpr-addons' ),
					'fas fa-chevron' => esc_html__( 'Chevron', 'wpr-addons' ),
				],
				'Svg' => Utilities::get_svg_icons_array( 'arrows', [] ),
			];
		}

		return self::$_arrow_icons;
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<label class="elementor-switch elementor-control-unit-2">
					<input id="elementor-control-default-c13678" type="checkbox" data-setting="eael_section_data_table_enabled" class="elementor-switch-input" value="true">
					<span class="elementor-switch-label" data-on="Yes" data-off="No"></span>
					<span class="elementor-switch-handle"></span>
				</label>

			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}