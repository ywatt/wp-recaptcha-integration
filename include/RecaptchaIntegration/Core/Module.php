<?php

namespace RecaptchaIntegration\Core;

class Module extends Singleton {

	/**
	 *	Get asset url for this plugin
	 *
	 *	@param	string	$asset	URL part relative to plugin class
	 *	@return wp_enqueue_editor
	 */
	public function get_asset_url( $asset ) {
		return plugins_url( $asset, RECAPTCHA_INTEGRATION_FILE );
	}


	/**
	 *	Get asset url for this plugin
	 *
	 *	@param	string	$asset	URL part relative to plugin class
	 *	@return wp_enqueue_editor
	 */
	public function get_asset_path( $asset ) {
		return plugin_dir_path( RECAPTCHA_INTEGRATION_FILE ) . $asset;
	}

}