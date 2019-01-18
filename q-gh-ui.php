<?php

/**
 * Build a shared consent, privacy and cookie system
 * https://docs.google.com/document/d/1GJ4BEg0jEbc4BAsj1spYAV0_GGU_41as_vQyv4RtIxw/edit?ts=5bd218cf
 *
 * @package   q_consent
 * @author    Q Studio <social@qstudio.us>
 * @license   GPL-2.0+
 * @link      http://qstudio.us/
 * @copyright 2016 Q Studio
 *
 * @wordpress-plugin
 * Plugin Name:     Consent
 * Plugin URI:      http://qstudio.us/
 * Description:     Build a shared consent, privacy and cookie system
 * Version:         0.5.7
 * Author:          Q Studio
 * Author URI:      http://qstudio.us
 * License:         GPL2
 * Class:           q_consent
 * Text Domain:     q-consent
 * Domain Path:     languages/
 * GitHub Plugin URI: qstudio/q-gh-consent
 */

namespace q\ui; 

use q\ui\core\plugin as plugin;

// stop direct access ##
defined( 'ABSPATH' ) OR exit;

// Define our constants ##
( ! defined( 'Q_UI_PATH' ) ) && define( 'Q_UI_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
( ! defined( 'Q_UI_URL' ) ) && define( 'Q_UI_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Load Dependencies
 *
 * @since       0.5
 */
$autoload = array(
        'core/plugin'
    ,   'core/helper'
    ,   'theme/template'
    ,   'admin/menu' // add admin UI ##
);
foreach ( $autoload as $load ) {
    if ( file_exists( Q_UI_PATH.'includes/'.$load.'.php' ) ) require_once( Q_UI_PATH.'includes/'.$load.'.php' );
}

/**
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, __NAMESPACE__.'\\activation' );
function activation() {

    $instance = new plugin();
    $instance->activate();

}

register_deactivation_hook( __FILE__, __NAMESPACE__.'\\deactivation' );
function deactivation() {

    $instance = new plugin();
    $instance->deactivate();

}


/**
 * Instatiate class and run hooks
 */
add_action( 'plugins_loaded', __NAMESPACE__.'\\hook', 5 );

function hook() {

    // new class instance ##
    $instance = new plugin();

    // load theme hooks ##
    $instance->run_hooks();

}