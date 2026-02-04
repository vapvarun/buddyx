<?php
/**
 * BuddyX\Buddyx\Scripts\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Scripts;

use BuddyX\Buddyx\Component_Interface;
use BuddyX\Buddyx\Templating_Component_Interface;
use function BuddyX\Buddyx\buddyx;
use function add_action;
use function wp_enqueue_script;
use function wp_register_script;
use function wp_script_add_data;
use function get_theme_file_uri;
use function get_theme_file_path;
use function _doing_it_wrong;
use function esc_html;
use function wp_print_scripts;
use function apply_filters;

/**
 * Class for managing javascript files.
 *
 * Exposes template tags:
 * * `buddyx()->print_scripts()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Associative array of JavaScript files, as $handle => $data pairs.
	 * $data must be an array with keys:
	 * - 'file' (file path relative to 'assets/js' directory) - required
	 * - 'global' (whether the file should immediately be enqueued instead of just being registered)
	 * - 'loading' (whether the file should be loaded 'async' or 'defer')
	 * - 'footer' (whether the file should be loaded in the footer)
	 * - 'deps' (array of dependencies)
	 * - 'localize' (array of variables to inject with wp_localize_scripts)
	 *
	 * Not currently implemented
	 * 'preload_callback'
	 * (callback function determining whether the file should be preloaded for the current request).
	 *
	 * Do not access this property directly, instead use the `get_js_files()` method.
	 *
	 * @var array
	 */
	protected $js_files;

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'scripts';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_scripts' ) );
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
			'print_scripts' => array( $this, 'print_scripts' ),
		);
	}

	/**
	 * Registers or enqueues JavaScript files.
	 *
	 * JavaScript files that are global are enqueued. All other JavaScript files are only registered, to be enqueued later.
	 */
	public function action_enqueue_scripts(): void {
		// If the AMP plugin is active, return early.
		if ( buddyx()->is_amp() ) {
			return;
		}

		$js_uri = get_theme_file_uri( '/assets/js/' );
		$js_dir = get_theme_file_path( '/assets/js/' );

		$js_files = $this->get_js_files();
		foreach ( $js_files as $handle => $data ) {
			$src     = $js_uri . $data['file'];
			$version = buddyx()->get_asset_version( $js_dir . $data['file'] );

			/*
			 * Enqueue global JavaScript files immediately and register the other ones for later use.
			 */
			if ( $data['global'] ) {
				wp_enqueue_script( $handle, $src, $data['deps'], $version, $data['footer'] );
			} else {
				wp_register_script( $handle, $src, $data['deps'], $version, $data['footer'] );
			}

			/**
			 * Set async and deferred attributes.
			 */
			if ( 'async' === $data['loading'] ) {
				wp_script_add_data( $handle, 'async', true );
			}
			if ( 'defer' === $data['loading'] ) {
				wp_script_add_data( $handle, 'defer', true );
			}

			/**
			 *  Uses wp_localize_scripts
			 */
			if ( $data['localize'] ) {
				foreach ( $data['localize'] as $object => $vars ) {
					wp_localize_script( $handle, $object, $vars );
				}
			}
		}
	}

	/**
	 * Prints JavaScript <script> tags directly.
	 *
	 * This should be used for JavaScript files that aren't global and thus should only be loaded if the HTML markup
	 * they are responsible for is actually present. Template parts should use this method when the related markup
	 * requires a specific JavaScript file to be loaded. If preloading JavaScript files is disabled, this method will not do
	 * anything.
	 *
	 * If the `<script>` tag for a given JavaScript file has already been printed, it will be skipped.
	 *
	 * @param string ...$handles One or more JavaScript file handles.
	 */
	public function print_scripts( string ...$handles ): void {

		$js_files = $this->get_js_files();
		$handles  = array_filter(
			$handles,
			function ( $handle ) use ( $js_files ) {
				$is_valid = isset( $js_files[ $handle ] ) && ! $js_files[ $handle ]['global'];
				if ( ! $is_valid ) {
					/* translators: %s: JS handle */
					_doing_it_wrong( __CLASS__ . '::print_scripts()', esc_html( sprintf( __( 'Invalid theme JS handle: %s', 'buddyx' ), $handle ) ), 'BuddyX 3.1.0' );
				}
				return $is_valid;
			}
		);

		if ( array() === $handles ) {
			return;
		}

		wp_print_scripts( $handles );
	}

	/**
	 * Gets all JS files.
	 *
	 * @return array Associative array of $handle => $data pairs.
	 */
	protected function get_js_files(): array {
		if ( is_array( $this->js_files ) ) {
			return $this->js_files;
		}

		$js_files = array(
			'buddyx-superfish'     => array(
				'file'    => 'superfish.min.js',
				'global'  => true,
				'footer'  => true,
				'deps'    => array( 'jquery' ),
				'loading' => null,
			),
			'buddyx-isotope-pkgd'  => array(
				'file'    => 'isotope.pkgd.min.js',
				'global'  => true,
				'footer'  => true,
				'deps'    => array( 'jquery' ),
				'loading' => 'defer',
			),
			'buddyx-fitvids'       => array(
				'file'    => 'fitvids.min.js',
				'global'  => true,
				'footer'  => true,
				'deps'    => array( 'jquery' ),
				'loading' => 'defer',
			),
			'buddyx-sticky-kit'    => array(
				'file'    => 'sticky-kit.min.js',
				'global'  => true,
				'footer'  => true,
				'deps'    => array( 'jquery' ),
				'loading' => 'defer',
			),
			'buddyx-jquery-cookie' => array(
				'file'    => 'jquery-cookie.min.js',
				'global'  => true,
				'footer'  => true,
				'deps'    => array( 'jquery' ),
				'loading' => null,
			),
			'buddyx-slick'         => array(
				'file'    => 'slick.min.js',
				'global'  => true,
				'footer'  => true,
				'deps'    => array( 'jquery' ),
				'loading' => 'defer',
			),
			'buddyx-custom'        => array(
				'file'     => 'custom.min.js',
				'global'   => true,
				'footer'   => true,
				'deps'     => array( 'jquery' ),
				'loading'  => null,
				'localize' => array(
					'buddyx_ajax' => array(
						'nonce'   => wp_create_nonce( 'buddyx_toggle_theme_color' ),
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
					),
				),
			),
		);

		// Conditionally add GamiPress script
		if ( class_exists( 'GamiPress' ) ) {
			$js_files['buddyx-gamipress'] = array(
				'file'    => 'gamipress.min.js',
				'global'  => true,
				'footer'  => true,
				'deps'    => array( 'jquery' ),
				'loading' => 'defer',
			);
		}

		// Conditionally add BuddyPress script
		if ( class_exists( 'BuddyPress' ) ) {
			$js_files['buddyx-buddypress'] = array(
				'file'    => 'buddyx-buddypress.min.js',
				'global'  => true,
				'footer'  => true,
				'deps'    => array( 'jquery' ),
				'loading' => null,
			);
		}

		// Conditionally add LearnDash script
		if ( class_exists( 'SFWD_LMS' ) ) {
			$js_files['buddyx-learndash'] = array(
				'file'    => 'buddyx-learndash.min.js',
				'global'  => true,
				'footer'  => true,
				'deps'    => array( 'jquery' ),
				'loading' => null,
			);
		}

		/**
		 * Filters default JS files.
		 *
		 * @param array $js_files Associative array of JS files, as $handle => $data pairs.
		 *                         $data must be an array with keys 'file' (file path relative to 'assets/js'
		 *                         directory), and optionally 'global' (whether the file should immediately be
		 *                         enqueued instead of just being registered) and 'preload_callback' (callback)
		 *                         function determining whether the file should be preloaded for the current request).
		 */
		$js_files = apply_filters( 'buddyx_js_files', $js_files );

		$this->js_files = array();
		foreach ( $js_files as $handle => $data ) {
			if ( is_string( $data ) ) {
				$data = array( 'file' => $data );
			}

			if ( empty( $data['file'] ) ) {
				continue;
			}

			$this->js_files[ $handle ] = array_merge(
				array(
					'global'   => false,
					'loading'  => null,
					'footer'   => false,
					'deps'     => array(),
					'localize' => null,
				),
				$data
			);
		}

		return $this->js_files;
	}
}
