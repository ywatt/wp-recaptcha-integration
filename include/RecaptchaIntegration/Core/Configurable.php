<?php

namespace RecaptchaIntegration\Core;

abstract class Configurable extends Singleton {

    use ConfigurableTrait;

	protected $core;

	protected function __construct() {

		$this->core = Core::instance();

		parent::__construct();
	}

	/**
	 *	Returns if a specific module is avaliable, e.g. by checking dependencies.
	 *
	 *	@return	bool	Whether all necessary requirements are met to use this this integration module.
	 */
	abstract function is_available();


}