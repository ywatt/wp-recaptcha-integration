<?php


namespace RecaptchaIntegration\Core;

use RecaptchaIntegration\Core;

abstract class ModuleCollection extends Singleton {

	private $modules;
	
	protected function __construct() {
		$this->modules = array();
	}
	
	abstract function get_module_class();

	public function get_all() {
		return array_values( $this->modules );
	}

	public function add( $module ) {
		$this->modules[ $module->get_id() ]	= $module;
		return $module;
	}

	public function remove( $module ) {
		$module_class = $this->get_module_class();
		if ( $module instanceOf $module_class ) {
			$module_key = $module->get_id();
		} else if ( is_string( $module ) ) {
			$module_key = $module;
		}
		
		if ( isset( $this->modules[ $module_key ] ) ) {
			unset( $this->modules[ $module_key ] );
			return true;
		}
		return false;
	}


	public function get( $module_key ) {
		if ( isset( $this->modules[ $module_key ] ) ) {
			return $this->modules[ $module_key ];
		}
		return false;
	}

	
}