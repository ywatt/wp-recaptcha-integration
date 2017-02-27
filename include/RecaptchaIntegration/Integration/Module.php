<?php


namespace RecaptchaIntegration\Integration;

use RecaptchaIntegration\Core;
use RecaptchaIntegration\Captcha;

abstract class Module extends Core\Configurable {

	/**
	 *	@var RecaptchaIntegration\Integration\Modules
	 */
	protected $collection;

	/**
	 *	@var RecaptchaIntegration\Captcha\Modules
	 */
	protected $captchas;

	/**
	 *	@var array
	 */
	private $forms = null;

	/**
	 *	Setup collections
	 */
	protected function __construct() {

		$this->collection	= Modules::instance();
		$this->captchas		= Captcha\Modules::instance();
		
		if ( $this->is_available() ) {

			$this->init();

			$this->collection->add( $this );

		}

		parent::__construct();
	}

	/**
	 *	Register all Hooks
	 *
	 *	@return	null
	 */
	abstract function init();

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
	 *	Get Forms being able to protect
	 * 
	 *	@return	array	array(
	 *						'form_id'	=> array(
	 * 											'name'				=> (string)
	 * 											'default_enabled'	=> (bool) 
	 *										),
	 *					)
	 */
	abstract function get_forms_config();

	final public function get_forms() {
		if ( is_null( $this->forms ) ) {
			$this->forms = array();
			foreach ( $this->get_forms_config() as $form_id => $form_config ) {
				$this->forms[ $form_id ] = new Form( $form_id, $form_config['name'], $form_config['default_enabled'], $this );
			}
		}
		return $this->forms;
	}

	protected function get_form( $form_key ) {
		$forms = $this->get_forms();
		if ( isset( $forms[$form_key] ) ) {
			return $forms[$form_key];
		}
		return false;
	}


	protected function check() {
	}


	protected function remder() {
	}


}