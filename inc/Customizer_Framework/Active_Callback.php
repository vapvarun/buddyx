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
						// Boolean-equivalent comparison so 'on'/'1'/1/true all match
						// when expected is true/'1'/1, and 'off'/''/0/false match
						// when expected is false/'0'/0/''. Pre-fix sites upgrading
						// from 5.0.x carry legacy 'on'/'off' theme_mods that PHP's
						// loose comparison treats as != '1' / != '0', causing
						// dependent controls (Site Footer / Sub Header background
						// fields, etc.) to be hidden on first customizer load even
						// when their parent toggle is ON.
						if ( ! self::values_equal( $actual, $expected ) ) {
							return false;
						}
						break;
					case '!=':
					case '!==':
						if ( self::values_equal( $actual, $expected ) ) {
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

	/**
	 * Compare two values with boolean-equivalence semantics.
	 *
	 * - If BOTH sides look boolean (any of true/1/'1'/'on'/'yes'/'true'/'enable'
	 *   for truthy, false/0/'0'/''/'off'/'no'/'false'/'disable'/null for falsy),
	 *   compare them by their boolean class.
	 * - Otherwise fall back to PHP loose equality (Kirki's original behavior).
	 *
	 * Why this matters: Field::sanitize_bool_int normalizes new switch saves to
	 * 0/1, but customers upgrading from 5.0.x carry literal 'on'/'off' theme_mod
	 * values that pre-date the sanitizer. Active_Callback conditions were
	 * authored against the sanitized 0/1 form and broke for those upgraders.
	 *
	 * @param mixed $a First value (typically the saved theme_mod).
	 * @param mixed $b Second value (typically the condition's expected value).
	 * @return bool True if the two values are equal under boolean-equivalence rules.
	 */
	protected static function values_equal( $a, $b ): bool {
		$truthy = array( true, 1, '1', 'on', 'yes', 'true', 'enable' );
		$falsy  = array( false, 0, '0', '', 'off', 'no', 'false', 'disable', null );

		$a_truthy = in_array( $a, $truthy, true );
		$a_falsy  = in_array( $a, $falsy, true );
		$b_truthy = in_array( $b, $truthy, true );
		$b_falsy  = in_array( $b, $falsy, true );

		if ( ( $a_truthy || $a_falsy ) && ( $b_truthy || $b_falsy ) ) {
			return $a_truthy === $b_truthy;
		}
		return $a == $b; // phpcs:ignore Universal.Operators.StrictComparisons
	}
}
