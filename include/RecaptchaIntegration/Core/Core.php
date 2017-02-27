<?php

namespace RecaptchaIntegration\Core;

use RecaptchaIntegration\Captcha;
use RecaptchaIntegration\Integration;

class Core extends Module {

	/**
	 *	Private constructor
	 */
	protected function __construct() {
		add_action( 'plugins_loaded' , array( $this , 'load_textdomain' ) );
		add_action( 'plugins_loaded' , array( $this , 'plugins_loaded' ) );
		add_action( 'init' , array( $this , 'init' ) );
		add_action( 'wp_enqueue_scripts' , array( $this , 'wp_enqueue_style' ) );

		register_activation_hook( RECAPTCHA_INTEGRATION_FILE, array( __CLASS__ , 'activate' ) );
		register_deactivation_hook( RECAPTCHA_INTEGRATION_FILE, array( __CLASS__ , 'deactivate' ) );
		register_uninstall_hook( RECAPTCHA_INTEGRATION_FILE, array( __CLASS__ , 'uninstall' ) );

		parent::__construct();
	}

	/**
	 *	Global prefix
	 *
	 *	@return	string
	 */
	public function get_id() {
		return 'recaptcha';
	}

	/**
	 *	Load frontend styles and scripts
	 *
	 *	@action wp_enqueue_scripts
	 */
	public function wp_enqueue_style() {
		wp_enqueue_script( 'wp-recaptcha-integration-script', $this->get_asset_url( 'js/frontend.js' ), array( 'jquery' ) );
	}


	/**
	 *	Load text domain
	 * 
	 *  @action plugins_loaded
	 */
	public function load_textdomain() {
		
		// load textdomain
		load_plugin_textdomain( 'wp-recaptcha-integration' , false, RECAPTCHA_INTEGRATION_DIRECTORY . '/languages/' );
	}

	/**
	 *	Init modules
	 * 
	 *  @action plugins_loaded
	 */
	public function plugins_loaded() {

		// first enable captcha modules
		Captcha\gRecaptcha::instance();

		// then enable integration modules
		Integration\AwesomeSupport::instance();
//		Integration\BBPress::instance();
//		Integration\ContactForm7::instance();
//		Integration\NinjaForms::instance();
//		Integration\WooCommerce::instance();
		Integration\WordPress::instance();
//		Integration\WordPressMU::instance();

	}

	/**
	 *	Init hook.
	 * 
	 *  @action init
	 */
	public function init() {
	}

	/**
	 *	@return bool	whether this plugin is network activated in a multisite
	 */
	public function is_network_activated() {
		if ( ! is_multisite() ) {
			return false;
		}

		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		return is_plugin_active_for_network( RECAPTCHA_INTEGRATION_FILE );
	}

	public function get_wp_error() {
		$msg = __( '<strong>Error:</strong> the Captcha didn’t verify.', 'wp-recaptcha-integration' );
		return new \WP_Error( 'captcha_error' , $msg );
	}

	/**
	 *	Fired on plugin activation
	 */
	public static function activate() {
	}

	/**
	 *	Fired on plugin deactivation
	 */
	public static function deactivate() {
	}

	/**
	 *	Fired on plugin deinstallation
	 */
	public static function uninstall() {
	}

}
