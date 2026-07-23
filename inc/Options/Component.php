<?php
/**
 * BuddyX\Buddyx\Base_Support\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Options;

use BuddyX\Buddyx\Component_Interface;
use BuddyX\Buddyx\Templating_Component_Interface;
use function add_action;
use function BuddyX\Buddyx\buddyx;

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
		return 'options';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'admin_enqueue_scripts', array( $this, 'theme_options_enqueue_scripts' ) );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `buddyx()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags(): array {
		return array();
	}

	/**
	 * Enqueues the theme options admin scripts.
	 */
	public function theme_options_enqueue_scripts(): void {

		// Add BuddyX welcome page script.
		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only screen check, no state change.
		if ( 'buddyx-welcome' === $page ) {
			wp_enqueue_script(
				'buddyx-admin-script',
				get_template_directory_uri() . '/assets/js/admin/buddyx-admin.min.js',
				array(),
				buddyx()->get_asset_version( get_template_directory() . '/assets/js/admin/buddyx-admin.min.js' ),
				true
			);

			/*
			 * The buddyx-install script is gone. BuddyX is distributed on
			 * WordPress.org, where a theme may not fetch plugin packages from a
			 * third-party server - and it certainly may not ship a distribution
			 * licence key to authorize that fetch. The Getting Started screen now
			 * links to each plugin instead. The one-click installer lives in
			 * BuddyX Pro, which is self-hosted and may do this.
			 */
		}

		// Customizer JS.
		global $pagenow;
		if ( 'customize.php' === $pagenow ) {
			wp_enqueue_script(
				'buddyx-customizer-script',
				get_template_directory_uri() . '/assets/js/admin/buddyx-customizer.min.js',
				array(),
				buddyx()->get_asset_version( get_template_directory() . '/assets/js/admin/buddyx-customizer.min.js' ),
				true
			);
		}

		wp_enqueue_style(
			'buddyx-theme-settings',
			get_template_directory_uri() . '/assets/css/admin/theme-settings.min.css',
			array(),
			buddyx()->get_asset_version( get_template_directory() . '/assets/css/admin/theme-settings.min.css' ),
		);
	}
}
