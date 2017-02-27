<?php


namespace RecaptchaIntegration\Integration;

use RecaptchaIntegration\Core;

class BBPress extends Module {


	/**
	 *	@inheritdoc
	 */
	public function is_available() {
		return class_exists( 'bbPress' );
	}

	/**
	 *	@inheritdoc
	 */
	public function init() {

	}

}