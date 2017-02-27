<?php


namespace RecaptchaIntegration\Integration;

use RecaptchaIntegration\Core;

/**
 *	Keeps API Key configuration in Sync between the two plugins
 */
class NinjaForms extends Module {

	/**
	 *	@inheritdoc
	 */
	public function is_available() {
		return class_exists('Ninja_Forms') || function_exists('ninja_forms_register_field');
	}

	/**
	 *	@inheritdoc
	 */
	public function init() {

	}

}