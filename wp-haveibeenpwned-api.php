<?php
/**
 * WP Have I Been Pwned API
 *
 * @package WP-Have I Been Pwned-API
 */

/*
* Plugin Name: WP Have I Been Pwned API
* Plugin URI: https://github.com/wp-api-libraries/wp-haveibeenpwned-api
* Description: Perform API requests to Have I Been Pwned in WordPress.
* Author: WP API Libraries
* Version: 1.0.0
* Author URI: https://wp-api-libraries.com
* GitHub Plugin URI: https://github.com/wp-api-libraries/wp-haveibeenpwned-api
* GitHub Branch: master
*/

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'HaveIBeenPwnedAPI' ) ) {

	/**
	 * HaveIBeenPwnedAPIclass.
	 */
	class HaveIBeenPwnedAPI {

		/**
		 * URL to the API.
		 *
		 * @var string
		 */
		private $base_uri = 'https://haveibeenpwned.com/api/v2/';

		public function __construct() {

		}

		/**
		 * Fetch the request from the API.
		 *
		 * @access private
		 * @param mixed $request Request URL.
		 * @return $body Body.
		 */
		private function fetch( $request ) {
			$request .= '?api_token=' .static::$api_token;
			$response = wp_remote_get( $request );
			$code = wp_remote_retrieve_response_code( $response );
			if ( 200 !== $code ) {
				return new WP_Error( 'response-error', sprintf( __( 'Server response code: %d', 'text-domain' ), $code ) );
			}
			$body = wp_remote_retrieve_body( $response );
			return json_decode( $body );
		}

		/**
		 * get_all_breaches function.
		 *
		 * @access public
		 * @return void
		 */
		public function get_all_breaches() {

			$request = $this->base_uri . 'breaches';
			return $this->fetch( $request );

		}

		/**
		 * Get Account Breaches.
		 *
		 * @access public
		 * @param mixed $account
		 * @return void
		 */
		public function get_acct_breaches( $account ) {

			$request = $this->base_uri . 'breachedaccount/' . $account;
			return $this->fetch( $request );

		}

		/**
		 * get_acct_pastes function.
		 *
		 * @access public
		 * @param mixed $account
		 * @return void
		 */
		public function get_acct_pastes( $account ) {
			$request = $this->base_uri . 'pasteaccount/' . $account;
			return $this->fetch( $request );
		}

		/**
		 * get_breach function.
		 *
		 * @access public
		 * @param mixed $breach_name
		 * @return void
		 */
		public function get_breach( $breach_name ) {

			$request = $this->base_uri . 'breach/' . $breach_name;
			return $this->fetch( $request );
		}

		/**
		 * get_data_classes function.
		 *
		 * @access public
		 * @return void
		 */
		public function get_data_classes() {
			$request = $this->base_uri . 'dataclasses';
			return $this->fetch( $request );
		}

		/**
		 * Response code message.
		 *
		 * @param  [String] $code : Response code to get message from.
		 * @return [String]       : Message corresponding to response code sent in.
		 */
		public function response_code_msg( $code = '' ) {
			switch ( $code ) {
				case 200:
					$msg = __( 'OK.', $this->textdomain );
					break;
				case 400:
					$msg = __( 'Bad Request.', $this->textdomain );
					break;
				case 403:
					$msg = __( 'Forbidden — no user agent has been specified in the request.', $this->textdomain );
					break;
				case 404:
					$msg = __( 'Not found — the account could not be found and has therefore not been pwned.', $this->textdomain );
					break;
				case 429:
					$msg = __( 'Too many requests — the rate limit has been exceeded.', $this->textdomain );
					break;
			}
			return $msg;
		}

	}
}
