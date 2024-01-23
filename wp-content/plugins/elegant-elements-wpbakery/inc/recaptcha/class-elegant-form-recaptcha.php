<?php
/**
 * A proxy class for recaptche.
 *
 * @package   Elegant Elements
 * @category  Core
 * @since 1.0
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

/**
 * A proxy class for recaptcha.
 *
 * @since 1.0
 */
class ElegantForms_ReCaptcha {

	/**
	 * The ReCaptcha object.
	 *
	 * @access public
	 * @since 1.0
	 * @var object ReCaptcha
	 */
	public $recaptcha;

	/**
	 * Class constructor.
	 *
	 * @param string $secret         The secret that will be passed-on to ReCaptcha.
	 * @param null   $request_method Not currently used.
	 */
	public function __construct( $secret, $request_method = null ) {

		if ( ! ini_get( 'allow_url_fopen' ) ) {
			$this->recaptcha = new \ReCaptcha\ReCaptcha( $secret, new \ReCaptcha\RequestMethod\SocketPost() );
		} else {
			$this->recaptcha = new \ReCaptcha\ReCaptcha( $secret );
		}
	}
}
