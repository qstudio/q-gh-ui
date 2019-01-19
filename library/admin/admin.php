<?php

namespace q\ui\admin;

use q\ui\core\core as core;
use q\ui\core\helper as helper;

// load it up ##
\q\ui\admin\admin::run();

class admin extends \q_ui {

    public static function run()
    {

        if ( \is_admin() ) {

            // remove admin search bar -- @todo move to Q ##
            add_action( 'admin_bar_menu', array( get_class(), 'remove_admin_bar_search' ), 999 );     

            // post type filters -- @todo move to Q  ##
            \add_action( 'restrict_manage_posts', array( get_class(), 'restrict_manage_posts' ));

            // filter admin preview link -- @todo move to Q  ##
            add_filter( 'preview_post_link', [ get_class(), 'preview_post_link' ], 10, 2 );

        }

    }



    /**
     * Fix for broken preview link in admin - link to default url ?p=ID
     * 
     * @since       0.1.0
     */
    public static function preview_post_link( $preview_link, $post ) 
    {

        if ( 
            \get_post_status ( $post->ID ) != 'draft' 
            && \get_post_status ( $post->ID ) != 'auto-draft' 
        ) {
      
            // preview URL for all published posts ##
            return \home_url()."?p=".$post->ID; 
      
         } else {
            
            // preview URL for all posts which are in draft ##
            return \home_url()."?p=".$post->ID; 

        }

    }



    /**
     * CSS hacks - seem unused
     * 
     * @todo        Review
     */
    public static function css(){

?>
<style>
    .error.aesop-notice{
        display: none;
    }
</style>
<?php

    }




    /**
    * Remove admin bar search, as gives SSl Error
    *
    * @return      void
    */
    public static function remove_admin_bar_search( $wp_admin_bar )
    {

        $wp_admin_bar->remove_node( 'search' );

    }




    /**
    * Remove unrequired menu items
    *
    * @since    2.0.0
    * @return   _false
    */
    public static function remove_menus()
    {

        \remove_menu_page( 'edit.php?post_type=ai_galleries' );       

    }


    
    /**
     * Add Taxonomy Filters to Admin views
     *
     * @since       1.0.1
     * @return      String      HTML select
     */
    public static function restrict_manage_posts()
    {

        // sanity ## 
        if ( ! function_exists( 'q_restrict_manage_posts' ) ) {

            return false;

        }

        \q_restrict_manage_posts( array (
            // 'resource'  => array ( 'resource_category' ),
            'post'      => array ( 'post_tag' ),
        ) );

    }


}