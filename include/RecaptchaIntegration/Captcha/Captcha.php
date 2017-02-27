<?php

namespace RecaptchaIntegration\Captcha;

use RecaptchaIntegration\Core;

abstract class Captcha extends Core\Configurable {

	protected $collection;

	/**
	 *	Setup collection
	 */
	protected function __construct() {

		$this->collection = Modules::instance();
		$this->collection->add( $this );

		parent::__construct();

	}

	/**
	 * Get the captcha HTML
	 * 
	 * @return	string	The Captcha Method Name
	 */
	abstract function get_name();

	/**
	 * Get the captcha HTML
	 * 
	 * @return	string	The Captcha Method Description
	 */
	abstract function get_description();

	/**
	 * Get the captcha HTML
	 * 
	 * @param	array	$attr		HTML attributes as key => value association
	 * @param	object	$context	RecaptchaIntegration\Integration\Form
	 * @return	string	The Captcha HTML
	 */
	abstract function get_html( $attr = array(), $context = null );

	/**
	 * Print the captcha HTML
	 * 
	 * @param	array	$attr		HTML attributes as key => value association
	 * @param	object	$context	RecaptchaIntegration\Integration\Form
	 */
	public final function print_html( $attr = array(), $context = null ) {
		echo $this->get_html( $attr, $context );
	}

	/**
	 * Check the users resonse.
	 * Performs a HTTP request to the google captcha service.
	 * 
	 * @return	bool	true when the captcha test verifies.
	 */
	abstract function check( );

}
