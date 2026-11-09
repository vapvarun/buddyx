<?php
/**
 * Shims for recent WordPress functions
 *
 * @package buddyx
 */

/**
 * Adds backwards compatibility for buddyx_wp_body_open() introduced with WordPress 5.2
 */
if ( ! function_exists( 'buddyx_wp_body_open' ) ) {
	/**
	 * Run the buddyx_wp_body_open action.
	 *
	 * @return void
	 */
	function buddyx_wp_body_open() {
		/**
		 * Fires right after the opening <body> tag, also on WordPress versions without wp_body_open().
		 */
		do_action( 'buddyx_wp_body_open' );
	}
}
