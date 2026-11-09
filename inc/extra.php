<?php
/**
 * The `buddyx()` extra.
 *
 * @package buddyx
 */

if ( ! function_exists( 'buddyx_is_truthy' ) ) {
	/**
	 * Boolean-equivalence helper for theme_mod values.
	 *
	 * Treats `true / 1 / '1' / 'on' / 'yes' / 'true' / 'enable'` as truthy
	 * and `false / 0 / '0' / '' / 'off' / 'no' / 'false' / 'disable' / null`
	 * as falsy. Any other value falls back to PHP loose-truthiness.
	 *
	 * Required because pre-5.1.0 customers carry literal 'on'/'off' string
	 * values for switch theme_mods (Kirki preserved the choice keys
	 * directly), and PHP gotchas mean `(int) 'on' === 0` and `(bool) 'off'
	 * === true` — both of which silently invert the customer's intent.
	 * WordPress's own `wp_validate_boolean()` is unsuitable here because
	 * it only recognizes 'false'/'0' as falsy (so `wp_validate_boolean('off')
	 * === true`). This helper mirrors the values_equal() logic added to
	 * Active_Callback in commit b4cccf6 so render-time and condition-time
	 * agree on the same truthiness contract.
	 *
	 * @param mixed $value Raw theme_mod value (any scalar / null).
	 * @return bool
	 */
	function buddyx_is_truthy( $value ): bool {
		if ( is_bool( $value ) ) {
			return $value;
		}
		if ( is_int( $value ) ) {
			return 0 !== $value;
		}
		if ( null === $value ) {
			return false;
		}
		if ( is_string( $value ) ) {
			$normalized = strtolower( trim( $value ) );
			if ( in_array( $normalized, array( '1', 'on', 'yes', 'true', 'enable' ), true ) ) {
				return true;
			}
			if ( in_array( $normalized, array( '0', '', 'off', 'no', 'false', 'disable' ), true ) ) {
				return false;
			}
		}
		return (bool) $value;
	}
}

if ( ! function_exists( 'buddyx_get_icon' ) ) {
	/**
	 * Inline SVG markup for one of the theme's own icons.
	 *
	 * The theme draws its icons itself so they never depend on Font Awesome,
	 * which the site owner can turn off (Customizer > Site Performance). The
	 * paths are Font Awesome Free 6.6.0 (CC BY 4.0), so the icons look the same
	 * as the font glyphs they replace. The width keeps the glyph's own advance
	 * (viewBox width / 512), sized by font-size like the font was; the
	 * `.bx-icon` rule in assets/css/src/_icons.css sets the height and alignment.
	 *
	 * @param string $name Icon name: bell, envelope, user, sign-in, bars, search,
	 *                     shopping-cart, camera, link or play.
	 * @return string SVG markup, or an empty string for an unknown name.
	 */
	function buddyx_get_icon( $name ) {
		static $icons = array(
			'bell'          => array( 448, 'M224 0c-17.7 0-32 14.3-32 32l0 19.2C119 66 64 130.6 64 208l0 18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416l384 0c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8l0-18.8c0-77.4-55-142-128-156.8L256 32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3l-64 0-64 0c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z' ),
			'envelope'      => array( 512, 'M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48L48 64zM0 176L0 384c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-208L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z' ),
			'user'          => array( 448, 'M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z' ),
			'sign-in'       => array( 512, 'M352 96l64 0c17.7 0 32 14.3 32 32l0 256c0 17.7-14.3 32-32 32l-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l64 0c53 0 96-43 96-96l0-256c0-53-43-96-96-96l-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32zm-9.4 182.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L242.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z' ),
			'bars'          => array( 448, 'M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z' ),
			'search'        => array( 512, 'M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z' ),
			'shopping-cart' => array( 576, 'M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z' ),
			'camera'        => array( 512, 'M149.1 64.8L138.7 96 64 96C28.7 96 0 124.7 0 160L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64l-74.7 0L362.9 64.8C356.4 45.2 338.1 32 317.4 32L194.6 32c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z' ),
			'link'          => array( 640, 'M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z' ),
			'play'          => array( 384, 'M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80L0 432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z' ),
		);

		if ( ! isset( $icons[ $name ] ) ) {
			return '';
		}

		list( $width, $path ) = $icons[ $name ];

		return sprintf(
			'<svg class="bx-icon bx-icon-%1$s" aria-hidden="true" focusable="false" width="%2$sem" height="1em" viewBox="0 0 %3$d 512"><path fill="currentColor" d="%4$s"/></svg>',
			esc_attr( $name ),
			esc_attr( (string) round( $width / 512, 4 ) ),
			(int) $width,
			esc_attr( $path )
		);
	}
}

// Content wrapper.
if ( ! function_exists( 'buddyx_content_top' ) ) {
	/**
	 * Open the site content wrapper.
	 *
	 * @return void
	 */
	function buddyx_content_top() {
		?>
		<div class="site-wrapper">
		<?php
	}
}

add_action( 'buddyx_before_content', 'buddyx_content_top' );

if ( ! function_exists( 'buddyx_content_bottom' ) ) {
	/**
	 * Close the site content wrapper.
	 *
	 * @return void
	 */
	function buddyx_content_bottom() {
		?>
		</div>
		<?php
	}
}

add_action( 'buddyx_after_content', 'buddyx_content_bottom' );

// Site Sub Header.
if ( ! function_exists( 'buddyx_sub_header' ) ) {
	add_action( 'buddyx_sub_header', 'buddyx_sub_header' );

	/**
	 * Render the site sub header: page title area and breadcrumbs.
	 *
	 * @return void
	 */
	function buddyx_sub_header() {
		global $post;
		if ( is_front_page() ) {
			return;
		}
		?>
		<div class="site-sub-header">
			<div class="container">
				<?php
				// Handle search results separately.
				if ( is_search() ) {
					get_template_part( 'template-parts/content/page_header' );
				} elseif ( get_post_type() === 'post' || is_single() || is_archive( 'post-type-archive-forum' ) || is_archive( 'post-type-archive-topic' ) || ( is_archive( 'post-type-archive-ideas' ) && ( function_exists( 'is_shop' ) && ! is_shop() ) ) ) {
					get_template_part( 'template-parts/content/page_header' );
				} elseif ( get_post_type() === 'page' || is_singular() ) {
					get_template_part( 'template-parts/content/entry_title', get_post_type() );
				}

				// Breadcrumbs for all cases. `buddyx_is_truthy()` handles
				// pre-5.1.0 'on'/'off' strings; `! empty('off')` would
				// otherwise treat the disabled toggle as enabled.
				/**
				 * Fires before the breadcrumb trail.
				 */
				do_action( 'buddyx_before_breadcrumb' );
				if ( buddyx_is_truthy( get_theme_mod( 'site_breadcrumbs', buddyx_defaults( 'site-breadcrumbs' ) ) ) ) {
					buddyx_the_breadcrumb();
				}
				/**
				 * Fires after the breadcrumb trail.
				 */
				do_action( 'buddyx_after_breadcrumb' );
				?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'buddyx_bbp_no_breadcrumb' ) ) {
	/**
	 * One breadcrumb trail on forum pages: bbPress hides its own trail while the
	 * theme breadcrumb (which already follows the forum hierarchy) is on.
	 *
	 * @param bool $hide Whether bbPress should skip its breadcrumb.
	 * @return bool
	 */
	function buddyx_bbp_no_breadcrumb( $hide ) {
		return $hide || buddyx_is_truthy( get_theme_mod( 'site_breadcrumbs', buddyx_defaults( 'site-breadcrumbs' ) ) );
	}
}
add_filter( 'bbp_no_breadcrumb', 'buddyx_bbp_no_breadcrumb' );

if ( ! function_exists( 'buddyx_edd_button_color_class' ) ) {
	/**
	 * Easy Digital Downloads buttons use the theme button until the owner picks an
	 * EDD button colour (Downloads > Settings > Styles). EDD falls back to blue when
	 * the option was never saved; a saved choice, including "Inherit", is kept.
	 *
	 * @param string $color_class EDD button colour class.
	 * @return string
	 */
	function buddyx_edd_button_color_class( $color_class ) {
		return false === edd_get_option( 'checkout_color', false ) ? '' : $color_class;
	}
}
add_filter( 'edd_button_color_class', 'buddyx_edd_button_color_class' );

if ( ! function_exists( 'buddyx_tutor_follow_color_mode' ) ) {
	/**
	 * Tutor LMS ships its own light/dark palette (data-tutor-theme). While the member
	 * has not picked a Tutor appearance ("system", Tutor's default), it follows the
	 * theme's colour mode instead of the operating system, so the site has one dark
	 * mode switch. The head bootstrap mirrors data-bx-mode into data-tutor-theme.
	 *
	 * @param string $output Attributes printed on <html>.
	 * @return string
	 */
	function buddyx_tutor_follow_color_mode( $output ) {
		if ( false === strpos( $output, 'data-tutor-theme="system"' ) ) {
			return $output;
		}
		return str_replace( 'data-tutor-theme="system"', 'data-tutor-theme="light" data-bx-mode-sync="data-tutor-theme"', $output );
	}
}
add_filter( 'language_attributes', 'buddyx_tutor_follow_color_mode', 20 );

if ( ! function_exists( 'buddyx_learndash_default_colors' ) ) {
	/**
	 * Whether LearnDash still uses its default colours (the owner has not picked any
	 * in LearnDash > Settings > General > Design).
	 *
	 * @return bool
	 */
	function buddyx_learndash_default_colors() {
		if ( ! defined( 'LD_30_COLOR_PRIMARY' ) || ! class_exists( 'LearnDash_Settings_Section' ) ) {
			return false;
		}
		foreach ( array(
			'color_primary'   => LD_30_COLOR_PRIMARY,
			'color_secondary' => LD_30_COLOR_SECONDARY,
		) as $key => $default ) {
			$value = (string) LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Theme_LD30', $key );
			if ( '' !== $value && 0 !== strcasecmp( $value, $default ) ) {
				return false;
			}
		}
		return true;
	}
}

if ( ! function_exists( 'buddyx_learndash_colors' ) ) {
	/**
	 * LearnDash uses the Theme Color while the owner has not picked LearnDash colours.
	 * LearnDash then prints its own colour map and button text from it.
	 *
	 * @param array $colors LearnDash colours (primary, secondary, tertiary, primary_button_text).
	 * @return array
	 */
	function buddyx_learndash_colors( $colors ) {
		if ( ! buddyx_learndash_default_colors() ) {
			return $colors;
		}
		$brand                         = \BuddyX\Buddyx\Tokens\Component::light_accent();
		$colors['primary']             = $brand;
		$colors['secondary']           = $brand;
		$colors['primary_button_text'] = class_exists( 'LearnDash\\Core\\Utilities\\Color' ) ? \LearnDash\Core\Utilities\Color::pick_foreground_color( $brand, '#ffffff', '#000000' ) : '#ffffff';
		return $colors;
	}
}
add_filter( 'learndash_30_custom_colors', 'buddyx_learndash_colors' );

if ( ! function_exists( 'buddyx_learndash_body_class' ) ) {
	/**
	 * Marks pages where LearnDash runs on the theme's colours, so dark mode can
	 * lighten LearnDash's brand-coloured text without touching owner-picked colours.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	function buddyx_learndash_body_class( $classes ) {
		if ( buddyx_learndash_default_colors() ) {
			$classes[] = 'bx-ld-theme-colors';
		}
		return $classes;
	}
}
add_filter( 'body_class', 'buddyx_learndash_body_class' );

if ( ! function_exists( 'buddyx_member_url' ) ) {
	/**
	 * Profile URL of a member: the BuddyPress profile (BuddyPress 12+ API first, then
	 * the older one BuddyBoss still uses), else the author archive.
	 *
	 * @param int $user_id User ID.
	 * @return string
	 */
	function buddyx_member_url( $user_id ) {
		if ( function_exists( 'bp_members_get_user_url' ) ) {
			return bp_members_get_user_url( $user_id );
		}
		if ( function_exists( 'bp_core_get_user_domain' ) ) {
			return bp_core_get_user_domain( $user_id );
		}
		return get_author_posts_url( $user_id );
	}
}

if ( ! function_exists( 'buddyx_archive_title_prefix' ) ) {
	/**
	 * Drop the "Archives:" prefix from post type archive titles ("Forums", not "Archives: Forums").
	 * Category, tag and author prefixes stay: they tell the visitor what they are browsing.
	 *
	 * @param string $prefix Archive title prefix.
	 * @return string
	 */
	function buddyx_archive_title_prefix( $prefix ) {
		return is_post_type_archive() ? '' : $prefix;
	}
}
add_filter( 'get_the_archive_title_prefix', 'buddyx_archive_title_prefix' );

/*
 * BREADCRUMBS
 */
if ( ! function_exists( 'buddyx_the_breadcrumb' ) ) {
	/**
	 * Displays breadcrumb navigation: Yoast SEO breadcrumbs when enabled, else the theme trail.
	 *
	 * Not cached: the trail is cheap to build and depends on the whole query, not just the
	 * first post, so a per-post cache served one page's trail on other pages.
	 *
	 * @return void
	 */
	function buddyx_the_breadcrumb() {
		ob_start();

		$wpseo_titles = get_option( 'wpseo_titles' );
		if ( function_exists( 'yoast_breadcrumb' ) && isset( $wpseo_titles['breadcrumbs-enable'] ) && 1 === (int) $wpseo_titles['breadcrumbs-enable'] ) {
			yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
		} else {
			// buddyx_get_breadcrumb() already wraps the trail in .buddyx-breadcrumbs.
			buddyx_get_breadcrumb();
		}

		echo wp_kses_post( ob_get_clean() );
	}
}

// Site Loader.
if ( ! function_exists( 'buddyx_site_loader' ) ) {
	/**
	 * Renders the site loader overlay shown while the page is initializing.
	 *
	 * Supports 5 animation types (dots / spinner / pulse / bars / logo) selected
	 * via the customizer. Adds role=status + aria-live for screen readers, and
	 * respects prefers-reduced-motion via CSS in assets/css/loaders.css.
	 */
	function buddyx_site_loader() {
		// `buddyx_is_truthy()` provides consistent boolean handling across the
		// theme; this replaces an earlier defensive multi-check that manually
		// listed '0' / 'off' string variants.
		if ( ! buddyx_is_truthy( get_theme_mod( 'site_loader', buddyx_defaults( 'site-loader' ) ) ) ) {
			return;
		}

		$type  = (string) get_theme_mod( 'site_loader_type', 'dots' );
		$text  = (string) get_theme_mod( 'site_loader_text', __( 'Loading', 'buddyx' ) );
		$logo  = (string) get_theme_mod( 'site_loader_logo', '' );
		$speed = (float) get_theme_mod( 'site_loader_speed', 1.5 );
		// Clamp to safe range.
		$speed = max( 0.3, min( 5.0, $speed ) );

		$valid_types = array( 'dots', 'spinner', 'pulse', 'bars', 'logo' );
		if ( ! in_array( $type, $valid_types, true ) ) {
			$type = 'dots';
		}

		$style_attr = sprintf( '--bx-loader-speed:%ss;', $speed );
		?>
		<div class="site-loader site-loader--<?php echo esc_attr( $type ); ?>"
			role="status"
			aria-live="polite"
			aria-label="<?php echo esc_attr( $text ); ?>"
			style="<?php echo esc_attr( $style_attr ); ?>">
			<div class="loader-inner">
				<?php if ( 'logo' === $type ) : ?>
					<?php if ( $logo ) : ?>
						<img class="site-loader__logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $text ); ?>" />
					<?php elseif ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<span class="site-loader__text"><?php echo esc_html( $text ); ?></span>
					<?php endif; ?>
				<?php elseif ( 'bars' === $type ) : ?>
					<span class="bar"></span><span class="bar"></span><span class="bar"></span><span class="bar"></span>
				<?php elseif ( 'spinner' === $type ) : ?>
					<span class="spinner"></span>
				<?php elseif ( 'pulse' === $type ) : ?>
					<span class="pulse"></span>
				<?php else : ?>
					<span class="dot"></span><span class="dot dot1"></span><span class="dot dot2"></span><span class="dot dot3"></span><span class="dot dot4"></span>
				<?php endif; ?>
			</div>
			<span class="screen-reader-text"><?php echo esc_html( $text ); ?></span>
		</div>
		<?php
	}
}

// Site Search and WooCommerce Cart Icon.
if ( ! function_exists( 'buddyx_site_menu_icon' ) ) {
	/**
	 * Renders site menu icons, including a search icon and a WooCommerce cart icon.
	 * The function checks the theme settings to determine if the icons should be displayed.
	 */
	function buddyx_site_menu_icon() {
		// Get the settings for search and cart icons from the theme customizer.
		// `buddyx_is_truthy()` correctly handles the pre-5.1.0 'on'/'off'
		// string values (Kirki preserved choice keys directly). PHP would
		// otherwise silently flip the intent: `(int) 'on' === 0` and
		// `(bool) 'off' === true`. WP core's `wp_validate_boolean()` is
		// unsuitable because `wp_validate_boolean('off') === true`.
		$searchicon        = buddyx_is_truthy( get_theme_mod( 'site_search', buddyx_defaults( 'site-search' ) ) );
		$carticon          = buddyx_is_truthy( get_theme_mod( 'site_cart', buddyx_defaults( 'site-cart' ) ) );
		$color_toggle_show = buddyx_is_truthy( get_theme_mod( 'site_color_mode_toggle_show', 'on' ) );

		// Wrapper renders if any of these icons (search, cart, color-mode toggle) are enabled.
		if ( ! empty( $searchicon ) || ! empty( $carticon ) || $color_toggle_show ) :
			?>
			<div class="menu-icons-wrapper">
				<?php
				// Render the search icon if enabled.
				if ( ! empty( $searchicon ) && function_exists( 'buddyx_use_buddynext_search' ) && buddyx_use_buddynext_search() ) :
					// BuddyNext active: the icon opens the community search palette (a real
					// link to the community search page where the palette script is absent).
					?>
					<div class="search search--buddynext">
						<?php buddynext_header_search(); ?>
					</div>
					<?php
				elseif ( ! empty( $searchicon ) ) :
					/**
					 * Filters the data attributes printed on the sliding search wrapper.
					 *
					 * @param string $attrs Attrs.
					 */
					?>
					<div class="search" <?php echo apply_filters( 'buddyx_search_slide_toggle_data_attrs', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Filter returns AMP data attributes; empty by default. ?>>
						<a href="#" id="overlay-search" class="search-icon" title="<?php esc_attr_e( 'Search', 'buddyx' ); ?>" aria-label="Toggle search overlay">
							<?php echo buddyx_get_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG from the theme icon map. ?>
						</a>
						<?php
						/**
						 * Filters the data attributes printed on the search field container.
						 *
						 * @param string $attrs Attrs.
						 */
						?>
						<div class="top-menu-search-container" <?php echo apply_filters( 'buddyx_search_field_toggle_data_attrs', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Filter returns AMP data attributes; empty by default. ?>>
							<?php get_search_form(); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php
				// Render the cart icon if enabled and WooCommerce is active.
				if ( ! empty( $carticon ) && function_exists( 'is_woocommerce' ) ) :
					buddyx_render_cart_icon();
				endif;

				// Color-mode toggle (Light / Dark / Auto). Component decides
				// whether to render based on customizer settings.
				/**
				 * Fires inside the desktop menu-icons area (search / cart / color-mode toggle).
				 */
				do_action( 'buddyx_header_actions' );
				?>
			</div>
			<?php
		endif;
	}
}

if ( ! function_exists( 'buddyx_footer_default_copyright_text' ) ) {
	/**
	 * Canonical default for the footer copyright text.
	 *
	 * Single source of truth, shared by the Customizer field
	 * (Footer_Fields.php) and the render site below, so the two cannot drift.
	 *
	 * @return string The default copyright string, with [tokens] unexpanded.
	 */
	function buddyx_footer_default_copyright_text() {
		return __( 'Copyright &copy; [current_year] [site_title] | Powered by [theme_author]', 'buddyx' );
	}
}

/**
 * Function Footer Custom Text
 */
if ( ! function_exists( 'buddyx_footer_custom_text' ) ) {
	/**
	 * Retrieves and formats the custom footer text based on theme settings.
	 * This function checks if a custom copyright text is set in the theme customizer.
	 * If a custom text is provided, it replaces placeholders with the current year, site title, and theme author link.
	 * If no custom text is set, it generates a default copyright message with the site title and a link to the BuddyX theme.
	 *
	 * @return string The formatted footer text.
	 */
	function buddyx_footer_custom_text() {
		/*
		 * ONE code path, ONE default. This previously read the setting with no
		 * default, so a fresh site fell into a second, hardcoded branch whose
		 * markup differed from the Customizer field's default (it linked the site
		 * title; the token string did not). Opening the Customizer and pressing
		 * Save without editing anything therefore saved the field default and
		 * silently changed the footer. Both branches are now the same string,
		 * sourced from buddyx_footer_default_copyright_text().
		 */
		$copyright = get_theme_mod( 'site_copyright_text', buddyx_footer_default_copyright_text() );

		$output = str_replace(
			array( '[current_year]', '[site_title]', '[theme_author]' ),
			array(
				date_i18n( 'Y' ),
				'<span class="buddyx-footer-site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></span>',
				'<a href="' . esc_url( 'https://wbcomdesigns.com/downloads/buddyx-theme/' ) . '">' . esc_html__( 'BuddyX WordPress Theme', 'buddyx' ) . '</a>',
			),
			$copyright
		);

		// Apply filter to allow modifications to the footer text.
		/**
		 * Filters the final footer copyright string.
		 *
		 * @param string $text Text.
		 */
		return apply_filters( 'buddyx_footer_copyright_text', wp_kses_post( $output ) );
	}
}

if ( ! function_exists( 'buddyx_categorized_blog' ) ) {
	/**
	 * Categorized Blog.
	 * Find out if blog has more than one category.
	 *
	 * @return bool True when the blog has more or fewer than exactly one category.
	 */
	function buddyx_categorized_blog() {
		$all_the_cool_cats = get_transient( 'buddyx_category_count' );
		if ( false === $all_the_cool_cats ) {

			$all_the_cool_cats = get_categories( array( 'hide_empty' => 1 ) );
			$all_the_cool_cats = count( $all_the_cool_cats );
			set_transient( 'buddyx_category_count', $all_the_cool_cats, DAY_IN_SECONDS );

		}

		if ( 1 !== (int) $all_the_cool_cats ) {
			return true;
		} else {
			return false;
		}
	}
}

/**
 * Managing 404 URL in Frontend
 */
if ( ! function_exists( 'buddyx_404_redirect' ) ) {
	/**
	 * Redirects 404 error pages to a custom page set in the theme customizer.
	 * This optimized version only runs necessary code when on a 404 page and
	 * adds more efficient checks.
	 *
	 * @return void
	 */
	function buddyx_404_redirect() {
		// Only proceed if we're on a 404 page - avoid unnecessary processing.
		if ( ! is_404() ) {
			return;
		}

		// Only redirect real browser navigations. A non-GET request that
		// resolves to a 404 (e.g. rtMedia's activity upload POST to
		// */upload/, or any plugin that posts to an unregistered BP
		// sub-route) must fall through so a later template_redirect handler
		// - or WordPress's own 404 response - can deal with it, instead of
		// being 301'd to the custom 404 page and losing its body.
		$request_method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';
		if ( ! in_array( $request_method, array( 'GET', 'HEAD' ), true ) ) {
			return;
		}

		// Check for rtMedia routes early to avoid unnecessary theme mod retrieval.
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		if ( '' !== $request_uri && strpos( $request_uri, '/media/' ) !== false ) {
			return;
		}

		// Get the custom 404 page ID - retrieve from theme mod only once.
		$redirect_page_id = get_theme_mod( 'buddyx_404_page', 0 );

		// Only proceed with redirection if a valid page ID exists.
		if ( ! empty( $redirect_page_id ) && get_post_status( $redirect_page_id ) === 'publish' ) {
			$redirect_url = get_permalink( $redirect_page_id );

			// Only redirect if we got a valid URL.
			if ( ! empty( $redirect_url ) ) {
				wp_safe_redirect( $redirect_url, 301 );
				exit;
			}
		}
	}

	// Hook the function to the `template_redirect` action.
	add_action( 'template_redirect', 'buddyx_404_redirect', 10 );
}

if ( ! function_exists( 'buddyx_register_elementor_locations' ) ) {
	/**
	 * Add Elementor Locations Support.
	 *
	 * @param object $elementor_theme_manager Elementor theme location manager.
	 * @return void
	 */
	function buddyx_register_elementor_locations( $elementor_theme_manager ) {

		$elementor_theme_manager->register_location( 'header' );
		$elementor_theme_manager->register_location( 'footer' );
	}
	add_action( 'elementor/theme/register_locations', 'buddyx_register_elementor_locations' );
}

if ( ! function_exists( 'buddyx_llms_sidebar_function' ) ) {
	/**
	 * Display LifterLMS Course and Lesson sidebars
	 * on courses and lessons in place of the sidebar returned by
	 * this function
	 *
	 * @param    string $id Default sidebar id (an empty string).
	 * @return   string
	 */
	function buddyx_llms_sidebar_function( $id ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found -- Signature required by the llms_get_theme_default_sidebar filter.

		$my_sidebar_id = 'sidebar-right'; // Replace this with your theme's sidebar ID.

		return $my_sidebar_id;
	}
	add_filter( 'llms_get_theme_default_sidebar', 'buddyx_llms_sidebar_function' );
}

if ( ! function_exists( 'buddyx_llms_theme_support' ) ) {
	/**
	 * Declare explicit theme support for LifterLMS course and lesson sidebars.
	 *
	 * @return   void
	 */
	function buddyx_llms_theme_support() {

		add_theme_support( 'lifterlms-sidebars' );
	}
	add_action( 'after_setup_theme', 'buddyx_llms_theme_support' );
}

// The legacy LearnDash `#buddyx-toggle-track` dark/light anchor was replaced by
// Color_Mode_Toggle\Component::render_focus_toggle() on the focus-header nav hook.

if ( ! function_exists( 'buddyx_remove_single_post_subheader' ) ) {
	/**
	 * Remove buddyx remove single post subheader.
	 *
	 * @return void
	 */
	function buddyx_remove_single_post_subheader() {
		if ( is_single() && 'post' === get_post_type() ) {
			remove_action( 'buddyx_sub_header', 'buddyx_sub_header' );
		}
	}
	add_action( 'wp', 'buddyx_remove_single_post_subheader' );
}

add_action( 'add_meta_boxes', 'buddyx_add_post_meta_box' );
if ( ! function_exists( 'buddyx_add_post_meta_box' ) ) {
	/**
	 * Register the Post Settings and Post Format Settings meta boxes on posts.
	 *
	 * @return void
	 */
	function buddyx_add_post_meta_box() {
		global $post;

		add_meta_box(
			'buddyx_post_settings',
			__( 'Post Settings', 'buddyx' ),
			'render_buddyx_add_post_settings_meta_box',
			array( 'post' ),
			'normal',
			'high'
		);

		add_meta_box(
			'buddyx_postformat_settings',
			__( 'Post Format Settings', 'buddyx' ),
			'render_buddyx_add_post_format_meta_box',
			array( 'post' ),
			'normal',
			'high'
		);
	}
}

if ( ! function_exists( 'render_buddyx_add_post_settings_meta_box' ) ) {
	/**
	 * Render the Post Settings meta box (title overwrite and title style).
	 *
	 * @param WP_Post $post Current post.
	 * @return void
	 */
	function render_buddyx_add_post_settings_meta_box( $post ) {
		$post_id         = $post->ID;
		$title_overwrite = get_post_meta( $post_id, '_post_title_overwrite', true );
		$title_position  = get_post_meta( $post_id, '_post_title_position', true );
		?>
		<div class="buddyx_post_settings">
			<div class="buddyx-field buddyx-checkbox">
				<label>
					<input type="checkbox" name="_post_title_overwrite" value='yes' <?php checked( $title_overwrite, 'yes' ); ?>/>
					<?php esc_html_e( 'Overwrite Title Customizer settings', 'buddyx' ); ?>
				</label>
			</div>
			<div class="buddyx-field buddyx-radio">
				<div>
					<h4><?php esc_html_e( 'Title Style', 'buddyx' ); ?></h4>
				</div>
				<ul class="buddyx_radio_list">
					<li class="buddyx_radio_list_item">
						<label>
							<input class="buddyx_radio_input" type="radio" name="_post_title_position" value="title-over" <?php checked( $title_position, 'title-over' ); ?>/>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/single-blog-layout-1.png' ); ?>" class="post-title-image" alt="<?php esc_attr_e( 'Title over featured image', 'buddyx' ); ?>"/>
						</label>
					</li>
					<li class="buddyx_radio_list_item">
						<label>
							<input class="buddyx_radio_input" type="radio" name="_post_title_position" value="half" <?php checked( $title_position, 'half' ); ?>/>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/single-blog-layout-2.png' ); ?>" class="post-title-image" alt="<?php esc_attr_e( 'Title overlapping featured image', 'buddyx' ); ?>"/>
						</label>
					</li>
					<li class="buddyx_radio_list_item">
						<label>
							<input class="buddyx_radio_input" type="radio" name="_post_title_position" value="title-above" <?php checked( $title_position, 'title-above' ); ?>/>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/single-blog-layout-3.png' ); ?>" class="post-title-image" alt="<?php esc_attr_e( 'Title above featured image', 'buddyx' ); ?>"/>
						</label>
					</li>
					<li class="buddyx_radio_list_item">
						<label>
							<input class="buddyx_radio_input" type="radio" name="_post_title_position" value="title-below" <?php checked( $title_position, 'title-below' ); ?>/>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/single-blog-layout-4.png' ); ?>" class="post-title-image" alt="<?php esc_attr_e( 'Title below featured image', 'buddyx' ); ?>"/>
						</label>
					</li>
				</ul>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'render_buddyx_add_post_format_meta_box' ) ) {
	/**
	 * Render the Post Format Settings meta box (video, audio, quote, link and gallery fields).
	 *
	 * @param WP_Post $post Current post.
	 * @return void
	 */
	function render_buddyx_add_post_format_meta_box( $post ) {
		$post_format       = get_post_format( $post );
		$post_id           = $post->ID;
		$post_video        = get_post_meta( $post_id, '_buddyx_post_video', true );
		$post_audio        = get_post_meta( $post_id, '_buddyx_post_audio', true );
		$post_quote        = get_post_meta( $post_id, '_buddyx_post_quote', true );
		$post_quote_author = get_post_meta( $post_id, '_buddyx_post_quote_author', true );

		$post_link_title    = get_post_meta( $post_id, '_buddyx_post_link_title', true );
		$post_link_url      = get_post_meta( $post_id, '_buddyx_post_link_url', true );
		$post_image_gallery = get_post_meta( $post_id, '_buddyx_image_gallery', true );
		?>
		<div class="buddyx_post_format-settings">
			<?php wp_nonce_field( 'buddyx_save_post_meta', 'buddyx_post_meta_nonce' ); ?>
			<input type="hidden" value="<?php echo esc_attr( $post_format ); ?>" id="buddyx_post_format"/>
			<div class="buddyx_video_format_setting">
				<p class="description"><?php esc_html_e( 'Enter Youtube, Vimeo and etc video url.', 'buddyx' ); ?></p>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Video URL', 'buddyx' ); ?></label>
					</div>
					<input type="text" id="buddyx_post_video" name="buddyx_post_video" value="<?php echo esc_attr( $post_video ); ?>" class="buddyx-input-text"/>
					<a href="javascript:void(0);" class="buddyx_upload_media option-tree-ui-button button button-primary light" data-id="buddyx_post_video" rel="<?php echo esc_attr( (string) $post_id ); ?>" title="<?php esc_attr_e( 'Add Media', 'buddyx' ); ?>">
						<span class="dashicons dashicons-insert"></span>
					</a>
				</div>
			</div>

			<div class="buddyx_audio_format_setting">
				<p class="description"><?php esc_html_e( 'Enter audio url.', 'buddyx' ); ?></p>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Audio URL', 'buddyx' ); ?></label>
					</div>
					<input type="text" id="buddyx_post_audio" name="buddyx_post_audio" value="<?php echo esc_attr( $post_audio ); ?>" class="buddyx-input-text"/>
					<a href="javascript:void(0);" class="buddyx_upload_media option-tree-ui-button button button-primary light" data-id="buddyx_post_audio" rel="<?php echo esc_attr( (string) $post_id ); ?>" title="<?php esc_attr_e( 'Add Media', 'buddyx' ); ?>">
						<span class="dashicons dashicons-insert"></span>
					</a>
				</div>
			</div>

			<div class="buddyx_quote_format_setting">
				<p class="description"><?php esc_html_e( 'Input your quote.', 'buddyx' ); ?></p>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Quote Text', 'buddyx' ); ?></label>
					</div>
					<textarea name="buddyx_post_quote" class="buddyx-input-textare"><?php echo esc_textarea( $post_quote ); ?></textarea>
				</div>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Quote Author', 'buddyx' ); ?></label>
					</div>
					<input type="text" name="buddyx_post_quote_author" value="<?php echo esc_attr( $post_quote_author ); ?>" class="buddyx-input-text"/>
				</div>
			</div>

			<div class="buddyx_link_format_setting">
				<p class="description"><?php esc_html_e( 'Input your link.', 'buddyx' ); ?></p>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Link Title', 'buddyx' ); ?></label>
					</div>
					<input type="text" name="buddyx_post_link_title" value="<?php echo esc_attr( $post_link_title ); ?>" class="buddyx-input-text"/>
				</div>
				<div class="buddyx_input_section">
					<div class="format-setting-label">
						<label class="label"><?php esc_html_e( 'Link URL', 'buddyx' ); ?></label>
					</div>
					<input type="text" name="buddyx_post_link_url" value="<?php echo esc_attr( $post_link_url ); ?>" class="buddyx-input-text"/>
				</div>
			</div>

			<div class="buddyx_gallery_format_setting">
				<p class="description"><?php esc_html_e( 'To create a gallery, upload your images and then select "Uploaded to this post" from the dropdown (in the media popup) to see images attached to this post. You can drag to re-order or delete them there.', 'buddyx' ); ?></p>
				<div id="images_gallery_container" class="buddyx_images_gallery_container">
					<ul class="buddyx_images_gallery images_gallery">
					<?php
						$image_gallery = '';
					if ( ! empty( $post_image_gallery ) ) {
						$post_image_gallery = explode( ',', $post_image_gallery );

						foreach ( $post_image_gallery as $image_id ) {
							if ( '' !== trim( $image_id ) ) {
								$image_id = absint( $image_id );

								echo '<li class="image" data-attachment_id="' . esc_attr( (string) $image_id ) . '">
										<div class="attachment-preview type-image">
											<div class="thumbnail">
												<div class="centered">
												' . wp_get_attachment_image( $image_id, 'thumbnail' ) . '
												</div>
											</div>
										</div>

										<div class="actions">
											<a href="#" id="' . esc_attr( (string) $image_id ) . '" class="delete" title="' . esc_attr__( 'Delete image', 'buddyx' ) . '"><i class="dashicons dashicons-no"></i></a>
										</div>
									</li>';
								$image_gallery .= $image_id . ',';
							}
						}
					}

					?>
					</ul>
					<input type="hidden" id="buddyx_image_gallery" name="buddyx_image_gallery" value="<?php echo esc_attr( substr( $image_gallery, 0, -1 ) ); ?>" />
				</div>
				<div class="clearfix buddyx_image_gallery_description">
					<p class="add_buddyx_images hide-if-no-js">
						<a class="components-button is-primary" href="#"><?php esc_html_e( 'Add images gallery', 'buddyx' ); ?></a>
					</p>
				</div>
			</div>


			<script>
			( function ( $ ) {
				'use strict';
				$('#buddyx_postformat_settings').hide();
				$( document ).ready( function () {

					var post_format = $('input[name=post_format]:checked').val();
					if ( typeof post_format == 'undefined' ) {
						post_format = $('#buddyx_post_format').val();
					}
					if ( post_format == 'video' || post_format == 'audio' || post_format == 'quote' || post_format == 'link' || post_format == 'gallery' ) {
						$('#buddyx_postformat_settings').show();
						$( '.buddyx_video_format_setting').hide();
						$( '.buddyx_audio_format_setting').hide();
						$( '.buddyx_quote_format_setting').hide();
						$( '.buddyx_link_format_setting').hide();
						$( '.buddyx_gallery_format_setting').hide();
						$( '.buddyx_' + post_format + '_format_setting').show();
					}


					$(document).on( "change", 'input[name=post_format], .editor-post-format__content select.components-select-control__input' , function(e){
						var post_format = $( this ).val();
						$( '.buddyx_video_format_setting').hide();
						$( '.buddyx_audio_format_setting').hide();
						$( '.buddyx_quote_format_setting').hide();
						$( '.buddyx_link_format_setting').hide();
						$( '.buddyx_gallery_format_setting').hide();
						if ( post_format == 'video' ) {
							$('#buddyx_postformat_settings').show();
							$( '.buddyx_video_format_setting').show();
						} else if ( post_format == 'audio' ) {
							$('#buddyx_postformat_settings').show();
							$( '.buddyx_audio_format_setting').show();
						} else if ( post_format == 'quote' ) {
							$('#buddyx_postformat_settings').show();
							$( '.buddyx_quote_format_setting').show();
						} else if ( post_format == 'link' ) {
							$('#buddyx_postformat_settings').show();
							$( '.buddyx_link_format_setting').show();
						}else if ( post_format == 'gallery' ) {
							$('#buddyx_postformat_settings').show();
							$( '.buddyx_gallery_format_setting').show();
						} else {
							$('#buddyx_postformat_settings').hide();
						}
					});

					/* Uploading files */
					var image_gallery_frame;
					var $image_gallery_ids = $('#buddyx_image_gallery');
					var $images_gallery = $('#images_gallery_container ul.images_gallery');
					$('.add_buddyx_images').on( 'click', 'a', function( event ) {
						var $el = $(this);
						var attachment_ids = $image_gallery_ids.val();
						event.preventDefault();
						/* If the media frame already exists, reopen it. */
						if ( image_gallery_frame ) {
							image_gallery_frame.open();
							return;
						}
						/* Create the media frame.  */
						image_gallery_frame = wp.media.frames.downloadable_file = wp.media({
							/* Set the title of the modal.  */
							title: '<?php echo esc_js( __( 'Add images gallery', 'buddyx' ) ); ?>',
							button: {
								text: '<?php echo esc_js( __( 'Add to gallery', 'buddyx' ) ); ?>',
							},
							multiple: true
						});
						/* When an image is selected, run a callback.  */
						image_gallery_frame.on( 'select', function() {
							var selection = image_gallery_frame.state().get('selection');
							selection.map( function( attachment ) {
								attachment = attachment.toJSON();
								if ( attachment.id ) {
									attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;
									$images_gallery.append('\
										<li class="image" data-attachment_id="' + attachment.id + '">\
											<div class="attachment-preview type-image">\
												<div class="thumbnail">\
													<div class="centered">\
														<img src="' + attachment.url + '" alt="' + (attachment.alt || "") + '" />\
													</div>\
												</div>\
											</div>\
											<div class="actions">\
												<a href="#" class="delete" title="<?php echo esc_attr( esc_js( __( 'Delete image', 'buddyx' ) ) ); ?>"><i class="dashicons dashicons-no"></i></a>\
											</div>\
										</li>');
								}
							} );
							$image_gallery_ids.val( attachment_ids );
						});
						/* Finally, open the modal. */
						image_gallery_frame.open();
					});
					/* Image ordering */
					$images_gallery.sortable({
						items: 'li.image',
						cursor: 'move',
						scrollSensitivity:40,
						forcePlaceholderSize: true,
						forceHelperSize: false,
						helper: 'clone',
						opacity: 0.65,
						placeholder: 'wc-metabox-sortable-placeholder',
						start:function(event,ui){
							ui.item.css('background-color','#f6f6f6');
						},
						stop:function(event,ui){
							ui.item.removeAttr('style');
						},
						update: function(event, ui) {
							var attachment_ids = '';
							$('#images_gallery_container ul li.image').css('cursor','default').each(function() {
								var attachment_id = $(this).attr( 'data-attachment_id' );
								attachment_ids = attachment_ids + attachment_id + ',';
							});
							$image_gallery_ids.val( attachment_ids );
						}
					});
					/* Remove images */
					$('#images_gallery_container').on( 'click', 'a.delete', function() {

						$(this).closest('li.image').remove();
						var attachment_ids = '';
						$('#images_gallery_container ul li.image').css('cursor','default').each(function() {
							var attachment_id = $(this).attr( 'data-attachment_id' );
							attachment_ids = attachment_ids + attachment_id + ',';
						});
						$image_gallery_ids.val( attachment_ids );
						return false;
					} );


					$('.buddyx_upload_media').on( 'click',  function( event ) {
						var $el = $(this);
						var media_id = $(this).data( 'id' );
						event.preventDefault();
						/* If the media frame already exists, reopen it. */
						if ( image_gallery_frame ) {
							image_gallery_frame.open();
							return;
						}
						/* Create the media frame.  */
						image_gallery_frame = wp.media.frames.downloadable_file = wp.media({
							/* Set the title of the modal.  */
							title: '<?php echo esc_js( __( 'Add Media', 'buddyx' ) ); ?>',
							button: {
								text: '<?php echo esc_js( __( 'Add to Media', 'buddyx' ) ); ?>',
							},
							multiple: true
						});
						/* When an image is selected, run a callback.  */
						image_gallery_frame.on( 'select', function() {
							var selection = image_gallery_frame.state().get('selection');
							selection.map( function( attachment ) {
								attachment = attachment.toJSON();
								if ( attachment.id ) {

									$( '#' + media_id ).val(attachment.url);
								}
							} );
						});
						/* Finally, open the modal. */
						image_gallery_frame.open();
					});

				});
			} )( jQuery );
			</script>

		</div>
		<?php
	}
}

add_action( 'save_post', 'buddyx_save_post_meta', 10, 1 );
if ( ! function_exists( 'buddyx_save_post_meta' ) ) {
	/**
	 * Saves custom post meta when a post is saved.
	 * Handles various post formats and custom settings.
	 *
	 * @param int $post_id The ID of the post being saved.
	 * @return void
	 */
	function buddyx_save_post_meta( $post_id ) {
		// Skip if this is an autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Skip if current user can't edit posts.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if we're working with a post.
		if ( ! isset( $_POST['post_type'] ) || 'post' !== $_POST['post_type'] ) {
			return;
		}

		// Verify nonce.
		if ( ! isset( $_POST['buddyx_post_meta_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['buddyx_post_meta_nonce'] ) ), 'buddyx_save_post_meta' ) ) {
			return;
		}

		// Array of text fields to update.
		$text_fields = array(
			'buddyx_post_video'        => '_buddyx_post_video',
			'buddyx_post_audio'        => '_buddyx_post_audio',
			'buddyx_post_quote'        => '_buddyx_post_quote',
			'buddyx_post_quote_author' => '_buddyx_post_quote_author',
			'buddyx_post_link_title'   => '_buddyx_post_link_title',
			'buddyx_post_link_url'     => '_buddyx_post_link_url',
			'buddyx_image_gallery'     => '_buddyx_image_gallery',
		);

		// Process and save text fields.
		foreach ( $text_fields as $field_name => $meta_key ) {
			if ( isset( $_POST[ $field_name ] ) ) {
				$value = sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) );
				update_post_meta( $post_id, $meta_key, $value );
			}
		}

		// Handle checkbox for title overwrite (separate because it needs different handling).
		if ( isset( $_POST['_post_title_overwrite'] ) ) {
			update_post_meta( $post_id, '_post_title_overwrite', sanitize_text_field( wp_unslash( $_POST['_post_title_overwrite'] ) ) );
		} else {
			// Remove the meta if the checkbox is unchecked.
			delete_post_meta( $post_id, '_post_title_overwrite' );
		}

		// Handle title position.
		if ( isset( $_POST['_post_title_position'] ) ) {
			// Validate against allowed values (optional security enhancement).
			$allowed_positions = array( 'title-over', 'half', 'title-above', 'title-below' );
			$position          = sanitize_text_field( wp_unslash( $_POST['_post_title_position'] ) );

			if ( in_array( $position, $allowed_positions, true ) ) {
				update_post_meta( $post_id, '_post_title_position', $position );
			}
		}
	}
}

/**
 * Function will add feature image for blog post in the activity feed content.
 *
 * @param string $content      Activity content.
 * @param int    $blog_post_id Blog post ID.
 *
 * @return string $content
 *
 * @since 4.2.2
 */
function buddyx_add_feature_image_blog_post_as_activity_content_callback( $content, $blog_post_id ) {
	if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {
		if ( ! empty( $blog_post_id ) && ! empty( get_post_thumbnail_id( $blog_post_id ) ) ) {
			$content .= sprintf( ' <a class="buddyx-post-img-link" href="%s"><img src="%s" alt="%s" loading="lazy" /></a>', esc_url( get_permalink( $blog_post_id ) ), esc_url( wp_get_attachment_image_url( get_post_thumbnail_id( $blog_post_id ), 'large' ) ), esc_attr( get_the_title( $blog_post_id ) ) );
		}
	}

	return $content;
}

add_filter( 'buddyx_add_feature_image_blog_post_as_activity_content', 'buddyx_add_feature_image_blog_post_as_activity_content_callback', 10, 2 );

add_action( 'bp_before_activity_activity_content', 'buddyx_bp_blogs_activity_content_set_temp_content' );

/**
 * Function which set the temporary content on the blog post activity.
 *
 * @since 4.2.2
 */
function buddyx_bp_blogs_activity_content_set_temp_content() {

	if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {

		global $activities_template;

		$activity = $activities_template->activity;
		if ( ( 'blogs' === $activity->component ) && isset( $activity->secondary_item_id ) && 'new_blog_' . get_post_type( $activity->secondary_item_id ) === $activity->type ) {
			$content = get_post( $activity->secondary_item_id );
			// If we converted $content to an object earlier, flip it back to a string.
			if ( is_a( $content, 'WP_Post' ) ) {
				$activities_template->activity->content = '&#8203;';
			}
		} elseif ( 'blogs' === $activity->component && 'new_blog_comment' === $activity->type && $activity->secondary_item_id && $activity->secondary_item_id > 0 ) {
			$activities_template->activity->content = '&#8203;';
		}
	}
}

add_filter( 'bp_get_activity_content_body', 'buddyx_bp_blogs_activity_content_with_read_more', 9999, 2 );

/**
 * Function which enhances activity blog post content with formatting, featured images, and read more links.
 *
 * @param string $content  The current activity content.
 * @param object $activity The activity object.
 *
 * @return string Modified activity content.
 *
 * @since 4.2.2
 */
function buddyx_bp_blogs_activity_content_with_read_more( $content, $activity = null ) {

	if ( ! is_object( $activity ) || empty( $activity->id ) ) {
		return $content;
	}

	// Only proceed if BuddyPress is active and not BuddyBoss.
	if ( ! function_exists( 'buddypress' ) || isset( buddypress()->buddyboss ) ) {
		return $content;
	}

	// Handle blog posts in activity stream.
	if ( 'blogs' === $activity->component && isset( $activity->secondary_item_id ) ) {
		// Handle new blog posts.
		if ( 'new_blog_' . get_post_type( $activity->secondary_item_id ) === $activity->type ) {
			$blog_post = get_post( $activity->secondary_item_id );

			if ( ! is_a( $blog_post, 'WP_Post' ) ) {
				return $content;
			}

			// Get featured image.
			/**
			 * Filters the image HTML added to a blog-post activity entry.
			 *
			 * @param string $content   Content.
			 * @param mixed  $blog_post Blog post.
			 */
			$content_img = apply_filters( 'buddyx_add_feature_image_blog_post_as_activity_content', '', $blog_post->ID );

			// Format post title with link.
			$post_title = sprintf(
				'<a class="buddyx-post-title-link" href="%s"><span class="buddyx-post-title">%s</span></a>',
				esc_url( get_permalink( $blog_post->ID ) ),
				esc_html( $blog_post->post_title )
			);

			// Get and process post excerpt.
			$excerpt = bp_create_excerpt(
				bp_strip_script_and_style_tags( html_entity_decode( get_the_excerpt( $blog_post->ID ) ) )
			);

			// Check if excerpt ends with ellipsis.
			$has_ellipsis = ( strpos( $excerpt, __( '&hellip;', 'buddyx' ) ) !== false );

			// Clean up the excerpt.
			if ( $has_ellipsis ) {
				$excerpt = str_replace( ' [&hellip;]', '&hellip;', $excerpt );
			}

			// Apply BP filters.
			$excerpt = apply_filters_ref_array( 'bp_get_activity_content', array( $excerpt, $activity ) );

			// Extract and preserve iframes.
			$has_iframe = preg_match( '/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $excerpt, $matches );

			// Strip tags but preserve links and allowed elements.
			if ( $has_iframe && ! empty( $matches[0] ) ) {
				$iframe   = $matches[0];
				$excerpt  = strip_tags( preg_replace( '/<iframe.*?\/iframe>/i', '', $excerpt ), '<a>' );
				$excerpt .= $iframe;
			} else {
				$excerpt = strip_tags( $excerpt, '<a><iframe><img><span><div>' );
			}

			// Format final content.
			return sprintf(
				'%1$s <div class="buddyx-content-wrp">%2$s %3$s</div>',
				$content_img,
				$post_title,
				wpautop( $excerpt )
			);
		} elseif ( 'new_blog_comment' === $activity->type && $activity->secondary_item_id > 0 ) {
			// Handle blog comments.
			$comment = get_comment( $activity->secondary_item_id );
			if ( ! $comment ) {
				return $content;
			}

			$content = bp_create_excerpt( html_entity_decode( $comment->comment_content ) );

			// Check if comment excerpt has ellipsis and add read more link if it does.
			if ( false !== strpos( $content, __( '&hellip;', 'buddyx' ) ) ) {
				$content     = str_replace( ' [&hellip;]', '&hellip;', $content );
				$append_text = apply_filters( 'bp_activity_excerpt_append_text', __( ' Read more', 'buddyx' ) );

				return wpautop(
					sprintf(
						'%1$s<span class="activity-blog-post-link"><a href="%2$s" rel="nofollow">%3$s</a></span>',
						$content,
						get_comment_link( $activity->secondary_item_id ),
						$append_text
					)
				);
			}

			return wpautop( $content );
		}
	}

	return $content;
}
