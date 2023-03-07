<?php
namespace WprAddons\Modules\FormBuilder\Widgets;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Form_Builder extends Widget_Base {
	
	public function get_name() {
		return 'wpr-form-builder';
	}

	public function get_title() {
		return esc_html__( 'Form Builder', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-form-horizontal';
	}

	public function get_categories() {
		return [ 'wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'cf7', 'contact form 7', 'caldera forms', 'ninja forms', 'wpforms', 'wp forms' ];
	}

    public function get_custom_help_url() {
    	if ( empty(get_option('wpr_wl_plugin_links')) )
        // return 'https://royal-elementor-addons.com/contact/?ref=rea-plugin-panel-forms-help-btn';
    		return 'https://wordpress.org/support/plugin/royal-elementor-addons/';
    }

	// Allow overwrite the control_id with a prefix, @see Email2
	protected function get_control_id( $control_id ) {
		return $control_id;
	}

	public function get_label() {
		return esc_html__( 'Email', 'elementor-pro' );
	}

	public static function get_site_domain() {
		return str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	}

	public function register_settings_section_email( $widget ) {
		$widget->start_controls_section(
			$this->get_control_id( 'section_email' ),
			[
				'label' => $this->get_label(),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'submit_actions' => 'email',
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_to' ),
			[
				'label' => esc_html__( 'To', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => get_option( 'admin_email' ),
				'placeholder' => get_option( 'admin_email' ),
				'label_block' => true,
				'title' => esc_html__( 'Separate emails with commas', 'elementor-pro' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		/* translators: %s: Site title. */
		$default_message = sprintf( esc_html__( 'New message from "%s"', 'elementor-pro' ), get_option( 'blogname' ) );

		$widget->add_control(
			$this->get_control_id( 'email_subject' ),
			[
				'label' => esc_html__( 'Subject', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_message,
				'placeholder' => $default_message,
				'label_block' => true,
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_content' ),
			[
				'label' => esc_html__( 'Message', 'elementor-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '[all-fields]',
				'placeholder' => '[all-fields]',
				'description' => sprintf(
					/* translators: %s: The [all-fields] shortcode. */
					esc_html__( 'By default, all form fields are sent via %s shortcode. To customize sent fields, copy the shortcode that appears inside each field and paste it above.', 'elementor-pro' ),
					'<code>[all-fields]</code>'
				),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$site_domain = $this->get_site_domain();

		$widget->add_control(
			$this->get_control_id( 'email_from' ),
			[
				'label' => esc_html__( 'From Email', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'email@' . $site_domain,
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_from_name' ),
			[
				'label' => esc_html__( 'From Name', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => get_bloginfo( 'name' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_reply_to' ),
			[
				'label' => esc_html__( 'Reply-To', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => '',
				],
				'render_type' => 'none',
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_to_cc' ),
			[
				'label' => esc_html__( 'Cc', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__( 'Separate emails with commas', 'elementor-pro' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_to_bcc' ),
			[
				'label' => esc_html__( 'Bcc', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__( 'Separate emails with commas', 'elementor-pro' ),
				'render_type' => 'none',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			$this->get_control_id( 'form_metadata' ),
			[
				'label' => esc_html__( 'Meta Data', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'separator' => 'before',
				'default' => [
					'date',
					'time',
					'page_url',
					'user_agent',
					'remote_ip',
					'credit',
				],
				'options' => [
					'date' => esc_html__( 'Date', 'elementor-pro' ),
					'time' => esc_html__( 'Time', 'elementor-pro' ),
					'page_url' => esc_html__( 'Page URL', 'elementor-pro' ),
					'user_agent' => esc_html__( 'User Agent', 'elementor-pro' ),
					'remote_ip' => esc_html__( 'Remote IP', 'elementor-pro' ),
					'credit' => esc_html__( 'Credit', 'elementor-pro' ),
				],
				'render_type' => 'none',
			]
		);

		$widget->add_control(
			$this->get_control_id( 'email_content_type' ),
			[
				'label' => esc_html__( 'Send As', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'html',
				'render_type' => 'none',
				'options' => [
					'html' => esc_html__( 'HTML', 'elementor-pro' ),
					'plain' => esc_html__( 'Plain', 'elementor-pro' ),
				],
			]
		);

		$widget->end_controls_section();
	}

	protected function register_controls() {

		$repeater = new Repeater();

		$field_types = [
			'text' => esc_html__( 'Text', 'wpr-addons' ),
			'email' => esc_html__( 'Email', 'wpr-addons' ),
			'textarea' => esc_html__( 'Textarea', 'wpr-addons' ),
			'url' => esc_html__( 'URL', 'wpr-addons' ),
			'tel' => esc_html__( 'Tel', 'wpr-addons' ),
			'radio' => esc_html__( 'Radio', 'wpr-addons' ),
			'select' => esc_html__( 'Select', 'wpr-addons' ),
			'checkbox' => esc_html__( 'Checkbox', 'wpr-addons' ),
			'acceptance' => esc_html__( 'Acceptance', 'wpr-addons' ),
			'number' => esc_html__( 'Number', 'wpr-addons' ),
			'date' => esc_html__( 'Date', 'wpr-addons' ),
			'time' => esc_html__( 'Time', 'wpr-addons' ),
			'upload' => esc_html__( 'File Upload', 'wpr-addons' ),
			'password' => esc_html__( 'Password', 'wpr-addons' ),
			'html' => esc_html__( 'HTML', 'wpr-addons' ),
			'hidden' => esc_html__( 'Hidden', 'wpr-addons' ),
		];

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_forms_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater->start_controls_tabs( 'form_fields_tabs' );

		$repeater->start_controls_tab( 'form_fields_content_tab', [
			'label' => esc_html__( 'Content', 'wpr-addons' ),
		] );

		$repeater->add_control(
			'field_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'field_label',
			[
				'label' => esc_html__( 'Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label' => esc_html__( 'Placeholder', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'tel',
								'text',
								'email',
								'textarea',
								'number',
								'url',
								'password',
							],
						],
					],
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'required',
			[
				'label' => esc_html__( 'Required', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'checkbox',
								'recaptcha',
								'recaptcha_v3',
								'hidden',
								'html',
								'step',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'label' => esc_html__( 'Options', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'description' => esc_html__( 'Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name', 'wpr-addons' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'allow_multiple',
			[
				'label' => esc_html__( 'Multiple Selection', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'select_size',
			[
				'label' => esc_html__( 'Rows', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'step' => 1,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
						[
							'name' => 'allow_multiple',
							'value' => 'true',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'inline_list',
			[
				'label' => esc_html__( 'Inline List', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'elementor-subgroup-inline',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_html',
			[
				'label' => esc_html__( 'HTML', 'wpr-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'html',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Column Width', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'wpr-addons' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'70' => '70%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'30' => '30%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'hidden',
								'recaptcha',
								'recaptcha_v3',
								'step',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'rows',
			[
				'label' => esc_html__( 'Rows', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'textarea',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_size', [
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'wpr-addons' ),
					'compact' => esc_html__( 'Compact', 'wpr-addons' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => esc_html__( 'Light', 'wpr-addons' ),
					'dark' => esc_html__( 'Dark', 'wpr-addons' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_badge', [
				'label' => esc_html__( 'Badge', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottomright',
				'options' => [
					'bottomright' => esc_html__( 'Bottom Right', 'wpr-addons' ),
					'bottomleft' => esc_html__( 'Bottom Left', 'wpr-addons' ),
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
				],
				'description' => esc_html__( 'To view the validation badge, switch to preview mode', 'wpr-addons' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha_v3',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'css_classes',
			[
				'label' => esc_html__( 'CSS Classes', 'wpr-addons' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'title' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'wpr-addons' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'form_fields_advanced_tab',
			[
				'label' => esc_html__( 'Advanced', 'wpr-addons' ),
				'condition' => [
					'field_type!' => 'html',
				],
			]
		);

		$repeater->add_control(
			'field_value',
			[
				'label' => esc_html__( 'Default Value', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'text',
								'email',
								'textarea',
								'url',
								'tel',
								'radio',
								'select',
								'number',
								'date',
								'time',
								'hidden',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_id',
			[
				'label' => esc_html__( 'ID', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere in this form. This field allows `A-z 0-9` & underscore chars without spaces.', 'wpr-addons' ),
				'render_type' => 'none',
				'required' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$shortcode_template = '{{ view.container.settings.get( \'custom_id\' ) }}';
		$repeater->add_control(
			'shortcode',
			[
				'label' => esc_html__( 'Shortcode', 'wpr-addons' ),
				'type' => Controls_Manager::RAW_HTML,
				'classes' => 'forms-field-shortcode',
				'raw' => '<input class="elementor-form-field-shortcode" value=\'[field id="' . $shortcode_template . '"]\' readonly />',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => esc_html__( 'Form Fields', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'form_name',
			[
				'label' => esc_html__( 'Form Name', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'New Form', 'wpr-addons' ),
				'placeholder' => esc_html__( 'Form Name', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'form_fields',
			[
				// 'type' => Fields_Repeater::CONTROL_TYPE,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'custom_id' => 'name',
						'field_type' => 'text',
						'field_label' => esc_html__( 'Name', 'wpr-addons' ),
						'placeholder' => esc_html__( 'Name', 'wpr-addons' ),
						'width' => '100',
						'dynamic' => [
							'active' => true,
						],
					],
					[
						'custom_id' => 'email',
						'field_type' => 'email',
						'required' => 'true',
						'field_label' => esc_html__( 'Email', 'wpr-addons' ),
						'placeholder' => esc_html__( 'Email', 'wpr-addons' ),
						'width' => '100',
					],
					[
						'custom_id' => 'message',
						'field_type' => 'textarea',
						'field_label' => esc_html__( 'Message', 'wpr-addons' ),
						'placeholder' => esc_html__( 'Message', 'wpr-addons' ),
						'width' => '100',
					],
				],
				'title_field' => '{{{ field_label }}}',
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => esc_html__( 'Input Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => esc_html__( 'Extra Small', 'wpr-addons' ),
					'sm' => esc_html__( 'Small', 'wpr-addons' ),
					'md' => esc_html__( 'Medium', 'wpr-addons' ),
					'lg' => esc_html__( 'Large', 'wpr-addons' ),
					'xl' => esc_html__( 'Extra Large', 'wpr-addons' ),
				],
				'default' => 'sm',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => esc_html__( 'Label', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'wpr-addons' ),
				'label_off' => esc_html__( 'Hide', 'wpr-addons' ),
				'return_value' => 'true',
				'default' => 'true',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mark_required',
			[
				'label' => esc_html__( 'Required Mark', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'wpr-addons' ),
				'label_off' => esc_html__( 'Hide', 'wpr-addons' ),
				'default' => '',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_position',
			[
				'label' => esc_html__( 'Label Position', 'wpr-addons' ),
				'type' => Controls_Manager::HIDDEN,
				'options' => [
					'above' => esc_html__( 'Above', 'wpr-addons' ),
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
				],
				'default' => 'above',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_buttons',
			[
				'label' => esc_html__( 'Buttons', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => esc_html__( 'Size', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => esc_html__( 'Column Width', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', 'elementor-pro' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'70' => '70%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'30' => '30%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'elementor-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'elementor-pro' ),
						'icon' => 'eicon-text-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Justified', 'elementor-pro' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'stretch',
				'prefix_class' => 'elementor%s-button-align-',
			]
		);

		$this->add_control(
			'heading_steps_buttons',
			[
				'label' => esc_html__( 'Step Buttons', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'step_next_label',
			[
				'label' => esc_html__( 'Next', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'default' => esc_html__( 'Next', 'elementor-pro' ),
				'placeholder' => esc_html__( 'Next', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'step_previous_label',
			[
				'label' => esc_html__( 'Previous', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'default' => esc_html__( 'Previous', 'elementor-pro' ),
				'placeholder' => esc_html__( 'Previous', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'heading_submit_button',
			[
				'label' => esc_html__( 'Submit Button', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Submit', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Send', 'elementor-pro' ),
				'placeholder' => esc_html__( 'Send', 'elementor-pro' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'selected_button_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor-pro' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'elementor-pro' ),
					'right' => esc_html__( 'After', 'elementor-pro' ),
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => esc_html__( 'Button ID', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor-pro' ),
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows `A-z 0-9` & underscore chars without spaces.', 'elementor-pro' ),
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_integration',
			[
				'label' => esc_html__( 'Actions After Submit', 'elementor-pro' ),
			]
		);

		$actions = [
			'email' => 'Email'
		];
		// $actions = Module::instance()->actions_registrar->get();

		$actions_options = [];

		foreach ( $actions as $key => $action ) {
			$actions_options[ $key ] = $action;
		}

		$default_submit_actions = [ 'email' ];

		/**
		 * Default submit actions.
		 *
		 * Filters the list of submit actions pre deffined by Elementor forms.
		 *
		 * By default, only one submit action is set by Elementor forms, an 'email'
		 * action. This hook allows developers to alter those submit action.
		 *
		 * @param array $default_submit_actions A list of default submit actions.
		 */
		// $default_submit_actions = apply_filters( 'elementor_pro/forms/default_submit_actions', $default_submit_actions );

		$this->add_control(
			'submit_actions',
			[
				'label' => esc_html__( 'Add Action', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $actions_options,
				'render_type' => 'none',
				'label_block' => true,
				'default' => $default_submit_actions,
				'description' => esc_html__( 'Add actions that will be performed after a visitor submits the form (e.g. send an email notification). Choosing an action will add its setting below.', 'elementor-pro' ),
			]
		);

		$this->end_controls_section();

		$this->register_settings_section_email($this);
    }

	public static function get_button_sizes() {
		return [
			'xs' => esc_html__( 'Extra Small', 'elementor-pro' ),
			'sm' => esc_html__( 'Small', 'elementor-pro' ),
			'md' => esc_html__( 'Medium', 'elementor-pro' ),
			'lg' => esc_html__( 'Large', 'elementor-pro' ),
			'xl' => esc_html__( 'Extra Large', 'elementor-pro' ),
		];
	}

	private function add_required_attribute( $element ) {
		$this->add_render_attribute( $element, 'required', 'required' );
		$this->add_render_attribute( $element, 'aria-required', 'true' );
	}

	public function get_attribute_name( $item ) {
		return "form_fields[{$item['custom_id']}]";
	}

	public function get_attribute_id( $item ) {
		return 'form-field-' . $item['custom_id'];
	}

	protected function make_textarea_field( $item, $item_index ) {
		$this->add_render_attribute( 'textarea' . $item_index, [
			'class' => [
				'elementor-field-textual',
				'elementor-field',
				esc_attr( $item['css_classes'] ),
				'elementor-size-' . $item['input_size'],
			],
			'name' => $this->get_attribute_name( $item ),
			'id' => $this->get_attribute_id( $item ),
			'rows' => $item['rows'],
		] );

		if ( $item['placeholder'] ) {
			$this->add_render_attribute( 'textarea' . $item_index, 'placeholder', $item['placeholder'] );
		}

		if ( $item['required'] ) {
			$this->add_required_attribute( 'textarea' . $item_index );
		}

		$value = empty( $item['field_value'] ) ? '' : $item['field_value'];

		return '<textarea ' . $this->get_render_attribute_string( 'textarea' . $item_index ) . '>' . $value . '</textarea>';
	}

	protected function make_select_field( $item, $i ) {
		$this->add_render_attribute(
			[
				'select-wrapper' . $i => [
					'class' => [
						'elementor-field',
						'elementor-select-wrapper',
						'remove-before',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select' . $i => [
					'name' => $this->get_attribute_name( $item ) . ( ! empty( $item['allow_multiple'] ) ? '[]' : '' ),
					'id' => $this->get_attribute_id( $item ),
					'class' => [
						'elementor-field-textual',
						'elementor-size-' . $item['input_size'],
					],
				],
			]
		);

		if ( $item['required'] ) {
			$this->add_required_attribute( 'select' . $i );
		}

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'select' . $i, 'multiple' );
			if ( ! empty( $item['select_size'] ) ) {
				$this->add_render_attribute( 'select' . $i, 'size', $item['select_size'] );
			}
		}

		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );

		if ( ! $options ) {
			return '';
		}

		ob_start();
		?>
		<div <?php $this->print_render_attribute_string( 'select-wrapper' . $i ); ?>>
			<div class="select-caret-down-wrapper">
				<?php
				if ( ! $item['allow_multiple'] ) {
					$icon = [
						'library' => 'eicons',
						'value' => 'eicon-caret-down',
						'position' => 'right',
					];
					Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
				}
				?>
			</div>
			<select <?php $this->print_render_attribute_string( 'select' . $i ); ?>>
				<?php
				foreach ( $options as $key => $option ) {
					$option_id = $item['custom_id'] . $key;
					$option_value = esc_attr( $option );
					$option_label = esc_html( $option );

					if ( false !== strpos( $option, '|' ) ) {
						list( $label, $value ) = explode( '|', $option );
						$option_value = esc_attr( $value );
						$option_label = esc_html( $label );
					}

					$this->add_render_attribute( $option_id, 'value', $option_value );

					// Support multiple selected values
					if ( ! empty( $item['field_value'] ) && in_array( $option_value, explode( ',', $item['field_value'] ) ) ) {
						$this->add_render_attribute( $option_id, 'selected', 'selected' );
					} ?>
					<option <?php $this->print_render_attribute_string( $option_id ); ?>><?php
						// PHPCS - $option_label is already escaped
						echo $option_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></option>
				<?php } ?>
			</select>
		</div>
		<?php

		$select = ob_get_clean();
		return $select;
	}

	protected function make_radio_checkbox_field( $item, $item_index, $type ) {
		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );
		$html = '';
		if ( $options ) {
			$html .= '<div class="elementor-field-subgroup ' . esc_attr( $item['css_classes'] ) . ' ' . $item['inline_list'] . '">';
			foreach ( $options as $key => $option ) {
				$element_id = $item['custom_id'] . $key;
				$html_id = $this->get_attribute_id( $item ) . '-' . $key;
				$option_label = $option;
				$option_value = $option;
				if ( false !== strpos( $option, '|' ) ) {
					list( $option_label, $option_value ) = explode( '|', $option );
				}

				$this->add_render_attribute(
					$element_id,
					[
						'type' => $type,
						'value' => $option_value,
						'id' => $html_id,
						'name' => $this->get_attribute_name( $item ) . ( ( 'checkbox' === $type && count( $options ) > 1 ) ? '[]' : '' ),
					]
				);

				if ( ! empty( $item['field_value'] ) && $option_value === $item['field_value'] ) {
					$this->add_render_attribute( $element_id, 'checked', 'checked' );
				}

				if ( $item['required'] && 'radio' === $type ) {
					$this->add_required_attribute( $element_id );
				}

				$html .= '<span class="elementor-field-option"><input ' . $this->get_render_attribute_string( $element_id ) . '> <label for="' . $html_id . '">' . $option_label . '</label></span>';
			}
			$html .= '</div>';
		}

		return $html;
	}	
	
	protected function form_fields_render_attributes( $i, $instance, $item ) {
		$this->add_render_attribute(
			[
				'field-group' . $i => [
					'class' => [
						'elementor-field-type-' . $item['field_type'],
						'elementor-field-group',
						'elementor-column',
						'elementor-field-group-' . $item['custom_id'],
					],
				],
				'input' . $i => [
					'type' => $item['field_type'],
					'name' => $this->get_attribute_name( $item ),
					'id' => $this->get_attribute_id( $item ),
					'class' => [
						'elementor-field',
						'elementor-size-' . $item['input_size'],
						empty( $item['css_classes'] ) ? '' : esc_attr( $item['css_classes'] ),
					],
				],
				'label' . $i => [
					'for' => $this->get_attribute_id( $item ),
					'class' => 'elementor-field-label',
				],
			]
		);

		if ( empty( $item['width'] ) ) {
			$item['width'] = '100';
		}

		$this->add_render_attribute( 'field-group' . $i, 'class', 'elementor-col-' . $item['width'] );

		if ( ! empty( $item['width_tablet'] ) ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'elementor-md-' . $item['width_tablet'] );
		}

		if ( $item['allow_multiple'] ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'elementor-field-type-' . $item['field_type'] . '-multiple' );
		}

		if ( ! empty( $item['width_mobile'] ) ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'elementor-sm-' . $item['width_mobile'] );
		}

		// Allow zero as placeholder.
		if ( ! Utils::is_empty( $item['placeholder'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'placeholder', $item['placeholder'] );
		}

		if ( ! empty( $item['field_value'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'value', $item['field_value'] );
		}

		if ( ! $instance['show_labels'] ) {
			$this->add_render_attribute( 'label' . $i, 'class', 'elementor-screen-only' );
		}

		if ( ! empty( $item['required'] ) ) {
			$class = 'elementor-field-required';
			if ( ! empty( $instance['mark_required'] ) ) {
				$class .= ' elementor-mark-required';
			}
			$this->add_render_attribute( 'field-group' . $i, 'class', $class );
			$this->add_required_attribute( 'input' . $i );
		}
	}

	protected function render() {
		$instance = $this->get_settings_for_display();

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'elementor-form-fields-wrapper',
						'elementor-labels-' . $instance['label_position'],
					],
				],
				'submit-group' => [
					'class' => [
						'elementor-field-group',
						'elementor-column',
						'elementor-field-type-submit',
					],
				],
				'button' => [
					'class' => 'elementor-button',
				],
				'icon-align' => [
					'class' => [
						empty( $instance['button_icon_align'] ) ? '' :
							'elementor-align-icon-' . $instance['button_icon_align'],
						'elementor-button-icon',
					],
				],
			]
		);

		if ( empty( $instance['button_width'] ) ) {
			$instance['button_width'] = '100';
		}

		$this->add_render_attribute( 'submit-group', 'class', 'elementor-col-' . $instance['button_width'] . ' e-form__buttons' );

		if ( ! empty( $instance['button_width_tablet'] ) ) {
			$this->add_render_attribute( 'submit-group', 'class', 'elementor-md-' . $instance['button_width_tablet'] );
		}

		if ( ! empty( $instance['button_width_mobile'] ) ) {
			$this->add_render_attribute( 'submit-group', 'class', 'elementor-sm-' . $instance['button_width_mobile'] );
		}

		if ( ! empty( $instance['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $instance['button_size'] );
		}

		if ( ! empty( $instance['button_type'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-button-' . $instance['button_type'] );
		}

		// if ( $instance['button_hover_animation'] ) {
		// 	$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $instance['button_hover_animation'] );
		// }

		if ( ! empty( $instance['form_id'] ) ) {
			$this->add_render_attribute( 'form', 'id', $instance['form_id'] );
		}

		if ( ! empty( $instance['form_name'] ) ) {
			$this->add_render_attribute( 'form', 'name', $instance['form_name'] );
		}

		if ( ! empty( $instance['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $instance['button_css_id'] );
		}

		$referer_title = trim( wp_title( '', false ) );

		if ( ! $referer_title && is_home() ) {
			$referer_title = get_option( 'blogname' );
		}

		?>
		<form class="wpr-form" method="post" <?php echo $this->get_render_attribute_string( 'form' ); ?>>
			<input type="hidden" name="post_id" value="<?php // PHPCS - the method Utils::get_current_post_id is safe.
				echo get_the_ID(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"/>
			<input type="hidden" name="form_id" value="<?php echo esc_attr( $this->get_id() ); ?>"/>
			<input type="hidden" name="referer_title" value="<?php echo esc_attr( $referer_title ); ?>" />

			<?php if ( is_singular() ) {
				// `queried_id` may be different from `post_id` on Single theme builder templates.
				?>
				<input type="hidden" name="queried_id" value="<?php echo get_the_ID(); ?>"/>
			<?php } ?>

			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<?php
				foreach ( $instance['form_fields'] as $item_index => $item ) :
					$item['input_size'] = $instance['input_size'];
					$this->form_fields_render_attributes( $item_index, $instance, $item );

					$field_type = $item['field_type'];

					$print_label = ! in_array( $item['field_type'], [ 'hidden', 'html', 'step' ], true );
					?>
					<div <?php $this->print_render_attribute_string( 'field-group' . $item_index ); ?>>
						<?php
						if ( $print_label && $item['field_label'] ) {
							?>
								<label <?php echo $this->get_render_attribute_string( 'label' . $item_index ); ?>>
									<?php // PHPCS - the variable $item['field_label'] is safe.
									echo $item['field_label']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</label>
							<?php
						}

						switch ( $item['field_type'] ) :
							case 'html':
								echo do_shortcode( $item['field_html'] );
								break;
							case 'textarea':
								// PHPCS - the method make_textarea_field is safe.
								echo $this->make_textarea_field( $item, $item_index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								break;

							case 'select':
								// PHPCS - the method make_select_field is safe.
								echo $this->make_select_field( $item, $item_index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								break;

							case 'radio':
							case 'checkbox':
								// PHPCS - the method make_radio_checkbox_field is safe.
								echo $this->make_radio_checkbox_field( $item, $item_index, $item['field_type'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								break;
							case 'text':
							case 'email':
							case 'url':
							case 'password':
							case 'hidden':
							case 'search':
								$this->add_render_attribute( 'input' . $item_index, 'class', 'elementor-field-textual' );
								?>
									<input size="1" <?php echo $this->get_render_attribute_string( 'input' . $item_index ); ?>>
								<?php
								break;
							default:
								$field_type = $item['field_type'];
	
								/**
								 * Elementor form field render.
								 *
								 * Fires when a field is rendered in the frontend. This hook allows developers to
								 * add functionality when from fields are rendered.
								 *
								 * The dynamic portion of the hook name, `$field_type`, refers to the field type.
								 *
								 * @since 1.0.0
								 *
								 * @param array $item       The field value.
								 * @param int   $item_index The field index.
								 * @param Form  $this       An instance of the form.
								 */
								do_action( "elementor_pro/forms/render_field/{$field_type}", $item, $item_index, $this );
						endswitch;
						?>
					</div>
				<?php endforeach; ?>
				<div <?php echo $this->get_render_attribute_string( 'submit-group' ); ?>>
					<button type="submit" <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
							<?php if ( ! empty( $instance['button_icon'] ) || ! empty( $instance['selected_button_icon'] ) ) : ?>
								<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
									<?php if ( empty( $instance['button_text'] ) ) : ?>
										<span class="elementor-screen-only"><?php echo esc_html__( 'Submit', 'elementor-pro' ); ?></span>
									<?php endif; ?>
								</span>
							<?php endif; ?>
							<?php if ( ! empty( $instance['button_text'] ) ) : ?>
								<span class="elementor-button-text"><?php echo $this->print_unescaped_setting( 'button_text' ); ?></span>
							<?php endif; ?>
						</span>
					</button>
				</div>
			</div>
		</form>
		<?php
	}
}