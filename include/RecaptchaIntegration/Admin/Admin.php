<?php

namespace RecaptchaIntegration\Admin;
use RecaptchaIntegration\Core;


class Admin extends Core\Module {

	/**
	 *	@var object	RecaptchaIntegration\Core\Core
	 */
	private $core;

	/**
	 *	Private constructor
	 */
	protected function __construct() {

		$this->core			= Core\Core::instance();

		add_action( 'admin_init', array( $this , 'admin_init' ) );

	}

	/**
	 *	@action 'admin_init'
	 */
	function admin_init() {
	}

}