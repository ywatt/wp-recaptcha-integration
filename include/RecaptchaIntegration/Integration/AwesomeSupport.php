<?php


namespace RecaptchaIntegration\Integration;

use RecaptchaIntegration\Core;

class AwesomeSupport extends Module {


	/**
	 *	@inheritdoc
	 */
	public function is_available() {
		return class_exists( 'Awesome_Support' );
	}

	/**
	 *	@inheritdoc
	 */
	public function init() {
		if ( $this->get_form('signup')->get_option() ) {
			add_action( 'wpas_after_login_fields', array( $this, 'print_captcha' ), 10, 0 );
			add_filter( 'wpas_try_login', array( $this, 'check_signup' ), 10, 1 );
		}
		if ( $this->get_form('login')->get_option() ) {
			add_action( 'wpas_after_registration_fields', array( $this, 'print_captcha' ), 10, 0 );
			add_filter( 'wpas_register_account_errors', array( $this, 'check_login' ), 10, 3 );
			add_action( 'login_footer', array( $lostpw_captcha, 'footer_scripts' ) );
		}
	}

	/**
	 * Check the Captcha after Awesome Support tried to log the user in
	 *
	 * If the user login failed we simply return the existing error.
	 *
	 * @param WP_Error|WP_User $signon The result of the login attempt
	 *
	 * @return WP_Error|WP_User
	 */
	function check_signup( $signon ) {
		if ( is_wp_error( $signon ) ) {
			return $signon;
		}

		if ( ! $this->get_form('signup')->get_captcha()->check() ) {
			return $this->core->get_wp_error();
		}

		return $signon;

	}

	public function print_captcha() {

		$current_filter = current_filter();

		if ( 'wpas_after_login_fields' === $current_filter ) {
			$form_slug = 'login';
		} else if ( 'wpas_after_registration_fields' === $current_filter ) {
			$form_slug = 'signup';
		}

		$form		= $this->get_form( $form_slug );
		$captcha	= $form->get_captcha( );
		$captcha->print_html( array(), $form );
	}


	public function get_forms_config() {
		return array(
			'login'	=> array(
				'name'				=>	__('Login Form','wp-recaptcha-integration'),
				'default_enabled'	=> true,
			),
			'signup'	=> array(
				'name'				=>	__('Signup Form','wp-recaptcha-integration'),
				'default_enabled'	=> true,
			),
		);
	}

	/**
	 *	@inheritdoc
	 */
	public function get_name() {
		return __( 'Awsome Support', 'wp-recaptcha-integration' );
	}

	/**
	 *	@inheritdoc
	 */
	public function get_description() {
		return __( 'Protect WordPress Forms', 'wp-recaptcha-integration' );
	}


}