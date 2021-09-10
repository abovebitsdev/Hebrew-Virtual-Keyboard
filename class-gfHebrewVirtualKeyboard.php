<?php

GFForms::include_addon_framework();

class GFHebrewVirtualKeyboardAddOn extends GFAddOn
{

	protected $_version = GF_HEBREW_VIRTUAL_KEYBOARD_ADDON_VERSION;
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'hebrewVirtualKeyboard';
	protected $_path = 'hebrew-virtual-keyboard/hebrewVirtualKeyboard.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Hebrew Virtual Keyboard Add-On';
	protected $_short_title = 'Hebrew Virtual Keyboard Add-On';

	private static $_instance = null;

	/**
	 * Get an instance of this class.
	 *
	 * @return GFHebrewVirtualKeyboardAddOn
	 */
	public static function get_instance()
	{
		if (self::$_instance == null) {
			self::$_instance = new GFHebrewVirtualKeyboardAddOn();
		}

		return self::$_instance;
	}

	/**
	 * Handles hooks and loading of language files.
	 */
	public function init()
	{
		parent::init();
		add_action('gform_after_submission', array($this, 'after_submission'), 10, 2);
		add_action('gform_field_advanced_settings', array($this, 'add_virtual_keyboard'), 10, 2);
		add_action('gform_editor_js', array($this, 'editor_script_styles'));
		add_filter('gform_tooltips', array($this, 'add_keyboard_tooltips'));
		add_filter('gform_field_css_class', array($this, 'show_keyboard'), 10, 3);
		add_filter('gform_field_content', array($this, 'appendIcon'), 10, 5);
		add_action('wp_enqueue_scripts', array($this, 'styles'));
	}


	// # SCRIPTS & STYLES -----------------------------------------------------------------------------------------------

	/**
	 * Return the scripts which should be enqueued.
	 *
	 * @return array
	 */
	public function scripts()
	{
		wp_enqueue_script("virtualKeyboard", $this->get_base_url() . '/libs/virtual-keyboard/dist/js/jquery.keyboard.min.js', array("jquery"), time());
		wp_enqueue_script("virtualKeyboardExtensions", $this->get_base_url() . '/libs/virtual-keyboard/dist/js/jquery.keyboard.extension-all.min.js', array("jquery"), time());
		wp_enqueue_script("virtualKeyboardHebrew", $this->get_base_url() . '/libs/virtual-keyboard/dist/languages/he.min.js', array("jquery"), time());
		wp_enqueue_script("keyboard", $this->get_base_url() . '/js/script.js', array("virtualKeyboard", "virtualKeyboardExtensions"), time());
		return parent::scripts();
	}

	/**
	 * Return the stylesheets which should be enqueued.
	 *
	 * @return array
	 */
	public function styles()
	{
		wp_enqueue_style("keyboard", $this->get_base_url() . '/css/styles.css', array("virtualKeyboard"), time());
		wp_enqueue_style("virtualKeyboard", $this->get_base_url() . '/libs/virtual-keyboard/dist/css/keyboard.min.css', null, time());
		return parent::styles();
	}




	// # FRONTEND FUNCTIONS --------------------------------------------------------------------------------------------

	/**
	 * Add the text in the plugin settings to the bottom of the form if enabled for this form.
	 *
	 * @param string $input The string containing the input tag to be filtered.
	 * @param array $form The form currently being displayed.
	 *
	 * @return string
	 */

	function show_keyboard($classes, $field, $form) // Adds class to the field
	{
		if ($field->keyboardField == 'true') {
			$classes .= ' addKeyboard';
		}
		return $classes;
	}

	function appendIcon($field_content, $field) // Adds data attributes  
	{
		$type = $this->get_plugin_setting('textOrIcon');
		$text = $this->get_plugin_setting('buttonText');
		$path = $this->get_plugin_setting('buttonImage');
		$title = $this->get_plugin_setting('buttonHoverTitle');
		//var_dump($field);
		if ($field->keyboardField == 'true') {
			return str_replace('name=', "data-type='" . $type . "' data-Text='" . $text . "' data-Title='" . $title . "' data-Image='" . $path . "' name=", $field_content);
		}
		return $field_content;
	}

	// # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------

	/**
	 * Configures the settings which should be rendered on the add-on settings tab.
	 *
	 * @return array
	 */
	public function plugin_settings_fields()
	{
		return array(
			array(
				'title'  => esc_html__('Hebrew Keyboard Settings', 'hebrewVirtualKeyboard'),
				'fields' => array(
					array(
						'label'   => esc_html__('What should be displayed on the button', 'hebrewVirtualKeyboard'),
						'type'    => 'radio',
						'name'    => 'textOrIcon',
						'tooltip' => esc_html__('Choose what type of content will be displayed on the button', 'hebrewVirtualKeyboard'),
						'default_value' => 'Text',
						'choices' => array(
							array(
								'label' => esc_html__('Text', 'hebrewVirtualKeyboard'),
							),
							array(
								'label' => esc_html__('Image', 'hebrewVirtualKeyboard'),
							),
						),
					),
					array(
						'name'              => 'buttonText',
						'tooltip'           => esc_html__('Enter text here. The text will be displayed on the button. Max 5 symbols', 'hebrewVirtualKeyboard'),
						'label'             => esc_html__('Text on the button', 'hebrewVirtualKeyboard'),
						'type'              => 'text',
						'class'             => 'small',
						'default_value' => 'א ב ג',
					),
					array(
						'name'              => 'buttonImage',
						'tooltip'           => esc_html__('Put the path to the image. The image will be displayed on the button', 'hebrewVirtualKeyboard'),
						'label'             => esc_html__('The icon on the virtual keyboard button', 'hebrewVirtualKeyboard'),
						'type'              => 'text',
						'class'             => 'small',
						'default_value' => '',
					),
					array(
						'name'              => 'buttonHoverTitle',
						'tooltip'           => esc_html__('Enter tooltip here. The tooltip will be displayed when hover on the button', 'hebrewVirtualKeyboard'),
						'label'             => esc_html__('Tooltip for the virtual keyboard button', 'hebrewVirtualKeyboard'),
						'type'              => 'text',
						'class'             => 'small',
						'default_value' => 'Click to open the virtual keyboard',
					)
				)
			)
		);
	}


	function add_virtual_keyboard($position, $form_id) // Adds checkbox in admin panel
	{
		if ($position == -1) { ?>
			<li class="keyboard_setting field_setting">
				<input type="checkbox" id="field_keyboard_value" onclick="SetFieldProperty('keyboardField', this.checked);" />
				<label for="field_keyboard_value" style="display:inline;">
					<?php _e("Show virtual keyboard", "your_text_domain"); ?>
					<?php gform_tooltip("form_field_keyboard") ?>
				</label>
			</li>
		<?php
		}
	}

	function editor_script_styles()  // Adds script to checkbox in admin panel
	{ ?>
		<script>
			//adding setting to fields of type "text"
			fieldSettings.text += ", .keyboard_setting";
			//binding to the load field settings event to initialize the checkbox
			jQuery(document).on("gform_load_field_settings", function(event, field, form) {
				jQuery('#field_keyboard_value').prop('checked', Boolean(rgar(field, 'keyboardField')));
			});

			jQuery("#gform_fields .selectable").click(function() {
				// const textInput = jQuery(this).find(".ginput_container_text").length > 0;
				if (jQuery(this).find(".ginput_container_text, .ginput_container_textarea").length > 0) {
					jQuery(this).addClass('showKeyboardCheckbox');
				}
			});
		</script>
		<style>
			.showKeyboardCheckbox .keyboard_setting {
				display: block !important;
			}
		</style>

<?php }
	function add_keyboard_tooltips($tooltips) // Adds tooltip to checkbox in admin panel
	{
		$tooltips['form_field_keyboard'] = "<h6>Virtual Keyboard</h6>";
		return $tooltips;
	}
}
