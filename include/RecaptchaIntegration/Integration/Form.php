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
class Form {

    use Core\ConfigurableTrait;

	private $id;
	private $name;
	private $default_enabled;
	private $module;
	private $captcha = null;
	
	public function __construct( $form_id, $name, $default_enabled, $module ) {

		$this->core = Core\Core::instance();

		$this->id				= $form_id;
		$this->name				= $name;
		$this->default_enabled	= $default_enabled;
		$this->module			= $module;
		$this->captchas			= Captcha\Modules::instance();
	}

	public function get_id() {
		return $this->module->get_id() .'_' . $this->id;
	}
	
	
	public function get_name() {
		return $this->name;
	}
	public function get_default_enabled() {
		return $this->default_enabled;
	}
	public function get_module() {
		return $this->module;
	}

	public function get_captcha() {
//		vaR_dump($this->get_option( 'captcha' ));exit();
		return $this->captchas->get( $this->get_option( 'captcha' ) );
	}

}