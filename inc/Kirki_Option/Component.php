<?php
/**
 * BuddyX\Buddyx\Typography_Options\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Kirki_Option;

use BuddyX\Buddyx\Component_Interface;
use function add_action;
use function add_filter;

/**
 * Class for adding custom background support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'kirki_option';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		
		add_filter( 'body_class', [ $this, 'site_width_body_classes' ] );
		add_filter( 'body_class', [ $this, 'site_sticky_sidebar_body_classes' ] );
		add_filter( 'body_class', [ $this, 'site_single_blog_post_body_classes' ] );
		if ( class_exists( 'SFWD_LMS' ) ) {
            add_filter('body_class', [$this, 'site_learndash_body_classes']);
        }

		/* Load required template */
		if ( class_exists( 'Kirki' ) ) {
			add_action( 'wp_head', [ $this, 'load_customizer_styles' ] );

			require_once __dir__ . '/Options.php';
			require_once __dir__ . '/Styles.php';
		}
		
	}

	/**
	 * Load Customizer Styles
	 */
	public function load_customizer_styles() {

		echo '<style id="buddyx-customizer-inline-styles" type="text/css">';
		$output = buddyx_get_customizer_style();
		echo buddyx_css_compress( $output );
		echo '</style>' . "\n";

	}

	/**
	 * Site layout body class.
	 */
	public function site_width_body_classes( array $classes ) : array {
		$classes[] = 'layout-' . get_theme_mod( 'site_layout', buddyx_defaults( 'site-layout' ) );

		return $classes;
	}

	/**
	 * Site sticky sidebar body class.
	 */
	public function site_sticky_sidebar_body_classes( array $classes ) : array {

		$sticky_sidebar = get_theme_mod( 'sticky_sidebar_option', buddyx_defaults( 'sticky-sidebar' ) );
		if ( $sticky_sidebar ) {
			$classes[] = 'sticky-sidebar-enable';
		}

		return $classes;
	}

	/**
	 * Site single blog post body class.
	 */
	public function site_single_blog_post_body_classes( array $classes ) : array {

		$single_post_layout = get_theme_mod( 'single_post_layout', buddyx_defaults( 'single-post-layout' ) );

		if ( $single_post_layout === '2' ) {
			$classes[] = 'single-post-layout-1';
		} elseif ( $single_post_layout === '3' ) {
			$classes[] = 'single-post-layout-2';
		} elseif ( $single_post_layout === '4' ) {
			$classes[] = 'single-post-layout-3';
		} else {
			$classes[] = '';
		}

		return $classes;
	}

	/**
	 * LearnDash dark mode body class.
	 */
    public function site_learndash_body_classes(array $classes): array {
        if ( isset( $_COOKIE['bxtheme'] ) && 'dark' == $_COOKIE['bxtheme'] &&  is_user_logged_in() ) {
            $classes[] = 'buddyx-dark-theme';
        }
    
        return $classes;
    }
}
