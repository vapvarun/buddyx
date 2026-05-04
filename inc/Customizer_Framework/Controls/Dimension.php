<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Dimension
 *
 * Number input + unit dropdown. Stores e.g. '120px', '1.5rem'.
 * JS in customizer-controls.js (Task 18) splits/recombines on change.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Dimension
 */
class Dimension extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'buddyx-dimension';

	/**
	 * Supported units.
	 *
	 * @var array<int, string>
	 */
	public $units = array( 'px', 'em', 'rem', '%', 'vh', 'vw' );

	/**
	 * Render the control content.
	 */
	public function render_content() {
		?>
		<label class="buddyx-dimension-row">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<span class="buddyx-dimension-controls">
				<input type="number" class="buddyx-dimension-number" />
				<select class="buddyx-dimension-unit">
					<?php foreach ( $this->units as $u ) : ?>
						<option value="<?php echo esc_attr( $u ); ?>"><?php echo esc_html( $u ); ?></option>
					<?php endforeach; ?>
				</select>
			</span>
			<input type="hidden" class="buddyx-dimension-value" <?php $this->link(); ?> />
		</label>
		<?php
	}
}
