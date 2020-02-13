<?php
/**
 * BuddyX\Buddyx\PWA\Component class
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\PWA;

use BuddyX\Buddyx\Component_Interface;
use function add_action;
use function add_theme_support;

/**
 * Class for managing PWA support.
 *
 * @link https://wordpress.org/plugins/pwa/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'pwa';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', [ $this, 'action_add_service_worker_support' ] );
	}

	/**
	 * Adds support for theme-specific service worker integrations.
	 */
	public function action_add_service_worker_support() {
		add_theme_support( 'service_worker', true );
	}
}
