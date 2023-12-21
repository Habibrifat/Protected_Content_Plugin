<?php
/**
 * Plugin Name: Elementor Protected Content
 * Description: Custom Elementor Protected Content.
 * Plugin URI:
 * Version:     1.0.0
 * Author:      Developed By Rifat
 * Author URI:
 * Text Domain: epc
 *
 * Elementor tested up to: 3.16.0
 * Elementor Pro tested up to: 3.16.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function elementor_protected_content() {

    // Load plugin file
    require_once( __DIR__ . '/includes/plugin.php' );

    // Run the plugin
    \Elementor_Protected_content\Plugin::instance();

}
add_action( 'plugins_loaded', 'elementor_protected_content' );