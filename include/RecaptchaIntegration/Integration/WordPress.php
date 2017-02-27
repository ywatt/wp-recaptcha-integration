<?php


namespace RecaptchaIntegration\Integration;

use RecaptchaIntegration\Core;
use RecaptchaIntegration\Captcha;

/**
 *	Forms
 *	 - signup
 *	 - login
 *	 - lost password
 *	 - comment
 *	 - MS create blog
 */
class WordPress extends Module {


	/**
	 *	@inheritdoc
	 */
	public function is_available() {
		return true; // surprise, surprise.
	}

	/**
	 *	@inheritdoc
	 */
	public function init() {

		$do_login_footer = false;
		if ( $this->get_form('login')->get_option() ) {

			$login_captcha = $this->get_form('login')->get_captcha( );

			add_action( 'login_form', array( $this, 'print_captcha') );
			add_filter( 'wp_authenticate_user', array( $this,'deny_login'), 99 );
			add_action( 'login_footer', array( $login_captcha, 'footer_scripts' ) );

		}

		if ( $this->get_form('signup')->get_option() ) {

			$signup_captcha = $this->get_form('signup')->get_captcha( );

			add_action('register_form', array( $this, 'print_captcha' ) );
			add_filter( 'registration_errors', array( $this,'registration_errors' ) );
			add_action( 'login_footer', array( $signup_captcha, 'footer_scripts' ) );

		}

		if ( $this->get_form('lostpw')->get_option() ) {

			$lostpw_captcha = $this->get_form('lostpw')->get_captcha( );

			add_action( 'lostpassword_form', array( $this, 'print_captcha' ) );
			add_filter( 'lostpassword_post', array( $this,'check_lostpw' ) );
			add_action( 'login_footer', array( $lostpw_captcha, 'footer_scripts' ) );

		}

		if ( $this->get_form('comment')->get_option() ) {
			$comment_captcha = $this->get_form('comment')->get_captcha( );
			// ...
		}
	}

	/**
	 *	@inheritdoc
	 */
	public function get_name() {
		return __( 'WordPress', 'wp-recaptcha-integration' );
	}

	/**
	 *	@inheritdoc
	 */
	public function get_description() {
		return __( 'Protect WordPress Forms', 'wp-recaptcha-integration' );
	}

	/**
	 *	@inheritdoc
	 */
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
			'lostpw'	=> array(
				'name'				=>	__('Lost Password Form','wp-recaptcha-integration'),
				'default_enabled'	=> true,
			),
			'comment'	=> array(
				'name'				=>	__('Comment Form','wp-recaptcha-integration'),
				'default_enabled'	=> false,
			),
		);
	}

	
	public function print_captcha() {

		$current_filter = current_filter();

		if ( 'login_form' === $current_filter ) {
			$form_slug = 'login';
		} else if ( 'register_form' === $current_filter ) {
			$form_slug = 'signup';
		} else if ( 'lostpassword_form' === $current_filter ) {
			$form_slug = 'lostpw';
		}

		$form		= $this->get_form( $form_slug );
		$captcha	= $form->get_captcha( );
		$captcha->print_html( array(), $form );
	}


	/**
	 *	check recaptcha on login
	 *	filter function for `wp_authenticate_user`
	 *
	 *	@param $user WP_User
	 *	@return object user or wp_error
	 */
	public function check_login( $user ) {
		if ( isset( $_POST["log"]) && ! $this->get_form('login')->get_captcha()->check() ) {
			return $this->core->get_wp_error();


//			if ( $this->get_option( 'prevent_lockout' ) && in_array( 'administrator' , $user->roles ) ) {
//				return $user;
//			} else {
//				return $this->wp_error( $user );
//			}
		}
		return $user;
	}

	/**
	 *	check recaptcha on registration
	 *	filter function for `registration_errors`
	 *
	 *	@param $errors WP_Error
	 *	@return WP_Error with captcha error added if test fails.
	 */
	public function check_signup( $errors ) {
		if ( ! $this->get_form('signup')->get_captcha()->check() ) {
			$errors[] = $this->core->get_wp_error();
		}
		return $errors;
	}
	
	/**
	 *	Check recaptcha and wp_die() on fail
	 *	hooks into `pre_comment_on_post`, `lostpassword_post`
	 */
 	public function check_lostpw( ) {
		if ( ! $this->get_form('lostpw')->get_captcha()->check() ) {
 			wp_die( $this->core->get_wp_error() );
 		}
 	}

/*

	public function get_options() {
		return array(
			'prevent_lockout'	=> array(
				'label'			=>	__('Prevent Lockout','wp-recaptcha-integration'),
				'type'			=> 'checkbox',
				'default'		=> true,
				'sanitize_cb'	=> 'boolval',
			),
			'signup'	=> array(
				'label'			=>	__('Signup Form','wp-recaptcha-integration'),
				'type'			=> 'checkbox',
				'default'		=> true,
				'sanitize_cb'	=> 'boolval',
			),
			'lostpw'	=> array(
				'label'			=>	__('Lost Password Form','wp-recaptcha-integration'),
				'type'			=> 'checkbox',
				'default'		=> true,
				'sanitize_cb'	=> 'boolval',
			),
			'comment'	=> array(
				'label'			=>	__('Comment Form','wp-recaptcha-integration'),
				'type'			=> 'checkbox',
				'default'		=> true,
				'sanitize_cb'	=> 'boolval',
			),
		);
	}
*/

}