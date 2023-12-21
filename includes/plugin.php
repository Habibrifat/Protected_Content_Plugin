<?php
namespace Elementor_Protected_content;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

final class Plugin {
    const VERSION = '1.0.0';
    const MINIMUM_ELEMENTOR_VERSION = '3.16.0';

    const MINIMUM_PHP_VERSION = '7.4';
    private static $_instance = null;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }
    public function __construct() {

        if ( $this->is_compatible() ) {
            add_action( 'elementor/init', [ $this, 'init' ] );
        }

    }
    public function is_compatible() {

        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return false;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return false;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return false;
        }

        return true;

    }
    public function admin_notice_missing_main_plugin() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'epc' ),
            '<strong>' . esc_html__( 'Elementor Protected Content', 'epc' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'epc' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }
    public function admin_notice_minimum_elementor_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'epc' ),
            '<strong>' . esc_html__( 'Elementor Protected Content', 'epc' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'epc' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }
    public function admin_notice_minimum_php_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'epc' ),
            '<strong>' . esc_html__( 'Elementor Protected Content', 'epc' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'epc' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    public function init() {

        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
//        add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );

//        add_action( "elementor/frontend/after_enqueue_scripts", [ $this, 'frontend_assets_scripts' ] );

    }


    public function register_widgets( $widgets_manager ) {

        require_once( __DIR__ . '/widgets/Elementor_Protected_content_widget.php' );

        $widgets_manager->register( new Elementor_Protected_content_widget() );


    }




}