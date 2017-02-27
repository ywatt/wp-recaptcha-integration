<?php

namespace RecaptchaIntegration\Settings;
use RecaptchaIntegration\Core;

abstract class Settings extends Core\Singleton {

	/**
	 *	Constructor
	 */
	protected function __construct(){

		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		parent::__construct();

	}


	abstract function register_settings();


	/**
	 *	Print a checkbox
	 *
	 *	@param $args	array( $option_name, $label )
	 */
	public function checkbox_ui_cb( $args ) {
		@list( $option_key, $label, $description, $object ) = array_values( $args );
		return $this->checkbox_ui( $option_key, $label, $description, $object );
	}
	protected function checkbox_ui( $option_key, $label, $description, $object ) {
		
		$option_key = (array) $option_key;

		$option_name = call_user_func_array( array( $object, 'wp_option_name'), $option_key );
		$option_value = get_option( $option_name );
		
		?>
		<input type="hidden" name="<?php esc_attr_e( $option_name ) ?>" value="0" />
		<input type="checkbox" <?php checked( boolval( $option_value ), true, true ); ?> name="<?php esc_attr_e( $option_name ) ?>" id="<?php esc_attr_e( $option_name ) ?>" value="1" />
		<label for="<?php esc_attr_e( $option_name ) ?>">
			<?php echo $label ?>
		</label>
		<?php 
			if ( ! empty( $description ) ) {
				printf( '<p class="description">%s</p>', $description );
			}
		?>
		<?php
	}


	/**
	 *	Print a checkbox
	 *
	 *	@param $args	array( $option_name, $label )
	 */
	public function hidden_ui_cb( $args ) {
		@list( $option_key, $label, $description, $object ) = array_values( $args );
		return $this->hidden_ui( $option_key, $label, $description, $object );
	}
	public function hidden_ui( $option_key, $label, $description, $object ) {

		$option_key = (array) $option_key;

		$option_name = call_user_func_array( array( $object, 'wp_option_name'), $option_key );
		$option_value = get_option( $option_name );
		
		?>
		<input type="hidden" name="<?php esc_attr_e( $option_name ) ?>" value="<?php esc_attr_e( $option_value ) ?>" />
		<?php
	}


	/**
	 *	Print a radio group
	 *
	 *	@param $args	array( $option_key, $label, $description, $object, $choices )
	 */
	public function radio_ui_cb( $args ) {
		@list( $option_key, $label, $description, $object, $choices ) = array_values( $args );
		return $this->radio_ui( $option_key, $label, $description, $object, $choices );
	}
	protected function radio_ui( $option_key, $label, $description, $object, $choices ) {

		$option_key		= (array) $option_key;

		$option_name	= call_user_func_array( array( $object, 'wp_option_name'), $option_key );
		$option_value	= get_option( $option_name );


		?><div class="option-wrap option-wrap-radio <?php echo $this->get_option_classname( $option_key ); ?>"><?php

			?><div class="label"><?php 
				echo $label;
			?></div><?php

			foreach ( $choices as $choice_value => $choice_label ) {
				$html_id = strtolower( $option_name . '-' . $choice_value );
				?>
					<input <?php checked( $choice_value, $option_value, true ) ?> type="radio" id="<?php esc_attr_e( $html_id ) ?>" name="<?php esc_attr_e( $option_name ) ?>" value="<?php esc_attr_e( $choice_value ) ?>" />
					<label for="<?php esc_attr_e( $html_id ) ?>">
						<?php esc_html_e( $choice_label ) ?>
					</label>
				<?php
			}

			if ( ! empty( $description ) ) {
				printf( '<p class="description">%s</p>', $description );
			}
		?></div><?php 


	}

	/**
	 *	Print a select
	 *
	 *	@param $args	array( $option_key, $label, $description, $object, $choices )
	 */
	public function select_ui_cb( $args ) {
		@list( $option_key, $label, $description, $object, $choices ) = array_values( $args );
		return $this->select_ui( $option_key, $label, $description, $object, $choices );
	}
	protected function select_ui( $option_key, $label, $description, $object, $choices ) {

		$option_key = (array) $option_key;

		$option_name = call_user_func_array( array( $object, 'wp_option_name'), $option_key );
		$option_value = get_option( $option_name );

		?><div class="option-wrap option-wrap-select <?php echo $this->get_option_classname( $option_key ); ?>"><?php
			?><label for="<?php esc_attr_e( $option_name ) ?>" class="option-<?php echo $option_key ?>"><?php
				echo $label;
			?></label>

			<select id="<?php esc_attr_e( $option_name ) ?>" name="<?php esc_attr_e( $option_name ) ?>"><?php

			foreach ( $choices as $choice_value => $choice_label ) {
				$html_id = strtolower( $option_name . '-' . $choice_value );
				?>
					<option value="<?php esc_attr_e( $choice_value ) ?>"  <?php selected( $choice_value, $option_value, true ) ?>><?php
						esc_html_e( $choice_label );
					?></option>
				<?php
			}
			?></select><?php 

			if ( ! empty( $description ) ) {
				printf( '<p class="description">%s</p>', $description );
			}

		?></div><?php 
	}


	/**
	 *	Print a checkbox
	 *
	 *	@param $args	array( $option_name, $label )
	 */
	public function text_ui_cb( $args ) {
		@list( $option_key, $label, $description, $object ) = array_values( $args );
		return $this->text_ui( $option_key, $label, $description, $object );
	}
	protected function text_ui( $option_key, $label, $description, $object ) {

		$option_key = (array) $option_key;

		$option_name = call_user_func_array( array( $object, 'wp_option_name'), $option_key );
		$option_value = get_option( $option_name );
		
		?><div class="option-wrap option-wrap-text <?php echo $this->get_option_classname( $option_key ); ?>"><?php

			?><label>
				<?php echo $label ?>
				<input type="text" name="<?php echo $option_name ?>" value="<?php esc_attr_e( $option_value ) ?>" />
			</label>
			<?php 
				if ( ! empty( $description ) ) {
					printf( '<p class="description">%s</p>', $description );
				}

		?></div><?php 
	}

	private function get_option_classname( $option_key ) {
		$classname = ['option'];
		foreach ( (array) $option_key as $part ) {
			if ( is_string( $part ) ) {
				$classname[] = $part;
			} else if ($part instanceOf Core\Configurable ) {
				$classname[] = $part->get_id();
			}
		}
		return implode( '-', $classname );
	
	}


	/**
	 *	Sanitize checkbox input
	 *
	 *	@param $value
	 *	@return boolean
	 */
	public function sanitize_checkbox( $value ) {
		return boolval( $value );
	}

	protected function get_ui_callback( $type ) {
		switch( $type ) {
			case 'checkbox':
				return array( $this, 'checkbox_ui_cb' );
			case 'radio':
				return array( $this, 'radio_ui_cb' );
			case 'select':
				return array( $this, 'select_ui_cb' );
			default;
			case 'text':
				return array( $this, 'text_ui_cb' );
		}
	}

}