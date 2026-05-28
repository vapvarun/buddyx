<?php
/**
 * BuddyX\Buddyx\Customizer_Framework\Controls\Slider
 *
 * Range slider + numeric input + unit suffix. Stores e.g. '120px'.
 * choices.min, choices.max, choices.step, choices.unit configure the range.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Slider
 */
class Slider extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'buddyx-slider';

	/**
	 * Render the control content.
	 */
	public function render_content() {
		$min  = $this->choices['min']  ?? 0;
		$max  = $this->choices['max']  ?? 100;
		$step = $this->choices['step'] ?? 1;
		$unit = $this->choices['unit'] ?? 'px';
		?>
		<label class="buddyx-slider">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<span class="buddyx-slider-controls" data-unit="<?php echo esc_attr( (string) $unit ); ?>">
				<input
					type="range"
					class="buddyx-slider-range"
					min="<?php echo esc_attr( (string) $min ); ?>"
					max="<?php echo esc_attr( (string) $max ); ?>"
					step="<?php echo esc_attr( (string) $step ); ?>"
				/>
				<input
					type="number"
					class="buddyx-slider-number"
					min="<?php echo esc_attr( (string) $min ); ?>"
					max="<?php echo esc_attr( (string) $max ); ?>"
					step="<?php echo esc_attr( (string) $step ); ?>"
				/>
				<span class="buddyx-slider-unit"><?php echo esc_html( (string) $unit ); ?></span>
			</span>
			<input type="hidden" class="buddyx-slider-value" <?php $this->link(); ?> />
		</label>
		<?php
	}
}
