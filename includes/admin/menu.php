<?php

namespace q\ui\admin;

use q\ui\core\plugin as plugin;

// load it up ##
\q\ui\admin\menu::run();

class menu {

    public static function run()
    {

        \add_action( 'admin_menu', [ get_class(), 'admin_menu' ] );

    }

    public static function admin_menu()
    {

        \add_options_page( 'Global UI', 'Global UI', 'manage_options', plugin::$slug, function() {

            // these settings should be filtered into the global Q settings ##
            // @todo - Ray to build API from Q to allow new menu items to be added to settings page and saved to db

            // validate
            if ( 
                $_POST 
                && isset($_POST['action']) 
                && plugin::$slug === $_POST['action'] ) {
                
                // sanitize ##
                $settings['active'] = intval( $_POST['settings']['active'] );

                // save ##
                if ( \update_option( plugin::$slug, $settings ) ) {
                    
                    print '<div class="updated"><p><strong>Settings saved.</strong></p></div>';

                }
            }

            // get setting from db ##
            $settings = \get_option( plugin::$slug );

?>
            <h1>Global UI</h1>

            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th>
                            Include Global UI
                        </th>
                        <td>
                            Off
                            <input type="radio" name="settings[active]" value="0" checked />
                            On
                            <input type="radio" name="settings[active]" value="1" <?php \checked( $settings['active'], 1 ); ?> />
                        </td>
                    </tr>
                </table>

                <input name="nonce" type="hidden" value="<?php echo \esc_attr( \wp_create_nonce( plugin::$slug ) ); ?>" />
                <input name="action" type="hidden" value="<?php echo \esc_attr( plugin::$slug ); ?>" />
                <input type="submit" class="button-primary" value="Save" />
            </form>
<?php

        });
    }
}