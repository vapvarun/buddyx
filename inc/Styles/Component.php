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
		add_action( 'after_setup_theme', array( $this, 'action_add_editor_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_enqueue_event_calendar_style' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_enqueue_dokan_style' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_enqueue_ideapush_style' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_enqueue_learndash_style' ), 99 );
		add_action( 'wpforms_frontend_css', array( $this, 'buddyx_enqueue_wpforms_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_enqueue_edd_style' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_enqueue_sensei_style' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'buddyx_elementor_kit_defaults' ), 99 );
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
		if ( has_filter( 'buddyx_preloading_styles_enabled' ) ) {
			_deprecated_hook( 'buddyx_preloading_styles_enabled', '5.2.0', '', esc_html__( 'Template stylesheets are always printed by the templates that use them, with or without AMP, so this filter no longer changes anything. Use the buddyx_css_files filter to change a stylesheet.', 'buddyx' ) );
		}

		$css_uri = get_theme_file_uri( '/assets/css/' );
		$css_dir = get_theme_file_path( '/assets/css/' );

		$css_files = $this->get_css_files();
		foreach ( $css_files as $handle => $data ) {
			$src     = $css_uri . $data['file'];
			$version = buddyx()->get_asset_version( $css_dir . $data['file'] );

			if ( 'buddyx-load-fontawesome' === $handle ) {
				/**
				 * Filters the Font Awesome stylesheet URL.
				 *
				 * Lets a site point at a different Font Awesome build, for example a self-hosted kit.
				 *
				 * @param string $src Stylesheet URL.
				 */
				$src = (string) apply_filters( 'buddyx_fontawesome_css', $src );
			}

			/*
			 * Enqueue global stylesheets immediately and register the other ones for later use
			 * (unless preloading stylesheets is disabled, in which case stylesheets should be immediately
			 * enqueued based on whether they are necessary for the page content).
			 */
			$global_style         = $data['global'];
			$preloading_available = is_callable( $data['preload_callback'] ) && call_user_func( $data['preload_callback'] );

			if ( $global_style || $preloading_available ) {
				// Enqueue global styles and the sheets this request needs. Registered-only
				// template sheets (buddyx-content, -sidebar, -widgets) are printed by the
				// templates via print_styles(), with preloading on or off (AMP); enqueueing
				// every template sheet when preloading is off loaded rules meant for other
				// templates and broke the header.
				wp_enqueue_style( $handle, $src, $data['deps'], $version, $data['media'] );
			} else {
				wp_register_style( $handle, $src, $data['deps'], $version, $data['media'] );
			}

			wp_style_add_data( $handle, 'precache', true );
		}

		// BuddyPress CSS on requests that show BuddyPress content.
		if ( buddyx_needs_buddypress_assets() ) {
			wp_enqueue_style( 'buddyx-buddypress', $css_uri . 'buddypress.min.css', array(), buddyx()->get_asset_version( $css_dir . 'buddypress.min.css' ) );
		}

		// Enqueue Better Messages dark-mode compatibility CSS (only when active).
		if ( class_exists( 'Better_Messages' ) ) {
			wp_enqueue_style( 'buddyx-better-messages', $css_uri . 'better-messages.min.css', array( 'buddyx-tokens-applied' ), buddyx()->get_asset_version( $css_dir . 'better-messages.min.css' ) );
		}

		// Enqueue GamiPress dark-mode compatibility CSS (only when active).
		if ( class_exists( 'GamiPress' ) ) {
			wp_enqueue_style( 'buddyx-gamipress', $css_uri . 'gamipress.min.css', array( 'buddyx-tokens-applied' ), buddyx()->get_asset_version( $css_dir . 'gamipress.min.css' ) );
		}

		// Enqueue WB Gamification accent mapping (only when active).
		if ( defined( 'WB_GAM_VERSION' ) ) {
			wp_enqueue_style( 'buddyx-wb-gamification', $css_uri . 'wb-gamification.min.css', array( 'buddyx-tokens-applied' ), buddyx()->get_asset_version( $css_dir . 'wb-gamification.min.css' ) );
		}

		// Enqueue rtMedia dark-mode compatibility CSS (only when active).
		if ( class_exists( 'RTMedia' ) ) {
			wp_enqueue_style( 'buddyx-rtmedia', $css_uri . 'rtmedia.min.css', array( 'buddyx-tokens-applied' ), buddyx()->get_asset_version( $css_dir . 'rtmedia.min.css' ) );
		}

		// Enqueue BP Verified Member dark-mode compatibility CSS (only when active).
		if ( class_exists( 'BP_Verified_Member' ) ) {
			wp_enqueue_style( 'buddyx-bp-verified-member', $css_uri . 'bp-verified-member.min.css', array( 'buddyx-tokens-applied' ), buddyx()->get_asset_version( $css_dir . 'bp-verified-member.min.css' ) );
		}

		// Enqueue TutorLMS dark-mode compatibility CSS (only when active).
		if ( function_exists( 'tutor' ) ) {
			wp_enqueue_style( 'buddyx-tutorlms', $css_uri . 'tutorlms.min.css', array( 'buddyx-tokens-applied' ), buddyx()->get_asset_version( $css_dir . 'tutorlms.min.css' ) );

			// Tutor uses its blue until the owner picks a brand colour in Tutor > Settings;
			// follow the Theme Color until then (light and dark). A picked colour is kept.
			if ( function_exists( 'tutor_utils' ) && class_exists( 'Tutor\\Options_V2' ) && 0 === strcasecmp( (string) tutor_utils()->get_brand_color(), \Tutor\Options_V2::DEFAULT_BRAND_COLOR ) ) {
				wp_add_inline_style( 'buddyx-tutorlms', 'html:root{--tutor-color-primary:var(--bx-color-accent);--tutor-color-primary-rgb:var(--bx-color-accent-rgb);--tutor-color-primary-hover:var(--bx-color-accent-hover);--tutor-color-primary-hover-rgb:var(--bx-color-accent-rgb);--tutor-text-brand:var(--bx-color-accent-text);--tutor-icon-brand:var(--bx-color-accent-text);--tutor-border-brand:var(--bx-color-accent);--tutor-actions-brand-primary:var(--bx-color-accent);--tutor-button-primary:var(--bx-color-accent);--tutor-button-primary-hover:var(--bx-color-accent-hover);--tutor-button-primary-focused:var(--bx-color-accent-hover);}html body .tutor-btn-primary{--tutor-text-primary-inverse:var(--bx-color-accent-inverse);}html body .tutor-btn-primary,html body .tutor-avatar .tutor-avatar-text{color:var(--bx-color-accent-inverse);}' );
			}
		}

		// Enqueue Platform CSS.
		if ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
			wp_enqueue_style( 'buddyx-platform', $css_uri . 'platform.min.css', array(), buddyx()->get_asset_version( $css_dir . 'platform.min.css' ) );
		}

		// Enqueue bbPress CSS.
		if ( buddyx_needs_bbpress_assets() ) {
			wp_enqueue_style( 'buddyx-bbpress', $css_uri . 'bbpress.min.css', array(), buddyx()->get_asset_version( $css_dir . 'bbpress.min.css' ) );
		}

		// Enqueue WC Vendors CSS.
		if ( class_exists( 'WC_Vendors' ) ) {
			wp_enqueue_style( 'buddyx-wc-vendor', $css_uri . 'wc-vendor.min.css', array(), buddyx()->get_asset_version( $css_dir . 'wc-vendor.min.css' ) );
		}

		// Enqueue LearnPress CSS.
		if ( class_exists( 'LearnPress' ) ) {
			wp_enqueue_style( 'buddyx-learnpress', $css_uri . 'learnpress.min.css', array(), buddyx()->get_asset_version( $css_dir . 'learnpress.min.css' ) );

			// LearnPress paints buttons and tabs amber until the owner picks a primary
			// colour in LearnPress > Settings; use the Theme Color until then. LearnPress
			// puts white text on that colour everywhere, so it keeps the light-mode shade
			// in dark mode too. Its secondary colour is a dark header under white text and stays.
			if ( '' === (string) \LP_Settings::get_option( 'primary_color', '' ) ) {
				wp_add_inline_style( 'buddyx-learnpress', 'html:root{--lp-primary-color:' . \BuddyX\Buddyx\Tokens\Component::light_accent() . ';}' );
			}
		}

		// Enqueue LifterLMS CSS.
		if ( class_exists( 'LifterLMS' ) ) {
			wp_enqueue_style( 'buddyx-lifterlms', $css_uri . 'lifterlms.min.css', array(), buddyx()->get_asset_version( $css_dir . 'lifterlms.min.css' ) );
		}

		// WooCommerce CSS on requests that show WooCommerce content.
		if ( buddyx_needs_woocommerce_assets() ) {
			wp_enqueue_style( 'buddyx-woocommerce', $css_uri . 'woocommerce.min.css', array(), buddyx()->get_asset_version( $css_dir . 'woocommerce.min.css' ) );
		}

		// Enqueue WP_Job_Manager CSS.
		if ( class_exists( 'WP_Job_Manager' ) ) {
			wp_enqueue_style( 'buddyx-wpjobmanager', $css_uri . 'buddyx-wpjobmanager.min.css', array(), buddyx()->get_asset_version( $css_dir . 'buddyx-wpjobmanager.min.css' ) );
		}

		// Enqueue MVX CSS.
		if ( class_exists( 'MVX' ) ) {
			wp_enqueue_style( 'multivendorx', $css_uri . 'multivendorx.min.css', array(), buddyx()->get_asset_version( $css_dir . 'multivendorx.min.css' ) );
		}

		// Enqueue RTL CSS.
		if ( is_rtl() ) {
			wp_enqueue_style( 'buddyx-rtl', $css_uri . 'rtl.min.css', array(), buddyx()->get_asset_version( $css_dir . 'rtl.min.css' ) );
		}

		// Enqueue AMP CSS.
		if ( buddyx()->is_amp() ) {
			wp_enqueue_style( 'buddyx-amp', $css_uri . 'buddyx-amp.min.css', array(), buddyx()->get_asset_version( $css_dir . 'buddyx-amp.min.css' ) );
		}

		// Enqueue Dark Mode CSS (it only styles LearnDash focus mode, so only on LearnDash screens).
		if ( buddyx_needs_learndash_assets() ) {
			wp_enqueue_style( 'buddyx-dark-mode', $css_uri . 'dark-mode.min.css', array(), buddyx()->get_asset_version( $css_dir . 'dark-mode.min.css' ) );
		}

		// Enqueue SureCart CSS.
		if ( defined( 'SURECART_PLUGIN_FILE' ) ) {
			wp_enqueue_style( 'buddyx-surecart', $css_uri . 'surecart.min.css', array(), buddyx()->get_asset_version( $css_dir . 'surecart.min.css' ) );
		}

		// Enqueue FluentCart CSS.
		if ( defined( 'FLUENTCART_PLUGIN_FILE_PATH' ) ) {
			wp_enqueue_style( 'buddyx-fluentcart', $css_uri . 'fluentcart.min.css', array(), buddyx()->get_asset_version( $css_dir . 'fluentcart.min.css' ) );
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
		if ( buddyx_needs_event_calendar_assets() ) {
			wp_enqueue_style( 'buddyx-eventscalendar', $css_uri . 'eventscalendar.min.css', array(), buddyx()->get_asset_version( $css_dir . 'eventscalendar.min.css' ) );
		}
	}

	/**
	 * Elementor's default kit (Roboto, #6EC1E4 headings, #7A7A7A text, green
	 * buttons) looks like another site pasted in. While the active kit still has
	 * Elementor's default system colours or fonts, point them at the theme so
	 * Elementor pages match in light and dark mode. A kit the owner customised, and
	 * any style set on a widget, is kept.
	 */
	public function buddyx_elementor_kit_defaults() {
		if ( ! did_action( 'elementor/loaded' ) || ! class_exists( 'Elementor\\Plugin' ) ) {
			return;
		}
		$kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit_for_frontend();
		if ( ! $kit ) {
			return;
		}
		$is_default = static function ( $items, $field, $defaults ) {
			foreach ( (array) $items as $item ) {
				$id = $item['_id'] ?? '';
				if ( isset( $defaults[ $id ] ) && 0 !== strcasecmp( (string) ( $item[ $field ] ?? '' ), $defaults[ $id ] ) ) {
					return false;
				}
			}
			return true;
		};
		$css        = '';
		if ( $is_default(
			$kit->get_settings( 'system_colors' ),
			'color',
			array(
				'primary'   => '#6EC1E4',
				'secondary' => '#54595F',
				'text'      => '#7A7A7A',
				'accent'    => '#61CE70',
			)
		) ) {
			$css .= '--e-global-color-primary:var(--bx-color-h2);--e-global-color-secondary:var(--bx-color-fg-muted);--e-global-color-text:var(--bx-color-fg);--e-global-color-accent:var(--bx-color-button-bg);';
		}
		if ( $is_default(
			$kit->get_settings( 'system_typography' ),
			'typography_font_family',
			array(
				'primary'   => 'Roboto',
				'secondary' => 'Roboto Slab',
				'text'      => 'Roboto',
				'accent'    => 'Roboto',
			)
		) ) {
			foreach ( array( 'primary', 'secondary', 'text', 'accent' ) as $slot ) {
				$css .= '--e-global-typography-' . $slot . '-font-family:var(--global-font-family);';
			}
		}
		if ( '' === $css ) {
			return;
		}
		wp_add_inline_style( 'buddyx-global', 'body[class*="elementor-kit-"]{' . $css . '}body[class*="elementor-kit-"] .elementor-widget-button .elementor-button{color:var(--bx-color-button-fg);border-radius:var(--bx-radius-button,6px);}' );
	}

	/**
	 * Enqueue the Sensei LMS Learning Mode token map when Sensei is active.
	 */
	public function buddyx_enqueue_sensei_style() {
		if ( ! function_exists( 'Sensei' ) ) {
			return;
		}
		$css_dir = get_theme_file_path( '/assets/css/' );
		wp_enqueue_style( 'buddyx-sensei', get_theme_file_uri( '/assets/css/sensei.min.css' ), array(), buddyx()->get_asset_version( $css_dir . 'sensei.min.css' ) );
	}

	/**
	 * Enqueue the Easy Digital Downloads button styles when EDD is active.
	 */
	public function buddyx_enqueue_edd_style() {
		if ( ! function_exists( 'EDD' ) ) {
			return;
		}
		$css_dir = get_theme_file_path( '/assets/css/' );
		wp_enqueue_style( 'buddyx-edd', get_theme_file_uri( '/assets/css/edd.min.css' ), array(), buddyx()->get_asset_version( $css_dir . 'edd.min.css' ) );
	}

	/**
	 * Enqueue the WPForms token map. Fires only when WPForms loads its own CSS,
	 * i.e. on pages that render a form.
	 */
	public function buddyx_enqueue_wpforms_style() {
		$css_dir = get_theme_file_path( '/assets/css/' );
		wp_enqueue_style( 'buddyx-wpforms', get_theme_file_uri( '/assets/css/wpforms.min.css' ), array(), buddyx()->get_asset_version( $css_dir . 'wpforms.min.css' ) );
	}

	/**
	 * Register and enqueue the IdeaPush dark-mode compat stylesheet.
	 */
	public function buddyx_enqueue_ideapush_style() {
		$css_uri = get_theme_file_uri( '/assets/css/' );
		$css_dir = get_theme_file_path( '/assets/css/' );

		if ( buddyx_needs_ideapush_assets() ) {
			wp_enqueue_style( 'buddyx-ideapush', $css_uri . 'ideapush.min.css', array(), buddyx()->get_asset_version( $css_dir . 'ideapush.min.css' ) );
		}
	}

	/**
	 * Register and enqueue a dokan stylesheet.
	 */
	public function buddyx_enqueue_dokan_style() {
		$css_uri = get_theme_file_uri( '/assets/css/' );
		$css_dir = get_theme_file_path( '/assets/css/' );

		// Enqueue Dokan CSS.
		if ( buddyx_needs_dokan_assets() ) {
			wp_enqueue_style( 'buddyx-dokan', $css_uri . 'dokan.min.css', array(), buddyx()->get_asset_version( $css_dir . 'dokan.min.css' ) );
		}
	}

	/**
	 * Register and enqueue a learndash stylesheet.
	 */
	public function buddyx_enqueue_learndash_style() {
		$css_uri = get_theme_file_uri( '/assets/css/' );
		$css_dir = get_theme_file_path( '/assets/css/' );

		// Enqueue Learndash CSS.
		if ( buddyx_needs_learndash_assets() ) {
			wp_enqueue_style( 'buddyx-learndash', $css_uri . 'learndash.min.css', array(), buddyx()->get_asset_version( $css_dir . 'learndash.min.css' ) );
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

		// Runs with preloading on or off (AMP turns it off). wp_print_styles() skips
		// any handle already printed, so a stylesheet enqueued in <head> is not
		// printed twice, and one that was only registered still loads here.
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
			'buddyx-tokens-applied'   => array(
				'file'   => 'bx-tokens-applied.css',
				'global' => true,
				'deps'   => array( 'buddyx-global' ),
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
				// Same condition buddyx_site_loader() uses to render the overlay markup.
				'global' => buddyx_is_truthy( get_theme_mod( 'site_loader', buddyx_defaults( 'site-loader' ) ) ),
			),
			'buddyx-slick'            => array(
				'file' => 'slick.min.css',
			),
			'buddyx-load-fontawesome' => array(
				'file'   => 'fontawesome.min.css',
				/**
				 * Filters whether the theme loads Font Awesome.
				 *
				 * The theme's own icons do not need it; plugins that print Font Awesome
				 * icons do. When false the stylesheet is only registered, so a plugin can
				 * still enqueue the 'buddyx-load-fontawesome' handle itself.
				 *
				 * @param bool $load Default the Customizer setting (Site Performance > Load Font Awesome), on by default.
				 */
				'global' => (bool) apply_filters( 'buddyx_load_fontawesome', buddyx_is_truthy( get_theme_mod( 'site_load_fontawesome', 'on' ) ) ),
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
		// Define non-critical styles that should be loaded with media="print" and onload.
		$non_critical_styles = array(
			'buddyx-slick',
			'buddyx-eventscalendar',
			'buddyx-dokan',
			'buddyx-wpjobmanager',
			'multivendorx',
		);

		// Load non-critical styles with print media and switch to all on load.
		if ( in_array( $handle, $non_critical_styles, true ) ) {
			$html = sprintf(
				'<link rel="stylesheet" id="%s-css" href="%s" media="print" onload="this.media=\'all\'" />', // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- Rewrites the tag of an already-enqueued style inside the style_loader_tag filter.
				esc_attr( $handle ),
				esc_url( $href )
			);
			// Add noscript fallback.
			$html .= sprintf(
				'<noscript><link rel="stylesheet" id="%s-css-noscript" href="%s" media="%s" /></noscript>', // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- Rewrites the tag of an already-enqueued style inside the style_loader_tag filter.
				esc_attr( $handle ),
				esc_url( $href ),
				esc_attr( $media )
			);
		}

		return $html;
	}
}
