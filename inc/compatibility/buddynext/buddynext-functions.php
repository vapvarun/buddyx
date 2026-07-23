<?php
/**
 * Support For BuddyNext
 *
 * BuddyNext is the next-generation community engine (the successor to, and
 * runtime-exclusive with, BuddyPress). template-parts/header/buddypress-profile.php
 * already renders BuddyNext's own header user section (bell, messages,
 * avatar dropdown) when the plugin is active; this file closes the two gaps
 * that are left over once that section is in place:
 *
 *   1. Feeds the site's assigned "User Menu" (`user_menu` nav location) into
 *      the BuddyNext profile dropdown, so site owners present a real,
 *      admin-controlled Manage menu instead of only BuddyNext's own default
 *      quick links. Log Out is always kept by BuddyNext.
 *   2. Points Log in / Register / Forgot password links at BuddyNext's single
 *      auth-hub page instead of letting them fall through to wp-login.php.
 *
 * Loaded from functions.php only when BUDDYNEXT_VERSION is defined and
 * BuddyPress is inactive (the two are mutually exclusive at runtime).
 *
 * @package buddyx
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'buddyx_buddynext_user_profile_menu_items' ) ) {
	/**
	 * Read the site's assigned "User Menu" nav menu as a flat list of
	 * BuddyNext header-dropdown rows.
	 *
	 * Only top-level items are used (a header dropdown is single-level). Each
	 * row is `[ 'label' => string, 'url' => string, 'icon' => '' ]`; items carry
	 * no BuddyNext icon (the dropdown reserves the icon column so labels still
	 * align). Returns an empty array when no menu is assigned or it is empty -
	 * the caller then keeps BuddyNext's own default quick links.
	 *
	 * @return array<int,array{label:string,url:string,icon:string}>
	 */
	function buddyx_buddynext_user_profile_menu_items() {
		$locations = get_nav_menu_locations();
		if ( empty( $locations['user_menu'] ) ) {
			return array();
		}

		$menu_items = wp_get_nav_menu_items( (int) $locations['user_menu'] );
		if ( empty( $menu_items ) ) {
			return array();
		}

		$rows = array();
		foreach ( $menu_items as $item ) {
			// Top-level only - flatten nothing, skip child items.
			if ( ! empty( $item->menu_item_parent ) ) {
				continue;
			}
			$label = trim( (string) $item->title );
			$url   = trim( (string) $item->url );
			if ( '' === $label || '' === $url ) {
				continue;
			}
			$rows[] = array(
				'label' => $label,
				'url'   => $url,
				'icon'  => '',
			);
		}

		return $rows;
	}
}

if ( ! function_exists( 'buddyx_buddynext_filter_user_menu_links' ) ) {
	/**
	 * Present the site's "User Menu" inside the BuddyNext dropdown.
	 *
	 * When the admin has assigned a menu to the theme's "User Menu" location,
	 * those items become the dropdown's links - exactly the menu the site
	 * owner controls. Otherwise BuddyNext's own quick links are kept. Log Out
	 * is appended by BuddyNext regardless, so it is never lost.
	 *
	 * @param array $links   BuddyNext default quick links.
	 * @param int   $user_id Member ID (unused; the menu is global).
	 * @return array Filtered links.
	 */
	function buddyx_buddynext_filter_user_menu_links( $links, $user_id ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found -- signature required by the filter.
		$menu = buddyx_buddynext_user_profile_menu_items();

		return ! empty( $menu ) ? $menu : $links;
	}
	add_filter( 'buddynext_header_user_menu_links', 'buddyx_buddynext_filter_user_menu_links', 10, 2 );
}

/**
 * --------------------------------------------------------------------------
 * Auth URL routing - point Log in / Register / Forgot password at BuddyNext
 * --------------------------------------------------------------------------
 *
 * BuddyX models auth as a mapped Login page and a mapped Registration page
 * (`buddyx_login_page` / `buddyx_registration_page` theme mods), falling back
 * to wp_login_url() / wp_registration_url() / wp_lostpassword_url() when no
 * page is mapped. BuddyNext instead serves a SINGLE auth hub page where
 * signup and reset-password are endpoints on that one page, not separate
 * published pages. BuddyNext does not filter WordPress core's login_url /
 * register_url / lostpassword_url, so left untouched the fallback bypasses
 * the BuddyNext auth hub entirely and lands on wp-login.php / wp-signup.php.
 *
 * These filters close that gap. Because every call site in the theme
 * (header, BuddyPress registration notice) resolves its fallback URL through
 * wp_login_url() / wp_registration_url() / wp_lostpassword_url(), a single set
 * of core filters routes all of them to BuddyNext at once. URLs are read
 * straight from BuddyNext's public PageRouter API (no hard-coded slugs), so a
 * custom auth slug or the buddynext_*_url filters are honoured.
 *
 * A mapped Login/Registration page still wins over this fallback by design:
 * the templates resolve the mapped page before calling wp_login_url() /
 * wp_registration_url(), so this filter is simply never reached in that case.
 *
 * Scope is front-end only: wp-admin login, wp-login.php itself, AJAX, REST and
 * cron are left on WordPress core so administrator sign-in and the
 * reset-password-from-email flow keep working unchanged.
 */

if ( ! function_exists( 'buddyx_buddynext_is_frontend_auth_context' ) ) {
	/**
	 * Whether the current request is a front-end context where the theme's
	 * auth links should resolve to the BuddyNext auth hub.
	 *
	 * @return bool
	 */
	function buddyx_buddynext_is_frontend_auth_context() {
		if ( is_admin() ) {
			return false;
		}
		if ( isset( $GLOBALS['pagenow'] ) && 'wp-login.php' === $GLOBALS['pagenow'] ) {
			return false;
		}
		if ( wp_doing_ajax() || wp_doing_cron() ) {
			return false;
		}
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( 'buddyx_buddynext_resolve_auth_url' ) ) {
	/**
	 * Resolve a BuddyNext auth URL from its public PageRouter API.
	 *
	 * @param string $type One of 'login', 'signup', 'reset'.
	 * @return string Resolved URL, or '' when BuddyNext is unavailable.
	 */
	function buddyx_buddynext_resolve_auth_url( $type ) {
		if ( ! class_exists( '\BuddyNext\Core\PageRouter' ) ) {
			return '';
		}

		switch ( $type ) {
			case 'signup':
				$url = \BuddyNext\Core\PageRouter::signup_url();
				break;
			case 'reset':
				$url = \BuddyNext\Core\PageRouter::reset_url();
				break;
			case 'login':
			default:
				$url = \BuddyNext\Core\PageRouter::auth_url();
				break;
		}

		return is_string( $url ) ? $url : '';
	}
}

if ( ! function_exists( 'buddyx_buddynext_filter_login_url' ) ) {
	/**
	 * Route the theme's login URL fallback to the BuddyNext auth hub on the
	 * front end.
	 *
	 * @param string $login_url The default login URL.
	 * @param string $redirect  Path to redirect to on login, if any.
	 * @return string
	 */
	function buddyx_buddynext_filter_login_url( $login_url, $redirect ) {
		if ( ! buddyx_buddynext_is_frontend_auth_context() ) {
			return $login_url;
		}

		$bn_url = buddyx_buddynext_resolve_auth_url( 'login' );
		if ( '' === $bn_url ) {
			return $login_url;
		}

		if ( ! empty( $redirect ) ) {
			$bn_url = add_query_arg( 'redirect_to', rawurlencode( $redirect ), $bn_url );
		}

		return $bn_url;
	}
	add_filter( 'login_url', 'buddyx_buddynext_filter_login_url', 20, 2 );
}

if ( ! function_exists( 'buddyx_buddynext_filter_register_url' ) ) {
	/**
	 * Route the theme's registration URL fallback to the BuddyNext signup
	 * endpoint on the front end.
	 *
	 * @param string $register_url The default registration URL.
	 * @return string
	 */
	function buddyx_buddynext_filter_register_url( $register_url ) {
		if ( ! buddyx_buddynext_is_frontend_auth_context() ) {
			return $register_url;
		}

		$bn_url = buddyx_buddynext_resolve_auth_url( 'signup' );

		return '' !== $bn_url ? $bn_url : $register_url;
	}
	add_filter( 'register_url', 'buddyx_buddynext_filter_register_url', 20, 1 );
}

if ( ! function_exists( 'buddyx_buddynext_filter_lostpassword_url' ) ) {
	/**
	 * Route the theme's lost-password URL fallback to the BuddyNext reset
	 * endpoint on the front end.
	 *
	 * @param string $lostpassword_url The default lost-password URL.
	 * @param string $redirect         Path to redirect to on reset, if any.
	 * @return string
	 */
	function buddyx_buddynext_filter_lostpassword_url( $lostpassword_url, $redirect ) {
		if ( ! buddyx_buddynext_is_frontend_auth_context() ) {
			return $lostpassword_url;
		}

		$bn_url = buddyx_buddynext_resolve_auth_url( 'reset' );
		if ( '' === $bn_url ) {
			return $lostpassword_url;
		}

		if ( ! empty( $redirect ) ) {
			$bn_url = add_query_arg( 'redirect_to', rawurlencode( $redirect ), $bn_url );
		}

		return $bn_url;
	}
	add_filter( 'lostpassword_url', 'buddyx_buddynext_filter_lostpassword_url', 20, 2 );
}
