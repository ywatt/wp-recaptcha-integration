<?php

namespace RecaptchaIntegration\Core;

trait ConfigurableTrait {
	

	/**
	 *	Get unique ID for this model used for prefixing several identifiers.
	 *	Override this Method if you like.
	 *	Defaults to lowercase short classname
	 *
	 *	@return	string	unique ID for this module
	 */
	public function get_id() {
		// Fastest way as of http://stackoverflow.com/a/27457689
		$class	= strtolower( get_class( $this ) );
		return substr($class, strrpos( $class, '\\') + 1 );
	}

	/**
	 *	Instance Options
	 *
	 *	@return	array	array(
	 *						'my_option'	=> array(
	 *							'label'			=> __( 'Something', 'wp-recaptcha-integration' ),
	 *							'description'	=> __( 'Something more to say', 'wp-recaptcha-integration' ),
	 *							'type'			=> 'boolean', // Values: 'boolean' | 'string'
	 *							'default'		=> 'default_value',
	 *						),
	 *					)
	 */
	public function get_options() {
		return array( );
	}

	/**
	 *	Get instance Option by context
	 *
	 *	@param	string	$option_name	As defined in get_options implementation
	 *	@param	object	$context		Context object, Integration module
	 *
	 *	@return	mixed	Option value
	 */
	public function get_option( $option_name = false, $context = null ) {
		$wp_option_name = $this->wp_option_name(  $option_name, $context );
		return get_option( $wp_option_name );
	}

	/**
	 *	Update instance Option
	 *
	 *	@param	string	$option_name	As defined in get_options implementation
	 *	@param	mixed	$option_value
	 *	@param	object	$context		Context object, Integration module
	 *
	 *	@return	mixed	Option value
	 */
	public function update_option( $option_name = false, $option_value, $context = null ) {
		$wp_option_name = $this->wp_option_name(  $option_name, $context );
		return update_option( $wp_option_name, $option_value );
	}


	/**
	 *	Update instance Option
	 *
	 *	@params	string|object	option name parts
	 *
	 *	@return	string	OPtion name to be used with get_option(), update_option and such
	 */
	public function wp_option_name( $option_name = false, $context = null ) {

		$name_parts = array(
			sanitize_key( $this->core->get_id() ), // 'recaptcha'
			sanitize_key( $this->get_id() ), // ...
		);
		if ( ! is_null( $context ) ) {
			$name_parts[] = sanitize_key( $context->get_id() );
		}
		if ( $option_name ) {
			$name_parts[] = sanitize_key( $option_name );
		}

		return implode( '_', $name_parts );
	}

	/**
	 *	Module Configuration
	 *	Like API-Keys and such.
	 *
	 *	@return	array	array(
	 *						'my_option'	=> array(
	 *							'label'			=> __( 'Something', 'wp-recaptcha-integration' ),
	 *							'description'	=> __( 'Something more to say', 'wp-recaptcha-integration' ),
	 *							'type'			=> 'boolean', // Values: 'boolean' | 'string'
	 *							'default'		=> 'default_value',
	 *						),
	 *					)
	 */
	public function get_configuration() {
		return array( );
	}




}