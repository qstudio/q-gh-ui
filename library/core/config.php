<?php

namespace q\ui\core;

use q\ui\core\core as core;
use q\ui\core\helper as helper;

// load it up ##
\q\ui\core\config::run();

class config extends \q_ui {

    public static function run()
    {

        // filter intermediate image sizes ##
        \add_filter( 'intermediate_image_sizes_advanced', array ( get_class(), 'intermediate_image_sizes_advanced' ) );

        // add shared theme widgets ##
        // \add_action( 'widgets_init', array( get_class(), 'widgets_init' ) );

        // set default email from ##
        \add_filter( 'wp_mail_from', array( get_class(), 'wp_mail_from' ) );

        // add generic theme support ##
        \add_action( 'init', array( get_class(), 'add_support' ) );

        // filter meta title ##
        \add_filter( 'wp_title', array ( get_class(), 'wp_title' ), 10, 2 );
        
        // add_image_sizes for all themes ##
        \add_action( 'init', array( get_class(), 'add_image_sizes' ) );

        if ( \is_admin() ) {

            // manage user roles ##
            \add_action( 'admin_init', array( get_class(), "user_roles" ) );

            // Add Filter Hook
            \add_filter( 'post_mime_types', array( get_class(), 'post_mime_types' ) );

        } else {

            // define Google Tag Manager ## -- @todo, make global ##
            #self::google_tag_manager();

            // define FB Pixel ## -- @todo, make global ##
            #self::fb_pixel();

            // load template properties ##
            \add_action( 'wp', array( get_class(), "load_properties" ) );

            // remove "url" field from comments ##
            \add_filter( 'comment_form_default_fields', array( get_class(), 'comment_form_default_fields' ) );

        }

        // make sure properties are loaded when AJAX requests run ##
        if ( \wp_doing_ajax() ) {

            self::load_properties();

        }

        // remove admin color schemes - silly idea ##
        \remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

        // sharelines widget ##
        // \add_filter( 'q/widget/sharelines/facebook', function() { return '137150683665520'; } ); // APP ID ##

    }



    /*
    public static function google_tag_manager()
    {

        self::$google_tag_manager = '
            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-44211158-1"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag(\'js\', new Date());

                gtag(\'config\', \'UA-44211158-1\');
            </script>
        ';

    }
    */


    /*
    public static function fb_pixel()
    {

        self::$fb_pixel = '
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version=\'2.0\';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,\'script\',
            \'https://connect.facebook.net/en_US/fbevents.js\');
            fbq(\'init\', \'511950592603984\'); 
            fbq(\'track\', \'PageView\');
        </script>
        <noscript>
            <img height="1" width="1" 
            src="https://www.facebook.com/tr?id=511950592603984&ev=PageView
            &noscript=1"/>
        </noscript>
        ';

    }
    */

    

    /**
     * Adds Support for shared Q features.
     *
     * @since       0.1
     * @return      void
     */
    public static function add_support()
    {

        // add thumbnails ##
        \add_theme_support( 'post-thumbnails' );

        // default Post Thumbnail dimensions
        \set_post_thumbnail_size( 194, 97 );

        // enable support for Q Feature ##
        \q_add_theme_support( array (
            // 'twitter', // twitter ##
            'bootstrap', // bootstrap ##
            'featherlight', // featherlight ##
            // 'sly', // sly horizontal scroll ##
            'lazy', // lazy loader - http://jquery.eisbehr.de/lazy/ ##
            #'hovereffects', // for hovereffects ##
            'snackbar'  // https://github.com/FezVrasta/snackbarjs
        ));

        // remove revisions from posts ##
        #remove_post_type_support( 'post', 'revisions' );

        // add revisions to certain CPT's ##
        // \add_post_type_support( 'page' );

        // remove revisions from posts ##
        \remove_post_type_support( 'post', 'revisions' );

    }



    
    /**
     * Add filters to WP Media Library
     *
     * @since       1.4.2
     * @return      Array
     */
    public static function post_mime_types( $post_mime_types )
    {

        // select the mime type, here: 'application/pdf'
        // then we define an array with the label values
        $post_mime_types['application/pdf'] = array(
            __( 'PDF' ),
            __( 'Show PDF' ),
            _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' )
        );

        // then we return the $post_mime_types variable
        return $post_mime_types;

    }



    /**
     * Filters the page title appropriately depending on the current page
     * This function is attached to the 'wp_title' filter hook.
     *
     * @uses	get_bloginfo()
     * @uses	is_home()
     * @uses	is_front_page()
     * 
     * @since       0.1
     */
    public static function wp_title( $title, $sep ) {

        global $page, $paged, $post;
        
        $page_title = $title;

        // helper::log( $page_title );
            
        // get site desription ##
        $site_description = \get_bloginfo( 'description' );
        
        if ( $post ) { 

            // allow for custom title - via post meta "metatitle" ##
            $page_title = \get_post_meta( $post->ID, "metatitle", true ) ? \get_post_meta( $post->ID, "metatitle", true ).' '.$sep. ' ' : $title;
            
            // if this is a singular post - but not of type page or post add post type name as parent ##
            if ( 
                \is_singular( \get_post_type() ) 
                && \get_post_type() !== 'post' 
                && \get_post_type() !== 'page' 
            ) {
                
                if ( $obj = \get_post_type_object( \get_post_type() ) ) {
                
                    $page_title = $page_title.' '.$obj->labels->menu_name.' '.$sep.' ';

                }
                
            }
            
            // add parent page, if page ##
            if ( 
                $post->post_parent && 
                $post->post_type === 'page' 
                && ! \is_search() 
            ) {

                if ( $get_post_ancestor = \get_post_ancestors( $post->ID ) ) {

                    $page_title = $page_title.' '.\get_the_title( array_pop( $get_post_ancestor ) ).' '.$sep.' ';

                }

            }
            
        }
        
        // if we're on a single category check if that page has a parent ##
        if ( \is_archive() ) {

            $term = \get_term_by( 'slug', \get_query_var( 'term' ), \get_query_var( 'taxonomy' ) );

            if ( 
                $term 
                // && $term->parent > 0 
            ) {

                // helper::log( 'Archive title' );

                // just use the term name ##
                $page_title = $term->name.' '.$sep.' ';

                // // get parent name ##
                // $term_parent = \get_term_by( 'ID', $term->parent, \get_query_var( 'taxonomy' ) ) ;

                // if ( $term_parent && $term_parent->name ) {

                //     $page_title .= $term_parent->name.' '.$sep.' ';

                // }

            }

        }
        
        // compile ##
        $page_title = $page_title . \get_option( 'blogname' ); // with site name ##
        #$filtered_title = $page_title; // without site name ##
        
        // add site description if not empty and on front page ##
        $page_title .= ( ! empty( $site_description ) && ( \is_front_page() ) ) ? ' | ' . $site_description : '' ;
        
        // add paging number, if paged ##
        $page_title .= ( 2 <= $paged || 2 <= $page ) ? ' | ' . sprintf( __( 'Page %s' ), max( $paged, $page ) ) : '' ;

        // helper::log( $page_title );

        // return title ##
        return $page_title;

    }




    /**
     * Remove standard image sizes so that these sizes are not
     * created during the Media Upload process
     *
     * Tested with WP 3.2.1
     *
     * Hooked to intermediate_image_sizes_advanced filter
     * See wp_generate_attachment_metadata( $attachment_id, $file ) in wp-admin/includes/image.php
     *
     * @param $sizes, array of default and added image sizes
     * @return $sizes, modified array of image sizes
     * @author http://www.wpmayor.com/code/remove-image-sizes-in-wordpress/
     */
    public static function intermediate_image_sizes_advanced( $sizes)
    {

        unset( $sizes['slides']);
        unset( $sizes['slides-small']);
        unset( $sizes['home']);
        unset( $sizes['new-photos']);
        unset( $sizes['new-photos']);
        unset( $sizes['hero']);

        return $sizes;

    }



    /**
     * Add image sizes for all devices - so that all device images sizes are prepared when files are uploaded
     * Note: Tablet uses desktop sized images
     *
     * @since       0.1
     * @return      void
     */
    public static function add_image_sizes()
    {

        // generic ##
        \add_image_size( 'icon', 80, 80, false ); // icon ##
        \add_image_size( 'thumb', 270, 9999, false ); // small thumb ##

        // generic ##
        \add_image_size( 'thumb', 194, 97, true ); // small thumb ##

    }



    public static function list_image_sizes()
    {

        global $_wp_additional_image_sizes; 
        if( self::$debug ) helper::log( $_wp_additional_image_sizes ); 

    }




    /**
     * Shared Sidebars & Widgetized areas
     *
     * @since       0.1
     * @return      void
     */
    public static function widgets_init()
    {

        // blog search ##
        register_sidebar( array(
            'name' => __( 'Blog Search', 'q_child' ),
            'id' => 'blog-search',
            'description' => __( 'Blog Search.', 'q_child' ),
        ));

    }






    /**
     * Manage User Roles
     *
     * @since       1.4.2
     * @return      void
     */
    public static function user_roles()
    {

        // $user = \wp_get_current_user();
        // \grant_super_admin( $user->ID ); 

        // get the the "editor" role object ##
        $role_editor = \get_role( 'editor' );

        // add $cap capability to this role object ##
        $role_editor->add_cap( 'edit_theme_options' ); // edit menus ##
        #$role_editor->add_cap( 'gform_full_access' ); // full access to gravity forms ##

        // give access to gravity forms ##
        $role_editor->add_cap( 'gform_full_access' );

    }



    /**
     * Load settings for use in templates
     *
     * @since       1.0.3
     * @return      string
     */
    public static function load_properties(){

        // holder images ##
        self::$the_holder = array (
            'the_posts'             => helper::get( "theme/css/images/holder/desktop_the_posts.svg", 'return' ),
            'the_avatar'            => helper::get( "theme/css/images/holder/desktop_the_avatar.svg", 'return' ),
            'template_header'       => helper::get_device() == 'desktop' ?
                                        helper::get( "theme/css/images/holder/desktop_header.svg", 'return' ) :
                                        helper::get( "theme/css/images/holder/handheld_header.svg", 'return' )
        );

        // get_posts() ##
        self::$the_posts = array(
            'posts_per_page'        => \get_option( "posts_per_page", 10 ),// per page ##
            'limit'                 => \get_option( "posts_per_page", 10 ), // posts to load ##
            'template'              => 'post', // used to make get_$template_loop method call ##
            'sidebar'               => self::$allow_sidebar, // show sidebar ##
            'pagination'            => true, // next / back links ##
            'class'                 => '', // class to add to wrapping tag
            'tag'                   => 'ul', // tag to wrap posts in ##
            'query_vars'            => false,
            'search'				=> false,
            'holder'                => array ( 
                                        'desktop'   => helper::get( "theme/css/images/holder/desktop-list-post.svg", 'return' ),
                                        'handheld'  => helper::get( "theme/css/images/holder/handheld-list-post.svg", 'return' )
                                    ),
            'handle'                => array( 
                                        'desktop'   => 'desktop-list-post',
                                        'handheld'  => 'handheld-list-post'
                                    ),
            'length'                => '200', // return limit for excerpt ##
            'date_format'           => 'F j, Y'
        );

        // search ---------
        self::$the_search = array(
            'post_type'             => 'page',
            'search'                => true,
            'query_vars'            => true,
            #'add_parent'            => false,
            'link_parents'          => false, // should we allow parents with children to be clickable ##
            'posts_per_page'        => 20,// per page ##
            'limit'					=> 20, // total results ##
            'sidebar'               => false, // show sidebar ##
            'allow_comments'        => self::$allow_comments, // allow comments ##
            'comments_system'       => 'disqus', // system to use for comments - add styling and links ##
            'pagination'            => true, // next / back links ##
            'thumbnail'             => false, // show featured image ##
            'class'                 => '', // class to add to wrapping tag
            'tag'                   => 'ul', // tag to wrap posts in ##
            'pagination'            => false
        );

        // the_gallery() ##
        self::$the_gallery = array(
            'gallery'               => self::$allow_gallery, // allow galleries ##
            'acf'                   => true, // use ACF gallery field type in admin ##
            'layout'                => 'inline', // style of gallery ( inline || full_width ) ##
            //'post_meta'             => false, // pull gallery code from a post meta fields, rather than extracting from the post_content ##
            'tag'                   => 'ul', // tag to wrap output  in ##
            'class'                 => 'gallery', // class to add to wrapping tag
            'tag_node'              => 'li', // tag to wrap each gallery item in ##
            'class_node'            => 'item', // class to add to inidividual item tag
            'post_meta'             => 'sc_gallery', // if passed, gallery shortcode will be pulled from post_meta field instead of post_content ##
            'img_handle'            => 'gallery', // img handle used for returned images ##
            'img_open'              => false, // should clicking on the images open them large ##
            'img_handle_open'       => 'full', // img handle used for opened images ##
            'limit'                 => 8 // limit number of images in gallery ##
        );

        // gallery or image ##
        self::$the_gallery_or_image = array(
            #'field'                 => 'template_gallery',
            'gallery'               => self::$allow_gallery, // allow galleries ##
            'acf'                   => true, // use ACF gallery field type in admin ##
            'layout'                => 'inline', // style of gallery ( inline || full_width ) ##
            'tag'                   => 'ul', // tag to wrap output  in ##
            'class'                 => 'gallery', // class to add to wrapping tag
            'tag_node'              => 'li', // tag to wrap each gallery item in ##
            'class_node'            => 'item', // class to add to inidividual item tag
            'post_meta'             => 'template_gallery', // if passed, gallery shortcode will be pulled from post_meta field instead of post_content ##
            'img_handle'            => 'gallery', // img handle used for returned images ##
            'img_open'              => false, // should clicking on the images open them large ##
            'img_handle_open'       => 'full', // img handle used for opened images ##
            'limit'                 => 8
        );

        // get_post_thumbnail() ##
        self::$the_post_thumbnail = array(
            // 'handle'                => 'medium', // image handle to use for feature image ##
            'class'                 => 'aligncenter', // class for image ##
            'tag'                   => 'div', // tag to wrap output in ##
            'class'                 => 'lazy', // class to add to wrapping tag
            'layout'                => 'inline' // allow for full width single images ##
        );

        // the_post_single() ##
        self::$the_post_single = array(
            'sidebar'               => true, // show sidebar ##
            'allow_comments'        => self::$allow_comments, // allow comments ##
            'comments_system'       => 'disqus', // system to use for comments - add styling and links ##
            'pagination'            => ( helper::get_device() == 'desktop' ) ? false : true, // next / home / back links ##
            'gallery'               => self::$allow_gallery, // allow galleries ##
            'gallery_handle'        => 'gallery', // img handle used for returned images ##
            'gallery_open'          => false, // should clicking on the images open them large ##
            'gallery_handle_open'   => 'full', // img handle used for opened images ##
            'gallery_limit'         => 8, // limit number of images in gallery ##
            'thumbnail'             => true, // show featured image ##
            'gallery_or_image'      => true // gallery or image ##
        );

        // the_meta() ##
        self::$the_meta = array(
            'layout'                => 'single', // layout ( single, tabs )##
            'tabs'                  => 'top', // layout ( top, side )##
            'class'                 => 'q-meta',
            'tag'                   => 'div'
        );

        // the_render() ##
        self::$the_render = array(
            'class'                 => 'q-render'
        );

        // the_meta_markup() ##
        self::$the_meta_markup = array(
            'class'                 => 'meta', // parent item prefix ##
            'tag'                   => 'ul' // tag element to use ##
            #'id'                   => 'meta', // sub item prefix ##
        );

        // the_post_meta() ##
        self::$the_post_meta = array(
            'allow_comments'        => self::$allow_comments, // allow comments ##
        );

        // get_loop() ##
        self::$the_loop = array(
            'excerpt_length'        => 200, // excerpt length ##
            'title_length'          => 60, // title length ##
            'holder'                => self::$the_holder["the_posts"], // holder image - same as $the_posts
            'image_handle'          => 'search' // image size to use ##
        );

        // title ##
        self::$the_title = array(
            #'layout'                => 'inline', // allow for full width layout ##
            'tag'                   => 'h1', // default excerpt length ##
            'class'                 => 'the-title',
            'title'                 => true // include title in excerpt ##
        );

        // parent ##
        self::$the_parent = array(
            #'layout'                => 'inline', // allow for full width layout ##
            'tag'                   => 'h3', // tag ##
            'class'                 => 'the-parent',
            'link'                  => true, // link up parent ##
            'post_type'             => 'page'
        );

        // the_header_page ##
        self::$the_header_page = array(
            'tag'                   => 'div', // tag ##
            'class'                 => array( 'the-header-page', 'q-wrap', 'wrapper' ),
            'handle'                => array(
                                        'desktop'   => 'desktop-page-header',
                                        'handheld'  => 'handheld-page-header',
                                    ),
            'holder'                => array(
                                        'desktop'   => helper::get( "theme/css/images/holder/desktop-page-header.svg", 'return' ),
                                        'handheld'  => helper::get( "theme/css/images/holder/handheld-page-header.svg", 'return' ),
                                    ),
            'layout'                => 'full_width',
        );  

        // the_excerpt() ##
        self::$the_excerpt = array(
            'layout'                => 'inline', // allow for full width layout ##
            'limit'                 => 300, // default excerpt length ##
            'class'                 => array ( 'the-excerpt' ),
            'title'                 => false // include title in excerpt ##
        );

        // the_content() ##
        self::$the_content = array(
            #'layout'                => 'full_width', // allow for full width layout ##
            'tag'                   => 'div', // tag ##
            'class'                 => 'the-content',
            'cta_class'              => false, // nada ##
            'cta_method'             => false // nada ##
        );

        // the_avatar() ##
        self::$the_avatar = array(
            'style'                 => 'post', // single post OR post category ##
            'holder'                => self::$the_holder["the_avatar"], // holder image ##
            'class'                 => 'the-avatar' // default class ##
        );

        // get_post_by_meta() ##
        self::$get_post_by_meta = array (
            'meta_key'              => 'page_name',
            'post_type'             => 'page',
            'posts_per_page'        => 1,
            'order'					=> 'DESC',
            'orderby'				=> 'date'
        );

        // the_widget_events ##
        self::$get_events = array(
            #'limit'                 => 3 // limit number of events returned ##
        );

        // navigation ---------
        self::$the_navigation = array(
            'post_type'             => 'page',
            'add_parent'            => false,
            'posts_per_page'        => 7
        );

        // navigation ---------
        self::$the_nav_menu = array(
            // no wrapping ##
            'items_wrap'        => '%3$s',
            // do not fall back to first non-empty menu
            'theme_location'    => '__no_such_location',
            // do not fall back to wp_page_menu()
            'fallback_cb'       => false,
            'container'         => false,
        );

        // landing ---------
        self::$the_landing = array(
            'post_type'             => 'page',
            #'add_parent'            => false,
            'link_parents'          => false, // should we allow parents with children to be clickable ##
            'posts_per_page'        => -1,
            'class'                 => 'the-landing'
        );

        // sidebar ---------
        self::$the_sidebar = array(
            'class'                 => 'the-sidebar',
            'tag'                   => 'div' // tag element to use ##
        );


        // ordered_posts ---------
        self::$ordered_posts = array(
            'post_parent'           => false, // if true, grab posts with parent of current post ##
            'show'                  => 2, // number of extra items to show ( x before and x after current item ) ##
            'direction'             => 'next', // next or back ##
            'title'                 => true, // show titles below src_small ##
            'order_by'              => 'menu_order',
            'find'                  => 'first' // middle or first ##
        );


        // forcing the post ##
        self::$set_force_post = array (
            'post_parent'           => true
        );

        // the_related_posts ##
        self::$the_related_posts = array(
            'title_length'          => 60, // title length ##
            'holder'                => self::$the_holder["the_posts"], // holder image - same as $the_posts
            'handle'                => 'thumbnail' // image size to use -- @todo make device aware with self::get_device() - NOTE: THERE IS CURRENTLY ONLY ONE THUMBNAIL ##
        );

        // the_page ##
        self::$the_page = array(
            'holder'                => ( helper::get_device() == 'desktop' ) ? '1440x480' : '1440x480', // holder image ##
            'handle'                => helper::get_device().'-single' // image size to use ##
        );

        // texts ---------------------------------------------------------------------------------------------------------------
        self::$get_text = array (
            'post'                  => null,
            'tag'                   => 'span',
            'class'                 => 'cta'
        );

        // calls to action ----------
        #self::$text["call_to_action"]           = \__( "Call Yeh!", self::text_domain );
        #self::$text["learn_more"]               = \__( "Learn More", self::text_domain );
        #self::$text["see_all"]                  = \__( "See All", self::text_domain );
        #self::$text["read_more"]                = \__( "Read More", self::text_domain );
        #self::$text["menu"]                     = \__( "Menu", self::text_domain );
        #self::$text["sections"]                 = \__( "Sections", self::text_domain );

        // blog ---------
        #self::$text["our_blog"]                 = \__( "Blog", self::text_domain );

        // contact info -------
        #self::$text["contact_name"]             = 'CCI Greenheart';
        #self::$text["contact_address"]          = 'Quinta Linda</br>Viseu</br>Portugal';
        #self::$text["contact_phone"]            = 'Tel: +351 931 941 472';

        // contact page -------
        #self::$text["get_in_touch"]             = \__( "I'd like to get in Touch!", self::text_domain );
        #self::$text["contact_details"]          = \__( "Contact Details", self::text_domain );

    }



    /**
    * change outgoing email address
    */
    public static function wp_mail_from( $email_address ) 
    {

        return 'no-reply@greenheart.org';

    }




    /**
     * Filter to remove URL field from comments form
     *
     * @since       1.6.1
     * @param       Array  $fields
     * @return      Array
     */
    public static function comment_form_default_fields( $fields )
    {

        if ( isset( $fields['url'] ) ) {

            unset($fields['url']);

        }

        // kick it back ##
        return $fields;

    }



}

