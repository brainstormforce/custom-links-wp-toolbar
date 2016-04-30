<?php
/**
* Plugin Name: Custom Admin Bar URL
* Plugin URI: https://www.brainstormforce.com/
* Description: 
* Version: 1.0.0
* Author: Brainstorm Force
* Author URI: https://www.brainstormforce.com/
*/

//Block direct access to plugin files
defined( 'ABSPATH' ) or die();

if(!class_exists('Custom_Admin_Bar_Url_Class')){
	class Custom_Admin_Bar_Url_Class{

		//Class variables
		private $custom_admin_bar_url_option;
		
		/*
		 * Function Name: __construct
		 * Function Description: Constructor
		 */
		
		function __construct() {
			$this->custom_admin_bar_url_option = get_option( 'custom_admin_bar_url_option' );
			//echo '<xmp>'; print_r($this->custom_admin_bar_url_option); echo '</xmp>';

			add_action( 'admin_menu', array( $this, 'add_plugin_page' ), 9999 );
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action('admin_bar_menu', array( $this, 'custom_admin_bar_link' ), 999 );
		}

		public function custom_admin_bar_link( $wp_admin_bar ) {
			if( $this->custom_admin_bar_url_option['custom_label'] != '' && $this->custom_admin_bar_url_option['custom_url'] != '' ) {
				$args = array(
					'id' => 'vrunda',
					'title' => $this->custom_admin_bar_url_option['custom_label'], 
					'href' => $this->custom_admin_bar_url_option['custom_url'],
					'meta' => array(
						'class' => '', 
						'title' => 'Visit ' . $this->custom_admin_bar_url_option['custom_label'],
						'target' => '_blank'
					)
				);
				$wp_admin_bar->add_node($args);
			}
		}

		/*
		 * Function Name: admin_init
		 * Function Description: Admin initialization
		 */

		public function admin_init() {

			wp_register_script( 'sync-script', plugins_url('js/sync-script.js', __FILE__), array('jquery'), '1.1', true );
			wp_enqueue_script( 'sync-script' );
			
			wp_register_style( 'sync-style', plugins_url('css/sync-style.css', __FILE__) );
			wp_enqueue_style( 'sync-style' );

        	// Register the setting tab
			register_setting(
	            'custom_admin_bar_url_group', // Option group
	            'custom_admin_bar_url_option', // Option name
	            array( $this, 'sanitize' ) // Sanitize
	        );

	        add_settings_section(
	            'custom_admin_bar_url_setting', // ID
	            '', // Title
	            array( $this, 'print_section_info' ), // Callback
	            'custom-admin-bar-url-admin' // Page
	        );

	        /*add_settings_field(
	            'url_type', // ID
	            'URL Type', // Title
	            array( $this, 'url_type_callback' ), // Callback
	            'custom-admin-bar-url-admin', // Page
	            'custom_admin_bar_url_setting' // Section
	        );*/

	        add_settings_field(
	            'custom_label', // ID
	            'Custom Label', // Title
	            array( $this, 'custom_label_callback' ), // Callback
	            'custom-admin-bar-url-admin', // Page
	            'custom_admin_bar_url_setting' // Section
	        );

	        add_settings_field(
	            'custom_url', // ID
	            'Custom URL', // Title
	            array( $this, 'custom_url_callback' ), // Callback
	            'custom-admin-bar-url-admin', // Page
	            'custom_admin_bar_url_setting' // Section
	        );

	        add_settings_field(
	            'target', // ID
	            'Open link on new page?', // Title
	            array( $this, 'target_callback' ), // Callback
	            'custom-admin-bar-url-admin', // Page
	            'custom_admin_bar_url_setting' // Section
	        );
		}


		/*
		 * Function Name: add_plugin_page
		 * Function Description: Add a setting page in WP Setting
		 */
		public function add_plugin_page() {

			add_menu_page (
				__("Custom URL","smile"),
				__("Custom URL","smile"),
				"administrator",
				'custom-admin-bar-url',
				array( $this, 'create_admin_page' )
			);
	    }


	    /*
		 * Function Name: create_admin_page
		 * Function Description: callback function to callback admin setting page
		 */
	    public function create_admin_page() {
	        ?>
	        <div class="wrap about-wrap">
	            <div class="heading-section">
					<h1><?php echo __( 'Custom Admin Bar URL', 'smile' ); ?></h1>
					<div class="about-text about-text"><?php echo __( 'Lorem Ipsum', 'smile' ); ?></div>
					<div class="badge"></div>
					<div class="tabs">
						<form method="post" action="options.php" autocomplete="off">
						<?php
							settings_fields( 'custom_admin_bar_url_group' ); 
							do_settings_sections( 'custom-admin-bar-url-admin' );
							submit_button();
						?>
						</form>
					</div>
	        	</div>
	        </div>
	        <?php
	    }


	    /*
     	 * Sanitize each setting field as needed
	     *
	     * @param array $input Contains all settings fields as array keys
	     */
	    public function sanitize( $input ) {

	        $new_input = array();
	        if( isset( $input['custom_label'] ) )
	            $new_input['custom_label'] = $input['custom_label'];

			if( isset( $input['custom_url'] ) )
	            $new_input['custom_url'] = $input['custom_url'];

	        return $new_input;
	    }


	    /*
		 * Function Name: print_section_info
		 * Function Description: Prints information about the section
		 */
	    public function print_section_info() {
	        //Nothing to do here
	    }

	    /*
	     * Callback function for Header Script input
	     */
	    public function custom_label_callback() {
	    	$script = ( isset( $this->custom_admin_bar_url_option['custom_label'] ) ) ? $this->custom_admin_bar_url_option['custom_label'] : '';
	        printf(
	        	'<input type="text" id="custom_label" name="custom_admin_bar_url_option[custom_label]" value="%s" placeholder="Some Title" />', $script
	        );
	    }

	    /*
	     * Callback function for Footer Script input
	     */
	    public function custom_url_callback() {
	    	$script = ( isset( $this->custom_admin_bar_url_option['custom_url'] ) ) ? $this->custom_admin_bar_url_option['custom_url'] : '';
	        printf(
				'<input type="text" id="custom_url" name="custom_admin_bar_url_option[custom_url]" value="%s" placeholder="https://google.com" />', $script
	        );
	    }


	    public function url_type_callback() {
	    	$selected = ( isset( $this->custom_admin_bar_url_option['url_type'] ) ) ? $this->custom_admin_bar_url_option['url_type'] : '';
	    	printf(
				'<select id="url_type" name="custom_admin_bar_url_option[url_type]" >
					<option value="url">URL</option>
					<option value="post">Post</option>
				</select>'
	        );
	    }

	    /*
	     * Callback function to open link on new/same page
	     */
	    public function target_callback(){
	    	$val = '';
			if( isset( $this->custom_admin_bar_url_option['target'] ) ){
				$val = ( $this->custom_admin_bar_url_option['target'] == 'on' ) ? 'checked="checked"' : '';
			} else {
				$val = '';
			}
			if( $val == '' ) {
				$class = ' check-no';
				$yesno = 'No';
			} else {
				$class = ' check-yes';
				$yesno = 'Yes';
			}
	        printf(
	        	'<input id="target" class="toggle toggle-round" type="checkbox" name="custom_admin_bar_url_option[target]" %s>
	        	<label for="target"><p id="target-p" class="check-yesno %s">%s</p></label>
	        	', $val, $class, $yesno
	        );
	    }
	}

	new Custom_Admin_Bar_Url_Class;
}

?>