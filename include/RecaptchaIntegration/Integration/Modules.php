<?php


namespace RecaptchaIntegration\Integration;

use RecaptchaIntegration\Core;

class Modules extends Core\ModuleCollection {

	public function get_module_class() {
		return Module;
	}
	
}