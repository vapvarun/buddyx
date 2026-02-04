<?php
/**
 * Dev-only helpers: Inject Tiny LiveReload client when browsing through the modern dev proxy.
 * Primary signal is the X-BUDDYX-DEV request header set by the proxy.
 * As a fallback, detect proxied requests via X-Forwarded-Host pointing to localhost:3000.
 * This does not rely on WP_DEBUG.
 *
 * This file should live under optional/ so it will not be included in production bundles.
 */

if ( ! function_exists( 'buddyx_is_dev_proxy_request' ) ) {
	/**
	 * Determines if the current request is a development proxy request.
	 *
	 * Checks for:
	 * - X-BUDDYX-DEV header (set by dev proxy)
	 * - X-Forwarded-Host containing localhost or 127.0.0.1 (any port)
	 * - Cookie buddyx_dev=1
	 *
	 * @return bool
	 */
	function buddyx_is_dev_proxy_request(): bool {
		$has_custom_header = ! empty( $_SERVER['HTTP_X_BUDDYX_DEV'] );
		$xfh               = isset( $_SERVER['HTTP_X_FORWARDED_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_HOST'] ) ) : '';
		// Accept any localhost forwarded host regardless of port (supports custom devPort).
		$is_localhost_forward = ( false !== stripos( $xfh, 'localhost' ) ) || ( false !== stripos( $xfh, '127.0.0.1' ) );
		$has_cookie           = isset( $_COOKIE['buddyx_dev'] ) && '1' === $_COOKIE['buddyx_dev'];

		return $has_custom_header || $is_localhost_forward || $has_cookie;
	}
}

add_action(
	'wp_head',
	function () {
		if ( buddyx_is_dev_proxy_request() ) {
			wp_enqueue_script( 'buddyx-livereload', '//localhost:35729/livereload.js?snipver=1', array(), null, false );
		}
	}
);
add_action(
	'admin_enqueue_scripts',
	function () {
		if ( buddyx_is_dev_proxy_request() ) {
			wp_enqueue_script( 'buddyx-admin-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, false );
		}
	}
);
