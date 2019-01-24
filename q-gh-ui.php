<?php

/**
 * Global UI 
 *
 * @package   q_ui
 * @author    Q Studio <social@qstudio.us>
 * @license   GPL-2.0+
 * @link      http://qstudio.us/
 * @copyright 2016 Q Studio
 *
 * @wordpress-plugin
 * Plugin Name:     Global UI
 * Plugin URI:      http://qstudio.us/
 * Description:     Shared libraries, controllers and UI features
 * Version:         0.1.7
 * Author:          Q Studio
 * Author URI:      http://qstudio.us
 * License:         GPL2
 * Class:           q_ui
 * Text Domain:     q-ui
 * Domain Path:     languages/
 * GitHub Plugin URI: qstudio/q-gh-ui
 */

defined( 'ABSPATH' ) OR exit;

if ( ! class_exists( 'q_ui' ) ) {

    // instatiate plugin via WP plugins_loaded - loaded at priority 5 same as q_theme ##
    add_action( 'plugins_loaded', array ( 'q_ui', 'get_instance' ), 5 );

    // define constants ##

    class q_ui {

        // Refers to a single instance of this class. ##
        private static $instance = null;

        // Plugin Settings
        const version = '0.1.7';
        const text_domain = 'q-ui'; // for translation ##
        static $debug = false; // controls how js is loaded ##
        static $device; // current device ##
        static $locale; // current locale ##

        // Template Settings
        public static
            $allow_comments = true,
            $allow_gallery = true,
            $allow_sidebar = false,
            $force_post = false, // this allows for a forced post ID ( used in get_header_* methods ) ##
            $set_force_post = false, // settings for forcing the post ##
            $get_text = array(),
            $post_parent = null, // allows for forcing a parent post
            $the_holder = array(),
            $the_posts = array(),
            $the_loop = array(),
            $the_title = array(),
            $the_parent = array(),
            $the_excerpt = array(),
            $the_content = array(),
            $the_avatar = array(),
            $get_post_by_meta = array(),
            $the_meta = array(),
            $the_meta_markup = array(),
            $the_post_meta = array(),
            $the_post_single = array(),
            $the_gallery = array(),
            $the_gallery_or_image = array(),
            $the_post_thumbnail = array(),
            $get_events = array(),
            $the_navigation = array(),
            $the_nav_menu = array(),
            $the_landing = array(),
			$the_search = array(),
            $the_sidebar = array(),
            $ordered_posts = array(),
            $ordered_posts_first = null, // allows for settings the first post to load ##
            $the_widget_events = array(),
            $page_contact,
            $text = array(),
            $the_related_programs = array(),
            $the_related_posts = array(),
            $the_page = array(),
            $the_render = array(),
            $the_header_page = array(),
            $google_tag_manager = false,
            $fb_pixel = false
        ;

        /**
         * Creates or returns an instance of this class.
         *
         * @return  Foo     A single instance of this class.
         */
        public static function get_instance()
        {

            if ( null == self::$instance ) {
                self::$instance = new self;
            }

            return self::$instance;

        }


        /**
         * Instatiate Class
         * 
         * @since       0.2
         * @return      void
         */
        private function __construct() 
        {
            
            // activation ##
            register_activation_hook( __FILE__, array ( $this, 'register_activation_hook' ) );

            // deactvation ##
            register_deactivation_hook( __FILE__, array ( $this, 'register_deactivation_hook' ) );

            // set text domain ##
            add_action( 'init', array( $this, 'load_plugin_textdomain' ), 1 );
            
            // load libraries ##
            self::load_libraries();

        }


        // the form for sites have to be 1-column-layout
        public function register_activation_hook() {

            #add_option( 'q_club_configured' );

        }


        public function register_deactivation_hook() {

            #delete_option( 'q_club_configured' );

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
            $domain = self::text_domain;
            
            // The "plugin_locale" filter is also used in load_plugin_textdomain()
            $locale = apply_filters('plugin_locale', get_locale(), $domain );

            // try from global WP location first ##
            load_textdomain( $domain, WP_LANG_DIR.'/plugins/'.$domain.'-'.$locale.'.mo' );
            
            // try from plugin last ##
            load_plugin_textdomain( $domain, FALSE, plugin_dir_path( __FILE__ ).'library/languages/' );
            
        }
        
        
        
        /**
         * Get Plugin URL
         * 
         * @since       0.1
         * @param       string      $path   Path to plugin directory
         * @return      string      Absoulte URL to plugin directory
         */
        public static function get_plugin_url( $path = '' ) 
        {

            #return plugins_url( ltrim( $path, '/' ), __FILE__ );
            return plugins_url( $path, __FILE__ );

        }
        
        
        /**
         * Get Plugin Path
         * 
         * @since       0.1
         * @param       string      $path   Path to plugin directory
         * @return      string      Absoulte URL to plugin directory
         */
        public static function get_plugin_path( $path = '' ) 
        {

            return plugin_dir_path( __FILE__ ).$path;

        }



        /**
         * Check for required classes to build UI features
         * 
         * @return      Boolean 
         * @since       0.1.0
         */
        public static function has_dependencies()
        {

            // check for what's needed ##
            if (
                ! class_exists( 'Q' )
                || ! class_exists( 'Q_Control' )
                // || ! class_exists( 'q_theme' ) // @todo, is this class is required ? ##
            ) {

                helper::log( 'Required dependencies missing, so bulking...' );

                return false;

            }

            // ok ##
            return true;

        }
        

        /**
        * Load Libraries
        *
        * @since        2.0
        */
		private static function load_libraries()
        {

            // check for dependencies ##
            if ( ! self::has_dependencies() ) {

                return false;

            }

            // methods ##
            require_once self::get_plugin_path( 'library/core/helper.php' );
            require_once self::get_plugin_path( 'library/core/config.php' );
            require_once self::get_plugin_path( 'library/core/core.php' );
            require_once self::get_plugin_path( 'library/core/options.php' );

            // backend ##
            require_once self::get_plugin_path( 'library/admin/admin.php' );
            // require_once self::get_plugin_path( 'library/admin/menu.php' ); // @todo - this should be filtered via an API into main options page on Q

            // plugins ##
            // require_once self::get_plugin_path( 'library/plugin/plugin.php' );

            // frontend ##
            // require_once self::get_plugin_path( 'library/theme/widget.php' );
            // require_once self::get_plugin_path( 'library/theme/meta.php' );
            // require_once self::get_plugin_path( 'library/theme/template.php' );
            require_once self::get_plugin_path( 'library/theme/theme.php' );

        }

    }

}