<?php
/**
 * BuddyX\Buddyx\Styles\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Styles;

use BuddyX\Buddyx\Component_Interface;
use BuddyX\Buddyx\Templating_Component_Interface;
use function BuddyX\Buddyx\buddyx;
use function add_action;
use function add_filter;
use function wp_enqueue_style;
use function wp_register_style;
use function wp_style_add_data;
use function get_theme_file_uri;
use function get_theme_file_path;
use function wp_styles;
use function esc_attr;
use function esc_url;
use function add_editor_style;
use function wp_style_is;
use function _doing_it_wrong;
use function esc_html;
use function wp_print_styles;
use function post_password_required;
use function is_singular;
use function comments_open;
use function get_comments_number;
use function apply_filters;
use function add_query_arg;

/**
 * Class for managing stylesheets.
 *
 * Exposes template tags:
 * * `buddyx()->print_styles()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Associative array of CSS files, as $handle => $data pairs.
	 * $data must be an array with keys 'file' (file path relative to 'assets/css' directory), and optionally 'global'
	 * (whether the file should immediately be enqueued instead of just being registered) and 'preload_callback'
	 * (callback function determining whether the file should be preloaded for the current request).
	 *
	 * Do not access this property directly, instead use the `get_css_files()` method.
	 *
	 * @var array
	 */
	protected $css_files;

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'styles';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_styles' ) );
		add_action( 'wp_head', array( $this, 'action_preload_styles' ) );
		add_action( 'after_setup_theme', array( $this, 'action_add_editor_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_enqueue_event_calendar_style' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_enqueue_dokan_style' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_enqueue_learndash_style' ), 99 );
		add_filter( 'style_loader_tag', array( $this, 'add_preload_for_critical_css' ), 10, 4 );
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
			'print_styles' => array( $this, 'print_styles' ),
		);
	}

	/**
	 * Registers or enqueues stylesheets.
	 *
	 * Stylesheets that are global are enqueued. All other stylesheets are only registered, to be enqueued later.
	 */
	public function action_enqueue_styles() {

		$css_uri = get_theme_file_uri( '/assets/css/' );
		$css_dir = get_theme_file_path( '/assets/css/' );

		$preloading_styles_enabled = $this->preloading_styles_enabled();

		$css_files = $this->get_css_files();
		foreach ( $css_files as $handle => $data ) {
			$src     = $css_uri . $data['file'];
			$version = buddyx()->get_asset_version( $css_dir . $data['file'] );

			/*
			 * Enqueue global stylesheets immediately and register the other ones for later use
			 * (unless preloading stylesheets is disabled, in which case stylesheets should be immediately
			 * enqueued based on whether they are necessary for the page content).
			 */
			$global_style         = $data['global'];
			$preloading_available = is_callable( $data['preload_callback'] ) && call_user_func( $data['preload_callback'] );

			if ( $global_style || ( ! $preloading_styles_enabled && $preloading_available ) ) {
				wp_enqueue_style( $handle, $src, $data['deps'], $version, $data['media'] );
			} else {
				wp_register_style( $handle, $src, $data['deps'], $version, $data['media'] );
			}

			wp_style_add_data( $handle, 'precache', true );
		}

		// Enqueue BuddyPress CSS.
		if ( ! class_exists( 'Youzify' ) ) {
			wp_enqueue_style( 'buddyx-buddypress', $css_uri . 'buddypress.min.css' );
		}

		// Enqueue Platform CSS.
		if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
			wp_enqueue_style( 'buddyx-platform', $css_uri . 'platform.min.css' );
		}

		// Enqueue bbPress CSS.
		if ( function_exists( 'is_bbpress' ) || function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
			wp_enqueue_style( 'buddyx-bbpress', $css_uri . 'bbpress.min.css' );
		}

		// Enqueue WC Vendors CSS.
		if ( class_exists( 'WC_Vendors' ) ) {
			wp_enqueue_style( 'buddyx-wc-vendor', $css_uri . 'wc-vendor.min.css' );
		}

		// Enqueue LearnPress CSS.
		if ( class_exists( 'LearnPress' ) ) {
			wp_enqueue_style( 'buddyx-learnpress', $css_uri . 'learnpress.min.css' );
		}

		// Enqueue LifterLMS CSS.
		if ( class_exists( 'LifterLMS' ) ) {
			wp_enqueue_style( 'buddyx-lifterlms', $css_uri . 'lifterlms.min.css' );
		}

		// Enqueue WooCommerce CSS.
		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style( 'buddyx-woocommerce', $css_uri . 'woocommerce.min.css' );
		}

		// Enqueue Youzify CSS.
		if ( class_exists( 'Youzify' ) ) {
			wp_enqueue_style( 'buddyx-youzify', $css_uri . 'buddyx-youzify.min.css' );
		}

		// Enqueue WP_Job_Manager CSS.
		if ( class_exists( 'WP_Job_Manager' ) ) {
			wp_enqueue_style( 'buddyx-wpjobmanager', $css_uri . 'buddyx-wpjobmanager.min.css' );
		}

		// Enqueue MVX CSS.
		if ( class_exists( 'MVX' ) ) {
			wp_enqueue_style( 'multivendorx', $css_uri . 'multivendorx.min.css' );
		}

		// Enqueue Slick CSS.
		wp_enqueue_style( 'buddyx-slick', $css_uri . 'slick.min.css' );

		// Enqueue RTL CSS.
		if ( is_rtl() ) {
			wp_enqueue_style( 'buddyx-rtl', $css_uri . 'rtl.min.css' );
		}

		// Enqueue AMP CSS.
		if ( buddyx()->is_amp() ) {
			wp_enqueue_style( 'buddyx-amp', $css_uri . 'buddyx-amp.min.css' );
		}

		// Enqueue Dark Mode CSS.
		wp_enqueue_style( 'buddyx-dark-mode', $css_uri . 'dark-mode.min.css' );

		// Enqueue SureCart CSS.
		if ( defined( 'SURECART_PLUGIN_FILE' ) ) {
			wp_enqueue_style( 'buddyx-surecart', $css_uri . 'surecart.min.css' );
		}

		// Enqueue FluentCart CSS.
		if ( defined( 'FLUENTCART_PLUGIN_FILE_PATH' ) ) {
			wp_enqueue_style( 'buddyx-fluentcart', $css_uri . 'fluentcart.min.css' );
		}
	}

	/**
	 * Preloads in-body stylesheets depending on what templates are being used.
	 *
	 * Only stylesheets that have a 'preload_callback' provided will be considered. If that callback evaluates to true
	 * for the current request, the stylesheet will be preloaded.
	 *
	 * Preloading is disabled when AMP is active, as AMP injects the stylesheets inline.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Preloading_content
	 */
	public function action_preload_styles() {

		// If preloading styles is disabled, return early.
		if ( ! $this->preloading_styles_enabled() ) {
			return;
		}

		$wp_styles = wp_styles();

		$css_files = $this->get_css_files();
		foreach ( $css_files as $handle => $data ) {

			// Skip if stylesheet not registered.
			if ( ! isset( $wp_styles->registered[ $handle ] ) ) {
				continue;
			}

			// Skip if no preload callback provided.
			if ( ! is_callable( $data['preload_callback'] ) ) {
				continue;
			}

			// Skip if preloading is not necessary for this request.
			if ( ! call_user_func( $data['preload_callback'] ) ) {
				continue;
			}

			$preload_uri = $wp_styles->registered[ $handle ]->src . '?ver=' . $wp_styles->registered[ $handle ]->ver;

			echo '<link rel="preload" id="' . esc_attr( $handle ) . '-preload" href="' . esc_url( $preload_uri ) . '" as="style" onload="this.rel=\'stylesheet\'">';
			echo "\n";
		}
	}

	/**
	 * Enqueues WordPress theme styles for the editor.
	 */
	public function action_add_editor_styles() {

		// Enqueue block editor stylesheet.
		add_editor_style( 'assets/css/editor/editor-styles.min.css' );
	}

	/**
	 * Register and enqueue a the event calendar stylesheet.
	 */
	public function buddyx_enqueue_event_calendar_style() {
		$css_uri = get_theme_file_uri( '/assets/css/' );
		$css_dir = get_theme_file_path( '/assets/css/' );

		// Enqueue EventsCalendar CSS.
		if ( class_exists( 'Tribe__Events__Main' ) ) {
			wp_enqueue_style( 'buddyx-eventscalendar', $css_uri . 'eventscalendar.min.css', '', time() );
		}
	}

	/**
	 * Register and enqueue a dokan stylesheet.
	 */
	public function buddyx_enqueue_dokan_style() {
		$css_uri = get_theme_file_uri( '/assets/css/' );
		$css_dir = get_theme_file_path( '/assets/css/' );

		// Enqueue Dokan CSS.
		if ( class_exists( 'WeDevs_Dokan' ) ) {
			wp_enqueue_style( 'buddyx-dokan', $css_uri . 'dokan.min.css', '', time() );
		}
	}

	/**
	 * Register and enqueue a learndash stylesheet.
	 */
	public function buddyx_enqueue_learndash_style() {
		$css_uri = get_theme_file_uri( '/assets/css/' );
		$css_dir = get_theme_file_path( '/assets/css/' );

		// Enqueue Learndash CSS.
		if ( class_exists( 'SFWD_LMS' ) ) {
			wp_enqueue_style( 'buddyx-learndash', $css_uri . 'learndash.min.css' );
		}
	}

	/**
	 * Prints stylesheet link tags directly.
	 *
	 * This should be used for stylesheets that aren't global and thus should only be loaded if the HTML markup
	 * they are responsible for is actually present. Template parts should use this method when the related markup
	 * requires a specific stylesheet to be loaded. If preloading stylesheets is disabled, this method will not do
	 * anything.
	 *
	 * If the `<link>` tag for a given stylesheet has already been printed, it will be skipped.
	 *
	 * @param string ...$handles One or more stylesheet handles.
	 */
	public function print_styles( string ...$handles ) {

		// If preloading styles is disabled (and thus they have already been enqueued), return early.
		if ( ! $this->preloading_styles_enabled() ) {
			return;
		}

		$css_files = $this->get_css_files();
		$handles   = array_filter(
			$handles,
			function ( $handle ) use ( $css_files ) {
				$is_valid = isset( $css_files[ $handle ] ) && ! $css_files[ $handle ]['global'];
				if ( ! $is_valid ) {
					/* translators: %s: stylesheet handle */
					_doing_it_wrong( __CLASS__ . '::print_styles()', esc_html( sprintf( __( 'Invalid theme stylesheet handle: %s', 'buddyx' ), $handle ) ), 'BuddyX 3.1.0' );
				}
				return $is_valid;
			}
		);

		if ( array() === $handles ) {
			return;
		}

		wp_print_styles( $handles );
	}

	/**
	 * Determines whether to preload stylesheets and inject their link tags directly within the page content.
	 *
	 * Using this technique generally improves performance, however may not be preferred under certain circumstances.
	 * {@see 'buddyx_preloading_styles_enabled'} filter can be used to tweak the return value.
	 *
	 * @return bool True if preloading stylesheets and injecting them is enabled, false otherwise.
	 */
	protected function preloading_styles_enabled(): bool {

		$preloading_styles_enabled = ! buddyx()->is_amp();

		/**
		 * Filters whether to preload stylesheets and inject their link tags within the page content.
		 *
		 * @param bool $preloading_styles_enabled Whether preloading stylesheets and injecting them is enabled.
		 */
		return apply_filters( 'buddyx_preloading_styles_enabled', $preloading_styles_enabled );
	}

	/**
	 * Gets all CSS files.
	 *
	 * @return array Associative array of $handle => $data pairs.
	 */
	protected function get_css_files(): array {
		if ( is_array( $this->css_files ) ) {
			return $this->css_files;
		}

		$css_files = array(
			'buddyx-global'           => array(
				'file'   => 'global.min.css',
				'global' => true,
			),
			'buddyx-comments'         => array(
				'file'             => 'comments.min.css',
				'preload_callback' => function () {
					return ! post_password_required() && is_singular() && ( comments_open() || get_comments_number() );
				},
			),
			'buddyx-content'          => array(
				'file'             => 'content.min.css',
				'preload_callback' => '__return_false',
			),
			'buddyx-sidebar'          => array(
				'file' => 'sidebar.min.css',
			),
			'buddyx-widgets'          => array(
				'file' => 'widgets.min.css',
			),
			'buddyx-front-page'       => array(
				'file'             => 'front-page.min.css',
				'preload_callback' => function () {
					global $template;

					if ( $template ) {
						return 'front-page.php' === basename( $template );
					} else {
						return false; // Or a different default behavior.
					}
				},
			),
			'buddyx-site-loader'      => array(
				'file'   => 'loaders.min.css',
				'global' => true,
			),
			'buddyx-load-fontawesome' => array(
				'file'   => 'fontawesome.min.css',
				'global' => true,
			),
		);

		/**
		 * Filters default CSS files.
		 *
		 * @param array $css_files Associative array of CSS files, as $handle => $data pairs.
		 *                         $data must be an array with keys 'file' (file path relative to 'assets/css'
		 *                         directory), and optionally 'global' (whether the file should immediately be
		 *                         enqueued instead of just being registered) and 'preload_callback' (callback)
		 *                         function determining whether the file should be preloaded for the current request).
		 */
		$css_files = apply_filters( 'buddyx_css_files', $css_files );

		$this->css_files = array();
		foreach ( $css_files as $handle => $data ) {
			if ( is_string( $data ) ) {
				$data = array( 'file' => $data );
			}

			if ( empty( $data['file'] ) ) {
				continue;
			}

			$this->css_files[ $handle ] = array_merge(
				array(
					'global'           => false,
					'preload_callback' => null,
					'media'            => 'all',
					'deps'             => array(),
				),
				$data
			);
		}

		return $this->css_files;
	}

	/**
	 * Add preload for critical CSS files to improve performance.
	 *
	 * @param string $html   The link tag for the enqueued style.
	 * @param string $handle The style's registered handle.
	 * @param string $href   The stylesheet's source URL.
	 * @param string $media  The stylesheet's media attribute.
	 * @return string Modified link tag.
	 */
	public function add_preload_for_critical_css( $html, $handle, $href, $media ) {
		// Define non-critical styles that should be loaded with media="print" and onload
		$non_critical_styles = array(
			'buddyx-slick',
			'buddyx-eventscalendar',
			'buddyx-dokan',
			'buddyx-youzify',
			'buddyx-wpjobmanager',
			'multivendorx',
		);

		// Load non-critical styles with print media and switch to all on load
		if ( in_array( $handle, $non_critical_styles, true ) ) {
			$html = sprintf(
				'<link rel="stylesheet" id="%s-css" href="%s" media="print" onload="this.media=\'all\'" />',
				esc_attr( $handle ),
				esc_url( $href )
			);
			// Add noscript fallback
			$html .= sprintf(
				'<noscript><link rel="stylesheet" id="%s-css-noscript" href="%s" media="%s" /></noscript>',
				esc_attr( $handle ),
				esc_url( $href ),
				esc_attr( $media )
			);
		}

		return $html;
	}
}
