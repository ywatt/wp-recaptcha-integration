<?php


namespace RecaptchaIntegration\Integration;

use RecaptchaIntegration\Core;

/**
 *	Forms
 *	 - WC checkout
 *	 - WC signup
 *	 - WC login
 *	 - WC lost password
 */
class WooCommerce extends Module {


	/**
	 *	@inheritdoc
	 */
	public function is_available() {
		return function_exists('WC') || class_exists('WooCommerce');
	}

	/**
	 *	@inheritdoc
	 */
	public function init() {

	}

}