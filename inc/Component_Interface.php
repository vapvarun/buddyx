<?php
/**
 * BuddyX\Buddyx\Component_Interface interface
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;

/**
 * Interface for a theme component.
 */
interface Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string;

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize();
}
