<?php


namespace RecaptchaIntegration\Integration;

use RecaptchaIntegration\Core;

/**
 *	Keeps API Key configuration in Sync between the two plugins
 */
class ContactForm7 extends Module {

	/**
	 *	@inheritdoc
	 */
	public function is_available() {
		return defined('WPCF7_VERSION') && version_compare( $wpcf7_version , '4.3' , '>' );
	}

	/**
	 *	@inheritdoc
	 */
	public function init() {

	}

}