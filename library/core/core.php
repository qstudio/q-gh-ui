<?php

namespace q\ui\core;

use q\ui\core\core as core;
use q\ui\core\helper as helper;
use q\ui\theme\template as template;

// load it up ##
#\q\ui\core\core::run();

class core extends \q_ui {

    public static function run()
    {


    }



    /**
	 * Detect if this is a development site running on a private/loopback IP
	 *
     * @todo        move to Q
	 * @return      Boolean
	 */
	public static function is_localhost() {

		$loopbacks = array( '127.0.0.1', '::1' );
        
        if ( in_array( $_SERVER['REMOTE_ADDR'], $loopbacks ) ) {

            return true;
            
		}

		if ( ! filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE ) ) {

            return true;
            
		}

        return false;
        
    }
    


    /**
	 * Detect if this is a development site running on a staging URL ".staging" 
	 *
     * @todo        move to Q
	 * @return      Boolean
	 */
	public static function is_staging() {

        $needle = '.staging'; # '.qlocal.com';
        
        $urlparts = parse_url( \network_site_url() );
        // helper::log( $urlparts );
        $domain = $urlparts['host'];
        
        // helper::log( 'network_site_url: '.\network_site_url() );
        // helper::log( 'domain: '.$domain );

        // if ( in_array( $domain, $loopbacks ) ) {
        if ( strpos( $domain, $needle ) !== false ) {

            // helper::log( 'On staging..' );

            return true;
            
		}

        // helper::log( 'Not on staging...' );

        return false;
        
	}


}