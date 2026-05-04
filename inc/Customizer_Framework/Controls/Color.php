<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Color
 *
 * Extends WP core's color control to support a curated palette and alpha
 * via the choices arg (Kirki-compatible).
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Color
 *
 * Extends WP core's WP_Customize_Color_Control which already wires the iris
 * picker via wp-color-picker. We inherit the parent's render_content() so the
 * picker UI renders correctly. Type stays 'color' (parent's value) so WP's
 * built-in JS controlConstructor binds; we expose palette + alpha to it.
 *
 * Choices:
 *   - palette  array  Curated swatches passed through to wp-color-picker.
 *   - alpha    bool   Enable alpha channel slider (handled by Color JS handler).
 */
class Color extends \WP_Customize_Color_Control {

	/**
	 * Expose palette + alpha to the JS template.
	 */
	public function to_json() {
		parent::to_json();
		if ( ! empty( $this->choices['palette'] ) && is_array( $this->choices['palette'] ) ) {
			$this->json['palette'] = $this->choices['palette'];
		}
		if ( ! empty( $this->choices['alpha'] ) ) {
			$this->json['alpha'] = true;
		}
	}
}
