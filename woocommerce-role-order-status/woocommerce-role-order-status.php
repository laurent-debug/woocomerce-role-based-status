<?php
/**
 * Plugin Name: WooCommerce Role-based Order Status (WROS)
 * Description: Set WooCommerce order status based on user role, with optional payment-method overrides.
 * Version: 0.1.0
 * Author: Fermentierra
 * License: GPL-2.0-or-later
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * WC requires at least: 7.0
 * WC tested up to: 9.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WROS_VERSION', '0.1.0' );
define( 'WROS_PATH', plugin_dir_path( __FILE__ ) );

autoload_if_needed();

function autoload_if_needed() {
	// Simple autoloader for this plugin only.
	spl_autoload_register( function( $class ) {
		if ( strpos( $class, 'WROS_' ) !== 0 ) {
			return;
		}

		$path = WROS_PATH . 'includes/class-' . strtolower( str_replace( '_', '-', $class ) ) . '.php';
		if ( file_exists( $path ) ) {
			require_once $path;
		}
	} );
}

add_action( 'plugins_loaded', function() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	( new WROS_Admin() )->init();
	( new WROS_Core() )->init();
} );
