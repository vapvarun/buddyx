<?php
/**
 * BuddyX\Buddyx\Base_Support\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Base_Support;

use BuddyX\Buddyx\Component_Interface;
use BuddyX\Buddyx\Templating_Component_Interface;
use function add_action;
use function add_filter;
use function add_theme_support;
use function is_singular;
use function pings_open;
use function esc_url;
use function get_bloginfo;
use function wp_scripts;
use function wp_get_theme;
use function get_template;

/**
 * Class for adding basic theme support, most of which is mandatory to be implemented by all themes.
 *
 * Exposes template tags:
 * * `buddyx()->get_version()`
 * * `buddyx()->get_asset_version( string $filepath )`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'base_support';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_essential_theme_support' ) );
		add_action( 'wp_head', array( $this, 'action_add_pingback_header' ) );
		add_filter( 'body_class', array( $this, 'filter_body_classes_add_hfeed' ) );
		add_filter( 'embed_defaults', array( $this, 'filter_embed_dimensions' ) );
		add_filter( 'theme_scandir_exclusions', array( $this, 'filter_scandir_exclusions_for_optional_templates' ) );
		add_filter( 'script_loader_tag', array( $this, 'filter_script_loader_tag' ), 10, 2 );
		add_action( 'init', array( $this, 'buddyx_theme_block_styles' ) );
		add_action( 'init', array( $this, 'buddyx_register_block_pattern_categories' ) );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `buddyx()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags(): array {
		return array(
			'get_version'       => array( $this, 'get_version' ),
			'get_asset_version' => array( $this, 'get_asset_version' ),
		);
	}

	/**
	 * Adds theme support for essential features.
	 */
	public function action_essential_theme_support() {
		// Add default RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Ensure WordPress manages the document title.
		add_theme_support( 'title-tag' );

		// Ensure WordPress theme features render in HTML5 markup.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'image',
				'gallery',
				'quote',
				'video',
				'audio',
				'link',
			)
		);

		// Add support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support custom-header, custom-background.
		add_theme_support( 'custom-header' );
		add_theme_support( 'custom-background' );

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for block patterns.
		add_theme_support( 'editor-patterns' );
	}

	/**
	 * Adds a pingback url auto-discovery header for singularly identifiable articles.
	 */
	public function action_add_pingback_header() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}

	/**
	 * Adds a 'hfeed' class to the array of body classes for non-singular pages.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes_add_hfeed( array $classes ): array {
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		return $classes;
	}

	/**
	 * Sets the embed width in pixels, based on the theme's design and stylesheet.
	 *
	 * @param array $dimensions An array of embed width and height values in pixels (in that order).
	 * @return array Filtered dimensions array.
	 */
	public function filter_embed_dimensions( array $dimensions ): array {
		$dimensions['width'] = 720;
		return $dimensions;
	}

	/**
	 * Excludes any directory named 'optional' from being scanned for theme template files.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/theme_scandir_exclusions/
	 *
	 * @param array $exclusions the default directories to exclude.
	 * @return array Filtered exclusions.
	 */
	public function filter_scandir_exclusions_for_optional_templates( array $exclusions ): array {
		return array_merge(
			$exclusions,
			array( 'optional' )
		);
	}

	/**
	 * Adds async/defer attributes to enqueued / registered scripts.
	 *
	 * If #12009 lands in WordPress, this function can no-op since it would be handled in core.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12009
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string Script HTML string.
	 */
	public function filter_script_loader_tag( string $tag, string $handle ): string {

		foreach ( array( 'async', 'defer' ) as $attr ) {
			if ( ! wp_scripts()->get_data( $handle, $attr ) ) {
				continue;
			}

			// Prevent adding attribute when already added in #12009.
			if ( ! preg_match( ":\s$attr(=|>|\s):", $tag ) ) {
				$tag = preg_replace( ':(?=></script>):', " $attr", $tag, 1 );
			}

			// Only allow async or defer, not both.
			break;
		}

		return $tag;
	}

	/**
	 * Gets the theme version.
	 *
	 * @return string Theme version number.
	 */
	public function get_version(): string {
		static $theme_version = null;

		if ( null === $theme_version ) {
			$theme_version = wp_get_theme( get_template() )->get( 'Version' );
		}

		return $theme_version;
	}

	/**
	 * Gets the version for a given asset.
	 *
	 * Returns filemtime when WP_DEBUG is true, otherwise the theme version.
	 *
	 * @param string $filepath Asset file path.
	 * @return string Asset version number.
	 */
	public function get_asset_version( string $filepath ): string {
		if ( WP_DEBUG ) {
			return (string) filemtime( $filepath );
		}

		return $this->get_version();
	}

	/**
	 * Register block styles.
	 */
	public function buddyx_theme_block_styles() {
		if ( function_exists( 'register_block_style' ) ) {
			register_block_style(
				'core/image',
				array(
					'name'  => 'buddyx-border',
					'label' => esc_html__( 'Borders', 'buddyx' ),
					'style' => 'buddyx-border-style',
				)
			);
		}
	}

	/**
	 * Register register block patterns.
	 */
	public function buddyx_register_block_pattern_categories() {

		register_block_pattern_category(
			'buddyx-general',
			array(
				'label' => __( 'BuddyX General', 'buddyx' ),
			)
		);

		register_block_pattern_category(
			'buddyx-hero',
			array(
				'label' => __( 'BuddyX Hero Sections', 'buddyx' ),
			)
		);

		register_block_pattern_category(
			'buddyx-footer',
			array(
				'label' => __( 'BuddyX Footer', 'buddyx' ),
			)
		);

		register_block_pattern_category(
			'buddyx-query',
			array(
				'label' => __( 'BuddyX Query', 'buddyx' ),
			)
		);

		if ( function_exists( 'register_block_pattern' ) ) {
			$block_patterns = array(
				'footer-default',
				'footer-central',
				'footer-default-mega',
				'footer-mega',
				'footer-simple',
				'footer-small',
				'general-banner',
				'general-banner-light',
				'general-faq',
				'general-features-light',
				'general-pricing',
				'hero-main',
				'hero-two',
				'hero-count',
				'query-cover-featured',
				'query-cover-grid',
				'query-grid-excerpt',
				'query-listbig',
				'query-simple-list',
			);

			foreach ( $block_patterns as $block_pattern ) {
				register_block_pattern(
					'buddyx/' . $block_pattern,
					require __DIR__ . '/patterns/' . $block_pattern . '.php'
				);
			}
		}
	}
}
