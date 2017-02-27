<?php


namespace RecaptchaIntegration\Integration;

use RecaptchaIntegration\Core;

/**
 *	Forms
 *	 - MS create blog
 */
class WordPressMU extends Module {


	/**
	 *	@inheritdoc
	 */
	public function is_available() {
		return Core\Core::instance()->is_network_activated(); // surprise, surprise.
	}

	/**
	 *	@inheritdoc
	 */
	public function init() {

	}

}