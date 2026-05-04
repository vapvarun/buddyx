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
 * Choices:
 *   - palette  array  Curated swatches passed through to wp-color-picker.
 *   - alpha    bool   Enable alpha channel slider.
 */
class Color extends \WP_Customize_Color_Control {

	/**
	 * @var string
	 */
	public $type = 'buddyx-color';

	/**
	 * Expose palette + alpha to the JS template.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['palette'] = $this->choices['palette'] ?? array();
		$this->json['alpha']   = ! empty( $this->choices['alpha'] );
	}

	/**
	 * Render the control content.
	 */
	public function render_content() {
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<input
				class="color-picker-hex"
				type="text"
				maxlength="7"
				placeholder="<?php esc_attr_e( 'Hex Value', 'buddyx' ); ?>"
				<?php $this->link(); ?>
			/>
		</label>
		<?php
	}
}
