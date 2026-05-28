<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Section — thin wrapper for accumulating sections.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework;

defined( 'ABSPATH' ) || exit;

/**
 * Section
 *
 * Pure ergonomic wrapper so authoring code reads `Section::add( $id, $args )`
 * instead of `Component::register_section( $id, $args )`.
 */
class Section {

	/**
	 * Register a section with the framework.
	 *
	 * @param string $id   Section id.
	 * @param array  $args Section args (title, panel, priority, description).
	 */
	public static function add( string $id, array $args ): void {
		Component::register_section( $id, $args );
	}
}
