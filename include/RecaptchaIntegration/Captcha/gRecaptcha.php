<?php

namespace RecaptchaIntegration\Captcha;

use RecaptchaIntegration\Core;

class gRecaptcha extends Captcha {

	private $rendered_captchas		= array();

	private $_last_result			= false;

	private $supported_languages	= array(
		'ar'	=>	'Arabic',
		'bg'	=>	'Bulgarian',
		'ca'	=>	'Catalan',
		'zh-CN'	=> 'Chinese (Simplified)',
		'zh-TW' => 'Chinese (Traditional)',
		'hr'	=>	'Croatian',
		'cs'	=>	'Czech',
		'da'	=>	'Danish',
		'nl'	=>	'Dutch',
		'en-GB'	=>	'English (UK)',
		'en'	=>	'English (US)',
		'fil'	=>	'Filipino',
		'fi'	=>	'Finnish',
		'fr'	=>	'French',
		'fr-CA'	=>	'French (Canadian)',
		'de'	=>	'German',
		'de-AT'	=>	'German (Austria)',
		'de-CH'	=>	'German (Switzerland)',
		'el'	=>	'Greek',
		'iw'	=>	'Hebrew',
		'hi'	=>	'Hindi',
		'hu'	=>	'Hungarain',
		'id'	=>	'Indonesian',
		'it'	=>	'Italian',
		'ja'	=>	'Japanese',
		'ko'	=>	'Korean',
		'lv'	=>	'Latvian',
		'lt'	=>	'Lithuanian',
		'no'	=>	'Norwegian',
		'fa'	=>	'Persian',
		'pl'	=>	'Polish',
		'pt'	=>	'Portuguese',
		'pt-BR'	=>	'Portuguese (Brazil)',
		'pt-PT'	=>	'Portuguese (Portugal)',
		'ro'	=>	'Romanian',
		'ru'	=>	'Russian',
		'sr'	=>	'Serbian',
		'sk'	=>	'Slovak',
		'sl'	=>	'Slovenian',
		'es'	=>	'Spanish',
		'es-419'	=>	'Spanish (Latin America)',
		'sv'	=>	'Swedish',
		'th'	=>	'Thai',
		'tr'	=>	'Turkish',
		'uk'	=>	'Ukrainian',
		'vi'	=>	'Vietnamese',
	);


	/**
	 *	Setup choices
	 */
	protected function __construct() {

		parent::__construct();

		$this->type_selection = array(
			'image'				=> __( 'Image', 'wp-recaptcha-integration' ),
			'audio'				=> __( 'Audio', 'wp-recaptcha-integration' ),
		);
		$this->language_selection = array_merge( array(
			''				=> __( 'Automatic', 'wp-recaptcha-integration' ),
			'WPLANG'		=> __( 'Site Language', 'wp-recaptcha-integration' ),
		), $this->supported_languages );

		$this->theme_selection = array(
			'light'				=> __( 'Light', 'wp-recaptcha-integration' ),
			'dark'				=> __( 'Dark', 'wp-recaptcha-integration' ),
		);
/*
		$this->badge_selection = array(
			'bottomright'			=> __( 'Bottom right', 'wp-recaptcha-integration' ),
			'bottomleft'			=> __( 'Bottom left', 'wp-recaptcha-integration' ),
			'inline'				=> __( 'Inline', 'wp-recaptcha-integration' ),
		);
*/
		$this->size_selection = array(
			'normal'			=> __( 'Normal', 'wp-recaptcha-integration' ),
			'compact'			=> __( 'Compact', 'wp-recaptcha-integration' ),
//			'invisible'			=> __( 'Invisible', 'wp-recaptcha-integration' ) ,
		);


	}

	public function is_available() {
		return true;
	}

	/**
	 *	@inheritdoc
	 */
	public function get_name() {
		return __( 'Google reCAPTCHA', 'wp-recaptcha-integration');
	}

	/**
	 *	@inheritdoc
	 */
	public function get_description() {
		return __( 'Protect your website from spam and abuse while letting real people pass through with ease.', 'wp-recaptcha-integration');
	}



	/**
	 *	@inheritdoc
	 */
	public function get_options() {

		return array(
			'theme'				=> array(
				'label'				=> __( 'Theme', 'wp-recaptcha-integration' ),
				'type'				=> 'radio', 
				'choices'			=> $this->theme_selection,
				'default'			=> 'light',
				'sanitize_cb'		=> array( $this, 'sanitize_theme' ),
			),
			'size'				=> array(
				'label'				=> __( 'Size', 'wp-recaptcha-integration' ),
				'type'				=> 'radio', 
				'choices'			=> $this->size_selection,
				'default'			=> 'normal',
				'sanitize_cb'		=> array( $this, 'sanitize_size' ),
			),
			'type'				=> array(
				'label'				=> __( 'Type', 'wp-recaptcha-integration' ),
				'type'				=> 'radio', 
				'choices'			=> $this->type_selection,
				'default'			=> 'image',
				'sanitize_cb'		=> array( $this, 'sanitize_type' ),
			),
/*
			'badge'				=> array(
				'label'				=> __( 'Badge Position','wp-recaptcha-integration'),
				'description'		=> __( 'Choose ‘Inline’ to control the css.' ,'wp-recaptcha-integration' ),
				'type'				=> 'radio', 
				'choices'			=> $this->badge_selection,
				'default'			=> 'bottomright',
				'sanitize_cb'		=> array( $this, 'sanitize_badge' ),
			),
*/
			'disable_submit'	=> array(
				'label'				=> __( 'Disable Submit Button','wp-recaptcha-integration'),
				'description'		=> __( 'Disable Form Submit Button until no-captcha is entered.' ,'wp-recaptcha-integration' ),
				'type'				=> 'checkbox',
				'default'			=> false,
				'sanitize_cb'		=> 'boolval',
			),
		);
	}




	/**
	 *	@inheritdoc
	 */
	public function get_configuration() {
		return array(
			'sitekey'		=> array(
				'label'				=> __( 'Site key', 'wp-recaptcha-integration' ),
				'type'				=> 'text',
				'default'			=> '',
				'sanitize_cb'		=> array( $this, 'sanitize_apikey' ),
			),
			'secretkey'	=> array(
				'label'				=> __( 'Secret key', 'wp-recaptcha-integration' ),
				'type'				=> 'text',
				'default'			=> '',
				'sanitize_cb'		=> array( $this, 'sanitize_apikey' ),
			),
			'language'			=> array(
				'label'				=> __( 'Language', 'wp-recaptcha-integration' ),
				'type'				=> 'select', 
				'choices'			=> $this->language_selection,
				'default'			=> '',
				'sanitize_cb'		=> array( $this, 'sanitize_language' ),
			),
			'noscript'		=> array(
				'label'				=> __( 'Noscript Fallback', 'wp-recaptcha-integration' ),
				'description'		=> __( 'Provide a fallback for non javascript capable browsers.','wp-recaptcha-integration' ) . ' ' .
										__( 'Leave this unchecked when your site requires JavaScript anyway.','wp-recaptcha-integration' ),
				'type'				=> 'checkbox',
				'default'			=> false,
				'sanitize_cb'		=> 'boolval',
			),
			'send_ip'		=> array(
				'label'				=> __( 'Send remote IP', 'wp-recaptcha-integration' ),
				'description'		=> __( 'Send your visitors remote IP to Google.','wp-recaptcha-integration' ) . ' ' .
										__( 'Be careful when enabling this option. In some countries this may be prohibited by Data Protection Law.','wp-recaptcha-integration' ),
				'type'				=> 'checkbox',
				'default'			=> false,
				'sanitize_cb'		=> 'boolval',
			),
		);
	}
	

	/**
	 *	Sanitize theme option
	 *
	 *	@param	string	$value
	 *	@return	string	Language code or empty string
	 */
	public function sanitize_type( $value ) {
		if ( array_key_exists( $value, $this->type_selection ) ) {
			return $value;
		}
		return 'image';
	}
	/**
	 *	Sanitize language option
	 *
	 *	@param	string	$value
	 *	@return	string	Language code or empty string
	 */
	public function sanitize_language( $value ) {
		if ( array_key_exists( $value, $this->language_selection ) ) {
			return $value;
		}
		return '';
	}

	/**
	 *	Sanitize theme option
	 *
	 *	@param	string	$value
	 *	@return	string	Language code or empty string
	 */
	public function sanitize_theme( $value ) {
		if ( array_key_exists( $value, $this->theme_selection ) ) {
			return $value;
		}
		return 'light';
	}

	/**
	 *	Sanitize badge option (invisible)
	 *
	 *	@param	string	$value
	 *	@return	string	badge option
	 */
/*
	public function sanitize_badge( $value ) {
		if ( array_key_exists( $value, $this->badge_selection ) ) {
			return $value;
		}
		return 'bottomright';
	}
*/

	/**
	 *	Sanitize size option
	 *
	 *	@param	string	$value
	 *	@return	string	Language code or empty string
	 */
	public function sanitize_size( $value ) {
		if ( array_key_exists( $value, $this->size_selection ) ) {
			return $value;
		}
		return 'normal';
	}

	/**
	 * @inheritdoc
	 */
	public function get_html( $attr = array(), $context = null ) {

		$this->rendered_captchas[ $context->get_id() ]	= $context;
		
		$size = $context->get_option( 'size', $this );

		$default = array(
			'id'			=> 'g-recaptcha-' . $context->get_id(),
			'class'			=> "g-recaptcha",
			'data-sitekey'	=> $this->get_option( 'sitekey' ),
			'data-theme' 	=> $context->get_option( 'theme', $this ),
			'data-size' 	=> $context->get_option( 'size', $this ),
			'data-type' 	=> $context->get_option( 'type', $this ),
//			'data-badge' 	=> $context->get_option( 'badge', $this ),
			'data-callback'	=> 'invisible' === $size ? 'wpGrecaptchaValidate' : null,
		);
		$attr = wp_parse_args( $attr , $default );

		$this->rendered_captchas[ $attr[ 'id' ] ]	= $context;
		
		$attr_str = '';

		foreach ( $attr as $attr_name => $attr_val ) {
			$attr_str .= sprintf( ' %s="%s"' , $attr_name , esc_attr( $attr_val ) );
		}

		$return = "<div {$attr_str}></div>";
		$return .= '<noscript>';
		if ( 'invisible' !== $size && $this->get_option('noscript') ) {
			$return .= '<div style="width: 302px; height: 462px;">' .
							'<div style="width: 302px; height: 422px; position: relative;">' .
								'<div style="width: 302px; height: 422px; position: absolute;">' .
									'<iframe src="https://www.google.com/recaptcha/api/fallback?k='.$attr['data-sitekey'].'"' .
											' frameborder="0" scrolling="no"' .
											' style="width: 302px; height:422px; border-style: none;">' .
									'</iframe>' .
								'</div>' .
							'</div>' .
							'<div style="width: 300px; height: 60px; border-style: none;' .
								' bottom: 12px; left: 25px; margin: 0px; padding: 0px; right: 25px;' .
								' background: #f9f9f9; border: 1px solid #c1c1c1; border-radius: 3px;">' .
								'<textarea id="g-recaptcha-response" name="g-recaptcha-response"' .
											' class="g-recaptcha-response"' .
											' style="width: 250px; height: 40px; border: 1px solid #c1c1c1;' .
													' margin: 10px 25px; padding: 0px; resize: none;" value="">' .
								'</textarea>' .
							'</div>' .
						'</div><br>';
		} else {
			$return .= __('Please enable JavaScript to submit this form.','wp-recaptcha-integration');
		}
		$return .= '<br /></noscript>';
		return $return;
	}

	public function footer_scripts(  ) {
		if ( ! count( $this->rendered_captchas ) ) {
			return;
		}

		$api_param	= array();

		if ( $language = $this->get_option( 'language' ) ) {
			if ( $language === 'WPLANG' ) {
				$language = $this->map_wplocale( get_locale() );
			}
			$api_param[ 'hl' ]	= $language;
		}
		?><script type="text/javascript">
			var elbyid = document.getElementById;

		<?php

		if ( count( $this->rendered_captchas ) > 1 ) {
			$api_param[ 'onload' ]	= 'wpGrecaptchaSetup';

			?>
			function wpGrecaptchaSetup() {
				var cpt = <?php echo json_encode( array_keys( $this->rendered_captchas ) ) ?>, i;
				for ( i=0;i<cpt.length;i++) {
					var el = elbyid( cpt[i] );
					grecaptcha.render( el );
				}
			}
			<?php
		}

/*
		$invisibles = array();

		foreach ( $this->rendered_captchas as $attr_id => $form ) {
			if ( 'invisible' === $form->get_option( 'size', $this ) ) {
				$invisibles[] = $attr_id;
			}
		}

		if ( count( $invisibles ) ) {
			?>
			function wpGrecaptchaValidate(e) {
				// captcha solved, submit form is scheduled for submit
				console.log(e.target);
			}
			(function(){
				var invis = <?php echo json_encode( $invisibles ) ?>, 
					i, el;
				for ( i=0; i<invis.length;i++) {
					var elId = invis[i],
						form, submit;

					el = form = document.getElementById( elId );
					if ( el ) {	
						while ( 'FORM' !== form.tagName ) {
							form = el.parentElement;
							if ( 'BODY' === form.tagName ) {
								form = false;
								break;
							}
						}
						if ( form ) {
//							submit = form.querySelector('[type="submit"]');
							form.addEventListener('submit',function(e){
								// form is scheduled for submit
								grecaptcha.execute( );
								e.preventDefault();
							});
						}
					}
//					console.log(el.parentElement);
					var form = el;
//					while (  )
				}
			})();
			<?php				
		}
*/
		?></script><?php
		$api_url = add_query_arg( $api_param, 'https://www.google.com/recaptcha/api.js' );

		?><script src="<?php echo esc_url( $api_url ) ?>" async defer></script><?php
	}


	/**
	 * @inheritdoc
	 */
	public function check() {

		$user_response = isset( $_REQUEST['g-recaptcha-response'] ) ? $_REQUEST['g-recaptcha-response'] : false;

		if ( $user_response !== false ) {
			if (  ! $this->_last_result ) {
				$api_url		= "https://www.google.com/recaptcha/api/siteverify";
				$api_param		= array(
					'secret'	=> $this->get_option( 'secretkey' ),
					'response'	=> $user_response,
				);
				if ( $this->get_option( 'send_ip' ) ) {
					$api_param['remoteip']	= $_SERVER['REMOTE_ADDR'];
				}

				$response = wp_remote_get( add_query_arg( $api_param, $api_url ) );

				if ( ! is_wp_error($response) ) {
					$response_data = wp_remote_retrieve_body( $response );
					$this->_last_result = json_decode($response_data);
				} else {
					$this->_last_result = (object) array( 'success' => false , 'wp_error' => $response );
				}
			}
			do_action( 'wp_recaptcha_checked' , $this->_last_result->success );
			return $this->_last_result->success;
		}
		return false;
	}

	/**
	 *	Override method
	 *	Get recaptcha language code that matches input WP-Locale
	 *	Sometimes WP locales differ from google language codes. 
	 *	
	 *	@param	$wp_lang	string	language code (WP locale)
	 *	@return	string		recaptcha language code if language available, empty string otherwise
	 */
	public function map_wplocale( $wp_locale ) {
		/*
		 	Map WP locale to recatcha locale.
		*/
		$mapping = array(
			'es_MX' => 'es-419',
			'es_PE' => 'es-419',
			'es_CL' => 'es-419',
			'he_IL' => 'iw', // hebrew > iwrit
		);
		if ( isset( $mapping[$wp_locale] ) ) {
			return $mapping[$wp_locale];
		}

		// form 
		$lang = str_replace( '_' , '-' , $wp_locale );
		
		// direct hit
		if ( isset($this->supported_languages[$lang]) ) {
			return $lang;
		}
		
		// remove countrycode, try again
		$lang = preg_replace('/-(.*)$/','',$lang);
		if ( isset( $this->supported_languages[$lang] ) ) {
			return $lang;
		}
		
		// lang does not exist.
		return '';
	}

}