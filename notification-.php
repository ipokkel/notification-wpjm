<?php
/**
 * Plugin Name: Notification : 
 * Description: Extension for Notification plugin
 * Plugin URI: https://localhost
 * Author: Author
 * Author URI: https://localhost
 * Version: 1.0.0
 * License: GPL3
 * Text Domain: notification-
 * Domain Path: /languages
 *
 * @package notification/
 */

/**
 * @todo replace globally these values:
 * - 
 * - 
 * - 
 *
 * You can do this with this simple command replacing the sed parts:
 * find . -type f \( -iname \*.php -o -iname \*.txt  -o -iname \*.json \) -exec sed -i 's//YOURNAMESPACE/g; s//Your Nicename/g; s//yourslug/g' {} +
 *
 * Or just executing rename.sh script
 */

/**
 * Plugin's autoload function
 *
 * @param  string $class class name.
 * @return mixed         false if not plugin's class or void
 */
function notification__autoload( $class ) {

	$parts = explode( '\\', $class );

	if ( array_shift( $parts ) != 'BracketSpace' ) {
		return false;
	}

	if ( array_shift( $parts ) != 'Notification' ) {
		return false;
	}

	if ( array_shift( $parts ) != '' ) {
		return false;
	}

	$file = trailingslashit( dirname( __FILE__ ) ) . trailingslashit( 'class' ) . implode( '/', $parts ) . '.php';

	if ( file_exists( $file ) ) {
		require_once $file;
	}

}
spl_autoload_register( 'notification__autoload' );

/**
 * Boot up the plugin in theme's action just in case the Notification
 * is used as a bundle.
 */
add_action( 'after_setup_theme', function() {

	/**
	 * Requirements check
	 */
	$requirements = new BracketSpace\Notification\\Utils\Requirements( __( 'Notification : ', 'notification-' ), array(
		'php'          => '5.3',
		'wp'           => '4.6',
		'notification' => true,
	) );

	/**
	 * Tests if Notification plugin is active
	 * We have to do it like this in case the plugin
	 * is loaded as a bundle.
	 *
	 * @param string $comparsion value to test.
	 * @param object $r          requirements.
	 * @return void
	 */
	function notification__check_base_plugin( $comparsion, $r ) {
		if ( $comparsion === true && ! function_exists( 'notification_runtime' ) ) {
			$r->add_error( __( 'Notification plugin active', 'notification-' ) );
		}
	}

	$requirements->add_check( 'notification', 'notification__check_base_plugin' );

	if ( ! $requirements->satisfied() ) {
		add_action( 'admin_notices', array( $requirements, 'notice' ) );
		return;
	}


} );
