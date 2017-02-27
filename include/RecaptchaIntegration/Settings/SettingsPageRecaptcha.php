<?php

namespace RecaptchaIntegration\Settings;

use RecaptchaIntegration\Captcha;
use RecaptchaIntegration\Core;
use RecaptchaIntegration\Integration;

class SettingsPageRecaptcha extends Settings {

	private $optionset				= 'recaptcha'; 
	private $optionset_captcha		= 'recaptcha-captcha'; 
	private $optionset_integration	= 'recaptcha-integration'; 

	/**
	 *	@var	RecaptchaIntegration\Captcha\Modules
	 */
	private $captchas;

	/**
	 *	@var	RecaptchaIntegration\Integration\Modules
	 */
	private $integrations;

	/**
	 *	@var	RecaptchaIntegration\Core\Core
	 */
	private $core;

	/**
	 *	Constructor
	 */
	protected function __construct() {

		$this->core = Core\Core::instance();

		add_action( 'init', array( $this, 'init' ) );

		add_action( "settings_page_{$this->optionset}" , array( $this , 'enqueue_assets' ) );

	}



	/**
	 *	Enqueue settings Assets
	 *
	 *	@action "settings_page_{$this->optionset}
	 */
	public function enqueue_assets() {
		wp_enqueue_style( "wp-recaptcha-settings-{$this->optionset}", $this->core->get_asset_url( "css/admin/settings-{$this->optionset}.css" ) );

		wp_enqueue_script( "wp-recaptcha-settings-{$this->optionset}", $this->core->get_asset_url( "js/admin/settings-{$this->optionset}.js" ) );
		wp_localize_script("wp-recaptcha-settings-{$this->optionset}", '{{plugin_slug}}_settings' , array(
		) );
	}


	public function init() {

		$this->captchas		= Captcha\Modules::instance();

		$this->integrations	= Integration\Modules::instance();

		foreach ( $this->captchas->get_all() as $captcha ) {
			foreach ( $captcha->get_configuration() as $config_key => $config ) {
				$wp_opt_name = $captcha->wp_option_name( $config_key );
					// recaptcha_<captcha>_<config_key>
				add_option( $wp_opt_name , $config[ 'default' ] );
			}
		}
		foreach ( $this->integrations->get_all() as $integration ) {
			foreach ( $integration->get_forms() as $form_key => $form ) {
				$wp_opt_name = $form->wp_option_name( );
					// recaptcha_<module>_<form>
				add_option( $wp_opt_name , $form->get_default_enabled() );


				foreach ( $this->captchas->get_all() as $i => $captcha ) {
					if ( $i === 0 ) {
						$wp_opt_name = $form->wp_option_name( 'captcha' );
						add_option( $wp_opt_name , $captcha->get_id() );
					}
					foreach ( $captcha->get_options() as $captcha_opt_key => $captcha_opt ) {
						$wp_opt_name = $form->wp_option_name( $captcha_opt_key, $captcha );
							// recaptcha_<module>_<form>
							// recaptcha_<module>_<form>_<captcha>_<option>
						add_option( $wp_opt_name , $captcha_opt[ 'default' ] );
					}
				}
			}
		}

	//		add_option( 'recaptcha_integration_setting_1' , 'Default Value' , '' , False );

		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );

		parent::__construct();

	}

	/**
	 *	Add Settings page
	 *
	 *	@action admin_menu
	 */
	public function admin_menu() {
		add_options_page( __( 'Captcha Integration' , 'wp-recaptcha-integration' ), __( 'Captcha Integration' , 'wp-recaptcha-integration'), 'manage_options', $this->optionset, array( $this, 'settings_page' ) );
	}

	/**
	 *	Render Settings page
	 */
	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
		<div class="wrap">
			<h2><?php _e('WP Recaptcha Integration Settings', 'wp-recaptcha-integration') ?></h2>

			<h2 class="nav-tab-wrapper" id="wp-recaptcha-tabs">
				<a href="#<?php echo $this->optionset_integration ?>" class="nav-tab nav-tab-active"><?php
					_e( 'Integration', 'wp-recaptcha-integration' );
				?></a>
				<a href="#<?php echo $this->optionset_captcha ?>" class="nav-tab"><?php
					_e( 'Configure', 'wp-recaptcha-integration' );
				?></a>
			</h2><?php

			?><div class="nav-tab-content active" id="<?php echo $this->optionset_integration ?>"><?php
				?><form action="options.php" method="post"><?php


					settings_fields(  $this->optionset_integration );
					do_settings_sections( $this->optionset_integration );
			

					submit_button( __('Save Settings' , 'wp-recaptcha-integration' ) );
				?></form><?php
			?></div><?php


			?><div class="nav-tab-content" id="<?php echo $this->optionset_captcha ?>"><?php
				?><form action="options.php" method="post"><?php

					settings_fields(  $this->optionset_captcha );
					do_settings_sections( $this->optionset_captcha );

					submit_button( __('Save Settings' , 'wp-recaptcha-integration' ) );
				?></form><?php
			?></div><?php
		?></div><?php
	}




	/**
	 *	Setup options.
	 *
	 *	@action admin_init
	 */
	public function register_settings() {
	
		$this->register_integration_settings();

		$this->register_captcha_settings();

	}

	private function register_integration_settings() {
		$captcha_choices = array();
		foreach ( $this->captchas->get_all() as $captcha ) {
			$captcha_choices[ $captcha->get_id() ]	= $captcha->get_name();
		}

		foreach ( $this->integrations->get_all() as $integration ) {
			$settings_section	= 'recaptcha_integration___' . $integration->get_id();

			add_settings_section( $settings_section, $integration->get_name(), array( $this, 'section_integration_description' ), $this->optionset_integration );

			foreach ( $integration->get_forms() as $form_key => $form ) {
				// enable / disable form
				$wp_opt_name	= $form->wp_option_name( );

				register_setting( $this->optionset_integration , $wp_opt_name, 'boolval' );
				add_settings_field(
					$wp_opt_name,
					$form->get_name(),
					array( $this, 'form_settings_ui' ),
					$this->optionset_integration,
					$settings_section,
					array( $form )
				);

				// select form captcha
				$wp_opt_name = $form->wp_option_name( 'captcha' );
				register_setting( $this->optionset_integration , $wp_opt_name, array( $this, 'sanitize_captcha' ) );

				foreach ( $this->captchas->get_all() as $captcha ) {
					foreach ( $captcha->get_options() as $captcha_opt_key => $captcha_opt_args ) {
						$wp_opt_name = $form->wp_option_name( $captcha_opt_key, $captcha );
							// should be: recaptcha_<form>_<captcha>_<optname>

						register_setting( $this->optionset_integration , $wp_opt_name, $captcha_opt_args[ 'sanitize_cb' ] );
					}
				}

			}
		}
	}

	public function form_settings_ui( $args ) {

		$captcha_choices = array();

		foreach ( $this->captchas->get_all() as $captcha ) {
			$captcha_choices[ $captcha->get_id() ]	= $captcha->get_name();
		}

		$form = $args[0];

		$wp_opt_name = $form->wp_option_name( 'captcha' );

		// enable for form
		$this->checkbox_ui( false, $form->get_name(), false, $form );

		?><div class="form-settings form-<?php esc_attr_e( $form->get_id() ) ?>"><?php

		// select captcha
		$select_captcha_ui = count( $captcha_choices ) === 1 ? 'hidden_ui' : 'select_ui';
		$this->$select_captcha_ui( 'captcha', false, false, $form, $captcha_choices );

		foreach ( $this->captchas->get_all() as $captcha ) {
			?><div class="captcha-settings captcha-<?php esc_attr_e( $captcha->get_id() ) ?> wp-clearfix"><?php
			foreach ( $captcha->get_options() as $captcha_opt_key => $captcha_opt_args ) {

				?><div class="captcha-setting captcha-setting-<?php esc_attr_e( $captcha_opt_key ) ?>"><?php
				call_user_func( $this->get_ui_callback( $captcha_opt_args[ 'type' ] ), array( 
					array( $captcha_opt_key, $captcha ),
					$captcha_opt_args[ 'label' ],
					isset( $captcha_opt_args[ 'description' ] ) ? $captcha_opt_args[ 'description' ] : false,
					$form,
					isset( $captcha_opt_args[ 'choices' ] ) ? $captcha_opt_args[ 'choices' ] : false,
				) );
				?></div><?php
			}
			?></div><?php
		}
		?></div><?php
	}


	private function register_captcha_settings() {

		foreach ( $this->captchas->get_all() as $captcha ) {
			$settings_section	= 'recaptcha_captcha___' . $captcha->get_id();

			add_settings_section( $settings_section, $captcha->get_name(), array( $this, 'section_captcha_description' ), $this->optionset_captcha );
	
			foreach ( $captcha->get_configuration() as $option_key => $option_args ) {
				$wp_opt_name = $captcha->wp_option_name( $option_key );
				register_setting( $this->optionset_captcha , $wp_opt_name, $option_args[ 'sanitize_cb' ] );

				add_settings_field(
					$wp_opt_name,
					$option_args[ 'label' ],
					$this->get_ui_callback( $option_args[ 'type' ] ),
					$this->optionset_captcha,
					$settings_section,
					array(
						'option_name'			=> $option_key,
						'option_label'			=> $option_args[ 'label' ],
						'option_description'	=> isset( $option_args[ 'description' ] ) ? $option_args[ 'description' ] : false,
						'object'				=> $captcha,
						'choices'				=> isset( $option_args[ 'choices' ] ) ? $option_args[ 'choices' ] : false,
					)
				);
			}

		}

	}

	/**
	 * Print some documentation for the optionset
	 */
	public function section_integration_description( $args ) {
		$key_parts		= explode('___',$args[ 'id' ] );
		$section_key	= array_pop( $key_parts );
		$integration	= $this->integrations->get( $section_key );

		?><div class="inside">
			<p><?php 
				esc_html_e( $integration->get_description() );
			?></p>
		</div><?php
	}

	/**
	 * Print some documentation for the optionset
	 */
	public function section_captcha_description( $args ) {
		$key_parts		= explode('___',$args[ 'id' ] );
		$section_key	= array_pop( $key_parts );
		$captcha		= $this->captchas->get( $section_key );

		?><div class="inside">
			<p><?php 
				esc_html_e( $captcha->get_description() );
			?></p>
		</div><?php
	}

}