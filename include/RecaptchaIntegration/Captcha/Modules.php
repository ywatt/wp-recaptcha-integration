<?php


namespace RecaptchaIntegration\Captcha;

use RecaptchaIntegration\Core;

class Modules extends Core\ModuleCollection {

	public function get_module_class() {
		return Captcha;
	}

	
}