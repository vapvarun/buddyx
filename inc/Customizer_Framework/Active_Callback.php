<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Active_Callback — array→closure adapter.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework;

defined( 'ABSPATH' ) || exit;

/**
 * Active_Callback
 *
 * Compiles Kirki-shape active_callback array conditions into a closure that
 * the WP_Customize_Control's evaluator can call. AND semantics across
 * multiple conditions; supported operators: ==, !=, >, <, in.
 *
 * Kirki shape:
 *   array(
 *     array( 'setting' => 'enable_x', 'operator' => '==', 'value' => true ),
 *     array( 'setting' => 'mode',     'operator' => '==', 'value' => 'advanced' ),
 *   )
 *
 * BuddyX free uses only '==' across all 54 active_callback chains today,
 * but the compiler supports the broader Kirki operator set for forward
 * compatibility (and because BuddyX Pro field defs may use richer operators
 * once it adopts this framework).
 */
class Active_Callback {

	/**
	 * Compile array-form conditions to a closure that returns bool.
	 *
	 * @param array $conditions Array of { setting, operator, value } arrays.
	 * @return callable Closure returning true iff ALL conditions hold.
	 */
	public static function compile( array $conditions ): callable {
		return static function () use ( $conditions ) {
			foreach ( $conditions as $cond ) {
				$setting  = $cond['setting']  ?? '';
				$operator = $cond['operator'] ?? '==';
				$expected = $cond['value']    ?? null;

				if ( '' === $setting ) {
					continue;
				}
				$actual = get_theme_mod( $setting );

				switch ( $operator ) {
					case '==':
					case '===':
						// Loose equality so '1' == 1 matches Kirki's behavior.
						if ( $actual != $expected ) { // phpcs:ignore Universal.Operators.StrictComparisons
							return false;
						}
						break;
					case '!=':
					case '!==':
						if ( $actual == $expected ) { // phpcs:ignore Universal.Operators.StrictComparisons
							return false;
						}
						break;
					case '>':
						if ( ! ( $actual > $expected ) ) {
							return false;
						}
						break;
					case '<':
						if ( ! ( $actual < $expected ) ) {
							return false;
						}
						break;
					case '>=':
						if ( ! ( $actual >= $expected ) ) {
							return false;
						}
						break;
					case '<=':
						if ( ! ( $actual <= $expected ) ) {
							return false;
						}
						break;
					case 'in':
						if ( ! in_array( $actual, (array) $expected, false ) ) {
							return false;
						}
						break;
					case 'contains':
						if ( false === strpos( (string) $actual, (string) $expected ) ) {
							return false;
						}
						break;
				}
			}
			return true;
		};
	}
}
