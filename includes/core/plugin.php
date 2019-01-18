<?php

namespace q\ui\core;

use q\ui\core\helper as helper;
use q\ui\theme\template as template;

/**
 * Class Plugin
 * @package q\ui\plugin
 */

class plugin {

	// Settings ##
    static $version = '0.1.0';
    // static $device; // current device handle ( 'tablet || desktop || handheld' ) ##
    protected static $debug = true;
    static $slug = 'q-ui';

    /**
     * Instatiate Class
     *
     * @since       0.2
     * @return      void
     */
    public function __construct()
    {

    }


    /**
     * Hook into WP actions and filters
     *
     * @access public
     * @since 0.5
     * @return void
     */
    public function run_hooks()
    {

        // set text domain ##
        \add_action( 'init', array( $this, 'load_plugin_textdomain' ), 1 );

    }


	/**
	 * Fired when the plugin is activated.
	 */
	public function activate() {

	}


	/**
	 * Fired when the plugin is deactivated.
	 */
	public function deactivate() {

	}


	/**
     * Load Text Domain for translations
     *
     * @since       1.7.0
     *
     */
    public function load_plugin_textdomain()
    {

        // set text-domain ##
        $domain = 'q-consent';

        // The "plugin_locale" filter is also used in load_plugin_textdomain()
        $locale = \apply_filters( 'plugin_locale', \get_locale(), $domain );

        // try from global WP location first ##
        \load_textdomain( $domain, WP_LANG_DIR.'/plugins/'.$domain.'-'.$locale.'.mo' );

        // try from plugin last ##
        \load_plugin_textdomain( $domain, FALSE, \plugin_dir_path( __FILE__ ).'languages/' );

    }

}