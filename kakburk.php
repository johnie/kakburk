<?php
/*
* Plugin Name: Kakburk
* Plugin URI: https://github.com/johnie/kakburk
* Description:
* Version: 1.0.0
* Author: Johnie Hjelm
* Author URI: http://johnie.se
* License: MIT
*/

/*
Copyright 2015 Johnie Hjelm <johniehjelm@me.com> (http://johnie.se)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Kakburk' ) ) {

  class Kakburk {

    private static $instance;

    /**
     * Tag identifier used by file includes and selector attributes.
     * @var string
     */

    public $tag;

    /**
     * User friendly name used to identify the plugin.
     * @var string
     */

    public $name;

    /**
     * Description of the plugin.
     * @var string
     */

    public $description;

    /**
     * Current version of the plugin.
     * @var string
     */

    public $version;

    /**
     * Plugin loader instance.
     *
     * @since 1.0.0
     *
     * @return object
     */

    public static function instance() {
      if ( ! isset( self::$instance ) ) {
        self::$instance = new static;
        self::$instance->setup_globals();
        self::$instance->setup_actions();
      }

      return self::$instance;
    }

    /**
     * Initiate the plugin by setting the default values and assigning any
     * required actions and filters.
     *
     * @access private
     */

    private function setup_actions() {

    	add_action( "admin_menu", array( $this, "kakburk_menu" ) );
  		add_action( "admin_init", array( $this, "display_kakburk_options" ) );

			add_action( "wp_enqueue_scripts", array( $this, "kakburk_enqueue_scripts" ), 130 );

    }

    /**
 		 * Initiate the globals
 		 *
     * @access private
 		 */

    private function setup_globals() {
      $this->tag = 'kakburk';
      $this->name = 'Kakburk';
      $this->description = 'Simple WordPress plugin for cookie law bars';
      $this->version = '1.0.0';
    }


    /**
     * Add kakburk menu
     */

		function kakburk_menu() {
    	add_submenu_page( 'options-general.php', $this->name, $this->name, "manage_options", $this->tag, array( $this, "kakburk_page" ) );
    }


    /**
     * Kakburk options page
     */

    function kakburk_page() {
		  ?>
	    <div class="wrap">
	      <h2><?php echo $this->name; ?></h2>
	      <form method="post" action="options.php">
					<?php
						settings_fields( "kakburk_header" );
						do_settings_sections( $this->tag );
						submit_button();
					?>
			  </form>
		  </div>
		  <?php
		}


		/**
		 * Captcha Login options
		 */

		function display_kakburk_options() {
	    add_settings_section( "kakburk_header", "Details", array( $this, "display_kakburk_content" ), $this->tag );

	    add_settings_field( "kakburk_description", __("Description"), array( $this, "display_kakburk_description_element" ), $this->tag, "kakburk_header" );
	    add_settings_field( "kakburk_readmore_text", __("Read more text"), array( $this, "display_kakburk_readmore_text_element" ), $this->tag, "kakburk_header" );
	    add_settings_field( "kakburk_readmore_link", __("Read more link"), array( $this, "display_kakburk_readmore_link_element" ), $this->tag, "kakburk_header" );
	    add_settings_field( "kakburk_button", __("Close button text"), array( $this, "display_kakburk_button_element" ), $this->tag, "kakburk_header" );

	    register_setting( "kakburk_header", "kakburk_description" );
	    register_setting( "kakburk_header", "kakburk_readmore_text" );
	    register_setting( "kakburk_header", "kakburk_readmore_link" );
	    register_setting( "kakburk_header", "kakburk_button" );
		}

		/**
		 * Kakburk content
		 */

		function display_kakburk_content() {
	    echo __( "Enter the details about the cookie law bar below" );
		}


		/**
		 * Kakburk description element
		 */

		function display_kakburk_description_element() {
    	?>
      	<input type="text" name="kakburk_description" id="kakburk_description" value="<?php echo get_option('kakburk_description'); ?>" class="regular-text" />
    	<?php
		}


		/**
		 * Kakburk read more text element
		 */

		function display_kakburk_readmore_text_element() {
    	?>
      	<input type="text" name="kakburk_readmore_text" id="kakburk_readmore_text" value="<?php echo get_option('kakburk_readmore_text'); ?>" class="regular-text" />
    	<?php
		}


		/**
		 * Kakburk read more link element
		 */

		function display_kakburk_readmore_link_element() {
    	?>
      	<input type="text" name="kakburk_readmore_link" id="kakburk_readmore_link" value="<?php echo get_option('kakburk_readmore_link'); ?>" class="regular-text" />
    	<?php
		}


		/**
		 * Kakburk close button element
		 */

		function display_kakburk_button_element() {
	    ?>
				<input type="text" name="kakburk_button" id="kakburk_button" value="<?php echo get_option('kakburk_button'); ?>" class="regular-text" />
	    <?php
		}


    /**
 		 * Register scripts
 		 */

    function kakburk_enqueue_scripts() {

    	wp_enqueue_script( 'kakburk', home_url() . '/lib/kakburk/js/kakburk.js', array('jquery', 'jquery-cookie'), null, true );

    	$handleName = preg_replace('#[ -]+#', '-', get_option( 'blogname' ) );

    	wp_localize_script( 'kakburk', 'kakburken', array(
    		'handle' 				=> strtolower($this->tag . '--' .$handleName),
    		'description' 	=> get_option( 'kakburk_description' ),
    		'readmore_text' => get_option( 'kakburk_readmore_text' ),
    		'readmore_link'	=> get_option( 'kakburk_readmore_link' ),
    		'button'			 	=> get_option( 'kakburk_button' ),
    	) );

    }

  }

}

if ( !function_exists( 'kakburk' ) ) {
  function kakburk() {
    return Kakburk::instance();
  }
}

add_action( 'plugins_loaded', 'kakburk' );
