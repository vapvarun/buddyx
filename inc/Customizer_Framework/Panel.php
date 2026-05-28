<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Panel — thin wrapper for accumulating panels.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework;

defined( 'ABSPATH' ) || exit;

/**
 * Panel
 *
 * Pure ergonomic wrapper so authoring code reads `Panel::add( $id, $args )`
 * instead of `Component::register_panel( $id, $args )`.
 */
class Panel {

	/**
	 * Register a panel with the framework.
	 *
	 * @param string $id   Panel id.
	 * @param array  $args Panel args (title, priority, description).
	 */
	public static function add( string $id, array $args ): void {
		Component::register_panel( $id, $args );
	}
}
