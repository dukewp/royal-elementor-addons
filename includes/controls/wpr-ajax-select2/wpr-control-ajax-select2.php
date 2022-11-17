<?php
namespace WprAddons\Includes\Controls\WprAjaxSelect2;

use Elementor\Base_Data_Control;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class WPR_Control_Ajax_Select2 extends Base_Data_Control {
	public function get_type() {
		return 'wpr-ajax-select2';
	}

	public function enqueue() {
		wp_register_script( 'wpr-control-ajax-select2', WPR_ADDONS_URL . 'includes/controls/wpr-ajax-select2/js/wpr-control-ajax-select2.js' );
		wp_enqueue_script( 'wpr-control-ajax-select2' );
	}

	protected function get_default_settings() {
		return [
			'options' => [],
			'multiple' => false,
			'select2options' => [],
		];
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select 
					id="<?php echo esc_attr($control_uid); ?>" 
					class="elementor-megamenuajaxselect2" 
					type="megamenuajaxselect2" {{ multiple }} 
					data-setting="{{ data.name }}"  
					data-rest-url="<?php echo esc_attr( get_rest_url(). 'wpraddons/v1' . '/{{data.options}}/' ); ?>"
				>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
