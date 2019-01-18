<?php

// namespace ##
namespace q\ui\theme;

use q\ui\core\plugin as plugin;
use q\ui\core\helper as helper;
// use q\ui\core\api as api;
// use q\ui\core\geotarget as geotarget;
// use q\ui\core\cookie as cookie;

/**
 * Template level UI changes
 *
 * @package   q_gh_ui
 */
// load it up ##
\q\ui\theme\template::run();

class template extends plugin {

    /**
     * Instatiate Class
     *
     * @since       0.2
     * @return      void
     */
    public static function run()
    {

        // styles and scripts ##
        \add_action( 'wp_enqueue_scripts', [ get_class(), 'wp_enqueue_scripts' ], 99 );

    }


    /**
     * WP Enqueue Scripts - on the front-end of the site
     *
     * @since       0.1
     * @return      void
     */
    public static function wp_enqueue_scripts()
    {

        // Register the script ##
        \wp_register_script( 'q-gh-ui-js', Q_CONSENT_URL.'javascript/q-gh-ui.js', array( 'jquery' ), plugin::$version, true );

        // Now we can localize the script with our data.
        $translation_array = array(
                'ajax_nonce'    => \wp_create_nonce( 'q_gh_ui' )
            ,   'ajax_url'      => \get_home_url( '', 'wp-admin/admin-ajax.php' )
            ,   'saved'         => __( "Saved!", 'q-consent' )
            ,   'disabled'      => __( "Functional Cookies cannot be disabled", 'q-gh-ui' )
        );

        \wp_localize_script( 'q-gh-ui-js', 'q_gh_ui', $translation_array );

        // enqueue the script ##
        \wp_enqueue_script( 'q-gh-ui-js' );

        wp_register_style( 'q-gh-ui-css', Q_CONSENT_URL.'scss/index.css', '', plugin::$version );
        wp_enqueue_style( 'q-gh-ui-css' );
    }





}